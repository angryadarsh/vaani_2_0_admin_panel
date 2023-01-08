<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vaani_agent_live_status".
 *
 * @property int $id
 * @property string|null $unique_id
 * @property string|null $agent_id
 * @property string|null $datetime
 * @property string|null $queue_name
 * @property string|null $status Incall,Hang up
 * @property string|null $caller_id
 * @property int|null $call_type
 * @property string|null $end_time
 * @property string|null $disposition_time
 */
class VaaniAgentLiveStatus extends \yii\db\ActiveRecord
{
    // call_type flags
    // 1-manual, 2-inbound, 3-outbound, 4-transfer, 5-consult, 6-conference, 7-call_back
    const STATUS_MANUAL = 1;
    const STATUS_INBOUND = 2;
    const STATUS_OUTBOUND = 3;
    const STATUS_TRANSFER = 4;
    const STATUS_CONSULT = 5;
    const STATUS_CONFERENCE = 6;
    const STATUS_CALL_BACK = 7;

    public static $call_types = [
        self::STATUS_MANUAL => 'Manual',
        self::STATUS_INBOUND => 'Inbound',
        self::STATUS_OUTBOUND => 'Outbound',
        self::STATUS_TRANSFER => 'Transfer',
        self::STATUS_CONSULT => 'Consult',
        self::STATUS_CONFERENCE => 'Conference',
        self::STATUS_CALL_BACK => 'Call Back',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_agent_live_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datetime', 'end_time', 'disposition_time'], 'safe'],
            [['call_type'], 'integer'],
            [['unique_id', 'agent_id', 'queue_name', 'status', 'caller_id'], 'string', 'max' => 50],
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
            'agent_id' => 'Agent ID',
            'datetime' => 'Datetime',
            'queue_name' => 'Queue Name',
            'status' => 'Status',
            'caller_id' => 'Caller ID',
            'call_type' => 'Call Type',
            'end_time' => 'End Time',
            'disposition_time' => 'Disposition Time',
        ];
    }
}
