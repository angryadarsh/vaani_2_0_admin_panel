<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\EdasDniMaster;

/* @var $this yii\web\View */
/* @var $model common\models\EdasDniMaster */
/* @var $form yii\widgets\ActiveForm */

$urlManager = Yii::$app->urlManager;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
} "], 'enableAjaxValidation' => true, 'validateOnChange' => false, 'id' => 'submitForm']); ?>

<div class="row">
    <div class="col-lg-6">
        <?= $form->field($model, 'client_id', ['template' => '{label}{input}{error}{hint}'])->dropdownList($clients, ['prompt' => 'Select...', 'required' => true])->label(' Client Name', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'carrier_name', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Carrier Name', 'required' => true])->label('Carrier Name', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'DNI_name', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'DNI Name', 'required' => true])->label('DNI Name', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'DNI_prefix', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'DNI Prefix'])->label('DNI Prefix', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'dni_type', ['template' => '{label}{input}{error}{hint}'])->dropdownList($dni_types, ['prompt' => 'Select...', 'required' => true])->label('DNI Type', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-6 dni_from_section">
        <?= $form->field($model, 'DNI_from', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'DNI From'])->label('DNI From', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-6 dni_to_section">
        <?= $form->field($model, 'DNI_to', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'DNI To'])->label('DNI To', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-6 dni_other_section">
        <?= $form->field($model, 'DNI_other', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'DNI Number'])->label('DNI Number', ['class' => 'form-label']) ?>
    </div>

    <div class="col-lg-12 form-group text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary form_loader']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs("
    var dni_type = $('#edasdnimaster-dni_type').val();
    if(dni_type == ".EdasDniMaster::TYPE_RANGE."){
        $('.dni_other_section').hide();
        $('.dni_from_section, .dni_to_section').show();
    }else if(dni_type == ".EdasDniMaster::TYPE_OTHER."){
        $('.dni_from_section, .dni_to_section').hide();
        $('.dni_other_section').show();
    }else{
        $('.dni_from_section, .dni_to_section, .dni_other_section').hide();
    }

    $('#edasdnimaster-dni_type').on('change', function(){
        var dni_type = $(this).val();
        if(dni_type == ".EdasDniMaster::TYPE_RANGE."){
            $('.dni_other_section').hide();
            $('#edasdnimaster-dni_other').val('');
            $('.dni_from_section, .dni_to_section').show();
        }else if(dni_type == ".EdasDniMaster::TYPE_OTHER."){
            $('.dni_from_section, .dni_to_section').hide();
            $('#edasdnimaster-dni_from').val('');
            $('#edasdnimaster-dni_to').val('');
            $('.dni_other_section').show();
        }else{
            $('.dni_from_section, .dni_to_section, .dni_other_section').hide();
        }
    });
");
?>