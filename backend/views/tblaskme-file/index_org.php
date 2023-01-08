<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TblaskmeFileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tblaskme Files';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tblaskme-file-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tblaskme File', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'process',
            'tab',
            'file_server',
            'file_count',
            //'file_name',
            //'file_path',
            //'file_content:ntext',
            //'created_by',
            //'created_date',
            //'updated_date',
            //'status',
            //'mandatory_info:ntext',
            //'additional_info:ntext',
            //'priority',
            //'clientid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
