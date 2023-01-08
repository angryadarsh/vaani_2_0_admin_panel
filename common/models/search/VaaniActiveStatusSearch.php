<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniActiveStatus;

/**
 * VaaniActiveStatusSearch represents the model behind the search form of `common\models\VaaniActiveStatus`.
 */
class VaaniActiveStatusSearch extends VaaniActiveStatus
{
    public $calls_date, $is_hold_data, $queueId ,$role_id, $campaignId;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auto_id', 'del_status'], 'integer'],
            [['session_id', 'user_id', 'unique_id', 'created_datetime', 'modified_date', 'calls_date', 'status', 'is_hold_data','queueId','role_id','campaignId'], 'safe'],
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
        $query = VaaniActiveStatus::find();

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

        if($this->unique_id){
            $query->andFilterWhere(['IN', 'vaani_active_status.unique_id', $this->unique_id])->groupBy('vaani_active_status.user_id');
        }else{
            $query->andWhere(['vaani_active_status.unique_id' => null]);
        }

        if($this->status || $this->status == 0){
            $query->andFilterWhere(['IN', 'vaani_active_status.status', $this->status]);
        }

        if($this->calls_date){
            $calls_date = explode(' - ', $this->calls_date);
            $query->joinWith('agentCalls')->andFilterWhere(['and', ['>=', 'datetime', $calls_date[0]], ['<=', 'datetime', $calls_date[1]]]);
        }

        if($this->is_hold_data){
            $query->joinWith('agentCalls')->andWhere(['vaani_active_status.sub_status' => 'hold']);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'auto_id' => $this->auto_id,
            // 'status' => $this->status,
            'created_datetime' => $this->created_datetime,
            'modified_date' => $this->modified_date,
            'del_status' => $this->del_status,
        ]);

        $query->andFilterWhere(['like', 'session_id', $this->session_id])
            // ->andFilterWhere(['like', 'vaani_active_status.user_id', $this->user_id]);
            // ->andFilterWhere(['like', 'unique_id', $this->unique_id]);
            ->andFilterWhere(['IN', 'vaani_active_status.user_id', $this->user_id])
            ->andFilterWhere(['IN', 'vaani_active_status.campaign_id', $this->campaignId]);

        return $dataProvider;
    }
}
