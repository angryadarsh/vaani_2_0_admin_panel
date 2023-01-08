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
-- Table structure for table `edas_session_master`
--

DROP TABLE IF EXISTS `edas_session_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `edas_session_master` (
  `session_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_start_time` datetime DEFAULT NULL,
  `session_end_time` datetime DEFAULT NULL,
  `session_agent_id` varchar(45) DEFAULT NULL,
  `session_terminal_id` varchar(45) DEFAULT NULL,
  `session_host` varchar(45) DEFAULT NULL,
  `session_acd_calls` varchar(45) DEFAULT NULL,
  `session_ivr_in_calls` varchar(45) DEFAULT NULL,
  `session_ivr_out_calls` varchar(45) DEFAULT NULL,
  `session_manual_calls` varchar(45) DEFAULT NULL,
  `session_abandon_calls` varchar(45) DEFAULT NULL,
  `session_busy_duration` varchar(45) DEFAULT NULL,
  `session_agent_duration` varchar(45) DEFAULT NULL,
  `session_idle_duration` varchar(45) DEFAULT NULL,
  `session_reserve_duration` varchar(45) DEFAULT NULL,
  `session_wrapup_duration` varchar(45) DEFAULT NULL,
  `session_outbound_service_id` varchar(45) DEFAULT NULL,
  `session_inbound_service_id` varchar(45) DEFAULT NULL,
  `session_pd_service_id` varchar(45) DEFAULT NULL,
  `session_level` varchar(45) DEFAULT NULL,
  `session_level1` varchar(45) DEFAULT NULL,
  `session_level2` varchar(45) DEFAULT NULL,
  `session_level3` varchar(45) DEFAULT NULL,
  `session_level4` varchar(45) DEFAULT NULL,
  `session_level5` varchar(45) DEFAULT NULL,
  `session_level1_name` varchar(45) DEFAULT NULL,
  `session_level2_name` varchar(45) DEFAULT NULL,
  `session_level3_name` varchar(45) DEFAULT NULL,
  `session_level4_name` varchar(45) DEFAULT NULL,
  `session_level5_name` varchar(45) DEFAULT NULL,
  `session_busy1_duration` varchar(45) DEFAULT NULL,
  `session_busy2_duration` varchar(45) DEFAULT NULL,
  `session_busy3_duration` varchar(45) DEFAULT NULL,
  `session_busy4_duration` varchar(45) DEFAULT NULL,
  `session_busy5_duration` varchar(45) DEFAULT NULL,
  `session_busy6_duration` varchar(45) DEFAULT NULL,
  `session_busy7_duration` varchar(45) DEFAULT NULL,
  `session_busy9_duration` varchar(45) DEFAULT NULL,
  `session_busy10_duration` varchar(45) DEFAULT NULL,
  `session_agent_preview_duration` varchar(45) DEFAULT NULL,
  `session_agent_dial_duration` varchar(45) DEFAULT NULL,
  `session_idle_noivr_duration` varchar(45) DEFAULT NULL,
  `session_idle_noleads_duration` varchar(45) DEFAULT NULL,
  `session_idle_block_duration` varchar(45) DEFAULT NULL,
  `session_block_duration` varchar(45) DEFAULT NULL,
  `session_agent_login_id` varchar(45) DEFAULT NULL,
  `session_agent_name` varchar(45) DEFAULT NULL,
  `session_server_number` varchar(45) DEFAULT NULL,
  `session_server_name` varchar(45) DEFAULT NULL,
  `session_server_name1` varchar(45) DEFAULT NULL,
  `session_email_calls` varchar(45) DEFAULT NULL,
  `session_sms_calls` varchar(45) DEFAULT NULL,
  `session_chat_calls` varchar(45) DEFAULT NULL,
  `session_social_media_calls` varchar(45) DEFAULT NULL,
  `session_email_service_id` varchar(45) DEFAULT NULL,
  `session_sms_service_id` varchar(45) DEFAULT NULL,
  `session_chat_service_id` varchar(45) DEFAULT NULL,
  `session_socialmedia_service_id` varchar(45) DEFAULT NULL,
  `session_end_reason` varchar(45) DEFAULT NULL,
  `session_end_reason_name` varchar(45) DEFAULT NULL,
  `session_auto_login` varchar(45) DEFAULT NULL,
  `session_parent_agent_id` varchar(45) DEFAULT NULL,
  `session_parent_session_id` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edas_session_master`
--

LOCK TABLES `edas_session_master` WRITE;
/*!40000 ALTER TABLE `edas_session_master` DISABLE KEYS */;
INSERT INTO `edas_session_master` VALUES (1,NULL,NULL,'sam',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `edas_session_master` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:46:25
