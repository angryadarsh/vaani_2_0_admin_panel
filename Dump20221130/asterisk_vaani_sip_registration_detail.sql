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
-- Table structure for table `vaani_sip_registration_detail`
--

DROP TABLE IF EXISTS `vaani_sip_registration_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_sip_registration_detail` (
  `sip` int(10) DEFAULT NULL,
  `host` varchar(50) DEFAULT NULL,
  `port` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_sip_registration_detail`
--

LOCK TABLES `vaani_sip_registration_detail` WRITE;
/*!40000 ALTER TABLE `vaani_sip_registration_detail` DISABLE KEYS */;
INSERT INTO `vaani_sip_registration_detail` VALUES (103,'(Unspecified)','0','UNKNOWN'),(1000,'(Unspecified)','0','UNKNOWN'),(10001,'(Unspecified)','0','UNKNOWN'),(10002,'(Unspecified)','0','UNKNOWN'),(10003,'(Unspecified)','0','UNKNOWN'),(1003,'(Unspecified)','0','UNKNOWN'),(102001,'(Unspecified)','0','UNKNOWN'),(102003,'(Unspecified)','0','UNKNOWN'),(102004,'(Unspecified)','0','UNKNOWN'),(102005,'(Unspecified)','0','UNKNOWN'),(102030,'(Unspecified)','0','UNKNOWN'),(102031,'(Unspecified)','0','UNKNOWN'),(102043,'(Unspecified)','0','UNKNOWN'),(102045,'(Unspecified)','0','UNKNOWN'),(102051,'(Unspecified)','0','UNKNOWN'),(1050,'(Unspecified)','0','UNKNOWN'),(10508,'(Unspecified)','0','UNKNOWN'),(105080,'(Unspecified)','0','UNKNOWN'),(108303,'(Unspecified)','0','UNKNOWN'),(11001,'(Unspecified)','0','UNKNOWN'),(11004,'(Unspecified)','0','UNKNOWN'),(11005,'(Unspecified)','0','UNKNOWN'),(11006,'(Unspecified)','0','UNKNOWN'),(1105080,'(Unspecified)','0','UNKNOWN'),(1111,'(Unspecified)','0','UNKNOWN'),(11111,'(Unspecified)','0','UNKNOWN'),(1114151,'(Unspecified)','0','UNKNOWN'),(1114653,'(Unspecified)','0','UNKNOWN'),(1115950,'(Unspecified)','0','UNKNOWN'),(11220,'(Unspecified)','0','UNKNOWN'),(11221,'(Unspecified)','0','UNKNOWN'),(11222,'(Unspecified)','0','UNKNOWN'),(11223,'(Unspecified)','0','UNKNOWN'),(11223311,'(Unspecified)','0','UNKNOWN'),(11223322,'(Unspecified)','0','UNKNOWN'),(11223344,'(Unspecified)','0','UNKNOWN'),(11223355,'(Unspecified)','0','UNKNOWN'),(11223366,'(Unspecified)','0','UNKNOWN'),(11223377,'(Unspecified)','0','UNKNOWN'),(11225,'(Unspecified)','0','UNKNOWN'),(11226,'(Unspecified)','0','UNKNOWN'),(11330,'(Unspecified)','0','UNKNOWN'),(11331,'(Unspecified)','0','UNKNOWN'),(11332,'(Unspecified)','0','UNKNOWN'),(11333,'(Unspecified)','0','UNKNOWN'),(11334,'(Unspecified)','0','UNKNOWN'),(11335,'(Unspecified)','0','UNKNOWN'),(11336,'(Unspecified)','0','UNKNOWN'),(11337,'(Unspecified)','0','UNKNOWN'),(11338,'(Unspecified)','0','UNKNOWN'),(11339,'(Unspecified)','0','UNKNOWN'),(114153,'(Unspecified)','0','UNKNOWN'),(114653,'(Unspecified)','0','UNKNOWN'),(115500,'(Unspecified)','0','UNKNOWN'),(115502,'(Unspecified)','0','UNKNOWN'),(115503,'(Unspecified)','0','UNKNOWN'),(115504,'(Unspecified)','0','UNKNOWN'),(115505,'(Unspecified)','0','UNKNOWN'),(115506,'(Unspecified)','0','UNKNOWN'),(115507,'(Unspecified)','0','UNKNOWN'),(115508,'(Unspecified)','0','UNKNOWN'),(115509,'(Unspecified)','0','UNKNOWN'),(115510,'(Unspecified)','0','UNKNOWN'),(115511,'(Unspecified)','0','UNKNOWN'),(115950,'(Unspecified)','0','UNKNOWN'),(11661,'(Unspecified)','0','UNKNOWN'),(11662,'(Unspecified)','0','UNKNOWN'),(11663,'(Unspecified)','0','UNKNOWN'),(11664,'(Unspecified)','0','UNKNOWN'),(11665,'(Unspecified)','0','UNKNOWN'),(11666,'(Unspecified)','0','UNKNOWN'),(11667,'(Unspecified)','0','UNKNOWN'),(11668,'(Unspecified)','0','UNKNOWN'),(11669,'(Unspecified)','0','UNKNOWN'),(116693,'(Unspecified)','0','UNKNOWN'),(116694,'(Unspecified)','0','UNKNOWN'),(116695,'(Unspecified)','0','UNKNOWN'),(1213,'(Unspecified)','0','UNKNOWN'),(1214,'(Unspecified)','0','UNKNOWN'),(1215,'(Unspecified)','0','UNKNOWN'),(1221,'(Unspecified)','0','UNKNOWN'),(1234,'(Unspecified)','0','UNKNOWN'),(1235,'(Unspecified)','0','UNKNOWN'),(1346,'(Unspecified)','0','UNKNOWN'),(1347,'(Unspecified)','0','UNKNOWN'),(1348,'(Unspecified)','0','UNKNOWN'),(1349,'(Unspecified)','0','UNKNOWN'),(1350,'(Unspecified)','0','UNKNOWN'),(1351,'(Unspecified)','0','UNKNOWN'),(1905,'(Unspecified)','0','UNKNOWN'),(1990,'(Unspecified)','0','UNKNOWN'),(1995,'(Unspecified)','0','UNKNOWN'),(2000,'(Unspecified)','0','UNKNOWN'),(20011,'(Unspecified)','0','UNKNOWN'),(201037,'(Unspecified)','0','UNKNOWN'),(201038,'(Unspecified)','0','UNKNOWN'),(201039,'(Unspecified)','0','UNKNOWN'),(2020,'(Unspecified)','0','UNKNOWN'),(2022,'(Unspecified)','0','UNKNOWN'),(2022101,'(Unspecified)','0','UNKNOWN'),(2022102,'(Unspecified)','0','UNKNOWN'),(2022103,'(Unspecified)','0','UNKNOWN'),(2022104,'(Unspecified)','0','UNKNOWN'),(21013,'(Unspecified)','0','UNKNOWN'),(3001,'(Unspecified)','0','UNKNOWN'),(3002,'(Unspecified)','0','UNKNOWN'),(3003,'(Unspecified)','0','UNKNOWN'),(30033,'(Unspecified)','0','UNKNOWN'),(3007,'(Unspecified)','0','UNKNOWN'),(3013,'(Unspecified)','0','UNKNOWN'),(3020,'(Unspecified)','0','UNKNOWN'),(310391,'(Unspecified)','0','UNKNOWN'),(31390,'(Unspecified)','0','UNKNOWN'),(31392,'(Unspecified)','0','UNKNOWN'),(31393,'(Unspecified)','0','UNKNOWN'),(31394,'(Unspecified)','0','UNKNOWN'),(31395,'(Unspecified)','0','UNKNOWN'),(31396,'(Unspecified)','0','UNKNOWN'),(50001,'(Unspecified)','0','UNKNOWN'),(50003,'(Unspecified)','0','UNKNOWN'),(50022,'(Unspecified)','0','UNKNOWN'),(60001,'(Unspecified)','0','UNKNOWN'),(600010,'(Unspecified)','0','UNKNOWN'),(600011,'(Unspecified)','0','UNKNOWN'),(600012,'(Unspecified)','0','UNKNOWN'),(600013,'(Unspecified)','0','UNKNOWN'),(60002,'(Unspecified)','0','UNKNOWN'),(60003,'(Unspecified)','0','UNKNOWN'),(60007,'(Unspecified)','0','UNKNOWN'),(60008,'(Unspecified)','0','UNKNOWN'),(60009,'(Unspecified)','0','UNKNOWN'),(66748,'(Unspecified)','0','UNKNOWN'),(6748,'(Unspecified)','0','UNKNOWN'),(7100,'(Unspecified)','0','UNKNOWN'),(72000,'(Unspecified)','0','UNKNOWN'),(72341,'(Unspecified)','0','UNKNOWN'),(72342,'(Unspecified)','0','UNKNOWN'),(7648,'(Unspecified)','0','UNKNOWN'),(8002,'172.30.10.110','OK','(37'),(8662,'(Unspecified)','0','UNKNOWN'),(8663,'(Unspecified)','0','UNKNOWN'),(89287,'(Unspecified)','0','UNKNOWN'),(900101,'(Unspecified)','0','UNKNOWN'),(900102,'(Unspecified)','0','UNKNOWN'),(9111,'(Unspecified)','0','UNKNOWN'),(9112,'(Unspecified)','0','UNKNOWN'),(9113,'(Unspecified)','0','UNKNOWN'),(9114,'(Unspecified)','0','UNKNOWN'),(9661,'(Unspecified)','0','UNKNOWN'),(9662,'(Unspecified)','0','UNKNOWN'),(0,'(Unspecified)','0','UNKNOWN'),(0,'125.17.6.24','UNREACHABLE',''),(0,'(Unspecified)','0','UNKNOWN'),(0,'(Unspecified)','0','UNKNOWN');
/*!40000 ALTER TABLE `vaani_sip_registration_detail` ENABLE KEYS */;
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
