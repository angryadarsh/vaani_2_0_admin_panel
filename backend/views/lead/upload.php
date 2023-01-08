<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniLeadDump */

$this->title = 'Upload Leads';

$urlManager = Yii::$app->urlManager;
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('<i class="fas fa-chevron-left"></i>', ['index'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
            </div>
        </div>
       
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'onsubmit' => "
                        if(!$('.help-block').text()) {
                            $('#LoadingBox').show();
                        } "], 'enableAjaxValidation' => true, 'validateOnChange' => false]); ?>
                            
                            <div class="row form-box">
                                <div class="col-lg-4">
                                    <label class ='form-label'>Campaign</label>
                                    <?= $form->field($model, 'campaign_id', ['template' => '{input}{label}{error}{hint}'])->widget(
                                        Select2::classname(), [
                                            'name' => 'VaaniLeadBatch[campaign_id]',
                                            'data' => $campaigns,
                                            'options' => [
                                                'prompt' => '',
                                                'required' => true
                                            ],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                                'required' => true
                                            ],
                                        ])->label(false) ?>
                                </div>
                                <div class="col-lg-4">
                                <label class ='form-label'>Batch Name</label>
                                    <?= $form->field($model, 'batch_name', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Batch Name', 'required' => true])->label(false) ?>
                                </div>
                                <div class="col-lg-3 mt-auto">
                                    

                                    <label class ='form-label'>Upload File</label>
                                    <div class="field-uploadFile">
                                    <?php echo $form->field($model, 'upload_lead_file', ['template' => '{input}{label}{error}{hint}'])->fileInput(['class' => 'field_upload upload_file_input field_upload', 'required' => true])->label('<span class="lead_label"><i class="fa fa-upload btn btn-default upload-label preview"> Upload Lead File </i></span> <span class="lead_name"></span>')
                                     ?>
                                        <?php //echo $form->field($model, 'upload_lead_file')->fileInput(['class' => 'upload_file_input field_upload'])->label('<span class="logo_label btn btn-default"><i class="fa fa-upload"></i> Upload</span> <span class="logo_name"></span>')?>
                                        <!-- <span id="choosefile" class="text-preview">Choose file to upload</span> -->
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <?= $form->field($model, 'is_previously_mapped')->hiddenInput()->label(false) ?>
                                </div>
                            </div>
                            
                            <!-- mapping data -->
                            <div class="row form-box">
                                <div class="col-lg-12 mapping_section">
                                </div>
                            </div>

                            <div class="row justify-content-center align-items-center form-box">
                                <div class="col-lg-12 form-group text-center">
                                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary form_loader crmField']) ?>
                                    <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
                                </div>
                            </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$this->registerCss("
    .preview{
        display:flex;
    }
");
$this->registerJs("
    $('.upload-label').css('cursor', 'not-allowed');
    $('.field_upload').attr('disabled', true);

    $('#vaanileadbatch-campaign_id').on('change', function(e){
        var campaign_id = $(this).val();
        if(campaign_id){
            $('.upload-label').css('cursor', 'pointer');
            $('.field_upload').attr('disabled', false);

            $.ajax({
                method: 'POST',
                url: '".$urlManager->baseUrl . '/index.php/lead/get-lead-mapping'."',
                data: {campaign_id : campaign_id}
            }).done(function(result){
                if(result){
                    $('.mapping_section').html(result);
                    window.setTimeout(function(){
                        if(confirm('Do you want to use this previously mapped data?')){
                            $('#vaanileadbatch-is_previously_mapped').val(1);
                        }else{
                            $('#vaanileadbatch-is_previously_mapped').val(0);
                            $('.mapping_section').html('');
                        }
                    }, 600);

                    
                    // set is_primary disable restriction
                    $('.is_primary_checkbox').each(function(){
                        var is_primary = $(this).prop('checked');
                        
                        if(is_primary == true){
                            $(this).attr('disabled', false);
                            $(this).parent().parent().next().children().children().attr('disabled', true);
                        }else{
                            $(this).attr('disabled', true);
                            $(this).parent().parent().next().children().children().removeAttr('disabled');
                        }
                        

                    });

                    // set is_callable sequence disable restriction
                    $('.is_callable_checkbox').each(function(){
                        var is_callable = $(this).prop('checked');
                        var is_primary = $(this).prop('checked');
                        console.log(is_callable);
                        $(this).parent().parent().next().children().children().attr('disabled', true);
                        
                        if(is_callable == true){
                            if(is_primary == true){
                                $(this).parent().parent().next().children().children().attr('disabled', true);
                            }else{
                                $(this).parent().parent().next().children().children().attr('disabled', false);
                            }
                        }
                    });
                }
            });
        }else{
            $('.upload-label').css('cursor', 'not-allowed');
            $('.field_upload').attr('disabled', true);
            $('.mapping_section').html('');
        }
    });

    $('#vaanileadbatch-upload_lead_file').on('change', function(e){
        var lead_file = $(this).val();
        var file = e.target.files[0];
        var fd = new FormData();
        var data = fd.append('lead_file', file);

        var filename = e.target.files[0].name;
        $('.lead_name').html(`<span class='logo-text text-muted ml-20 pl-10'>` + filename + `</span>`);
        // $('.lead_label .btn-default').hide();

        // fetch is previous flag
        var is_previous = $('#vaanileadbatch-is_previously_mapped').val();
        data = fd.append('is_previous', is_previous);
        
        // fetch campaign id
        var campaign_id = $('#vaanileadbatch-campaign_id').val();
        data = fd.append('campaign_id', campaign_id);

        $.ajax({
            method: 'POST',
            dataType: 'html',
            url: '".$urlManager->baseUrl . '/index.php/lead/get-lead-columns'."',
            contentType: false,
            processData: false,
            data: fd,
        }).done(function(result){
            if(result){
                $('.mapping_section').html(result);

                // set is_callable sequence disable restriction
                $('.is_callable_checkbox').each(function(){
                    var is_callable = $(this).prop('checked');
                    console.log(is_callable);
                    if(is_callable == true){
                        $(this).parent().parent().next().children().children().attr('disabled', true);
                    }else{
                        $(this).parent().parent().next().children().children().attr('disabled', true);
                    }
                });
            }
        });
    });
    
    
    $(document).on('change', '.is_primary_checkbox', function(){
        var is_primary = $(this).prop('checked');
        let x = 1;
         
        if(is_primary == true){
            $('.is_primary_checkbox').attr('disabled', true);
            $(this).attr('disabled', false);

           // set callable checked
           $(this).parent().parent().next().children().children().attr('disabled', true);
            $(this).parent().parent().next().children().children().prop('checked', $(this).prop('checked'));
            
            // set secondary sequence 1 for primary field
            $(this).parent().parent().next().next().children().children().attr('disabled', true);
            $(this).parent().parent().next().next().children().children().val(x);
            counter = 1;
            
        }else{
            $('.is_primary_checkbox').attr('disabled', false);
            $(this).parent().parent().next().children().children().prop('checked', true);
            $(this).parent().parent().next().next().children().children().val(1);
            $(this).parent().parent().next().next().children().children().attr('disabled', false);
            $(this).parent().parent().next().children().children().attr('disabled', false);
        }
        
    });
   
    $(document).on('change', '.is_callable_checkbox', function(){
        var is_callable = $(this).prop('checked');
        var sequence_list = null;
        if(is_callable == true){
	        sequence_list = [...$('.secondary_sequence')].map(input => input.value);
            var max = Math.max.apply(Math,sequence_list);
           
	        $(this).parent().parent().next().children().children().val(max+1);
            $(this).parent().parent().next().children().children().attr('disabled', false);
	    }else{
            $(this).parent().parent().next().children().children().val(0);
            $(this).parent().parent().next().children().children().attr('disabled', true);
	    }
    });

");
?>