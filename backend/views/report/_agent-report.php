<?php

/* @var $this yii\web\View */

use yii\bootstrap4\Html;
use yii\helpers\Url;
use fedemotta\datatables\DataTables;
use common\models\User;
use common\models\VaaniActiveStatus;
use common\models\VaaniAgentLiveStatus;
use common\models\VaaniSession;
use common\models\EdasCampaign;
use common\models\VaaniCampaignQueue;

$urlManager = Yii::$app->urlManager;

$todays_active = '';
$ready_active = '';
$active_active = '';
$wrap_active = '';
$not_ready_active = '';
$hold_active = '';
$total_active = '';

// echo $flag;exit;
if(isset($flag)){
    switch ($flag) {
        case 'todays':
            $todays_active = 'active';
            break;
        case 'ready':
            $ready_active = 'active';
            break;
        case 'active':
            $active_active = 'active';
            break;
        case 'wrap':
            $wrap_active = 'active';
            break;
        case 'not_ready':
            $not_ready_active = 'active';
            break;
        case 'hold':
            $hold_active = 'active';
            break;
        
        default:
            $total_active = 'active';
            break;
    }
}
?>

<div class="row campaign-stats">
    <div class="container-fluid">
        <div id="viewDetails" class="row">
            <?php if($campaign_type == EdasCampaign::TYPE_INBOUND || $campaign_type == EdasCampaign::TYPE_MANUAL){ ?>
                <div class="col-md-3 bordered-right">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-8 pr-0">
                                <span class="stats-label">Offered</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="font-md font-weight-bold"><?= $campaign_data['total_calls'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 bordered-right">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-8 pr-0">
                                <span class="stats-label">Answered</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="font-md font-weight-bold"><?= $campaign_data['answered_calls'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 bordered-right">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-8 pr-0">
                                <span class="stats-label">In Queue</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="font-md font-weight-bold"><?= $campaign_data['in_queue_calls'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-8 pr-0">
                                <span class="stats-label">In IVR</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="font-md font-weight-bold"><?= ($campaign_data['in_ivr_calls'] ? $campaign_data['in_ivr_calls'] : 0) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 bordered-right">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-8 pr-0">
                                <span class="stats-label">Abandoned</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="font-md font-weight-bold"><?= ($campaign_data['abandoned_calls'] ? $campaign_data['abandoned_calls'] : 0) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 bordered-right">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-8 pr-0">
                                <span class="stats-label">Average wait</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="font-md font-weight-bold"><?= round($campaign_data['average_wait'], 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 bordered-right">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-9 pr-0">
                                <span class="stats-label">Average wait(Dropped Calls)</span>
                            </div>
                            <div class="col-md-3 pl-0 text-right">
                                <span class="font-md font-weight-bold"><?= round($campaign_data['average_drop_wait'], 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-8 pr-0">
                                <span class="stats-label">Drop Percentage</span>
                            </div>
                            <div class="col-md-4 pl-0 text-right">
                                <span class="font-md font-weight-bold"><?= round($campaign_data['drop_percent'], 2) ?> %</span>
                            </div>
                        </div>
                    </div>
                </div>

            <?php }else if($campaign_type == EdasCampaign::TYPE_OUTBOUND){ ?>

                <div class="col-md-4 bordered-right">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-8 pr-0">
                                <span class="stats-label">Dialed</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="font-md font-weight-bold"><?= ($campaign_data['dailed_calls'] ? $campaign_data['dailed_calls'] : 0) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 bordered-right">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-8 pr-0">
                                <span class="stats-label">Connected</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="font-md font-weight-bold"><?= ($campaign_data['connected_calls'] ? $campaign_data['connected_calls'] : 0) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-8 pr-0">
                                <span class="stats-label">Abandoned</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="font-md font-weight-bold"><?= ($campaign_data['abandoned_calls'] ? $campaign_data['abandoned_calls'] : 0) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 bordered-right">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-8 pr-0">
                                <span class="stats-label">AHT</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="font-md font-weight-bold"><?= ($campaign_data['aht_calls'] ? $campaign_data['aht_calls'] : 0) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 bordered-right">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-8 pr-0">
                                <span class="stats-label">Connect Percentage</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="font-md font-weight-bold"><?= round($campaign_data['connected_percent'], 2) ?> %</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="campaign-stats-card">
                        <div class="row">
                            <div class="col-md-8 pr-0">
                                <span class="stats-label">Abandoned Percentage</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="font-md font-weight-bold"><?= round($campaign_data['abandoned_percent'], 2) ?> %</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php //}else if($campaign_type == EdasCampaign::TYPE_MANUAL){ ?>

            <?php } ?>
        </div>
    </div>
</div>


<div class="row monitoring-stats">
    <div class="col-md pl-1">
        <div class="row outb-m-content stats_count <?= $total_active ?>" data-flag="all">
            <div class="col-4 p-0">
                <div class="img-circle">
                    <img src="<?= Yii::$app->request->baseUrl . '/images/monitoring_icon/monitor-agent.png'?>" alt="">
                </div>
            </div>
            <div class="col-8 p-0">
                <div class="outb-m-data">
                    <h4>Agents</h4>
                    <h5><?= $total ?></h5>
                </div>
            </div>
            
        </div>
    </div>
    <div class="col-md">
        <div class="row outb-m-content stats_count  <?= $todays_active ?>" data-flag="todays">
            <div class="col-4 p-0">
                <div class="img-circle">
                    <img src="<?= Yii::$app->request->baseUrl . '/images/monitoring_icon/monitoring-today-call.png'?>" alt="">
                </div>
            </div>
            <div class="col-8 p-0">
                <div class="outb-m-data">
                    <h4>Today's Call</h4>
                    <h5><?= $today_calls ?></h5>
                </div>
            </div>
            
        </div>
    </div>
    <div class="col-md">
        <div class="row outb-m-content stats_count  <?= $ready_active ?>" data-flag="ready">
            <div class="col-4 p-0">
                <div class="img-circle">
                    <img src="<?= Yii::$app->request->baseUrl . '/images/monitoring_icon/monitoring-ready.png'?>" alt="">
                </div>
            </div>
            <div class="col-8 p-0">
                <div class="outb-m-data">
                    <h4>Ready</h4>
                    <h5><?= $ready ?></h5>
                </div>
            </div>
            
        </div>
    </div>
    <div class="col-md">
        <div class="row outb-m-content stats_count  <?= $active_active ?>" data-flag="active">
            <div class="col-4 p-0">
                <div class="img-circle">
                    <img src="<?= Yii::$app->request->baseUrl . '/images/monitoring_icon/monitoring-active.png'?>" alt="">
                </div>
            </div>
            <div class="col-8 p-0">
                <div class="outb-m-data">
                    <h4>Active</h4>
                    <h5><?= $active ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="row outb-m-content stats_count  <?= $wrap_active ?>" data-flag="wrap">
            <div class="col-4 p-0">
                <div class="img-circle">
                    <img src="<?= Yii::$app->request->baseUrl . '/images/monitoring_icon/monitoring-wrap.png'?>" alt="">
                </div>
            </div>
            <div class="col-8 p-0">
                <div class="outb-m-data">
                    <h4>Wrap</h4>
                    <h5><?= $wrap ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="row outb-m-content stats_count  <?= $not_ready_active ?>" data-flag="not_ready">
            <div class="col-4 p-0">
                <div class="img-circle">
                    <img src="<?= Yii::$app->request->baseUrl . '/images/monitoring_icon/monitoring-not-ready.png'?>" alt="">
                </div>
            </div>
            <div class="col-8 p-0">
                <div class="outb-m-data">
                    <h4>Not Ready</h4>
                    <h5><?= $not_ready ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="row outb-m-content stats_count  <?= $hold_active ?>" data-flag="hold">
            <div class="col-4 p-0">
                <div class="img-circle">
                    <img src="<?= Yii::$app->request->baseUrl . '/images/monitoring_icon/monitoring-hold.png'?>" alt="">
                </div>
            </div>
            <div class="col-8 p-0">
                <div class="outb-m-data">
                    <h4>hold</h4>
                    <h5><?= $hold ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- legends for incall actions highlight -->
<div class="note_section text-right mt-2">
    <span class="legend-label monitor_color"><span class="circle-badge"></span> Monitoring</span>
    <span class="legend-label whisper_color"><span class="circle-badge"></span> Whispering</span>
    <span class="legend-label barge_color"><span class="circle-badge"></span> Barge-In</span>
</div>

<div class="outbound-list">
    <?php if($dataProvider->query->all()){ ?>
        <?= DataTables::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                // ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'user_id',
                    'label' => 'Name',
                    'value' => function($model){
                        $user = User::findOne(['user_id' => $model->user_id]);
                        if($user) return $user->user_name . ' - ' . $model->user_id;
                    },
                    'headerOptions' => ['width' => '15%']
                ],
                [
                    'label' => 'Campaign',
                    'value' => function($model){
                        $session = VaaniSession::findOne(['unique_id' => $model->unique_id]);
                        $campaign = null;
                        if($session) $campaign = EdasCampaign::find()->where(['campaign_id' => $session->campaign])->one();
                        if($campaign) return $campaign->campaign_name;
                    },
                    'headerOptions' => ['width' => '15%']
                ],
                [
                    'attribute' => 'status',
                    'label' => 'Status',
                    'format' => 'html',
                    'value' => function($model) use ($sub_status_codes){
                        $result = '';
                        if($model->status) $result = VaaniActiveStatus::$statuses[$model->status];
                        if(isset($sub_status_codes[$model->user_id])) $result .= (strtolower($sub_status_codes[$model->user_id]) == 'wrap' ? ucwords($sub_status_codes[$model->user_id]) : (' - ' . ucwords($sub_status_codes[$model->user_id])));

                        if($model->status == VaaniActiveStatus::STATUS_INCALL){
                            $on_going_call = VaaniAgentLiveStatus::find()->where(['agent_id' => $model->user_id])->andWhere(['and', ['>=', 'datetime', date('Y-m-d 00:00:00')], ['<=', 'datetime', date('Y-m-d 23:59:59')]])->andWhere(['IN', 'status', ['INCALL', 'ANSWERED', 'PAUSE']])->one();
                            if($on_going_call){
                                $queue = VaaniCampaignQueue::find()->where(['queue' => $on_going_call->queue_name])->one();
                                if($queue)
                                    $result .= ' ( ' . $queue->queue_name . ' ) ';

                                if($on_going_call->call_type == VaaniAgentLiveStatus::STATUS_MANUAL){
                                    $result .= "<i class='fa fa-user' title='Manual'></i>";
                                
                                } else if($on_going_call->call_type == VaaniAgentLiveStatus::STATUS_INBOUND){
                                    $result .= "<i class='fa fa-arrow-down' title='Inbound'></i>";
                                
                                } else if($on_going_call->call_type == VaaniAgentLiveStatus::STATUS_OUTBOUND){
                                    $result .= "<i class='fa fa-arrow-up' title='Outbound'></i>";

                                } else if($on_going_call->call_type == VaaniAgentLiveStatus::STATUS_TRANSFER){
                                    $result .= "<i class='fa fa-user-plus' title='Transfer'></i>";

                                } else if($on_going_call->call_type == VaaniAgentLiveStatus::STATUS_CONSULT){
                                    $result .= "<i class='fa fa-user-secret' title='Consult'></i>";
                                
                                } else if($on_going_call->call_type == VaaniAgentLiveStatus::STATUS_CONFERENCE){
                                    $result .= "<i class='fa fa-users' title='Conference'></i>";
                                
                                }
                            }
                        }

                        return $result;
                    },
                    'headerOptions' => ['width' => '20%']
                ],
                [
                    'attribute' => 'status_start_datetime',
                    'label' => 'Duration',
                    'value' => function($model) use ($sub_status_duration){
                        if(isset($sub_status_duration[$model->user_id])){
                            return $sub_status_duration[$model->user_id];
                        }else if($model->status_start_datetime){
                            $current = date('Y-m-d H:i:s');
                            $start_datetime = $model->status_start_datetime;
                            $diff = strtotime($current) - strtotime($start_datetime);
                            return gmdate('H:i:s', ($diff));
                        }
                    },
                    'headerOptions' => ['width' => '10%']
                ],
                [
                    'label' => 'Total Calls',
                    'format' => 'raw',
                    'value' => function($model){
                        if($model->user_id){
                            // $calls = VaaniAgentLiveStatus::find()->where(['agent_id' => $model->user_id])->andWhere(['and', ['>=', 'datetime', date('Y-m-d 00:00:00')], ['<=', 'datetime', date('Y-m-d 23:59:59')]])->all();
                            $result = count($model->agentCalls);
                            return $result;
                        }
                    },
                    'headerOptions' => ['width' => '10%']
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Action',
                    'headerOptions' => ['width' => '10%'],
                    'template' => '{display} ',
                    'buttons' => [
                        'display' => function ($url, $model) {
                            $agent_live_status = VaaniAgentLiveStatus::find()->where(['agent_id' => $model->user_id])->andWhere(['and', ['>=', 'datetime', date('Y-m-d 00:00:00')], ['<=', 'datetime', date('Y-m-d 23:59:59')]])->andWhere(['IN', 'status', ['INCALL', 'ANSWERED', 'PAUSE', 'DISPO']])->orderBy('id DESC')->one();

                            $unique_id = ($agent_live_status ? $agent_live_status->unique_id : "");

                            return Html::a('<span class="fa fa-eye"></span>', null, [
                                'class' => 'incall_action',
                                'data-user' => $model->user_id,
                                'data-unique_id' => $unique_id,
                                'title' => 'Monitor',
                                ]) . Html::a('<span class="fa fa-eye-slash"></span>', null, [
                                'class' => 'incall_action_stop hidden',
                                'data-user' => $model->user_id,
                                'data-unique_id' => $unique_id,
                                'title' => 'Stop'
                            ]);
                        },
                        'report' => function($url, $model){
                            return Html::a('<span class="fas fa-file-alt" title="View Call Register Report"></span>', ['report/call-register-report', 'id' => $model->user_id], ['target' => '_blank']);
                        }
                    ],
                    'visibleButtons' => [
                        'display' => function ($model) use ($sub_status_codes) {
                            $result = '';
                            $sub_result = '';
                            if($model->status) $result = VaaniActiveStatus::$statuses[$model->status];
                            if(isset($sub_status_codes[$model->user_id])) $sub_result = $sub_status_codes[$model->user_id];

                            return (($result == "In Call" && $sub_result != "Ringing") ? true : false);
                        }
                    ]
                ],
            ],
            'rowOptions' => function ($model, $key, $index, $grid) {
                return ['data-id' => $model->user_id, 'data-unique-id' => $model->unique_id];
            },
            'clientOptions' => [
                "lengthMenu" => [[10, 20, 50, -1], [10, 20, 50, "All"]],
                "responsive"=>true, 
                "dom"=> 'lBfrtip',
                "buttons"=> [  
                    'copy', 'excel', 'csv', 'pdf', 'print'
                ]
            ],
        ]); ?>
    <?php } ?>
</div>
