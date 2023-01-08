<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\VaaniQmsSheet;
use common\models\VaaniQmsParameter;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniQmsParameter */
/* @var $form yii\widgets\ActiveForm */

$urlManager = Yii::$app->urlManager;
?>

<?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
} "], 'enableAjaxValidation' => true, 'validateOnChange' => false]); ?>

<div class="row sheet-section"> 
    
    <div class="col-lg-1"></div>
    <div class="col-lg-5">
        <?= $form->field($model, 'qms_id', ['template' => '{label}{input}{error}{hint}'])->dropdownList($templates, ['prompt' => '---Select---', 'required' => true])->label('Select QMS Template', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-5">
        <?= $form->field($model, 'sheet_id', ['template' => '{label}{input}{error}{hint}'])->dropdownList($sheets, ['prompt' => '---Select---', 'required' => true])->label('Select Audit Sheet', ['class' => 'form-label']) ?>
        <span class="sheet-type"></span>
    </div>
    <div class="col-lg-1"></div>
    
    <div class="col-lg-1"></div>
    <div class="col-lg-5">
        <?= $form->field($model, 'type', ['template' => '{label}{input}{error}{hint}'])->dropdownList(VaaniQmsParameter::$types, ['prompt' => '---Select---', 'required' => true])->label('Select Type', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-5 name_field">
        <?= $form->field($model, 'name', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Add Parameter', 'required' => true])->label('Add Parameter', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-5 parent_field hidden">
        <?= $form->field($model, 'parent_id', ['template' => '{label}{input}{error}{hint}'])->dropdownList([], ['prompt' => '---Select---'])->label('Select Parameter', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-1"></div>

    <div class="col-lg-1"></div>
    <div class="col-lg-5 sub-name-section">
        <?= $form->field($model, 'sub_name', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Add SubParameter', 'required' => true])->label('Add SubParameter', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-5 sub-type-section">
        <?= $form->field($model, 'sub_type', ['template' => '{label}{input}{error}{hint}'])->dropdownList(VaaniQmsParameter::$sub_types, ['prompt' => '---Select---', 'required' => true])->label('Sub-Parameter Type', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-1"></div>

    <div class="col-lg-1"></div>
    <div class="col-lg-5 score-section">
        <?= $form->field($model, 'score', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Add Score', 'required' => true])->label('Score', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-5 remarks-section">
        <?= $form->field($model, 'remarks', ['template' => '{label}{input}{error}{hint}'])->textarea(['row' => '2', 'required' => true])->label('Remarks', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-1"></div>
    
    <div class="col-lg-12 form-group text-center">
        <?= Html::submitButton('Add', ['class' => 'btn btn-primary submit-btn']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<!-- list of previous parameters of the selected sheet -->
<div class="row">
    <div class="col-lg-1"></div>
    <div class="col-lg-10 parameter_list table-responsive"></div>
    <div class="col-lg-1"></div>
</div>
                        
<?php
$this->registerCss("
	.dataTables_length{
		margin-left : 2%
	}
    tr {
        cursor : auto !important;
    }
");
// SHOW FIELDS BASED ON PARAMETER TYPE
$this->registerJs("
    function nameVisible(type)
    {
        // if type is parameter
        if(type == 1){
            // PARENT ID - HIDE
            $('.parent_field').hide();
            $('#vaaniqmsparameter-parent_id').val('');
            $('#vaaniqmsparameter-parent_id').attr('required', false);
            
            // SUB NAME - HIDE
            $('.field-vaaniqmsparameter-sub_name').hide();
            $('#vaaniqmsparameter-sub_name').val('');
            $('#vaaniqmsparameter-sub_name').attr('required', false);
            
            // SUB TYPE - HIDE
            $('.field-vaaniqmsparameter-sub_type').hide();
            $('#vaaniqmsparameter-sub_type').val('');
            $('#vaaniqmsparameter-sub_type').attr('required', false);
            
            // SCORE - HIDE
            $('.score-section').hide();
            $('#vaaniqmsparameter-score').val('');
            $('#vaaniqmsparameter-score').attr('required', false);
            
            // REMARKS - HIDE
            $('.remarks-section').hide();
            $('#vaaniqmsparameter-remarks').val('');
            $('#vaaniqmsparameter-remarks').attr('required', false);
            
            // NAME - SHOW
            $('.name_field').show();
            $('.field-vaaniqmsparameter-name').show();
            $('#vaaniqmsparameter-name').attr('required', true);
        }else if (type == 2) {
            // NAME - HIDE
            $('.name_field').hide();
            $('.field-vaaniqmsparameter-name').hide();
            $('#vaaniqmsparameter-name').val('');
            $('#vaaniqmsparameter-name').attr('required', false);
            
            // PARENT ID - SHOW
            $('.parent_field').show();
            $('#vaaniqmsparameter-parent_id').attr('required', true);
            
            // SUB NAME - SHOW
            $('.field-vaaniqmsparameter-sub_name').show();
            $('#vaaniqmsparameter-sub_name').attr('required', true);
            
            // SUB TYPE - SHOW
            $('.field-vaaniqmsparameter-sub_type').show();
            $('#vaaniqmsparameter-sub_type').attr('required', true);
            
            // SCORE - SHOW
            // console.log($('.sheet-type').attr('value'));
            if($('.sheet-type').attr('value') == 1){
                $('#vaaniqmsparameter-score').val('');
                $('.score-section').show();
                $('#vaaniqmsparameter-score').attr('required', true);
            }else{
                // SCORE - HIDE
                $('.score-section').hide();
                $('#vaaniqmsparameter-score').val('Yes,No');
                $('#vaaniqmsparameter-score').attr('required', false);
            }
            
            // REMARK - SHOW
            $('.remarks-section').show();
            $('#vaaniqmsparameter-remarks').attr('required', true);
        }
    }

    nameVisible($('#vaaniqmsparameter-type').val());

    $('#vaaniqmsparameter-type').on('change', function(){
        nameVisible($(this).val());
    });
");

// ONCHANGE OF TEMPLATE FETCH SHEETS
$this->registerJs("
    function fetchSheets(qms_id)
    {
        $.ajax({
            method: 'POST',
            url: '". $urlManager->baseUrl . '/index.php/qms/get-sheets' ."',
            data: {qms_id : qms_id}
        }).done(function(data){
            var sheet_id = '".$model->sheet_id."';
            var optionList = '<option value=\"\">---Select---</option>';
            
            $('#vaaniqmsparameter-sheet_id').html('');
            data = JSON.parse(data);
            $.each(data, function(key, value){
                sel = '';
                if(sheet_id == key){
                    sel = 'selected';
                    
                    $.ajax({
                        method: 'GET',
                        url: '". $urlManager->baseUrl . '/index.php/qms/sheet-details' ."',
                        data: {sheet_id : key}
                    }).done(function(data){
                        data = JSON.parse(data);
                        $('.sheet-type').attr('value', data.type);
                        nameVisible($('#vaaniqmsparameter-type').val());
                    });

                    fetchParameters(qms_id, key);
                    fetchSheetParameters(qms_id, key);
                }
                optionList += '<option value=\"'+key+'\" '+ sel +'>'+ value +'</option>';
            });
                
            $('#vaaniqmsparameter-sheet_id').html(optionList);
        });
    }
    
    $('#vaaniqmsparameter-qms_id').on('change', function(){
        var qms_id = $(this).val();

        fetchSheets(qms_id);
    });

    if($('#vaaniqmsparameter-qms_id').val()){
        fetchSheets($('#vaaniqmsparameter-qms_id').val());
    }
");
// validation of score field 
$this->registerJs("
    function checkScore(score)
    {   
        var sub_param_type = $('#vaaniqmsparameter-sub_type').val();
        console.log(sub_param_type);
        console.log(score.includes('fatal'));
        if(sub_param_type == 1 ){
            if((score.includes('fatal')) || (score.includes('FATAL')) || (score.includes('Fatal'))){
                
            }else{
                Swal.fire('Please Add Fatal As a Option In Score Field As You Select Sub Parameter Type Fatal', '', 'error');
                return false;
            }
        }else{
            var array = score.split(',');
            $.each(array,function(i){
                // alert(array[i]);
                var check = $.isNumeric(array[i]);  
                if(check){
                    if(array[i] > 100){
                        alert('Numbers Must Be smaller than 100');
                        // $('.submit-btn').attr('disabled', true);
                        return false;
                    }else if(array[i] < 0){
                        alert(' Negative Numbers Are Not Allowed');
                        // $('.submit-btn').attr('disabled', true);
                        return false;
                    }else{
                        $('.submit-btn').attr('disabled', false);
                    }
                }else{
                    var restriction = array[i];
                    if(restriction == ' '){
                        alert('No Space or special characters are allowed!');
                        // $('.submit-btn').attr('disabled', true);
                        return false;
                    }

                    restriction = restriction.toLowerCase();
                    // console.log(restriction);
                    if(restriction != 'fatal' && restriction != 'na'){
                        alert('Only Fatal or NA Words are allowed ');
                        // $('.submit-btn').attr('disabled', true);
                        return false;
                    }else{
                        $('.submit-btn').attr('disabled', false);
                    }
                }
            });
        }
    }
   
    // restrict space in score
    $('#vaaniqmsparameter-score').keypress(function (e){
        if(e.which === 32){
            return false;
        }
    });

    $('#vaaniqmsparameter-score').change(function (){
        var score = $('#vaaniqmsparameter-score').val();
        checkScore(score);
    });
  
    $('.submit-btn').on('submit', function(){
        var score = $('#vaaniqmsparameter-score').val();
        checkScore(score);
    });
");

// ONCHANGE OF SHEETS FETCH PARAMETERS
$this->registerJs("
    function fetchParameters(qms_id, sheet_id)
    {
        $.ajax({
            method: 'POST',
            url: '". $urlManager->baseUrl . '/index.php/qms/get-parameters' ."',
            data: {qms_id : qms_id, sheet_id : sheet_id}
        }).done(function(data){
            var optionList = '<option value=\"\">---Select---</option>';
            
            $('#vaaniqmsparameter-parent_id').html('');
            data = JSON.parse(data);
            $.each(data, function(key, value){
                optionList += '<option value=\"'+key+'\">'+ value +'</option>';
            });
                
            $('#vaaniqmsparameter-parent_id').html(optionList);
        });
    }

    // fetch list of parameters & sub-parameters of the selected sheet
    function fetchSheetParameters(qms_id, sheet_id)
    {
        $.ajax({
            method: 'GET',
            url: '". $urlManager->baseUrl . '/index.php/qms/get-sheet-parameters' ."',
            data: {qms_id : qms_id, sheet_id : sheet_id}
        }).done(function(data){
            $('.parameter_list').html('');
            var table = '';

            if(data){
                table = '<table id=\"parameters_table\" class=\"table table-striped table-bordered\">';
                table += '<thead><tr><th>Sr.No.</th><th>Parameters</th><th>SubParameters</th><th>SubParameter Type</th><th>Scores</th><th>Remarks</th></tr></thead>';
                table += '<tbody>';

                data = JSON.parse(data);
                var list_count = 1;
                $.each(data, function(key, value){
                    if(value){
                        table += '<tr><td>'+list_count+'</td><td>'+value.parameter+'</td><td>'+value.sub_name+'</td><td>'+value.sub_type+'</td><td>'+value.score+'</td><td>'+value.remarks+'</td></tr>';
                        list_count++;
                    }
                });
                table += '</tbody>';
            }
                
            $('.parameter_list').html(table);
        });
    }
    
    $('#vaaniqmsparameter-sheet_id').on('change', function(){
        var sheet_id = $(this).val();
        var qms_id = $('#vaaniqmsparameter-qms_id').val();

        $.ajax({
            method: 'GET',
            url: '". $urlManager->baseUrl . '/index.php/qms/sheet-details' ."',
            data: {sheet_id : sheet_id}
        }).done(function(data){
            data = JSON.parse(data);
            $('.sheet-type').attr('value', data.type);
            nameVisible($('#vaaniqmsparameter-type').val());
        });

        fetchParameters(qms_id, sheet_id);
        fetchSheetParameters(qms_id, sheet_id);
    });

    if($('#vaaniqmsparameter-sheet_id').val()){
        var sheet_id = $('#vaaniqmsparameter-sheet_id').val();
        var qms_id = $('#vaaniqmsparameter-qms_id').val();
        
        $.ajax({
            method: 'GET',
            url: '". $urlManager->baseUrl . '/index.php/qms/sheet-details' ."',
            data: {sheet_id : sheet_id}
        }).done(function(data){
            data = JSON.parse(data);
            $('.sheet-type').attr('value', data.type);
            nameVisible($('#vaaniqmsparameter-type').val());
        });

        fetchParameters(qms_id, sheet_id);
        fetchSheetParameters(qms_id, sheet_id);
    }
");

?>