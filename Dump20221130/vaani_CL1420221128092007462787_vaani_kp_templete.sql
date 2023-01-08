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
-- Table structure for table `vaani_kp_templete`
--

DROP TABLE IF EXISTS `vaani_kp_templete`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_kp_templete` (
  `templete_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(100) DEFAULT NULL,
  `created_date` varchar(100) DEFAULT NULL,
  `modified_date` varchar(100) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  `created_ip` varchar(100) DEFAULT NULL,
  `modified_ip` varchar(100) DEFAULT NULL,
  `del_status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`templete_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_kp_templete`
--

LOCK TABLES `vaani_kp_templete` WRITE;
/*!40000 ALTER TABLE `vaani_kp_templete` DISABLE KEYS */;
INSERT INTO `vaani_kp_templete` VALUES (1,'HDFC','2022-11-29 10:30:11','2022-11-29 10:30:11','super admin','super admin','172.16.154.166',NULL,'1'),(2,'HDFC_loans','2022-11-29 11:38:13','2022-11-29 11:41:11','super admin','super admin','172.16.154.166','172.16.154.166','2');
/*!40000 ALTER TABLE `vaani_kp_templete` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:43
