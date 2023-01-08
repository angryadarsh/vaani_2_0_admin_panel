<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_break_log".
 *
 * @property int $id
 * @property string|null $b_id
 * @property string|null $agent_id
 * @property string|null $break_id
 * @property string|null $break_name
 * @property string|null $campaign_id
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $created_date
 * @property string|null $modified_date
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property int|null $del_status
 */
class VaaniBreakLog extends \yii\db\ActiveRecord
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

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_break_log';
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
            [['start_time', 'end_time', 'created_date', 'modified_date'], 'safe'],
            [['del_status'], 'integer'],
            [['b_id'], 'string', 'max' => 50],
            [['agent_id', 'break_id', 'break_name', 'campaign_id', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 255],
            [['b_id'], 'unique'],
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
            'b_id' => 'B ID',
            'agent_id' => 'Agent ID',
            'break_id' => 'Break ID',
            'break_name' => 'Break Name',
            'campaign_id' => 'Campaign ID',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }

    // get break model
    public function getBreak() {
        return $this->hasOne(VaaniCampaignBreak::className(), ['break_id' => 'break_id']);
    }

    // get campaign model
    public function getCampaign() {
        return $this->hasOne(EdasCampaign::className(), ['campaign_id' => 'campaign_id']);
    }

    // get user model
    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'agent_id']);
    }
}
