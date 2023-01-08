<?php

use yii\db\Migration;

/**
 * Class m220304_091121_add_priority_field_in_user_access_table
 */
class m220304_091121_add_priority_field_in_user_access_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("vaani_user_access", "priority", "INTEGER DEFAULT 0 AFTER role_id");
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("vaani_user_access", "priority");
    }
}
