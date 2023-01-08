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
-- Table structure for table `outbound_dialer`
--

DROP TABLE IF EXISTS `outbound_dialer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `outbound_dialer` (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `mobile_number` varchar(16) DEFAULT NULL,
  `second_party_number` varchar(16) DEFAULT NULL,
  `campaign_id` varchar(16) DEFAULT NULL,
  `retry_count` tinyint(4) DEFAULT '0',
  `call_status` enum('0','1','2') DEFAULT '0' COMMENT '0-to be dialed, 1-dialing done, 2-sent to dialer',
  `insert_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cli` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outbound_dialer`
--

LOCK TABLES `outbound_dialer` WRITE;
/*!40000 ALTER TABLE `outbound_dialer` DISABLE KEYS */;
INSERT INTO `outbound_dialer` VALUES (7,'8802444546',NULL,NULL,1,'1','2021-08-10 17:18:06','2021-08-10 11:48:06',NULL),(8,'8802444546',NULL,NULL,2,'1','2021-08-10 18:44:01','2021-08-10 13:19:01',NULL),(9,'8369854211','8076994599',NULL,0,'','2022-01-08 22:56:31','2022-01-08 17:26:31',NULL),(10,'8369854211','8076994599',NULL,0,'','2022-01-08 23:03:24','2022-01-08 17:33:24',NULL);
/*!40000 ALTER TABLE `outbound_dialer` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:54
