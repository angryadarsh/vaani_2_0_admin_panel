<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniLeadDump;

/**
 * VaaniLeadDumpSearch represents the model behind the search form of `common\models\VaaniLeadDump`.
 */
class VaaniLeadDumpSearch extends VaaniLeadDump
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'del_status'], 'integer'],
            [['lead_id', 'batch_id', 'campaign_id', 'lead_data', 'primary_no', 'date_created', 'created_by', 'date_modified', 'modified_by'], 'safe'],
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
        $query = VaaniLeadDump::find();

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
            'date_created' => $this->date_created,
            'date_modified' => $this->date_modified,
            'del_status' => $this->del_status,
        ]);

        $query->andFilterWhere(['like', 'lead_id', $this->lead_id])
            ->andFilterWhere(['like', 'batch_id', $this->batch_id])
            ->andFilterWhere(['like', 'campaign_id', $this->campaign_id])
            ->andFilterWhere(['like', 'lead_data', $this->lead_data])
            ->andFilterWhere(['like', 'primary_no', $this->primary_no])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by]);

        return $dataProvider;
    }
}
