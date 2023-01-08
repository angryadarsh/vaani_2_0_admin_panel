<?php

use yii\db\Migration;

/**
 * Class m220208_061306_create_campaign_break_and_break_log_tables
 */
class m220208_061306_create_campaign_break_and_break_log_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vaani_campaign_break}}', [
            'id' => $this->primaryKey(),
            'campaign_id' => $this->string(),
            'break_name' => $this->string(),
            'is_active' => $this->integer(),
            'created_date' => $this->string(),
            'modified_date' => $this->string(),
            'created_by' => $this->string(),
            'modified_by' => $this->string(),
            'created_ip' => $this->string(),
            'modified_ip' => $this->string(),
            'del_status' => $this->integer(),
        ]);
        
        $this->createTable('{{%vaani_break_log}}', [
            'id' => $this->primaryKey(),
            'agent_id' => $this->string(),
            'break_id' => $this->string(),
            'break_name' => $this->string(),
            'campaign_id' => $this->string(),
            'start_time' => $this->string(),
            'end_time' => $this->string(),
            'created_date' => $this->string(),
            'modified_date' => $this->string(),
            'created_by' => $this->string(),
            'modified_by' => $this->string(),
            'created_ip' => $this->string(),
            'modified_ip' => $this->string(),
            'del_status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%vaani_break_log}}');
        $this->dropTable('{{%vaani_campaign_break}}');
    }
}
