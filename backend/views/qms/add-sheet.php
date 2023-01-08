<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniQmsSheet */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Create Qms Sheet';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <div class="float-right">
                <?= Html::a('<i class="fas fa-chevron-left"></i>', ['index'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
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
                        <?= $this->render('_sheet_form', [
                            'model' => $model,
                            'templates' => $templates,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>