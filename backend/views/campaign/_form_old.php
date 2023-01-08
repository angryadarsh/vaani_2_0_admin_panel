<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use common\models\EdasCampaign;
use common\models\User;
use kartik\time\TimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\EdasCampaign */
/* @var $form yii\widgets\ActiveForm */

$urlManager = Yii::$app->urlManager;

// echo "<pre>"; print_r($model->call_window);
// echo "<pre>"; print_r($call_windows);exit;
?>

<?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'], 'enableAjaxValidation' => true, 'validateOnChange' => false, 'id' => 'campaign_form_submit']); ?>

<div class="row">
    <div class="col-lg-6">
        <div class="row">
            <div class="col-lg-12">
                <span class="camp_id" value="<?= $model->id ?>"></span>
                <?= $form->field($model, 'client_id', ['template' => '{input}{label}{error}{hint}'])->dropdownList($clients, ['prompt' => 'Select...', 'required' => true])->label(' Client Name', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-12">
                <?= $form->field($model, 'campaign_type', ['template' => '{input}{label}{error}{hint}'])->dropdownList($campaign_types, ['prompt' => 'Select...', 'required' => true, 'disabled' => !$model->isNewRecord])->label('Campaign Type', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-12">
                <?= $form->field($model, 'campaign_name', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Campaign Name', 'required' => true])->label('Campaign Name', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-12 campaign_call_medium">
                <?= $form->field($model, 'call_medium', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_mediums, ['prompt' => 'Select...', 'required' => true, 'disabled' => !$model->isNewRecord])->label('Call Medium', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-12 campaign_mode hidden">
                <?= $form->field($model, 'campaign_sub_type', ['template' => '{input}{label}{error}{hint}'])->dropdownList($campaign_modes, ['prompt' => 'Select...'])->label('Campaign Mode', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-12 hidden campaign_dni_field">
                <?= $form->field($model, 'campaign_dni', ['template' => '{input}{label}{error}{hint}'])->dropdownList(($model->campaign_dni ? array_replace([$model->campaign_dni => $model->dni->DNI_name], $dnis) : $dnis), ['prompt' => 'Select...', 'id' => 'selected_dni_id'])->label('Campaign DNI', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-12 call_window_field">
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'call_window', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_windows, ['prompt' => 'Select...', 'id' => 'selected_call_window'])->label('Call Window', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-lg-6 hidden">
                        <?php // $form->field($model, 'manual_call_window', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_windows, ['prompt' => 'Select...', 'id' => 'selected_manual_call_window'])->label('Manual Call Window', ['class' => 'form-label']) ?>
                    </div>
                </div>
                <?php /*  $form->field($model, 'campaign_inbound_days', ['template' => '{input}{label}{error}{hint}'])->widget(Select2::classname(), [
                    'data' => $week_days,
                    'options' => ['prompt' => '', 'multiple' => true, 'required' => true],
                    'pluginOptions' => [
                        'tags' => true,
                        'allowClear' => true,
                    ],
                ])->label('Campaign Inbound Days', ['class' => 'form-label']); */ ?>
            </div>
            <!-- <div class="col-lg-6">
                 <?php // $form->field($model, 'service_level_calls', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Service Level Calls', 'required' => true])->label('Service Level Calls', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-6">
                 <?php // $form->field($model, 'service_level_seconds', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Service Level Seconds', 'required' => true])->label('Service Level Seconds', ['class' => 'form-label']) ?>
            </div> -->
            <!-- <div class="col-lg-6">
                <?php /* $form->field($model, 'campaign_inbound_start_time')->widget(
                        TimePicker::classname(), [
                        'name' => 'campaign_inbound_start_time',
                        'options' => [
                            'placeholder' => 'HH:MM',
                            'required' => true,
                            'class' => 'form-control',
                        ],
                        'pluginOptions' => [
                            'minuteStep' => 1,
                            'showMeridian' => false,
                            'autoclose' => true
                        ]
                    ])->label(false) */ ?>
            </div>
            <div class="col-lg-6">
                <?php /* $form->field($model, 'campaign_inbound_end_time')->widget(
                    TimePicker::classname(), [
                        'name' => 'campaign_inbound_end_time',
                        'class' => 'form-control',
                        'options' => [
                            'required' => true,
                            'placeholder' => 'HH:MM',
                        ],
                        'pluginOptions' => [
                            'minuteStep' => 1,
                            'showMeridian' => false,
                            'autoclose' => true
                        ]
                    ])->label(false) */ ?>
            </div> -->
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($campaignAccessModel, 'is_conference', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_conference'])->label('Conference?', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($campaignAccessModel, 'is_transfer', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_transfer'])->label('Transfer?', ['class' => 'form-label']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($campaignAccessModel, 'is_consult', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_consult'])->label('Consult?', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($campaignAccessModel, 'is_manual', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_manual'])->label('Manual?', ['class' => 'form-label']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <!-- <div class="row"> -->
            <!-- <div class="col-lg-12">
                <?php // $form->field($model, 'campaign_inbound_agent_selection_criteria', ['template' => '{input}{label}{error}{hint}'])->dropdownList($criterias, ['prompt' => 'Select...', 'required' => true])->label('Campaign Inbound Agent Selection Criteria', ['class' => 'form-label']) ?>
            </div> -->
        <!-- </div> -->
        <div class="row">
            <div class="col-lg-12">
                <label class="form-label ml-5">Campaign Sticky Agent</label>
                <?= $form->field($model, 'campaign_sticky_agent', ['template' => '<div class="toggle-checked"><label class="switch">{input}<div class="slider round"><span class="on">Enabled</span><span class="off">Disabled</span></div></label></div>{error}{hint}'])->checkbox(
                    [
                        'checked' => ($model->campaign_sticky_agent ? 'checked' : false),
                    ])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <label class="form-label ml-5">Campaign Status</label>
                <?= $form->field($model, 'campaign_status', ['template' => '<div class="toggle-checked"><label class="switch">{input}<div class="slider round"><span class="on">Enabled</span><span class="off">Disabled</span></div></label></div>{error}{hint}'])->checkbox(
                    [
                        'checked' => (($model->campaign_status == EdasCampaign::CAMPAIGN_ACTIVE) ? 'checked' : false),
                        'uncheck' => 2
                    ])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <label class="form-label ml-5">Campaign SMS</label>
                <?= $form->field($model, 'campaign_sms_mode', ['template' => '<div class="toggle-checked"><label class="switch">{input}<div class="slider round"><span class="on">Enabled</span><span class="off">Disabled</span></div></label></div>{error}{hint}'])->checkbox(
                    [
                        'checked' => ($model->campaign_sms_mode ? 'checked' : false),
                    ])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <label class="form-label ml-5">Campaign Email</label>
                <?= $form->field($model, 'campaign_email_mode', ['template' => '<div class="toggle-checked"><label class="switch">{input}<div class="slider round"><span class="on">Enabled</span><span class="off">Disabled</span></div></label></div>{error}{hint}'])->checkbox(
                    [
                        'checked' => ($model->campaign_email_mode ? 'checked' : false),
                    ])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <label class="form-label ml-5">Campaign Chat</label>
                <?= $form->field($model, 'campaign_chat_mode', ['template' => '<div class="toggle-checked"><label class="switch">{input}<div class="slider round"><span class="on">Enabled</span><span class="off">Disabled</span></div></label></div>{error}{hint}'])->checkbox(
                    [
                        'checked' => ($model->campaign_chat_mode ? 'checked' : false),
                    ])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <label class="form-label ml-5">Campaign WhatsApp</label>
                <?= $form->field($model, 'campaign_whatsapp_mode', ['template' => '<div class="toggle-checked"><label class="switch">{input}<div class="slider round"><span class="on">Enabled</span><span class="off">Disabled</span></div></label></div>{error}{hint}'])->checkbox(
                    [
                        'checked' => ($model->campaign_whatsapp_mode ? 'checked' : false),
                    ])->label(false) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3 preview_time hidden">
                <?= $form->field($model, 'preview_time', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Preview Time', 'value' => ($model->preview_time ? $model->preview_time : 10)])->label('Preview Time(In Secs)', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-3 outbound_criteria hidden">
                <?= $form->field($model, 'outbound_criteria', ['template' => '{input}{label}{error}{hint}'])->dropdownList($criterias, ['prompt' => 'Select...'])->label('Criteria', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-3 pacing_value hidden">
                <?= $form->field($model, 'pacing_value', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Pacing Value', 'value' => ($model->pacing_value ? $model->pacing_value : 1)])->label('Pacing Value', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-3 abandoned_percent hidden">
                <?= $form->field($model, 'abandoned_percent', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Abandoned Percent', 'value' => ($model->abandoned_percent ? $model->abandoned_percent : 5)])->label('Abandoned Percent', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-3 hopper_count hidden"> 
                <?= $form->field($model, 'hopper_count', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Basket Count', 'value' => ($model->hopper_count ? $model->hopper_count : 0)])->label('Basket Count', ['class' => 'form-label']) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <?= $form->field($model, 'campaign_operators', ['template' => '{input}{label}{error}{hint}'])->dropdownList($clientsoperator, ['prompt' => 'Select.....'])->label('Campaign Operators', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-3 call_timeout_camp_section hidden">
        <?= $form->field($model, 'call_timeout', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Call Timeout', 'value' => ($model->call_timeout ? $model->call_timeout : 60)])->label('Call Timeout', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-6 row">
        <div class="col-lg-11">
            <?= $form->field($model, 'disposition_plan_id', ['template' => '{input}{label}{error}{hint}'])->dropdownList($disposition_plans, ['prompt' => 'Select...', 'class' => 'disposition_plan form-control', 'value' => User::encrypt_data($model->disposition_plan_id)])->label('Disposition Plan', ['class' => 'form-label']) ?>
        </div>
        <div class="col-lg-1">
            <span class=""><?= Html::a('<i class="fa fa-eye"></i>', null, ['title' => 'View Dispositions', 'id' => 'view_dispo'])?></span>
        </div>
    </div>
    <div class="col-lg-12">
        <?= $form->field($model, 'campaign_inbound_timeover_url', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Campaign Url'])->label('Campaign Url', ['class' => 'form-label']) ?>
    </div>

    <div class="col-lg-12 queue-section hidden">
        <hr>
        <h5 class="m-0 table table-3d"> Queue Details </h5>
        <?php if(!$model->isNewRecord || ($cloneid && $model->call_medium == EdasCampaign::MEDIUM_QUEUE && isset($queues) && $queues)){ 
            foreach($queues as $key => $queue_model){ 
                if($cloneid){ $queue_model->dni_id=null; }?>

            <div class="row queue-row">
                <div class="col-md-12"> <hr> </div>
                <?= $form->field($queueModel, 'id[]', ['template' => '{input}{label}{error}{hint}'])->hiddenInput(['id' => 'queue-queue_id', 'value' => ($cloneid ? null : $queue_model->id)])->label(false) ?>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'queue_name[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Queue Name', 'value' => $queue_model->queue_name])->label('Queue Name', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'call_window[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_windows, ['prompt' => 'Select...', 'id' => 'queue_call_window', 'value' => $queue_model->call_window, 'class' => 'form-control queue-call-window'])->label('Call Window', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'call_timeout[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Call Timeout', 'value' => ($queue_model->call_timeout ? $queue_model->call_timeout : 60)])->label('Call Timeout', ['class' => 'form-label']) ?>
                    <?php // $form->field($queueModel, 'manual_call_window[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_windows, ['prompt' => 'Select...', 'id' => 'queue_manual_call_window', 'value' => $queue_model->manual_call_window, 'class' => 'form-control queue-manual-call-window'])->label('Manual Call Window', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'dni_id[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList(($queue_model->dni ? (array_replace([$queue_model->dni_id => $queue_model->dni->DNI_name] ,$dnis)) : $dnis), ['prompt' => 'Select...', 'id' => 'queue_dni_id', 'value' => $queue_model->dni_id])->label('DNI', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'criteria[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($criterias, ['prompt' => 'Select...', 'value' => $queue_model->criteria])->label('Criteria', ['class' => 'form-label']) ?>
                </div>
                <!-- <div class="col-lg-3">
                    <?php // $form->field($queueModel, 'is_manual[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['value' => ($queue_model->callAccess ? $queue_model->callAccess->is_manual : 2)])->label('Manual Dialer?', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-3">
                    <?php // $form->field($queueModel, 'is_conference[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['value' => ($queue_model->callAccess ? $queue_model->callAccess->is_conference : 2)])->label('Conference?', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-3">
                    <?php // $form->field($queueModel, 'is_transfer[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['value' => ($queue_model->callAccess ? $queue_model->callAccess->is_transfer : 2)])->label('Transfer?', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-3">
                    <?php // $form->field($queueModel, 'is_consult[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['value' => ($queue_model->callAccess ? $queue_model->callAccess->is_consult : 2)])->label('Consult?', ['class' => 'form-label']) ?>
                </div> -->
                <div class="col-lg-2 mr-0 pr-0">
                    <?= Html::a('+', null, ['class' => 'btn btn-success add-queue']) ?>
                    <?= Html::a('-', null, ['class' => 'btn btn-danger remove-queue']) ?>
                </div>
            </div>

            <?php } ?>
        <?php }else{ ?>
            <div class="row queue-row">
                <div class="col-md-12"> <hr> </div>
                <?= $form->field($queueModel, 'id[]', ['template' => '{input}{label}{error}{hint}'])->hiddenInput(['id' => 'queue-queue_id'])->label(false) ?>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'queue_name[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Queue Name'])->label('Queue Name', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'call_window[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_windows, ['prompt' => 'Select...', 'id' => 'queue_call_window', 'class' => 'form-control queue-call-window'])->label('Call Window', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'call_timeout[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Call Timeout', 'value' => 60])->label('Call Timeout', ['class' => 'form-label']) ?>
                    <?php // $form->field($queueModel, 'manual_call_window[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_windows, ['prompt' => 'Select...', 'id' => 'queue_manual_call_window', 'class' => 'form-control queue-manual-call-window'])->label('Manual Call Window', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'dni_id[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($dnis, ['prompt' => 'Select...', 'id' => 'queue_dni_id'])->label('DNI', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'criteria[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($criterias, ['prompt' => 'Select...'])->label('Criteria', ['class' => 'form-label']) ?>
                </div>
                <!-- <div class="col-lg-2 mr-0 pr-0">
                    <?php // $form->field($queueModel, 'hold_music_file[0]', ['options' => ['class' => 'upload_file_input form-group']])->fileInput(['id' => 'vaanicampaignqueue-hold_music_file'])->label('<span class="music_label"><i class="fa fa-upload btn btn-default"> Upload Music </i></span> <span class="music_name"></span>') ?>
                </div> -->
                <!-- <div class="col-lg-3">
                    <?php // $form->field($queueModel, 'is_manual[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values)->label('Manual Dialer?', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-3">
                    <?php // $form->field($queueModel, 'is_conference[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values)->label('Conference?', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-3">
                    <?php // $form->field($queueModel, 'is_transfer[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values)->label('Transfer?', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-3">
                    <?php // $form->field($queueModel, 'is_consult[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values)->label('Consult?', ['class' => 'form-label']) ?>
                </div> -->
                <div class="col-lg-2 mr-0 pr-0">
                    <?= Html::a('+', null, ['class' => 'btn btn-success add-queue']) ?>
                    <?= Html::a('-', null, ['class' => 'btn btn-danger remove-queue']) ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="col-lg-12 ivr-section hidden">
        <hr>
        <h5 class="m-0 table table-3d"> IVR Details </h5>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'is_dtmf', ['template' => '{input}{label}{error}{hint}'])->checkbox(['label' => 'Is DTMF? ', 'id' => 'edit-is_dtmf', 'uncheck' => 2])->label(false) ?>
            </div>
            <div class="col-md-3 check_ivr_queue">
                <?= $form->field($model, 'is_ivr_queue', ['template' => '{input}{label}{error}{hint}'])->checkbox(['label' => 'Is Queue Mapping? ', 'id' => 'edit-is_ivr_queue', 'uncheck' => 2])->label(false) ?>
            </div>
        </div>
        <!-- non queue section -->
        <div class="row ivr-non-queue-section">
            <div class="col-md-3">
                <?= $form->field($model, 'key_input', ['template' => '{input}{label}{error}{hint}<span class="disabled">e.g.  1~2~3~4~5~6</span>'])->textInput(['placeholder' => 'Key Input ', 'id' => 'key_input'])->label('Key Input', ['class' => 'form-label']) ?>
            </div>
        </div>
        <!-- queue section -->
        <div class="row ivr-queue-section">
            <?php if(!$model->isNewRecord && $model->call_medium == EdasCampaign::MEDIUM_IVR && isset($queues) && $queues){ 
                foreach($queues as $key => $queue_model){
                    $queue_model->call_timeout = ($queue_model->call_timeout ? $queue_model->call_timeout : 60); ?>
                    <?= $form->field($queueModel, 'id[]', ['template' => '{input}{label}{error}{hint}'])->hiddenInput(['id' => 'queue-queue_id', 'value' => $queue_model->id, 'name' => 'IvrQueue[id][]'])->label(false) ?>
                    <div class="col-lg-2 mr-0 pr-0">
                        <?= $form->field($queueModel, 'key_input[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Key Input ', 'id' => 'queue-key_input', 'value' => $queue_model->key_input, 'name' => 'IvrQueue[key_input][]'])->label('Key Input', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-lg-2 mr-0 pr-0">
                        <?= $form->field($queueModel, 'queue_name[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Queue Name ', 'id' => 'queue-queue_name', 'value' => $queue_model->queue_name, 'name' => 'IvrQueue[queue_name][]'])->label('Queue Name', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-lg-2 mr-0 pr-0">
                        <?= $form->field($queueModel, 'call_window[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_windows, ['prompt' => 'Select...', 'id' => 'queue_call_window', 'value' => $queue_model->call_window, 'name' => 'IvrQueue[call_window][]', 'class' => 'form-control queue-call-window'])->label('Call Window', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-lg-2 mr-0 pr-0">
                        <?= $form->field($queueModel, 'call_timeout[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Call Timeout ', 'id' => 'queue-call_timeout', 'value' => $queue_model->call_timeout, 'name' => 'IvrQueue[call_timeout][]'])->label('Call Timeout', ['class' => 'form-label']) ?>
                        <?php // $form->field($queueModel, 'manual_call_window[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_windows, ['prompt' => 'Select...', 'id' => 'queue_manual_call_window', 'value' => $queue_model->manual_call_window, 'name' => 'IvrQueue[manual_call_window][]', 'class' => 'form-control queue-manual-call-window'])->label('Manual Call Window', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-lg-2 mr-0 pr-0">
                        <?= $form->field($queueModel, 'criteria[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($criterias, ['prompt' => 'Select...', 'id' => 'queue-criteria', 'value' => $queue_model->criteria, 'name' => 'IvrQueue[criteria][]'])->label('Criteria', ['class' => 'form-label']) ?>
                    </div>
                    <!-- <div class="col-md-1 pr-0">
                        <?php // $form->field($queueModel, 'is_conference[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_conference', 'value' => $queue_model->is_conference, 'name' => 'IvrQueue[is_conference][]'])->label('Conference?', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-md-1 pr-0">
                        <?php // $form->field($queueModel, 'is_transfer[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_transfer', 'value' => $queue_model->is_transfer, 'name' => 'IvrQueue[is_transfer][]'])->label('Transfer?', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-md-1 pr-0">
                        <?php // $form->field($queueModel, 'is_consult[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_consult', 'value' => $queue_model->is_consult, 'name' => 'IvrQueue[is_consult][]'])->label('Consult?', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-md-1 pr-0">
                        <?php // $form->field($queueModel, 'is_manual[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_manual', 'value' => $queue_model->is_manual, 'name' => 'IvrQueue[is_manual][]'])->label('Manual?', ['class' => 'form-label']) ?>
                    </div> -->
                    <div class="col-lg-2 mr-0 pr-0">
                        <?= Html::a('+', null, ['class' => 'btn btn-success add-ivr-queue']) ?>
                        <?= Html::a('-', null, ['class' => 'btn btn-danger remove-ivr-queue']) ?>
                    </div>
                <?php }
            }else{ ?>
                <?= $form->field($queueModel, 'id[]', ['template' => '{input}{label}{error}{hint}'])->hiddenInput(['id' => 'queue-queue_id', 'name' => 'IvrQueue[id][]'])->label(false) ?>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'key_input[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Key Input ', 'id' => 'queue-key_input', 'name' => 'IvrQueue[key_input][]'])->label('Key Input', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'queue_name[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Queue Name ', 'id' => 'queue-queue_name', 'name' => 'IvrQueue[queue_name][]'])->label('Queue Name', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'call_window[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_windows, ['prompt' => 'Select...', 'id' => 'queue_call_window', 'name' => 'IvrQueue[call_window][]', 'class' => 'form-control queue-call-window'])->label('Call Window', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'call_timeout[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Call Timeout ', 'id' => 'queue-call_timeout', 'name' => 'IvrQueue[call_timeout][]', 'value' => 60])->label('Call Timeout', ['class' => 'form-label']) ?>
                    <?php // $form->field($queueModel, 'manual_call_window[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_windows, ['prompt' => 'Select...', 'id' => 'queue_manual_call_window', 'name' => 'IvrQueue[manual_call_window][]', 'class' => 'form-control queue-manual-call-window'])->label('Manual Call Window', ['class' => 'form-label']) ?>
                </div>
                <div class="col-lg-2 mr-0 pr-0">
                    <?= $form->field($queueModel, 'criteria[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($criterias, ['prompt' => 'Select...', 'id' => 'queue-criteria', 'name' => 'IvrQueue[criteria][]'])->label('Criteria', ['class' => 'form-label']) ?>
                </div>
                <!-- <div class="col-md-1 pr-0">
                    <?php // $form->field($queueModel, 'is_conference[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_conference', 'name' => 'IvrQueue[is_conference][]'])->label('Conference?', ['class' => 'form-label']) ?>
                </div>
                <div class="col-md-1 pr-0">
                    <?php // $form->field($queueModel, 'is_transfer[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_transfer', 'name' => 'IvrQueue[is_transfer][]'])->label('Transfer?', ['class' => 'form-label']) ?>
                </div>
                <div class="col-md-1 pr-0">
                    <?php // $form->field($queueModel, 'is_consult[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_consult', 'name' => 'IvrQueue[is_consult][]'])->label('Consult?', ['class' => 'form-label']) ?>
                </div>
                <div class="col-md-1 pr-0">
                    <?php // $form->field($queueModel, 'is_manual[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_manual', 'name' => 'IvrQueue[is_manual][]'])->label('Manual?', ['class' => 'form-label']) ?>
                </div> -->
                <div class="col-lg-2 mr-0 pr-0">
                    <?= Html::a('+', null, ['class' => 'btn btn-success add-ivr-queue']) ?>
                    <?= Html::a('-', null, ['class' => 'btn btn-danger remove-ivr-queue']) ?>
                </div>
            <?php } ?>
        </div>
    </div>
    
    <!-- DISPOSITION MODAL -->
    <div id="dispo_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <p class="">Set Dispositions Configurations</p>
                    <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="dispo_details">

                    </div>
                </div>
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
    .toggle-checked{
        width: 80%;
    }
");

$this->registerJs("
    $('#edascampaign-campaign_inbound_start_time').after('<label class=\"form-label\">Inbound Start Time</label>');
    $('#edascampaign-campaign_inbound_end_time').after('<label class=\"form-label\">Inbound End Time</label>');

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

    // fetch unused DNIS
    function fetchDnis(client_id)
    {
        $.ajax({
            method: 'GET',
            url: '".$urlManager->baseUrl . '/index.php/campaign/get-dnis'."',
            data: {client_id: client_id}
        }).done(function(data){
            var data = $.parseJSON(data);

            if(Object.keys(data).length > 0){
                var optionList = '<option value=\"\">Select...</option>';
                $.each(data, function(key, value){
                    optionList += '<option value=\"'+key+'\">'+ value +'</option>';
                });
                $('#selected_dni_id').html(optionList);
                $('#queue_dni_id').html(optionList);
            }else{
                if(client_id){
                    Swal.fire('No DNIs are available for use!', '', 'error');
                }
            }
        });
    }

    // ON CHANGE OF CLIENT FETCH UNUSED DNIS
    $('#edascampaign-client_id').on('change', function(){
        var client_id = $(this).val();
        
        // fetch dnis function
        if($('#edascampaign-campaign_type').val() && $('#edascampaign-campaign_type').val() != ".EdasCampaign::TYPE_OUTBOUND."){
            fetchDnis(client_id);
        }
    });

    /* $(document).ready(function(){
        var clientid = $('#edascampaign-client_id').val();
        // fetch dnis function
        console.log($('.camp_id').attr('value'));
        if(!$('.camp_id').attr('value')){
            fetchDnis(clientid);
        }
    }); */
");

$this->registerJs("
    // call medium
    var call_medium = $('#edascampaign-call_medium').val();
    if(call_medium == ".EdasCampaign::MEDIUM_QUEUE."){
        $('#selected_dni_id').val('');
        $('.campaign_dni_field').hide();
        $('.ivr-section').hide();
        $('.queue-section').show();
        $('#selected_dni_id').attr('required', false);
    }else if(call_medium == ".EdasCampaign::MEDIUM_IVR."){
        $('.campaign_dni_field').show();
        $('.queue-section').hide();
        $('.ivr-section').show();
        $('#selected_dni_id').attr('required', true);
    }else{
        $('.campaign_dni_field').hide();
        $('.queue-section').hide();
        $('.ivr-section').hide();
        $('#selected_dni_id').attr('required', false);
    }
    
    // on change of call medium show/hide queue/ivr section
    $('#edascampaign-call_medium').on('change', function(){
        var call_medium = $(this).val();
        if(call_medium == ".EdasCampaign::MEDIUM_QUEUE."){
            $('#selected_dni_id').val('');
            $('.campaign_dni_field').hide();
            $('.ivr-section').hide();
            $('.queue-section').show();
            $('#selected_dni_id').attr('required', false);
        }else if(call_medium == ".EdasCampaign::MEDIUM_IVR."){
            $('.campaign_dni_field').show();
            $('.queue-section').hide();
            $('.ivr-section').show();
            $('#selected_dni_id').attr('required', true);
        }else{
            $('.campaign_dni_field').hide();
            $('.queue-section').hide();
            $('.ivr-section').hide();
            $('#selected_dni_id').attr('required', false);
        }
    });
");

$this->registerJs("

    // add/remove queue row
    $('.remove-queue').first().hide();

    $(document).on('click', '.add-queue', function(){
        var thisRow = $(this).closest('.queue-row' );
        var cloneRow = thisRow.clone();
        cloneRow.insertAfter( thisRow ).find( 'input' ).val( '' );
        cloneRow.find( '#queue_dni_id, #vaanicampaignqueue-criteria' ).val( '' );
        
        // SET DEFAULT VALUE OF CALL TIMEOUT TO 60
        cloneRow.find( '#vaanicampaignqueue-call_timeout' ).val( '60' );
        
        // set empty file upload
        /* hold_music_name = cloneRow.find( '#vaanicampaignqueue-hold_music_file' ).attr('name');
        hold_music_key = hold_music_name.charAt(hold_music_name.length-2);
        console.log(hold_music_key);
        if(hold_music_key){
            hold_music_key = parseInt(hold_music_key) + 1;
            cloneRow.find( '#vaanicampaignqueue-hold_music_file' ).attr('name', 'VaaniCampaignQueue[hold_music_file][' + (hold_music_key) + ']');
            cloneRow.find( '#vaanicampaignqueue-hold_music_file' ).prev().attr('name', 'VaaniCampaignQueue[hold_music_file][' + (hold_music_key) + ']');
        }
        cloneRow.find( '#vaanicampaignqueue-hold_music_file' ).files = null;
        cloneRow.find( '#vaanicampaignqueue-hold_music_file' ).parent().find('.music_name').html(``);
        cloneRow.find( '#vaanicampaignqueue-hold_music_file' ).parent().find('.music_label .btn-default').show(); */

        cloneRow.find( '.help-block' ).html( '' );
        cloneRow.find('.remove-queue').show();

        // remove selected dni for next
        var dni_id = thisRow.find('#queue_dni_id').val();
        if(dni_id)
            cloneRow.find('#queue_dni_id option[value=\"'+dni_id+'\"]').remove();
    });
    $(document).on('click', '.remove-queue', function(){
        var thisRow = $(this).closest('.queue-row' );
        thisRow.remove();
    });
");

$this->registerJs("

    // check if queue section is visible and no data is provided
    $('#campaign-submit').on('click', function(){
        if($('#edascampaign-campaign_type').val() == ".EdasCampaign::TYPE_INBOUND."){
            if($('#edascampaign-call_medium').val() == ".EdasCampaign::MEDIUM_QUEUE."){
                if(!$('#vaanicampaignqueue-queue_name:first').val() && !$('#queue_dni_id:first').val() && !$('#vaanicampaignqueue-criteria:first').val()){
                    Swal.fire('Kindly add atleast one queue!', '', 'error');
                    return false;

                }else if(!$('#vaanicampaignqueue-queue_name').val() || !$('#queue_dni_id').val() || !$('#vaanicampaignqueue-criteria').val()){
                    Swal.fire('Kindly add all details for the queue!', '', 'error');
                    return false;
                }
            }else if($('#edascampaign-call_medium').val() == ".EdasCampaign::MEDIUM_IVR."){
                // return false;
            }
        }
    });
");

$this->registerJs("

    // on change of call window of campaign
    $('#selected_call_window').on('change', function(){
        var campaign_call_window = $('#selected_call_window').val();
        if(($('#edascampaign-campaign_type').val() == ".EdasCampaign::TYPE_INBOUND.") && campaign_call_window){
            Swal.fire('This will override all queues call window!', '', 'warning');
            $('.queue-call-window').val(campaign_call_window);
        }
    });

    // on change of call window of queue
    $(document).on('change', '.queue-call-window', function(){
        var campaign_call_window = $('#selected_call_window').val();
        var queue_call_window = $(this).val();

        if(queue_call_window){
            if(campaign_call_window){
                Swal.fire('This will override the campaign call window!', '', 'warning');
                // $('#selected_call_window').val(queue_call_window);
            }
        }else{
            Swal.fire('Kindly add all details for the queue!', '', 'warning');
            return false;
        }
    });

    // on change of manual call window of campaign
    /* $('#selected_manual_call_window').on('change', function(){
        var campaign_manual_call_window = $('#selected_manual_call_window').val();
        if(($('#edascampaign-campaign_type').val() == ".EdasCampaign::TYPE_INBOUND.") && campaign_manual_call_window){
            Swal.fire('This will override all queues manual call window!', '', 'warning');
            $('.queue-manual-call-window').val(campaign_manual_call_window);
        }
    }); */

    // on change of manual call window of queue
    /* $(document).on('change', '.queue-manual-call-window', function(){
        var campaign_manual_call_window = $('#selected_manual_call_window').val();
        var queue_manual_call_window = $(this).val();

        if(queue_manual_call_window){
            if(campaign_manual_call_window){
                Swal.fire('This will override the campaign manual call window!', '', 'warning');
                // $('#selected_manual_call_window').val(queue_manual_call_window);
            }
        }else{
            Swal.fire('Kindly add all details for the queue!', '', 'warning');
            return false;
        }
    }); */
");

$this->registerJs("
    function fetchMode(type){
        if(type == ".EdasCampaign::TYPE_OUTBOUND." || type == ".EdasCampaign::TYPE_BLENDED."){
            // $('#selected_call_window').val('');
            $('.campaign_mode').show();
            $('.outbound_criteria').show();
            $('#edascampaign-outbound_criteria').attr('required', true);
            
            // hide all inbound fields
            if(type == ".EdasCampaign::TYPE_OUTBOUND."){
                $('.campaign_call_medium').hide();
                $('#edascampaign-call_medium').val(1);
                $('.campaign_dni_field').hide();
                $('.queue-section').hide();
                $('.ivr-section').hide();
                $('#selected_dni_id').attr('required', false);
            }else{
                $('#edascampaign-call_medium').val(1).trigger('change');
            }
        }else{
            $('.campaign_mode').hide();
            $('.campaign_call_medium').show();
            $('.outbound_criteria').hide();
            $('.pacing_value').hide();
            $('.abandoned_percent').hide();
            $('.hopper_count').hide();
            $('.preview_time').hide();
            $('#edascampaign-outbound_criteria').attr('required', false);
        }
    }

    // on change of campaign type 
    $('#edascampaign-campaign_type').on('change', function(){
        var type = $(this).val();
        var client_id = $('#edascampaign-client_id').val();
        fetchMode(type);
        if(type != ".EdasCampaign::TYPE_OUTBOUND."){
            fetchDnis(client_id);
        }
        if(type != ".EdasCampaign::TYPE_INBOUND."){
            $('.call_timeout_camp_section').show();
        }else{
            $('.call_timeout_camp_section').hide();
        }
    });

    var campaign_type = $('#edascampaign-campaign_type').val();
    if(campaign_type) fetchMode(campaign_type);
    if(campaign_type != ".EdasCampaign::TYPE_INBOUND."){
        $('.call_timeout_camp_section').show();
    }else{
        $('.call_timeout_camp_section').hide();
    }

    // ////////////////////////////////////////////

    function displayPreviewTime(sub_type){
        if(sub_type == ".EdasCampaign::MODE_TIME_PREVIEW."){
            $('.preview_time').show();
            $('.outbound_criteria').hide();
            $('#edascampaign-outbound_criteria').attr('required', false);
            $('#edascampaign-preview_time').attr('required', true);
        }else{
            $('.preview_time').hide();
            $('.outbound_criteria').show();
            $('#edascampaign-outbound_criteria').attr('required', true);
            $('#edascampaign-preview_time').attr('required', false);
        }
        
        if(sub_type == ".EdasCampaign::MODE_NORMAL_PREVIEW." || sub_type == ".EdasCampaign::MODE_TIME_PREVIEW."){
            $('.outbound_criteria').hide();
            $('#edascampaign-outbound_criteria').attr('required', false);
        }else{
            $('.outbound_criteria').show();
            $('#edascampaign-outbound_criteria').attr('required', true);
        }
    }

    function displayPacingValue(sub_type){
        if(sub_type == ".EdasCampaign::MODE_PROGRESSIVE."|| sub_type == ".EdasCampaign::MODE_PREDICTIVE."){
            $('.pacing_value').show();
            $('#edascampaign-pacing_value').attr('required', true);
        }else{
            $('.pacing_value').hide();
            $('#edascampaign-pacing_value').attr('required', false);
        }
    }
    function displayAbundantRatio(sub_type){
        if(sub_type == ".EdasCampaign::MODE_PREDICTIVE."){
            $('.abandoned_percent').show();
            $('#edascampaign-abandoned_percent').attr('required', true);
        }else{
            $('.abandoned_percent').hide();
            $('#edascampaign-abandoned_percent').attr('required', false);
        }
    }
    function displayhoppercount(sub_type){
        console.log(sub_type);
        if(sub_type == ".EdasCampaign::MODE_PROGRESSIVE." || sub_type == ".EdasCampaign::MODE_PREDICTIVE."){
            $('.hopper_count').show();
            $('#edascampaign-hopper_count').attr('required', true);
        }else{
            $('.hopper_count').hide();
            $('#edascampaign-hopper_count').attr('required', false);
        }
    }
   
    // on change of campaign sub type 
    $('#edascampaign-campaign_sub_type').on('change', function(){
        var sub_type = $(this).val();
        displayPreviewTime(sub_type);
        displayPacingValue(sub_type);
        displayAbundantRatio(sub_type);
        displayhoppercount(sub_type);
    });

    var sub_type = $('#edascampaign-campaign_sub_type').val();
    if(sub_type){
        displayPreviewTime(sub_type);
        displayPacingValue(sub_type);
        displayAbundantRatio(sub_type);
        displayhoppercount(sub_type);
    }
");

// campaign dispositions
$this->registerJs("
    function fetchDispo(plan_id, campaign_id){
        $.ajax({
            method: 'GET',
            url: '".$urlManager->baseUrl . '/index.php/campaign/get-dispositions'."',
            data: {plan_id: plan_id, campaign_id : campaign_id}
        }).done(function(data){
            $('.dispo_details').html(data);
        });
    }

    var plan_id = $('.disposition_plan').val();
    var campaign_id = '".User::encrypt_data($model->campaign_id)."';
    fetchDispo(plan_id, campaign_id);

    $('.disposition_plan').on('change', function(){
        var plan_id = $(this).val();
        var campaign_id = '".User::encrypt_data($model->campaign_id)."';
        fetchDispo(plan_id, campaign_id);
        $('#dispo_modal').modal('show');
    });

    // view dispo
    $('#view_dispo').on('click', function(){
        $('#dispo_modal').modal('show');
    });
");

?>

<!-- IVR -->

<?php
$this->registerJs("
    if($('#edit-is_dtmf').is(':checked')){
        $('.check_ivr_queue').show();
        
        // is queue checked
        if($('#edit-is_ivr_queue').is(':checked')){
            $('.ivr-non-queue-section').hide();
            $('.ivr-non-queue-section').find( 'input:text' ).attr('required', false);

            $('.ivr-queue-section').show();
            $('.ivr-queue-section').find( 'input:text' ).attr('required', true);
            $( '#queue-criteria' ).attr('required', true);
        }else{
            // $('.ivr-queue-section').hide();
            $('.ivr-queue-section').find( 'input:text' ).attr('required', false);
            $( '#queue-criteria' ).attr('required', false);

            $('.ivr-non-queue-section').show();
            $('.ivr-non-queue-section').find( 'input:text' ).attr('required', true);
        }
    }else{
        $('.check_ivr_queue').hide();
        $('.ivr-non-queue-section').hide();
        $('.ivr-queue-section').hide();
    }
    
    $('#edit-is_dtmf').on('change', function(){
        var is_dtmf = $(this).is(':checked');
        
        if(is_dtmf){
            $('.check_ivr_queue').show();
            $('#edit-is_ivr_queue').prop('checked', false);
            $('.ivr-non-queue-section').show();
            $('.ivr-non-queue-section').find( 'input:text' ).attr('required', true);
            
            $('.ivr-queue-section').find( 'input:text' ).val('');
            $( '#queue-criteria' ).val('');
            $('.ivr-queue-section').find( 'input:text' ).attr('required', false);
            $( '#queue-criteria' ).attr('required', false);
        }else{
            $('.ivr-non-queue-section').hide();
            $('.ivr-non-queue-section').find( 'input:text' ).val('');
            $('.ivr-non-queue-section').find( 'input:text' ).attr('required', false);

            $('.ivr-queue-section').hide();
            $('.ivr-queue-section').find( 'input:text' ).val('');
            $( '#queue-criteria' ).val('');
            $('.ivr-queue-section').find( 'input:text' ).attr('required', false);
            $( '#queue-criteria' ).attr('required', false);
            $('.check_ivr_queue').hide();
        }
    });
    
    $('#edit-is_ivr_queue').on('change', function(){
        var is_ivr_queue = $(this).is(':checked');
        
        if(is_ivr_queue){
            $('.ivr-non-queue-section').hide();
            $('.ivr-non-queue-section').find( 'input:text' ).val('');
            $('.ivr-non-queue-section').find( 'input:text' ).attr('required', false);

            $('.ivr-queue-section').show();
            $('.ivr-queue-section').find( 'input:text' ).attr('required', true);
            $( '#queue-criteria' ).attr('required', true);
        }else{
            $('.ivr-queue-section').hide();
            $('.ivr-queue-section').find( 'input:text' ).val('');
            $( '#queue-criteria' ).val('');
            $('.ivr-queue-section').find( 'input:text' ).attr('required', false);
            $( '#queue-criteria' ).attr('required', false);

            $('.ivr-non-queue-section').show();
            $('.ivr-non-queue-section').find( 'input:text' ).attr('required', true);
        }

    });

    $('.remove-ivr-queue').first().hide();

    $(document).on('click', '.add-ivr-queue', function(){
        var thisRow = $(this).closest('.ivr-queue-section' );
        var cloneRow = thisRow.clone();
        cloneRow.insertAfter( thisRow ).find( 'input' ).val( '' );
        cloneRow.find('#queue-criteria').val('');
        cloneRow.find('.remove-ivr-queue').show();
    });
    $(document).on('click', '.remove-ivr-queue', function(){
        var thisRow = $(this).closest('.ivr-queue-section' );
        thisRow.remove();
    });

    // on change of upload music file
    /* $('#vaanicampaignqueue-hold_music_file').change(function(e) {
        if(e.target.files[0]){
            var filename = e.target.files[0].name;
            $(this).parent().find('.music_name').html(`<span class='music-text'>` + filename + `</span> <span class=''><i class='fa fa-upload'></i></span>`);
            $(this).parent().find('.music_label .btn-default').hide();
        }else{
            $(this).parent().find('.music_name').html(``);
            $(this).parent().find('.music_label .btn-default').show();
        }
    }); */
");
?>

<?php
// loader ajax message
$this->registerJs("
$(document).ready(function(){

    var loader = '';
    var loadercnt = 0 ;

    var refreshLoader = function () {
        $('.customized_loader_text span').html('<marquee scrollamount=\"8\">Hey! Hold Back! &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  We are processing your request...</marquee>');
        loader = setInterval(function(){ 
            loadercnt++;
            if(loadercnt==18)
            {
                $('.customized_loader_text span').html('<marquee scrollamount=\"8\">Just a moment!</marquee>');
            }
            else if(loadercnt==50)
            {
                $('.customized_loader_text span').html('<marquee scrollamount=\"5\"> We are almost Done! <i class=\"fas fa-check-circle\"></i></marquee>');
            }
        }, 1000);
    }

    $('#campaign_form_submit').on('submit', function(){
        
        if(!$('.help-block').text()) {
            $('#LoadingBox').show();
            refreshLoader();
            
            $.ajax({
                method: 'POST',
                url: $(this).prop('action'),
                data: $(this).serialize(),
            }).done(function(data){

            });
        }

    });
});
");
?>