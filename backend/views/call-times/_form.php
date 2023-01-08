<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use common\models\VaaniCallTimesConfig;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniCallTimesConfig */
/* @var $form yii\widgets\ActiveForm */

$urlManager = Yii::$app->urlManager;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
} "], 'enableAjaxValidation' => true, 'validateOnChange' => false, 'id' => 'submitForm']); ?>

<div class="row">
    <div class="col-lg-6">
        <?= $form->field($model, 'call_time_name', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Call Time Name', 'required' => true, 'readOnly' => ($model->isNewRecord ? false : true)])->label('Call Time Name <i class="fas fa-question-circle" title="Enter an appropriate name"></i>', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-3">
        <?= $form->field($model, 'type', ['template' => '{label}{input}{error}{hint}'])->dropdownList($types, ['prompt' => 'Select...', 'required' => true, 'readOnly' => ($model->isNewRecord ? false : true)])->label('Days Type', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-3">
        <?= $form->field($model, 'time_zone', ['template' => '{label}{input}{error}{hint}'])->dropdownList(VaaniCallTimesConfig::timeZones(), ['value' => (!$model->isNewRecord && $model->time_zone ? $model->time_zone : 'Asia/Kolkata'), 'required' => true, 'readOnly' => ($model->isNewRecord ? false : true)])->label('Time Zone', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-12">
        <?= $form->field($model, 'comments', ['template' => '{label}{input}{error}{hint}'])->textarea(['placeholder' => 'Comments', 'readOnly' => ($model->isNewRecord ? false : true)])->label('Comments', ['class' => 'form-label']) ?>
    </div>

    <!-- default time section -->
    <div class="col-lg-12 default-section">
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'default_start_time')->widget(
                    TimePicker::classname(), [
                    'name' => 'default_start_time',
                    'options' => [
                        'id' => 'default_start_time',
                        'placeholder' => 'HH:MM',
                        'required' => true,
                        'class' => 'form-control',
                        'value' => $model->default_start_time,
                        'readOnly' => ($model->isNewRecord ? false : true)
                    ],
                    'pluginOptions' => [
                        'minuteStep' => 1,
                        'showMeridian' => false,
                        'autoclose' => true
                    ]
                ])->label(false) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'default_end_time')->widget(
                    TimePicker::classname(), [
                    'name' => 'default_end_time',
                    'options' => [
                        'id' => 'default_end_time',
                        'placeholder' => 'HH:MM',
                        'required' => true,
                        'class' => 'form-control',
                        'value' => $model->default_end_time,
                        'readOnly' => ($model->isNewRecord ? false : true)
                    ],
                    'pluginOptions' => [
                        'minuteStep' => 1,
                        'showMeridian' => false,
                        'autoclose' => true
                    ]
                ])->label(false) ?>
            </div>
        </div>
    </div>

    <!-- days section -->
    <div class="col-lg-12 days-section">
        <?php $timestamp = strtotime('next Sunday');
        for($i = 0; $i < 7; $i++){
            $day = jddayofweek($i,1);
            $day_id = strtolower(jddayofweek($i,2));
            $existingModel = null;
            
            if(!$model->isNewRecord){
                $existingModel = $model->getDayDetail($day_id);
            }?>
            <div class="row">
                <?php if($existingModel){ ?>
                    <?= $form->field($dayModel, 'id[]')->hiddenInput(['value' => $existingModel->id])->label(false) ?>
                <?php } ?>
                <!-- <div class="col-md-1">
                    <div class="form-group field-vaanicalltimes-day_check ml-4 mt-2">
                        <?php // Html::checkbox('VaaniCallTimes[day_check]['.$i.']') ?>
                    </div>
                </div> -->
                <div class="col-md-3">
                    <?= $form->field($dayModel, 'day[]', ['template' => '{label}{input}{error}{hint}'])->textInput(['readOnly' => true, 'value' => $day])->label('Day', ['class' => 'form-label']) ?>
                    <?= $form->field($dayModel, 'day_id[]')->hiddenInput(['value' => $day_id])->label(false) ?>
                </div>
                <div class="col-md-3 mt-auto">
                    <?= $form->field($dayModel, 'start_time[]')->widget(
                        TimePicker::classname(), [
                        'name' => 'day_start_time',
                        'options' => [
                            'id' => 'vaanicalltimes-start_time_'.$i,
                            'placeholder' => 'HH:MM',
                            'required' => true,
                            'class' => 'form-control start_time_class',
                            'value' => ($existingModel ? $existingModel->start_time : '00:00'),
                            'readOnly' => ($model->isNewRecord ? false : true)
                        ],
                        'pluginOptions' => [
                            'minuteStep' => 1,
                            'showMeridian' => false,
                            'autoclose' => true
                        ]
                    ])->label(false) ?>
                </div>
                <div class="col-md-3 mt-auto">
                    <?= $form->field($dayModel, 'end_time[]')->widget(
                        TimePicker::classname(), [
                        'name' => 'day_end_time',
                        'options' => [
                            'id' => 'vaanicalltimes-end_time_'.$i,
                            'placeholder' => 'HH:MM',
                            'required' => true,
                            'class' => 'form-control end_time_class',
                            'value' => ($existingModel ? ($existingModel->subEndTime ? $existingModel->subEndTime->end_time : $existingModel->end_time) : '00:00'),
                            'readOnly' => ($model->isNewRecord ? false : true)
                        ],
                        'pluginOptions' => [
                            'minuteStep' => 1,
                            'showMeridian' => false,
                            'autoclose' => true
                        ]
                    ])->label(false) ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php if($model->isNewRecord){ ?>
        <div class="col-lg-12 form-group text-center">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary form_loader']) ?>
            <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
        </div>
    <?php } ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs("
    $('#default_start_time').after('<label class=\"form-label\">Start Time</label>');
    $('#default_end_time').after('<label class=\"form-label\">End Time</label>');

    for(var i = 0; i < 7; i++ ){
        $('#vaanicalltimes-start_time_'+i).after('<label class=\"form-label\">Start Time</label>');
        $('#vaanicalltimes-end_time_'+i).after('<label class=\"form-label\">End Time</label>');
    }


    if($('#vaanicalltimesconfig-type').val() == ".VaaniCallTimesConfig::TYPE_ALL."){
        $('.days-section').hide();
        $('.start_time_class').attr('required', false);
        $('.end_time_class').attr('required', false);

        $('.default-section').show();
        $('#default_start_time').attr('required', true);
        $('#default_end_time').attr('required', true);
    }else if($('#vaanicalltimesconfig-type').val() == ".VaaniCallTimesConfig::TYPE_CUSTOM."){
        $('.default-section').hide();
        $('#default_start_time').attr('required', false);
        $('#default_end_time').attr('required', false);

        $('.days-section').show();
        $('.start_time_class').attr('required', true);
        $('.end_time_class').attr('required', true);
    }else{
        $('.days-section').hide();
        $('.default-section').hide();
    }
        
    $('#vaanicalltimesconfig-type').on('change', function(){
        var type = $(this).val();
        if(type == ".VaaniCallTimesConfig::TYPE_ALL."){
            $('.days-section').hide();
            $('.start_time_class').attr('required', false);
            $('.end_time_class').attr('required', false);

            $('.default-section').show();
            $('#default_start_time').attr('required', true);
            $('#default_end_time').attr('required', true);
        }else if(type == ".VaaniCallTimesConfig::TYPE_CUSTOM."){
            $('.default-section').hide();
            $('#default_start_time').attr('required', false);
            $('#default_end_time').attr('required', false);

            $('.days-section').show();
            $('.start_time_class').attr('required', true);
            $('.end_time_class').attr('required', true);
        }else{
            $('.days-section').hide();
            $('.default-section').hide();
        }
    });

    // AVOID FORM TO SUBMIT ON KEY ENTER
    $(document).ready(function() {
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });
");
?>