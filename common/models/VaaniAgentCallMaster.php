<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vaani_agent_call_master".
 *
 * @property int $call_id
 * @property string|null $user_id
 * @property string|null $unique_id
 * @property string|null $call_action
 * @property string|null $action_starttime
 * @property string|null $action_duration
 * @property int|null $call_sequence
 */
class VaaniAgentCallMaster extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_agent_call_master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action_starttime'], 'safe'],
            [['call_sequence'], 'integer'],
            [['user_id', 'unique_id', 'action_duration'], 'string', 'max' => 20],
            [['call_action'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'call_id' => 'Call ID',
            'user_id' => 'User ID',
            'unique_id' => 'Unique ID',
            'call_action' => 'Call Action',
            'action_starttime' => 'Action Starttime',
            'action_duration' => 'Action Duration',
            'call_sequence' => 'Call Sequence',
        ];
    }
}
