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
-- Table structure for table `vaani_acd_report`
--

DROP TABLE IF EXISTS `vaani_acd_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_acd_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campaign` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `queue` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interval` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_date` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `offered` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent_count` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent_offered` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_in_10_sec` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_in_20_sec` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_in_30_sec` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_in_40_sec` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_in_50_sec` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_in_60_sec` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_in_90_sec` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_in_120_sec` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_after_120_sec` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calls_in_queue` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `talk_time` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wrap_time` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hold_time` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_abandoned` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abandoned_on_ivr` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abandoned_on_agent` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abandoned_in_10_sec` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abandoned_in_20_sec` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abandoned_in_30_sec` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abandoned_in_40_sec` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abandoned_in_50_sec` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abandoned_in_60_sec` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abandoned_in_90_sec` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abandoned_in_120_sec` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abandoned_after_120_sec` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_percent` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abandoned_percent` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inserted_at` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores calculated acd report values from cron';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_acd_report`
--

LOCK TABLES `vaani_acd_report` WRITE;
/*!40000 ALTER TABLE `vaani_acd_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `vaani_acd_report` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:05
