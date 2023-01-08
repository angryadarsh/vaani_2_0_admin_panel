<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;
use phpseclib3\Net\SFTP;
use yii\helpers\Url;

/**
 * This is the model class for table "edas_campaign_id".
 *
 * @property int $id
 * @property string $campaign_id
 * @property string|null $campaign_name
 * @property string|null $client_id
 * @property string|null $campaign_dni
 * @property string|null $campaign_type
 * @property string|null $campaign_sub_type
 * @property string|null $call_medium
 * @property string|null $campaign_status
 * @property string|null $service_supervisior_user_level
 * @property int|null $campaign_inbound_ivr
 * @property string|null $campaign_inbound_timeover_url
 * @property string|null $campaign_inbound_timeover_flow
 * @property string|null $campaign_inbound_days
 * @property string|null $campaign_inbound_start_time
 * @property string|null $campaign_inbound_end_time
 * @property string|null $campaign_inbound_agent_selection_criteria
 * @property string|null $campaign_sticky_agent
 * @property string|null $campaign_inbound_wrapup_time
 * @property string|null $campaign_inbound_auto_wrapup_disp
 * @property string|null $campaign_inbound_blacklisted_url
 * @property string|null $campaign_inbound_blacklisted_flow
 * @property string|null $campaign_inbound_clicktohelp
 * @property string|null $campaign_sms_mode
 * @property string|null $campaign_email_mode
 * @property string|null $campaign_chat_mode
 * @property string|null $campaign_whatsapp_mode
 * @property string|null $created_by
 * @property string $created_date
 * @property string|null $created_ip
 * @property string|null $modified_by
 * @property string|null $modified_date
 * @property string|null $modified_ip
 * @property string|null $last_activity
 * @property string|null $service_level_calls
 * @property string|null $service_level_seconds
 * @property string|null $sub_disposition
 * @property int|null $del_status
 * @property int|null $is_dtmf
 * @property int|null $is_ivr_queue
 * @property string|null $key_input
 * @property string|null $call_window
 * @property string|null $manual_call_window
 * @property string|null $mode
 * @property string|null $preview_upload
 * @property string|null $preview_time
 * @property string|null $outbound_criteria
 * @property string|null $pacing_value
 * @property string|null $abandoned_percent
 * @property string|null $prev_type
 * @property string|null $hopper_count
 * @property string|null $operator_id
 * @property string|null $call_timeout
 * @property string|null $disposition_plan_id
 * @property string|null $qms_id
 */
class EdasCampaign extends \yii\db\ActiveRecord
{
    public $cloneid; //create a cloneid variable just like id 
    public $campaign_operators;
    public $logoFile;

    // delete status
    const STATUS_NOT_DELETED = 1;
    const STATUS_PERMANENT_DELETED = 2;
    const STATUS_TEMPORARY_DELETED = 3;

    public static $delete_statuses = [
        self::STATUS_NOT_DELETED => 'Not Deleted',
        self::STATUS_PERMANENT_DELETED => 'Permanent Deleted',
        self::STATUS_TEMPORARY_DELETED => 'Temporary Deleted',
    ];

    // campaign status
    const CAMPAIGN_ACTIVE = 1;
    const CAMPAIGN_INACTIVE = 2;

    public static $campaign_statuses = [
        self::CAMPAIGN_ACTIVE => 'Active',
        self::CAMPAIGN_INACTIVE => 'Inactive',
    ];

    // campaign modes
    const MODE_NORMAL_PREVIEW = 1;
    const MODE_TIME_PREVIEW = 2;
    const MODE_PROGRESSIVE = 3;
    const MODE_PREDICTIVE = 4;
    // const MODE_AUTO_PREVIEW = 4;

    public static $campaign_modes = [
        self::MODE_NORMAL_PREVIEW => 'Normal Preview',
        self::MODE_TIME_PREVIEW => 'Time Preview',
        self::MODE_PROGRESSIVE => 'Progressive',
        self::MODE_PREDICTIVE => 'Predictive',
    ];

    // Campaign Inbound Agent Selection Criteria
    const CRITERIA_RING_ALL = 'ringall';
    const CRITERIA_LEAST_RECENT = 'leastrecent';
    const CRITERIA_FEWEST_CALLS = 'fewestcalls';
    const CRITERIA_RANDOM = 'random';
    const CRITERIA_ROUND_ROBIN_MEMORY = 'rrmemory';
    const CRITERIA_ROUND_ROBIN_ORDERED = 'rrordered';
    const CRITERIA_LINEAR = 'linear';
    const CRITERIA_RINGS_RANDOM = 'wrandom';

    public static $campaign_criterias = [
        self::CRITERIA_RING_ALL => 'Ring All',
        self::CRITERIA_LEAST_RECENT => 'Least Recent',
        self::CRITERIA_FEWEST_CALLS => 'Fewest Calls',
        self::CRITERIA_RANDOM => 'Random',
        self::CRITERIA_ROUND_ROBIN_MEMORY => 'Round Robin Memory',
        self::CRITERIA_ROUND_ROBIN_ORDERED => 'Round Robin Ordered',
        self::CRITERIA_LINEAR => 'Linear',
        self::CRITERIA_RINGS_RANDOM => 'Rings Random',
    ];

    // Campaign Types
    const TYPE_MANUAL = 1;
    const TYPE_INBOUND = 2;
    const TYPE_OUTBOUND = 3;
    const TYPE_BLENDED = 4;

    public static $campaign_types = [
        self::TYPE_INBOUND => 'Inbound',
        self::TYPE_OUTBOUND => 'Outbound',
        self::TYPE_BLENDED => 'Blended',
        // self::TYPE_MANUAL => 'Manual',
    ];

    // Call Mediums
    const MEDIUM_QUEUE = 1;
    const MEDIUM_IVR = 2;

    public static $call_mediums = [
        self::MEDIUM_QUEUE => 'Queue',
        self::MEDIUM_IVR => 'IVR',
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
        return 'edas_campaign_id';
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
            [['client_id', 'campaign_name', 'campaign_type'], 'required'],
            [['campaign_inbound_ivr', 'del_status', 'preview_time'], 'integer'],
            [['campaign_inbound_wrapup_time', 'created_date', 'modified_date', 'last_activity', 'campaign_inbound_days', 'call_window', 'manual_call_window', 'mode', 'preview_upload', 'call_medium', 'campaign_sub_type', 'outbound_criteria', 'prev_type', 'campaign_operators', 'call_timeout', 'disposition_plan_id', 'cloneid','logoFile', 'qms_id'], 'safe'],
            [['pacing_value'], 'number'],
            [['abandoned_percent', 'hopper_count', 'operator_id'],  'string', 'max' => 20],
            [['campaign_id', 'created_ip', 'modified_ip', 'sub_disposition'], 'string', 'max' => 50],
            [['campaign_name', 'campaign_inbound_agent_selection_criteria', 'campaign_inbound_auto_wrapup_disp', 'campaign_inbound_blacklisted_url', 'campaign_inbound_blacklisted_flow', 'service_level_calls'], 'string', 'max' => 100],
            [['client_id', 'campaign_dni', 'campaign_status', 'service_supervisior_user_level', 'campaign_inbound_timeover_url', 'campaign_inbound_timeover_flow', 'campaign_inbound_start_time', 'campaign_inbound_end_time', 'campaign_sticky_agent', 'campaign_inbound_clicktohelp', 'campaign_sms_mode', 'campaign_email_mode', 'campaign_chat_mode', 'campaign_whatsapp_mode', 'created_by', 'modified_by'], 'string', 'max' => 45],
            [['service_level_seconds'], 'string', 'max' => 10],
            [['campaign_id', 'campaign_name'], 'unique'],
            [['campaign_name'], 'match', 'pattern' => '/^[a-zA-Z0-9_]*$/', 'message' => 'Campaign Name cannot contain special characters & space, except underscore.'],
            [['key_input'], 'checkKeyInputFormat'],
            ['campaign_sub_type', 'required', 'when' => function ($model) {
                return $model->campaign_type == self::TYPE_OUTBOUND;
            }, 'whenClient' => "function (attribute, value) {
                return $('#edascampaign-campaign_type').val() == 3;
            }"],
            ['del_status', 'default', 'value' => self::STATUS_NOT_DELETED],
            ['campaign_status', 'default', 'value' => self::CAMPAIGN_ACTIVE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'campaign_id' => 'Campaign ID',
            'campaign_name' => 'Campaign Name',
            'client_id' => 'Client ID',
            'campaign_dni' => 'Campaign Dni',
            'campaign_status' => 'Campaign Status',
            'service_supervisior_user_level' => 'Service Supervisior User Level',
            'campaign_inbound_ivr' => 'Campaign Inbound Ivr',
            'campaign_inbound_timeover_url' => 'Campaign Inbound Timeover Url',
            'campaign_inbound_timeover_flow' => 'Campaign Inbound Timeover Flow',
            'campaign_inbound_days' => 'Campaign Inbound Days',
            'campaign_inbound_start_time' => 'Campaign Inbound Start Time',
            'campaign_inbound_end_time' => 'Campaign Inbound End Time',
            'campaign_inbound_agent_selection_criteria' => 'Campaign Inbound Agent Selection Criteria',
            'campaign_sticky_agent' => 'Campaign Sticky Agent',
            'campaign_inbound_wrapup_time' => 'Campaign Inbound Wrapup Time',
            'campaign_inbound_auto_wrapup_disp' => 'Campaign Inbound Auto Wrapup Disp',
            'campaign_inbound_blacklisted_url' => 'Campaign Inbound Blacklisted Url',
            'campaign_inbound_blacklisted_flow' => 'Campaign Inbound Blacklisted Flow',
            'campaign_inbound_clicktohelp' => 'Campaign Inbound Clicktohelp',
            'campaign_sms_mode' => 'Campaign Sms Mode',
            'campaign_email_mode' => 'Campaign Email Mode',
            'campaign_chat_mode' => 'Campaign Chat Mode',
            'campaign_whatsapp_mode' => 'Campaign Whatsapp Mode',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'created_ip' => 'Created Ip',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'modified_ip' => 'Modified Ip',
            'last_activity' => 'Last Activity',
            'service_level_calls' => 'Service Level Calls',
            'service_level_seconds' => 'Service Level Seconds',
            'sub_disposition' => 'Sub Disposition',
            'del_status' => 'Del Status',
        ];
    }

    public function checkKeyInputFormat($attribute, $params)
    {
        if(preg_match("/![a-z]/i", $this->key_input)){
            $this->addError($attribute, 'Incorrect Key input format alpha!');
        }else{
            for($i=1; $i <= strlen($this->key_input); $i++){
                if($i < strlen($this->key_input) && $this->key_input[$i] != '~' && isset($this->key_input[$i+1]) && $this->key_input[$i+1] != '~'){
                    $this->addError($attribute, 'Incorrect Key input format!' . $this->key_input[$i]);
                }
            }
        }
    }

    public function afterFind()
    {
        parent::afterFind();

        if($this->campaign_inbound_days){
            $this->campaign_inbound_days = array_filter(explode(',', $this->campaign_inbound_days));
        }
        if ($this->campaign_inbound_start_time) {
            $this->campaign_inbound_start_time = substr_replace($this->campaign_inbound_start_time, ':', -2, 0 );
        }
        if ($this->campaign_inbound_end_time) {
            $this->campaign_inbound_end_time = substr_replace($this->campaign_inbound_end_time, ':', -2, 0 );
        }
        if ($this->disposition_plan_id) {
            // $this->disposition_plan_id = User::encrypt_data($this->disposition_plan_id);
        }
    }

    public function beforeSave($insert)
    {
        if ($this->campaign_inbound_days) {
            $this->campaign_inbound_days = implode(',', $this->campaign_inbound_days);
        }
        if ($this->campaign_inbound_start_time) {
            $this->campaign_inbound_start_time = str_replace(':', '', $this->campaign_inbound_start_time);
        }
        if ($this->campaign_inbound_end_time) {
            $this->campaign_inbound_end_time = str_replace(':', '', $this->campaign_inbound_end_time);
        }
        if ($this->disposition_plan_id) {
            $this->disposition_plan_id = User::decrypt_data($this->disposition_plan_id);
        }
        return parent::beforeSave($insert);
    }

    // get client
    public function getClient() {
        return $this->hasOne(VaaniClientMaster::className(), ['client_id' => 'client_id'])->andOnCondition(['del_status' => VaaniClientMaster::STATUS_NOT_DELETED]);
    }

    // get client's role access
    public function getDni() {
        return $this->hasOne(EdasDniMaster::className(), ['id' => 'campaign_dni'])->andOnCondition(['del_status' => EdasDniMaster::STATUS_NOT_DELETED]);
    }

    // get campaign's queues
    public function getAllQueues() {
        return $this->hasMany(VaaniCampaignQueue::className(), ['campaign_id' => 'campaign_id'])->andOnCondition(['del_status' => VaaniCampaignQueue::STATUS_NOT_DELETED]);
    }

    // get campaign's queues
    public function getQueues() {
        return $this->hasMany(VaaniCampaignQueue::className(), ['campaign_id' => 'campaign_id'])->andOnCondition(['del_status' => VaaniCampaignQueue::STATUS_NOT_DELETED])->andWhere(['type' => VaaniCampaignQueue::TYPE_QUEUE]);
    }

    // get campaign's ivr queues
    public function getIvrQueues() {
        return $this->hasMany(VaaniCampaignQueue::className(), ['campaign_id' => 'campaign_id'])->andOnCondition(['del_status' => VaaniCampaignQueue::STATUS_NOT_DELETED])->andWhere(['type' => VaaniCampaignQueue::TYPE_IVR]);
    }

    // get call access
    public function getCallAccess() {
        return $this->hasOne(VaaniCallAccess::className(), ['campaign_id' => 'campaign_id'])->andOnCondition(['del_status' => self::STATUS_NOT_DELETED])->andWhere(['queue_id' => null]);
    }

    // get call access
    public function getCampaignActiveQueues() {
        return $this->hasOne(VaaniCampaignActiveQueues::className(), ['campaign' => 'campaign_name']);
    }

    // get breaks
    public function getBreaks() {
        return $this->hasMany(VaaniCampaignBreak::className(), ['campaign_id' => 'campaign_id'])->andOnCondition(['del_status' => VaaniCampaignBreak::STATUS_NOT_DELETED])->andWhere(['is_active' => VaaniCampaignBreak::STATUS_ACTIVE]);
    }
    
    //get disposition - OLD - NOT IN USE
    public function getDispositions() {
        return $this->hasMany(VaaniDispositions::className(), ['campaign_id' => 'campaign_id'])->andOnCondition(['del_status' => VaaniDispositions::STATUS_NOT_DELETED])->andWhere(['parent_id' => NULL])->orderBy('sequence');
    }
    
    //get all dispositions - OLD - NOT IN USE
    public function getAllDispositions() {
        return $this->hasMany(VaaniDispositions::className(), ['campaign_id' => 'campaign_id'])->andOnCondition(['del_status' => VaaniDispositions::STATUS_NOT_DELETED])->orderBy('sequence');
    }

    // get disposition plan
    public function getPlanDisposition()
    {
        return $this->hasOne(VaaniDispositionPlan::className(), ['plan_id' => 'disposition_plan_id']);
    }

    // fetch client's campaigns
    public static function campaignsList($campaign_id=null, $client_id=null, $campaign_type=null)
    {
        $query = self::find()
            ->select(['campaign_id', 'campaign_name'])
            ->where(['del_status' => self::STATUS_NOT_DELETED]);
        
        if($campaign_id)
            $query->andWhere(['IN', 'campaign_id', $campaign_id])->andWhere(['NOT IN', 'campaign_id', '']);
        if($client_id)
            $query->andFilterWhere(['IN', 'client_id', $client_id]);
        if($campaign_type)
            $query->andFilterWhere(['IN', 'campaign_type', $campaign_type]);
            
        return $query->all();
    }

    // write asterisk file for the campaign add/edit
    public function asterisk_write($action, $prev_campaign_name = null, $prev_dni_id = null)
    {
        // configration file of remote server
        $sftp = new SFTP(Yii::$app->params['IP_ADDRESS']);
        if (!$sftp->login(Yii::$app->params['USER_ID'], Yii::$app->params['PASSWORD'])) {
            exit('Login Failed');
        }
        
        // request file extension
        // $myFile = "extensions_context.txt";              // predefine copy file.
        $myFile = $_SERVER['DOCUMENT_ROOT']  . '/edas_vaani/backend/web/files/extensions_context_temp.txt';      // predefine temp copy file.
        // $routingFile = "extensions_routing.txt";
        $routingFile = $_SERVER['DOCUMENT_ROOT']  . '/edas_vaani/backend/web/files/extensions_routing_temp.txt';
        
        $res = $sftp->get(Yii::$app->params['remo_context_url'], $myFile);         // copy file from remote server to local server
        $res = $sftp->get(Yii::$app->params['remo_extension_url'], $routingFile);       // copy file from remote server to local server
        
        $lines = file($myFile);                             //file in to an array
        $routingline = file($routingFile);

        // create backup file
        $backupFileName = Yii::$app->params['asterisk_backup'] . '/extensions-edas_' . time() . '.conf';
        copy(Yii::$app->params['remo_context_url'], $backupFileName);        // copy file from remote server to local server
        $backupFileName = Yii::$app->params['asterisk_backup'] . '/extensions_' . time() . '.conf';
        copy(Yii::$app->params['remo_extension_url'], $backupFileName);        // copy file from remote server to local server

        // request update data file name.
        $data = strtolower(trim($this->campaign_name));
        $data = str_replace(" ", "_", $data);
        // static context for the testing.
        // $data = 'eos';
        $exts = 'pl';  // pre define extension

        // tmp_context for append in file.
        $tmp_context_header = ';context_start'.PHP_EOL;
        $tmp_context = '['.$data.']'.PHP_EOL;
        $tmp_context .= 'exten => _x.,1,NoOp(Started '.$data.' context)'.PHP_EOL;
        $tmp_context .= 'exten => _x.,n,Set(CID=${CALLERID(num)})'.PHP_EOL;
        $tmp_context .= 'exten => _x.,n,Set(_unique_id=${UNIQUEID})'.PHP_EOL;
        $tmp_context.= 'exten => _x.,n,Set(_caller_channel=${CHANNEL})'.PHP_EOL;
        $tmp_context.= 'exten => _x.,n,Set(_start_time=${CDR(start)})'.PHP_EOL;
        $tmp_context.= 'exten => _x.,n,Set(_conf_num=${RAND(9001,9999)})'.PHP_EOL;
        $tmp_context .= 'exten => _x.,n,AGI(edas_inbound_start_script.pl,${DID},${UNIQUEID},${CHANNEL}'.$data.')'.PHP_EOL;
        $tmp_context .= 'exten => _x.,n,Hangup()'.PHP_EOL;
        $tmp_context .='exten => h,1,Noop(Dial status  for ${CID},${UNIQUEID},${MEMBERNAME},${HANGUPCAUSE},${start_time})'.PHP_EOL;
        $tmp_context .='exten => h,n,Set(_end_time=${CDR(end)})'.PHP_EOL;
        $tmp_context .='exten => h,n,AGI(edas_inbound_end_duration.pl,${UNIQUEID},${start_time},${end_time})'.PHP_EOL; 
        $tmp_context .= ';'.$data.'_end'.PHP_EOL;
        $tmp_context .= ';context_end'.PHP_EOL;

        //temp_routing 
        $tmp_routing ='[globals]'.PHP_EOL;
        $tmp_routing .='#include extensions-edas.conf'.PHP_EOL;
        $tmp_routing .= "".PHP_EOL;
        $tmp_routing .= ';did_routing_start'.PHP_EOL;
        $tmp_routing .= '[from-pstn]'.PHP_EOL;
        $tmp_routing .= 'exten=> _x.,1,Set(DID=${EXTEN})'.PHP_EOL;
        $tmp_routing .= 'exten => _x.,1,Noop(Entered Inbound setup with Plus sign)'.PHP_EOL;
        $tmp_routing .= 'exten => _x.,n,Set(called_number=${CALLERID(dnid):-10})'.PHP_EOL;

        $DNI_from = trim($this->dni ? $this->dni->DNI_from : '');
        $DNI_to = trim($this->dni ? $this->dni->DNI_to : '');
        $DNI_other = trim($this->dni ? $this->dni->DNI_other : '');

        $tmp_routing_ext ='';
        // form data
        if(!empty($DNI_from) && !empty($DNI_to)){
            for ($i = $DNI_from; $i <= $DNI_to ; $i++) { 
                $r_no = ltrim($i, "0"); 
                $tmp_routing_ext .= 'exten => _+x.,n,GotoIf($["${called_number}"="'.$r_no.'"]?'.$data.',_x.,1)'.PHP_EOL;
            }
        }elseif(!empty($DNI_other)){
            $expl_dni = explode(',', $DNI_other);
            foreach ($expl_dni as $key => $value) {
                $e_value = ltrim($value, "0");
                $tmp_routing_ext .= 'exten => _+x.,n,GotoIf($["${called_number}"="'.$e_value.'"]?'.$data.',_x.,1)'.PHP_EOL;
            }
        }

        if(!empty($this->campaign_name) && $action == 'add')
        {
            if(empty($routingline)){
                $writing = fopen($routingFile, 'w')or die("Unable to open file!:".$routingFile."-w");
                if(!empty($tmp_routing_ext)) {				
                    $tmp_routing .= $tmp_routing_ext;
                    $tmp_routing .= ';did_routing_end'.PHP_EOL;
                    fputs($writing,$tmp_routing);
                    $res = $sftp->put(Yii::$app->params['remo_extension_url'], $routingFile, SFTP::SOURCE_LOCAL_FILE);
                }		   
                fclose($writing);
            }else{
                $writing = fopen($routingFile, 'w')or die("Unable to open file!:".$routingFile."-w");
                if(!empty($tmp_routing_ext)) {
                    // $last 	= sizeof($routingline) - 1 ; 
                    // unset($routingline[$last]);
                    foreach ($routingline as $key => $value) {
                        if(trim($value)== ';did_routing_end'){
                            $chk = $key;
                            $value = $tmp_routing_ext . PHP_EOL . $value;
                        }
                        fputs($writing,$value);
                    }
                    // fputs($writing,$tmp_routing_ext);
                    $res = $sftp->put(Yii::$app->params['remo_extension_url'], $routingFile, SFTP::SOURCE_LOCAL_FILE);
                }
                fclose($writing);
            }

            // in case of remote server have empty file.
            if(empty($lines)){
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w"); 
                // fputs($writing,$tmp_context_header);
                $tmp_context_header .= $tmp_context;
                fputs($writing,$tmp_context_header);
                $res = $sftp->put(Yii::$app->params['remo_context_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
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
                $res = $sftp->put(Yii::$app->params['remo_context_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
                fclose($writing);
            }

            User::reload_call();
            echo "complete";
        }
        elseif($action == 'edit')
        {
            if(!empty($this->campaign_name) && !empty($prev_campaign_name))
		    {
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                foreach ($lines as $key => $value) 
                {
                    // if (preg_match("/^".$prev_campaign_name."/", $value)) 
                    if (strpos($value, $prev_campaign_name) > '-1') 
                    {
                        $value = str_replace($prev_campaign_name, $data, $value);
                    }
                    fputs($writing,$value);
                }	
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_context_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);

                $writing = fopen($routingFile, 'w') or die("Unable to open file!:".$routingFile."-w");
                foreach ($routingline as $key => $value)
                {
                    // if (preg_match("/^".$prev_campaign_name."/", $value))
                    if (strpos($value, $prev_campaign_name) > '-1')
                    {
                        $value = str_replace($prev_campaign_name, $data, $value);
                    }
                    fputs($writing,$value);
                }
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_extension_url'], $routingFile, SFTP::SOURCE_LOCAL_FILE);
            }

            // based on previous DNI 
            if(!empty($prev_dni_id))
            {
                $prev_dni = EdasDniMaster::find()->where(['id' => $prev_dni_id])->asArray()->one();
                $prev_DNI_from  = trim($prev_dni['DNI_from']);
                $prev_DNI_to    = trim($prev_dni['DNI_to']);
                $prev_DNI_other = trim($prev_dni['DNI_other']);

                $writing = fopen($routingFile, 'w') or die("Unable to open file!:".$routingFile."-w");

                if(!empty($prev_DNI_from) && !empty($prev_DNI_to))
                {
                    $apn_inx = '';
                    foreach ($routingline as $key => $value) 
                    {
                        for ($i = $prev_DNI_from; $i <= $prev_DNI_to ; $i++) {
                            // if (preg_match("/^".$i."/", $value))
                            $str = ltrim($i, "0");
                            if ((strpos($value,$str) > '-1' || strpos($value, '?'.$prev_data_time.',') > '-1') && strpos($value, "exten") == '0') 
                            {
                                if(empty($apn_inx)) {
                                    $apn_inx = $key-1;
                                }
                                unset($routingline[$key]);
                            }
                        }
                    }
                    foreach ($routingline as $key => $value) {
                        if($key == $apn_inx) {
                            $chk = $key;
                            $value= $value.PHP_EOL.$tmp_routing_ext;
                        }
                        fputs($writing,$value);
                    }
                }elseif(!empty($prev_DNI_other))	// DNI other 
                {
                    $apn_inx = '';
                    foreach ($routingline as $key => $value)
                    {
                        if((strpos($value, '"'.$prev_DNI_other.'"') > '-1' || strpos($value, '?'.$prev_data_time.',') > '-1') && strpos($value, "exten")=='0')
                        {
                            if(empty($apn_inx)) {
                                $apn_inx = $key-1;
                            }
                            unset($routingline[$key]);
                        }
                    }
                    foreach ($routingline as $key => $value) {
                        if($key == $apn_inx) {
                            $chk = $key;
                            $value= $value.$tmp_routing_ext;
                        }
                        fputs($writing,$value);
                    }
                }
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_extension_url'], $routingFile, SFTP::SOURCE_LOCAL_FILE);
            }

            User::reload_call();
            echo "complete";
        }
        elseif(!empty($this->campaign_name) && $action == 'delete')
        {
            // extension-edas.conf 
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
                if($count==1 && (strpos($value, ';'.$data.'_end') > '-1')) {
                    $count=0;
                }
            }
            fclose($writing);
            $res = $sftp->put(Yii::$app->params['remo_context_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);		   	
            // end extension-edas.conf

            $DNI_from  		= trim($this->dni ? $this->dni->DNI_from : '');
            $DNI_to         = trim($this->dni ? $this->dni->DNI_to : '');
            $DNI_other      = trim($this->dni ? $this->dni->DNI_other : '');

            //extension.conf
	   	    $writing = fopen($routingFile, 'w') or die("Unable to open file!:".$routingFile."-w");
            if(!empty($DNI_from) && !empty($DNI_to))
            {
                $apn_inx = '';
                foreach ($routingline as $key => $value) 
                {
                    $del =0;
                    for ($i = $DNI_from; $i <= $DNI_to; $i++) { 
                        // if (preg_match("/^".$i."/", $value)) 
                        $str = ltrim($i, "0"); 
                        if (strpos($value,$str)>'-1' && strpos($value, "exten")=='0') 
                        {
                            $del = 1;
                            break;
                        }						
                    }
                    if($del==1) {
                        continue;
                    }
                    fputs($writing,$value);					
                }
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_extension_url'], $routingFile, SFTP::SOURCE_LOCAL_FILE);
            } elseif(!empty($DNI_other))	// DNI other 
            {
                foreach ($routingline as $key => $value) 
                {
                    if(strpos($value, '"'.$DNI_other.'"')>'-1' && strpos($value, "exten")=='0') 
                    {
                        continue;
                    }
                    fputs($writing,$value);				
                }
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_extension_url'], $routingFile, SFTP::SOURCE_LOCAL_FILE);
            }
            //end extension.cond 
            User::reload_call();
            echo "success";
        }
        else
        {
            echo "Campaign not found!";
        }
    }

    // write queue file for the campaign add/edit
    public function queue_write($action, $prev_campaign_name = null, $prev_criteria = null)
    {
        // configration file of remote server
        $sftp = new SFTP(Yii::$app->params['IP_ADDRESS']);
        if (!$sftp->login(Yii::$app->params['USER_ID'], Yii::$app->params['PASSWORD'])) {
            exit('Login Failed');
        }
        
        // request file extension
        // $myFile = "extensions_context.txt";              // predefine copy file.
        $myFile = $_SERVER['DOCUMENT_ROOT']  . '/edas_vaani/backend/web/files/queues_temp.txt';      // predefine temp copy file.
        
        $res = $sftp->get(Yii::$app->params['remo_queue_url'], $myFile);        // copy file from remote server to local server
        
        $lines = file($myFile);                             //file in to an array

        // create backup file
        $backupFileName = Yii::$app->params['asterisk_backup'] . '/queues_' . time() . '.conf';
        copy(Yii::$app->params['remo_queue_url'], $backupFileName);        // copy file from remote server to local server

        // request update data file name.
        $data = strtolower(trim($this->campaign_name));
        $data = str_replace(" ", "_", $data);

        // tmp_context for append in file.
        $tmp_context = '['.$data.']'.PHP_EOL;
        $tmp_context .= 'strategy = '.$this->campaign_inbound_agent_selection_criteria.PHP_EOL;
        $tmp_context .= 'announce-frequency=10'.PHP_EOL;
        $tmp_context .= 'min-announce-frequency=10'.PHP_EOL;
        $tmp_context .= 'announce-position=10'.PHP_EOL;
        $tmp_context .= 'announce-position-limit=10'.PHP_EOL;
        $tmp_context .= 'announce-holdtime=5'.PHP_EOL;
        $tmp_context .= 'timeout =20'.PHP_EOL;
        $tmp_context .= 'setinterfacevar=yes'.PHP_EOL;
        $tmp_context .= 'setqueueentryvar=yes'.PHP_EOL;
        $tmp_context .= 'setqueuevar=yes'.PHP_EOL;
        $tmp_context .= 'monitor-format = wav49'.PHP_EOL;
        $tmp_context .= ';campaign_queue_end'.PHP_EOL;

        if(!empty($this->campaign_name) && $action == 'add')
        {
            // in case of remote server have empty file.
            if(empty($lines)){
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                fputs($writing,$tmp_context);
                $res = $sftp->put(Yii::$app->params['remo_queue_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
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
                $res = $sftp->put(Yii::$app->params['remo_queue_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
                fclose($writing);
            }

            User::reload_call();
            echo "complete";
        }
        elseif($action == 'edit')
        {
            // edit campaign name
            if(!empty($this->campaign_name) && !empty($prev_campaign_name))
		    {
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                foreach ($lines as $key => $value) 
                {
                    // if (preg_match("/^".$prev_campaign_name."/", $value)) 
                    if (strpos($value, $prev_campaign_name) > '-1') 
                    {
                        $value = str_replace($prev_campaign_name, $data, $value);
                    }
                    fputs($writing,$value);
                }	
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_queue_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
            }

            // edit criteria
            if(!empty($this->campaign_inbound_agent_selection_criteria) && !empty($prev_criteria))
		    {
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                foreach ($lines as $key => $value) 
                {
                    // if (preg_match("/^".$prev_criteria."/", $value)) 
                    if (strpos($value, $prev_criteria) > '-1') 
                    {
                        $value = str_replace($prev_criteria, $this->campaign_inbound_agent_selection_criteria, $value);
                    }
                    fputs($writing,$value);
                }	
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_queue_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
            }

            User::reload_call();
            echo "complete";
        }
        elseif(!empty($this->campaign_name) && $action == 'delete')
        {
            // extension-edas.conf 
            $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");	
            $count = 0;		
            foreach ($lines as $key => $value)
            {
                // if (preg_match("/^".$prev_cid."/", $value)) 
                if (strpos($value, '['.$data.']')>'-1' && $count==0) {
                    $count=1;
                    continue;
                }
                if($count==0) {
                    fputs($writing,$value);
                }
                if($count==1 && strpos($value, 'wav49') > '-1') {
                    $count=0;
                }
            }
            fclose($writing);
            $res = $sftp->put(Yii::$app->params['remo_queue_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);		   	
            // end extension-edas.conf

            User::reload_call();
            echo "success";
        }
        else{
            echo "Campaign not found!";
        }
    }

    // add sound folder in /var/lib/asterisk/sounds/Ivr/edas
    public function add_sound_folder($action, $prev_campaign_name = null)
    {
        $campaign_name = strtolower(trim($this->campaign_name));
        $campaign_name = str_replace(" ", "_", $campaign_name);

        $prev_campaign_name = strtolower(trim($prev_campaign_name));

        if(!empty($campaign_name) && $action == 'add')
        {
            if (!file_exists(Yii::$app->params['sound_path'] . '/' . $campaign_name)) {
                $mask=umask(0);
                if(mkdir(Yii::$app->params['sound_path'] . '/' . $campaign_name, 0777, true)){
                    umask($mask);
                    self::dir_copy_delete($action, Yii::$app->params['sound_path'] . '/sales' , Yii::$app->params['sound_path'] . '/' . $campaign_name);
                }
            }
        }else if($action == 'edit'){
            if(!empty($campaign_name) && !empty($prev_campaign_name) && ($campaign_name != $prev_campaign_name)){
                rename(Yii::$app->params['sound_path'] . '/' . $prev_campaign_name, Yii::$app->params['sound_path'] . '/' . $campaign_name);
            }
        }else if ($action == 'delete'){
            if (file_exists(Yii::$app->params['sound_path'] . '/' . $campaign_name)) {
                self::dir_copy_delete($action, Yii::$app->params['sound_path'] . '/' . $campaign_name);
            }
        }
    }

    // copy/delete all the contents from one directory to another
    public static function dir_copy_delete($action, $src, $dest=null)
    {
        // open the source directory
        $dir = opendir($src);
        if($action == 'add'){
            // Make the destination directory if not exist
            @mkdir($dest); 
        }
        // Loop through the files in source directory
        while( $file = readdir($dir) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    // Recursively calling custom copy function, for sub directory 
                    if($action == 'add'){
                        self::dir_copy_delete('add', $src . '/' . $file, $dest . '/' . $file);
                    }else if($action == 'delete'){
                        self::dir_copy_delete('delete', $src . '/' . $file);
                    }
                } 
                else {
                    if($action == 'add'){
                        copy($src . '/' . $file, $dest . '/' . $file);
                    }else if($action == 'delete'){
                        unlink($src . '/' . $file);
                    }
                }
            }
        }
        if($action == 'add'){
            closedir($dir);
        }else if($action == 'delete'){
            rmdir($src);
        }
    }
}
