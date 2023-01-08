<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniRole;

/**
 * VaaniRoleSearch represents the model behind the search form of `common\models\VaaniRole`.
 */
class VaaniRoleSearch extends VaaniRole
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'parent_id', 'role_name', 'role_description', 'role_enable', 'created_by', 'created_date', 'created_ip', 'modified_by', 'modified_date', 'modified_ip', 'last_activity', 'change_set'], 'safe'],
            [['level', 'del_status'], 'integer'],
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
        $query = VaaniRole::find();

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

        $query->andWhere(['>=', 'level', 'parent_id']);

        // grid filtering conditions
        $query->andFilterWhere([
            'level' => $this->level,
            'modified_date' => $this->modified_date,
            'del_status' => $this->del_status,
        ]);

        $query->andFilterWhere(['like', 'role_id', $this->role_id])
            ->andFilterWhere(['like', 'parent_id', $this->parent_id])
            ->andFilterWhere(['like', 'role_name', $this->role_name])
            ->andFilterWhere(['like', 'role_description', $this->role_description])
            ->andFilterWhere(['like', 'role_enable', $this->role_enable])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'created_date', $this->created_date])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by])
            ->andFilterWhere(['like', 'modified_ip', $this->modified_ip])
            ->andFilterWhere(['like', 'last_activity', $this->last_activity])
            ->andFilterWhere(['like', 'change_set', $this->change_set]);

        return $dataProvider;
    }
}
