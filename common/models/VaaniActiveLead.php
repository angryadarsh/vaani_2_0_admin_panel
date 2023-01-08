<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_active_lead".
 *
 * @property int $id
 * @property string $lead_id
 * @property string $batch_id
 * @property string $campaign_id
 * @property string $lead_data
 * @property string|null $primary_no
 * @property string $date_created
 * @property string $created_by
 * @property string|null $date_modified
 * @property string|null $modified_by
 * @property int $del_status
 */
class VaaniActiveLead extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_active_lead';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lead_id', 'batch_id', 'campaign_id', 'lead_data', 'date_created', 'created_by', 'del_status'], 'required'],
            [['lead_data'], 'string'],
            [['date_created', 'date_modified', 'created_ip', 'modified_ip'], 'safe'],
            [['del_status'], 'integer'],
            [['lead_id', 'batch_id', 'campaign_id', 'created_by', 'modified_by'], 'string', 'max' => 50],
            [['primary_no'], 'string', 'max' => 20],
            [['lead_id'], 'unique'],
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
            'lead_id' => 'Lead ID',
            'batch_id' => 'Batch ID',
            'campaign_id' => 'Campaign ID',
            'lead_data' => 'Lead Data',
            'primary_no' => 'Primary No',
            'date_created' => 'Date Created',
            'created_by' => 'Created By',
            'date_modified' => 'Date Modified',
            'modified_by' => 'Modified By',
            'del_status' => 'Del Status',
        ];
    }
}
