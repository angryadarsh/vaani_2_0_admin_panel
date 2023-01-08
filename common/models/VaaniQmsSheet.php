<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_qms_sheet".
 *
 * @property int $id
 * @property int|null $qms_id
 * @property string|null $sheet_name
 * @property int|null $type
 * @property int|null $out_of
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property int|null $del_status
 */
class VaaniQmsSheet extends \yii\db\ActiveRecord
{
    public static $out_of_data = [
        50 => '50',
        100 => '100'
    ];

    const TYPE_TRANSACTIONAL = 1;
    const TYPE_ANALYTICAL = 2;

    public static $types = [
        self::TYPE_TRANSACTIONAL => 'Transactional',
        self::TYPE_ANALYTICAL => 'Analytical'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_qms_sheet';
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
    public function rules()
    {
        return [
            [['type', 'out_of', 'del_status'], 'integer'],
            [['qms_id',], 'safe'],
            [['sheet_name'], 'string', 'max' => 100],
            [['date_created', 'date_modified', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 45],
            [['sheet_name'], 'match', 'pattern' => '/^[a-zA-Z0-9_]*$/', 'message' => 'Name cannot contain special characters & space, except underscore.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'qms_id' => 'Template ID',
            'sheet_name' => 'Sheet Name',
            'type' => 'Type',
            'out_of' => 'Out Of',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }

    public function getTemplate()
    {
        return $this->hasOne(VaaniQmsTemplate::className(), ['qms_id' => 'qms_id']);
    }
    
    public function getParameters()
    {
        return $this->hasMany(VaaniQmsParameter::className(), ['sheet_id' => 'id'])->andWhere(['type' => VaaniQmsParameter::TYPE_PARAMETER]);
    }

    // FETCH SHEETS
    public static function fetchSheets($qms_id=null)
    {
        $query = self::find()->where(['del_status' => User::STATUS_NOT_DELETED]);
        
        if($qms_id) $query = $query->andWhere(['qms_id' => $qms_id]);

        return $query->all();
    }
}
