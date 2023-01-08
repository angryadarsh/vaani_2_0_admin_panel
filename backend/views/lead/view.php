<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniLeadDump */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vaani Lead Dumps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="vaani-lead-dump-view">

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
            'lead_id',
            'batch_id',
            'campaign_id',
            'lead_data:ntext',
            'primary_no',
            'date_created',
            'created_by',
            'date_modified',
            'modified_by',
            'del_status',
        ],
    ]) ?>

</div>
