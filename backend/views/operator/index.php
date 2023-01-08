<?php

use yii\helpers\Html;
use fedemotta\datatables\DataTables;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniOperatorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Operators';
?>
<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
			</ol>
		</nav>
        <div class="clearfix top-header">
            <h1 class="float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('Add Operator', ['create'], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <?= DataTables::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'operator_name',
                'created_date',
                'modified_date',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} | {delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => User::encrypt_data($model->id)], ['title' => 'Update Operator']);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' => User::encrypt_data($model->id)], [
                                'data-message' => 'Are you sure you want to delete this Operator?',
                                'data-method' => 'post',
                                'title' => 'Delete',
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
    </div>
</section>