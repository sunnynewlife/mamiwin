CREATE DATABASE  IF NOT EXISTS `magtadmin` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `magtadmin`;
-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: magtadmin
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
-- Table structure for table `myadmin_quick_note`
--

DROP TABLE IF EXISTS `myadmin_quick_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `myadmin_quick_note` (
  `note_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'note_id',
  `note_content` varchar(255) NOT NULL COMMENT '内容',
  `owner_id` int(10) unsigned NOT NULL COMMENT '谁添加的',
  PRIMARY KEY (`note_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=963 COMMENT='用于显示的quick note; InnoDB free: 7168 kB';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `myadmin_quick_note`
--

LOCK TABLES `myadmin_quick_note` WRITE;
/*!40000 ALTER TABLE `myadmin_quick_note` DISABLE KEYS */;
INSERT INTO `myadmin_quick_note` VALUES (6,'孔子说：万能的不是神，是程序员！',1),(7,'听说飞信被渗透了几百台服务器',1),(8,'（yamete）＝不要 ，一般音译为”亚美爹”，正确发音是：亚灭贴',1),(9,'（kimochiii）＝爽死了，一般音译为”可莫其”，正确发音是：克一莫其一一 ',1),(10,'（itai）＝疼 ，一般音译为以太',1),(11,'（iku）＝要出来了 ，一般音译为一库',1),(12,'（soko dame）＝那里……不可以 一般音译：锁扩，打灭',1),(13,'(hatsukashi)＝羞死人了 ，音译：哈次卡西',1),(14,'（atashinookuni）＝到人家的身体里了，音译：啊她西诺喔库你',1),(15,'（mottto mottto）＝还要，还要，再大力点的意思 音译：毛掏 毛掏',1),(23,'你造吗？quick note可以关掉的，在右上角的我的账号里可以设置的。',1),(24,'你造吗？“功能”其实就是“链接”啦啦，权限控制是根据用户访问的链接来验证的。',1),(25,'你造吗？权限是赋予给账号组的，账号组下的用户拥有相同的权限。',1),(26,'Hi，你注意到navibar上的+号和-号了吗？',1),(28,'你造吗？这页面设计用是bootstrap模板改的',1),(29,'你造吗？这全部都是我一个人开发的，可特么累了！',1),(30,'程序员？还是程序猿？这是个问题！',1);
/*!40000 ALTER TABLE `myadmin_quick_note` ENABLE KEYS */;
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
