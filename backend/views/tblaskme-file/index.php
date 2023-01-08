<?php

use yii\helpers\Html;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;
use common\models\TblaskmeFile;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TblaskmeFileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Knowledge Portal';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item active" aria-current="page">Knowledge Portal</li>
			</ol>
		</nav>
        <div class="clearfix top-header">
            <h1 class="float-left">Knowledge Portal List</h1>
            <div class="float-right">
                <?= Html::a('Add Tabs', ['create'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                            <?= DataTables::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    ['label'=>'Client',
                                        'value' => function ($data) {
                                            return TblaskmeFile::getClientName($data->clientid); }
                                    ],
                                    ['label'=>'Campaign',
                                        'value' => function ($data) {
                                            return TblaskmeFile::getCampaignName($data->process); }
                                    ],
                                    'tab',
                                    'created_by',
                                    'file_name',
                                    'created_date',
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        // 'template' => '{update} {delete}',
                                        'template' => '{delete}',
                                        'buttons'=> [
                                            'delete' => function ($url, $model) {
                                                return Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' => $model->id], [
                                                    'data-confirm' => 'Are you sure you want to delete this User?',
                                                    'data-method' => 'post',
                                                ]);
                                            }
                                        ]
                                    ],
                                ],
                            ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
