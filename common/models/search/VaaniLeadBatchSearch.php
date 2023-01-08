<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniLeadBatch;

/**
 * VaaniLeadBatchSearch represents the model behind the search form of `common\models\VaaniLeadBatch`.
 */
class VaaniLeadBatchSearch extends VaaniLeadBatch
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_previously_mapped', 'is_active', 'del_status'], 'integer'],
            [['campaign_id', 'queue_id', 'batch_id', 'batch_name', 'mapping_id', 'crm_group_id', 'date_created', 'date_modified', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'safe'],
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
        $query = VaaniLeadBatch::find();

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

        $query->orderBy([
            'date_created' => SORT_DESC,
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_previously_mapped' => $this->is_previously_mapped,
            'is_active' => $this->is_active,
            'del_status' => $this->del_status,
        ]);

        $query->andFilterWhere(['like', 'campaign_id', $this->campaign_id])
            ->andFilterWhere(['like', 'queue_id', $this->queue_id])
            ->andFilterWhere(['like', 'batch_id', $this->batch_id])
            ->andFilterWhere(['like', 'batch_name', $this->batch_name])
            ->andFilterWhere(['like', 'mapping_id', $this->mapping_id])
            ->andFilterWhere(['like', 'crm_group_id', $this->crm_group_id])
            ->andFilterWhere(['like', 'date_created', $this->date_created])
            ->andFilterWhere(['like', 'date_modified', $this->date_modified])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'modified_ip', $this->modified_ip]);

        return $dataProvider;
    }
}
