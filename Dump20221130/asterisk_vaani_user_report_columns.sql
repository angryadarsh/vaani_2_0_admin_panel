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
-- Table structure for table `vaani_user_report_columns`
--

DROP TABLE IF EXISTS `vaani_user_report_columns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_user_report_columns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(50) DEFAULT NULL,
  `formname` varchar(50) DEFAULT NULL,
  `reportcolumn` text,
  `del_status` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_user_report_columns`
--

LOCK TABLES `vaani_user_report_columns` WRITE;
/*!40000 ALTER TABLE `vaani_user_report_columns` DISABLE KEYS */;
INSERT INTO `vaani_user_report_columns` VALUES (1,'superadmin1','agemtperformancereport','Date,Interval,Agent,Agent Id','N'),(2,'superadmin1','agemtperformancereport','Date,Interval,Agent,Agent Id,Total Calls,Outbound Answered,Inbound Answered,Login Time,Not Ready','N'),(3,'superadmin1','agemtperformancereport','Date,Interval,Agent,Agent Id,Total Calls,Outbound Answered,Inbound Answered,Login Time,Not Ready,Idle Duration,Ring Duration,Talk Duration,Hold Duration,Wrap Up Duration','N'),(4,'superadmin1','agemtperformancereport','Date,Interval,Agent,Agent Id,Total Calls,Outbound Answered,Inbound Answered,Login Time,Not Ready,Idle Duration,Ring Duration,Talk Duration','N'),(5,'superadmin','agemtperformancereport','Date,Interval,Agent,Agent Id,Total Calls,Outbound Answered,Inbound Answered,Login Time,Idle Duration,Talk Duration,Hold Duration,Wrap Up Duration,Ring Duration,Not Ready','N'),(6,'Admin','agemtperformancereport','Hold Duration,Ring Duration,Agent Id,Wrap Up Duration','N');
/*!40000 ALTER TABLE `vaani_user_report_columns` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:58
