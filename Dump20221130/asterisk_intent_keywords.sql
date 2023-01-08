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
-- Table structure for table `intent_keywords`
--

DROP TABLE IF EXISTS `intent_keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `intent_keywords` (
  `SrNo` int(8) NOT NULL AUTO_INCREMENT,
  `flow_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `intent_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `intent` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(800) CHARACTER SET utf8 DEFAULT NULL,
  KEY `SrNo` (`SrNo`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intent_keywords`
--

LOCK TABLES `intent_keywords` WRITE;
/*!40000 ALTER TABLE `intent_keywords` DISABLE KEYS */;
INSERT INTO `intent_keywords` VALUES (3,'kotak_bank','talking','positive','हो रही है#बोल रहा हूं#जिओ रही है#हां हो रही है#सो रही है#हां जी#मैं ही#मैं हूं#स्पीकिंग#ओरिया#अंजी#मैं#भांजी#बिल्कुल#बोलिये#बोलिए#हैल्लो#हैलो#हेल्लो#बोलो#बोलों#बोले#जस#हांजी#हाँजी#व्यास#एक्स#जिया#दीया#सेक्स#केस#यश#गैस#ओके#गैस#यह#या#यस#हाय#हेलो#यह#येस#हाँ#हां#हा#जी#था#हो#हो#या'),(4,'kotak_bank','talking','negative','रॉन्ग नंबर#नहीँ#नहीं#नही#नहि#नाही#ना#नाह#नो#नोप#लो#नई#नॉट'),(5,'kotak_bank','have_pancard','positive','पैन कार्ड है#उपलब्ध है#अवेलेबल है#हां दोनों है#आधार कार्ड है#बोलिये#व्यास#बोलिए#अंजी#मैं#भांजी#बिल्कुल है#हां जी#बोलों#बोलो#बोले#जस#यह#हांजी#हाँजी#यश#गैस#ओके#एक्स#जिया#सेक्स#केसया#यस#येस#हाँ#हां#हा#जी#था#या'),(6,'kotak_bank','have_pancard','negative','उपलब्ध नहीं है#नहीँ#नहीं#नही#नहि#नाही#ना#नाह#नो#नोप#लो#नई#नॉट'),(9,'kotak_bank','pan_not_applied','positive','बना नहीं#अप्लाय नहीं#एप्लाय नहीं#बना नही#नहीं किया#अप्लाई नहीं#अप्लाई नहीं किया है#नॉट अप्लाई#नॉट अप्लाइड#रिप्लाई नहीं'),(10,'kotak_bank','pan_not_applied','negative','नहीं है#नही है#घर पर है#बाहर हूं#नई है#पैन कार्ड है#उपलब्ध है#अवेलेबल है#हां दोनों है#आधार कार्ड है#मेरे पास है#मेरे पास सब कुछ है#मेरे पास पैन कार्ड भी है और आधार कार्ड भी है#मेरे पास पैन कार्ड आधार कार्ड आ गई है#दोनों है#आहे'),(11,'kotak_bank','age_18_plus','positive','मेरी उम्र 18 साल के ऊपर है#18 से अधिक है#अधिक है#ज्यादा है#हां जी#हांजी#हाँजी#अंजी#मैं#भांजी#बोलिये#बोलिए#बोलों#बोलो#बोले#हाँ#हां#यस#येस#हाय#यश#गैस#ओके#एक्स#जिया#सेक्स#केस#हा#जी#था#या#'),(12,'kotak_bank','age_18_plus','negative','नहीँ#नहीं#नही#नहि#नाही#ना#नाह#नो#नोप#लो#नई#नॉट'),(13,'kotak_bank','agent_transfer','positive','कीजिए#आ कर सकता हूं#ठीक है#ट्रांसफर कर सकते हैं#ट्रांसफर कीजिए#कॉल ट्रांसफर कीजिए#कर सकते हो#ठीक है#अंजी#मैं#भांजी#जरूर#बिल्कुल#करवा दीजिए#कनेक्ट#सकते हो#कर सकती हो#कर सकते है#कर दो#ट्रांसफर करो#ठीक है#कर दीजिए#सकते हैं#हां जी#हाँजी#हांजी#हाँ#हां#जस#यह#या#यस#येस#हा#जी#यश#गैस#ओके#था#या#एक्स#जिया#सेक्स#करो#प्लीज#केस#व्यास'),(14,'kotak_bank','agent_transfer','negative','बिजी हो मैं#मत कीजिए#नहीँ#नहीं#नही#नहि#नाही#ना#नाह#नो#नोप#लो#नई#नॉट'),(15,'metropolis','welcome','positive','test#taste'),(16,'metropolis','which_test','positive','covid#corona'),(17,'metropolis','assist_you','positive','for me#myself#son#daughter#dad#mom#mummy#papa#pappa#brother#sister#mother#uncle#aunty#father'),(18,'metropolis','reason_for_test','positive','flight#flying#travel#surgery#exam#symptom#rejoin'),(19,'metropolis','which_service','positive','centre#center#home'),(20,'metropolis','appointment','positive','day after tomorrow#today#tomorrow'),(21,'metropolis','confirm_visit','positive','yes#yeah#ya#sure#okay#ok'),(22,'metropolis','anything_else','positive','nope#no#naa'),(23,'metropolis','surgery_report_valid','positive','24#48#twenty four#fourty eight#forty eight'),(24,'metropolis','check_appointment','positive','yes#yeah#ya#sure#okay#ok'),(25,'metropolis','center_details','positive','ok#okay#centre#center'),(26,'metropolis','share_details_sms','positive','yes#yeah#ya#sure#okay#ok'),(27,'metropolis','number_not_dnd','positive','nope#no#naa'),(28,'kotak_mutualfund','talking','positive','हो रही है#बोल रहा हूं#जिओ रही है#हां हो रही है#सो रही है#हां जी#मैं ही#मैं हूं#स्पीकिंग#ओरिया#व्यास#अंजी#मैं#भांजी#बिल्कुल#बोलिये#बोलिए#हैल्लो#हैलो#हेल्लो#बोलो#बोलों#बोले#जस#हांजी#हाँजी#एक्स#जिया#दीया#सेक्स#केस#यश#गैस#ओके#गैस#यह#या#यस#हाय#हेलो#यह#येस#हाँ#हां#हा#जी#था#हो#हो#या'),(29,'kotak_mutualfund','talking','negative','नहीँ#नहीं#नही#नहि#नाही#ना#नाह#नो#नोप#लो#नई#रॉन्ग नंबर#नॉट'),(30,'kotak_mutualfund','help','positive','मदद कर#मदद कीजिए#हेल्प कर सकते#कर सकते हैं#हां जी#बोलिये#बोलिए#बिल्कुल#जरूर#व्यास#सेक्स#प्लीज#केस#बोलों#अंजी#मैं#भांजी#बोलो#बोले#जस#यह#हाँजी#हांजी#यश#गैस#ओके#यस#येस#एक्स#जिया#हाँ#हां#हा#जी#था#या'),(31,'kotak_mutualfund','help','negative','नहीँ#नहीं#नही#नहि#नाही#ना#नाह#नो#नोप#लो#नई#नॉट'),(32,'kotak_mutualfund','agent_transfer','positive','कीजिए#आ कर सकता हूं#ठीक है#ट्रांसफर कर सकते हैं#ट्रांसफर कीजिए#कॉल ट्रांसफर कीजिए#कर सकते हो#ठीक है#व्यास#अंजी#मैं#भांजी#जरूर#बिल्कुल#करवा दीजिए#कनेक्ट#सकते हो#कर सकती हो#कर सकते है#कर दो#ट्रांसफर करो#ठीक है#कर दीजिए#सकते हैं#हां जी#हाँजी#हांजी#हाँ#हां#जस#यह#या#यस#येस#हा#जी#यश#गैस#ओके#था#या#एक्स#जिया#सेक्स#करो#प्लीज#केस'),(33,'kotak_mutualfund','agent_transfer','negative','बिजी हो मैं#मत कीजिए#नहीँ#नहीं#नही#नहि#नाही#ना#नाह#नो#नोप#लो#नई#नॉट'),(34,'kotak_bank','test','positive','0'),(35,'kotak_bank','test','negative','लो#उपलब्ध नहीं है#नईनहीं#नही#नहि#नाही#नहीँ#ना#नाह#नो#नोप#लो#उपलब्ध नहीं है#नई#लो#नई'),(36,'account_opening_hin','talking','positive','हो रही है#बोल रहा हूं#जिओ रही है#हां हो रही है#सो रही है#हां जी#मैं ही#मैं हूं#स्पीकिंग#अंजी#ओरिया#व्यास#मैं#भांजी#बिल्कुल#बोलिये#बोलिए#हैल्लो#हैलो#हेल्लो#बोलो#बोलों#बोले#जस#हांजी#हाँजी#एक्स#जिया#दीया#सेक्स#केस#यश#गैस#ओके#गैस#यह#या#यस#हाय#हेलो#यह#येस#हाँ#हां#हा#जी#था#हो#हो#या'),(37,'account_opening_hin','talking','negative','नहीँ#नहीं#नही#नहि#नाही#ना#नाह#नो#नोप#लो#नई#रॉन्ग नंबर#नॉट'),(38,'account_opening_hin','help','positive','मदद कर#मदद कीजिए#हेल्प कर सकते#कर सकते हैं#हां जी#बोलिये#बोलिए#बिल्कुल#जरूर#व्यास#सेक्स#प्लीज#केस#बोलों#अंजी#मैं#भांजी#बोलो#बोले#जस#यह#हाँजी#हांजी#यश#गैस#ओके#यस#येस#एक्स#जिया#हाँ#हां#हा#जी#था#या'),(39,'account_opening_hin','help','negative','नहीँ#नहीं#नही#नहि#नाही#ना#नाह#नो#नोप#लो#नई#नॉट'),(40,'account_opening_hin','agent_transfer','positive','कीजिए#आ कर सकता हूं#ठीक है#ट्रांसफर कर सकते हैं#ट्रांसफर कीजिए#कॉल ट्रांसफर कीजिए#कर सकते हो#ठीक है#अंजी#मैं#भांजी#जरूर#बिल्कुल#करवा दीजिए#कनेक्ट#सकते हो#कर सकती हो#कर सकते है#कर दो#ट्रांसफर करो#ठीक है#कर दीजिए#सकते हैं#हां जी#हाँजी#हांजी#हाँ#हां#जस#यह#या#यस#येस#हा#जी#यश#गैस#ओके#था#या#एक्स#जिया#सेक्स#करो#प्लीज#केस'),(41,'account_opening_hin','agent_transfer','negative','बिजी हो मैं#मत कीजिए#नहीँ#नहीं#नही#नहि#नाही#ना#नाह#नो#नोप#लो#नई#नॉट'),(42,'account_opening_eng','talking','positive','obviously#proceed#ji spelling#go ahead#yes#yeah#ya#okay#ok#offcourse#sure#speaking#of course#yash#yah#yo#yup#han#haan#hello#shark#gas#press#chess#ho#jiya#right'),(43,'account_opening_eng','talking','negative','nope#now#no#naa#nahi#not at all#naah#never#nahin#not'),(44,'account_opening_eng','help','positive','please process#interesting#obviously#proceed#interested#ji spelling#go ahead#yes#yeah#ya#yah#yup#okay#ok#offcourse#sure#of course#yash#han#haan#shark#gas#press#chess#ho#jiya#right#done'),(45,'account_opening_eng','help','negative','nope#now#no#naa#nahi#not at all#naah#never#nahin#not'),(46,'account_opening_eng','agent_transfer','positive','obviously#proceed#transfer my call#ji spelling#go ahead#yah#yup#yes#yeah#ya#okay#ok#offcourse#sure#why not#of course#yash#han#haan#shark#gas#press#chess#ho#jiya#done'),(47,'account_opening_eng','agent_transfer','negative','nope#now#no#naa#nahi#not at all#naah#never#nahin#not'),(48,'account_opening_eng','rebuttal2','negative','do not call#already opened#not interested#busy#dint understand#didn\'t understand#busy#where are you calling#fraud call#contact detail#information#features#charges#understand'),(49,'account_opening_eng','rebuttal1','negative','do not call#already opened#not interested#busy#dint understand#didn\'t understand#busy#repeat#where are you calling#fraud call#contact detail#understand'),(50,'appointment_reminder','confirmation','positive','obviously#proceed#ji spelling#go ahead#yes#yeah#ya#okay#ok#offcourse#sure#speaking#of course#yash#yah#yo#yup#han#haan#hello#shark#gas#press#chess#ho#jiya#right'),(51,'appointment_reminder','confirmation','negative','nope#now#no#naa#nahi#not at all#naah#never#nahin#not');
/*!40000 ALTER TABLE `intent_keywords` ENABLE KEYS */;
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
