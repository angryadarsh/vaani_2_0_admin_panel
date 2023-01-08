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
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer` (
  `emp_id` int(11) NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (12,'201038','Akshata ','9324042501','9324042501','9819538699','akshatadevadiga2001@gmail.com','2022-04-01 17:10:01','','115503','','Connected','In Discussion','ok',NULL,'','','1648812600.1681'),(13,'114653','Amit','8369854211','','','amianand63@gmail.com','2022-04-13 15:05:23','','11225','','Connected','In Discussion','ok',NULL,'','','1649842482.1077'),(14,'115184','Ashish','8108954065','','','ashish.vishwakarma@edas.tech','2022-04-13 14:25:33','','11225','','Connected','In Discussion','ok',NULL,'','','1649839829.1073'),(15,'201038','Akshata ','2241817009','9324042501','9819538699','akshatadevadiga2001@gmail.com','2022-04-01 17:00:22','','115502','','Not connected','0','test ',NULL,'','','1648812601.1686'),(16,'115950','Ravinder','8655440322','7900051939','','r.k@edas.com','2022-04-12 18:00:34','','11225','','Connected','In Discussion','ok',NULL,'','','1649766562.1021'),(17,'114653','Amit','9819880419','','','amianand63@gmail.com','2022-04-01 16:59:55','','115502','','Not connected','0','Test ',NULL,'','','1648812530.1632'),(18,'201038','Akshata ','2241817900','7900051939','','akshatadevadiga@gmail.com','2022-03-29 11:36:46','','11665','','Not connected','In Discussion','ok',NULL,'','','1648533948.1356'),(19,'201038','Akshata ','','7900051939','','akshatadevadiga@gmail.com','2022-03-31 18:21:31','','115510','','Connected','In Discussion','test',NULL,'','','1648730921.965'),(20,'3003','krunal','8976784394','','','krunal.wankhede@edas.tech','2022-04-01 17:41:25','','115503','','Not connected','0','ok',NULL,'','','1648814968.1773'),(21,'105080','105080','9415592060','','','shyam.maurya@edas.tech','2022-04-01 17:42:44','','115504','','Connected','In Discussion','ok',NULL,'','','1648815128.1791'),(22,'12345','sss','7208051291','','','ss@ss.cc','2022-03-28 15:19:00','','150014','','Connected','In Discussion','test',NULL,'','','1648460877.710'),(23,'105080','105080','2261846860','','','shyamm@eosglobe.com','2022-04-01 17:00:09','','115504','','Connected','In Discussion','ok',NULL,'','','1648812550.1650'),(24,'114151','Dharmesh Mali','150011','','','dharmesh.mali@edas.tech','2022-03-28 15:17:26','','150020','','Not connected','0','test4',NULL,'','','1648460743.691'),(25,'150014','150014','150014','','','ashish.vishwakarma@edas.tech','2022-03-28 16:42:31','','150019','','Not connected','0','test call',NULL,'','','1648465751.1102'),(26,'114152','Dharmesh Mali','8802444546','','','dharmesh.mali@edas.tech','2022-04-01 17:42:25','','115503','','Connected','In Discussion','ok',NULL,'','','1648815117.1789'),(27,'12345','sss','9969199321','','','ss@ss.cc','2022-03-28 16:48:02','','150014','','Connected','In Discussion','test',NULL,'','','1648466046.1176'),(28,'3003','krunal','8433668697','','','krunal.wankhede@edas.tech','2022-03-28 15:13:42','','150013','','Disconnected','0','testing',NULL,'','','1648460558.647'),(29,'201038','Akshata ','7900051939','','','akshatadevadiga@gmail.com','2022-03-28 15:23:05','','150011','','Connected','In Discussion','test ',NULL,'','','1648461134.734'),(30,'11111','11111','undefined','','','test@1234.com','2022-03-28 15:40:15','','undefined','','Connected','In Discussion','test',NULL,'','','undefined'),(31,'114653','Amit','150020','','','amianand63@gmail.com','2022-03-28 15:53:53','','150011','','Connected','In Discussion','ok',NULL,'','','1648462804.945'),(32,'116692','A','11665','','','akshatadevadiga@gmail.com','2022-03-29 12:40:24','','11662','','Not connected','0','Test ',NULL,'','','1648536829.1424'),(33,'114653','Amit','11664','','','amianand63@gmail.com','2022-03-31 14:55:58','','11665','','Connected','In Discussion','ok',NULL,'','','1648718702.284'),(34,'3013','3013','9699804908','9696969696969','','asd@asd.com','2022-04-01 17:01:08','','115508','','Disconnected','0','3',NULL,'','','1648812541.1639'),(35,'115509','115509','115509','','','ashish.vishwakarma@edas.tech','2022-03-31 17:38:28','','115506','','Connected','In Discussion','test',NULL,'','','1648728148.478'),(36,'1234','Shrikant','9029447342','','','pratik.bhosale@edas.tech','2022-03-31 17:54:38','','115507','','Connected','In Discussion','xcxcxc',NULL,'','','1648729449.768'),(37,'114653','Amit','115505','','','amianand63@gmail.com','2022-04-01 16:10:23','','115506','','No Response','0','test1',NULL,'','','1648809573.1414'),(38,'11111','105080','9768176039','','','shyam.maurya@edas.tech','2022-03-31 18:16:13','','115509','','Not connected','0','test',NULL,'','','1648730699.948'),(39,'1234','Shrikant','115502','','','pratik.bhosale@edas.tech','2022-03-31 17:55:48','','115507','','Connected','In Discussion','xxcxc',NULL,'','','1648729482.775'),(40,'105080','LandLine','2261846832','','','jkashd@gmail.com','2022-03-31 18:25:14','','115510','','Not connected','0','test',NULL,'','','1648731193.972'),(41,'1234','Shrikant','8888888888','','','shrikantthombare1991@gmail.com','2022-03-31 18:03:27','','115507','','Disconnected','0','dfdf',NULL,'<p>Dear Shrikant,<br />\r\n<br />\r\nWe are trying to contact you, as you are not reporting to office. We would request you to contact us immediately on MOB no. 7208953344.<br />\r\n<br />\r\nThanks &amp; Regards,<br />\r\nEOS HR TEAM</p>\r\n','','1648729948.844'),(42,'1234','krunal','115510','','','krunal.wankhede@edas.tech','2022-03-31 18:24:46','','115504','','Not connected','0','ok',NULL,'','','1648731193.972'),(43,'1234','12312','9999999999','','','1231@fsdf.234','2022-04-01 11:31:31','','115504','','Connected','In Discussion','ok',NULL,'','','1648792745.1203'),(44,'1234','Shrikant','714555555','','','pratik.bhosale@edas.tech','2022-03-31 18:19:50','','115507','','Connected','In Discussion','xcxcxc',NULL,'','','1648730944.967'),(45,'1234','krunal','2261372900','9696969696969','','krunal.wankhede@edas.tech','2022-04-01 17:31:55','','115503','','Connected','In Discussion','ok',NULL,'','','1648813267.1748'),(46,'201038','Akshata ','115511','','','akshatadevadiga@gmail.com','2022-04-01 16:45:10','','115502','','Connected','In Discussion','Test ',NULL,'','','1648811617.1518'),(47,'115508','115508','115508','','','ashish.vishwakarma@edas.tech','2022-04-01 16:46:14','','115506','','Not connected','Not Interested','test',NULL,'','','1648811683.1525'),(48,'114653','Amit','2269191051','','','amianand63@gmail.com','2022-04-01 17:02:25','','115504','','Connected','In Discussion','ok',NULL,'','','1648812725.1721'),(49,'114653','ami','115504','','','amianand63@gmail.com','2022-04-08 11:50:36','','115504','','Connected','In Discussion','ok',NULL,'','',''),(50,'114653','ami','115506','','','amianand63@gmail.com','2022-04-08 16:27:28','','115504','','Connected','In Discussion','ok',NULL,'','','1649415369.459'),(51,'114653','ami','11225','','','amianand63@gmail.com','2022-04-11 18:24:36','','11223','','Connected','In Discussion','ok',NULL,'','','1649681599.893');
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

-- Dump completed on 2022-11-30 10:46:09
