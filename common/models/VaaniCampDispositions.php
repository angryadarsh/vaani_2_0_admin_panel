<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_camp_dispositions".
 *
 * @property int $id
 * @property string|null $campaign_id
 * @property string|null $disposition_id
 * @property string|null $max_retry_count
 * @property string|null $retry_delay
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property string|null $del_status
 */
class VaaniCampDispositions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_camp_dispositions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign_id', 'disposition_id', 'max_retry_count', 'retry_delay', 'date_created', 'date_modified', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 45],
            ['del_status', 'default', 'value' => VaaniDispositions::STATUS_NOT_DELETED],
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
            'campaign_id' => 'Campaign ID',
            'disposition_id' => 'Disposition ID',
            'max_retry_count' => 'Max Retry Count',
            'retry_delay' => 'Retry Delay',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }

    public function getCampaign() {
        return $this->hasOne(EdasCampaign::className(), ['campaign_id' => 'campaign_id']);
    }

    public function getDisporecord()
    {
        return $this->hasMany(VaaniDispositions::className(), ['disposition_id' => 'disposition_id'])->andOnCondition(['vaani_dispositions.del_status' => 1]);
    }
}
