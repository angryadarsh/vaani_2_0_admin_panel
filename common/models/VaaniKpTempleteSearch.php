<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\vaani_kp_templete;
use common\models\User;

/**
 * VaaniKpTempleteSearch represents the model behind the search form of `common\models\vaani_kp_templete`.
 */
class VaaniKpTempleteSearch extends vaani_kp_templete
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['templete_id'], 'integer'],
            [['template_name', 'created_date', 'modified_date', 'created_by', 'modified_by', 'created_ip', 'modified_ip', 'del_status'], 'safe'],
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
        $query = vaani_kp_templete::find();

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
            'id' => $this->templete_id,
        ]);

        $query->andFilterWhere(['del_status' => User::STATUS_NOT_DELETED]);
        
        $query->andFilterWhere(['like', 'template_name', $this->template_name])
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
