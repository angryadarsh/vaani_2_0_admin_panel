<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use common\models\VaaniSipRegistrationDetail;
use common\models\VaaniSession;
use common\models\User;
use common\models\VaaniSessionLog;
use common\models\VaaniClientLicense;
use common\models\VaaniClientMaster;
use common\models\Api;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'sip-status', 'set-client-connection', 'change-password', 'agent-logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(['report/monitoring']);
        // return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        // if user hit this url, logout the session if any
        if(Yii::$app->user->identity){
            User::autoLogout();
            return $this->goHome();
        }

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = Yii::$app->user->identity;
            $role = $user->userRole;

            $access_menus = User::userMenus();
            if(empty($access_menus['access'])){
                $_SESSION['logout_message'] = 'User has no access!';
                User::autoLogout();
            }else{

                if($role && strtoUpper($role->role_name) == 'AGENT'){
                    // if agent logins, logout and points to agent panel
                    $_SESSION['logout_message'] = 'Agents cannot login here! Kindly go to <a href="http://172.16.152.50/pradeep/vaani_2_0/login.php">Agent Panel</a>';
                    
                    User::autoLogout(true);
                    return $this->goHome();
                    // if agent logins, redirect to agent panel
                    // return $this->redirect(['agent/dashboard']);
                }else{
                    if(!$user->service_key && strtoUpper($role->role_name) != 'SUPERADMIN'){
                        $_SESSION['logout_message'] = 'You cannot login due to incorrect licensing!';
                        
                        User::autoLogout(true);
                        return $this->goHome();
                    }else{
                        if(strtoUpper($role->role_name) != 'SUPERADMIN'){
                            // fetch user service key
                            $user_service_key = trim($user->service_key);
                            // fetch client service key
                            $service_key = Api::callAPI('POST', Yii::$app->params['SERVICE_KEY_API'], null, Yii::$app->params['VAANI_REMOTE_API_USERNAME'], Yii::$app->params['VAANI_REMOTE_API_PASSWORD']);
                            $service_lines = explode(PHP_EOL, $service_key);
                            $client_service_key = User::encrypt_data(trim(end($service_lines)));
                            
                            // check if both service keys matches
                            if($user_service_key != $client_service_key){
                                $_SESSION['logout_message'] = 'You cannot login due to incorrect licensing!';
                                User::autoLogout(true);
                                return $this->goHome();
                            }
    
                            $user_client = ($user->userAccess ? $user->userAccess[0]->client : null);
                            if($user_client){
                                $db_name = User::decrypt_data($user_client->db);
                                $db_host = User::decrypt_data($user_client->server);
                                $db_username = User::decrypt_data($user_client->username);
                                $db_password = User::decrypt_data($user_client->password);

                                \Yii::$app->db->close(); // make sure it clean
                                \Yii::$app->db->dsn = 'mysql:host='.$db_host.';dbname='.$db_name;
                                \Yii::$app->db->username = $db_username;
                                \Yii::$app->db->password = $db_password;
                                $_SESSION['client_connection'] = $user_client->client_id;
                            }
                        }
                        $loggedInSession = $user->loggedInSession;
                        /* if($user->user_id == 115184){
                            echo "<pre>"; print_r($loggedInSession);exit;
                        } */
                        $unique_id = User::newID('5','S');
                        $session_id = session_id();
                        
                        if($loggedInSession){
                            // delete previous session logs
                            if($user->activeSessionLogs){
                                foreach ($user->activeSessionLogs as $key => $value) {
                                    $value->active_log = VaaniSessionLog::ACTIVE_LOG_OFF;
                                    $value->save();
                                }
                            }
                            // create session log for login
                            VaaniSessionLog::addLog($session_id, $user->user_id, $unique_id, VaaniSessionLog::STATUS_ALREADY_LOGIN);

                            $_SESSION['logout_message'] = 'User Already logged in!';

                            User::autoLogout(true);
                            return $this->goHome();
                        }else{
                            if(strtoUpper($role->role_name) != 'SUPERADMIN'){
                                $client_licenses = VaaniClientLicense::clientRoleLicense($user->clientIds, $role->role_id);
                                // check login count & logged in count
                                if($client_licenses){
                                    if($client_licenses->logged_in_count >= $client_licenses->login_count){
                                        // delete previous session logs
                                        if($user->activeSessionLogs){
                                            foreach ($user->activeSessionLogs as $key => $value) {
                                                $value->active_log = VaaniSessionLog::ACTIVE_LOG_OFF;
                                                $value->save();
                                            }
                                        }

                                        $_SESSION['logout_message'] = 'You cannot login, since logged in count for ' . ucwords($role->role_name) . ' is exceeding!';

                                        User::autoLogout(true);
                                        return $this->goHome();
                                    }else{
                                        // increment the logged in count
                                        $client_licenses->logged_in_count = ($client_licenses->logged_in_count + 1);
                                        $client_licenses->save();

                                        // create session
                                        $loggedInSession = new VaaniSession();
                                        $loggedInSession->user_id = $user->user_id;
                                        $loggedInSession->login_datetime = date('Y-m-d H:i:s');
                                        $loggedInSession->date_created = date('Y-m-d H:i:s');
                                        $loggedInSession->last_action_epoch = time();
                                        $loggedInSession->session_id = $session_id;
                                        $loggedInSession->unique_id = $unique_id;
                                        if(!$loggedInSession->save()){
                                            foreach($loggedInSession->errors as $error){
                                                Yii::$app->session->setFlash('error', json_encode($error));
                                            }
                                        }

                                        // delete previous session logs
                                        if($user->activeSessionLogs){
                                            foreach ($user->activeSessionLogs as $key => $value) {
                                                $value->active_log = VaaniSessionLog::ACTIVE_LOG_OFF;
                                                $value->save();
                                            }
                                        }

                                        // create session log for login
                                        $sessionLog = VaaniSessionLog::addLog($session_id, $user->user_id, $unique_id, VaaniSessionLog::STATUS_LOGIN);

                                        // set data in session
                                        $_SESSION['sid'] 			= $session_id;
                                        $_SESSION['user_name'] 		= $user->user_name;
                                        $_SESSION['user_id'] 		= $user->user_id;
                                        $_SESSION['gender'] 		= $user->gender;
                                        $_SESSION['user_role_id'] 	= $role->role_id;
                                        $_SESSION['user_role'] 		= $role->role_name;
                                        $_SESSION['user_level'] 	= $role->level;
                                        $_SESSION['unique_id'] 		= $unique_id;
                                        $_SESSION['log_id'] 		= $sessionLog->log_id;
                                        $_SESSION['timestamp'] 		= time();
                                    }
                                }
                            }else{
                                // create session
                                $loggedInSession = new VaaniSession();
                                $loggedInSession->user_id = $user->user_id;
                                $loggedInSession->login_datetime = date('Y-m-d H:i:s');
                                $loggedInSession->date_created = date('Y-m-d H:i:s');
                                $loggedInSession->last_action_epoch = time();
                                $loggedInSession->session_id = $session_id;
                                $loggedInSession->unique_id = $unique_id;
                                if(!$loggedInSession->save()){
                                    foreach($loggedInSession->errors as $error){
                                        Yii::$app->session->setFlash('error', json_encode($error));
                                    }
                                }

                                // delete previous session logs
                                if($user->activeSessionLogs){
                                    foreach ($user->activeSessionLogs as $key => $value) {
                                        $value->active_log = VaaniSessionLog::ACTIVE_LOG_OFF;
                                        $value->save();
                                    }
                                }

                                // create session log for login
                                $sessionLog = VaaniSessionLog::addLog($session_id, $user->user_id, $unique_id, VaaniSessionLog::STATUS_LOGIN);

                                // set data in session
                                $_SESSION['sid'] 			= $session_id;
                                $_SESSION['user_name'] 		= $user->user_name;
                                $_SESSION['user_id'] 		= $user->user_id;
                                $_SESSION['gender'] 		= $user->gender;
                                $_SESSION['user_role_id'] 	= $role->role_id;
                                $_SESSION['user_role'] 		= $role->role_name;
                                $_SESSION['user_level'] 	= $role->level;
                                $_SESSION['unique_id'] 		= $unique_id;
                                $_SESSION['log_id'] 		= $sessionLog->log_id;
                                $_SESSION['timestamp'] 		= time();
                                
                            }
                        }
                        return $this->redirect(['report/monitoring']);
                    }
                }
            }
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        User::autoLogout();

        return $this->goHome();
    }

    // fetch the status of sip (X-lite)
    public function actionSipStatus()
    {
        $supervisor_id = Yii::$app->request->get()['supervisor_id'];

        $sip_detail = VaaniSipRegistrationDetail::find()->where(['sip' => $supervisor_id])->one();

        return ($sip_detail ? $sip_detail->status : "NONE");
    }

    // set client connection for superadmin
    public function actionSetClientConnection()
    {
        $client_id = Yii::$app->request->post()['client_id'];
        if(!$client_id && isset($_SESSION['client_connection']) && $_SESSION['client_connection']){
            $client_id = $_SESSION['client_connection'];
        }
        if($client_id){
            $client = VaaniClientMaster::find()->where(['client_id' => $client_id])->one();
            $db_name = User::decrypt_data($client->db);
            $db_host = User::decrypt_data($client->server);
            $db_username = User::decrypt_data($client->username);
            $db_password = User::decrypt_data($client->password);

            \Yii::$app->db->close(); // make sure it clean
            \Yii::$app->db->dsn = 'mysql:host='.$db_host.';dbname='.$db_name;
            \Yii::$app->db->username = $db_username;
            \Yii::$app->db->password = $db_password;

            $_SESSION['client_connection'] = $client_id;
        }
    }

    // Action for Change password 
    public function actionChangePassword()
    {   
        $user = Yii::$app->user->identity->id;
        $load_model = User::findOne($user);
        // echo "<pre>";print_r($load_model);exit;
        $load_model->scenario = 'change_password';
        
        if (Yii::$app->request->isAjax && $load_model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($load_model);
        }else if ($this->request->isPost) {
            if ($load_model->load($this->request->post())) {
                $encrypt_password = User::encrypt_data($load_model->new_password);
                if($load_model->save()){
                    $load_model->updateAttributes(['user_password' => $encrypt_password]);
                }else{
                    foreach($load_model->errors as $error){
                        Yii::$app->session->setFlash('error', json_encode($error));
                    }
                }
                Yii::$app->session->setFlash('success', 'Password Changed successfully!');
                return $this->redirect(['change-password']);
            }else{
                Yii::$app->session->setFlash('error', 'Password Not Changed!');
            }
        }
        return $this->render('changepwd', [
            'load_model' => $load_model,
        ]);
        
    }

    public function actionAgentLogout($id)
    {
        User::autoLogout(false, true, $id);
        $_SESSION['logout_message'] = 'User Logged Out Successfully!';
        return $this->redirect(['user/index']);
    }
}
