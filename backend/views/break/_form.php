<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniCampaignBreak */
/* @var $form yii\widgets\ActiveForm */

$urlManager = Yii::$app->urlManager;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
} "], 'enableAjaxValidation' => true, 'validateOnChange' => false, 'id' => 'submitForm']); ?>

<div class="row justify-content-center align-items-center form-box">
    <div class="col-lg-6">
        <?= $form->field($model, 'campaign_id', ['template' => '{label}{input}{error}{hint}'])->widget(
            Select2::classname(), [
                'data' => $campaigns,
                'options' => ['prompt' => ''],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true,
                ],
            ])->label('Campaign', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-2"></div>
</div>
<div class="row justify-content-center align-items-center form-box breaks-section">
    
</div>
<div class="row justify-content-center align-items-center form-box">
    <div class="col-lg-12 form-group text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary form_loader']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs("
    // add/remove break row
    $('.remove-break').first().hide();

    $(document).on('click', '.add-break', function(){
        var thisRow = $(this).closest('.break-row' );
        var cloneRow = thisRow.clone();
        cloneRow.insertAfter( thisRow ).find( 'input' ).val( '' );
        thisRow.find('.add-break').hide();
        cloneRow.find('.remove-break').show();
    });
    $(document).on('click', '.remove-break', function(){
        var thisRow = $(this).closest('.break-row' );

        if(thisRow && confirm('Are you sure to delete the break - ' + thisRow.find('#vaanicampaignbreak-break').val() + ' ?')){
            if(thisRow.prev().length && !thisRow.next().length){
                thisRow.prev().find('.add-break').show();
                thisRow.prev().find('.remove-break').show();
            }
            $('.remove-break').first().hide();

            // delete the break from table
            var id = thisRow.find('#vaanicampaignbreak-b_id').val();
            if(id){
                $.ajax({
                    method: 'POST',
                    url: '". $urlManager->baseUrl . '/index.php/break/delete' ."',
                    data: {id : id}
                }).done(function(data){
                    if(data == 'success'){
                        thisRow.remove();
                        Swal.fire('Break deleted successfully.', '', 'success');
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

    // fetch campaign breaks
    function getBreaks(campaign_id = null)
    {
        if(campaign_id){
            $.ajax({
                method: 'GET',
                url: '". $urlManager->baseUrl . '/index.php/break/get-breaks' ."',
                data: {campaign_id : campaign_id}
            }).done(function(data){
                if(data){
                    $('.breaks-section').html(data);
                    $('.add-break').hide();
                    $('.add-break:last').show();
                    $('.remove-break:last').show();
                    $('.remove-break').first().hide();
                }
            });
        }
    }

    // on change of campaign fetch existing breaks
    $(document).on('change', '#vaanicampaignbreak-campaign_id', function(){
        var campaign_id = $(this).val();
        if(campaign_id){
            getBreaks(campaign_id);
        }
    });
    
    // campaign breaks
    var campaign_id = $('#vaanicampaignbreak-campaign_id').val();
    if(campaign_id){
        getBreaks(campaign_id);
    }
");

$this->registerCss("
        .select2-container--krajee-bs4 .select2-selection{height:34px;}
");