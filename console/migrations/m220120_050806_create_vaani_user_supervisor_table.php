<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vaani_user_supervisor}}`.
 */
class m220120_050806_create_vaani_user_supervisor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vaani_user_supervisor}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->string(),
            'supervisor_id' => $this->string(),
            'created_date' => $this->string(),
            'modified_date' => $this->string(),
            'created_by' => $this->string(),
            'modified_by' => $this->string(),
            'created_ip' => $this->string(),
            'modified_ip' => $this->string(),
            'del_status' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%vaani_user_supervisor}}');
    }
}
