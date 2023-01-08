<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use fedemotta\datatables\DataTables;
use common\models\VaaniCampaignQueue;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniCampaignQueue */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'IVR - Campaign: ' . ucwords($campaign->campaign_name);
$urlManager = Yii::$app->urlManager;
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <div class="float-left">
                <h1 class="m-0"><?= $this->title ?></h1>
            </div>
            <div class="float-right">
                <?= Html::a('Back', ['update', 'id' => $campaign->id], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <?php $form = ActiveForm::begin(['action' => ['ivr', 'id' => $campaign->id], 'method' => 'post', 'options' => ['autocomplete' => 'off']]); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <?= $form->field($campaign, 'is_dtmf', ['template' => '{input}{label}{error}{hint}'])->checkbox(['label' => 'Is DTMF? ', 'id' => 'edit-is_dtmf', 'uncheck' => 2])->label(false) ?>
                            </div>
                            <div class="col-md-3 check_ivr_queue">
                                <?= $form->field($campaign, 'is_ivr_queue', ['template' => '{input}{label}{error}{hint}'])->checkbox(['label' => 'Is Queue Mapping? ', 'id' => 'edit-is_ivr_queue', 'uncheck' => 2])->label(false) ?>
                            </div>
                        </div>
                        <!-- non queue section -->
                        <div class="row non-queue-section">
                            <div class="col-md-3">
                                <?= $form->field($campaign, 'key_input', ['template' => '{input}{label}{error}{hint}<span class="disabled">e.g.  1~2~3~4~5~6</span>'])->textInput(['placeholder' => 'Key Input ', 'id' => 'key_input'])->label('Key Input', ['class' => 'form-label']) ?>
                            </div>
                        </div>
                        <!-- queue section -->
                        <div class="row queue-section">
                            <?php if($queues){ 
                                foreach($queues as $key => $queueModel){ ?>
                                    <?= $form->field($model, 'id[]', ['template' => '{input}{label}{error}{hint}'])->hiddenInput(['id' => 'queue-queue_id', 'value' => $queueModel->id])->label(false) ?>
                                    <div class="col-md-2 pr-0">
                                        <?= $form->field($model, 'key_input[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Key Input ', 'id' => 'queue-key_input', 'value' => $queueModel->key_input])->label('Key Input', ['class' => 'form-label']) ?>
                                    </div>
                                    <div class="col-md-3 pr-0">
                                        <?= $form->field($model, 'queue_name[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Queue Name ', 'id' => 'queue-queue_name', 'value' => $queueModel->queue_name])->label('Queue Name', ['class' => 'form-label']) ?>
                                    </div>
                                    <div class="col-md-2 pr-0">
                                        <?= $form->field($model, 'criteria[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($criterias, ['prompt' => 'Select...', 'id' => 'queue-criteria', 'value' => $queueModel->criteria])->label('Criteria', ['class' => 'form-label']) ?>
                                    </div>
                                    <div class="col-md-1 pr-0">
                                        <?= $form->field($model, 'is_conference[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_conference', 'value' => $queueModel->is_conference])->label('Conference?', ['class' => 'form-label']) ?>
                                    </div>
                                    <div class="col-md-1 pr-0">
                                        <?= $form->field($model, 'is_transfer[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_transfer', 'value' => $queueModel->is_transfer])->label('Transfer?', ['class' => 'form-label']) ?>
                                    </div>
                                    <div class="col-md-1 pr-0">
                                        <?= $form->field($model, 'is_consult[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_consult', 'value' => $queueModel->is_consult])->label('Consult?', ['class' => 'form-label']) ?>
                                    </div>
                                    <div class="col-md-1 pr-0">
                                        <?= $form->field($model, 'is_manual[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_manual', 'value' => $queueModel->is_manual])->label('Manual?', ['class' => 'form-label']) ?>
                                    </div>
                                    <div class="col-md-1 pr-0">
                                        <?= Html::a('+', null, ['class' => 'btn btn-success add-queue']) ?>
                                        <?= Html::a('-', null, ['class' => 'btn btn-danger remove-queue']) ?>
                                    </div>
                                <?php }
                            }else{ ?>
                                <?= $form->field($model, 'id[]', ['template' => '{input}{label}{error}{hint}'])->hiddenInput(['id' => 'queue-queue_id'])->label(false) ?>
                                <div class="col-md-2 pr-0">
                                    <?= $form->field($model, 'key_input[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Key Input ', 'id' => 'queue-key_input'])->label('Key Input', ['class' => 'form-label']) ?>
                                </div>
                                <div class="col-md-3 pr-0">
                                    <?= $form->field($model, 'queue_name[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Queue Name ', 'id' => 'queue-queue_name'])->label('Queue Name', ['class' => 'form-label']) ?>
                                </div>
                                <div class="col-md-2 pr-0">
                                    <?= $form->field($model, 'criteria[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($criterias, ['prompt' => 'Select...', 'id' => 'queue-criteria'])->label('Criteria', ['class' => 'form-label']) ?>
                                </div>
                                <div class="col-md-1 pr-0">
                                    <?= $form->field($model, 'is_conference[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_conference'])->label('Conference?', ['class' => 'form-label']) ?>
                                </div>
                                <div class="col-md-1 pr-0">
                                    <?= $form->field($model, 'is_transfer[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_transfer'])->label('Transfer?', ['class' => 'form-label']) ?>
                                </div>
                                <div class="col-md-1 pr-0">
                                    <?= $form->field($model, 'is_consult[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_consult'])->label('Consult?', ['class' => 'form-label']) ?>
                                </div>
                                <div class="col-md-1 pr-0">
                                    <?= $form->field($model, 'is_manual[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_manual'])->label('Manual?', ['class' => 'form-label']) ?>
                                </div>
                                <div class="col-md-1 pr-0">
                                    <?= Html::a('+', null, ['class' => 'btn btn-success add-queue']) ?>
                                    <?= Html::a('-', null, ['class' => 'btn btn-danger remove-queue']) ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 form-group text-center">
                                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'id' => 'ivr-submit']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$this->registerJs("
    if($('#edit-is_dtmf').is(':checked')){
        $('.check_ivr_queue').show();
        
        // is queue checked
        if($('#edit-is_ivr_queue').is(':checked')){
            $('.non-queue-section').hide();
            $('.non-queue-section').find( 'input:text' ).attr('required', false);

            $('.queue-section').show();
            $('.queue-section').find( 'input:text' ).attr('required', true);
            $( '#queue-criteria' ).attr('required', true);
        }else{
            $('.queue-section').hide();
            $('.queue-section').find( 'input:text' ).attr('required', false);
            $( '#queue-criteria' ).attr('required', false);

            $('.non-queue-section').show();
            $('.non-queue-section').find( 'input:text' ).attr('required', true);
        }
    }else{
        $('.check_ivr_queue').hide();
        $('.non-queue-section').hide();
        $('.queue-section').hide();
    }
    
    $('#edit-is_dtmf').on('change', function(){
        var is_dtmf = $(this).is(':checked');
        
        if(is_dtmf){
            $('.check_ivr_queue').show();
            $('#edit-is_ivr_queue').prop('checked', false);
            $('.non-queue-section').show();
            $('.non-queue-section').find( 'input:text' ).attr('required', true);
            
            $('.queue-section').find( 'input:text' ).val('');
            $( '#queue-criteria' ).val('');
            $('.queue-section').find( 'input:text' ).attr('required', false);
            $( '#queue-criteria' ).attr('required', false);
        }else{
            $('.non-queue-section').hide();
            $('.non-queue-section').find( 'input:text' ).val('');
            $('.non-queue-section').find( 'input:text' ).attr('required', false);

            $('.queue-section').hide();
            $('.queue-section').find( 'input:text' ).val('');
            $( '#queue-criteria' ).val('');
            $('.queue-section').find( 'input:text' ).attr('required', false);
            $( '#queue-criteria' ).attr('required', false);
            $('.check_ivr_queue').hide();
        }
    });
    
    $('#edit-is_ivr_queue').on('change', function(){
        var is_ivr_queue = $(this).is(':checked');
        
        if(is_ivr_queue){
            $('.non-queue-section').hide();
            $('.non-queue-section').find( 'input:text' ).val('');
            $('.non-queue-section').find( 'input:text' ).attr('required', false);

            $('.queue-section').show();
            $('.queue-section').find( 'input:text' ).attr('required', true);
            $( '#queue-criteria' ).attr('required', true);
        }else{
            $('.queue-section').hide();
            $('.queue-section').find( 'input:text' ).val('');
            $( '#queue-criteria' ).val('');
            $('.queue-section').find( 'input:text' ).attr('required', false);
            $( '#queue-criteria' ).attr('required', false);

            $('.non-queue-section').show();
            $('.non-queue-section').find( 'input:text' ).attr('required', true);
        }

    });

    $('.remove-queue').first().hide();

    $(document).on('click', '.add-queue', function(){
        var thisRow = $(this).closest('.queue-section' );
        var cloneRow = thisRow.clone();
        cloneRow.insertAfter( thisRow ).find( 'input:text' ).val( '' );
        cloneRow.find('#queue-criteria').val('');
        cloneRow.find('.remove-queue').show();
    });
    $(document).on('click', '.remove-queue', function(){
        var thisRow = $(this).closest('.queue-section' );
        thisRow.remove();
    });
");
?>