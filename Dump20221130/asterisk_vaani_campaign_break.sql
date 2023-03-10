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
-- Table structure for table `vaani_campaign_break`
--

DROP TABLE IF EXISTS `vaani_campaign_break`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_campaign_break` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` varchar(255) DEFAULT NULL,
  `break_name` varchar(255) DEFAULT NULL,
  `break_id` varchar(50) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `modified_by` varchar(255) DEFAULT NULL,
  `created_ip` varchar(255) DEFAULT NULL,
  `modified_ip` varchar(255) DEFAULT NULL,
  `del_status` int(11) DEFAULT NULL,
  `syn` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_campaign_break`
--

LOCK TABLES `vaani_campaign_break` WRITE;
/*!40000 ALTER TABLE `vaani_campaign_break` DISABLE KEYS */;
INSERT INTO `vaani_campaign_break` VALUES (1,'CAM320220207052355754057','Bio Break',NULL,1,NULL,'2022-03-03 19:05:14',NULL,'super admin',NULL,'172.16.154.39',2,1),(2,'CAM320220207052355754057','Lunch Break',NULL,1,NULL,'2022-03-03 18:52:54',NULL,'super admin',NULL,'172.16.154.39',2,1),(3,'CAM320220207052355754057','Tea Break',NULL,1,NULL,'2022-03-03 19:05:14',NULL,'super admin',NULL,'172.16.154.39',2,1),(4,'CAM320220207052355754057','Training','BRK720220223045553563819',1,NULL,'2022-03-03 18:37:39',NULL,'super admin',NULL,'172.16.154.39',2,1),(5,'CAM320220207060303779274','Tea Break','BRK720220214123648978675',1,'2022-02-14 15:36:06','2022-02-14 18:06:48','super admin','super admin','172.16.154.39','172.16.154.39',1,1),(6,'CAM320220207060303779274','Bio Break','BRK720220214123651742836',1,'2022-02-14 15:37:15','2022-02-14 18:06:51','super admin','super admin','172.16.154.39','172.16.154.39',1,1),(7,'CAM320220207060303779274','Lunch','BRK720220214123654397117',1,'2022-02-14 17:44:45','2022-02-14 18:06:54','super admin','super admin','172.16.154.39','172.16.154.39',1,1),(8,'CAM320220223121352856801','Lunch','BRK720220223124040535472',1,'2022-02-23 18:10:40','2022-02-23 18:10:40','super admin','super admin','10.32.1.120',NULL,1,1),(9,'CAM320220223121352856801','Tea','BRK720220223124111715403',1,'2022-02-23 18:11:11','2022-02-23 18:11:11','super admin','super admin','10.32.1.120',NULL,1,1),(10,'CAM320220301100318514531','Tea Break','BRK720220301100554790115',1,'2022-03-01 15:35:54','2022-03-01 15:35:54','MMT Admin','MMT Admin','172.16.154.39',NULL,1,1),(11,'CAM320220301100318514531','Lunch','BRK720220301100615714929',1,'2022-03-01 15:36:15','2022-03-01 15:36:15','MMT Admin','MMT Admin','172.16.154.39',NULL,1,1),(12,'CAM320220223121352856801','Snack','BRK720220303090531420311',1,'2022-03-03 14:35:31','2022-03-03 16:58:04','super admin','super admin','172.16.154.39','172.16.154.39',1,1),(13,'CAM320220307125031798332','bio','BRK720220307125625441156',1,'2022-03-07 18:26:25','2022-03-14 16:17:24','2022101','super admin','172.16.154.30','172.16.154.30',1,1),(14,'CAM320220307125031798332','lunch','BRK720220307125625455773',1,'2022-03-07 18:26:25','2022-03-14 16:17:24','2022101','super admin','172.16.154.30','172.16.154.30',1,1),(15,'CAM320220308100644707367','Lunch ','BRK720220308105852502912',1,'2022-03-08 16:28:52','2022-03-08 16:29:22','super admin','super admin','172.16.11.254','172.16.11.254',1,1),(16,'CAM320220308100644707367','Bio','BRK720220308105923001205',1,'2022-03-08 16:29:23','2022-03-08 16:29:23','super admin','super admin','172.16.11.254',NULL,1,1),(17,'CAM320220308100644707367','Tea','BRK720220308105923022953',1,'2022-03-08 16:29:23','2022-03-08 16:29:23','super admin','super admin','172.16.11.254',NULL,1,1),(18,'CAM320220308100644707367','Tl Session ','BRK720220308105923040027',1,'2022-03-08 16:29:23','2022-03-08 16:29:23','super admin','super admin','172.16.11.254',NULL,1,1),(19,'CAM320220307125031798332','training','BRK720220314104724367914',1,'2022-03-14 16:17:24','2022-03-14 16:17:24','super admin','super admin','172.16.154.30',NULL,1,1),(20,'CAM320220314133040280447','Tea','BRK720220314133838363493',1,'2022-03-14 19:08:38','2022-03-14 19:09:19','Akshata','Akshata','172.16.11.88','172.16.11.88',1,1),(21,'CAM320220314133040280447','Bio','BRK720220314133903437229',1,'2022-03-14 19:09:03','2022-03-14 19:09:19','Akshata','Akshata','172.16.11.88','172.16.11.88',1,1),(22,'CAM320220314133040280447','Lunch ','BRK720220314133919596512',1,'2022-03-14 19:09:19','2022-03-14 19:09:19','Akshata','Akshata','172.16.11.88',NULL,1,1),(23,'CAM320220315043925056529','Bio Break','BRK720220315044522583094',1,'2022-03-15 10:15:22','2022-03-15 11:04:47','Amit','super admin','172.16.153.24','172.16.153.24',2,1),(24,'CAM320220315043925056529','Tea Break','BRK720220315044522603065',1,'2022-03-15 10:15:22','2022-03-15 11:04:47','Amit','super admin','172.16.153.24','172.16.153.24',2,1),(25,'CAM320220315043925056529','Lunch Break','BRK720220315044522620907',1,'2022-03-15 10:15:22','2022-03-15 11:04:47','Amit','super admin','172.16.153.24','172.16.153.24',2,1),(26,'CAM320220315063049478575','Tea Break','BRK720220315063248480153',1,'2022-03-15 12:02:48','2022-03-15 12:02:48','Amit','Amit','172.16.153.24',NULL,1,1),(27,'CAM320220315063049478575','BioBreak','BRK720220315063248507489',1,'2022-03-15 12:02:48','2022-03-15 12:02:48','Amit','Amit','172.16.153.24',NULL,1,1),(28,'CAM320220315071736750944','Bio Break','BRK720220315071930708905',1,'2022-03-15 12:49:30','2022-03-15 12:49:30','Amit','Amit','192.168.253.1',NULL,1,1),(29,'CAM320220315071736750944','Lunch Break','BRK720220315071930741063',1,'2022-03-15 12:49:30','2022-03-15 12:49:30','Amit','Amit','192.168.253.1',NULL,1,1),(30,'CAM320220322080235949208','Tea','BRK720220322095523527425',1,'2022-03-22 15:25:23','2022-03-22 15:25:23','super admin','super admin','172.16.11.88',NULL,1,1),(31,'CAM320220322080235949208','Bio','BRK720220322095523542362',1,'2022-03-22 15:25:23','2022-03-22 15:25:23','super admin','super admin','172.16.11.88',NULL,1,1),(32,'CAM320220322080235949208','Snacks ','BRK720220322095523553724',1,'2022-03-22 15:25:23','2022-03-22 15:25:23','super admin','super admin','172.16.11.88',NULL,1,1),(33,'CAM320220322080235949208','Lunch ','BRK720220322095523565131',1,'2022-03-22 15:25:23','2022-03-22 15:25:23','super admin','super admin','172.16.11.88',NULL,1,1),(34,'CAM320220328085805495536','Tea','BRK720220328093600566132',1,'2022-03-28 15:06:00','2022-03-28 15:06:38','super admin','super admin','172.16.11.88','172.16.11.88',1,1),(35,'CAM320220328085805495536','Bio','BRK720220328093638552185',1,'2022-03-28 15:06:38','2022-03-28 15:06:38','super admin','super admin','172.16.11.88',NULL,1,1),(36,'CAM320220328085805495536','Lunch ','BRK720220328093638570110',1,'2022-03-28 15:06:38','2022-03-28 15:06:38','super admin','super admin','172.16.11.88',NULL,1,1),(37,'CAM320220328085805495536','Breakfast','BRK720220328093638588031',1,'2022-03-28 15:06:38','2022-03-28 15:06:38','super admin','super admin','172.16.11.88',NULL,1,1),(38,'CAM320220329053040000061','Tea','BRK720220329054106899387',1,'2022-03-29 11:11:06','2022-03-29 11:11:06','super admin','super admin','172.16.11.88',NULL,1,1),(39,'CAM320220329053040000061','Lunch','BRK720220329054106916659',1,'2022-03-29 11:11:06','2022-03-29 11:11:06','super admin','super admin','172.16.11.88',NULL,1,1),(40,'CAM320220329053040000061','Bio','BRK720220329054106940583',1,'2022-03-29 11:11:06','2022-03-29 11:11:06','super admin','super admin','172.16.11.88',NULL,1,1),(41,'CAM320220331110357174379','Tea','BRK720220331120101638816',1,'2022-03-31 17:31:01','2022-03-31 17:31:01','super admin','super admin','172.16.11.88',NULL,1,1),(42,'CAM320220331110357174379','Bio','BRK720220331120101667037',1,'2022-03-31 17:31:01','2022-03-31 17:31:01','super admin','super admin','172.16.11.88',NULL,1,1),(43,'CAM320220331110357174379','Lunch','BRK720220331120101711959',1,'2022-03-31 17:31:01','2022-03-31 17:31:01','super admin','super admin','172.16.11.88',NULL,1,1),(44,'CAM320220411102202624114','Tea','BRK720220411102246504819',1,'2022-04-11 15:52:46','2022-04-11 16:09:28','super admin','super admin','172.16.154.39','172.16.154.39',2,1),(45,'CAM320220411102202624114','Lunch','BRK720220411102246521678',1,'2022-04-11 15:52:46','2022-04-11 16:09:28','super admin','super admin','172.16.154.39','172.16.154.39',2,1),(46,'CAM320220411102202624114','Bio','BRK720220411102246538601',1,'2022-04-11 15:52:46','2022-04-11 16:09:28','super admin','super admin','172.16.154.39','172.16.154.39',2,1),(47,'CAM320220412122658813718','Tea','BRK720220413050631726000',1,'2022-04-13 10:36:31','2022-04-13 11:27:12','super admin','super admin','172.16.11.88','172.16.11.88',2,1),(48,'CAM320220412122658813718','Bio','BRK720220413050631744924',1,'2022-04-13 10:36:31','2022-04-13 11:27:12','super admin','super admin','172.16.11.88','172.16.11.88',2,1),(49,'CAM320220412122658813718','Lunch ','BRK720220413050631762699',1,'2022-04-13 10:36:31','2022-04-13 11:27:12','super admin','super admin','172.16.11.88','172.16.11.88',2,1),(50,'CAM320220412122658813718','Tea','BRK720220413055733835022',1,'2022-04-13 11:27:33','2022-04-13 11:27:33','super admin','super admin','172.16.11.88',NULL,1,1),(51,'CAM320220412122658813718','Bio','BRK720220413055733849136',1,'2022-04-13 11:27:33','2022-04-13 11:27:33','super admin','super admin','172.16.11.88',NULL,1,1),(52,'CAM320220412122658813718','Lunch ','BRK720220413055733866077',1,'2022-04-13 11:27:33','2022-04-13 11:27:33','super admin','super admin','172.16.11.88',NULL,1,1),(53,'CAM320220421094203006130','Bio ','BRK720220426071526929049',1,'2022-04-26 12:45:26','2022-04-26 12:45:26','Akshata','Akshata','172.16.11.88',NULL,1,1),(54,'CAM320220421094203006130','Tea','BRK720220426071526944872',1,'2022-04-26 12:45:26','2022-04-26 12:45:26','Akshata','Akshata','172.16.11.88',NULL,1,1);
/*!40000 ALTER TABLE `vaani_campaign_break` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:46:05
