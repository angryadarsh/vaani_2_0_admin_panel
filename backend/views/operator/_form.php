<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniOperators */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
}"], 'enableAjaxValidation' => true, 'validateOnChange' => false, 'id' => 'submitForm']); ?>

<div class="row">
    <div class="col-lg-12">
        <?= $form->field($model, 'operator_name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-12 form-group text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>