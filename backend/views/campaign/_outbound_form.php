<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use common\models\EdasCampaign;
use kartik\time\TimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\EdasCampaign */
/* @var $form yii\widgets\ActiveForm */

$urlManager = Yii::$app->urlManager;
?>

<?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'onsubmit' => "$('#LoadingBox').show(); "], 'enableAjaxValidation' => true, 'validateOnChange' => false]); ?>

<div class="row">
    <!-- first section -->
    <div class="col-lg-6">
        <div class="row">
            <div class="col-lg-12">
                <?= $form->field($model, 'client_id', ['template' => '{input}{label}{error}{hint}'])->dropdownList($clients, ['prompt' => 'Select...', 'required' => true])->label(' Client Name', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-12">
                <?= $form->field($model, 'campaign_name', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Campaign Name', 'required' => true])->label('Campaign Name', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-12 campaign_mode">
                <?= $form->field($model, 'call_window', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_windows, ['prompt' => 'Select...', 'id' => 'selected_call_window'])->label('Call Window', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, 'mode', ['template' => '{input}{label}{error}{hint}'])->dropdownList($campaign_modes, ['prompt' => 'Select...'])->label('Campaign Mode', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($campaignAccessModel, 'is_conference', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_conference'])->label('Conference?', ['class' => 'form-label']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($campaignAccessModel, 'is_transfer', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_transfer'])->label('Transfer?', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($campaignAccessModel, 'is_consult', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_consult'])->label('Consult?', ['class' => 'form-label']) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <?= $form->field($model, 'campaign_inbound_timeover_url', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Campaign Url'])->label('Campaign Url', ['class' => 'form-label']) ?>
            </div>
        </div>
    </div>

    <!-- second section -->
    <div class="col-lg-6">
        <div class="row">
            <div class="col-lg-8">
                <label class="form-label">Campaign Sticky Agent</label>
                <?= $form->field($model, 'campaign_sticky_agent', ['template' => '<div class="toggle-checked"><label class="switch">{input}<div class="slider round"><span class="on">Enabled</span><span class="off">Disabled</span></div></label></div>{error}{hint}'])->checkbox(
                    [
                        'checked' => ($model->campaign_sticky_agent ? 'checked' : false),
                    ])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <label class="form-label">Campaign Status</label>
                <?= $form->field($model, 'campaign_status', ['template' => '<div class="toggle-checked"><label class="switch">{input}<div class="slider round"><span class="on">Enabled</span><span class="off">Disabled</span></div></label></div>{error}{hint}'])->checkbox(
                    [
                        'checked' => (($model->campaign_status == EdasCampaign::CAMPAIGN_ACTIVE) ? 'checked' : false),
                        'uncheck' => 2
                    ])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <label class="form-label">Campaign SMS</label>
                <?= $form->field($model, 'campaign_sms_mode', ['template' => '<div class="toggle-checked"><label class="switch">{input}<div class="slider round"><span class="on">Enabled</span><span class="off">Disabled</span></div></label></div>{error}{hint}'])->checkbox(
                    [
                        'checked' => ($model->campaign_sms_mode ? 'checked' : false),
                    ])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <label class="form-label">Campaign Email</label>
                <?= $form->field($model, 'campaign_email_mode', ['template' => '<div class="toggle-checked"><label class="switch">{input}<div class="slider round"><span class="on">Enabled</span><span class="off">Disabled</span></div></label></div>{error}{hint}'])->checkbox(
                    [
                        'checked' => ($model->campaign_email_mode ? 'checked' : false),
                    ])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <label class="form-label">Campaign Chat</label>
                <?= $form->field($model, 'campaign_chat_mode', ['template' => '<div class="toggle-checked"><label class="switch">{input}<div class="slider round"><span class="on">Enabled</span><span class="off">Disabled</span></div></label></div>{error}{hint}'])->checkbox(
                    [
                        'checked' => ($model->campaign_chat_mode ? 'checked' : false),
                    ])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <label class="form-label">Campaign WhatsApp</label>
                <?= $form->field($model, 'campaign_whatsapp_mode', ['template' => '<div class="toggle-checked"><label class="switch">{input}<div class="slider round"><span class="on">Enabled</span><span class="off">Disabled</span></div></label></div>{error}{hint}'])->checkbox(
                    [
                        'checked' => ($model->campaign_whatsapp_mode ? 'checked' : false),
                    ])->label(false) ?>
            </div>
        </div>
    </div>

    <div class="col-lg-12 form-group text-center">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary form_loader', 'id' => 'campaign-submit']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerCss("
    .bootstrap-switch{
        border-radius: 50px;
        margin-top: 5%;
    }
");


$this->registerJs("
    $('label > #edascampaign-campaign_sticky_agent').insertBefore($('label > #edascampaign-campaign_sticky_agent').parent());
    $('#edascampaign-campaign_sticky_agent').next().remove();
    
    $('label > #edascampaign-campaign_status').insertBefore($('label > #edascampaign-campaign_status').parent());
    $('#edascampaign-campaign_status').next().remove();

    $('label > #edascampaign-campaign_sms_mode').insertBefore($('label > #edascampaign-campaign_sms_mode').parent());
    $('#edascampaign-campaign_sms_mode').next().remove();
    
    $('label > #edascampaign-campaign_email_mode').insertBefore($('label > #edascampaign-campaign_email_mode').parent());
    $('#edascampaign-campaign_email_mode').next().remove();
    
    $('label > #edascampaign-campaign_chat_mode').insertBefore($('label > #edascampaign-campaign_chat_mode').parent());
    $('#edascampaign-campaign_chat_mode').next().remove();

    $('label > #edascampaign-campaign_whatsapp_mode').insertBefore($('label > #edascampaign-campaign_whatsapp_mode').parent());
    $('#edascampaign-campaign_whatsapp_mode').next().remove();
");