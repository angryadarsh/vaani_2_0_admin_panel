<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{
    public $client_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_active'], 'integer'],
            [['user_id', 'user_name', 'user_password', 'gender', 'created_by', 'created_ip', 'created_date', 'modified_date', 'modified_by', 'modified_ip', 'del_status', 'client_id'], 'safe'],
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
        $query = User::find()->orderBy('id DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($this->client_id){
            $query->joinWith('userAccess')->andFilterWhere(['IN', 'client_id', $this->client_id]);
        }

        $user = Yii::$app->user->identity;
        if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
            // $query->andFilterWhere(['NOT IN', 'vaani_user.user_id', $user->user_id]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'vaani_user.id' => $this->id,
            'vaani_user.user_id' => $this->user_id,
            'vaani_user.created_date' => $this->created_date,
            'vaani_user.modified_date' => $this->modified_date,
            'vaani_user.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'user_password', $this->user_password])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'vaani_user.created_by', $this->created_by])
            ->andFilterWhere(['like', 'vaani_user.created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'vaani_user.modified_by', $this->modified_by])
            ->andFilterWhere(['like', 'vaani_user.modified_ip', $this->modified_ip])
            ->andFilterWhere(['like', 'vaani_user.del_status', $this->del_status]);

        return $dataProvider;
    }
}
