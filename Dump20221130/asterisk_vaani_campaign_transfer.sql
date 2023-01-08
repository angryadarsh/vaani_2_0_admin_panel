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
-- Table structure for table `vaani_campaign_transfer`
--

DROP TABLE IF EXISTS `vaani_campaign_transfer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_campaign_transfer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(50) NOT NULL,
  `trf_camp_queue` varchar(100) NOT NULL COMMENT 'transfer campaign or queue name',
  `trf_flag` tinyint(2) NOT NULL COMMENT 'transfer flag- 1:not tranfer, 2:transfer',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `del_status` tinyint(2) NOT NULL COMMENT '1-not deleted, 2-not deleted',
  `syn` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_campaign_transfer`
--

LOCK TABLES `vaani_campaign_transfer` WRITE;
/*!40000 ALTER TABLE `vaani_campaign_transfer` DISABLE KEYS */;
INSERT INTO `vaani_campaign_transfer` VALUES (1,'1644908585.108','mhl_pun_16',0,'2022-02-15 12:35:24','0000-00-00 00:00:00',1,1),(2,'1644908856.122','mhl_mum_16',0,'2022-02-15 12:38:39','0000-00-00 00:00:00',1,1),(3,'1644910301.154','mhl_pun_16',0,'2022-02-15 13:02:10','0000-00-00 00:00:00',1,1),(4,'1645598427.919','mhl_pun_16',2,'2022-02-23 12:11:06','2022-02-23 12:11:07',1,1),(5,'1645599332.933','mhl_pun_16',2,'2022-02-23 12:25:49','2022-02-23 12:25:51',1,1),(6,'1645600533.938','mhl_pun_16',2,'2022-02-23 12:46:01','2022-02-23 12:46:02',1,1),(7,'1645600900.943','mhl_pun_16',2,'2022-02-23 12:51:55','2022-02-23 12:51:56',1,1),(8,'1645601205.948','mhl_pun_16',2,'2022-02-23 12:56:58','2022-02-23 12:56:59',1,1),(9,'1645601492.957','mhl_pun_16',2,'2022-02-23 13:01:52','2022-02-23 13:01:53',1,1),(10,'1645602323.970','mhl_pun_16',2,'2022-02-23 13:15:46','2022-02-23 13:15:48',1,1),(11,'1648461070.729','testing__42',1,'2022-03-28 15:22:17','0000-00-00 00:00:00',1,1),(12,'1648462982.962','testing__42',2,'2022-03-28 15:53:32','2022-03-28 15:53:33',1,1),(13,'1648729948.844','edas_testing_44',1,'2022-03-31 18:04:44','0000-00-00 00:00:00',1,1),(14,'1648811879.1547','edas_testing_44',2,'2022-04-01 16:52:44','2022-04-01 16:52:46',1,1);
/*!40000 ALTER TABLE `vaani_campaign_transfer` ENABLE KEYS */;
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
