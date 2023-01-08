<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniCallTimesConfig */

$this->title = $model->call_time_name;
// $this->params['breadcrumbs'][] = ['label' => 'Vaani Call Times Configs', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
// $this->params['breadcrumbs'][] = 'Update';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('<i class="fas fa-chevron-left"></i>', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
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
                        <?= $this->render('_form', [
                            'model' => $model,
                            'dayModel' => $dayModel,
                            'types' => $types,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
