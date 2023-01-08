<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_user_access".
 *
 * @property int $id
 * @property string|null $user_id
 * @property string $role_id
 * @property string $client_id
 * @property string $campaign_id
 * @property string $queue_id
 * @property int $access_level
 * @property int $priority
 * @property int $ext_privilege 1- not have privilege to access beyond the role, 2- have privilege to access beyond the role which given them
 * @property string|null $created_by
 * @property string|null $created_date
 * @property string|null $created_ip
 * @property string|null $modified_by
 * @property string|null $modified_date
 * @property string|null $modified_ip
 * @property string|null $del_status
 */
class VaaniUserAccess extends \yii\db\ActiveRecord
{
    // delete status
    const STATUS_NOT_DELETED = 1;
    const STATUS_PERMANENT_DELETED = 2;
    const STATUS_TEMPORARY_DELETED = 3;

    public static $delete_statuses = [
        self::STATUS_NOT_DELETED => 'Not Deleted',
        self::STATUS_PERMANENT_DELETED => 'Permanent Deleted',
        self::STATUS_TEMPORARY_DELETED => 'Temporary Deleted',
    ];

    // access_level
    const LEVEL_CLIENT = 1;
    const LEVEL_CAMPAIGN = 2;
    const LEVEL_QUEUE = 3;

    public static $access_levels = [
        self::LEVEL_CLIENT => 'All Campaigns & Queues',
        self::LEVEL_CAMPAIGN => 'All Queues',
        self::LEVEL_QUEUE => 'Only Selected',
    ];

    // priorities
    const PRIORITY_DEFAULT = 5;
    const PRIORITY_HIGH = 0;
    const PRIORITY_MEDIUM = 10;
    const PRIORITY_LOW = 20;

    public static $user_priorities = [
        self::PRIORITY_DEFAULT => 'Default',
        self::PRIORITY_HIGH => 'High',
        self::PRIORITY_MEDIUM => 'Medium',
        self::PRIORITY_LOW => 'Low',
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
        return 'vaani_user_access';
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
            [['role_id', 'user_id', 'client_id'], 'required'],
            [['campaign_id', 'queue_id'], 'string'],
            [['ext_privilege'], 'integer'],
            [['created_date', 'modified_date', 'del_status', 'access_level'], 'safe'],
            [['user_id', 'role_id', 'client_id', 'created_by', 'created_ip', 'modified_ip'], 'string', 'max' => 50],
            [['modified_by'], 'string', 'max' => 45],
            ['del_status', 'default', 'value' => self::STATUS_NOT_DELETED],
            ['access_level', 'default', 'value' => self::LEVEL_CLIENT],
            ['priority', 'default', 'value' => self::PRIORITY_DEFAULT],
            ['ext_privilege', 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'role_id' => 'Role ID',
            'client_id' => 'Client ID',
            'campaign_id' => 'Campaign ID',
            'ext_privilege' => 'Ext Privilege',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'created_ip' => 'Created Ip',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }

    // get role model
    public function getRole() {
        return $this->hasOne(VaaniRole::className(), ['role_id' => 'role_id']);
    }

    // get client model
    public function getClient() {
        return $this->hasOne(VaaniClientMaster::className(), ['client_id' => 'client_id'])->andOnCondition(['del_status' => self::STATUS_NOT_DELETED]);
    }

    // get campaign model
    public function getCampaign() {
        return $this->hasOne(EdasCampaign::className(), ['campaign_id' => 'campaign_id'])->andOnCondition(['del_status' => self::STATUS_NOT_DELETED]);
    }

    // get queue model
    public function getQueue() {
        return $this->hasOne(VaaniCampaignQueue::className(), ['queue_id' => 'queue_id'])->andOnCondition(['del_status' => self::STATUS_NOT_DELETED]);
    }

    // get user model
    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }
}
