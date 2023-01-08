<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vaani_client_license}}`.
 */
class m220324_122809_create_vaani_client_license_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vaani_client_license}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->string(),
            'role_id' => $this->string(),
            'login_count' => $this->integer(),
            'logged_in_count' => $this->integer(),
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
        $this->dropTable('{{%vaani_client_license}}');
    }
}
