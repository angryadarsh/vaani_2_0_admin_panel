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
-- Table structure for table `edas_agent`
--

DROP TABLE IF EXISTS `edas_agent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `edas_agent` (
  `agent_id` varchar(20) NOT NULL,
  `agent_name` varchar(45) NOT NULL,
  `agent_email_id` varchar(45) NOT NULL DEFAULT 'NULL',
  `agent_mobile` varchar(45) NOT NULL DEFAULT 'NULL',
  `agent_enabled` varchar(45) NOT NULL DEFAULT 'NULL',
  `agent_login_id` varchar(45) NOT NULL DEFAULT 'NULL',
  `agent_login_password` varchar(45) NOT NULL DEFAULT 'NULL',
  `agent_terminal_id` varchar(45) NOT NULL DEFAULT 'NULL',
  `agent_dni` varchar(45) NOT NULL DEFAULT 'NULL',
  `agent_cli` varchar(45) DEFAULT NULL,
  `agent_role_id` varchar(45) NOT NULL DEFAULT 'NULL',
  PRIMARY KEY (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edas_agent`
--

LOCK TABLES `edas_agent` WRITE;
/*!40000 ALTER TABLE `edas_agent` DISABLE KEYS */;
INSERT INTO `edas_agent` VALUES ('1','Sumit','NULL','NULL','NULL','11456','NULL','NULL','NULL',NULL,'NULL'),('2','Sumit','NULL','NULL','NULL','11456','NULL','NULL','NULL',NULL,'NULL'),('3','Sumit','NULL','NULL','NULL','11456','NULL','NULL','NULL',NULL,'NULL'),('4','Sumit','NULL','NULL','NULL','11456','NULL','NULL','NULL',NULL,'NULL');
/*!40000 ALTER TABLE `edas_agent` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:54
