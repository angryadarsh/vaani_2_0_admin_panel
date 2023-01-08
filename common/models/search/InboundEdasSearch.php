<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InboundEdas;

/**
 * InboundEdasSearch represents the model behind the search form of `common\models\InboundEdas`.
 */
class InboundEdasSearch extends InboundEdas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'total_call_agent_handled', 'queue_calls', 'queue_calls_completed', 'queue_calls_abondoned', 'queue_hold_time'], 'integer'],
            [['mobile_no', 'date', 'time', 'day', 'Time_Session', 'campaign', 'weekday', 'unique_id', 'keyinput', 'agent_id', 'agent_name', 'last_call_time', 'queue', 'queue_strategy', 'end_time'], 'safe'],
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
        $query = InboundEdas::find();

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
            'total_call_agent_handled' => $this->total_call_agent_handled,
            'queue_calls' => $this->queue_calls,
            'queue_calls_completed' => $this->queue_calls_completed,
            'queue_calls_abondoned' => $this->queue_calls_abondoned,
            'end_time' => $this->end_time,
            'queue_hold_time' => $this->queue_hold_time,
        ]);

        $query->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'day', $this->day])
            ->andFilterWhere(['like', 'Time_Session', $this->Time_Session])
            ->andFilterWhere(['like', 'campaign', $this->campaign])
            ->andFilterWhere(['like', 'weekday', $this->weekday])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id])
            ->andFilterWhere(['like', 'keyinput', $this->keyinput])
            ->andFilterWhere(['like', 'agent_id', $this->agent_id])
            ->andFilterWhere(['like', 'agent_name', $this->agent_name])
            ->andFilterWhere(['like', 'last_call_time', $this->last_call_time])
            ->andFilterWhere(['like', 'queue', $this->queue])
            ->andFilterWhere(['like', 'queue_strategy', $this->queue_strategy]);

        return $dataProvider;
    }
}
