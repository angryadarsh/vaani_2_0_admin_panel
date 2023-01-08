<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
} "], 'enableAjaxValidation' => true, 'validateOnChange' => false]); ?>
<div class="card text-center ">
    <div class="row justify-content-center">
        <div class=" col-5 mx-5 px-5">
            <div class=" text-center">
                <p class="h5 section-header col-lg-10 mt-5 pt-5">Change Password</p>
            </div>
            <div class="col-lg-10">
                <?= $form->field($load_model, 'old_password', ['template' => '{input}{label}{error}{hint}'])->passwordInput(['placeholder' => 'Current Password', 'required' => true])->label('Current Password', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-10">
                <?= $form->field($load_model, 'new_password', ['template' => '{input}{label}{error}{hint}'])->passwordInput(['placeholder' => 'New Password', 'required' => true])->label('New Password', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-10">
                <?= $form->field($load_model, 'confirm_password', ['template' => '{input}{label}{error}{hint}'])->passwordInput(['placeholder' => 'Confirm Password', 'required' => true])->label('Confirm Password', ['class' => 'form-label']) ?>
            </div>

            <div class=" col-lg-10 form-group text-center">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton('Cancel', ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>