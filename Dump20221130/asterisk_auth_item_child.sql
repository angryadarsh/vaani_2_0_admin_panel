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
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` VALUES ('admin','/*'),('agent','/*'),('manager','/*'),('qa','/*'),('super admin','/*'),('supervisor','/*'),('support','/*'),('tl','/*'),('admin','/admin/*'),('super admin','/debug/*'),('super admin','/debug/default/*'),('super admin','/debug/default/db-explain'),('super admin','/debug/default/download-mail'),('super admin','/debug/default/index'),('super admin','/debug/default/toolbar'),('super admin','/debug/default/view'),('super admin','/debug/user/*'),('super admin','/debug/user/reset-identity'),('super admin','/debug/user/set-identity'),('super admin','/gii/*'),('super admin','/gii/default/*'),('super admin','/gii/default/action'),('super admin','/gii/default/diff'),('super admin','/gii/default/index'),('super admin','/gii/default/preview'),('super admin','/gii/default/view'),('super admin','/rbac/*'),('super admin','/rbac/assignment/*'),('super admin','/rbac/assignment/assign'),('super admin','/rbac/assignment/index'),('super admin','/rbac/assignment/remove'),('super admin','/rbac/assignment/view'),('super admin','/rbac/permission/*'),('super admin','/rbac/permission/assign'),('super admin','/rbac/permission/create'),('super admin','/rbac/permission/delete'),('super admin','/rbac/permission/index'),('super admin','/rbac/permission/remove'),('super admin','/rbac/permission/update'),('super admin','/rbac/permission/view'),('super admin','/rbac/role/*'),('super admin','/rbac/role/assign'),('super admin','/rbac/role/create'),('super admin','/rbac/role/delete'),('super admin','/rbac/role/index'),('super admin','/rbac/role/remove'),('super admin','/rbac/role/update'),('super admin','/rbac/role/view'),('super admin','/rbac/route/*'),('super admin','/rbac/route/assign'),('super admin','/rbac/route/index'),('super admin','/rbac/route/refresh'),('super admin','/rbac/route/remove'),('super admin','/rbac/rule/*'),('super admin','/rbac/rule/create'),('super admin','/rbac/rule/delete'),('super admin','/rbac/rule/index'),('super admin','/rbac/rule/update'),('super admin','/rbac/rule/view'),('admin','/site/*'),('manager','/site/*'),('super admin','/site/*'),('supervisor','/site/*'),('manager','/site/agent-logout'),('super admin','/site/agent-logout'),('supervisor','/site/agent-logout'),('tl','/site/agent-logout'),('super admin','/site/error'),('super admin','/site/index'),('super admin','/site/login'),('super admin','/site/logout'),('super admin','/site/monitoring');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
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
