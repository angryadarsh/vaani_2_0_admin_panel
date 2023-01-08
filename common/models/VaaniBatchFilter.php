<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_batch_filter".
 *
 * @property int $id
 * @property string $batch_id
 * @property string|null $query
 * @property string|null $filter_query
 * @property string|null $sort_query
 * @property int|null $is_active
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property int|null $del_status
 */
class VaaniBatchFilter extends \yii\db\ActiveRecord
{
    public $lead_batch_id;

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
        return 'vaani_batch_filter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['batch_id'], 'required'],
            [['is_active', 'del_status'], 'integer'],
            [['filter_query', 'sort_query', 'query', 'lead_batch_id'], 'safe'],
            [['batch_id', 'date_created', 'date_modified', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 45],
            ['del_status', 'default', 'value' => User::STATUS_NOT_DELETED],
            ['is_active', 'default', 'value' => self::STATUS_ACTIVE],
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
            'query' => 'Query',
            'is_active' => 'Is Active',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }
}
