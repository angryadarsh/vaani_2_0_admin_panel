<?php

/* @var $this yii\web\View */
/* @var $model \common\models\VaaniClientMaster */

use yii\bootstrap4\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

$this->title = 'Generate Custom Report';

$urlManager = Yii::$app->urlManager;
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <p>Note:</p>
                    </div>
                    <div class="card-body">
                    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                            $('#LoadingBox').show(); 
                        } "], 'id' => 'acd_form']); ?>

                        <div class="row align-items-center form-box">

                            <div class="col-sm-3 col-xl-3 text-center">
                                <div class="form-group">
                                    <?= Select2::widget([
                                        'name' => 'report_type',
                                        'data' => [
                                            1 => 'APR',
                                            2 => 'ACD',
                                            3 => 'Call Register'
                                        ],
                                        'options' => [
                                            'placeholder' => 'Select...',
                                            'id' => 'select_report_type',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]); ?>
                                    <label class="form-label">Type</label>
                                </div>
                            </div>

                            <div class="col-sm-3 col-xl-3 text-center">
                                <div class="form-group">
                                    <input type="date" id="start_date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                                    <label class="form-label">Start Date</label>
                                </div>
                            </div>

                            <div class="col-sm-3 col-xl-3 text-center">
                                <div class="form-group">
                                    <input type="time" id="start_time" name="start_time" class="form-control" value="<?= date('H:i', strtotime('-15 minutes')) ?>" required>
                                    <label class="form-label">Start Time</label>
                                </div>
                            </div>

                            <div class="col-sm-3 col-xl-3 text-center">
                                <div class="form-group">
                                    <input type="time" id="end_time" name="end_time" class="form-control" value="<?= date('H:i') ?>" required>
                                    <label class="form-label">End Time</label>
                                </div>
                            </div>
                        
                        </div>

                        <div class="text-center">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Generate</button>
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
    .client_connection_section{
        display: none;
    }
");

$this->registerJs("
    $('#start_time').on('change', function(){
        var start_time = $(this).val();
        
        var minsToAdd = 15;
        var newTime = new Date(new Date('1970/01/01 ' + start_time).getTime() + minsToAdd * 60000).toLocaleTimeString('en-UK', { hour: '2-digit', minute: '2-digit', hour12: false });
        
        $('#end_time').val(newTime);
    });

    $('#end_time').on('change', function(){
        var end_time = $(this).val();
        
        var minsToSubtract = 15;
        var newTime = new Date(new Date('1970/01/01 ' + end_time).getTime() - minsToSubtract * 60000).toLocaleTimeString('en-UK', { hour: '2-digit', minute: '2-digit', hour12: false });
        
        $('#start_time').val(newTime);
    });
");
?>