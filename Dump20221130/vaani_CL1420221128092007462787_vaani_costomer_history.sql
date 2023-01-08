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
-- Table structure for table `vaani_costomer_history`
--

DROP TABLE IF EXISTS `vaani_costomer_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_costomer_history` (
  `id` bigint(50) NOT NULL,
  `unique_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_mobile` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_alt_mobile` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cust_status` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `disposition` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remark` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `callback_datetime` datetime NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `syn` tinyint(2) unsigned NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_costomer_history`
--

LOCK TABLES `vaani_costomer_history` WRITE;
/*!40000 ALTER TABLE `vaani_costomer_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `vaani_costomer_history` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:46
