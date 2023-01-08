<?php

/* @var $this yii\web\View */
/* @var $model \common\models\InboundEdas */

use yii\bootstrap4\Html;
use fedemotta\datatables\DataTables;

$this->title = 'ACD Report';
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left">ACD Report</h1>
            <div class="float-right">
                <?= Html::a('Back', ['acd-report'], ['class' => 'btn btn-primary']) ?>
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
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="report_data" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <?php 
                                    if(empty($report_columns)){
                                        $report_columns = ['SR'=>'SR',
                                            'Date'=>'Date',
                                            'Service'=>'Service',
                                            'Interval'=>'Interval',
                                            'Offered'=>'Offered',
                                            'Agent'=>'Agent',
                                            'Answered On Agent'=>'Answered On Agent',
                                            'Answered 10 Sec'=>'Answered 10 Sec',
                                            'Answered 20 Sec'=>'Answered 20 Sec',
                                            'Answered 30 Sec'=>'Answered 30 Sec',
                                            'Answered 40 Sec'=>'Answered 40 Sec',
                                            'Answered 50 Sec'=>'Answered 50 Sec',
                                            'Answered 60 Sec'=>'Answered 60 Sec',
                                            'Answered 90 Sec'=>'Answered 90 Sec',
                                            'Answered 120 Sec'=>'Answered 120 Sec',
                                            'Answered > 120 Sec'=>'Answered > 120 Sec',
                                            'Calls In Queue'=>'Calls In Queue',
                                            'Talk Time'=>'Talk Time',
                                            'Wrapup Time'=>'Wrapup Time',
                                            'Hold Time'=>'Hold Time',
                                            'Call Abandoned'=>'Call Abandoned',
                                            'Abandoned On Ivr'=>'Abandoned On Ivr',
                                            'Abandoned On Agent'=>'Abandoned On Agent',
                                            'Abandoned 10 Sec'=>'Abandoned 10 Sec',
                                            'Abandoned 20 Sec'=>'Abandoned 20 Sec',
                                            'Abandoned 30 Sec'=>'Abandoned 30 Sec',
                                            'Abandoned 40 Sec'=>'Abandoned 40 Sec',
                                            'Abandoned 50 Sec'=>'Abandoned 50 Sec',
                                            'Abandoned 60 Sec'=>'Abandoned 60 Sec',
                                            'Abandoned 90 Sec'=>'Abandoned 90 Sec',
                                            'Abandoned 120 Sec'=>'Abandoned 120 Sec',
                                            'Abandoned > 120 Sec'=>'Abandoned > 120 Sec',
                                            'Answered Percent'=>'Answered Percent',
                                            'Abandoned Percent'=>'Abandoned Percent',
                                        ];
                                    }

                                    foreach($report_columns as $column){?>
                                    <th><?= $column ?></th>
                                    <?php }?>

                                    <!-- <th>#</th>
                                    <th>Date</th>
                                    <th>Service</th>
                                    <th>Interval</th> 
                                    <th>Offered</th>
                                    <th>Agent</th> -->
                                    <!-- <th>Offered On Agent</th> -->
                                    <!-- <th>Answered On Agent</th>
                                    <th>Answered 10 Sec</th>
                                    <th>Answered 20 Sec</th>
                                    <th>Answered 30 Sec</th>
                                    <th>Answered 40 Sec</th>
                                    <th>Answered 50 Sec</th>
                                    <th>Answered 60 Sec</th>
                                    <th>Answered 90 Sec</th>
                                    <th>Answered 120 Sec</th>
                                    <th>Answered > 120 Sec</th>
                                    <th>Calls In Queue</th>
                                    <th>Talk Time</th>
                                    <th>Wrapup Time</th>
                                    <th>Hold Time</th>
                                    <th>Call Abandoned</th>
                                    <th>Abandoned On Ivr</th>
                                    <th>Abandoned On Agent</th>
                                    <th>Abandoned 10 Sec</th>
                                    <th>Abandoned 20 Sec</th>
                                    <th>Abandoned 30 Sec</th>
                                    <th>Abandoned 40 Sec</th>
                                    <th>Abandoned 50 Sec</th>
                                    <th>Abandoned 60 Sec</th>
                                    <th>Abandoned 90 Sec</th>
                                    <th>Abandoned 120 Sec</th>
                                    <th>Abandoned > 120 Sec</th>
                                    <th>Answered Percent</th>
                                    <th>Abandoned Percent</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($data){
                                    foreach ($data as $key => $value) {
                                        
                                        $arr['SR']=($key+1);
                                        $arr['Date']=(isset($value['date']) ? $value['date'] : '-');
                                        $arr['Service']=(isset($value['queue']) ? $value['queue'] : '-');
                                        $arr['Interval']=(isset($value['report_interval']) ? $value['report_interval'] : '00:00-24:00');
                                        $arr['Offered']=(isset($value['offered']) ? $value['offered'] : '-');
                                        $arr['Agent']=(isset($value['agent_count']) ? $value['agent_count'] : '-');
                                        $arr['Answered On Agent']=(isset($value['agent_offered']) ? $value['agent_offered'] : 0);
                                        $arr['Answered 10 Sec']=(isset($value['answered_in_10_sec']) ? $value['answered_in_10_sec'] : 0);
                                        $arr['Answered 20 Sec']=(isset($value['answered_in_20_sec']) ? $value['answered_in_20_sec'] : 0);
                                        $arr['Answered 30 Sec']=(isset($value['answered_in_30_sec']) ? $value['answered_in_30_sec'] : 0);
                                        $arr['Answered 40 Sec']=(isset($value['answered_in_40_sec']) ? $value['answered_in_40_sec'] : 0);
                                        $arr['Answered 50 Sec']=(isset($value['answered_in_50_sec']) ? $value['answered_in_50_sec'] : 0);
                                        $arr['Answered 60 Sec']=(isset($value['answered_in_60_sec']) ? $value['answered_in_60_sec'] : 0);
                                        $arr['Answered 90 Sec']=(isset($value['answered_in_90_sec']) ? $value['answered_in_90_sec'] : 0);
                                        $arr['Answered 120 Sec']=(isset($value['answered_in_120_sec']) ? $value['answered_in_120_sec'] : 0);
                                        $arr['Answered > 120 Sec']=(isset($value['answered_>_120_sec']) ? $value['answered_>_120_sec'] : 0);
                                        $arr['Calls In Queue']=(isset($value['calls_in_queue']) ? $value['calls_in_queue'] : 0);
                                        $arr['Talk Time']=(isset($value['talk_time']) ? $value['talk_time'] : 0);
                                        $arr['Wrapup Time']=(isset($value['wrap_time']) ? $value['wrap_time'] : 0);
                                        $arr['Hold Time']=(isset($value['hold_time']) ? $value['hold_time'] : 0);
                                        $arr['Call Abandoned']=(isset($value['call_abandoned']) ? $value['call_abandoned'] : 0);
                                        $arr['Abandoned On Ivr']=(isset($value['abandoned_on_ivr']) ? $value['abandoned_on_ivr'] : 0);
                                        $arr['Abandoned On Agent']=(isset($value['abandoned_on_agent']) ? $value['abandoned_on_agent'] : 0);
                                        $arr['Abandoned 10 Sec']=(isset($value['abandoned_in_10_sec']) ? $value['abandoned_in_10_sec'] : 0);
                                        $arr['Abandoned 20 Sec']=(isset($value['abandoned_in_20_sec']) ? $value['abandoned_in_20_sec'] : 0);
                                        $arr['Abandoned 30 Sec']=(isset($value['abandoned_in_30_sec']) ? $value['abandoned_in_30_sec'] : 0);
                                        $arr['Abandoned 40 Sec']=(isset($value['abandoned_in_40_sec']) ? $value['abandoned_in_40_sec'] : 0);
                                        $arr['Abandoned 50 Sec']=(isset($value['abandoned_in_50_sec']) ? $value['abandoned_in_50_sec'] : 0);
                                        $arr['Abandoned 60 Sec']=(isset($value['abandoned_in_60_sec']) ? $value['abandoned_in_60_sec'] : 0);
                                        $arr['Abandoned 90 Sec']=(isset($value['abandoned_in_90_sec']) ? $value['abandoned_in_90_sec'] : 0);
                                        $arr['Abandoned 120 Sec']=(isset($value['abandoned_in_120_sec']) ? $value['abandoned_in_120_sec'] : 0);
                                        $arr['Abandoned > 120 Sec']=(isset($value['abandoned_>_120_sec']) ? $value['abandoned_>_120_sec'] : 0);
                                        $arr['Answered Percent']=(isset($value['answered_percent']) ? round($value['answered_percent'], 2) : 0);
                                        $arr['Abandoned Percent']=(isset($value['abandoned_percent']) ? round($value['abandoned_percent'], 2) : 0);

                                        ?>
                                        <tr>
                                            <?php 
                                                $html ='';
                                                foreach($report_columns as $column){
                                                    $html .="<td>".$arr[$column]."</td>";
                                                
                                                }
                                                echo $html;
                                            ?>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
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