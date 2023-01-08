<?php

/* @var $this yii\web\View */
/* @var $model \common\models\InboundEdas */

use yii\bootstrap4\Html;
use fedemotta\datatables\DataTables;

$this->title = 'Agent Performance Report';
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left">Agent Performance Report</h1>
            <div class="float-right">
                <?= Html::a('Back', ['agent-performance-report'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6"><b>Dates :</b> <?= $start_date . " - " . $end_date ?></div>
                            <div class="col-lg-6"><b>Campaigns :</b> <?= implode(", ", $campaign_list) ?></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="report_data" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <?php 
                                    if(empty($report_columns)){
                                        $report_columns = ['Date'=>'Date',
                                                    'Interval'=>'Interval',
                                                    'Agent'=>'Agent',
                                                    'Agent Id'=>'Agent Id',
                                                    'Total Calls'=>'Total Calls',
                                                    'Outbound Answered'=>'Outbound Answered',
                                                    'Inbound Answered'=>'Inbound Answered',
                                                    'Login Time'=>'Login Time',
                                                    'Not Ready'=>'Not Ready',
                                                    'Idle Duration'=>'Idle Duration',
                                                    'Ring Duration'=>'Ring Duration',
                                                    'Talk Duration'=>'Talk Duration',
                                                    'Hold Duration'=>'Hold Duration',
                                                    'Wrap Up Duration'=>'Wrap Up Duration'];
                                    }
                                    
                                    
                                    foreach($report_columns as $column){?>
                                    <th><?= $column ?></th>
                                    <?php }?>
                                    
                                    <!-- <th>Date</th>
                                    <th>Interval</th> 
                                    <th>Agent</th>
                                    <th>Agent Id</th>
                                    <th>Total Calls</th>
                                    <th>Outbound Answered</th>
                                    <th>Inbound Answered</th>
                                    <th>Login Time</th>
                                    <th>Not Ready</th>
                                    <th>Idle Duration</th>
                                    <th>Ring Duration</th>
                                    <th>Talk Duration</th>
                                    <th>Hold Duration</th>
                                    <th>Wrap Up Duration</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($data){
                                    foreach ($data as $key => $value) {
                                        // $value['Login_Time'] = (isset($login_data[$value['agent_id']]) ? $login_data[$value['agent_id']] : '00:00:00');
                                        // $value['Idle Duration'] = (isset($idle_data[$value['agent_id']]) ? $idle_data[$value['agent_id']] : '00:00:00');
                                        /* $sum_login = null;
                                        $sum_login = (strtotime($value['Not Ready']) - strtotime('00:00:00')) + 
                                        (strtotime($value['Idle Duration']) - strtotime('00:00:00')) + 
                                        (strtotime($value['Ring_Duration']) - strtotime('00:00:00')) + 
                                        (strtotime($value['Talk_Duration']) - strtotime('00:00:00')) + 
                                        (strtotime($value['Hold_Duration']) - strtotime('00:00:00')) + 
                                        (strtotime($value['Wrap_Up_Duration']) - strtotime('00:00:00'));

                                        $sum_login_val = gmdate("H:i:s", $sum_login);

                                        if($sum_login_val != $value['Login_Time']){
                                            $add_idle = abs($sum_login - 
                                            (strtotime($value['Login_Time']) - strtotime('00:00:00')));

                                            $idle_duration = (strtotime($value['Idle Duration']) - strtotime('00:00:00')) + $add_idle;

                                            $value['Idle Duration'] = gmdate("H:i:s", $idle_duration);

                                        } */
                                        // $arr['Interval']=($times ? $times : (isset($value['Interval']) ? $value['Interval'] : '00:00-24:00'));
                                        $arr['Date']=(isset($value['date']) ? $value['date'] : '-');
                                        $arr['Interval']=(isset($value['interval']) ? $value['interval'] : '-');
                                        $arr['Agent']=(isset($value['agent_name']) ? $value['agent_name'] : '-');
                                        $arr['Agent Id']=(isset($value['agent_id']) ? $value['agent_id'] : '-');
                                        $arr['Total Calls']=(isset($value['total_calls']) ? $value['total_calls'] : 0);
                                        $arr['Outbound Answered']=(isset($value['outbound_answered']) ? $value['outbound_answered'] : 0);
                                        $arr['Inbound Answered']=(isset($value['inbound_answered']) ? $value['inbound_answered'] : 0);
                                        $arr['Login Time']=(isset($value['login_time']) ? $value['login_time'] : '00:00:00');
                                        $arr['Not Ready']=(isset($value['not_ready']) ? $value['not_ready'] : '00:00:00');
                                        $arr['Idle Duration']=(isset($value['idle_duration']) ? $value['idle_duration'] : '00:00:00');
                                        $arr['Ring Duration']=(isset($value['ring_duration']) ? $value['ring_duration'] : '00:00:00');
                                        $arr['Talk Duration']=(isset($value['talk_duration']) ? $value['talk_duration'] : '00:00:00');
                                        $arr['Hold Duration']=(isset($value['hold_duration']) ? $value['hold_duration'] : '00:00:00');
                                        $arr['Wrap Up Duration']=(isset($value['wrap_up_duration']) ? $value['wrap_up_duration'] : '00:00:00');

                                        ?>
                                        <tr>
                                                <?php 
                                                $html ='';
                                                foreach($report_columns as $column){
                                                    // if($column == 'Date'){
                                                        $html .="<td>".$arr[$column]."</td>";
                                                    // }?>
                                                    
                                                
                                                <?php }
                                                echo $html;
                                                ?>
                                            
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                            <?php /* DataTables::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    [
                                        'label' => 'Date'
                                    ],
                                    [
                                        'label' => 'Interval'
                                    ],
                                    [
                                        'label' => 'Agent',
                                        'attribute' => 'user_name'
                                    ],
                                    [
                                        'label' => 'Agent Id',
                                        'attribute' => 'user_id'
                                    ],
                                    [
                                        'label' => 'Total Call'
                                    ],
                                    [
                                        'label' => 'Outbound Answered'
                                    ],
                                    [
                                        'label' => 'Inbound Answered'
                                    ],
                                    [
                                        'label' => 'Login Time'
                                    ],
                                    [
                                        'label' => 'Lunch'
                                    ],
                                    [
                                        'label' => 'Dinner'
                                    ],
                                    [
                                        'label' => 'Tea'
                                    ],
                                    [
                                        'label' => 'Meeting'
                                    ],
                                    [
                                        'label' => 'Breaks'
                                    ],
                                    [
                                        'label' => 'Bio'
                                    ],
                                    [
                                        'label' => 'Email'
                                    ],
                                    [
                                        'label' => 'TLSession'
                                    ],
                                    [
                                        'label' => 'QASession'
                                    ],
                                    [
                                        'label' => 'Training'
                                    ],
                                    [
                                        'label' => 'Total Break'
                                    ],
                                    [
                                        'label' => 'Idle Duration',
                                    ],
                                    [
                                        'label' => 'Ring Duration',
                                    ],
                                    [
                                        'label' => 'Talk Duration',
                                    ],
                                    [
                                        'label' => 'Hold Duration',
                                    ],
                                    [
                                        'label' => 'Wrap Up Duration',
                                    ],
                                ],
                                'clientOptions' => [
                                    "lengthMenu" => [[10, 20, 50, -1], [10, 20, 50, 'All']],
                                    "responsive"=>true, 
                                    "dom"=> 'Bflrtip',
                                    "buttons"=> [  
                                        'copy', 'excel', 'csv', 'print',
                                        [
                                            "text" => "PDF",
                                            "extend" => "pdfHtml5",
                                            "orientation" => "landscape", //portrait
                                            "pageSize" => "A3",
                                            "exportOptions" => [
                                                "columns" => ":visible",
                                                "search" => "applied",
                                                "order" => "applied"
                                            ],
                                        ]
                                    ],
                                ],
                            ]); */ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$this->registerCss("
	.dataTables_length{
		margin-left : 2%
	}
    table {
        overflow: auto!important;
    }
");

$this->registerJs("
	$('table').addClass('table-responsive');
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