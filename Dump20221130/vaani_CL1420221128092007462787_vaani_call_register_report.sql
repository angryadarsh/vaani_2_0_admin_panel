-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 172.30.10.102    Database: vaani_CL1420221128092007462787
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
-- Table structure for table `vaani_call_register_report`
--

DROP TABLE IF EXISTS `vaani_call_register_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_call_register_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent_id` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent_name` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campaign` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `queue` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interval` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_date` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_time` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cli` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_type` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_status` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disposition` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hold_duration` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ring_duration` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `talk_duration` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wrap_duration` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recording_path` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inserted_at` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores calculated call register report values from cron';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_call_register_report`
--

LOCK TABLES `vaani_call_register_report` WRITE;
/*!40000 ALTER TABLE `vaani_call_register_report` DISABLE KEYS */;
INSERT INTO `vaani_call_register_report` VALUES (1,'1669705348.98','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','12:30-12:45','2022-11-29 12:30:02','2022-11-29 12:45:02','2022-11-29 12:32:16','2022-11-29 12:33:30','09664334674','1','CONNECTED',NULL,'00:01:14','00:00:00','00:00:29','00:00:23','00:00:22',NULL,'2022-11-29 12:45:02'),(2,'1669705429.102','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','12:30-12:45','2022-11-29 12:30:02','2022-11-29 12:45:02','2022-11-29 12:33:38','2022-11-29 12:34:20','09664334674','1','CONNECTED',NULL,'00:00:42','00:00:00','00:00:30','00:00:10','00:00:02',NULL,'2022-11-29 12:45:02'),(4,'1669706521.106','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','13:00-13:15','2022-11-29 13:00:01','2022-11-29 13:15:01','2022-11-29 12:51:50','2022-11-29 13:04:25','09664334674','1','CONNECTED',NULL,'00:12:35','00:00:00','00:00:37','00:04:57','00:07:01',NULL,'2022-11-29 13:15:01'),(5,'1669713412.116','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','14:45-15:00','2022-11-29 14:45:01','2022-11-29 15:00:01','2022-11-29 14:46:41','2022-11-29 14:48:15','09664334674','1','CONNECTED',NULL,'00:01:34','00:00:00','00:00:29','00:00:57','00:00:08',NULL,'2022-11-29 15:00:01'),(6,'1669714340.120','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','15:00-15:15','2022-11-29 15:00:02','2022-11-29 15:15:02','2022-11-29 15:02:09','2022-11-29 15:06:40','09664334674','1','CONNECTED',NULL,'00:04:31','00:00:00','00:00:28','00:00:50','00:03:13',NULL,'2022-11-29 15:15:02'),(7,'1669714623.124','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','15:00-15:15','2022-11-29 15:00:02','2022-11-29 15:15:02','2022-11-29 15:06:52','2022-11-29 15:10:14','09664334674','1','CONNECTED',NULL,'00:03:22','00:00:00','00:00:29','00:01:54','00:00:59',NULL,'2022-11-29 15:15:02'),(9,'1669715106.130','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','15:15-15:30','2022-11-29 15:15:01','2022-11-29 15:30:01','2022-11-29 15:14:55','2022-11-29 15:17:09','09664334674','1','CONNECTED',NULL,'00:02:14','00:00:00','00:00:29','00:01:36','00:00:09',NULL,'2022-11-29 15:30:01'),(10,'1669715307.140','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','15:15-15:30','2022-11-29 15:15:01','2022-11-29 15:30:01','2022-11-29 15:18:16','2022-11-29 15:19:51','09664334674','1','CONNECTED',NULL,'00:01:35','00:00:00','00:00:28','00:01:00','00:00:07',NULL,'2022-11-29 15:30:01'),(12,'1669718216.150','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','16:00-16:15','2022-11-29 16:00:02','2022-11-29 16:15:02','2022-11-29 16:06:45','2022-11-29 16:09:44','09664334674','1','CONNECTED',NULL,'00:02:59','00:00:00','00:00:29','00:02:19','00:00:11',NULL,'2022-11-29 16:15:02'),(13,'1669718596.172','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','16:00-16:15','2022-11-29 16:00:02','2022-11-29 16:15:02','2022-11-29 16:13:05','2022-11-29 16:14:04','09664334674','1','CONNECTED',NULL,'00:00:59','00:00:00','00:00:28','00:00:07','00:00:24',NULL,'2022-11-29 16:15:02'),(15,'1669718730.176','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','16:15-16:30','2022-11-29 16:15:02','2022-11-29 16:30:02','2022-11-29 16:15:19','2022-11-29 16:16:09','09664334674','7','CONNECTED',NULL,'00:00:50','00:00:00','00:00:29','00:00:11','00:00:10',NULL,'2022-11-29 16:30:02'),(16,'1669718842.184','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','16:15-16:30','2022-11-29 16:15:02','2022-11-29 16:30:02','2022-11-29 16:17:11','2022-11-29 16:18:12','09664334674','8','CONNECTED',NULL,'00:01:01','00:00:00','00:00:29','00:00:18','00:00:14',NULL,'2022-11-29 16:30:02'),(18,'1669720897.188','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','16:45-17:00','2022-11-29 16:45:02','2022-11-29 17:00:02','2022-11-29 16:51:37','2022-11-29 16:52:41','09664334674','1','ANSWERED',NULL,'00:01:15','00:00:00','00:00:31','00:00:35','00:00:09','/var/www/html/recordings/hdfc_ergo_insure/hdf','2022-11-29 17:00:02'),(19,'1669723005.192','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','17:15-17:30','2022-11-29 17:15:01','2022-11-29 17:30:01','2022-11-29 17:26:45','2022-11-29 17:27:46','09867170131','1','ANSWERED',NULL,'00:01:12','00:00:00','00:00:39','00:00:12','00:00:21','/var/www/html/recordings/hdfc_ergo_insure/hdf','2022-11-29 17:30:01'),(20,'1669724871.206','11004','11004','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','17:45-18:00','2022-11-29 17:45:02','2022-11-29 18:00:02','2022-11-29 17:57:51','2022-11-29 17:58:57','09970352640','8','ANSWERED',NULL,'00:01:17','00:00:00','00:00:31','00:00:16','00:00:30','/var/www/html/recordings/hdfc_ergo_insure/hdf','2022-11-29 18:00:02'),(21,'1669725121.210','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','18:00-18:15','2022-11-29 18:00:01','2022-11-29 18:15:01','2022-11-29 18:01:50','2022-11-29 18:04:55','09664334674','1','CONNECTED',NULL,'00:03:05','00:00:00','00:00:29','00:02:26','00:00:10',NULL,'2022-11-29 18:15:02'),(22,'1669726733.220','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','18:15-18:30','2022-11-29 18:15:02','2022-11-29 18:30:02','2022-11-29 18:28:53','2022-11-29 18:29:30','09664334674','1','ANSWERED',NULL,'00:00:48','00:00:00','00:00:36','00:00:05','00:00:07','/var/www/html/recordings/hdfc_ergo_insure/hdf','2022-11-29 18:30:02'),(23,'1669726791.224','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','18:30-18:45','2022-11-29 18:30:01','2022-11-29 18:45:01','2022-11-29 18:29:40','2022-11-29 18:31:48','09664334674','1','CONNECTED',NULL,'00:02:08','00:00:00','00:00:26','00:01:02','00:00:40',NULL,'2022-11-29 18:45:01'),(24,'1669727023.238','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','18:30-18:45','2022-11-29 18:30:01','2022-11-29 18:45:01','2022-11-29 18:33:32','2022-11-29 18:37:24','09664334674','1','CONNECTED',NULL,'00:03:52','00:00:00','00:00:29','00:03:07','00:00:16',NULL,'2022-11-29 18:45:01'),(25,'1669727263.263','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','18:30-18:45','2022-11-29 18:30:01','2022-11-29 18:45:01','2022-11-29 18:37:32','2022-11-29 18:40:22','09664334674','1','CONNECTED',NULL,'00:02:50','00:00:08','00:00:28','00:02:13','00:00:01',NULL,'2022-11-29 18:45:01'),(26,'1669728396.294','11005','11005','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','19:00-19:15','2022-11-29 19:00:02','2022-11-29 19:15:02','2022-11-29 18:56:36','2022-11-29 19:01:45','09664334674','1','ANSWERED',NULL,'00:05:20','00:00:00','00:00:30','00:03:18','00:01:32','/var/www/html/recordings/hdfc_ergo_insure/hdf','2022-11-29 19:15:02'),(27,'1669728690.310','11004','11004','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','19:00-19:15','2022-11-29 19:00:02','2022-11-29 19:15:02','2022-11-29 19:01:30','2022-11-29 19:03:06','09970352640','1','ANSWERED',NULL,'00:01:47','00:00:00','00:00:30','00:01:15','00:00:02','/var/www/html/recordings/hdfc_ergo_insure/hdf','2022-11-29 19:15:02'),(28,'1669728819.320','11004','11004','hdfc_ergo_insure','hdfc_insure_146','2022-11-29','19:00-19:15','2022-11-29 19:00:02','2022-11-29 19:15:02','2022-11-29 19:03:39','2022-11-29 19:05:12','09970352640','1','ANSWERED',NULL,'00:01:44','00:00:00','00:00:34','00:01:08','00:00:02','/var/www/html/recordings/hdfc_ergo_insure/hdf','2022-11-29 19:15:02');
/*!40000 ALTER TABLE `vaani_call_register_report` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:30
