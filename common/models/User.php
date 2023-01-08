<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;
use phpseclib3\Net\SFTP;
use common\models\VaaniClientMaster;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $user_id
 * @property string $user_name
 * @property string $user_password
 * @property string $gender
 * @property string $role_id
 * @property string $created_by
 * @property string $created_ip
 * @property string $created_date
 * @property string $modified_date
 * @property string $modified_by
 * @property string $modified_ip
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $is_active
 * @property integer $del_status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $service_key
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $client, $campaigns, $queues, $role, $supervisor_id, $manager_id, 
            $is_conference, $is_transfer, $is_consult, $is_manual,
            $priority, $operator, $old_password, $new_password, $confirm_password;

    // const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const TWO_LEG_ACTIVE = 1;
    const TWO_LEG_INACTIVE = 2;

    const WEB_RTC_ACTIVE = 1;
    const WEB_RTC_INACTIVE = 2;

    public static $two_leg_statuses = [
        self::TWO_LEG_ACTIVE => 'Active',
        self::TWO_LEG_INACTIVE => 'Inactive',
    ];

    // delete status
    const STATUS_NOT_DELETED = 1;
    const STATUS_PERMANENT_DELETED = 2;
    const STATUS_TEMPORARY_DELETED = 3;

    public static $delete_statuses = [
        self::STATUS_NOT_DELETED => 'Not Deleted',
        self::STATUS_PERMANENT_DELETED => 'Permanent Deleted',
        self::STATUS_TEMPORARY_DELETED => 'Temporary Deleted',
    ];

    // gender types
    const GENDER_MALE = 'M';
    const GENDER_FEMALE = 'F';

    public static $gender_types = [
        self::GENDER_MALE => 'Male',
        self::GENDER_FEMALE => 'Female',
    ];

    // role types
    // manager, qa support tl agent
    const ROLE_SUPER_ADMIN = 'M';
    const ROLE_ADMIN = 'F';
    const ROLE_MANAGER = 'F';
    const ROLE_QA = 'F';
    const ROLE_SUPPORT = 'F';
    const ROLE_TL = 'F';
    const ROLE_AGENT = 'F';

    public static $role_types = [
        self::ROLE_SUPER_ADMIN => 'Male',
        self::ROLE_ADMIN => 'Female',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%vaani_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_date',
                'updatedAtAttribute' => 'modified_date',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
                'value' => function($event){
                    $user = Yii::$app->get('user', false);
                    return $user && !$user->isGuest ? $user->identity->user_name : null;
                }
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_ip',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'modified_ip',
                ],
                'value' => function ($event) {
                    return $_SERVER['REMOTE_ADDR'];
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_name', 'user_password', 'gender', 'role'], 'required', 'on' => 'create_update'],
            // [['user_id'], 'integer'],
            [['user_id'], 'string' ,'min' => 4],
            [['contact_number'], 'integer'],
            [['contact_number'], 'string', 'min' => 10 ,'max' => 10],
            [['user_id'], 'validateUniqueUser'],
            [['user_id', 'user_name', 'user_password', 'contact_number'], 'trim'],
            [['user_name', 'user_password', 'gender', 'created_by', 'created_ip', 'created_date', 'modified_date', 'modified_by', 'modified_ip', 'del_status', 'client', 'campaigns', 'queues', 'role', 'role_id', 'supervisor_id', 'manager_id', 'is_conference', 'is_transfer', 'is_consult', 'is_manual', 'priority', 'is_two_leg', 'operator', 'service_key', 'web_rtc'], 'safe'],
            ['is_two_leg', 'default', 'value' => self::TWO_LEG_INACTIVE],
            ['is_active', 'default', 'value' => self::STATUS_ACTIVE],
            ['del_status', 'default', 'value' => self::STATUS_NOT_DELETED],
            ['is_active', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            [['new_password','old_password','confirm_password'],'required', 'on' => 'change_password'],
            [['old_password'],'validateOldPassword'],
            [['confirm_password'],'compare', 'compareAttribute' => 'new_password', 'message' => 'Password do not matches!'],
        ];
    }

    // fetch parent db
    public static function getDb()
    {
        return Yii::$app->pa_db;
    }

    // before save field changes
    public function beforeSave($insert)
    {
        if ($this->contact_number) {
            $this->contact_number = '0' . $this->contact_number;
        }
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();

        if($this->contact_number){
            $this->contact_number = ltrim($this->contact_number, 0);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $identity_model = static::findOne(['id' => $id, 'is_active' => self::STATUS_ACTIVE]);

        if($identity_model->userRole && strtoUpper($identity_model->userRole->role_name) != 'SUPERADMIN'){
            $user_client = ($identity_model->userAccess ? $identity_model->userAccess[0]->client : null);
            if($user_client){
                $db_name = self::decrypt_data($user_client->db);
                $db_host = self::decrypt_data($user_client->server);
                $db_username = self::decrypt_data($user_client->username);
                $db_password = self::decrypt_data($user_client->password);
        
                \Yii::$app->db->close(); // make sure it clean
                \Yii::$app->db->dsn = 'mysql:host='.$db_host.';dbname='.$db_name;
                \Yii::$app->db->username = $db_username;
                \Yii::$app->db->password = $db_password;
            }
        }else{
            if(isset($_SESSION['client_connection']) && $_SESSION['client_connection']){
                $client = VaaniClientMaster::find()->where(['client_id' => $_SESSION['client_connection']])->one();

                if($client){
                    $db_name = User::decrypt_data($client->db);
                    $db_host = User::decrypt_data($client->server);
                    $db_username = User::decrypt_data($client->username);
                    $db_password = User::decrypt_data($client->password);

                    \Yii::$app->db->close(); // make sure it clean
                    \Yii::$app->db->dsn = 'mysql:host='.$db_host.';dbname='.$db_name;
                    \Yii::$app->db->username = $db_username;
                    \Yii::$app->db->password = $db_password;
                }
            }
        }
        return $identity_model;
    }

    public function validateUniqueUser()
    {
        $prev_users = self::userList(null, null, false, null, null, null, $this->id);
        if($prev_users){
            $prev_user_ids = ArrayHelper::getColumn($prev_users, function ($element) {
                return strtolower($element['user_id']);
            });

            if(in_array(strtolower($this->user_id), $prev_user_ids)){
                $this->addError('user_id', 'User Id is already been taken.');
            }
        }
    }

    //check old password match
    public function validateOldPassword(){
        if(!$this->validatePassword($this->old_password)){
            $this->addError("old_password", "Old Password is Incorrect!");
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by user_name
     *
     * @param string $user_name
     * @return static|null
     */
    public static function findByUsername($user_name)
    {
        return static::findOne(['user_name' => $user_name, 'is_active' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by user_id
     *
     * @param string $user_id
     * @return static|null
     */
    public static function findByUserId($user_id)
    {
        return static::findOne(['user_id' => $user_id, 'is_active' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'is_active' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'is_active' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->is_active;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        // return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function getRole()
    {
        $user = Yii::$app->user->identity;                  //fetch logged in user object
        $user_role = ($user->userAccess && $user->userAccess[0]->role ? $user->userAccess[0]->role->role_name : '-');
        return $user_role;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientIds()
    {
        $user = Yii::$app->user->identity;                  //fetch logged in user object
        $client_ids = [];
        foreach ($user->userAccess as $key => $user_access) {
            $client_ids[] = $user_access->client_id;
        }
        return $client_ids;
    }

    /**
     * {@inheritdoc}
     */
    public function getCampaignIds()
    {
        $user = Yii::$app->user->identity;                  //fetch logged in user object
        $campaign_ids = [];
        foreach ($user->userAccess as $key => $user_access) {
            if($user_access->access_level == VaaniUserAccess::LEVEL_CLIENT){
                foreach ($user_access->client->campaigns as $k => $val) {
                    $campaign_ids[] = $val->campaign_id;
                }
            }else{
                $campaign_ids[] = $user_access->campaign_id;
            }
        }
        return $campaign_ids;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueueIds()
    {
        $user = Yii::$app->user->identity;                  //fetch logged in user object
        $queue_ids = [];
        foreach ($user->userAccess as $key => $user_access) {
            if($user_access->access_level == VaaniUserAccess::LEVEL_CLIENT){
                foreach ($user_access->client->queues as $k => $val) {
                    $queue_ids[] = $val->queue_id;
                }
            }else{
                $queue_ids[] = $user_access->queue_id;
            }
        }
        return $queue_ids;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        // return $this->getAuthKey() === $authKey;
        return true;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        $decrypt = self::decrypt_data($this->user_password);
        return ($decrypt == $password);
        // return Yii::$app->security->validatePassword($password, $this->user_password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->user_password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    // get active status models
    public function getVaaniActiveStatus() {
        return $this->hasMany(VaaniActiveStatus::className(), ['user_id' => 'user_id']);
    }

    // get active session log models
    public function getActiveSessionLogs() {
        return $this->hasMany(VaaniSessionLog::className(), ['user_id' => 'user_id'])->andOnCondition(['del_status' => VaaniSessionLog::STATUS_NOT_DELETED])->andWhere(['active_log' => VaaniSessionLog::ACTIVE_LOG_ON]);
    }

    // get active session models, based on unique id
    public function getActiveUniqueSessions($unique_id) {
        return VaaniSession::find()->where(['unique_id' => $unique_id])->andWhere(['del_status' => VaaniSession::STATUS_NOT_DELETED])->all();
    }

    // get active session models, based on user id
    public function getActiveUserSessions() {
        return $this->hasMany(VaaniSession::className(), ['user_id' => 'user_id'])->andOnCondition(['del_status' => VaaniSession::STATUS_NOT_DELETED]);
    }

    // get logged in session of user
    public function getLoggedInSession() {
        return $this->hasOne(VaaniSession::className(), ['user_id' => 'user_id'])->andOnCondition(['del_status' => VaaniSession::STATUS_NOT_DELETED])->orderBy('login_datetime DESC');
    }

    // get access model
    public function getUserAccess() {
        return $this->hasMany(VaaniUserAccess::className(), ['user_id' => 'user_id'])->andOnCondition(['vaani_user_access.del_status' => self::STATUS_NOT_DELETED]);
    }

    // get role
    public function getUserRole() {
        return $this->hasOne(VaaniRole::className(), ['role_id' => 'role_id'])->andOnCondition(['del_status' => self::STATUS_NOT_DELETED]);
    }

    // get supervisor
    public function getSupervisor() {
        return $this->hasOne(VaaniUserSupervisor::className(), ['user_id' => 'user_id'])->andOnCondition(['vaani_user_supervisor.del_status' => self::STATUS_NOT_DELETED]);
    }

    // get call access
    public function getCallAccess() {
        return $this->hasOne(VaaniCallAccess::className(), ['user_id' => 'user_id'])->andOnCondition(['del_status' => self::STATUS_NOT_DELETED]);
    }

    // get user operators
    public function getUserOperator() {
        return $this->hasOne(VaaniUserOperator::className(), ['user_id' => 'user_id'])->andOnCondition(['del_status' => self::STATUS_NOT_DELETED]);
    }

    // queue names
    public function getUserActiveQueues()
    {
        return $this->hasOne(VaaniUserActiveQueues::className(), ['user_id' => 'user_id']);
    }

    // get agent role id
    public static function agentRoleId()
    {
        return VaaniRole::find()->where(['role_name' => 'agent'])->select(['role_id'])->asArray()->one();
    }

    // fetch users list
    public static function userList($campaign_id=null, $role_id = null, $is_array = false, $client_id = null, $not_users = null, $manager=null, $except_ids = null)
    {
        $query = self::find()
            ->where(['vaani_user.del_status' => self::STATUS_NOT_DELETED])
            ->andWhere(['vaani_user.is_active' => self::STATUS_ACTIVE]);
        
        if($role_id){
            $query->andFilterWhere(['IN', 'vaani_user.role_id', $role_id]);
        }
        if($campaign_id){
            $query->leftJoin('vaani_user_access vua', '`vaani_user`.`user_id` = `vua`.`user_id`')
            ->andFilterWhere(['IN', 'vua.campaign_id', $campaign_id])
            ->andFilterWhere(['IN', 'vua.del_status', self::STATUS_NOT_DELETED]);
        }
        if($client_id){
            $query->leftJoin('vaani_user_access ua', '`vaani_user`.`user_id` = `ua`.`user_id`')
            ->andFilterWhere(['IN', 'ua.client_id', $client_id])
            ->andFilterWhere(['IN', 'ua.del_status', self::STATUS_NOT_DELETED]);
        }
        if($manager){
            $query->leftJoin('vaani_user_supervisor vus', '`vaani_user`.`user_id` = `vus`.`user_id`')
            ->andFilterWhere(['vus.manager_id' => $manager])
            ->andFilterWhere(['IN', 'vus.del_status', self::STATUS_NOT_DELETED]);
        }
        if($not_users){
            $query->andFilterWhere(['NOT IN', 'vaani_user.user_id', $not_users]);
        }
        if($except_ids){
            $query->andFilterWhere(['NOT IN', 'vaani_user.id', $except_ids]);
        }
        
        if($is_array)
            return $query->orderBy('vaani_user.user_id DESC')->asArray()->all();
        else
            return $query->orderBy('vaani_user.user_id DESC')->all();
    }

    // vaani_dashboard data
    // auther : Ashish
    public static function encrypt_data($data)
    {
        // Store the cipher method
        $ciphering = "AES-128-CTR";
          
        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
          
        // Non-NULL Initialization Vector for encryption
        $encryption_iv = '1234567890123456';
          
        // Store the encryption key
        $encryption_key = "edasenc";
          
        // Use openssl_encrypt() function to encrypt the data
        $encryption = openssl_encrypt($data, $ciphering,
                    $encryption_key, $options, $encryption_iv);
          
        // Display the encrypted string
        return $encryption;
          
        
    }
    // auther : Ashish
    public static function decrypt_data($encryption)
    {
        // Store the cipher method
        $ciphering = "AES-128-CTR";

        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        // Non-NULL Initialization Vector for decryption
        $decryption_iv = '1234567890123456';
          
        // Store the decryption key
        $decryption_key = "edasenc";
          
        // Use openssl_decrypt() function to decrypt the data
        $decryption=openssl_decrypt ($encryption, $ciphering, 
                $decryption_key, $options, $decryption_iv);
          
        // Display the decrypted string
        return $decryption;
    }

    // return the menu list based on user role
    public static function userMenus($campid='',$route=[], $is_role_define=false) 
    {
        $user = Yii::$app->user->identity;                  //fetch logged in user object
        $user_role = strtolower($user->userAccess && $user->userAccess[0]->role ? $user->userAccess[0]->role->role_name : '-');

        $final_menu   = [];
        $user_access  = [];
        $cond = [];

        $request_route = false;
        foreach($route as $route_value){
            $request_route[$route_value] = false;
        }
        
        if($user_role != 'superadmin' && $user_role != 'agent')
        {
            $cond = ['not_in_group' => 1];

            $user_access_models  = VaaniUserAccess::find()
                ->leftJoin('vaani_client_master', '`vaani_user_access`.`client_id` = `vaani_client_master`.`client_id`')
                ->select('vaani_user_access.client_id, vaani_user_access.campaign_id, vaani_user_access.queue_id, vaani_client_master.logo')
                ->where(['vaani_client_master.del_status' => 1, 'vaani_user_access.user_id' => $user->user_id, 'vaani_user_access.del_status' => 1])
                ->asArray()->all();

                // echo "<pre>";print_r($user_access_models );exit;
            foreach ($user_access_models as $k => $access_row) 
            {
                if(!empty($access_row['campaign_id']))
                {
                    $user_access['campaign'][$access_row['campaign_id']]  = $access_row['campaign_id'];
                }
                if(!empty($access_row['queue_id']))
                {
                    $user_access['queue'][$access_row['queue_id']]  = $access_row['queue_id'];
                }
                $user_access['client'][$access_row['client_id']] = $access_row['client_id'];
                // we consider this time one user have only one client access.
                $user_access['logo'] = Yii::$app->request->baseUrl . '/uploads/client_logo/'.$access_row['logo'];
            }

            if(!empty($user_access['client']))
            {
                $role_condition = [];
                if(!empty($user_access['campaign']))
                {
                    $role_condition = ['IN', 'vaani_role_master.campaign_id', array_values($user_access['campaign'])];
                }
                if(!empty($user_access['queue']))
                {
                    $role_condition = ['IN', 'vaani_role_master.queue_id', array_values($user_access['queue'])];
                }
                
                $role_menus = VaaniRoleMaster::find()
                ->leftJoin('vaani_user_access', '`vaani_role_master`.`role_id` = `vaani_user_access`.`role_id`')
                ->select('vaani_role_master.role_group_id, vaani_role_master.role_assign_id, vaani_role_master.role_id, vaani_role_master.client_id, vaani_role_master.campaign_id, vaani_role_master.queue_id, vaani_role_master.menu_ids, vaani_role_master.add, vaani_role_master.modify, vaani_role_master.delete, vaani_role_master.view, vaani_role_master.status')
                ->where(['vaani_user_access.user_id' => $user->user_id, 'vaani_role_master.del_status' => 1])
                ->andWhere(['IN', 'vaani_role_master.client_id', implode("', '",$user_access['client'])])
                ->andWhere($role_condition)
                ->asArray()->all();

                foreach ($role_menus as $role_menu) 
                {
                    $data[$role_menu['menu_ids']][$role_menu['campaign_id']] = $role_menu;
                    $data[$role_menu['menu_ids']][$role_menu['queue_id']] = $role_menu;
                }
            }
        } else if($user_role == 'superadmin') {
            $user_access['queue']['ALL']   = 'ALL';
            $user_access['campaign']['ALL']   = 'ALL';
            $user_access['client']['ALL']     = 'ALL';
            $user_access['logo']              = Yii::$app->request->baseUrl . '/images/vaani-initial-logo.png';
        } else {
            $user_access['logo']              = Yii::$app->request->baseUrl . '/images/vaani-initial-logo.png';
        }

        $query      = VaaniMenu::find()->where(['del_status' => 1, 'status' => 1])->andWhere($cond)->orderBy('sequence')->asArray()->all();

        $menu       = [];
        $level_menu = [];
        $max_level  = 0; // this will store the max sub category of menu

        foreach($query as $row_key => $row)
        {
            // check is admin panel route
            $check_route = explode('/', $row['route']);
            $is_admin_panel = true;
            if(!$is_role_define && $check_route && isset($check_route[1]) && strtolower($check_route[1]) == 'agent'){
                $is_admin_panel = false;
            }
            // check menu id exist for user
            if($is_admin_panel){
                if(!empty($data[$row['menu_id']]) || $user_role == 'superadmin') {
                    // store data according to level wise and menu id 
                    $level_menu[$row['level']][$row['menu_id']] = $row;
                    if($max_level < $row['level']) {
                        $max_level = $row['level'];
                    }
                }
            } else {
                if($user_role == 'agent') {
                    // store data according to level wise and menu id 
                    $level_menu[$row['level']][$row['menu_id']] = $row;
                    if($max_level < $row['level']) {
                        $max_level = $row['level'];
                    }
                }
            }

            // check user requested route
            if($route){
                foreach($route as $route_value){
                    if($user_role == 'superadmin'){
                        $request_route[$route_value] = true;
                    }else{
                        if( (($row['route'] != '/client/index' && $row['route'] != '/client/create' && $row['route'] != '/client/update') && (!empty($route_value) && ('/' . $route_value) == $row['route'])) || $route_value == 'site/change-password' || $route_value == 'site/agent-logout') {
                            $request_route[$route_value] = true;
                        }
                    }
                }
            }
        }

        // create level wise menues and its sub menus. // menu start form second level
        for ($i=$max_level; $i >0 ; $i--) 
        {
            $p_level = $i-1;
            foreach ($level_menu[$i] as $key => $value) 
            {
                if($p_level>=1)
                {
                    if(!empty($level_menu[$p_level][$value['parent_id']]))
                    {
                        $level_menu[$p_level][$value['parent_id']]['sub'][$key]= $value; // add the sub category into the parent id array
                        unset($level_menu[$i][$key]); // remove all the sub category from the array
                    }            
                }          
            }
        }
        
        $final_menu['menu'] = [];
        if(!empty($level_menu)) {
            
            foreach($level_menu as $k => $l_menu){
                $keys = array_column($l_menu, 'sequence');
                array_multisort($keys, SORT_ASC, $l_menu);
                
                foreach ($l_menu as $key => $value)
                {
                    if($value['parent_id'] && $value['parent_id'] != $value['menu_id']){
                        $parent_menu = VaaniMenu::find()->where(['menu_id' => $value['parent_id'], 'del_status' => 1, 'status' => 1])->asArray()->one();

                        if($parent_menu){
                            $final_menu['menu'][$parent_menu['menu_id']] = $parent_menu;
                            $final_menu['menu'][$parent_menu['menu_id']]['sub'][$key] = $value;
                        }
                    }else{
                        $final_menu['menu'][$value['menu_id']] = $value;
                    }
                }
                $final_menu['access'] = $user_access;
            }
        }else{
            $final_menu['access'] = [];
            $final_menu['logo'] = [];
        }
        $final_menu['request_access'] = $request_route;
        
        return $final_menu;
    }

    // return the menu list based on user role
    public static function roleWiseMenus($role_id, $client_id='',$campaign_id='', $queue_id='') 
    {
        $user = Yii::$app->user->identity;          //fetch logged in user object
        // $user_role = ($user->userAccess && $user->userAccess[0]->role ? $user->userAccess[0]->role->role_name : '-');

        $return = [];
        $cond = '';
        $campair = '';

        if(!empty($user_id)){
            $query = VaaniRoleMaster::find()
                ->leftJoin('vaani_user_access', '`vaani_role_master`.`role_id` = `vaani_user_access`.`role_id`')
                ->where(['vaani_user_access.role_id' => $role_id])
                ->andWhere(['vaani_user_access.del_status' => self::STATUS_NOT_DELETED, 'vaani_role_master.del_status' => self::STATUS_NOT_DELETED]);
        }else{
            $query = VaaniRoleMaster::find()
                ->where(['vaani_role_master.role_id' => $role_id])
                ->andWhere(['vaani_role_master.del_status' => self::STATUS_NOT_DELETED]);
        }

        if(!empty($campaign_id) || !empty($client_id)){ 
            if(!empty($campaign_id)){
                $query->andFilterWhere(['IN', 'vaani_role_master.campaign_id', $campaign_id]);
            }
            
            if(!empty($queue_id)){
                $query->andFilterWhere(['IN', 'vaani_role_master.queue_id', $queue_id]);
            }

            if(!empty($client_id)){
                $query->andFilterWhere(['IN', 'vaani_role_master.client_id', $client_id]);
                    // ->andFilterWhere(['vaani_role_master.client_id' => 'vaani_user_access.client_id']);
            }
        }else{
            $query->andFilterWhere(['vaani_role_master.status' => VaaniRoleMaster::STATUS_DEFAULT_SETTING]);
        }

        $data = $query->groupBy('menu_ids')->asArray()->all();

        return $data;
    }

    public static function sub_menu_string($sub_menu, $level = '', $role_id = '', $client_id = '', $campaign_id = '', $queue_id = '')
    {
        $default_roles = self::roleWiseMenus($role_id, $client_id, $campaign_id, $queue_id);

        $mn  = '';
        $chk = '';

        if (!empty($level)) //  check the level of the menu is greater than 1
        {
            //incomplete  some modification may be required.
            $mn .= "<li class='sub-menu'>" . $sub_menu['menu_name'] . "";
            $mn .= "<ul class='list-unstyled menu-tabs'>";
        } else {

            $mn .= '<td class="">
                    <ul class="list-unstyled">
                    <li>' . $sub_menu['menu_name'] . '
                    <ul class="list-unstyled menu-tabs">
                ';
        }
        
        foreach ($sub_menu['sub'] as $k => $val) {
            $menu_access = (isset($default_roles[$k]) ? $default_roles[$k] : null);
            // if (!empty($default_roles[$k])) {
                $sub_html = '';
                $is_row = false;

                
                $chk = '';
                if (isset($val['sub'])) {
                    $sub_html = User::sub_menu_string($val, $val['level']);
                    if($sub_html) {
                        $is_row = true;
                        $mn .= $sub_html;
                    }
                } else {
                    // use current class for activate the list
                    if (array_search($val['menu_id'], array_column($default_roles, 'menu_ids')) || array_search($val['menu_id'], array_column($default_roles, 'menu_ids')) === 0) {
                        $chk = 'checked';
                    }
                    // echo array_search($val['menu_id'], array_column($default_roles, 'menu_ids'));
                    $is_row = true;
                    
                    $mn .=  '<li class="define_role" data-menu="' . $k . '"> 
                                <div class="row">
                                <a href="javascript:void(0);" class="col-9">
                                ' . $val['menu_name'] . '
                                </a>
                                <div class="col-3 text-right pr-0">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input class_role_update" data-parent="' . $sub_menu['menu_id'] . '" data-check="' . $k . '" id="customSwitch~' . $k . '" ' . $chk . '>
                                        <label class="custom-control-label" for="customSwitch~' . $k . '"></label>
                                    </div>
                                </div>
                                </div>
                            </li>';
                }
                if($is_row){
                    $mn .= '';
                }
            // }
        }
        $mn .= "</ul>
            </li>
            </ul>
            </td>";
        
        return $mn;
    }

    // This method use to generate new unique id
    //function to get 24 digit unique
    public static function newId($license_id, $rec_location_flag='')
	{
		$micro_sec		=	gettimeofday();
		//micro_sec are generated 6 digit and with no leading zeros
		//Implemeting the logic to add leading zeros if microsec are less than 6 digit
		$micsec			=	$micro_sec['usec'];		// Microseconds
		$len_micsecs	=	strlen($micsec);		//Length Of Micro Seconds
		$leadingzerocnt	=	6-$len_micsecs;			//Subtracting Length Of Micro seconds from 6 'p_20210920132057804328
		$leadingzero	=	NULL;
		for($i = 1; $i <= $leadingzerocnt; $i++)
		{
			$leadingzero .=	"0";
		}
		$micsec			=	$leadingzero.$micsec;
		$dateTimeStamp 	= 	gmdate("YmdHis").$micsec;

		$UID	=	$rec_location_flag.$license_id.$dateTimeStamp;
        
		return $UID;
	}

    public static function reload_call()
    {
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL            => Yii::$app->params['asterisk_reload'],
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => 'reload'
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    // write sip file for the user add/edit
    public function sip_write($action, $prev_user_name = null, $prev_user_id = null)
    {
        // configration file of remote server
        $sftp = new SFTP(Yii::$app->params['IP_ADDRESS']);
        if (!$sftp->login(Yii::$app->params['USER_ID'], Yii::$app->params['PASSWORD'])) {
            exit('Login Failed');
        }
        
        // request file extension
        // $myFile = "sip.txt";              // predefine copy file.
        $myFile = $_SERVER['DOCUMENT_ROOT']  . '/edas_vaani_dev/backend/web/files/sip_temp.txt';      // predefine temp copy file.
        
        $res = $sftp->get(Yii::$app->params['remo_sip_url'], $myFile);        // copy file from remote server to local server
        
        $lines = file($myFile);                             //file in to an array

        // create backup file
        $backupFileName = Yii::$app->params['asterisk_backup'] . '/sip_' . time() . '.conf';
        copy(Yii::$app->params['remo_sip_url'], $backupFileName);        // copy file from remote server to local server

        // request update data file name.
        $data = trim($this->user_id);
        $data = str_replace(" ", "_", $data);

        // tmp_context for append in file.

        if($this->web_rtc == self::WEB_RTC_ACTIVE){

            $tmp_context = '['.$data.']'.PHP_EOL;
            $tmp_context .= 'username='.$data.PHP_EOL;
            $tmp_context .= 'secret='.$data.PHP_EOL;
            $tmp_context .= 'accountcode='.$data.PHP_EOL;
            $tmp_context .= 'callerid="'.$this->user_name.'" <'.$data.'>'.PHP_EOL;
            $tmp_context .= 'mailbox='.$data.PHP_EOL;
            $tmp_context .= 'type=friend'.PHP_EOL;
            $tmp_context .= 'host=dynamic'.PHP_EOL;
            $tmp_context .= 'encryption=yes'.PHP_EOL;
            $tmp_context .= 'avpf=yes'.PHP_EOL;
            $tmp_context .= 'icesupport=yes'.PHP_EOL;
            $tmp_context .= 'directmedia=no'.PHP_EOL;
            $tmp_context .= 'transport=wss'.PHP_EOL;
            $tmp_context .= 'force_avp=yes'.PHP_EOL;
            $tmp_context .= 'dtlsenable=yes'.PHP_EOL;
            $tmp_context .= 'dtlsverify=no'.PHP_EOL;
            $tmp_context .= 'dtlscertfile=/etc/asterisk/keys/asterisk.crt'.PHP_EOL;
            $tmp_context .= 'dtlsprivatekey=/etc/asterisk/keys/asterisk.key'.PHP_EOL;
            $tmp_context .= 'dtlssetup=actpass'.PHP_EOL;
            $tmp_context .= 'rtcp_mux=yes'.PHP_EOL;
            $tmp_context .= 'context=default'.PHP_EOL;
            $tmp_context .= ';user_sip_end'.PHP_EOL;
        }else{
            $tmp_context = '['.$data.']'.PHP_EOL;
            $tmp_context .= 'defaultuser='.$data.PHP_EOL;
            $tmp_context .= 'secret='.$data.PHP_EOL;
            $tmp_context .= 'accountcode='.$data.PHP_EOL;
            $tmp_context .= 'callerid="'.$this->user_name.'" <'.$data.'>'.PHP_EOL;
            $tmp_context .= 'mailbox='.$data.PHP_EOL;
            $tmp_context .= 'allow=ulaw,alaw'.PHP_EOL;
            $tmp_context .= 'context=default'.PHP_EOL;
            $tmp_context .= 'type=friend'.PHP_EOL;
            $tmp_context .= 'host=dynamic'.PHP_EOL;
            $tmp_context .= ';user_sip_end'.PHP_EOL;
        }

        if(!empty($this->user_id) && $action == 'add')
        {
            // in case of remote server have empty file.
            if(empty($lines)){
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                fputs($writing,$tmp_context);
                $res = $sftp->put(Yii::$app->params['remo_sip_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
                fclose($writing);
            }else{
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                $last = sizeof($lines) - 1 ; 
                unset($lines[$last]);
                foreach ($lines as $key => $value) 
                {
                    fputs($writing,$value);
                }
                fputs($writing,''.PHP_EOL); 				
                fputs($writing,$tmp_context); 				
                $res = $sftp->put(Yii::$app->params['remo_sip_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
                fclose($writing);
            }

            self::reload_call();
            echo "complete";
        }
        elseif($action == 'edit')
        {
            // edit user id
            if(!empty($this->user_id) && !empty($prev_user_id))
		    {
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                foreach ($lines as $key => $value) 
                {
                    // if (preg_match("/^".$prev_user_id."/", $value)) 
                    if (strpos($value, $prev_user_id) > '-1') 
                    {
                        $value = str_replace($prev_user_id, $data, $value);
                    }
                    fputs($writing,$value);
                }	
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_sip_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
            }

            // edit user name
            if(!empty($this->user_name) && !empty($prev_user_name))
		    {
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                foreach ($lines as $key => $value) 
                {
                    // if (preg_match("/^".$prev_user_name."/", $value)) 
                    if (strpos($value, $prev_user_name) > '-1') 
                    {
                        $value = str_replace($prev_user_name, $this->user_name, $value);
                    }
                    fputs($writing,$value);
                }	
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_sip_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
            }

            self::reload_call();
            echo "complete";
        }
        elseif(!empty($this->user_id) && $action == 'delete')
        {
            $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");	
            $count = 0;		
            foreach ($lines as $key => $value)
            {
                if (strpos($value, '['.$data.']')>'-1' && $count==0) {
                    $count=1;
                    continue;
                }
                if($count==0) {
                    fputs($writing,$value);
                }
                if($count==1 && strpos($value,';user_sip_end') > '-1') {
                    $count=0;
                }
            }
            fclose($writing);
            $res = $sftp->put(Yii::$app->params['remo_sip_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);

            self::reload_call();
            echo "success";
        }
        else{
            echo "User not found!";
        }
    }

    // user queue association in queues.conf file
    public function queue_write($action, $queue, $priority = 0, $user_operator = null, $prev_contact = null)
    {
        // configration file of remote server
        $sftp = new SFTP(Yii::$app->params['IP_ADDRESS']);
        if (!$sftp->login(Yii::$app->params['USER_ID'], Yii::$app->params['PASSWORD'])) {
            exit('Login Failed');
        }
        
        // request file extension
        $myFile = $_SERVER['DOCUMENT_ROOT']  . '/edas_vaani_dev/backend/web/files/queues_temp.txt';      // predefine temp copy file.
        
        $res = $sftp->get(Yii::$app->params['remo_queue_url'], $myFile);        // copy file from remote server to local server
        $lines = file($myFile);                             //file in to an array

        // create backup file
        $backupFileName = Yii::$app->params['asterisk_backup'] . '/queues_' . time() . '.conf';
        copy(Yii::$app->params['remo_queue_url'], $backupFileName);        // copy file from remote server to local server

        // request update data member name.
        $data = trim($this->user_id);
        
        $add_context = null;
        // tmp_context for append in file.
        if($this->is_two_leg == self::TWO_LEG_ACTIVE){
            $user_operator = ($user_operator ? $user_operator : (($this->userOperator && $this->userOperator->operator) ? $this->userOperator->operator->operator_name : null) );
            $add_context = 'member => SIP/'.trim($this->contact_number).'@'.$user_operator.','.$priority.PHP_EOL;
        }elseif($this->is_two_leg == self::TWO_LEG_INACTIVE){
            $add_context = 'member => SIP/'.$data.','.$priority.PHP_EOL;
        }

        // add log
        if($add_context){
            $log = Yii::$app->Utility->addLog($add_context, 'add_queue_file');
        }

        $queue = strtolower($queue);
        
        if(!empty($data))
        {
            if($action == 'add')
            {
                $writing = fopen($myFile, 'w')or die("Unable to open file!:".$myFile."-w");
                $str = 0;
                if(!empty($add_context)) {
                    foreach ($lines as $key => $value) {
                        if (strpos($value, $queue) > '-1') {
                            $str = 1;
                        }
                        if($str == 1 && strpos($value, 'wav49') > '-1') {
                            $value = $add_context . $value;
                            $str = 0;
                        }
                        fputs($writing,$value);
                    }
                    $res = $sftp->put(Yii::$app->params['remo_queue_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
                }
                fclose($writing);
            }else if($action == 'delete')
            {
                $writing = fopen($myFile, 'w')or die("Unable to open file!:".$myFile."-w");
                $str = 0;
                if(!empty($add_context)) {
                    foreach ($lines as $key => $value) {
                        if (strpos($value, $queue) > '-1') {
                            $str = 1;
                        }
                        if(($str == 1) && ($value && (strpos($value, ('member => SIP/' . $data)) > '-1') || ($prev_contact && (strpos($value, ($prev_contact))) > '-1'))) {
                            $str = 0;
                            continue;
                        }
                        fputs($writing,$value);
                    }
                    $res = $sftp->put(Yii::$app->params['remo_queue_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
                }
                fclose($writing);
            }
        }
    }

    // auto logout
    public static function autoLogout($no_session_data=false, $is_agent=false, $user_id=null)
    {
        if($user_id == null){
            $user = Yii::$app->user->identity;
            $loggedInSession = $user->loggedInSession;
        }else{
            $user = User::find()->where(['user_id' => $user_id])->one();
            $identity = $user;
            $loggedInSession = $user->loggedInSession;
            // echo "<pre>we are user";print_r($loggedInSession);exit;
        }
        if(!$no_session_data){
            if($is_agent){
                $uni = VaaniSession::find()->select(['unique_id'])->where(['user_id' => $user_id])->one();
                // foreach ($uni as $key => $value) {}
                $role = $user->userRole;
                $user_role_id = $role->role_id;
                $user_role = $role->role_name;
                $unique_id = $uni->unique_id;
                $user_id = $user_id;
                // echo "<pre>we are cheacking";print_r($user->loggedInSession->client_id);exit;
            }else{
                $user_role_id = isset($_SESSION['user_role_id'])? $_SESSION['user_role_id'] : null ;
                $user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
                $unique_id = isset($_SESSION['unique_id']) ? $_SESSION['unique_id'] : null;
                $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                // echo "<pre>";print_r($user_role_id);exit;
                // echo "<pre>we are cheacking";print_r($user->clientIds);exit;
            }
            // decrement the logged in count
            if((isset($user_role) && strtolower($user_role)) != 'superadmin'){
                if(isset($user_role_id)){
                    $client_licenses = VaaniClientLicense::clientRoleLicense($user->loggedInSession->client_id, $user_role_id);
                    if($client_licenses && $client_licenses->logged_in_count > 0){
                        $client_licenses->logged_in_count = ($client_licenses->logged_in_count - 1);
                        $client_licenses->save();
                    }
                }
            }

            if($loggedInSession){
                $loggedInSession->logout_datetime = date('Y-m-d H:i:s');
                $loggedInSession->del_status = VaaniSession::STATUS_PERMANENT_DELETED;
                if($loggedInSession->save()){
                    // $log = Yii::$app->Utility->addLog(, 'user_autologout');
                    // return $browser['name'];
                }
            }

            if((isset($user_role) && strtolower($user_role)) == 'agent' || $user->vaaniActiveStatus){
                foreach ($user->vaaniActiveStatus as $key => $value) {
                    $value->delete();
                }
            }

            // delete previous session logs
            if($user->activeSessionLogs){
                foreach ($user->activeSessionLogs as $key => $value) {
                    $value->active_log = VaaniSessionLog::ACTIVE_LOG_OFF;
                    $value->save();
                }
            }

            $sessionModels = null;
            if(isset($unique_id) && $unique_id){
                // create session log for logout
                VaaniSessionLog::addLog(session_id(), $user_id, $unique_id, VaaniSessionLog::STATUS_LOGOUT);
                $sessionModels = $user->getActiveUniqueSessions($unique_id);
                
            }else if(isset($user_id) && $user_id){
                // create session log for force logout
                VaaniSessionLog::addLog(session_id(), $user_id, $unique_id, VaaniSessionLog::STATUS_FORCE_LOGOUT);
                $sessionModels = $user->activeUserSessions;
            }
            if($sessionModels){
                foreach ($sessionModels as $key => $value) {
                    $value->del_status = VaaniSession::STATUS_PERMANENT_DELETED;
                    $value->logout_datetime = date('Y-m-d H:i:s');
                    $value->save();
                }
            }
        }
        $message = null;
        if(isset($_SESSION['logout_message'])){
            $message = $_SESSION['logout_message'];
        }

        if($is_agent){
          
        }else{
            Yii::$app->user->logout();                      // logout
        }
        
        if($message){
            Yii::$app->session->setFlash('warning', $message);
        }

        return true;
    }
}