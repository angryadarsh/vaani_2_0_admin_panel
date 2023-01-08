<?php

use yii\db\Migration;

/**
 * Class m220128_133047_create_table_campaign_active_queues
 */
class m220128_133047_create_table_campaign_active_queues extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('vaani_campaign_active_queues',[
            'id' => $this->primaryKey(),
            'campaign' => $this->string(),
            'q1' => $this->string(),
            'q2' => $this->string(),
            'q3' => $this->string(),
            'q4' => $this->string(),
            'q5' => $this->string(),
            'q6' => $this->string(),
            'q7' => $this->string(),
            'q8' => $this->string(),
            'q9' => $this->string(),
            'q10' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('vaani_campaign_active_queues');
    }
}
