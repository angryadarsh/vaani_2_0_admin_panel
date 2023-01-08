<?php

use yii\helpers\Html;
use fedemotta\datatables\DataTables;
use common\models\VaaniCampaignBreak;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniCampaignBreakSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Breaks';
?>
<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item active" aria-current="page">Breaks</li>
			</ol>
		</nav>
        <div class="clearfix top-header">
            <h1 class="float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('Add Break', ['create'], ['class' => 'btn btn-outline-primary']) ?>
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
                        'attribute' => 'campaign_id',
                        'label' => 'Campaign',
                        'value' => function($model){
                            return ($model->campaign ? $model->campaign->campaign_name : '-');
                        },
                    ],
                    [
                        'attribute' => 'break_name',
                        'label' => 'Breaks',
                        'format' => 'html',
                        'value' => function($model){
                            $result = '<ul>';
                            $campaign = $model->campaign;
                            if($campaign && $campaign->breaks){
                                foreach($campaign->breaks as $break){
                                    $result .= '<li>'. $break->break_name .'</li>';
                                }
                                $result .= '</ul>';
                            }else{
                                $result = '-';
                            }
                            return $result;
                        },
                    ],
                    /* [
                        'attribute' => 'is_active',
                        'label' => 'Status',
                        'value' => function($model){
                            return ($model->is_active && isset(VaaniCampaignBreak::$active_statuses[$model->is_active]) ? VaaniCampaignBreak::$active_statuses[$model->is_active] : '-');
                        },
                    ], */
                    
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} | {delete}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('<span class="fas fa-pencil-alt"></span>', ['add', 'c_id' => User::encrypt_data($model->campaign_id)]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<span class="fa fa-trash"></span>', ['delete-all', 'id' => User::encrypt_data($model->campaign_id)], [
                                    'data-confirm' => 'Are you sure you want to delete all the breaks for the campaign ' . $model->campaign->campaign_name . '?',
                                    'data-method' => 'post',
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
