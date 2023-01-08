<?php

use yii\db\Migration;

/**
 * Class m220118_114218_add_access_fields_in_campaign_and_queue_tables
 */
class m220118_114218_add_access_fields_in_campaign_and_queue_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // queue fields
        $this->addColumn('vaani_campaign_queue', 'queue_name', 'string');
        $this->addColumn('vaani_campaign_queue', 'queue_id', 'string');
        // is_dtmf & key input fields
        $this->addColumn('edas_campaign_id', 'is_dtmf', 'integer');
        $this->addColumn('edas_campaign_id', 'is_ivr_queue', 'integer');
        $this->addColumn('edas_campaign_id', 'key_input', 'varchar(255)');
        $this->addColumn('vaani_campaign_queue', 'key_input', 'integer');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('vaani_campaign_queue', 'key_input');
        $this->dropColumn('edas_campaign_id', 'key_input');
        $this->dropColumn('edas_campaign_id', 'is_ivr_queue');
        $this->dropColumn('edas_campaign_id', 'is_dtmf');
        $this->dropColumn('vaani_campaign_queue', 'queue_id');
        $this->dropColumn('vaani_campaign_queue', 'queue_name');
    }
}
