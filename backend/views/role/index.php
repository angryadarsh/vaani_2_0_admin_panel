<?php

use yii\helpers\Html;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;
use common\models\VaaniRole;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniRoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roles';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item"><a href="#" class="barIcon">Role</a></li>
				<li class="breadcrumb-item active" aria-current="page">Role List</li>
			</ol>
		</nav>
        <div class="clearfix top-header">
            <h1 class="float-left">Roles</h1>
            <div class="float-right">
                <?= Html::a('Add Role', ['create'], ['class' => 'btn btn-outline-primary']) ?>
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

                    'role_name',
                    [
                        'attribute' => 'parent_id',
                        'value' => function($data){
                            return ($data->parent ? $data->parent->role_name : '-');
                        }
                    ],
                    [
                        'attribute' => 'role_enable',
                        'format' => 'html',
                        'value' => function($data){
                            if(strtolower($data->role_name) != 'superadmin'){
                                return ($data->role_enable == VaaniRole::STATUS_ACTIVE ? Html::a('<i class="far fa-check-square btn-sm text-success"></i>', ['set-status', 'id' => $data->id, 'status' => VaaniRole::STATUS_INACTIVE], ['title' => 'Set Inactive']) : Html::a('<i class="far fa-square btn-sm text-danger"></i>', ['set-status', 'id' => $data->id, 'status' => VaaniRole::STATUS_ACTIVE], ['title' => 'Set Active']));
                            }else{
                                return "";
                            }
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}  {delete}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                $id = User::encrypt_data($model->id);
                              
                                return (((Yii::$app->user->identity->userRole && Yii::$app->user->identity->userRole->role_name == 'superadmin') || (strtolower($model->role_name) != 'superadmin')) ? Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $id]) : false);
                            },
                            'delete' => function ($url, $model) {
                               
                                $id = User::encrypt_data($model->id);
                                return (((Yii::$app->user->identity->userRole && Yii::$app->user->identity->userRole->role_name == 'superadmin') || (strtolower($model->role_name) != 'superadmin')) ? Html::a('<span class="fa fa-trash"></span>',
                                ['delete', 'id' => $id], [
                                    'data-message' => 'Are you sure you want to delete the role ?',
                                    'data-method' => 'post',
                                ]) : false);
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
        <?php } ?>
    </div>
</section>

<?php
$this->registerCss("
    tr{ cursor: auto !important; }
");
?>