<?php

/* @var $this yii\web\View */
/* @var $model \common\models\VaaniClientMaster */

use yii\bootstrap4\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

$this->title = 'ACD Report';

$urlManager = Yii::$app->urlManager;
?>


<div class="content-header">
    <div class="container-fluid">
        <!-- <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item"><a href="#" class="barIcon">Report</a></li>
				<li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
			</ol>
		</nav> -->
        <div class="clearfix top-header">
            <h1 class="float-left">ACD Report</h1>
        </div>
    </div>
</div>

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                            $('#LoadingBox').show(); 
                        } "], 'id' => 'acd_form', 'action' => ['report/acd-report']]); ?>
                            <?= $this->render('_form', [
                                'campaigns' => $campaigns,
                                'queues' => $queues,
                                'users' => $users,
                                'is_hourly' => true,
                                'report_columns' => $report_columns
                            ]) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
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
    $(function() {
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

        // display type only for todays date
        $('#select_date').on('change', function(){
            var selected_date = $(this).val();
            var today_date = moment().format('YYYY-MM-DD');
            
            if(selected_date == (today_date + ' - ' + today_date)){
                $('.type_input').css('display', 'flex');
            }else{
                $('.type_input').hide();
            }
        });
    });
");
?>