<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vaani_preview_dial_detail".
 *
 * @property int $id
 * @property string|null $mobile_no
 * @property string|null $agent_id
 * @property string|null $agent_name
 * @property string|null $date
 * @property string|null $time
 * @property string|null $day
 * @property string|null $time_session
 * @property string|null $campaign
 * @property string|null $queue_name
 * @property string|null $weekday
 * @property string|null $unique_id
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $status
 * @property string|null $duration
 * @property string|null $ring_duration
 * @property string|null $transfer_start_time
 * @property string|null $trasfer_end_time
 * @property string|null $transfer_agent_id
 * @property string|null $consult_start_time
 * @property string|null $consult_end_time
 * @property string|null $consult_agent_id
 * @property string|null $conference_start_time
 * @property string|null $conference_end_time
 * @property string|null $conference_agent_id
 * @property string|null $transfer_channel
 * @property string|null $hangup_by
 * @property string|null $recording_path
 */
class VaaniPreviewDialDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_preview_dial_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mobile_no', 'duration'], 'string', 'max' => 50],
            [['agent_id', 'date', 'time', 'day', 'time_session', 'campaign', 'queue_name', 'weekday', 'unique_id', 'status', 'ring_duration', 'transfer_start_time', 'trasfer_end_time', 'transfer_agent_id', 'consult_start_time', 'consult_end_time', 'consult_agent_id', 'conference_start_time', 'conference_end_time', 'conference_agent_id', 'hangup_by'], 'string', 'max' => 20],
            [['agent_name'], 'string', 'max' => 45],
            [['start_time', 'end_time'], 'string', 'max' => 30],
            [['transfer_channel'], 'string', 'max' => 200],
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
            'mobile_no' => 'Mobile No',
            'agent_id' => 'Agent ID',
            'agent_name' => 'Agent Name',
            'date' => 'Date',
            'time' => 'Time',
            'day' => 'Day',
            'time_session' => 'Time Session',
            'campaign' => 'Campaign',
            'queue_name' => 'Queue Name',
            'weekday' => 'Weekday',
            'unique_id' => 'Unique ID',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'status' => 'Status',
            'duration' => 'Duration',
            'ring_duration' => 'Ring Duration',
            'transfer_start_time' => 'Transfer Start Time',
            'trasfer_end_time' => 'Trasfer End Time',
            'transfer_agent_id' => 'Transfer Agent ID',
            'consult_start_time' => 'Consult Start Time',
            'consult_end_time' => 'Consult End Time',
            'consult_agent_id' => 'Consult Agent ID',
            'conference_start_time' => 'Conference Start Time',
            'conference_end_time' => 'Conference End Time',
            'conference_agent_id' => 'Conference Agent ID',
            'transfer_channel' => 'Transfer Channel',
            'hangup_by' => 'Hangup By',
            'recording_path' => 'Recording Path',
        ];
    }
}
