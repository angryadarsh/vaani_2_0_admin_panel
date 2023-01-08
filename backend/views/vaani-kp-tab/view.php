<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniKpTab */

$this->title = $model->id;
// $this->params['breadcrumbs'][] = ['label' => 'Vaani Kp Tabs', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
// \yii\web\YiiAsset::register($this);
// ?>
<div class="vaani-kp-tab-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php //echo Html::a('Delete', ['delete', 'id' => $model->id], [
            //'class' => 'btn btn-danger',
            //'data' => [
                //'confirm' => 'Are you sure you want to delete this item?',
                //'method' => 'post',
           // ],
        //]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'templete_id',
            'tab_name',
            'file',
            'mandatory_info',
            'additional_info',
            // 'created_date',
            // 'modified_date',
            // 'created_by',
            // 'modified_by',
            // 'created_ip',
            // 'modified_ip',
            // 'del_status',
        ],
    ]) ?>

</div>
