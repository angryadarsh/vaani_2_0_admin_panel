<?php

use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;
use fedemotta\datatables\DataTables;
use common\models\User;


$this->title = 'QMS Report';
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix top-header">
            <h1 class="float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <!-- <?= Html::a('<i class="fas fa-chevron-left"></i>', ['qms-report'], ['class' => 'btn btn-outline-primary']) ?> -->
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
                        <?= Html::beginForm(['/report/qms-report'], 'POST'); ?>
                            <div class="row justify-content-center">
                                <div class="col-md-5 ">
                                    <div class="form-group field-qmsreport-dates">
                                        <?= Html::label('Date', 'qmsreport-dates', ['class' => 'form-label']) ?>
                                        <?= Html::input('date','QmsReport[dates]',$searchModel->dates, ['class'=>'form-control','placeholder' => 'Select Dates', 'autocomplete' => 'off', 'id' => 'qmsreport-dates', 'max'=> date('Y-m-d')])?>
                                    </div>
                                </div>
                                <div class="col-md-1 mx-2">
                                    <div class="form-group pull-right">
                                        <label class="form-label mt-3"></label>
                                        <?= Html::submitButton('Search', ['class' => 'btn btn-secondary']) ?>
                                    </div>
                                </div>
                            </div>
                        <?= Html::endForm(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
        <table id="report_data" class="table table-striped table-bordered responsive" style="width:100%">
            <thead>
                <tr>
                    <th>EVAL ID</th>
                    <th>MONTH</th>
                    <th>WEEK</th> 
                    <th>AUDIT DATE</th>
                    <th>AGENT</th>
                    <th>AGENT NAME</th>
                    <th>LOCATION</th>
                    <th>AGENT TYPE</th>
                    <th>CATEGORIZATION</th>
                    <th>ACTION STATUS</th>
                    <th>PIP STATUS</th>
                </tr>
            </thead>
            <tbody>
            <?php if($dataProvider){
                foreach ($dataProvider->query->all() as $key => $value) { ?>
                    <tr>
                        <td><?= $value->id ?></td>
                        <td><?= $value->month ?></td>
                        <td><?= $value->week ?></td>
                        <td><?= $value->date_created ?></td>
                        <td><?= $value->agent_id ?></td>
                        <td><?= $value->agent->user_name ?></td>
                        <td><?= $value->location ?></td>
                        <td><?= $value->agent_type ?></td>
                        <td><?= $value->categorization ?></td>
                        <td><?= $value->action_status ?></td>
                        <td><?= $value->pip_status ?></td>
                    </tr>
            <?php } }?>
            </tbody>
        </table>
        </div>
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


?>