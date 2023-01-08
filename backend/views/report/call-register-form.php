<?php

/* @var $this yii\web\View */
/* @var $model \common\models\VaaniClientMaster */

use yii\bootstrap4\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

$this->title = 'Call Register Report';

$urlManager = Yii::$app->urlManager;
?>


<div class="content-header">
    
    <div class="container-fluid">
        <!-- <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item"><a href="#" class="barIcon">Report</a></li>
				<li class="breadcrumb-item active" aria-current="page">Call Register Report</li>
			</ol>
		</nav> -->
        <div class="clearfix top-header">
            <h1 class="float-left">Call Register Report</h1>
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
                        } "], 'id' => 'call_register_form', 'action' => ['report/call-register-report']]); ?>
                            <?= $this->render('_form', [
                                'campaigns' => $campaigns,
                                'queues' => $queues,
                                'users' => $users,
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
