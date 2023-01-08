<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniKpTab */
/* @var $form yii\widgets\ActiveForm */

// $id = trim($_GET['templete_id']);
$id = User::decrypt_data($templete_id);

// echo "<pre>"; print_r($id);exit;
//echo"<pre>"; echo $id; exit;
?>
<div class="row justify-content-center form-containter-box">
    <div class="col-4">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'autocomplete' => 'off'], 'enableAjaxValidation' => true, 'validateOnChange' => false]); ?>
        
        <?php //echo $form->field($model, 'id')->textInput()?>
        
        <?php echo $form->field($model, 'templete_id')->hiddenInput(['value' => isset($id) ? $id: null])->label(false) ?>

        <div class='form-group'>
            <div class="row">
                <label class="form-label col-sm-4">Tab Name</label>
                <div class="col-sm-8">
                    <?php echo $form->field($model, 'tab_name')->textInput(['maxlength' => true,'autocomplete' => 'off'])->label(false) ?>
                </div>
            </div>
        </div>
        <div class='form-group'>
            <div class="row">
                <label class="form-label col-sm-4">Upload File</label>
                <div class="col-sm-8">
                    <?php echo $form->field($model, 'file')->fileInput(['class' => 'upload_file_input','id'=> 'uploadFile'])->label('<span class="logo_label btn btn-default"><i class="fa fa-upload"></i> Upload</span> <span class="logo_name"></span>') ?>
                    <span id="choosefile" class="text-preview">Choose file to upload</span>
                    <?php echo $form->field($model, 'tempfile')->hiddenInput(['value' => isset($file['file']) ? $file['file'] : null ,'id' => 'previousFile'])->label(false) ?>
                    <span class="error-message" id="uploadFileError"></span>
                </div>
            </div>
        </div>
    
            
            <div class='form-group'>
                <div class="row">
                    <label class="form-label col-sm-4">Mandatory Info</label>
                    <div class="col-sm-8">
                        <?= $form->field($model, 'mandatory_info')->textInput(['maxlength' => true,'autocomplete' => 'off'])->label(false) ?>
                    </div>
                </div>
            </div>
            
            <div class='form-group'>
                <div class="row">
                    <label class="form-label col-sm-4">Additional Info</label>
                    <div class="col-sm-8">
                        <?= $form->field($model, 'additional_info')->textInput(['maxlength' => true,'autocomplete' => 'off'])->label(false) ?>
                    </div>
                </div>
            </div>
            
            <div class="form-group text-center"> 
                <?= Html::submitButton('Save', ['class' => 'btn btn-outline-primary']) ?>
            </div>
    
    <?php ActiveForm::end(); ?>
    
</div>
</div>
</div>

<?php
$this->registerCss("
.upload_file_input{float:left;}
.form-containter-box .col-sm-8 .form-group{margin-bottom:0;}
.form-containter-box .col-sm-8 .form-group.field-uploadFile{float: left; margin-left:10px;}
");
?>
<?php 
$this->registerJs("
$('.field-uploadFile').change(function(e){
    var file = e.target.files[0].name;
    temp_file = file;
    var privousFile = $('#previousFile').val();

    console.log(file);

    if(file){
        $('#choosefile').text(file);
    }
    else{
        $('#choosefile').text(privousFile);
    }
});

$(document).ready( function () {

    var privousFile = $('#previousFile').val();
    if(privousFile){
        $('#choosefile').text(privousFile);
    }

});

$('.field-uploadFile').change(function(e){

    $('.help-block').addClass('errormessage');
    $('.errormessage').css('width','20rem');
});

")

?>
