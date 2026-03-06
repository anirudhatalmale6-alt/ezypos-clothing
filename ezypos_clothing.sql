-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: ezypos
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ezy_pos_bank`
--

DROP TABLE IF EXISTS `ezy_pos_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_bank` (
  `bank_id` int NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(250) NOT NULL,
  `bank_branch` varchar(250) NOT NULL,
  `bank_accname` varchar(250) NOT NULL,
  `bank_accnumber` varchar(250) NOT NULL,
  `bank_createdby` int NOT NULL,
  `bank_status` tinyint NOT NULL,
  PRIMARY KEY (`bank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_bank`
--

LOCK TABLES `ezy_pos_bank` WRITE;
/*!40000 ALTER TABLE `ezy_pos_bank` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_categories`
--

DROP TABLE IF EXISTS `ezy_pos_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_categories` (
  `cat_id` int NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(250) NOT NULL,
  `cat_is_raw` tinyint(1) NOT NULL DEFAULT '0',
  `cat_status` int NOT NULL DEFAULT '1',
  `cat_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_categories`
--

LOCK TABLES `ezy_pos_categories` WRITE;
/*!40000 ALTER TABLE `ezy_pos_categories` DISABLE KEYS */;
INSERT INTO `ezy_pos_categories` VALUES (1,'Fabrics & Textiles',1,1,'2026-03-05 14:47:45'),(2,'Threads & Yarn',1,1,'2026-03-05 14:47:45'),(3,'Buttons & Fasteners',1,1,'2026-03-05 14:47:45'),(4,'Zippers & Trims',1,1,'2026-03-05 14:47:45'),(5,'Lining & Interfacing',1,1,'2026-03-05 14:47:45'),(6,'Men\'s Shirts',0,1,'2026-03-05 14:47:45'),(7,'Men\'s Trousers',0,1,'2026-03-05 14:47:45'),(8,'Men\'s Suits',0,1,'2026-03-05 14:47:45'),(9,'Women\'s Dresses',0,1,'2026-03-05 14:47:45'),(10,'Women\'s Blouses',0,1,'2026-03-05 14:47:45'),(11,'Women\'s Skirts',0,1,'2026-03-05 14:47:45'),(12,'Children\'s Wear',0,1,'2026-03-05 14:47:45'),(13,'Accessories',0,1,'2026-03-05 14:47:45'),(14,'Uniforms',0,1,'2026-03-05 14:47:45');
/*!40000 ALTER TABLE `ezy_pos_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_company`
--

DROP TABLE IF EXISTS `ezy_pos_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_company` (
  `com_id` int NOT NULL,
  `com_name` varchar(250) NOT NULL,
  `com_address` varchar(250) DEFAULT NULL,
  `com_telephone` varchar(17) DEFAULT NULL,
  `com_mobile` varchar(17) DEFAULT NULL,
  `com_email` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_company`
--

LOCK TABLES `ezy_pos_company` WRITE;
/*!40000 ALTER TABLE `ezy_pos_company` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_config2`
--

DROP TABLE IF EXISTS `ezy_pos_config2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_config2` (
  `config_id` int NOT NULL AUTO_INCREMENT,
  `config_key` varchar(250) NOT NULL,
  `config_value` varchar(250) NOT NULL,
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_config2`
--

LOCK TABLES `ezy_pos_config2` WRITE;
/*!40000 ALTER TABLE `ezy_pos_config2` DISABLE KEYS */;
INSERT INTO `ezy_pos_config2` VALUES (7,'name','Handloom Gallery'),(8,'addressLine1',''),(9,'addressLine2',''),(10,'tel',''),(11,'mob',''),(12,'footer','Thank You, Please Come Again!');
/*!40000 ALTER TABLE `ezy_pos_config2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_cur_grn_vs_sale`
--

DROP TABLE IF EXISTS `ezy_pos_cur_grn_vs_sale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_cur_grn_vs_sale` (
  `grnvssale_id` int NOT NULL AUTO_INCREMENT,
  `grnvssale_curQtyWithGrnID` int NOT NULL,
  `grnvssale_itmID` int NOT NULL,
  `grnvssale_saleID` int NOT NULL,
  `grnvssale_status` tinyint(1) NOT NULL DEFAULT '0',
  `grn_quantity` int DEFAULT NULL COMMENT 'sale quantity from grn ',
  PRIMARY KEY (`grnvssale_id`),
  KEY `idx_saleID` (`grnvssale_saleID`),
  KEY `idx_itmID` (`grnvssale_itmID`),
  KEY `idx_curQtyWithGrnID` (`grnvssale_curQtyWithGrnID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_cur_grn_vs_sale`
--

LOCK TABLES `ezy_pos_cur_grn_vs_sale` WRITE;
/*!40000 ALTER TABLE `ezy_pos_cur_grn_vs_sale` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_cur_grn_vs_sale` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_currentqtywithgrn`
--

DROP TABLE IF EXISTS `ezy_pos_currentqtywithgrn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_currentqtywithgrn` (
  `cur_id` int NOT NULL AUTO_INCREMENT,
  `cur_grnID` int NOT NULL,
  `cur_itmID` int NOT NULL,
  `cur_grnQty` double(250,2) NOT NULL,
  `cur_grnPrice` double(30,2) NOT NULL,
  `cur_grnTotal` double(30,2) NOT NULL,
  `cur_currentQTY` double(250,2) NOT NULL,
  `cur_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cur_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_currentqtywithgrn`
--

LOCK TABLES `ezy_pos_currentqtywithgrn` WRITE;
/*!40000 ALTER TABLE `ezy_pos_currentqtywithgrn` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_currentqtywithgrn` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_cus_balnce`
--

DROP TABLE IF EXISTS `ezy_pos_cus_balnce`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_cus_balnce` (
  `bal_id` int NOT NULL AUTO_INCREMENT,
  `bal_cusid` int NOT NULL,
  `bal_amount` double(50,2) NOT NULL,
  PRIMARY KEY (`bal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_cus_balnce`
--

LOCK TABLES `ezy_pos_cus_balnce` WRITE;
/*!40000 ALTER TABLE `ezy_pos_cus_balnce` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_cus_balnce` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_cus_cheque`
--

DROP TABLE IF EXISTS `ezy_pos_cus_cheque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_cus_cheque` (
  `cus_cheque_id` int NOT NULL AUTO_INCREMENT,
  `cheque_cus_id` varchar(250) DEFAULT NULL,
  `cus_cheque_saleid` int NOT NULL,
  `cus_cheque_amount` double(50,2) NOT NULL,
  `cus_cheque_bank` varchar(250) NOT NULL,
  `cus_cheque_num` varchar(250) NOT NULL,
  `cus_cheque_date` date NOT NULL,
  `cus_cheque_givendate` date NOT NULL,
  `cus_cheque_status` tinyint(1) NOT NULL,
  `cus_cheque_transferred` tinyint NOT NULL,
  PRIMARY KEY (`cus_cheque_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_cus_cheque`
--

LOCK TABLES `ezy_pos_cus_cheque` WRITE;
/*!40000 ALTER TABLE `ezy_pos_cus_cheque` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_cus_cheque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_cus_chequelog`
--

DROP TABLE IF EXISTS `ezy_pos_cus_chequelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_cus_chequelog` (
  `cheqlog_id` int NOT NULL AUTO_INCREMENT,
  `cheqlog_chequeid` int NOT NULL,
  `cheqlog_saleid` int NOT NULL,
  `cheqlog_amount` double(50,2) NOT NULL,
  PRIMARY KEY (`cheqlog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_cus_chequelog`
--

LOCK TABLES `ezy_pos_cus_chequelog` WRITE;
/*!40000 ALTER TABLE `ezy_pos_cus_chequelog` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_cus_chequelog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_cus_payment`
--

DROP TABLE IF EXISTS `ezy_pos_cus_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_cus_payment` (
  `cus_pay_id` int NOT NULL AUTO_INCREMENT,
  `cus_pay_saleid` int NOT NULL,
  `cus_pay_cash` double(50,2) NOT NULL,
  `cus_pay_credit` double(50,2) NOT NULL,
  `cus_pay_paiddate` date NOT NULL,
  PRIMARY KEY (`cus_pay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_cus_payment`
--

LOCK TABLES `ezy_pos_cus_payment` WRITE;
/*!40000 ALTER TABLE `ezy_pos_cus_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_cus_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_cus_paymnt_log`
--

DROP TABLE IF EXISTS `ezy_pos_cus_paymnt_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_cus_paymnt_log` (
  `pymntlog_id` int NOT NULL AUTO_INCREMENT,
  `pymntlog_saleid` int NOT NULL,
  `pymntlog_amount` double(50,2) NOT NULL,
  `pymntlog_date` date NOT NULL,
  PRIMARY KEY (`pymntlog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_cus_paymnt_log`
--

LOCK TABLES `ezy_pos_cus_paymnt_log` WRITE;
/*!40000 ALTER TABLE `ezy_pos_cus_paymnt_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_cus_paymnt_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_cus_return`
--

DROP TABLE IF EXISTS `ezy_pos_cus_return`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_cus_return` (
  `cusrtrn_id` int NOT NULL AUTO_INCREMENT,
  `cusrtrn_cusID` int NOT NULL,
  `cusrtrn_totalRtrn` double(20,2) NOT NULL,
  `cusrtrn_createdby` int NOT NULL,
  `cusrtrn_status` tinyint(1) NOT NULL,
  `cusrtrn_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cusrtrn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_cus_return`
--

LOCK TABLES `ezy_pos_cus_return` WRITE;
/*!40000 ALTER TABLE `ezy_pos_cus_return` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_cus_return` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_cus_return_item`
--

DROP TABLE IF EXISTS `ezy_pos_cus_return_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_cus_return_item` (
  `retrn_itm_id` int NOT NULL AUTO_INCREMENT,
  `retrn_itm_retrnID` int NOT NULL,
  `retrn_itm_itmID` int NOT NULL,
  `retrn_itm_rQty` double(250,2) NOT NULL,
  `retrn_itm_rAmount` double(30,2) NOT NULL,
  `retrn_itm_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`retrn_itm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_cus_return_item`
--

LOCK TABLES `ezy_pos_cus_return_item` WRITE;
/*!40000 ALTER TABLE `ezy_pos_cus_return_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_cus_return_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_customers`
--

DROP TABLE IF EXISTS `ezy_pos_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_customers` (
  `cus_id` int NOT NULL AUTO_INCREMENT,
  `cus_name` varchar(200) NOT NULL,
  `cus_address` varchar(250) NOT NULL,
  `cus_contact` varchar(20) NOT NULL,
  `cus_balance` decimal(30,2) NOT NULL,
  `cus_creditlimit` double(30,2) NOT NULL,
  `cus_status` int NOT NULL DEFAULT '1',
  `cus_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cus_id`)
) ENGINE=MyISAM AUTO_INCREMENT=289 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_customers`
--

LOCK TABLES `ezy_pos_customers` WRITE;
/*!40000 ALTER TABLE `ezy_pos_customers` DISABLE KEYS */;
INSERT INTO `ezy_pos_customers` VALUES (288,'CASH','Walk-in Customer','0000000000',0.00,500000.00,1,'2026-03-06 12:57:26');
/*!40000 ALTER TABLE `ezy_pos_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_employe_salary`
--

DROP TABLE IF EXISTS `ezy_pos_employe_salary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_employe_salary` (
  `empsalary_id` int NOT NULL AUTO_INCREMENT,
  `empsalary_staffid` int NOT NULL,
  `empsalary_month` tinyint NOT NULL,
  `empsalary_year` smallint NOT NULL,
  `empsalary_balance` double(30,2) NOT NULL,
  `empsalary_basicsalary` double(30,2) NOT NULL,
  `empsalary_food` double(30,2) NOT NULL,
  `empsalary_travel` double(30,2) NOT NULL,
  `empsalary_other` double(30,2) NOT NULL,
  `empsalary_ot` double(30,2) NOT NULL,
  `empsalary_bonus` double(30,2) NOT NULL,
  `empsalary_total` double(30,2) NOT NULL,
  `empsalary_createdby` int NOT NULL,
  `empsalary_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`empsalary_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_employe_salary`
--

LOCK TABLES `ezy_pos_employe_salary` WRITE;
/*!40000 ALTER TABLE `ezy_pos_employe_salary` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_employe_salary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_employe_salary_log`
--

DROP TABLE IF EXISTS `ezy_pos_employe_salary_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_employe_salary_log` (
  `emp_slry_log_expense_id` int NOT NULL AUTO_INCREMENT,
  `emp_slry_log_expense_tblid` int NOT NULL,
  `emp_slry_log_empsalry_tblid` int NOT NULL,
  `emp_slry_log_empid` int NOT NULL,
  `emp_slry_log_month` tinyint NOT NULL,
  `emp_slry_log_year` smallint NOT NULL,
  `emp_slry_log_amount` double(50,2) NOT NULL,
  `emp_slry_log_balanceofmonth` double(50,2) NOT NULL,
  `emp_slry_log_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`emp_slry_log_expense_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_employe_salary_log`
--

LOCK TABLES `ezy_pos_employe_salary_log` WRITE;
/*!40000 ALTER TABLE `ezy_pos_employe_salary_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_employe_salary_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_expen_cheque`
--

DROP TABLE IF EXISTS `ezy_pos_expen_cheque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_expen_cheque` (
  `expen_chq_id` int NOT NULL AUTO_INCREMENT,
  `expen_chq_expntblid` int NOT NULL,
  `expen_chq_amount` double(50,2) NOT NULL,
  `expen_chq_bank` varchar(250) NOT NULL,
  `expen_chq_chq_no` varchar(20) NOT NULL,
  `expen_chq_date` date NOT NULL,
  `expen_chq_status` tinyint NOT NULL,
  PRIMARY KEY (`expen_chq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_expen_cheque`
--

LOCK TABLES `ezy_pos_expen_cheque` WRITE;
/*!40000 ALTER TABLE `ezy_pos_expen_cheque` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_expen_cheque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_expense`
--

DROP TABLE IF EXISTS `ezy_pos_expense`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_expense` (
  `expen_id` int NOT NULL AUTO_INCREMENT,
  `expen_expencat_id` int NOT NULL,
  `expen_description` text NOT NULL,
  `expen_amount` double(30,2) NOT NULL,
  `expen_date` date NOT NULL,
  `expen_createdby` int NOT NULL,
  `expen_status` tinyint(1) NOT NULL DEFAULT '1',
  `expen_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`expen_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_expense`
--

LOCK TABLES `ezy_pos_expense` WRITE;
/*!40000 ALTER TABLE `ezy_pos_expense` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_expense` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_expense_cat`
--

DROP TABLE IF EXISTS `ezy_pos_expense_cat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_expense_cat` (
  `expencat_id` int NOT NULL AUTO_INCREMENT,
  `expencat_catname` varchar(250) NOT NULL,
  `expencat_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`expencat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_expense_cat`
--

LOCK TABLES `ezy_pos_expense_cat` WRITE;
/*!40000 ALTER TABLE `ezy_pos_expense_cat` DISABLE KEYS */;
INSERT INTO `ezy_pos_expense_cat` VALUES (1,'Employee Salary',1),(2,'Rent',1),(3,'Water Bill',1),(4,'Electricity Bill',1),(6,'Other',1),(7,'Shop Allowances',1);
/*!40000 ALTER TABLE `ezy_pos_expense_cat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_grn_item`
--

DROP TABLE IF EXISTS `ezy_pos_grn_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_grn_item` (
  `grnitm_id` int NOT NULL AUTO_INCREMENT,
  `grnitm_grn_id` int NOT NULL,
  `grnitm_itemid` int NOT NULL,
  `grnitm_price` double(50,2) NOT NULL,
  `grnitm_quantity` double(250,2) NOT NULL,
  `grnitm_total` double(50,2) NOT NULL,
  `grnitm_discount` double(5,2) NOT NULL,
  `grnitm_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`grnitm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_grn_item`
--

LOCK TABLES `ezy_pos_grn_item` WRITE;
/*!40000 ALTER TABLE `ezy_pos_grn_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_grn_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_grns`
--

DROP TABLE IF EXISTS `ezy_pos_grns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_grns` (
  `grn_id` int NOT NULL AUTO_INCREMENT,
  `grn_code` varchar(50) NOT NULL,
  `grn_supplier_id` int NOT NULL,
  `grn_grandtotal` double(50,2) NOT NULL,
  `grn_subtotal` double(50,2) NOT NULL,
  `grn_discount` double(5,2) NOT NULL,
  `grn_less` double(10,2) NOT NULL,
  `grn_createdby` int NOT NULL,
  `grn_date` date NOT NULL,
  `grn_status` tinyint(1) NOT NULL,
  `grn_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`grn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_grns`
--

LOCK TABLES `ezy_pos_grns` WRITE;
/*!40000 ALTER TABLE `ezy_pos_grns` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_grns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_insuffistocksale`
--

DROP TABLE IF EXISTS `ezy_pos_insuffistocksale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_insuffistocksale` (
  `insuffi_id` int NOT NULL AUTO_INCREMENT,
  `insuffi_saleid` int NOT NULL,
  `insuffi_itmid` int NOT NULL,
  `insuffi_qty` double(250,2) NOT NULL,
  `insuffi_newqty` double(250,2) NOT NULL,
  `insuffi_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `insuffi_grnstatus` tinyint(1) NOT NULL DEFAULT '0',
  `insuffi_salestatus` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`insuffi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_insuffistocksale`
--

LOCK TABLES `ezy_pos_insuffistocksale` WRITE;
/*!40000 ALTER TABLE `ezy_pos_insuffistocksale` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_insuffistocksale` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_item_warehouse`
--

DROP TABLE IF EXISTS `ezy_pos_item_warehouse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_item_warehouse` (
  `ware_item_id` int NOT NULL,
  `ware_warehouse_id` int NOT NULL,
  PRIMARY KEY (`ware_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_item_warehouse`
--

LOCK TABLES `ezy_pos_item_warehouse` WRITE;
/*!40000 ALTER TABLE `ezy_pos_item_warehouse` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_item_warehouse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_items`
--

DROP TABLE IF EXISTS `ezy_pos_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_items` (
  `itm_id` int NOT NULL AUTO_INCREMENT,
  `itm_code` varchar(250) NOT NULL,
  `itm_name` varchar(250) NOT NULL,
  `itm_category` int NOT NULL,
  `itm_subcategory` int NOT NULL,
  `itm_brand` varchar(250) NOT NULL,
  `itm_manufacture` varchar(250) NOT NULL,
  `itm_description` text NOT NULL,
  `itm_sellingprice` double(30,2) NOT NULL,
  `itm_quantity` double(250,2) NOT NULL,
  `itm_reorderlevel` double(250,2) NOT NULL,
  `itm_uom` varchar(250) NOT NULL,
  `itm_status` int NOT NULL DEFAULT '1',
  `itm_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`itm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_items`
--

LOCK TABLES `ezy_pos_items` WRITE;
/*!40000 ALTER TABLE `ezy_pos_items` DISABLE KEYS */;
INSERT INTO `ezy_pos_items` VALUES (1,'FAB-COT-001','White Cotton Fabric',1,1,'TextilePro','TextilePro Ltd','Premium white cotton, 60 inch width',850.00,0.00,10.00,'Yards',1,'2026-03-05 14:48:58'),(2,'FAB-COT-002','Blue Cotton Fabric',1,1,'TextilePro','TextilePro Ltd','Blue cotton fabric, 60 inch width',900.00,0.00,10.00,'Yards',1,'2026-03-05 14:48:58'),(3,'FAB-SLK-001','Red Silk Fabric',1,2,'SilkWorld','SilkWorld','Pure red silk, 45 inch width',2500.00,0.00,5.00,'Yards',1,'2026-03-05 14:48:58'),(4,'FAB-PLY-001','Black Polyester Fabric',1,3,'PolyFab','PolyFab','Black polyester blend, 58 inch width',650.00,0.00,15.00,'Yards',1,'2026-03-05 14:48:58'),(5,'FAB-DEN-001','Indigo Denim Fabric',1,6,'DenimCo','DenimCo','Heavy-weight indigo denim, 62 inch',1100.00,0.00,10.00,'Yards',1,'2026-03-05 14:48:58'),(6,'THR-COT-001','White Cotton Thread',2,9,'ThreadMaster','ThreadMaster','White sewing thread, 5000m spool',350.00,0.00,20.00,'PCS',1,'2026-03-05 14:48:58'),(7,'THR-PLY-001','Black Polyester Thread',2,11,'ThreadMaster','ThreadMaster','Black polyester thread, 5000m spool',300.00,0.00,20.00,'PCS',1,'2026-03-05 14:48:58'),(8,'BTN-PLT-001','White Plastic Buttons 18mm',3,12,'ButtonKing','ButtonKing','4-hole white buttons, 18mm',5.00,0.00,100.00,'PCS',1,'2026-03-05 14:48:58'),(9,'BTN-MTL-001','Silver Metal Buttons 20mm',3,13,'ButtonKing','ButtonKing','2-hole silver metal, 20mm',15.00,0.00,50.00,'PCS',1,'2026-03-05 14:48:58'),(10,'ZIP-MTL-001','Silver Metal Zipper 7in',4,15,'ZipFast','ZipFast','Closed-end metal zipper, 7 inch',45.00,0.00,50.00,'PCS',1,'2026-03-05 14:48:58'),(11,'ZIP-NYL-001','Black Nylon Zipper 20in',4,16,'ZipFast','ZipFast','Open-end nylon zipper, 20 inch',35.00,0.00,50.00,'PCS',1,'2026-03-05 14:48:58'),(12,'LIN-FUS-001','White Fusible Interfacing',5,19,'InterTex','InterTex','Medium weight fusible, 36 inch',200.00,0.00,20.00,'Yards',1,'2026-03-05 14:48:58'),(13,'MSH-FRM-001','White Formal Shirt - M',6,21,'ClassicWear','ClassicWear','Men formal white shirt, Medium',3500.00,0.00,5.00,'PCS',1,'2026-03-05 14:48:58'),(14,'MSH-FRM-002','White Formal Shirt - L',6,21,'ClassicWear','ClassicWear','Men formal white shirt, Large',3500.00,0.00,5.00,'PCS',1,'2026-03-05 14:48:58'),(15,'MSH-CAS-001','Blue Casual Shirt - M',6,22,'UrbanStyle','UrbanStyle','Men casual blue shirt, Medium',2800.00,0.00,5.00,'PCS',1,'2026-03-05 14:48:58'),(16,'MTR-FRM-001','Black Formal Trousers - 32',7,23,'ClassicWear','ClassicWear','Men formal black trousers, waist 32',4200.00,0.00,5.00,'PCS',1,'2026-03-05 14:48:58'),(17,'MTR-JNS-001','Blue Jeans - 32',7,25,'DenimCo','DenimCo','Men straight fit blue jeans, waist 32',3800.00,0.00,5.00,'PCS',1,'2026-03-05 14:48:58'),(18,'MST-2PC-001','Navy Two-Piece Suit - M',8,26,'SuitCraft','SuitCraft','Men navy 2-piece suit, Medium',18000.00,0.00,3.00,'PCS',1,'2026-03-05 14:48:58'),(19,'WDR-CAS-001','Floral Casual Dress - S',9,28,'FemStyle','FemStyle','Women floral casual dress, Small',4500.00,0.00,5.00,'PCS',1,'2026-03-05 14:48:58'),(20,'WBL-FRM-001','White Formal Blouse - M',10,31,'FemStyle','FemStyle','Women formal white blouse, Medium',2500.00,0.00,5.00,'PCS',1,'2026-03-05 14:48:58'),(21,'WSK-PCL-001','Black Pencil Skirt - M',11,34,'FemStyle','FemStyle','Women black pencil skirt, Medium',3200.00,0.00,5.00,'PCS',1,'2026-03-05 14:48:58'),(22,'KID-BOY-001','Boys School Shirt - 8Y',12,35,'KidsWear','KidsWear','Boys school white shirt, Age 8',1800.00,0.00,10.00,'PCS',1,'2026-03-05 14:48:58'),(23,'ACC-TIE-001','Navy Silk Tie',13,37,'ClassicWear','ClassicWear','Men navy silk necktie',1500.00,0.00,10.00,'PCS',1,'2026-03-05 14:48:58'),(24,'UNI-SCH-001','School Uniform Set - 10Y',14,40,'UniPro','UniPro','Complete school uniform set, Age 10',5500.00,0.00,10.00,'PCS',1,'2026-03-05 14:48:58');
/*!40000 ALTER TABLE `ezy_pos_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_privileges`
--

DROP TABLE IF EXISTS `ezy_pos_privileges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_privileges` (
  `priv_id` int NOT NULL AUTO_INCREMENT,
  `priv_userid` int NOT NULL,
  `priv_item` tinyint(1) NOT NULL DEFAULT '0',
  `priv_category` tinyint(1) NOT NULL DEFAULT '0',
  `priv_customer` tinyint(1) NOT NULL DEFAULT '0',
  `priv_supplier` tinyint(1) NOT NULL DEFAULT '0',
  `priv_store` tinyint(1) NOT NULL DEFAULT '0',
  `priv_staff` tinyint(1) NOT NULL DEFAULT '0',
  `priv_tax` tinyint(1) NOT NULL DEFAULT '0',
  `priv_register` tinyint(1) NOT NULL DEFAULT '0',
  `priv_expense_cat` tinyint(1) NOT NULL DEFAULT '0',
  `priv_grn` int NOT NULL DEFAULT '0',
  `priv_sales` int NOT NULL DEFAULT '0',
  `priv_expense` int NOT NULL DEFAULT '0',
  `priv_l_allGrn` int NOT NULL DEFAULT '0',
  `priv_l_stock` int NOT NULL DEFAULT '0',
  `priv_l_stockSupplierWise` int NOT NULL DEFAULT '0',
  `priv_l_stockLog` int NOT NULL DEFAULT '0',
  `priv_l_cheque` int NOT NULL DEFAULT '0',
  `priv_re_stock` int NOT NULL DEFAULT '0',
  `priv_re_stockLog` int NOT NULL DEFAULT '0',
  `priv_re_salesReport` int NOT NULL DEFAULT '0',
  `priv_re_monthlySalesReport` int NOT NULL DEFAULT '0',
  `priv_re_purchaseReport` int NOT NULL DEFAULT '0',
  `priv_re_expenseReport` int NOT NULL DEFAULT '0',
  `priv_re_todaySummary` int NOT NULL DEFAULT '0',
  `priv_re_profitLossReport` int NOT NULL DEFAULT '0',
  `priv_py_customerPayment` int NOT NULL DEFAULT '0',
  `priv_py_supplierPayment` int NOT NULL DEFAULT '0',
  `priv_bank` int NOT NULL DEFAULT '0',
  `priv_production` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`priv_id`)
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_privileges`
--

LOCK TABLES `ezy_pos_privileges` WRITE;
/*!40000 ALTER TABLE `ezy_pos_privileges` DISABLE KEYS */;
INSERT INTO `ezy_pos_privileges` VALUES (98,138,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
/*!40000 ALTER TABLE `ezy_pos_privileges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_production`
--

DROP TABLE IF EXISTS `ezy_pos_production`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_production` (
  `prod_id` int NOT NULL AUTO_INCREMENT,
  `prod_code` varchar(50) NOT NULL,
  `prod_date` date NOT NULL,
  `prod_output_item_id` int NOT NULL COMMENT 'Finished garment item',
  `prod_output_qty` double(10,2) NOT NULL DEFAULT '1.00',
  `prod_tailor_id` int DEFAULT NULL COMMENT 'Supplier ID if outsourced, NULL if in-house',
  `prod_type` enum('in-house','outsource') NOT NULL DEFAULT 'in-house',
  `prod_material_cost` double(30,2) NOT NULL DEFAULT '0.00' COMMENT 'Total raw material cost',
  `prod_tailoring_cost` double(30,2) NOT NULL DEFAULT '0.00',
  `prod_other_cost` double(30,2) NOT NULL DEFAULT '0.00',
  `prod_total_cost` double(30,2) NOT NULL DEFAULT '0.00' COMMENT 'material + tailoring + other',
  `prod_unit_cost` double(30,2) NOT NULL DEFAULT '0.00' COMMENT 'total_cost / output_qty',
  `prod_status` enum('Issued','In-Progress','Completed','Cancelled') NOT NULL DEFAULT 'Issued',
  `prod_notes` text,
  `prod_createdby` int NOT NULL,
  `prod_createdat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `prod_completedat` datetime DEFAULT NULL,
  PRIMARY KEY (`prod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_production`
--

LOCK TABLES `ezy_pos_production` WRITE;
/*!40000 ALTER TABLE `ezy_pos_production` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_production` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_production_costs`
--

DROP TABLE IF EXISTS `ezy_pos_production_costs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_production_costs` (
  `prodcost_id` int NOT NULL AUTO_INCREMENT,
  `prodcost_prod_id` int NOT NULL,
  `prodcost_description` varchar(250) NOT NULL,
  `prodcost_type` enum('tailoring','other') NOT NULL DEFAULT 'other',
  `prodcost_amount` double(30,2) NOT NULL,
  `prodcost_createdat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`prodcost_id`),
  KEY `idx_prod` (`prodcost_prod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_production_costs`
--

LOCK TABLES `ezy_pos_production_costs` WRITE;
/*!40000 ALTER TABLE `ezy_pos_production_costs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_production_costs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_production_materials`
--

DROP TABLE IF EXISTS `ezy_pos_production_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_production_materials` (
  `prodmat_id` int NOT NULL AUTO_INCREMENT,
  `prodmat_prod_id` int NOT NULL,
  `prodmat_item_id` int NOT NULL COMMENT 'Raw material item',
  `prodmat_qty` double(10,2) NOT NULL COMMENT 'Quantity consumed (yards, pcs etc)',
  `prodmat_unit_price` double(30,2) NOT NULL COMMENT 'Cost per unit from stock',
  `prodmat_total` double(30,2) NOT NULL COMMENT 'qty * unit_price',
  `prodmat_createdat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`prodmat_id`),
  KEY `idx_prod` (`prodmat_prod_id`),
  KEY `idx_item` (`prodmat_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_production_materials`
--

LOCK TABLES `ezy_pos_production_materials` WRITE;
/*!40000 ALTER TABLE `ezy_pos_production_materials` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_production_materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_sale`
--

DROP TABLE IF EXISTS `ezy_pos_sale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_sale` (
  `sale_id` int NOT NULL AUTO_INCREMENT,
  `sale_cus_id` int NOT NULL,
  `sale_grandtotal` double(50,2) NOT NULL,
  `sale_subtotal` double(50,2) NOT NULL,
  `sale_discount` double(5,2) NOT NULL,
  `sale_less` double(10,2) NOT NULL,
  `sale_createdby` int NOT NULL,
  `sale_date` date NOT NULL,
  `sale_location` int NOT NULL,
  `sale_status` tinyint NOT NULL DEFAULT '1',
  `sale_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sale_id`),
  KEY `idx_sale_cus_id` (`sale_cus_id`),
  KEY `idx_sale_date` (`sale_date`),
  KEY `idx_sale_status` (`sale_status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_sale`
--

LOCK TABLES `ezy_pos_sale` WRITE;
/*!40000 ALTER TABLE `ezy_pos_sale` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_sale` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_sale_item`
--

DROP TABLE IF EXISTS `ezy_pos_sale_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_sale_item` (
  `saleitem_id` int NOT NULL AUTO_INCREMENT,
  `saleitem_sale_id` int NOT NULL,
  `saleitem_item_id` int NOT NULL,
  `saleitem_price` double(50,2) NOT NULL,
  `saleitem_quantity` double(250,2) NOT NULL,
  `saleitem_total` double(50,2) NOT NULL,
  `saleitem_discount` double(5,2) NOT NULL,
  `saleitem_ctreatedat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`saleitem_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_sale_item`
--

LOCK TABLES `ezy_pos_sale_item` WRITE;
/*!40000 ALTER TABLE `ezy_pos_sale_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_sale_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_staffs`
--

DROP TABLE IF EXISTS `ezy_pos_staffs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_staffs` (
  `staff_id` int NOT NULL AUTO_INCREMENT,
  `staff_name` varchar(250) NOT NULL,
  `staff_address` varchar(250) NOT NULL,
  `staff_contact` varchar(20) NOT NULL,
  `staff_basicsalary` double(50,2) NOT NULL,
  `staff_food` double(50,2) NOT NULL,
  `staff_travel` double(50,2) NOT NULL,
  `staff_other` double(50,2) NOT NULL,
  `staff_otperhour` double(50,2) NOT NULL,
  `staff_status` int NOT NULL DEFAULT '1',
  `staff_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`staff_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_staffs`
--

LOCK TABLES `ezy_pos_staffs` WRITE;
/*!40000 ALTER TABLE `ezy_pos_staffs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_staffs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_stock`
--

DROP TABLE IF EXISTS `ezy_pos_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_stock` (
  `stock_id` int NOT NULL AUTO_INCREMENT,
  `stock_itm_id` int NOT NULL,
  `stock_qty` double(30,2) NOT NULL,
  `stock_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`stock_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_stock`
--

LOCK TABLES `ezy_pos_stock` WRITE;
/*!40000 ALTER TABLE `ezy_pos_stock` DISABLE KEYS */;
INSERT INTO `ezy_pos_stock` VALUES (1,1,0.00,1),(2,2,0.00,1),(3,3,0.00,1),(4,4,0.00,1),(5,5,0.00,1),(6,6,0.00,1),(7,7,0.00,1),(8,8,0.00,1),(9,9,0.00,1),(10,10,0.00,1),(11,11,0.00,1),(12,12,0.00,1),(13,13,0.00,1),(14,14,0.00,1),(15,15,0.00,1),(16,16,0.00,1),(17,17,0.00,1),(18,18,0.00,1),(19,19,0.00,1),(20,20,0.00,1),(21,21,0.00,1),(22,22,0.00,1),(23,23,0.00,1),(24,24,0.00,1);
/*!40000 ALTER TABLE `ezy_pos_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_stock_log`
--

DROP TABLE IF EXISTS `ezy_pos_stock_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_stock_log` (
  `stocklog_id` int NOT NULL AUTO_INCREMENT,
  `stocklog_itmid` int NOT NULL,
  `stocklog_qty` double(250,2) NOT NULL,
  `stocklog_grnID` int NOT NULL,
  `stocklog_saleID` int NOT NULL,
  `stocklog_return_sup_retrnID` int NOT NULL,
  `stocklog_return_supID` int NOT NULL DEFAULT '0',
  `stocklog_return_cus_retrnID` int NOT NULL,
  `stocklog_return_cusID` int NOT NULL DEFAULT '0',
  `stocklog_status` tinyint(1) NOT NULL,
  `stocklog_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`stocklog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_stock_log`
--

LOCK TABLES `ezy_pos_stock_log` WRITE;
/*!40000 ALTER TABLE `ezy_pos_stock_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_stock_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_store_items`
--

DROP TABLE IF EXISTS `ezy_pos_store_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_store_items` (
  `storeitem_id` int NOT NULL AUTO_INCREMENT,
  `storeitem_storeid` int NOT NULL,
  `storeitem_itemid` int NOT NULL,
  `storeitem_grnid` int NOT NULL,
  PRIMARY KEY (`storeitem_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_store_items`
--

LOCK TABLES `ezy_pos_store_items` WRITE;
/*!40000 ALTER TABLE `ezy_pos_store_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_store_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_stores`
--

DROP TABLE IF EXISTS `ezy_pos_stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_stores` (
  `store_id` int NOT NULL AUTO_INCREMENT,
  `store_name` varchar(200) NOT NULL,
  `store_address` varchar(250) NOT NULL,
  `store_address2` varchar(250) NOT NULL,
  `store_tel` varchar(15) NOT NULL,
  `store_mobile` varchar(15) NOT NULL,
  `store_mobile2` varchar(15) NOT NULL,
  `store_fax` varchar(15) NOT NULL,
  `store_email` varchar(200) NOT NULL,
  `store_status` int NOT NULL DEFAULT '1',
  `store_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`store_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_stores`
--

LOCK TABLES `ezy_pos_stores` WRITE;
/*!40000 ALTER TABLE `ezy_pos_stores` DISABLE KEYS */;
INSERT INTO `ezy_pos_stores` VALUES (6,'Main Store','Handloom Gallery','','0000000000','0000000000','','','',1,'2026-03-06 12:57:56');
/*!40000 ALTER TABLE `ezy_pos_stores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_subcategories`
--

DROP TABLE IF EXISTS `ezy_pos_subcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_subcategories` (
  `sub_cat_id` int NOT NULL AUTO_INCREMENT,
  `parent_cat_id` int NOT NULL,
  `sub_cat_name` varchar(250) NOT NULL,
  `sub_cat_status` int NOT NULL DEFAULT '1',
  `sub_cat_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sub_cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_subcategories`
--

LOCK TABLES `ezy_pos_subcategories` WRITE;
/*!40000 ALTER TABLE `ezy_pos_subcategories` DISABLE KEYS */;
INSERT INTO `ezy_pos_subcategories` VALUES (4,1,'Linen',1,'2026-03-05 14:48:27'),(3,1,'Polyester',1,'2026-03-05 14:48:27'),(2,1,'Silk',1,'2026-03-05 14:48:27'),(1,1,'Cotton',1,'2026-03-05 14:48:27'),(5,1,'Wool',1,'2026-03-05 14:48:27'),(6,1,'Denim',1,'2026-03-05 14:48:27'),(7,1,'Chiffon',1,'2026-03-05 14:48:27'),(8,1,'Satin',1,'2026-03-05 14:48:27'),(9,2,'Cotton Thread',1,'2026-03-05 14:48:27'),(10,2,'Silk Thread',1,'2026-03-05 14:48:27'),(11,2,'Polyester Thread',1,'2026-03-05 14:48:27'),(12,3,'Plastic Buttons',1,'2026-03-05 14:48:27'),(13,3,'Metal Buttons',1,'2026-03-05 14:48:27'),(14,3,'Snaps & Hooks',1,'2026-03-05 14:48:27'),(15,4,'Metal Zippers',1,'2026-03-05 14:48:27'),(16,4,'Nylon Zippers',1,'2026-03-05 14:48:27'),(17,4,'Lace Trim',1,'2026-03-05 14:48:27'),(18,4,'Ribbon',1,'2026-03-05 14:48:27'),(19,5,'Fusible Interfacing',1,'2026-03-05 14:48:27'),(20,5,'Polyester Lining',1,'2026-03-05 14:48:27'),(21,6,'Formal Shirts',1,'2026-03-05 14:48:27'),(22,6,'Casual Shirts',1,'2026-03-05 14:48:27'),(23,7,'Formal Trousers',1,'2026-03-05 14:48:27'),(24,7,'Casual Trousers',1,'2026-03-05 14:48:27'),(25,7,'Jeans',1,'2026-03-05 14:48:27'),(26,8,'Two-Piece Suit',1,'2026-03-05 14:48:27'),(27,8,'Three-Piece Suit',1,'2026-03-05 14:48:27'),(28,9,'Casual Dresses',1,'2026-03-05 14:48:27'),(29,9,'Evening Dresses',1,'2026-03-05 14:48:27'),(30,9,'Traditional',1,'2026-03-05 14:48:27'),(31,10,'Formal Blouses',1,'2026-03-05 14:48:27'),(32,10,'Casual Blouses',1,'2026-03-05 14:48:27'),(33,11,'A-Line Skirts',1,'2026-03-05 14:48:27'),(34,11,'Pencil Skirts',1,'2026-03-05 14:48:27'),(35,12,'Boys Wear',1,'2026-03-05 14:48:27'),(36,12,'Girls Wear',1,'2026-03-05 14:48:27'),(37,13,'Ties & Bowties',1,'2026-03-05 14:48:27'),(38,13,'Scarves',1,'2026-03-05 14:48:27'),(39,13,'Belts',1,'2026-03-05 14:48:27'),(40,14,'School Uniforms',1,'2026-03-05 14:48:27'),(41,14,'Office Uniforms',1,'2026-03-05 14:48:27');
/*!40000 ALTER TABLE `ezy_pos_subcategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_sup_balnce`
--

DROP TABLE IF EXISTS `ezy_pos_sup_balnce`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_sup_balnce` (
  `supbal_id` int NOT NULL AUTO_INCREMENT,
  `supbal_supid` int NOT NULL,
  `supbal_amount` double(50,2) NOT NULL,
  PRIMARY KEY (`supbal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_sup_balnce`
--

LOCK TABLES `ezy_pos_sup_balnce` WRITE;
/*!40000 ALTER TABLE `ezy_pos_sup_balnce` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_sup_balnce` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_sup_cash_payment`
--

DROP TABLE IF EXISTS `ezy_pos_sup_cash_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_sup_cash_payment` (
  `supcash_id` int NOT NULL AUTO_INCREMENT,
  `supcash_supid` int NOT NULL,
  `supcash_amount` float NOT NULL,
  `supcash_date` datetime NOT NULL,
  `supcash_status` int NOT NULL,
  PRIMARY KEY (`supcash_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_sup_cash_payment`
--

LOCK TABLES `ezy_pos_sup_cash_payment` WRITE;
/*!40000 ALTER TABLE `ezy_pos_sup_cash_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_sup_cash_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_sup_cheque`
--

DROP TABLE IF EXISTS `ezy_pos_sup_cheque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_sup_cheque` (
  `sup_cheque_id` int NOT NULL AUTO_INCREMENT,
  `sup_cheque_supid` varchar(250) DEFAULT NULL,
  `sup_cheque_grnid` int NOT NULL,
  `sup_cheque_amount` double(50,2) NOT NULL,
  `sup_cheque_bank` varchar(250) NOT NULL,
  `sup_cheque_num` varchar(250) NOT NULL,
  `sup_cheque_our0_cuscheqtblid` tinyint(1) NOT NULL,
  `sup_cheque_date` date NOT NULL,
  `sup_cheque_givendate` date NOT NULL,
  `sup_cheque_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`sup_cheque_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_sup_cheque`
--

LOCK TABLES `ezy_pos_sup_cheque` WRITE;
/*!40000 ALTER TABLE `ezy_pos_sup_cheque` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_sup_cheque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_sup_chequelog`
--

DROP TABLE IF EXISTS `ezy_pos_sup_chequelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_sup_chequelog` (
  `supchqlog_id` int NOT NULL AUTO_INCREMENT,
  `supchqlog_chqid` int NOT NULL,
  `supchqlog_grnid` int NOT NULL,
  `supchqlog_amnt` double(50,2) NOT NULL,
  `supchqlog_givndate` datetime NOT NULL,
  `supchqlog_status` varchar(10) NOT NULL,
  PRIMARY KEY (`supchqlog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_sup_chequelog`
--

LOCK TABLES `ezy_pos_sup_chequelog` WRITE;
/*!40000 ALTER TABLE `ezy_pos_sup_chequelog` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_sup_chequelog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_sup_payment`
--

DROP TABLE IF EXISTS `ezy_pos_sup_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_sup_payment` (
  `sup_pay_id` int NOT NULL AUTO_INCREMENT,
  `sup_pay_grnid` int NOT NULL,
  `sup_pay_cash` double(50,2) NOT NULL,
  `sup_pay_credit` double(50,2) NOT NULL,
  PRIMARY KEY (`sup_pay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_sup_payment`
--

LOCK TABLES `ezy_pos_sup_payment` WRITE;
/*!40000 ALTER TABLE `ezy_pos_sup_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_sup_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_sup_paymentcashlog`
--

DROP TABLE IF EXISTS `ezy_pos_sup_paymentcashlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_sup_paymentcashlog` (
  `paycashlog_id` int NOT NULL AUTO_INCREMENT,
  `paycashlog_grnid` int NOT NULL,
  `paycashlog_cashid` int NOT NULL,
  `paycashlog_amount` double(50,2) NOT NULL,
  `paycashlog_date` date NOT NULL,
  `paycashlog_status` int NOT NULL,
  PRIMARY KEY (`paycashlog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_sup_paymentcashlog`
--

LOCK TABLES `ezy_pos_sup_paymentcashlog` WRITE;
/*!40000 ALTER TABLE `ezy_pos_sup_paymentcashlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_sup_paymentcashlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_sup_return`
--

DROP TABLE IF EXISTS `ezy_pos_sup_return`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_sup_return` (
  `suprtrn_id` int NOT NULL AUTO_INCREMENT,
  `suprtrn_supID` int NOT NULL,
  `suprtrn_totalRtrn` double(30,2) NOT NULL,
  `suprtrn_createdby` int NOT NULL,
  `suprtrn_status` tinyint(1) NOT NULL DEFAULT '1',
  `suprtrn_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`suprtrn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_sup_return`
--

LOCK TABLES `ezy_pos_sup_return` WRITE;
/*!40000 ALTER TABLE `ezy_pos_sup_return` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_sup_return` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_sup_return_item`
--

DROP TABLE IF EXISTS `ezy_pos_sup_return_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_sup_return_item` (
  `supRtrn_itm_id` int NOT NULL AUTO_INCREMENT,
  `supRtrn_itm_supRtrnID` int NOT NULL,
  `supRtrn_itm_itmID` int NOT NULL,
  `supRtrn_itm_rQty` double(250,2) NOT NULL,
  `supRtrn_itm_rAmount` double(30,2) NOT NULL,
  `supRtrn_itm_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`supRtrn_itm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_sup_return_item`
--

LOCK TABLES `ezy_pos_sup_return_item` WRITE;
/*!40000 ALTER TABLE `ezy_pos_sup_return_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_sup_return_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_suppliers`
--

DROP TABLE IF EXISTS `ezy_pos_suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_suppliers` (
  `sup_id` int NOT NULL AUTO_INCREMENT,
  `sup_name` varchar(200) NOT NULL,
  `sup_address` varchar(250) NOT NULL,
  `sup_contact` int NOT NULL,
  `sup_balance` double(50,2) NOT NULL,
  `sup_status` int NOT NULL DEFAULT '1',
  `sup_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sup_id`)
) ENGINE=MyISAM AUTO_INCREMENT=158 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_suppliers`
--

LOCK TABLES `ezy_pos_suppliers` WRITE;
/*!40000 ALTER TABLE `ezy_pos_suppliers` DISABLE KEYS */;
INSERT INTO `ezy_pos_suppliers` VALUES (6,'Nimali Stitching House','Maharagama, Sri Lanka',772345678,0.00,1,'2026-03-05 14:49:13'),(5,'Master Tailor Perera','Nugegoda, Sri Lanka',771234567,0.00,1,'2026-03-05 14:49:13'),(4,'ZipFast Industries','Colombo, Sri Lanka',113456789,0.00,1,'2026-03-05 14:49:13'),(3,'ButtonKing Supplies','Galle, Sri Lanka',912345678,0.00,1,'2026-03-05 14:49:13'),(2,'SilkWorld Imports','Kandy, Sri Lanka',812345678,0.00,1,'2026-03-05 14:49:13'),(1,'TextilePro Ltd','Colombo, Sri Lanka',112345678,0.00,1,'2026-03-05 14:49:13');
/*!40000 ALTER TABLE `ezy_pos_suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_taxs`
--

DROP TABLE IF EXISTS `ezy_pos_taxs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_taxs` (
  `tax_id` int NOT NULL AUTO_INCREMENT,
  `tax_name` varchar(200) NOT NULL,
  `tax_value` double(50,2) NOT NULL,
  `tax_status` int NOT NULL DEFAULT '1',
  `tax_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tax_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_taxs`
--

LOCK TABLES `ezy_pos_taxs` WRITE;
/*!40000 ALTER TABLE `ezy_pos_taxs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_taxs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_user_store`
--

DROP TABLE IF EXISTS `ezy_pos_user_store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_user_store` (
  `user_store_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `store_id` int NOT NULL,
  `user_store_status` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_user_store`
--

LOCK TABLES `ezy_pos_user_store` WRITE;
/*!40000 ALTER TABLE `ezy_pos_user_store` DISABLE KEYS */;
INSERT INTO `ezy_pos_user_store` VALUES (6,107,2,0),(7,111,1,0),(8,112,4,0),(9,113,3,0),(10,114,5,0),(15,116,3,0),(16,116,1,0),(17,117,4,0),(18,118,4,0),(19,119,1,0),(20,120,3,0),(21,121,5,0),(30,124,4,0),(32,123,3,0),(33,125,5,0),(38,128,1,0),(54,129,1,0),(55,129,3,0),(56,129,5,0),(63,102,1,1),(64,102,3,1),(65,102,4,1),(66,102,5,1),(78,131,1,1),(79,131,3,1),(80,131,5,1),(81,132,1,0),(82,132,5,0),(88,130,1,1),(89,130,3,1),(90,130,5,1),(105,122,1,0),(112,133,1,0),(113,115,1,1),(114,115,3,1),(115,115,4,1),(116,115,5,1),(118,127,5,0),(119,126,3,0),(125,134,3,1);
/*!40000 ALTER TABLE `ezy_pos_user_store` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_users`
--

DROP TABLE IF EXISTS `ezy_pos_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_username` varchar(250) NOT NULL,
  `user_name` varchar(250) NOT NULL,
  `user_password` varchar(250) NOT NULL,
  `user_role` int NOT NULL,
  `user_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=139 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_users`
--

LOCK TABLES `ezy_pos_users` WRITE;
/*!40000 ALTER TABLE `ezy_pos_users` DISABLE KEYS */;
INSERT INTO `ezy_pos_users` VALUES (138,'admin','System Admin','26a91342190d515231d7238b0c5438e1',1,1);
/*!40000 ALTER TABLE `ezy_pos_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_warehouse`
--

DROP TABLE IF EXISTS `ezy_pos_warehouse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_warehouse` (
  `wh_id` int NOT NULL AUTO_INCREMENT,
  `wh_name` varchar(200) NOT NULL,
  `wh_address` varchar(250) NOT NULL,
  `wh_address2` varchar(250) NOT NULL,
  `wh_tel` varchar(15) NOT NULL,
  `wh_mobile` varchar(15) NOT NULL,
  `wh_mobile2` varchar(15) NOT NULL,
  `wh_fax` varchar(15) NOT NULL,
  `wh_email` varchar(200) NOT NULL,
  `wh_status` int NOT NULL DEFAULT '1',
  `wh_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`wh_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_warehouse`
--

LOCK TABLES `ezy_pos_warehouse` WRITE;
/*!40000 ALTER TABLE `ezy_pos_warehouse` DISABLE KEYS */;
INSERT INTO `ezy_pos_warehouse` VALUES (11,'Main Warehouse','Handloom Gallery','','0000000000','0000000000','0000000000','','',1,'2026-03-06 12:58:03');
/*!40000 ALTER TABLE `ezy_pos_warehouse` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-06 13:07:02
