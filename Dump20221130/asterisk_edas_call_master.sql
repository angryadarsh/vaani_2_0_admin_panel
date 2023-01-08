-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 172.30.10.102    Database: asterisk
-- ------------------------------------------------------
-- Server version	5.7.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `edas_call_master`
--

DROP TABLE IF EXISTS `edas_call_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `edas_call_master` (
  `call_number` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `call_start_time` datetime DEFAULT NULL,
  `call_end_time` datetime DEFAULT NULL,
  `call_dni` varchar(45) DEFAULT NULL,
  `call_cli` varchar(45) DEFAULT NULL,
  `call_cli2` varchar(45) DEFAULT NULL,
  `all_terminal_id` varchar(45) DEFAULT NULL,
  `call_agent_name` varchar(45) DEFAULT NULL,
  `call_agent_id` varchar(45) DEFAULT NULL,
  `call_service_id` varchar(45) DEFAULT NULL,
  `call_service_name` varchar(45) DEFAULT NULL,
  `call_session_id` varchar(45) DEFAULT NULL,
  `call_abandoned_acd` varchar(45) DEFAULT NULL,
  `call_type` varchar(45) DEFAULT NULL,
  `call_end_type` varchar(45) DEFAULT NULL,
  `call_end_type_name` varchar(45) DEFAULT NULL,
  `call_caller_drop` varchar(45) DEFAULT NULL,
  `call_ring_duration` varchar(45) DEFAULT NULL,
  `call_first_ring_duration` varchar(45) DEFAULT NULL,
  `call_hold_duration` varchar(45) DEFAULT NULL,
  `call_talk_duration` varchar(45) DEFAULT NULL,
  `call_ivr_duration` varchar(45) DEFAULT NULL,
  `call_total_duration` varchar(45) DEFAULT NULL,
  `call_wrapup_duration` varchar(45) DEFAULT NULL,
  `call_aftercall_duration` varchar(45) DEFAULT NULL,
  `call_queue_duration1` varchar(45) DEFAULT NULL,
  `call_agent_cnt` varchar(45) DEFAULT NULL,
  `call_group_acd` varchar(45) DEFAULT NULL,
  `call_did_acd` varchar(45) DEFAULT NULL,
  `call_wait_duration` varchar(45) DEFAULT NULL,
  `call_queue_duration` varchar(45) DEFAULT NULL,
  `call_queue_count` varchar(45) DEFAULT NULL,
  `call_transfer_count` varchar(45) DEFAULT NULL,
  `call_record_processed` varchar(45) DEFAULT NULL,
  `call_endtype_count_id` varchar(45) DEFAULT NULL,
  `call_outbound_id` varchar(45) DEFAULT NULL,
  `call_lead_id` varchar(45) DEFAULT NULL,
  `call_answer_size` varchar(45) DEFAULT NULL,
  `call_freq_dur` varchar(45) DEFAULT NULL,
  `call_freq_dur2` varchar(45) DEFAULT NULL,
  `call_freq_dur3` varchar(45) DEFAULT NULL,
  `call_freq_hz` varchar(45) DEFAULT NULL,
  `call_freq_hz2` varchar(45) DEFAULT NULL,
  `call_freq_hz3` varchar(45) DEFAULT NULL,
  `call_ivr_terminal` varchar(45) DEFAULT NULL,
  `call_lead_import_batch_no` varchar(45) DEFAULT NULL,
  `call_lead_zone` varchar(45) DEFAULT NULL,
  `call_ivr_board` varchar(45) DEFAULT NULL,
  `call_ivr_channel` varchar(45) DEFAULT NULL,
  `call_voicefile_name` varchar(45) DEFAULT NULL,
  `call_next_dial_time` datetime DEFAULT NULL,
  `call_bucket_code` varchar(45) DEFAULT NULL,
  `call_lead_batch_run_id` varchar(45) DEFAULT NULL,
  `call_phone_index` varchar(45) DEFAULT NULL,
  `call_voice_detected` varchar(45) DEFAULT NULL,
  `call_trunk_number` varchar(45) DEFAULT NULL,
  `call_alternate_number` varchar(45) DEFAULT NULL,
  `call_remark` varchar(45) DEFAULT NULL,
  `call_module` varchar(45) DEFAULT NULL,
  `call_processed` varchar(45) DEFAULT NULL,
  `call_preview_duration` varchar(45) DEFAULT NULL,
  `call_dial_duration` varchar(45) DEFAULT NULL,
  `call_user_parameter1` varchar(45) DEFAULT NULL,
  `call_user_parameter2` varchar(45) DEFAULT NULL,
  `call_user_parameter3` varchar(45) DEFAULT NULL,
  `call_user_parameter4` varchar(45) DEFAULT NULL,
  `call_user_parameter5` varchar(45) DEFAULT NULL,
  `call_user_parameter6` varchar(45) DEFAULT NULL,
  `call_user_parameter7` varchar(45) DEFAULT NULL,
  `call_user_parameter8` varchar(45) DEFAULT NULL,
  `call_user_parameter9` varchar(45) DEFAULT NULL,
  `call_user_parameter10` varchar(45) DEFAULT NULL,
  `call_user_parameter11` varchar(45) DEFAULT NULL,
  `call_user_parameter12` varchar(45) DEFAULT NULL,
  `call_user_parameter13` varchar(45) DEFAULT NULL,
  `call_user_parameter14` varchar(45) DEFAULT NULL,
  `call_user_parameter15` varchar(45) DEFAULT NULL,
  `call_user_parameter16` varchar(45) DEFAULT NULL,
  `call_user_parameter17` varchar(45) DEFAULT NULL,
  `call_user_parameter18` varchar(45) DEFAULT NULL,
  `call_user_parameter19` varchar(45) DEFAULT NULL,
  `call_user_parameter20` varchar(45) DEFAULT NULL,
  `call_ivr_wait_duration` varchar(45) DEFAULT NULL,
  `call_trunk_wait_duration` varchar(45) DEFAULT NULL,
  `call_current_phone_index` varchar(45) DEFAULT NULL,
  `call_isdn_cause` varchar(45) DEFAULT NULL,
  `call_flow_id` varchar(45) DEFAULT NULL,
  `call_city` varchar(45) DEFAULT NULL,
  `call_state` varchar(45) DEFAULT NULL,
  `call_region` varchar(45) DEFAULT NULL,
  `call_addon_url` varchar(45) DEFAULT NULL,
  `call_addon_url2` varchar(45) DEFAULT NULL,
  `call_addon_url3` varchar(45) DEFAULT NULL,
  `call_last_dial_number` varchar(45) DEFAULT NULL,
  `call_last_dial_time` varchar(45) DEFAULT NULL,
  `call_last_dial_status` varchar(45) DEFAULT NULL,
  `call_last_bucket_code` varchar(45) DEFAULT NULL,
  `call_block_mode` varchar(45) DEFAULT NULL,
  `call_agent_login_id` varchar(45) DEFAULT NULL,
  `call_level` varchar(45) DEFAULT NULL,
  `call_level1` varchar(45) DEFAULT NULL,
  `call_level2` varchar(45) DEFAULT NULL,
  `call_level3` varchar(45) DEFAULT NULL,
  `call_level4` varchar(45) DEFAULT NULL,
  `call_level5` varchar(45) DEFAULT NULL,
  `call_level1_name` varchar(45) DEFAULT NULL,
  `call_level2_name` varchar(45) DEFAULT NULL,
  `call_level3_name` varchar(45) DEFAULT NULL,
  `call_level4_name` varchar(45) DEFAULT NULL,
  `call_level5_name` varchar(45) DEFAULT NULL,
  `call_carrier_id` varchar(45) DEFAULT NULL,
  `call_carrier_name` varchar(45) DEFAULT NULL,
  `call_trunk_duration` varchar(45) DEFAULT NULL,
  `call_screenfile_name` varchar(45) DEFAULT NULL,
  `call_pacing_configured` varchar(45) DEFAULT NULL,
  `call_pacing_running` varchar(45) DEFAULT NULL,
  `call_fix_agent_id` varchar(45) DEFAULT NULL,
  `call_fix_agent_name` varchar(45) DEFAULT NULL,
  `call_pd_avg_wraptime1` varchar(45) DEFAULT NULL,
  `call_pd_avg_wraptime2` varchar(45) DEFAULT NULL,
  `call_total_agents_logged_in` varchar(45) DEFAULT NULL,
  `call_total_agents_idle` varchar(45) DEFAULT NULL,
  `call_lead_day_count` varchar(45) DEFAULT NULL,
  `call_lead_day_attempts` varchar(45) DEFAULT NULL,
  `call_lead_retry_interval` varchar(45) DEFAULT NULL,
  `call_server_number` varchar(45) DEFAULT NULL,
  `call_server_name` varchar(45) DEFAULT NULL,
  `call_peer_disconnected` varchar(45) DEFAULT NULL,
  `call_origin_acd` varchar(45) DEFAULT NULL,
  `call_ivr_dial_duration` varchar(45) DEFAULT NULL,
  `call_service_dnc_flag` varchar(45) DEFAULT NULL,
  `call_child_callnumber` varchar(45) DEFAULT NULL,
  `call_first_port` varchar(45) DEFAULT NULL,
  `call_parent_callnumber` varchar(45) DEFAULT NULL,
  `call_channel_type` varchar(45) DEFAULT NULL,
  `call_isdn_cause_agent` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`call_number`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edas_call_master`
--

LOCK TABLES `edas_call_master` WRITE;
/*!40000 ALTER TABLE `edas_call_master` DISABLE KEYS */;
INSERT INTO `edas_call_master` VALUES (1,NULL,NULL,'sam',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `edas_call_master` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:46:06
