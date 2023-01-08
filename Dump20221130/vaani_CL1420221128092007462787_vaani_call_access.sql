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
-- Table structure for table `vaani_call_access`
--

DROP TABLE IF EXISTS `vaani_call_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_call_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` varchar(255) DEFAULT NULL,
  `queue_id` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `is_conference` int(11) DEFAULT NULL,
  `is_transfer` int(11) DEFAULT NULL,
  `is_consult` int(11) DEFAULT NULL,
  `is_manual` int(11) DEFAULT NULL,
  `created_date` varchar(255) DEFAULT NULL,
  `modified_date` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `modified_by` varchar(255) DEFAULT NULL,
  `created_ip` varchar(255) DEFAULT NULL,
  `modified_ip` varchar(255) DEFAULT NULL,
  `del_status` varchar(255) DEFAULT NULL,
  `syn` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_call_access`
--

LOCK TABLES `vaani_call_access` WRITE;
/*!40000 ALTER TABLE `vaani_call_access` DISABLE KEYS */;
INSERT INTO `vaani_call_access` VALUES (1,'CAM320221128102453476920',NULL,NULL,1,1,1,1,'2022-11-28 15:54:53','2022-11-28 15:54:53','super admin','super admin','172.16.154.165',NULL,'1',1),(2,'CAM320221128102453476920','QUE320221128102514627793',NULL,1,1,1,1,'2022-11-28 15:55:14','2022-11-28 15:55:14','super admin','super admin','172.16.154.165',NULL,'1',1),(3,NULL,NULL,'11005',2,2,2,2,'2022-11-28 15:57:52','2022-11-28 15:57:52','super admin','super admin','172.16.154.165',NULL,'2',1),(4,NULL,NULL,'11005',1,1,1,1,'2022-11-28 15:59:01','2022-11-28 15:59:01','super admin','super admin','172.16.154.165',NULL,'1',1),(5,NULL,NULL,'11003',2,2,2,2,'2022-11-28 15:59:56','2022-11-28 15:59:56','super admin','super admin','172.16.154.165',NULL,'1',1),(6,NULL,NULL,'11002',2,2,2,2,'2022-11-28 16:00:34','2022-11-28 16:00:34','super admin','super admin','172.16.154.165',NULL,'1',1),(7,NULL,NULL,'11001',2,2,2,2,'2022-11-28 16:01:12','2022-11-28 16:01:12','super admin','super admin','172.16.154.165',NULL,'1',1),(8,NULL,NULL,'11004',1,1,1,1,'2022-11-28 16:13:39','2022-11-28 16:13:39','super admin','super admin','172.16.154.165',NULL,'1',1),(9,NULL,NULL,'11006',1,1,1,1,'2022-11-29 16:19:32','2022-11-29 16:19:32','super admin','super admin','172.16.154.52',NULL,'1',1);
/*!40000 ALTER TABLE `vaani_call_access` ENABLE KEYS */;
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
