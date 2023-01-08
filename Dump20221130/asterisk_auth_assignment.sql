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
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('admin','100',1645702316),('admin','103',1646126523),('admin','106',1646140549),('admin','108',1646205863),('admin','114',1646642392),('admin','116',1646654561),('admin','119',1646718464),('admin','122',1646733685),('admin','124',1647264963),('admin','126',1647318880),('admin','129',1647323350),('admin','131',1647328022),('admin','134',1647523023),('admin','136',1648193971),('admin','137',1648193997),('admin','138',1648203100),('admin','172',1649653655),('admin','178',1649834593),('admin','180',1650432306),('admin','186',1650434210),('admin','188',1650447085),('admin','189',1650448327),('admin','191',1651039499),('admin','192',1651721130),('admin','195',1652080032),('admin','196',1653994748),('admin','204',1655292173),('admin','215',1655892274),('admin','217',1655894444),('admin','220',1656584837),('admin','224',1657611749),('admin','226',1658747808),('admin','227',1658754692),('admin','231',1659417836),('admin','237',1659951953),('admin','247',1660822639),('admin','283',1662531423),('admin','297',1669628003),('admin','33',1641984737),('admin','34',1641984715),('admin','35',1641984703),('admin','38',1641965280),('admin','44',1643273147),('admin','49',1643289421),('admin','52',1643619808),('admin','57',1643806412),('admin','58',1643807516),('admin','66',1643879594),('admin','69',1643886878),('admin','73',1643895847),('admin','76',1643951329),('admin','81',1644211301),('admin','83',1644212208),('admin','88',1644229297),('admin','89',1644301583),('admin','90',1644391544),('admin','94',1645613468),('admin','97',1645617057),('agent','101',1645703704),('agent','102',1645703740),('agent','105',1646129136),('agent','107',1646140833),('agent','109',1646627611),('agent','110',1646628214),('agent','111',1646630251),('agent','112',1646630385),('agent','113',1646637252),('agent','115',1646642631),('agent','117',1646657508),('agent','118',1646657546),('agent','120',1646719238),('agent','121',1646719270),('agent','123',1646734088),('agent','125',1647265072),('agent','127',1647319438),('agent','128',1647319477),('agent','130',1647325937),('agent','132',1647328735),('agent','133',1647337120),('agent','135',1647936556),('agent','139',1648208405),('agent','141',1648458064),('agent','142',1648458095),('agent','143',1648458125),('agent','144',1648458151),('agent','145',1648458173),('agent','146',1648458195),('agent','147',1648458234),('agent','148',1648458483),('agent','149',1648458521),('agent','151',1648531927),('agent','152',1648532019),('agent','153',1648532047),('agent','154',1648532076),('agent','155',1648532146),('agent','156',1648532191),('agent','157',1648532227),('agent','158',1648532268),('agent','160',1648724864),('agent','161',1648724898),('agent','162',1648724967),('agent','163',1648724999),('agent','164',1648725024),('agent','165',1648725049),('agent','166',1648725081),('agent','167',1648725113),('agent','168',1648725139),('agent','171',1648808265),('agent','174',1649672937),('agent','175',1649672971),('agent','176',1649679532),('agent','177',1649679762),('agent','179',1649836094),('agent','182',1650432581),('agent','183',1650432620),('agent','184',1650432659),('agent','185',1650432944),('agent','187',1650441315),('agent','190',1650534179),('agent','193',1651721671),('agent','194',1651837249),('agent','199',1653999556),('agent','200',1653999612),('agent','201',1653999671),('agent','202',1653999848),('agent','203',1653999929),('agent','206',1655293152),('agent','207',1655293219),('agent','208',1655293335),('agent','209',1655456039),('agent','210',1655456070),('agent','211',1655706582),('agent','212',1655798029),('agent','213',1655800593),('agent','214',1655800941),('agent','216',1655892308),('agent','218',1655895120),('agent','219',1656506742),('agent','221',1656671296),('agent','222',1657259504),('agent','223',1657259533),('agent','225',1657689038),('agent','228',1659098662),('agent','229',1659098687),('agent','230',1659349333),('agent','232',1659590260),('agent','233',1659590385),('agent','234',1659590498),('agent','235',1659590551),('agent','236',1659590675),('agent','238',1659952254),('agent','239',1659952298),('agent','240',1660198315),('agent','241',1660282414),('agent','242',1660713703),('agent','243',1660713747),('agent','244',1660713776),('agent','245',1660713864),('agent','246',1660713914),('agent','250',1660824505),('agent','251',1660824661),('agent','252',1660825105),('agent','254',1661145284),('agent','255',1661148488),('agent','256',1661154327),('agent','257',1661166435),('agent','258',1661174669),('agent','259',1661326302),('agent','262',1661421275),('agent','263',1661421307),('agent','264',1661493618),('agent','265',1661752358),('agent','278',1662109968),('agent','284',1662553502),('agent','285',1662959592),('agent','286',1662959991),('agent','287',1662960125),('agent','288',1662960777),('agent','290',1662962085),('agent','295',1669370677),('agent','296',1669384365),('agent','300',1669629614),('agent','302',1669718951),('agent','31',1641984750),('agent','32',1641893705),('agent','36',1641893982),('agent','40',1641984675),('agent','43',1643008265),('agent','45',1643275389),('agent','46',1643285532),('agent','47',1643285858),('agent','48',1643286098),('agent','50',1643290818),('agent','51',1643290872),('agent','53',1643624713),('agent','54',1643712590),('agent','55',1643794841),('agent','56',1643795236),('agent','59',1643808136),('agent','60',1643808176),('agent','61',1643808221),('agent','62',1643809168),('agent','63',1643809240),('agent','64',1643809972),('agent','65',1643865552),('agent','67',1643881083),('agent','68',1643881122),('agent','70',1643887320),('agent','71',1643887476),('agent','74',1643896241),('agent','75',1643896283),('agent','77',1643951791),('agent','78',1643951891),('agent','79',1643960599),('agent','80',1643969087),('agent','82',1644211482),('agent','85',1644213972),('agent','86',1644214017),('agent','87',1644214047),('agent','92',1644557448),('agent','93',1644995916),('agent','95',1645613515),('agent','96',1645613542),('agent','98',1645618745),('agent','99',1645618822),('manager','197',1653995986),('manager','248',1660824179),('manager','260',1661421211),('manager','266',1661843584),('manager','276',1662098105),('manager','280',1662359637),('manager','281',1662466795),('manager','292',1669369044),('manager','293',1669369306),('manager','298',1669629502),('manager','72',1643887532),('super admin','1',1639396043),('super admin','39',1641984565),('supervisor','170',1648725682),('supervisor','173',1649672900),('supervisor','181',1650432525),('supervisor','198',1653998976),('supervisor','205',1655292863),('supervisor','249',1660824252),('supervisor','253',1660903325),('supervisor','261',1661421240),('supervisor','267',1661843662),('supervisor','268',1661850111),('supervisor','269',1661852517),('supervisor','270',1661852658),('supervisor','271',1661852925),('supervisor','272',1661868078),('supervisor','273',1661868296),('supervisor','274',1661868450),('supervisor','275',1661868533),('supervisor','277',1662109878),('supervisor','279',1662358137),('supervisor','282',1662466841),('supervisor','294',1669370632),('supervisor','299',1669629559),('tl','41',1642662927),('tl','42',1642663535);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 10:45:55
