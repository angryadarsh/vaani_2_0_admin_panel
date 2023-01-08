<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniLeadDump */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vaani-lead-dump-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lead_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'batch_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'campaign_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lead_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'primary_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_created')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_modified')->textInput() ?>

    <?= $form->field($model, 'modified_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'del_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
