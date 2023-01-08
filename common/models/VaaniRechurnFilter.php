<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_rechurn_filter".
 *
 * @property int $id
 * @property string|null $batch_id
 * @property string|null $query
 * @property int|null $is_active
 * @property string|null $filter_query
 * @property string|null $sort_query
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property string|null $del_status
 */
class VaaniRechurnFilter extends \yii\db\ActiveRecord
{

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
        return 'vaani_rechurn_filter';
    }

    // /**
    //  * @return \yii\db\Connection the database connection used by this AR class.
    //  */
    // public static function getDb()
    // {
    //     return Yii::$app->get('db2');
    // }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_active','del_status'], 'integer'],
            [['batch_id', 'filter_query', 'sort_query', 'date_created', 'date_modified', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 45],
            // [['del_status'], 'string', 'max' => 20],
            [['query'], 'safe'],
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
            'filter_query' => 'Filter Query',
            'sort_query' => 'Sort Query',
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
