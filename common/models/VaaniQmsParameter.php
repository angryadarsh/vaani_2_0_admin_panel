<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_qms_parameter".
 *
 * @property int $id
 * @property string|null $qms_id
 * @property string|null $sheet_id
 * @property string|null $name
 * @property int|null $type
 * @property string|null $parent_id
 * @property int|null $sub_type
 * @property string|null $score
 * @property string|null $remarks
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property int|null $del_status
 */
class VaaniQmsParameter extends \yii\db\ActiveRecord
{
    public $sub_name;

    const TYPE_PARAMETER = 1;
    const TYPE_SUB_PARAMETER = 2;

    public static $types = [
        self::TYPE_PARAMETER => 'Parameter',
        self::TYPE_SUB_PARAMETER => 'Sub Parameter'
    ];

    const TYPE_FATAL = 1;
    const TYPE_NON_FATAL = 2;

    public static $sub_types = [
        self::TYPE_FATAL => 'Fatal',
        self::TYPE_NON_FATAL => 'Non Fatal'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_qms_parameter';
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
            [['type', 'sub_type', 'del_status'], 'integer'],
            [['qms_id', 'score'], 'string', 'max' => 50],
            [['sheet_id', 'parent_id', 'date_created', 'date_modified', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 45],
            [['name', 'sub_name'], 'string', 'max' => 100],
            [['remarks'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'qms_id' => 'Qms ID',
            'sheet_id' => 'Sheet ID',
            'name' => 'Name',
            'type' => 'Type',
            'parent_id' => 'Parent ID',
            'sub_type' => 'Sub Type',
            'score' => 'Score',
            'remarks' => 'Remarks',
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

    public function getSheet()
    {
        return $this->hasOne(VaaniQmsSheet::className(), ['id' => 'sheet_id']);
    }

    public function getParentParameter()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    public function getSubParameters()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id'])->andWhere(['type' => self::TYPE_SUB_PARAMETER]);
    }

    // FETCH PARAMETERS
    public static function fetchParameters($qms_id=null, $sheet_id=null)
    {
        $query = self::find()->where(['del_status' => User::STATUS_NOT_DELETED])->andWhere(['type' => self::TYPE_PARAMETER]);
        
        if($qms_id) $query = $query->andWhere(['qms_id' => $qms_id]);
        if($sheet_id) $query = $query->andWhere(['sheet_id' => $sheet_id]);

        return $query->all();
    }
}
