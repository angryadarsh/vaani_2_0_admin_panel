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
-- Table structure for table `appointment_reminder`
--

DROP TABLE IF EXISTS `appointment_reminder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointment_reminder` (
  `call_id` int(10) NOT NULL AUTO_INCREMENT,
  `context` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_mobile_number` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `officer_mobile_number` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `officer_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appointment_time` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `campaign_type` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_policies` int(4) DEFAULT NULL,
  `inforce_policies` int(4) DEFAULT NULL,
  `lapsed_policies` int(4) DEFAULT NULL,
  `fund_value` int(16) DEFAULT NULL,
  `bonus_amount` int(16) DEFAULT NULL,
  `scheduled_time` datetime DEFAULT NULL,
  `campaign_category` enum('dtmf','voice') COLLATE utf8_unicode_ci DEFAULT 'dtmf',
  `sent_to_dialer` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`call_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointment_reminder`
--

LOCK TABLES `appointment_reminder` WRITE;
/*!40000 ALTER TABLE `appointment_reminder` DISABLE KEYS */;
INSERT INTO `appointment_reminder` VALUES (12,'appointment_reminder','8802444546','2261846833','male','Dharmesh Mali','Wilson Nadar','14:35','service campaign',7,3,4,54000,15000,'2021-08-10 12:55:29','dtmf','1'),(13,'appointment_reminder','2261846833','8802444546','male','Dharmesh Mali','Wilson Nadar','14:35','service campaign',7,3,4,54000,15000,'2021-08-10 17:17:10','dtmf','1'),(14,'appointment_reminder','2261846833','8802444546','male','Dharmesh Mali','Wilson Nadar','14:35','service campaign',7,3,4,54000,15000,'2021-08-10 18:43:28','dtmf','1'),(15,'appointment_reminder','9427840088','8802444546','male','dharmesh','dharmesh','14:35','service campaign',7,7,4,54000,15000,'2021-08-11 18:15:00','dtmf','1'),(16,'appointment_reminder','9029447342','8369854211','male','dharmesh','dharmesh','14:35','service campaign',7,7,4,54000,15000,'2021-08-10 14:15:00','dtmf','1'),(17,'appointment_reminder','9029447342','8369854211','male','dharmesh','dharmesh','14:35','service campaign',7,7,4,54000,15000,'2021-08-10 14:15:00','dtmf','1');
/*!40000 ALTER TABLE `appointment_reminder` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:46:00
