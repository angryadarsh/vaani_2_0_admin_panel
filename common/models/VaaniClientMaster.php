<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;
use phpseclib3\Net\SFTP;

/**
 * This is the model class for table "vaani_client_master".
 *
 * @property int $id
 * @property string|null $client_id
 * @property string|null $client_name
 * @property string|null $client_descreption
 * @property string|null $logo
 * @property string|null $conf_file
 * @property string|null $dashboard_color
 * @property string|null $db
 * @property string|null $username
 * @property string|null $password
 * @property string|null $server
 * @property string|null $dbport
 * @property string|null $dbhost
 * @property string|null $created_by
 * @property string|null $created_date
 * @property string|null $created_ip
 * @property string|null $modified_by
 * @property string|null $modified_date
 * @property string|null $modified_ip
 * @property string|null $service_key
 * @property int|null $del_status 1-not deleted, 2-deleted
 */
class VaaniClientMaster extends \yii\db\ActiveRecord
{
    public $logoFile, $operators, $role_login_count;

    // delete status
    
    const STATUS_NOT_DELETED = 1;
    const STATUS_PERMANENT_DELETED = 2;
    const STATUS_TEMPORARY_DELETED = 3;

    public static $delete_statuses = [
        self::STATUS_NOT_DELETED => 'Not Deleted',
        self::STATUS_PERMANENT_DELETED => 'Permanent Deleted',
        self::STATUS_TEMPORARY_DELETED => 'Temporary Deleted',
    ];

    // fetch parent db
    public static function getDb()
    {
        return Yii::$app->pa_db;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_client_master';
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
            [['client_name'], 'required'],
            // [['client_name'], 'unique'],
            [['modified_date', 'logoFile', 'conf_file', 'operators', 'role_login_count', 'db', 'username', 'password', 'server', 'dbport', 'dbhost', 'service_key'], 'safe'],
            // [['server'], 'match', 'pattern' => '/^[a-zA-Z0-9_.-]*$/', 'message' => 'Server Name is Invalid.'],
            [['logoFile'], 'file', 'extensions' => 'jpeg, jpg, png'],
            [['del_status'], 'integer'],
            [['client_id', 'client_name', 'client_descreption', 'logo', 'dashboard_color', 'created_by', 'created_date', 'created_ip', 'modified_by', 'modified_ip'], 'string', 'max' => 50],
            [['client_id'], 'unique'],
            [['client_name'], 'match', 'pattern' => '/^[a-zA-Z0-9_]*$/', 'message' => 'Client Name cannot contain special characters & space, except underscore.'],
            ['del_status', 'default', 'value' => self::STATUS_NOT_DELETED],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'client_name' => 'Client Name',
            'client_descreption' => 'Client Description',
            'logo' => 'Logo',
            'dashboard_color' => 'Dashboard Color',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'created_ip' => 'Created Ip',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }

    // before save event actions
    public function beforeSave($insert)
    {
        if ($this->client_name) {
            $this->client_name = trim($this->client_name);
            $this->client_name = str_replace(" ", "_", $this->client_name);

            // $this->client_name = $client_name;
            $this->conf_file = strtolower($this->client_name);
        }
        if($this->server){
            $this->dbhost = $this->server;
        }
        return parent::beforeSave($insert);
    }

    // get client's campaigns
    public function getCampaigns() {
        return $this->hasMany(EdasCampaign::className(), ['client_id' => 'client_id'])->andOnCondition(['del_status' => EdasCampaign::STATUS_NOT_DELETED]);
    }

    // get client's user access
    public function getUserAccess() {
        return $this->hasMany(VaaniUserAccess::className(), ['client_id' => 'client_id'])->andOnCondition(['del_status' => VaaniUserAccess::STATUS_NOT_DELETED]);
    }

    // get client's role access
    public function getRoleMasters() {
        return $this->hasMany(VaaniRoleMaster::className(), ['client_id' => 'client_id'])->andOnCondition(['del_status' => VaaniRoleMaster::STATUS_NOT_DELETED])->groupBy('role_id');
    }

    // get client's operators
    public function getClientOperators() {
        return $this->hasMany(VaaniClientOperators::className(), ['client_id' => 'client_id'])->andOnCondition(['del_status' => User::STATUS_NOT_DELETED]);
    }

    // get client's license data
    public function getClientLicenses() {
        return $this->hasMany(VaaniClientLicense::className(), ['client_id' => 'client_id'])->andOnCondition(['del_status' => User::STATUS_NOT_DELETED]);
    }

    // fetch list of clients
    public static function clientsList($client_id='')
    {
        return self::find()
            ->select(['client_id', 'client_name', 'client_descreption', 'logo', 'created_date'])
            ->where(['del_status' => self::STATUS_NOT_DELETED])
            ->andFilterWhere(['IN', 'client_id', $client_id])
            ->orderBy('created_date DESC')
            ->all();
    }

    // fetch list of client's campaigns
    public function getCampaignsList()
    {
        $campaigns_list = [];
        $campaigns = $this->campaigns;
        if($campaigns){
            foreach($campaigns as $campaign){
                $campaigns_list[$campaign->campaign_id] = $campaign->campaign_name;
            }
        }
        return $campaigns_list;
    }

    // create/update/delete conf file for client in uc/config/ini folder
    public function add_conf_file($action, $conf_file, $prev_conf = null, $config_operators = null)
    {
        if(!empty($conf_file)){
            // configration file of remote server
            $sftp = new SFTP(Yii::$app->params['IP_ADDRESS']);
            if (!$sftp->login(Yii::$app->params['USER_ID'], Yii::$app->params['PASSWORD'])) {
                exit('Login Failed');
            }

            $client_file = Yii::$app->params['client_ini_path'] . '/' . $conf_file . '.conf';

            $ini_temp_file = $_SERVER['DOCUMENT_ROOT']  . '/edas_vaani/backend/web/files/client_ini.txt';

            // create file content
            /* $data = '';
            $data .= '['.$conf_file.']'.PHP_EOL;
            $data .= "host = 127.0.0.1".PHP_EOL;
            $data .= 'database = asterisk'.PHP_EOL;
            $data .= 'username = root'.PHP_EOL;
            $data .= 'password = Mys@roja2021'.PHP_EOL;
            $data .= 'serverip=172.16.152.50'.PHP_EOL; */

            // create file content
            $data = '';
            $data .= '['.$conf_file.']'.PHP_EOL;
            $data .= 'clientid = '.base64_encode($this->client_id).PHP_EOL;
            // parent db details
            $data .= 'pahost = '.base64_encode("127.0.0.1").PHP_EOL;
            $data .= 'padatabase = '.base64_encode("asterisk").PHP_EOL;
            $data .= 'pausername = '.base64_encode("root").PHP_EOL;
            $data .= 'papassword = '.base64_encode("Mys@roja2021").PHP_EOL;
            $data .= 'paserverip = '.base64_encode("172.16.152.50").PHP_EOL;
            // dynamic db details
            $db_server   = User::decrypt_data($this->server);
			$db_user     = User::decrypt_data($this->username);
			$db_pass     = User::decrypt_data($this->password);
			$db_name     = User::decrypt_data($this->db);
			$db_host     = User::decrypt_data($this->dbhost);
            $data .= 'host = ' . base64_encode($db_host) .PHP_EOL;
            $data .= 'database = ' . base64_encode($db_name) .PHP_EOL;
            $data .= 'username = ' . base64_encode($db_user) .PHP_EOL;
            $data .= 'password = ' . base64_encode($db_pass) .PHP_EOL;
            $data .= 'serverip = ' . base64_encode($db_server) .PHP_EOL;

            if($action == 'delete'){
                // delete file
                if(file_exists($client_file)){
                    unlink($client_file);
                }
            }else {
                $is_new = true;
                if($action == 'edit'){
                    // if($conf_file == $prev_conf && !$config_operators['remove']){
                        // $is_new = false;
                    // }else{
                        $prev_conf_file = Yii::$app->params['client_ini_path'] . '/' . $prev_conf . '.conf';
                        // delete old file
                        if(file_exists($prev_conf_file)){
                            unlink($prev_conf_file);
                        }
                    // }
                }

                if($is_new){
                    $main_file = fopen($client_file, 'w') or die("Unable to open file!:".$client_file."-w");

                    $writing = fopen($ini_temp_file, 'w') or die("Unable to open file!:".$ini_temp_file."-w");

                    if(isset($config_operators['add']) && $config_operators['add']){
                        foreach ($config_operators['add'] as $key_count => $operator) {
                            $data .= 'operator' . ($key_count > 0 ? $key_count : '') . ' = ' . $operator . ''.PHP_EOL;
                        }
                    }else{
                        $data .= 'operator = airtelsip'.PHP_EOL;
                    }
                    $data .= ';end_of_config'.PHP_EOL;

                    fputs($writing,$data);
                    $res = $sftp->put($client_file, $ini_temp_file, SFTP::SOURCE_LOCAL_FILE);
                    fclose($writing);
                    fclose($main_file);
                }
            }

            User::reload_call();
            echo "complete";
        }
        else{
            echo "Client not found!";
        }
    }

    // create client wise database
    public static function create_client_db($client_id)
    {
        $result = null;
        
		$client_model = self::find()->where(['client_id' => $client_id])->one();
		if($client_model){
            $db = User::decrypt_data($client_model->db);

            // Create connection
            $link = Yii::$app->Utility->client_db_connect($client_id, false);
            // Check connection
            if (!$link) {
                return "Connection failed: " . mysqli_connect_error();
            }
            $db_selected = mysqli_select_db($link, $db);

            if (!$db_selected) {
                // If we couldn't, then it either doesn't exist, or we can't see it.
                // Create database
                $sql = "CREATE DATABASE " . $db . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ";
                $log = Yii::$app->Utility->addLog($sql, 'create_client_db');
                
                $exec_sql = mysqli_query($link, $sql);
                // $log = Yii::$app->Utility->addLog($exec_sql, 'create_client_db');
            
                if ($exec_sql) {
                    // call create tables function
                    $create_tables = self::create_new_tables($client_id);
                    if($create_tables == 'success'){
                        $result = "success";
                    }else{
                        $result = $create_tables;
                        $sql = "DROP DATABASE " . $db;
                        $log = Yii::$app->Utility->addLog($sql, 'create_client_db');
                        $exec_sql = mysqli_query($link, $sql);
                    }
                } else {
                    $result = 'Error creating database: ' . mysqli_error($link);
                }
            }          

            mysqli_close($link);
            return $result;
        }
		return "No client Found!";
    }

    // create new tables in the new db
    public static function create_new_tables($client_id)
    {
        $result = null;
        
		$client_model = self::find()->where(['client_id' => $client_id])->one();
		if($client_model){

            $sourceDB = User::decrypt_data(Yii::$app->params['DUM_DB_NAME']);
            $targetDB = User::decrypt_data($client_model->db);

            $source_link = Yii::$app->Utility->db_connect();
            $target_link = Yii::$app->Utility->client_db_connect($client_id);

            if($source_link && $target_link){
                $source_result = mysqli_query($source_link, 'SHOW TABLES FROM ' . $sourceDB) or die(mysqli_error($source_link));
                $log = Yii::$app->Utility->addLog('SHOW TABLES FROM ' . $sourceDB, 'create_client_db');
                // $log = Yii::$app->Utility->addLog($source_result, 'create_client_db');

                while($row = mysqli_fetch_array($source_result)) {
                    mysqli_query($target_link, 'DROP TABLE IF EXISTS `' . $row[0] . '`') or die("drop query - " . mysqli_error($target_link));
                    $log = Yii::$app->Utility->addLog("drop query", 'create_client_db');
                    
                    // create table structure
                    $create_statement = mysqli_query($source_link, 'SHOW CREATE TABLE `' . $row[0] . '`') or die("SHOW CREATE TABLE - " . mysqli_error($source_link));
                    $log = Yii::$app->Utility->addLog("SHOW CREATE TABLE `" . $row[0] . "`", 'create_client_db');
                    $create_table = mysqli_fetch_array($create_statement);
                    // $log = Yii::$app->Utility->addLog($create_table, 'create_client_db');
                    
                    mysqli_next_result($target_link);
                    mysqli_query($target_link, $create_table[1]) or die("create table - " . mysqli_error($target_link));
                    // $log = Yii::$app->Utility->addLog($create_table[1], 'create_client_db');
                    mysqli_next_result($target_link);
                    
                    // insert table data
                    /* $insert_statement = mysqli_query($source_link, 'SELECT * FROM `' . $row[0] . '`') or die(mysqli_error($source_link));
                    $insert_table = mysqli_fetch_array($insert_statement);
                    
                    mysqli_next_result($target_link);
                    mysqli_query($target_link, $insert_table[1]) or die(mysqli_error($target_link));
                    mysqli_next_result($target_link); */
                    if($create_table[0]=='disp_list')
                    {
                        $result         = mysqli_query($source_link,"SELECT * FROM $create_table[0]"); // select all content 
                        $com            = '';
                        $i              = 0;
                        $dynamic_query   = '';
                        while ($rows = mysqli_fetch_assoc($result) ) {     
                            // use multi-insert 
                            if($i==0)
                            {
                                $dynamic_query   ="INSERT INTO $create_table[0] (".implode(", ",array_keys($rows)).") VALUES ";
                            }

                            if($i<=50)
                            {
                                $dynamic_query   .=$com."('".implode("', '",array_values($rows))."')";
                                $com            = ', ';
                            }

                            $i++;// increament value of i.

                            if($i==50)
                            {
                                mysqli_query($target_link,$dynamic_query) or die(mysqli_error($target_link));
                                $com            = '';
                                $i              = 0;
                                $dynamic_query   = '';
                            }               
                        }
                        
                        if($i>0)
                        {
                            mysqli_query($target_link,$dynamic_query)or die(mysqli_error($target_link));
                        }
                    }

                    // mysqli_query($target_link, 'CREATE TABLE `' . $targetDB . '`.`' . $row[0] . '` LIKE `' . $sourceDB . '`.`' . $row[0] . '`') or die(mysqli_error($target_link));
                    // mysqli_query($target_link, 'INSERT INTO `' . $targetDB . '`.`' . $row[0] . '` SELECT * FROM `' . $sourceDB . '`.`' . $row[0] . '`') or die(mysqli_error($target_link));
                    mysqli_query($target_link, 'OPTIMIZE TABLE `' . $row[0] . '`') or die(mysqli_error($target_link));
                }
                
                // delete targetDB SP for APR is existing
                // mysqli_query($target_link, 'USE `'. $targetDB . '`; DROP procedure IF EXISTS `'. $targetDB . '`.`usp_apr_aj`;') or die(mysqli_error($target_link));
                
                // create targetDB SP for APR
                /* $sp_query = "
                    DELIMITER $$
                    USE `".$targetDB."`$$
                    CREATE DEFINER=`dbadmin`@`%` PROCEDURE `".$targetDB."`.`usp_apr_aj`( puser_id varchar(1000), pcampaign varchar(1000),pstarttime varchar(200),penddate varchar(100))
                    BEGIN
                    
                    SELECT Date,'00:00-24:00' as 'Interval',agent_name as 'Agent',report.agent_id as 'agent_id',agent_calls as 'total_calls',Inbound_Answered,Outbound_Answered, '' as Login_Time
                    ,sec_TO_time(not_ready) as 'Not Ready','' as 'Idle Duration',
                    Ring_Duration,
                    Talk_Duration,
                    Hold_Duration,
                    Wrap_Up_Duration 
                    FROM
                    (select count(abc.agent_id) as 'agent_calls',abc.agent_name as 'agent_name', abc.agent_id as 'agent_id',Date(insert_date) as 'Date',convert(sec_to_time(sum(abc.ringing)),time) as 'Ring_Duration',
                    convert(sec_to_time(sum(abc.talk)),time) as 'Talk_Duration',
                    convert(sec_to_time(sum(abc.hold)),time) as 'Hold_Duration',
                    convert(sec_to_time(sum(abc.wrap)),time) as 'Wrap_Up_Duration',
                    COUNT(case when call_type=2 then abc.agent_id end) as 'Inbound_Answered',
                    COUNT(case when call_type IN (1,3,7,8) then abc.agent_id end) as 'Outbound_Answered'
                    from vaani_agent_call_report abc where abc.call_type IN (1,2,3,7,8) and abc.insert_date between pstarttime and penddate group by abc.agent_id)
                    report 
                    left join (SELECT agent_id,
                        SUM(duration) as not_ready
                        FROM vaani_break_log 
                            WHERE date(start_time) between pstarttime and pstarttime GROUP BY vaani_break_log.agent_id) vbl
                            on report.agent_id = vbl.agent_id group by report.agent_id;
                    
                    
                    END$$
                    
                    DELIMITER ;
                "; */
                // $sp_query = addslashes($sp_query);
                // mysqli_query($target_link, $sp_query) or die(mysqli_error($target_link));

                mysqli_free_result($source_result);
                mysqli_close($source_link);
                return 'success';
            }
            return "Connection failed";
        }
		return "No client Found!";
    }

    public function getTest()
    {
        return $this->client_id;
    }
}
