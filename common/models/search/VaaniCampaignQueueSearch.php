<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniCampaignQueue;

/**
 * VaaniCampaignQueueSearch represents the model behind the search form of `common\models\VaaniCampaignQueue`.
 */
class VaaniCampaignQueueSearch extends VaaniCampaignQueue
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'key_input'], 'integer'],
            [['queue', 'campaign_id', 'dni_id', 'criteria', 'created_date', 'modified_date', 'created_by', 'modified_by', 'created_ip', 'modified_ip', 'del_status', 'queue_name', 'queue_id', 'type'], 'safe'],
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
        $query = VaaniCampaignQueue::find();

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

        if($this->campaign_id){
            $query->andFilterWhere(['IN', 'campaign_id', $this->campaign_id]);
        }

        if($this->dni_id){
            $query->andFilterWhere(['IN', 'dni_id', $this->dni_id]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'queue_id' => $this->queue_id,
            'key_input' => $this->key_input,
            'type' => $this->type,
            'del_status' => $this->del_status,
        ]);

        $query->andFilterWhere(['like', 'queue', $this->queue])
            ->andFilterWhere(['like', 'criteria', $this->criteria])
            ->andFilterWhere(['like', 'created_date', $this->created_date])
            ->andFilterWhere(['like', 'modified_date', $this->modified_date])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'modified_ip', $this->modified_ip])
            ->andFilterWhere(['like', 'queue_name', $this->queue_name]);

        return $dataProvider;
    }
}
