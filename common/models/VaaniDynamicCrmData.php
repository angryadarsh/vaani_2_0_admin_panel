<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_dynamic_crm_data".
 *
 * @property int $id
 * @property string|null $crm_id
 * @property string|null $field_id
 * @property string|null $field_type
 * @property string|null $field_data_type
 * @property string|null $field_name
 * @property string|null $field_label
 * @property string|null $level
 * @property string|null $parent_id
 * @property string|null $sequence
 * @property int|null $is_editable
 * @property int|null $is_required
 * @property string|null $criteria
 * @property string|null $field_values
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property int|null $del_status
 */
class VaaniDynamicCrmData extends \yii\db\ActiveRecord
{
    // field types
    const TYPE_TEXTBOX = 1;
    const TYPE_DROPDOWN = 2;
    const TYPE_TEXTAREA = 3;
    const TYPE_RADIO = 4;
    const TYPE_CHECKBOX = 5;
    const TYPE_RANGE = 6;
    const TYPE_BUTTON = 7;

    public static $field_types = [
        self::TYPE_TEXTBOX => 'Text Box',
        self::TYPE_DROPDOWN => 'Dropdown',
        self::TYPE_TEXTAREA => 'Text Area',
        self::TYPE_RADIO => 'Radio Button',
        self::TYPE_CHECKBOX => 'Checkbox',
        self::TYPE_RANGE => 'Range',
        self::TYPE_BUTTON => 'Button',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_dynamic_crm_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['del_status'], 'integer'],
            [['crm_id', 'field_id', 'field_type', 'field_data_type', 'field_name', 'field_label', 'level', 'parent_id', 'sequence', 'criteria', 'field_values', 'is_editable', 'is_required'], 'safe'],
            [['date_created', 'date_modified', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 45],
            ['del_status', 'default', 'value' => User::STATUS_NOT_DELETED],
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
            'crm_id' => 'Crm ID',
            'field_id' => 'Field ID',
            'field_type' => 'Field Type',
            'field_data_type' => 'Field Data Type',
            'field_name' => 'Field Name',
            'field_label' => 'Field Label',
            'level' => 'Level',
            'parent_id' => 'Parent ID',
            'sequence' => 'Sequence',
            'is_editable' => 'Is Editable',
            'is_required' => 'Is Required',
            'criteria' => 'Criteria',
            'field_values' => 'Field Values',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }

    public function getCrm() {
        return $this->hasOne(VaaniDynamicCrm::className(), ['crm_id' => 'crm_id']);
    }
}
