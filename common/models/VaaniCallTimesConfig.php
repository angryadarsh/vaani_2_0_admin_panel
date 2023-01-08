<?php

namespace common\models;

use Yii;
use \DateTimeZone;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_call_times_config".
 *
 * @property int $id
 * @property string|null $call_time_name
 * @property string|null $time_zone
 * @property string|null $comments
 * @property int|null $type
 * @property string|null $created_date
 * @property string|null $modified_date
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property int|null $del_status
 */
class VaaniCallTimesConfig extends \yii\db\ActiveRecord
{
    public $default_start_time, $default_end_time;
    
    // delete status
    const STATUS_NOT_DELETED = 1;
    const STATUS_PERMANENT_DELETED = 2;
    const STATUS_TEMPORARY_DELETED = 3;

    public static $delete_statuses = [
        self::STATUS_NOT_DELETED => 'Not Deleted',
        self::STATUS_PERMANENT_DELETED => 'Permanent Deleted',
        self::STATUS_TEMPORARY_DELETED => 'Temporary Deleted',
    ];

    const TYPE_ALL = 1;
    const TYPE_CUSTOM = 2;

    public static $types = [
        self::TYPE_ALL => 'All',
        self::TYPE_CUSTOM => 'Custom',
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
        return 'vaani_call_times_config';
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
            [['type', 'call_time_name', 'time_zone'], 'required'],
            [['call_time_name'], 'unique'],
            [['type', 'del_status'], 'integer'],
            [['default_start_time', 'default_end_time'], 'safe'],
            [['comments'], 'string', 'max' => 100],
            [['call_time_name', 'created_date', 'modified_date', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 50],
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
            'call_time_name' => 'Call Time Name',
            'comments' => 'Comments',
            'type' => 'Type',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }
    
    // fetch all time zones
    public static function timeZones()
    {
        $result = [];
        $time_zones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        foreach ($time_zones as $key => $value) {
            $result[$value] = $value;
        }
        return $result;
    }

    // fetch single call times table data
    public function getDayDetail($day_id)
    {
        return VaaniCallTimes::find()->where(['config_id' => $this->id])
            ->andWhere(['day_id' => $day_id])
            ->andWhere(['del_status' => self::STATUS_NOT_DELETED])
            ->andWhere(['parent_id' => null])
            ->one();
    }

    // fetch call times table data
    public function getDaysData()
    {
        return $this->hasMany(VaaniCallTimes::className(), ['config_id' => 'id'])->andOnCondition(['del_status' => self::STATUS_NOT_DELETED]);
    }

    // fetch queues assigned with the call window
    public function getQueues()
    {
        return $this->hasMany(VaaniCampaignQueue::className(), ['call_window' => 'id'])->andOnCondition(['del_status' => self::STATUS_NOT_DELETED]);
    }

    // fetch active call windows
    public static function activeCallWindows()
    {
        return self::find()->where(['del_status' => self::STATUS_NOT_DELETED])->orderBy('id desc')->asArray()->all();
    }
}
