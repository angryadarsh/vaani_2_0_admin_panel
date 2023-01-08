<?php

use yii\helpers\Html;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;
use common\models\User;
use common\models\VaaniSession;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left">Users</h1>
            <div class="float-right">
                <?= Html::a('Add User', ['create'], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <?php if($dataProvider->query->all()){ ?>
            <?= DataTables::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'user_id',
                    'user_name',
                    /* [
                        'label' => 'Client',
                        'value' => function($model){
                            return ($model->userAccess && $model->userAccess[0]->client ? $model->userAccess[0]->client->client_name : '-');
                        }
                    ], */
                    [
                        'label' => 'Role',
                        'value' => function($model){
                            return ($model->userAccess && $model->userAccess[0]->role ? $model->userAccess[0]->role->role_name : '-');
                        }
                    ],
                    'created_date',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete} {logout}',
                        'buttons'=> [
                            'update' => function ($url, $model) {
                                return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => User::encrypt_data($model->id)], ['title' => 'Update User']);
                            },
                            
                            'delete' => function ($url, $model) {
                                return Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' => User::encrypt_data($model->id)], [
                                    'data-message' => 'Are you sure you want to delete this User?',
                                    'data-method' => 'post',
                                    'class' => "delete_user"
                                ]);
                            },
                            'logout' => function ($url, $model) {
                                $loggedInUser = VaaniSession::find()->where(['del_status' => VaaniSession::STATUS_NOT_DELETED, 'logout_datetime' => null, 'user_id' => $model->user_id])->one();
                                if($loggedInUser){
                                    return Html::a('<span class="fas fa-power-off"></span>', ['site/agent-logout', 'id' => $model->user_id], ['title' => 'Logout User']);
                                }
                            },
                        ]
                    ],
                ],
                'clientOptions' => [
                    "lengthMenu" => [[10, 20, 50, -1], [10, 20, 50, "All"]],
                    "responsive"=>true, 
                    "dom"=> 'frtip',
                ],
            ]); ?>
        <?php }else{ ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'user_id',
                    'user_name',
                    'role',
                    'created_date',
                    ['class' => 'yii\grid\ActionColumn',]
                ]
            ]); ?>
        <?php } ?>
    </div>
</section>

<?php
// display loader on delete
$this->registerJs("
    $('.delete_user').on('click', function(){
        var content = $(this).data('message');
        if(confirm(content)){
            $('#LoadingBox').show();
        }else{
            return false;
        }
    });
");
?>