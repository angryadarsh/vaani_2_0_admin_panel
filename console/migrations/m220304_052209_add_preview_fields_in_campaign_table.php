<?php

use yii\db\Migration;

/**
 * Class m220304_052209_add_preview_fields_in_campaign_table
 */
class m220304_052209_add_preview_fields_in_campaign_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("edas_campaign_id", "mode", "INTEGER AFTER campaign_type");
        $this->addColumn("edas_campaign_id", "preview_upload", "INTEGER");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("edas_campaign_id", "preview_upload");
        $this->dropColumn("edas_campaign_id", "mode");
    }
}
