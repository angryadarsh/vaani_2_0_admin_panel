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
-- Table structure for table `vaani_role`
--

DROP TABLE IF EXISTS `vaani_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_role` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `role_id` varchar(50) NOT NULL,
  `parent_id` varchar(50) NOT NULL,
  `level` int(4) NOT NULL,
  `role_name` varchar(50) DEFAULT NULL,
  `role_description` varchar(50) DEFAULT NULL,
  `role_enable` varchar(50) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` varchar(50) DEFAULT NULL,
  `created_ip` varchar(50) DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `modified_ip` varchar(45) DEFAULT NULL,
  `last_activity` varchar(45) DEFAULT NULL,
  `change_set` varchar(45) DEFAULT NULL,
  `del_status` int(11) DEFAULT NULL,
  `syn` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_role`
--

LOCK TABLES `vaani_role` WRITE;
/*!40000 ALTER TABLE `vaani_role` DISABLE KEYS */;
INSERT INTO `vaani_role` VALUES (1,'2','2',1,'superadmin','','3','superadmin','2021-10-13 11:20:38',NULL,NULL,NULL,NULL,NULL,NULL,1,1),(2,'3','2',2,'Admin','','1','superadmin','2021-10-13 11:20:38',NULL,'super admin','2022-04-19 17:39:28','172.16.154.33',NULL,NULL,1,1),(3,'4','3',3,'Manager','','1','superadmin','2021-10-13 11:20:38',NULL,'super admin','2022-02-03 14:41:07','172.16.154.39',NULL,NULL,1,1),(4,'5','3',3,'Supervisor','','1','superadmin','2021-10-13 11:20:38',NULL,'super admin','2022-02-03 14:40:39','172.16.154.39',NULL,NULL,1,1),(5,'ROL5520211030093854888065','4',4,'Quality','','1','super admin','2021-10-30 15:08:54','172.16.154.33','super admin','2022-02-09 12:51:42','172.16.154.30',NULL,NULL,1,1),(6,'ROL5520211030095819776097','4',4,'MIS','','1','super admin','2021-10-30 15:28:19','172.16.154.33','super admin','2022-06-01 11:37:54','172.16.154.39',NULL,NULL,1,1),(7,'ROL5520211101094927719720','5',4,'Agent','','1','super admin','2021-11-01 15:19:27','172.16.154.30','super admin','2022-01-05 12:40:20','172.16.154.39',NULL,NULL,1,1),(12,'ROL5520211216111829573329','5',4,'abc','','2','super admin','2021-12-16 16:48:29','172.16.154.39','Akshata','2022-06-01 15:45:03','172.16.154.39',NULL,NULL,2,1),(13,'ROL5520220322050125925149','5',4,'Tl ',NULL,'2','super admin','2022-03-22 10:31:25','172.16.11.88','Admin','2022-09-07 12:01:49','172.16.11.254',NULL,NULL,1,1);
/*!40000 ALTER TABLE `vaani_role` ENABLE KEYS */;
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
