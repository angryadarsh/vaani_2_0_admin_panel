<?php

use yii\helpers\Html;
use fedemotta\datatables\DataTables;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\EdasDniMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'DNI';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item"><a href="#" class="barIcon">DNI</a></li>
				<li class="breadcrumb-item active" aria-current="page">DNI List</li>
			</ol>
		</nav>
        <div class="clearfix top-header">
            <h1 class="float-left">DNI List</h1>
            <div class="float-right">
                <?= Html::a('Add DNI', ['create'], ['class' => 'btn btn-outline-primary']) ?>
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

                    'DNI_name',
                    [
                        'label' => 'DNI No.',
                        'value' => function($model){
                            return (($model->DNI_from >= 1) ? ($model->DNI_from . ' - ' . $model->DNI_to) : $model->DNI_other);
                        }
                    ],
                    /* [
                        'label' => 'Client',
                        'value' => function($model){
                            return ($model->client ? $model->client->client_name : '-');
                        }
                    ], */
                    [
                        'label' => 'Queue',
                        'value' => function($model){
                            return ($model->queue ? ($model->queue->queue_name) : '-');
                        }
                    ],
                    'carrier_name',
                    'created_date',
                    
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} | {delete}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => User::encrypt_data($model->id)]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' => User::encrypt_data($model->id)], [
                                    'data-message' => 'Are you sure you want to delete the dni for the client ?',
                                    'data-method' => 'post',
                                    'class' => 'delete_dni'
                                ]);
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
// display loader on delete
$this->registerJs("
    $('.delete_dni').on('click', function(){
        var content = $(this).data('message');
        if(confirm(content)){
            $('#LoadingBox').show();
        }else{
            return false;
        }
    });
");
?>