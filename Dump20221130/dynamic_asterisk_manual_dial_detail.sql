-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 172.30.10.102    Database: dynamic_asterisk
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
-- Table structure for table `manual_dial_detail`
--

DROP TABLE IF EXISTS `manual_dial_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `manual_dial_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent_id` varchar(20) DEFAULT NULL,
  `agent_name` varchar(50) DEFAULT NULL,
  `date` varchar(20) DEFAULT NULL,
  `time` varchar(20) DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `Time_Session` varchar(20) DEFAULT NULL,
  `campaign` varchar(20) DEFAULT NULL,
  `queue_name` varchar(20) DEFAULT NULL,
  `weekday` varchar(20) DEFAULT NULL,
  `unique_id` varchar(20) DEFAULT NULL,
  `start_time` varchar(30) DEFAULT NULL,
  `end_time` varchar(30) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `duration` bigint(20) DEFAULT NULL,
  `ring_duration` bigint(20) DEFAULT NULL,
  `transfer_start_time` varchar(20) DEFAULT NULL,
  `trasfer_end_time` varchar(20) DEFAULT NULL,
  `transfer_agent_id` varchar(20) DEFAULT NULL,
  `consult_start_time` varchar(20) DEFAULT NULL,
  `consult_end_time` varchar(20) DEFAULT NULL,
  `consult_agent_id` varchar(20) DEFAULT NULL,
  `conference_start_time` varchar(20) DEFAULT NULL,
  `conference_end_time` varchar(20) DEFAULT NULL,
  `conference_agent_id` varchar(20) DEFAULT NULL,
  `transfer_channel` varchar(200) DEFAULT NULL,
  `hangup_by` varchar(20) DEFAULT 'CUSTOMER',
  `recording_path` varchar(180) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manual_dial_detail`
--

LOCK TABLES `manual_dial_detail` WRITE;
/*!40000 ALTER TABLE `manual_dial_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `manual_dial_detail` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:15
