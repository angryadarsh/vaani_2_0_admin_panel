<?php

/* @var $this yii\web\View */
/* @var $model \common\models\InboundEdas */

use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;
use fedemotta\datatables\DataTables;
use common\models\User;

$this->title = 'Network Lag Report';
?>
<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item"><a href="#" class="barIcon">Report</a></li>
				<li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
			</ol>
		</nav>
        <div class="clearfix top-header">
            <h1 class="float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('Back', ['network-lag-report'], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header filter-section mt-10">
                        <?php $form = ActiveForm::begin([
                            'method' => 'get',
                            'id' => 'filter_form'
                        ]); ?>
                            <div class="row">
                                <div class="col-md-5">
                                    <?= $form->field($searchModel, 'agent_id', ['template' => '{input}{label}{error}{hint}'])->dropdownList($agents, ['prompt' => 'Select...'])->label('Agent', ['class' => 'form-label']) ?>
                                </div>
                                <div class="col-md-5">
                                    <?= $form->field($searchModel, 'dates', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Select Dates', 'autocomplete' => 'off'])->label('Dates', ['class' => 'form-label']) ?>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group pull-right">
                                        <?= Html::submitButton('Search', ['class' => 'btn btn-secondary report_search']) ?>
                                    </div>
                                </div>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
        <table id="report_data" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>AGENT ID</th>
                    <th>AGENT NAME</th> 
                    <th>START TIME</th>
                    <th>END TIME</th>
                    <th>DURATION</th>
                </tr>
            </thead>
            <tbody>
            <?php if($dataProvider->query->all()){
                foreach ($dataProvider->query->asArray()->all() as $key => $value) {
                    $user = User::findOne(['user_id' => $value['agent_id']]);
                    $user_name = ($user ? ($user->user_name) : '-');
            ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td><?= (isset($value['agent_id']) ? $value['agent_id'] : '-') ?></td>
                    <td><?= (isset($user_name) ? $user_name : '-') ?></td>
                    <td><?= (isset($value['start_epoch']) ? date('H:i:s', $value['start_epoch']) : '-') ?></td>
                    <td><?= (isset($value['end_epoch']) ? date('H:i:s', $value['end_epoch']) : '-') ?></td>
                    <td><?= (isset($value['duration']) ? gmdate('H:i:s', $value['duration']) : '00:00:00') ?></td>
                </tr>
            <?php } } ?>
            </tbody>
        </table>
    </div>
</section>

<?php
$this->registerCss("
	.dataTables_length{
		margin-left : 2%
	}
");

$this->registerJs("
	// $('table').addClass('table-responsive');
    $(document).ready(function() {
        $('#report_data').DataTable( {
            'lengthMenu' : [[10, 20, 50, -1], [10, 20, 50, 'All']],
            'responsive' : true,
            'dom': 'Bflrtip',
            'buttons': [  
                'copy', 'excel', 'csv', 'print',
                {
                    'text' : 'PDF',
                    'extend' : 'pdfHtml5',
                    'orientation' : 'landscape',
                    'pageSize' : 'A3',
                    'exportOptions' : {
                        'columns' : ':visible',
                        'search' : 'applied',
                        'order' : 'applied'
                    },
                }
            ],
        });
    } );
");

$this->registerJs("
    // daterange picker
    $(function() {
        var start = moment();
        var end = moment();

        $('input[name=\"NetworkLogSearch[dates]\"]').daterangepicker({
            autoUpdateInput : true,
            locale: {
                format: 'YYYY-MM-DD',
            },
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        // validation on report filter fields
        $('.report_search').on('click', function(){
            if(!$('#networklogsearch-agent_id').val()) {
                $('#networklogsearch-agent_id').next().next().html('Kindly select the Agent!');
                return false;
            }        
        });
    });
");
?>