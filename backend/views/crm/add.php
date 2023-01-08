<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\VaaniCrm;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniCrm */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'CRM Configuration';

$urlManager = Yii::$app->urlManager;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
} "], 'enableAjaxValidation' => true, 'validateOnChange' => false, 'id' => 'submitForm']); ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('<i class="fas fa-chevron-left"></i>', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
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
                        <div class="row form-box">
                            <div class="col-lg-6">
                                <label class = 'form-label'>Campaign</label>
                                <?= $form->field($model, 'campaign_id', ['template' => '{input}{label}{error}{hint}'])->widget(
                                    Select2::classname(), [
                                        'data' => isset($clone_id) ? $campaign_list_clone : $campaigns,
                                        'options' => ['prompt' => '', 'required' => true],
                                        'pluginOptions' => [
                                            'tags' => true,
                                            'allowClear' => true,'required' => true
                                        ],
                                    ])->label(false) ?>
                            </div>
                            <div class="col-lg-6">
                            <label class = 'form-label'>Queue</label>
                                <?= $form->field($model, 'queue_id', ['template' => '{input}{label}{error}{hint}'])->widget(
                                    Select2::classname(), [
                                        'data' => [],
                                        'options' => ['prompt' => '', 'required' => true],
                                        'pluginOptions' => [
                                            'tags' => true,
                                            'allowClear' => true,
                                        ],
                                    ])->label(false) ?>
                            </div>
                            <?= $form->field($model, 'clone_id', ['template' => '{input}{label}{error}{hint}'])->hiddenInput(['value' => $clone_id])->label(false)?>
                        </div>

                        <div class="fields-section">
                        </div>

                        <div class="row justify-content-center align-items-center form-box">
                            <div class="col-lg-12 form-group text-center">
                                <?= Html::submitButton('Save', ['class' => 'btn btn-primary form_loader']) ?>
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
// fetch queues & frm details, if any, on change/select of campaign
$this->registerJs("
    function fetchData(campaign_id)
    {
        if(campaign_id){
            $.ajax({
                method: 'POST',
                url: '". $urlManager->baseUrl . '/index.php/user/get-campaigns-queues' ."',
                data: {campaign_ids : campaign_id}
            }).done(function(data){
                var optionList = '<option value=\"\">Queues...</option>';
                if(data){
                    var data_count = 1;
                    var selected_queue_id = null;

                    $('#vaanicrm-queue_id').html('');
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
                $('#vaanicrm-queue_id').html(optionList);

                // fetch crm fields data
                $.ajax({
                    method: 'POST',
                    url: '". $urlManager->baseUrl . '/index.php/crm/get-crm-fields' ."',
                    data: {campaign_id : campaign_id, queue_id : selected_queue_id, clone_id : clone_id}
                }).done(function(data){
                    if(data){
                        $('.fields-section').html(data);
                        // $('.add-field').hide();
                        // $('.add-field:last').show();
                        // $('.remove-field:last').show();
                        // $('.remove-field').first().hide();
                    }
                });
            });
        }else{
            $('#vaanicrm-queue_id').html('');
        }
    }

    function fetchDataClone(clone_id)
    {
        if(clone_id)
        {
            // fetch crm fields data
            $.ajax({
                method: 'POST',
                url: '". $urlManager->baseUrl . '/index.php/crm/get-crm-fields' ."',
                data: {clone_id : clone_id}
            }).done(function(data){
                if(data){
                    $('.fields-section').html(data);
                    
                  
                }
            }); 
        }
    }

    var campaign_id = $('#vaanicrm-campaign_id').val();
    var clone_id = $('#vaanicrm-clone_id').val();

    if(clone_id){ fetchDataClone(clone_id);}
    if(campaign_id) fetchData(campaign_id);
    
    $('#vaanicrm-campaign_id').on('change', function(){
        var campaign_id = $(this).val();
        fetchData(campaign_id);
    });

    // remove visibility property for required attribute
    $('#vaanicrm-campaign_id').css('visibility', 'unset');
    $('#vaanicrm-queue_id').css('visibility', 'unset');
");

// add/remove field row
$this->registerJs("
    // add/remove field row
    $('.remove-field').first().hide();
    
    $(document).on('click', '.add-field', function(){
        var thisRow = $(this).closest('.fields-list' );
        var cloneRow = thisRow.clone();
        cloneRow.insertAfter( thisRow ).find( 'input' ).val( '' );
        // thisRow.find('.add-field').hide();
        cloneRow.find('.remove-field').show();
    });
    $(document).on('click', '.remove-field', function(){
        var thisRow = $(this).closest('.fields-list' );

        if(thisRow && confirm('Are you sure to delete the field?')){
            if(thisRow.prev().length && !thisRow.next().length){
                thisRow.prev().find('.add-field').show();
                thisRow.prev().find('.remove-field').show();
            }
            // $('.remove-field').first().hide();

            // delete the break from table
            var id = thisRow.find('#vaanicrm-field_ids').val();
            if(id){
                $.ajax({
                    method: 'POST',
                    url: '". $urlManager->baseUrl . '/index.php/crm/delete-field' ."',
                    data: {id : id}
                }).done(function(data){
                    if(data == 'success'){
                        thisRow.remove();
                        Swal.fire('Field deleted successfully.', '', 'success');
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
");
?>