-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 172.30.10.102    Database: dynamic_asterisk
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
-- Table structure for table `vaani_lead_basket`
--

DROP TABLE IF EXISTS `vaani_lead_basket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_lead_basket` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `batch_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lead_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `campaign_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact1` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact2` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact3` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact4` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c1_count` int(11) DEFAULT NULL,
  `c2_count` int(11) DEFAULT NULL,
  `c3_count` int(11) DEFAULT NULL,
  `c4_count` int(11) DEFAULT NULL,
  `lead_owner` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modify_by` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `del_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-not deleted, 2-deleted',
  `filter_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_lead_basket`
--

LOCK TABLES `vaani_lead_basket` WRITE;
/*!40000 ALTER TABLE `vaani_lead_basket` DISABLE KEYS */;
/*!40000 ALTER TABLE `vaani_lead_basket` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:44:38
