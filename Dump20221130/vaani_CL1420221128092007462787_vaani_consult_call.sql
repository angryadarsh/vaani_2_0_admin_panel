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
-- Table structure for table `vaani_consult_call`
--

DROP TABLE IF EXISTS `vaani_consult_call`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_consult_call` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `agent_id` varchar(50) NOT NULL,
  `unique_id` varchar(50) NOT NULL,
  `consult_no` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL COMMENT 'ext- external, in- internal ',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `del_status` tinyint(3) NOT NULL COMMENT '1:not deleted, 2:deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_consult_call`
--

LOCK TABLES `vaani_consult_call` WRITE;
/*!40000 ALTER TABLE `vaani_consult_call` DISABLE KEYS */;
INSERT INTO `vaani_consult_call` VALUES (1,'11005','1669715307.140','9867170131','ext','2022-11-29 15:19:17','0000-00-00 00:00:00',1),(2,'11005','1669727023.238','9867170131','ext','2022-11-29 18:34:09','0000-00-00 00:00:00',1),(3,'11005','1669727023.238','9867170131','ext','2022-11-29 18:35:53','0000-00-00 00:00:00',1),(4,'11005','1669727263.263','9867170131','ext','2022-11-29 18:39:05','0000-00-00 00:00:00',1),(5,'11005','1669728396.294','9867170131','ext','2022-11-29 18:57:28','0000-00-00 00:00:00',1),(6,'11005','1669728396.294','9867170131','ext','2022-11-29 18:59:26','0000-00-00 00:00:00',1),(7,'11004','1669728690.310','7718915236','ext','2022-11-29 19:02:21','0000-00-00 00:00:00',1),(8,'11004','1669728819.320','7718915236','ext','2022-11-29 19:04:40','0000-00-00 00:00:00',1);
/*!40000 ALTER TABLE `vaani_consult_call` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:19
