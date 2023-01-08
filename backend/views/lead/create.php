<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniLeadDump */

$this->title = 'Create Vaani Lead Dump';
$this->params['breadcrumbs'][] = ['label' => 'Vaani Lead Dumps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vaani-lead-dump-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
