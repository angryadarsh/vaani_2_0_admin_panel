<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vaani_call_access}}`.
 */
class m220119_101912_create_vaani_call_access_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vaani_call_access}}', [
            'id' => $this->primaryKey(),
            'campaign_id' => $this->string(),
            'queue_id' => $this->string(),
            'user_id' => $this->string(),
            'is_conference' => $this->integer(),
            'is_transfer' => $this->integer(),
            'is_consult' => $this->integer(),
            'is_manual' => $this->integer(),
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
        $this->dropTable('{{%vaani_call_access}}');
    }
}
