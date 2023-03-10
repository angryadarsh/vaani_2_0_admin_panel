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
-- Table structure for table `vaani_costomer_info`
--

DROP TABLE IF EXISTS `vaani_costomer_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_costomer_info` (
  `id` bigint(50) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_mobile` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_alt_mobile` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_status` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `disposition` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remark` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `callback_datetime` datetime NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `syn` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_costomer_info`
--

LOCK TABLES `vaani_costomer_info` WRITE;
/*!40000 ALTER TABLE `vaani_costomer_info` DISABLE KEYS */;
INSERT INTO `vaani_costomer_info` VALUES (1,NULL,'1234','Ashish vishwakarma','8108954065','8369854211','mumbai','active','','','1970-01-01 05:30:00','2022-02-10 00:00:00','2022-02-22 12:35:06',1),(2,'1644485925.2438','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 15:11:47',NULL,1),(3,'1644486116.2447','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 15:28:33',NULL,1),(4,'1644487121.2460','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 15:33:48',NULL,1),(5,'1644487439.2465','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 15:58:05',NULL,1),(6,'1644488930.2471','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 16:12:07',NULL,1),(7,'1644491053.2497','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 16:34:38',NULL,1),(8,'1644491087.2501','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 16:37:38',NULL,1),(9,'1644491267.2505','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 16:40:21',NULL,1),(10,'1644491432.2509','121212','','','','','','','','0000-00-00 00:00:00','2022-02-10 16:43:55',NULL,1),(11,'1644491432.2509','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 17:10:29',NULL,1),(12,'1644493239.2515','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 17:11:11',NULL,1),(13,'1644493298.2521','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 17:13:00',NULL,1),(14,'1644493390.2525','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 17:27:44',NULL,1),(15,'1644495171.2552','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 17:45:26',NULL,1),(16,'1644495336.2557','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 17:48:33',NULL,1),(17,'1644495523.2562','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 17:51:48',NULL,1),(18,'1644495825.2572','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 17:56:05',NULL,1),(19,'1644495977.2577','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 17:56:38',NULL,1),(20,'1644496008.2581','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 18:00:46',NULL,1),(21,'1644496255.2587','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 18:02:37',NULL,1),(22,'1644496458.2592','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 18:06:12',NULL,1),(23,'1644496580.2596','1236','','','','','','','','0000-00-00 00:00:00','2022-02-10 18:45:50',NULL,1),(24,'1644554252.2606','1236','ami anand','9730641180','9730641180','Thane','intrested','Successful_Contact','','0000-00-00 00:00:00','2022-02-11 10:13:06',NULL,1),(25,'1644554998.2612','1236','','','','','','','','0000-00-00 00:00:00','2022-02-11 10:24:49',NULL,1),(26,'1644555299.2621','1236','','','','','','','','0000-00-00 00:00:00','2022-02-11 10:32:20',NULL,1),(27,'1644555751.2627','1236','','','','','','','','0000-00-00 00:00:00','2022-02-11 10:37:39','2022-02-11 10:38:32',1),(28,'1644556080.2633','1235','amit anand','8369854211','8108954065','thane','active','','','1970-01-01 05:30:00','2022-02-11 10:39:18','2022-02-22 12:48:42',1),(29,'1644558910.2650','121212','','','','','','','','0000-00-00 00:00:00','2022-02-11 11:42:31',NULL,1),(30,'1644559597.2659','121212','','','','','','','','0000-00-00 00:00:00','2022-02-11 12:03:09',NULL,1),(31,'1644585853.2799','7007','','','','','','','','0000-00-00 00:00:00','2022-02-11 18:54:36',NULL,1),(32,'1644843811.12','1234','','','','','','','','0000-00-00 00:00:00','2022-02-14 18:59:57',NULL,1),(33,'1645005483.513','115950','','','','','','','','0000-00-00 00:00:00','2022-02-16 15:28:22',NULL,1),(34,'1645005595.517','115950','','','','','','','','0000-00-00 00:00:00','2022-02-16 15:30:07',NULL,1),(35,'1645005679.521','115950','','','','','','','','0000-00-00 00:00:00','2022-02-16 15:31:27',NULL,1),(36,'1645006158.528','115950','','','','','','','','0000-00-00 00:00:00','2022-02-16 15:39:35',NULL,1),(37,'1645006224.531','115950','','','','','','','','0000-00-00 00:00:00','2022-02-16 15:40:58',NULL,1),(38,'1645006298.536','115950','','','','','','','','0000-00-00 00:00:00','2022-02-16 15:45:25',NULL,1),(39,'1645006930.543','115950','','','','','','','','0000-00-00 00:00:00','2022-02-16 15:52:20',NULL,1),(40,'1645007000.0','115950','','','','','','','','0000-00-00 00:00:00','2022-02-16 15:59:36',NULL,1),(41,'1645008231.16','115950','','','','','','','','0000-00-00 00:00:00','2022-02-16 16:15:53',NULL,1),(42,'1645008231.16','115950','','','','','','','','0000-00-00 00:00:00','2022-02-16 16:15:54',NULL,1),(43,'1645008365.23','115950','','','','','','','','0000-00-00 00:00:00','2022-02-16 16:16:16',NULL,1),(44,'1645010181.41','115950','','','','','','','','0000-00-00 00:00:00','2022-02-16 16:46:35',NULL,1),(45,'1645424451.680','1234','Ravinder','8655440322','8655440322','thane','active','','','1970-01-01 05:30:00','2022-02-21 11:52:35','2022-02-21 12:24:48',1);
/*!40000 ALTER TABLE `vaani_costomer_info` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:55
