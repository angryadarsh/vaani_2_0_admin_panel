<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_crm_fields".
 *
 * @property int $id
 * @property string|null $crm_field_id
 * @property string|null $crm_id
 * @property string|null $field_name
 * @property string|null $field_label
 * @property string|null $sequence
 * @property string|null $field_type
 * @property int|null $is_required
 * @property int|null $is_editable
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property int|null $del_status
 */
class VaaniCrmFields extends \yii\db\ActiveRecord
{
    // field types
    const TYPE_TEXT = 1;
    const TYPE_TEXTAREA = 2;
    const TYPE_NUMBER = 3;
    const TYPE_EMAIL = 4;

    public static $types = [
        self::TYPE_TEXT => 'Text',
        self::TYPE_TEXTAREA => 'TextArea',
        self::TYPE_NUMBER => 'Number',
        self::TYPE_EMAIL => 'Email',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_crm_fields';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_required', 'is_editable', 'del_status'], 'integer'],
            [['crm_field_id', 'crm_id', 'field_name', 'field_label', 'sequence', 'field_type', 'date_created', 'date_modified', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 45],
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
            'crm_field_id' => 'Crm Field ID',
            'crm_id' => 'Crm ID',
            'field_name' => 'Field Name',
            'field_label' => 'Field Label',
            'sequence' => 'Sequence',
            'field_type' => 'Field Type',
            'is_required' => 'Is Required',
            'is_editable' => 'Is Editable',
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
