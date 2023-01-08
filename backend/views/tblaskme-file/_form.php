<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\TblaskmeFile */
/* @var $form yii\widgets\ActiveForm */

$urlManager = Yii::$app->urlManager;

?>


<?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]); ?>

<div class="row">
    <div class="col-lg-6">
        <?= $form->field($model, 'clientid', ['template' => '{label}{input}{error}{hint}'])->dropdownList($clients, ['prompt' => 'Select Client'])->label('clientid', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'process', ['template' => '{label}{input}{error}{hint}'])->widget(Select2::classname(), [
            'data' => $campaigns,
            'options' => ['placeholder' => 'Select Campaigns'],
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => false
            ],
        ])->label('Campaigns', ['class' => 'form-label']); ?>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'tab', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Tab', 'readOnly' => ($model->isNewRecord ? false : 'readOnly')])->label('Tab', ['class' => 'form-label']) ?>
    </div>
 
    <div class="col-lg-6">
        <?= $form->field($model, 'file')->fileInput()->label('<div class="upload-icon-label">Upload Logo</div><span class="logo_label"><i class="fa fa-upload btn btn-info"> </i></span> <span class="logo_name"></span>') ?>
    </div>

    <div class="col-lg-12 form-group text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs("
    //ONCHANGE OF CLIENT, FETCH IT'S CAMPAIGNS
    $('#tblaskmefile-clientid').on('change', function(){
        var client_id = $(this).val();
        $.ajax({
            method: 'POST',
            url: '". $urlManager->baseUrl . '/index.php/user/get-client-campaigns' ."',
            data: {client_id : client_id}
        }).done(function(data){
            var optionList = '';
            if(data){
                $('#tblaskmefile-process').html('');
                data = JSON.parse(data);
                $.each(data, function(key, value){
                    optionList += '<option value=\"'+key+'\">'+ value +'</option>';
                });
            }
            $('#tblaskmefile-process').html(optionList);
        });
    });");    
?>