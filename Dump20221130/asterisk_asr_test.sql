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
-- Table structure for table `asr_test`
--

DROP TABLE IF EXISTS `asr_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asr_test` (
  `call_id` int(10) NOT NULL AUTO_INCREMENT,
  `mobile_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `gender` enum('male','female') COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expiry_date` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `members` int(8) DEFAULT NULL,
  `context` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `udf1` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `udf2` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `udf3` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`call_id`,`mobile_number`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asr_test`
--

LOCK TABLES `asr_test` WRITE;
/*!40000 ALTER TABLE `asr_test` DISABLE KEYS */;
INSERT INTO `asr_test` VALUES (1,'8802444546','male','Dharmesh Mali','42312','07-11-1986',6,'india_bulls','dharmesh.mali@edas.tech','2',NULL,NULL),(2,'9819880419','male','Wilson Nadar','500000','20-10-2021',3,'riya_travels','wilson.nadar@eosglobe.com','12345678',NULL,NULL),(3,'7827303532','female','Advika Sonawane','98643','18-09-1993',8,'asr_test',NULL,NULL,NULL,NULL),(4,'9594994653','male','Abhinav Arora','2500000','09-09-2021',4,'asr_test',NULL,NULL,NULL,NULL),(5,'2261846800','male','I D C','589321','31-10-1979',154,'riya_travels','swapnil.kulkarni@eosglobe.com','87654321',NULL,NULL),(6,'2261846860','male','Wilson Nadar','500000',NULL,NULL,'riya_travels','wilson.nadar@eosglobe.com','12345678',NULL,NULL),(7,'9819880419','male','Wilson Nadar',NULL,NULL,NULL,'rgi_sales','wilson.nadar@eosglobe.com','Nadar',NULL,NULL),(8,'8452956251','male','Kunal Nagani','87123','21-09-2023',5,'rgi_sales','kunal.nagani@eosglobe.com','Nagani',NULL,NULL),(9,'8452956251','male','Kunal Nagani','87123','21-09-2023',5,'riya_travels','kunal.nagani@eosglobe.com','12345678',NULL,NULL),(10,'9819880419','male','Wilson Nadar',NULL,NULL,NULL,'kotak_bank',NULL,'8802444546',NULL,NULL),(11,'2261846800','male','DHarmesh Mali',NULL,NULL,NULL,'kotak_bank',NULL,'8802444546',NULL,NULL),(12,'7718946961','female','Taniya Verma',NULL,NULL,NULL,'kotak_bank',NULL,'8802444546',NULL,NULL);
/*!40000 ALTER TABLE `asr_test` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:46:25
