<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vaani_agent_call_report".
 *
 * @property int $id
 * @property string|null $agent_id
 * @property string|null $unique_id
 * @property string $agent_name
 * @property string $queue_name
 * @property string $caller_id
 * @property string $disposition
 * @property string|null $ringing
 * @property string|null $incall
 * @property string|null $talk
 * @property string|null $hold
 * @property string|null $transfer
 * @property string|null $conference
 * @property string|null $consult
 * @property string|null $dispo
 * @property string|null $wrap
 * @property string|null $insert_date
 * @property string $updated_date
 * @property string|null $campaign_name
 * @property int|null $call_type 1-manual, 2-inbound, 3-outbound
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $recording_path
 * @property string|null $call_status
 */
class VaaniAgentCallReport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_agent_call_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent_name', 'queue_name', 'caller_id', 'disposition', 'updated_date'], 'required'],
            [['insert_date', 'updated_date'], 'safe'],
            [['call_type'], 'integer'],
            [['agent_id', 'unique_id', 'start_date', 'end_date', 'recording_path', 'call_status'], 'string', 'max' => 45],
            [['agent_name', 'queue_name', 'campaign_name'], 'string', 'max' => 100],
            [['caller_id'], 'string', 'max' => 50],
            [['disposition'], 'string', 'max' => 200],
            [['ringing', 'incall', 'talk', 'hold', 'transfer', 'conference', 'consult', 'dispo', 'wrap'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'agent_id' => 'Agent ID',
            'unique_id' => 'Unique ID',
            'agent_name' => 'Agent Name',
            'queue_name' => 'Queue Name',
            'caller_id' => 'Caller ID',
            'disposition' => 'Disposition',
            'ringing' => 'Ringing',
            'incall' => 'Incall',
            'talk' => 'Talk',
            'hold' => 'Hold',
            'transfer' => 'Transfer',
            'conference' => 'Conference',
            'consult' => 'Consult',
            'dispo' => 'Dispo',
            'wrap' => 'Wrap',
            'insert_date' => 'Insert Date',
            'updated_date' => 'Updated Date',
            'campaign_name' => 'Campaign Name',
            'call_type' => 'Call Type',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'recording_path' => 'Recording Path',
            'call_status' => 'Call Status',
        ];
    }

    public function getDispositionData()
    {
        return $this->hasOne(VaaniDispositions::className(), ['short_code' => 'disposition']);
    }

    public function getHistory()
    {
        return $this->hasOne(History::className(), ['unique_id' => 'unique_id']);
    }
}
