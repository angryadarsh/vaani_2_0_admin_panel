<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_crm".
 *
 * @property int $id
 * @property string|null $crm_id
 * @property string|null $campaign_id
 * @property string|null $queue_id
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property int|null $del_status
 */
class VaaniCrm extends \yii\db\ActiveRecord
{
    public $field_ids, $field_names, $field_labels, $field_types, $sequences, $field_required, $field_editable, $clone_id;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_crm';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['del_status'], 'integer'],
            [['crm_id', 'campaign_id', 'queue_id', 'date_created', 'date_modified', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 45],
            ['del_status', 'default', 'value' => User::STATUS_NOT_DELETED],
            [['field_ids', 'field_names', 'field_labels', 'field_types', 'sequences', 'field_required', 'field_editable', 'clone_id'], 'safe'],
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
            'campaign_id' => 'Campaign ID',
            'queue_id' => 'Queue ID',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }

    // campaign model
    public function getCampaign() {
        return $this->hasOne(EdasCampaign::className(), ['campaign_id' => 'campaign_id'])->andOnCondition(['del_status' => User::STATUS_NOT_DELETED]);
    }

    // fetch crm fields
    public function getCrmFields()
    {
        return $this->hasMany(VaaniCrmFields::className(), ['crm_id' => 'crm_id'])->andOnCondition(['del_status' => User::STATUS_NOT_DELETED]);
    }
}
