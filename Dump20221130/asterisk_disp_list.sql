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
-- Table structure for table `disp_list`
--

DROP TABLE IF EXISTS `disp_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disp_list` (
  `dispid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `disposition` varchar(200) DEFAULT NULL,
  `subdisposition` varchar(200) DEFAULT NULL,
  `shorcode` varchar(45) DEFAULT NULL,
  `insertdate` datetime DEFAULT NULL,
  PRIMARY KEY (`dispid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disp_list`
--

LOCK TABLES `disp_list` WRITE;
/*!40000 ALTER TABLE `disp_list` DISABLE KEYS */;
INSERT INTO `disp_list` VALUES (1,'Connected','In Discussion','IND','2021-08-16 15:36:21'),(2,'Connected','Not Interested','NI','2021-08-16 15:36:21'),(3,'Connected','NCNS','NCNS','2021-08-16 15:36:21'),(4,'Connected','Schedule next call','SNC','2021-08-16 15:36:21'),(5,'Not connected','','NC','2021-08-16 15:36:21'),(6,'Disconnected','','DICN','2021-08-16 15:36:21'),(7,'No Response','','NR','2021-08-16 15:36:21');
/*!40000 ALTER TABLE `disp_list` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:46:04
