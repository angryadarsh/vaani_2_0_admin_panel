<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_dispositions".
 *
 * @property int $id
 * @property string|null $disposition_id
 * @property string|null $plan_id
 * @property string|null $campaign_id
 * @property string|null $queue_id
 * @property string|null $disposition_name
 * @property string|null $short_code
 * @property string|null $parent_id
 * @property string|null $level
 * @property string|null $type
 * @property string|null $sequence
 * @property string|null $created_date
 * @property string|null $modified_date
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property string|null $del_status
 */
class VaaniDispositions extends \yii\db\ActiveRecord
{
    public $queues;
    // public $campaign_id;
    

    // delete status
    const STATUS_NOT_DELETED = 1;
    const STATUS_PERMANENT_DELETED = 2;
    const STATUS_TEMPORARY_DELETED = 3;

    public static $delete_statuses = [
        self::STATUS_NOT_DELETED => 'Not Deleted',
        self::STATUS_PERMANENT_DELETED => 'Permanent Deleted',
        self::STATUS_TEMPORARY_DELETED => 'Temporary Deleted',
    ];

    // types of dispositions
    const TYPE_SUCCESS = 1;
    const TYPE_FAILURE = 2;
    const TYPE_CALLBACK = 3;
    const TYPE_DNC = 4;
    const TYPE_OTHER = 5;

    public static $types = [
        self::TYPE_SUCCESS => 'Success',
        self::TYPE_FAILURE => 'Failed',
        self::TYPE_CALLBACK => 'Callback',
        self::TYPE_DNC => 'DNC',
        self::TYPE_OTHER => 'Other',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_dispositions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['disposition_id', 'disposition_name', 'short_code'], 'required'],
            [['disposition_name', 'short_code', 'disposition_id', 'queue_id', 'campaign_id','del_status', 'parent_id', 'type', 'created_date', 'modified_date', 'created_by', 'modified_by', 'created_ip', 'modified_ip', 'level', 'sequence', 'plan_id'], 'safe'],
            ['del_status', 'default', 'value' => self::STATUS_NOT_DELETED],
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'disposition_id' => 'Disposition Id',
            'disposition_name' => 'Disposition Name',
            'short_code' => 'Short Code',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created IP',
            'modified_ip' => 'Modified IP',
            'del_status' => 'Del Status',
        ];
    }

    public function getPlan() {
        return $this->hasOne(VaaniDispositionPlan::className(), ['plan_id' => 'plan_id']);
    }

    public function getCampaign() {
        return $this->hasOne(EdasCampaign::className(), ['campaign_id' => 'campaign_id']);
    }

    public function getSubDispositions() {
        return $this->hasMany(VaaniDispositions::className(), ['parent_id' => 'id'])->andOnCondition(['del_status' => VaaniDispositions::STATUS_NOT_DELETED])->andWhere(['IS NOT', 'parent_id', NULL])->orderBy('sequence');
    }

    public function getParent() {
        return $this->hasOne(VaaniDispositions::className(), ['id' => 'parent_id']);
    }
}
