<?php

/* @var $this yii\web\View */
/* @var $model \common\models\VaaniClientMaster */

use yii\bootstrap4\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use common\models\EdasCampaign;

$this->title = 'Evaluation';

$urlManager = Yii::$app->urlManager;
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('Pending Audit Feedback', ['/qms/pending'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                <?= Html::a('Audit Dispute', ['/qms/disputes'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
            </div>
        </div>
        <!-- <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item"><a href="#" class="barIcon">Report</a></li>
				<li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
			</ol>
		</nav> -->
        <!-- <div class="clearfix top-header">
            <h1 class="float-left">Evaluation</h1>
        </div> -->
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <!-- <ul class="nav nav-link-inline mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="recordings">Add Process</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Add Sheets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Add Parameters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">User Master</a>
                    </li>
                </ul> -->
                
                <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                    $('#LoadingBox').show(); 
                } "], 'id' => 'performance_form', 'action' => ['report/recordings']]); ?>
                    <div class="row justify-content-center align-items-center form-box">
                        <!-- <div class="col-sm-6 col-xl-6 text-center">
                            <div class="form-group">
                                <?php  /* Select2::widget([
                                    'name' => 'campaign_type',
                                    'data' => EdasCampaign::$campaign_types,
                                    'options' => [
                                        'placeholder' => 'Select...',
                                        'id' => 'select_campaign_type',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]); */ ?>
                                <label class="form-label">Type</label>
                            </div>
                        </div> -->
                        <?= $this->render('_form', [
                            'campaigns' => $campaigns,
                            'queues' => $queues,
                            'users' => $users,
                            'report_columns' => $report_columns
                        ]) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>

<?php
$this->registerCss("
    .select2-selection__rendered { text-align: left; }
    /* select {
        opacity: 0;
        visibility: visible !important;
    } */
");

$this->registerJs("

    // daterange picker
    /* $(function() {
        var start = moment();
        var end = moment();

        $('input[name=\"dates\"]').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
            },
            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });
    }); */
");
?>