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
-- Table structure for table `vaani_agent_audit_sheet`
--

DROP TABLE IF EXISTS `vaani_agent_audit_sheet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_agent_audit_sheet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_id` varchar(45) DEFAULT NULL,
  `agent_id` varchar(45) DEFAULT NULL,
  `sheet_id` varchar(45) DEFAULT NULL,
  `campaign_id` varchar(45) DEFAULT NULL,
  `campaign` varchar(45) DEFAULT NULL,
  `language` varchar(45) DEFAULT NULL,
  `audit_type` varchar(45) DEFAULT NULL,
  `location` varchar(45) DEFAULT NULL,
  `call_duration` varchar(45) DEFAULT NULL,
  `call_date` varchar(45) DEFAULT NULL,
  `week` varchar(45) DEFAULT NULL,
  `month` varchar(45) DEFAULT NULL,
  `call_id` varchar(45) DEFAULT NULL,
  `analysis_finding` varchar(45) DEFAULT NULL,
  `agent_type` varchar(45) DEFAULT NULL,
  `unique_id` varchar(45) DEFAULT NULL,
  `disposition` varchar(45) DEFAULT NULL,
  `sub_disposition` varchar(45) DEFAULT NULL,
  `pip_status` varchar(45) DEFAULT NULL,
  `categorization` varchar(45) DEFAULT NULL,
  `action_status` varchar(45) DEFAULT NULL,
  `gist_of_case` tinytext,
  `resolution_provided` tinytext,
  `areas_of_improvement` tinytext,
  `reason_for_fatal_call` tinytext,
  `quality_score` float DEFAULT NULL,
  `out_of` float DEFAULT NULL,
  `final_score` float DEFAULT NULL,
  `yes_count` int(50) DEFAULT NULL,
  `total_percent` float DEFAULT NULL,
  `rec_markers` varchar(180) DEFAULT NULL,
  `start_time` varchar(45) DEFAULT NULL,
  `end_time` varchar(45) DEFAULT NULL,
  `audit_duration` varchar(45) DEFAULT NULL,
  `date_created` varchar(45) DEFAULT NULL,
  `date_modified` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `modified_by` varchar(45) DEFAULT NULL,
  `created_ip` varchar(45) DEFAULT NULL,
  `modified_ip` varchar(45) DEFAULT NULL,
  `del_status` int(11) DEFAULT NULL,
  `feedback` varchar(180) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL COMMENT '1 - Accepted, 2 - Rejected, 3 - Pending',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_agent_audit_sheet`
--

LOCK TABLES `vaani_agent_audit_sheet` WRITE;
/*!40000 ALTER TABLE `vaani_agent_audit_sheet` DISABLE KEYS */;
/*!40000 ALTER TABLE `vaani_agent_audit_sheet` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:44:54