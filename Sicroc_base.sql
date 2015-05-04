-- MySQL dump 10.14  Distrib 5.5.37-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: Sicroc
-- ------------------------------------------------------
-- Server version	5.5.37-MariaDB

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
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` int(11) DEFAULT NULL,
  `widget` int(11) NOT NULL,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content`
--

LOCK TABLES `content` WRITE;
/*!40000 ALTER TABLE `content` DISABLE KEYS */;
INSERT INTO `content` VALUES (75,26,57,NULL),(76,25,58,NULL),(79,29,60,NULL),(78,27,59,NULL),(80,30,61,NULL),(81,31,62,NULL),(84,0,64,NULL),(85,33,65,NULL),(86,27,63,NULL),(87,28,67,NULL),(88,34,68,NULL),(97,40,76,NULL),(90,34,70,NULL),(91,36,71,NULL),(92,35,72,NULL),(93,37,73,NULL),(94,38,74,NULL),(95,39,69,NULL),(96,27,75,NULL),(98,41,77,NULL),(99,42,78,NULL);
/*!40000 ALTER TABLE `content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataDevices`
--

DROP TABLE IF EXISTS `dataDevices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataDevices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataDevices`
--

LOCK TABLES `dataDevices` WRITE;
/*!40000 ALTER TABLE `dataDevices` DISABLE KEYS */;
INSERT INTO `dataDevices` VALUES (1,'first device'),(2,'second device'),(3,'third device');
/*!40000 ALTER TABLE `dataDevices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataEnvironments`
--

DROP TABLE IF EXISTS `dataEnvironments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataEnvironments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `primaryContact` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `primaryContact` (`primaryContact`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataEnvironments`
--

LOCK TABLES `dataEnvironments` WRITE;
/*!40000 ALTER TABLE `dataEnvironments` DISABLE KEYS */;
/*!40000 ALTER TABLE `dataEnvironments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataIpAddresses`
--

DROP TABLE IF EXISTS `dataIpAddresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataIpAddresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipv4` varchar(255) DEFAULT NULL,
  `ipv6` varchar(255) DEFAULT NULL,
  `networkInterfaceCard` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `networkInterfaceCard` (`networkInterfaceCard`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataIpAddresses`
--

LOCK TABLES `dataIpAddresses` WRITE;
/*!40000 ALTER TABLE `dataIpAddresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `dataIpAddresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataLocations`
--

DROP TABLE IF EXISTS `dataLocations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataLocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) DEFAULT NULL,
  `streetAddress1` varchar(255) DEFAULT NULL,
  `streetAddress2` varchar(255) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `county` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `postcode` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataLocations`
--

LOCK TABLES `dataLocations` WRITE;
/*!40000 ALTER TABLE `dataLocations` DISABLE KEYS */;
/*!40000 ALTER TABLE `dataLocations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataMacAddresses`
--

DROP TABLE IF EXISTS `dataMacAddresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataMacAddresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `macAddress` varchar(255) DEFAULT NULL,
  `device` int(11) DEFAULT NULL,
  `physical` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `device` (`device`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataMacAddresses`
--

LOCK TABLES `dataMacAddresses` WRITE;
/*!40000 ALTER TABLE `dataMacAddresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `dataMacAddresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataPurchaseOrders`
--

DROP TABLE IF EXISTS `dataPurchaseOrders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataPurchaseOrders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `acl_read` int(11) DEFAULT NULL,
  `acl_write` int(11) DEFAULT NULL,
  `acl_delete` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataPurchaseOrders`
--

LOCK TABLES `dataPurchaseOrders` WRITE;
/*!40000 ALTER TABLE `dataPurchaseOrders` DISABLE KEYS */;
/*!40000 ALTER TABLE `dataPurchaseOrders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_memberships`
--

DROP TABLE IF EXISTS `group_memberships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_memberships`
--

LOCK TABLES `group_memberships` WRITE;
/*!40000 ALTER TABLE `group_memberships` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_memberships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `layout` varchar(32) DEFAULT 'normal',
  `ident` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ident` (`ident`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (25,'Create page','normal','PAGE_CREATE'),(26,'Register Widget','normal','WIDGET_REGISTER'),(27,'Update page','normal','PAGE_UPDATE'),(28,'update section','normal','SECTION_UPDATE'),(29,'Create Widget','normal','WIDGET_CREATE'),(30,'Create Section','normal','SECTION_CREATE'),(31,'Update Widget','normal','WIDGET_INSTANCE_UPDATE'),(0,'Index','normal','INDEX'),(33,'Update wiki page','normal','WIKI_EDIT'),(34,'Database home','normal',''),(35,'Table structure','normal','TABLE_STRUCTURE'),(36,'Insert into table','normal','TABLE_INSERT'),(37,'Admin','normal','ADMIN'),(38,'Section list','normal','SECTION_LIST'),(39,'Table create','normal','TABLE_CREATE'),(40,'List of pages','normal','PAGE_LIST'),(41,'Edit Table Row','normal','TABLE_ROW_EDIT'),(42,'View table row','normal','TABLE_ROW');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(32) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privileges_g`
--

DROP TABLE IF EXISTS `privileges_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `privileges_g` (
  `permission` int(11) DEFAULT NULL,
  `group` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privileges_g`
--

LOCK TABLES `privileges_g` WRITE;
/*!40000 ALTER TABLE `privileges_g` DISABLE KEYS */;
/*!40000 ALTER TABLE `privileges_g` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privileges_u`
--

DROP TABLE IF EXISTS `privileges_u`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `privileges_u` (
  `permission` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privileges_u`
--

LOCK TABLES `privileges_u` WRITE;
/*!40000 ALTER TABLE `privileges_u` DISABLE KEYS */;
/*!40000 ALTER TABLE `privileges_u` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `master` int(11) NOT NULL,
  `index` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,'root',0,0),(7,'Home',1,NULL),(8,'Database',1,34),(9,'Admin',1,37);
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sources`
--

DROP TABLE IF EXISTS `sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cn` text,
  `dsn` int(11) NOT NULL,
  `c_name` varchar(255) DEFAULT NULL COMMENT 'test',
  `s_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sources`
--

LOCK TABLES `sources` WRITE;
/*!40000 ALTER TABLE `sources` DISABLE KEYS */;
/*!40000 ALTER TABLE `sources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `table_fk_metadata`
--

DROP TABLE IF EXISTS `table_fk_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table_fk_metadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sourceField` varchar(64) DEFAULT NULL,
  `sourceTable` varchar(64) DEFAULT NULL,
  `foreignTable` varchar(64) DEFAULT NULL,
  `foreignField` varchar(64) DEFAULT NULL,
  `foreignDescription` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `table_fk_metadata`
--

LOCK TABLES `table_fk_metadata` WRITE;
/*!40000 ALTER TABLE `table_fk_metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `table_fk_metadata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tmpAssetsComputers`
--

DROP TABLE IF EXISTS `tmpAssetsComputers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmpAssetsComputers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `purchaseOrder` int(11) DEFAULT NULL,
  `owner` int(11) DEFAULT NULL,
  `environment` int(11) DEFAULT NULL,
  `location` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchaseOrder` (`purchaseOrder`),
  KEY `owner` (`owner`),
  KEY `environment` (`environment`),
  KEY `location` (`location`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tmpAssetsComputers`
--

LOCK TABLES `tmpAssetsComputers` WRITE;
/*!40000 ALTER TABLE `tmpAssetsComputers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmpAssetsComputers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tmpAssetsNids`
--

DROP TABLE IF EXISTS `tmpAssetsNids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmpAssetsNids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` longtext,
  `cost` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tmpAssetsNids`
--

LOCK TABLES `tmpAssetsNids` WRITE;
/*!40000 ALTER TABLE `tmpAssetsNids` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmpAssetsNids` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `userLevel` int(11) DEFAULT NULL,
  `group` int(11) DEFAULT NULL,
  `lastLogin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widget_argument_values`
--

DROP TABLE IF EXISTS `widget_argument_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widget_argument_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget` int(11) DEFAULT NULL,
  `key` varchar(32) NOT NULL,
  `value` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widget_argument_values`
--

LOCK TABLES `widget_argument_values` WRITE;
/*!40000 ALTER TABLE `widget_argument_values` DISABLE KEYS */;
INSERT INTO `widget_argument_values` VALUES (30,57,'formClass','FormWidgetClassRegister'),(31,58,'formClass','FormPageCreate'),(32,59,'formClass','FormAddToPage'),(33,60,'formClass','FormWidgetCreate'),(34,62,'formClass','FormWidgetUpdate'),(37,61,'formClass','FormCreateSection'),(36,63,'formClass','FormPageUpdate'),(38,61,'formClass','FormSectionCreate'),(39,65,'formClass','FormWikiUpdate'),(40,67,'formClass','FormSectionUpdate'),(41,69,'formClass','FormTableCreate'),(42,71,'formClass','FormTableInsert'),(43,72,'formClass','FormTableAddRow'),(44,75,'formClass','FormPageContentDelete'),(45,77,'formClass','FormTableRowEdit'),(46,77,'formClass','FormTableEditRow');
/*!40000 ALTER TABLE `widget_argument_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widget_arguments`
--

DROP TABLE IF EXISTS `widget_arguments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widget_arguments` (
  `ident` varchar(32) NOT NULL,
  `widget` int(11) DEFAULT NULL,
  `datatype` varchar(16) DEFAULT NULL,
  `default` longtext,
  PRIMARY KEY (`ident`),
  UNIQUE KEY `ident` (`ident`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widget_arguments`
--

LOCK TABLES `widget_arguments` WRITE;
/*!40000 ALTER TABLE `widget_arguments` DISABLE KEYS */;
INSERT INTO `widget_arguments` VALUES ('formClass',26,'string',NULL);
/*!40000 ALTER TABLE `widget_arguments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widget_instances`
--

DROP TABLE IF EXISTS `widget_instances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widget_instances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) DEFAULT NULL,
  `principle` longtext,
  `method` varchar(23) DEFAULT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widget_instances`
--

LOCK TABLES `widget_instances` WRITE;
/*!40000 ALTER TABLE `widget_instances` DISABLE KEYS */;
INSERT INTO `widget_instances` VALUES (57,'Register widget class',NULL,NULL,9),(58,'Create Page',NULL,NULL,9),(59,'Page Update',NULL,NULL,9),(60,'Create widget instance',NULL,NULL,9),(61,'Create Section','','display',9),(62,'FormWidgetUpdate','','display',9),(63,'Update Page','','display',9),(64,'homepage splash','','display',10),(65,'Update wiki page','','display',9),(66,'Update wiki page','','display',9),(67,'No form constructed','','display',9),(68,'Database home','databasehome','display',10),(69,'Create Table','','display',9),(70,'Devices','dataDevices','display',11),(71,'Insert into table','','display',9),(72,'Add field to table','','display',9),(73,'admin','admin','display',10),(74,'Sections','sections','display',11),(75,'Delete content','','display',9),(76,'pages','pages','display',11),(77,'Edit row','','display',9),(78,'','','display',12);
/*!40000 ALTER TABLE `widget_instances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widget_types`
--

DROP TABLE IF EXISTS `widget_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widget_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `viewableController` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widget_types`
--

LOCK TABLES `widget_types` WRITE;
/*!40000 ALTER TABLE `widget_types` DISABLE KEYS */;
INSERT INTO `widget_types` VALUES (9,'ControllerForm'),(10,'WikiContent'),(11,'Table'),(12,'TableRow');
/*!40000 ALTER TABLE `widget_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_content`
--

DROP TABLE IF EXISTS `wiki_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wiki_content` (
  `principle` varchar(32) NOT NULL,
  `content` longtext,
  PRIMARY KEY (`principle`),
  UNIQUE KEY `principle` (`principle`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wiki_content`
--

LOCK TABLES `wiki_content` WRITE;
/*!40000 ALTER TABLE `wiki_content` DISABLE KEYS */;
INSERT INTO `wiki_content` VALUES ('','Hi there, this is the homepage of Sicroc.'),('databasehome','This is the homepage of the database. You may want to <a href = \"?pageIdent=TABLE_CREATE\">create</a> a few more tables.'),('admin','<p>This is the admin page.<p>\r\n\r\n<h2>Sections</h2>\r\n\r\n<a href = \"?pageIdent=SECTION_LIST\">Sections</a>\r\n\r\n<h2>Pages</h2>\r\n\r\n<a href = \"?pageIdent=PAGE_LIST\">Pages</a>\r\n\r\n<h2>Tables</h2>\r\n\r\n<a href = \"?pageIdent=TABLE_CREATE\">Create</a>');
/*!40000 ALTER TABLE `wiki_content` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-07-10  0:25:27
