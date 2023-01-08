<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniDispositions;

/**
 * VaaniDispositionsSearch represents the model behind the search form of `common\models\VaaniDispositions`.
 */
class VaaniDispositionsSearch extends VaaniDispositions
{
    public $camp_listing, $no_parent;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['campaign_id','disposition_id', 'disposition_name', 'short_code', 'created_date', 'modified_date', 'created_by', 'modified_by', 'created_ip', 'modified_ip', 'del_status', 'no_parent', 'parent_id', 'type'], 'safe'],
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
        $query = VaaniDispositions::find()->orderBy('sequence');

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

        if($this->no_parent){
            $query->andWhere(['parent_id' => NULL]);
        }

        $query->andFilterWhere(['IN', 'campaign_id', $this->campaign_id]);
        if($this->camp_listing){
            $query->groupBy('campaign_id');
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'disposition_id' => $this->disposition_id,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'del_status' => $this->del_status,
        ]);

        $query->andFilterWhere(['like', 'disposition_name', $this->disposition_name])
            ->andFilterWhere(['like', 'short_code', $this->short_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'modified_ip', $this->modified_ip]);

        return $dataProvider;
    }
}
