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
-- Table structure for table `vaani_client_cron`
--

DROP TABLE IF EXISTS `vaani_client_cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_client_cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` varchar(45) DEFAULT NULL,
  `client_name` varchar(45) DEFAULT NULL,
  `cron_name` varchar(45) DEFAULT NULL,
  `last_executed_at` varchar(45) DEFAULT NULL,
  `records_executed` varchar(20) DEFAULT NULL,
  `executed_first_id` varchar(20) DEFAULT NULL,
  `executed_last_id` varchar(20) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=188 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_client_cron`
--

LOCK TABLES `vaani_client_cron` WRITE;
/*!40000 ALTER TABLE `vaani_client_cron` DISABLE KEYS */;
INSERT INTO `vaani_client_cron` VALUES (1,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 16:00:02','0','','',1),(2,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 16:15:02','0','','',1),(3,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 16:30:02','0','','',1),(4,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 16:45:01','0','','',1),(5,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 17:00:02','0','','',1),(6,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 17:15:01','0','','',1),(7,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 17:30:02','0','','',1),(8,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 17:45:02','0','','',1),(9,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 18:00:02','0','','',1),(10,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 18:15:01','0','','',1),(11,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 18:30:01','0','','',1),(12,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 18:45:02','0','','',1),(13,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 19:00:01','0','','',1),(14,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 19:15:02','0','','',1),(15,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 19:30:01','0','','',1),(16,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 19:45:01','0','','',1),(17,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 20:00:01','0','','',1),(18,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 20:15:01','0','','',1),(19,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 20:30:01','0','','',1),(20,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 20:45:02','0','','',1),(21,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 21:00:02','0','','',1),(22,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 21:15:02','0','','',1),(23,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 21:30:01','0','','',1),(24,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 21:45:02','0','','',1),(25,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 22:00:01','0','','',1),(26,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 22:15:02','0','','',1),(27,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 22:30:02','0','','',1),(28,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 22:45:01','0','','',1),(29,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 23:00:02','0','','',1),(30,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 23:15:01','0','','',1),(31,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 23:30:02','0','','',1),(32,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-28 23:45:02','0','','',1),(33,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 00:00:02','0','','',1),(34,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 00:15:01','0','','',1),(35,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 00:30:01','0','','',1),(36,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 00:45:01','0','','',1),(37,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 01:00:02','0','','',1),(38,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 01:15:02','0','','',1),(39,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 01:30:01','0','','',1),(40,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 01:45:02','0','','',1),(41,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 02:00:02','0','','',1),(42,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 02:15:02','0','','',1),(43,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 02:30:02','0','','',1),(44,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 02:45:02','0','','',1),(45,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 03:00:02','0','','',1),(46,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 03:15:02','0','','',1),(47,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 03:30:01','0','','',1),(48,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 03:45:02','0','','',1),(49,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 04:00:01','0','','',1),(50,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 04:15:02','0','','',1),(51,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 04:30:02','0','','',1),(52,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 04:45:01','0','','',1),(53,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 05:00:02','0','','',1),(54,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 05:15:01','0','','',1),(55,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 05:30:02','0','','',1),(56,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 05:45:02','0','','',1),(57,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 06:00:02','0','','',1),(58,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 06:15:02','0','','',1),(59,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 06:30:02','0','','',1),(60,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 06:45:01','0','','',1),(61,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 07:00:02','0','','',1),(62,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 07:15:02','0','','',1),(63,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 07:30:02','0','','',1),(64,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 07:45:01','0','','',1),(65,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 08:00:01','0','','',1),(66,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 08:15:02','0','','',1),(67,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 08:30:02','0','','',1),(68,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 08:45:02','0','','',1),(69,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 09:00:02','0','','',1),(70,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 09:15:01','0','','',1),(71,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 09:30:02','0','','',1),(72,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 09:45:01','0','','',1),(73,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 10:00:01','0','','',1),(74,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 10:15:02','0','','',1),(75,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 10:30:02','0','','',1),(76,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 10:45:02','0','','',1),(77,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 11:00:01','0','','',1),(78,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 11:15:02','0','','',1),(79,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 11:30:01','0','','',1),(80,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 11:45:01','0','','',1),(81,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 12:00:02','0','','',1),(82,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 12:15:02','0','','',1),(83,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','acd_cron','2022-11-29 12:15:02','4','3','6',1),(84,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 12:30:01','0','','',1),(85,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 12:45:02','2','1','2',1),(86,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 12:45:02','2','1','2',1),(87,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 13:00:02','0','','',1),(88,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 13:15:01','1','3','3',1),(89,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 13:15:01','1','3','3',1),(90,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 13:30:01','0','','',1),(91,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 13:45:02','0','','',1),(92,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 14:00:02','0','','',1),(93,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 14:15:01','0','','',1),(94,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 14:30:01','0','','',1),(95,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 14:45:02','0','','',1),(96,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 15:00:01','1','4','4',1),(97,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 15:00:01','1','4','4',1),(98,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 15:15:02','2','5','6',1),(99,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 15:15:02','2','5','6',1),(100,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 15:30:01','2','7','8',1),(101,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 15:30:01','2','7','8',1),(102,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 15:45:02','0','','',1),(103,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 16:00:01','0','','',1),(104,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 16:15:02','2','9','10',1),(105,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 16:15:02','2','9','10',1),(106,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 16:30:02','2','11','12',1),(107,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 16:30:02','2','11','12',1),(108,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 16:45:01','0','','',1),(109,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 17:00:02','1','13','13',1),(110,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 17:00:02','1','13','13',1),(111,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 17:15:02','0','','',1),(112,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 17:30:01','1','14','14',1),(113,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 17:30:01','1','14','14',1),(114,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 17:45:02','0','','',1),(115,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 18:00:02','1','15','15',1),(116,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 18:00:02','1','15','15',1),(117,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 18:15:01','1','16','16',1),(118,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 18:15:01','1','16','16',1),(119,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 18:30:02','1','17','17',1),(120,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 18:30:02','1','17','17',1),(121,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 18:45:01','4','18','21',1),(122,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 18:45:01','3','18','21',1),(123,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 19:00:02','0','','',1),(124,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','call_register_cron','2022-11-29 19:15:02','3','22','24',1),(125,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 19:15:02','4','22','25',1),(126,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 19:30:01','0','','',1),(127,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 19:45:01','0','','',1),(128,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 20:00:02','0','','',1),(129,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 20:15:01','0','','',1),(130,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 20:30:02','0','','',1),(131,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 20:45:01','0','','',1),(132,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 21:00:02','0','','',1),(133,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 21:15:01','0','','',1),(134,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 21:30:02','0','','',1),(135,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 21:45:02','0','','',1),(136,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 22:00:01','0','','',1),(137,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 22:15:01','0','','',1),(138,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 22:30:01','0','','',1),(139,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 22:45:02','0','','',1),(140,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 23:00:02','0','','',1),(141,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 23:15:01','0','','',1),(142,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 23:30:01','0','','',1),(143,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-29 23:45:02','0','','',1),(144,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 00:00:02','0','','',1),(145,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 00:15:02','0','','',1),(146,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 00:30:02','0','','',1),(147,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 00:45:01','0','','',1),(148,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 01:00:01','0','','',1),(149,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 01:15:01','0','','',1),(150,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 01:30:02','0','','',1),(151,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 01:45:02','0','','',1),(152,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 02:00:01','0','','',1),(153,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 02:15:02','0','','',1),(154,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 02:30:01','0','','',1),(155,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 02:45:01','0','','',1),(156,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 03:00:01','0','','',1),(157,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 03:15:02','0','','',1),(158,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 03:30:02','0','','',1),(159,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 03:45:02','0','','',1),(160,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 04:00:02','0','','',1),(161,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 04:15:02','0','','',1),(162,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 04:30:02','0','','',1),(163,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 04:45:02','0','','',1),(164,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 05:00:01','0','','',1),(165,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 05:15:02','0','','',1),(166,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 05:30:02','0','','',1),(167,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 05:45:02','0','','',1),(168,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 06:00:02','0','','',1),(169,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 06:15:01','0','','',1),(170,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 06:30:01','0','','',1),(171,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 06:45:01','0','','',1),(172,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 07:00:01','0','','',1),(173,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 07:15:02','0','','',1),(174,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 07:30:01','0','','',1),(175,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 07:45:02','0','','',1),(176,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 08:00:01','0','','',1),(177,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 08:15:02','0','','',1),(178,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 08:30:01','0','','',1),(179,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 08:45:02','0','','',1),(180,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 09:00:01','0','','',1),(181,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 09:15:02','0','','',1),(182,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 09:30:02','0','','',1),(183,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 09:45:01','0','','',1),(184,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 10:00:01','0','','',1),(185,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 10:15:02','0','','',1),(186,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 10:30:01','0','','',1),(187,'CL1420221128092007462787','HDFC_ERGO_INSURENCE','apr_cron','2022-11-30 10:45:01','0','','',1);
/*!40000 ALTER TABLE `vaani_client_cron` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:45
