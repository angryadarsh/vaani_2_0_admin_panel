<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniAgentAuditSheet;

/**
 * VaaniAgentAuditSheetSearch represents the model behind the search form of `common\models\VaaniAgentAuditSheet`.
 */
class VaaniAgentAuditSheetSearch extends VaaniAgentAuditSheet
{
    public $dates;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'del_status'], 'integer'],
            [['audit_id', 'agent_id', 'sheet_id', 'campaign_id', 'campaign', 'language', 'audit_type', 'location', 'call_duration', 'call_date', 'week', 'month', 'call_id', 'analysis_finding', 'agent_type', 'unique_id', 'disposition', 'sub_disposition', 'pip_status', 'categorization', 'action_status', 'gist_of_case', 'resolution_provided', 'areas_of_improvement', 'reason_for_fatal_call', 'rec_markers', 'start_time', 'end_time', 'audit_duration', 'date_created', 'date_modified', 'created_by', 'modified_by', 'created_ip', 'modified_ip', 'feedback', 'status'], 'safe'],
            [['quality_score', 'out_of', 'final_score', 'total_percent'], 'number'],
            [['dates'], 'safe'],
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
        $query = VaaniAgentAuditSheet::find();

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

        if($this->dates){
            $start_time = ($this->dates . ' 00:00:00');
            $end_time = ($this->dates . ' 23:59:59');
            $query->andFilterWhere( ['and', ['>=', 'date_created', $start_time], ['<=', 'date_created', $end_time]] );
        }
       
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'quality_score' => $this->quality_score,
            'out_of' => $this->out_of,
            'final_score' => $this->final_score,
            'total_percent' => $this->total_percent,
            'del_status' => $this->del_status,
        ]);

        $query->andFilterWhere(['like', 'audit_id', $this->audit_id])
            ->andFilterWhere(['like', 'agent_id', $this->agent_id])
            ->andFilterWhere(['like', 'sheet_id', $this->sheet_id])
            ->andFilterWhere(['like', 'campaign_id', $this->campaign_id])
            ->andFilterWhere(['like', 'campaign', $this->campaign])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'audit_type', $this->audit_type])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'call_duration', $this->call_duration])
            ->andFilterWhere(['like', 'call_date', $this->call_date])
            ->andFilterWhere(['like', 'week', $this->week])
            ->andFilterWhere(['like', 'month', $this->month])
            ->andFilterWhere(['like', 'call_id', $this->call_id])
            ->andFilterWhere(['like', 'analysis_finding', $this->analysis_finding])
            ->andFilterWhere(['like', 'agent_type', $this->agent_type])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id])
            ->andFilterWhere(['like', 'disposition', $this->disposition])
            ->andFilterWhere(['like', 'sub_disposition', $this->sub_disposition])
            ->andFilterWhere(['like', 'pip_status', $this->pip_status])
            ->andFilterWhere(['like', 'categorization', $this->categorization])
            ->andFilterWhere(['like', 'action_status', $this->action_status])
            ->andFilterWhere(['like', 'gist_of_case', $this->gist_of_case])
            ->andFilterWhere(['like', 'resolution_provided', $this->resolution_provided])
            ->andFilterWhere(['like', 'areas_of_improvement', $this->areas_of_improvement])
            ->andFilterWhere(['like', 'reason_for_fatal_call', $this->reason_for_fatal_call])
            ->andFilterWhere(['like', 'rec_markers', $this->rec_markers])
            ->andFilterWhere(['like', 'start_time', $this->start_time])
            ->andFilterWhere(['like', 'end_time', $this->end_time])
            ->andFilterWhere(['like', 'audit_duration', $this->audit_duration])
            ->andFilterWhere(['like', 'date_created', $this->date_created])
            ->andFilterWhere(['like', 'date_modified', $this->date_modified])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'modified_ip', $this->modified_ip])
            ->andFilterWhere(['like', 'feedback', $this->feedback])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
