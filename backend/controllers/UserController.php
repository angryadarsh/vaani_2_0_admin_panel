<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\search\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\VaaniClientMaster;
use common\models\EdasCampaign;
use common\models\VaaniRoleMaster;
use common\models\VaaniRole;
use common\models\VaaniUserAccess;
use yii\helpers\ArrayHelper;
use mdm\admin\models\Assignment;
use common\models\VaaniUserSupervisor;
use common\models\VaaniCallAccess;
use common\models\VaaniCampaignQueue;
use common\models\VaaniUserActiveQueues;
use common\models\VaaniOperators;
use common\models\VaaniClientOperators;
use common\models\VaaniUserOperator;
use yii\web\Response;
use yii\widgets\ActiveForm;
use phpseclib3\Net\SFTP;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $searchModel = new UserSearch();
        $searchModel->is_active = User::STATUS_ACTIVE;
        $searchModel->del_status = User::STATUS_NOT_DELETED;
        if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
            $searchModel->client_id = $user->clientIds;
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
     * Displays a single User model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->redirect(['update', 'id' => $this->findModel($id)]);
    }

    public function actionTest()
    {
        echo Yii::$app->params['remo_sip_url'];exit;
        // configration file of remote server
        $sftp = new SFTP(Yii::$app->params['IP_ADDRESS']);
        if (!$sftp->login(Yii::$app->params['USER_ID'], Yii::$app->params['PASSWORD'])) {
            exit('Login Failed');
        }

        $myFile = $_SERVER['DOCUMENT_ROOT']  . '/edas_vaani_dev/backend/web/files/sip_temp.txt';
        $lines = file($myFile);

        $tmp_context = '[11111111]'.PHP_EOL;
        $tmp_context .= 'defaultuser=11111111'.PHP_EOL;
        $tmp_context .= 'secret=11111111'.PHP_EOL;
        $tmp_context .= 'accountcode=11111111'.PHP_EOL;
        $tmp_context .= 'callerid="11111111" <11111111>'.PHP_EOL;
        $tmp_context .= 'mailbox=11111111'.PHP_EOL;
        $tmp_context .= 'allow=ulaw,alaw'.PHP_EOL;
        $tmp_context .= 'context=default'.PHP_EOL;
        $tmp_context .= 'type=friend'.PHP_EOL;
        $tmp_context .= 'host=dynamic'.PHP_EOL;
        $tmp_context .= ';user_sip_end'.PHP_EOL;

        $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
        $last = sizeof($lines) - 1 ; 
        unset($lines[$last]);
        foreach ($lines as $key => $value) 
        {
            fputs($writing,$value);
        }
        fputs($writing,''.PHP_EOL); 				
        fputs($writing,$tmp_context); 				
        fclose($writing);
        $res = $sftp->put(Yii::$app->params['remo_sip_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);

        User::reload_call();
        echo "complete";
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create_update';
        $model->client = (isset($_SESSION['client_connection'])?$_SESSION['client_connection']:''); // Ashish:01-jun-2022

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $is_error = false;
                // check for unique user id
                $prev_users = User::userList();
                if(isset($prev_users) && !empty($prev_users)){
                    $prev_user_ids = ArrayHelper::getColumn($prev_users, function ($element) {
                        return strtolower($element['user_id']);
                    });
                    
                    if(in_array(strtolower($model->user_id), $prev_user_ids)){
                        Yii::$app->session->setFlash('error', 'User Id is already been taken.');
                        $is_error = true;
                    }
                }
    
                if(!$is_error){
                    $model->user_password = User::encrypt_data($model->user_password);
                    $model->role_id = $model->role;
                    $model->is_two_leg = $model->is_two_leg ? $model->is_two_leg : User::TWO_LEG_INACTIVE;
                    $model->web_rtc = $model->web_rtc ? $model->web_rtc : User::WEB_RTC_INACTIVE;

                    // set service key
                    $client_model = VaaniClientMaster::find()->where(['client_id' => $model->client])->one();
                    if($client_model){
                        $model->service_key = $client_model->service_key;
                    }
                    
                    if($model->save()){
                        // provide yii2-admin role access
                        $assignmentModel = new Assignment($model->id);
                        $success = $assignmentModel->assign([$model->userRole->role_name]);
                    
                        if($model->client || $model->campaigns || $model->queues){
                            $user_access_model = new VaaniUserAccess();
                            $user_access_model->user_id = $model->user_id;
                            $user_access_model->role_id = $model->role;
                            $user_access_model->client_id = $model->client;
                            $user_access_model->access_level = VaaniUserAccess::LEVEL_CLIENT;
                            
                            $queue_names = [];
                            if($model->queues){
                                foreach($model->queues as $queue_id){
                                    $queue = VaaniCampaignQueue::find()->where(['queue_id' => $queue_id])->one();
                                    if($queue){
                                        $queue_names[] = $queue->queue;

                                        $user_access_model = new VaaniUserAccess();
                                        $user_access_model->user_id = $model->user_id;
                                        $user_access_model->role_id = $model->role;
                                        $user_access_model->client_id = $model->client;
                                        $user_access_model->campaign_id = $queue->campaign_id;
                                        $user_access_model->queue_id = $queue_id;
                                        $user_access_model->priority = (($model->priority && isset($model->priority[$queue_id])) ? $model->priority[$queue_id] : VaaniUserAccess::PRIORITY_DEFAULT);
                                        $user_access_model->access_level = VaaniUserAccess::LEVEL_QUEUE;
                                        if($user_access_model->save()){
                                            // add user-queue data in queue conf file
                                            $queue_agent_data = 'Action - add , Queue - ' . $queue->queue . ', priority - ' . $user_access_model->priority;
                                            $log = Yii::$app->Utility->addLog($queue_agent_data, 'add_queue_file');

                                            $model->queue_write('add', $queue->queue, $user_access_model->priority);
                                        }else{
                                            foreach($user_access_model->errors as $error){
                                                Yii::$app->session->setFlash('error', json_encode($error));
                                            }
                                        }
                                    }
                                }
                            }else{
                                if($model->campaigns){
                                    foreach($model->campaigns as $campaign_id){
                                        $user_access_model = new VaaniUserAccess();
                                        $user_access_model->user_id = $model->user_id;
                                        $user_access_model->role_id = $model->role;
                                        $user_access_model->client_id = $model->client;
                                        $user_access_model->campaign_id = $campaign_id;
                                        $user_access_model->access_level = VaaniUserAccess::LEVEL_CAMPAIGN;
                                        if(!$user_access_model->save()){
                                            foreach($user_access_model->errors as $error){
                                                Yii::$app->session->setFlash('error', json_encode($error));
                                            }
                                        }
                                        if($user_access_model->campaign->allQueues){
                                            $queue_names = array_merge($queue_names, ArrayHelper::getColumn($user_access_model->campaign->allQueues, 'queue'));
                                        }
                                    }
                                }else{
                                    if(!$user_access_model->save()){
                                        foreach($user_access_model->errors as $error){
                                            Yii::$app->session->setFlash('error', json_encode($error));
                                        }
                                    }
                                    if($user_access_model->client->campaigns){
                                        foreach($user_access_model->client->campaigns as $campaign){
                                            if($campaign->allQueues){
                                                $queue_names = array_merge($queue_names, ArrayHelper::getColumn($campaign->allQueues, 'queue'));
                                            }
                                        }
                                    }
                                }
                            }
                            
                            // add user active queues
                            if($model->userActiveQueues){
                                $model->userActiveQueues->delete();
                            }
                            $userActiveQueues = new VaaniUserActiveQueues();
                            $userActiveQueues->user_id = $model->user_id;
                            
                            if($queue_names){
                                foreach ($queue_names as $key => $queue_name) {
                                    if($key < 10){
                                        $field_name = 'q' . ($key+1);
                                        $userActiveQueues->{$field_name} = $queue_name;
                                    }
                                }
                            }
                            $userActiveQueues->save();
                        }
                        // add user data in sip file
                        $model->sip_write('add');
                        // add supervisor model
                        if($model->supervisor_id || $model->role_id == 5){
                            $supervisor_model = new VaaniUserSupervisor();
                            $supervisor_model->user_id = $model->user_id;
                            $supervisor_model->supervisor_id = $model->supervisor_id;
                            $supervisor_model->manager_id = $model->manager_id;
                            $supervisor_model->save();
                        }
                        // add call access model
                        // set call access default to yes for admin role
                        if(strtolower($model->userRole->role_name) == 'admin'){
                            $call_access_model = new VaaniCallAccess();
                            $call_access_model->user_id = $model->user_id;
                            $call_access_model->is_conference = VaaniCallAccess::ACCESS_YES;
                            $call_access_model->is_transfer = VaaniCallAccess::ACCESS_YES;
                            $call_access_model->is_consult = VaaniCallAccess::ACCESS_YES;
                            $call_access_model->is_manual = VaaniCallAccess::ACCESS_YES;
                            $call_access_model->save();
                        }else{
                            $call_access_model = new VaaniCallAccess();
                            $call_access_model->user_id = $model->user_id;
                            $call_access_model->is_conference = ($model->is_conference ? $model->is_conference : VaaniCallAccess::ACCESS_NO);
                            $call_access_model->is_transfer = ($model->is_transfer ? $model->is_transfer : VaaniCallAccess::ACCESS_NO);
                            $call_access_model->is_consult = ($model->is_consult ? $model->is_consult : VaaniCallAccess::ACCESS_NO);
                            $call_access_model->is_manual = ($model->is_manual ? $model->is_manual : VaaniCallAccess::ACCESS_NO);
                            $call_access_model->save();
                        }
                        
                        Yii::$app->session->setFlash('success', 'User created successfully.');
                        return $this->redirect(['index']);
                    }else{
                        foreach($model->errors as $error){
                            Yii::$app->session->setFlash('error', json_encode($error));
                            return $this->redirect(['create']);
                        }
                    }
                }
            }
        } else{
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
        }

        // fetch list of clients
        $clients = [];
        $client_ids = null;
        if((Yii::$app->user->identity->userRole && Yii::$app->user->identity->userRole->role_name != 'superadmin') && Yii::$app->user->identity->role != 'superadmin'){
            $client_ids = Yii::$app->user->identity->clientIds;
        }
        $clients = ArrayHelper::map(VaaniClientMaster::clientsList($client_ids), 'client_id', 'client_name');
        $roles = [];
        $campaigns = [];
        $queues = [];
        $supervisors = [];
        $managers = [];
        $queue_list = [];
        $priorities = VaaniUserAccess::$user_priorities;

        $operators = [];

        return $this->render('create', [
            'model' => $model,
            'clients' => $clients,
            'roles' => $roles,
            'campaigns' => $campaigns,
            'queues' => $queues,
            'queue_list' => $queue_list,
            'supervisors' => $supervisors,
            'managers' => $managers,
            'priorities' => $priorities,
            'operators' => $operators,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);
        $model->scenario = 'create_update';
        // $model->user_password = User::decrypt_data($model->user_password);
        $prev_role = $model->role = $model->role_id;
        // fetch previous edit values for various purposes
        $prev_user_name = $model->user_name;
        $prev_user_id = $model->user_id;
        $prev_password = User::decrypt_data($model->user_password);
        $prev_client = null;
        $prev_campaigns = [];
        $prev_queues = [];
        $queue_list = [];
        $queue_priorities = [];

        $user_access = $model->userAccess;
        if($user_access){
            $prev_client = $model->client = $user_access[0]->client ? $user_access[0]->client->client_id : null;
            foreach($user_access as $access_record){
                // fetch campaigns
                if($access_record->campaign){
                    $prev_campaigns[] = $model->campaigns[] = $access_record->campaign_id;
                }
                // fetch queues
                if($access_record->queue){
                    $queue_list[$access_record->queue_id] = ['name' => $access_record->queue->queue_name, 'priority' => $access_record->priority];
                    $queue_priorities[$access_record->queue_id] = $access_record->priority;
                    $prev_queues[] = $model->queues[] = $access_record->queue_id;
                }
            }
        }

        // fetch supervisor
        $prev_supervisor = null;
        $prev_manager = null;
        $prevSupervisorModel = null;
        if($model->supervisor){
            $prevSupervisorModel = $model->supervisor;
            $prev_supervisor = $model->supervisor_id = $model->supervisor->supervisor_id;
            $prev_manager = $model->manager_id = $model->supervisor->manager_id;
        }

        // fetch call actions access
        $prev_is_conference = null;
        $prev_is_transfer = null;
        $prev_is_consult = null;
        $prev_is_manual = null;
        $prevCallAccessModel = null;
        if($model->callAccess){
            $prevCallAccessModel = $model->callAccess;
            $prev_is_conference = $model->is_conference = $model->callAccess->is_conference;
            $prev_is_transfer = $model->is_transfer = $model->callAccess->is_transfer;
            $prev_is_consult = $model->is_consult = $model->callAccess->is_consult;
            $prev_is_manual = $model->is_manual = $model->callAccess->is_manual;
        }

        $prev_is_two_leg = $model->is_two_leg;
        $prev_web_rtc = $model->web_rtc;
        $model->operator = ($model->userOperator ? $model->userOperator->operator_id : null);
        $prev_operator = $model->operator;
        $prev_user_operator = $model->userOperator;
        $prev_contact = $model->contact_number;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost && $model->load($this->request->post())){
            $is_error = false;
            // check for unique user id
            $prev_users = User::userList();

            if($prev_users){
                $prev_user_ids = ArrayHelper::getColumn($prev_users, function ($element) use ($prev_user_id) {
                    if(strtolower($element['user_id']) != strtolower($prev_user_id)){
                        return strtolower($element['user_id']);
                    }
                });
                $prev_user_ids = array_filter($prev_user_ids);
                
                if(in_array(strtolower($model->user_id), $prev_user_ids)){
                    Yii::$app->session->setFlash('error', 'User Id is already been taken.');
                    $is_error = true;
                }
            }

            if(!$is_error){
                // encrypt previous password and check if it matches the password from post
                $prev_password = User::encrypt_data($prev_password);
                if($prev_password != $model->user_password){
                    $model->user_password = User::encrypt_data($model->user_password);
                }

                $model->is_two_leg = $model->is_two_leg ? $model->is_two_leg : User::TWO_LEG_INACTIVE;
                $model->web_rtc = $model->web_rtc ? $model->web_rtc : User::WEB_RTC_INACTIVE;
                
                $model->role_id = $model->role;
                
                if(!$model->service_key){
                    // set service key
                    $client_model = VaaniClientMaster::find()->where(['client_id' => $model->client])->one();
                    if($client_model){
                        $model->service_key = $client_model->service_key;
                    }
                }
            
                if($model->save()) {
                    // save operator
                    $user_operator = null;
                    $is_delete_operator = false;
                    $is_two_leg_change = false;

                    if($model->is_two_leg == User::TWO_LEG_ACTIVE){
                        if($prev_operator != $model->operator){
                            $is_delete_operator = true;
                            $is_two_leg_change = true;
                        }
                        if($is_delete_operator && $model->operator){
                            $operator = VaaniOperators::find()->where(['id' => $model->operator])->one();
                            if($operator){
                                $userOperatorModel = new VaaniUserOperator();
                                $userOperatorModel->operator_id = $operator->id;
                                $userOperatorModel->user_id = $model->user_id;
                                if($userOperatorModel->save()){
                                    // add operators in queues conf file
                                    $user_operator = $operator->operator_name;
                                    $is_two_leg_change = true;
                                }else{
                                    foreach($userOperatorModel->errors as $error){
                                        Yii::$app->session->setFlash('error', json_encode($error));
                                    }
                                }
                            }
                        }

                        if($model->contact_number != $prev_contact){
                            $is_two_leg_change = true;
                        }
                    }else{
                        $is_delete_operator = true;
                        $is_two_leg_change = true;
                    }
                    if($is_delete_operator){
                        // delete if any previous user operators
                        if($prev_user_operator){
                            $prev_user_operator->del_status = User::STATUS_PERMANENT_DELETED;
                            if($prev_user_operator->save()){
                                // add operators in queues conf file
                                // $user_operator = $prev_user_operator->operator->operator_name;
                            }else{
                                foreach($prev_user_operator->errors as $error){
                                    Yii::$app->session->setFlash('error', json_encode($error));
                                }
                            }
                        }
                    }
                    if($model->client || $model->campaigns || $model->queues){
                        if($model->client != $prev_client || ($model->campaigns && array_merge(array_diff($prev_campaigns,$model->campaigns), array_diff($model->campaigns,$prev_campaigns))) || ($model->queues && array_merge(array_diff($prev_queues,$model->queues),array_diff($model->queues,$prev_queues))) || $model->role != $prev_role || ($model->priority && array_merge(array_diff($queue_priorities,$model->priority), array_diff($model->priority, $queue_priorities))) || $model->is_two_leg != $prev_is_two_leg || $prev_operator != $model->operator || $is_two_leg_change){
                            // delete previous access
                            foreach($user_access as $access_record){
                                $access_record->del_status = VaaniUserAccess::STATUS_PERMANENT_DELETED;
                                $is_queue_write = false;
                                if($access_record->access_level == VaaniUserAccess::LEVEL_QUEUE && $access_record->queue){
                                    $prev_queue = $access_record->queue->queue;
                                    $is_queue_write = true;
                                }
                                if($access_record->save()){
                                    if($is_queue_write){
                                        // add user-queue data in queue conf file
                                        $model->queue_write('delete', $prev_queue, 0, null, $prev_contact);
                                    }
                                }
                            }
                            $user_access_model = new VaaniUserAccess();
                            $user_access_model->user_id = $model->user_id;
                            $user_access_model->role_id = $model->role;
                            $user_access_model->client_id = $model->client;
                            $user_access_model->access_level = VaaniUserAccess::LEVEL_CLIENT;

                            $queue_names = [];
                            if($model->queues){
                                foreach($model->queues as $queue_id){
                                    $queue = VaaniCampaignQueue::find()->where(['queue_id' => $queue_id])->one();
                                    if($queue){
                                        $queue_names[] = $queue->queue;

                                        $user_access_model = new VaaniUserAccess();
                                        $user_access_model->user_id = $model->user_id;
                                        $user_access_model->role_id = $model->role;
                                        $user_access_model->client_id = $model->client;
                                        $user_access_model->campaign_id = $queue->campaign_id;
                                        $user_access_model->queue_id = $queue_id;
                                        $user_access_model->priority = (($model->priority && isset($model->priority[$queue_id])) ? $model->priority[$queue_id] : VaaniUserAccess::PRIORITY_DEFAULT);
                                        $user_access_model->access_level = VaaniUserAccess::LEVEL_QUEUE;
                                        if($user_access_model->save()){
                                            // add user-queue data in queue conf file
                                            $model->queue_write('add', $queue->queue, $user_access_model->priority, $user_operator);
                                        }else{
                                            foreach($user_access_model->errors as $error){
                                                Yii::$app->session->setFlash('error', json_encode($error));
                                            }
                                        }
                                    }
                                }
                            }else{
                                if($model->campaigns){
                                    foreach($model->campaigns as $campaign_id){
                                        $user_access_model = new VaaniUserAccess();
                                        $user_access_model->user_id = $model->user_id;
                                        $user_access_model->role_id = $model->role;
                                        $user_access_model->client_id = $model->client;
                                        $user_access_model->campaign_id = $campaign_id;
                                        $user_access_model->access_level = VaaniUserAccess::LEVEL_CAMPAIGN;
                                        if(!$user_access_model->save()){
                                            foreach($user_access_model->errors as $error){
                                                Yii::$app->session->setFlash('error', json_encode($error));
                                            }
                                        }
                                        if($user_access_model->campaign->allQueues){
                                            $queue_names = array_merge($queue_names, ArrayHelper::getColumn($user_access_model->campaign->allQueues, 'queue'));
                                        }
                                    }
                                }else{
                                    if(!$user_access_model->save()){
                                        foreach($user_access_model->errors as $error){
                                            Yii::$app->session->setFlash('error', json_encode($error));
                                        }
                                    }
                                    if($user_access_model->client->campaigns){
                                        foreach($user_access_model->client->campaigns as $campaign){
                                            if($campaign->allQueues){
                                                $queue_names = array_merge($queue_names, ArrayHelper::getColumn($campaign->allQueues, 'queue'));
                                            }
                                        }
                                    }
                                }
                            }
                            // add user active queues
                            if($model->userActiveQueues){
                                $model->userActiveQueues->delete();
                            }
                            $userActiveQueues = new VaaniUserActiveQueues();
                            $userActiveQueues->user_id = $model->user_id;
                            
                            if($queue_names){
                                foreach ($queue_names as $key => $queue_name) {
                                    if($key < 10){
                                        $field_name = 'q' . ($key+1);
                                        $userActiveQueues->{$field_name} = $queue_name;
                                    }
                                }
                            }
                            $userActiveQueues->save();
                        }
                    }
                    
                    // edit user data in sip file
                    // if($prev_web_rtc == $model->web_rtc){
                    //     $model->sip_write('edit', $prev_user_name, $prev_user_id);
                    // }else{
                        $model->sip_write('delete');
                        $model->sip_write('add');
                    // }

                    // add supervisor model
                    if(($model->supervisor_id && $prev_supervisor != $model->supervisor_id) || ($model->manager_id && $prev_manager != $model->manager_id)){
                        // delete previous supervisor model if exists
                        if(($prev_supervisor || $prev_manager) && $prevSupervisorModel){
                            $prevSupervisorModel->updateAttributes(['del_status' => VaaniUserSupervisor::STATUS_PERMANENT_DELETED]);
                        }
                        $supervisor_model = new VaaniUserSupervisor();
                        $supervisor_model->user_id = $model->user_id;
                        $supervisor_model->supervisor_id = $model->supervisor_id;
                        $supervisor_model->manager_id = $model->manager_id;
                        $supervisor_model->save();
                    }
                    // edit call access model
                    if($prev_is_conference != $model->is_conference || $prev_is_transfer != $model->is_transfer || $prev_is_consult != $model->is_consult || $prev_is_manual != $model->is_manual){
                        // delete previous call access model if exists
                        if($prevCallAccessModel){
                            $prevCallAccessModel->updateAttributes(['del_status' => VaaniCallAccess::STATUS_PERMANENT_DELETED]);
                        }
                        $call_access_model = new VaaniCallAccess();
                        $call_access_model->user_id = $model->user_id;
                        $call_access_model->is_conference = ($model->is_conference ? $model->is_conference : VaaniCallAccess::ACCESS_NO);
                        $call_access_model->is_transfer = ($model->is_transfer ? $model->is_transfer : VaaniCallAccess::ACCESS_NO);
                        $call_access_model->is_consult = ($model->is_consult ? $model->is_consult : VaaniCallAccess::ACCESS_NO);
                        $call_access_model->is_manual = ($model->is_manual ? $model->is_manual : VaaniCallAccess::ACCESS_NO);
                        $call_access_model->save();
                    }

                    Yii::$app->session->setFlash('success', 'User updated successfully.');
                    return $this->redirect(['index']);
                }else{
                    foreach($model->errors as $error){
                        Yii::$app->session->setFlash('error', json_encode($error));
                    }
                }
            }
        }

        // fetch list of clients
        $clients = [];
        $clients = ArrayHelper::map(VaaniClientMaster::clientsList(), 'client_id', 'client_name');

        // fetch list of campaigns based on client id
        $campaigns = [];
        $campaigns = ArrayHelper::map(EdasCampaign::campaignsList(null, $model->client), 'campaign_id', 'campaign_name');

        // fetch list of roles based on client id & campaign id
        $roles = [];
        $role_ids = VaaniRoleMaster::rolesList($model->client, $model->campaigns);
        $roles = ArrayHelper::map(VaaniRole::rolesData($roles), 'role_id', 'role_name');

        // fetch list of queues based on campaign id
        $queues = [];
        $queues = ArrayHelper::map(VaaniCampaignQueue::queuesList($model->campaigns), 'queue_id', 'queue_name');

        $managers = [];
        $supervisors = [];
        if($model->userRole){
            // fetch list of managers based on role
            $manager_role = VaaniRole::find()->where(['like', 'role_name', 'manager'])->one();
            $managersList = User::userList(null, ($manager_role ? $manager_role->role_id : $model->userRole->parent_id), true, $model->client);
            $managers = ArrayHelper::map($managersList, 'user_id', 'user_name');

            // fetch list of supervisors based on role
            $supervisor_role = VaaniRole::find()->where(['like', 'role_name', 'supervisor'])->one();
            // $supervisorsList = User::userList(null, ($supervisor_role ? $supervisor_role->role_id : $model->userRole->parent_id), true, $model->client);
            $supervisorsList = User::userList(null, ($supervisor_role ? $supervisor_role->role_id : $model->userRole->parent_id), true, $model->client, $model->user_id, $model->manager_id);
            $supervisors = ArrayHelper::map($supervisorsList, 'user_id', 'user_name');
        }

        $priorities = VaaniUserAccess::$user_priorities;

        $clientOperatorModels = VaaniClientOperators::find()->where(['client_id' => $model->client])->all();

        $operatorModels = ($clientOperatorModels ? (VaaniOperators::getOperatorList(ArrayHelper::getColumn($clientOperatorModels, 'operator_id'))) : []);
        $operators = ArrayHelper::map($operatorModels, 'id', 'operator_name');

        return $this->render('update', [
            'model' => $model,
            'clients' => $clients,
            'campaigns' => $campaigns,
            'queues' => $queues,
            'queue_list' => $queue_list,
            'roles' => $roles,
            'supervisors' => $supervisors,
            'managers' => $managers,
            'priorities' => $priorities,
            'operators' => $operators,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);
        $model->del_status = User::STATUS_PERMANENT_DELETED;
        if($model->save()){
            // remove user active queues
            if($model->userActiveQueues){
                $model->userActiveQueues->delete();
            }
            Yii::$app->session->setFlash('success', 'User deleted successfully.');
            // remove user data in sip file
            $model->sip_write('delete');
            
            // add user-queue data in queue conf file
            $user_access = $model->userAccess;
            if($user_access){
                foreach($user_access as $access_record){
                    // fetch queues
                    if($access_record->queue){
                        $model->queue_write('delete', $access_record->queue->queue);
                    }
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // fetch list of campaigns of the client
    public function actionGetClientCampaigns()
    {
        $client_id = Yii::$app->request->post('client_id');

        if($client_id){
            $campaigns = EdasCampaign::campaignsList(null, $client_id);

            return json_encode(ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name'));
        }
    }

    // fetch list of queues of the campaigns
    public function actionGetCampaignsQueues()
    {
        $campaign_ids = Yii::$app->request->post('campaign_ids');

        if($campaign_ids){
            $queues = VaaniCampaignQueue::queuesList($campaign_ids);

            return json_encode(ArrayHelper::map($queues, 'queue_id', 'queue_name'));
        }
    }

    // fetch list of roles
    public function actionGetRoles()
    {
        $client_id = Yii::$app->request->post('client_id');
        $campaign_ids = Yii::$app->request->post('campaign_ids');

        if($campaign_ids || $client_id){
            $roles = VaaniRoleMaster::rolesList($client_id, $campaign_ids);

            return ( ($roles) ? json_encode(ArrayHelper::map(VaaniRole::rolesData($roles), 'role_id', 'role_name')) : null );
        }
    }

    // fetch list of supervisors
    public function actionGetSupervisors()
    {
        $manager = Yii::$app->request->post('manager');
        $user_id = Yii::$app->request->post('user_id');
        $client_id = Yii::$app->request->post('client_id');
        $campaign_ids = Yii::$app->request->post('campaign_ids');
        $role_id = Yii::$app->request->post('role_id');
        $supervisor_role = VaaniRole::find()->where(['like', 'role_name', 'supervisor'])->one();

        // $role = VaaniRole::find()->where(['role_id' => $role_id])->one();

        // if($campaign_ids && $supervisor_role && $supervisor_role->role_id){
            $supervisors = User::userList(null, $supervisor_role->role_id, true, $client_id, $user_id, $manager);

            /* $supervisors = User::find()
                ->where(['vaani_user.del_status' => User::STATUS_NOT_DELETED])
                ->where(['vaani_user.is_active' => User::STATUS_ACTIVE])
                ->leftJoin('vaani_user_supervisor vus', '`vaani_user`.`user_id` = `vus`.`user_id`')
                ->andFilterWhere(['vus.manager_id' => $manager])
                ->andFilterWhere(['IN', 'vaani_user.role_id', $supervisor_role->role_id])
                ->andFilterWhere(['IN', 'vus.del_status', User::STATUS_NOT_DELETED])
                ->orderBy('vaani_user.user_id DESC')->asArray()->all(); */

            return ( ($supervisors) ? json_encode(ArrayHelper::map($supervisors, 'user_id', 'user_name')) : null );
        // }

    }

    // fetch list of managers
    public function actionGetManagers()
    {
        $user_id = Yii::$app->request->post('user_id');
        $client_id = Yii::$app->request->post('client_id');
        $role_id = Yii::$app->request->post('role_id');

        $manager_role = VaaniRole::find()->where(['like', 'role_name', 'manager'])->one();
        // $role = VaaniRole::find()->where(['role_id' => $role_id])->one();

        // if($campaign_ids && $role->parent_id){
            $managers = User::userList(null, $manager_role->role_id, true, $client_id, $user_id);

            return ( ($managers) ? json_encode(ArrayHelper::map($managers, 'user_id', 'user_name')) : null );
        // }
    }

    // fetch list of operators
    public function actionGetOperators()
    {
        $client_id = Yii::$app->request->post('client_id');

        $operatorModels = VaaniClientOperators::find()->where(['client_id' => $client_id])->all();

        if($operatorModels){
            $operators = VaaniOperators::getOperatorList(ArrayHelper::getColumn($operatorModels, 'operator_id'));

            return ( ($operators) ? json_encode(ArrayHelper::map($operators, 'id', 'operator_name')) : null );
        }
    }
}
