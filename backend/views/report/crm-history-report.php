<?php

use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;
use fedemotta\datatables\DataTables;
use common\models\User;


$this->title = 'CRM History Report';
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
            <h1 class="float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('Back', ['crm-history-report'], ['class' => 'btn btn-outline-primary']) ?>
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
                        <?= Html::beginForm(['/report/crm-history-report', 'id' => 'filter_form'], 'POST'); ?>
                            <div class="row justify-content-center">
                                <div class="col-md-5 ">
                                    <div class="form-group field-crmhistoryreport-dates">
                                        <?= Html::label('Date', 'crmhistoryreport-dates', $options= ['class' => 'form-label']) ?>
                                        <?= Html::input('date','CrmHistoryReport[dates]',$date, $options= ['class'=>'form-control','placeholder' => 'Select Dates', 'autocomplete' => 'off', 'id' => 'crmhistoryreport-dates', 'max'=> date('Y-m-d')])?>
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
                    <th>#</th>
                    <th>Calling date & Time</th>
                    <th>Cust id</th> 
                    <th>Mobile</th>
                    <th>Agent ID</th>
                    <th>Agent Name</th>
                    <th>Campaign Name</th>
                    <th>Disposition</th>
                    <th>Sub Disposition</th>
                    <th>Callback Datetime</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
            <?php if($dataProvider){
                foreach ($dataProvider as $key => $value) {
                    $connection = \Yii::$app->db; 
                    $data = $connection->createCommand('SELECT * FROM vaani_agent_call_report WHERE unique_id = "'.$value['unique_id'].'"')->queryAll();
                ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td><?= (isset($data[0]['insert_date'])? $data[0]['insert_date'] : '-') ?></td>
                    <td><?= (isset($value['vaani_lead_id']) ? $value['vaani_lead_id'] : '-') ?></td>
                    <td><?= (isset($data[0]['caller_id']) ? $data[0]['caller_id'] : '-') ?></td>
                    <td><?= (isset($value['agent_id']) ? $value['agent_id'] : '-') ?></td>
                    <td><?= (isset($data[0]['agent_name']) ? $data[0]['agent_name'] : '-') ?></td>
                    <td><?= (isset($data[0]['campaign_name']) ? $data[0]['campaign_name'] : '-') ?></td>
                    <td><?= (isset($value['disposition']) ? $value['disposition'] : '-') ?></td>
                    <td><?= (isset($value['sub_disposition']) ? $value['sub_disposition'] : '-') ?></td>
                    <td><?= (isset($value['callbackdatetime']) ? $value['callbackdatetime'] : '-') ?></td>
                    <td><?= (isset($value['remark']) ? $value['remark'] : '-') ?></td>
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