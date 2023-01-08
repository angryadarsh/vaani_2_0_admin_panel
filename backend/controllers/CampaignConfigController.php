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
use yii\helpers\Json;
use common\models\VaaniCallTimes;
use common\models\VaaniUserAccess;
use common\models\VaaniQueueWaitCount;
use common\models\vaani_kp_templete;
use common\models\VaaniKpTab;
use Shuchkin\SimpleXLSX;

class CampaignConfigController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionLocalStorageData()
    {
        $user = Yii::$app->user->identity;
        $data = Yii::$app->request->post('campaign_data');
        $assosiative_array = json_decode(($data),true);
        // echo"<pre>";print_r($assosiative_array);exit;
        // calling  campaign model 
        $model = new EdasCampaign();
        $model->campaign_id = User::newID('3','CAM');
        $model->client_id = ((isset($_SESSION['client_connection']) && $_SESSION['client_connection']) ? $_SESSION['client_connection'] : null);
        $model->campaign_name = $assosiative_array['campaign_name'];
        $model->campaign_type = $assosiative_array['campaign_type'];
        $model->call_medium = $assosiative_array['call_medium'];
        $model->campaign_status = $assosiative_array['campaign_status'];
        $model->campaign_sticky_agent = '0';
        $model->service_level_seconds = '0';
        $model->del_status = '1';
        $model->call_window = $assosiative_array['call_window'];
        $model->call_timeout = $assosiative_array['campaign_call_timeout'];
        $model->disposition_plan_id = $assosiative_array['disposition_plan'];
        $model->operator_id = $assosiative_array['campaign_operators'];
        $model->qms_id = $assosiative_array['qms_id'];

        if ($model->campaign_type== EdasCampaign::TYPE_OUTBOUND) {
            $model->campaign_sub_type = $assosiative_array['campaign_sub_type'];
            $model->pacing_value = $assosiative_array['pacing_ratio'];
            $model->abandoned_percent = $assosiative_array['abandoned_percent']; 
            $model->preview_time = $assosiative_array['time_preview'];
            $model->outbound_criteria = $assosiative_array['outbound_criteria'];
        }

         // campaign call access model
        $is_access_edit = false;
        // $access_updated = false;
        $access_updated = false;
        $campaignAccessModel = new VaaniCallAccess();
        $campaignAccessModel->campaign_id = $model->campaign_id;
        $campaignAccessModel->is_conference=$assosiative_array['conference'];
        $campaignAccessModel->is_transfer=$assosiative_array['blind_transfer'];
        $campaignAccessModel->is_consult=$assosiative_array['consultation'];
        $campaignAccessModel->is_manual=$assosiative_array['manual_call'];
        
        
        // queue new model
        // $queueModel = new VaaniCampaignQueue();
        // $queueModel->campaign_id = $model->campaign_id;

        if ($model->save()) {
            
            Yii::$app->session->setFlash('success', 'Campaign added successfully!');
            //setting disposition configuration ....
            foreach ($assosiative_array['set_dispositions_configurations'] as $key => $dispo) {
            
                        $dispo_model = new VaaniCampDispositions();
                        
                        $dispo_model->disposition_id = $dispo['disposition_id'];
                        $dispo_model->campaign_id = $model->campaign_id;
                        $dispo_model->max_retry_count = $dispo['max_retry_count'];
                        $dispo_model->retry_delay = $dispo['retry_delay'];
                        if($dispo_model->save()){
                            Yii::$app->session->setFlash('success', 'Campaign Disposition added successfully.');
                        }else{
                            Yii::$app->session->setFlash('error', 'Something went wrong while adding the disposition.');
                        }
                    }       
            //  campaign call accesss 
            $campaignAccessModel->campaign_id = $model->campaign_id;
            if($campaignAccessModel->save()){
                    $access_updated = true;
            }


            // astrix
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

             // queue model
             if($model->campaign_type == EdasCampaign::TYPE_INBOUND){
                if($model->call_medium == EdasCampaign::MEDIUM_QUEUE){
                    $queue_idd = array();
                    $queue_namess = array();
                    foreach ($assosiative_array['queues'] as $key => $value) {
                        $queueModel = new VaaniCampaignQueue();
                        $queueModel->campaign_id = $model->campaign_id;
                        $queueModel->queue_name = $value['add_skill'];
                        $queueModel->queue_id = User::newID('3','QUE');
                        $queueModel->queue = strtolower($value['add_skill']) . '_' . $model->id;
                        $queueModel->criteria = $value['criteria'];
                        $queueModel->dni_id = $value['dni'];
                        $queueModel->call_window = $value['call_window'];
                        $queueModel->call_timeout = $value['call_timeout'];
                        $queueModel->type = VaaniCampaignQueue::TYPE_QUEUE;
                        array_push($queue_idd,$queueModel->queue_id);
                        array_push($queue_namess,$queueModel->queue_name);
                        // Queue wait count 
                        $queuewaitcountModel = new VaaniQueueWaitCount();
                        $queuewaitcountModel->campaign_name = $model->campaign_name;
                        $queuewaitcountModel->queue_name = $model->queue_name;
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
                            $queuewaitcountModel->save();
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
            // Assigned Users
            if ($assosiative_array['assigned_agent_list']) {
              foreach ($assosiative_array['assigned_agent_list'] as $key => $value) {
                    $k = array_search($value['assigned_agent_skill_name'], $queue_namess);
                    $user_access_model = new VaaniUserAccess();
                    $user_access_model->user_id = $value['assigned_agent_id'];
                    $user_access_model->role_id = 'ROL5520211101094927719720';
                    $user_access_model->client_id = $model->client_id;
                    $user_access_model->campaign_id = $model->campaign_id;
                    $user_access_model->queue_id = trim($queue_idd[$k],'"');
                    $user_access_model->priority = VaaniUserAccess::PRIORITY_DEFAULT;
                    $user_access_model->access_level = VaaniUserAccess::LEVEL_QUEUE;
                    $user_access_model->is_active = $value['assigned_agent_active_status'];
                    if($user_access_model->save()){
                        // add user-queue data in queue conf file
                        $queue_agent_data = 'Action - add , Queue - ' . $value['assigned_agent_skill_name'] . ', priority - ' . $user_access_model->priority;
                        $log = Yii::$app->Utility->addLog($queue_agent_data, 'add_queue_file');
                        $userModel = new User();
                        $userModel->queue_write('add', $value['assigned_agent_skill_name'], $user_access_model->priority);
                    }else{
                        foreach($user_access_model->errors as $error){
                            Yii::$app->session->setFlash('error', json_encode($error));
                        }
                    }
                }

            }
            // Unassigned Users
            if (isset($assosiative_array['unassigned_agent_list'])) {
                foreach ($assosiative_array['unassigned_agent_list'] as $key => $value) {
                      $k = array_search($value['unassigned_agent_skill_name'], $queue_namess);
                      $user_access_model = new VaaniUserAccess();
                      $user_access_model->user_id = $value['unassigned_agent_id'];
                      $user_access_model->role_id = 'ROL5520211101094927719720';
                      $user_access_model->client_id = $model->client_id;
                      $user_access_model->campaign_id = $model->campaign_id;
                      $user_access_model->queue_id = trim($queue_idd[$k],'"');
                      $user_access_model->priority = VaaniUserAccess::PRIORITY_DEFAULT;
                      $user_access_model->access_level = VaaniUserAccess::LEVEL_QUEUE;
                      $user_access_model->is_active =  $value['unassigned_agent_active_status'];
                      if($user_access_model->save()){
                          // add user-queue data in queue conf file
                          $queue_agent_data = 'Action - add , Queue - ' . $value['unassigned_agent_skill_name'] . ', priority - ' . $user_access_model->priority;
                          $log = Yii::$app->Utility->addLog($queue_agent_data, 'add_queue_file');
                          $userModel = new User();
                          $userModel->queue_write('add', $value['unassigned_agent_skill_name'], $user_access_model->priority);
                      }else{
                          foreach($user_access_model->errors as $error){
                              Yii::$app->session->setFlash('error', json_encode($error));
                          }
                      }
                  }
  
              }
            // return json_encode((trim($queue_idd[$k],'"')),true);
            // 
            $campaign_name = strtolower(trim($model->campaign_name));
            $campaign_name = str_replace(" ", "_", $campaign_name);

            $campaignActiveQueues = new VaaniCampaignActiveQueues();
            $campaignActiveQueues->campaign = $campaign_name;
            $campaignActiveQueues->save();

        }else{
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
        }
        
        
           
    } 
    //fetch call run time 
    public function actionCallRunTime()
    {
       $config_id =  Yii::$app->request->post('config_id');
    //    echo"<pre>";print_r($config_id);
        $times = VaaniCallTimes::find()->where(['config_id' => $config_id, 'del_status' => 1])->asArray()->all();
        return json_encode(($times),true);
        
    }
    // fetch queues data 
    public function actionGetQueueData()
    {
        $queue_id =  Yii::$app->request->post('queue_id');
        $queues_data = VaaniCampaignQueue::find()->select(['id','dni_id','call_window','criteria','call_timeout'])->where(['queue_id' => $queue_id, 'del_status' => User::STATUS_NOT_DELETED])->asArray()->one();
        
        return json_encode($queues_data);
        
    }
    
    // fetch queue users 
    public function actionAssignUsers()
    {
        $queue_ids = Yii::$app->request->get('queue_ids');
        $client_id = Yii::$app->request->get('client_id');
        $users = User::find()
        ->joinWith('userAccess')
        ->joinWith('supervisor')
        ->where(['vaani_user.del_status' => User::STATUS_NOT_DELETED, '`vaani_user_access`.`client_id`' => $client_id])
        ->andWhere(['not',['`vaani_user_access`.`queue_id`' => $queue_ids]])
        ->andWhere(['vaani_user.role_id' => 'ROL5520211101094927719720'])
        ->select('vaani_user.user_id, vaani_user.user_name, manager_id, supervisor_id ,queue_id')
        ->asArray()
        ->groupBy('vaani_user.user_id')
        ->all(); 
        return json_encode(($users),true);
        
    }
    //file upload
    public function actionHoldMusic(){
        $fd = Yii::$app->request->post('fd');
        $filename = $_FILES['file']['name'];
        $location = Yii::$app->basePath . '/web/uploads/hold_music/' . $filename;
        move_uploaded_file($_FILES['file']['tmp_name'], $location);

    }
    // 
    public function actionLocalStorageUpdateData()
    {
        // echo"hello";exit;
        $user = Yii::$app->user->identity;
        $data = Yii::$app->request->post('campaign_data');
        $model_id = Yii::$app->request->post('model_id');
        $fd = Yii::$app->request->post('fd');
        $queues_data = Yii::$app->request->post('queues');
        $update_data = json_decode(($data),true);
        // $queues_data =  json_decode(($queues),true);
        
        $model = EdasCampaign::find()->where(['id'=> $model_id])->one();
        
        $prev_campaign_name = $model->campaign_name;
        $prev_dni_id = $model->campaign_dni;
        $prev_criteria = $model->campaign_inbound_agent_selection_criteria;
        $prev_camp = $model;
        $prev_camp_type = $model->campaign_type;
        $model->prev_type = $prev_camp_type;
        
        $prev_call_medium = $model->call_medium;
        $prev_call_timeout = $model->call_timeout;
        if($model) {
                $model->campaign_name = $update_data['campaign_name'];
                $model->campaign_type = $update_data['campaign_type'];
                $model->call_medium = $update_data['call_medium'];
                $model->campaign_status = $update_data['campaign_status'];
                $model->campaign_sticky_agent = '0';
                $model->service_level_seconds = '0';
                $model->del_status = '1';
                $model->call_window = $update_data['call_window'];
                $model->call_timeout = $update_data['campaign_call_timeout'];
                $model->disposition_plan_id = isset($update_data['disposition_plan']) ? $update_data['disposition_plan'] : null;
                $model->operator_id = $update_data['campaign_operators'];
                $model->qms_id = $update_data['qms_id'];
            
                if ($model->campaign_type== EdasCampaign::TYPE_OUTBOUND) {
                        $model->campaign_sub_type = $update_data['campaign_sub_type'];
                        $model->pacing_value = $update_data['pacing_ratio'];
                        $model->abandoned_percent = $update_data['abandoned_percent']; 
                        $model->preview_time = $update_data['time_preview'];
                        $model->outbound_criteria = $update_data['outbound_criteria'];
                }
              
              
                // campaign queues
                $queues = [];
                if($queues_data){
                    $model_queues = [];
                    foreach($queues_data as $queue_id) {
                        $modelQueues = VaaniCampaignQueue::findOne($queue_id);
                        array_push($model_queues,$modelQueues);
                    }
                    $queues = (($model->call_medium == EdasCampaign::MEDIUM_QUEUE) ? $model_queues : null);
                    // echo"<pre>";print_r($queues);    
        
                }
            }
            // echo"<pre>";print_r($model->campaign_id);exit;
        if ($model->save()) {
           
            Yii::$app->session->setFlash('success', 'Campaign updated successfully!');
            if (isset($update_data['set_dispositions_configurations'])) {
                foreach ($update_data['set_dispositions_configurations'] as $key => $dispo) {
                    $dispo_model = VaaniCampDispositions::find()->where(['disposition_id' => $dispo['disposition_id']])->andWhere(['del_status' => VaaniDispositions::STATUS_NOT_DELETED])->one();
                    if(!$dispo_model){
                        $dispo_model = new VaaniCampDispositions();
                    }
                    $dispo_model->disposition_id = $dispo['disposition_id'];
                    $dispo_model->campaign_id = $model->campaign_id;
                    $dispo_model->max_retry_count = $dispo['max_retry_count'];
                    $dispo_model->retry_delay = $dispo['retry_delay'];
                    if($dispo_model->save()){
                        Yii::$app->session->setFlash('success', 'Campaign Disposition updated successfully.');
                    }else{
                        Yii::$app->session->setFlash('error', 'Something went wrong while updating the disposition.');
                    }
                }
            }
            // campaign call access 
            if($model->callAccess){
                $is_access_edit = true;
                // $model_call_access = VaaniCallAccess::find()->where(['del_status' => User::STATUS_NOT_DELETED, 'id' => $call_access])->one();
                if (isset($update_data['conference']) ||  isset($update_data['blind_transfer']) ||  isset($update_data['consultation']) ||  isset($update_data['manual_call'])) {
                    # code...
                    $campaignAccessModel = $model->callAccess;
                    $prev_camp_conference =  $model->callAccess->is_conference;
                    $prev_camp_transfer =  $model->callAccess->is_transfer;
                    $prev_camp_manual = $model->callAccess->is_manual;
                    $prev_camp_consult =  $model->callAccess->is_consult;
                    $campaignAccessModel->is_conference = $update_data['conference'];
                    $campaignAccessModel->is_transfer = $update_data['blind_transfer'];
                    $campaignAccessModel->is_consult = $update_data['consultation'];
                    $campaignAccessModel->is_manual = $update_data['manual_call'];
                    // echo"<pre>"; print_r($campaignAccessModel);exit;
                }else{
                    // echo "hey";exit;
                    $campaignAccessModel = $model->callAccess;
                    $prev_camp_conference =  $model->callAccess->is_conference;
                    $prev_camp_transfer =  $model->callAccess->is_transfer;
                    $prev_camp_manual = $model->callAccess->is_manual;
                    $prev_camp_consult =  $model->callAccess->is_consult;
                }
                // echo"<pre>";echo($prev_camp_consult.'+'.$campaignAccessModel->is_transfer);exit;
                if(isset($prev_camp_conference)){
                    if(($prev_camp_conference != $campaignAccessModel->is_conference || $prev_camp_transfer != $campaignAccessModel->is_transfer || $prev_camp_consult != $campaignAccessModel->is_consult || $prev_camp_manual != $campaignAccessModel->is_manual)){
                        // echo"<pre>"; print_r($model->campaign_id);exit;

                        $new_campaignAccessModel = new VaaniCallAccess();
                        $new_campaignAccessModel->campaign_id = $model->campaign_id;
                        $new_campaignAccessModel->is_conference = $campaignAccessModel->is_conference;
                        $new_campaignAccessModel->is_transfer = $campaignAccessModel->is_transfer;
                        $new_campaignAccessModel->is_consult = $campaignAccessModel->is_consult;
                        $new_campaignAccessModel->is_manual = $campaignAccessModel->is_manual;
                        // echo "<pre>";print_r($new_campaignAccessModel);exit;
                        if($new_campaignAccessModel->save()){
                            // echo"<pre>";print_r($new_campaignAccessModel);exit;
                            $access_updated = true;
                            // delete previous call access model if exists
                            $campaignAccessModel->updateAttributes(['del_status' => VaaniCallAccess::STATUS_PERMANENT_DELETED]);
                            
                            $campaignAccessModel = $new_campaignAccessModel;
                        }else{
                            foreach($new_campaignAccessModel->errors as $error){
                                Yii::$app->session->setFlash('error', json_encode($error));
                            }
                        }
                    }else{
                        $access_updated = false;
                    }
                }
            }
             // edit campaign data in asterisk file
            $model->asterisk_write('edit', $prev_campaign_name, $prev_dni_id);
             // edit campaign data in queues conf file
            $model->queue_write('edit', $prev_campaign_name, $prev_criteria);
             // edit sound folder
            $model->add_sound_folder('edit', $prev_campaign_name);
             
            Yii::$app->session->setFlash('success', 'Campaign updated successfully.');

            if($model->campaign_type == EdasCampaign::TYPE_INBOUND){
                if($model->call_medium == EdasCampaign::MEDIUM_QUEUE){
                    if (isset($update_data['queues'])) {
                        // # code...
                        $post_queue = $update_data['queues'];
                        $queue_ids_array = array();
                        if (isset($value['id'])) {
                             foreach ($post_queue as $key => $value) {
                                # code...
                                array_push($queue_ids_array,$value['id']);
                                // echo "<pre>";print_r($queue_ids_array);exit;
                                // array_key_exists("Volvo",$a)
                                $prev_queues_ids = ArrayHelper::getColumn($queues, 'id');
                                // echo"<pre>";print_r($post_queue['call_timeout']);exit;
                                $ids_diff = array_diff($prev_queues_ids,$queue_ids_array);
                            }
                        }
                        if(isset($ids_diff)){
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
                    
                        $queue_idd = array();
                        $queue_namess = array();
                    
                        foreach ($update_data['queues'] as $key => $value) {
                            if(isset($value['id'])){
                                $queueModel = VaaniCampaignQueue::findOne($value['id']);
                                if($queueModel){
                                    $prev_queue = $queueModel->queue;
                                    $prev_dni_id = $queueModel->dni_id;
                                    $prev_criteria = $queueModel->criteria;
                                    $prev_call_timeout = $queueModel->call_timeout;
                                    $prev_call_window = (($queueModel->call_window != $value['call_window']) ? $queueModel->call_window : null );

                                    if($queueModel->queue_name != $value['add_skill']){
                                        $queueModel->queue = strtolower($value['add_skill']) . '_' . $queueModel->campaign->id;
                                    }
                                    $queueModel->queue_name = $value['add_skill'];
                                    $queueModel->criteria = $value['criteria'];
                                    $queueModel->call_timeout = $value['call_timeout'];
                                    $queueModel->dni_id = $value['dni'];
                                    $queueModel->call_window = $value['call_window'];

                                    if($queueModel->save()){
                                        // edit campaign queue data in asterisk file
                                        Yii::$app->queue->push($queueModel->asterisk_write('edit', $prev_queue, $prev_dni_id, $prev_call_window, null, null, $prev_camp, $prev_call_timeout));
                                        // edit campaign queue data in queue conf file
                                        $queueModel->queue_write('edit', $prev_queue, $prev_criteria);
                                        // edit campaign queue sound folder
                                        $queueModel->add_sound_folder('edit', $prev_queue);
                                        // create campaign queue file
                                   

                                        // queue access model
                                        $prevCallAccessModel = $queueModel->callAccess;
                                    
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
                                $queueModel->queue_name = $value['add_skill'];
                                $queueModel->queue_id = User::newID('3','QUE');
                                $queueModel->queue = strtolower($value['add_skill']) . '_' . $model->id;
                                $queueModel->criteria = $value['criteria'];
                                $queueModel->dni_id = $value['dni'];
                                $queueModel->call_window = $value['call_window'];
                                $queueModel->call_timeout = $value['call_timeout'];
                                $queueModel->type = VaaniCampaignQueue::TYPE_QUEUE;
                                array_push($queue_idd,$queueModel->queue_id);
                                array_push($queue_namess,$queueModel->queue_name);
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
                            foreach ($model_queues as $key => $outbound_queue) {
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
            // default queue if no queue is present 
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
            // active users update 
            $queue_data = VaaniCampaignQueue::find()->where(['del_status' => User::STATUS_NOT_DELETED,'campaign_id' => $model->campaign_id])->asArray()->all();
            $queue_names_all = ArrayHelper::getColumn($queue_data, 'queue_name');
            $queue_ids_all = ArrayHelper::getColumn($queue_data, 'queue_id');

            if (isset($update_data['assigned_agent_list'])) {
                foreach ($update_data['assigned_agent_list'] as $key => $value) {
                    
                    $user_access_model_update = VaaniUserAccess::find()->where(['del_status' => User::STATUS_NOT_DELETED, 'user_id' => $value['assigned_agent_id']])->andWhere(['campaign_id' => $model->campaign_id])->one();
                    // echo "<pre>";print_r($user_access_model_update);exit;     

                    if (!$user_access_model_update) {
                            $k = array_search($value['assigned_agent_skill_name'], $queue_namess);
                            if (!$k) {
                                $index = array_search($value['assigned_agent_skill_name'], $queue_names_all);
                            }
                            $user_access_model = new VaaniUserAccess();
                            $user_access_model->user_id = $value['assigned_agent_id'];
                            $user_access_model->role_id = 'ROL5520211101094927719720';
                            $user_access_model->client_id = $model->client_id;
                            $user_access_model->campaign_id = $model->campaign_id;
                            $user_access_model->queue_id = ($queue_idd[$k]) ? trim($queue_idd[$k],'"') : trim($queue_ids_all[$index],'"') ;
                            $user_access_model->priority = VaaniUserAccess::PRIORITY_DEFAULT;
                            $user_access_model->access_level = VaaniUserAccess::LEVEL_QUEUE;
                            $user_access_model->is_active = 1;
                            if($user_access_model->save()){
                                // add user-queue data in queue conf file
                                $queue_agent_data = 'Action - add , Queue - ' . $value['assigned_agent_skill_name'] . ', priority - ' . $user_access_model->priority;
                                $log = Yii::$app->Utility->addLog($queue_agent_data, 'add_queue_file');
                                $userModel = new User();
                                $userModel->queue_write('add', $value['assigned_agent_skill_name'], $user_access_model->priority);
                            }else{
                                foreach($user_access_model->errors as $error){
                                    Yii::$app->session->setFlash('error', json_encode($error));
                                }
                            }
                        
                    }else{
                        $user_access_model_update->is_active = 1;
                        // $user_access_model_update->save();
                        if ($user_access_model_update->save()) {
                            Yii::$app->session->setFlash('success', 'Campaign assigned users updated successfully.');
                        }else{
                            foreach($user_access_model->errors as $error){
                                Yii::$app->session->setFlash('error', json_encode($error));
                            }
                        }
                    }
                  
                }
            }
            // unassigned users
            if (isset($update_data['unassigned_agent_list'])) {
                # code...
                foreach ($update_data['unassigned_agent_list'] as $key => $value) {

                    $user_access_model_update = VaaniUserAccess::find()->where(['del_status' => User::STATUS_NOT_DELETED, 'user_id' => $value['unassigned_agent_id']])->andWhere(['campaign_id' => $model->campaign_id])->one();

                    if (!$user_access_model_update) {
                        $k = array_search($value['unassigned_agent_skill_name'], $queue_namess);
                        if (!$k) {
                            $index = array_search($value['unassigned_agent_skill_name'], $queue_names_all);
                        }
                        $user_access_model = new VaaniUserAccess();
                        $user_access_model->user_id = $value['unassigned_agent_id'];
                        $user_access_model->role_id = 'ROL5520211101094927719720';
                        $user_access_model->client_id = $model->client_id;
                        $user_access_model->campaign_id = $model->campaign_id;
                        $user_access_model->queue_id = ($queue_idd[$k]) ? trim($queue_idd[$k],'"') : trim($queue_ids_all[$index],'"') ;
                        $user_access_model->priority = VaaniUserAccess::PRIORITY_DEFAULT;
                        $user_access_model->access_level = VaaniUserAccess::LEVEL_QUEUE;
                        $user_access_model->is_active = 2;
                        if($user_access_model->save()){
                            // add user-queue data in queue conf file
                            $queue_agent_data = 'Action - add , Queue - ' . $value['unassigned_agent_skill_name'] . ', priority - ' . $user_access_model->priority;
                            $log = Yii::$app->Utility->addLog($queue_agent_data, 'add_queue_file');
                            $userModel = new User();
                            $userModel->queue_write('add', $value['unassigned_agent_skill_name'], $user_access_model->priority);
                        }else{
                            foreach($user_access_model->errors as $error){
                                Yii::$app->session->setFlash('error', json_encode($error));
                            }
                        }
                        
                    }else{
                        $user_access_model_update->is_active = 2 ;
                        // $user_access_model_update->save();
                        if ($user_access_model_update->save()) {
                            Yii::$app->session->setFlash('success', 'Campaign assigned users updated successfully.');
                        }else{
                            foreach($user_access_model_update->errors as $error){
                                Yii::$app->session->setFlash('error', json_encode($error));
                            }
                        }
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

        }else{
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
        }  
    }
    // delete 
    public function actionDelete($id)
    {
        $id = User::decrypt_data($id);
        // $model = $this->findModel($id);
        $model = EdasCampaign::find()->where(['id' => $id,'del_status' => EdasCampaign::STATUS_NOT_DELETED])->one();
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

        return $this->redirect(['campaign/index']);
    }

    // get kp preivew values 
    // fetch queues data 
    // public function actionKpPreview()
    // {
    //     $id =  Yii::$app->request->post('id');
    //     // $id = User::decrypt_data($kp_template_id);
    //        //prevewing file on click of tab
    //        $model = new vaani_kp_templete();
    //        $getTabList = VaaniKpTab::find()->select(['id','tab_name','file'])->Where(['del_status' => User::STATUS_NOT_DELETED])->andWhere(['templete_id' => $id])->OrderBy('sequence')->asArray()->all();
    //     //    echo"<pre>";print_r($id);exit;
    //        $tabs = ArrayHelper::map($getTabList , 'id', 'tab_name','file');
           
    //        $tab_files = ArrayHelper::map($getTabList , 'id', 'file');
   
    //        $temp_name = vaani_kp_templete::find()->select(['template_name'])->where(['del_status' => User::STATUS_NOT_DELETED,'templete_id' => $id])->one();
   
    //        $files = VaaniKpTab::find()->select(['id','file'])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->OrderBy('sequence')->all();
   
    //        include_once __DIR__.'/SimpleXLSX.php';
   
   
    //        $tblData ='';
    //        $test = [];
    //        foreach ($files as $file) {
   
    //                $ext = pathinfo( $file['file'], PATHINFO_EXTENSION);
                   
    //                $filepath = Yii::$app->basePath . '/web/uploads/knowledge_portal/client/'.$file->file;
   
    //                $fileid =$file['id'];
   
    //                if ($ext == 'xlsx') {
   
    //                        if ( $xlsx = SimpleXLSX::parse($filepath) ) {
    //                        $tblData = '<table id="excel_table" class="table_excel" border="1" cellpadding="3" style="border-collapse: collapse">';
    //                        $tblData .= '<thead>';
    //                        $excelRows = $xlsx->rows(); 
    //                        foreach( $excelRows as $r ) {
    //                            if ( $r === reset( $excelRows ) ) {
    //                                $tblData .= '<tr><th>'.implode('</th><th>', $r ).'</th></tr></thead><tbody>';
    //                            }   
    //                            $tblData .= '<tr><td>'.implode('</td><td>', $r ).'</td></tr>';
    //                        }
    //                        $tblData .= '</tbody></table>';
    //                    } else {
    //                        echo SimpleXLSX::parseError();
    //                    }
    //                    $test[$fileid] = $tblData;
    //                }
    //                else{
   
    //                    $tblData  = '';
    //                    $test[$fileid] = $tblData;
    //                    }
    //        }
    //         // return json_encode(array($tabs, $id,$temp_name,$file,$getTabList,$tblData));
   
    //        return $this->render('/campaign/_previewtabs', [
    //            'model' => $model,
    //            'tabs' =>  $tabs,
    //            'id' => $id,
    //            'temp_name' => $temp_name,
    //            'file' => $file,
    //            'getTabList' => $getTabList,
    //            'tblData' => $test,
            
    //        ]);
       
    // }
}

    


