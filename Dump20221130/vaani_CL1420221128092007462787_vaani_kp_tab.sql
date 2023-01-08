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
-- Table structure for table `vaani_kp_tab`
--

DROP TABLE IF EXISTS `vaani_kp_tab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_kp_tab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `templete_id` int(11) DEFAULT NULL,
  `tab_name` varchar(100) DEFAULT NULL,
  `file` varchar(100) DEFAULT NULL,
  `mandatory_info` varchar(100) DEFAULT NULL,
  `additional_info` varchar(100) DEFAULT NULL,
  `created_date` varchar(100) DEFAULT NULL,
  `modified_date` varchar(100) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  `created_ip` varchar(100) DEFAULT NULL,
  `modified_ip` varchar(100) DEFAULT NULL,
  `del_status` varchar(100) DEFAULT NULL,
  `sequence` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_kp_tab`
--

LOCK TABLES `vaani_kp_tab` WRITE;
/*!40000 ALTER TABLE `vaani_kp_tab` DISABLE KEYS */;
INSERT INTO `vaani_kp_tab` VALUES (1,1,'HDFC_ins','tasksheet.xlsx','HDFC_ins','HDFC_ins','2022-11-29 10:30:56','2022-11-29 10:30:56','super admin','super admin','172.16.154.166',NULL,'1','2'),(2,1,'HDFC_loans','sample.pdf','HDFC_loans','HDFC_loans','2022-11-29 11:39:00','2022-11-29 11:39:00','super admin','super admin','172.16.154.166',NULL,'1','1'),(3,1,'HDFC','tasksheet1.xlsx','HDFC','HDFC','2022-11-29 17:42:48','2022-11-29 17:42:48','super admin','super admin','172.16.154.166',NULL,'1','3');
/*!40000 ALTER TABLE `vaani_kp_tab` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:33
