-- MySQL dump 10.13  Distrib 5.1.63, for debian-linux-gnu (x86_64)
--
-- Host: mysql009.hosting.combell.com    Database: ID124930_app
-- ------------------------------------------------------
-- Server version	5.5.11

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
-- Table structure for table `##_default_advanced`
--

DROP TABLE IF EXISTS `##_default_advanced`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_advanced` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `disabled` tinyint(4) NOT NULL DEFAULT '0',
  `view` varchar(254) NOT NULL,
  `name` varchar(254) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '1000',
  `description` text NOT NULL,
  `type` enum('internal','single_b','single_f','multi_f','multi_b') NOT NULL DEFAULT 'single_b',
  `css` varchar(32) NOT NULL,
  `include` varchar(254) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_crontab`
--

DROP TABLE IF EXISTS `##_default_crontab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_crontab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `disabled` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(254) NOT NULL,
  `description` text NOT NULL,
  `recurrence` varchar(16) NOT NULL,
  `export_to` varchar(16) NOT NULL,
  `connect_info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_crontab_log`
--

DROP TABLE IF EXISTS `##_default_crontab_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_crontab_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `disabled` tinyint(4) NOT NULL DEFAULT '0',
  `crontab_id` int(11) NOT NULL DEFAULT '-1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `exit_status` int(11) NOT NULL DEFAULT '0',
  `output` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_currency`
--

DROP TABLE IF EXISTS `##_default_currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '_UNKNOWN',
  `short` varchar(5) NOT NULL DEFAULT '_UNK',
  `sign` char(1) NOT NULL DEFAULT '_',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_entity`
--

DROP TABLE IF EXISTS `##_default_entity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_entity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `salutation` varchar(254) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0',
  `entity_type_id` int(11) NOT NULL DEFAULT '-1',
  `tel` varchar(32) NOT NULL DEFAULT '',
  `fax` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `www` varchar(255) NOT NULL DEFAULT '',
  `VAT_number` varchar(255) NOT NULL DEFAULT '',
  `default_currency_id` int(11) NOT NULL DEFAULT '-1',
  `default_vat_id` int(11) NOT NULL DEFAULT '-1',
  `managed` tinyint(1) NOT NULL DEFAULT '0',
  `reference_prefix` varchar(8) NOT NULL DEFAULT '',
  `correspondence_language_id` int(11) NOT NULL DEFAULT '1',
  `notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_entity_address`
--

DROP TABLE IF EXISTS `##_default_entity_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_entity_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `entity_id` int(11) NOT NULL DEFAULT '-1',
  `address_type` varchar(255) NOT NULL DEFAULT '_FACTURATION',
  `attn` varchar(254) NOT NULL,
  `invoicing` tinyint(4) NOT NULL DEFAULT '0',
  `street` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL DEFAULT '',
  `province` varchar(255) NOT NULL DEFAULT '',
  `code` varchar(255) NOT NULL DEFAULT '',
  `country` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_entity_type`
--

DROP TABLE IF EXISTS `##_default_entity_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_entity_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '_UNKNOWN',
  `description` text NOT NULL,
  `vat_number_required` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_inventory_item`
--

DROP TABLE IF EXISTS `##_default_inventory_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_inventory_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `sku` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `field0` varchar(254) NOT NULL,
  `inventory_type_id` int(11) NOT NULL DEFAULT '0',
  `indicative_price` decimal(10,3) NOT NULL DEFAULT '0.000',
  `indicative_vat_id` int(11) NOT NULL DEFAULT '0',
  `indicative_currency_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_inventory_item_type`
--

DROP TABLE IF EXISTS `##_default_inventory_item_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_inventory_item_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `prefix` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_logging`
--

DROP TABLE IF EXISTS `##_default_logging`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_logging` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('modify','delete','create') NOT NULL DEFAULT 'modify',
  `table` varchar(255) NOT NULL DEFAULT '',
  `column` varchar(254) NOT NULL,
  `entry_id` int(11) NOT NULL DEFAULT '-1',
  `owner_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `old` text NOT NULL,
  `new` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_offer`
--

DROP TABLE IF EXISTS `##_default_offer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_offer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `entity_id` int(11) NOT NULL DEFAULT '-1',
  `reference` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(254) NOT NULL,
  `remark` varchar(254) NOT NULL,
  `contact` varchar(254) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vat_id` int(11) NOT NULL DEFAULT '0',
  `currency_id` int(11) NOT NULL DEFAULT '0',
  `purchased` tinyint(1) NOT NULL DEFAULT '0',
  `delivery_address_id` int(11) NOT NULL DEFAULT '-1',
  `invoice_address_id` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_offer_item`
--

DROP TABLE IF EXISTS `##_default_offer_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_offer_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `offer_id` int(11) NOT NULL DEFAULT '-1',
  `inventory_item_id` int(11) NOT NULL DEFAULT '-1',
  `sku` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `field0` varchar(254) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  `volume` decimal(10,3) NOT NULL DEFAULT '0.000',
  `price` decimal(10,3) NOT NULL DEFAULT '0.000',
  `vat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_transaction`
--

DROP TABLE IF EXISTS `##_default_transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `entity_id` int(11) NOT NULL DEFAULT '-1',
  `offer_id` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(32) NOT NULL DEFAULT '_UNKNOWN',
  `account` enum('income','expense','other') NOT NULL DEFAULT 'other',
  `type` enum('invoice','credit note') NOT NULL DEFAULT 'invoice',
  `uid_prefix` varchar(16) NOT NULL,
  `uid` int(11) NOT NULL,
  `period` varchar(254) NOT NULL,
  `field0` varchar(254) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deadline` int(11) NOT NULL DEFAULT '1',
  `discount_type` enum('fixed','relative') NOT NULL DEFAULT 'fixed',
  `discount` decimal(10,3) NOT NULL DEFAULT '0.000',
  `notes` text NOT NULL,
  `satisfied` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_transaction_item`
--

DROP TABLE IF EXISTS `##_default_transaction_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_transaction_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `disabled` tinyint(4) NOT NULL DEFAULT '0',
  `transaction_id` int(11) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `volume` decimal(10,3) NOT NULL DEFAULT '0.000',
  `offer_item_id` int(11) NOT NULL DEFAULT '0',
  `discount_type` enum('fixed','relative') NOT NULL DEFAULT 'fixed',
  `discount` decimal(10,3) NOT NULL DEFAULT '0.000',
  `vat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `##_default_vat`
--

DROP TABLE IF EXISTS `##_default_vat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `##_default_vat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('fixed','relative') NOT NULL DEFAULT 'relative',
  `amount` decimal(10,3) NOT NULL DEFAULT '0.000',
  `currency_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '_UNKNOWN',
  `description` text,
  `remark` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-03-15 15:32:21
