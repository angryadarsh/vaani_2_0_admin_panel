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
-- Table structure for table `vaani_role_id1`
--

DROP TABLE IF EXISTS `vaani_role_id1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_role_id1` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(45) DEFAULT NULL,
  `role_description` varchar(45) DEFAULT NULL,
  `role_enable` varchar(45) DEFAULT NULL COMMENT '1-enable, 2-disable, 3-superadmin',
  `created_by` varchar(45) DEFAULT NULL,
  `created_date` varchar(45) DEFAULT NULL,
  `created_ip` varchar(45) DEFAULT NULL,
  `modified_by` varchar(45) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `modified_ip` varchar(45) DEFAULT NULL,
  `last_activity` varchar(45) DEFAULT NULL,
  `change_set` varchar(45) DEFAULT NULL,
  `del_status` tinyint(4) DEFAULT '1' COMMENT '1-not deleted, 2-deleted',
  `syn` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_role_id1`
--

LOCK TABLES `vaani_role_id1` WRITE;
/*!40000 ALTER TABLE `vaani_role_id1` DISABLE KEYS */;
INSERT INTO `vaani_role_id1` VALUES (1,'sam',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1),(2,'superadmin','','3','superadmin','2021-10-13 11:20:38',NULL,NULL,NULL,NULL,NULL,NULL,1,1),(3,'admin','','1','superadmin','2021-10-13 11:20:38',NULL,NULL,NULL,NULL,NULL,NULL,1,1),(4,'manager','','1','superadmin','2021-10-13 11:20:38',NULL,NULL,NULL,NULL,NULL,NULL,1,1),(5,'tl','','1','superadmin','2021-10-13 11:20:38',NULL,NULL,NULL,NULL,NULL,NULL,1,1);
/*!40000 ALTER TABLE `vaani_role_id1` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:56
