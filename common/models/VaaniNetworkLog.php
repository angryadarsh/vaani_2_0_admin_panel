<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vaani_network_log".
 *
 * @property int $id
 * @property string $session_id
 * @property string $agent_id
 * @property string $start_epoch
 * @property string|null $end_epoch
 * @property string|null $duration
 * @property string|null $created_date
 * @property string|null $modify_date
 * @property int|null $del_status
 * @property string|null $inserted_by
 * @property int $is_active
 * @property string $unique_id
 */
class VaaniNetworkLog extends \yii\db\ActiveRecord
{
    // fetch parent db
    public static function getDb()
    {
        return Yii::$app->pa_db;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_network_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['session_id', 'agent_id', 'start_epoch', 'unique_id'], 'required'],
            [['created_date', 'modify_date'], 'safe'],
            [['del_status', 'is_active'], 'integer'],
            [['session_id'], 'string', 'max' => 200],
            [['agent_id'], 'string', 'max' => 100],
            [['start_epoch', 'end_epoch', 'duration', 'inserted_by', 'unique_id'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'session_id' => 'Session ID',
            'agent_id' => 'Agent ID',
            'start_epoch' => 'Start Epoch',
            'end_epoch' => 'End Epoch',
            'duration' => 'Duration',
            'created_date' => 'Created Date',
            'modify_date' => 'Modify Date',
            'del_status' => 'Del Status',
            'inserted_by' => 'Inserted By',
            'is_active' => 'Is Active',
            'unique_id' => 'Unique ID',
        ];
    }
}
