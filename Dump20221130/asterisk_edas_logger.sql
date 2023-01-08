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
-- Table structure for table `edas_logger`
--

DROP TABLE IF EXISTS `edas_logger`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `edas_logger` (
  `chnl_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chnl_name` varchar(45) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `start_dt` varchar(45) DEFAULT NULL,
  `start_type` varchar(45) DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `end_dt` varchar(45) DEFAULT NULL,
  `end_type` varchar(45) DEFAULT NULL,
  `call_type` varchar(45) DEFAULT NULL,
  `smdr_id` varchar(45) DEFAULT NULL,
  `smdr_name` varchar(45) DEFAULT NULL,
  `Call_DNI` varchar(45) DEFAULT NULL,
  `Call_CLI` varchar(45) DEFAULT NULL,
  `trunk_num` varchar(45) DEFAULT NULL,
  `extn_num` varchar(45) DEFAULT NULL,
  `duration` varchar(45) DEFAULT NULL,
  `agent_name` varchar(45) DEFAULT NULL,
  `digits` varchar(45) DEFAULT NULL,
  `digits_cnt` varchar(45) DEFAULT NULL,
  `filepath` varchar(45) DEFAULT NULL,
  `filename` varchar(45) DEFAULT NULL,
  `filesize` varchar(45) DEFAULT NULL,
  `read_flag` varchar(45) DEFAULT NULL,
  `user_tag1` varchar(45) DEFAULT NULL,
  `user_tag2` varchar(45) DEFAULT NULL,
  `user_tag3` varchar(45) DEFAULT NULL,
  `user_tag4` varchar(45) DEFAULT NULL,
  `user_tag5` varchar(45) DEFAULT NULL,
  `rule_name` varchar(45) DEFAULT NULL,
  `file_exist` varchar(45) DEFAULT NULL,
  `pbx_num` varchar(45) DEFAULT NULL,
  `machine_no` varchar(45) DEFAULT NULL,
  `chnl_dept_name` varchar(45) DEFAULT NULL,
  `server_name` varchar(45) DEFAULT NULL,
  `chnl_dept_id` varchar(45) DEFAULT NULL,
  `supervisor_tag1` varchar(45) DEFAULT NULL,
  `supervisor_tag2` varchar(45) DEFAULT NULL,
  `supervisor_tag3` varchar(45) DEFAULT NULL,
  `supervisor_tag4` varchar(45) DEFAULT NULL,
  `supervisor_tag5` varchar(45) DEFAULT NULL,
  `lead_id` varchar(45) DEFAULT NULL,
  `file_encrypted` varchar(45) DEFAULT NULL,
  `uniqueid` varchar(45) DEFAULT NULL,
  `first_hangup_channel` varchar(45) DEFAULT NULL,
  `audit_file_path` varchar(45) DEFAULT NULL,
  `server_number` varchar(45) DEFAULT NULL,
  `path_updated` varchar(45) DEFAULT NULL,
  `vlog_call_type` varchar(45) DEFAULT NULL,
  `vlog_tagged_by` varchar(45) DEFAULT NULL,
  `vlog_tagged_at` varchar(45) DEFAULT NULL,
  `supervisor_tag6` varchar(45) DEFAULT NULL,
  `supervisor_tag7` varchar(45) DEFAULT NULL,
  `supervisor_tag8` varchar(45) DEFAULT NULL,
  `supervisor_tag9` varchar(45) DEFAULT NULL,
  `supervisor_tag10` varchar(45) DEFAULT NULL,
  `user_tag6` varchar(45) DEFAULT NULL,
  `user_tag7` varchar(45) DEFAULT NULL,
  `user_tag8` varchar(45) DEFAULT NULL,
  `user_tag9` varchar(45) DEFAULT NULL,
  `user_tag10` varchar(45) DEFAULT NULL,
  `cti_call_number` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`chnl_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edas_logger`
--

LOCK TABLES `edas_logger` WRITE;
/*!40000 ALTER TABLE `edas_logger` DISABLE KEYS */;
INSERT INTO `edas_logger` VALUES (1,'sam',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `edas_logger` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:46:23
