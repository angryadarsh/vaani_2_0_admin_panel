<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EdasDniMaster;

/**
 * EdasDniMasterSearch represents the model behind the search form of `common\models\EdasDniMaster`.
 */
class EdasDniMasterSearch extends EdasDniMaster
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'service_non_voice_outbound_autogap_between_2_calls', 'service_non_voice_outbound_wrapup_time', 'del_status'], 'integer'],
            [['client_id', 'DNI_from', 'DNI_to', 'DNI_other', 'DNI_prefix', 'DNI_name', 'carrier_name', 'service_outbound_max_days', 'service_outbound_max_attempts_total', 'service_outbound_max_attempts_per_day', 'service_outbound_eod_processed_till', 'service_outbound_mix_fresh_retry', 'service_outbound_lead_expire_fail_flag', 'service_leadstructure_attempts_tablename', 'service_outbound_msc_code', 'service_outbound_max_attempts_per_day_flag', 'service_server_numbers', 'service_sms_mode', 'service_email_mode', 'service_chat_mode', 'service_social_media_mode', 'service_transfer_inbound_flow', 'service_transfer_outbound_flow', 'service_template_id_disposition', 'service_template_id_lookup', 'service_template_id_module', 'service_template_id_zone', 'service_template_id_crm', 'service_ini_parameters', 'created_by', 'created_date', 'created_ip', 'modified_by', 'modified_date', 'modified_ip', 'last_activity', 'change_set', 'service_outbound_days', 'service_grp_id', 'service_crmmanager_enabled', 'service_non_voice_outbound_url', 'service_non_voice_outbound_days', 'service_non_voice_outbound_start_time', 'service_non_voice_outbound_end_time', 'service_non_voice_outbound_autowrapup_disp_code', 'service_email_timeover_autoreply_template_id', 'service_email_lead_selection_criteria', 'service_email_lead_selection_criteria_cb', 'service_non_voice_lead_selection_name_cb', 'service_non_voice_lead_selection_id_cb', 'service_email_queue_selection_criteria_cb', 'service_email_actions_allowed', 'service_email_compose_features', 'service_email_compose_attachment_enabled', 'service_email_compose_max_attachment_size', 'service_email_compose_max_attachment_count', 'service_email_compose_attachment_file_types', 'service_email_compose_mailbox_enabled', 'service_email_compose_default_mailbox_id', 'service_email_compose_template_enabled', 'service_email_compose_cannedmsg_enabled', 'service_email_signature_id', 'service_email_compose_method', 'service_current_leadmaintenance_schedule_id', 'service_next_leadmaintenance_schedule_id', 'service_leadmaintenance_auto_repeat', 'service_outbound_lead_db_path_ldf', 'service_activity_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = EdasDniMaster::find()->orderBy('id DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($this->client_id){
            $query->andWhere(['IN', 'client_id', $this->client_id]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'service_non_voice_outbound_start_time' => $this->service_non_voice_outbound_start_time,
            'service_non_voice_outbound_end_time' => $this->service_non_voice_outbound_end_time,
            'service_non_voice_outbound_autogap_between_2_calls' => $this->service_non_voice_outbound_autogap_between_2_calls,
            'service_non_voice_outbound_wrapup_time' => $this->service_non_voice_outbound_wrapup_time,
            'del_status' => $this->del_status,
        ]);

        $query->andFilterWhere(['like', 'DNI_from', $this->DNI_from])
            ->andFilterWhere(['like', 'DNI_to', $this->DNI_to])
            ->andFilterWhere(['like', 'DNI_other', $this->DNI_other])
            ->andFilterWhere(['like', 'DNI_prefix', $this->DNI_prefix])
            ->andFilterWhere(['like', 'DNI_name', $this->DNI_name])
            ->andFilterWhere(['like', 'carrier_name', $this->carrier_name])
            ->andFilterWhere(['like', 'service_outbound_max_days', $this->service_outbound_max_days])
            ->andFilterWhere(['like', 'service_outbound_max_attempts_total', $this->service_outbound_max_attempts_total])
            ->andFilterWhere(['like', 'service_outbound_max_attempts_per_day', $this->service_outbound_max_attempts_per_day])
            ->andFilterWhere(['like', 'service_outbound_eod_processed_till', $this->service_outbound_eod_processed_till])
            ->andFilterWhere(['like', 'service_outbound_mix_fresh_retry', $this->service_outbound_mix_fresh_retry])
            ->andFilterWhere(['like', 'service_outbound_lead_expire_fail_flag', $this->service_outbound_lead_expire_fail_flag])
            ->andFilterWhere(['like', 'service_leadstructure_attempts_tablename', $this->service_leadstructure_attempts_tablename])
            ->andFilterWhere(['like', 'service_outbound_msc_code', $this->service_outbound_msc_code])
            ->andFilterWhere(['like', 'service_outbound_max_attempts_per_day_flag', $this->service_outbound_max_attempts_per_day_flag])
            ->andFilterWhere(['like', 'service_server_numbers', $this->service_server_numbers])
            ->andFilterWhere(['like', 'service_sms_mode', $this->service_sms_mode])
            ->andFilterWhere(['like', 'service_email_mode', $this->service_email_mode])
            ->andFilterWhere(['like', 'service_chat_mode', $this->service_chat_mode])
            ->andFilterWhere(['like', 'service_social_media_mode', $this->service_social_media_mode])
            ->andFilterWhere(['like', 'service_transfer_inbound_flow', $this->service_transfer_inbound_flow])
            ->andFilterWhere(['like', 'service_transfer_outbound_flow', $this->service_transfer_outbound_flow])
            ->andFilterWhere(['like', 'service_template_id_disposition', $this->service_template_id_disposition])
            ->andFilterWhere(['like', 'service_template_id_lookup', $this->service_template_id_lookup])
            ->andFilterWhere(['like', 'service_template_id_module', $this->service_template_id_module])
            ->andFilterWhere(['like', 'service_template_id_zone', $this->service_template_id_zone])
            ->andFilterWhere(['like', 'service_template_id_crm', $this->service_template_id_crm])
            ->andFilterWhere(['like', 'service_ini_parameters', $this->service_ini_parameters])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by])
            ->andFilterWhere(['like', 'modified_ip', $this->modified_ip])
            ->andFilterWhere(['like', 'last_activity', $this->last_activity])
            ->andFilterWhere(['like', 'change_set', $this->change_set])
            ->andFilterWhere(['like', 'service_outbound_days', $this->service_outbound_days])
            ->andFilterWhere(['like', 'service_grp_id', $this->service_grp_id])
            ->andFilterWhere(['like', 'service_crmmanager_enabled', $this->service_crmmanager_enabled])
            ->andFilterWhere(['like', 'service_non_voice_outbound_url', $this->service_non_voice_outbound_url])
            ->andFilterWhere(['like', 'service_non_voice_outbound_days', $this->service_non_voice_outbound_days])
            ->andFilterWhere(['like', 'service_non_voice_outbound_autowrapup_disp_code', $this->service_non_voice_outbound_autowrapup_disp_code])
            ->andFilterWhere(['like', 'service_email_timeover_autoreply_template_id', $this->service_email_timeover_autoreply_template_id])
            ->andFilterWhere(['like', 'service_email_lead_selection_criteria', $this->service_email_lead_selection_criteria])
            ->andFilterWhere(['like', 'service_email_lead_selection_criteria_cb', $this->service_email_lead_selection_criteria_cb])
            ->andFilterWhere(['like', 'service_non_voice_lead_selection_name_cb', $this->service_non_voice_lead_selection_name_cb])
            ->andFilterWhere(['like', 'service_non_voice_lead_selection_id_cb', $this->service_non_voice_lead_selection_id_cb])
            ->andFilterWhere(['like', 'service_email_queue_selection_criteria_cb', $this->service_email_queue_selection_criteria_cb])
            ->andFilterWhere(['like', 'service_email_actions_allowed', $this->service_email_actions_allowed])
            ->andFilterWhere(['like', 'service_email_compose_features', $this->service_email_compose_features])
            ->andFilterWhere(['like', 'service_email_compose_attachment_enabled', $this->service_email_compose_attachment_enabled])
            ->andFilterWhere(['like', 'service_email_compose_max_attachment_size', $this->service_email_compose_max_attachment_size])
            ->andFilterWhere(['like', 'service_email_compose_max_attachment_count', $this->service_email_compose_max_attachment_count])
            ->andFilterWhere(['like', 'service_email_compose_attachment_file_types', $this->service_email_compose_attachment_file_types])
            ->andFilterWhere(['like', 'service_email_compose_mailbox_enabled', $this->service_email_compose_mailbox_enabled])
            ->andFilterWhere(['like', 'service_email_compose_default_mailbox_id', $this->service_email_compose_default_mailbox_id])
            ->andFilterWhere(['like', 'service_email_compose_template_enabled', $this->service_email_compose_template_enabled])
            ->andFilterWhere(['like', 'service_email_compose_cannedmsg_enabled', $this->service_email_compose_cannedmsg_enabled])
            ->andFilterWhere(['like', 'service_email_signature_id', $this->service_email_signature_id])
            ->andFilterWhere(['like', 'service_email_compose_method', $this->service_email_compose_method])
            ->andFilterWhere(['like', 'service_current_leadmaintenance_schedule_id', $this->service_current_leadmaintenance_schedule_id])
            ->andFilterWhere(['like', 'service_next_leadmaintenance_schedule_id', $this->service_next_leadmaintenance_schedule_id])
            ->andFilterWhere(['like', 'service_leadmaintenance_auto_repeat', $this->service_leadmaintenance_auto_repeat])
            ->andFilterWhere(['like', 'service_outbound_lead_db_path_ldf', $this->service_outbound_lead_db_path_ldf])
            ->andFilterWhere(['like', 'service_activity_id', $this->service_activity_id]);

        return $dataProvider;
    }
}
