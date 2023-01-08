<?php

use yii\helpers\Html;
use fedemotta\datatables\DataTables;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniClientMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Client';
// $this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item"><a href="#" class="barIcon">Client</a></li>
				<li class="breadcrumb-item active" aria-current="page">Client List</li>
			</ol>
		</nav>
        <div class="clearfix top-header">
            <h1 class="float-left">Client List</h1>
            <div class="float-right">
                <?= Html::a('Add Client', ['create'], ['class' => 'btn btn-outline-primary']) ?>
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

                    'client_name',
                    'client_descreption',
                    'created_date',
                    
                    [
                        'header' => 'Actions',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} | {delete}',
                        'buttons'=> [
                            'update' => function ($url, $model) {
                                return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => User::encrypt_data($model->id)]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' => User::encrypt_data($model->id)], [
                                    'data-message' => ($model->campaignsList ? ('This Client includes following Campaigns and will be deleted : (' . implode(", ", $model->campaignsList) . '). ') : '') . 'Are you sure you want to delete this Client?',
                                    'data-method' => 'post',
                                    'class' => 'delete_client'
                                ]);
                            }
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
// display loader on delete
$this->registerJs("
    $('.delete_client').on('click', function(){
        var content = $(this).data('message');
        if(confirm(content)){
            $('#LoadingBox').show();
        }else{
            return false;
        }
    });
");
?>