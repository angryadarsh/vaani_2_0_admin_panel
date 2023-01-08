<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TblaskmeFile */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tblaskme Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tblaskme-file-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'clientid',
            'process',
            'tab',
            'file_server',
            'file_count',
            'file_name',
            'file_path',
            'file_content:ntext',
            'created_by',
            'created_date',
            'updated_date',
            'status',
            'priority',
        ],
    ]) ?>

</div>
