<?php

use yii\helpers\Html;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VaaniKpTabSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tabs';
// echo"<pre";print_r($_GET);exit;
$id = trim($_GET['id']);
$id = User::decrypt_data($id);

?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="clearfix mb-3 top-heading">
                <h4 class="m-0 float-left"><?= Html::encode($this->title) ?></h4>
                <div class="float-right">
                <?php //echo Html::a(' Arrange Tab', ['arrangetab'], ['class' => 'btn btn-outline-primary mr-2','id' => $id]) ?>
                <?= Html::a('<span class="btn btn-outline-primary btn-sm mr-2">Preview</span>', ['kp/preview', 'id' =>User::encrypt_data($id . '_1')]);?>
                <?= Html::a('<span class="btn btn-outline-primary btn-sm mr-2">Arrange Tab</span>', ['arrangetab', 'id' =>User::encrypt_data($id)]); ?>
                <?= Html::a('<span class="btn btn-outline-primary btn-sm mr-2"><i class="fas fa-plus"></i> Tab</span>', ['create', 'id' =>User::encrypt_data($id)]); ?>
                <?= Html::a('<span class="btn btn-outline-primary btn-sm"><i class="fas fa-chevron-left"></i></span>', ['kp/index', 'id' =>User::encrypt_data($id)]); ?>
                </div>
                </div>
                <?php if($dataProvider->query->all()){ ?>
                <?= DataTables::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //'templete_id' => $templete_id,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        //'id',
                        //'templete_id',
                        'tab_name',
                        'file',
                        //'mandatory_info',
                        'additional_info',
                        //'created_date',
                        //'modified_date',
                        //'created_by',
                        //'modified_by',
                        //'created_ip',
                        //'modified_ip',
                        //'del_status',

                        [   
                            'header' => 'Action',
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update} | {delete}',
                            'buttons'=> [
                            'update' => function ($url, $model) {
                                return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' =>User::encrypt_data($model->id)],['title' => 'Update']);
                            },

                            'delete' => function ($url, $model) {
                                return Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' =>User::encrypt_data($model->id)],[
                                    'data-message' => 'Are you sure you want to delete ?',
                                    'data-method' => 'post',
                                    'title' => 'Delete',
                                    'class' => 'delete'
                                ]);
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
                        'tab_name',
                        'file',
                        'additional_info',
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
