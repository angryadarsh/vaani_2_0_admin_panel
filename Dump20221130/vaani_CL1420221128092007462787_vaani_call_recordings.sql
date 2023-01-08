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
-- Table structure for table `vaani_call_recordings`
--

DROP TABLE IF EXISTS `vaani_call_recordings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_call_recordings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(45) DEFAULT NULL,
  `start_time` varchar(45) DEFAULT NULL,
  `duration` varchar(45) DEFAULT NULL,
  `agent_id` varchar(45) DEFAULT NULL,
  `customer_number` varchar(45) DEFAULT NULL,
  `campaign` varchar(50) DEFAULT NULL,
  `queue` varchar(50) DEFAULT NULL,
  `recording_path` varchar(180) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `end_time` varchar(45) NOT NULL,
  `disposition` varchar(100) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL COMMENT '1 - Not Audited, 2 - Audited, 3 - Dispute Initiated, 10 - Closed',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_call_recordings`
--

LOCK TABLES `vaani_call_recordings` WRITE;
/*!40000 ALTER TABLE `vaani_call_recordings` DISABLE KEYS */;
INSERT INTO `vaani_call_recordings` VALUES (1,'1669633543.58','2022-11-28 16:35:43','00:00:20','','9867170131','hdfc_ergo_insure','hdfc_insure_146',' /var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/28/edas-9867170131-1669633543.58',2,'2022-11-28 16:36:03',NULL,NULL),(2,'1669633596.61','2022-11-28 16:36:36','00:00:17','','8369854211','hdfc_ergo_insure','hdfc_insure_146',' /var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/28/edas-8369854211-1669633596.61',2,'2022-11-28 16:36:53',NULL,NULL),(3,'1669638253.64','2022-11-28 17:54:13','00:00:03','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/28/edas-11005-09664334674-1669638253.64',1,'2022-11-28 17:54:16',NULL,NULL),(4,'1669639429.66','2022-11-28 18:13:49','00:00:01','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/28/edas-11005-09664334674-1669639429.66',1,'2022-11-28 18:13:50',NULL,NULL),(5,'1669639677.68','2022-11-28 18:17:57','00:00:24','11005','08369854211','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/28/edas-11005-08369854211-1669639677.68',1,'2022-11-28 18:18:21',NULL,NULL),(6,'1669640327.72','2022-11-28 18:28:47','00:00:22','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/28/edas-11005-09664334674-1669640327.72',1,'2022-11-28 18:29:09',NULL,NULL),(7,'1669700027.76','2022-11-29 11:03:47','00:02:05','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669700027.76',1,'2022-11-29 11:05:52',NULL,NULL),(8,'1669703831.82','2022-11-29 12:07:11','00:01:04','','8059596523','hdfc_ergo_insure','hdfc_insure_146',' /var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-8059596523-1669703831.82',2,'2022-11-29 12:08:15',NULL,NULL),(9,'1669703916.84','2022-11-29 12:08:36','00:01:04','','8059596523','hdfc_ergo_insure','hdfc_insure_146',' /var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-8059596523-1669703916.84',2,'2022-11-29 12:09:40',NULL,NULL),(10,'1669703997.86','2022-11-29 12:09:57','00:00:46','','8059596523','hdfc_ergo_insure','hdfc_insure_146',' /var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-8059596523-1669703997.86',2,'2022-11-29 12:10:43',NULL,NULL),(11,'1669704075.88','2022-11-29 12:11:15','00:01:02','','8059596523','hdfc_ergo_insure','hdfc_insure_146',' /var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-8059596523-1669704075.88',2,'2022-11-29 12:12:17',NULL,NULL),(12,'1669705348.98','2022-11-29 12:32:28','00:00:40','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669705348.98',1,'2022-11-29 12:33:08',NULL,NULL),(13,'1669705429.102','2022-11-29 12:33:49','00:00:30','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669705429.102',1,'2022-11-29 12:34:19',NULL,NULL),(14,'1669713412.116','2022-11-29 14:46:52','00:01:15','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669713412.116',1,'2022-11-29 14:48:07',NULL,NULL),(15,'1669714340.120','2022-11-29 15:02:20','00:01:06','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669714340.120',1,'2022-11-29 15:03:26',NULL,NULL),(16,'1669714623.124','2022-11-29 15:07:03','00:02:12','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669714623.124',1,'2022-11-29 15:09:15',NULL,NULL),(17,'1669715307.140','2022-11-29 15:18:27','00:01:16','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669715307.140',1,'2022-11-29 15:19:43',NULL,NULL),(18,'1669718596.172','2022-11-29 16:13:16','00:00:24','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669718596.172',1,'2022-11-29 16:13:40',NULL,NULL),(19,'1669718730.176','2022-11-29 16:15:30','00:00:28','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669718730.176',1,'2022-11-29 16:15:58',NULL,NULL),(20,'1669718798.180','2022-11-29 16:16:38','00:00:25','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669718798.180',1,'2022-11-29 16:17:03',NULL,NULL),(21,'1669718842.184','2022-11-29 16:17:22','00:00:35','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669718842.184',1,'2022-11-29 16:17:57',NULL,NULL),(22,'1669720897.188','2022-11-29 16:51:37','00:00:55','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669720897.188',1,'2022-11-29 16:52:32',NULL,NULL),(23,'1669723005.192','2022-11-29 17:26:45','00:00:39','11005','09867170131','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09867170131-1669723005.192',1,'2022-11-29 17:27:24',NULL,NULL),(24,'1669724871.206','2022-11-29 17:57:51','00:00:35','11004','09970352640','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11004-09970352640-1669724871.206',1,'2022-11-29 17:58:26',NULL,NULL),(25,'1669726733.220','2022-11-29 18:28:53','00:00:29','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669726733.220',1,'2022-11-29 18:29:22',NULL,NULL),(26,'1669726932.234','2022-11-29 18:32:12','00:00:32','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669726932.234',1,'2022-11-29 18:32:44',NULL,NULL),(27,'1669728396.294','2022-11-29 18:56:36','00:03:37','11005','09664334674','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11005-09664334674-1669728396.294',1,'2022-11-29 19:00:13',NULL,NULL),(28,'1669728690.310','2022-11-29 19:01:30','00:01:36','11004','09970352640','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11004-09970352640-1669728690.310',1,'2022-11-29 19:03:06',NULL,NULL),(29,'1669728819.320','2022-11-29 19:03:39','00:01:32','11004','09970352640','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11004-09970352640-1669728819.320',1,'2022-11-29 19:05:11',NULL,NULL),(30,'1669728950.330','2022-11-29 19:05:50','00:00:48','11004','09970352640','hdfc_ergo_insure','hdfc_insure_146','/var/www/html/recordings/hdfc_ergo_insure/hdfc_insure_146/2022/11/29/edas-11004-09970352640-1669728950.330',1,'2022-11-29 19:06:38',NULL,NULL);
/*!40000 ALTER TABLE `vaani_call_recordings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:40
