<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "inbound_edas".
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
 * @property string|null $keyinput
 * @property string|null $agent_id
 * @property string|null $agent_name
 * @property int $total_call_agent_handled
 * @property string|null $last_call_time
 * @property string|null $queue
 * @property string|null $type
 * @property string|null $dni_number
 * @property string|null $queue_strategy
 * @property int $queue_calls
 * @property int $queue_calls_completed
 * @property int $queue_calls_abondoned
 * @property string $end_time
 * @property int|null $queue_hold_time
 */
class InboundEdas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inbound_edas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total_call_agent_handled', 'queue_calls', 'queue_calls_completed', 'queue_calls_abondoned', 'queue_hold_time'], 'integer'],
            [['end_time'], 'safe'],
            [['mobile_no', 'agent_name', 'last_call_time', 'dni_number'], 'string', 'max' => 50],
            [['date', 'time', 'day', 'Time_Session', 'campaign', 'weekday', 'unique_id'], 'string', 'max' => 20],
            [['keyinput', 'agent_id', 'queue', 'queue_strategy'], 'string', 'max' => 10],
            [['type'], 'safe'],
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
            'keyinput' => 'Keyinput',
            'agent_id' => 'Agent ID',
            'agent_name' => 'Agent Name',
            'total_call_agent_handled' => 'Total Call Agent Handled',
            'last_call_time' => 'Last Call Time',
            'queue' => 'Queue',
            'queue_strategy' => 'Queue Strategy',
            'queue_calls' => 'Queue Calls',
            'queue_calls_completed' => 'Queue Calls Completed',
            'queue_calls_abondoned' => 'Queue Calls Abandoned',
            'end_time' => 'End Time',
            'queue_hold_time' => 'Queue Hold Time',
        ];
    }

    public function getWrapCall()
    {
        return $this->hasOne(VaaniAgentCallMaster::className(), ['unique_id' => 'unique_id'])->andOnCondition(['call_action' => 'wrap']);
    }
}
