<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "manual_dial_detail".
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
 * @property string $end_time
 * @property string|null $status
 * @property string|null $transfer_start_time
 * @property string|null $trasfer_end_time
 * @property string|null $transfer_agent_id
 */
class ManualDialDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manual_dial_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['end_time'], 'safe'],
            [['mobile_no'], 'string', 'max' => 50],
            [['date', 'time', 'day', 'Time_Session', 'campaign', 'weekday', 'unique_id', 'status', 'transfer_start_time', 'trasfer_end_time', 'transfer_agent_id'], 'string', 'max' => 20],
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
            'end_time' => 'End Time',
            'status' => 'Status',
            'transfer_start_time' => 'Transfer Start Time',
            'trasfer_end_time' => 'Trasfer End Time',
            'transfer_agent_id' => 'Transfer Agent ID',
        ];
    }
}
