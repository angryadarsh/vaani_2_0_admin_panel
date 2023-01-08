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
-- Table structure for table `vaani_apr_report`
--

DROP TABLE IF EXISTS `vaani_apr_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_apr_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` varchar(45) DEFAULT NULL,
  `queue_id` varchar(45) DEFAULT NULL,
  `queue` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `interval` varchar(45) DEFAULT NULL,
  `start_date` varchar(45) DEFAULT NULL,
  `end_date` varchar(45) DEFAULT NULL,
  `agent_name` varchar(45) DEFAULT NULL,
  `total_calls` varchar(45) DEFAULT NULL,
  `inbound_answered` varchar(45) DEFAULT NULL,
  `outbound_answered` varchar(45) DEFAULT NULL,
  `login_time` varchar(45) DEFAULT NULL,
  `not_ready` varchar(45) DEFAULT NULL,
  `idle_duration` varchar(45) DEFAULT NULL,
  `ring_duration` varchar(45) DEFAULT NULL,
  `talk_duration` varchar(45) DEFAULT NULL,
  `hold_duration` varchar(45) DEFAULT NULL,
  `wrap_up_duration` varchar(45) DEFAULT NULL,
  `inserted_at` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores calculated apr report values from cron';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_apr_report`
--

LOCK TABLES `vaani_apr_report` WRITE;
/*!40000 ALTER TABLE `vaani_apr_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `vaani_apr_report` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:44:53
