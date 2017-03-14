CREATE DATABASE  IF NOT EXISTS `lunalog` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */;
USE `lunalog`;
-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: lunalog
-- ------------------------------------------------------
-- Server version	5.7.17-log

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
-- Table structure for table `lunaaccessloghistory`
--

DROP TABLE IF EXISTS `lunaaccessloghistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lunaaccessloghistory` (
  `IDX` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SiteName` varchar(50) DEFAULT NULL,
  `SessionId` varchar(100) DEFAULT NULL,
  `ExecuteDuration` int(10) unsigned DEFAULT NULL,
  `RequestTime` datetime DEFAULT NULL,
  `RequestUri` varchar(100) DEFAULT NULL,
  `RequestMethod` varchar(20) DEFAULT NULL,
  `RequestParameter` varchar(2000) DEFAULT NULL,
  `UserIp` varchar(100) DEFAULT NULL,
  `ServerIp` varchar(100) DEFAULT NULL,
  `RecGUID` varchar(100) DEFAULT NULL,
  `CreateDt` datetime DEFAULT NULL,
  PRIMARY KEY (`IDX`),
  UNIQUE KEY `Index_GUID` (`RecGUID`),
  KEY `Index_SessionId` (`SessionId`),
  KEY `Index_ClientIp` (`UserIp`),
  KEY `Index_RequestUri` (`RequestUri`),
  KEY `Index_RequestTime` (`RequestTime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lunaaccessloghistory`
--

LOCK TABLES `lunaaccessloghistory` WRITE;
/*!40000 ALTER TABLE `lunaaccessloghistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `lunaaccessloghistory` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-03-12 19:58:36
