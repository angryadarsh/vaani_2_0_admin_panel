<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniSessionLog;

/**
 * VaaniSessionLogSearch represents the model behind the search form of `common\models\VaaniSessionLog`.
 */
class VaaniSessionLogSearch extends VaaniSessionLog
{
    public $calls_date, $campaign_id, $live_monitoring, $is_hold_data;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auto_id', 'action_duration_sec', 'active_log', 'del_status'], 'integer'],
            [['log_id', 'session_id', 'user_id', 'unique_id', 'action_start_datetime', 'action_end_datetime', 'created_ip', 'broswer', 'broswer_detail', 'created_datetime', 'modified_date', 'status_id', 'calls_date', 'campaign_id', 'live_monitoring', 'is_hold_data'], 'safe'],
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
        $query = VaaniSessionLog::find();

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

        if($this->live_monitoring){
            $query->andWhere(['<>','status_id', VaaniSessionLog::STATUS_ALREADY_LOGIN]);
        }

        if($this->unique_id){
            $query->andFilterWhere(['IN', 'vaani_session_log.unique_id', $this->unique_id])->groupBy('vaani_session_log.user_id');
        }else{
            $query->andWhere(['vaani_session_log.unique_id' => null]);
        }

        if($this->status_id || $this->status_id == 0){
            $query->andFilterWhere(['IN', 'status_id', $this->status_id]);
        }

        if($this->calls_date){
            $calls_date = explode(' - ', $this->calls_date);
            $query->joinWith('agentCalls')->andFilterWhere(['and', ['>=', 'datetime', $calls_date[0]], ['<=', 'datetime', $calls_date[1]]]);
        }

        if($this->is_hold_data){
            $query->joinWith('agentCalls')->andWhere(['sub_status' => 'HOLD']);
        }

        if($this->campaign_id || ((Yii::$app->user->identity->userRole && strtolower(Yii::$app->user->identity->userRole->role_name) != 'superadmin') && strtolower(Yii::$app->user->identity->role) != 'superadmin')){
            $query->joinWith('session')->andWhere(['campaign' => $this->campaign_id]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'auto_id' => $this->auto_id,
            'action_start_datetime' => $this->action_start_datetime,
            'action_end_datetime' => $this->action_end_datetime,
            'action_duration_sec' => $this->action_duration_sec,
            'created_datetime' => $this->created_datetime,
            'modified_date' => $this->modified_date,
            'active_log' => $this->active_log,
            'vaani_session_log.del_status' => $this->del_status,
        ]);

        $query->andFilterWhere(['like', 'log_id', $this->log_id])
            ->andFilterWhere(['like', 'session_id', $this->session_id])
            ->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'broswer', $this->broswer])
            ->andFilterWhere(['like', 'broswer_detail', $this->broswer_detail]);

        return $dataProvider;
    }
}
