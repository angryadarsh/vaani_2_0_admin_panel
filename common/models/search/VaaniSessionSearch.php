<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VaaniSession;
use common\models\User;
use yii\helpers\ArrayHelper;
use common\models\VaaniRole;
use common\models\VaaniClientMaster;

/**
 * VaaniSessionSearch represents the model behind the search form of `common\models\VaaniSession`.
 */
class VaaniSessionSearch extends VaaniSession
{
    public $dates, $is_report;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'del_status', 'syn'], 'integer'],
            [['session_id', 'user_id', 'campaign', 'login_datetime', 'last_action_epoch', 'logout_datetime', 'date_created', 'created_ip', 'created_datetime', 'modified_date', 'unique_id', 'client_id', 'dates', 'is_report'], 'safe'],
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
        $query = VaaniSession::find();

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
        $client_id = (isset($_SESSION['client_connection']) ? $_SESSION['client_connection'] : null);
        $role_id = VaaniRole::find()->select('role_id')->where(['role_name' => 'Agent','del_status' => VaaniRole::STATUS_NOT_DELETED]);
        $agents = ArrayHelper::getColumn(User::userList(null, $role_id, true, $client_id), 'user_id');
        if($this->user_id){
            if (in_array($this->user_id, $agents)) 
            {
                $query->andFilterWhere(['IN', 'user_id', $this->user_id]);
            }else{
                $query->andFilterWhere(['IN', 'user_id', $agents]);
            }
        }
        
        if($this->dates){
            $dates = explode(' - ', $this->dates);
            if(isset($dates[0]) && isset($dates[1])){
                $start_time = strtotime($dates[0] . ' 00:00:00');
                $end_time = strtotime($dates[1] . ' 23:59:59');
                $query->andFilterWhere(['between', 'last_action_epoch', $start_time, $end_time])
                ->andFilterWhere(['user_id' => $agents]);
            }
        }
       
        if($this->is_report){
            $query->orderBy('id DESC');
        }
            
        // grid filtering conditions
        $query->andFilterWhere([
            // 'id' => $this->id,
            'login_datetime' => $this->login_datetime,
            'logout_datetime' => $this->logout_datetime,
            'date_created' => $this->date_created,
            'created_datetime' => $this->created_datetime,
            'modified_date' => $this->modified_date,
            'del_status' => $this->del_status,
            'syn' => $this->syn,
        ]);

        $query->andFilterWhere(['like', 'session_id', $this->session_id])
            // ->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'campaign', $this->campaign])
            ->andFilterWhere(['like', 'last_action_epoch', $this->last_action_epoch])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id])
            ->andFilterWhere(['like', 'client_id', $this->client_id]);

        return $dataProvider;
    }
}
