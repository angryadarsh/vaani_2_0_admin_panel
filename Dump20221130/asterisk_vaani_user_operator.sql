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
-- Table structure for table `vaani_user_operator`
--

DROP TABLE IF EXISTS `vaani_user_operator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_user_operator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) DEFAULT NULL,
  `operator_id` varchar(255) DEFAULT NULL,
  `created_date` varchar(255) DEFAULT NULL,
  `modified_date` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `modified_by` varchar(255) DEFAULT NULL,
  `created_ip` varchar(255) DEFAULT NULL,
  `modified_ip` varchar(255) DEFAULT NULL,
  `del_status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_user_operator`
--

LOCK TABLES `vaani_user_operator` WRITE;
/*!40000 ALTER TABLE `vaani_user_operator` DISABLE KEYS */;
INSERT INTO `vaani_user_operator` VALUES (1,'2022102','1','2022-03-16 15:26:38','2022-03-16 15:26:38','super admin','super admin','172.16.154.39',NULL,2),(2,'2022102','2','2022-03-16 15:43:43','2022-03-16 15:43:43','super admin','super admin','172.16.154.39',NULL,2),(3,'1905','2','2022-03-21 10:52:26','2022-03-21 10:52:26','super admin','super admin','172.16.153.24',NULL,2),(4,'2022102','2','2022-03-21 11:46:45','2022-03-21 12:08:26','super admin','super admin','172.16.154.39','172.16.154.39',2),(5,'1905','2','2022-03-21 11:52:22','2022-03-21 12:03:04','super admin','super admin','172.16.154.39','172.16.153.24',2),(6,'1905','2','2022-03-21 12:03:55','2022-03-21 12:03:55','super admin','super admin','172.16.153.24',NULL,2),(7,'1905','2','2022-03-21 12:09:25','2022-03-21 12:51:47','super admin','super admin','172.16.153.24','172.16.153.24',2),(8,'2022102','2','2022-03-21 12:11:36','2022-03-21 12:15:59','super admin','super admin','172.16.154.39','172.16.154.39',2),(9,'2022102','2','2022-03-21 12:17:28','2022-03-21 12:17:28','super admin','super admin','172.16.154.39',NULL,2),(10,'2022102','2','2022-03-21 12:18:09','2022-03-21 15:22:31','super admin','super admin','172.16.154.39','172.16.153.24',2),(11,'1905','2','2022-03-21 12:52:40','2022-03-21 12:54:51','super admin','super admin','172.16.153.24','172.16.153.24',2),(12,'1905','2','2022-03-22 13:03:51','2022-03-22 13:03:51','super admin','super admin','192.168.253.1',NULL,1),(13,'116692','2','2022-03-22 14:52:01','2022-03-25 12:31:17','super admin','super admin','172.16.11.88','172.16.154.39',2),(14,'600015','2','2022-08-01 16:33:44','2022-08-01 16:33:44','super admin','super admin','172.16.154.30',NULL,1),(15,'1349','2','2022-08-18 17:53:08','2022-08-19 11:14:25','Somnath','super admin','10.32.1.120','172.16.154.165',2),(16,'1349','2','2022-08-19 11:19:39','2022-08-19 15:37:48','super admin','super admin','172.16.154.165','172.16.154.165',2),(17,'1221','1','2022-08-22 16:38:36','2022-08-22 16:38:36','super admin','super admin','172.16.154.39',NULL,2),(18,'1221','2','2022-08-22 18:01:58','2022-08-22 18:25:10','super admin','super admin','172.16.154.39','172.16.154.41',2),(19,'1221','2','2022-08-23 12:07:48','2022-08-23 12:07:48','super admin','super admin','172.16.154.39',NULL,1),(20,'1349','2','2022-08-23 18:52:43','2022-08-23 18:52:43','super admin','super admin','172.16.154.39',NULL,1);
/*!40000 ALTER TABLE `vaani_user_operator` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:46:12
