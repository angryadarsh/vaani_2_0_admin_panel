<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_lead_batch".
 *
 * @property int $id
 * @property string|null $campaign_id
 * @property string|null $queue_id
 * @property string|null $batch_name
 * @property string|null $batch_id
 * @property string|null $mapping_id
 * @property string|null $crm_group_id
 * @property int|null $is_previously_mapped
 * @property int|null $is_rechurn
 * @property string|null $rechurn_id
 * @property string|null $call_attempts
 * @property string|null $rechurn_time
 * @property string|null $last_dial_datetime
 * @property int|null $is_active
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property int|null $del_status
 */
class VaaniLeadBatch extends \yii\db\ActiveRecord
{
    public $upload_lead_file, $dispositions, $filter_query_rechurn,$query;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    public static $statuses = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_lead_batch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign_id', 'batch_name'], 'required'],
            [['is_previously_mapped', 'is_active', 'del_status', 'is_rechurn'], 'integer'],
            [['batch_id', 'mapping_id', 'crm_group_id', 'date_created', 'date_modified', 'created_by', 'modified_by', 'batch_name', 'call_attempts', 'rechurn_time', 'last_dial_datetime', 'rechurn_id'], 'string', 'max' => 45],
            [['date_created', 'date_modified', 'created_ip', 'modified_ip', 'campaign_id', 'queue_id', 'dispositions', 'filter_query_rechurn','query'], 'safe'],
            [['upload_lead_file'], 'file', 'extensions' => 'xls, xlsx, csv'],
            ['del_status', 'default', 'value' => User::STATUS_NOT_DELETED],
            ['is_active', 'default', 'value' => self::STATUS_INACTIVE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_created',
                'updatedAtAttribute' => 'date_modified',
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'batch_id' => 'Batch ID',
            'mapping_id' => 'Mapping ID',
            'crm_group_id' => 'Crm Group ID',
            'is_previously_mapped' => 'Is Previously Mapped',
            'is_active' => 'Is Active',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'del_status' => 'Del Status',
        ];
    }

    // fetch campaign model
    public function getCampaign()
    {
        return $this->hasOne(EdasCampaign::className(), ['campaign_id' => 'campaign_id'])->andOnCondition(['del_status' => EdasCampaign::STATUS_NOT_DELETED]);
    }
    
    //get rechurn dispositions
    public function getRechurnDispositions() {
        return $this->hasMany(VaaniRechurnDispositions::className(), ['batch_id' => 'batch_id'])->andOnCondition(['del_status' => VaaniDispositions::STATUS_NOT_DELETED]);
    }
    //get dail count
    public function getDialcount(){
        return $this->hasMany(VaaniDialLeads::className(), ['batch_id' => 'batch_id'])->andOnCondition(['del_status' => User::STATUS_NOT_DELETED]);
        //if we used user then we don't need to define at top

    }
    //get total count
    public function getTotalcount(){
        return $this->hasMany(VaaniLeadDump::className(), ['batch_id' => 'batch_id'])->andOnCondition(['del_status' => User::STATUS_NOT_DELETED]);
        //if we used user then we don't need to define at top
    }
}
