<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;
use phpseclib3\Net\SFTP;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vaani_campaign_queue".
 *
 * @property int $id
 * @property string|null $queue
 * @property string|null $queue_name
 * @property string|null $queue_id
 * @property string|null $campaign_id
 * @property string|null $dni_id
 * @property string|null $criteria
 * @property string|null $created_date
 * @property string|null $modified_date
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property string|null $del_status
 * @property string|null $is_conference
 * @property string|null $is_transfer
 * @property string|null $is_consult
 * @property string|null $is_manual
 * @property string|null $key_input
 * @property string|null $type
 * @property string|null $call_window
 * @property string|null $manual_call_window
 * @property string|null $hold_music
 * @property string|null $sound_music
 * @property string|null $call_timeout
 */
class VaaniCampaignQueue extends \yii\db\ActiveRecord
{
    public $is_conference, $is_transfer, $is_consult, $is_manual, $hold_music_file;

    // delete status
    const STATUS_NOT_DELETED = 1;
    const STATUS_PERMANENT_DELETED = 2;
    const STATUS_TEMPORARY_DELETED = 3;

    public static $delete_statuses = [
        self::STATUS_NOT_DELETED => 'Not Deleted',
        self::STATUS_PERMANENT_DELETED => 'Permanent Deleted',
        self::STATUS_TEMPORARY_DELETED => 'Temporary Deleted',
    ];

    // Criterias
    const CRITERIA_RING_ALL = 'ringall';
    const CRITERIA_LEAST_RECENT = 'leastrecent';
    const CRITERIA_FEWEST_CALLS = 'fewestcalls';
    const CRITERIA_RANDOM = 'random';
    const CRITERIA_ROUND_ROBIN_MEMORY = 'rrmemory';
    const CRITERIA_ROUND_ROBIN_ORDERED = 'rrordered';
    const CRITERIA_LINEAR = 'linear';
    const CRITERIA_RINGS_RANDOM = 'wrandom';

    public static $queue_criterias = [
        self::CRITERIA_RING_ALL => 'Ring All',
        self::CRITERIA_LEAST_RECENT => 'Least Recent',
        self::CRITERIA_FEWEST_CALLS => 'Fewest Calls',
        self::CRITERIA_RANDOM => 'Random',
        self::CRITERIA_ROUND_ROBIN_MEMORY => 'Round Robin Memory',
        self::CRITERIA_ROUND_ROBIN_ORDERED => 'Round Robin Ordered',
        self::CRITERIA_LINEAR => 'Linear',
        self::CRITERIA_RINGS_RANDOM => 'Rings Random',
    ];

    // Access
    const ACCESS_YES = '1';
    const ACCESS_NO = '2';

    public static $access_values = [
        self::ACCESS_NO => 'No',
        self::ACCESS_YES => 'Yes',
    ];

    // Types
    const TYPE_QUEUE = '1';
    const TYPE_IVR = '2';

    public static $types = [
        self::TYPE_QUEUE => 'Queue',
        self::TYPE_IVR => 'Ivr',
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
        return 'vaani_campaign_queue';
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
            [['queue', 'queue_id'], 'unique'],
            [['queue', 'queue_name', 'campaign_id', 'dni_id', 'criteria', 'created_date', 'modified_date', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 50],
            [['queue', 'dni_id', 'criteria', 'is_conference', 'is_transfer', 'is_consult', 'is_manual', 'del_status', 'type', 'call_window', 'manual_call_window', 'hold_music', 'call_timeout'], 'safe'],
            [['hold_music_file'], 'file', 'extensions' => 'wav'],
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
            'queue' => 'Queue',
            'campaign_id' => 'Campaign ID',
            'dni_id' => 'Dni ID',
            'criteria' => 'Criteria',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->queue) {
            $data = strtolower(trim($this->queue));
            $data = str_replace(" ", "_", $data);
            $this->queue = $data;
        }
        return parent::beforeSave($insert);
    }

    // get campaign
    public function getCampaign() {
        return $this->hasOne(EdasCampaign::className(), ['campaign_id' => 'campaign_id']);
    }

    // get campaign
    public function getDni() {
        return $this->hasOne(EdasDniMaster::className(), ['id' => 'dni_id'])->andOnCondition(['del_status' => EdasDniMaster::STATUS_NOT_DELETED]);
    }

    // get call access
    public function getCallAccess() {
        return $this->hasOne(VaaniCallAccess::className(), ['queue_id' => 'queue_id'])->andOnCondition(['del_status' => self::STATUS_NOT_DELETED]);
    }

    // get call window
    public function getCallWindow() {
        return $this->hasOne(VaaniCallTimesConfig::className(), ['id' => 'call_window'])->andOnCondition(['del_status' => VaaniCallTimesConfig::STATUS_NOT_DELETED]);
    }

    // get manual call window
    public function getManualCallWindow() {
        return $this->hasOne(VaaniCallTimesConfig::className(), ['id' => 'manual_call_window'])->andOnCondition(['del_status' => VaaniCallTimesConfig::STATUS_NOT_DELETED]);
    }

    // get user active queues
    public function getUserActiveQueues() {
        return VaaniUserActiveQueues::find()->where([ 'OR' ,
            ['q1' => $this->queue ],
            ['q2' => $this->queue ],
            ['q3' => $this->queue ],
            ['q4' => $this->queue ],
            ['q5' => $this->queue ],
            ['q6' => $this->queue ],
            ['q7' => $this->queue ],
            ['q8' => $this->queue ],
            ['q9' => $this->queue ],
            ['q10' => $this->queue ],
        ])->asArray()->all();
    }

    // fetch campaigns's queues
    public static function queuesList($campaign_ids=null, $type=null, $queue_ids=null)
    {
        $query = VaaniCampaignQueue::find()
            // ->select(['queue_id', 'queue', 'queue_name', 'dni_id', 'edas_campaign_id.campaign_id', 'edas_campaign_id.mode'])
            ->innerJoinWith('campaign')
            ->where(['vaani_campaign_queue.del_status' => self::STATUS_NOT_DELETED]);
        
        if($campaign_ids)
            $query->andWhere(['IN', 'vaani_campaign_queue.campaign_id', $campaign_ids])->andWhere(['NOT IN', 'vaani_campaign_queue.campaign_id', '']);
        if($type)
            $query->andFilterWhere(['IN', 'vaani_campaign_queue.type', $type]);
        if($queue_ids)
            $query->andFilterWhere(['IN', 'queue_id', $queue_ids]);
            
        return $query->all();
    }

    // fetch queues's users
    public static function usersList($campaign_ids=null, $queue_ids=null, $role_id=null, $client_id=null)
    {
        $query = VaaniUserAccess::find()
            ->select(['vaani_user.user_id', 'vaani_user.user_name'])
            ->innerJoinWith('user')
            ->where(['vaani_user_access.del_status' => self::STATUS_NOT_DELETED]);
        
        if($client_id){
            $query->andWhere(['IN', 'vaani_user_access.client_id', $client_id]);
        }
        if($campaign_ids){
            $query->andWhere(['IN', 'vaani_user_access.campaign_id', $campaign_ids]);
        }
        if($queue_ids){
            $query->andFilterWhere(['IN', 'vaani_user_access.queue_id', $queue_ids]);
        }
        if($role_id){
            $query->andFilterWhere(['IN', 'vaani_user_access.role_id', $role_id]);
        }
            
        return $query->groupBy('user_id')->all();
    }

    public static function usersInActiveList($campaign_ids=null, $queue_ids=null, $role_id=null)
    {
        $active_users = self::usersList($campaign_ids, $queue_ids);
        $active_user_status = ArrayHelper::getColumn($active_users, 'user_id');
        $query = VaaniUserAccess::find()
            ->select(['vaani_user.user_id','vaani_user.user_name','vaani_user_access.del_status'])
            ->innerJoinWith('user')
            //  ->where(['vaani_user_access.del_status' => self::STATUS_PERMANENT_DELETED])
            ->andWhere(['NOT IN', 'vaani_user_access.user_id', $active_user_status]);
            
        if($campaign_ids){
            $query->andWhere(['IN', 'vaani_user_access.campaign_id', $campaign_ids]);
        }
        if($queue_ids){
            $query->andFilterWhere(['IN', 'vaani_user_access.queue_id', $queue_ids]);
        }
        if($role_id){
            $query->andFilterWhere(['IN', 'vaani_user_access.role_id', $role_id]);
        }

        return $query->groupBy('user_id')->all();
    }

    // write asterisk file for the campaign-queue add/edit/delete
    public function asterisk_write($action, $prev_queue = null, $prev_dni_id = null, $prev_call_window = null, $prev_manual_call_window = null, $prev_client_conf = null, $prev_camp = null, $prev_timeout = null)
    {
        // configration file of remote server
        $sftp = new SFTP(Yii::$app->params['IP_ADDRESS']);
        if (!$sftp->login(Yii::$app->params['USER_ID'], Yii::$app->params['PASSWORD'])) {
            exit('Login Failed');
        }
        
        // request file extension
        $myFile = $_SERVER['DOCUMENT_ROOT']  . '/edas_vaani/backend/web/files/extensions_context_temp.txt';      // predefine temp copy file.
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
        $data = strtolower(trim($this->queue));
        $data = str_replace(" ", "_", $data);
        $data_time = (($this->callWindow && $this->callWindow->daysData) ? ($data . '_time') : $data);
        $data_manual_time = (($this->manualCallWindow && $this->manualCallWindow->daysData) ? ($data . '_manual_time') : $data);
        $campaign_name = strtolower(trim($this->campaign->campaign_name));
        $campaign_name = str_replace(" ", "_", $campaign_name);
        // fetch client conf file name
        $client_conf = (($this->campaign && $this->campaign->client && $this->campaign->client->conf_file) ? strtolower($this->campaign->client->conf_file) : null);
        // previous data
        $prev_data = strtolower(trim($prev_queue));
        $prev_data = str_replace(" ", "_", $prev_data);
        $prev_data_time = $prev_data . '_time';
        $prev_data_manual_time = $prev_data . '_manual_time';

        $timeout_update = false;
        if($prev_timeout != $this->call_timeout){
            $timeout_update = true;
        }

        // static context for the testing.
        // $data = 'eos';
        $exts = 'pl';  // pre define extension

        // tmp_context for append in file.
        $tmp_context_header = ';context_start'.PHP_EOL;
        // call window data for queue
        $tmp_context = '';
        $tmp_context .= ';'.$data.'_start'.PHP_EOL;
        if($this->callWindow && $this->callWindow->daysData){
            $tmp_context .= '['.$data_time.']'.PHP_EOL;
            $tmp_context .= 'exten => _x.,1,NoOp(In '.$data.' time zone)'.PHP_EOL;
            $tmp_context .= 'exten => _x.,n,Set(_CALLTIME=${STRFTIME(,'.$this->callWindow->time_zone.',%d-%b-%y-%H-%M-%S)})'.PHP_EOL;
            // add daywise data with time range
            foreach ($this->callWindow->daysData as $key => $day_model) {
                $tmp_context .= 'exten => _x.,n,GotoIfTime('.$day_model->start_time.'-'.$day_model->end_time.','.$day_model->day_id.',*,*?'.$data.',_x.,1)'.PHP_EOL;
            }
            $tmp_context .= 'exten => _x.,n,Playback(Ivr/edas/edas_support_non_working_hours)'.PHP_EOL;
            $tmp_context .= 'exten => _x.,n,Hangup()'.PHP_EOL.PHP_EOL;
        }
        /* if($this->manualCallWindow && $this->manualCallWindow->daysData){
            $tmp_context .= '['.$data_manual_time.']'.PHP_EOL;
            $tmp_context .= 'exten => _x.,1,NoOp(In '.$data.' time zone)'.PHP_EOL;
            $tmp_context .= 'exten => _x.,n,Set(_CALLTIME=${STRFTIME(,'.$this->manualCallWindow->time_zone.',%d-%b-%y-%H-%M-%S)})'.PHP_EOL;
            // add daywise data with time range
            foreach ($this->manualCallWindow->daysData as $key => $day_model) {
                $tmp_context .= 'exten => _x.,n,GotoIfTime('.$day_model->start_time.'-'.$day_model->end_time.','.$day_model->day_id.',*,*?'.$data.',_x.,1)'.PHP_EOL;
            }
            $tmp_context .= 'exten => _x.,n,Playback(Ivr/edas/edas_support_non_working_hours)'.PHP_EOL;
            $tmp_context .= 'exten => _x.,n,Hangup()'.PHP_EOL.PHP_EOL;
        } */
        $context_name = $data;
        if($this->campaign->campaign_type == EdasCampaign::TYPE_OUTBOUND){
            if($this->campaign->campaign_sub_type == EdasCampaign::MODE_PROGRESSIVE)
            {
                $context_name = $context_name . '_progressive';
            }elseif($this->campaign->campaign_sub_type == EdasCampaign::MODE_PREDICTIVE){
                $context_name = $context_name . '_predictdial';
            }
        }
        $tmp_context .= '['.$context_name.']'.PHP_EOL;
        $tmp_context .= 'exten => _x.,1,NoOp(Started '.$context_name.' context)'.PHP_EOL;
        $tmp_context .= 'exten => _x.,n,Set(CID=${CALLERID(num)})'.PHP_EOL;
        $tmp_context .= 'exten => _x.,n,Set(_unique_id=${UNIQUEID})'.PHP_EOL;
        $tmp_context .= 'exten => _x.,n,Set(_caller_channel=${CHANNEL})'.PHP_EOL;
        
        if($this->campaign->campaign_type == EdasCampaign::TYPE_OUTBOUND && ($this->campaign->campaign_sub_type == EdasCampaign::MODE_PROGRESSIVE || $this->campaign->campaign_sub_type == EdasCampaign::MODE_PREDICTIVE))
        {
            $tmp_context .= 'exten => _x.,n,Set(_dial_id=${dial_id})'.PHP_EOL;
            $tmp_context .= 'exten => _x.,n,Set(_lead_id=${lead_id})'.PHP_EOL;

            if($this->campaign->campaign_sub_type == EdasCampaign::MODE_PREDICTIVE){
                $tmp_context .= 'exten => _x.,n,Set(_campaign_name=${campaign_name})'.PHP_EOL;
                $tmp_context .= 'exten => _x.,n,Set(_queue_name=${queue_name})'.PHP_EOL;
            }
            $tmp_context .= 'exten => _x.,n,Set(_customer_no=${customer_no})'.PHP_EOL;
        }
        
        if($this->campaign->campaign_type == EdasCampaign::TYPE_INBOUND){
            $tmp_context .= 'exten => _x.,n,Set(_start_time=${CDR(start)})'.PHP_EOL;
        }else{
            $tmp_context .= 'exten => _x.,n,Set(_start_time=${start_time})'.PHP_EOL;
        }

        $tmp_context .= 'exten => _x.,n,Set(_conf_num=${RAND(9001,9999)})'.PHP_EOL;
        
        if($this->campaign->campaign_type == EdasCampaign::TYPE_OUTBOUND){
            if($this->campaign->campaign_sub_type == EdasCampaign::MODE_PROGRESSIVE)
            {
                if($client_conf){
                    $tmp_context .= 'exten => _x.,n,AGI(progressive_dialing_start_script.pl,${CID},${UNIQUEID},${CHANNEL},${dial_id},${lead_id},'.$campaign_name.','.$data.','.$client_conf.',${start_time})'.PHP_EOL;
                }else{
                    $tmp_context .= 'exten => _x.,n,AGI(progressive_dialing_start_script.pl,${CID},${UNIQUEID},${CHANNEL},${dial_id},${lead_id},'.$campaign_name.','.$data.',,${start_time})'.PHP_EOL;
                }
            }elseif($this->campaign->campaign_sub_type == EdasCampaign::MODE_PREDICTIVE){
                if($client_conf){
                    $tmp_context .= 'exten => _x.,n,AGI(predictive_dialing_start_script.pl,${CID},${UNIQUEID},${CHANNEL},${dial_id},${lead_id},'.$campaign_name.','.$data.','.$client_conf.',${start_time})'.PHP_EOL;
                }else{
                    $tmp_context .= 'exten => _x.,n,AGI(predictive_dialing_start_script.pl,${CID},${UNIQUEID},${CHANNEL},${dial_id},${lead_id},'.$campaign_name.','.$data.',,${start_time})'.PHP_EOL;
                }
            }
        }else{
            if($client_conf){
                $tmp_context .= 'exten => _x.,n,AGI(edas_inbound_start_script.pl,${DID},${UNIQUEID},${CHANNEL},'.$campaign_name.','.$data.','.$client_conf.')'.PHP_EOL;
            }else{
                $tmp_context .= 'exten => _x.,n,AGI(edas_inbound_start_script.pl,${DID},${UNIQUEID},${CHANNEL},'.$campaign_name.','.$data.',)'.PHP_EOL;
            }
        }
        
        if($this->campaign->campaign_type == EdasCampaign::TYPE_INBOUND || ($this->campaign->campaign_type == EdasCampaign::TYPE_OUTBOUND && ($this->campaign->campaign_sub_type == EdasCampaign::MODE_PROGRESSIVE || $this->campaign->campaign_sub_type == EdasCampaign::MODE_PREDICTIVE))
        ){
            $tmp_context .= 'exten => _x.,n,Set(_recording_path= ${recording_path})'.PHP_EOL;
            
            if($this->campaign->campaign_sub_type == EdasCampaign::MODE_PROGRESSIVE){
                $tmp_context .= 'exten => _x.,n,Set(_connect_time= ${connect_time})'.PHP_EOL;
            }

            $tmp_context .= 'exten => _x.,n,NoOp('.$data.'_timeout)'.PHP_EOL;

            if($this->campaign->campaign_sub_type == EdasCampaign::MODE_PREDICTIVE){
                $tmp_context .= 'exten => _x.,n,Queue('.$data.',tT,,,'.($this->call_timeout ? $this->call_timeout : 60).',,,mymacro_predictive)'.PHP_EOL;
            }elseif($this->campaign->campaign_sub_type == EdasCampaign::MODE_PROGRESSIVE){
                $tmp_context .= 'exten => _x.,n,Queue('.$data.',tT,,,'.($this->call_timeout ? $this->call_timeout : 60).',,,mymacro_progressive)'.PHP_EOL;
            }else{
                $tmp_context .= 'exten => _x.,n,Queue('.$data.',tT,,,'.($this->call_timeout ? $this->call_timeout : 60).',,,mymacro)'.PHP_EOL;
            }
        }

        $tmp_context .= 'exten => _x.,n,Hangup()'.PHP_EOL;
        $tmp_context .='exten => h,1,Noop(Dial status  for ${CID},${UNIQUEID},${MEMBERNAME},${HANGUPCAUSE},${start_time})'.PHP_EOL;
        $tmp_context .='exten => h,n,Set(_end_time=${CDR(end)})'.PHP_EOL;
        
        if($this->campaign->campaign_type == EdasCampaign::TYPE_OUTBOUND){
            if($this->campaign->campaign_sub_type == EdasCampaign::MODE_PROGRESSIVE)
            {
                if($client_conf){
                    $tmp_context .='exten => h,n,AGI(progressive_end_duration.pl,${UNIQUEID},${start_time},${end_time},${dial_id},${lead_id},${MEMBERNAME},'.$client_conf.',${recording_path},${REASON},${connect_time},${campaign_name},${queue_name},${customer_no})'.PHP_EOL;
                }else{
                    $tmp_context .='exten => h,n,AGI(progressive_end_duration.pl,${UNIQUEID},${start_time},${end_time},${dial_id},${lead_id},${MEMBERNAME},,${recording_path},${REASON},${connect_time},${campaign_name},${queue_name},${customer_no})'.PHP_EOL;
                }
            }elseif($this->campaign->campaign_sub_type == EdasCampaign::MODE_PREDICTIVE){
                if($client_conf){
                    $tmp_context .='exten => h,n,AGI(predictive_end_duration.pl,${UNIQUEID},${start_time},${end_time},${dial_id},${lead_id},${MEMBERNAME},'.$client_conf.',${recording_path},${REASON},${connect_time},${campaign_name},${queue_name},${customer_no})'.PHP_EOL;
                }else{
                    $tmp_context .='exten => h,n,AGI(predictive_end_duration.pl,${UNIQUEID},${start_time},${end_time},${dial_id},${lead_id},${MEMBERNAME},,${recording_path},${REASON},${connect_time},${campaign_name},${queue_name},${customer_no})'.PHP_EOL;
                }
            }
        }else{
            if($client_conf){
                $tmp_context .='exten => h,n,AGI(edas_inbound_end_duration.pl,${UNIQUEID},${start_time},${end_time},'.$client_conf.',${recording_path})'.PHP_EOL;
            }else{
                $tmp_context .='exten => h,n,AGI(edas_inbound_end_duration.pl,${UNIQUEID},${start_time},${end_time},,${recording_path})'.PHP_EOL;
            }
        }

        // add new lines - 12/08/2022 Ravinder
        if($this->campaign->campaign_type == EdasCampaign::TYPE_OUTBOUND && ($this->campaign->campaign_sub_type == EdasCampaign::MODE_PROGRESSIVE || $this->campaign->campaign_sub_type == EdasCampaign::MODE_PREDICTIVE)){
            $tmp_context .= 'exten = failed,1,NoOp(The call failed)'.PHP_EOL;
            $tmp_context .= 'exten = failed,n,GotoIf($[${REASON}=5]?busy)'.PHP_EOL;
            $tmp_context .= 'exten = failed,n,GotoIf($[${REASON}=8]?congested)'.PHP_EOL;
            $tmp_context .= 'exten = failed,n,GotoIf($[${REASON}=0]?Failed)'.PHP_EOL;
            $tmp_context .= 'exten = failed,n,GotoIf($[${REASON}=1]?HungUp)'.PHP_EOL;
            $tmp_context .= 'exten = failed,n,GotoIf($[${REASON}=3]?Ring TimeOut)'.PHP_EOL;
        }
        
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
                $tmp_routing_ext .= 'exten => _+x.,n,GotoIf($["${called_number}"="'.$r_no.'"]?'.$data_time.',_x.,1)'.PHP_EOL;
            }
        }elseif(!empty($DNI_other)){
            $expl_dni = explode(',', $DNI_other);
            foreach ($expl_dni as $key => $value) {
                $e_value = ltrim($value, "0");
                $tmp_routing_ext .= 'exten => _+x.,n,GotoIf($["${called_number}"="'.$e_value.'"]?'.$data_time.',_x.,1)'.PHP_EOL;
            }
        }

        if(!empty($this->queue) && $action == 'add')
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

                    // delete the dnis if exists previously
                    if(!empty($DNI_from) && !empty($DNI_to))
                    {
                        foreach ($routingline as $key => $value) 
                        {
                            for ($i = $DNI_from; $i <= $DNI_to ; $i++) {
                                $str = ltrim($i, "0");
                                if ((strpos($value,$str) > '-1') && strpos($value, "exten") == '0') 
                                {
                                    unset($routingline[$key]);
                                }
                            }
                        }
                    }elseif(!empty($DNI_other))	// DNI other 
                    {
                        foreach ($routingline as $key => $value)
                        {
                            if((strpos($value, '"'.$DNI_other.'"') > '-1') && strpos($value, "exten")=='0')
                            {
                                unset($routingline[$key]);
                            }
                        }
                    }

                    foreach ($routingline as $key => $value) {
                        if(trim($value)== ';did_routing_end'){
                            $chk = $key;
                            $value = $tmp_routing_ext . $value;
                        }
                        fputs($writing,$value);
                    }
                    $res = $sftp->put(Yii::$app->params['remo_extension_url'], $routingFile, SFTP::SOURCE_LOCAL_FILE);
                }
                fclose($writing);
            }

            // in case of remote server have empty file.
            if(empty($lines)){
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
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
            if(!empty($this->queue) && !empty($prev_queue))
		    {
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                foreach ($lines as $key => $value) 
                {
                    if (strpos($value, $prev_data) > '-1') 
                    {
                        $value = str_replace($prev_data, $data, $value);
                    }
                    fputs($writing,$value);
                }
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_context_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);

                $writing = fopen($routingFile, 'w') or die("Unable to open file!:".$routingFile."-w");
                foreach ($routingline as $key => $value)
                {
                    if (strpos($value, $prev_data) > '-1')
                    {
                        $value = str_replace($prev_data, $data, $value);
                    }
                    fputs($writing,$value);
                }
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_extension_url'], $routingFile, SFTP::SOURCE_LOCAL_FILE);
            }

            // based on previous DNI 
            $apn_inx = '';
            $extens_writing = fopen($routingFile, 'w') or die("Unable to open file!:".$routingFile."-w");

            if(!empty($prev_dni_id))
            {
                $prev_dni = EdasDniMaster::find()->where(['id' => $prev_dni_id])->asArray()->one();
                $prev_DNI_from  = trim($prev_dni['DNI_from']);
                $prev_DNI_to    = trim($prev_dni['DNI_to']);
                $prev_DNI_other = trim($prev_dni['DNI_other']);

                if(!empty($prev_DNI_from) && !empty($prev_DNI_to))
                {
                    foreach ($routingline as $key => $value) 
                    {
                        for ($i = $prev_DNI_from; $i <= $prev_DNI_to ; $i++) {
                            $str = ltrim($i, "0");
                            if (((strpos($value,$str) > '-1') || (strpos($value, '?'.$prev_data_time.',') > '-1') || (strpos($value,$DNI_from) > '-1') || (strpos($value,$DNI_to) > '-1')) && strpos($value, "exten") == '0') 
                            {
                                if(empty($apn_inx)) {
                                    $apn_inx = $key-1;
                                }
                                unset($routingline[$key]);
                            }
                        }
                    }
                }elseif(!empty($prev_DNI_other))	// DNI other 
                {
                    $apn_inx = '';
                    foreach ($routingline as $key => $value)
                    {
                        if(((strpos($value, '"'.$prev_DNI_other.'"') > '-1') || (strpos($value, '?'.$prev_data_time.',') > '-1') || (strpos($value, '"'.$DNI_other.'"') > '-1')) && strpos($value, "exten")=='0')
                        {
                            if(empty($apn_inx)) {
                                $apn_inx = $key-1;
                            }
                            unset($routingline[$key]);
                        }
                    }
                }
            }
            foreach ($routingline as $key => $value) {
                if($apn_inx){
                    if($key == $apn_inx) {
                        $chk = $key;
                        $value= $value.$tmp_routing_ext;
                    }
                }else{
                    if(trim($value)== ';did_routing_end'){
                        $chk = $key;
                        $value = $tmp_routing_ext . $value;
                    }
                }
                fputs($extens_writing,$value);
            }
            fclose($extens_writing);
            $res = $sftp->put(Yii::$app->params['remo_extension_url'], $routingFile, SFTP::SOURCE_LOCAL_FILE);

            // edit call_window
            if(!empty($prev_call_window)){
                $edit_context = '';
                if($this->callWindow && $this->callWindow->daysData){
                    $edit_context .= '['.$data_time.']'.PHP_EOL;
                    $edit_context .= 'exten => _x.,1,NoOp(In '.$data.' time zone)'.PHP_EOL;
                    $edit_context .= 'exten => _x.,n,Set(_CALLTIME=${STRFTIME(,'.$this->callWindow->time_zone.',%d-%b-%y-%H-%M-%S)})'.PHP_EOL;
                    // add daywise data with time range
                    foreach ($this->callWindow->daysData as $key => $day_model) {
                        $edit_context .= 'exten => _x.,n,GotoIfTime('.$day_model->start_time.'-'.$day_model->end_time.','.$day_model->day_id.',*,*?'.$data.',_x.,1)'.PHP_EOL;
                    }
                    $edit_context .= 'exten => _x.,n,Playback(Ivr/edas/edas_support_non_working_hours)'.PHP_EOL;
                    $edit_context .= 'exten => _x.,n,Hangup()'.PHP_EOL.PHP_EOL;
                }

                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                $count = 0;
                foreach ($lines as $key => $value)
                {
                    if (strpos($value, '['.$prev_data_time.']')>'-1' && $count==0) {
                        $count=1;
                        continue;
                    }
                    if($count==0) {
                        fputs($writing,$value);
                    }
                    if($count==1 && (strpos($value, 'Hangup()') > '-1')) {
                        $count=0;

                        // on last line of the time context, add new call window context.
                        fputs($writing,$edit_context);
                    }
                }
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_context_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
            }

            // edit manual_call_window
            /* if(!empty($prev_manual_call_window)){
                $edit_context = '';
                if($this->manualCallWindow && $this->manualCallWindow->daysData){
                    $edit_context .= '['.$data_manual_time.']'.PHP_EOL;
                    $edit_context .= 'exten => _x.,1,NoOp(In '.$data.' time zone)'.PHP_EOL;
                    $edit_context .= 'exten => _x.,n,Set(_CALLTIME=${STRFTIME(,'.$this->manualCallWindow->time_zone.',%d-%b-%y-%H-%M-%S)})'.PHP_EOL;
                    // add daywise data with time range
                    foreach ($this->manualCallWindow->daysData as $key => $day_model) {
                        $edit_context .= 'exten => _x.,n,GotoIfTime('.$day_model->start_time.'-'.$day_model->end_time.','.$day_model->day_id.',*,*?'.$data.',_x.,1)'.PHP_EOL;
                    }
                    $edit_context .= 'exten => _x.,n,Playback(Ivr/edas/edas_support_non_working_hours)'.PHP_EOL;
                    $edit_context .= 'exten => _x.,n,Hangup()'.PHP_EOL.PHP_EOL;
                }

                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                $count = 0;
                foreach ($lines as $key => $value)
                {
                    if (strpos($value, '['.$prev_data_manual_time.']')>'-1' && $count==0) {
                        $count=1;
                        continue;
                    }
                    if($count==0) {
                        fputs($writing,$value);
                    }
                    if($count==1 && (strpos($value, 'Hangup()') > '-1')) {
                        $count=0;

                        // on last line of the time context, add new manual call window context.
                        fputs($writing,$edit_context);
                    }
                }
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_context_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
            } */

            // edit client conf name
            if($timeout_update && !empty($prev_timeout)){
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                foreach ($lines as $key => $value) 
                {
                    if (isset($lines[$key-1]) && strpos($lines[$key-1], $data . '_timeout') > '-1') 
                    {
                        if($this->campaign->campaign_type == EdasCampaign::TYPE_OUTBOUND && $this->campaign->campaign_sub_type == EdasCampaign::MODE_PREDICTIVE){
                            $value = 'exten => _x.,n,Queue('.$data.',tT,,,'.($this->call_timeout ? $this->call_timeout : 60).',,,mymacro_predictive)'.PHP_EOL;
                        }else{
                            $value = 'exten => _x.,n,Queue('.$data.',tT,,,'.($this->call_timeout ? $this->call_timeout : 60).',,,mymacro_progressive)'.PHP_EOL;
                        }
                    }
                    fputs($writing,$value);
                }
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_context_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
            }

            // edit client conf name
            if(!empty($prev_client_conf)){
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                foreach ($lines as $key => $value) 
                {
                    if (strpos($value, $prev_client_conf) > '-1') 
                    {
                        $value = str_replace($prev_client_conf, $client_conf, $value);
                    }
                    fputs($writing,$value);
                }
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_context_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
            }

            User::reload_call();
            echo "complete";
        }
        elseif(!empty($this->queue) && $action == 'delete')
        {
            // extension-edas.conf 
            $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");	
            $count = 0;
            $time_count = 0;
            $context_data = '';
            foreach ($lines as $key => $value)
            {
                // call window time context
                if (strpos($value, ';'.$data.'_start')>'-1' && $count == 0) {
                    $count = 1;
                    continue;
                }
                if($count == 0 && ($key == 0 || (isset($lines[$key-1]) && !(strpos($lines[$key-1], ';'.$data.'_end') > '-1')))) {
                    fputs($writing,$value);
                }
                if($count == 1 && (strpos($value, ';'.$data.'_end') > '-1')) {
                    $count = 0;
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
            echo "Queue not found!";
        }
    }

    // write queue file for the campaign-queue add/edit/delete
    public function queue_write($action, $prev_queue = null, $prev_criteria = null)
    {
        // configration file of remote server
        $sftp = new SFTP(Yii::$app->params['IP_ADDRESS']);
        if (!$sftp->login(Yii::$app->params['USER_ID'], Yii::$app->params['PASSWORD'])) {
            exit('Login Failed');
        }
        
        // request file extension
        $myFile = $_SERVER['DOCUMENT_ROOT']  . '/edas_vaani/backend/web/files/queues_temp.txt';      // predefine temp copy file.
        
        $res = $sftp->get(Yii::$app->params['remo_queue_url'], $myFile);        // copy file from remote server to local server
        $lines = file($myFile);                             //file in to an array
        
        // create backup file
        $backupFileName = Yii::$app->params['asterisk_backup'] . '/queues_' . time() . '.conf';
        copy(Yii::$app->params['remo_queue_url'], $backupFileName);        // copy file from remote server to local server

        // request update data file name.
        $data = strtolower(trim($this->queue));
        $data = str_replace(" ", "_", $data);

        // tmp_context for append in file.
        $tmp_context = '['.$data.']'.PHP_EOL;
        $tmp_context .= 'strategy = '.$this->criteria.PHP_EOL;
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

        if(!empty($this->queue) && $action == 'add')
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
            // edit queue
            if(!empty($this->queue) && !empty($prev_queue))
		    {
                $writing = fopen($myFile, 'w') or die("Unable to open file!:".$myFile."-w");
                $str = 0;
                foreach ($lines as $key => $value) 
                {
                    if (strpos($value, $prev_queue) > '-1') 
                    {
                        $value = str_replace($prev_queue, $data, $value);
                        $str = 1;
                    }

                    if (strpos($value, 'wav49') > '-1') 
                    {
                        $str = 0;
                    }

                    // edit criteria
                    if($str == 1)
                    {
                        if (strpos($value, $prev_criteria) > '-1') 
                        {
                            $value = str_replace($prev_criteria, $this->criteria, $value);
                        }  
                    }
                    fputs($writing,$value);
                }	
                fclose($writing);
                $res = $sftp->put(Yii::$app->params['remo_queue_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);
            }

            User::reload_call();
            echo "complete";
        }
        elseif(!empty($this->queue) && $action == 'delete')
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
                if($count==1 && strpos($value, 'wav49') > '-1') {
                    $count=0;
                }
            }
            fclose($writing);
            $res = $sftp->put(Yii::$app->params['remo_queue_url'], $myFile, SFTP::SOURCE_LOCAL_FILE);		   	
            // end queue.conf

            User::reload_call();
            echo "success";
        }
        else{
            echo "Queue not found!";
        }
    }

    // add sound folder in /var/lib/asterisk/sounds/Ivr/edas
    public function add_sound_folder($action, $prev_queue = null)
    {
        $campaign_name = strtolower(trim($this->campaign->campaign_name));
        $campaign_name = str_replace(" ", "_", $campaign_name);

        $campaign_folder = Yii::$app->params['sound_path'] . '/' . $campaign_name;

        // create campaign folder if does not exists
        if (!file_exists($campaign_folder)) {
            $mask=umask(0);
            if(mkdir($campaign_folder, 0777, true)){
                umask($mask);
            }
        }else{
            // set permission to the existing folder
            chmod($campaign_folder, 0777);
        }

        if(!empty($this->queue) && $action == 'add')
        {
            if (!file_exists($campaign_folder . '/' . $this->queue)) {
                $maskadd=umask(0);
                if(mkdir($campaign_folder . '/' . $this->queue, 0777, true)){
                    umask($maskadd);
                    EdasCampaign::dir_copy_delete($action, Yii::$app->params['sound_path'] . '/sales' , $campaign_folder . '/' . $this->queue);
                }
            }
        }else if($action == 'edit'){
            if(!empty($this->queue) && !empty($prev_queue) && ($this->queue != $prev_queue)){
                rename($campaign_folder . '/' . $prev_queue, $campaign_folder . '/' . $this->queue);
            }
        }else if ($action == 'delete'){
            if (file_exists($campaign_folder . '/' . $this->queue)) {
                EdasCampaign::dir_copy_delete($action, $campaign_folder . '/' . $this->queue);
            }
        }
    }

    // NOT IN USE
    // create campaign queue name file in /uc/config/ini
    public function add_queue_file($action, $prev_queue = null)
    {
        if(!empty($this->queue)){
            // configration file of remote server
            $sftp = new SFTP(Yii::$app->params['IP_ADDRESS']);
            if (!$sftp->login(Yii::$app->params['USER_ID'], Yii::$app->params['PASSWORD'])) {
                exit('Login Failed');
            }
            
            $queue_name = strtolower(trim($this->queue));
            $queue_name = str_replace(" ", "_", $queue_name);

            $queue_file = Yii::$app->params['queue_ini_path'] . '/' . $queue_name . '.conf';

            $ini_temp_file = $_SERVER['DOCUMENT_ROOT']  . '/edas_vaani/backend/web/files/queue_ini.txt';

            // create file content
            $data = '';
            $data .= '['.$queue_name.']'.PHP_EOL;
            $data .= 'campaign_id = 1'.PHP_EOL;
            $data .= 'campaign_active = 1'.PHP_EOL;
            $data .= 'language_enable = 1'.PHP_EOL;
            $data .= 'language_option = 2'.PHP_EOL;
            $data .= 'total_dtmf_input = 2'.PHP_EOL;
            $data .= 'timeout = 5000'.PHP_EOL;
            $data .= 'timeout_count = 2'.PHP_EOL;
            $data .= 'invalid_count = 2'.PHP_EOL;
            $data .= 'timeout_file_present = 0'.PHP_EOL;
            $data .= 'invalid_file_present = 0'.PHP_EOL;
            $data .= 'queue_no = 2'.PHP_EOL;
            $data .= "queue_name_1 = 'sales'".PHP_EOL;
            $data .= "queue_name_2 = 'support'".PHP_EOL;
            $data .= 'max_allowed_digit = 1'.PHP_EOL;
            $data .= "host = 127.0.0.1".PHP_EOL;
            $data .= 'database = asterisk'.PHP_EOL;
            $data .= 'username = root'.PHP_EOL;
            $data .= 'password = Mys@roja2021'.PHP_EOL;
            $data .= 'serverip=172.16.152.50'.PHP_EOL;
            $data .= 'operator = airtelsip'.PHP_EOL;

            if($action == 'delete'){
                // delete file
                unlink($queue_file);
            }else {
                $is_new = true;
                if($action == 'edit'){
                    if($this->queue == $prev_queue){
                        $is_new = false;
                    }else{
                        // delete old file
                        unlink($queue_file);
                    }
                }

                if($is_new){
                    $main_file = fopen($queue_file, 'w') or die("Unable to open file!:".$queue_file."-w");

                    $writing = fopen($ini_temp_file, 'w') or die("Unable to open file!:".$ini_temp_file."-w");

                    fputs($writing,$data);
                    $res = $sftp->put($queue_file, $ini_temp_file, SFTP::SOURCE_LOCAL_FILE);
                    fclose($writing);
                    fclose($main_file);
                }
            }

            User::reload_call();
            echo "complete";
        }
        else{
            echo "Queue not found!";
        }
    }
}
