<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniRole */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
}"]]); ?>

<div class="row">
    <div class="col-lg-6">
        <?= $form->field($model, 'role_name')->textInput(['placeholder' => 'Role Name'])->label(false) ?>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'parent_id')->dropdownList($roles, ['prompt' => 'Select Parent'])->label(false) ?>
    </div>

    <div class="col-lg-12 form-group text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Cancel', ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>