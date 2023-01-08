<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniMenu;

/**
 * VaaniMenuSearch represents the model behind the search form of `common\models\VaaniMenu`.
 */
class VaaniMenuSearch extends VaaniMenu
{
    public $is_sequence;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id', 'parent_id', 'level', 'sequence', 'status', 'del_status', 'not_in_group'], 'integer'],
            [['menu_name', 'route', 'link', 'icon', 'created_by', 'created_date', 'created_ip', 'modified_by', 'modified_date', 'modified_ip', 'is_sequence'], 'safe'],
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
        $query = VaaniMenu::find();

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
            'menu_id' => $this->menu_id,
            'parent_id' => $this->parent_id,
            'level' => $this->level,
            'sequence' => $this->sequence,
            'status' => $this->status,
            'created_date' => $this->created_date,
            'del_status' => $this->del_status,
            'not_in_group' => $this->not_in_group,
        ]);

        $query->andFilterWhere(['like', 'menu_name', $this->menu_name])
            ->andFilterWhere(['like', 'route', $this->route])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by])
            ->andFilterWhere(['like', 'modified_date', $this->modified_date])
            ->andFilterWhere(['like', 'modified_ip', $this->modified_ip]);

        if($this->is_sequence){
            $query->orderBy('sequence');
        }
        return $dataProvider;
    }
}
