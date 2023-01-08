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
-- Table structure for table `vaani_agent_live_status`
--

DROP TABLE IF EXISTS `vaani_agent_live_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_agent_live_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` varchar(50) DEFAULT NULL,
  `unique_id` varchar(50) DEFAULT NULL,
  `agent_id` varchar(20) DEFAULT NULL,
  `datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `campaign_name` varchar(50) DEFAULT NULL,
  `queue_name` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL COMMENT 'Incall,Hang up',
  `sub_status` varchar(50) DEFAULT NULL,
  `caller_id` varchar(50) DEFAULT NULL,
  `transfer_id` varchar(50) DEFAULT NULL,
  `call_type` int(10) unsigned DEFAULT NULL COMMENT '1 - manual, 2 - inbound, 3 - outbound, 4 - transfer, 5 - consult, 6 - conference, 7 - call_back',
  `end_time` datetime DEFAULT NULL,
  `disposition_time` datetime DEFAULT NULL,
  `conf_num` int(7) DEFAULT NULL,
  `conf_status` int(1) DEFAULT NULL COMMENT '1 for new 2 used',
  `redial` tinyint(2) DEFAULT '1' COMMENT '1-redial not set, 2-redial set',
  `is_active` int(2) NOT NULL DEFAULT '1',
  `syn` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `disposition` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_agent_id` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_agent_live_status`
--

LOCK TABLES `vaani_agent_live_status` WRITE;
/*!40000 ALTER TABLE `vaani_agent_live_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `vaani_agent_live_status` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:08
