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
-- Table structure for table `vaani_agent_call_report`
--

DROP TABLE IF EXISTS `vaani_agent_call_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_agent_call_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `insert_date` datetime DEFAULT NULL,
  `updated_date` datetime NOT NULL,
  `campaign_name` varchar(100) DEFAULT NULL,
  `call_type` tinyint(4) DEFAULT NULL COMMENT '1-manual, 2-inbound, 3-outbound',
  `start_date` varchar(45) DEFAULT NULL,
  `end_date` varchar(45) DEFAULT NULL,
  `recording_path` varchar(45) DEFAULT NULL,
  `call_status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_agent_call_report`
--

LOCK TABLES `vaani_agent_call_report` WRITE;
/*!40000 ALTER TABLE `vaani_agent_call_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `vaani_agent_call_report` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:44:50
