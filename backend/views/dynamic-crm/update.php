<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniDynamicCrm */

$this->title = 'Update Vaani Dynamic Crm: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Vaani Dynamic Crms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vaani-dynamic-crm-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
