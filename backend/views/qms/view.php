<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniQmsTemplate */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vaani Qms Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="vaani-qms-template-view">

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
            'template_name',
            'cq_score_target',
            'categorization:ntext',
            'action_status',
            'pip_status',
            'date_created',
            'date_modified',
            'created_by',
            'modified_by',
            'created_ip',
            'modified_ip',
            'del_status',
        ],
    ]) ?>

</div>
