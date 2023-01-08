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
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `history` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `vaani_lead_id` varchar(50) DEFAULT NULL,
  `emp_code` varchar(50) DEFAULT NULL,
  `emp_name` varchar(100) DEFAULT NULL,
  `contact_no_1` varchar(15) DEFAULT NULL,
  `contact_no_2` varchar(15) DEFAULT NULL,
  `contact_no_3` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `insertdate` datetime DEFAULT NULL,
  `agent_name` varchar(100) DEFAULT NULL,
  `agent_id` varchar(50) DEFAULT NULL,
  `callbackdatetime` varchar(50) DEFAULT NULL,
  `disposition` varchar(100) DEFAULT NULL,
  `sub_disposition` varchar(100) DEFAULT NULL,
  `remark` varchar(200) DEFAULT NULL,
  `mail_body` longtext CHARACTER SET latin1,
  `sms_response` longtext CHARACTER SET latin1,
  `unique_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`activity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history`
--

LOCK TABLES `history` WRITE;
/*!40000 ALTER TABLE `history` DISABLE KEYS */;
INSERT INTO `history` VALUES (1,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 12:33:29','','11005','','Connected','Not Interested','qewqeqqe','','','1669705348.98'),(2,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 12:34:18','','11005','','Connected','Not Interested','dasdsa','','','1669705429.102'),(3,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 13:04:23','','11005','','Connected','In Discussion','ffdfs','','','1669706521.106'),(4,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 14:48:13','','11005','','Connected','Not Interested','dsfsdf','','','1669713412.116'),(5,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 15:06:37','','11005','','Connected','Not Interested','asdasd','','','1669714340.120'),(6,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 15:10:12','','11005','','Connected','In Discussion','asdasd da dsd ','','','1669714623.124'),(7,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 15:17:06','','11005','','Connected','In Discussion','asdasd','','','1669715106.130'),(8,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 15:19:49','','11005','','Connected','Not Interested','dsfsf','','','1669715307.140'),(9,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 16:09:41','','11005','','Connected','In Discussion','dsad','','','1669718216.150'),(10,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 16:14:01','','11005','2022-11-29 16:15:18','Connected','Schedule next call','sdad','','','1669718596.172'),(11,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 16:16:06','','11005','','Connected','In Discussion','fadsd','','','1669718730.176'),(12,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 16:17:09','','11005','','redial','redial','','','','1669718798.180'),(13,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 16:18:09','','11005','','Connected','Not Interested','dasdsad','','','1669718842.184'),(14,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 16:52:38','','11005','','Connected','Not Interested','dsad','','','1669720897.188'),(15,'','125','Pradeep','9867170131','','','amit123@gmail.com','2022-11-29 17:27:45','','11005','','Connected','In Discussion','ok','','','1669723005.192'),(16,'','','','9970352640','','','','2022-11-29 17:57:28','','11004','','redial','redial','','','','1669724677.196'),(17,'','','','9970352640','','','','2022-11-29 17:57:38','','11004','','redial','redial','','','','1669724677.196'),(18,'','231','abc','9970352640','7987985768','','abc@gmail','2022-11-29 17:58:55','','11004','','Not connected','0','vdsv','','','1669724871.206'),(19,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 18:04:52','','11005','','Connected','In Discussion','sdsd','','','1669725121.210'),(20,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 18:29:28','','11005','','Connected','In Discussion','dsdas','','','1669726733.220'),(21,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 18:31:46','','11005','','Connected','Not Interested','dsad','','','1669726791.224'),(22,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 18:37:23','','11005','','Connected','Not Interested','dasda','','','1669727023.238'),(23,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 18:40:21','','11005','','Connected','In Discussion','fdsfds','','','1669727263.263'),(24,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 19:01:43','','11005','','Connected','In Discussion','asdasdsd','','','1669728396.294'),(25,'','231','abc','9970352640','7987985768','','abc@gmail','2022-11-29 19:03:03','','11004','','Connected','In Discussion','trfcx','','','1669728690.310'),(26,'','231','abc','9970352640','7987985768','','abc@gmail','2022-11-29 19:05:10','','11004','','No Response','0','vxdzef','','','1669728819.320');
/*!40000 ALTER TABLE `history` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:39
