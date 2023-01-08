<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use common\models\InboundEdas;
use common\models\search\InboundEdasSearch;
use common\models\search\VaaniSessionLogSearch;
use common\models\search\ManualDialDetailSearch;
use common\models\search\UserSearch;
use common\models\VaaniActiveStatus;
use common\models\VaaniSessionLog;
use common\models\EdasCampaign;
use common\models\VaaniAgentLiveStatus;
use common\models\ManualDialDetail;
use common\models\VaaniSupervisorDetail;
use common\models\VaaniCampaignQueue;
use common\models\VaaniClientMaster;
use common\models\Api;
use common\models\User;
use common\models\VaaniNetworkLog;
use common\models\search\NetworkLogSearch;
use yii\helpers\ArrayHelper;
use common\models\EdasDniMaster;
use common\models\VaaniUserAccess;
use common\models\VaaniUserReportColumns;
use yii\db\Expression;
use common\models\search\VaaniActiveStatusSearch;
use common\models\VaaniRole;
use common\models\VaaniPreviewDialDetail;
use common\models\VaaniPredictiveDialDetail;
use common\models\VaaniProgressiveDialDetail;
use common\models\VaaniCallRecordings;
use common\models\search\VaaniSessionSearch;
use common\models\VaaniAgentAuditSheet;
use common\models\search\VaaniAgentAuditSheetSearch;
use yii\db\Query; 

/**
 * ReportController implements the all the report actions.
 */
class ReportController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Monitoring action.
     * $flag (status values from VaaniActiveStatus::$statuses)
     * @return Response
     */
    public function actionMonitoring($flag=null)
    {
        // fetch today's call count
        $todays_start_time = date('Y-m-d').' 00:00:00';
        $todays_end_time = date('Y-m-d H:i:s');

        return $this->render('monitoring');
    }

    public function actionGetAgentReport()
    {
        $user = Yii::$app->user->identity;

        $campaign_type = Yii::$app->request->post('campaign_type');
        $campaign_id = Yii::$app->request->post('campaign_id');
        $queue_id = Yii::$app->request->post('queue_id');
        $flag = Yii::$app->request->post('flag');

        if((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') && !$campaign_id){
            $campaign_id = $user->campaignIds;
        }

        $campaign = [];
        $campaign_ids = null;
        if($campaign_id || (($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin')){
            $campaignModels = EdasCampaign::find()->where(['campaign_id' => $campaign_id])->all();
        }else{
            $campaignModels = EdasCampaign::find()->where(['campaign_type' => $campaign_type])->andWhere(['del_status' => EdasCampaign::STATUS_NOT_DELETED])->all();
        }
        $campaign = ArrayHelper::getColumn($campaignModels, 'campaign_name');
        $campaign_ids = ArrayHelper::getColumn($campaignModels, 'campaign_id');

        $queue = [];
        if($queue_id){
            $queueModels = VaaniCampaignQueue::find()->where(['IN', 'queue_id', $queue_id])->all();
            foreach ($queueModels as $queueModel) {
                $queue[] = $queueModel->queue;
            }
        }else {
            if($campaign_ids){
                $queueModels = VaaniCampaignQueue::find()->where(['IN', 'campaign_id', $campaign_ids])->andWhere(['del_status' => VaaniCampaignQueue::STATUS_NOT_DELETED])->all();
                foreach ($queueModels as $queueModel) {
                    $queue[] = $queueModel->queue;
                }
            }
        }

        $status_flags = [];
        if($flag){
            switch ($flag) {
                case 'todays':
                    $status_flags = [];
                    break;
                case 'ready':
                    $status_flags[] = VaaniActiveStatus::STATUS_READY;
                    break;
                case 'active':
                    $status_flags[] = VaaniActiveStatus::STATUS_INCALL;
                    break;
                case 'wrap':
                    $status_flags[] = VaaniActiveStatus::STATUS_UNKNOWN_ERROR;
                    break;
                case 'not_ready':
                    $status_flags = [VaaniActiveStatus::STATUS_LOGIN, VaaniActiveStatus::STATUS_PAUSE];
                    break;
                case 'hold':
                    $status_flags[] = VaaniActiveStatus::STATUS_INCALL;
                    break;
                
                default:
                    $status_flags = [];
                    break;
            }
        }
        
        $status_codes = [];
        $sub_status_codes = [];
        $sub_status_duration = [];

        $vaani_users = VaaniActiveStatus::agentStatus([VaaniActiveStatus::STATUS_LOGIN, VaaniActiveStatus::STATUS_READY, VaaniActiveStatus::STATUS_PAUSE, VaaniActiveStatus::STATUS_INCALL, VaaniActiveStatus::STATUS_UNKNOWN_ERROR], $campaign_id);

        $total = count($vaani_users);
        $today_calls = 0;
        $ready = 0;
        $active = 0;
        $wrap = 0;
        $not_ready = 0;
        $hold = 0;
        $unique_ids = [];

        if($vaani_users){
            foreach ($vaani_users as $key => $agent) {
                $unique_ids[] = $agent->unique_id;
                $status_codes[] = $agent->status;
                if($agent->status != VaaniActiveStatus::STATUS_READY && $agent->status != VaaniActiveStatus::STATUS_UNKNOWN_ERROR){
                    if($agent->status == VaaniActiveStatus::STATUS_PAUSE && $agent->sub_status){
                        $sub_status_codes[$agent->user_id] = $agent->sub_status;
                        $sub_status_duration[$agent->user_id] = gmdate('H:i:s', (strtotime(date('Y-m-d H:i:s')) - strtotime($agent->modified_date)) );
                    }else{
                        if(!in_array(strtolower($agent->sub_status), [VaaniActiveStatus::SUB_STATUS_UNHOLD, VaaniActiveStatus::SUB_STATUS_CONF_E, VaaniActiveStatus::SUB_STATUS_CONF_EP, VaaniActiveStatus::SUB_STATUS_CONS_E]) && isset(VaaniActiveStatus::$sub_statuses[$agent->sub_status])){
                            $sub_status_codes[$agent->user_id] = VaaniActiveStatus::$sub_statuses[$agent->sub_status];
                            $sub_status_duration[$agent->user_id] = gmdate('H:i:s', (strtotime(date('Y-m-d H:i:s')) - strtotime($agent->modified_date)) );
                        }
                    }
                }
                if($agent->status == VaaniActiveStatus::STATUS_LOGIN || $agent->status == VaaniActiveStatus::STATUS_PAUSE){
                    $not_ready += 1;
                }else if($agent->status == VaaniActiveStatus::STATUS_READY){
                    $ready += 1;
                }else if($agent->status == VaaniActiveStatus::STATUS_UNKNOWN_ERROR){
                    $wrap += 1;
                }else if($agent->status == VaaniActiveStatus::STATUS_INCALL){
                    $active += 1;
                    if($agent->sub_status == VaaniActiveStatus::SUB_STATUS_HOLD){
                        $hold += 1;
                    }
                }
            }
        }

        // fetch today's call count
        $todays_start_time = date('Y-m-d').' 00:00:00';
        $todays_end_time = date('Y-m-d H:i:s');

        if($campaign_id || (($user->userRole && strtolower($user->userRole->role_name) != 'superadmin') && strtolower($user->role) != 'superadmin')){
            $today_calls = VaaniAgentLiveStatus::find()->where(['and', ['>=', 'datetime', $todays_start_time], ['<=', 'datetime', $todays_end_time]])->andWhere(['IN', 'campaign_name', $campaign])->groupBy('unique_id')->count();
        }else{
            $today_calls = VaaniAgentLiveStatus::find()->where(['and', ['>=', 'datetime', $todays_start_time], ['<=', 'datetime', $todays_end_time]])->groupBy('unique_id')->count();
        }
        
        if($status_flags){
            $status_codes = $status_flags;
        }

        /* $searchModel = new VaaniSessionLogSearch();
        $searchModel->del_status = VaaniActiveStatus::STATUS_NOT_DELETED;
        $searchModel->active_log = VaaniSessionLog::ACTIVE_LOG_ON;
        $searchModel->unique_id = $unique_ids;
        $searchModel->status_id = $status_codes;
        if($flag == 'todays') $searchModel->calls_date = $todays_start_time . ' - ' . $todays_end_time;
        $searchModel->campaign_id = $campaign_id;
        $searchModel->live_monitoring = true;
        if($flag == 'hold') $searchModel->is_hold_data = true;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams); */

        /* $searchModel = new VaaniActiveStatusSearch();
        $searchModel->del_status = VaaniActiveStatus::STATUS_NOT_DELETED;
        $searchModel->unique_id = $unique_ids;
        $searchModel->status = $status_codes;
        if($flag == 'todays') $searchModel->calls_date = $todays_start_time . ' - ' . $todays_end_time;
        // $searchModel->campaign_id = $campaign_id;
        // $searchModel->live_monitoring = true;
        if($flag == 'hold') $searchModel->is_hold_data = true;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams); */

        $role_id = $user['userAccess'][0]['role_id'];
        $queueId = $user['userAccess'][0]['queue_id'];
        $campaignId = $user['userAccess'][0]['campaign_id'];
        // echo "<pre>";print_r($campaignId);exit;
        // fetch all users of current mapped supervisor
        $users = VaaniUserAccess::find()->where(['campaign_id'=> $campaignId,'del_status' => VaaniUserAccess::STATUS_NOT_DELETED])->all();
        $user_lists = ArrayHelper::getColumn($users, 'user_id');
        $searchModel = new VaaniActiveStatusSearch();
        $searchModel->del_status = VaaniActiveStatus::STATUS_NOT_DELETED;
        $searchModel->unique_id = $unique_ids;
        $searchModel->status = $status_codes;
        $searchModel->queueId = $queueId;
        $searchModel->role_id = $role_id;
        $searchModel->campaignId = $campaignId;
        $searchModel->user_id = $role_id == 5 ? $user_lists : null;
        if($flag == 'todays') $searchModel->calls_date = $todays_start_time . ' - ' . $todays_end_time;
        // $searchModel->campaign_id = $campaign_id;
        // $searchModel->live_monitoring = true;
        if($flag == 'hold') $searchModel->is_hold_data = true;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $campaign_data = [];
        $campaign_data['total_calls'] = 0;              //inbound
        $campaign_data['answered_calls'] = 0;           //inbound
        $campaign_data['in_queue_calls'] = 0;           //inbound
        $campaign_data['in_ivr_calls'] = 0;             //inbound
        $campaign_data['completed_calls'] = 0;          // -
        $campaign_data['abandoned_calls'] = 0;          //inbound
        $campaign_data['average_wait'] = 0;             //inbound
        $campaign_data['average_drop_wait'] = 0;        //inbound
        $campaign_data['drop_percent'] = 0;             //inbound
        
        $campaign_data['dailed_calls'] = 0;             //outbound
        $campaign_data['connected_calls'] = 0;          //outbound
        $campaign_data['abandoned_calls'] = 0;          //outbound
        $campaign_data['aht_calls'] = 0;                //outbound
        $campaign_data['connected_percent'] = 0;        //outbound
        $campaign_data['abandoned_percent'] = 0;        //outbound

        if((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') || $campaign){
            if($campaign_type == EdasCampaign::TYPE_INBOUND || $campaign_type == EdasCampaign::TYPE_MANUAL){
                $campaign_data['inbound_calls'] = InboundEdas::find()
                                // ->andWhere(['IN', 'queue', $queue])
                                ->andWhere(['IN', 'campaign', $campaign])
                                ->andFilterWhere(['date' => date('Y-m-d')])
                                ->count();
                $campaign_data['manual_calls'] = ManualDialDetail::find()
                                // ->andWhere(['IN', 'queue', $queue])
                                ->andWhere(['IN', 'campaign', $campaign])
                                ->andFilterWhere(['date' => date('Y-m-d')])
                                ->count();
                $campaign_data['total_calls'] = $campaign_data['inbound_calls'] + $campaign_data['manual_calls'];

                $campaign_data['inbound_answered_calls'] = InboundEdas::find()
                                ->andWhere(['IS NOT', 'agent_id', NULL])
                                // ->andWhere(['IN', 'queue', $queue])
                                ->andWhere(['IN', 'campaign', $campaign])
                                ->andFilterWhere(['date' => date('Y-m-d')])
                                ->count();
                $campaign_data['manual_answered_calls'] = ManualDialDetail::find()
                                ->andWhere(['IS NOT', 'agent_id', NULL])
                                // ->andWhere(['IN', 'queue', $queue])
                                ->andWhere(['IN', 'campaign', $campaign])
                                ->andFilterWhere(['date' => date('Y-m-d')])
                                ->count();
                $campaign_data['answered_calls'] = $campaign_data['inbound_answered_calls'] + $campaign_data['manual_answered_calls'];

                $campaign_data['in_queue_calls'] = InboundEdas::find()
                                ->andWhere(['agent_id' => NULL])
                                ->andWhere(['duration' => NULL])
                                ->andWhere(['type' => 1])
                                // ->andWhere(['IN', 'queue', $queue])
                                ->andWhere(['IN', 'campaign', $campaign])
                                ->andFilterWhere(['date' => date('Y-m-d')])
                                ->count();
                $campaign_data['in_ivr_calls'] = InboundEdas::find()
                                ->andWhere(['agent_id' => NULL])
                                ->andWhere(['duration' => NULL])
                                ->andWhere(['type' => 2])
                                // ->andWhere(['IN', 'queue', $queue])
                                ->andWhere(['IN', 'campaign', $campaign])
                                ->andFilterWhere(['date' => date('Y-m-d')])
                                ->count();
                /* $campaign_data['completed_calls'] = InboundEdas::find()
                                ->andFilterWhere(['IN', 'queue', $queue])
                                ->andFilterWhere(['IN', 'campaign', $campaign])
                                ->andFilterWhere(['date' => date('Y-m-d')])
                                ->sum('queue_calls_completed'); */
                $campaign_data['inbound_abandoned_calls'] = InboundEdas::find()
                                ->andWhere(['agent_id' => NULL])
                                ->andWhere(['IS NOT', 'duration', NULL])
                                // ->andWhere(['IN', 'queue', $queue])
                                ->andWhere(['IN', 'campaign', $campaign])
                                ->andFilterWhere(['date' => date('Y-m-d')])
                                ->count();
                $campaign_data['mannual_abandoned_calls'] = InboundEdas::find()
                                ->andWhere(['agent_id' => NULL])
                                ->andWhere(['IS NOT', 'duration', NULL])
                                // ->andWhere(['IN', 'queue', $queue])
                                ->andWhere(['IN', 'campaign', $campaign])
                                ->andFilterWhere(['date' => date('Y-m-d')])
                                ->count();
                $campaign_data['abandoned_calls'] = $campaign_data['inbound_abandoned_calls'] + $campaign_data['mannual_abandoned_calls'];

                $campaign_data['average_wait'] = (
                    ($campaign_data['answered_calls'] || $campaign_data['abandoned_calls']) ? 
                                round((InboundEdas::find()
                                    ->andWhere(['IS NOT', 'agent_id', NULL])
                                    ->andWhere(['IS NOT', 'duration', NULL])
                                    // ->andWhere(['IN', 'queue', $queue])
                                    ->andWhere(['IN', 'campaign', $campaign])
                                    ->andFilterWhere(['date' => date('Y-m-d')])
                                    ->sum('queue_hold_time')) / ( $campaign_data['answered_calls'] + $campaign_data['abandoned_calls'] )) : 0 );
                $campaign_data['average_drop_wait'] = ($campaign_data['abandoned_calls'] ? 
                                round((InboundEdas::find()
                                    ->andWhere(['agent_id' => NULL])
                                    ->andWhere(['IS NOT', 'duration', NULL])
                                    // ->andWhere(['IN', 'queue', $queue])
                                    ->andWhere(['IN', 'campaign', $campaign])
                                    ->andFilterWhere(['date' => date('Y-m-d')])
                                    ->sum('queue_hold_time')) / $campaign_data['abandoned_calls'] ) : 0 );
                $campaign_data['drop_percent'] = ($campaign_data['total_calls'] ? (
                                ((InboundEdas::find()
                                ->andWhere(['agent_id' => NULL])
                                ->andWhere(['IS NOT', 'duration', NULL])
                                // ->andWhere(['IN', 'queue', $queue])
                                ->andWhere(['IN', 'campaign', $campaign])
                                ->andFilterWhere(['date' => date('Y-m-d')])
                                ->count()) / $campaign_data['total_calls']) * 100) : 0);
                                
            }else if($campaign_type == EdasCampaign::TYPE_OUTBOUND){

            }
        }

        return $this->renderPartial('_agent-report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => $total,
            'today_calls' => $today_calls,
            'active' => $active,
            'ready' => $ready,
            'wrap' => $wrap,
            'not_ready' => $not_ready,
            'hold' => $hold,
            'flag' => $flag,
            'sub_status_codes' => $sub_status_codes,
            'sub_status_duration' => $sub_status_duration,
            'campaign_data' => $campaign_data,
            'campaign_type' => $campaign_type,
        ]);
    }

    /**
     * Lists all InboundEdas models.
     * @return mixed
     */
    public function actionCallRegisterReport($id=null)
    {
        $user = Yii::$app->user->identity;
        $report_columns = ['Sr'=>'Sr',
                        'Date'=>'Date',
                        'Interval'=>'Interval',
                        'CLI'=>'CLI',
                        'Service'=>'Service',
                        'Call Type'=>'Call Type',
                        'Call Status'=>'Call Status',
                        'Agent'=>'Agent',
                        'Start Time'=>'Start Time',
                        'End Time'=>'End Time',
                        // 'DNI'=>'DNI',
                        'Disposition'=>'Disposition',
                        'Duration'=>'Duration',
                        'Hold Duration'=>'Hold Duration',
                        'Ring Duration'=>'Ring Duration',
                        'Talk Duration'=>'Talk Duration',
                        'Wrap Duration'=>'Wrap Duration'];

        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();
            
            $start_date = (isset($post['start_date']) ? $post['start_date'] : null);
            $end_date = (isset($post['end_date']) ? $post['end_date'] : null);
            $start_time = (isset($post['start_time']) ? ($post['start_time']) : null);
            $end_time = (isset($post['end_time']) ? ($post['end_time']) : null);
            $time_type = (isset($post['time_type']) && $post['time_type'] ? $post['time_type'] : 1);
            // $campaign_type = (isset($post['campaign_type']) ? $post['campaign_type'] : null);
            $campaigns = (isset($post['campaigns']) ? $post['campaigns'] : null);
            $queues = (isset($post['queues']) ? $post['queues'] : []);
            $users = (isset($post['users']) ? $post['users'] : []);
            $report_columns = (isset($post['report_columns']) ? $post['report_columns'] : []);
            
            $campaign_names = [];
            if($campaigns){
                $campaign_detail = EdasCampaign::campaignsList($campaigns);
                $campaign_names = ArrayHelper::getColumn($campaign_detail, 'campaign_name');
            }
            $queue_names = [];
            if($queues){
                $queue_detail = VaaniCampaignQueue::queuesList(null, null, $queues);
                $queue_names = ArrayHelper::getColumn($queue_detail, 'queue');
            }

            if(!$users){
                if(!$queues){
                    if($campaigns){
                        $queue_list = VaaniCampaignQueue::queuesList($campaigns);
                        $queues = ArrayHelper::getColumn($queue_list, 'queue_id');
                        $queue_names = ArrayHelper::getColumn($queue_list, 'queue');
                    }else{
                        Yii::$app->session->setFlash('error', 'Kindly select the campaign!');
                        return $this->redirect('recordings');
                    }
                }
                $user_list = VaaniCampaignQueue::usersList($campaigns, $queues);
                $users = ArrayHelper::getColumn($users, 'user_id');
            }

            
            $users = ($users ? ("'".implode("','", $users)."'") : 'NULL');
            $queue_names = ($queue_names ? ("'".implode("','", $queue_names)."'") : 'NULL');
            $campaign_names = "'".strtolower(implode("','", $campaign_names))."'";
            $start_date = "'".$start_date." ".$start_time.":00'";
            $end_date = "'".$end_date." ".$end_time.":00'";
            
            if($time_type == 2){
                // HOURLY WISE
                $interval = "date_format(start_date,'%H %p')";
                $group_by = "date_format(start_date,'%H %p'), id";
            }else{
                // DAY WISE
                $interval = "'". $start_time .'-'. $end_time . "'";
                $group_by = 'id';
            }

            $result_query = "
                SELECT *, ".$interval." as 'report_interval' FROM vaani_call_register_report 
                WHERE
                    start_date BETWEEN ".$start_date." and ".$end_date."
                    and (agent_id IN (".$users.") OR agent_id IS NULL)
                    and queue IN (".$queue_names.")
                GROUP BY ".$group_by.";
            ";
            
            Yii::$app->Utility->addLog("** FETCH CALL REGISTER REPORT QUERY ** " . $result_query, 'call_register_report', \Yii::getAlias('@runtime') . "/logs/");      // LOG
            $result = \Yii::$app->db->createCommand($result_query)->queryAll();
            
            // echo "<pre>"; print_r($result);exit;

            return $this->render('call-register-report', [
                'data' => $result,
                'report_columns' => $report_columns,
            ]);
        }else{
            // fetch list of campaigns
            $campaign_list = [];
            $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
            
            if($campaign_ids){
                $campaigns = EdasCampaign::campaignsList($campaign_ids, null);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }else if(isset($_SESSION['client_connection'])){
                $campaigns = EdasCampaign::campaignsList(null, $_SESSION['client_connection']);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }
            $queues = [];
            $users = [];
            
            return $this->render('call-register-form', [
                'campaigns' => $campaign_list,
                'queues' => $queues,
                'users' => $users,
                'report_columns' => $report_columns,
            ]);
        }
    }

    // OLD CALL REGISTER REPORT - UNUSED
    public function actionCallRegisterReportOld($id=null)
    {
        $user = Yii::$app->user->identity;
        $report_columns = ['Sr'=>'Sr',
                        'Date'=>'Date',
                        'Interval'=>'Interval',
                        'CLI'=>'CLI',
                        'Service'=>'Service',
                        'Call Type'=>'Call Type',
                        'Call Status'=>'Call Status',
                        'Agent'=>'Agent',
                        'Start Time'=>'Start Time',
                        'End Time'=>'End Time',
                        'DNI'=>'DNI',
                        'Disposition'=>'Disposition',
                        'Duration'=>'Duration',
                        'Hold Duration'=>'Hold Duration',
                        'Ring Duration'=>'Ring Duration',
                        'Talk Duration'=>'Talk Duration',
                        'Wrap Duration'=>'Wrap Duration'];

        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();
            
            $start_date = (isset($post['start_date']) ? $post['start_date'] : null);
            $end_date = (isset($post['end_date']) ? $post['end_date'] : null);
            $start_time = (isset($post['start_time']) ? ($post['start_time'] . ':00') : null);
            $end_time = (isset($post['end_time']) ? ($post['end_time'] . ':00') : null);
            // $campaign_type = (isset($post['campaign_type']) ? $post['campaign_type'] : null);
            $campaigns = (isset($post['campaigns']) ? $post['campaigns'] : null);
            $queues = (isset($post['queues']) ? $post['queues'] : []);
            $users = (isset($post['users']) ? $post['users'] : []);
            $report_columns = (isset($post['report_columns']) ? $post['report_columns'] : []);
            
            $campaign_names = [];
            if($campaigns){
                $campaign_detail = EdasCampaign::campaignsList($campaigns);
                $campaign_names = ArrayHelper::getColumn($campaign_detail, 'campaign_name');
            }
            $queue_names = [];
            if($queues){
                $queue_detail = VaaniCampaignQueue::queuesList($queues);
                $queue_names = ArrayHelper::getColumn($queue_detail, 'queue_name');
            }

            if(!$users){
                if(!$queues){
                    if($campaigns){
                        $queue_list = VaaniCampaignQueue::queuesList($campaigns);
                        $queues = ArrayHelper::getColumn($queue_list, 'queue_id');
                        $queue_names = ArrayHelper::getColumn($queue_list, 'queue_name');
                    }else{
                        Yii::$app->session->setFlash('error', 'Kindly select the campaign!');
                        return $this->redirect('recordings');
                    }
                }
                $user_list = VaaniCampaignQueue::usersList($campaigns, $queues);
                $users = ArrayHelper::getColumn($users, 'user_id');
            }

            $inbound_query = InboundEdas::find()
                    ->leftJoin('vaani_agent_live_status', '`inbound_edas`.`unique_id` = `vaani_agent_live_status`.`unique_id`')
                    ->where(['between', 'date', $start_date, $end_date])
                    ->andFilterWhere(['between', 'time', $start_time, $end_time])
                    ->andFilterWhere(['IN', 'inbound_edas.agent_id', $users])
                    ->andFilterWhere(['IN', 'queue', $queue_names]);
            $manual_query = ManualDialDetail::find()
                    ->leftJoin('vaani_agent_live_status', '`manual_dial_detail`.`unique_id` = `vaani_agent_live_status`.`unique_id`')
                    ->where(['between', 'date', $start_date, $end_date])
                    ->andFilterWhere(['between', 'time', $start_time, $end_time])
                    ->andFilterWhere(['IN', 'manual_dial_detail.agent_id', $users])
                    ->andFilterWhere(['IN', 'queue_name', $queue_names]);
            $preview_query = VaaniPreviewDialDetail::find()
                    ->leftJoin('vaani_agent_live_status', '`vaani_preview_dial_detail`.`unique_id` = `vaani_agent_live_status`.`unique_id`')
                    ->where(['between', 'date', $start_date, $end_date])
                    ->andFilterWhere(['between', 'time', $start_time, $end_time])
                    ->andFilterWhere(['IN', 'vaani_preview_dial_detail.agent_id', $users])
                    ->andFilterWhere(['IN', 'queue_name', $queue_names]);
            $predictive_query = VaaniPredictiveDialDetail::find()
                    ->leftJoin('vaani_agent_live_status', '`vaani_predictive_dial_detail`.`unique_id` = `vaani_agent_live_status`.`unique_id`')
                    ->where(['between', 'date', $start_date, $end_date])
                    ->andFilterWhere(['between', 'time', $start_time, $end_time])
                    ->andFilterWhere(['IN', 'vaani_predictive_dial_detail.agent_id', $users])
                    ->andFilterWhere(['IN', 'queue', $queue_names]);
            $progressive_query = VaaniProgressiveDialDetail::find()
                    ->leftJoin('vaani_agent_live_status', '`vaani_progressive_dial_detail`.`unique_id` = `vaani_agent_live_status`.`unique_id`')
                    ->where(['between', 'date', $start_date, $end_date])
                    ->andFilterWhere(['between', 'time', $start_time, $end_time])
                    ->andFilterWhere(['IN', 'vaani_progressive_dial_detail.agent_id', $users])
                    ->andFilterWhere(['IN', 'queue', $queue_names]);

            // $inbound_query = InboundEdas::find()->where(['date' => date('Y-m-d')]);
            // $manual_query = ManualDialDetail::find()->where(['date' => date('Y-m-d')]);

            if($id){
                $inbound_query->andWhere(['agent_id' => $id]);
                $manual_query->andWhere(['agent_id' => $id]);
                $preview_query->andWhere(['agent_id' => $id]);
                $predictive_query->andWhere(['agent_id' => $id]);
                $progressive_query->andWhere(['agent_id' => $id]);
            }
            /* if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
                $user_access = $user->userAccess;
                $user_campaigns = [];
                $user_queues = [];

                if($user_access){
                    $user_client = ($user_access[0]->client ? $user_access[0]->client : null);
                    
                    foreach($user_access as $access_record){
                        // fetch campaigns
                        if($access_record->campaign){
                            $user_campaigns[] = $access_record->campaign->campaign_name;
                        }
                        // fetch queues
                        if($access_record->queue){
                            $user_queues[] = $access_record->queue->queue_name;
                        }
                    }
                    if(!$user_campaigns && !$user_queues){
                        $user_campaigns = ($user_client->campaignsList ? array_values($user_client->campaignsList) : null);
                    }
                }

                if($user_queues){
                    $inbound_query->andWhere(['IN', 'queue', $user_queues]);
                    $manual_query->andWhere(['IN', 'queue_name', $user_queues]);
                }else{
                    $inbound_query->andWhere(['IN', 'campaign', ($user_campaigns ? $user_campaigns : [])]);
                    $manual_query->andWhere(['IN', 'campaign', ($user_campaigns ? $user_campaigns : [])]);
                }
            } */

            // inbound call details
            $inboundDataprovider= new ArrayDataProvider ([
                'allModels' => $inbound_query->orderBy('time DESC')
                    ->select(['*', /* new Expression('1 as call_type') */])
                    ->asArray()
                    ->all(),
            ]);
            // manual call details
            $manualDataprovider= new ArrayDataProvider ([
                'allModels' => $manual_query->orderBy('time DESC')
                    ->select(['*', /* new Expression('2 as call_type') */])
                    ->asArray()
                    ->all(),
            ]);
            // preview call details
            $previewDataprovider= new ArrayDataProvider ([
                'allModels' => $preview_query->orderBy('time DESC')
                    ->select(['*', /* new Expression('2 as call_type') */])
                    ->asArray()
                    ->all(),
            ]);
            // predictive call details
            $predictiveDataprovider= new ArrayDataProvider ([
                'allModels' => $predictive_query->orderBy('time DESC')
                    ->select(['*', /* new Expression('2 as call_type') */])
                    ->asArray()
                    ->all(),
            ]);
            // progressive call details
            $progressiveDataprovider= new ArrayDataProvider ([
                'allModels' => $progressive_query->orderBy('time DESC')
                    ->select(['*', /* new Expression('2 as call_type') */])
                    ->asArray()
                    ->all(),
            ]);
            // merge all the models into one
            $merged_data = ArrayHelper::merge($inboundDataprovider->getModels(),$manualDataprovider->getModels(), $previewDataprovider->getModels(), $predictiveDataprovider->getModels(), $progressiveDataprovider->getModels());

            // sort based on time field
            usort($merged_data, function($a, $b) {
                return $a['time'] <=> $b['time'];
            });
        
            // create a main one ArrayDataProvider which will have the merged models as data
            $dataProvider = new ArrayDataProvider([
                'key' => 'unique_id',
                'allModels' => $merged_data,
                'sort' => [
                    'attributes' => ['time'],
                    'defaultOrder' => [
                        'time' => SORT_DESC,
                    ]
                ]
            ]);

            return $this->render('call-register-report', [
                'dataProvider' => $dataProvider,
                'report_columns' => $report_columns,
            ]);
        }else{
            // fetch list of campaigns
            $campaign_list = [];
            $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
            
            if($campaign_ids){
                $campaigns = EdasCampaign::campaignsList($campaign_ids, null);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }else if(isset($_SESSION['client_connection'])){
                $campaigns = EdasCampaign::campaignsList(null, $_SESSION['client_connection']);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }
            $queues = [];
            $users = [];
            
            return $this->render('call-register-form', [
                'campaigns' => $campaign_list,
                'queues' => $queues,
                'users' => $users,
                'report_columns' => $report_columns,
            ]);
        }
    }

    // fetch list of campaigns based on type
    public function actionGetCampaigns()
    {
        $user = Yii::$app->user->identity;
        $type = Yii::$app->request->get()['campaign_type'];
        if($type){
            $campaign_list = [];
            $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
            
            if($campaign_ids){
                $campaigns = EdasCampaign::campaignsList($campaign_ids, null ,$type);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }else if(isset($_SESSION['client_connection'])){
                $campaigns = EdasCampaign::campaignsList(null, $_SESSION['client_connection'],$type);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }

            return json_encode($campaign_list);
        }
        return false;
    }

    // fetch list of queues based on type
    public function actionGetQueues()
    {
        $user = Yii::$app->user->identity;
        $campaign_id = Yii::$app->request->get()['campaign_id'];
        if($campaign_id){
            $queues = VaaniCampaignQueue::queuesList($campaign_id);
            $queue_list = ArrayHelper::map($queues, 'queue_id', 'queue_name');

            return json_encode($queue_list);
        }
        return false;
    }

    // fetch list of users based on queues
    public function actionGetUsers()
    {
        $user = Yii::$app->user->identity;
        $campaigns = Yii::$app->request->get()['campaigns'];
        $queues = (isset(Yii::$app->request->get()['queues']) ? Yii::$app->request->get()['queues'] : null);
        if($queues){
            $users = VaaniCampaignQueue::usersList($campaigns, $queues);
            $deleted_users = VaaniCampaignQueue::usersInActiveList($campaigns, $queues);
            $result = ArrayHelper::merge($users, $deleted_users);
            $user_list = ArrayHelper::map($result, 'user_id', 'user.user_name','del_status');

            return json_encode($user_list);
        }
        return false;
    }

    // call to incall_action api
    public function actionDisplayIncall()
    {
        $logged_in_user = Yii::$app->user->identity;
        $agent_id = Yii::$app->request->post()['agent_id'];
        $supervisor_id = Yii::$app->request->post()['supervisor_id'];
        $action = Yii::$app->request->post()['monitor_action'];
        $unique_id = Yii::$app->request->post()['unique_id'];

        $result = [];

        if($action){
            if($supervisor_id){
                if($agent_id){
                    // fetch client name
                    $agent = User::find()->where(['user_id' => $agent_id])->one();
                    $user_access = ($agent ? $agent->userAccess : null);
                    $client_name = (($user_access && $user_access[0]->client) ? $user_access[0]->client->client_name : null );

                    $data = json_encode(['agent_id' => $agent_id, 'supervisor_id' => $supervisor_id, 'action' => $action, 'unique_id' => $unique_id, 'client_name' => $client_name]);

                    if(!isset($_SESSION['monitor_action']) && !isset($_SESSION['monitor_agent_id'])){
                        $resultmsg = Api::callAPI('POST', Yii::$app->params['INCALL_ACTION_API'], $data, Yii::$app->params['VAANI_REMOTE_API_USERNAME'], Yii::$app->params['VAANI_REMOTE_API_PASSWORD']);
                        $result['msg'] = $resultmsg;
                        if($result['msg'] == "success" ){
                            $_SESSION['monitor_action'] = $result['monitor_action'] = $action;
                            $_SESSION['monitor_agent_id'] = $result['monitor_agent_id'] = $agent_id;
                            $_SESSION['monitor_extension'] = $result['monitor_extension'] = $supervisor_id;
                            $_SESSION['monitor_unique_id'] = $result['monitor_unique_id'] = $unique_id;
                        }
                    }else{
                        $result['msg'] = 'Call is already under monitoring!';
                    }
                    return ($result ? json_encode($result) : null);
                }
                return 'Kindly provide the agent id!';
            }
            return 'Kindly provide the extension!';
        }
        return 'Kindly select the monitor action!';
    }

    // call to hangup api
    public function actionStopIncallAction()
    {
        $supervisor_id = Yii::$app->request->post()['supervisor_id'];
        $unique_id = Yii::$app->request->post()['unique_id'];

        if(!$unique_id){
            $unique_id = $_SESSION['monitor_unique_id'];
        }

        if($supervisor_id){
            if($unique_id){
                $supervisor_detail = VaaniSupervisorDetail::find()->where(['supervisor_id' => $supervisor_id])->andWhere(['unique_id' => $unique_id])->orderBy('id DESC')->one();
                
                if($supervisor_detail){
                    $data = json_encode(['caller_channel' => $supervisor_detail->supervisor_channel]);
                    $result = Api::callAPI('POST', Yii::$app->params['AGENT_CALL_HANGUP'], $data, Yii::$app->params['VAANI_REMOTE_API_USERNAME'], Yii::$app->params['VAANI_REMOTE_API_PASSWORD']);
                    if($result == 'success'){
                        $supervisor_detail->status = 1;
                        $supervisor_detail->save();

                        if(isset($_SESSION['monitor_action']) && isset($_SESSION['monitor_agent_id'])){
                            unset($_SESSION['monitor_action']);
                            unset($_SESSION['monitor_agent_id']);
                            unset($_SESSION['monitor_extension']);
                            unset($_SESSION['monitor_unique_id']);
                        }
                    }
                    return $result;
                }
                return 'Supervisor Detail Not Found!';
            }
            return 'Kindly provide the unique id!';
        }
        return 'Kindly provide the extension!';
    }

    // fetch call status of agent by unique id
    public function actionCheckCallStatus()
    {
        $unique_id = Yii::$app->request->get()['unique_id'];

        if($unique_id){
            $agent_live_status = VaaniAgentLiveStatus::find()->where(['unique_id' => $unique_id])->one();
            if($agent_live_status){
                return $agent_live_status->status;
            }
        }
        return false;
    }

    // recording list
    public function actionRecordings($id=null)
    {
        $user = Yii::$app->user->identity;
        $report_columns = [
            // 'SR'=>'SR',
            'UNIQUE ID'=>'UNIQUE ID',
            'START TIME'=>'START TIME',
            'DURATION'=>'DURATION',
            'USER ID'=>'USER ID',
            'NUMBER'=>'NUMBER',
            'CAMPAIGN'=>'CAMPAIGN',
            'DOWNLOAD'=>'DOWNLOAD',
            'EVALUATION'=>'EVALUATION',
            // 'RECORDING'=>'RECORDING'
        ];

        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();

            $start_date = (isset($post['start_date']) ? $post['start_date'] : null);
            $end_date = (isset($post['end_date']) ? $post['end_date'] : null);
            $start_time = (isset($post['start_time']) ? ($post['start_time'] . ':00') : null);
            $end_time = (isset($post['end_time']) ? ($post['end_time'] . ':00') : null);
            $campaign_type = (isset($post['campaign_type']) ? $post['campaign_type'] : null);
            $campaigns = (isset($post['campaigns']) ? $post['campaigns'] : null);
            $queues = (isset($post['queues']) ? $post['queues'] : []);
            $users = (isset($post['users']) ? $post['users'] : []);
            $report_columns = (isset($post['report_columns']) ? $post['report_columns'] : []);

            $campaign_names = [];
            if($campaigns){
                $campaign_detail = EdasCampaign::campaignsList($campaigns);
                $campaign_names = ArrayHelper::getColumn($campaign_detail, 'campaign_name');
            }
            $queue_names = [];
            if($queues){
                $queue_detail = VaaniCampaignQueue::queuesList($queues);
                $queue_names = ArrayHelper::getColumn($queue_detail, 'queue_name');
            }

            if(!$users){
                if(!$queues){
                    if($campaigns){
                        $queue_list = VaaniCampaignQueue::queuesList($campaigns);
                        $queues = ArrayHelper::getColumn($queue_list, 'queue_id');
                        $queue_names = ArrayHelper::getColumn($queue_list, 'queue_name');
                    }else{
                        Yii::$app->session->setFlash('error', 'Kindly select the campaign!');
                        return $this->redirect('recordings');
                    }
                }
                $user_list = VaaniCampaignQueue::usersList($campaigns, $queues);
                $users = ArrayHelper::getColumn($users, 'user_id');
            }

            $start_date_time = $start_date . " " . $start_time;
            $end_date_time = $end_date . " " . $end_time;
 
            $query = VaaniCallRecordings::find()
                // ->where(['status' => VaaniCallRecordings::STATUS_NOT_AUDITED])
                ->where(['>=', 'start_time', $start_date_time])
                ->andWhere(['<', 'end_time', $end_date_time])
                ->andFilterWhere(['IN', 'agent_id', $users])
                ->andFilterWhere(['IN', 'campaign', $campaign_names])
                ->andFilterWhere(['IN', 'queue', $queue_names]);

            $dataProvider= new ArrayDataProvider ([
                'allModels' => $query->orderBy('start_time DESC')->all(),
            ]);

            /* $inbound_query = InboundEdas::find()
                    ->where(['between', 'date', $start_date, $end_date])
                    ->andFilterWhere(['between', 'time', $start_time, $end_time])
                    ->andFilterWhere(['IN', 'agent_id', $users])
                    ->andFilterWhere(['IN', 'campaign', $campaign_names])
                    ->andFilterWhere(['IN', 'queue', $queue_names]);
            $manual_query = ManualDialDetail::find()
                    ->where(['between', 'date', $start_date, $end_date])
                    ->andFilterWhere(['between', 'time', $start_time, $end_time])
                    ->andFilterWhere(['IN', 'agent_id', $users])
                    ->andFilterWhere(['IN', 'campaign', $campaign_names])
                    ->andFilterWhere(['IN', 'queue_name', $queue_names]);
            $preview_query = VaaniPreviewDialDetail::find()
                    ->where(['between', 'date', $start_date, $end_date])
                    ->andFilterWhere(['between', 'time', $start_time, $end_time])
                    ->andFilterWhere(['IN', 'agent_id', $users])
                    ->andFilterWhere(['IN', 'campaign', $campaign_names])
                    ->andFilterWhere(['IN', 'queue_name', $queue_names]);
            $predictive_query = VaaniPredictiveDialDetail::find()
                    ->where(['between', 'date', $start_date, $end_date])
                    ->andFilterWhere(['between', 'time', $start_time, $end_time])
                    ->andFilterWhere(['IN', 'agent_id', $users])
                    ->andFilterWhere(['IN', 'campaign', $campaign_names])
                    ->andFilterWhere(['IN', 'queue', $queue_names]);
            $progressive_query = VaaniProgressiveDialDetail::find()
                    ->where(['between', 'date', $start_date, $end_date])
                    ->andFilterWhere(['between', 'time', $start_time, $end_time])
                    ->andFilterWhere(['IN', 'agent_id', $users])
                    ->andFilterWhere(['IN', 'campaign', $campaign_names])
                    ->andFilterWhere(['IN', 'queue', $queue_names]); */

            /* if($id){
                $inbound_query->andWhere(['agent_id' => $id]);
                $manual_query->andWhere(['agent_id' => $id]);
            } */

            // inbound call details
            /* $inboundDataprovider= new ArrayDataProvider ([
                'allModels' => $inbound_query->orderBy('time DESC')->all(),
            ]);
            // manual call details
            $manualDataprovider= new ArrayDataProvider ([
                'allModels' => $manual_query->orderBy('time DESC')->all(),
            ]);
            // preview call details
            $previewDataprovider= new ArrayDataProvider ([
                'allModels' => $preview_query->orderBy('time DESC')->all(),
            ]);
            // predictive call details
            $predictiveDataprovider= new ArrayDataProvider ([
                'allModels' => $predictive_query->orderBy('time DESC')->all(),
            ]);
            // progressive call details
            $progressiveDataprovider= new ArrayDataProvider ([
                'allModels' => $progressive_query->orderBy('time DESC')->all(),
            ]); */

            // merge all the models into one
            /* $merged_data = ArrayHelper::merge($inboundDataprovider->getModels(),$manualDataprovider->getModels(), $previewDataprovider->getModels(), $predictiveDataprovider->getModels(), $progressiveDataprovider->getModels()); */
            // sort based on time field
            /* usort($merged_data, function($a, $b) {
                return $b['time'] <=> $a['time'];
            }); */
            // create a main one ArrayDataProvider which will have the merged models as data
            /* $dataProvider = new ArrayDataProvider([
                'key' => 'unique_id',
                'allModels' => $merged_data,
                'sort' => [
                    'attributes' => ['time'],
                    'defaultOrder' => [
                        'time' => SORT_DESC,
                    ]
                ]
            ]); */

            return $this->render('recordings', [
                'dataProvider' => $dataProvider,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'campaign_list' => $campaign_names,
                'report_columns' => $report_columns,
            ]);
        }else{
            // fetch list of campaigns
            $campaign_list = [];
            $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
            
            if($campaign_ids){
                $campaigns = EdasCampaign::campaignsList($campaign_ids, null, [EdasCampaign::TYPE_OUTBOUND, EdasCampaign::TYPE_BLENDED]);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }else if(isset($_SESSION['client_connection'])){
                $campaigns = EdasCampaign::campaignsList(null, $_SESSION['client_connection'], [EdasCampaign::TYPE_OUTBOUND, EdasCampaign::TYPE_BLENDED]);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }
            $queues = [];
            $users = [];
            
            return $this->render('recordings-form', [
                'campaigns' => $campaign_list,
                'queues' => $queues,
                'users' => $users,
                'report_columns' => $report_columns,
            ]);
        }
    }

    // agent performance report
    public function actionAgentPerformanceReport()
    {
        $user = Yii::$app->user->identity;
        $form_name = "agemtperformancereport";
        $times = $campaigns = $queues = $users = null;
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');

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

        $selected_report_columns = $this->GetReportColumn('select',$form_name,array_values($report_columns));
        
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();

            $start_date = (isset($post['start_date']) ? $post['start_date'] : null);
            $end_date = (isset($post['end_date']) ? $post['end_date'] : null);
            $start_time = (isset($post['start_time']) ? $post['start_time'] : null);
            $end_time = (isset($post['end_time']) ? $post['end_time'] : null);
            $time_type = (isset($post['time_type']) && $post['time_type'] ? $post['time_type'] : 1);
            $campaigns = (isset($post['campaigns']) ? $post['campaigns'] : null);
            $queues = (isset($post['queues']) ? $post['queues'] : []);
            $users = (isset($post['users']) ? $post['users'] : []);
            //filter columns
            $report_columns = (isset($post['report_columns']) ? $post['report_columns'] : []);
            $formName = (isset($post['formName']) ? $post['formName'] : []);
            $update_column_selection = (isset($post['update_column_selection']) ? $post['update_column_selection'] : []);
            
            $foo =serialize($report_columns);
            $bar =serialize($selected_report_columns);
            if ($foo !== $bar){
                $this->GetReportColumn('update',$form_name,$report_columns);
            } 
            
            $campaign_names = [];
            if($campaigns){
                $campaign_detail = EdasCampaign::campaignsList($campaigns);
                $campaign_names = ArrayHelper::getColumn($campaign_detail, 'campaign_name');
            }

            if(!$users){
                if(!$queues){
                    if($campaigns){
                        $queue_list = VaaniCampaignQueue::queuesList($campaigns);
                        $queues = ArrayHelper::getColumn($queue_list, 'queue_id');
                    }else{
                        Yii::$app->session->setFlash('error', 'Kindly select the campaign!');
                        return $this->redirect('agent-performance-report');
                    }
                }
                $user_list = VaaniCampaignQueue::usersList($campaigns, $queues);
                $users = ArrayHelper::getColumn($users, 'user_id');
            }

            $users = ($users ? ("".implode(",", $users)."") : 'NULL');
            $campaigns = "'".implode("','", $campaigns)."'";
            $queues = ($queues ? ("'".implode("','", $queues)."'") : 'NULL');
            $campaign_names = "'".strtolower(implode("','", $campaign_names))."'";
            $start_date = "'".$start_date." ".$start_time.":00'";
            $end_date = "'".$end_date." ".$end_time.":00'";
            
            $result = null;
            if($time_type == 2){
                // HOURLY WISE
                $interval = "date_format(start_date,'%H %p')";
                $group_by = "date_format(start_date,'%H %p'), agent_id";
            }else{
                // DAY WISE
                $interval = "'". $start_time .'-'. $end_time . "'";
                $group_by = 'agent_id';
            }
            // echo "<pre>";print_r($end_date);exit;
            $result = \Yii::$app->db->createCommand("
                SELECT 
                    `agent_id`, `queue_id`, `queue`, `date`, `agent_name`,
                    ".$interval." as `interval`,
                    SUM(`total_calls`) as 'total_calls',
                    SUM(`inbound_answered`) as 'inbound_answered',
                    SUM(`outbound_answered`) as 'outbound_answered',
                    convert(sec_to_time(sum(time_to_sec(`login_time`))),time) as 'login_time',
                    convert(sec_to_time(sum(time_to_sec(`not_ready`))),time) as 'not_ready',
                    convert(sec_to_time(sum(time_to_sec(`idle_duration`))),time) as 'idle_duration',
                    convert(sec_to_time(sum(time_to_sec(`ring_duration`))),time) as 'ring_duration',
                    convert(sec_to_time(sum(time_to_sec(`talk_duration`))),time) as 'talk_duration',
                    convert(sec_to_time(sum(time_to_sec(`hold_duration`))),time) as 'hold_duration',
                    convert(sec_to_time(sum(time_to_sec(`wrap_up_duration`))),time) as 'wrap_up_duration',
                    convert(sec_to_time(sum(time_to_sec(`wrap_up_duration`))),time) as 'wrap_up_duration'
                FROM vaani_apr_report WHERE
                    start_date BETWEEN ".$start_date." and ".$end_date."
                    and start_date BETWEEN ".$start_date." and ".$end_date."
                    and agent_id IN (".$users.")
                    and queue_id IN (".$queues.")
                GROUP BY ".$group_by.";
            ")->queryAll();

            if($campaigns){
                $campaign_list = explode(",", $campaigns);
                $campaign_arr = [];
                foreach ($campaign_list as $key => $camp) {
                    $camp = trim($camp, '\'"');
                    $campaignModel = EdasCampaign::find()->where(["campaign_id" => $camp])->one();
                    $campaign_arr[] = ($campaignModel ? $campaignModel->campaign_name : null);
                }
            }

            return $this->render('agent-performance-report', [
                'data' => $result,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'times' => $times,
                'campaign_list' => $campaign_arr,
                'queues' => $queues,
                'users' => $users,
                'report_columns' => $report_columns,
                'selected_report_columns' => '',
            ]);
        }else{
            // fetch list of campaigns
            $campaign_list = [];
            $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
            
            if($campaign_ids){
                $campaigns = EdasCampaign::campaignsList($campaign_ids, null);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }else if(isset($_SESSION['client_connection'])){
                $campaigns = EdasCampaign::campaignsList(null, $_SESSION['client_connection']);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }
            $queues = [];
            $users = [];
            
            return $this->render('agent-performance-form', [
                'campaigns' => $campaign_list,
                'queues' => $queues,
                'users' => $users,
                'report_columns' => $report_columns,
                'selected_report_columns' => $selected_report_columns,
            ]);
        }
    }

    // agent performance report NOT IN USE
    public function actionAgentPerformanceReportOld()
    {
        $user = Yii::$app->user->identity;
        $form_name = "agemtperformancereport";
        $times = $campaigns = $queues = $users = null;
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
        // $report_columns = ['Date','Interval','Agent','Agent Id','Total Calls','Outbound Answered','Inbound Answered','Login Time','Not Ready','Idle Duration','Ring Duration','Talk Duration','Hold Duration','Wrap Up Duration'];
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

        $selected_report_columns = $this->GetReportColumn('select',$form_name,array_values($report_columns));

        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();

            // $dates = (isset($post['dates']) ? $post['dates'] : null);
            $start_date = (isset($post['start_date']) ? $post['start_date'] : null);
            $end_date = (isset($post['end_date']) ? $post['end_date'] : null);
            // $times = (isset($post['times']) ? $post['times'] : null);
            // $campaigns = (isset($post['campaigns']) ? array_filter($post['campaigns']) : []);
            $time_type = (isset($post['time_type']) && $post['time_type'] ? $post['time_type'] : 1);
            $campaigns = (isset($post['campaigns']) ? $post['campaigns'] : null);
            $queues = (isset($post['queues']) ? $post['queues'] : []);
            $users = (isset($post['users']) ? $post['users'] : []);
            //new devevlopment
            $report_columns = (isset($post['report_columns']) ? $post['report_columns'] : []);
            $formName = (isset($post['formName']) ? $post['formName'] : []);
            $update_column_selection = (isset($post['update_column_selection']) ? $post['update_column_selection'] : []);
            
            $foo =serialize($report_columns);
            $bar =serialize($selected_report_columns);
            if ($foo !== $bar){
                $this->GetReportColumn('update',$form_name,$report_columns);
            } 
            

            // $report_columns = $this->GetArrKeyValue($report_columns, $report_columns_keys);
            
            // if($update_column_selection){
            //     $this->GetReportColumn('update',$form_name,$report_columns);
            // } //else{
            //     $this->GetReportColumn('save',$form_name,$report_columns);
            // }
            // $this->GetReportColumn('1',$form_name,$report_columns);
            // exit;
            $campaign_names = [];
            if($campaigns){
                $campaign_detail = EdasCampaign::campaignsList($campaigns);
                $campaign_names = ArrayHelper::getColumn($campaign_detail, 'campaign_name');
            }

            if(!$users){
                if(!$queues){
                    if($campaigns){
                        $queue_list = VaaniCampaignQueue::queuesList($campaigns);
                        $queues = ArrayHelper::getColumn($queue_list, 'queue_id');
                    }else{
                        Yii::$app->session->setFlash('error', 'Kindly select the campaign!');
                        return $this->redirect('agent-performance-report');
                    }
                }
                $user_list = VaaniCampaignQueue::usersList($campaigns, $queues);
                $users = ArrayHelper::getColumn($users, 'user_id');
            }

            // fetch start & end dates
            /* if($dates){
                $dates = explode(' - ', $dates);
                if(isset($dates[0])) $start_date = $dates[0];
                if(isset($dates[1])) $end_date = $dates[1];
            } */
            
            $users = "".implode(",", $users)."";
            $campaigns = "'".implode(",", $campaigns)."'";
            $campaign_names = "'".strtolower(implode(",", $campaign_names))."'";
            
            $result = null;
            // if(isset($post['dates']) && $post['dates'] == (date('Y-m-d') . ' - ' . date('Y-m-d'))){
                if($time_type == 1){
                    // DAY WISE
                    $start_date = "'".$start_date." 00:00:00'";
                    $end_date = "'".$end_date." 23:59:59'";

                    // $result = \Yii::$app->db->createCommand('call usp_apr_aj("","",'.$start_date.','.$end_date.')')
                    $result = \Yii::$app->db->createCommand("
                        SELECT Date,'00:00-24:00' as 'Interval',agent_name as 'Agent',report.agent_id as 'agent_id',agent_calls as 'total_calls',Inbound_Answered,Outbound_Answered, '' as Login_Time
                        ,sec_TO_time(not_ready) as 'Not Ready','' as 'Idle Duration',
                        Ring_Duration,
                        Talk_Duration,
                        Hold_Duration,
                        Wrap_Up_Duration 
                        FROM
                        (select count(abc.agent_id) as 'agent_calls',abc.agent_name as 'agent_name', abc.agent_id as 'agent_id',Date(insert_date) as 'Date',convert(sec_to_time(sum(abc.ringing)),time) as 'Ring_Duration',
                        convert(sec_to_time(sum(abc.talk)),time) as 'Talk_Duration',
                        convert(sec_to_time(sum(abc.hold)),time) as 'Hold_Duration',
                        convert(sec_to_time(sum(abc.wrap)),time) as 'Wrap_Up_Duration',
                        COUNT(case when call_type=2 then abc.agent_id end) as 'Inbound_Answered',
                        COUNT(case when call_type IN (1,3,7,8) then abc.agent_id end) as 'Outbound_Answered'
                        from vaani_agent_call_report abc where abc.call_type IN (1,2,3,7,8) and agent_id IN ( " . $users . " ) and abc.insert_date between " . $start_date . " and " . $end_date . " and abc.campaign_name IN ( " . $campaign_names . " ) group by abc.agent_id)
                        report 
                        left join (SELECT agent_id,
                            SUM(duration) as not_ready
                            FROM vaani_break_log 
                                WHERE agent_id IN ( " . $users . " ) and date(start_time) between " . $start_date . " and " . $end_date . " GROUP BY vaani_break_log.agent_id) vbl
                                on report.agent_id = vbl.agent_id group by report.agent_id;
                    ")
                    ->queryAll();

                    // $result_users = ArrayHelper::getColumn($result, 'agent_id');

                    // fetch login data for the users
                    $login_query = \Yii::$app->pa_db->createCommand('
                        select user_id, sec_to_time(sum(TIME_TO_SEC(timediff( (case when logout_datetime is null then from_unixtime(last_action_epoch) else logout_datetime  end  ),login_datetime)))) as login_time
                        from asterisk.vaani_session
                        where user_id IN (' . $users . ') and created_datetime between ' . $start_date . ' and ' . $end_date . ' group by user_id')
                    ->queryAll();

                    // fetch idle data for the users
                    $idle_query = \Yii::$app->pa_db->createCommand('
                        SELECT user_id,
                        sec_to_time(SUM(case when status_id = 3 then 
                            (case when action_end_datetime is not null then action_duration_sec else time_to_sec(now()) - time_to_sec(action_start_datetime) end) 
                        end)) as idle_duration
                        FROM asterisk.vaani_session_log 
		                WHERE user_id IN (' . $users . ') and status_id in (3) and date(action_start_datetime) between ' . $start_date . ' and ' . $end_date . ' GROUP BY user_id')
                    ->queryAll();

                    $login_data = ArrayHelper::map($login_query, 'user_id', 'login_time');
                    $idle_data = ArrayHelper::map($idle_query, 'user_id', 'idle_duration');
                    
                    /* $result = \Yii::$app->db->createCommand('call usp_vaani_apr_report('.$users.','.$campaigns.','.$start_date.','.$end_date.')')
                    ->queryAll(); */
                    
                    // query to get total calls start here.
                    /* $query = \Yii::$app->db->createCommand('
                        SELECT count(distinct `unique_id`) as `count`, DATE(action_starttime) as cdate FROM vaani_agent_call_master where user_id IN ('.$users.') and action_starttime between '.$start_date.' and '.$end_date.' GROUP BY DATE(vaani_agent_call_master.action_starttime)
                    ')->queryAll();

                    echo "<pre>"; print_r($query);exit; */
                }elseif($time_type == 2){
                    Yii::$app->session->setFlash('warning', 'Report for past dates are under construction.');
                    // HOURLY
                    /* $result = \Yii::$app->db->createCommand('call ]usp_apr_hourly_report_new('.$users.','.$campaign_names.','.$start_date.','.$end_date.')')
                    ->queryAll(); */
                }
            /* }else{
                Yii::$app->session->setFlash('warning', 'Report for past dates are under construction.');
            } */

            if($campaigns){
                $campaign_list = explode(",", $campaigns);
                $campaign_arr = [];
                foreach ($campaign_list as $key => $camp) {
                    $camp = trim($camp, '\'"');
                    $campaignModel = EdasCampaign::find()->where(["campaign_id" => $camp])->one();
                    $campaign_arr[] = ($campaignModel ? $campaignModel->campaign_name : null);
                }
            }

           
            return $this->render('agent-performance-report', [
                'data' => $result,
                'login_data' => $login_data,
                'idle_data' => $idle_data,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'times' => $times,
                'campaign_list' => $campaign_arr,
                'queues' => $queues,
                'users' => $users,
                'report_columns' => $report_columns,
                'selected_report_columns' => '',
            ]);
        }else{
            // fetch list of campaigns
            $campaign_list = [];
            $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
            
            if($campaign_ids){
                $campaigns = EdasCampaign::campaignsList($campaign_ids, null);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }else if(isset($_SESSION['client_connection'])){
                $campaigns = EdasCampaign::campaignsList(null, $_SESSION['client_connection']);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }
            $queues = [];
            $users = [];
            
            return $this->render('agent-performance-form', [
                'campaigns' => $campaign_list,
                'queues' => $queues,
                'users' => $users,
                'report_columns' => $report_columns,
                'selected_report_columns' => $selected_report_columns,
            ]);
        }
    }

    // network lag report
    public function actionNetworkLagReport()
    {
        $user = Yii::$app->user->identity;
        $dates = date('Y-m-d') . ' - ' . date('Y-1-d');

        $searchModel = new NetworkLogSearch();
        $searchModel->dates = $dates;

        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // fetch list of agents
        /* $agents = [];

        $client_ids = null;
        if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
            $client_ids = $user->clientIds;
        }
        $agents = ArrayHelper::map(User::userList(null, null, true, $client_ids), 'user_id', 'user_name'); */
        $agents = [];

        $client_id = (isset($_SESSION['client_connection']) ? $_SESSION['client_connection'] : null);
        $role_id = VaaniRole::find()->where(['role_name' => 'agent'])->select(['role_id'])->asArray()->one();
        /*if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
            $client_id = $user->clientIds;
        }*/

        if($client_id){
            $agents = ArrayHelper::map(User::userList(null, $role_id['role_id'], true, $client_id), 'user_id', 'user_name');
        }

        return $this->render('network-lag-report', [
            'agents' => $agents,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // automatic call distribution ACD report
    public function actionAcdReport()
    {
        $user = Yii::$app->user->identity;

        $times = $campaigns = $queues = $users = null;
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
        $report_columns =['SR'=>'SR','Date'=>'Date',
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
                'Abandoned Percent'=>'Abandoned Percent'];

        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();
            $result = [];

            $start_date = (isset($post['start_date']) ? $post['start_date'] : null);
            $end_date = (isset($post['end_date']) ? $post['end_date'] : null);
            $start_time = (isset($post['start_time']) ? ($post['start_time']) : null);
            $end_time = (isset($post['end_time']) ? ($post['end_time']) : null);
            $time_type = (isset($post['time_type']) && $post['time_type'] ? $post['time_type'] : 1);
            // $campaign_type = (isset($post['campaign_type']) ? $post['campaign_type'] : null);
            $campaigns = (isset($post['campaigns']) ? $post['campaigns'] : null);
            $queues = (isset($post['queues']) ? $post['queues'] : []);
            $users = (isset($post['users']) ? $post['users'] : []);
            $report_columns = (isset($post['report_columns']) ? $post['report_columns'] : []);
            
            $campaign_names = [];
            if($campaigns){
                $campaign_detail = EdasCampaign::campaignsList($campaigns);
                $campaign_names = ArrayHelper::getColumn($campaign_detail, 'campaign_name');
            }
            $queue_names = [];
            if($queues){
                $queue_detail = VaaniCampaignQueue::queuesList(null, null, $queues);
                $queue_names = ArrayHelper::getColumn($queue_detail, 'queue');
            }

            if(!$users){
                if(!$queues){
                    if($campaigns){
                        $queue_list = VaaniCampaignQueue::queuesList($campaigns);
                        $queues = ArrayHelper::getColumn($queue_list, 'queue_id');
                        $queue_names = ArrayHelper::getColumn($queue_list, 'queue');
                    }else{
                        Yii::$app->session->setFlash('error', 'Kindly select the campaign!');
                        return $this->redirect('recordings');
                    }
                }
                $user_list = VaaniCampaignQueue::usersList($campaigns, $queues);
                $users = ArrayHelper::getColumn($users, 'user_id');
            }
            
            $users = ($users ? ("".implode(",", $users)."") : 'NULL');
            $queue_names = ($queue_names ? ("'".implode("','", $queue_names)."'") : 'NULL');
            $campaign_names = "'".strtolower(implode("','", $campaign_names))."'";
            $start_date = "'".$start_date." ".$start_time.":00'";
            $end_date = "'".$end_date." ".$end_time.":00'";
            
            if($time_type == 2){
                // HOURLY WISE
                $interval = "date_format(start_date,'%H %p')";
                $group_by = "date_format(start_date,'%H %p'), queue";
            }else{
                // DAY WISE
                $interval = "'". $start_time .'-'. $end_time . "'";
                $group_by = 'queue';
            }

            $result_query = "
                SELECT 
                    (`date`),
                    (`campaign`),
                    (`queue`),
                    (`start_date`),
                    (`end_date`),
                    sum(`offered`) as `offered`,
                    (`agent_count`),
                    sum(`agent_offered`) as `agent_offered`,
                    sum(`answered_in_10_sec`) as `answered_in_10_sec`,
                    sum(`answered_in_20_sec`) as `answered_in_20_sec`,
                    sum(`answered_in_30_sec`) as `answered_in_30_sec`,
                    sum(`answered_in_40_sec`) as `answered_in_40_sec`,
                    sum(`answered_in_50_sec`) as `answered_in_50_sec`,
                    sum(`answered_in_60_sec`) as `answered_in_60_sec`,
                    sum(`answered_in_90_sec`) as `answered_in_90_sec`,
                    sum(`answered_in_120_sec`) as `answered_in_120_sec`,
                    sum(`answered_after_120_sec`) as `answered_after_120_sec`,
                    sum(`calls_in_queue`) as `calls_in_queue`,
                    sec_to_time(sum(time_to_sec(`talk_time`))) as `talk_time`,
                    sec_to_time(sum(time_to_sec(`wrap_time`))) as `wrap_time`,
                    sec_to_time(sum(time_to_sec(`hold_time`))) as `hold_time`,
                    sum(`call_abandoned`) as `call_abandoned`,
                    sum(`abandoned_on_ivr`) as `abandoned_on_ivr`,
                    sum(`abandoned_on_agent`) as `abandoned_on_agent`,
                    sum(`abandoned_in_10_sec`) as `abandoned_in_10_sec`,
                    sum(`abandoned_in_20_sec`) as `abandoned_in_20_sec`,
                    sum(`abandoned_in_30_sec`) as `abandoned_in_30_sec`,
                    sum(`abandoned_in_40_sec`) as `abandoned_in_40_sec`,
                    sum(`abandoned_in_50_sec`) as `abandoned_in_50_sec`,
                    sum(`abandoned_in_60_sec`) as `abandoned_in_60_sec`,
                    sum(`abandoned_in_90_sec`) as `abandoned_in_90_sec`,
                    sum(`abandoned_in_120_sec`) as `abandoned_in_120_sec`,
                    sum(`abandoned_after_120_sec`) as `abandoned_after_120_sec`,
                    avg(`answered_percent`) as `answered_percent`,
                    avg(`abandoned_percent`) as `abandoned_percent`, 
                    ".$interval." as 'report_interval' 
                FROM vaani_acd_report 
                WHERE
                    end_date BETWEEN ".$start_date." and ".$end_date."
                    /* and (agent_id IN (".$users.") OR agent_id IS NULL) */
                    and queue IN (".$queue_names.")
                GROUP BY ".$group_by.";
            ";

            
            Yii::$app->Utility->addLog("** FETCH ACD REPORT QUERY ** " . $result_query, 'acd_report', \Yii::getAlias('@runtime') . "/logs/");      // LOG
            $result = \Yii::$app->db->createCommand($result_query)->queryAll();
            // echo "<pre>"; print_r($result);exit;
            
            return $this->render('acd-report', [
                'data' => $result,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'report_columns' => $report_columns,
            ]);
        }else{
            // fetch list of campaigns
            $campaign_list = [];
            $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
            
            if($campaign_ids){
                $campaigns = EdasCampaign::campaignsList($campaign_ids, null);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }else if(isset($_SESSION['client_connection'])){
                $campaigns = EdasCampaign::campaignsList(null, $_SESSION['client_connection']);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }
            $queues = [];
            $users = [];

            return $this->render('acd-form', [
                'campaigns' => $campaign_list,
                'queues' => $queues,
                'users' => $users,
                'report_columns' => $report_columns,
            ]);
        }
    }

    // automatic call distribution ACD report - NOT IN USE
    public function actionAcdReportOld()
    {
        $user = Yii::$app->user->identity;

        $times = $campaigns = $queues = $users = null;
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
        $report_columns =['Sr'=>'Sr','Date'=>'Date',
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
                'Abandoned Percen'=>'Abandoned Percent'];

        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();
            $result = [];

            $start_date = (isset($post['start_date']) ? $post['start_date'] : null);
            $end_date = (isset($post['end_date']) ? $post['end_date'] : null);
            $time_type = (isset($post['time_type']) && $post['time_type'] ? $post['time_type'] : 1);
            $campaigns = (isset($post['campaigns']) ? $post['campaigns'] : null);
            $queues = (isset($post['queues']) ? $post['queues'] : []);
            $users = (isset($post['users']) ? $post['users'] : []);
            $report_columns = (isset($post['report_columns']) ? $post['report_columns'] : []);
            
            $campaign_names = [];
            if($campaigns){
                $campaign_detail = EdasCampaign::campaignsList($campaigns);
                $campaign_names = ArrayHelper::getColumn($campaign_detail, 'campaign_name');
            }

            if(!$users){
                if(!$queues){
                    if($campaigns){
                        $queue_list = VaaniCampaignQueue::queuesList($campaigns);
                        $queues = ArrayHelper::getColumn($queue_list, 'queue_id');
                    }else{
                        Yii::$app->session->setFlash('error', 'Kindly select the campaign!');
                        return $this->redirect('acd-report');
                    }
                }
                $user_list = VaaniCampaignQueue::usersList($campaigns, $queues);
                $users = ArrayHelper::getColumn($users, 'user_id');
            }

            // fetch dni number
            $dni_numbers = [];
            $queue_ids = [];
            if($queues){
                $queue_list = VaaniCampaignQueue::queuesList(null, null, $queues);
                $dni_ids = ArrayHelper::getColumn($queue_list, 'dni_id');
                $queue_ids = ArrayHelper::getColumn($queue_list, 'queue_id');
                
                if($dni_ids){
                    $dni_list = EdasDniMaster::dniList($dni_ids);
                    foreach ($dni_list as $key => $dniData) {
                        if($dniData->DNI_other){
                            $dni_numbers[] = '+91'. $dniData->DNI_other;
                        }else{
                            for($i = $dniData->DNI_from; $i <= $dniData->DNI_to; $i++){
                                $dni_numbers[] = '+91'. $i;
                            }
                        }
                    }
                }
            }
            
            $users = "'".implode("','", $users)."'";
            $dni_numbers = "'".implode("','", $dni_numbers)."'";
            $queue_ids = "'".implode("','", $queue_ids)."'";

            $query = null;
            if($time_type == 1){
                $query = \Yii::$app->db->createCommand("SELECT
                inbound.date,
                inbound.queue as 'service',
                '00:00-24:00' as 'interval',
                (SELECT count(inb.id) FROM inbound_edas inb WHERE inb.queue = inbound.queue and inb.dni_number IN (" . $dni_numbers . ") and inb.date BETWEEN '" . $start_date . "' and '" . $end_date . "') as `calls_offered`,
                
                // (SELECT count(vua.id) FROM asterisk.vaani_user_access vua WHERE vua.queue_id = queue_data.queue_id and vua.user_id IN (" . $users . ") and vua.access_level = " . VaaniUserAccess::LEVEL_QUEUE . " and del_status = ". VaaniUserAccess::STATUS_NOT_DELETED .") as `agents`,

                'agents',

                COUNT(case when inbound.agent_id IN (" . $users . ") then inbound.id end) as 'agent_offered',
                COUNT(case when inbound.agent_id IN (" . $users . ") and inbound.queue_hold_time >= 0 and queue_hold_time <= 10 then inbound.id end) as 'answered_in_10_sec',
                COUNT(case when inbound.agent_id IN (" . $users . ") and inbound.queue_hold_time > 10 and queue_hold_time <= 20 then inbound.id end) as 'answered_in_20_sec',
                COUNT(case when inbound.agent_id IN (" . $users . ") and inbound.queue_hold_time > 20 and queue_hold_time <= 30 then inbound.id end) as 'answered_in_30_sec',
                COUNT(case when inbound.agent_id IN (" . $users . ") and inbound.queue_hold_time > 30 and queue_hold_time <= 40 then inbound.id end) as 'answered_in_40_sec',
                COUNT(case when inbound.agent_id IN (" . $users . ") and inbound.queue_hold_time > 40 and queue_hold_time <= 50 then inbound.id end) as 'answered_in_50_sec',
                COUNT(case when inbound.agent_id IN (" . $users . ") and inbound.queue_hold_time > 50 and queue_hold_time <= 60 then inbound.id end) as 'answered_in_60_sec',
                COUNT(case when inbound.agent_id IN (" . $users . ") and inbound.queue_hold_time > 60 and queue_hold_time <= 90 then inbound.id end) as 'answered_in_90_sec',
                COUNT(case when inbound.agent_id IN (" . $users . ") and inbound.queue_hold_time > 90 and queue_hold_time <= 120 then inbound.id end) as 'answered_in_120_sec',
                COUNT(case when inbound.agent_id IN (" . $users . ") and inbound.queue_hold_time > 120 then inbound.id end) as 'answered_>_120_sec',
                (SELECT count(inb2.id) FROM inbound_edas inb2 WHERE inb2.queue = inbound.queue and inb2.dni_number IN (" . $dni_numbers . ") and inb2.agent_id IS NULL and inb2.end_time IS NULL and inb2.date BETWEEN '" . $start_date . "' and '" . $end_date . "') as 'calls_in_queue',
                convert(sec_to_time(sum(report.talk)),time) as `talk_time`,
                convert(sec_to_time(sum(report.wrap)),time) as `wrap_time`,
                convert(sec_to_time(sum(report.hold)),time) as `hold_time`,
                COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL then inbound.id end) as 'call_abandoned',
                COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and hangup_by = 'CUSTOMER' then inbound.id end) as 'abandoned_on_ivr',
                COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and hangup_by = 'AGENT' then inbound.id end) as 'abandoned_on_agent',
                COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and time_to_sec(inbound.duration) >= 0 and time_to_sec(inbound.duration) <= 10 then inbound.id end) as 'abandoned_in_10_sec',
                COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and time_to_sec(inbound.duration) > 10 and time_to_sec(inbound.duration) <= 20 then inbound.id end) as 'abandoned_in_20_sec',
                COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and time_to_sec(inbound.duration) > 20 and time_to_sec(inbound.duration) <= 30 then inbound.id end) as 'abandoned_in_30_sec',
                COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and time_to_sec(inbound.duration) > 30 and time_to_sec(inbound.duration) <= 40 then inbound.id end) as 'abandoned_in_40_sec',
                COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and time_to_sec(inbound.duration) > 40 and time_to_sec(inbound.duration) <= 50 then inbound.id end) as 'abandoned_in_50_sec',
                COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and time_to_sec(inbound.duration) > 50 and time_to_sec(inbound.duration) <= 60 then inbound.id end) as 'abandoned_in_60_sec',
                COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and time_to_sec(inbound.duration) > 60 and time_to_sec(inbound.duration) <= 90 then inbound.id end) as 'abandoned_in_90_sec',
                COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and time_to_sec(inbound.duration) > 90 and time_to_sec(inbound.duration) <= 120 then inbound.id end) as 'abandoned_in_120_sec',
                COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and time_to_sec(inbound.duration) > 120 then inbound.id end) as 'abandoned_>_120_sec',
                (
                    (COUNT(case when inbound.agent_id IN (" . $users . ") then inbound.id end)) / 
	                (SELECT count(inb.id) FROM inbound_edas inb WHERE inb.queue = inbound.queue and inb.dni_number IN (" . $dni_numbers . ") and inb.date BETWEEN '" . $start_date . "' and '" . $end_date . "')
                )*100 as 'answered_percent',
                (
                    (COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL then inbound.id end)) / 
	                (SELECT count(inb.id) FROM inbound_edas inb WHERE inb.queue = inbound.queue and inb.dni_number IN (" . $dni_numbers . ") and inb.date BETWEEN '" . $start_date . "' and '" . $end_date . "')
                )*100 as 'abandoned_percent'

                FROM inbound_edas inbound
                // left join asterisk.vaani_campaign_queue queue_data on inbound.queue=queue_data.queue
                left join vaani_agent_call_report report on inbound.unique_id=report.unique_id 
                WHERE
                dni_number IN (" . $dni_numbers . ")
                and date BETWEEN '" . $start_date . "' and '" . $end_date . "'
                group by inbound.queue")
                ->queryAll();
            }

            // fetch login data for the users
            $agent_count_query = \Yii::$app->pa_db->createCommand("
                SELECT queue_data.queue, count(*) as 'agents' FROM asterisk.vaani_user_access vua
                left join asterisk.vaani_campaign_queue queue_data on vua.queue_id=queue_data.queue_id
                WHERE 
                vua.queue_id IN (" . $queue_ids . ") and vua.user_id IN (" . $users . ") and queue_data.del_status = ". VaaniUserAccess::STATUS_NOT_DELETED ." 
                group by vua.queue_id")
            ->queryAll();
            $agent_count = ArrayHelper::map($agent_count_query, 'queue', 'agents');

            // echo "<pre>";print_r($agent_count);exit;

            return $this->render('acd-report', [
                'data' => $query,
                'agent_count' => $agent_count,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'report_columns' => $report_columns,
            ]);
        }else{
            // fetch list of campaigns
            $campaign_list = [];
            $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
            
            if($campaign_ids){
                $campaigns = EdasCampaign::campaignsList($campaign_ids, null);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }else if(isset($_SESSION['client_connection'])){
                $campaigns = EdasCampaign::campaignsList(null, $_SESSION['client_connection']);
                $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
            }
            $queues = [];
            $users = [];

            return $this->render('acd-form', [
                'campaigns' => $campaign_list,
                'queues' => $queues,
                'users' => $users,
                'report_columns' => $report_columns,
            ]);
        }
    }

    public function GetArrKeyValue($arrValue,$arrKey){ 
        return $arrKey;
        $arr =[];
        foreach ($arrKey as $val) {
            array_push($arr,$arrValue[$val]);
        }
       return $arr;
    }

    public function GetReportColumn($action,$formName,$list_column){
        $app = Yii::$app->user->identity;
        $user = $app->userRole->role_name;
        $formname = $formName;
        
        if($action == 'select'){
            $reportModels = VaaniUserReportColumns::find()
            ->where(['formname' => $formname])
            ->where(['userid' => $user])
            ->all();
            if($reportModels){
                $arr = explode(',',$reportModels[0]->reportcolumn);
                return $arr;
            }else{ //echo "<pre>";print_r($list_column);exit;
                $model = new VaaniUserReportColumns();
                $model->userid = $user;
                $model->formname = $formname;
                $model->reportcolumn = implode(',', $list_column);
                if(!$model->save()){
                    echo "<pre>";print_r($model->errors);exit;
                }else{ 
                    $arr = explode(',',$model->reportcolumn);
                    return $arr;
                } 
            }
            
        } if($action == 'update'){
            $reportModels = VaaniUserReportColumns::find()
            ->where(['formname' => $formname])
            ->where(['userid' => $user])
            ->one(); //echo "<pre>";print_r($reportModels);exit;
            $reportModels->reportcolumn = implode(',', $list_column);
            // $reportModels->save();
            if(!$reportModels->save()){
                echo "<pre>";print_r($reportModels->errors);exit;
            }
            $arr = $reportModels->reportcolumn; //echo "<pre>";print_r($arr);exit;
            return $arr;
        }else{ //save
            $model = new VaaniUserReportColumns();
            $model->userid = $user;
            $model->formname = $formname;
            $model->reportcolumn = implode(',', $list_column);
            if(!$model->save()){
                echo "<pre>";print_r($model->errors);exit;
            }else{
                $arr = explode(',',$model[0]->reportcolumn);
                return $arr;
            }
        }
        
        
    }
    
    public function actionAgentLoginReport()
    {
        $user = Yii::$app->user->identity;
        
        $agents = [];

        $client_id = (isset($_SESSION['client_connection']) ? $_SESSION['client_connection'] : null);
        
        $role_id = VaaniRole::find()->select('role_id')->where(['role_name' => 'Agent','del_status' => VaaniRole::STATUS_NOT_DELETED]);

        if($client_id){
            $agents = ArrayHelper::map(User::userList(null, $role_id, true, $client_id), 'user_id', 'user_name');
        }

        $dates = date('Y-m-d') . ' - ' . date('Y-m-d');

        $searchModel = new VaaniSessionSearch();
        $searchModel->dates = $dates;
        $searchModel->is_report = true;
        $agentss = ArrayHelper::getColumn(User::userList(null, $role_id, true, $client_id), 'user_id');
        $agent_id = isset(Yii::$app->request->queryParams['VaaniSessionSearch']['user_id']) ? Yii::$app->request->queryParams['VaaniSessionSearch']['user_id'] : null ;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // FETCH TOTAL OF EACH SELECTED AGENTS
        $dates = explode(' - ', $searchModel->dates);
        $start_time = date('Y-m-d H:i:s');
        $end_time = date('Y-m-d H:i:s');

        if(isset($dates[0]) && isset($dates[1])){
            $start_time = $dates[0] . ' 00:00:00';
            $end_time = $dates[1] . ' 23:59:59';
        }

        $total_login = \Yii::$app->pa_db->createCommand("Select 
            user_id, 
            sec_to_time(sum(TIME_TO_SEC(
                case 
                    when logout_datetime is null 
                        then sec_to_time(time_to_sec(NOW()) - time_to_sec(login_datetime))
                    else
                        timediff(logout_datetime, login_datetime)
                end
            ))) as total_login 
        FROM vaani_session 
        where 
            user_id IN ( '". (is_array($searchModel->user_id) ? (implode("','", $searchModel->user_id)) : $searchModel->user_id) ."') AND (`last_action_epoch` BETWEEN '" . strtotime($start_time) . "' AND '" . strtotime($end_time) . "') 
        GROUP BY user_id;")->queryAll();

        if($total_login){
            $total_login = array_combine(array_column($total_login, 'user_id'), array_column($total_login, 'total_login'));
        }
        
        return $this->render('agent-login-report', [
            'agents' => $agents,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total_login' => $total_login,
        ]);
    }
    
    public function actionCrmHistoryReport()
    {
        $user = Yii::$app->user->identity;
        $post = Yii::$app->request->post();
        $date = isset($post['CrmHistoryReport']['dates']) ? $post['CrmHistoryReport']['dates'] : null ;
        if($date){
            $start_time = $date . ' 00:00:00';
            $end_time = $date . ' 23:59:59';
            $connection = \Yii::$app->db;
            $dataProvider = $connection->createCommand('SELECT * from customer WHERE insertdate BETWEEN :start_time AND  :end_time')->bindValue(':start_time', $start_time)->bindValue(':end_time', $end_time)->queryAll();
            
        }else{
            $dates = date('Y-m-d');
            $start_time = $dates . ' 00:00:00';
            $end_time = $dates . ' 23:59:59';
            $connection = \Yii::$app->db; 
            $dataProvider = $connection->createCommand('SELECT * FROM customer WHERE insertdate BETWEEN :start_time AND  :end_time')->bindValue(':start_time', $start_time)->bindValue(':end_time', $end_time)->queryAll();
        }
      return $this->render('crm-history-report', [
             'dataProvider' => $dataProvider,
             'date' => $date,
        ]);
    }

    // fetch agent details hourly cron
    public function actionCron()
    {
        exit;
        $clients = VaaniClientMaster::find()->where(['del_status' => VaaniClientMaster::STATUS_NOT_DELETED])->asArray()->all();
        
        if(isset($clients)){
            // $start_date = "'".date('Y-m-d H:i:s', strtotime('-30 minutes'))."'";
            // $end_date = "'".date('Y-m-d H:i:s', strtotime('-15 minutes'))."'";
            $start_date = "'".date('Y-m-06 17:15:00', strtotime('-30 minutes'))."'";
            $end_date = "'".date('Y-m-06 17:30:00', strtotime('-15 minutes'))."'";
            $interval = "'" . date('H:i', strtotime('-30 minutes')) . "-" . date('H:i', strtotime('-15 minutes')) . "'";
        
            foreach ($clients as $client) {
                
                $result = null;
                $campaign_list = null;
                $queue_list = null;
                $queue_names = null;
                $agent_list = null;
                $queue_ids = null;
                $queues = null;

                // fetch client connections
                $db_name = User::decrypt_data($client['db']);
                $db_host = User::decrypt_data($client['server']);
                $db_username = User::decrypt_data($client['username']);
                $db_password = User::decrypt_data($client['password']);

                \Yii::$app->db->close(); // make sure it clean
                \Yii::$app->db->dsn = 'mysql:host='.$db_host.';dbname='.$db_name;
                \Yii::$app->db->username = $db_username;
                \Yii::$app->db->password = $db_password;

                // fetch client cron data
                $createe = \Yii::$app->db->createCommand('
                    CREATE TABLE IF NOT EXISTS `vaani_client_cron` (
                        `id` INT NOT NULL AUTO_INCREMENT,
                        `client_id` VARCHAR(45) NULL,
                        `client_name` VARCHAR(45) NULL,
                        `cron_name` VARCHAR(45) NULL,
                        `last_executed_at` VARCHAR(45) NULL,
                        `records_executed` VARCHAR(20) NULL, 
                        `executed_first_id` VARCHAR(20) NULL,
                        `executed_last_id` VARCHAR(20) NULL,
                        `status` INT NULL DEFAULT 1,
                        PRIMARY KEY (`id`),
                        UNIQUE INDEX `id_UNIQUE` (`id` ASC));
                ')->execute();

                $last_cron = \Yii::$app->db->createCommand('
                    select id
                    from vaani_client_cron
                    where client_id = "' . $client['client_id'] . '" AND cron_name = "apr_cron" and last_executed_at between ' . $start_date . ' and ' . $end_date . ' ')
                ->queryOne();

                if(!$last_cron){
                    $records_executed = 0;
                    $executed_first_id = 0;
                    $executed_last_id = 0;

                    $campaigns = EdasCampaign::campaignsList(null, $client['client_id']);
                    $campaign_list = ArrayHelper::getColumn($campaigns, 'campaign_id');

                    if($campaign_list){
                        $queues = VaaniCampaignQueue::find()
                                ->innerJoinWith('campaign')
                                ->where(['vaani_campaign_queue.del_status' => VaaniCampaignQueue::STATUS_NOT_DELETED])
                                ->andWhere(['IN', 'vaani_campaign_queue.campaign_id', $campaign_list])
                                ->andWhere(['NOT IN', 'vaani_campaign_queue.campaign_id', ''])->asArray()->all();

                        $queue_list = ArrayHelper::map($queues, 'queue_id', 'queue');
                        $queue_ids = ArrayHelper::getColumn($queues, 'queue_id');
                        $queue_names = ArrayHelper::getColumn($queues, 'queue');
                    }
                    if($queue_ids){
                        $users = VaaniCampaignQueue::usersList($campaign_list, $queue_ids, User::agentRoleId());
                        $agent_list = ArrayHelper::getColumn($users, 'user_id');
                    }

                    $agents = "'".strtolower(implode("','", $agent_list))."'";
                    $queue_names = "'".implode("','", $queue_names)."'";

                    // create temp table
                    $temp_query = \Yii::$app->db->createCommand("DROP TABLE if exists tempAprTable;")->execute();
                    $temp_query = \Yii::$app->db->createCommand("CREATE TEMPORARY TABLE tempAprTable (
                        `id` int NOT NULL,
                        `agent_id` varchar(45) DEFAULT NULL,
                        `unique_id` varchar(45) DEFAULT NULL,
                        `agent_name` varchar(100) NOT NULL,
                        `queue_name` varchar(100) NOT NULL,
                        `caller_id` varchar(50) NOT NULL,
                        `disposition` varchar(200) NOT NULL,
                        `ringing` varchar(20) DEFAULT NULL,
                        `incall` varchar(20) DEFAULT NULL,
                        `talk` varchar(20) DEFAULT NULL,
                        `hold` varchar(20) DEFAULT NULL,
                        `transfer` varchar(20) DEFAULT NULL,
                        `conference` varchar(20) DEFAULT NULL,
                        `consult` varchar(20) DEFAULT NULL,
                        `dispo` varchar(20) DEFAULT NULL,
                        `wrap` varchar(20) DEFAULT NULL,
                        `not_ready` varchar(20) DEFAULT NULL,
                        `insert_date` datetime DEFAULT NULL,
                        `updated_date` datetime NOT NULL,
                        `campaign_name` varchar(100) DEFAULT NULL,
                        `call_type` INT NULL,
                        PRIMARY KEY(id)
                    ) ENGINE=InnoDB")
                        ->execute();
                        
                    // insert into temp table from vaani_agent_call_report table
                    $temp_query = \Yii::$app->db->createCommand("
                        INSERT INTO tempAprTable
                        ( `id`, `agent_id`, `unique_id`, `agent_name`, `queue_name`, `caller_id`, `disposition`, `ringing`, `incall`, `talk`, `hold`, `transfer`, `conference`, `consult`, `dispo`, `wrap`, `not_ready`, `insert_date`, `updated_date`, `campaign_name`, `call_type` )
                        SELECT 
                            vacr.`id`, vacr.`agent_id`, vacr.`unique_id`, vacr.`agent_name`, vacr.`queue_name`, vacr.`caller_id`, vacr.`disposition`, vacr.`ringing`, vacr.`incall`, vacr.`talk`, vacr.`hold`, vacr.`transfer`, vacr.`conference`, vacr.`consult`, vacr.`dispo`, vacr.`wrap`, /* SUM(vbl.duration) AS `not_ready` */ '', vacr.`insert_date`, vacr.`updated_date`, vacr.`campaign_name`, vacr.`call_type`
                        FROM
                            `vaani_agent_call_report` vacr
                                /* LEFT JOIN
                            vaani_break_log vbl ON vacr.agent_id = vbl.agent_id */
                        WHERE
                            vacr.call_type IN (1 , 2, 3, 7, 8)
                            AND vacr.agent_id IN (" . $agents . ")
                            AND vacr.insert_date BETWEEN " . $start_date . " AND " . $end_date . "
                            AND vacr.queue_name IN (" . $queue_names . ")
                    ")->execute();

                    $temp_data_query = \Yii::$app->db->createCommand("SELECT count(*) as records_executed, MIN(id) as executed_first_id, MAX(id) as executed_last_id FROM tempAprTable")->queryOne();

                    if($temp_data_query){
                        $records_executed = $temp_data_query['records_executed'];
                        $executed_first_id = $temp_data_query['executed_first_id'];
                        $executed_last_id = $temp_data_query['executed_last_id'];
                    }
                
                    foreach ($queues as $k => $queue) {
                        $users = VaaniCampaignQueue::usersList(null, $queue['queue_id'], User::agentRoleId());
                        $agent_list = ArrayHelper::getColumn($users, 'user_id');
                        $agents = "'".strtolower(implode("','", $agent_list))."'";

                        // FETCH CALCULATED DATA FROM TEMP TABLE
                        $result = \Yii::$app->db->createCommand("
                            SELECT Date,".$interval." as 'Interval',agent_name as 'Agent',report.agent_id as 'agent_id',agent_calls as 'total_calls',Inbound_Answered,Outbound_Answered, '".$queue['queue']."' as Queue
                            ,/* sec_TO_time(not_ready) as 'Not Ready' */ '','' as 'Idle Duration',
                            Ring_Duration,
                            Talk_Duration,
                            Hold_Duration,
                            Wrap_Up_Duration 
                            FROM
                            (select count(abc.agent_id) as 'agent_calls',abc.agent_name as 'agent_name', abc.agent_id as 'agent_id',Date(insert_date) as 'Date',convert(sec_to_time(sum(abc.ringing)),time) as 'Ring_Duration',
                            convert(sec_to_time(sum(abc.talk)),time) as 'Talk_Duration',
                            convert(sec_to_time(sum(abc.hold)),time) as 'Hold_Duration',
                            convert(sec_to_time(sum(abc.wrap)),time) as 'Wrap_Up_Duration',
                            COUNT(case when call_type=2 then abc.agent_id end) as 'Inbound_Answered',
                            COUNT(case when call_type IN (1,3,7,8) then abc.agent_id end) as 'Outbound_Answered'
                            FROM tempAprTable abc 
                            WHERE abc.call_type IN (1,2,3,7,8) and agent_id IN ( " . $agents . " ) and abc.insert_date between " . $start_date . " and " . $end_date . " and abc.queue_name IN ( '" . $queue['queue'] . "' ) group by abc.agent_id)
                            report;
                        ")
                        ->queryAll();

                        // NOT READY DURATION
                        $not_ready_query = \Yii::$app->db->createCommand("
                            SELECT agent_id, sec_TO_time(SUM(duration)) as not_ready FROM vaani_break_log vbl where vbl.agent_id IN (" . $agents . ") AND vbl.campaign_id IN ( '" . $queue['campaign_id'] . "' ) AND vbl.end_time IS NOT NULL AND vbl.end_time BETWEEN " . $start_date . " AND " . $end_date . "")->queryAll();
                
                        // fetch login data for the users
                        $login_query = \Yii::$app->pa_db->createCommand('
                            select user_id as agent_id, sec_to_time(sum(TIME_TO_SEC(timediff( (case when logout_datetime is null then from_unixtime(last_action_epoch) else logout_datetime  end  ),login_datetime)))) as login_time
                            from asterisk.vaani_session
                            where user_id IN (' . $agents . ') AND campaign IN ( "' . $queue["campaign_id"] . '" ) and created_datetime between ' . $start_date . ' and ' . $end_date . ' group by user_id')
                        ->queryAll();
                        // fetch idle data for the users
                        $idle_query = \Yii::$app->pa_db->createCommand('
                            SELECT log.user_id as agent_id,
                            vaani_session.campaign,
                            sec_to_time(SUM(case when status_id = 3 then 
                                (case when action_end_datetime is not null then action_duration_sec else time_to_sec(now()) - time_to_sec(action_start_datetime) end) 
                            end)) as idle_duration
                            FROM asterisk.vaani_session_log log
                            LEFT JOIN asterisk.vaani_session ON vaani_session.unique_id = log.unique_id
                            WHERE log.user_id IN (' . $agents . ') and log.status_id in (3) and (action_end_datetime) between ' . $start_date . ' and ' . $end_date . ' AND vaani_session.campaign IN ( "' . $queue["campaign_id"] . '" ) GROUP BY log.user_id')
                        ->queryAll();

                        $not_ready_data = ArrayHelper::map($not_ready_query, 'agent_id', 'not_ready');
                        $login_data = ArrayHelper::map($login_query, 'agent_id', 'login_time');
                        $idle_data = ArrayHelper::map($idle_query, 'agent_id', 'idle_duration');
        
                        // insert into vaani_apr_report table
                        for ($i = 0; $i < count($result); $i++) {
                            $final_result = \Yii::$app->db->createCommand("
                                INSERT INTO vaani_apr_report
                                ( `agent_id`, `queue_id`, `queue`, `date`, `interval`, `agent_name`, `total_calls`, `inbound_answered`, `outbound_answered`, `login_time`, `not_ready`, `idle_duration`, `ring_duration`, `talk_duration`, `hold_duration`, `wrap_up_duration`, `inserted_at`, `status` )
                                VALUES
                                (
                                    ".$result[$i]['agent_id'].",
                                    '".$queue['queue_id']."',
                                    '".$queue['queue']."',
                                    '".$result[$i]['Date']."',
                                    '".$result[$i]['Interval']."',
                                    '".$result[$i]['Agent']."',
                                    ".$result[$i]['total_calls'].",
                                    ".$result[$i]['Inbound_Answered'].",
                                    ".$result[$i]['Outbound_Answered'].",
                                    '".$login_data[$result[$i]['agent_id']]."',
                                    '".$not_ready_data[$result[$i]['agent_id']]."',
                                    '".$idle_data[$result[$i]['agent_id']]."',
                                    '".$result[$i]['Ring_Duration']."',
                                    '".$result[$i]['Talk_Duration']."',
                                    '".$result[$i]['Hold_Duration']."',
                                    '".$result[$i]['Wrap_Up_Duration']."',
                                    '".date('Y-m-d H:i:s', time())."',
                                    1
                                )
                            ")->execute();
                        }
                        
                    }
                    
                    // insert cron record
                    $add_cron = \Yii::$app->db->createCommand('
                        INSERT INTO vaani_client_cron
                        ( `client_id`, `client_name`, `cron_name`, `last_executed_at`, `records_executed`, `executed_first_id`, `executed_last_id`, `status` )
                        VALUES
                        (
                            "'.$client['client_id'].'",
                            "'.$client['client_name'].'",
                            "apr_cron",
                            "'.$end_date.'",
                            "'.$records_executed.'",
                            "'.$executed_first_id.'",
                            "'.$executed_last_id.'",
                            1,
                        )
                        ')
                    ->execute();

                    // DROP TEMPORARY TABLE
                    $drop_temp = \Yii::$app->db->createCommand("DROP TEMPORARY TABLE tempAprTable;")->execute();
                }
            }
        }
        // echo "<pre>"; print_r($result);
        exit;
    }
    
    // fetch call register details cron
    public function actionCallRegister()
    {
        $clients = VaaniClientMaster::find()->where(['del_status' => VaaniClientMaster::STATUS_NOT_DELETED])->asArray()->all();
        
        if(isset($clients)){
            // $start_date = "'".date('Y-m-d H:i:s', strtotime('-15 minutes'))."'";
            // $end_date = "'".date('Y-m-d H:i:s', time())."'";
            // $interval = "'" . date('H:i', strtotime('-15 minutes')) . "-" . date('H:i', time()) . "'";
            $start_date = "'".date('Y-m-18 17:15:01')."'";
            $end_date = "'".date('Y-m-18 17:30:00')."'";
            $interval = "'17:15-17:30'";
        
            foreach ($clients as $client) {
                
                $result = null;
                $campaign_list = null;
                $queue_list = null;
                $queue_names = null;
                $agent_list = null;
                $queue_ids = null;
                $queues = null;
                
                // fetch client connections
                $db_name = User::decrypt_data($client['db']);
                $db_host = User::decrypt_data($client['server']);
                $db_username = User::decrypt_data($client['username']);
                $db_password = User::decrypt_data($client['password']);

                \Yii::$app->db->close(); // make sure it clean
                \Yii::$app->db->dsn = 'mysql:host='.$db_host.';dbname='.$db_name;
                \Yii::$app->db->username = $db_username;
                \Yii::$app->db->password = $db_password;

                // create call_register report table if not exist
                $create_call_register_query = 'CREATE TABLE IF NOT EXISTS `vaani_call_register_report` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `unique_id` VARCHAR(45) NULL,
                    `agent_id` VARCHAR(45) NULL,
                    `agent_name` VARCHAR(45) NULL,
                    `campaign` VARCHAR(45) NULL,
                    `queue` VARCHAR(45) NULL,
                    `date` VARCHAR(45) NULL,
                    `interval` VARCHAR(45) NULL,
                    `start_date` VARCHAR(45) NULL,
                    `end_date` VARCHAR(45) NULL,
                    `time` VARCHAR(45) NULL,
                    `start_time` VARCHAR(45) NULL,
                    `end_time` VARCHAR(45) NULL,
                    `cli` VARCHAR(45) NULL,
                    `call_type` VARCHAR(45) NULL,
                    `call_status` VARCHAR(45) NULL,
                    `dni` VARCHAR(45) NULL,
                    `disposition` VARCHAR(45) NULL,
                    `duration` VARCHAR(45) NULL,
                    `hold_duration` VARCHAR(45) NULL,
                    `ring_duration` VARCHAR(45) NULL,
                    `talk_duration` VARCHAR(45) NULL,
                    `wrap_duration` VARCHAR(45) NULL,
                    `recording_path` VARCHAR(200) NULL,
                    `inserted_at` VARCHAR(45) NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `id_UNIQUE` (`id` ASC))
                    COMMENT = "Stores calculated call register report values from cron";';
                
                Yii::$app->Utility->addLog("** CREATE CALL REGISTER TABLE QUERY ** " . $create_call_register_query, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");    //log
                $create_call_register_table = \Yii::$app->db->createCommand($create_call_register_query)->execute();

                // create client cron table if not exist
                $create_cron_table = \Yii::$app->db->createCommand('
                    CREATE TABLE IF NOT EXISTS `vaani_client_cron` (
                        `id` INT NOT NULL AUTO_INCREMENT,
                        `client_id` VARCHAR(45) NULL,
                        `client_name` VARCHAR(45) NULL,
                        `cron_name` VARCHAR(45) NULL,
                        `last_executed_at` VARCHAR(45) NULL,
                        `records_executed` VARCHAR(20) NULL, 
                        `executed_first_id` VARCHAR(20) NULL,
                        `executed_last_id` VARCHAR(20) NULL,
                        `status` INT NULL DEFAULT 1,
                        PRIMARY KEY (`id`),
                        UNIQUE INDEX `id_UNIQUE` (`id` ASC));
                ')->execute();

                // fetch client cron data
                // $check_start_date = "'".date('Y-m-d H:i:59', strtotime('-15 minutes'))."'";
                // $check_end_date = "'".date('Y-m-d H:i:59', time())."'";

                $last_cron_query = 'select id
                from vaani_client_cron
                where client_id = "' . $client['client_id'] . '" AND cron_name = "call_register_cron" and last_executed_at between ' . $start_date . ' and ' . $end_date . ' ';
                
                Yii::$app->Utility->addLog("FETCH LAST CRON QUERY => " . $last_cron_query, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                $last_cron = \Yii::$app->db->createCommand($last_cron_query)->queryOne();

                if(!$last_cron){
                    $records_executed = 0;
                    $executed_first_id = 0;
                    $executed_last_id = 0;

                    $campaigns = EdasCampaign::campaignsList(null, $client['client_id']);
                    $campaign_list = ArrayHelper::getColumn($campaigns, 'campaign_id');

                    if($campaign_list){
                        $queues = VaaniCampaignQueue::find()
                                ->innerJoinWith('campaign')
                                ->where(['vaani_campaign_queue.del_status' => VaaniCampaignQueue::STATUS_NOT_DELETED])
                                ->andWhere(['IN', 'vaani_campaign_queue.campaign_id', $campaign_list])
                                ->andWhere(['NOT IN', 'vaani_campaign_queue.campaign_id', ''])->asArray()->all();

                        $queue_list = ArrayHelper::map($queues, 'queue_id', 'queue');
                        $queue_ids = ArrayHelper::getColumn($queues, 'queue_id');
                        $queue_names = ArrayHelper::getColumn($queues, 'queue');
                    }
                    if($queue_ids){
                        $users = VaaniCampaignQueue::usersList($campaign_list, $queue_ids, User::agentRoleId());
                        $agent_list = ArrayHelper::getColumn($users, 'user_id');
                    }

                    $agents = "'".strtolower(implode("','", $agent_list))."'";
                    $queue_names = "'".implode("','", $queue_names)."'";

                    // create temp table
                    $temp_query = \Yii::$app->db->createCommand("DROP TABLE if exists tempAprTable;")->execute();
                    
                    $temp_query_string = "CREATE TEMPORARY TABLE tempAprTable (
                        `id` int NOT NULL,
                        `agent_id` varchar(45) DEFAULT NULL,
                        `unique_id` varchar(45) DEFAULT NULL,
                        `agent_name` varchar(100) NOT NULL,
                        `date` varchar(45) DEFAULT NULL,
                        `time` varchar(45) DEFAULT NULL,
                        `start_time` varchar(45) DEFAULT NULL,
                        `end_time` varchar(45) DEFAULT NULL,
                        `duration` varchar(45) DEFAULT NULL,
                        `campaign` varchar(100) DEFAULT NULL,
                        `queue` varchar(100) DEFAULT NULL,
                        `ring_duration` varchar(45) DEFAULT NULL,
                        `queue_hold_time` varchar(45) DEFAULT NULL,
                        `wrap_duration` varchar(45) DEFAULT NULL,
                        `mobile_no` varchar(45) DEFAULT NULL,
                        `status` varchar(20) DEFAULT NULL,
                        `dni_number` varchar(45) DEFAULT NULL,
                        `disposition` varchar(50) DEFAULT NULL,
                        `recording_path` varchar(200) DEFAULT NULL,
                        `call_type` varchar(20) DEFAULT NULL
                    ) ENGINE=InnoDB";
                    
                    Yii::$app->Utility->addLog("** CREATE TEMP TABLE QUERY ** " . $temp_query_string, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query = \Yii::$app->db->createCommand($temp_query_string)->execute();
                    
                    // insert into temp table from inbound/manual/preview table
                    // inbound
                    $temp_query_string = "INSERT INTO tempAprTable
                    ( `id`, `agent_id`, `unique_id`, `agent_name`, `date`, `time`, `start_time`, `end_time`, `duration`, `campaign`, `queue`, `ring_duration`, `queue_hold_time`, `wrap_duration`, `mobile_no`, `status`, `dni_number`, `disposition`, `recording_path`, `call_type`)
                    SELECT 
                        inb.`id`, inb.`agent_id`, inb.`unique_id`, `agent_name`, `date`, `time`, `start_time`, `end_time`, `duration`, `campaign`, `queue`, '00:00:00', `queue_hold_time`, `action_duration`, `mobile_no`, 'ANSWERED', `dni_number`, '', `recording_path`, 'Inbound'
                    FROM
                        `inbound_edas` inb
                        left join vaani_agent_call_master vacm on vacm.unique_id = inb.unique_id
                        WHERE
                        inb.agent_id IN (" . $agents . ")
                        and vacm.call_action = 'wrap'
                        AND inb.end_time BETWEEN " . $start_date . " AND " . $end_date . "
                        AND inb.queue IN (" . $queue_names . ")";
                    
                    Yii::$app->Utility->addLog("** INSERT INTO TEMP TABLE QUERY FROM INBOUND ** " . $temp_query_string, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query = \Yii::$app->db->createCommand($temp_query_string)->execute();
                    
                    // manual
                    $temp_query_string = "INSERT INTO tempAprTable
                    ( `id`, `agent_id`, `unique_id`, `agent_name`, `date`, `time`, `start_time`, `end_time`, `duration`, `campaign`, `queue`, `ring_duration`, `queue_hold_time`, `wrap_duration`, `mobile_no`, `status`, `dni_number`, `disposition`, `recording_path`, `call_type`)
                    SELECT 
                        mdd.`id`, mdd.`agent_id`, mdd.`unique_id`, `agent_name`, `date`, `time`, `start_time`, `end_time`, `duration`, `campaign`, `queue_name`, `ring_duration`, '', `action_duration`, `mobile_no`, `status`, '', '', `recording_path`, 'Manual'
                    FROM
                        `manual_dial_detail` mdd
                        left join vaani_agent_call_master vacm on vacm.unique_id = mdd.unique_id
                        WHERE
                        mdd.agent_id IN (" . $agents . ")
                        and vacm.call_action = 'wrap'
                        AND end_time BETWEEN " . $start_date . " AND " . $end_date . "
                        AND queue_name IN (" . $queue_names . ")";
                    
                    Yii::$app->Utility->addLog("** INSERT INTO TEMP TABLE QUERY FROM MANUAL TABLE ** " . $temp_query_string, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query = \Yii::$app->db->createCommand($temp_query_string)->execute();

                    // preview
                    $temp_query_string = "INSERT INTO tempAprTable
                    ( `id`, `agent_id`, `unique_id`, `agent_name`, `date`, `time`, `start_time`, `end_time`, `duration`, `campaign`, `queue`, `ring_duration`, `queue_hold_time`, `wrap_duration`, `mobile_no`, `status`, `dni_number`, `disposition`, `recording_path`, `call_type`)
                    SELECT 
                        vpdd.`id`, vpdd.`agent_id`, vpdd.`unique_id`, `agent_name`, `date`, `time`, `start_time`, `end_time`, `duration`, `campaign`, `queue_name`, `ring_duration`, '', `action_duration`, `mobile_no`, `status`, '', '', `recording_path`, 'Outbound'
                    FROM
                        `vaani_preview_dial_detail` vpdd
                        left join vaani_agent_call_master vacm on vacm.unique_id = vpdd.unique_id
                        WHERE
                        vpdd.agent_id IN (" . $agents . ")
                        and vacm.call_action = 'wrap'
                        AND end_time BETWEEN " . $start_date . " AND " . $end_date . "
                        AND queue_name IN (" . $queue_names . ")";
                    
                    Yii::$app->Utility->addLog("** INSERT INTO TEMP TABLE QUERY FROM PREVIEW ** " . $temp_query_string, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query = \Yii::$app->db->createCommand($temp_query_string)->execute();

                    // CALCULATE RECORDS EXECUTED COUNT AND IDS
                    $temp_data_query_string = "SELECT count(*) as records_executed, MIN(id) as executed_first_id, MAX(id) as executed_last_id FROM tempAprTable";

                    Yii::$app->Utility->addLog("** FETCH RECORDS EXECUTED QUERY ** " . $temp_data_query_string, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_data_query = \Yii::$app->db->createCommand($temp_data_query_string)->queryOne();

                    /* if($temp_data_query){
                        echo "<pre>"; print_r($temp_data_query);exit;
                    } */
                    
                    if($temp_data_query){
                        $records_executed = $temp_data_query['records_executed'];
                        $executed_first_id = $temp_data_query['executed_first_id'];
                        $executed_last_id = $temp_data_query['executed_last_id'];
                    }
                    
                    foreach ($queues as $k => $queue) {
                        $users = VaaniCampaignQueue::usersList(null, $queue['queue_id'], User::agentRoleId());
                        $agent_list = ArrayHelper::getColumn($users, 'user_id');
                        $agents = "'".strtolower(implode("','", $agent_list))."'";

                        foreach ($agent_list as $i => $agent_id) {
                            $final_result_query = "INSERT INTO vaani_call_register_report
                            ( `unique_id`, `agent_id`, `agent_name`, `campaign`, `queue`, `date`, `interval`, `start_date`, `end_date`, `time`, `start_time`, `end_time`, `cli`, `call_type`, `call_status`, `dni`, `disposition`, `duration`, `hold_duration`, `ring_duration`, `talk_duration`, `wrap_duration`, `recording_path`, `inserted_at` )
                            SELECT 
                                `unique_id`, `agent_id`, `agent_name`, `campaign`,`queue`, `date`, ".$interval.", ".$start_date .", ".$end_date.", `time`, `start_time`, `end_time`, `mobile_no`, `call_type`, `status`, `dni_number`, `disposition`, 
                                (case when `duration` is not null then `duration` else sec_to_time(time_to_sec(`end_time`) - time_to_sec(`time`)) end), 
                                sec_to_time(`queue_hold_time`), 
                                sec_to_time(`ring_duration`), 
                                sec_to_time((case when `duration` is not null then time_to_sec(`duration`) else (time_to_sec(`end_time`) - time_to_sec(`time`)) end) - `queue_hold_time`),
                                sec_to_time(wrap_duration),
                                `recording_path`,
                                '".date('Y-m-d H:i:s', time())."'
                            FROM
                                `tempAprTable`
                            WHERE
                                agent_id = " . $agent_id;
                            
                            Yii::$app->Utility->addLog("** INSERT RECORD INTO CALL REGISTER TABLE QUERY ** " . $final_result_query, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                            $final_result = \Yii::$app->db->createCommand($final_result_query)->execute();
                            
                            if($final_result){
                                echo "Data Inserted!";
                            }
                        }
                    }
                    
                    // insert cron record
                    $add_cron_query = 'INSERT INTO vaani_client_cron
                    ( `client_id`, `client_name`, `cron_name`, `last_executed_at`, `records_executed`, `executed_first_id`, `executed_last_id`, `status` )
                    VALUES
                    (
                        "'.$client['client_id'].'",
                        "'.$client['client_name'].'",
                        "call_register_cron",
                        '.$end_date.',
                        "'.$records_executed.'",
                        "'.$executed_first_id.'",
                        "'.$executed_last_id.'",
                        1
                    )';

                    Yii::$app->Utility->addLog("** INSERT CRON RECORD QUERY ** " . $add_cron_query, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $add_cron = \Yii::$app->db->createCommand($add_cron_query)
                    ->execute();

                    if($add_cron){
                        echo "Cron data added!";
                    }

                    // DROP TEMPORARY TABLE
                    $drop_temp = \Yii::$app->db->createCommand("DROP TEMPORARY TABLE tempAprTable;")->execute();
                    if($drop_temp){
                        return "Temp Table deleted!";
                    }
                }
            }
        }
    }

    // generate custom report (15 mins range only)
    public function actionCustomGenerate()
    {
        if($this->request->isPost && $this->request->post()){
            $post = $this->request->post();
            // echo "<pre>";print_r($post);exit;

            $start_date = "'".$post['start_date']." ".$post['start_time'].":00'";
            $end_date = "'".$post['start_date']." ".$post['end_time'].":00'";
            $interval = "'".$post['start_time']."-".$post['end_time']."'";

            if($post['report_type'] == 1){                  // APR REPORT

            }else if($post['report_type'] == 2){            // ACD REPORT
                
                $consoleController = new \console\controllers\CronController('cron', Yii::$app);
                $result = $consoleController->runAction('acd', [
                            $start_date,
                            $end_date,
                            $interval
                        ]);
                        
                Yii::$app->session->setFlash('warning', $result);
        
            }else if($post['report_type'] == 3){            // CALL REGISTER REPORT

                $consoleController = new \console\controllers\CronController('cron', Yii::$app);
                $result = $consoleController->runAction('call-register', [
                            $start_date,
                            $end_date,
                            $interval
                        ]);
                        
                Yii::$app->session->setFlash('warning', $result);
            }
        }
        
        return $this->render('custom-generate');
    }

    // qms report
    public function actionQmsReport()
    {
        $user = Yii::$app->user->identity;
        
        $agents = [];

        $client_id = (isset($_SESSION['client_connection']) ? $_SESSION['client_connection'] : null);
        
        $role_id = VaaniRole::find()->select('role_id')->where(['role_name' => 'Agent','del_status' => VaaniRole::STATUS_NOT_DELETED]);

        if($client_id){
            $agents = ArrayHelper::map(User::userList(null, $role_id, true, $client_id), 'user_id', 'user_name');
        }

        $searchModel = new VaaniAgentAuditSheetSearch();
        $post = Yii::$app->request->post();
        $dates = isset($post['QmsReport']['dates']) ? $post['QmsReport']['dates'] : date('Y-m-d') ;
        $searchModel->dates = $dates;
        // $agents = ArrayHelper::getColumn(User::userList(null, $role_id, true, $client_id), 'user_id');
        // $agent_id = isset(Yii::$app->request->queryParams['VaaniSessionSearch']['user_id']) ? Yii::$app->request->queryParams['VaaniSessionSearch']['user_id'] : null ;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('qms-report', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
