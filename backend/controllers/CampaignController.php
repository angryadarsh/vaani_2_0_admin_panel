<?php

namespace backend\controllers;

use Yii;
use common\models\EdasCampaign;
use common\models\search\EdasCampaignSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\VaaniClientMaster;
use common\models\EdasDniMaster;
use common\models\User;
use common\models\VaaniRoleMaster;
use common\models\VaaniCallAccess;
use common\models\VaaniCampaignQueue;
use common\models\VaaniCampaignActiveQueues;
use common\models\VaaniUserActiveQueues;
use common\models\VaaniCallTimesConfig;
use common\models\VaaniCampaignBreak;
use common\models\VaaniClientOperators;
use common\models\VaaniOperators;
use common\models\search\VaaniCampaignQueueSearch;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use common\models\VaaniDispositionPlan;
use common\models\VaaniCampDispositions;
use common\models\VaaniDispositions;
use common\models\VaaniQmsTemplate;
use common\models\vaani_kp_templete;
use common\models\VaaniKpTab;
use Shuchkin\SimpleXLSX;

/**
 * CampaignController implements the CRUD actions for EdasCampaign model.
 */
class CampaignController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all EdasCampaign models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $searchModel = new EdasCampaignSearch();
        $searchModel->del_status = EdasCampaign::STATUS_NOT_DELETED;
        if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
            $searchModel->client_id = $user->clientIds;
            // $searchModel->campaign_id = $user->campaignIds;
        }else if(isset($_SESSION['client_connection'])){
            $searchModel->client_id = $_SESSION['client_connection'];
        }
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EdasCampaign model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new EdasCampaign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // redirect to add campaign
        return $this->redirect(['add']);

        $user = Yii::$app->user->identity;

        $model = new EdasCampaign();
        $model->campaign_id = User::newID('3','CAM');
        // queue new model
        $queueModel = new VaaniCampaignQueue();
        $queueModel->campaign_id = $model->campaign_id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                if($model->call_medium == EdasCampaign::MEDIUM_QUEUE){
                    return $this->redirect(['add-queue', 'id' => $model->id]);
                }else if($model->call_medium == EdasCampaign::MEDIUM_IVR){
                    return $this->redirect(['ivr', 'id' => $model->id]);
                }
                // insert default role access for client
                VaaniRoleMaster::addDefaultRoleAccess($model->client_id, $model->campaign_id);

                // add campaign data in asterisk file
                $model->asterisk_write('add');
                // add campaign data in queue conf file
                $model->queue_write('add');
                // create sound folder
                $model->add_sound_folder('add');
                
                Yii::$app->session->setFlash('success', 'Campaign created successfully.');
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        // fetch list of clients
        $clients = [];
        $client_ids = null;
        if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
            $client_ids = $user->clientIds;
        }
        $clients = ArrayHelper::map(VaaniClientMaster::clientsList($client_ids), 'client_id', 'client_name');
        
        $dnis = [];
        $week_days = ['Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wesnesday' => 'Wesnesday', 'Thusday' => 'Thusday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'];
        $criterias = EdasCampaign::$campaign_criterias;
        $campaign_types = EdasCampaign::$campaign_types;
        $call_mediums = EdasCampaign::$call_mediums;
        $access_values = VaaniCallAccess::$access_values;

        return $this->render('create', [
            'model' => $model,
            'queueModel' => $queueModel,
            'clients' => $clients,
            'week_days' => $week_days,
            'criterias' => $criterias,
            'campaign_types' => $campaign_types,
            'call_mediums' => $call_mediums,
            'dnis' => $dnis,
            'access_values' => $access_values,
        ]);
    }

    /** <<<<<<<<<<<<<<<<  ADD CAMPAIGN  >>>>>>>>>>>>>>>>>
     * Add/Updates an existing EdasCampaign model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddCampaign($id=null, $cloneid=null)
    {
        $user = Yii::$app->user->identity;

        $is_camp_update = false;
        $prev_camp = null;
        $prev_camp_type = null;

        if($id){
            $id = User::decrypt_data($id);
            $model = $this->findModel($id);
            $prev_camp = $model;
            $is_camp_update = true;
            $prev_camp_type = $model->campaign_type;
            $model->prev_type = $prev_camp_type;
        }else{
            $model = new EdasCampaign();
            $model->campaign_id = User::newID('3','CAM');
            $model->client_id = ((isset($_SESSION['client_connection']) && $_SESSION['client_connection']) ? $_SESSION['client_connection'] : null);

            //created by gaurav
           ////////////////////
           if($cloneid){
                $cloneid = User::decrypt_data($cloneid);
                $data=EdasCampaign::find()->where(['id'=>$cloneid])->one();
 
                if($data){
                    //fetching add campaign form data from $model to $data
                    $model->client_id=$data->client_id;
                    // $model->campaign_id=$data->campaign_id;
                    // $model->campaign_name=$data->campaign_name;
                    $model->campaign_type=$data->campaign_type;
                    $model->campaign_status=$data->campaign_status;
                    $model->campaign_sub_type=$data->campaign_sub_type;
                    $model->call_medium=$data->call_medium;
                    $model->campaign_sticky_agent=$data->campaign_sticky_agent;
                    $model->call_window=$data->call_window;
                    $model->manual_call_window=$data->manual_call_window;
                    $model->preview_time=$data->preview_time;
                    $model->pacing_value=$data->pacing_value;
                    $model->preview_upload=$data->preview_upload;
                    $model->outbound_criteria=$data->outbound_criteria;
                    $model->campaign_sms_mode=$data->campaign_sms_mode;
                    $model->campaign_email_mode=$data->campaign_email_mode;
                    $model->campaign_chat_mode=$data->campaign_chat_mode;
                    $model->campaign_whatsapp_mode=$data->campaign_whatsapp_mode;
                    $model->campaign_inbound_agent_selection_criteria = $data->campaign_inbound_agent_selection_criteria;
                    $model->is_dtmf = $data->is_dtmf;
                    $model->is_ivr_queue = $data->is_ivr_queue;
                    $model->prev_type=$data->prev_type;
                    $model->call_timeout=$data->call_timeout;
                    $model->disposition_plan_id=$data->disposition_plan_id;
                }
            }
            ///////////////////// 
        }
        // campaign call access model
        $is_access_edit = false;
        $access_updated = false;
        if($model->callAccess){
            $is_access_edit = true;
            $campaignAccessModel = $model->callAccess;
            $prev_camp_conference = $model->callAccess->is_conference;
            $prev_camp_transfer = $model->callAccess->is_transfer;
            $prev_camp_manual = $model->callAccess->is_manual;
            $prev_camp_consult = $model->callAccess->is_consult;
        }else{
            $access_updated = true;
            $campaignAccessModel = new VaaniCallAccess();
            $campaignAccessModel->campaign_id = $model->campaign_id;
            
            //created by gaurav for fetching call access data
            ////////////////////
            if($cloneid && $data && $data->callAccess){
                $campaignAccessModel->is_conference=$data->callAccess->is_conference;
                $campaignAccessModel->is_transfer=$data->callAccess->is_transfer;
                $campaignAccessModel->is_consult=$data->callAccess->is_consult;
                $campaignAccessModel->is_manual=$data->callAccess->is_consult;
                
            }
            /////////////////
        }

        // queue new model
        $queueModel = new VaaniCampaignQueue();
        $queueModel->campaign_id = $model->campaign_id;

        // campaign queues
        $queues = [];
        if($model->queues || $model->ivrQueues){
            $queues = (($model->call_medium == EdasCampaign::MEDIUM_QUEUE) ? $model->queues : $model->ivrQueues);
        
        }else{
            //////////created by gaurav
            if($cloneid && $data && ($data->queues || $data->ivrQueues) ){
                $queues = (($model->call_medium == EdasCampaign::MEDIUM_QUEUE) ? $data->queues : $data->ivrQueues);
                $queues=$data->queues;
            }
        }

        $prev_campaign_name = $model->campaign_name;
        $prev_dni_id = $model->campaign_dni;
        $prev_criteria = $model->campaign_inbound_agent_selection_criteria;
        
        $prev_call_medium = $model->call_medium;
        $prev_call_timeout = $model->call_timeout;
        
        $prev_is_dtmf = $model->is_dtmf;
        $prev_is_ivr_queue = $model->is_ivr_queue;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        // }else if ($this->request->isPost && $model->load($this->request->post())) {
            // echo "<pre>";print_r($this->request->post());exit;
        }else if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Campaign added successfully!');
            
            // Add camp dispositions configurations
            if(isset($this->request->post()['VaaniCampDispositions'])){
                $camp_dispositions = $this->request->post()['VaaniCampDispositions'];
                
                if($camp_dispositions['disposition_id']){
                    $parent_disposition = null;
                    foreach ($camp_dispositions['disposition_id'] as $key => $disposition) {
                        $dispo_model = VaaniCampDispositions::find()->where(['disposition_id' => $disposition])->andWhere(['del_status' => VaaniDispositions::STATUS_NOT_DELETED])->one();
                        
                        if(!$dispo_model){
                            $dispo_model = new VaaniCampDispositions();
                        }
                        $dispo_model->disposition_id = $disposition;
                        $dispo_model->campaign_id = $model->campaign_id;
                        $dispo_model->max_retry_count = $camp_dispositions['max_retry_count'][$key];
                        $dispo_model->retry_delay = $camp_dispositions['retry_delay'][$key];
                        if($dispo_model->save()){
                            Yii::$app->session->setFlash('success', 'Campaign Disposition added successfully.');
                        }else{
                            Yii::$app->session->setFlash('error', 'Something went wrong while adding the disposition.');
                        }
                    }
                }
            }

            // campaign access
            if($is_access_edit){
                if($campaignAccessModel->load($this->request->post())){
                    
                    if(isset($prev_camp_conference)){
                        if(($prev_camp_conference != $campaignAccessModel->is_conference || $prev_camp_transfer != $campaignAccessModel->is_transfer || $prev_camp_consult != $campaignAccessModel->is_consult || $prev_camp_manual != $campaignAccessModel->is_manual)){

                            $new_campaignAccessModel = new VaaniCallAccess();
                            $new_campaignAccessModel->campaign_id = $model->campaign_id;
                            $new_campaignAccessModel->is_conference = $campaignAccessModel->is_conference;
                            $new_campaignAccessModel->is_transfer = $campaignAccessModel->is_transfer;
                            $new_campaignAccessModel->is_consult = $campaignAccessModel->is_consult;
                            $new_campaignAccessModel->is_manual = $campaignAccessModel->is_manual;
                            
                            if($new_campaignAccessModel->save()){
                                $access_updated = true;
                                // delete previous call access model if exists
                                $campaignAccessModel->updateAttributes(['del_status' => VaaniCallAccess::STATUS_PERMANENT_DELETED]);

                                $campaignAccessModel = $new_campaignAccessModel;
                            }
                        }else{
                            $access_updated = false;
                        }
                    }
                }
            }else{
                $campaignAccessModel = new VaaniCallAccess();
                $campaignAccessModel->campaign_id = $model->campaign_id;
                if($campaignAccessModel->load($this->request->post()) && $campaignAccessModel->save()){
                    $access_updated = true;
                }
            }

            if($id){
                // edit campaign data in asterisk file
                $model->asterisk_write('edit', $prev_campaign_name, $prev_dni_id);
                // edit campaign data in queues conf file
                $model->queue_write('edit', $prev_campaign_name, $prev_criteria);
                // edit sound folder
                $model->add_sound_folder('edit', $prev_campaign_name);
                
                Yii::$app->session->setFlash('success', 'Campaign updated successfully.');
            }else{
                // add campaign data in asterisk file
                $model->asterisk_write('add');
                // add campaign data in queues conf file
                $model->queue_write('add');
                // add sound folder
                $model->add_sound_folder('add');

                // add default breaks for the campaign
                $breakModel = new VaaniCampaignBreak();
                $breakModel->break_id = User::newID('7','BRK');
                $breakModel->campaign_id = $model->campaign_id;
                $breakModel->break_name = 'Tea';
                $breakModel->save();
                
                $breakModel = new VaaniCampaignBreak();
                $breakModel->break_id = User::newID('7','BRK');
                $breakModel->campaign_id = $model->campaign_id;
                $breakModel->break_name = 'Bio';
                $breakModel->save();
                
                Yii::$app->session->setFlash('success', 'Campaign added successfully.');
            }

            // QUEUE / IVR DATA
            if($model->campaign_type == EdasCampaign::TYPE_INBOUND){
                if($model->call_medium == EdasCampaign::MEDIUM_QUEUE){
                    // queue data
                    $post_queue = $this->request->post()['VaaniCampaignQueue'];

                    $prev_queues_ids = ArrayHelper::getColumn($queues, 'id');
                    $ids_diff = array_diff($prev_queues_ids,$post_queue['id']);
                    // delete queue data if row is removed
                    if($ids_diff && !$cloneid){
                        foreach ($ids_diff as $old_id) {
                            $oldQueueModel = VaaniCampaignQueue::findOne($old_id);
                            $oldQueueModel->del_status = VaaniCampaignQueue::STATUS_PERMANENT_DELETED;
                            if($oldQueueModel->save()){
                                Yii::$app->session->setFlash('success', 'Queue deleted successfully.');
                                // remove campaign-queue data in asterisk file
                                $oldQueueModel->asterisk_write('delete');
                                // remove campaign-queue data in queues conf file
                                $oldQueueModel->queue_write('delete');
                                // remove sound folder
                                $oldQueueModel->add_sound_folder('delete');
                                // remove campaign queue file
                                /* $oldQueueModel->add_queue_file('delete'); */
                    
                                // delete call access model
                                $accessModel = $oldQueueModel->callAccess;
                                if($accessModel){
                                    $accessModel->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                                    $accessModel->save();
                                }
                            }
                        }
                    }

                    foreach ($post_queue['queue_name'] as $key => $queue) {
                        if($queue && $post_queue['criteria'][$key] && $post_queue['dni_id'][$key]){
                            if($post_queue['id'][$key] && !in_array($post_queue['id'][$key], $ids_diff)){
                                // edit queue
                                $queueModel = VaaniCampaignQueue::findOne($post_queue['id'][$key]);
                                if($queueModel){
                                    $prev_queue = $queueModel->queue;
                                    $prev_dni_id = $queueModel->dni_id;
                                    $prev_criteria = $queueModel->criteria;
                                    $prev_call_timeout = $queueModel->call_timeout;
                                    $prev_call_window = (($queueModel->call_window != $post_queue['call_window'][$key]) ? $queueModel->call_window : null );

                                    if($queueModel->queue_name != $queue){
                                        $queueModel->queue = strtolower($queue) . '_' . $queueModel->campaign->id;
                                    }
                                    $queueModel->queue_name = $queue;
                                    $queueModel->criteria = $post_queue['criteria'][$key];
                                    $queueModel->call_timeout = $post_queue['call_timeout'][$key];
                                    $queueModel->dni_id = $post_queue['dni_id'][$key];
                                    $queueModel->call_window = $post_queue['call_window'][$key];

                                    if($queueModel->save()){
                                        // edit campaign queue data in asterisk file
                                        $queueModel->asterisk_write('edit', $prev_queue, $prev_dni_id, $prev_call_window, null, null, $prev_camp, $prev_call_timeout);
                                        // edit campaign queue data in queue conf file
                                        $queueModel->queue_write('edit', $prev_queue, $prev_criteria);
                                        // edit campaign queue sound folder
                                        $queueModel->add_sound_folder('edit', $prev_queue);
                                        // create campaign queue file
                                        /* $queueModel->add_queue_file('edit', $prev_queue); */

                                        // queue access model
                                        $prevCallAccessModel = $queueModel->callAccess;
                                        /* $is_access_new = false;
                                        
                                        if(!$prevCallAccessModel){
                                            $is_access_new = true;
                                            
                                        }else if($prevCallAccessModel && ($prevCallAccessModel->is_conference != $post_queue['is_conference'][$key] || $prevCallAccessModel->is_transfer != $post_queue['is_transfer'][$key] || $prevCallAccessModel->is_consult != $post_queue['is_consult'][$key] || $prevCallAccessModel->is_manual != $post_queue['is_manual'][$key])){
                                            // delete previous call access model if exists
                                            $prevCallAccessModel->updateAttributes(['del_status' => VaaniCallAccess::STATUS_PERMANENT_DELETED]);
                                            $is_access_new = true;
                                        } */
                                        if($access_updated){
                                            // delete previous call access model if exists
                                            if($prevCallAccessModel){
                                                $prevCallAccessModel->updateAttributes(['del_status' => VaaniCallAccess::STATUS_PERMANENT_DELETED]);
                                            }
                                            $accessModel = new VaaniCallAccess();
                                            $accessModel->campaign_id = $queueModel->campaign_id;
                                            $accessModel->queue_id = $queueModel->queue_id;
                                            $accessModel->is_conference = $campaignAccessModel->is_conference;
                                            $accessModel->is_transfer = $campaignAccessModel->is_transfer;
                                            $accessModel->is_consult = $campaignAccessModel->is_consult;
                                            $accessModel->is_manual = $campaignAccessModel->is_manual;
                                            $accessModel->save();
                                        }
                                        Yii::$app->session->setFlash('success', 'Queue Updated successfully!');
                                    }
                                }
                            }else{
                                $queueModel = new VaaniCampaignQueue();
                                $queueModel->campaign_id = $model->campaign_id;
                                $queueModel->queue_name = $queue;
                                $queueModel->queue_id = User::newID('3','QUE');
                                $queueModel->queue = strtolower($queue) . '_' . $model->id;
                                $queueModel->criteria = $post_queue['criteria'][$key];
                                $queueModel->dni_id = $post_queue['dni_id'][$key];
                                $queueModel->call_window = $post_queue['call_window'][$key];
                                $queueModel->call_timeout = $post_queue['call_timeout'][$key];
                                $queueModel->type = VaaniCampaignQueue::TYPE_QUEUE;

                                // queue access model
                                $accessModel = new VaaniCallAccess();
                                $accessModel->campaign_id = $queueModel->campaign_id;
                                $accessModel->queue_id = $queueModel->queue_id;
                                $accessModel->is_conference = $campaignAccessModel->is_conference;
                                $accessModel->is_transfer = $campaignAccessModel->is_transfer;
                                $accessModel->is_consult = $campaignAccessModel->is_consult;
                                $accessModel->is_manual = $campaignAccessModel->is_manual;
                                
                                if($queueModel->save()){
                                    $accessModel->save();
                                    // insert default role access for campaign's queue
                                    VaaniRoleMaster::addDefaultRoleAccess($model->client_id, $model->campaign_id, $queueModel->queue_id);
                                    // add campaign queue data in asterisk file
                                    $queueModel->asterisk_write('add');
                                    // add campaign queue data in queue conf file
                                    $queueModel->queue_write('add');
                                    // create campaign queue sound folder
                                    $queueModel->add_sound_folder('add');
                                    // create campaign queue file
                                    /* $queueModel->add_queue_file('add'); */
                                }else{
                                    foreach($queueModel->errors as $error){
                                        Yii::$app->session->setFlash('error', json_encode($error));
                                    }
                                }
                                if($key == count($post_queue['queue_name'])){
                                    Yii::$app->session->setFlash('success', 'Queue added successfully!');
                                }
                            }
                        }else{
                            Yii::$app->session->setFlash('error', 'Kindly add all details for the queue!');
                        }
                    }
                }else if($model->call_medium == EdasCampaign::MEDIUM_IVR){
                    $model->updateAttributes(['is_dtmf' => $this->request->post()['EdasCampaign']['is_dtmf']]);
                    $model->updateAttributes(['is_ivr_queue' => $this->request->post()['EdasCampaign']['is_ivr_queue']]);
                    // queue data
                    $post_queue = $this->request->post()['IvrQueue'];

                    // delete call medium queues' queues data
                    if($prev_call_medium != $model->call_medium){
                        foreach ($queues as $k => $old_queue) {
                            $old_queue->del_status = VaaniCampaignQueue::STATUS_PERMANENT_DELETED;
                            if($old_queue->save()){
                                // remove campaign-queue data in asterisk file
                                $old_queue->asterisk_write('delete');
                                // remove campaign-queue data in queues conf file
                                $old_queue->queue_write('delete');
                                // remove sound folder
                                $old_queue->add_sound_folder('delete');
                                // remove campaign queue file
                                /* $old_queue->add_queue_file('delete'); */
                    
                                // delete call access model
                                $accessModel = $old_queue->callAccess;
                                if($accessModel){
                                    $accessModel->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                                    $accessModel->save();
                                }
                            }
                        }
                    }

                    if($model->is_dtmf == 1){

                        if($model->is_ivr_queue == 1){
                            $prev_queues_ids = ArrayHelper::getColumn($queues, 'id');
                            $ids_diff = array_diff($prev_queues_ids,$post_queue['id']);
                            // delete queue data if row is removed
                            if($ids_diff){
                                foreach ($ids_diff as $old_id) {
                                    $oldQueueModel = VaaniCampaignQueue::findOne($old_id);
                                    $oldQueueModel->del_status = VaaniCampaignQueue::STATUS_PERMANENT_DELETED;
                                    if($oldQueueModel->save()){
                                        Yii::$app->session->setFlash('success', 'Queue deleted successfully.');
                                        // remove campaign-queue data in asterisk file
                                        $oldQueueModel->asterisk_write('delete');
                                        // remove campaign-queue data in queues conf file
                                        $oldQueueModel->queue_write('delete');
                                        // remove sound folder
                                        $oldQueueModel->add_sound_folder('delete');
                                        // remove campaign queue file
                                        /* $oldQueueModel->add_queue_file('delete'); */
                            
                                        // delete call access model
                                        $accessModel = $oldQueueModel->callAccess;
                                        if($accessModel){
                                            $accessModel->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                                            $accessModel->save();
                                        }
                                    }
                                }
                            }

                            foreach ($post_queue['queue_name'] as $key => $queue) {
                                if($queue && $post_queue['criteria'][$key] && $post_queue['key_input'][$key]){
                                    if($post_queue['id'][$key]){
                                        // edit queue
                                        $queueModel = VaaniCampaignQueue::findOne($post_queue['id'][$key]);
                                        if($queueModel){
                                            $prev_queue = $queueModel->queue;
                                            $prev_criteria = $queueModel->criteria;
                                            $prev_timeout = $queueModel->call_timeout;
                                            $prev_call_window = (($queueModel->call_window != $post_queue['call_window'][$key]) ? $queueModel->call_window : null );

                                            if($queueModel->queue_name != $queue){
                                                $queueModel->queue = strtolower($queue) . '_' . $queueModel->campaign->id;
                                            }
                                            $queueModel->queue_name = $queue;
                                            $queueModel->criteria = $post_queue['criteria'][$key];
                                            $queueModel->key_input = $post_queue['key_input'][$key];
                                            $queueModel->call_window = $post_queue['call_window'][$key];
                                            $queueModel->dni_id = $model->campaign_dni;

                                            if($queueModel->save()){
                                                // edit campaign queue data in asterisk file
                                                $queueModel->asterisk_write('edit', $prev_queue, $prev_dni_id, $prev_call_window, null, null, $prev_camp, $prev_timeout);
                                                // edit campaign queue data in queue conf file
                                                $queueModel->queue_write('edit', $prev_queue, $prev_criteria);
                                                // edit campaign queue sound folder
                                                $queueModel->add_sound_folder('edit', $prev_queue);
                                                // create campaign queue file
                                                /* $queueModel->add_queue_file('edit', $prev_queue); */

                                                // queue access model
                                                $prevCallAccessModel = $queueModel->callAccess;
                                                /* $is_access_new = false;
                                                
                                                if(!$prevCallAccessModel){
                                                    $is_access_new = true;
                                                    
                                                }else if($prevCallAccessModel && ($prevCallAccessModel->is_conference != $post_queue['is_conference'][$key] || $prevCallAccessModel->is_transfer != $post_queue['is_transfer'][$key] || $prevCallAccessModel->is_consult != $post_queue['is_consult'][$key] || $prevCallAccessModel->is_manual != $post_queue['is_manual'][$key])){
                                                    // delete previous call access model if exists
                                                    if($prevCallAccessModel){
                                                        $prevCallAccessModel->updateAttributes(['del_status' => VaaniCallAccess::STATUS_PERMANENT_DELETED]);
                                                    }
                                                    $is_access_new = true;
                                                } */
                                                // if($is_access_new){
                                                if($access_updated){
                                                    // delete previous call access model if exists
                                                    if($prevCallAccessModel){
                                                        $prevCallAccessModel->updateAttributes(['del_status' => VaaniCallAccess::STATUS_PERMANENT_DELETED]);
                                                    }
                                                    $accessModel = new VaaniCallAccess();
                                                    $accessModel->campaign_id = $queueModel->campaign_id;
                                                    $accessModel->queue_id = $queueModel->queue_id;
                                                    $accessModel->is_conference = $campaignAccessModel->is_conference;
                                                    $accessModel->is_transfer = $campaignAccessModel->is_transfer;
                                                    $accessModel->is_consult = $campaignAccessModel->is_consult;
                                                    $accessModel->is_manual = $campaignAccessModel->is_manual;
                                                    $accessModel->save();
                                                }
                                                Yii::$app->session->setFlash('success', 'Queue Updated successfully!');
                                            }
                                        }
                                    }else{
                                        $queueModel = new VaaniCampaignQueue();
                                        $queueModel->campaign_id = $model->campaign_id;
                                        $queueModel->dni_id = $model->campaign_dni;
                                        $queueModel->queue_name = $queue;
                                        $queueModel->queue_id = User::newID('3','QUE');
                                        $queueModel->queue = strtolower($queue) . '_' . $model->id;
                                        $queueModel->call_timeout = $post_queue['call_timeout'][$key];
                                        $queueModel->criteria = $post_queue['criteria'][$key];
                                        $queueModel->key_input = $post_queue['key_input'][$key];
                                        $queueModel->call_window = $post_queue['call_window'][$key];
                                        $queueModel->type = VaaniCampaignQueue::TYPE_IVR;

                                        // queue access model
                                        $accessModel = new VaaniCallAccess();
                                        $accessModel->campaign_id = $queueModel->campaign_id;
                                        $accessModel->queue_id = $queueModel->queue_id;
                                        $accessModel->is_conference = $campaignAccessModel->is_conference;
                                        $accessModel->is_transfer = $campaignAccessModel->is_transfer;
                                        $accessModel->is_consult = $campaignAccessModel->is_consult;
                                        $accessModel->is_manual = $campaignAccessModel->is_manual;
                                        
                                        if($queueModel->save()){
                                            $accessModel->save();
                                            // insert default role access for campaign's queue
                                            VaaniRoleMaster::addDefaultRoleAccess($model->client_id, $model->campaign_id, $queueModel->queue_id);
                                            // add campaign queue data in asterisk file
                                            $queueModel->asterisk_write('add');
                                            // add campaign queue data in queue conf file
                                            $queueModel->queue_write('add');
                                            // create campaign queue sound folder
                                            $queueModel->add_sound_folder('add');
                                            // create campaign queue file
                                            /* $queueModel->add_queue_file('add'); */
                                        }else{
                                            foreach($queueModel->errors as $error){
                                                Yii::$app->session->setFlash('error', json_encode($error));
                                            }
                                        }
                                        if($key == count($post_queue['queue_name'])){
                                            Yii::$app->session->setFlash('success', 'Queue added successfully!');
                                        }
                                    }
                                }else{
                                    Yii::$app->session->setFlash('error', 'Kindly add all details for the queue!');
                                }
                            }
                        }else{
                            if($prev_is_ivr_queue != $model->is_ivr_queue){
                                foreach ($queues as $k => $ivr_queue) {
                                    $ivr_queue->del_status = VaaniCampaignQueue::STATUS_PERMANENT_DELETED;
                                    if($ivr_queue->save()){
                                        // remove campaign-queue data in asterisk file
                                        $ivr_queue->asterisk_write('delete');
                                        // remove campaign-queue data in queues conf file
                                        $ivr_queue->queue_write('delete');
                                        // remove sound folder
                                        $ivr_queue->add_sound_folder('delete');
                                        // remove campaign queue file
                                        /* $ivr_queue->add_queue_file('delete'); */
                            
                                        // delete call access model
                                        $accessModel = $ivr_queue->callAccess;
                                        if($accessModel){
                                            $accessModel->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                                            $accessModel->save();
                                        }
                                    }
                                }
                            }
                        }
                    }else{
                        // delete queues & call access if exists
                        if($prev_is_dtmf != $model->is_dtmf){
                            foreach ($queues as $k => $ivr_queue) {
                                $ivr_queue->del_status = VaaniCampaignQueue::STATUS_PERMANENT_DELETED;
                                if($ivr_queue->save()){
                                    Yii::$app->session->setFlash('success', 'Queue deleted successfully.');
                                    // remove campaign-queue data in asterisk file
                                    $ivr_queue->asterisk_write('delete');
                                    // remove campaign-queue data in queues conf file
                                    $ivr_queue->queue_write('delete');
                                    // remove sound folder
                                    $ivr_queue->add_sound_folder('delete');
                                    // remove campaign queue file
                                    /* $ivr_queue->add_queue_file('delete'); */
                        
                                    // delete call access model
                                    $accessModel = $ivr_queue->callAccess;
                                    if($accessModel){
                                        $accessModel->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                                        $accessModel->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }elseif($model->campaign_type == EdasCampaign::TYPE_OUTBOUND){
                if($is_camp_update){
                    if($model->allQueues){
                        // delete previous queues
                        $queue_access_deleted = false;
                        $queue_update = false;

                        if($prev_camp_type != $model->campaign_type){
                            foreach ($model->queues as $key => $outbound_queue) {
                                $outbound_queue->del_status = VaaniCampaignQueue::STATUS_PERMANENT_DELETED;
                                if($outbound_queue->save()){
                                    // remove campaign-queue data in asterisk file
                                    $outbound_queue->asterisk_write('delete');
                                    // remove campaign-queue data in queues conf file
                                    $outbound_queue->queue_write('delete');
                                    // remove sound folder
                                    $outbound_queue->add_sound_folder('delete');
                                    // remove campaign queue file
                                    /* $outbound_queue->add_queue_file('delete'); */
                        
                                    // delete call access model
                                    $accessModel = $outbound_queue->callAccess;
                                    if($accessModel){
                                        $accessModel->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                                        if($accessModel->save()){
                                            $queue_access_deleted = true;
                                        }
                                    }
                                }
                            }
                            // add new queues with call access
                            // add default queue for the campaign
                            $default_queue = new VaaniCampaignQueue();
                            $default_queue->queue_id = User::newID('3','QUE');
                            $default_queue->campaign_id = $model->campaign_id;
                        }else{
                            $prev_queue = $model->allQueues[0]->queue;
                            $prev_criteria = $model->allQueues[0]->criteria;
                            $prev_call_timeout = $model->allQueues[0]->call_timeout;
                            $prev_call_window = (($model->allQueues[0]->call_window != $model->call_window) ? $model->allQueues[0]->call_window : null );

                            $default_queue = $model->allQueues[0];
                            $queue_update = true;
                        }
                        
                        $default_queue->call_window = $model->call_window;
                        $default_queue->call_timeout = $model->call_timeout;
                        $default_queue->criteria = ($model->outbound_criteria ? $model->outbound_criteria : VaaniCampaignQueue::CRITERIA_LEAST_RECENT);
                        $default_queue->type = VaaniCampaignQueue::TYPE_QUEUE;
                        
                        if($prev_campaign_name != $model->campaign_name){
                            $default_queue->queue_name = $model->campaign_name . '_default';
                            $default_queue->queue = $model->campaign_name . '_default'.date('s').'_' . $model->id;
                        }

                        if($access_updated){
                            // delete call access model
                            $accessModel = $default_queue->callAccess;
                            if($accessModel){
                                $accessModel->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                                if($accessModel->save()){
                                    $queue_access_deleted = true;
                                }
                            }
                        }

                        if($queue_access_deleted){
                            // queue access model
                            $accessModel = new VaaniCallAccess();
                            $accessModel->campaign_id = $default_queue->campaign_id;
                            $accessModel->queue_id = $default_queue->queue_id;
                            $accessModel->is_conference = $campaignAccessModel->is_conference;
                            $accessModel->is_transfer = $campaignAccessModel->is_transfer;
                            $accessModel->is_consult = $campaignAccessModel->is_consult;
                            $accessModel->is_manual = $campaignAccessModel->is_manual;
                    
                            if($default_queue->save()){
                                $accessModel->save();
                                // insert default role access for campaign's queue
                                VaaniRoleMaster::addDefaultRoleAccess($model->client_id, $model->campaign_id, $default_queue->queue_id);
                                // add campaign queue data in asterisk file
                                $default_queue->asterisk_write('add');
                                // add campaign queue data in queue conf file
                                $default_queue->queue_write('add');
                                // create campaign queue sound folder
                                $default_queue->add_sound_folder('add');
                                // create campaign queue file
                                /* $default_queue->add_queue_file('add'); */
                            }else{
                                foreach($default_queue->errors as $error){
                                    Yii::$app->session->setFlash('error', json_encode($error));
                                }
                            }
                        }elseif($queue_update){
                            if($default_queue->save()){
                                // edit campaign queue data in asterisk file
                                $default_queue->asterisk_write('edit', $prev_queue, $prev_dni_id, $prev_call_window, null, null, $prev_camp);
                                // edit campaign queue data in queue conf file
                                $default_queue->queue_write('edit', $prev_queue, $prev_criteria);
                                // edit campaign queue sound folder
                                $default_queue->add_sound_folder('edit', $prev_queue);
                            }
                        }
                    }
                }
            }

            if(!$model->allQueues){
                // add default queue for the campaign
                $default_queue = new VaaniCampaignQueue();
                $default_queue->queue_id = User::newID('3','QUE');
                $default_queue->campaign_id = $model->campaign_id;
                if($model->campaign_type == EdasCampaign::TYPE_INBOUND){
                    $default_queue->dni_id = $model->campaign_dni;
                }
                $default_queue->call_window = $model->call_window;
                $default_queue->call_timeout = $model->call_timeout;
                $default_queue->queue_name = $model->campaign_name . '_default';
                $default_queue->queue = $model->campaign_name . '_default'.date('s').'_' . $model->id;
                $default_queue->criteria = ($model->outbound_criteria ? $model->outbound_criteria : VaaniCampaignQueue::CRITERIA_LEAST_RECENT);
                $default_queue->type = ($model->campaign_type == EdasCampaign::TYPE_OUTBOUND ? VaaniCampaignQueue::TYPE_QUEUE : VaaniCampaignQueue::TYPE_IVR);

                // queue access model
                $accessModel = new VaaniCallAccess();
                $accessModel->campaign_id = $default_queue->campaign_id;
                $accessModel->queue_id = $default_queue->queue_id;
                $accessModel->is_conference = $campaignAccessModel->is_conference;
                $accessModel->is_transfer = $campaignAccessModel->is_transfer;
                $accessModel->is_consult = $campaignAccessModel->is_consult;
                $accessModel->is_manual = $campaignAccessModel->is_manual;
                
                if($default_queue->save()){
                    $accessModel->save();
                    // insert default role access for campaign's queue
                    VaaniRoleMaster::addDefaultRoleAccess($model->client_id, $model->campaign_id, $default_queue->queue_id);
                    // add campaign queue data in asterisk file
                    $default_queue->asterisk_write('add');
                    // add campaign queue data in queue conf file
                    $default_queue->queue_write('add');
                    // create campaign queue sound folder
                    $default_queue->add_sound_folder('add');
                    // create campaign queue file
                    /* $default_queue->add_queue_file('add'); */
                }else{
                    foreach($default_queue->errors as $error){
                        Yii::$app->session->setFlash('error', json_encode($error));
                    }
                }
            }

            // add campaign active queues
            if($model->campaignActiveQueues){
                $model->campaignActiveQueues->delete();
            }
            $campaign_name = strtolower(trim($model->campaign_name));
            $campaign_name = str_replace(" ", "_", $campaign_name);

            $campaignActiveQueues = new VaaniCampaignActiveQueues();
            $campaignActiveQueues->campaign = $campaign_name;

            if($model->allQueues){
                foreach ($model->allQueues as $key => $camp_queue) {
                    if($key < 10){
                        $field_name = 'q' . ($key+1);
                        $campaignActiveQueues->{$field_name} = $camp_queue->queue;
                    }
                }
            }
            $campaignActiveQueues->save();

            return $this->redirect(['index']);
        }else{
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
        }

        // fetch list of clients
        $clients = [];
        $client_ids = null;
        if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
            $client_ids = $user->clientIds;
        }
        $clients = ArrayHelper::map(VaaniClientMaster::clientsList($client_ids), 'client_id', 'client_name');

        $prev_dni_data = [];
        /* if($model->queues){
            foreach ($model->queues as $k => $val) {
                if($val->dni_id)
                    $prev_dni_data[$val->dni_id] = $val->dni->DNI_name;
            }
        }else{
            $prev_dni_data = ($model->dni ? [$model->campaign_dni => $model->dni->DNI_name] : []);
        } */
        if(!$id) {
            // $model->client_id = array_key_first($clients);
        }
        if($model->client_id) {
            $client_ids = $model->client_id;
        }
        // VaaniQmsTemplate
        // $qms_ids = VaaniQmsTemplate::find()->select(['qms_id,template_name'])->where(['del_status' => User::STATUS_NOT_DELETED])->asArray()->all();
        $qms_names = ArrayHelper::map(VaaniQmsTemplate::allActiveQms(), 'qms_id','template_name');
        $kptemplates = ArrayHelper::map(vaani_kp_templete::tempName(), 'templete_id','template_name');
        $unused_dnis = ArrayHelper::map(EdasDniMaster::unusedDniList($client_ids), 'id', 'DNI_name');
        $dnis = array_replace($prev_dni_data, $unused_dnis);
        $week_days = ['Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wesnesday' => 'Wesnesday', 'Thusday' => 'Thusday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'];
        $criterias = EdasCampaign::$campaign_criterias;
        $campaign_types = EdasCampaign::$campaign_types;
        $campaign_modes = EdasCampaign::$campaign_modes;
        $call_mediums = EdasCampaign::$call_mediums;
        $access_values = VaaniCallAccess::$access_values;
        // fetch call_windows
        $call_windows = ArrayHelper::map(VaaniCallTimesConfig::activeCallWindows(), 'id', 'call_time_name');
        
        // fetch client's operator 
        $operatorModels = VaaniClientOperators::find()->select(['operator_id','created_date'])->where(['client_id' => (isset($_SESSION['client_connection']) ? $_SESSION['client_connection'] : null),'del_status' => User::STATUS_NOT_DELETED])->all();
        $operators = ArrayHelper::getColumn($operatorModels, 'operator_id');
        $oprator_name = VaaniOperators::find()->select(['id','operator_name'])->where(['id' => $operators])->all();
        $clientsoperator = ArrayHelper::map($oprator_name, 'id','operator_name');

        //Fetch list of disposition plans
        $disposition_plans = ArrayHelper::map(VaaniDispositionPlan::activePlans(), function($model){
            return User::encrypt_data($model['plan_id']);
        }, 'name');

        // added by Adarsh 
        $queue_ids = Yii::$app->request->get('queue_ids');
        $users = User::find()
        ->joinWith('userAccess')
        ->joinWith('supervisor')
        ->where(['vaani_user.del_status' => User::STATUS_NOT_DELETED, '`vaani_user_access`.`client_id`' => $client_ids])
        ->andWhere(['not',['`vaani_user_access`.`queue_id`' => $queue_ids]])
        ->andWhere(['vaani_user.role_id' => 'ROL5520211101094927719720'])
        ->select('vaani_user.user_id, vaani_user.user_name, manager_id, supervisor_id ,queue_id')
        ->asArray()
        ->groupBy('vaani_user.user_id')
        
        ->all(); 
        
        // queues of campaign   
        $queue_list = VaaniCampaignQueue::find()->where(['campaign_id' => $model->campaign_id,'del_status' => User::STATUS_NOT_DELETED])->all();
        $add_skills = ArrayHelper::map($queue_list,'queue_id','queue_name');
        
        
        // fetch skill assigned users of campaign but not active user
         $skill_assigned_users = User::find()
        ->joinWith('userAccess')
        ->joinWith('supervisor')
        ->where(['vaani_user.del_status' => User::STATUS_NOT_DELETED, '`vaani_user_access`.`client_id`' => $client_ids])
        ->andWhere(['`vaani_user_access`.`campaign_id`' => $model->campaign_id])
        ->andWhere(['`vaani_user_access`.`is_active`' => User::STATUS_PERMANENT_DELETED])
        ->andWhere(['vaani_user.role_id' => 'ROL5520211101094927719720'])
        ->select('vaani_user.user_id, vaani_user.user_name, manager_id, supervisor_id ,queue_id,vaani_user_access.is_active')
        ->asArray()
        ->groupBy('vaani_user.user_id')
        ->all(); 
        // fetch skill assigned users of campaign active user
        $skill_assigned_active_users = User::find()
        ->joinWith('userAccess')
        ->joinWith('supervisor')
        ->where(['vaani_user.del_status' => User::STATUS_NOT_DELETED, '`vaani_user_access`.`client_id`' => $client_ids])
        ->andWhere(['`vaani_user_access`.`campaign_id`' => $model->campaign_id])
        ->andWhere(['`vaani_user_access`.`is_active`' => 1])
        ->andWhere(['vaani_user.role_id' => 'ROL5520211101094927719720'])
        ->select('vaani_user.user_id, vaani_user.user_name, manager_id, supervisor_id ,queue_id,vaani_user_access.is_active')
        ->asArray()
        ->groupBy('vaani_user.user_id')
        ->all(); 
        // fetch disposition data 
        // $dispostion_data = VaaniCampDispositions::find()->where(['campaign_id'=> $model->campaign_id, 'del_status' => User::STATUS_NOT_DELETED])->asArray()->all();
        $dispostion_data = VaaniCampDispositions::find()->joinWith('disporecord')->where(['vaani_camp_dispositions.del_status' => User::STATUS_NOT_DELETED, '`vaani_camp_dispositions`.`campaign_id`' => $model->campaign_id])
       ->asArray()->all();
        // inbound campign list 
        $inbound_campaign = EdasCampaign::find()->select(['campaign_id','campaign_name'])->where(['del_status' => User::STATUS_NOT_DELETED,'client_id'=> $client_ids])->andWhere(['campaign_type' => EdasCampaign::TYPE_INBOUND])->asArray()->all();


        return $this->render('add-campaign', [
            'cloneid' => $cloneid,
            'model' => $model,
            'queueModel' => $queueModel,
            'clients' => $clients,
            'week_days' => $week_days,
            'criterias' => $criterias,
            'campaign_types' => $campaign_types,
            'campaign_modes' => $campaign_modes,
            'call_mediums' => $call_mediums,
            'dnis' => $dnis,
            'access_values' => $access_values,
            'queues' => $queues,
            'call_windows' => $call_windows,
            'campaignAccessModel' => $campaignAccessModel,
            'clientsoperator' => $clientsoperator,
            'disposition_plans' => $disposition_plans,
            'add_skills' => $add_skills,
            'skill_assigned_users' => $skill_assigned_users,
            'skill_assigned_active_users' => $skill_assigned_active_users,
            'dispostion_data' => $dispostion_data,
            'users'=> $users,
            'inbound_campaign' => $inbound_campaign,
            'qms_names'=> $qms_names,
            'kptemplates' => $kptemplates,
        ]);
    }

    /** <<<<<<<<<<<<<<<<  OUTBOUND CAMPAIGN TYPE  >>>>>>>>>>>>>>>>>
     * Add/Updates an existing EdasCampaign model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddOutbound($id=null)
    {
        Yii::$app->session->setFlash('info', 'Outbound Campaign under construction.');

        $user = Yii::$app->user->identity;

        $model = new EdasCampaign();
        $model->campaign_id = User::newID('3','CAM');
        $model->campaign_type = EdasCampaign::TYPE_OUTBOUND;
        
        // campaign call access model
        $is_access_edit = false;
        $access_updated = false;
        if($model->callAccess){
            $is_access_edit = true;
            $campaignAccessModel = $model->callAccess;
            $prevCampaignAccessModel = $model->callAccess;
        }else{
            $access_updated = true;
            $campaignAccessModel = new VaaniCallAccess();
            $campaignAccessModel->campaign_id = $model->campaign_id;
            $campaignAccessModel->is_manual = VaaniCallAccess::ACCESS_YES;
        }

        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {

            // campaign access
            if($is_access_edit){
                $campaignAccessModel = new VaaniCallAccess();
                $campaignAccessModel->campaign_id = $model->campaign_id;
                
                if($campaignAccessModel->load($this->request->post())){
                    if(isset($prevCampaignAccessModel) && $prevCampaignAccessModel && ($prevCampaignAccessModel->is_conference != $campaignAccessModel->is_conference || $prevCampaignAccessModel->is_transfer != $campaignAccessModel->is_transfer || $prevCampaignAccessModel->is_consult != $campaignAccessModel->is_consult || $prevCampaignAccessModel->is_manual != $campaignAccessModel->is_manual)){
                        if($campaignAccessModel->load($this->request->post()) && $campaignAccessModel->save()){
                            $access_updated = true;
                            // delete previous call access model if exists
                            $prevCampaignAccessModel->updateAttributes(['del_status' => VaaniCallAccess::STATUS_PERMANENT_DELETED]);
                        }
                    }
                }
            }

            if($id){
                // edit campaign data in asterisk file
                $model->asterisk_write('edit', $prev_campaign_name, $prev_dni_id);
                // edit campaign data in queues conf file
                $model->queue_write('edit', $prev_campaign_name, $prev_criteria);
                // edit sound folder
                $model->add_sound_folder('edit', $prev_campaign_name);
                
                Yii::$app->session->setFlash('success', 'Campaign updated successfully.');
            }else{
                // add campaign data in asterisk file
                $model->asterisk_write('add');
                // add campaign data in queues conf file
                $model->queue_write('add');
                // add sound folder
                $model->add_sound_folder('add');
                
                Yii::$app->session->setFlash('success', 'Campaign added successfully.');
            }
            
            return $this->redirect(['index']);
        }else{
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
        }


        // fetch list of clients
        $clients = [];
        $client_ids = null;
        if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
            $client_ids = $user->clientIds;
        }
        $clients = ArrayHelper::map(VaaniClientMaster::clientsList($client_ids), 'client_id', 'client_name');

        if(!$id) {
            $model->client_id = array_key_first($clients);
        }
        $campaign_modes = EdasCampaign::$campaign_modes;
        $access_values = VaaniCallAccess::$access_values;
        // fetch call_windows
        $call_windows = ArrayHelper::map(VaaniCallTimesConfig::activeCallWindows(), 'id', 'call_time_name');

        return $this->render('add-outbound', [
            'model' => $model,
            'clients' => $clients,
            'campaign_modes' => $campaign_modes,
            'access_values' => $access_values,
            'call_windows' => $call_windows,
            'campaignAccessModel' => $campaignAccessModel,
        ]);
    }

    /**
     * Deletes an existing EdasCampaign model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);
        $model->del_status = EdasCampaign::STATUS_PERMANENT_DELETED;
        if($model->save()){
            // remove campaign data in asterisk file
            $model->asterisk_write('delete');
            // remove campaign data in queues conf file
            $model->queue_write('delete');
            // remove sound folder
            $model->add_sound_folder('delete');
            Yii::$app->session->setFlash('success', 'Campaign deleted successfully.');

            if($model->allQueues){
                foreach ($model->allQueues as $k => $queue) {
                    $queue->del_status = VaaniCampaignQueue::STATUS_PERMANENT_DELETED;
                    if($queue->save()){
                        // remove campaign-queue data in asterisk file
                        $queue->asterisk_write('delete');
                        // remove campaign-queue data in queues conf file
                        $queue->queue_write('delete');
                        // remove sound folder
                        $queue->add_sound_folder('delete');
                        // remove campaign queue file
                        /* $queue->add_queue_file('delete'); */
                        Yii::$app->session->setFlash('success', 'Queues deleted successfully.');
                
                        // delete call access model
                        $accessModel = $queue->callAccess;
                        if($accessModel){
                            $accessModel->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                            $accessModel->save();
                        }

                        // remove user active queues
                        if($queue->userActiveQueues){
                            foreach ($queue->userActiveQueues as $k => $val) {
                                $skey   = array_search($queue, $val);
                                $id     = $val['id'];
                                $user_id     = $val['user_id'];
                                if(!empty($skey))
                                {
                                    unset($val[$skey], $val['id'], $val['user_id']);
                                    // fetch
                                    $old_queues = array_filter(array_values($val));

                                    // delete old record
                                    $userQueues = VaaniUserActiveQueues::findOne($id);
                                    $userQueues->delete();
                                    // add new record
                                    if($old_queues){
                                        $userActiveQueues = new VaaniUserActiveQueues();
                                        $userActiveQueues->user_id = $user_id;

                                        for ($i=0; $i <10 ; $i++) {
                                            $field_name = 'q' . ($i+1);
                                            $userActiveQueues->{$field_name} = (isset($old_queues[$i]) ? $old_queues[$i] : null);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // remove campaign active queues
            if($model->campaignActiveQueues){
                $model->campaignActiveQueues->delete();
            }

            // remove campaign call access
            if($model->callAccess){
                $model->callAccess->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                $model->callAccess->save();
            }

            // delete campaign breaks
            if($model->breaks){
                foreach ($model->breaks as $key => $break) {
                    $break->del_status = VaaniCampaignBreak::STATUS_PERMANENT_DELETED;
                    $break->save();
                }
            }
        }else{
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the EdasCampaign model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return EdasCampaign the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EdasCampaign::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // fetch unused dni on change of client
    public function actionGetDnis()
    {
        $client_id = Yii::$app->request->get()['client_id'];
        
        if($client_id)
            return json_encode(ArrayHelper::map(EdasDniMaster::unusedDniList($client_id), 'id', 'DNI_name'));
        else
            return false;
    }

    // add queue data for campaign (id => campaign id)
    public function actionAddQueue($id)
    {
        $campaign = $this->findModel($id);
        if($campaign){
            // campaign queues
            $searchModel = new VaaniCampaignQueueSearch();
            $searchModel->campaign_id = $campaign->campaign_id;
            $searchModel->del_status = VaaniCampaignQueue::STATUS_NOT_DELETED;
            $searchModel->type = VaaniCampaignQueue::TYPE_QUEUE;
            $dataProvider = $searchModel->search($this->request->queryParams);

            // create new model for new queue
            $model = new VaaniCampaignQueue();
            $model->campaign_id = $campaign->campaign_id;

            $prev_dni_data = [];
            if($campaign->queues){
                foreach ($campaign->queues as $k => $val) {
                    if($val->dni_id)
                        $prev_dni_data[$val->dni_id] = $val->dni->DNI_name;
                }
            }

            if(Yii::$app->request->post()){
                if(Yii::$app->request->post()['VaaniCampaignQueue']){
                    $queues = Yii::$app->request->post()['VaaniCampaignQueue']['queue_name'];
                    $dni_ids = Yii::$app->request->post()['VaaniCampaignQueue']['dni_id'];
                    $criterias = Yii::$app->request->post()['VaaniCampaignQueue']['criteria'];
                    $is_manuals = Yii::$app->request->post()['VaaniCampaignQueue']['is_manual'];
                    $is_conferences = Yii::$app->request->post()['VaaniCampaignQueue']['is_conference'];
                    $is_transfers = Yii::$app->request->post()['VaaniCampaignQueue']['is_transfer'];
                    $is_consults = Yii::$app->request->post()['VaaniCampaignQueue']['is_consult'];
                    
                    if($campaign->call_medium == EdasCampaign::MEDIUM_QUEUE && !$queues[0]){
                        Yii::$app->session->setFlash('error', 'Kindly add atleast one queue!');
                    }else{
                        if($queues){
                            foreach ($queues as $key => $queue) {
                                $queueModel = new VaaniCampaignQueue();
                                $queueModel->campaign_id = $campaign->campaign_id;
                                $queueModel->queue_name = $queue;
                                $queueModel->queue_id = User::newID('3','QUE');
                                $queueModel->queue = strtolower($queue) . '_' . $campaign->id;
                                $queueModel->dni_id = $dni_ids[$key];
                                $queueModel->criteria = $criterias[$key];
                                $queueModel->type = VaaniCampaignQueue::TYPE_QUEUE;
                                
                                // queue access model
                                $accessModel = new VaaniCallAccess();
                                $accessModel->campaign_id = $queueModel->campaign_id;
                                $accessModel->queue_id = $queueModel->queue_id;
                                $accessModel->is_conference = $is_conferences[$key];
                                $accessModel->is_transfer = $is_transfers[$key];
                                $accessModel->is_consult = $is_consults[$key];
                                $accessModel->is_manual = $is_manuals[$key];

                                if($queueModel->save()){
                                    $accessModel->save();
                                    // insert default role access for campaign's queue
                                    VaaniRoleMaster::addDefaultRoleAccess($campaign->client_id, $campaign->campaign_id, $queueModel->queue_id);
                                    // add campaign queue data in asterisk file
                                    $queueModel->asterisk_write('add');
                                    // add campaign queue data in queue conf file
                                    $queueModel->queue_write('add');
                                    // create campaign queue sound folder
                                    $queueModel->add_sound_folder('add');
                                }
                                if($key == count($queues)){
                                    Yii::$app->session->setFlash('success', 'Queue added successfully!');
                                    return $this->redirect(['index']);
                                }
                            }
                        }
                    }
                }
            }

            $unused_dnis = ArrayHelper::map(EdasDniMaster::unusedDniList($campaign->client_id), 'id', 'DNI_name');
            $dnis = array_replace($prev_dni_data, $unused_dnis);
            $criterias = EdasCampaign::$campaign_criterias;
            $access_values = VaaniCallAccess::$access_values;

            return $this->render('add-queue', [
                'model' => $model,
                'campaign' => $campaign,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'criterias' => $criterias,
                'dnis' => $dnis,
                'access_values' => $access_values,
            ]);
        }
        throw new NotFoundHttpException('Campaign does not exist.');
    }

    // edit queue model (id => campaign id)
    public function actionUpdateQueue($id)
    {
        $post = Yii::$app->request->post();

        if($post['queue_id']){
            $model = VaaniCampaignQueue::findOne($post['queue_id']);
            if($model){
                $prev_queue = $model->queue;
                $prev_dni_id = $model->dni_id;
                $prev_criteria = $model->criteria;

                if($model->queue_name != $post['queue_name']){
                    $model->queue = strtolower($post['queue_name']) . '_' . $model->campaign->id;
                }
                $model->queue_name = $post['queue_name'];
                $model->dni_id = $post['dni_id'];
                $model->criteria = $post['criteria'];

                if($model->save()){
                    // edit campaign queue data in asterisk file
                    $model->asterisk_write('edit', $prev_queue, $prev_dni_id);
                    // edit campaign queue data in queue conf file
                    $model->queue_write('edit', $prev_queue, $prev_criteria);
                    // edit campaign queue sound folder
                    $model->add_sound_folder('edit', $prev_queue);

                    // queue access model
                    $prevCallAccessModel = $model->callAccess;
                    $is_access_new = false;
                    
                    if(!$prevCallAccessModel){
                        $is_access_new = true;
                        
                    }else if($prevCallAccessModel && ($prevCallAccessModel->is_conference != $post['is_conference'] || $prevCallAccessModel->is_transfer != $post['is_transfer'] || $prevCallAccessModel->is_consult != $post['is_consult'] || $prevCallAccessModel->is_manual != $post['is_manual'])){
                        // delete previous call access model if exists
                        if($prevCallAccessModel){
                            $prevCallAccessModel->updateAttributes(['del_status' => VaaniCallAccess::STATUS_PERMANENT_DELETED]);
                        }
                        $is_access_new = true;
                    }
                    if($is_access_new){
                        $accessModel = new VaaniCallAccess();
                        $accessModel->campaign_id = $model->campaign_id;
                        $accessModel->queue_id = $model->queue_id;
                        $accessModel->is_conference = $post['is_conference'];
                        $accessModel->is_transfer = $post['is_transfer'];
                        $accessModel->is_consult = $post['is_consult'];
                        $accessModel->is_manual = $post['is_manual'];
                        $accessModel->save();
                    }
                    Yii::$app->session->setFlash('success', 'Queue Updated successfully!');
                }
            }
        }
        return $this->redirect(['add-queue', 'id' => $id]);
    }

    // delete queue (id => campaign id, queue_id => queue id)
    public function actionDeleteQueue($id, $queue_id)
    {
        $model = VaaniCampaignQueue::findOne($queue_id);
        $model->del_status = VaaniCampaignQueue::STATUS_PERMANENT_DELETED;
        if($model->save()){
            Yii::$app->session->setFlash('success', 'Queue deleted successfully.');
            // remove campaign-queue data in asterisk file
            $model->asterisk_write('delete');
            // remove campaign-queue data in queues conf file
            $model->queue_write('delete');
            // remove sound folder
            $model->add_sound_folder('delete');

            // delete call access model
            $accessModel = $model->callAccess;
            if($accessModel){
                $accessModel->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                $accessModel->save();
            }
        }else{
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
        }

        return $this->redirect(['add-queue', 'id' => $id]);
    }

    // ivr details
    public function actionIvr($id)
    {
        $campaign = $this->findModel($id);
        if($campaign){
            $queues = [];
            if($campaign->ivrQueues){
                $queues = $campaign->ivrQueues;
            }
            $model = new VaaniCampaignQueue();

            $prev_is_dtmf = $campaign->is_dtmf;
            $prev_is_ivr_queue = $campaign->is_ivr_queue;

            if(Yii::$app->request->post()){
                $post = Yii::$app->request->post();
                $post_campaign = Yii::$app->request->post()['EdasCampaign'];
                $post_queue = Yii::$app->request->post()['VaaniCampaignQueue'];
                
                if($post_campaign['is_dtmf'] == 1){
                    $campaign->is_dtmf = $post_campaign['is_dtmf'];
                    $campaign->is_ivr_queue = $post_campaign['is_ivr_queue'];

                    if($campaign->is_ivr_queue == 2){
                        $campaign->key_input = $post_campaign['key_input'];
                    }
                    if($campaign->save()){
                        if($campaign->is_ivr_queue == 1){
                            $prev_queues_ids = ArrayHelper::getColumn($queues, 'id');
                            $ids_diff = array_diff($prev_queues_ids,$post_queue['id']);
                            // delete queue data if row is removed
                            if($ids_diff){
                                foreach ($ids_diff as $old_id) {
                                    $oldQueueModel = VaaniCampaignQueue::findOne($old_id);
                                    $oldQueueModel->del_status = VaaniCampaignQueue::STATUS_PERMANENT_DELETED;
                                    if($oldQueueModel->save()){
                                        Yii::$app->session->setFlash('success', 'Queue deleted successfully.');
                                        // remove campaign-queue data in asterisk file
                                        $oldQueueModel->asterisk_write('delete');
                                        // remove campaign-queue data in queues conf file
                                        $oldQueueModel->queue_write('delete');
                                        // remove sound folder
                                        $oldQueueModel->add_sound_folder('delete');
                            
                                        // delete call access model
                                        $accessModel = $oldQueueModel->callAccess;
                                        if($accessModel){
                                            $accessModel->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                                            $accessModel->save();
                                        }
                                    }
                                }
                            }
                            foreach ($post_queue['queue_name'] as $key => $queue) {
                                if($post_queue['id'][$key]){
                                    // edit queue
                                    $queueModel = VaaniCampaignQueue::findOne($post_queue['id'][$key]);
                                    if($queueModel){
                                        $prev_queue = $queueModel->queue;
                                        $prev_dni_id = $queueModel->dni_id;
                                        $prev_criteria = $queueModel->criteria;

                                        if($queueModel->queue_name != $queue){
                                            $queueModel->queue = strtolower($queue) . '_' . $queueModel->campaign->id;
                                        }
                                        $queueModel->queue_name = $queue;
                                        $queueModel->criteria = $post_queue['criteria'][$key];
                                        $queueModel->key_input = $post_queue['key_input'][$key];

                                        if($queueModel->save()){
                                            // edit campaign queue data in asterisk file
                                            $queueModel->asterisk_write('edit', $prev_queue, $prev_dni_id);
                                            // edit campaign queue data in queue conf file
                                            $queueModel->queue_write('edit', $prev_queue, $prev_criteria);
                                            // edit campaign queue sound folder
                                            $queueModel->add_sound_folder('edit', $prev_queue);

                                            // queue access model
                                            $prevCallAccessModel = $queueModel->callAccess;
                                            $is_access_new = false;
                                            
                                            if(!$prevCallAccessModel){
                                                $is_access_new = true;
                                                
                                            }else if($prevCallAccessModel && ($prevCallAccessModel->is_conference != $post['is_conference'] || $prevCallAccessModel->is_transfer != $post['is_transfer'] || $prevCallAccessModel->is_consult != $post['is_consult'] || $prevCallAccessModel->is_manual != $post['is_manual'])){
                                                // delete previous call access model if exists
                                                if($prevCallAccessModel){
                                                    $prevCallAccessModel->updateAttributes(['del_status' => VaaniCallAccess::STATUS_PERMANENT_DELETED]);
                                                }
                                                $is_access_new = true;
                                            }
                                            if($is_access_new){
                                                $accessModel = new VaaniCallAccess();
                                                $accessModel->campaign_id = $queueModel->campaign_id;
                                                $accessModel->queue_id = $queueModel->queue_id;
                                                $accessModel->is_conference = $post_queue['is_conference'][$key];
                                                $accessModel->is_transfer = $post_queue['is_transfer'][$key];
                                                $accessModel->is_consult = $post_queue['is_consult'][$key];
                                                $accessModel->is_manual = $post_queue['is_manual'][$key];
                                                $accessModel->save();
                                            }
                                            Yii::$app->session->setFlash('success', 'Queue Updated successfully!');
                                        }
                                    }
                                }else{
                                    $queueModel = new VaaniCampaignQueue();
                                    $queueModel->campaign_id = $campaign->campaign_id;
                                    $queueModel->queue_name = $queue;
                                    $queueModel->queue_id = User::newID('3','QUE');
                                    $queueModel->queue = strtolower($queue) . '_' . $campaign->id;
                                    $queueModel->criteria = $post_queue['criteria'][$key];
                                    $queueModel->key_input = $post_queue['key_input'][$key];
                                    $queueModel->type = VaaniCampaignQueue::TYPE_IVR;

                                    
                                    // queue access model
                                    $accessModel = new VaaniCallAccess();
                                    $accessModel->campaign_id = $queueModel->campaign_id;
                                    $accessModel->queue_id = $queueModel->queue_id;
                                    $accessModel->is_conference = $post_queue['is_conference'][$key];
                                    $accessModel->is_transfer = $post_queue['is_transfer'][$key];
                                    $accessModel->is_consult = $post_queue['is_consult'][$key];
                                    $accessModel->is_manual = $post_queue['is_manual'][$key];
                                    
                                    if($queueModel->save()){
                                        $accessModel->save();
                                        // insert default role access for campaign's queue
                                        VaaniRoleMaster::addDefaultRoleAccess($campaign->client_id, $campaign->campaign_id, $queueModel->queue_id);
                                        // add campaign queue data in asterisk file
                                        $queueModel->asterisk_write('add');
                                        // add campaign queue data in queue conf file
                                        $queueModel->queue_write('add');
                                        // create campaign queue sound folder
                                        $queueModel->add_sound_folder('add');
                                    }else{
                                        foreach($queueModel->errors as $error){
                                            Yii::$app->session->setFlash('error', json_encode($error));
                                        }
                                    }
                                    if($key == count($post_queue['queue_name'])){
                                        Yii::$app->session->setFlash('success', 'Queue added successfully!');
                                        return $this->redirect(['index']);
                                    }
                                }
                            }
                        }else{
                            if($prev_is_ivr_queue != $campaign->is_ivr_queue){
                                foreach ($queues as $k => $ivr_queue) {
                                    $ivr_queue->del_status = VaaniCampaignQueue::STATUS_PERMANENT_DELETED;
                                    if($ivr_queue->save()){
                                        Yii::$app->session->setFlash('success', 'Queue deleted successfully.');
                                        // remove campaign-queue data in asterisk file
                                        $ivr_queue->asterisk_write('delete');
                                        // remove campaign-queue data in queues conf file
                                        $ivr_queue->queue_write('delete');
                                        // remove sound folder
                                        $ivr_queue->add_sound_folder('delete');
                            
                                        // delete call access model
                                        $accessModel = $ivr_queue->callAccess;
                                        if($accessModel){
                                            $accessModel->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                                            $accessModel->save();
                                        }
                                    }
                                }
                            }
                            return $this->redirect(['campaign-access', 'id' => $campaign->id]);
                        }
                    }
                }else{
                    $campaign->is_dtmf = $post_campaign['is_dtmf'];
                    $campaign->is_ivr_queue = $post_campaign['is_ivr_queue'];
                    $campaign->key_input = $post_campaign['key_input'];
                    
                    if($campaign->save()){
                        // delete queues & call access if exists
                        if($prev_is_dtmf != $campaign->is_dtmf){
                            foreach ($queues as $k => $ivr_queue) {
                                $ivr_queue->del_status = VaaniCampaignQueue::STATUS_PERMANENT_DELETED;
                                if($ivr_queue->save()){
                                    Yii::$app->session->setFlash('success', 'Queue deleted successfully.');
                                    // remove campaign-queue data in asterisk file
                                    $ivr_queue->asterisk_write('delete');
                                    // remove campaign-queue data in queues conf file
                                    $ivr_queue->queue_write('delete');
                                    // remove sound folder
                                    $ivr_queue->add_sound_folder('delete');
                        
                                    // delete call access model
                                    $accessModel = $ivr_queue->callAccess;
                                    if($accessModel){
                                        $accessModel->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                                        $accessModel->save();
                                    }
                                }
                            }
                        }
                        return $this->redirect(['campaign-access', 'id' => $campaign->id]);
                    }
                }
            }
            
            $criterias = EdasCampaign::$campaign_criterias;
            $access_values = VaaniCallAccess::$access_values;

            return $this->render('ivr', [
                'model' => $model,
                'campaign' => $campaign,
                'queues' => $queues,
                'criterias' => $criterias,
                'access_values' => $access_values,
            ]);
        }
        throw new NotFoundHttpException('Campaign does not exist.');
    }

    // set campaign access
    public function actionCampaignAccess($id)
    {
        $campaign = $this->findModel($id);
        if($campaign){
            $model = VaaniCallAccess::find()->where(['campaign_id' => $campaign->campaign_id])->andWhere(['del_status' => VaaniCallAccess::STATUS_NOT_DELETED])->one();
            $is_edit = false;
            if($model){
                $is_edit = true;
                $prevModel = $model;
            }else{
                $model = new VaaniCallAccess();
                $model->campaign_id = $campaign->campaign_id;
            }
            
            if ($this->request->isPost) {
                if($is_edit){
                    if($prevModel && ($prevModel->is_conference != $model->is_conference || $prevModel->is_transfer != $model->is_transfer || $prevModel->is_consult != $model->is_consult || $prevModel->is_manual != $model->is_manual)){
                        $model = new VaaniCallAccess();
                        $model->campaign_id = $campaign->campaign_id;
                        // delete previous call access model if exists
                        $prevModel->updateAttributes(['del_status' => VaaniCallAccess::STATUS_PERMANENT_DELETED]);
                    }
                }
                if($model->load($this->request->post()) && $model->save()){
                    // add campaign data in asterisk file
                    $campaign->asterisk_write('add');
                    // add campaign data in queue conf file
                    $campaign->queue_write('add');
                    // create sound folder
                    $campaign->add_sound_folder('add');
                    Yii::$app->session->setFlash('success', 'Campaign configured successfully.');
                    return $this->redirect(['index']);
                }
            }
            $access_values = VaaniCallAccess::$access_values;
            return $this->render('campaign-access', [
                'model' => $model,
                'campaign' => $campaign,
                'access_values' => $access_values,
            ]);
        }
        throw new NotFoundHttpException('Campaign does not exist.');
    }

    // fetch queues for hold music
    public function actionGetQueuesMusic()
    {
        $campaign_id = Yii::$app->request->get()['campaign_id'];
        
        if($campaign_id){
            $campaign = EdasCampaign::find()->where(['campaign_id' => $campaign_id])->one();
            if($campaign){
                $queues = $campaign->allQueues;
                
                /* $data = ArrayHelper::toArray($queues, [
                    'common\models\VaaniCampaignQueue' => [
                        'queue_id',
                        'queue_name',
                        'hold_music',
                    ]
                ]); */

                return $this->renderPartial('_queue_music_modal', [
                    'queues' => $queues
                ]);
            }
        }
    }

    public function actionUploadHoldMusic()
    {
        $post = Yii::$app->request->post();
        $model = new VaaniCampaignQueue();
        
        if($post && $model->load($this->request->post())){
            echo "<pre>";print_r($model);exit;
            foreach ($model->hold_music_file as $queue_id => $file) {
                $queueModel = VaaniCampaignQueue::find()->where(['queue_id' => $queue_id])->one();
                // echo "<pre>";print_r(UploadedFile::getInstanceByName($file));exit;
                if($queueModel){
                    $queueModel->hold_music_file = $file;
                    $queueModel->hold_music_file = UploadedFile::getInstance($queueModel, 'hold_music_file');
                    // echo "<pre>";print_r($queueModel->hold_music_file);exit;
                    echo "<pre>";print_r(UploadedFile::getInstanceByName('hold_music_file'));exit;

                    if($queueModel->hold_music_file){
                        $queueModel->hold_music = $queueModel->hold_music_file->name . '_' . date('Ymd_His') . '.' . $queueModel->hold_music_file->extension;
                        
                        $queueModel->hold_music_file->saveAs(Yii::$app->basePath . '/web/uploads/hold_music/'. $queueModel->queue_name . '/' . $queueModel->hold_music);
                    }
                }
            }
        }
    }

    // FETCH PLAN DISPOSITIONS
    public function actionGetDispositions()
    {
        $plan_id = Yii::$app->request->get('plan_id');
        $campaign_id = Yii::$app->request->get('campaign_id');

        if($plan_id){
            $plan_id = User::decrypt_data($plan_id);
            $plan = VaaniDispositionPlan::find()->where(['plan_id' => $plan_id])->one();
            
            if($plan){
                $campaign_id = User::decrypt_data($campaign_id);
                $camp_dispositions = VaaniCampDispositions::find()->where(['campaign_id' => $campaign_id])->asArray()->all();

                if($camp_dispositions){
                    $camp_dispositions = ArrayHelper::index($camp_dispositions, 'disposition_id');
                }
                
                return $this->renderPartial('_dispo_list', [
                    'campaign_id' => $campaign_id,
                    'camp_dispositions' => $camp_dispositions,
                    'dispositions' => $plan->dispositions
                ]);
            }
        }
    }

    // fetch queue's camp type
    public function actionGetCampType()
    {
        $queue_id = Yii::$app->request->get()['queue_id'];

        if($queue_id){
            $queue = VaaniCampaignQueue::find()->where(['queue_id' => $queue_id])->one();
            if($queue && $queue->campaign){
                return $queue->campaign->campaign_type;
            }
        }
    }

    public function actionTest()
    {
        return $this->render('test');
    }

    // 
    public function actionKpPreview()
    {
        $id =  Yii::$app->request->post('id');
        // $id = User::decrypt_data($kp_template_id);
           //prevewing file on click of tab
           $model = new vaani_kp_templete();
           $getTabList = VaaniKpTab::find()->select(['id','tab_name','file'])->Where(['del_status' => User::STATUS_NOT_DELETED])->andWhere(['templete_id' => $id])->OrderBy('sequence')->asArray()->all();
        //    echo"<pre>";print_r($id);exit;
           $tabs = ArrayHelper::map($getTabList , 'id', 'tab_name','file');
           
           $tab_files = ArrayHelper::map($getTabList , 'id', 'file');
   
           $temp_name = vaani_kp_templete::find()->select(['template_name'])->where(['del_status' => User::STATUS_NOT_DELETED,'templete_id' => $id])->one();
   
           $files = VaaniKpTab::find()->select(['id','file'])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->OrderBy('sequence')->all();
   
           include_once __DIR__.'/SimpleXLSX.php';
   
   
           $tblData ='';
           $test = [];
           foreach ($files as $file) {
   
                   $ext = pathinfo( $file['file'], PATHINFO_EXTENSION);
                   
                   $filepath = Yii::$app->basePath . '/web/uploads/knowledge_portal/client/'.$file->file;
   
                   $fileid =$file['id'];
   
                   if ($ext == 'xlsx') {
   
                           if ( $xlsx = SimpleXLSX::parse($filepath) ) {
                           $tblData = '<table id="excel_table" class="table_excel" border="1" cellpadding="3" style="border-collapse: collapse">';
                           $tblData .= '<thead>';
                           $excelRows = $xlsx->rows(); 
                           foreach( $excelRows as $r ) {
                               if ( $r === reset( $excelRows ) ) {
                                   $tblData .= '<tr><th>'.implode('</th><th>', $r ).'</th></tr></thead><tbody>';
                               }   
                               $tblData .= '<tr><td>'.implode('</td><td>', $r ).'</td></tr>';
                           }
                           $tblData .= '</tbody></table>';
                       } else {
                           echo SimpleXLSX::parseError();
                       }
                       $test[$fileid] = $tblData;
                   }
                   else{
   
                       $tblData  = '';
                       $test[$fileid] = $tblData;
                       }
           }
            // return json_encode(array($tabs, $id,$temp_name,$file,$getTabList,$tblData));
   
           return $this->renderPartial('_previewtabs', [
               'model' => $model,
               'tabs' =>  $tabs,
               'id' => $id,
               'temp_name' => $temp_name,
               'file' => $file,
               'getTabList' => $getTabList,
               'tblData' => $test,
            
           ]);
       
    }
}
