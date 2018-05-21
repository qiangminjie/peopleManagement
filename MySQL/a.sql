-- MySQL dump 10.13  Distrib 5.7.18, for macos10.12 (x86_64)
--
-- Host: localhost    Database: peopleManagement
-- ------------------------------------------------------
-- Server version	5.7.18

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
-- Table structure for table `employ_add`
--

DROP TABLE IF EXISTS `employ_add`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employ_add` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employ_add`
--

LOCK TABLES `employ_add` WRITE;
/*!40000 ALTER TABLE `employ_add` DISABLE KEYS */;
INSERT INTO `employ_add` VALUES (1,'test1',0,'招个高管',1,2),(2,'test2',1,'招个开发',2,2),(3,'test3',2,'我也不知道这是啥了',3,2),(4,'test4',3,'看着招把',4,3),(5,'急招java',1,'1 熟悉mysql\n2 会ssm\n3 熟悉linux',10,2);
/*!40000 ALTER TABLE `employ_add` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interview`
--

DROP TABLE IF EXISTS `interview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interview` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `time` int(10) NOT NULL,
  `resume_id` int(6) NOT NULL,
  `interviewer_id` int(6) NOT NULL,
  `over` int(1) NOT NULL DEFAULT '1',
  `result` int(1) DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interview`
--

LOCK TABLES `interview` WRITE;
/*!40000 ALTER TABLE `interview` DISABLE KEYS */;
/*!40000 ALTER TABLE `interview` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interviewer`
--

DROP TABLE IF EXISTS `interviewer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interviewer` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `number` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `sex` int(1) NOT NULL,
  `education` int(1) NOT NULL,
  `school` varchar(50) DEFAULT NULL,
  `major` varchar(20) DEFAULT NULL,
  `birthday` int(8) NOT NULL,
  `age` int(2) NOT NULL,
  `type` int(2) NOT NULL,
  `subtype` int(2) NOT NULL,
  `level` int(2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `in_time` int(8) DEFAULT NULL,
  `out_time` int(8) DEFAULT NULL,
  `isoffer` int(11) DEFAULT '-1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_number_uindex` (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interviewer`
--

LOCK TABLES `interviewer` WRITE;
/*!40000 ALTER TABLE `interviewer` DISABLE KEYS */;
INSERT INTO `interviewer` VALUES (22,'01010101','测试 ',0,1,'哈尔滨工业','哈哈',20180501,0,1,1,1,1,20180501,NULL,1),(25,'00000001','的承诺机动车',0,1,'rev','ve',20180501,0,0,0,0,1,20180501,NULL,3),(26,'02010001','测试4',0,2,'人反而','丰富',20180501,0,2,1,0,0,20180501,NULL,3),(27,'02000002','吾甫尔',0,1,'废物','贺卫方',20180501,0,2,0,0,0,20180501,NULL,2),(28,'01000101','热个人',0,2,'人格','热个人',20180501,0,1,0,1,1,20180501,NULL,2),(29,'01000003','访问',0,1,'个人','违反',20180501,0,1,0,0,0,20180501,NULL,-1);
/*!40000 ALTER TABLE `interviewer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `level`
--

DROP TABLE IF EXISTS `level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `level` (
  `type_id` int(2) NOT NULL,
  `subtype_id` int(2) NOT NULL,
  `level_id` int(2) NOT NULL,
  `level_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `level`
--

LOCK TABLES `level` WRITE;
/*!40000 ALTER TABLE `level` DISABLE KEYS */;
INSERT INTO `level` VALUES (1,0,0,'Java技术负责人'),(1,0,1,'高级Java工程师'),(1,0,2,'中级Java工程师'),(1,0,3,'初级Java工程师'),(1,1,0,'php技术负责人'),(1,1,1,'高级php工程师'),(1,1,2,'中级php工程师'),(1,1,3,'初级php工程师'),(1,2,0,'C++技术负责人'),(1,2,1,'高级C++工程师'),(1,2,2,'中级C++工程师'),(1,2,3,'初级C++工程师'),(2,0,0,'财务顾问'),(2,0,1,'高级会计'),(2,0,2,'中级会计'),(2,0,3,'初级会计');
/*!40000 ALTER TABLE `level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oes_user`
--

DROP TABLE IF EXISTS `oes_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oes_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `permission` int(11) DEFAULT NULL,
  `sub_permission` bigint(20) DEFAULT NULL,
  `account` varchar(36) NOT NULL,
  `mobile` varchar(11) DEFAULT '0',
  `name` varchar(20) DEFAULT NULL,
  `memo` varchar(100) DEFAULT NULL,
  `pass` varchar(32) NOT NULL,
  `status` int(1) DEFAULT '0',
  `tag` int(1) DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `error_time` int(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oes_user`
--

LOCK TABLES `oes_user` WRITE;
/*!40000 ALTER TABLE `oes_user` DISABLE KEYS */;
INSERT INTO `oes_user` VALUES (1,-1,0,0,'admin','18600000000','Admin',NULL,'e99a18c428cb38d5f260853678922e03',0,0,'2018-03-21 02:24:39',0);
/*!40000 ALTER TABLE `oes_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resume`
--

DROP TABLE IF EXISTS `resume`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resume` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `sex` int(1) NOT NULL,
  `education` int(1) NOT NULL,
  `school` varchar(50) DEFAULT NULL,
  `birthday` int(8) NOT NULL,
  `age` int(2) NOT NULL,
  `type` int(2) NOT NULL,
  `subtype` int(2) NOT NULL,
  `level` int(2) NOT NULL,
  `result` int(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resume`
--

LOCK TABLES `resume` WRITE;
/*!40000 ALTER TABLE `resume` DISABLE KEYS */;
/*!40000 ALTER TABLE `resume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salary`
--

DROP TABLE IF EXISTS `salary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary` (
  `type` int(2) NOT NULL,
  `subtype` int(2) NOT NULL,
  `level` int(2) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `flag` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salary`
--

LOCK TABLES `salary` WRITE;
/*!40000 ALTER TABLE `salary` DISABLE KEYS */;
/*!40000 ALTER TABLE `salary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salary_info`
--

DROP TABLE IF EXISTS `salary_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_info` (
  `staff_id` int(6) NOT NULL,
  `month` int(6) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `bonus` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salary_info`
--

LOCK TABLES `salary_info` WRITE;
/*!40000 ALTER TABLE `salary_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `salary_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salary_stat`
--

DROP TABLE IF EXISTS `salary_stat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_stat` (
  `salary` varchar(20) DEFAULT NULL,
  `number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salary_stat`
--

LOCK TABLES `salary_stat` WRITE;
/*!40000 ALTER TABLE `salary_stat` DISABLE KEYS */;
INSERT INTO `salary_stat` VALUES ('<3000',8),('5000-3000',33),('10000-5000',192),('10000-15000',168),('15000-30000',67),('30000-40000',15),('50000-100000',5),('>100000',2);
/*!40000 ALTER TABLE `salary_stat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `number` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `sex` int(1) NOT NULL,
  `education` int(1) NOT NULL,
  `school` varchar(50) DEFAULT NULL,
  `major` varchar(20) DEFAULT NULL,
  `birthday` int(8) NOT NULL,
  `age` int(2) NOT NULL,
  `type` int(2) NOT NULL,
  `subtype` int(2) NOT NULL,
  `level` int(2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `in_time` int(8) DEFAULT NULL,
  `out_time` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_number_uindex` (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (9,'01000001','大黑2',0,0,'北工大2','工业设计2',20180502,0,2,0,0,0,20180502,NULL),(10,'01010101','dsa',0,2,'a','经济管理',19900102,28,1,1,1,0,20180419,NULL),(13,'00000001','帚神',0,0,'mit','航天',19920305,26,0,0,0,0,20180420,NULL),(14,'00000002','aaa',0,0,'adfad','fasdfsad',20180328,0,0,0,0,0,20180420,NULL),(15,'00010001','辉夜姬',0,3,NULL,'厨师',20180430,0,0,1,0,1,20180501,20180502);
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subtype`
--

DROP TABLE IF EXISTS `subtype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subtype` (
  `type_id` int(2) NOT NULL,
  `subtype_id` int(2) NOT NULL,
  `subtype_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subtype`
--

LOCK TABLES `subtype` WRITE;
/*!40000 ALTER TABLE `subtype` DISABLE KEYS */;
INSERT INTO `subtype` VALUES (0,0,'董事长'),(0,1,'总经理'),(1,0,'java'),(1,1,'php'),(1,2,'c++'),(2,0,'会计'),(2,1,'审计'),(3,0,'人事'),(3,1,'业务');
/*!40000 ALTER TABLE `subtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type`
--

DROP TABLE IF EXISTS `type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type` (
  `type_id` int(2) NOT NULL,
  `type_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type`
--

LOCK TABLES `type` WRITE;
/*!40000 ALTER TABLE `type` DISABLE KEYS */;
INSERT INTO `type` VALUES (0,'高层'),(1,'开发'),(2,'财务'),(3,'行政');
/*!40000 ALTER TABLE `type` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-02 16:40:51
