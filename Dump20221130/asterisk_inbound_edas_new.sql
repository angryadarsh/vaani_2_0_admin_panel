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
-- Table structure for table `inbound_edas_new`
--

DROP TABLE IF EXISTS `inbound_edas_new`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inbound_edas_new` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` varchar(20) DEFAULT NULL,
  `time` varchar(20) DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `Time_Session` varchar(20) DEFAULT NULL,
  `campaign` varchar(20) DEFAULT NULL,
  `weekday` varchar(20) DEFAULT NULL,
  `unique_id` varchar(20) DEFAULT NULL,
  `keyinput` varchar(10) DEFAULT NULL,
  `agent_id` varchar(10) DEFAULT NULL,
  `agent_name` varchar(50) DEFAULT NULL,
  `total_call_agent_handled` int(10) NOT NULL DEFAULT '0',
  `last_call_time` varchar(50) DEFAULT NULL,
  `queue` varchar(10) DEFAULT NULL,
  `queue_strategy` varchar(10) DEFAULT NULL,
  `queue_calls` int(10) NOT NULL DEFAULT '0',
  `queue_calls_completed` int(10) NOT NULL DEFAULT '0',
  `queue_calls_abondoned` int(10) NOT NULL DEFAULT '0',
  `end_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `queue_hold_time` int(10) DEFAULT '0',
  `hangup_by` varchar(20) DEFAULT NULL,
  `transfer_start_time` varchar(20) DEFAULT NULL,
  `trasfer_end_time` varchar(20) DEFAULT NULL,
  `transfer_agent_id` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inbound_edas_new`
--

LOCK TABLES `inbound_edas_new` WRITE;
/*!40000 ALTER TABLE `inbound_edas_new` DISABLE KEYS */;
/*!40000 ALTER TABLE `inbound_edas_new` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:51
