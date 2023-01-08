<?php

namespace backend\controllers;

use Yii;
use common\models\VaaniClientMaster;
use common\models\search\VaaniClientMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use yii\web\UploadedFile;
use common\models\VaaniRoleMaster;
use common\models\VaaniRole;
use common\models\EdasCampaign;
use common\models\VaaniUserAccess;
use common\models\VaaniCampaignQueue;
use common\models\VaaniCallAccess;
use common\models\VaaniOperators;
use common\models\VaaniClientOperators;
use common\models\VaaniClientLicense;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Api;
use common\models\EdasDniMaster;
use common\models\VaaniCrm;
use common\models\VaaniLeadBatch;
use common\models\VaaniDispositionPlan;

/**
 * ClientController implements the CRUD actions for VaaniClientMaster model.
 */
class ClientController extends Controller
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
     * Lists all VaaniClientMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $searchModel = new VaaniClientMasterSearch();
        $searchModel->del_status = VaaniClientMaster::STATUS_NOT_DELETED;
        if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
            $searchModel->client_id = $user->clientIds;
        }
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VaaniClientMaster model.
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
     * Creates a new VaaniClientMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VaaniClientMaster();
        $model->client_id = User::newID('14','CL');
        $model->dbport = 3306;
    
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost && $model->load($this->request->post())) {
            $is_error = false;
            
            // check for unique client name
            $prev_clients = VaaniClientMaster::clientsList();
            if($prev_clients){
                $prev_client_names = ArrayHelper::getColumn($prev_clients, function ($element) {
                    return strtolower($element['client_name']);
                });
                if(in_array(strtolower($model->client_name), $prev_client_names)){
                    Yii::$app->session->setFlash('error', 'Client Name is already been taken.');
                    $is_error = true;
                }
            }

            /* // check db connection
            if($model->server && $model->username && $model->password){
                if (!mysqli_connect("$model->server", "$model->username", "$model->password")) {
                    Yii::$app->session->setFlash('error', 'Connection Failed!');
                    $is_error = true;
                }
            } */

            if(!$is_error){
                
                // fetch service key
                $service_key = Api::callAPI('POST', Yii::$app->params['SERVICE_KEY_API'], null, Yii::$app->params['VAANI_REMOTE_API_USERNAME'], Yii::$app->params['VAANI_REMOTE_API_PASSWORD']);
                $service_lines = explode(PHP_EOL, $service_key);
                $model->service_key = User::encrypt_data(trim(end($service_lines)));
        
                // create db name for the client
                $clientName = $model->client_name;
                $clientName = strtolower(trim($clientName));
                $clientName = str_replace(" ", "_", $clientName);
                $model->db = 'vaani_' . $model->client_id;
                
                // encrypt db connection details
                $model->db = User::encrypt_data($model->db);
                $model->server = User::encrypt_data($model->server);
                $model->username = User::encrypt_data($model->username);
                $model->password = User::encrypt_data($model->password);
                $model->dbport = User::encrypt_data($model->dbport);
                $model->dbhost = User::encrypt_data($model->dbhost);

                $model->logoFile = UploadedFile::getInstance($model, 'logoFile');
                if($model->logoFile){
                    $model->logo = $model->client_name . '_' . date('Ymd_His') . '.' . $model->logoFile->extension;
                }

                if ($model->save()) {
                    // create database and tables
                    $create_db = VaaniClientMaster::create_client_db($model->client_id);
                    if($create_db != 'success'){
                        $model->delete();
                        Yii::$app->session->setFlash('warning', $create_db);
                    }else{
                        if($model->logoFile){
                            $model->logoFile->saveAs(Yii::$app->basePath . '/web/uploads/client_logo/' . $model->logo);
                        }    

                        // insert default role access for client
                        VaaniRoleMaster::addDefaultRoleAccess($model->client_id);

                        // add client operators
                        $config_operators = [];
                        $config_operators['is_remove'] = [];
                        $config_operators['add'] = [];
                        if($model->operators){
                            foreach($model->operators as $operator_id){
                                $operator = VaaniOperators::find()->where(['id' => $operator_id])->one();
                                if($operator){
                                    $operatorModel = new VaaniClientOperators();
                                    $operatorModel->client_id = $model->client_id;
                                    $operatorModel->operator_id = $operator_id;
                                    if($operatorModel->save()){
                                        // add operators in client ini file
                                        $config_operators['add'][] = $operator->operator_name;
                                    }else{
                                        foreach($operatorModel->errors as $error){
                                            Yii::$app->session->setFlash('error', json_encode($error));
                                        }
                                    }
                                }
                            }
                        }

                        // create client conf file
                        $model->add_conf_file('add', $model->conf_file, null, $config_operators);
                        
                        // save role wise login count data
                        if($model->role_login_count){
                            foreach($model->role_login_count as $role_id => $login_count){
                                $licenseModel = new VaaniClientLicense();
                                $licenseModel->client_id = $model->client_id;
                                $licenseModel->role_id = $role_id;
                                $licenseModel->login_count = $login_count;
                                if(!$licenseModel->save()){
                                    foreach($licenseModel->errors as $error){
                                        Yii::$app->session->setFlash('error', json_encode($error));
                                    }
                                }
                            }
                        }

                        Yii::$app->session->setFlash('success', 'Client created successfully.');
                    }
                    return $this->redirect(['index']);
                }
                Yii::$app->session->setFlash('error', 'Something went wrong. Kindly try again.');
            }
        } else {
            $model->loadDefaultValues();
        }

        $operatorModels = VaaniOperators::getOperatorList();
        $operators = ArrayHelper::map($operatorModels, 'id', 'operator_name');

        $role_login_counts = [];

        return $this->render('create', [
            'model' => $model,
            'operators' => $operators,
            'role_login_counts' => $role_login_counts,
        ]);
    }

    /**
     * Updates an existing VaaniClientMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);

        $prev_conf = $model->conf_file;
        $prev_name = $model->client_name;
        $prev_db_password = User::decrypt_data($model->password);

        $model->db = User::decrypt_data($model->db);
        $model->server = User::decrypt_data($model->server);
        $model->username = User::decrypt_data($model->username);
        $model->password = User::decrypt_data($model->password);
        $model->dbport = User::decrypt_data($model->dbport);
        $model->dbhost = User::decrypt_data($model->dbhost);

        $prev_operators = [];
        if($model->clientOperators){
            foreach($model->clientOperators as $operator){
                $model->operators[] = $operator->operator_id;
                $prev_operators[] = $operator->operator_id;
            }
        }

        // fetch role wise login count
        $role_login_counts = [];
        $licenses = $model->clientLicenses;
        foreach ($licenses as $key => $license) {
            $role_login_counts[$license->role_id] = $license->login_count;
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost && $model->load($this->request->post())) {
            $is_error = false;
            // check for unique client name
            $prev_clients = VaaniClientMaster::clientsList();
    
            if($prev_clients){
                $prev_client_names = ArrayHelper::getColumn($prev_clients, function ($element) use ($prev_name) {
                    if(strtolower($element['client_name']) != strtolower($prev_name)){
                        return strtolower($element['client_name']);
                    }                        
                });
                $prev_client_names = array_filter($prev_client_names);
                
                if(in_array(strtolower($model->client_name), $prev_client_names)){
                    Yii::$app->session->setFlash('error', 'Client Name is already been taken.');
                    $is_error = true;
                }
            }

            if(!$is_error){
                // encrypt previous password and check if it matches the password from post
                $prev_db_password = User::encrypt_data($prev_db_password);
                if($prev_db_password != $model->password){
                    $model->password = User::encrypt_data($model->password);
                }
                
                $model->db = User::encrypt_data($model->db);
                $model->server = User::encrypt_data($model->server);
                $model->username = User::encrypt_data($model->username);
                $model->dbport = User::encrypt_data($model->dbport);
                $model->dbhost = User::encrypt_data($model->dbhost);

                $model->logoFile = UploadedFile::getInstance($model, 'logoFile');
                if($model->logoFile){
                    $model->logo = $model->client_name . '_' . date('Ymd_His') . '.' . $model->logoFile->extension;
                }

                if(!$model->service_key){
                    // fetch service key
                    $service_key = Api::callAPI('POST', Yii::$app->params['SERVICE_KEY_API'], null, Yii::$app->params['VAANI_REMOTE_API_USERNAME'], Yii::$app->params['VAANI_REMOTE_API_PASSWORD']);
                    $service_lines = explode(PHP_EOL, $service_key);
                    $model->service_key = User::encrypt_data(trim(end($service_lines)));
                }

                if($model->save()){
                    if($model->logoFile){
                        $model->logoFile->saveAs(Yii::$app->basePath . '/web/uploads/client_logo/' . $model->logo);
                    }

                    // update client conf name in queue context
                    if($model->campaigns){
                        foreach($model->campaigns as $campaign){
                            if($campaign->allQueues){
                                foreach ($campaign->allQueues as $k => $queue) {
                                    // edit campaign queue data in asterisk file
                                    $queue->asterisk_write('edit', $queue->queue, $queue->dni_id, null, null, $prev_conf);
                                }
                            }
                        }
                    }

                    // update client operators
                    $config_operators = [];
                    $config_operators['is_remove'] = [];
                    $config_operators['add'] = [];

                    if($model->operators && array_merge(array_diff($prev_operators,$model->operators), array_diff($model->operators,$prev_operators))){
                        $config_operators['is_remove'] = true;
                        // delete the previous operators
                        foreach($model->clientOperators as $operatorModel){
                            $operatorModel->del_status = User::STATUS_PERMANENT_DELETED;
                            if(!$operatorModel->save()){
                                foreach($operatorModel->errors as $error){
                                    Yii::$app->session->setFlash('error', json_encode($error));
                                }
                            }
                        }

                        // add operators
                        foreach($model->operators as $operator_id){
                            $operator = VaaniOperators::find()->where(['id' => $operator_id])->one();
                            if($operator){
                                $operatorModel = new VaaniClientOperators();
                                $operatorModel->client_id = $model->client_id;
                                $operatorModel->operator_id = $operator_id;
                                if($operatorModel->save()){
                                    // add operators in client ini file
                                    $config_operators['add'][] = $operator->operator_name;
                                }else{
                                    foreach($operatorModel->errors as $error){
                                        Yii::$app->session->setFlash('error', json_encode($error));
                                    }
                                }
                            }
                        }
                    }

                    // update client conf file
                    $model->add_conf_file('edit', $model->conf_file, $prev_conf, $config_operators);

                    // save role wise login count data
                    if($model->role_login_count){
                        foreach($model->role_login_count as $role_id => $login_count){
                            $licenseModel = VaaniClientLicense::clientRoleLicense($model->client_id, $role_id);
                            if(!$licenseModel){
                                $licenseModel = new VaaniClientLicense();
                            }
                            $licenseModel->client_id = $model->client_id;
                            $licenseModel->role_id = $role_id;
                            $licenseModel->login_count = $login_count;
                            if(!$licenseModel->save()){
                                foreach($licenseModel->errors as $error){
                                    Yii::$app->session->setFlash('error', json_encode($error));
                                }
                            }
                        }
                    }

                    Yii::$app->session->setFlash('success', 'Client updated successfully.');
                    return $this->redirect(['index']);
                }
                Yii::$app->session->setFlash('error', 'Something went wrong. Kindly try again.');
            }
        }

        $operatorModels = VaaniOperators::getOperatorList();
        $operators = ArrayHelper::map($operatorModels, 'id', 'operator_name');

        return $this->render('update', [
            'model' => $model,
            'operators' => $operators,
            'role_login_counts' => $role_login_counts,
        ]);
    }

    /**
     * Deletes an existing VaaniClientMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);
        $model->del_status = VaaniClientMaster::STATUS_PERMANENT_DELETED;

        if($model->save()){
            // remove client conf file
            $model->add_conf_file('delete', $model->conf_file);

            // delete client's campaigns
            if($model->campaigns){
                foreach($model->campaigns as $campaign){
                    $campaign->del_status = EdasCampaign::STATUS_PERMANENT_DELETED;
                    if($campaign->save()){
                        // remove campaign data in asterisk file
                        $campaign->asterisk_write('delete');
                        // remove campaign data in queues conf file
                        $campaign->queue_write('delete');
                        // remove sound folder
                        $campaign->add_sound_folder('delete');
                        Yii::$app->session->setFlash('success', 'Campaigns deleted successfully.');

                        if($campaign->allQueues){
                            foreach ($campaign->allQueues as $k => $queue) {
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
                            
                                    // delete dni assigned to the queue
                                    if($queue->dni){
                                    	$delete_dni = $queue->dni;
                                    	$delete_dni->del_status = EdasDniMaster::STATUS_PERMANENT_DELETED;
                                    	$delete_dni->save();
                                    }

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
                        if($campaign->campaignActiveQueues){
                            $campaign->campaignActiveQueues->delete();
                        }

                        // remove campaign call access
                        if($campaign->callAccess){
                            $campaign->callAccess->del_status = VaaniCallAccess::STATUS_PERMANENT_DELETED;
                            $campaign->callAccess->save();
                        }
                        
                        // delete campaign crm
                        $campaign_crm = VaaniCrm::find()->where(['campaign_id' => $campaign->campaign_id])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->all();
                        if($campaign_crm){
                        	foreach ($campaign_crm as $key => $crm) {
                        		$crm->del_status = User::STATUS_PERMANENT_DELETED;
                            	$crm->save();
                        	}
                        }

                        // delete campaign batch
                        $campaign_batches = VaaniLeadBatch::find()->where(['campaign_id' => $campaign->campaign_id])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->all();
                        if($campaign_batches){
                        	foreach ($campaign_batches as $key => $batch) {
                        		$batch->del_status = User::STATUS_PERMANENT_DELETED;
                            	$batch->save();
                        	}
                        }
                    }
                }
            }
            // delete client users
            $user_list = VaaniCampaignQueue::usersList(null, null, null, $model->client_id);
            if($user_list){
            	foreach ($user_list as $key => $user) {
            		$user->del_status = User::STATUS_PERMANENT_DELETED;
            	}
            }
            // delete client's user access
            if($model->userAccess){
                foreach($model->userAccess as $access){
                    $access->del_status = VaaniUserAccess::STATUS_PERMANENT_DELETED;
                    if($access->save()){
                        Yii::$app->session->setFlash('success', 'User Access deleted successfully.');
                    }
                }
            }
            // delete client's role access
            if($model->roleMasters){
                foreach($model->roleMasters as $role_master){
                    $role_master->del_status = VaaniRoleMaster::STATUS_PERMANENT_DELETED;
                    if($role_master->save()){
                        Yii::$app->session->setFlash('success', 'Role Access deleted successfully.');
                    }
                }
            }
            // delete client licenses
            if($model->clientLicenses){
                foreach ($model->clientLicenses as $license) {
                    $license->del_status = User::STATUS_PERMANENT_DELETED;
                    if($license->save()){
                        Yii::$app->session->setFlash('success', 'License deleted successfully.');
                    }
                }
            }
            // delete disposition plans
            $dispo_plans = VaaniDispositionPlan::activePlansModels();
            if($dispo_plans){
            	foreach ($dispo_plans as $key => $plan) {
            		$dispositions = $plan->dispositions;
            		if($dispositions){
            			foreach ($dispositions as $k => $dispo) {
            				$dispo->del_status = User::STATUS_PERMANENT_DELETED;
            				$dispo->save();
            			}
            		}
            		$plan->del_status = User::STATUS_PERMANENT_DELETED;
            		$plan->save();
            	}
            }

            Yii::$app->session->setFlash('success', 'Client deleted successfully.');
        }
        return $this->redirect(['index']);
    }

    // test connection
    public function actionTestConnection()
    {
        $server = Yii::$app->request->post()['server'];
        $username = Yii::$app->request->post()['username'];
        $password = Yii::$app->request->post()['password'];

        if($server && $username && $password){
            $conn = mysqli_connect("$server", "$username", "$password") or die('Connection Failed!');
            if (!$conn) {
				return "Connection failed: ";
			}
            return 'Connection Successful!';
        }
        return 'Kindly fill all connection settings!';
    }

    /**
     * Finds the VaaniClientMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return VaaniClientMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VaaniClientMaster::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
