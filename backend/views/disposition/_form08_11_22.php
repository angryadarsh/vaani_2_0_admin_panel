<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniDispostionList */
/* @var $form yii\widgets\ActiveForm */
$urlManager = Yii::$app->urlManager;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
} "], 'enableAjaxValidation' => true, 'validateOnChange' => false, 'id' => 'submitForm']); ?>
<div class="row justify-content-center align-items-center form-box">
    <div class="col-lg-3">
        <?= $form->field($planModel, 'name', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Plan Name'])->label('Plan Name', ['class' => 'form-label']) ?>
        <?= $form->field($planModel, 'plan_id')->hiddenInput()->label(false) ?>
    </div>
    <div class="col-lg-6 hidden">
        <?= $form->field($model, 'campaign_id', ['template' => '{label}{input}{error}{hint}'])->widget(
            Select2::classname(), [
                'data' => $campaigns,
                'options' => ['prompt' => 'Select.....'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true,
                ],
            ])->label('Campaign', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-6 hidden">
        <?= $form->field($model, 'queue_id', ['template' => '{label}{input}{error}{hint}'])->widget(
            Select2::classname(), [
                'data' => [],
                'options' => ['prompt' => 'Select.....'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true,
                ],
            ])->label('Queue', ['class' => 'form-label']) ?>
    </div>
</div>
<div class="row justify-content-center align-items-center form-box dispositions-section">

</div>
    

        <div class="col-lg-12 form-group text-center">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary submit-disposition']) ?>
            <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>


<?php
$this->registerJs("
    // add/remove disposition row
    $('.disposition-actions .remove-disposition').first().hide();

    $(document).on('click', '.add-sub-disposition', function(){
        var thisRow = $(this).parent().parent().parent().parent();
        // thisRow.addClass('hello');
        console.log(thisRow);
        var subdispositionRow = $('.sub-disposition-row' ).eq(0);
        // subdispositionRow;
        var cloneRow = subdispositionRow.clone();
        cloneRow.insertAfter( thisRow ).find( 'input' ).val( '' );
        cloneRow.removeClass('hidden');
        cloneRow.removeClass('demo-sub-row');
        cloneRow.find('.remove-disposition').show();
    });

    $(document).on('click', '.add-sub2-disposition', function(){
        var thisRow = $(this).parent().parent().parent().parent();
        console.log(thisRow.index());
        var subdispositionRow = $('.sub2-disposition-row' ).eq(0);
       
        var cloneRow = subdispositionRow.clone();
        cloneRow.insertAfter( thisRow ).find( 'input' ).val( '' );
        cloneRow.removeClass('hidden');
        cloneRow.removeClass('demo-sub2-row');
    });

    $(document).on('click', '.disposition-actions .add-disposition', function(){
        var thisRow = $(this).closest('.disposition-row' );
        var cloneRow = thisRow.clone();

        if(thisRow.next().hasClass('sub-disposition-row') && !thisRow.next().hasClass('demo-sub-row') && !thisRow.next().hasClass('demo-sub2-row')){
            
            cloneRow.insertAfter( thisRow.nextUntil('.disposition-row').not('.demo-sub-row, .demo-sub2-row').last() ).find( 'input' ).val( '' );
        }else{
            cloneRow.insertAfter( thisRow ).find( 'input' ).val( '' );
        }
        
        // thisRow.find('.disposition-actions .add-disposition').hide();
        cloneRow.find('.disposition-actions .remove-disposition').show();
        cloneRow.find('.row-break').show();
    });

    $(document).on('click', '.remove-disposition', function(){
        var thisRow = $(this).parent().parent().parent().parent();

        if(thisRow && confirm('Are you sure to delete the disposition - ' + thisRow.find('#vaanicampaigndisposition-disposition').val() + ' ?')){
            if(thisRow.prev().length && !thisRow.next().length){
                thisRow.prev().find('.add-disposition').show();
                thisRow.prev().find('.remove-disposition').show();
            }
            $('.remove-disposition').first().hide();

            // delete the disposition from table
            var id = thisRow.find('#vaanicampaigndisposition-disposition_id').val();
            if(id){
                $.ajax({
                    method: 'POST',
                    url: '". $urlManager->baseUrl . '/index.php/disposition/delete' ."',
                    data: {id : id}
                }).done(function(data){
                    if(data == 'success'){
                        thisRow.remove();
                        Swal.fire('disposition deleted successfully.', '', 'success');
                    }else{
                        Swal.fire(data, '', 'error');
                        return false;
                    }
                });
            }else{
                thisRow.remove();
            }
        }
    });

    // fetch plan dispositions
    function getdispositions(plan_id = null)
    {
        $.ajax({
            method: 'GET',
            url: '". $urlManager->baseUrl . '/index.php/disposition/get-dispositions' ."',//function write to controller
            data: {plan_id : plan_id}
        }).done(function(data){
            $('.dispositions-section').html(data);
            // $('.disposition-actions .add-disposition').hide();
            // $('.disposition-actions .add-disposition:last').show();
            $('.disposition-actions .remove-disposition:last').show();
            $('.disposition-actions .remove-disposition').first().hide();
        });
    }

    var plan_id = $('#vaanidispositionplan-plan_id').val();
    getdispositions(plan_id);
    
    // remove dummy sub disposition row on click of submit button
    $('.submit-disposition').on('click', function(){
        $('.demo-sub-row').remove();
        $('.demo-sub2-row').remove();
    });
");

$this->registerCss("
    .dispositions-section{
        // padding-top: 2%;
        margin-bottom: 2%;
    }

    .sub-icon {
        padding-left: 4%;
    }
    .sub-icon::after {
        display: block;
        content: ' ';
        height: 1px;
        width: 8%;
        background-color: #e3e3e3;
        position: absolute;
        top: 52%;
        left: 4%;
    }

    .sub-icon::before {
        display: block;
        content: ' ';
        width: 2px;
        height: 75%;
        position: absolute;
        left: 4%;
        background: #e3e3e3;
        margin-top: -4%;
    }
    // .sub-disposition-row .sub-icon::after {
    //     width: 12%;
    //     top: 54%;
    //     height: 2px;
    // }
    // .sub-disposition-row .sub-icon::before {
    //     height: 80%;
    //     margin-top: -5.5%;
    // }
    .sub2-disposition-row .sub-icon::after {
        width: 12%;
        top: 54%;
        height: 2px;
    }
    .sub2-disposition-row .sub-icon::before {
        height: 80%;
        margin-top: -5.5%;
    }

    .disposition-row hr{
        border: 1px solid #00000059;
        margin-bottom: 30px;
    }

    .add-disposition, .add-sub-disposition, .add-sub2-disposition, .remove-disposition {
        background-color : #0084FF; color:#FFF;
    }
");

// NOT IN USE JS // - Ravinder - 09-08-2022
/* $this->registerJs("
    // fetch campaign queues
    function getQueues(campaign_id = null)
    {
        $.ajax({
            method: 'POST',
            url: '". $urlManager->baseUrl . '/index.php/user/get-campaigns-queues' ."',
            data: {campaign_ids : campaign_id}
        }).done(function(data){
            var optionList = '<option value=\"\">Select...</option>';
            if(data){
                var data_count = 1;
                var selected_queue_id = null;

                $('#vaanidispositions-queue_id').html('');
                data = JSON.parse(data);
                
                $.each(data, function(key, value){
                    var selected = false;
                    if(data_count == 1){
                        selected = 'selected';
                        selected_queue_id = key;
                    }
                    optionList += '<option value=\"'+key+'\" '+ selected +'>'+ value +'</option>';
                    data_count++;
                });
            }
            $('#vaanidispositions-queue_id').html(optionList);

            var queue_id = $('#vaanidispositions-queue_id').val();
            getdispositions(campaign_id, queue_id);
        });
    }

    // on change of campaign fetch queues
    $(document).on('change', '#vaanidispositions-campaign_id', function(){
        var campaign_id = $(this).val();
        getQueues(campaign_id);
    });

    // campaign dispositions
    var campaign_id = $('#vaanidispositions-campaign_id').val();
    getQueues(campaign_id);

    // onchange of queue fetch existing dispositions
    $(document).on('change', '#vaanidispositions-queue_id', function(){
        var queue_id = $(this).val();
        var campaign_id = $('#vaanidispositions-campaign_id').val();
        getdispositions(campaign_id, queue_id);
    });
"); */