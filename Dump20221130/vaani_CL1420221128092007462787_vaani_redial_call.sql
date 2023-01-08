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
-- Table structure for table `vaani_redial_call`
--

DROP TABLE IF EXISTS `vaani_redial_call`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_redial_call` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lead_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unique_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `agent_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `redial_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `number` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(2) NOT NULL COMMENT '1-not dial, 2-redial',
  `created_datetime` datetime NOT NULL,
  `modified_datetime` datetime DEFAULT NULL,
  `del_status` tinyint(2) NOT NULL COMMENT '1-not deleted, 2-deleted',
  `syn` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_redial_call`
--

LOCK TABLES `vaani_redial_call` WRITE;
/*!40000 ALTER TABLE `vaani_redial_call` DISABLE KEYS */;
INSERT INTO `vaani_redial_call` VALUES (1,'','1669718798.180','11005','1669718798.180',NULL,'9664334674',2,'2022-11-29 16:17:09','2022-11-29 16:17:11',1,1),(2,'','1669724677.196','11004','1669724677.196',NULL,'9970352640',2,'2022-11-29 17:57:38','2022-11-29 17:57:40',1,1);
/*!40000 ALTER TABLE `vaani_redial_call` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:41
