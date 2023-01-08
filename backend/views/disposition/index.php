<?php

use yii\helpers\Html;
use fedemotta\datatables\DataTables;
use common\models\VaaniDispositions;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniDispositionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Disposition';
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('Add Disposition', ['add'], ['class' => 'btn btn-outline-primary']) ?>
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
                [
                    'attribute' => 'name',
                    'label' => 'Plan',
                    'value' => function($model){
                        return ($model->name);
                    },
                ],  
                /* [
                    'attribute' => '',
                    'label' => 'Campaign',
                    'value' => function($model){
                        return ($model->campaign ? $model->campaign->campaign_name : '-');
                    },
                ], */
                [
                    'attribute' => '',
                    'label' => 'Dispositions',
                    'format' => 'html',
                    'value' => function($model){
                        $result = '<ul>';
                        $dispositions = $model->dispositions;
                        if($dispositions){
                            foreach($dispositions as $dispositions){
                                $result .= '<li>'. $dispositions->disposition_name .'</li>';
                            }
                            $result .= '</ul>';
                        }else{
                            $result = '-';
                        }
                        return $result;
                    },
                ],
                
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} | {delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="fas fa-pencil-alt"></span>', ['add', 'id' => User::encrypt_data($model->plan_id)]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="fa fa-trash"></span>', ['delete-all', 'id' => User::encrypt_data($model->plan_id)], [
                                'data-message' => 'Are you sure you want to delete all the disposition for the campaign ?',
                                'data-method' => 'post',
                                'class' => 'delete_disposition'
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
         <?php }else{ ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    'dispositions',
                ]
            ]); ?>
        <?php } ?>
    </div>
</section>

<?php
// display loader on delete
$this->registerJs("
    $('.delete_disposition').on('click', function(){
        var content = $(this).data('message');
        if(confirm(content)){
            $('#LoadingBox').show();
        }else{
            return false;
        }
    });
");
?>