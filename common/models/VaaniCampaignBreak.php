<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_campaign_break".
 *
 * @property int $id
 * @property string|null $campaign_id
 * @property string|null $break_name
 * @property string|null $break_id
 * @property int|null $is_active
 * @property string|null $created_date
 * @property string|null $modified_date
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property int|null $del_status
 */
class VaaniCampaignBreak extends \yii\db\ActiveRecord
{
    public $break, $b_id;

    // delete status
    const STATUS_NOT_DELETED = 1;
    const STATUS_PERMANENT_DELETED = 2;
    const STATUS_TEMPORARY_DELETED = 3;

    public static $delete_statuses = [
        self::STATUS_NOT_DELETED => 'Not Deleted',
        self::STATUS_PERMANENT_DELETED => 'Permanent Deleted',
        self::STATUS_TEMPORARY_DELETED => 'Temporary Deleted',
    ];

    // active status
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    public static $active_statuses = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_campaign_break';
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
            [['campaign_id'], 'required'],
            [['del_status'], 'integer'],
            [['created_date', 'modified_date', 'break_id', 'break', 'b_id', 'is_active'], 'safe'],
            [['campaign_id', 'break_name', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 255],
            ['is_active', 'default', 'value' => self::STATUS_ACTIVE],
            ['del_status', 'default', 'value' => self::STATUS_NOT_DELETED],
            ['syn', 'default', 'value' => 1],
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
            'break_name' => 'Break Name',
            'break_id' => 'Break ID',
            'is_active' => 'Is Active',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }

    // get campaign
    public function getCampaign() {
        return $this->hasOne(EdasCampaign::className(), ['campaign_id' => 'campaign_id']);
    }
}
