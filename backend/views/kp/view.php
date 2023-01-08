<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\vaani_kp_templete */

$this->title = $model->templete_id;
// $this->params['breadcrumbs'][] = ['label' => 'Vaani Kp Templetes', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="vaani-kp-templete-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => User::encrypt_data($model->templete_id)], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => User::encrypt_data($model->templete_id)], [
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
            'templete_id',
            'template_name',
            'created_date',
            'modified_date',
            'created_by',
            'modified_by',
            'created_ip',
            'modified_ip',
            'del_status',
        ],
    ]) ?>

</div>
