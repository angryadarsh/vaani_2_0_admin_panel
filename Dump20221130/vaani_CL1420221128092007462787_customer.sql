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
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer` (
  `emp_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `upload_flag` varchar(10) DEFAULT NULL,
  `mail_body` longtext CHARACTER SET latin1,
  `sms_response` longtext CHARACTER SET latin1,
  `unique_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`emp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (1,'','123','Pradeep','9664334674','8369854211','7777777777','amit123@gmail.com','2022-11-29 12:33:29','','11005','','Connected','In Discussion','asdasdsd',NULL,'','','1669728396.294'),(2,'','125','Pradeep','9867170131','','','amit123@gmail.com','2022-11-29 17:27:45','','11005','','Connected','In Discussion','ok',NULL,'','','1669723005.192'),(3,'','231','abc','9970352640','7987985768','','abc@gmail','2022-11-29 17:57:28','','11004','','No Response','0','vxdzef',NULL,'','','1669728819.320');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:36
