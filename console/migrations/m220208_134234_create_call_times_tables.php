<?php

use yii\db\Migration;

/**
 * Class m220208_134234_create_call_times_tables
 */
class m220208_134234_create_call_times_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vaani_call_times_config}}', [
            'id' => $this->primaryKey(),
            'call_time_name' => $this->string(),
            'comments' => $this->string(),
            'type' => $this->integer(),
            'created_date' => $this->string(),
            'modified_date' => $this->string(),
            'created_by' => $this->string(),
            'modified_by' => $this->string(),
            'created_ip' => $this->string(),
            'modified_ip' => $this->string(),
            'del_status' => $this->integer(),
        ]);
        
        $this->createTable('{{%vaani_call_times}}', [
            'id' => $this->primaryKey(),
            'config_id' => $this->string(),
            'day' => $this->string(),
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
        $this->dropTable('{{%vaani_call_times}}');
        $this->dropTable('{{%vaani_call_times_config}}');
    }
}
