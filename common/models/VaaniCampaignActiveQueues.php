<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vaani_campaign_active_queues".
 *
 * @property int $id
 * @property string|null $campaign
 * @property string|null $q1
 * @property string|null $q2
 * @property string|null $q3
 * @property string|null $q4
 * @property string|null $q5
 * @property string|null $q6
 * @property string|null $q7
 * @property string|null $q8
 * @property string|null $q9
 * @property string|null $q10
 */
class VaaniCampaignActiveQueues extends \yii\db\ActiveRecord
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
        return 'vaani_campaign_active_queues';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign', 'q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'campaign' => 'Campaign',
            'q1' => 'Q1',
            'q2' => 'Q2',
            'q3' => 'Q3',
            'q4' => 'Q4',
            'q5' => 'Q5',
            'q6' => 'Q6',
            'q7' => 'Q7',
            'q8' => 'Q8',
            'q9' => 'Q9',
            'q10' => 'q10',
        ];
    }
}
