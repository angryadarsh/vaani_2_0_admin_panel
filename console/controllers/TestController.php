<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use common\models\VaaniClientMaster;
use common\models\User;
use common\models\EdasCampaign;
use common\models\VaaniCampaignQueue;
use common\models\EdasDniMaster;
use common\models\VaaniUserAccess;
use yii\base\ErrorException;

class TestController extends Controller
{
    // test cron
    public function actionTesting() {
        /* $query = 'select id
        from vaani_client_cron
        where client_id = "" AND cron_name = "apr_cron" and last_executed_at between "2022-07-15 10:00:01" and "2022-07-15 13:00:01" ';
        
        Yii::$app->Utility->addLog($query, 'apr_cron', \Yii::getAlias('@runtime') . "/logs/"); */
    }

    // fetch agent details hourly cron
    public function actionApr($start_date=null, $end_date=null, $interval=null)
    {
        $clients = VaaniClientMaster::find()->where(['del_status' => VaaniClientMaster::STATUS_NOT_DELETED])->asArray()->all();
        
        if(isset($clients)){
            // $start_date = ($start_date ? $start_date : ("'".date('Y-m-d H:i:s', strtotime('-15 minutes'))."'"));
            // $end_date = ($end_date ? $end_date : ("'".date('Y-m-d H:i:s', time())."'"));
            // $interval = ($interval ? $interval : ("'" . date('H:i', strtotime('-15 minutes')) . "-" . date('H:i', time()) . "'"));
            $start_date = "'".date('Y-m-d 16:45:01')."'";
            $end_date = "'".date('Y-m-d 17:00:00')."'";
            $interval = "'16:45-17:00'";
        
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

                // CHECK DB SERVER CONNECTION
                try{
                    $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);
                }catch(ErrorException $e){
                    continue;
                }

                \Yii::$app->db->close(); // make sure it clean
                \Yii::$app->db->dsn = 'mysql:host='.$db_host.';dbname='.$db_name;
                \Yii::$app->db->username = $db_username;
                \Yii::$app->db->password = $db_password;

                // create apr table if not exist
                $create_apr_query = 'CREATE TABLE IF NOT EXISTS `vaani_apr_report` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `agent_id` VARCHAR(45) NULL,
                    `queue_id` VARCHAR(45) NULL,
                    `queue` VARCHAR(45) NULL,
                    `date` VARCHAR(45) NULL,
                    `interval` VARCHAR(45) NULL,
                    `start_date` VARCHAR(45) NULL,
                    `end_date` VARCHAR(45) NULL,
                    `agent_name` VARCHAR(45) NULL,
                    `total_calls` VARCHAR(45) NULL,
                    `inbound_answered` VARCHAR(45) NULL,
                    `outbound_answered` VARCHAR(45) NULL,
                    `login_time` VARCHAR(45) NULL,
                    `not_ready` VARCHAR(45) NULL,
                    `idle_duration` VARCHAR(45) NULL,
                    `ring_duration` VARCHAR(45) NULL,
                    `talk_duration` VARCHAR(45) NULL,
                    `hold_duration` VARCHAR(45) NULL,
                    `wrap_up_duration` VARCHAR(45) NULL,
                    `inserted_at` VARCHAR(45) NULL,
                    `status` INT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `id_UNIQUE` (`id` ASC))
                    COMMENT = "Stores calculated apr report values from cron";';
                
                Yii::$app->Utility->addLog("** CREATE APR TABLE QUERY ** " . $create_apr_query, 'apr_cron', \Yii::getAlias('@runtime') . "/logs/");    //log
                $create_apr_table = \Yii::$app->db->createCommand($create_apr_query)->execute();

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
                $check_start_date = "'".date('Y-m-d 16:45:01')."'";
                $check_end_date = "'".date('Y-m-d 17:00:00')."'";

                $last_cron_query = 'select id
                from vaani_client_cron
                where client_id = "' . $client['client_id'] . '" AND cron_name = "apr_cron" and last_executed_at between ' . $check_start_date . ' and ' . $check_end_date . ' ';
                
                Yii::$app->Utility->addLog("FETCH LAST CRON QUERY => " . $last_cron_query, 'apr_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
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

                    $agents = "'".($agent_list ? strtolower(implode("','", $agent_list)) : null)."'";
                    $queue_names = "'".($queue_names ? implode("','", $queue_names) : null)."'";

                    // create temp table
                    $temp_query = \Yii::$app->db->createCommand("DROP TABLE if exists tempAprTable;")->execute();
                    
                    $temp_query_string = "CREATE TEMPORARY TABLE tempAprTable (
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
                    ) ENGINE=InnoDB";

                    Yii::$app->Utility->addLog("** CREATE TEMP TABLE QUERY ** " . $temp_query_string, 'apr_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query = \Yii::$app->db->createCommand($temp_query_string)->execute();

                    // insert into temp table from vaani_agent_call_report table
                    $temp_query_string = "INSERT INTO tempAprTable
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
                        AND vacr.updated_date BETWEEN " . $start_date . " AND " . $end_date . "
                        AND vacr.queue_name IN (" . $queue_names . ")";

                    Yii::$app->Utility->addLog("** INSERT INTO TEMP TABLE QUERY ** " . $temp_query_string, 'apr_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query = \Yii::$app->db->createCommand($temp_query_string)->execute();
                    
                    // CALCULATE RECORDS EXECUTED COUNT AND IDS
                    $temp_data_query_string = "SELECT count(*) as records_executed, MIN(id) as executed_first_id, MAX(id) as executed_last_id FROM tempAprTable";

                    Yii::$app->Utility->addLog("** FETCH RECORDS EXECUTED QUERY ** " . $temp_data_query_string, 'apr_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_data_query = \Yii::$app->db->createCommand($temp_data_query_string)->queryOne();
                    
                    if($temp_data_query){
                        $records_executed = $temp_data_query['records_executed'];
                        $executed_first_id = $temp_data_query['executed_first_id'];
                        $executed_last_id = $temp_data_query['executed_last_id'];
                    }
                
                    // check if records executed, then add data & cron
                    if($queues){
                        foreach ($queues as $k => $queue) {
                            $users = VaaniCampaignQueue::usersList(null, $queue['queue_id'], User::agentRoleId());
                            $agent_list = ArrayHelper::getColumn($users, 'user_id');
                            $agents = "'".strtolower(implode("','", $agent_list))."'";

                            // FETCH CALCULATED DATA FROM TEMP TABLE
                            $result_query = "SELECT Date,".$interval." as 'Interval',agent_name as 'Agent',report.agent_id as 'agent_id',agent_calls as 'total_calls',Inbound_Answered,Outbound_Answered, '".$queue['queue']."' as Queue
                            ,/* sec_TO_time(not_ready) as 'Not Ready' */ '','' as 'Idle Duration',
                            Ring_Duration,
                            Talk_Duration,
                            Hold_Duration,
                            Wrap_Up_Duration 
                            FROM
                            (select 
                                COUNT(case when call_type <> 8 then abc.agent_id end) as 'agent_calls',
                                abc.agent_name as 'agent_name', abc.agent_id as 'agent_id',Date(insert_date) as 'Date',convert(sec_to_time(sum(abc.ringing)),time) as 'Ring_Duration',
                                convert(sec_to_time(sum(abc.talk)),time) as 'Talk_Duration',
                                convert(sec_to_time(sum(abc.hold)),time) as 'Hold_Duration',
                                convert(sec_to_time(sum(abc.wrap)),time) as 'Wrap_Up_Duration',
                                COUNT(case when call_type=2 then abc.agent_id end) as 'Inbound_Answered',
                                COUNT(case when call_type IN (1,3,7) then abc.agent_id end) as 'Outbound_Answered'
                            FROM tempAprTable abc 
                            WHERE abc.call_type IN (1,2,3,7,8) and agent_id IN ( " . $agents . " ) and abc.updated_date between " . $start_date . " and " . $end_date . " and abc.queue_name IN ( '" . $queue['queue'] . "' ) group by abc.agent_id)
                            report;";

                            Yii::$app->Utility->addLog("** SELECT MANIPULATED DATA FROM TEMP TABLE QUERY ** " . $result_query, 'apr_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                            $result = \Yii::$app->db->createCommand($result_query)->queryAll();
                            
                            if($result){ 
                                $result = array_combine(array_column($result, 'agent_id'), $result);
                            }

                            // NOT READY DURATION
                            $not_ready_string = "SELECT agent_id, sec_TO_time(SUM(duration)) as not_ready FROM vaani_break_log vbl where vbl.agent_id IN (" . $agents . ") AND vbl.campaign_id IN ( '" . $queue['campaign_id'] . "' ) AND vbl.end_time IS NOT NULL AND vbl.end_time BETWEEN " . $start_date . " AND " . $end_date . "";

                            Yii::$app->Utility->addLog("** SELECT NOT READY QUERY ** " . $not_ready_string, 'apr_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                            $not_ready_query = \Yii::$app->db->createCommand($not_ready_string)->queryAll();
                    
                            // fetch login data for the users
                            /* $login_query = \Yii::$app->pa_db->createCommand('
                                select user_id as agent_id, sec_to_time(sum(TIME_TO_SEC(
                                    case 
                                        when logout_datetime is null and (login_datetime between '.$start_date.' and '.$end_date.') 
                                            then sec_to_time(time_to_sec('.$end_date.') - time_to_sec(login_datetime))

                                        when logout_datetime is null and (time_to_sec(login_datetime) < time_to_sec('.$start_date.')) 
                                            then "00:15:00"
                
                                        when logout_datetime is not null and ((logout_datetime between '.$start_date.' and '.$end_date.') and (login_datetime between '.$start_date.' and '.$end_date.'))
                                            then timediff(logout_datetime, login_datetime)
                
                                        when logout_datetime is not null and (time_to_sec(login_datetime) < time_to_sec('.$start_date.') and time_to_sec(logout_datetime) > time_to_sec('.$end_date.'))
                                            then "00:15:00"
                
                                        when logout_datetime is not null and (time_to_sec(login_datetime) < time_to_sec('.$start_date.') and (logout_datetime between '.$start_date.' and '.$end_date.'))
                                            then timediff(logout_datetime, '.$start_date.')
                
                                        when logout_datetime is not null and (time_to_sec(logout_datetime) > time_to_sec('.$end_date.') and (login_datetime between '.$start_date.' and '.$end_date.'))
                                            then timediff('.$end_date.', login_datetime)
                                    end
                                ))) as login_time
                                from asterisk.vaani_session
                                where user_id IN (' . $agents . ') AND campaign IN ( "' . $queue["campaign_id"] . '" ) and date_created >= ' . date('Y-m-d', strtotime($start_date)) . ' and ' . date('Y-m-d', strtotime($end_date)) . ' <= last_action_epoch group by user_id')
                            ->queryAll(); */
                            
                            // fetch idle data for the users
                            $idle_query_string = 'SELECT log.user_id as agent_id,
                            vaani_session.campaign,
                            sec_to_time(SUM(case when status_id = 3 then 
                                (case when action_end_datetime is not null then action_duration_sec else time_to_sec(now()) - time_to_sec(action_start_datetime) end) 
                            end)) as idle_duration
                            FROM asterisk.vaani_session_log log
                            LEFT JOIN asterisk.vaani_session ON vaani_session.unique_id = log.unique_id
                            WHERE log.user_id IN (' . $agents . ') and log.status_id in (3) and (action_end_datetime) between ' . $start_date . ' and ' . $end_date . ' AND vaani_session.campaign IN ( "' . $queue["campaign_id"] . '" ) GROUP BY log.user_id';

                            Yii::$app->Utility->addLog("** SELECT IDLE QUERY ** " . $idle_query_string, 'apr_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                            $idle_query = \Yii::$app->pa_db->createCommand($idle_query_string)
                            ->queryAll();

                            $not_ready_data = ArrayHelper::map($not_ready_query, 'agent_id', 'not_ready');
                            // $login_data = ArrayHelper::map($login_query, 'agent_id', 'login_time');
                            $idle_data = ArrayHelper::map($idle_query, 'agent_id', 'idle_duration');
            
                            // insert into vaani_apr_report table
                            // for ($i = 0; $i < count($result); $i++) {
                            foreach ($agent_list as $i => $agent_id) {
                                if(isset($result[$agent_id])){
                                    $agent_result = $result[$agent_id];
                                }else{
                                    $agent_result['agent_id'] = $agent_id;
                                    $agent_result['Date'] = date('Y-m-d');
                                    $agent_result['Agent'] = $agent_id;
                                    $agent_result['total_calls'] = 0;
                                    $agent_result['Inbound_Answered'] = 0;
                                    $agent_result['Outbound_Answered'] = 0;
                                    $agent_result['Ring_Duration'] = '00:00:00';
                                    $agent_result['Talk_Duration'] = '00:00:00';
                                    $agent_result['Hold_Duration'] = '00:00:00';
                                    $agent_result['Wrap_Up_Duration'] = '00:00:00';
                                }

                                $not_ready = (isset($not_ready_data[$agent_id]) ? $not_ready_data[$agent_id] : '00:00:00');
                                $idle = (isset($idle_data[$agent_id]) ? $idle_data[$agent_id] : '00:00:00');

                                // $login = (isset($login_data[$agent_id]) ? $login_data[$agent_id] : null);
                                
                                $login = gmdate('H:i:s', ((strtotime($not_ready) - strtotime('TODAY')) + 
                                (strtotime($idle) - strtotime('TODAY')) + 
                                (strtotime($agent_result['Ring_Duration']) - strtotime('TODAY')) + 
                                (strtotime($agent_result['Talk_Duration']) - strtotime('TODAY')) + 
                                (strtotime($agent_result['Hold_Duration']) - strtotime('TODAY')) + 
                                (strtotime($agent_result['Wrap_Up_Duration']) - strtotime('TODAY'))) );

                                $final_result_query = "INSERT INTO vaani_apr_report
                                ( `agent_id`, `queue_id`, `queue`, `date`, `interval`, `start_date`, `end_date`, `agent_name`, `total_calls`, `inbound_answered`, `outbound_answered`, `login_time`, `not_ready`, `idle_duration`, `ring_duration`, `talk_duration`, `hold_duration`, `wrap_up_duration`, `inserted_at`, `status` )
                                VALUES
                                (
                                    ".$agent_result['agent_id'].",
                                    '".$queue['queue_id']."',
                                    '".$queue['queue']."',
                                    '".$agent_result['Date']."',
                                    ".$interval.",
                                    ".$start_date .",
                                    ".$end_date.",
                                    '".$agent_result['Agent']."',
                                    ".$agent_result['total_calls'].",
                                    ".$agent_result['Inbound_Answered'].",
                                    ".$agent_result['Outbound_Answered'].",
                                    '".$login."',
                                    '".$not_ready."',
                                    '".$idle."',
                                    '".$agent_result['Ring_Duration']."',
                                    '".$agent_result['Talk_Duration']."',
                                    '".$agent_result['Hold_Duration']."',
                                    '".$agent_result['Wrap_Up_Duration']."',
                                    '".date('Y-m-d H:i:s', time())."',
                                    1
                                )";

                                Yii::$app->Utility->addLog("** INSERT RECORD INTO APR TABLE QUERY ** " . $final_result_query, 'apr_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
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
                            "apr_cron",
                            '.$end_date.',
                            "'.$records_executed.'",
                            "'.$executed_first_id.'",
                            "'.$executed_last_id.'",
                            1
                        )';

                        Yii::$app->Utility->addLog("** INSERT CRON RECORD QUERY ** " . $add_cron_query, 'apr_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                        $add_cron = \Yii::$app->db->createCommand($add_cron_query)
                        ->execute();

                        if($add_cron){
                            echo "Cron data added!";
                        }
                    }

                    // DROP TEMPORARY TABLE
                    $drop_temp = \Yii::$app->db->createCommand("DROP TEMPORARY TABLE tempAprTable;")->execute();
                    if($drop_temp){
                        echo "Temp Table deleted!";
                    }
                }
            }
        }
    }

    // fetch call register details cron
    public function actionCallRegister1()
    {
        $clients = VaaniClientMaster::find()->where(['del_status' => VaaniClientMaster::STATUS_NOT_DELETED])->asArray()->all();
        
        if(isset($clients)){
            // $start_date = "'".date('Y-m-d H:i:s', strtotime('-15 minutes'))."'";
            // $end_date = "'".date('Y-m-d H:i:s', time())."'";
            // $interval = "'" . date('H:i', strtotime('-15 minutes')) . "-" . date('H:i', time()) . "'";
            $start_date = "'".date('Y-m-d 11:30:01')."'";
            $end_date = "'".date('Y-m-d 11:45:00')."'";
            $interval = "'11:30-11:45'";
        
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
                    // $temp_query = \Yii::$app->db->createCommand("DROP TABLE if exists vaani_call_register_report;")->execute();
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
                    `start_time` VARCHAR(45) NULL,
                    `end_time` VARCHAR(45) NULL,
                    `cli` VARCHAR(45) NULL,
                    `call_type` VARCHAR(45) NULL,
                    `call_status` VARCHAR(45) NULL,
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
                $check_start_date = "'".date('Y-m-d 11:30:01')."'";
                $check_end_date = "'".date('Y-m-d 11:45:00')."'";

                $last_cron_query = 'select id
                from vaani_client_cron
                where client_id = "' . $client['client_id'] . '" AND cron_name = "call_register_cron" and last_executed_at between ' . $check_start_date . ' and ' . $check_end_date  . ' ';
                
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
                        `start_time` varchar(45) DEFAULT NULL,
                        `end_time` varchar(45) DEFAULT NULL,
                        `campaign` varchar(100) DEFAULT NULL,
                        `queue` varchar(100) DEFAULT NULL,
                        `ring_duration` bigint DEFAULT NULL,
                        `hold_time` bigint DEFAULT NULL,
                        `talk_time` bigint DEFAULT NULL,
                        `wrap_duration` bigint DEFAULT NULL,
                        `mobile_no` varchar(45) DEFAULT NULL,
                        `status` varchar(20) DEFAULT NULL,
                        `disposition` varchar(50) DEFAULT NULL,
                        `recording_path` varchar(200) DEFAULT NULL,
                        `call_type` varchar(20) DEFAULT NULL
                    ) ENGINE=InnoDB";
                    
                    Yii::$app->Utility->addLog("** CREATE TEMP TABLE QUERY ** " . $temp_query_string, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query = \Yii::$app->db->createCommand($temp_query_string)->execute();
                    
                    echo $client['client_id'];
                    // insert into temp table from vaani_agent_call_report table
                    // inbound
                    $temp_query_string = "INSERT INTO tempAprTable
                    ( `id`, `agent_id`, `unique_id`, `agent_name`, `date`, `start_time`, `end_time`, `campaign`, `queue`, `ring_duration`, `hold_time`, `talk_time`, `wrap_duration`, `mobile_no`, `status`, `disposition`, `recording_path`, `call_type`)
                    SELECT 
                        vacr.`id`, vacr.`agent_id`, vacr.`unique_id`, vacr.`agent_name`, `insert_date`, `start_date`, `end_date`, `campaign_name`, `queue_name`, `ringing`, `hold`, `talk`, `wrap`, `caller_id`, `call_status`, disp.`disposition_name`, `recording_path`, `call_type`
                    FROM
                        `vaani_agent_call_report` vacr
                        left join vaani_dispositions disp on disp.short_code = vacr.disposition
                    WHERE
                        vacr.agent_id IN (" . $agents . ")
                        AND vacr.insert_date BETWEEN " . $start_date . " AND " . $end_date . "
                        AND vacr.queue_name IN (" . $queue_names . ")
                    GROUP BY id";
                    
                    Yii::$app->Utility->addLog("** INSERT INTO TEMP TABLE QUERY FROM AGENT REPORT ** " . $temp_query_string, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
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
                    
                    foreach ($queue_list as $queue_id => $queue) {
                        $users = VaaniCampaignQueue::usersList(null, $queue_id, User::agentRoleId());
                        $agent_list = ArrayHelper::getColumn($users, 'user_id');
                        $agents = "'".strtolower(implode("','", $agent_list))."'";

                        foreach ($agent_list as $i => $agent_id) {
                            $final_result_query = "INSERT INTO vaani_call_register_report
                            ( `unique_id`, `agent_id`, `agent_name`, `campaign`, `queue`, `date`, `interval`, `start_date`, `end_date`, `start_time`, `end_time`, `cli`, `call_type`, `call_status`, `disposition`, `duration`, `hold_duration`, `ring_duration`, `talk_duration`, `wrap_duration`, `recording_path`, `inserted_at` )
                            SELECT 
                                `unique_id`, `agent_id`, `agent_name`, `campaign`,`queue`, '".date('Y-m-d')."', ".$interval.", ".$start_date .", ".$end_date.", `start_time`, `end_time`, `mobile_no`, `call_type`, `status`, `disposition`,

                                TIME_FORMAT(SEC_TO_TIME( (CASE WHEN `hold_time` IS NOT NULL THEN `hold_time` ELSE 0 END) + (CASE WHEN `ring_duration` IS NOT NULL THEN `ring_duration` ELSE 0 END) + (CASE WHEN `talk_time` IS NOT NULL THEN `talk_time` ELSE 0 END) + (CASE WHEN `wrap_duration` IS NOT NULL THEN `wrap_duration` ELSE 0 END) ), '%H:%i:%s') as duration, 

                                sec_to_time(CASE WHEN `hold_time` IS NOT NULL THEN `hold_time` ELSE 0 END) as hold, 
                                sec_to_time(CASE WHEN `ring_duration` IS NOT NULL THEN `ring_duration` ELSE 0 END) as ring, 
                                sec_to_time(CASE WHEN `talk_time` IS NOT NULL THEN `talk_time` ELSE 0 END) as talk, 
                                sec_to_time(CASE WHEN `wrap_duration` IS NOT NULL THEN `wrap_duration` ELSE 0 END) as wrap,

                                `recording_path`,
                                '".date('Y-m-d H:i:s', time())."'
                            FROM
                                `tempAprTable`
                            WHERE
                                agent_id = " . $agent_id . "
                                and queue = '" . $queue . "'";
                            
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

    // fetch ACD details cron
    public function actionAcd1()
    {
        $clients = VaaniClientMaster::find()->where(['del_status' => VaaniClientMaster::STATUS_NOT_DELETED])->asArray()->all();
        
        if(isset($clients)){
            // $start_date = "'".date('Y-m-d H:i:s', strtotime('-15 minutes'))."'";
            // $end_date = "'".date('Y-m-d H:i:s', time())."'";
            // $interval = "'" . date('H:i', strtotime('-15 minutes')) . "-" . date('H:i', time()) . "'";
            $start_date = "'".date('Y-m-d 12:45:01')."'";
            $end_date = "'".date('Y-m-d 13:00:00')."'";
            $interval = "'12:45-13:00'";
            
            foreach ($clients as $client) {
                
                $result = null;
                $campaign_list = null;
                $queue_list = null;
                $queue_names = null;
                $agent_list = null;
                $queue_ids = null;
                $queues = null;
                $dni_numbers = [];
                
                // fetch client connections
                $db_name = User::decrypt_data($client['db']);
                $db_host = User::decrypt_data($client['server']);
                $db_username = User::decrypt_data($client['username']);
                $db_password = User::decrypt_data($client['password']);

                \Yii::$app->db->close(); // make sure it clean
                \Yii::$app->db->dsn = 'mysql:host='.$db_host.';dbname='.$db_name;
                \Yii::$app->db->username = $db_username;
                \Yii::$app->db->password = $db_password;

                // create acd report table if not exist
                    // $temp_query = \Yii::$app->db->createCommand("DROP TABLE if exists vaani_acd_report;")->execute();
                $create_acd_query = 'CREATE TABLE IF NOT EXISTS `vaani_acd_report` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `date` VARCHAR(45) NULL,
                    `campaign` VARCHAR(45) NULL,
                    `queue` VARCHAR(45) NULL,
                    `interval` VARCHAR(45) NULL,
                    `start_date` VARCHAR(45) NULL,
                    `end_date` VARCHAR(45) NULL,
                    `offered` VARCHAR(45) NULL,
                    `agent_count` VARCHAR(45) NULL,
                    `agent_offered` VARCHAR(45) NULL,
                    `answered_in_10_sec` VARCHAR(45) NULL,
                    `answered_in_20_sec` VARCHAR(45) NULL,
                    `answered_in_30_sec` VARCHAR(45) NULL,
                    `answered_in_40_sec` VARCHAR(45) NULL,
                    `answered_in_50_sec` VARCHAR(45) NULL,
                    `answered_in_60_sec` VARCHAR(45) NULL,
                    `answered_in_90_sec` VARCHAR(45) NULL,
                    `answered_in_120_sec` VARCHAR(45) NULL,
                    `answered_after_120_sec` VARCHAR(45) NULL,
                    `calls_in_queue` VARCHAR(45) NULL,
                    `talk_time` VARCHAR(200) NULL,
                    `wrap_time` VARCHAR(200) NULL,
                    `hold_time` VARCHAR(200) NULL,
                    `call_abandoned` VARCHAR(200) NULL,
                    `abandoned_on_ivr` VARCHAR(200) NULL,
                    `abandoned_on_agent` VARCHAR(200) NULL,
                    `abandoned_in_10_sec` VARCHAR(200) NULL,
                    `abandoned_in_20_sec` VARCHAR(200) NULL,
                    `abandoned_in_30_sec` VARCHAR(200) NULL,
                    `abandoned_in_40_sec` VARCHAR(200) NULL,
                    `abandoned_in_50_sec` VARCHAR(200) NULL,
                    `abandoned_in_60_sec` VARCHAR(200) NULL,
                    `abandoned_in_90_sec` VARCHAR(200) NULL,
                    `abandoned_in_120_sec` VARCHAR(200) NULL,
                    `abandoned_after_120_sec` VARCHAR(200) NULL,
                    `answered_percent` VARCHAR(200) NULL,
                    `abandoned_percent` VARCHAR(200) NULL,
                    `inserted_at` VARCHAR(45) NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `id_UNIQUE` (`id` ASC))
                    COMMENT = "Stores calculated acd report values from cron";';
                
                Yii::$app->Utility->addLog("** CREATE ACD TABLE QUERY ** " . $create_acd_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");    //log
                $create_acd_table = \Yii::$app->db->createCommand($create_acd_query)->execute();
                
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
                $check_start_date = "'".date('Y-m-d 12:45:01')."'";
                $check_end_date = "'".date('Y-m-d 13:00:00')."'";

                $last_cron_query = 'select id
                from vaani_client_cron
                where client_id = "' . $client['client_id'] . '" AND cron_name = "acd_cron" and last_executed_at between ' . $check_start_date . ' and ' . $check_end_date  . ' ';
                
                Yii::$app->Utility->addLog("FETCH LAST CRON QUERY => " . $last_cron_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
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

                        // fetch dni number
                        $dni_ids = ArrayHelper::getColumn($queues, 'dni_id');
                        
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

                    $agents = ($agent_list ? "'".strtolower(implode("','", $agent_list))."'" : "''");
                    $queue_names = ($queue_names ? "'".implode("','", $queue_names)."'" : "''");
                    $dni_numbers = "'".implode("','", $dni_numbers)."'";
                    $queue_ids = ($queue_ids ? "'".implode("','", $queue_ids)."'" : "''" );

                    // create temp table 1
                    $temp_query = \Yii::$app->db->createCommand("DROP TABLE if exists tempAprTable;")->execute();
                    
                    $temp_query_string = "CREATE TEMPORARY TABLE tempAprTable (
                        `id` int NOT NULL,
                        `date` varchar(45) DEFAULT NULL,
                        `agent_id` varchar(45) DEFAULT NULL,
                        `agent_name` varchar(100) NOT NULL,
                        `unique_id` varchar(45) DEFAULT NULL,
                        `dni_number` varchar(45) DEFAULT NULL,
                        `campaign` varchar(100) DEFAULT NULL,
                        `queue` varchar(100) DEFAULT NULL,
                        `start_time` varchar(45) DEFAULT NULL,
                        `end_time` varchar(45) DEFAULT NULL,
                        `queue_hold_time` varchar(45) DEFAULT NULL,
                        `duration` varchar(45) DEFAULT NULL,
                        `hangup_by` varchar(45) DEFAULT NULL
                    ) ENGINE=InnoDB";
                 
                    Yii::$app->Utility->addLog("** CREATE TEMP TABLE QUERY ** " . $temp_query_string, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query = \Yii::$app->db->createCommand($temp_query_string)->execute();
                    
                    // insert inbound table data in temporary table
                    $temp_query_string = "INSERT INTO tempAprTable
                    ( `id`, `date`, `agent_id`, `agent_name`, `unique_id`, `dni_number`, `campaign`, `queue`, `start_time`, `end_time`, `queue_hold_time`, `duration`, `hangup_by`)
                    SELECT 
                        `id`, `date`, `agent_id`, `agent_name`, `unique_id`, `dni_number`, `campaign`, `queue`, `start_time`, `end_time`, `queue_hold_time`, `duration`, `hangup_by`
                    FROM
                        `inbound_edas`
                    WHERE
                        (agent_id IN (" . $agents . ") || agent_id IS NULL)
                        AND end_time BETWEEN " . $start_date . " AND " . $end_date . "
                        AND queue IN (" . $queue_names . ")
                    GROUP BY id";

                    Yii::$app->Utility->addLog("** INSERT INTO TEMP TABLE QUERY FROM INBOUND ** " . $temp_query_string, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query = \Yii::$app->db->createCommand($temp_query_string)->execute();

                    // create temp table 2
                    $temp_query_string1 = "CREATE TEMPORARY TABLE tempAprTable1 (
                        `id` int NOT NULL,
                        `unique_id` varchar(45) DEFAULT NULL,
                        `talk` varchar(100) DEFAULT NULL,
                        `wrap` varchar(100) DEFAULT NULL,
                        `hold` varchar(45) DEFAULT NULL
                    ) ENGINE=InnoDB";

                    Yii::$app->Utility->addLog("** CREATE 2nd TEMP TABLE QUERY ** " . $temp_query_string1, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query1 = \Yii::$app->db->createCommand($temp_query_string1)->execute();

                    $inbound_unique_ids_query = "SELECT unique_id FROM tempAprTable";

                    Yii::$app->Utility->addLog("** FETCH UNIQUE IDS QUERY ** " . $inbound_unique_ids_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $inbound_unique_ids = \Yii::$app->db->createCommand($inbound_unique_ids_query)->queryAll();

                    $unique_ids = "''";
                    if($inbound_unique_ids){
                        $unique_ids = ArrayHelper::getColumn($inbound_unique_ids, 'unique_id');
                        $unique_ids = "'".strtolower(implode("','", $unique_ids))."'";
                    }
                    
                    // insert agent call report table data in temporary1 table
                    $temp_query_string1 = "INSERT INTO tempAprTable1
                    ( `id`, `unique_id`, `talk`, `wrap`, `hold`)
                    SELECT 
                        `id`, `unique_id`, `talk`, `wrap`, `hold`
                    FROM
                        `vaani_agent_call_report`
                    WHERE
                        unique_id IN (" . $unique_ids . ")
                    GROUP BY id";
                    
                    Yii::$app->Utility->addLog("** INSERT INTO TEMP TABLE QUERY FROM AGENT CALL REPORT TABLE ** " . $temp_query_string1, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query1 = \Yii::$app->db->createCommand($temp_query_string1)->execute();
                    
                    /* $test_query = "SELECT * FROM tempAprTable";

                    // Yii::$app->Utility->addLog("** FETCH UNIQUE IDS QUERY ** " . $test_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $test_temp = \Yii::$app->db->createCommand($test_query)->queryAll();

                    if($test_temp){
                        // echo "<pre>"; print_r($test_temp); exit;
                    } */
                    
                    // CALCULATE RECORDS EXECUTED COUNT AND IDS
                    $temp_data_query_string = "SELECT count(*) as records_executed, MIN(id) as executed_first_id, MAX(id) as executed_last_id FROM tempAprTable";

                    Yii::$app->Utility->addLog("** FETCH RECORDS EXECUTED QUERY ** " . $temp_data_query_string, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_data_query = \Yii::$app->db->createCommand($temp_data_query_string)->queryOne();

                    if($temp_data_query){
                        $records_executed = $temp_data_query['records_executed'];
                        $executed_first_id = $temp_data_query['executed_first_id'];
                        $executed_last_id = $temp_data_query['executed_last_id'];
                    }

                    // FETCH QUEUE WISE AGENT COUNT
                    $queue_agents_query = "(SELECT queue, count(vua.id) as `agents` FROM vaani_user_access vua
                    LEFT JOIN vaani_campaign_queue queue_data on vua.queue_id = queue_data.queue_id
                    WHERE 
                        vua.queue_id IN (" . $queue_ids . ")
                        and vua.user_id IN (" . $agents . ") and vua.access_level = " . VaaniUserAccess::LEVEL_QUEUE . " and vua.del_status = ". VaaniUserAccess::STATUS_NOT_DELETED ." 
                    group by queue_data.queue)";

                    Yii::$app->Utility->addLog("** EXECUTED QUEUE AGENTS COUNT QUERY ** " . $queue_agents_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $queue_agents = \Yii::$app->pa_db->createCommand($queue_agents_query)->queryAll();

                    if($queue_agents){
                        $queue_agents = array_combine(array_column($queue_agents, 'queue'), array_column($queue_agents, 'agents'));
                    }
                    
                    // CREATE COPY OF TEMP TABLE
                    /* $temp_query = \Yii::$app->db->createCommand("DROP TABLE IF EXISTS tempAprTableCopy1;")->execute();
                    $copy_temp_query = \Yii::$app->db->createCommand("CREATE TEMPORARY TABLE tempAprTableCopy1 AS SELECT * FROM tempAprTable;")->execute();
                    
                    $temp_query = \Yii::$app->db->createCommand("DROP TABLE IF EXISTS tempAprTableCopy2;")->execute();
                    $copy_temp_query = \Yii::$app->db->createCommand("CREATE TEMPORARY TABLE tempAprTableCopy2 AS SELECT * FROM tempAprTable;")->execute();
                    
                    $temp_query = \Yii::$app->db->createCommand("DROP TABLE IF EXISTS tempAprTableCopy3;")->execute();
                    $copy_temp_query = \Yii::$app->db->createCommand("CREATE TEMPORARY TABLE tempAprTableCopy3 AS SELECT * FROM tempAprTable;")->execute();
                    
                    $temp_query = \Yii::$app->db->createCommand("DROP TABLE IF EXISTS tempAprTableCopy4;")->execute();
                    $copy_temp_query = \Yii::$app->db->createCommand("CREATE TEMPORARY TABLE tempAprTableCopy4 AS SELECT * FROM tempAprTable;")->execute(); */

                    /* $exc = \Yii::$app->db->createCommand("SELECT count(inb.id) FROM tempAprTableCopy1 inb WHERE inb.end_time BETWEEN " . $start_date . " and " . $end_date . " GROUP BY inb.queue;")->queryAll();

                    if($exc){
                        echo "<pre>"; print_r($exc); exit;
                    } */

                    // FETCH CALCULATED DATA FROM TEMP TABLE
                    $result_query = "SELECT
                        inbound.date,
                        inbound.queue as 'service',
                        inbound.campaign,
                        count(inbound.id) as `calls_offered`,
                        COUNT(case when inbound.end_time IS NOT NULL AND inbound.agent_id IS NOT NULL then inbound.id end) as 'agent_offered',
                        COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time >= 0 and queue_hold_time <= 10 then inbound.id end) as 'answered_in_10_sec',
                        COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 10 and queue_hold_time <= 20 then inbound.id end) as 'answered_in_20_sec',
                        COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 20 and queue_hold_time <= 30 then inbound.id end) as 'answered_in_30_sec',
                        COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 30 and queue_hold_time <= 40 then inbound.id end) as 'answered_in_40_sec',
                        COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 40 and queue_hold_time <= 50 then inbound.id end) as 'answered_in_50_sec',
                        COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 50 and queue_hold_time <= 60 then inbound.id end) as 'answered_in_60_sec',
                        COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 60 and queue_hold_time <= 90 then inbound.id end) as 'answered_in_90_sec',
                        COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 90 and queue_hold_time <= 120 then inbound.id end) as 'answered_in_120_sec',
                        COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 120 then inbound.id end) as 'answered_>_120_sec',
                        COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NULL then inbound.id end) as 'calls_in_queue',
                        convert(sec_to_time(sum(report.talk)),time) as `talk_time`,
                        convert(sec_to_time(sum(report.wrap)),time) as `wrap_time`,
                        convert(sec_to_time(sum(report.hold)),time) as `hold_time`,
                        COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL then inbound.id end) as 'call_abandoned',
                        COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and hangup_by = 'CUSTOMER' then inbound.id end) as 'abandoned_on_ivr',
                        COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and hangup_by = 'AGENT' then inbound.id end) as 'abandoned_on_agent',
                        COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) >= 0 and (inbound.duration) <= 10 then inbound.id end) as 'abandoned_in_10_sec',
                        COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 10 and (inbound.duration) <= 20 then inbound.id end) as 'abandoned_in_20_sec',
                        COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 20 and (inbound.duration) <= 30 then inbound.id end) as 'abandoned_in_30_sec',
                        COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 30 and (inbound.duration) <= 40 then inbound.id end) as 'abandoned_in_40_sec',
                        COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 40 and (inbound.duration) <= 50 then inbound.id end) as 'abandoned_in_50_sec',
                        COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 50 and (inbound.duration) <= 60 then inbound.id end) as 'abandoned_in_60_sec',
                        COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 60 and (inbound.duration) <= 90 then inbound.id end) as 'abandoned_in_90_sec',
                        COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 90 and (inbound.duration) <= 120 then inbound.id end) as 'abandoned_in_120_sec',
                        COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 120 then inbound.id end) as 'abandoned_>_120_sec',
                        ROUND(((
                            (COUNT(case when inbound.agent_id IS NOT NULL then inbound.id end)) / 
                            (count(inbound.id))
                        )*100), 2) as 'answered_percent',
                        ROUND(((
                            (COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL then inbound.id end)) / 
                            (count(inbound.id))
                        )*100), 2) as 'abandoned_percent'
                        
                        FROM tempAprTable inbound
                            left join tempAprTable1 report on inbound.unique_id=report.unique_id
                        WHERE
                            end_time BETWEEN " . $start_date . " and " . $end_date . "
                            and dni_number IN (" . $dni_numbers . ")
                        group by inbound.queue
                    ";
                    
                    Yii::$app->Utility->addLog("** SELECT MANIPULATED DATA FROM TEMP TABLE QUERY ** " . $result_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $result = \Yii::$app->db->createCommand($result_query)->queryAll();
                    
                    if($result){ 
                        // echo "<pre>"; print_r($result); exit;
                    }

                    /// INSERT CALCULATED DATE IN ACD REPORT TABLE
                    foreach ($result as $i => $data) {
                        $final_result_query = "INSERT INTO vaani_acd_report
                        ( `date`, `campaign`, `queue`, `interval`, `start_date`, `end_date`, `offered`, `agent_count`, `agent_offered`, `answered_in_10_sec`, `answered_in_20_sec`, `answered_in_30_sec`, `answered_in_40_sec`, `answered_in_50_sec`, `answered_in_60_sec`, `answered_in_90_sec`, `answered_in_120_sec`, `answered_after_120_sec`, `calls_in_queue`, `talk_time`, `wrap_time`, `hold_time`, `call_abandoned`, `abandoned_on_ivr`, `abandoned_on_agent`, `abandoned_in_10_sec`, `abandoned_in_20_sec`, `abandoned_in_30_sec`, `abandoned_in_40_sec`, `abandoned_in_50_sec`, `abandoned_in_60_sec`, `abandoned_in_90_sec`, `abandoned_in_120_sec`, `abandoned_after_120_sec`, `answered_percent`, `abandoned_percent`, `inserted_at` )
                        VALUES
                        (
                            '".$data['date']."',
                            '".$data['campaign']."',
                            '".$data['service']."',
                            ".$interval.",
                            ".$start_date .",
                            ".$end_date.",
                            ".$data['calls_offered'].",
                            ".$queue_agents[$data['service']].",
                            ".$data['agent_offered'].",
                            ".$data['answered_in_10_sec'].",
                            ".$data['answered_in_20_sec'].",
                            ".$data['answered_in_30_sec'].",
                            ".$data['answered_in_40_sec'].",
                            ".$data['answered_in_50_sec'].",
                            ".$data['answered_in_60_sec'].",
                            ".$data['answered_in_90_sec'].",
                            ".$data['answered_in_120_sec'].",
                            ".$data['answered_>_120_sec'].",
                            ".$data['calls_in_queue'].",
                            '".$data['talk_time']."',
                            '".$data['wrap_time']."',
                            '".$data['hold_time']."',
                            ".$data['call_abandoned'].",
                            ".$data['abandoned_on_ivr'].",
                            ".$data['abandoned_on_agent'].",
                            ".$data['abandoned_in_10_sec'].",
                            ".$data['abandoned_in_20_sec'].",
                            ".$data['abandoned_in_30_sec'].",
                            ".$data['abandoned_in_40_sec'].",
                            ".$data['abandoned_in_50_sec'].",
                            ".$data['abandoned_in_60_sec'].",
                            ".$data['abandoned_in_90_sec'].",
                            ".$data['abandoned_in_120_sec'].",
                            ".$data['abandoned_>_120_sec'].",
                            ".$data['answered_percent'].",
                            ".$data['abandoned_percent'].",
                            '".date('Y-m-d H:i:s', time())."'
                        )";

                        Yii::$app->Utility->addLog("** INSERT RECORD INTO ACD TABLE QUERY ** " . $final_result_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                        $final_result = \Yii::$app->db->createCommand($final_result_query)->execute();
                        
                        if($final_result){
                            echo "Data Inserted!";
                        }
                    }
                    
                    // insert cron record
                    $add_cron_query = 'INSERT INTO vaani_client_cron
                    ( `client_id`, `client_name`, `cron_name`, `last_executed_at`, `records_executed`, `executed_first_id`, `executed_last_id`, `status` )
                    VALUES
                    (
                        "'.$client['client_id'].'",
                        "'.$client['client_name'].'",
                        "acd_cron",
                        '.$end_date.',
                        "'.$records_executed.'",
                        "'.$executed_first_id.'",
                        "'.$executed_last_id.'",
                        1
                    )';

                    Yii::$app->Utility->addLog("** INSERT CRON RECORD QUERY ** " . $add_cron_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
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
                    
                    // DROP TEMPORARY TABLE
                    $drop_temp = \Yii::$app->db->createCommand("DROP TEMPORARY TABLE tempAprTable1;")->execute();
                    if($drop_temp){
                        return "Temp Table deleted!";
                    }
                    
                    // DROP TEMPORARY TABLE
                    /* $drop_temp = \Yii::$app->db->createCommand("DROP TEMPORARY TABLE tempAprTableCopy1;")->execute();
                    $drop_temp = \Yii::$app->db->createCommand("DROP TEMPORARY TABLE tempAprTableCopy2;")->execute();
                    $drop_temp = \Yii::$app->db->createCommand("DROP TEMPORARY TABLE tempAprTableCopy3;")->execute();
                    $drop_temp = \Yii::$app->db->createCommand("DROP TEMPORARY TABLE tempAprTableCopy4;")->execute();
                    if($drop_temp){
                        return "Temp Table deleted!";
                    } */
                }
            }
        }
    }

    // fetch ACD details cron
    public function actionAcd($start_date=null, $end_date=null, $interval=null)
    {
        $clients = VaaniClientMaster::find()->where(['del_status' => VaaniClientMaster::STATUS_NOT_DELETED])->asArray()->all();
        
        if(isset($clients)){
            // $start_date = ($start_date ? $start_date : ("'".date('Y-m-d H:i:s', strtotime('-15 minutes'))."'"));
            // $end_date = ($end_date ? $end_date : ("'".date('Y-m-d H:i:s', time())."'"));
            // $interval = ($interval ? $interval : ("'" . date('H:i', strtotime('-15 minutes')) . "-" . date('H:i', time()) . "'"));
            $start_date = "'".date('Y-m-d 18:45:01')."'";
            $end_date = "'".date('Y-m-d 19:00:00')."'";
            $interval = "'18:45-19:00'";
            
            foreach ($clients as $client) {
                
                $result = null;
                $campaign_list = null;
                $queue_list = null;
                $queue_names = null;
                $agent_list = null;
                $queue_ids = null;
                $queues = null;
                $dni_numbers = [];
                $msg = null;
                
                // fetch client connections
                $db_name = User::decrypt_data($client['db']);
                $db_host = User::decrypt_data($client['server']);
                $db_username = User::decrypt_data($client['username']);
                $db_password = User::decrypt_data($client['password']);
                
                // CHECK DB SERVER CONNECTION
                try{
                    $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);
                }catch(ErrorException $e){
                    continue;
                }

                \Yii::$app->db->close(); // make sure it clean
                \Yii::$app->db->dsn = 'mysql:host='.$db_host.';dbname='.$db_name;
                \Yii::$app->db->username = $db_username;
                \Yii::$app->db->password = $db_password;

                // create acd report table if not exist
                $create_acd_query = 'CREATE TABLE IF NOT EXISTS `vaani_acd_report` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `date` VARCHAR(45) NULL,
                    `campaign` VARCHAR(45) NULL,
                    `queue` VARCHAR(45) NULL,
                    `interval` VARCHAR(45) NULL,
                    `start_date` VARCHAR(45) NULL,
                    `end_date` VARCHAR(45) NULL,
                    `offered` VARCHAR(45) NULL,
                    `agent_count` VARCHAR(45) NULL,
                    `agent_offered` VARCHAR(45) NULL,
                    `answered_in_10_sec` VARCHAR(45) NULL,
                    `answered_in_20_sec` VARCHAR(45) NULL,
                    `answered_in_30_sec` VARCHAR(45) NULL,
                    `answered_in_40_sec` VARCHAR(45) NULL,
                    `answered_in_50_sec` VARCHAR(45) NULL,
                    `answered_in_60_sec` VARCHAR(45) NULL,
                    `answered_in_90_sec` VARCHAR(45) NULL,
                    `answered_in_120_sec` VARCHAR(45) NULL,
                    `answered_after_120_sec` VARCHAR(45) NULL,
                    `calls_in_queue` VARCHAR(45) NULL,
                    `talk_time` VARCHAR(200) NULL,
                    `wrap_time` VARCHAR(200) NULL,
                    `hold_time` VARCHAR(200) NULL,
                    `call_abandoned` VARCHAR(200) NULL,
                    `abandoned_on_ivr` VARCHAR(200) NULL,
                    `abandoned_on_agent` VARCHAR(200) NULL,
                    `abandoned_in_10_sec` VARCHAR(200) NULL,
                    `abandoned_in_20_sec` VARCHAR(200) NULL,
                    `abandoned_in_30_sec` VARCHAR(200) NULL,
                    `abandoned_in_40_sec` VARCHAR(200) NULL,
                    `abandoned_in_50_sec` VARCHAR(200) NULL,
                    `abandoned_in_60_sec` VARCHAR(200) NULL,
                    `abandoned_in_90_sec` VARCHAR(200) NULL,
                    `abandoned_in_120_sec` VARCHAR(200) NULL,
                    `abandoned_after_120_sec` VARCHAR(200) NULL,
                    `answered_percent` VARCHAR(200) NULL,
                    `abandoned_percent` VARCHAR(200) NULL,
                    `inserted_at` VARCHAR(45) NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `id_UNIQUE` (`id` ASC))
                    COMMENT = "Stores calculated acd report values from cron";';
                
                Yii::$app->Utility->addLog("** CREATE ACD TABLE QUERY ** " . $create_acd_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");    //log
                $create_acd_table = \Yii::$app->db->createCommand($create_acd_query)->execute();
                
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
                $check_start_date = "'".date('Y-m-d 18:45:01')."'";
                $check_end_date = "'".date('Y-m-d 19:00:00')."'";

                $last_cron_query = 'select id
                from vaani_client_cron
                where client_id = "' . $client['client_id'] . '" AND cron_name = "acd_cron" and last_executed_at between ' . $check_start_date . ' and ' . $check_end_date  . ' ';
                
                Yii::$app->Utility->addLog("FETCH LAST CRON QUERY => " . $last_cron_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
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

                        // fetch dni number
                        $dni_ids = ArrayHelper::getColumn($queues, 'dni_id');
                        
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

                    $agents = ($agent_list ? "'".strtolower(implode("','", $agent_list))."'" : "''");
                    $queue_names = ($queue_names ? "'".implode("','", $queue_names)."'" : "''");
                    $dni_numbers = "'".implode("','", $dni_numbers)."'";
                    $queue_ids = ($queue_ids ? "'".implode("','", $queue_ids)."'" : "''" );

                    // create temp table 1
                    $temp_query = \Yii::$app->db->createCommand("DROP TABLE if exists tempAprTable;")->execute();
                    
                    $temp_query_string = "CREATE TEMPORARY TABLE tempAprTable (
                        `id` int NOT NULL,
                        `date` varchar(45) DEFAULT NULL,
                        `agent_id` varchar(45) DEFAULT NULL,
                        `agent_name` varchar(100) NOT NULL,
                        `unique_id` varchar(45) DEFAULT NULL,
                        `dni_number` varchar(45) DEFAULT NULL,
                        `campaign` varchar(100) DEFAULT NULL,
                        `queue` varchar(100) DEFAULT NULL,
                        `start_time` varchar(45) DEFAULT NULL,
                        `end_time` varchar(45) DEFAULT NULL,
                        `queue_hold_time` varchar(45) DEFAULT NULL,
                        `duration` varchar(45) DEFAULT NULL,
                        `hangup_by` varchar(45) DEFAULT NULL
                    ) ENGINE=InnoDB";
                 
                    Yii::$app->Utility->addLog("** CREATE TEMP TABLE QUERY ** " . $temp_query_string, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query = \Yii::$app->db->createCommand($temp_query_string)->execute();
                    
                    // insert inbound table data in temporary table
                    $temp_query_string = "INSERT INTO tempAprTable
                    ( `id`, `date`, `agent_id`, `agent_name`, `unique_id`, `dni_number`, `campaign`, `queue`, `start_time`, `end_time`, `queue_hold_time`, `duration`, `hangup_by`)
                    SELECT 
                        `id`, `date`, `agent_id`, `agent_name`, `unique_id`, `dni_number`, `campaign`, `queue`, `start_time`, `end_time`, `queue_hold_time`, `duration`, `hangup_by`
                    FROM
                        `inbound_edas`
                    WHERE
                        (agent_id IN (" . $agents . ") || agent_id IS NULL)
                        AND end_time BETWEEN " . $start_date . " AND " . $end_date . "
                        AND queue IN (" . $queue_names . ")
                    GROUP BY id";

                    Yii::$app->Utility->addLog("** INSERT INTO TEMP TABLE QUERY FROM INBOUND ** " . $temp_query_string, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query = \Yii::$app->db->createCommand($temp_query_string)->execute();

                    // create temp table 2
                    $temp_query_string1 = "CREATE TEMPORARY TABLE tempAprTable1 (
                        `id` int NOT NULL,
                        `unique_id` varchar(45) DEFAULT NULL,
                        `talk` varchar(100) DEFAULT NULL,
                        `wrap` varchar(100) DEFAULT NULL,
                        `hold` varchar(45) DEFAULT NULL
                    ) ENGINE=InnoDB";

                    Yii::$app->Utility->addLog("** CREATE 2nd TEMP TABLE QUERY ** " . $temp_query_string1, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query1 = \Yii::$app->db->createCommand($temp_query_string1)->execute();

                    $inbound_unique_ids_query = "SELECT unique_id FROM tempAprTable";

                    Yii::$app->Utility->addLog("** FETCH UNIQUE IDS QUERY ** " . $inbound_unique_ids_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $inbound_unique_ids = \Yii::$app->db->createCommand($inbound_unique_ids_query)->queryAll();

                    $unique_ids = "''";
                    if($inbound_unique_ids){
                        $unique_ids = ArrayHelper::getColumn($inbound_unique_ids, 'unique_id');
                        $unique_ids = "'".strtolower(implode("','", $unique_ids))."'";
                    }
                    
                    // insert agent call report table data in temporary1 table
                    $temp_query_string1 = "INSERT INTO tempAprTable1
                    ( `id`, `unique_id`, `talk`, `wrap`, `hold`)
                    SELECT 
                        `id`, `unique_id`, `talk`, `wrap`, `hold`
                    FROM
                        `vaani_agent_call_report`
                    WHERE
                        unique_id IN (" . $unique_ids . ")
                    GROUP BY id";
                    
                    Yii::$app->Utility->addLog("** INSERT INTO TEMP TABLE QUERY FROM AGENT CALL REPORT TABLE ** " . $temp_query_string1, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query1 = \Yii::$app->db->createCommand($temp_query_string1)->execute();
                    
                    // CALCULATE RECORDS EXECUTED COUNT AND IDS
                    $temp_data_query_string = "SELECT count(*) as records_executed, MIN(id) as executed_first_id, MAX(id) as executed_last_id FROM tempAprTable";

                    Yii::$app->Utility->addLog("** FETCH RECORDS EXECUTED QUERY ** " . $temp_data_query_string, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_data_query = \Yii::$app->db->createCommand($temp_data_query_string)->queryOne();

                    if($temp_data_query){
                        $records_executed = $temp_data_query['records_executed'];
                        $executed_first_id = $temp_data_query['executed_first_id'];
                        $executed_last_id = $temp_data_query['executed_last_id'];
                    }

                    // check if records executed, then add data & cron
                    if($records_executed > 0){
                        // FETCH QUEUE WISE AGENT COUNT
                        $queue_agents_query = "(SELECT queue, count(vua.id) as `agents` FROM vaani_user_access vua
                        LEFT JOIN vaani_campaign_queue queue_data on vua.queue_id = queue_data.queue_id
                        WHERE 
                            vua.queue_id IN (" . $queue_ids . ")
                            and vua.user_id IN (" . $agents . ") and vua.access_level = " . VaaniUserAccess::LEVEL_QUEUE . " and vua.del_status = ". VaaniUserAccess::STATUS_NOT_DELETED ." 
                        group by queue_data.queue)";

                        Yii::$app->Utility->addLog("** EXECUTED QUEUE AGENTS COUNT QUERY ** " . $queue_agents_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                        $queue_agents = \Yii::$app->pa_db->createCommand($queue_agents_query)->queryAll();

                        if($queue_agents){
                            $queue_agents = array_combine(array_column($queue_agents, 'queue'), array_column($queue_agents, 'agents'));
                        }

                        // FETCH CALCULATED DATA FROM TEMP TABLE
                        $result_query = "SELECT
                            inbound.date,
                            inbound.queue as 'service',
                            inbound.campaign,
                            count(inbound.id) as `calls_offered`,
                            COUNT(case when inbound.end_time IS NOT NULL AND inbound.agent_id IS NOT NULL then inbound.id end) as 'agent_offered',
                            COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time >= 0 and queue_hold_time <= 10 then inbound.id end) as 'answered_in_10_sec',
                            COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 10 and queue_hold_time <= 20 then inbound.id end) as 'answered_in_20_sec',
                            COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 20 and queue_hold_time <= 30 then inbound.id end) as 'answered_in_30_sec',
                            COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 30 and queue_hold_time <= 40 then inbound.id end) as 'answered_in_40_sec',
                            COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 40 and queue_hold_time <= 50 then inbound.id end) as 'answered_in_50_sec',
                            COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 50 and queue_hold_time <= 60 then inbound.id end) as 'answered_in_60_sec',
                            COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 60 and queue_hold_time <= 90 then inbound.id end) as 'answered_in_90_sec',
                            COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 90 and queue_hold_time <= 120 then inbound.id end) as 'answered_in_120_sec',
                            COUNT(case when inbound.agent_id IS NOT NULL and inbound.end_time IS NOT NULL and inbound.queue_hold_time > 120 then inbound.id end) as 'answered_>_120_sec',
                            COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NULL then inbound.id end) as 'calls_in_queue',
                            convert(sec_to_time(sum(report.talk)),time) as `talk_time`,
                            convert(sec_to_time(sum(report.wrap)),time) as `wrap_time`,
                            convert(sec_to_time(sum(report.hold)),time) as `hold_time`,
                            COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL then inbound.id end) as 'call_abandoned',
                            COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and hangup_by = 'CUSTOMER' then inbound.id end) as 'abandoned_on_ivr',
                            COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and hangup_by = 'AGENT' then inbound.id end) as 'abandoned_on_agent',
                            COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) >= 0 and (inbound.duration) <= 10 then inbound.id end) as 'abandoned_in_10_sec',
                            COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 10 and (inbound.duration) <= 20 then inbound.id end) as 'abandoned_in_20_sec',
                            COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 20 and (inbound.duration) <= 30 then inbound.id end) as 'abandoned_in_30_sec',
                            COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 30 and (inbound.duration) <= 40 then inbound.id end) as 'abandoned_in_40_sec',
                            COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 40 and (inbound.duration) <= 50 then inbound.id end) as 'abandoned_in_50_sec',
                            COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 50 and (inbound.duration) <= 60 then inbound.id end) as 'abandoned_in_60_sec',
                            COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 60 and (inbound.duration) <= 90 then inbound.id end) as 'abandoned_in_90_sec',
                            COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 90 and (inbound.duration) <= 120 then inbound.id end) as 'abandoned_in_120_sec',
                            COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL and (inbound.duration) > 120 then inbound.id end) as 'abandoned_>_120_sec',
                            ROUND(((
                                (COUNT(case when inbound.agent_id IS NOT NULL then inbound.id end)) / 
                                (count(inbound.id))
                            )*100), 2) as 'answered_percent',
                            ROUND(((
                                (COUNT(case when inbound.agent_id IS NULL and inbound.end_time IS NOT NULL then inbound.id end)) / 
                                (count(inbound.id))
                            )*100), 2) as 'abandoned_percent'
                            
                            FROM tempAprTable inbound
                                left join tempAprTable1 report on inbound.unique_id=report.unique_id
                            WHERE
                                end_time BETWEEN " . $start_date . " and " . $end_date . "
                                and dni_number IN (" . $dni_numbers . ")
                            group by inbound.queue
                        ";
                    
                        Yii::$app->Utility->addLog("** SELECT MANIPULATED DATA FROM TEMP TABLE QUERY ** " . $result_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                        $result = \Yii::$app->db->createCommand($result_query)->queryAll();
                        
                        if($result){ 
                            // echo "<pre>"; print_r($result); exit;
                        }

                        /// INSERT CALCULATED DATE IN ACD REPORT TABLE
                        foreach ($result as $i => $data) {
                            $final_result_query = "INSERT INTO vaani_acd_report
                            ( `date`, `campaign`, `queue`, `interval`, `start_date`, `end_date`, `offered`, `agent_count`, `agent_offered`, `answered_in_10_sec`, `answered_in_20_sec`, `answered_in_30_sec`, `answered_in_40_sec`, `answered_in_50_sec`, `answered_in_60_sec`, `answered_in_90_sec`, `answered_in_120_sec`, `answered_after_120_sec`, `calls_in_queue`, `talk_time`, `wrap_time`, `hold_time`, `call_abandoned`, `abandoned_on_ivr`, `abandoned_on_agent`, `abandoned_in_10_sec`, `abandoned_in_20_sec`, `abandoned_in_30_sec`, `abandoned_in_40_sec`, `abandoned_in_50_sec`, `abandoned_in_60_sec`, `abandoned_in_90_sec`, `abandoned_in_120_sec`, `abandoned_after_120_sec`, `answered_percent`, `abandoned_percent`, `inserted_at` )
                            VALUES
                            (
                                '".$data['date']."',
                                '".$data['campaign']."',
                                '".$data['service']."',
                                ".$interval.",
                                ".$start_date .",
                                ".$end_date.",
                                ".$data['calls_offered'].",
                                ".$queue_agents[$data['service']].",
                                ".$data['agent_offered'].",
                                ".$data['answered_in_10_sec'].",
                                ".$data['answered_in_20_sec'].",
                                ".$data['answered_in_30_sec'].",
                                ".$data['answered_in_40_sec'].",
                                ".$data['answered_in_50_sec'].",
                                ".$data['answered_in_60_sec'].",
                                ".$data['answered_in_90_sec'].",
                                ".$data['answered_in_120_sec'].",
                                ".$data['answered_>_120_sec'].",
                                ".$data['calls_in_queue'].",
                                '".$data['talk_time']."',
                                '".$data['wrap_time']."',
                                '".$data['hold_time']."',
                                ".$data['call_abandoned'].",
                                ".$data['abandoned_on_ivr'].",
                                ".$data['abandoned_on_agent'].",
                                ".$data['abandoned_in_10_sec'].",
                                ".$data['abandoned_in_20_sec'].",
                                ".$data['abandoned_in_30_sec'].",
                                ".$data['abandoned_in_40_sec'].",
                                ".$data['abandoned_in_50_sec'].",
                                ".$data['abandoned_in_60_sec'].",
                                ".$data['abandoned_in_90_sec'].",
                                ".$data['abandoned_in_120_sec'].",
                                ".$data['abandoned_>_120_sec'].",
                                ".$data['answered_percent'].",
                                ".$data['abandoned_percent'].",
                                '".date('Y-m-d H:i:s', time())."'
                            )";

                            Yii::$app->Utility->addLog("** INSERT RECORD INTO ACD TABLE QUERY ** " . $final_result_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                            $final_result = \Yii::$app->db->createCommand($final_result_query)->execute();
                            
                            if($final_result){
                                $msg .= "Data Inserted Successfully!";
                            }
                        }
                    
                        // insert cron record
                        $add_cron_query = 'INSERT INTO vaani_client_cron
                        ( `client_id`, `client_name`, `cron_name`, `last_executed_at`, `records_executed`, `executed_first_id`, `executed_last_id`, `status` )
                        VALUES
                        (
                            "'.$client['client_id'].'",
                            "'.$client['client_name'].'",
                            "acd_cron",
                            '.$end_date.',
                            "'.$records_executed.'",
                            "'.$executed_first_id.'",
                            "'.$executed_last_id.'",
                            1
                        )';

                        Yii::$app->Utility->addLog("** INSERT CRON RECORD QUERY ** " . $add_cron_query, 'acd_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                        $add_cron = \Yii::$app->db->createCommand($add_cron_query)
                        ->execute();

                        if($add_cron){
                            $msg .= "Cron data added!";
                        }
                    }else{
                        $msg .= "No Records found!";
                    }
                    
                    // DROP TEMPORARY TABLE
                    $drop_temp = \Yii::$app->db->createCommand("DROP TEMPORARY TABLE tempAprTable;")->execute();
                    if($drop_temp){
                        echo "Temp Table deleted!";
                    }
                    
                    // DROP TEMPORARY TABLE
                    $drop_temp = \Yii::$app->db->createCommand("DROP TEMPORARY TABLE tempAprTable1;")->execute();
                    if($drop_temp){
                        echo "Temp Table deleted!";
                    }
                    
                    echo $msg;
                }else{
                    echo "Data is already generated!";
                }
            }
        }
    }

    // fetch call register details cron
    public function actionCallRegister($start_date=null, $end_date=null, $interval=null)
    {
        $clients = VaaniClientMaster::find()->where(['del_status' => VaaniClientMaster::STATUS_NOT_DELETED])->asArray()->all();
        
        if(isset($clients)){
            // $start_date = ($start_date ? $start_date : ("'".date('Y-m-d H:i:s', strtotime('-15 minutes'))."'"));
            // $end_date = ($end_date ? $end_date : ("'".date('Y-m-d H:i:s', time())."'"));
            // $interval = ($interval ? $interval : ("'" . date('H:i', strtotime('-15 minutes')) . "-" . date('H:i', time()) . "'"));
            $start_date = "'".date('Y-m-d 18:45:01')."'";
            $end_date = "'".date('Y-m-d 19:00:00')."'";
            $interval = "'18:45-19:00'";
        
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

                // CHECK DB SERVER CONNECTION
                try{
                    $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);
                }catch(ErrorException $e){
                    continue;
                }

                \Yii::$app->db->close(); // make sure it clean
                \Yii::$app->db->dsn = 'mysql:host='.$db_host.';dbname='.$db_name;
                \Yii::$app->db->username = $db_username;
                \Yii::$app->db->password = $db_password;

                // create call_register report table if not exist
                    // $temp_query = \Yii::$app->db->createCommand("DROP TABLE if exists vaani_call_register_report;")->execute();
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
                    `start_time` VARCHAR(45) NULL,
                    `end_time` VARCHAR(45) NULL,
                    `cli` VARCHAR(45) NULL,
                    `call_type` VARCHAR(45) NULL,
                    `call_status` VARCHAR(45) NULL,
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
                $check_start_date = "'".date('Y-m-d 18:45:01')."'";
                $check_end_date = "'".date('Y-m-d 19:00:00')."'";

                $last_cron_query = 'select id
                from vaani_client_cron
                where client_id = "' . $client['client_id'] . '" AND cron_name = "call_register_cron" and last_executed_at between ' . $check_start_date . ' and ' . $check_end_date  . ' ';
                
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

                    $agents = "'".($agent_list ? strtolower(implode("','", $agent_list)) : null)."'";
                    $queue_names = "'".($queue_names ? implode("','", $queue_names) : null)."'";

                    // create temp table
                    $temp_query = \Yii::$app->db->createCommand("DROP TABLE if exists tempAprTable;")->execute();
                    
                    $temp_query_string = "CREATE TEMPORARY TABLE tempAprTable (
                        `id` int NOT NULL,
                        `agent_id` varchar(45) DEFAULT NULL,
                        `unique_id` varchar(45) DEFAULT NULL,
                        `agent_name` varchar(100) NOT NULL,
                        `date` varchar(45) DEFAULT NULL,
                        `start_time` varchar(45) DEFAULT NULL,
                        `end_time` varchar(45) DEFAULT NULL,
                        `campaign` varchar(100) DEFAULT NULL,
                        `queue` varchar(100) DEFAULT NULL,
                        `ring_duration` bigint DEFAULT NULL,
                        `hold_time` bigint DEFAULT NULL,
                        `talk_time` bigint DEFAULT NULL,
                        `wrap_duration` bigint DEFAULT NULL,
                        `mobile_no` varchar(45) DEFAULT NULL,
                        `status` varchar(20) DEFAULT NULL,
                        `disposition` varchar(50) DEFAULT NULL,
                        `recording_path` varchar(200) DEFAULT NULL,
                        `call_type` varchar(20) DEFAULT NULL
                    ) ENGINE=InnoDB";
                    
                    Yii::$app->Utility->addLog("** CREATE TEMP TABLE QUERY ** " . $temp_query_string, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                    $temp_query = \Yii::$app->db->createCommand($temp_query_string)->execute();
                    
                    // echo $client['client_id'];
                    // insert into temp table from vaani_agent_call_report table
                    // inbound
                    $temp_query_string = "INSERT INTO tempAprTable
                    ( `id`, `agent_id`, `unique_id`, `agent_name`, `date`, `start_time`, `end_time`, `campaign`, `queue`, `ring_duration`, `hold_time`, `talk_time`, `wrap_duration`, `mobile_no`, `status`, `disposition`, `recording_path`, `call_type`)
                    SELECT 
                        vacr.`id`, vacr.`agent_id`, vacr.`unique_id`, vacr.`agent_name`, `insert_date`, `start_date`, `end_date`, `campaign_name`, `queue_name`, `ringing`, `hold`, `talk`, `wrap`, `caller_id`, `call_status`, disp.`disposition_name`, `recording_path`, `call_type`
                    FROM
                        `vaani_agent_call_report` vacr
                        left join vaani_dispositions disp on disp.short_code = vacr.disposition
                    WHERE
                        (vacr.agent_id IN (" . $agents . ") OR vacr.agent_id IS NULL)
                        AND vacr.end_date BETWEEN " . $start_date . " AND " . $end_date . "
                        AND vacr.queue_name IN (" . $queue_names . ")
                    GROUP BY id";
                    
                    Yii::$app->Utility->addLog("** INSERT INTO TEMP TABLE QUERY FROM AGENT REPORT ** " . $temp_query_string, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
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
                    
                    // check if records executed, then add data & cron
                    if($records_executed > 0){
                        foreach ($queue_list as $queue_id => $queue) {
                            $users = VaaniCampaignQueue::usersList(null, $queue_id, User::agentRoleId());
                            $agent_list = ArrayHelper::getColumn($users, 'user_id');
                            $agents = "'".strtolower(implode("','", $agent_list))."'";

                            // foreach ($agent_list as $i => $agent_id) {
                                $final_result_query = "INSERT INTO vaani_call_register_report
                                ( `unique_id`, `agent_id`, `agent_name`, `campaign`, `queue`, `date`, `interval`, `start_date`, `end_date`, `start_time`, `end_time`, `cli`, `call_type`, `call_status`, `disposition`, `duration`, `hold_duration`, `ring_duration`, `talk_duration`, `wrap_duration`, `recording_path`, `inserted_at` )
                                SELECT 
                                    `unique_id`, `agent_id`, `agent_name`, `campaign`,`queue`, '".date('Y-m-d')."', ".$interval.", ".$start_date .", ".$end_date.", `start_time`, `end_time`, `mobile_no`, `call_type`, `status`, `disposition`,

                                    TIME_FORMAT(SEC_TO_TIME( (CASE WHEN `hold_time` IS NOT NULL THEN `hold_time` ELSE 0 END) + (CASE WHEN `ring_duration` IS NOT NULL THEN `ring_duration` ELSE 0 END) + (CASE WHEN `talk_time` IS NOT NULL THEN `talk_time` ELSE 0 END) + (CASE WHEN `wrap_duration` IS NOT NULL THEN `wrap_duration` ELSE 0 END) ), '%H:%i:%s') as duration, 

                                    sec_to_time(CASE WHEN `hold_time` IS NOT NULL THEN `hold_time` ELSE 0 END) as hold, 
                                    sec_to_time(CASE WHEN `ring_duration` IS NOT NULL THEN `ring_duration` ELSE 0 END) as ring, 
                                    sec_to_time(CASE WHEN `talk_time` IS NOT NULL THEN `talk_time` ELSE 0 END) as talk, 
                                    sec_to_time(CASE WHEN `wrap_duration` IS NOT NULL THEN `wrap_duration` ELSE 0 END) as wrap,

                                    `recording_path`,
                                    '".date('Y-m-d H:i:s', time())."'
                                FROM
                                    `tempAprTable`
                                WHERE
                                    (agent_id IN (" . $agents . ") OR agent_id IS NULL)
                                    and queue = '" . $queue . "'
                                ";
                                
                                Yii::$app->Utility->addLog("** INSERT RECORD INTO CALL REGISTER TABLE QUERY ** " . $final_result_query, 'call_register_cron', \Yii::getAlias('@runtime') . "/logs/");      // LOG
                                $final_result = \Yii::$app->db->createCommand($final_result_query)->execute();
                                
                                if($final_result){
                                    echo "Data Inserted!";
                                }
                            // }
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
                    }

                    // DROP TEMPORARY TABLE
                    $drop_temp = \Yii::$app->db->createCommand("DROP TEMPORARY TABLE tempAprTable;")->execute();
                    if($drop_temp){
                        echo "Temp Table deleted!";
                    }
                }
            }
        }
    }
}