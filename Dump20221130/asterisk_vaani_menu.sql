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
-- Table structure for table `vaani_menu`
--

DROP TABLE IF EXISTS `vaani_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaani_menu` (
  `menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `menu_name` varchar(50) DEFAULT NULL,
  `level` int(10) unsigned DEFAULT NULL,
  `route` varchar(100) DEFAULT NULL,
  `link` varchar(50) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `sequence` int(10) unsigned DEFAULT NULL,
  `status` int(10) unsigned DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_ip` varchar(50) DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `modified_date` varchar(50) DEFAULT NULL,
  `modified_ip` varchar(50) DEFAULT NULL,
  `del_status` tinyint(3) unsigned DEFAULT NULL,
  `not_in_group` int(11) DEFAULT '1' COMMENT '1-all , 2- only super admin',
  `syn` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaani_menu`
--

LOCK TABLES `vaani_menu` WRITE;
/*!40000 ALTER TABLE `vaani_menu` DISABLE KEYS */;
INSERT INTO `vaani_menu` VALUES (1,46,'Call Register Report',2,'/report/call-register-report','dashboard.php','fas fa-tachometer-alt',1,1,NULL,NULL,'','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(2,2,'Client',1,'','clients.php','fas fa-mug-hot',1,1,NULL,NULL,'','super admin','2022-08-25 15:06:09','172.16.154.39',1,1,1),(3,3,'DNI',1,'','dni_list.php','fas fa-table',2,1,NULL,NULL,'','super admin','2022-08-25 15:06:09','172.16.154.39',1,1,1),(4,NULL,'Campaign',1,'/campaign/index','campaign_list.php','fas fa-table',3,1,NULL,NULL,'','super admin','2022-08-25 15:06:09','172.16.154.39',1,1,1),(5,NULL,'Role',1,'','role.php','fas fa-male',7,1,NULL,NULL,'','super admin','2022-08-25 15:06:09','172.16.154.39',1,2,1),(6,6,'Users',1,'','users.php','fas fa-user',5,1,NULL,NULL,'','super admin','2022-08-25 15:06:09','172.16.154.39',1,1,1),(7,5,'Role Access',2,'/role/access','define_role.php','fas fa-user-tag',3,1,NULL,NULL,'','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(8,8,'Menu Master',1,'/menu/index','menu_master.php','far fa-address-card',6,1,NULL,NULL,'','super admin','2022-08-25 15:06:09','172.16.154.39',1,2,1),(9,4,'Campaign List',2,'/campaign/index','campaign_list.php','fas fa-list',1,1,NULL,NULL,'','super admin','2022-08-24 11:27:43','172.16.154.52',2,1,1),(10,4,'Outbound',2,NULL,'outbound.php','fas fa-list',2,1,NULL,NULL,'','','','',2,1,1),(11,11,'Reports',1,NULL,'report.php','fas fa-table',10,1,NULL,NULL,'','','','',2,1,1),(12,0,'Test',1,NULL,'test.php','fas fa-vial',8,1,NULL,'2021-10-27 16:39:59','172.16.154.30',NULL,NULL,NULL,2,1,1),(13,11,'Test',2,NULL,'test.php','fas fa-table',1,1,NULL,'2021-10-27 17:04:41','172.16.154.30',NULL,NULL,NULL,2,1,1),(14,11,'Test',2,NULL,'test1.php','fas fa-table',1,1,NULL,'2021-10-27 17:43:51','172.16.154.30',NULL,NULL,NULL,2,1,1),(15,11,'Test',2,NULL,'test1.php','fas fa-table',1,1,NULL,'2021-10-27 17:54:59','172.16.154.30',NULL,NULL,NULL,1,1,1),(16,0,'Testing Menu',1,NULL,'','',10,1,NULL,'2021-10-28 11:21:11','172.16.154.33',NULL,NULL,NULL,2,1,1),(17,16,'Testing Sub Menu',2,NULL,'','',1,1,NULL,'2021-10-28 11:21:40','172.16.154.33',NULL,NULL,NULL,1,1,1),(18,17,'Testing Sub Menu Sub Menu',3,NULL,'','',1,1,NULL,'2021-10-28 11:23:04','172.16.154.33',NULL,NULL,NULL,1,1,1),(19,0,'Testing Menu',1,NULL,'','',10,1,NULL,'2021-10-28 11:50:58','172.16.154.33',NULL,NULL,NULL,2,1,1),(20,19,'Testing Sub Menu',2,NULL,'','',1,1,NULL,'2021-10-28 11:51:04','172.16.154.33',NULL,NULL,NULL,1,1,1),(21,20,'Testing Sub Menu Sub Menu',3,NULL,'','',1,1,NULL,'2021-10-28 11:51:11','172.16.154.33',NULL,NULL,NULL,1,1,1),(22,NULL,'Add Campaign',2,'/campaign/add-campaign','campaign_configuration.php','fas fa-table',2,1,NULL,'2021-11-01 18:35:47','172.16.154.30','super admin','2022-08-24 11:29:19','172.16.154.52',2,1,1),(23,2,'Add Client',2,'/client/create','client_form.php','fas fa-table',2,1,NULL,'2021-11-01 18:43:15','172.16.154.30','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(24,2,'Client List',2,'/client/index','clients.php','fas fa-table',1,1,NULL,'2021-11-01 18:44:17','172.16.154.30','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(25,3,'DNI List',2,'/dni/index','dni_list.php','fas fa-table',1,1,NULL,'2021-11-01 18:45:39','172.16.154.30','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(26,3,'Add DNI',2,'/dni/create','dni_configuration.php','fas fa-table',2,1,NULL,'2021-11-01 18:46:11','172.16.154.30','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(27,6,'User List',2,'/user/index','users.php','fas fa-table',1,1,NULL,'2021-11-01 18:46:54','172.16.154.30','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(28,6,'Add User',2,'/user/create','add_new_user.php','fa fa-user',2,1,NULL,'2021-11-01 18:47:36','172.16.154.30','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(29,4,'User List',2,NULL,'campaign_user_list.php','',3,1,NULL,'2021-11-12 12:57:25','172.16.154.33',NULL,NULL,NULL,2,1,1),(30,0,'Shrikant',1,NULL,'','',10,1,NULL,'2021-11-27 17:20:43','172.16.154.33',NULL,NULL,NULL,2,1,1),(31,30,'Sameer',2,NULL,'','',1,1,NULL,'2021-11-27 17:21:27','172.16.154.33',NULL,NULL,NULL,1,1,1),(32,0,'Test',1,NULL,'','',10,1,NULL,'2021-12-03 16:31:46','172.16.154.33',NULL,NULL,NULL,2,1,1),(33,NULL,'Monitoring',1,'/report/monitoring','outbound-monitoring.php','fas fa-sitemap',12,1,NULL,'2021-12-06 10:36:00','172.16.154.33','super admin','2022-08-25 15:06:09','172.16.154.39',1,1,1),(34,8,'Menu List',2,'/menu/index',NULL,'far fa-address-card',10,1,'super admin','2022-01-07 18:50:26','172.16.154.39','super admin','2022-01-11 12:41:20','172.16.154.39',2,1,1),(35,8,'Menu List',2,'/menu/index',NULL,'far fa-address-card',11,1,'super admin','2022-01-07 19:10:18','172.16.154.39','super admin','2022-01-11 12:40:46','172.16.154.39',2,1,1),(36,5,'Add Role',2,'/role/create',NULL,'fa fa-user',2,1,'super admin','2022-01-10 17:54:31','192.168.253.1','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(37,8,'Menu Data',2,'/menu/index',NULL,'fa fa-user',13,1,'super admin','2022-01-11 12:48:22','172.16.154.39','super admin','2022-01-11 14:39:19','172.16.154.39',2,1,1),(38,8,'Add Menu',2,'/menu/index',NULL,'far fa-address-card',14,1,'super admin','2022-01-11 14:37:54','172.16.154.39','super admin','2022-01-11 14:39:14','172.16.154.39',2,1,1),(39,5,'Role List',2,'/role/index',NULL,'far fa-address-card',1,1,'super admin','2022-01-11 15:03:32','172.16.154.39','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(40,46,'Call Recordings',2,'/report/recordings',NULL,'fas fa-microphone',2,1,'super admin','2022-01-14 17:19:58','192.168.253.1','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(41,NULL,'Dashboard',1,'/agent/dashboard',NULL,'fas fa-tachometer-alt',10,1,'super admin','2022-01-17 13:08:26','172.16.154.39','super admin','2022-08-25 15:06:09','172.16.154.39',1,1,1),(42,NULL,'Call Windows',1,'/call-times/index',NULL,'fas fa-clock',9,1,'super admin','2022-02-09 11:54:47','172.16.154.39','super admin','2022-08-25 15:06:09','172.16.154.39',1,1,1),(43,NULL,'Breaks',1,'/break/index',NULL,'fas fa-mug-hot',8,1,'super admin','2022-02-14 12:52:58','172.16.154.39','super admin','2022-08-25 15:06:09','172.16.154.39',1,1,1),(44,NULL,'PBX Configuration',1,'/pbx/configuration',NULL,'fas fa-wrench',11,1,'super admin','2022-02-18 10:54:43','172.16.154.39','super admin','2022-08-25 15:06:09','172.16.154.39',1,1,1),(45,NULL,'Knowledge Portal',1,'/tblaskme-file/index',NULL,'fas fa-info-circle',14,1,'super admin','2022-02-21 13:28:05','172.16.154.29','super admin','2022-08-25 15:06:09','172.16.154.39',1,1,1),(46,NULL,'Reports',1,'',NULL,'fas fa-table',13,1,'super admin','2022-02-23 14:33:07','172.16.154.39','super admin','2022-08-25 15:06:09','172.16.154.39',1,1,1),(47,46,'Agent Performance Report',2,'/report/agent-performance-report',NULL,'fas fa-table',3,1,'super admin','2022-02-23 14:58:30','172.16.154.39','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(48,4,'Add Outbound Campaign',2,'/campaign/add-outbound',NULL,'fas fa-table',14,1,'super admin','2022-03-08 16:37:29','172.16.154.39','super admin','2022-03-14 10:43:38','172.16.154.39',2,1,1),(49,NULL,'Operators',1,'/operator/index',NULL,'fas fa-toolbox',15,1,'super admin','2022-03-21 10:51:02','172.16.154.39','super admin','2022-08-25 15:06:09','172.16.154.39',1,1,1),(50,46,'Network Lag Report',2,'/report/network-lag-report',NULL,'fas fa-chart-area',4,1,'super admin','2022-03-24 17:37:41','172.16.154.39','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(51,46,'ACD Report',2,'/report/acd-report',NULL,'fas fa-table',5,1,'super admin','2022-03-28 18:59:02','172.16.154.39','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(52,64,'Dispositions',2,'/disposition/index',NULL,'fas fa-user',1,1,'super admin','2022-04-15 12:59:33','172.16.154.41','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(53,NULL,'QMS',1,'',NULL,'fas fa-user',16,1,'super admin','2022-04-22 11:13:59','172.16.154.39','super admin','2022-05-20 16:24:40','172.16.154.39',2,1,1),(54,53,'Administration',2,'',NULL,'fas fa-user',17,1,'super admin','2022-04-22 11:14:45','172.16.154.39','super admin','2022-04-22 11:14:45',NULL,1,1,1),(55,64,'Lead Upload',2,'/lead/upload',NULL,'fas fa-upload',2,1,'super admin','2022-04-27 11:30:30','172.16.154.39','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(56,NULL,'Campaign Configuration',1,'',NULL,'fas fa-table',19,1,'super admin','2022-05-04 11:56:55','172.16.154.39','super admin','2022-05-04 11:57:15','172.16.154.39',2,1,1),(57,64,'Batches',2,'/lead/index',NULL,'fas fa-table',3,1,'super admin','2022-05-04 11:58:26','172.16.154.39','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(58,64,'CRM',2,'/crm/index',NULL,'fas fa-table',4,1,'super admin','2022-05-04 13:11:49','172.16.154.39','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(59,2,'Ram',2,'/break/index',NULL,'',21,1,'super admin','2022-05-23 15:21:11','172.16.154.52','super admin','2022-05-23 15:33:54','172.16.154.52',2,1,1),(60,2,'fred',3,'/break/view',NULL,'',22,1,'super admin','2022-05-23 15:29:28','172.16.154.52','super admin','2022-05-23 15:31:17','172.16.154.52',2,1,1),(61,NULL,'dews',3,'/break/view',NULL,'fas fa-table',22,1,'super admin','2022-05-23 15:33:12','172.16.154.52','super admin','2022-05-23 15:33:12',NULL,2,1,1),(62,46,'Agent Login Report',2,'/report/agent-login-report',NULL,'fas fa-table',6,1,'super admin','2022-07-05 17:36:00','192.168.253.1','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(63,46,'CRM History Report',2,'/report/crm-history-report',NULL,'fas fa-table',7,1,'super admin','2022-08-03 12:04:20','172.16.154.52','super admin','2022-08-25 15:06:10','172.16.154.39',1,1,1),(64,NULL,'Campaign Settings',1,'',NULL,'fas fa-table',4,1,'super admin','2022-08-25 15:04:26','172.16.154.39','super admin','2022-08-25 15:06:37','172.16.154.39',1,1,1);
/*!40000 ALTER TABLE `vaani_menu` ENABLE KEYS */;
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
