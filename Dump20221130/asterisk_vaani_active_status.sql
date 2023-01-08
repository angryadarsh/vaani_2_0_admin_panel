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
-- Table structure for table `vaani_active_status`
--

DROP TABLE IF EXISTS `vaani_active_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_active_status` (
  `auto_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(200) NOT NULL,
  `campaign_id` varchar(100) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `unique_id` varchar(50) DEFAULT NULL,
  `status` int(3) unsigned DEFAULT NULL,
  `sub_status` varchar(50) DEFAULT NULL,
  `agent_name` varchar(100) NOT NULL,
  `status_start_datetime` datetime NOT NULL,
  `substatus_start_datetime` datetime NOT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `del_status` tinyint(2) unsigned DEFAULT NULL,
  `syn` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `auto_id` (`auto_id`),
  KEY `unique_id` (`unique_id`),
  CONSTRAINT `vaani_active_status_ibfk_1` FOREIGN KEY (`unique_id`) REFERENCES `vaani_session` (`unique_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_active_status`
--

LOCK TABLES `vaani_active_status` WRITE;
/*!40000 ALTER TABLE `vaani_active_status` DISABLE KEYS */;
INSERT INTO `vaani_active_status` VALUES (13,'78aolm84jqjccu28pvbaid19hkiek4qnd4g529rn9og57gfsfmq0','','10003','S520220701071225000906',1,'Login','','0000-00-00 00:00:00','0000-00-00 00:00:00','2022-07-01 12:42:26','2022-07-01 12:42:26',1,1),(7,'ri2cqbcict2feour488kgpj357u1gshuaef4egj4a0f8abli52a0','','8663','S520220701054020115427',1,'Login','','0000-00-00 00:00:00','0000-00-00 00:00:00','2022-07-01 11:10:20','2022-07-01 11:10:20',1,1);
/*!40000 ALTER TABLE `vaani_active_status` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:46:10
