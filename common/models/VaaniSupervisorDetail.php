<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vaani_supervisor_detail".
 *
 * @property int $id
 * @property string|null $unique_id
 * @property string|null $supervisor_id
 * @property string|null $supervisor_channel
 * @property string $time
 */
class VaaniSupervisorDetail extends \yii\db\ActiveRecord
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
        return 'vaani_supervisor_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time'], 'safe'],
            [['unique_id', 'supervisor_id', 'supervisor_channel'], 'string', 'max' => 50],
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
            'supervisor_id' => 'Supervisor ID',
            'supervisor_channel' => 'Supervisor Channel',
            'time' => 'Time',
        ];
    }
}
