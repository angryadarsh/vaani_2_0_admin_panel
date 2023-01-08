<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vaani_call_recordings".
 *
 * @property int $id
 * @property string|null $unique_id
 * @property string|null $start_time
 * @property string|null $duration
 * @property string|null $agent_id
 * @property string|null $customer_number
 * @property string|null $campaign
 * @property string|null $queue
 * @property string|null $recording_path
 * @property int|null $type
 * @property string $end_time
 */
class VaaniCallRecordings extends \yii\db\ActiveRecord
{

    // status values
    const STATUS_NOT_AUDITED = '1';
    const STATUS_AUDITED = '2';
    const STATUS_DISPUTED_INITIATED = '3';
    const STATUS_CLOSED = '10';

    public static $statuses = [
        self::STATUS_NOT_AUDITED => 'Not Audited',
        self::STATUS_AUDITED => 'Audited',
        self::STATUS_DISPUTED_INITIATED => 'Dispute Initiated',
        self::STATUS_CLOSED => 'Closed',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_call_recordings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['unique_id', 'start_time', 'duration', 'agent_id', 'customer_number', 'end_time'], 'string', 'max' => 45],
            [['campaign', 'queue'], 'string', 'max' => 50],
            [['recording_path'], 'string', 'max' => 180],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unique_id' => 'Unique ID',
            'start_time' => 'Start Time',
            'duration' => 'Duration',
            'agent_id' => 'Agent ID',
            'customer_number' => 'Customer Number',
            'campaign' => 'Campaign',
            'queue' => 'Queue',
            'recording_path' => 'Recording Path',
            'type' => 'Type',
            'end_time' => 'End Time',
        ];
    }

    // get user model
    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'agent_id']);
    }

    // get recent audited model
    public function getAudit() {
        return $this->hasOne(VaaniAgentAuditSheet::className(), ['unique_id' => 'unique_id'])->orderBy('id DESC');
    }

    // get recent audited model with status dispute
    public function getDisputeAudit() {
        return $this->hasOne(VaaniAgentAuditSheet::className(), ['unique_id' => 'unique_id'])->andWhere(['status' => VaaniAgentAuditSheet::STATUS_REJECTED])->orderBy('id DESC');
    }

    public function getHistory()
    {
        return $this->hasOne(History::className(), ['unique_id' => 'unique_id']);
    }
}
