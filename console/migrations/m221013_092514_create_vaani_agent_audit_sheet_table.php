<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vaani_agent_audit_sheet}}`.
 */
class m221013_092514_create_vaani_agent_audit_sheet_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vaani_agent_audit_sheet}}', [
            'id' => $this->primaryKey(),
            'agent_id' => $this->string(),
            'sheet_id' => $this->string(),
            'campaign' => $this->string(),
            'language' => $this->string(),
            'audit_type' => $this->string(),
            'location' => $this->string(),
            'call_duration' => $this->string(),
            'call_date' => $this->string(),
            'week' => $this->string(),
            'month' => $this->string(),
            'call_id' => $this->string(),
            'analysis_finding' => $this->string(),
            'agent_type' => $this->string(),
            'unique_id' => $this->string(),
            'disposition' => $this->string(),
            'sub_disposition' => $this->string(),
            'pip_status' => $this->string(),
            'categorization' => $this->string(),
            'action_status' => $this->string(),
            'gist_of_case' => $this->text(),
            'resolution_provided' => $this->text(),
            'areas_of_improvement' => $this->text(),
            'reason_for_fatal_call' => $this->text(),
            'quality_score' => $this->text(),
            'out_of' => $this->text(),
            'final_score' => $this->text(),
            'total_percent' => $this->text(),
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
        $this->dropTable('{{%vaani_agent_audit_sheet}}');
    }
}
