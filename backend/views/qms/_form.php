<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\VaaniQmsTemplate;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniQmsTemplate */
/* @var $form yii\widgets\ActiveForm */

$urlManager = Yii::$app->urlManager;
?>

<?php $form = ActiveForm::begin(['id' => 'template_form', 'options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
} "], 'enableAjaxValidation' => true, 'validateOnChange' => false]); ?>

<div class="row template-section">

    <div class="col-lg-1"></div>
    <div class="col-lg-5">
</label>
        <?= $form->field($model, 'template_name', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Template Name', 'required' => true])->label('Template Name', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-5">
        <?= $form->field($model, 'cq_score_target', ['template' => '{label}{input}{error}{hint}'])->textInput(['type' => 'number', 'placeholder' => 'CQ Score Target (%)', 'required' => true])->label('CQ Score Target (%)', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-1"></div>
    
    <div class="col-lg-1"></div>
    <div class="col-lg-2">
        <label>Categorization</label>
    </div>
    <div class="col-lg-2">
        <?= $form->field($model, 'categorization[A]', ['template' => '{label}{input}{error}{hint}'])->textInput(['maxlength' => '2' /* , 'pattern' => '\d{4}' */, 'placeholder' => 'A', 'required' => true, 'class' => 'form-control categorization_score'])->label('A', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-2">
        <?= $form->field($model, 'categorization[B]', ['template' => '{label}{input}{error}{hint}'])->textInput(['maxlength' => '2' /* , 'pattern' => '\d{4}' */, 'placeholder' => 'B', 'required' => true, 'class' => 'form-control categorization_score'])->label('B', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-2">
        <?= $form->field($model, 'categorization[C]', ['template' => '{label}{input}{error}{hint}'])->textInput(['maxlength' => '2' /* , 'pattern' => '\d{4}' */, 'placeholder' => 'C', 'required' => true, 'class' => 'form-control categorization_score'])->label('C', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-2">
        <?= $form->field($model, 'categorization[D]', ['template' => '{label}{input}{error}{hint}'])->textInput(['maxlength' => '2' /* , 'pattern' => '\d{4}' */, 'placeholder' => 'D', 'required' => true, 'class' => 'form-control categorization_score'])->label('D', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-1"></div>

    <div class="col-lg-1"></div>
    <div class="col-lg-5">
        <?= $form->field($model, 'action_status', ['template' => '{label}{input}{error}{hint}'])->dropdownList(VaaniQmsTemplate::$action_statuses, ['prompt' => '---Select---', 'required' => true])->label('Action Status', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-5">
        <?= $form->field($model, 'pip_status', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'PIP Status', 'required' => true, 'readonly' => true])->label('PIP Status', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-1"></div>
    
    <div class="col-lg-12 form-group text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary submit-btn']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>

</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs("
    $('#template_form').on('submit', function(){
        if($('.help-block').text()) {
            $('#LoadingBox').hide();
            return false;
        }
    });

    // number validation
    $('#vaaniqmstemplate-cq_score_target').on('change', function (){
        var val = $(this).val();
        var check = $.isNumeric(val);  
        if(check){
            if(val > 100){
                alert('Numbers Must Be smaller than 100!');
                $(this).next().text('Numbers Must Be smaller than 100!');
                $('.submit-btn').attr('disabled', true);
                return false;
            }else if(val < 0){
                alert('Negative Numbers Are Not Allowed!');
                $(this).next().text(' Negative Numbers Are Not Allowed!');
                $('.submit-btn').attr('disabled', true);
                return false;
            }else{
                $('.submit-btn').attr('disabled', false);
            }
        }else{
            alert('Only Valid Numbers are allowed!');
            $(this).next().text('Only Valid Numbers are allowed!');
            $('.submit-btn').attr('disabled', true);
            return false;
        }
    });
    
    // number validation
    $('.categorization_score').on('change', function (){
        var val = $(this).val();
        var check = $.isNumeric(val);  
        if(check){
            if(val > 100){
                alert('Numbers Must Be smaller than 100!');
                $(this).next().text('Numbers Must Be smaller than 100!');
                $('.submit-btn').attr('disabled', true);
                return false;
            }else if(val < 0){
                alert('Negative Numbers Are Not Allowed!');
                $(this).next().text(' Negative Numbers Are Not Allowed!');
                $('.submit-btn').attr('disabled', true);
                return false;
            }else{
                $('.submit-btn').attr('disabled', false);
            }
        }else{
            alert('Only Valid Numbers are allowed!');
            $(this).next().text('Only Valid Numbers are allowed!');
            $('.submit-btn').attr('disabled', true);
            return false;
        }
    });
");
?>