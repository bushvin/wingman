-- MySQL dump 10.13  Distrib 5.1.63, for debian-linux-gnu (x86_64)
--
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `##_auth_ace`
--

DROP TABLE IF EXISTS `##_auth_ace`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_auth_ace` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `object` varchar(254) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `list` tinyint(1) NOT NULL DEFAULT '0',
  `create` tinyint(1) NOT NULL DEFAULT '0',
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  `modify` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `##_auth_ace`
--

LOCK TABLES `##_auth_ace` WRITE;
/*!40000 ALTER TABLE `##_auth_ace` DISABLE KEYS */;
INSERT INTO `##_auth_ace` VALUES (1,0,0,'ENTITY_FULL','','entity',1,1,1,1,1),(2,0,0,'INVENTORY_FULL','','inventory',1,1,1,1,1),(3,0,0,'OFFER_FULL','','offer',1,1,1,1,1),(4,0,0,'TRANSACTION_FULL','','transaction',1,1,1,1,1),(5,0,0,'USER_FULL','','user',1,1,1,1,1),(6,0,0,'ROLE_FULL','','role',1,1,1,1,1),(7,0,0,'ENTITY_READ','','entity',1,1,0,0,0),(8,0,0,'INVENTORY_READ','','inventory',1,1,0,0,0),(9,0,0,'OFFER_READ','','offer',1,1,0,0,0),(10,0,0,'TRANSACTION_READ','','transaction',1,1,0,0,0),(11,0,0,'USER_READ','','user',1,1,0,0,0),(12,0,0,'ROLE_READ','','role',1,1,0,0,0),(13,0,0,'ENTITY_MODIFY','','entity',0,0,0,0,1),(14,0,0,'INVENTORY_MODIFY','','inventory',0,0,0,0,1),(15,0,0,'OFFER_MODIFY','','offer',0,0,0,0,1),(16,0,0,'TRANSACTION_MODIFY','','transaction',0,0,0,0,1),(17,0,0,'USER_MODIFY','','user',0,0,0,0,1),(18,0,0,'ROLE_MODIFY','','role',0,0,0,0,1),(19,0,0,'ENTITY_DELETE','','entity',0,0,0,1,0),(20,0,0,'INVENTORY_DELETE','','inventory',0,0,0,1,0),(21,0,0,'OFFER_DELETE','','offer',0,0,0,1,0),(22,0,0,'TRANSACTION_DELETE','','transaction',0,0,0,1,0),(23,0,0,'USER_DELETE','','user',0,0,0,1,0),(24,0,0,'ROLE_DELETE','','role',0,0,0,1,0),(25,0,0,'ENTITY_CREATE','','entity',0,0,1,0,0),(26,0,0,'INVENTORY_CREATE','','inventory',0,0,1,0,0),(27,0,0,'OFFER_CREATE','','offer',0,0,1,0,0),(28,0,0,'TRANSACTION_CREATE','','transaction',0,0,1,0,0),(29,0,0,'USER_CREATE','','user',0,0,1,0,0),(30,0,0,'ROLE_CREATE','','role',0,0,1,0,0),(31,0,0,'CURRENCY_CREATE','','currency',0,0,1,0,0),(32,0,0,'CURRENCY_DELETE','','currency',0,0,0,1,0),(33,0,0,'CURRENCY_FULL','','currency',1,1,1,1,1),(34,0,0,'CURRENCY_MODIFY','','currency',0,0,0,0,1),(35,0,0,'CURRENCY_READ','','currency',1,1,0,0,0),(36,0,0,'VAT_CREATE','','vat',0,0,1,0,0),(37,0,0,'VAT_DELETE','','vat',0,0,0,1,0),(38,0,0,'VAT_FULL','','vat',1,1,1,1,1),(39,0,0,'VAT_MODIFY','','vat',0,0,0,0,1),(40,0,0,'VAT_READ','','vat',1,1,0,0,0),(41,0,0,'LANGUAGE_CREATE','','language',0,0,1,0,0),(42,0,0,'LANGUAGE_DELETE','','language',0,0,0,1,0),(43,0,0,'LANGUAGE_FULL','','language',1,1,1,1,1),(44,0,0,'LANGUAGE_MODIFY','','language',0,0,0,0,1),(45,0,0,'LANGUAGE_READ','','language',1,1,0,0,0),(46,0,0,'CRONTAB_FULL','','crontab',1,1,1,1,1),(47,0,0,'CRONTAB_READ','','crontab',1,1,0,0,0),(48,0,0,'CRONTAB_MODIFY','','crontab',0,0,0,0,1),(49,0,0,'CRONTAB_DELETE','','crontab',0,0,0,1,0),(50,0,0,'CRONTAB_CREATE','','crontab',0,0,1,0,0),(51,0,0,'CUSTOMER_FULL','','customer',1,1,1,1,1),(52,0,0,'CUSTOMER_READ','','customer',1,1,0,0,0),(53,0,0,'CUSTOMER_MODIFY','','customer',0,0,0,0,1),(54,0,0,'CUSTOMER_DELETE','','customer',0,0,0,1,0),(55,0,0,'CUSTOMER_CREATE','','customer',0,0,1,0,0),(56,0,0,'ENTITYTYPE_FULL','','entitytype',1,1,1,1,1),(57,0,0,'ENTITYTYPE_READ','','entitytype',1,1,0,0,0),(58,0,0,'ENTITYTYPE_MODIFY','','entitytype',0,0,0,0,1),(59,0,0,'ENTITYTYPE_DELETE','','entitytype',0,0,0,1,0),(60,0,0,'ENTITYTYPE_CREATE','','entitytype',0,0,1,0,0);
/*!40000 ALTER TABLE `##_auth_ace` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `##_auth_acl`
--

DROP TABLE IF EXISTS `##_auth_acl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_auth_acl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `auth_role_id` int(11) NOT NULL DEFAULT '-1',
  `auth_ace_id` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `##_auth_acl`
--

LOCK TABLES `##_auth_acl` WRITE;
/*!40000 ALTER TABLE `##_auth_acl` DISABLE KEYS */;
INSERT INTO `##_auth_acl` VALUES (1,0,0,1,1),(2,0,0,1,2),(3,0,0,1,3),(4,0,0,1,4),(5,0,0,1,5),(6,0,0,1,6),(7,0,0,1,33),(8,0,0,1,38),(9,0,0,1,43),(10,0,0,1,46),(11,0,0,2,1),(12,0,0,2,2),(13,0,0,2,3),(14,0,0,2,4),(15,0,0,3,46),(16,0,0,4,1),(17,0,0,4,2),(18,0,0,4,3),(19,0,0,4,4),(20,0,0,4,33),(21,0,0,4,38),(22,0,0,4,46),(23,0,0,1,51),(24,0,0,1,56),(25,0,0,4,56);
/*!40000 ALTER TABLE `##_auth_acl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `##_auth_role`
--

DROP TABLE IF EXISTS `##_auth_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_auth_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `##_auth_role`
--

LOCK TABLES `##_auth_role` WRITE;
/*!40000 ALTER TABLE `##_auth_role` DISABLE KEYS */;
INSERT INTO `##_auth_role` VALUES (1,0,0,'root','Super Administrative rights'),(2,0,0,'User','Default user role'),(3,0,0,'Cronjob','Run Cronjobs'),(4,0,0,'Space Admin','');
/*!40000 ALTER TABLE `##_auth_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `##_auth_token`
--

DROP TABLE IF EXISTS `##_auth_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_auth_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `auth_user_id` int(11) NOT NULL DEFAULT '-1',
  `token` varchar(64) NOT NULL DEFAULT '',
  `ip_address` varchar(45) NOT NULL DEFAULT '0.0.0.0',
  `issued` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_used` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timeout` int(11) NOT NULL DEFAULT '600',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `##_auth_user`
--

DROP TABLE IF EXISTS `##_auth_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_auth_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(254) NOT NULL DEFAULT 'internal',
  `login` varchar(255) NOT NULL DEFAULT '_UNKNOWN',
  `passphrase` varchar(64) NOT NULL DEFAULT '',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `locale_id` int(11) NOT NULL,
  `token_timeout` int(11) NOT NULL DEFAULT '600',
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(254) NOT NULL,
  `email` varchar(254) NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `##_auth_user`
--

LOCK TABLES `##_auth_user` WRITE;
/*!40000 ALTER TABLE `##_auth_user` DISABLE KEYS */;
INSERT INTO `##_auth_user` VALUES (0,0,0,'internal','root','74cc1c60799e0a786ac7094b532f01b1',2,2,600,'0000-00-00 00:00:00','rootus rootus','root@root',1000);
/*!40000 ALTER TABLE `##_auth_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `##_auth_user_role`
--

DROP TABLE IF EXISTS `##_auth_user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_auth_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `auth_user_id` int(11) NOT NULL DEFAULT '-1',
  `auth_role_id` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `##_auth_user_role`
--

LOCK TABLES `##_auth_user_role` WRITE;
/*!40000 ALTER TABLE `##_auth_user_role` DISABLE KEYS */;
INSERT INTO `##_auth_user_role` VALUES (1,0,0,0,1);
/*!40000 ALTER TABLE `##_auth_user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `##_customer`
--

DROP TABLE IF EXISTS `##_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '_UNKNOWN',
  `space` varchar(255) NOT NULL DEFAULT '_UNKNOWN',
  `css` varchar(255) NOT NULL DEFAULT '_UNKNOWN',
  `offer_reference_auto_increment` tinyint(4) NOT NULL DEFAULT '0',
  `offer_reference_prefix` varchar(8) NOT NULL,
  `transaction_uid_auto_increment` tinyint(4) NOT NULL DEFAULT '1',
  `transaction_uid_prefix` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `##_customer`
--

LOCK TABLES `##_customer` WRITE;
/*!40000 ALTER TABLE `##_customer` DISABLE KEYS */;
INSERT INTO `##_customer` VALUES (1000,0,0,'Default','default','default',0,'',1,'');
/*!40000 ALTER TABLE `##_customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `##_language`
--

DROP TABLE IF EXISTS `##_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '_UNKNOWN',
  `short` varchar(5) NOT NULL DEFAULT '_UNK',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `##_language`
--

LOCK TABLES `##_language` WRITE;
/*!40000 ALTER TABLE `##_language` DISABLE KEYS */;
INSERT INTO `##_language` VALUES (-1,0,0,'_UNKNOWN','_UNK'),(1,0,0,'Nederlands (Vlaams)','nl-be'),(2,0,0,'English (UK)','en-uk');
/*!40000 ALTER TABLE `##_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `##_locale`
--

DROP TABLE IF EXISTS `##_locale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_locale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '_UNKNOWN',
  `decimal_separator` varchar(1) NOT NULL DEFAULT ',',
  `thousand_separator` varchar(4) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `##_locale`
--

LOCK TABLES `##_locale` WRITE;
/*!40000 ALTER TABLE `##_locale` DISABLE KEYS */;
INSERT INTO `##_locale` VALUES (1,0,0,'nl-be',',',''),(2,0,0,'en-uk','.',',');
/*!40000 ALTER TABLE `##_locale` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-09-18 10:55:48
