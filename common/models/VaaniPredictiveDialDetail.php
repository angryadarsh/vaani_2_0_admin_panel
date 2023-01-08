<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vaani_predictive_dial_detail".
 *
 * @property int $id
 * @property string|null $mobile_no
 * @property string|null $date
 * @property string|null $time
 * @property string|null $day
 * @property string|null $Time_Session
 * @property string|null $campaign
 * @property string|null $weekday
 * @property string|null $unique_id
 * @property string $dial_id
 * @property string|null $keyinput
 * @property string|null $agent_id
 * @property string|null $agent_name
 * @property int|null $type (1 - queue, 2 - ivr)
 * @property string|null $dni_number
 * @property int $total_call_agent_handled
 * @property string|null $last_call_time
 * @property int $ring_duration
 * @property string|null $queue
 * @property string|null $queue_strategy
 * @property int $queue_calls
 * @property int $queue_calls_completed
 * @property int $queue_calls_abondoned
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $duration
 * @property int|null $queue_hold_time
 * @property string|null $hangup_by
 * @property string|null $transfer_start_time
 * @property string|null $trasfer_end_time
 * @property string|null $transfer_agent_id
 * @property string|null $consult_start_time
 * @property string|null $consult_end_time
 * @property string|null $consult_agent_id
 * @property string|null $conference_start_time
 * @property string|null $conference_end_time
 * @property string|null $conference_agent_id
 * @property string|null $Column 35
 * @property string|null $recording_path
 * @property string $status
 */
class VaaniPredictiveDialDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_predictive_dial_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['dial_id', 'ring_duration', 'status'], 'required'],
            [['type', 'total_call_agent_handled', 'ring_duration', 'queue_calls', 'queue_calls_completed', 'queue_calls_abondoned', 'queue_hold_time'], 'integer'],
            [['mobile_no', 'campaign', 'dial_id', 'agent_name', 'dni_number', 'last_call_time', 'queue', 'duration', 'status'], 'string', 'max' => 50],
            [['date', 'time', 'day', 'Time_Session', 'weekday', 'unique_id', 'agent_id', 'queue_strategy', 'hangup_by', 'transfer_start_time', 'trasfer_end_time', 'transfer_agent_id', 'consult_start_time', 'consult_end_time', 'consult_agent_id', 'conference_start_time', 'conference_end_time', 'conference_agent_id', 'Column 35'], 'string', 'max' => 20],
            [['keyinput'], 'string', 'max' => 10],
            [['start_time', 'end_time'], 'string', 'max' => 30],
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
            'date' => 'Date',
            'time' => 'Time',
            'day' => 'Day',
            'Time_Session' => 'Time  Session',
            'campaign' => 'Campaign',
            'weekday' => 'Weekday',
            'unique_id' => 'Unique ID',
            'dial_id' => 'Dial ID',
            'keyinput' => 'Keyinput',
            'agent_id' => 'Agent ID',
            'agent_name' => 'Agent Name',
            'type' => 'Type',
            'dni_number' => 'Dni Number',
            'total_call_agent_handled' => 'Total Call Agent Handled',
            'last_call_time' => 'Last Call Time',
            'ring_duration' => 'Ring Duration',
            'queue' => 'Queue',
            'queue_strategy' => 'Queue Strategy',
            'queue_calls' => 'Queue Calls',
            'queue_calls_completed' => 'Queue Calls Completed',
            'queue_calls_abondoned' => 'Queue Calls Abondoned',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'duration' => 'Duration',
            'queue_hold_time' => 'Queue Hold Time',
            'hangup_by' => 'Hangup By',
            'transfer_start_time' => 'Transfer Start Time',
            'trasfer_end_time' => 'Trasfer End Time',
            'transfer_agent_id' => 'Transfer Agent ID',
            'consult_start_time' => 'Consult Start Time',
            'consult_end_time' => 'Consult End Time',
            'consult_agent_id' => 'Consult Agent ID',
            'conference_start_time' => 'Conference Start Time',
            'conference_end_time' => 'Conference End Time',
            'conference_agent_id' => 'Conference Agent ID',
            'Column 35' => 'Column 35',
            'recording_path' => 'Recording Path',
            'status' => 'Status',
        ];
    }
}
