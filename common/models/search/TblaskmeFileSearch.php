<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TblaskmeFile;

/**
 * TblaskmeFileSearch represents the model behind the search form of `common\models\TblaskmeFile`.
 */
class TblaskmeFileSearch extends TblaskmeFile
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'priority'], 'integer'],
            [['clientid', 'process', 'tab', 'file_server', 'file_count', 'file_name', 'file_path', 'file_content', 'created_by', 'created_date', 'updated_date'], 'safe'],
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
        $query = TblaskmeFile::find();

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
            'status' => $this->status,
            'priority' => $this->priority,
        ]);

        $query->andFilterWhere(['like', 'clientid', $this->clientid])
            ->andFilterWhere(['like', 'process', $this->process])
            ->andFilterWhere(['like', 'tab', $this->tab])
            ->andFilterWhere(['like', 'file_server', $this->file_server])
            ->andFilterWhere(['like', 'file_count', $this->file_count])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'file_content', $this->file_content])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'created_date', $this->created_date])
            ->andFilterWhere(['like', 'updated_date', $this->updated_date]);

        return $dataProvider;
    }
}
