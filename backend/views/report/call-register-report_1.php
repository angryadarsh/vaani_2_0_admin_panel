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
            <h1 class="m-0 float-left">Call Register Report</h1>
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
                                    <?php foreach($report_columns as $column){?>
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
                                <?php if($dataProvider->query->all()){
                                    foreach ($dataProvider->query->asArray()->all() as $key => $value) {

                                        $call_type_arr = VaaniAgentLiveStatus::find()->where(['unique_id' => $value['unique_id']])->one();
                                        $agent_live_status = VaaniAgentLiveStatus::find()->where(['unique_id' => $model['unique_id']])->asArray()->one();


                                        $arr['date']=(isset($value['date']) ? $value['date'] : '-');
                                        $arr['Interval']=($times ? $times : (isset($value['Interval']) ? $value['Interval'] : '00:00-24:00'));
                                        $arr['CLI']=(isset($value['mobile_no']) ? $value['mobile_no'] : '-');
                                        $arr['Agent Id']=(isset($value['agent_id']) ? $value['agent_id'] : '-');
                                        $arr['Total Calls']=(isset($value['total_calls']) ? $value['total_calls'] : 0);
                                        $arr['Outbound Answered']=(isset($value['Outbound_Answered']) ? $value['Outbound_Answered'] : 0);
                                        $arr['Inbound Answered']=(isset($value['Inbound_Answered']) ? $value['Inbound_Answered'] : 0);
                                        $arr['Login Time']=(isset($value['Login_Time']) ? $value['Login_Time'] : '00:00:00');
                                        $arr['Not Ready']=(isset($value['Not Ready']) ? $value['Not Ready'] : '00:00:00');
                                        $arr['Idle Duration']=(isset($value['Idle Duration']) ? $value['Idle Duration'] : '00:00:00');
                                        $arr['Ring Duration']=(isset($value['Ring_Duration']) ? $value['Ring_Duration'] : '00:00:00');
                                        $arr['Talk Duration']=(isset($value['Talk_Duration']) ? $value['Talk_Duration'] : '00:00:00');
                                        $arr['Hold Duration']=(isset($value['Hold_Duration']) ? $value['Hold_Duration'] : '00:00:00');
                                        $arr['Wrap Up Duration']=(isset($value['Wrap_Up_Duration']) ? $value['Wrap_Up_Duration'] : '00:00:00');

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
         <?/*= DataTables::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                /* [
                    'label' => 'Lead ID',
                    'attribute' => 'id'
                ], */
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
                /* [
                    'label' => 'Extension'
                ], */
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
                /* [
                    'label' => 'Agent Remark'
                ], */
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
                /* [
                    'label' => 'IVR Terminal'
                ], */
                /* [
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
                ], */
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
                        /* "customize" => function($doc){
                            $doc->content->splice(0,1);
                            $doc->pageMargins = [20,60,20,30];
                            $doc->defaultStyle->fontSize = 12;
                            $doc->defaultStyle->alignment = "center";
                            $doc->styles->tableHeader->fontSize = 12;
                        } */
                    ]
                ],
            ],
        ]); */?> 
    </div>
</section>

<?php
$this->registerCss("
	.dataTables_length{
		margin-left : 2%
	}
");

$this->registerJs("
	$('table').addClass('table-responsive');
");
?>