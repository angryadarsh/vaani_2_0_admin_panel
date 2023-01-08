<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EdasCampaign;

/**
 * EdasCampaignSearch represents the model behind the search form of `common\models\EdasCampaign`.
 */
class EdasCampaignSearch extends EdasCampaign
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'campaign_inbound_ivr', 'del_status'], 'integer'],
            [['campaign_id', 'campaign_name', 'client_id', 'campaign_dni', 'campaign_status', 'service_supervisior_user_level', 'campaign_inbound_timeover_url', 'campaign_inbound_timeover_flow', 'campaign_inbound_days', 'campaign_inbound_start_time', 'campaign_inbound_end_time', 'campaign_inbound_agent_selection_criteria', 'campaign_sticky_agent', 'campaign_inbound_wrapup_time', 'campaign_inbound_auto_wrapup_disp', 'campaign_inbound_blacklisted_url', 'campaign_inbound_blacklisted_flow', 'campaign_inbound_clicktohelp', 'campaign_sms_mode', 'campaign_email_mode', 'campaign_chat_mode', 'campaign_whatsapp_mode', 'created_by', 'created_date', 'created_ip', 'modified_by', 'modified_date', 'modified_ip', 'last_activity', 'service_level_calls', 'service_level_seconds', 'sub_disposition'], 'safe'],
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
        $query = EdasCampaign::find()->orderBy('id DESC');

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'campaign_inbound_ivr' => $this->campaign_inbound_ivr,
            'campaign_inbound_wrapup_time' => $this->campaign_inbound_wrapup_time,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'last_activity' => $this->last_activity,
            'del_status' => $this->del_status,
            'client_id' => $this->client_id,
        ]);

        $query->andFilterWhere(['IN', 'campaign_id', $this->campaign_id]);
        
        $query->andFilterWhere(['like', 'campaign_name', $this->campaign_name])
            ->andFilterWhere(['like', 'campaign_dni', $this->campaign_dni])
            ->andFilterWhere(['like', 'campaign_status', $this->campaign_status])
            ->andFilterWhere(['like', 'service_supervisior_user_level', $this->service_supervisior_user_level])
            ->andFilterWhere(['like', 'campaign_inbound_timeover_url', $this->campaign_inbound_timeover_url])
            ->andFilterWhere(['like', 'campaign_inbound_timeover_flow', $this->campaign_inbound_timeover_flow])
            ->andFilterWhere(['like', 'campaign_inbound_days', $this->campaign_inbound_days])
            ->andFilterWhere(['like', 'campaign_inbound_start_time', $this->campaign_inbound_start_time])
            ->andFilterWhere(['like', 'campaign_inbound_end_time', $this->campaign_inbound_end_time])
            ->andFilterWhere(['like', 'campaign_inbound_agent_selection_criteria', $this->campaign_inbound_agent_selection_criteria])
            ->andFilterWhere(['like', 'campaign_sticky_agent', $this->campaign_sticky_agent])
            ->andFilterWhere(['like', 'campaign_inbound_auto_wrapup_disp', $this->campaign_inbound_auto_wrapup_disp])
            ->andFilterWhere(['like', 'campaign_inbound_blacklisted_url', $this->campaign_inbound_blacklisted_url])
            ->andFilterWhere(['like', 'campaign_inbound_blacklisted_flow', $this->campaign_inbound_blacklisted_flow])
            ->andFilterWhere(['like', 'campaign_inbound_clicktohelp', $this->campaign_inbound_clicktohelp])
            ->andFilterWhere(['like', 'campaign_sms_mode', $this->campaign_sms_mode])
            ->andFilterWhere(['like', 'campaign_email_mode', $this->campaign_email_mode])
            ->andFilterWhere(['like', 'campaign_chat_mode', $this->campaign_chat_mode])
            ->andFilterWhere(['like', 'campaign_whatsapp_mode', $this->campaign_whatsapp_mode])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by])
            ->andFilterWhere(['like', 'modified_ip', $this->modified_ip])
            ->andFilterWhere(['like', 'service_level_calls', $this->service_level_calls])
            ->andFilterWhere(['like', 'service_level_seconds', $this->service_level_seconds])
            ->andFilterWhere(['like', 'sub_disposition', $this->sub_disposition]);

        return $dataProvider;
    }
}
