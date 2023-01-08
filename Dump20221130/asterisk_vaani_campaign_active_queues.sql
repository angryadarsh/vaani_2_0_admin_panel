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
-- Table structure for table `vaani_campaign_active_queues`
--

DROP TABLE IF EXISTS `vaani_campaign_active_queues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_campaign_active_queues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign` varchar(255) DEFAULT NULL,
  `q1` varchar(255) DEFAULT NULL,
  `q2` varchar(255) DEFAULT NULL,
  `q3` varchar(255) DEFAULT NULL,
  `q4` varchar(255) DEFAULT NULL,
  `q5` varchar(255) DEFAULT NULL,
  `q6` varchar(255) DEFAULT NULL,
  `q7` varchar(255) DEFAULT NULL,
  `q8` varchar(255) DEFAULT NULL,
  `q9` varchar(255) DEFAULT NULL,
  `q10` varchar(255) DEFAULT NULL,
  `syn` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=656 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_campaign_active_queues`
--

LOCK TABLES `vaani_campaign_active_queues` WRITE;
/*!40000 ALTER TABLE `vaani_campaign_active_queues` DISABLE KEYS */;
INSERT INTO `vaani_campaign_active_queues` VALUES (1,'edas_it','support_1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(2,'plcc_sales','Sales_2','Support_2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(3,'test_camp','testq1_3','testq2_3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(156,'vaani_campaign','vaani_queue_48',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(295,'thermo_camp1','q1_76',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(619,'edas_qa_ob',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(626,'edas_ob_tp','edas_ob_tp_default38_127',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(627,'edas_ob_tp_00','edas_ob_tp_00_default45_127',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(633,'tester_01_campaign',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(638,'tester_01_timepre','tester_01_timepre_default18_129',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(655,'hdfc_ergo_insure','hdfc_insure_146',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `vaani_campaign_active_queues` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:46:09
