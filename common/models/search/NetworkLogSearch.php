<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniNetworkLog;

/**
 * NetworkLogSearch represents the model behind the search form of `common\models\VaaniNetworkLog`.
 */
class NetworkLogSearch extends VaaniNetworkLog
{
    public $dates;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'del_status', 'is_active'], 'integer'],
            [['session_id', 'agent_id', 'start_epoch', 'end_epoch', 'duration', 'created_date', 'modify_date', 'inserted_by', 'unique_id', 'dates'], 'safe'],
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
        $query = VaaniNetworkLog::find();

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

        if($this->agent_id){
            $query->andFilterWhere(['IN', 'agent_id', $this->agent_id]);
        }

        if($this->dates){
            // echo $this->dates;
            $dates = explode(' - ', $this->dates);
            if(isset($dates[0]) && isset($dates[1])){
                $start_time = $dates[0] . ' 00:00:00';
                $end_time = $dates[1] . ' 23:59:59';
                $query->andFilterWhere(['between', 'created_date', $start_time, $end_time]);
            }
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            // 'created_date' => $this->created_date,
            'modify_date' => $this->modify_date,
            'del_status' => $this->del_status,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'session_id', $this->session_id])
            // ->andFilterWhere(['like', 'agent_id', $this->agent_id])
            ->andFilterWhere(['like', 'start_epoch', $this->start_epoch])
            ->andFilterWhere(['like', 'end_epoch', $this->end_epoch])
            ->andFilterWhere(['like', 'duration', $this->duration])
            ->andFilterWhere(['like', 'inserted_by', $this->inserted_by])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id]);

        return $dataProvider;
    }
}
