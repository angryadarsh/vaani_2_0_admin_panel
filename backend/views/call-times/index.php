<?php

use yii\helpers\Html;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;
use common\models\VaaniCallTimesConfig;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniCallTimesConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Call Windows';
?>

<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item active" aria-current="page">Call Windows</li>
			</ol>
		</nav>
        <div class="clearfix top-header">
            <h1 class="float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('Add Call Window', ['create'], ['class' => 'btn btn-outline-primary']) ?>
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

                    'call_time_name',
                    [
                        'attribute' => 'type',
                        'value' => function($model){
                            return VaaniCallTimesConfig::$types[$model->type];
                        }
                    ],
                    'created_date',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} | {delete}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<span class="fa fa-eye"></span>', ['view', 'id' => 
                                User::encrypt_data($model->id)]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' => 
                                User::encrypt_data($model->id)], [
                                    'data-confirm' => 'Are you sure you want to delete the call times template?',
                                    'data-method' => 'post',
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