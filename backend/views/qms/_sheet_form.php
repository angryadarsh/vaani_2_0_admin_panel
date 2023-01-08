<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\VaaniQmsSheet;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniQmsSheet */
/* @var $form yii\widgets\ActiveForm */

$urlManager = Yii::$app->urlManager;
?>

<?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
} "], 'enableAjaxValidation' => true, 'validateOnChange' => false]); ?>

<div class="row sheet-section"> 
    
    <div class="col-lg-1"></div>
    <div class="col-lg-10">
        <?= $form->field($model, 'qms_id', ['template' => '{label}{input}{error}{hint}'])->dropdownList($templates, ['prompt' => '---Select---', 'required' => true])->label('Select Template', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-1"></div>
    
    <div class="col-lg-3"></div>
    <div class="col-lg-8">
        <?= $form->field($model, 'type', ['template' => '{label}{input}{error}{hint}'])->radioList(
            [1 => 'Transactional', 2 => 'Analytical'],
            [
                'item' => function($index, $label, $name, $checked, $value) {

                    $return = '<label class="modal-radio" style="width:45%">';
                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" tabindex="3" '.($checked == $value ? "checked" : "" ).'>';
                    $return .= '<i></i>';
                    $return .= '<span>' . ucwords($label) . '</span>';
                    $return .= '</label>';

                    return $return;
                }
            ]
        )->label(false) ?>
    </div>
    <div class="col-lg-1"></div>

    <div class="col-lg-1"></div>
    <div class="col-lg-5">
        <?= $form->field($model, 'sheet_name', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Enter Audit Sheet', 'required' => true])->label('Enter Audit Sheet', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-5">
        <?= $form->field($model, 'out_of', ['template' => '{label}{input}{error}{hint}'])->dropdownList(VaaniQmsSheet::$out_of_data, ['prompt' => '---Select---', 'required' => true])->label('Out Of (%)', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-1"></div>
    
    <div class="col-lg-12 form-group text-center">
        <?= Html::submitButton('Add', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs("
    // SHOW OUT OF FIELD ONLY FOR TRANSACTIONAL
    function outofVisible(type)
    {
        if(type == 1){
            $('.field-vaaniqmssheet-out_of').show();
            $('#vaaniqmssheet-out_of').attr('required', true);
        }else{
            $('.field-vaaniqmssheet-out_of').hide();
            $('#vaaniqmssheet-out_of').val('');
            $('#vaaniqmssheet-out_of').attr('required', false);
        }
    }

    outofVisible($('[name=\"VaaniQmsSheet[type]\"]:checked').val());

    $('[name=\"VaaniQmsSheet[type]\"]').on('change', function(){
        outofVisible($(this).val());
    });
");
?>