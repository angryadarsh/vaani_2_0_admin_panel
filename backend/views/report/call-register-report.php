<?php

/* @var $this yii\web\View */
/* @var $model \common\models\InboundEdas */

use yii\bootstrap4\Html;
use fedemotta\datatables\DataTables;
use common\models\User;
use common\models\VaaniActiveStatus;
use common\models\VaaniAgentLiveStatus;
use common\models\VaaniAgentCallMaster;
use common\models\VaaniSession;
use common\models\EdasCampaign;

$this->title = 'Call Register Report';
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="float-left">Call Register Report</h1>
            <div class="float-right">
                <?= Html::a('Back', ['call-register-report'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="call_register_report_data" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <?php 
                                    if(empty($report_columns))
                                    {
                                        $report_columns = ['Sr'=>'Sr',
                                                        'Date'=>'Date',
                                                        'Interval'=>'Interval',
                                                        'CLI'=>'CLI',
                                                        'Service'=>'Service',
                                                        'Call Type'=>'Call Type',
                                                        'Call Status'=>'Call Status',
                                                        'Agent Id'=>'Agent Id',
                                                        'Agent'=>'Agent',
                                                        'Start Time'=>'Start Time',
                                                        'End Time'=>'End Time',
                                                        // 'DNI'=>'DNI',
                                                        'Disposition'=>'Disposition',
                                                        'Duration'=>'Duration',
                                                        'Hold Duration'=>'Hold Duration',
                                                        'Ring Duration'=>'Ring Duration',
                                                        'Talk Duration'=>'Talk Duration',
                                                        'Wrap Duration'=>'Wrap Duration',
                                                        'Recording'=>'Recording',
                                                    ];
                                    }
                                    
                                    foreach($report_columns as $column){?>
                                    <th><?= $column ?></th>
                                    <?php }?>

                                    <!-- <th>#</th>
                                    <th>Date</th>
                                    <th>Interval</th> 
                                    <th>CLI</th>
                                    <th>Service</th>
                                    <th>Call Type</th>
                                    <th>Call Status</th>
                                    <th>Agent</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>DNI</th>
                                    <th>Disposition</th>
                                    <th>Duration</th>
                                    <th>Hold Duration</th>
                                    <th>Ring Duration</th>
                                    <th>Talk Duration</th>
                                    <th>Wrap Duration</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($data){
                                    foreach ($data as $key => $model) {

                                        // fetch recording path
                                        if($model['recording_path']){
                                            $recording_path = explode('html', $model['recording_path']);
                                            $recording_path = ($recording_path && isset($recording_path[1]) ? $recording_path[1] : '-');
                                        }

                                        // $user = User::findOne(['user_id' => $model['agent_id']]);
                                        /* $user_name = (isset($model['agent_name']) ? ($model['agent_name']) : '-');
                                        
                                        $duration = strtotime(date('H:i:s', strtotime($model['end_time']))) - strtotime($model['time']);

                                        $call_type = null;
                                        $ring_duration = '00:00:00';
                                        
                                        $call_type = (($model['call_type'] && isset(VaaniAgentLiveStatus::$call_types[$model['call_type']])) ? VaaniAgentLiveStatus::$call_types[$model['call_type']] : '-');

                                        if($call_type == 'Inbound'){
                                            $ring_duration = '00:00:00';
                                        }else{
                                            $ring_duration = (isset($model['ring_duration']) ? gmdate("H:i:s", $model['ring_duration']) : '00:00:00');
                                        }

                                        $queue_hold_time = 0;
                                        if(isset($model['queue_hold_time'])){
                                            $queue_hold_time = $model['queue_hold_time'];
                                        }
                                        $talk_data = strtotime(date('H:i:s', strtotime($model['end_time']))) - strtotime($model['time']);
                                        
                                        $wrap_data = VaaniAgentCallMaster::find()->where(['unique_id' => $model['unique_id']])->andWhere(['call_action' => 'wrap'])->one(); */

                                        $arr['Sr'] = $key+1;
                                        $arr['Date'] = (isset($model['date']) ? $model['date'] : '-');
                                        $arr['Interval'] = (isset($model['report_interval']) ? $model['report_interval'] : '-');
                                        $arr['CLI'] = ($model['cli'] ? substr($model['cli'], -10) : '-');
                                        $arr['Service'] = (isset($model['queue']) ? $model['queue'] : '-');

                                        $arr['Call Type'] = (($model['call_type'] && isset(VaaniAgentLiveStatus::$call_types[$model['call_type']])) ? VaaniAgentLiveStatus::$call_types[$model['call_type']] : '-');

                                        $arr['Call Status'] = (isset($model['call_status']) ? $model['call_status'] : 'ANSWERED');
                                        $arr['Agent Id'] = (isset($model['agent_id']) ? $model['agent_id'] : '-');
                                        $arr['Agent'] = (isset($model['agent_name']) ? $model['agent_name'] : '-');
                                        $arr['Start Time'] = ($model['start_time'] ? date('H:i:s', strtotime($model['start_time'])) : '-');
                                        $arr['End Time'] = ($model['end_time'] ? date('H:i:s', strtotime($model['end_time'])) : '-');
                                        $arr['Disposition'] = (isset($model['disposition']) && $model['disposition'] ? $model['disposition'] : 'System Dispo');
                                        
                                        $arr['Duration'] = (isset($model['duration']) ? $model['duration'] : '00:00:00');
                                        $arr['Hold Duration'] = (isset($model['hold_duration']) ? $model['hold_duration'] : '00:00:00');
                                        $arr['Ring Duration'] = (isset($model['ring_duration']) ? $model['ring_duration'] : '00:00:00');
                                        $arr['Talk Duration'] = (isset($model['talk_duration']) ? $model['talk_duration'] : '00:00:00');
                                        $arr['Wrap Duration'] = (isset($model['wrap_duration']) ? $model['wrap_duration'] : '00:00:00');
                                        $arr['Recording'] = ((isset($model['recording_path']) && $model['recording_path']) ? ("
                                            <audio controls>
                                                <source src='". $recording_path.".wav' type='audio/wav'>
                                                Your browser does not support the audio element.
                                            </audio>
                                        ") : "-");
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
                                <?php } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php /* DataTables::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                // [
                //     'label' => 'Lead ID',
                //     'attribute' => 'id'
                // ],
                'date',
                [
                    'label' => 'Interval',
                    'value' => function($model){
                        // return ($model['time'] ? date('h A', strtotime($model['time'])) : '-');
                        return '00:00 - 24:00';
                    }
                ],
                [
                    'attribute' => 'mobile_no',
                    'label' => 'CLI',
                    'value' => function($model){
                        return ($model['mobile_no'] ? substr($model['mobile_no'], -10) : '-');
                    }
                ],
                [
                    'attribute' => 'queue',
                    'label' => 'Service',
                    'value' => function($model){
                        return (isset($model['queue']) ? $model['queue'] : (isset($model['queue_name']) ? $model['queue_name'] : '-'));
                    }
                ],
                [
                    'label' => 'Call Type',
                    'value' => function($model){
                        $data = VaaniAgentLiveStatus::find()->where(['unique_id' => $model['unique_id']])->one();
                        
                        return (($data && $data->call_type && isset(VaaniAgentLiveStatus::$call_types[$data->call_type])) ? VaaniAgentLiveStatus::$call_types[$data->call_type] : '-');
                    }
                ],
                [
                    'label' => 'Call Status',
                    'value' => function($model){
                        return (isset($model['status']) ? $model['status'] : 'ANSWERED');
                    }
                ],
                [
                    'attribute' => 'agent_id',
                    'label' => 'Agent',
                    'value' => function($model){
                        $user = User::findOne(['user_id' => $model['agent_id']]);
                        return ($user ? $user->user_name : '-');
                    }
                ],
                [
                    'attribute' => 'time',
                    'label' => 'Start Time',
                    'value' => function($model){
                        return date('H:i:s', strtotime($model['time']));
                    }
                ],
                [
                    'attribute' => 'end_time',
                    'label' => 'End Time',
                    'value' => function($model){
                        return date('H:i:s', strtotime($model['end_time']));
                    }
                ],
                // [
                //     'label' => 'Extension'
                // ],
                [
                    'label' => 'DNI',
                    'attribute' => 'dni_number',
                    'value' => function($model){
                        return (isset($model['dni_number']) ? $model['dni_number'] : '-');
                    }
                ],
                [
                    'label' => 'Disposition',
                    'value' => function($model){
                        $agent_live_status = VaaniAgentLiveStatus::find()->where(['unique_id' => $model['unique_id']])->asArray()->one();
                        
                        return (isset($agent_live_status['disposition']) && $agent_live_status['disposition'] ? $agent_live_status['disposition'] : 'System Dispo');
                    }
                ],
                // [
                //     'label' => 'Agent Remark'
                // ],
                [
                    'label' => 'Duration',
                    'value' => function($model){
                        $diff = strtotime(date('H:i:s', strtotime($model['end_time']))) - strtotime($model['time']);
                        return gmdate('H:i:s', $diff);
                    }
                ],
                [
                    'label' => 'Hold Duration',
                    'value' => function($model){
                        return (isset($model['queue_hold_time']) ? gmdate("H:i:s", $model['queue_hold_time']) : '00:00:00');
                    }
                ],
                [
                    'label' => 'Ring Duration',
                    'value' => function($model){
                        $call_type = null;
                        $data = VaaniAgentLiveStatus::find()->where(['unique_id' => $model['unique_id']])->one();
                        
                        if($data) $call_type = (($data->call_type && isset(VaaniAgentLiveStatus::$call_types[$data->call_type])) ? VaaniAgentLiveStatus::$call_types[$data->call_type] : '-');

                        if($call_type == 'Inbound'){
                            return '00:00:00';
                        }else{
                            return (isset($model['ring_duration']) ? gmdate("H:i:s", $model['ring_duration']) : '00:00:00');
                        }
                    }
                ],
                [
                    'label' => 'Talk Duration',
                    'value' => function($model){
                        $queue_hold_time = 0;
                        if(isset($model['queue_hold_time'])){
                            $queue_hold_time = $model['queue_hold_time'];
                        }
                        $diff = strtotime(date('H:i:s', strtotime($model['end_time']))) - strtotime($model['time']);
                        return ($diff > 0 ? gmdate("H:i:s", ($diff - $queue_hold_time)) : '00:00:00');
                        return '00:00:00';
                    }
                ],
                [
                    'label' => 'Wrap Duration',
                    'value' => function($model){
                        $wrap_data = VaaniAgentCallMaster::find()->where(['unique_id' => $model['unique_id']])->andWhere(['call_action' => 'wrap'])->one();
                        
                        return (isset($wrap_data) ? gmdate("H:i:s", $wrap_data->action_duration) : '00:00:00');
                    }
                ],
                // [
                //     'label' => 'IVR Terminal'
                // ],
                [
                    'label' => 'Recording',
                    'format' => 'raw',
                    'value' => function($model){
                        if(isset($model['recording_path'])){
                            return '
                                <audio controls>
                                    <source src="'.Yii::$app->params['recordings_path'] . $model['recording_path'].'.wav" type="audio/wav">
                                    Your browser does not support the audio element.
                                </audio>
                            ';
                        }
                        return '-';
                    }
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
                        // "customize" => function($doc){
                        //     $doc->content->splice(0,1);
                        //     $doc->pageMargins = [20,60,20,30];
                        //     $doc->defaultStyle->fontSize = 12;
                        //     $doc->defaultStyle->alignment = "center";
                        //     $doc->styles->tableHeader->fontSize = 12;
                        // }
                    ]
                ],
            ],
        ]); */ ?>
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
        $('#call_register_report_data').DataTable( {
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