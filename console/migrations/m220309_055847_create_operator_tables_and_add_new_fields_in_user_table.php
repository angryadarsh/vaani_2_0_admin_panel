<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%operator_tables_and_add_new_fields_in_user}}`.
 */
class m220309_055847_create_operator_tables_and_add_new_fields_in_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vaani_operators}}', [
            'id' => $this->primaryKey(),
            'operator_name' => $this->string(),
            'created_date' => $this->string(),
            'modified_date' => $this->string(),
            'created_by' => $this->string(),
            'modified_by' => $this->string(),
            'created_ip' => $this->string(),
            'modified_ip' => $this->string(),
            'del_status' => $this->integer(),
        ]);

        $this->createTable('{{%vaani_client_operators}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->string(),
            'operator_id' => $this->string(),
            'created_date' => $this->string(),
            'modified_date' => $this->string(),
            'created_by' => $this->string(),
            'modified_by' => $this->string(),
            'created_ip' => $this->string(),
            'modified_ip' => $this->string(),
            'del_status' => $this->integer(),
        ]);

        $this->createTable('{{%vaani_user_operator}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->string(),
            'operator_id' => $this->string(),
            'created_date' => $this->string(),
            'modified_date' => $this->string(),
            'created_by' => $this->string(),
            'modified_by' => $this->string(),
            'created_ip' => $this->string(),
            'modified_ip' => $this->string(),
            'del_status' => $this->integer(),
        ]);

        $this->addColumn('vaani_user', 'is_two_leg', 'smallint default 2 after role_id');
        $this->addColumn('vaani_user', 'contact_number', 'varchar(50) after is_two_leg');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('vaani_user', 'contact_number');
        $this->dropColumn('vaani_user', 'is_two_leg');

        $this->dropTable('{{%vaani_user_operator}}');
        $this->dropTable('{{%vaani_client_operators}}');
        $this->dropTable('{{%vaani_operators}}');
    }
}
