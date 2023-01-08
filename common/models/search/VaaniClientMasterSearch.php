<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniClientMaster;

/**
 * VaaniClientMasterSearch represents the model behind the search form of `common\models\VaaniClientMaster`.
 */
class VaaniClientMasterSearch extends VaaniClientMaster
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'del_status'], 'integer'],
            [['client_id', 'client_name', 'client_descreption', 'logo', 'dashboard_color', 'created_by', 'created_date', 'created_ip', 'modified_by', 'modified_date', 'modified_ip'], 'safe'],
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
        $query = VaaniClientMaster::find()->orderBy('id DESC');

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

        if($this->client_id){
            $query->andWhere(['IN', 'client_id', $this->client_id]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'modified_date' => $this->modified_date,
            'del_status' => $this->del_status,
            // 'client_id' => $this->client_id,
        ]);

        // $query->andFilterWhere(['IN', 'client_id', $this->client_id]);
        
        $query->andFilterWhere(['like', 'client_name', $this->client_name])
            ->andFilterWhere(['like', 'client_descreption', $this->client_descreption])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'dashboard_color', $this->dashboard_color])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'created_date', $this->created_date])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by])
            ->andFilterWhere(['like', 'modified_ip', $this->modified_ip]);

        return $dataProvider;
    }
}
