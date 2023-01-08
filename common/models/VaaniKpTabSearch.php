<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniKpTab;
use common\models\User;


/**
 * VaaniKpTabSearch represents the model behind the search form of `common\models\VaaniKpTab`.
 */
class VaaniKpTabSearch extends VaaniKpTab
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'templete_id'], 'integer'],
            [['tab_name', 'file', 'mandatory_info', 'additional_info', 'created_date', 'modified_date', 'created_by', 'modified_by', 'created_ip', 'modified_ip', 'del_status'], 'safe'],
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
        $query = VaaniKpTab::find();

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
            'templete_id' => $this->templete_id,
        ]);
        $query->andFilterWhere(['del_status' => User::STATUS_NOT_DELETED]);

        $query->andFilterWhere(['like', 'tab_name', $this->tab_name])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'mandatory_info', $this->mandatory_info])
            ->andFilterWhere(['like', 'additional_info', $this->additional_info])
            ->andFilterWhere(['like', 'created_date', $this->created_date])
            ->andFilterWhere(['like', 'modified_date', $this->modified_date])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'modified_ip', $this->modified_ip])
            ->andFilterWhere(['like', 'del_status', User::STATUS_NOT_DELETED]);

        return $dataProvider;
    }
}
