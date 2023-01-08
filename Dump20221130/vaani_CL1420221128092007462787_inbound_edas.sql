-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 172.30.10.102    Database: vaani_CL1420221128092007462787
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
-- Table structure for table `inbound_edas`
--

DROP TABLE IF EXISTS `inbound_edas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inbound_edas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` varchar(20) DEFAULT NULL,
  `time` varchar(20) DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `Time_Session` varchar(20) DEFAULT NULL,
  `campaign` varchar(50) DEFAULT NULL,
  `weekday` varchar(20) DEFAULT NULL,
  `unique_id` varchar(20) DEFAULT NULL,
  `keyinput` varchar(10) DEFAULT NULL,
  `agent_id` varchar(20) DEFAULT NULL,
  `agent_name` varchar(50) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '(1 - queue, 2 - ivr)',
  `dni_number` varchar(50) DEFAULT NULL,
  `total_call_agent_handled` int(10) NOT NULL DEFAULT '0',
  `last_call_time` varchar(50) DEFAULT NULL,
  `queue` varchar(50) DEFAULT NULL,
  `queue_strategy` varchar(20) DEFAULT NULL,
  `queue_calls` int(10) NOT NULL DEFAULT '0',
  `queue_calls_completed` int(10) NOT NULL DEFAULT '0',
  `queue_calls_abondoned` int(10) NOT NULL DEFAULT '0',
  `start_time` varchar(30) DEFAULT NULL,
  `end_time` varchar(30) DEFAULT NULL,
  `duration` bigint(20) DEFAULT NULL,
  `queue_hold_time` bigint(20) DEFAULT '0',
  `hangup_by` varchar(20) DEFAULT 'CUSTOMER',
  `transfer_start_time` varchar(20) DEFAULT NULL,
  `trasfer_end_time` varchar(20) DEFAULT NULL,
  `transfer_agent_id` varchar(20) DEFAULT NULL,
  `consult_start_time` varchar(20) DEFAULT NULL,
  `consult_end_time` varchar(20) DEFAULT NULL,
  `consult_agent_id` varchar(20) DEFAULT NULL,
  `conference_start_time` varchar(20) DEFAULT NULL,
  `conference_end_time` varchar(20) DEFAULT NULL,
  `conference_agent_id` varchar(20) DEFAULT NULL,
  `Column 35` varchar(20) DEFAULT NULL,
  `recording_path` varchar(180) DEFAULT NULL,
  `call_status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inbound_edas`
--

LOCK TABLES `inbound_edas` WRITE;
/*!40000 ALTER TABLE `inbound_edas` DISABLE KEYS */;
INSERT INTO `inbound_edas` VALUES (1,'9867170131','2022-11-28','16:35:43','MON','EVENING','hdfc_ergo_insure','WEEKDAY','1669633543.58',NULL,'',' SIP/11005',1,'00912269191010',0,'1970-01-01 05:30:00','hdfc_insure_146','rrordered',1,0,0,'2022-11-28 16:35:43','2022-11-28 16:36:03',20,10,'CUSTOMER',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/hdfc_ergo_insure/hdfc_insure_146/2022/11/28/edas-9867170131-1669633543.58',NULL),(2,'8369854211','2022-11-28','16:36:36','MON','EVENING','hdfc_ergo_insure','WEEKDAY','1669633596.61',NULL,'',' SIP/11005',1,'00912269191010',1,'2022-11-28 16:36:03','hdfc_insure_146','rrordered',1,1,0,'2022-11-28 16:36:36','2022-11-28 16:36:53',17,10,'CUSTOMER',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/hdfc_ergo_insure/hdfc_insure_146/2022/11/28/edas-8369854211-1669633596.61',NULL),(3,'8059596523','2022-11-29','12:07:11','TUE','AFTERNOON','hdfc_ergo_insure','WEEKDAY','1669703831.82',NULL,NULL,NULL,1,'00912269191010',0,NULL,'hdfc_insure_146',NULL,0,0,0,'2022-11-29 12:07:11','2022-11-29 12:08:15',104,0,'CUSTOMER',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-8059596523-1669703831.82',NULL),(4,'8059596523','2022-11-29','12:08:36','TUE','AFTERNOON','hdfc_ergo_insure','WEEKDAY','1669703916.84',NULL,NULL,NULL,1,'00912269191010',0,NULL,'hdfc_insure_146',NULL,0,0,0,'2022-11-29 12:08:36','2022-11-29 12:09:40',104,0,'CUSTOMER',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-8059596523-1669703916.84',NULL),(5,'8059596523','2022-11-29','12:09:58','TUE','AFTERNOON','hdfc_ergo_insure','WEEKDAY','1669703997.86',NULL,NULL,NULL,1,'00912269191010',0,NULL,'hdfc_insure_146',NULL,0,0,0,'2022-11-29 12:09:57','2022-11-29 12:10:43',46,0,'CUSTOMER',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-8059596523-1669703997.86',NULL),(6,'8059596523','2022-11-29','12:11:15','TUE','AFTERNOON','hdfc_ergo_insure','WEEKDAY','1669704075.88',NULL,NULL,NULL,1,'00912269191010',0,NULL,'hdfc_insure_146',NULL,0,0,0,'2022-11-29 12:11:15','2022-11-29 12:12:17',102,0,'CUSTOMER',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-8059596523-1669704075.88',NULL);
/*!40000 ALTER TABLE `inbound_edas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:31
