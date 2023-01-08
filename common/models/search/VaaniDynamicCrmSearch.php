<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniDynamicCrm;

/**
 * VaaniDynamicCrmSearch represents the model behind the search form of `common\models\VaaniDynamicCrm`.
 */
class VaaniDynamicCrmSearch extends VaaniDynamicCrm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'del_status'], 'integer'],
            [['crm_id', 'name', 'date_created', 'date_modified', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'safe'],
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
        $query = VaaniDynamicCrm::find();

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
            'del_status' => $this->del_status,
        ]);

        $query->andFilterWhere(['like', 'crm_id', $this->crm_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'date_created', $this->date_created])
            ->andFilterWhere(['like', 'date_modified', $this->date_modified])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'modified_ip', $this->modified_ip]);

        return $dataProvider;
    }
}
