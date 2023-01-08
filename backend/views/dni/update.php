<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\EdasDniMaster */

$this->title = 'Update DNI: ' . $model->DNI_name;
// $this->params['breadcrumbs'][] = ['label' => 'DNI', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
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
                            'clients' => $clients,
                            'dni_types' => $dni_types,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>