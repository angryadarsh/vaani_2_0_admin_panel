<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_call_access".
 *
 * @property int $id
 * @property string|null $campaign_id
 * @property string|null $queue_id
 * @property string|null $user_id
 * @property int|null $is_conference
 * @property int|null $is_transfer
 * @property int|null $is_consult
 * @property int|null $is_manual
 * @property string|null $created_date
 * @property string|null $modified_date
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property string|null $del_status
 */
class VaaniCallAccess extends \yii\db\ActiveRecord
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

    // access values
    const ACCESS_YES = '1';
    const ACCESS_NO = '2';

    public static $access_values = [
        self::ACCESS_NO => 'No',
        self::ACCESS_YES => 'Yes',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_call_access';
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
            [['is_conference', 'is_transfer', 'is_consult', 'is_manual'], 'integer'],
            [['campaign_id', 'queue_id', 'user_id', 'created_date', 'modified_date', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 255],
            [['del_status'], 'safe'],
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
            'campaign_id' => 'Campaign ID',
            'queue_id' => 'Queue ID',
            'user_id' => 'User ID',
            'is_conference' => 'Is Conference',
            'is_transfer' => 'Is Transfer',
            'is_consult' => 'Is Consult',
            'is_manual' => 'Is Manual',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }
}
