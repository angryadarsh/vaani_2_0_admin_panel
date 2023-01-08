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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_agent_call_report`
--

LOCK TABLES `vaani_agent_call_report` WRITE;
/*!40000 ALTER TABLE `vaani_agent_call_report` DISABLE KEYS */;
INSERT INTO `vaani_agent_call_report` VALUES (1,'11005','1669705348.98','11005','hdfc_insure_146','09664334674','NI','29','23','23','','','','','0','22','2022-11-29 12:32:16','2022-11-29 12:33:30','hdfc_ergo_insure',1,'2022-11-29 12:32:16','2022-11-29 12:33:30',NULL,'CONNECTED'),(2,'11005','1669705429.102','11005','hdfc_insure_146','09664334674','NI','30','10','10','','','','','0','2','2022-11-29 12:33:38','2022-11-29 12:34:20','hdfc_ergo_insure',1,'2022-11-29 12:33:38','2022-11-29 12:34:20',NULL,'CONNECTED'),(3,'11005','1669706521.106','11005','hdfc_insure_146','09664334674','IND','37','297','297','','','69','','0','421','2022-11-29 12:51:50','2022-11-29 13:04:25','hdfc_ergo_insure',1,'2022-11-29 12:51:50','2022-11-29 13:04:25',NULL,'CONNECTED'),(4,'11005','1669713412.116','11005','hdfc_insure_146','09664334674','NI','29','57','57','','','','','0','8','2022-11-29 14:46:41','2022-11-29 14:48:15','hdfc_ergo_insure',1,'2022-11-29 14:46:41','2022-11-29 14:48:15',NULL,'CONNECTED'),(5,'11005','1669714340.120','11005','hdfc_insure_146','09664334674','NI','28','50','50','','','','','0','193','2022-11-29 15:02:09','2022-11-29 15:06:40','hdfc_ergo_insure',1,'2022-11-29 15:02:09','2022-11-29 15:06:40',NULL,'CONNECTED'),(6,'11005','1669714623.124','11005','hdfc_insure_146','09664334674','IND','29','114','114','','','','','0','59','2022-11-29 15:06:52','2022-11-29 15:10:14','hdfc_ergo_insure',1,'2022-11-29 15:06:52','2022-11-29 15:10:14',NULL,'CONNECTED'),(7,'11005','1669715106.130','11005','hdfc_insure_146','09664334674','IND','29','96','96','','','39','','0','9','2022-11-29 15:14:55','2022-11-29 15:17:09','hdfc_ergo_insure',1,'2022-11-29 15:14:55','2022-11-29 15:17:09',NULL,'CONNECTED'),(8,'11005','1669715307.140','11005','hdfc_insure_146','09664334674','NI','28','60','60','','','','24','0','7','2022-11-29 15:18:16','2022-11-29 15:19:51','hdfc_ergo_insure',1,'2022-11-29 15:18:16','2022-11-29 15:19:51',NULL,'CONNECTED'),(9,'11005','1669718216.150','11005','hdfc_insure_146','09664334674','IND','29','139','139','','','20','','0','11','2022-11-29 16:06:45','2022-11-29 16:09:44','hdfc_ergo_insure',1,'2022-11-29 16:06:45','2022-11-29 16:09:44',NULL,'CONNECTED'),(10,'11005','1669718596.172','11005','hdfc_insure_146','09664334674','SNC','28','7','7','','','','','0','24','2022-11-29 16:13:05','2022-11-29 16:14:04','hdfc_ergo_insure',1,'2022-11-29 16:13:05','2022-11-29 16:14:04',NULL,'CONNECTED'),(11,'11005','1669718730.176','11005','hdfc_insure_146','09664334674','IND','29','11','11','','','','','0','10','2022-11-29 16:15:19','2022-11-29 16:16:09','hdfc_ergo_insure',7,'2022-11-29 16:15:19','2022-11-29 16:16:09',NULL,'CONNECTED'),(12,'11005','1669718842.184','11005','hdfc_insure_146','09664334674','NI','29','18','18','','','','','0','14','2022-11-29 16:17:11','2022-11-29 16:18:12','hdfc_ergo_insure',8,'2022-11-29 16:17:11','2022-11-29 16:18:12',NULL,'CONNECTED'),(13,'11005','1669720897.188','11005','hdfc_insure_146','09664334674','NI','31','35','35','','','','','0','9','2022-11-29 16:51:26','2022-11-29 16:59:42','hdfc_ergo_insure',1,'2022-11-29 16:51:37','2022-11-29 16:52:41','/var/www/html/recordings/hdfc_ergo_insure/hdf','ANSWERED'),(14,'11005','1669723005.192','11005','hdfc_insure_146','09867170131','IND','39','12','12','','','','','0','21','2022-11-29 17:26:34','2022-11-29 17:27:47','hdfc_ergo_insure',1,'2022-11-29 17:26:45','2022-11-29 17:27:46','/var/www/html/recordings/hdfc_ergo_insure/hdf','ANSWERED'),(15,'11004','1669724871.206','11004','hdfc_insure_146','09970352640','NC','31','16','16','','','','','0','30','2022-11-29 17:57:40','2022-11-29 17:58:57','hdfc_ergo_insure',8,'2022-11-29 17:57:51','2022-11-29 17:58:57','/var/www/html/recordings/hdfc_ergo_insure/hdf','ANSWERED'),(16,'11005','1669725121.210','11005','hdfc_insure_146','09664334674','IND','29','146','146','','','128','','0','10','2022-11-29 18:01:50','2022-11-29 18:04:55','hdfc_ergo_insure',1,'2022-11-29 18:01:50','2022-11-29 18:04:55',NULL,'CONNECTED'),(17,'11005','1669726733.220','11005','hdfc_insure_146','09664334674','IND','36','5','5','','','','','0','7','2022-11-29 18:28:42','2022-11-29 18:29:30','hdfc_ergo_insure',1,'2022-11-29 18:28:53','2022-11-29 18:29:30','/var/www/html/recordings/hdfc_ergo_insure/hdf','ANSWERED'),(18,'11005','1669726791.224','11005','hdfc_insure_146','09664334674','NI','26','62','62','','','23','','0','40','2022-11-29 18:29:40','2022-11-29 18:31:48','hdfc_ergo_insure',1,'2022-11-29 18:29:40','2022-11-29 18:31:48',NULL,'CONNECTED'),(19,'11005','1669726932.234','11005','hdfc_insure_146','09664334674','system_logout','27','15','15','','','','','0','15','2022-11-29 18:32:01','2022-11-29 18:32:44','hdfc_ergo_insure',1,'2022-11-29 18:32:12',NULL,'/var/www/html/recordings/hdfc_ergo_insure/hdf','ANSWERED'),(20,'11005','1669727023.238','11005','hdfc_insure_146','09664334674','NI','29','187','187','','','22','37','0','16','2022-11-29 18:33:32','2022-11-29 18:37:24','hdfc_ergo_insure',1,'2022-11-29 18:33:32','2022-11-29 18:37:24',NULL,'CONNECTED'),(21,'11005','1669727263.263','11005','hdfc_insure_146','09664334674','IND','28','141','133','8','','28','7','0','1','2022-11-29 18:37:32','2022-11-29 18:40:22','hdfc_ergo_insure',1,'2022-11-29 18:37:32','2022-11-29 18:40:22',NULL,'CONNECTED'),(22,'11005','1669728396.294','11005','hdfc_insure_146','09664334674','IND','30','198','198','','','','101','0','92','2022-11-29 18:56:25','2022-11-29 19:01:45','hdfc_ergo_insure',1,'2022-11-29 18:56:36','2022-11-29 19:01:45','/var/www/html/recordings/hdfc_ergo_insure/hdf','ANSWERED'),(23,'11004','1669728690.310','11004','hdfc_insure_146','09970352640','IND','30','75','75','','','','43','0','2','2022-11-29 19:01:19','2022-11-29 19:03:06','hdfc_ergo_insure',1,'2022-11-29 19:01:30','2022-11-29 19:03:06','/var/www/html/recordings/hdfc_ergo_insure/hdf','ANSWERED'),(24,'11004','1669728819.320','11004','hdfc_insure_146','09970352640','NR','34','68','68','','','','25','0','2','2022-11-29 19:03:28','2022-11-29 19:05:12','hdfc_ergo_insure',1,'2022-11-29 19:03:39','2022-11-29 19:05:12','/var/www/html/recordings/hdfc_ergo_insure/hdf','ANSWERED'),(25,'11004','1669728950.330','11004','hdfc_insure_146','09970352640','','34','25','25','','','','','0','','2022-11-29 19:05:39','2022-11-29 19:06:39','hdfc_ergo_insure',1,'2022-11-29 19:05:50',NULL,'/var/www/html/recordings/hdfc_ergo_insure/hdf','ANSWERED');
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

-- Dump completed on 2022-11-30 10:45:25
