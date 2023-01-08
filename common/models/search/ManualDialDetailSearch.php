<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ManualDialDetail;

/**
 * ManualDialDetailSearch represents the model behind the search form of `common\models\ManualDialDetail`.
 */
class ManualDialDetailSearch extends ManualDialDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['mobile_no', 'date', 'time', 'day', 'Time_Session', 'campaign', 'weekday', 'unique_id', 'end_time', 'status', 'transfer_start_time', 'trasfer_end_time', 'transfer_agent_id'], 'safe'],
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
        $query = ManualDialDetail::find();

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
            'end_time' => $this->end_time,
        ]);

        $query->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'day', $this->day])
            ->andFilterWhere(['like', 'Time_Session', $this->Time_Session])
            ->andFilterWhere(['like', 'campaign', $this->campaign])
            ->andFilterWhere(['like', 'weekday', $this->weekday])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'transfer_start_time', $this->transfer_start_time])
            ->andFilterWhere(['like', 'trasfer_end_time', $this->trasfer_end_time])
            ->andFilterWhere(['like', 'transfer_agent_id', $this->transfer_agent_id]);

        return $dataProvider;
    }
}
