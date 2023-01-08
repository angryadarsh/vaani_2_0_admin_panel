<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "edas_dni_master".
 *
 * @property int $id
 * @property string $client_id
 * @property string|null $DNI_from
 * @property string|null $DNI_to
 * @property string|null $DNI_other
 * @property string|null $DNI_prefix
 * @property string|null $DNI_name
 * @property string|null $carrier_name
 * @property string|null $service_outbound_max_days
 * @property string|null $service_outbound_max_attempts_total
 * @property string|null $service_outbound_max_attempts_per_day
 * @property string|null $service_outbound_eod_processed_till
 * @property string|null $service_outbound_mix_fresh_retry
 * @property string|null $service_outbound_lead_expire_fail_flag
 * @property string|null $service_leadstructure_attempts_tablename
 * @property string|null $service_outbound_msc_code
 * @property string|null $service_outbound_max_attempts_per_day_flag
 * @property string|null $service_server_numbers
 * @property string|null $service_sms_mode
 * @property string|null $service_email_mode
 * @property string|null $service_chat_mode
 * @property string|null $service_social_media_mode
 * @property string|null $service_transfer_inbound_flow
 * @property string|null $service_transfer_outbound_flow
 * @property string|null $service_template_id_disposition
 * @property string|null $service_template_id_lookup
 * @property string|null $service_template_id_module
 * @property string|null $service_template_id_zone
 * @property string|null $service_template_id_crm
 * @property string|null $service_ini_parameters
 * @property string|null $created_by
 * @property string|null $created_date
 * @property string|null $created_ip
 * @property string|null $modified_by
 * @property string|null $modified_date
 * @property string|null $modified_ip
 * @property string|null $last_activity
 * @property string|null $change_set
 * @property string|null $service_outbound_days
 * @property string|null $service_grp_id
 * @property string|null $service_crmmanager_enabled
 * @property string|null $service_non_voice_outbound_url
 * @property string|null $service_non_voice_outbound_days
 * @property string|null $service_non_voice_outbound_start_time
 * @property string|null $service_non_voice_outbound_end_time
 * @property int|null $service_non_voice_outbound_autogap_between_2_calls
 * @property int|null $service_non_voice_outbound_wrapup_time
 * @property string|null $service_non_voice_outbound_autowrapup_disp_code
 * @property string|null $service_email_timeover_autoreply_template_id
 * @property string|null $service_email_lead_selection_criteria
 * @property string|null $service_email_lead_selection_criteria_cb
 * @property string|null $service_non_voice_lead_selection_name_cb
 * @property string|null $service_non_voice_lead_selection_id_cb
 * @property string|null $service_email_queue_selection_criteria_cb
 * @property string|null $service_email_actions_allowed
 * @property string|null $service_email_compose_features
 * @property string|null $service_email_compose_attachment_enabled
 * @property string|null $service_email_compose_max_attachment_size
 * @property string|null $service_email_compose_max_attachment_count
 * @property string|null $service_email_compose_attachment_file_types
 * @property string|null $service_email_compose_mailbox_enabled
 * @property string|null $service_email_compose_default_mailbox_id
 * @property string|null $service_email_compose_template_enabled
 * @property string|null $service_email_compose_cannedmsg_enabled
 * @property string|null $service_email_signature_id
 * @property string|null $service_email_compose_method
 * @property string|null $service_current_leadmaintenance_schedule_id
 * @property string|null $service_next_leadmaintenance_schedule_id
 * @property string|null $service_leadmaintenance_auto_repeat
 * @property string|null $service_outbound_lead_db_path_ldf
 * @property string|null $service_activity_id
 * @property int $del_status
 */
class EdasDniMaster extends \yii\db\ActiveRecord
{
    public $dni_type;

    // delete status
    const STATUS_NOT_DELETED = 1;
    const STATUS_PERMANENT_DELETED = 2;
    const STATUS_TEMPORARY_DELETED = 3;

    public static $delete_statuses = [
        self::STATUS_NOT_DELETED => 'Not Deleted',
        self::STATUS_PERMANENT_DELETED => 'Permanent Deleted',
        self::STATUS_TEMPORARY_DELETED => 'Temporary Deleted',
    ];

    // delete status
    const TYPE_RANGE = 1;
    const TYPE_OTHER = 2;

    public static $dni_types = [
        self::TYPE_OTHER => 'DNI Single',
        self::TYPE_RANGE => 'DNI Range',
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
        return 'edas_dni_master';
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
            [['client_id', 'DNI_name', 'carrier_name', 'dni_type'], 'required', 'on' => 'create_update'],
            // [['DNI_name', 'carrier_name'], 'unique'],
            [['created_date', 'modified_date', 'service_non_voice_outbound_start_time', 'service_non_voice_outbound_end_time'], 'safe'],
            [['service_non_voice_outbound_autogap_between_2_calls', 'service_non_voice_outbound_wrapup_time', 'del_status'], 'integer'],
            [['client_id'], 'string', 'max' => 50],
            [['DNI_prefix', 'DNI_name', 'carrier_name', 'service_outbound_max_days', 'service_outbound_max_attempts_total', 'service_outbound_max_attempts_per_day', 'service_outbound_eod_processed_till', 'service_outbound_mix_fresh_retry', 'service_outbound_lead_expire_fail_flag', 'service_leadstructure_attempts_tablename', 'service_outbound_msc_code', 'service_outbound_max_attempts_per_day_flag', 'service_server_numbers', 'service_sms_mode', 'service_email_mode', 'service_chat_mode', 'service_social_media_mode', 'service_transfer_inbound_flow', 'service_transfer_outbound_flow', 'service_template_id_disposition', 'service_template_id_lookup', 'service_template_id_module', 'service_template_id_zone', 'service_template_id_crm', 'service_ini_parameters', 'created_by', 'created_ip', 'modified_by', 'modified_ip', 'last_activity', 'change_set', 'service_outbound_days', 'service_grp_id', 'service_crmmanager_enabled', 'service_non_voice_outbound_url', 'service_non_voice_outbound_days', 'service_non_voice_outbound_autowrapup_disp_code', 'service_email_timeover_autoreply_template_id', 'service_email_lead_selection_criteria', 'service_email_lead_selection_criteria_cb', 'service_non_voice_lead_selection_name_cb', 'service_non_voice_lead_selection_id_cb', 'service_email_queue_selection_criteria_cb', 'service_email_actions_allowed', 'service_email_compose_features', 'service_email_compose_attachment_enabled', 'service_email_compose_max_attachment_size', 'service_email_compose_max_attachment_count', 'service_email_compose_attachment_file_types', 'service_email_compose_mailbox_enabled', 'service_email_compose_default_mailbox_id', 'service_email_compose_template_enabled', 'service_email_compose_cannedmsg_enabled', 'service_email_signature_id', 'service_email_compose_method', 'service_current_leadmaintenance_schedule_id', 'service_next_leadmaintenance_schedule_id', 'service_leadmaintenance_auto_repeat', 'service_outbound_lead_db_path_ldf', 'service_activity_id'], 'string', 'max' => 45],
            [['DNI_other', 'DNI_from', 'DNI_to', ], 'string', 'max' => 10, 'min' => 10],
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
            'DNI_from' => 'Dni From',
            'DNI_to' => 'Dni To',
            'DNI_other' => 'Dni Number',
            'DNI_prefix' => 'Dni Prefix',
            'DNI_name' => 'Dni Name',
            'carrier_name' => 'Carrier Name',
            'service_outbound_max_days' => 'Service Outbound Max Days',
            'service_outbound_max_attempts_total' => 'Service Outbound Max Attempts Total',
            'service_outbound_max_attempts_per_day' => 'Service Outbound Max Attempts Per Day',
            'service_outbound_eod_processed_till' => 'Service Outbound Eod Processed Till',
            'service_outbound_mix_fresh_retry' => 'Service Outbound Mix Fresh Retry',
            'service_outbound_lead_expire_fail_flag' => 'Service Outbound Lead Expire Fail Flag',
            'service_leadstructure_attempts_tablename' => 'Service Leadstructure Attempts Tablename',
            'service_outbound_msc_code' => 'Service Outbound Msc Code',
            'service_outbound_max_attempts_per_day_flag' => 'Service Outbound Max Attempts Per Day Flag',
            'service_server_numbers' => 'Service Server Numbers',
            'service_sms_mode' => 'Service Sms Mode',
            'service_email_mode' => 'Service Email Mode',
            'service_chat_mode' => 'Service Chat Mode',
            'service_social_media_mode' => 'Service Social Media Mode',
            'service_transfer_inbound_flow' => 'Service Transfer Inbound Flow',
            'service_transfer_outbound_flow' => 'Service Transfer Outbound Flow',
            'service_template_id_disposition' => 'Service Template Id Disposition',
            'service_template_id_lookup' => 'Service Template Id Lookup',
            'service_template_id_module' => 'Service Template Id Module',
            'service_template_id_zone' => 'Service Template Id Zone',
            'service_template_id_crm' => 'Service Template Id Crm',
            'service_ini_parameters' => 'Service Ini Parameters',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'created_ip' => 'Created Ip',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'modified_ip' => 'Modified Ip',
            'last_activity' => 'Last Activity',
            'change_set' => 'Change Set',
            'service_outbound_days' => 'Service Outbound Days',
            'service_grp_id' => 'Service Grp ID',
            'service_crmmanager_enabled' => 'Service Crmmanager Enabled',
            'service_non_voice_outbound_url' => 'Service Non Voice Outbound Url',
            'service_non_voice_outbound_days' => 'Service Non Voice Outbound Days',
            'service_non_voice_outbound_start_time' => 'Service Non Voice Outbound Start Time',
            'service_non_voice_outbound_end_time' => 'Service Non Voice Outbound End Time',
            'service_non_voice_outbound_autogap_between_2_calls' => 'Service Non Voice Outbound Autogap Between 2 Calls',
            'service_non_voice_outbound_wrapup_time' => 'Service Non Voice Outbound Wrapup Time',
            'service_non_voice_outbound_autowrapup_disp_code' => 'Service Non Voice Outbound Autowrapup Disp Code',
            'service_email_timeover_autoreply_template_id' => 'Service Email Timeover Autoreply Template ID',
            'service_email_lead_selection_criteria' => 'Service Email Lead Selection Criteria',
            'service_email_lead_selection_criteria_cb' => 'Service Email Lead Selection Criteria Cb',
            'service_non_voice_lead_selection_name_cb' => 'Service Non Voice Lead Selection Name Cb',
            'service_non_voice_lead_selection_id_cb' => 'Service Non Voice Lead Selection Id Cb',
            'service_email_queue_selection_criteria_cb' => 'Service Email Queue Selection Criteria Cb',
            'service_email_actions_allowed' => 'Service Email Actions Allowed',
            'service_email_compose_features' => 'Service Email Compose Features',
            'service_email_compose_attachment_enabled' => 'Service Email Compose Attachment Enabled',
            'service_email_compose_max_attachment_size' => 'Service Email Compose Max Attachment Size',
            'service_email_compose_max_attachment_count' => 'Service Email Compose Max Attachment Count',
            'service_email_compose_attachment_file_types' => 'Service Email Compose Attachment File Types',
            'service_email_compose_mailbox_enabled' => 'Service Email Compose Mailbox Enabled',
            'service_email_compose_default_mailbox_id' => 'Service Email Compose Default Mailbox ID',
            'service_email_compose_template_enabled' => 'Service Email Compose Template Enabled',
            'service_email_compose_cannedmsg_enabled' => 'Service Email Compose Cannedmsg Enabled',
            'service_email_signature_id' => 'Service Email Signature ID',
            'service_email_compose_method' => 'Service Email Compose Method',
            'service_current_leadmaintenance_schedule_id' => 'Service Current Leadmaintenance Schedule ID',
            'service_next_leadmaintenance_schedule_id' => 'Service Next Leadmaintenance Schedule ID',
            'service_leadmaintenance_auto_repeat' => 'Service Leadmaintenance Auto Repeat',
            'service_outbound_lead_db_path_ldf' => 'Service Outbound Lead Db Path Ldf',
            'service_activity_id' => 'Service Activity ID',
            'del_status' => 'Del Status',
        ];
    }

    // get client
    public function getClient() {
        return $this->hasOne(VaaniClientMaster::className(), ['client_id' => 'client_id'])->andOnCondition(['del_status' => VaaniClientMaster::STATUS_NOT_DELETED]);
    }

    // get queue associated with the dni
    public function getQueue() {
        return $this->hasOne(VaaniCampaignQueue::className(), ['dni_id' => 'id'])->andOnCondition(['del_status' => VaaniCampaignQueue::STATUS_NOT_DELETED]);
    }

    // fetch list of dnis
    public static function dniList($ids=null, $client_id=null)
    {
        $query = self::find()
            ->select(['id', 'DNI_name', 'DNI_from', 'DNI_to', 'DNI_other', 'DNI_prefix', 'carrier_name'])
            ->where(['del_status' => self::STATUS_NOT_DELETED]);

        if($ids){
            $query->andWhere(['IN', 'id', $ids]);
        }
        if($client_id){
            $query->andWhere(['client_id' => $client_id]);
        }
            
        return $query->all();
    }

    // fetch list of unused dnis
    public static function unusedDniList($client_id=null)
    {
        $used_ids = [];
        $all_dni_ids = ArrayHelper::getColumn(self::dniList(null, $client_id), 'id');
        $campaigns = EdasCampaign::find()->where(['in', 'campaign_dni', $all_dni_ids])->andWhere(['del_status' => EdasCampaign::STATUS_NOT_DELETED])->all();
        $queues = VaaniCampaignQueue::find()->where(['in', 'dni_id', $all_dni_ids])->andWhere(['del_status' => VaaniCampaignQueue::STATUS_NOT_DELETED])->all();

        if($campaigns)
            $used_ids = ArrayHelper::getColumn($campaigns, 'campaign_dni');
        
        if($queues)
            $used_ids = array_merge($used_ids, ArrayHelper::getColumn($queues, 'dni_id'));
        
        $query = self::find()
            ->select(['id', 'DNI_name', 'DNI_from', 'DNI_to', 'DNI_other', 'DNI_prefix', 'carrier_name'])
            ->where(['del_status' => self::STATUS_NOT_DELETED]);

        if($client_id){
            $query->andWhere(['client_id' => $client_id]);
        }

        if($used_ids){
            $query->andWhere(['NOT IN', 'id', $used_ids]);
        }
            
        return $query->asArray()->all();
    }
}
