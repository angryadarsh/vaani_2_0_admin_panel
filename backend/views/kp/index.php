<?php

use yii\helpers\Html;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VaaniKpTempleteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Help Template';

?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="clearfix mb-3 top-heading">
                <h4 class="m-0 float-left"><?= Html::encode($this->title) ?></h4>
                <div class="float-right">
            <p>
                <?= Html::a('<i class="fas fa-plus"></i>', ['create'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
            </p>
            </div>
            </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if($dataProvider->query->all()){ ?>
    <?= DataTables::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'templete_id',
            'template_name',
            // 'created_date',
            // 'modified_date',
            // 'created_by',
            //'modified_by',
            //'created_ip',
            //'modified_ip',
            //'del_status', 
                [   
                    'header' => 'Action',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{viewtabs} | {update} | {delete} | {pen}',
                        'buttons'=> [   
                            'viewtabs' => function ($url, $model) {
                                return Html::a('<span class="fa fa-eye"></span>', ['preview', 'id' => User::encrypt_data($model->templete_id)],['title' => 'Preview']);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' =>User::encrypt_data($model->templete_id)],['title' => 'Update']);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' =>User::encrypt_data($model->templete_id)],[
                                    'data-message' => 'Are you sure you want to delete ?',
                                    'data-method' => 'post',
                                    'title' => 'Delete',
                                    'class' => 'delete'
                                ]);
                            },
                            'pen' => function ($url ,$model){
                                return Html::a('<span class="fa fa-bars"></span>', ['vaani-kp-tab/index', 'id' =>User::encrypt_data($model->templete_id)],['title' => 'Tabs']);
                            },
                        ]
            ],
        ],
    ]); ?>
    <?php }else{ ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'template_name',
                    ['class' => 'yii\grid\ActionColumn'],
                ]
            ]); ?>
        <?php } ?>

</div>
    </div>
</div>
<script>
$('.delete').on('click', function(){
        var content = $(this).data('message');
        if(confirm(content)){
            $('#LoadingBox').show();
        }else{
            return false;
        }
    });
</script>