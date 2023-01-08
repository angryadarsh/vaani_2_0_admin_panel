<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vaani_user_active_queues}}`.
 */
class m220131_085432_create_vaani_user_active_queues_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vaani_user_active_queues}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->string(),
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
        $this->dropTable('{{%vaani_user_active_queues}}');
    }
}
