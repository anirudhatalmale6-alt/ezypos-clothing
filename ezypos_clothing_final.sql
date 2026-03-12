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
INSERT INTO `ezy_pos_categories` VALUES (1,'FABRIC',1,1,NOW()),(2,'FASHION',0,1,NOW()),(3,'FEMALE',0,1,NOW()),(4,'HOUSEHOLD',0,1,NOW()),(5,'MALE',0,1,NOW());
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_cur_grn_vs_sale`
--

LOCK TABLES `ezy_pos_cur_grn_vs_sale` WRITE;
/*!40000 ALTER TABLE `ezy_pos_cur_grn_vs_sale` DISABLE KEYS */;
-- Test data cleared from `ezy_pos_cur_grn_vs_sale`
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
  `cur_store_id` int NOT NULL DEFAULT '0',
  `cur_grnQty` double(250,2) NOT NULL,
  `cur_grnPrice` double(30,2) NOT NULL,
  `cur_grnTotal` double(30,2) NOT NULL,
  `cur_currentQTY` double(250,2) NOT NULL,
  `cur_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cur_id`),
  KEY `idx_curqty_item_store` (`cur_itmID`,`cur_store_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_currentqtywithgrn`
--

LOCK TABLES `ezy_pos_currentqtywithgrn` WRITE;
/*!40000 ALTER TABLE `ezy_pos_currentqtywithgrn` DISABLE KEYS */;
-- Cleared test data
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
INSERT INTO `ezy_pos_customers` VALUES (288,'CASH','Walk-in Customer','0000000000',0.00,0.00,1,'2026-03-06 12:57:26');
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_grn_item`
--

LOCK TABLES `ezy_pos_grn_item` WRITE;
/*!40000 ALTER TABLE `ezy_pos_grn_item` DISABLE KEYS */;
-- Test data cleared from `ezy_pos_grn_item`
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
  `grn_location` int NOT NULL DEFAULT '0',
  `grn_date` date NOT NULL,
  `grn_status` tinyint(1) NOT NULL,
  `grn_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`grn_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_grns`
--

LOCK TABLES `ezy_pos_grns` WRITE;
/*!40000 ALTER TABLE `ezy_pos_grns` DISABLE KEYS */;
-- Test data cleared from `ezy_pos_grns`
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
INSERT INTO `ezy_pos_items` VALUES (1,'KBKCOFB03','BATIK COTTON 3 YARD FABRIC',1,1,'','','',0.00,0.00,0.00,'pcs',1,NOW()),(2,'RTBKSI002','BATIK SILK 2 YARD FABRIC',1,2,'','','',0.00,0.00,0.00,'pcs',1,NOW()),(3,'RTBKSI003','BATIK SILK 3 YARD FABRIC',1,2,'','','',17000.00,0.00,0.00,'pcs',1,NOW()),(4,'RTBSCM003','BATIK SILKCOTTON MIX 3 YARD FABRIC',1,3,'','','',0.00,0.00,0.00,'pcs',1,NOW()),(5,'KBKSLFB01','BATIK SUPERLONE 1 YARD FABRIC',1,4,'','','',0.00,0.00,0.00,'pcs',1,NOW()),(6,'KBKSLFB02','BATIK SUPERLONE 2 YARD FABRIC',1,4,'','','',0.00,0.00,0.00,'pcs',1,NOW()),(7,'KBKSLFB25','BATIK SUPERLONE 2.5 YARD FABRIC',1,4,'','','',0.00,0.00,0.00,'pcs',1,NOW()),(8,'RTBKSV002','BATIK SUPERVOIL 2 YARD FABRIC',1,5,'','','',0.00,0.00,0.00,'pcs',1,NOW()),(9,'KBKSVFB25','BATIK SUPERVOIL 2.5 YARD FABRIC',1,5,'','','',0.00,0.00,0.00,'pcs',1,NOW()),(10,'KBKSVFB03','BATIK SUPERVOIL 3 YARD FABRIC',1,5,'','','',0.00,0.00,0.00,'pcs',1,NOW()),(11,'RTBKSV003','BATIK SUPERVOIL 3 YARD FABRIC',1,5,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(12,'RTBKVC002','BATIK VISCOS 2 YARD FABRIC',1,6,'','','',0.00,0.00,0.00,'pcs',1,NOW()),(13,'ACOHLDFB1','COTTON HANDLOOM DESIGN FABRIC',1,7,'','','',1650.00,0.00,0.00,'pcs',1,NOW()),(14,'SRCOHLD01','COTTON HANDLOOM DESIGN FABRIC',1,7,'','','',1650.00,0.00,0.00,'pcs',1,NOW()),(15,'SFCOHLD01','COTTON HANDLOOM DESIGN FABRIC',1,7,'','','',1650.00,0.00,0.00,'pcs',1,NOW()),(16,'SRCOHLE01','COTTON HANDLOOM EMBOSS FABRIC',1,7,'','','',1650.00,0.00,0.00,'pcs',1,NOW()),(17,'SFCOHLP01','COTTON HANDLOOM PLAIN FABRIC',1,7,'','','',1550.00,0.00,0.00,'pcs',1,NOW()),(18,'ACOHLPFB1','COTTON HANDLOOM PLAIN FABRIC',1,7,'','','',1550.00,0.00,0.00,'pcs',1,NOW()),(19,'SFCOHLW01','COTTON HANDLOOM WHITE FABRIC',1,7,'','','',1550.00,0.00,0.00,'pcs',1,NOW()),(20,'HSKHLDFB1','SLIK HANDLOOM DESIGN FABRIC',1,8,'','','',1850.00,0.00,0.00,'pcs',1,NOW()),(21,'ASKHLDFB1','SLIK HANDLOOM DESIGN FABRIC',1,8,'','','',1850.00,0.00,0.00,'pcs',1,NOW()),(22,'ASKHLPFB1','SLIK HANDLOOM PLAIN FABRIC',1,8,'','','',1750.00,0.00,0.00,'pcs',1,NOW()),(23,'HSKHLPFB1','SLIK HANDLOOM PLAIN FABRIC',1,8,'','','',1750.00,0.00,0.00,'pcs',1,NOW()),(24,'CJERING01','EAR RING',2,9,'','','',250.00,0.00,0.00,'pcs',1,NOW()),(25,'CJJLERY02','JEWELLERY',2,10,'','','',1550.00,0.00,0.00,'pcs',1,NOW()),(26,'CJJLERY01','JEWELLERY',2,10,'','','',1100.00,0.00,0.00,'pcs',1,NOW()),(27,'CJSCLIP01','SAREE CLIP',2,10,'','','',375.00,0.00,0.00,'pcs',1,NOW()),(28,'RTNSBLXL1','BATIK BLOUSE',3,11,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(29,'RTNSBLL01','BATIK BLOUSE',3,11,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(30,'RTNBILNM1','BATIK BLOUSE',3,11,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(31,'RTNSBL2X1','BATIK BLOUSE',3,11,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(32,'KNBCBL2X1','BATIK COTTON BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(33,'SBNBCBLLR1','BATIK COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(34,'SBNBCBLLR2','BATIK COTTON BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(35,'SBNBCBLME1','BATIK COTTON BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(36,'OSBCBLLR1','BATIK COTTON BLOUSE',3,11,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(37,'SMNBCBL3X1','BATIK COTTON BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(38,'KNBCBLLR2','BATIK COTTON BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(39,'KNBCBLLR1','BATIK COTTON BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(40,'KNBCBLME1','BATIK COTTON BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(41,'SMNBCBL2X1','BATIK COTTON BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(42,'SMNBCBLME1','BATIK COTTON BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(43,'SMNBIBLME1','BATIK SUPERLONE BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(44,'KNBLBLLR2','BATIK SUPERLONE BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(45,'KNBLBLLR4','BATIK SUPERLONE BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(46,'PBNBIBLME1','BATIK SUPERLONE BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(47,'KNBLBL2X2','BATIK SUPERLONE BLOUSE',3,11,'','','',4550.00,0.00,0.00,'pcs',1,NOW()),(48,'KNBLBLME3','BATIK SUPERLONE BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(49,'KNBLBLLR3','BATIK SUPERLONE BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(50,'KNBLBLLR5','BATIK SUPERLONE BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(51,'KNBLBLSM2','BATIK SUPERLONE BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(52,'KNBLBLME2','BATIK SUPERLONE BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(53,'KNBLBLME1','BATIK SUPERLONE BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(54,'KNBLBLLR1','BATIK SUPERLONE BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(55,'KNBLBLSM1','BATIK SUPERLONE BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(56,'KNBIBLSM1','BATIK SUPERVOIL BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(57,'KNBIBLLR4','BATIK SUPERVOIL BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(58,'KNBIBLME1','BATIK SUPERVOIL BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(59,'KNBIBLLR1','BATIK SUPERVOIL BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(60,'RTNBIBLSM1','BATIK SUPERVOIL BLOUSE',3,11,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(61,'RTNBIBLME1','BATIK SUPERVOIL BLOUSE',3,11,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(62,'SMNBIBLSM1','BATIK SUPERVOIL BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(63,'RTNBIBL01','BATIK SUPERVOIL BLOUSE',3,11,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(64,'KNBIBL2X2','BATIK SUPERVOIL BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(65,'KNBIBLLR3','BATIK SUPERVOIL BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(66,'KNBIBL2X1','BATIK SUPERVOIL BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(67,'KNBIBL3X1','BATIK SUPERVOIL BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(68,'PBNBIBLLR1','BATIK SUPERVOIL BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(69,'PBNBIBLSM1','BATIK SUPERVOIL BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(70,'KNBIBL1X1','BATIK SUPERVOIL BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(71,'KNBIBLLR2','BATIK SUPERVOIL BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(72,'OSSALEBL2','BLOUSE',3,11,'','','',2000.00,0.00,0.00,'pcs',1,NOW()),(73,'OSSALEBL1','BLOUSE',3,11,'','','',1500.00,0.00,0.00,'pcs',1,NOW()),(74,'OSCOBLME1','COTTON BLOUSE',3,11,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(75,'SRKCBLLR1','COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(76,'OSCOBL1X1','COTTON BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(77,'OSCOBL4X4','COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(78,'OSCOBLME7','COTTON BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(79,'OSCOBL2X7','COTTON BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(80,'OSCOBL2X3','COTTON BLOUSE',3,11,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(81,'OSCOBL3X7','COTTON BLOUSE',3,11,'','','',3350.00,0.00,0.00,'pcs',1,NOW()),(82,'OSCOBL2X5','COTTON BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(83,'OSCOBLSM12','COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(84,'SFKCOBLLR2','COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(85,'SFKCBL1X1','COTTON BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(86,'OSCOBLSM2','COTTON BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(87,'OSCOBL3X2','COTTON BLOUSE',3,11,'','','',3900.00,0.00,0.00,'pcs',1,NOW()),(88,'SFKCOBL2X3','COTTON BLOUSE',3,11,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(89,'OSCOBL2X9','COTTON BLOUSE',3,11,'','','',3550.00,0.00,0.00,'pcs',1,NOW()),(90,'SFKCOBLME2','COTTON BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(91,'SFKCOBLLR1','COTTON BLOUSE',3,11,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(92,'SFKCOBL1X1','COTTON BLOUSE',3,11,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(93,'SFKCOBL4X1','COTTON BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(94,'OSCOBL1X5','COTTON BLOUSE',3,11,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(95,'OSCOBLME11','COTTON BLOUSE',3,11,'','','',2500.00,0.00,0.00,'pcs',1,NOW()),(96,'OSCOBLME12','COTTON BLOUSE',3,11,'','','',1850.00,0.00,0.00,'pcs',1,NOW()),(97,'OSCOBL5X2','COTTON BLOUSE',3,11,'','','',3350.00,0.00,0.00,'pcs',1,NOW()),(98,'SRKCOBL3X1','COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(99,'OSCOBLME4','COTTON BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(100,'SRKCBL3X1','COTTON BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(101,'SFKCOBLME1','COTTON BLOUSE',3,11,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(102,'SFKCOBL3X1','COTTON BLOUSE',3,11,'','','',5100.00,0.00,0.00,'pcs',1,NOW()),(103,'SFKCBLME3','COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(104,'SFKCOBL3X3','COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(105,'OSCOBLLR2','COTTON BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(106,'OSCOBLSM4','COTTON BLOUSE',3,11,'','','',3100.00,0.00,0.00,'pcs',1,NOW()),(107,'OSCOBLLR6','COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(108,'OSCOBL2X10','COTTON BLOUSE',3,11,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(109,'OSCOBL3X6','COTTON BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(110,'OSCOBL4X3','COTTON BLOUSE',3,11,'','','',3350.00,0.00,0.00,'pcs',1,NOW()),(111,'OSCOBLME5','COTTON BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(112,'OSCOBLME8','COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(113,'OSCOBL3X5','COTTON BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(114,'SFKCBLLR1','COTTON BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(115,'SFKCBL2X1','COTTON BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(116,'OSCOBLSM10','COTTON BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(117,'OSCOBLLR1','COTTON BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(118,'OSCOBL2X6','COTTON BLOUSE',3,11,'','','',2350.00,0.00,0.00,'pcs',1,NOW()),(119,'OSCOBLSM6','COTTON BLOUSE',3,11,'','','',2350.00,0.00,0.00,'pcs',1,NOW()),(120,'SFKCOBL1X3','COTTON BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(121,'OSCOBL1X6','COTTON BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(122,'OSCOBLLR3','COTTON BLOUSE',3,11,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(123,'OSCOBL1X2','COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(124,'OSCOBLSM3','COTTON BLOUSE',3,11,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(125,'OSCOBLSM5','COTTON BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(126,'OSCOBL4X6','COTTON BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(127,'SFKCOBL2X2','COTTON BLOUSE',3,11,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(128,'OSCOBLME14','COTTON BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(129,'OSCOBL2X2','COTTON BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(130,'OSCOBL2X4','COTTON BLOUSE',3,11,'','','',3100.00,0.00,0.00,'pcs',1,NOW()),(131,'SFKCOBL2X1','COTTON BLOUSE',3,11,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(132,'SFKCOBL1X2','COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(133,'AKCOBL5X1','COTTON BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(134,'OSCOBLME10','COTTON BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(135,'OSCOBL3X3','COTTON BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(136,'SFKCBL3X1','COTTON BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(137,'SFKCBLME2','COTTON BLOUSE',3,11,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(138,'OSCOBLLR4','COTTON BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(139,'SFKCOBLLR3','COTTON BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(140,'OSCOBL1X3','COTTON BLOUSE',3,11,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(141,'OSCOBLME2','COTTON BLOUSE',3,11,'','','',2250.00,0.00,0.00,'pcs',1,NOW()),(142,'OSCOBL2X11','COTTON BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(143,'SRKCOBLLR1','COTTON BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(144,'OSCOBL3X4','COTTON BLOUSE',3,11,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(145,'OSCOBLSM1','COTTON BLOUSE',3,11,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(146,'OSCOBLME6','COTTON BLOUSE',3,11,'','','',3100.00,0.00,0.00,'pcs',1,NOW()),(147,'OSCOBL1X4','COTTON BLOUSE',3,11,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(148,'SFKCOBL3X2','COTTON BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(149,'SRKCBL1X1','COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(150,'OSCOBL4X7','COTTON BLOUSE',3,11,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(151,'OSCOBL3X1','COTTON BLOUSE',3,11,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(152,'OSCOBLME3','COTTON BLOUSE',3,11,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(153,'OSCOBL5X1','COTTON BLOUSE',3,11,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(154,'OSCOBLME13','COTTON BLOUSE',3,11,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(155,'OSCOBL6X2','COTTON BLOUSE',3,11,'','','',3350.00,0.00,0.00,'pcs',1,NOW()),(156,'OSCOBLLR5','COTTON BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(157,'OSCOBL4X1','COTTON BLOUSE',3,11,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(158,'OSCOBL4X2','COTTON BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(159,'SRKCBL2X1','COTTON BLOUSE',3,11,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(160,'OSCOBLSM11','COTTON BLOUSE',3,11,'','','',3350.00,0.00,0.00,'pcs',1,NOW()),(161,'OSCOBLSM9','COTTON BLOUSE',3,11,'','','',3550.00,0.00,0.00,'pcs',1,NOW()),(162,'SFKCBLME1','COTTON BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(163,'OSCOBL2X1','COTTON BLOUSE',3,11,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(164,'OSCOBLME9','COTTON BLOUSE',3,11,'','','',4350.00,0.00,0.00,'pcs',1,NOW()),(165,'OSCOBL6X1','COTTON BLOUSE',3,11,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(166,'OSCOBL4X5','COTTON BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(167,'OSCOBLSM7','COTTON BLOUSE',3,11,'','','',2500.00,0.00,0.00,'pcs',1,NOW()),(168,'OSCOBLSM8','COTTON BLOUSE',3,11,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(169,'SRKCOBL2X1','COTTON BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(170,'OSCOBL2X8','COTTON BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(171,'OSSIBLME8','SILK BLOUSE',3,11,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(172,'OSSIBL1X2','SILK BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(173,'OSSIBLSM3','SILK BLOUSE',3,11,'','','',2850.00,0.00,0.00,'pcs',1,NOW()),(174,'AKSIBL2X3','SILK BLOUSE',3,11,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(175,'OSSIBLME1','SILK BLOUSE',3,11,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(176,'OSSIBLME4','SILK BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(177,'OSSIBL2X2','SILK BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(178,'OSSIBL3X3','SILK BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(179,'OSSIBL1X4','SILK BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(180,'OSSIBL3X2','SILK BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(181,'AKSIBL2X2','SILK BLOUSE',3,11,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(182,'AKSIBLLR2','SILK BLOUSE',3,11,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(183,'AKSIBL4X1','SILK BLOUSE',3,11,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(184,'OSSIBLSM6','SILK BLOUSE',3,11,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(185,'AKSIBLME3','SILK BLOUSE',3,11,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(186,'OSSIBLME10','SILK BLOUSE',3,11,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(187,'OSSIBLME9','SILK BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(188,'AKSIBLSM2','SILK BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(189,'AKSIBL3X1','SILK BLOUSE',3,11,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(190,'AKSIBL2X1','SILK BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(191,'OSSIBLSM4','SILK BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(192,'AKSIBLLR4','SILK BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(193,'OSSIBL1X1','SILK BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(194,'OSSIBLLR4','SILK BLOUSE',3,11,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(195,'OSSIBL2X7','SILK BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(196,'OSSIBL2X5','SILK BLOUSE',3,11,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(197,'AKSIBLSM1','SILK BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(198,'OSSIBLLR7','SILK BLOUSE',3,11,'','','',3100.00,0.00,0.00,'pcs',1,NOW()),(199,'AKSIBL1X2','SILK BLOUSE',3,11,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(200,'AKSIBLME1','SILK BLOUSE',3,11,'','','',5450.00,0.00,0.00,'pcs',1,NOW());
INSERT INTO `ezy_pos_items` VALUES (201,'OSSIBL2X6','SILK BLOUSE',3,11,'','','',3350.00,0.00,0.00,'pcs',1,NOW()),(202,'OSSIBLLR1','SILK BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(203,'OSSIBLME2','SILK BLOUSE',3,11,'','','',2850.00,0.00,0.00,'pcs',1,NOW()),(204,'OSSIBL1X3','SILK BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(205,'AKSIBL3X2','SILK BLOUSE',3,11,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(206,'OSSIBL1X6','SILK BLOUSE',3,11,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(207,'OSSIBLLR2','SILK BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(208,'OSSIBL4X2','SILK BLOUSE',3,11,'','','',4400.00,0.00,0.00,'pcs',1,NOW()),(209,'OSSIBL1X5','SILK BLOUSE',3,11,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(210,'OSSIBL2X1','SILK BLOUSE',3,11,'','','',2650.00,0.00,0.00,'pcs',1,NOW()),(211,'OSSIBL6X1','SILK BLOUSE',3,11,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(212,'OSSIBL3X1','SILK BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(213,'AKSIBL1X1','SILK BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(214,'OSSIBLLR6','SILK BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(215,'OSSIBLME5','SILK BLOUSE',3,11,'','','',3350.00,0.00,0.00,'pcs',1,NOW()),(216,'OSSIBL4X4','SILK BLOUSE',3,11,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(217,'OSSIBL2X8','SILK BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(218,'OSSIBLLR3','SILK BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(219,'OSSIBL3X5','SILK BLOUSE',3,11,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(220,'OSSIBL3X6','SILK BLOUSE',3,11,'','','',3350.00,0.00,0.00,'pcs',1,NOW()),(221,'OSSIBLME11','SILK BLOUSE',3,11,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(222,'AKSIBL5X1','SILK BLOUSE',3,11,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(223,'OSSIBL2X3','SILK BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(224,'OSSIBL2X4','SILK BLOUSE',3,11,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(225,'AKSIBLLR1','SILK BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(226,'OSSIBL4X1','SILK BLOUSE',3,11,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(227,'OSSIBL3X4','SILK BLOUSE',3,11,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(228,'OSSIBLME6','SILK BLOUSE',3,11,'','','',2100.00,0.00,0.00,'pcs',1,NOW()),(229,'AKSIBLLR3','SILK BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(230,'OSSIBLME7','SILK BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(231,'ANSIBLME1','SILK BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(232,'AKSIBL3X3','SILK BLOUSE',3,11,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(233,'OSSIBL1X7','SILK BLOUSE',3,11,'','','',3350.00,0.00,0.00,'pcs',1,NOW()),(234,'OSSIBLME3','SILK BLOUSE',3,11,'','','',3100.00,0.00,0.00,'pcs',1,NOW()),(235,'OSSIBLME12','SILK BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(236,'ANSIBL2X1','SILK BLOUSE',3,11,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(237,'OSSIBL4X3','SILK BLOUSE',3,11,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(238,'OSSIBLSM5','SILK BLOUSE',3,11,'','','',3100.00,0.00,0.00,'pcs',1,NOW()),(239,'OSSIBLSM2','SILK BLOUSE',3,11,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(240,'OSSIBLSM1','SILK BLOUSE',3,11,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(241,'OSSIBL5X1','SILK BLOUSE',3,11,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(242,'OSSIBLLR5','SILK BLOUSE',3,11,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(243,'AKSIBL1X3','SILK BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(244,'AKSIBLME2','SILK BLOUSE',3,11,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(245,'1DTNBVCFFS','BATIK CAFTAN',3,12,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(246,'GBBCCFFS1','BATIK COTTON CAFTAN',3,12,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(247,'1DTBCDRFS','BATIK COTTON DRESS',3,12,'','','',8750.00,0.00,0.00,'pcs',1,NOW()),(248,'3DTBSCFFS','BATIK SILK CAFTAN',3,12,'','','',13750.00,0.00,0.00,'pcs',1,NOW()),(249,'2DTNBSCFME','BATIK SILK CAFTAN',3,12,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(250,'1DTNBSCF1X','BATIK SILK CAFTAN',3,12,'','','',13750.00,0.00,0.00,'pcs',1,NOW()),(251,'2DTBSCFFS','BATIK SILK CAFTAN',3,12,'','','',15950.00,0.00,0.00,'pcs',1,NOW()),(252,'1DTBSCF1X','BATIK SILK CAFTAN',3,12,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(253,'1DTBSCF2X','BATIK SILK CAFTAN',3,12,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(254,'1DTNBSCF3X','BATIK SILK CAFTAN',3,12,'','','',13750.00,0.00,0.00,'pcs',1,NOW()),(255,'2DTNBSCF2X','BATIK SILK CAFTAN',3,12,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(256,'2DTNBSCF1X','BATIK SILK CAFTAN',3,12,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(257,'2DTNBSCFLR','BATIK SILK CAFTAN',3,12,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(258,'RTNBKSICF1','BATIK SILK CAFTAN',3,12,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(259,'1DTBSCFLR','BATIK SILK CAFTAN',3,12,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(260,'1DTNBSCFME','BATIK SILK CAFTAN',3,12,'','','',13750.00,0.00,0.00,'pcs',1,NOW()),(261,'2DTBSCFLR','BATIK SILK CAFTAN',3,12,'','','',16750.00,0.00,0.00,'pcs',1,NOW()),(262,'1DTNBSCFLR','BATIK SILK CAFTAN',3,12,'','','',13750.00,0.00,0.00,'pcs',1,NOW()),(263,'1DTBSCFFS','BATIK SILK CAFTAN',3,12,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(264,'1DTNBSCF2X','BATIK SILK CAFTAN',3,12,'','','',13750.00,0.00,0.00,'pcs',1,NOW()),(265,'5DTBSCFFS','BATIK SILK CAFTAN',3,12,'','','',15750.00,0.00,0.00,'pcs',1,NOW()),(266,'RTNBSCF1X1','BATIK SILK CAFTAN',3,12,'','','',14750.00,0.00,0.00,'pcs',1,NOW()),(267,'KNBSCF1X1','BATIK SILK CAFTAN',3,12,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(268,'2DTNBSCF3X','BATIK SILK CAFTAN',3,12,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(269,'RTRBMCF1X1','BATIK SILK COTTON CAFTAN',3,12,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(270,'4DTBSCFFS','BATIK SILK LONG  CAFTAN',3,12,'','','',17750.00,0.00,0.00,'pcs',1,NOW()),(271,'6DTBSDRFS','BATIK SILK LONG DRESS',3,12,'','','',17750.00,0.00,0.00,'pcs',1,NOW()),(272,'1DTBSRHDR','BATIK SILK RUCH DRESS',3,12,'','','',18750.00,0.00,0.00,'pcs',1,NOW()),(273,'2DTBSRHDR','BATIK SILK RUCH DRESS',3,12,'','','',17950.00,0.00,0.00,'pcs',1,NOW()),(274,'1DTBSTRDR','BATIK SILK TRANGLE',3,12,'','','',16750.00,0.00,0.00,'pcs',1,NOW()),(275,'KNBLCFFS1','BATIK SUPERLONE CAFTAN',3,12,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(276,'RTRBICF4X1','BATIK SUPERVOIL CAFTAN',3,12,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(277,'RTRBICF3X2','BATIK SUPERVOIL CAFTAN',3,12,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(278,'PBRBICFME1','BATIK SUPERVOIL CAFTAN',3,12,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(279,'RTRBICFSM1','BATIK SUPERVOIL CAFTAN',3,12,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(280,'RTRBICF3X1','BATIK SUPERVOIL CAFTAN',3,12,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(281,'PBRBICF2X1','BATIK SUPERVOIL CAFTAN',3,12,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(282,'RTRBICF2X2','BATIK SUPERVOIL CAFTAN',3,12,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(283,'RTNBICFFS','BATIK SUPERVOIL CAFTAN',3,12,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(284,'RTRBICF1X1','BATIK SUPERVOIL CAFTAN',3,12,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(285,'RTRBICFLR1','BATIK SUPERVOIL CAFTAN',3,12,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(286,'RTRBICFME1','BATIK SUPERVOIL CAFTAN',3,12,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(287,'PBRBICF3X1','BATIK SUPERVOIL CAFTAN',3,12,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(288,'RTRBICF1X2','BATIK SUPERVOIL CAFTAN',3,12,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(289,'RTRBICFLR2','BATIK SUPERVOIL CAFTAN',3,12,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(290,'RTRBICF2X1','BATIK SUPERVOIL CAFTAN',3,12,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(291,'PBRBICFLR1','BATIK SUPERVOIL CAFTAN',3,12,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(292,'RTRBICFME2','BATIK SUPERVOIL CAFTAN',3,12,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(293,'PBRBICF1X1','BATIK SUPERVOIL CAFTAN',3,12,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(294,'PBRBICFF1','BATIK SUPERVOIL CAFTAN',3,12,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(295,'KRBICF2X1','BATIK SUPERVOIL CAFTAN',3,12,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(296,'KRBICFFS1','BATIK SUPERVOIL CAFTAN',3,12,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(297,'1DTNBVCF2X','BATIK VISCOS CAFTAN',3,12,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(298,'HNSICF4X1','SILK CAFTAN',3,12,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(299,'HNSICFLR1','SILK CAFTAN',3,12,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(300,'HNSICF5X1','SILK CAFTAN',3,12,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(301,'HNSICF2X1','SILK CAFTAN',3,12,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(302,'HNSICF3X1','SILK CAFTAN',3,12,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(303,'HNSICF1X1','SILK CAFTAN',3,12,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(304,'HNSICFME1','SILK CAFTAN',3,12,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(305,'KBNSCTLR1','BATIK  CROP TOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(306,'KBNSCT1X1','BATIK  CROP TOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(307,'PBNBICTLR1','BATIK SUPER VOIL CROP TOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(308,'PBNBICT1X1','BATIK SUPER VOIL CROP TOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(309,'PBNBICTME1','BATIK SUPER VOIL CROP TOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(310,'PBNBICT2X1','BATIK SUPER VOIL CROP TOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(311,'KNBLCTLR1','BATIK SUPERLONE CROPTOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(312,'KNBLCT3X1','BATIK SUPERLONE CROPTOP',3,13,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(313,'KNBLCTSM1','BATIK SUPERLONE CROPTOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(314,'KNBLCT1X1','BATIK SUPERLONE CROPTOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(315,'KNBLCT2X1','BATIK SUPERLONE CROPTOP',3,13,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(316,'KNBLCTLR2','BATIK SUPERLONE CROPTOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(317,'KNBLCTME2','BATIK SUPERLONE CROPTOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(318,'KNBLCTME1','BATIK SUPERLONE CROPTOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(319,'PBNBICTSM1','BATIK SUPERVOIL CROP TOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(320,'AMCOCTLR1','COTTON CROP TOP',3,13,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(321,'OSCOCTLR1','COTTON CROP TOP',3,13,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(322,'OSCOCT1X2','COTTON CROP TOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(323,'OSCOCT3X1','COTTON CROP TOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(324,'OSCOCT2X2','COTTON CROP TOP',3,13,'','','',3350.00,0.00,0.00,'pcs',1,NOW()),(325,'OSCOCTLR2','COTTON CROP TOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(326,'OSCOCT2X1','COTTON CROP TOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(327,'OSCOCT3X2','COTTON CROP TOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(328,'OSCOCTME1','COTTON CROP TOP',3,13,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(329,'OSCOCT1X1','COTTON CROP TOP',3,13,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(330,'SFKCTSM01','CROP TOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(331,'SFKCTLR01','CROP TOP',3,13,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(332,'SFKCTME01','CROP TOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(333,'SFKCT2X01','CROP TOP',3,13,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(334,'SFKCT01X1','CROP TOP',3,13,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(335,'OSSICT1X1','SILK  CROP TOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(336,'OSSICT3X1','SILK  CROP TOP',3,13,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(337,'OSSICT2X1','SILK  CROP TOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(338,'OSSICTLR1','SILK  CROP TOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(339,'OSSICTME1','SILK  CROP TOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(340,'ASICT4X01','SILK CROP TOP',3,13,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(341,'OSSICTLR2','SILK CROP TOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(342,'HKSICTME1','SILK CROP TOP',3,13,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(343,'HKSICTLR1','SILK CROP TOP',3,13,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(344,'OSSICT2X2','SILK CROP TOP',3,13,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(345,'OSSICT3X2','SILK CROP TOP',3,13,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(346,'HKSICTSM1','SILK CROP TOP',3,13,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(347,'OSSICT1X2','SILK CROP TOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(348,'HFNSICTME1','SILK CROPTOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(349,'HFNSICT2X1','SILK CROPTOP',3,13,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(350,'HFNSICT4X1','SILK CROPTOP',3,13,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(351,'HFNSICT1X1','SILK CROPTOP',3,13,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(352,'ANSICTLR1','SILK CROPTOP',3,13,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(353,'HFNSICT3X1','SILK CROPTOP',3,13,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(354,'HFNSICTLR1','SILK CROPTOP',3,13,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(355,'HFWSCT1X1','WORKMENT SILK CROP TOP',3,13,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(356,'HFWSCT3X1','WORKMENT SILK CROP TOP',3,13,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(357,'HFWSCTLR1','WORKMENT SILK CROP TOP',3,13,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(358,'SPDHS0001','DHOTHIES',3,14,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(359,'1DTBSDRFS','BATIK SILK DRESS',3,15,'','','',16950.00,0.00,0.00,'pcs',1,NOW()),(360,'1DTNBSDR','BATIK SILK DRESS',3,15,'','','',14950.00,0.00,0.00,'pcs',1,NOW()),(361,'1DTBSDR01','BATIK SILK DRESS',3,15,'','','',18750.00,0.00,0.00,'pcs',1,NOW()),(362,'4DTNBSF2X','BATIK SILK FROCK',3,15,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(363,'4DTNBSF1X','BATIK SILK FROCK',3,15,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(364,'4DTNBSFME','BATIK SILK FROCK',3,15,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(365,'ANCODRME1','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(366,'SFNCDR1X3','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(367,'SFNCDRME2','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(368,'OSCODR1X7','COTTON DRESS',3,15,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(369,'OSCODRLR20','COTTON DRESS',3,15,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(370,'OSCODR3X6','COTTON DRESS',3,15,'','','',6850.00,0.00,0.00,'pcs',1,NOW()),(371,'SRNCDRLR4','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(372,'OSCODRLR6','COTTON DRESS',3,15,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(373,'SRNCDR1X3','COTTON DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(374,'OSCODRME9','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(375,'OSCODRME3','COTTON DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(376,'ANCODR2X3','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(377,'SRNCDR2X1','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(378,'SFNCODRME1','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(379,'OSCODR6X2','COTTON DRESS',3,15,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(380,'SFNCDR2X3','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(381,'OSCODR5X2','COTTON DRESS',3,15,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(382,'OSCODR5X4','COTTON DRESS',3,15,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(383,'OSCODRLR18','COTTON DRESS',3,15,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(384,'OSCODR6X3','COTTON DRESS',3,15,'','','',6850.00,0.00,0.00,'pcs',1,NOW()),(385,'SFNCDR4X2','COTTON DRESS',3,15,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(386,'SFKCODR2X2','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(387,'OSCODRLR2','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(388,'OSCODRSM6','COTTON DRESS',3,15,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(389,'OSCODRLR13','COTTON DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(390,'SFNCODR4X2','COTTON DRESS',3,15,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(391,'OSCODR3X1','COTTON DRESS',3,15,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(392,'OSCODRME16','COTTON DRESS',3,15,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(393,'ANCODR1X3','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(394,'SRNCDR2X4','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(395,'SRNCDRLR2','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(396,'OSCODR3X11','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(397,'OSCODRME14','COTTON DRESS',3,15,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(398,'OSCODRSM10','COTTON DRESS',3,15,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(399,'OSCODRLR7','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(400,'SFNCODR1X2','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW());
INSERT INTO `ezy_pos_items` VALUES (401,'OSCODRLR3','COTTON DRESS',3,15,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(402,'ANCODR1X1','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(403,'SRNCDR1X1','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(404,'ANCODR3X1','COTTON DRESS',3,15,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(405,'OSCODRSM2','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(406,'OSCODRLR9','COTTON DRESS',3,15,'','','',7950.00,0.00,0.00,'pcs',1,NOW()),(407,'OSCODR1X15','COTTON DRESS',3,15,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(408,'OSCODR5X1','COTTON DRESS',3,15,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(409,'SFNCDR1X4','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(410,'SFNCODR2X1','COTTON DRESS',3,15,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(411,'OSCODR3X2','COTTON DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(412,'SFNCDR5X1','COTTON DRESS',3,15,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(413,'SFNCDR6X1','COTTON DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(414,'SFKCODR1X3','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(415,'SFNCDR5X3','COTTON DRESS',3,15,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(416,'SRNCDRSM1','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(417,'SFKCODRME2','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(418,'SFNCDRLR5','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(419,'OSCODRSM9','COTTON DRESS',3,15,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(420,'SFNCDRSM1','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(421,'OSCODR4X3','COTTON DRESS',3,15,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(422,'OSCODR2X11','COTTON DRESS',3,15,'','','',7100.00,0.00,0.00,'pcs',1,NOW()),(423,'ANCODRSM2','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(424,'SRNCDR2X2','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(425,'OSCODRLR5','COTTON DRESS',3,15,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(426,'OSCODRSM3','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(427,'SRNCDR3X4','COTTON DRESS',3,15,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(428,'OSCODR1X10','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(429,'OSCODR3X3','COTTON DRESS',3,15,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(430,'SFNCDR3X2','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(431,'SRNCDRLR1','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(432,'OSCODR3X4','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(433,'SRNCDRME2','COTTON DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(434,'SFNCDR1X2','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(435,'SRNCDR3X3','COTTON DRESS',3,15,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(436,'OSCODR5X3','COTTON DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(437,'OSCODR6X4','COTTON DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(438,'SFNCDRSM2','COTTON DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(439,'SFNCDR4X3','COTTON DRESS',3,15,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(440,'SRNCDR1X2','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(441,'OSCODR1X2','COTTON DRESS',3,15,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(442,'OSCODRSM1','COTTON DRESS',3,15,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(443,'ANCODRLR1','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(444,'SRNCDRSM2','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(445,'OSCODR2X1','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(446,'SFNCDRLR1','COTTON DRESS',3,15,'','','',6850.00,0.00,0.00,'pcs',1,NOW()),(447,'OSCODRLR19','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(448,'SRNCDR3X1','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(449,'OSCODR3X7','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(450,'OSCODRME15','COTTON DRESS',3,15,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(451,'SRNCDRME1','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(452,'SFNCODR3X1','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(453,'SFNCDR3X3','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(454,'OSCODRSM4','COTTON DRESS',3,15,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(455,'OSCODRLR16','COTTON DRESS',3,15,'','','',6850.00,0.00,0.00,'pcs',1,NOW()),(456,'OSCODRSM14','COTTON DRESS',3,15,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(457,'OSCODR1X6','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(458,'OSCODRLR17','COTTON DRESS',3,15,'','','',6850.00,0.00,0.00,'pcs',1,NOW()),(459,'OSCODR4X1','COTTON DRESS',3,15,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(460,'OSCODR2X10','COTTON DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(461,'OSCODRME4','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(462,'SFNCDR2X2','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(463,'OSCODRME17','COTTON DRESS',3,15,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(464,'SRNCDR4X2','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(465,'OSCODR1X1','COTTON DRESS',3,15,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(466,'OSCODR1X8','COTTON DRESS',3,15,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(467,'OSCODRSM12','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(468,'SFNCDRME3','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(469,'SRNCDRME4','COTTON DRESS',3,15,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(470,'OSCODR2X12','COTTON DRESS',3,15,'','','',4550.00,0.00,0.00,'pcs',1,NOW()),(471,'SRNCDR4X1','COTTON DRESS',3,15,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(472,'OSCODRME1','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(473,'SRNCDR6X1','COTTON DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(474,'OSCODRSM7','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(475,'OSCODR2X2','COTTON DRESS',3,15,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(476,'OSCODRME11','COTTON DRESS',3,15,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(477,'OSCODRME2','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(478,'OSCODR3X8','COTTON DRESS',3,15,'','','',6100.00,0.00,0.00,'pcs',1,NOW()),(479,'OSCODR2X13','COTTON DRESS',3,15,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(480,'OSCODR2X3','COTTON DRESS',3,15,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(481,'ANCODRLR3','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(482,'SFNCDRLR3','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(483,'OSCODRLR15','COTTON DRESS',3,15,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(484,'OSCODR4X4','COTTON DRESS',3,15,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(485,'OSCODR3X12','COTTON DRESS',3,15,'','','',6350.00,0.00,0.00,'pcs',1,NOW()),(486,'ANCODR1X2','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(487,'OSCODRME13','COTTON DRESS',3,15,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(488,'OSCODRSM11','COTTON DRESS',3,15,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(489,'OSCODR6X1','COTTON DRESS',3,15,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(490,'OSCODR4X2','COTTON DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(491,'OSCODRLR10','COTTON DRESS',3,15,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(492,'SRNCDR2X3','COTTON DRESS',3,15,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(493,'OSCODRLR1','COTTON DRESS',3,15,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(494,'OSCODRME6','COTTON DRESS',3,15,'','','',7950.00,0.00,0.00,'pcs',1,NOW()),(495,'OSCODR1X5','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(496,'SFNCDR5X2','COTTON DRESS',3,15,'','','',7650.00,0.00,0.00,'pcs',1,NOW()),(497,'OSCODR1X3','COTTON DRESS',3,15,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(498,'SFKCODRLR1','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(499,'ANCODRSM1','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(500,'OSCODR2X5','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(501,'OSCODRSM13','COTTON DRESS',3,15,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(502,'SFNCDR4X1','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(503,'OSCODRSM8','COTTON DRESS',3,15,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(504,'OSCODR3X9','COTTON DRESS',3,15,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(505,'SRNCDR3X5','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(506,'SFNCDRLR4','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(507,'ANCODRLR2','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(508,'SFNCODR5X1','COTTON DRESS',3,15,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(509,'OSCODR4X7','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(510,'OSCODR4X6','COTTON DRESS',3,15,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(511,'SFNCODR1X1','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(512,'SRNCDRME3','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(513,'SFNCDR2X4','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(514,'OSCODRLR14','COTTON DRESS',3,15,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(515,'ANCODR2X1','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(516,'OSCODRSM5','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(517,'OSCODR2X4','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(518,'SRNCDRLR3','COTTON DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(519,'ANCODRME2','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(520,'OSCODR1X13','COTTON DRESS',3,15,'','','',6100.00,0.00,0.00,'pcs',1,NOW()),(521,'SRNCDR5X1','COTTON DRESS',3,15,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(522,'OSCODRLR4','COTTON DRESS',3,15,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(523,'OSCODRME12','COTTON DRESS',3,15,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(524,'OSCODRLR8','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(525,'OSCODRME5','COTTON DRESS',3,15,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(526,'OSCODR2X7','COTTON DRESS',3,15,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(527,'OSCODR2X6','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(528,'OSCODR1X14','COTTON DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(529,'SFNCDRME4','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(530,'OSCODR2X9','COTTON DRESS',3,15,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(531,'OSCODR2X8','COTTON DRESS',3,15,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(532,'SFKCODR3X2','COTTON DRESS',3,15,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(533,'ANCODR2X2','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(534,'OSCODR4X5','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(535,'OSCODRME7','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(536,'SFNCODRLR2','COTTON DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(537,'OSCODR1X9','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(538,'SRNCDR1X4','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(539,'SFNCDR3X4','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(540,'OSCODRLR11','COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(541,'OSCODRME18','COTTON DRESS',3,15,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(542,'SRNCDRSM3','COTTON DRESS',3,15,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(543,'SRNCDR3X2','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(544,'OSCODR3X5','COTTON DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(545,'OSCODR1X4','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(546,'OSCODR1X11','COTTON DRESS',3,15,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(547,'OSCODRME10','COTTON DRESS',3,15,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(548,'SFNCDR2X5','COTTON DRESS',3,15,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(549,'OSCODRLR12','COTTON DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(550,'OSCODR1X12','COTTON DRESS',3,15,'','','',7950.00,0.00,0.00,'pcs',1,NOW()),(551,'OSCODRME8','COTTON DRESS',3,15,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(552,'SFNCDRLR2','COTTON LASE DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(553,'SFNCDR1X1','COTTON LASE DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(554,'SFNCDR3X1','COTTON LASE DRESS',3,15,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(555,'SFNCDRME1','COTTON LASE DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(556,'SFNCDR2X1','COTTON LASE DRESS',3,15,'','','',6850.00,0.00,0.00,'pcs',1,NOW()),(557,'DRESS40%1','DRESS',3,15,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(558,'OSSALEDR6','DRESS',3,15,'','','',3000.00,0.00,0.00,'pcs',1,NOW()),(559,'OSSALEDR3','DRESS',3,15,'','','',2250.00,0.00,0.00,'pcs',1,NOW()),(560,'OSSALEDR5','DRESS',3,15,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(561,'OSSALEDR2','DRESS',3,15,'','','',2000.00,0.00,0.00,'pcs',1,NOW()),(562,'OSSALEDR4','DRESS',3,15,'','','',2500.00,0.00,0.00,'pcs',1,NOW()),(563,'OSNDRME01','DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(564,'OSSALEDR1','DRESS',3,15,'','','',1500.00,0.00,0.00,'pcs',1,NOW()),(565,'ANSIDR1X6','SILK  DRESS',3,15,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(566,'OSSIDRLR1','SILK DRESS',3,15,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(567,'OSSIDR3X3','SILK DRESS',3,15,'','','',6350.00,0.00,0.00,'pcs',1,NOW()),(568,'HNSIDR1X1','SILK DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(569,'ANSIDR1X4','SILK DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(570,'ANSIDRLR2','SILK DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(571,'OSSIDRSM3','SILK DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(572,'OSSIDRLR8','SILK DRESS',3,15,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(573,'OSSIDR6X1','SILK DRESS',3,15,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(574,'OSSIDR1X6','SILK DRESS',3,15,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(575,'OSSIDRSM2','SILK DRESS',3,15,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(576,'HNSIDR5X1','SILK DRESS',3,15,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(577,'HNSIDR1X3','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(578,'ANSIDR2X5','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(579,'HNSIDRLR1','SILK DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(580,'OSSIDR1X2','SILK DRESS',3,15,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(581,'ANSIDR1X3','SILK DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(582,'OSSIDRME7','SILK DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(583,'OSSIDR2X3','SILK DRESS',3,15,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(584,'OSSIDR6X4','SILK DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(585,'OSSIDR1X9','SILK DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(586,'OSSIDR3X7','SILK DRESS',3,15,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(587,'HNSIDR1X2','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(588,'OSSIDR5X2','SILK DRESS',3,15,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(589,'OSSIDRME1','SILK DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(590,'ANSIDRLR1','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(591,'OSSIDR1X4','SILK DRESS',3,15,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(592,'HNSIDR4X3','SILK DRESS',3,15,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(593,'OSSIDR3X1','SILK DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(594,'OSSIDRLR3','SILK DRESS',3,15,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(595,'OSSIDR4X2','SILK DRESS',3,15,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(596,'OSSIDR1X1','SILK DRESS',3,15,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(597,'HNSIDR3X1','SILK DRESS',3,15,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(598,'OSSIDR6X3','SILK DRESS',3,15,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(599,'OSSIDRSM4','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(600,'OSSIDR1X5','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW());
INSERT INTO `ezy_pos_items` VALUES (601,'OSSIDRME3','SILK DRESS',3,15,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(602,'OSSIDRME2','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(603,'OSSIDR3X6','SILK DRESS',3,15,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(604,'ANSIDRME1','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(605,'OSSIDR2X7','SILK DRESS',3,15,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(606,'ANSIDRSM1','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(607,'OSSIDR5X5','SILK DRESS',3,15,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(608,'OSSIDRLR2','SILK DRESS',3,15,'','','',8750.00,0.00,0.00,'pcs',1,NOW()),(609,'OSSIDR2X6','SILK DRESS',3,15,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(610,'OSSIDR1X7','SILK DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(611,'OSSIDRLR10','SILK DRESS',3,15,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(612,'OSSIDR2X8','SILK DRESS',3,15,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(613,'ANSIDR3X3','SILK DRESS',3,15,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(614,'OSSIDR1X3','SILK DRESS',3,15,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(615,'HNSIDR3X3','SILK DRESS',3,15,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(616,'OSSIDR3X11','SILK DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(617,'OSSIDR3X4','SILK DRESS',3,15,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(618,'OSSIDR5X1','SILK DRESS',3,15,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(619,'HNSIDRME3','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(620,'OSSIDR2X9','SILK DRESS',3,15,'','','',8750.00,0.00,0.00,'pcs',1,NOW()),(621,'HNSIDR4X2','SILK DRESS',3,15,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(622,'HNSIDR3X2','SILK DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(623,'OSSIDR4X4','SILK DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(624,'ANSIDR2X1','SILK DRESS',3,15,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(625,'ANSIDR2X2','SILK DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(626,'ANSIDR1X2','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(627,'OSSIDR6X2','SILK DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(628,'OSSIDR4X3','SILK DRESS',3,15,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(629,'OSSIDRLR12','SILK DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(630,'ANSIDR3X1','SILK DRESS',3,15,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(631,'OSSIDRLR5','SILK DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(632,'OSSIDR5X4','SILK DRESS',3,15,'','','',9450.00,0.00,0.00,'pcs',1,NOW()),(633,'OSSIDRLR9','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(634,'ANSIDR4X1','SILK DRESS',3,15,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(635,'OSSIDRLR7','SILK DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(636,'OSSIDRLR11','SILK DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(637,'OSSIDR2X2','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(638,'OSSIDR3X10','SILK DRESS',3,15,'','','',9450.00,0.00,0.00,'pcs',1,NOW()),(639,'ANSIDR3X2','SILK DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(640,'ANSIDRLR3','SILK DRESS',3,15,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(641,'HNSIDRLR2','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(642,'OSSIDR2X5','SILK DRESS',3,15,'','','',7100.00,0.00,0.00,'pcs',1,NOW()),(643,'HNSIDR4X1','SILK DRESS',3,15,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(644,'OSSIDRSM1','SILK DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(645,'OSSIDRSM6','SILK DRESS',3,15,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(646,'ANSIDR2X3','SILK DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(647,'OSSIDR1X8','SILK DRESS',3,15,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(648,'OSSIDR2X1','SILK DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(649,'OSSIDRLR4','SILK DRESS',3,15,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(650,'OSSIDRLR6','SILK DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(651,'OSSIDRME4','SILK DRESS',3,15,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(652,'OSSIDRME5','SILK DRESS',3,15,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(653,'OSSIDRME6','SILK DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(654,'HNSIDR2X1','SILK DRESS',3,15,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(655,'HNSIDRME1','SILK DRESS',3,15,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(656,'OSSIDR3X5','SILK DRESS',3,15,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(657,'OSSIDR3X2','SILK DRESS',3,15,'','','',6100.00,0.00,0.00,'pcs',1,NOW()),(658,'OSSIDRME8','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(659,'HNSIDR2X2','SILK DRESS',3,15,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(660,'OSSIDR5X3','SILK DRESS',3,15,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(661,'OSSIDR2X4','SILK DRESS',3,15,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(662,'HNSIDRME2','SILK DRESS',3,15,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(663,'ANSIDR1X1','SILK DRESS',3,15,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(664,'OSSIDR4X1','SILK DRESS',3,15,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(665,'ANWCDR3X1','WORKMENT COTTON DRES',3,15,'','','',8750.00,0.00,0.00,'pcs',1,NOW()),(666,'SFNSCDRLR1','WORKMENT COTTON DRESS',3,15,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(667,'SFNSCDRME1','WORKMENT COTTON DRESS',3,15,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(668,'SFNSCDR1X1','WORKMENT COTTON DRESS',3,15,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(669,'SFNSCDR2X2','WORKMENT COTTON DRESS',3,15,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(670,'SFNSCDR4X1','WORKMENT COTTON DRESS',3,15,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(671,'SFNSCDR3X1','WORKMENT COTTON DRESS',3,15,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(672,'SFNSCDR2X1','WORKMENT COTTON DRESS',3,15,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(673,'OSWCDRME1','WORKMENT COTTON DRESS',3,15,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(674,'ANSWDR1X1','WORKMENT SILK DRESS',3,15,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(675,'HGE0008','EARINGS',3,16,'','','',250.00,0.00,0.00,'pcs',1,NOW()),(676,'SFNCKU1X2','COTTON KURTHA',3,17,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(677,'SFNCKUME2','COTTON KURTHA',3,17,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(678,'SFNCKULR3','COTTON KURTHA',3,17,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(679,'SFNCKU2X2','COTTON KURTHA',3,17,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(680,'KNBLBL1X1','BATIK BLOUSE',3,18,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(681,'KNBLBL2X1','BATIK BLOUSE',3,18,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(682,'SBNBCFRLR1','BATIK COTTON FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(683,'RTNBCFRME1','BATIK COTTON FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(684,'SMNBCFRLR1','BATIK COTTON FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(685,'BGNBCFRLR1','BATIK COTTON FROCK',3,18,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(686,'RTNBCFR5X1','BATIK COTTON FROCK',3,18,'','','',7650.00,0.00,0.00,'pcs',1,NOW()),(687,'RTNBCFRLR1','BATIK COTTON FROCK',3,18,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(688,'KNBCFR2X1','BATIK COTTON FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(689,'KNBCFRSM2','BATIK COTTON FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(690,'RTNBCFRME2','BATIK COTTON FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(691,'RTNBCFRLR2','BATIK COTTON FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(692,'RTNBCFR6X1','BATIK COTTON FROCK',3,18,'','','',7650.00,0.00,0.00,'pcs',1,NOW()),(693,'KNBCFRLR2','BATIK COTTON FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(694,'KNBCFRME2','BATIK COTTON FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(695,'KNBCFRLR1','BATIK COTTON FROCK',3,18,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(696,'RTNBCFRLR3','BATIK COTTON FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(697,'RNNBCFME1','BATIK COTTON FROCK',3,18,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(698,'KNBCFR3X1','BATIK COTTON FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(699,'KNBCFR1X1','BATIK COTTON FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(700,'SBNCOFRSM1','BATIK COTTON FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(701,'BGNBCFRME1','BATIK COTTON FROCK',3,18,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(702,'SMNCOFRME1','BATIK COTTON FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(703,'SBNCOFRSM2','BATIK COTTON FROCK',3,18,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(704,'BATIKFR05','BATIK FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(705,'KNBLFR3XL','BATIK FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(706,'BATIKFR07','BATIK FROCK',3,18,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(707,'BATIKFR02','BATIK FROCK',3,18,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(708,'BATIKFR08','BATIK FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(709,'BATIKFR12','BATIK FROCK',3,18,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(710,'BATIKFR04','BATIK FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(711,'BATIKFR11','BATIK FROCK',3,18,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(712,'BATIKFR10','BATIK FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(713,'KNBLFRSE1','BATIK FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(714,'BATIKFR13','BATIK FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(715,'BATIKFR14','BATIK FROCK',3,18,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(716,'KNBLFR2XL','BATIK FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(717,'BATIKFR16','BATIK FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(718,'KNBCFRL01','BATIK FROCK',3,18,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(719,'KNBCFR4XL','BATIK FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(720,'BATIKFR06','BATIK FROCK',3,18,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(721,'BATIKFR09','BATIK FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(722,'KNBLFR4XL','BATIK FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(723,'BATIKFR03','BATIK FROCK',3,18,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(724,'KNBLFR5XL','BATIK FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(725,'BATIKFR01','BATIK FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(726,'BATIKFR15','BATIK FROCK',3,18,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(727,'5DTNBSFRLR','BATIK SILK FROCK',3,18,'','','',17750.00,0.00,0.00,'pcs',1,NOW()),(728,'3DTNBSFR1X','BATIK SILK FROCK',3,18,'','','',15950.00,0.00,0.00,'pcs',1,NOW()),(729,'RTNBSFR3X2','BATIK SILK FROCK',3,18,'','','',19750.00,0.00,0.00,'pcs',1,NOW()),(730,'2DTNBSF3X','BATIK SILK FROCK',3,18,'','','',18750.00,0.00,0.00,'pcs',1,NOW()),(731,'1DTNBSFRME','BATIK SILK FROCK',3,18,'','','',16950.00,0.00,0.00,'pcs',1,NOW()),(732,'1DTNBSF2X','BATIK SILK FROCK',3,18,'','','',14950.00,0.00,0.00,'pcs',1,NOW()),(733,'1DTNBSFR2X','BATIK SILK FROCK',3,18,'','','',16950.00,0.00,0.00,'pcs',1,NOW()),(734,'2DTNBSFR2X','BATIK SILK FROCK',3,18,'','','',16750.00,0.00,0.00,'pcs',1,NOW()),(735,'2DTNBSFRLR','BATIK SILK FROCK',3,18,'','','',16750.00,0.00,0.00,'pcs',1,NOW()),(736,'RTNBSFR1X1','BATIK SILK FROCK',3,18,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(737,'2DTNBSF5X','BATIK SILK FROCK',3,18,'','','',18750.00,0.00,0.00,'pcs',1,NOW()),(738,'RTNBSFRSM1','BATIK SILK FROCK',3,18,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(739,'RTNBSFRME1','BATIK SILK FROCK',3,18,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(740,'2DTNBSF2X','BATIK SILK FROCK',3,18,'','','',15950.00,0.00,0.00,'pcs',1,NOW()),(741,'2DTNBSFLR','BATIK SILK FROCK',3,18,'','','',15950.00,0.00,0.00,'pcs',1,NOW()),(742,'2DTNBSFME','BATIK SILK FROCK',3,18,'','','',15950.00,0.00,0.00,'pcs',1,NOW()),(743,'2DTNBSF1X','BATIK SILK FROCK',3,18,'','','',15950.00,0.00,0.00,'pcs',1,NOW()),(744,'3DTNBSFRLR','BATIK SILK FROCK',3,18,'','','',15950.00,0.00,0.00,'pcs',1,NOW()),(745,'1DTNBSFRLR','BATIK SILK FROCK',3,18,'','','',16950.00,0.00,0.00,'pcs',1,NOW()),(746,'4DTNBSFR2X','BATIK SILK FROCK',3,18,'','','',16250.00,0.00,0.00,'pcs',1,NOW()),(747,'1DTNBSF1X','BATIK SILK FROCK',3,18,'','','',14950.00,0.00,0.00,'pcs',1,NOW()),(748,'1DTNBSFRSM','BATIK SILK FROCK',3,18,'','','',13950.00,0.00,0.00,'pcs',1,NOW()),(749,'5DTNBSFRME','BATIK SILK FROCK',3,18,'','','',13950.00,0.00,0.00,'pcs',1,NOW()),(750,'RTNBSFR1X2','BATIK SILK FROCK',3,18,'','','',19750.00,0.00,0.00,'pcs',1,NOW()),(751,'RTNBSFR4X2','BATIK SILK FROCK',3,18,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(752,'4DTNBSFR1X','BATIK SILK FROCK',3,18,'','','',16250.00,0.00,0.00,'pcs',1,NOW()),(753,'1DTNBSFLR','BATIK SILK FROCK',3,18,'','','',14950.00,0.00,0.00,'pcs',1,NOW()),(754,'6DTNBSFRLR','BATIK SILK FROCK',3,18,'','','',13950.00,0.00,0.00,'pcs',1,NOW()),(755,'6DTNBSFR2X','BATIK SILK FROCK',3,18,'','','',13950.00,0.00,0.00,'pcs',1,NOW()),(756,'6DTNBSFR1X','BATIK SILK FROCK',3,18,'','','',13950.00,0.00,0.00,'pcs',1,NOW()),(757,'1DTNBSFME','BATIK SILK FROCK',3,18,'','','',14950.00,0.00,0.00,'pcs',1,NOW()),(758,'3DTNBSFLR','BATIK SILK FROCK',3,18,'','','',18750.00,0.00,0.00,'pcs',1,NOW()),(759,'5DTNBSFR1X','BATIK SILK FROCK',3,18,'','','',17750.00,0.00,0.00,'pcs',1,NOW()),(760,'1DTNBSFR3X','BATIK SILK FROCK',3,18,'','','',16950.00,0.00,0.00,'pcs',1,NOW()),(761,'1DTNBSF3X','BATIK SILK FROCK',3,18,'','','',14950.00,0.00,0.00,'pcs',1,NOW()),(762,'1DTNBSF5X','BATIK SILK FROCK',3,18,'','','',17950.00,0.00,0.00,'pcs',1,NOW()),(763,'5DTNBSFR2X','BATIK SILK FROCK',3,18,'','','',17750.00,0.00,0.00,'pcs',1,NOW()),(764,'3DTNBSFR2X','BATIK SILK FROCK',3,18,'','','',15950.00,0.00,0.00,'pcs',1,NOW()),(765,'3DTNBSFR3X','BATIK SILK FROCK',3,18,'','','',17750.00,0.00,0.00,'pcs',1,NOW()),(766,'RTNBSFRLR2','BATIK SILK FROCK',3,18,'','','',19750.00,0.00,0.00,'pcs',1,NOW()),(767,'2DTNBSF4X','BATIK SILK FROCK',3,18,'','','',18750.00,0.00,0.00,'pcs',1,NOW()),(768,'4DTNBSFR3X','BATIK SILK FROCK',3,18,'','','',15950.00,0.00,0.00,'pcs',1,NOW()),(769,'RTNBSFRLR1','BATIK SILK FROCK',3,18,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(770,'3DTNBSFRME','BATIK SILK FROCK',3,18,'','','',15950.00,0.00,0.00,'pcs',1,NOW()),(771,'3DTNBSF2X','BATIK SILK FROCK',3,18,'','','',18750.00,0.00,0.00,'pcs',1,NOW()),(772,'2DTNBSFRME','BATIK SILK FROCK',3,18,'','','',16750.00,0.00,0.00,'pcs',1,NOW()),(773,'3DTNBSFME','BATIK SILK FROCK',3,18,'','','',18750.00,0.00,0.00,'pcs',1,NOW()),(774,'4DTNBSFRME','BATIK SILK FROCK',3,18,'','','',17750.00,0.00,0.00,'pcs',1,NOW()),(775,'RTNBSFR2X1','BATIK SILK FROCK',3,18,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(776,'2DTNBSFR3X','BATIK SILK FROCK',3,18,'','','',16750.00,0.00,0.00,'pcs',1,NOW()),(777,'RTNBSFR2X2','BATIK SILK FROCK',3,18,'','','',19750.00,0.00,0.00,'pcs',1,NOW()),(778,'RTNBSFR4X1','BATIK SILK FROCK',3,18,'','','',19750.00,0.00,0.00,'pcs',1,NOW()),(779,'RTNBSFR3X1','BATIK SILK FROCK',3,18,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(780,'2DTNBSFR1X','BATIK SILK FROCK',3,18,'','','',16750.00,0.00,0.00,'pcs',1,NOW()),(781,'1DTNBSF4X','BATIK SILK FROCK',3,18,'','','',17950.00,0.00,0.00,'pcs',1,NOW()),(782,'1DTNBSFR1X','BATIK SILK FROCK',3,18,'','','',16950.00,0.00,0.00,'pcs',1,NOW()),(783,'RTNBSFRME2','BATIK SILK FROCK',3,18,'','','',19750.00,0.00,0.00,'pcs',1,NOW()),(784,'3DTNBSF1X','BATIK SILK FROCK',3,18,'','','',18750.00,0.00,0.00,'pcs',1,NOW()),(785,'4DTNBSFRLR','BATIK SILK FROCK',3,18,'','','',16250.00,0.00,0.00,'pcs',1,NOW()),(786,'RTNBMFR1X1','BATIK SILKCOTTON FROCK',3,18,'','','',10750.00,0.00,0.00,'pcs',1,NOW()),(787,'RTNBMFRSM1','BATIK SILKCOTTON FROCK',3,18,'','','',10750.00,0.00,0.00,'pcs',1,NOW()),(788,'RTNBMFR3X2','BATIK SILKCOTTON FROCK',3,18,'','','',9950.00,0.00,0.00,'pcs',1,NOW()),(789,'RTNBMFRME1','BATIK SILKCOTTON FROCK',3,18,'','','',10750.00,0.00,0.00,'pcs',1,NOW()),(790,'RTNBMFRME2','BATIK SILKCOTTON FROCK',3,18,'','','',9950.00,0.00,0.00,'pcs',1,NOW()),(791,'RTNBMFRLR2','BATIK SILKCOTTON FROCK',3,18,'','','',9950.00,0.00,0.00,'pcs',1,NOW()),(792,'RTNBMFRSM2','BATIK SILKCOTTON FROCK',3,18,'','','',9950.00,0.00,0.00,'pcs',1,NOW()),(793,'RTNBMFRLR3','BATIK SILKCOTTON FROCK',3,18,'','','',11250.00,0.00,0.00,'pcs',1,NOW()),(794,'RTNBMFR2X1','BATIK SILKCOTTON FROCK',3,18,'','','',10750.00,0.00,0.00,'pcs',1,NOW()),(795,'RTNBMFR2X3','BATIK SILKCOTTON FROCK',3,18,'','','',11250.00,0.00,0.00,'pcs',1,NOW()),(796,'RTNBMFR3X1','BATIK SILKCOTTON FROCK',3,18,'','','',10750.00,0.00,0.00,'pcs',1,NOW()),(797,'RTNBMFR2X2','BATIK SILKCOTTON FROCK',3,18,'','','',9950.00,0.00,0.00,'pcs',1,NOW()),(798,'RTNBMFRLR1','BATIK SILKCOTTON FROCK',3,18,'','','',10750.00,0.00,0.00,'pcs',1,NOW()),(799,'RTNBMFR1X2','BATIK SILKCOTTON FROCK',3,18,'','','',9950.00,0.00,0.00,'pcs',1,NOW()),(800,'RTNBIF2X7','BATIK SMOKE DRESS',3,18,'','','',8250.00,0.00,0.00,'pcs',1,NOW());
INSERT INTO `ezy_pos_items` VALUES (801,'RTNBIFLR5','BATIK SMOKE DRESS',3,18,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(802,'1DTBCSMDR','BATIK SMOKE DRESS',3,18,'','','',8750.00,0.00,0.00,'pcs',1,NOW()),(803,'RTNBIF1X5','BATIK SMOKE DRESS',3,18,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(804,'RTNBIF3X6','BATIK SMOKE DRESS',3,18,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(805,'2DTBCSMDR','BATIK SMOKE DRESS',3,18,'','','',17750.00,0.00,0.00,'pcs',1,NOW()),(806,'RTNBIFME5','BATIK SMOKE DRESS',3,18,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(807,'KNBLFRME5','BATIK SUPERLONE  FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(808,'KNBLFRME6','BATIK SUPERLONE  FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(809,'2DNNBLFRME','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(810,'1DNNBLFR1X','BATIK SUPERLONE FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(811,'KNBLFR3X8','BATIK SUPERLONE FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(812,'KNBLFRME4','BATIK SUPERLONE FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(813,'2DNNBLF4X','BATIK SUPERLONE FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(814,'KNBLFR4X2','BATIK SUPERLONE FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(815,'KNBLFRLR5','BATIK SUPERLONE FROCK',3,18,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(816,'PBNBLFRSM1','BATIK SUPERLONE FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(817,'KNBLFR5X2','BATIK SUPERLONE FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(818,'3DNNBLFR1X','BATIK SUPERLONE FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(819,'2DNNBLFR2X','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(820,'2DNNBLF1X','BATIK SUPERLONE FROCK',3,18,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(821,'KNBLFR4X5','BATIK SUPERLONE FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(822,'SBNBLFRLR1','BATIK SUPERLONE FROCK',3,18,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(823,'KNBLFR3X5','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(824,'KNBLFR2X5','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(825,'KNBLFRLR4','BATIK SUPERLONE FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(826,'KNBLFRLR8','BATIK SUPERLONE FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(827,'KNBLFR2X8','BATIK SUPERLONE FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(828,'KNBLFR4X3','BATIK SUPERLONE FROCK',3,18,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(829,'KNBLFRLR3','BATIK SUPERLONE FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(830,'KNBLFR1X2','BATIK SUPERLONE FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(831,'KNBLFR3X3','BATIK SUPERLONE FROCK',3,18,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(832,'3DNNBLF3X','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(833,'KNBLFR1X5','BATIK SUPERLONE FROCK',3,18,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(834,'KNBLFRSM4','BATIK SUPERLONE FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(835,'KNBLFR5X1','BATIK SUPERLONE FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(836,'KNBLFRLR2','BATIK SUPERLONE FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(837,'1DNNBLFME','BATIK SUPERLONE FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(838,'KNBLFRSM5','BATIK SUPERLONE FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(839,'1DNNBLFR3X','BATIK SUPERLONE FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(840,'KNBLFR4X4','BATIK SUPERLONE FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(841,'KNBLFRSM1','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(842,'3DNNBLFRME','BATIK SUPERLONE FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(843,'KNBLFRLR6','BATIK SUPERLONE FROCK',3,18,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(844,'1DNNBLF1X','BATIK SUPERLONE FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(845,'KNBLFRSM2','BATIK SUPERLONE FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(846,'1DNNBLFRLR','BATIK SUPERLONE FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(847,'1DNNBLF2X','BATIK SUPERLONE FROCK',3,18,'','','',6650.00,0.00,0.00,'pcs',1,NOW()),(848,'KNBLFR3X2','BATIK SUPERLONE FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(849,'KNBLFRLR9','BATIK SUPERLONE FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(850,'KNBLFRSM6','BATIK SUPERLONE FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(851,'KNBLFRSM3','BATIK SUPERLONE FROCK',3,18,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(852,'1DNNBLFSM','BATIK SUPERLONE FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(853,'KNBLFRSM7','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(854,'KNBLFR2X3','BATIK SUPERLONE FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(855,'KNBLFR4X6','BATIK SUPERLONE FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(856,'KNBLFR2X1','BATIK SUPERLONE FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(857,'KNBLFR2X4','BATIK SUPERLONE FROCK',3,18,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(858,'2DNNBLFLR','BATIK SUPERLONE FROCK',3,18,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(859,'PBNBLFRLR1','BATIK SUPERLONE FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(860,'KNBLFRME1','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(861,'PBNBLFRME1','BATIK SUPERLONE FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(862,'KNBLFR3X1','BATIK SUPERLONE FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(863,'KNBLFR4X1','BATIK SUPERLONE FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(864,'KNBLFR1X9','BATIK SUPERLONE FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(865,'KNBLFR3X7','BATIK SUPERLONE FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(866,'1DNNBLF4X','BATIK SUPERLONE FROCK',3,18,'','','',6850.00,0.00,0.00,'pcs',1,NOW()),(867,'KNBLFRME3','BATIK SUPERLONE FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(868,'KNBLFR2X2','BATIK SUPERLONE FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(869,'KNBLFR1X3','BATIK SUPERLONE FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(870,'KNBLFR1X1','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(871,'KNBLFRLR1','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(872,'KNBLFR3X4','BATIK SUPERLONE FROCK',3,18,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(873,'KNBLFRME2','BATIK SUPERLONE FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(874,'KNBLFR2X6','BATIK SUPERLONE FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(875,'2DNNBLFR3X','BATIK SUPERLONE FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(876,'RTNBIFLR4','BATIK SUPERLONE FROCK',3,18,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(877,'KNBLFRME7','BATIK SUPERLONE FROCK',3,18,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(878,'KNBLFR2X7','BATIK SUPERLONE FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(879,'KNBLFRLR10','BATIK SUPERLONE FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(880,'2DNNBLFR1X','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(881,'KNBLFR1X6','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(882,'PBNBLFR1X1','BATIK SUPERLONE FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(883,'KNBLFR1X7','BATIK SUPERLONE FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(884,'RNNBLFRLR1','BATIK SUPERLONE FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(885,'KNBLFR1X4','BATIK SUPERLONE FROCK',3,18,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(886,'1DNNBLFR2X','BATIK SUPERLONE FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(887,'1DNNBLF3X','BATIK SUPERLONE FROCK',3,18,'','','',6650.00,0.00,0.00,'pcs',1,NOW()),(888,'KNBLFR3X6','BATIK SUPERLONE FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(889,'KNBLFR1X8','BATIK SUPERLONE FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(890,'1DNNBLFLR','BATIK SUPERLONE FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(891,'2DNNBLFRLR','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(892,'2DNNBLF2X','BATIK SUPERLONE FROCK',3,18,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(893,'3DNNBLFRLR','BATIK SUPERLONE FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(894,'VBNBLFRSM1','BATIK SUPERLONE FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(895,'KNBLFRME8','BATIK SUPERLONE FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(896,'3DNNBLF2X','BATIK SUPERLONE FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(897,'1DNNBLFRME','BATIK SUPERLONE FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(898,'RTNBIF3X2','BATIK SUPERVOIL  FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(899,'RTNBIF4X4','BATIK SUPERVOIL  FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(900,'PBNBIFRLR3','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(901,'PBNBIFRME4','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(902,'RTNBIFME4','BATIK SUPERVOIL FROCK',3,18,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(903,'RTNBIF2X6','BATIK SUPERVOIL FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(904,'RTNBIF2X4','BATIK SUPERVOIL FROCK',3,18,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(905,'RNNBIFRME1','BATIK SUPERVOIL FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(906,'PBNBIFRME1','BATIK SUPERVOIL FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(907,'RTNBIF3X3','BATIK SUPERVOIL FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(908,'1DNNBIF4X','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(909,'3DNNBIFS0','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(910,'KNBIFR4X1','BATIK SUPERVOIL FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(911,'3DNNBIFLR','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(912,'RTNBIF1X2','BATIK SUPERVOIL FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(913,'1DNNBIF2X','BATIK SUPERVOIL FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(914,'SBNBIFRLR1','BATIK SUPERVOIL FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(915,'PBNBIFRSM1','BATIK SUPERVOIL FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(916,'SBNBIFRME2','BATIK SUPERVOIL FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(917,'RNNBIFRSM1','BATIK SUPERVOIL FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(918,'1DNNBIF1X','BATIK SUPERVOIL FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(919,'KNBIFR2X2','BATIK SUPERVOIL FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(920,'RNNBIFR4X1','BATIK SUPERVOIL FROCK',3,18,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(921,'4DNNBIF3X','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(922,'4DNNBIF1X','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(923,'RTNBIFSM1','BATIK SUPERVOIL FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(924,'RTNBIF5X1','BATIK SUPERVOIL FROCK',3,18,'','','',7650.00,0.00,0.00,'pcs',1,NOW()),(925,'KNBIFRME1','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(926,'RTNBIFME3','BATIK SUPERVOIL FROCK',3,18,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(927,'KNBIFRSM1','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(928,'RTNBIF4X1','BATIK SUPERVOIL FROCK',3,18,'','','',7650.00,0.00,0.00,'pcs',1,NOW()),(929,'RTNBIF6X3','BATIK SUPERVOIL FROCK',3,18,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(930,'KNBIFRLR3','BATIK SUPERVOIL FROCK',3,18,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(931,'VBNBIF3X1','BATIK SUPERVOIL FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(932,'RTNBIFRLR3','BATIK SUPERVOIL FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(933,'PBNBIFRLR2','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(934,'RTNBIFRLR1','BATIK SUPERVOIL FROCK',3,18,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(935,'SBNBIFRME1','BATIK SUPERVOIL FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(936,'PBNBIFRME3','BATIK SUPERVOIL FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(937,'RTNBIFRME1','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(938,'VBNBIFRSM1','BATIK SUPERVOIL FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(939,'PBNBIFRSM2','BATIK SUPERVOIL FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(940,'1DNNBIF6X','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(941,'1DNNBIFME','BATIK SUPERVOIL FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(942,'1DNNBIF5X','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(943,'1DNNBIFSM','BATIK SUPERVOIL FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(944,'RTNBIF3X4','BATIK SUPERVOIL FROCK',3,18,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(945,'RTNBIFME1','BATIK SUPERVOIL FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(946,'3DNNBIFME','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(947,'2DNNBIF3X','BATIK SUPERVOIL FROCK',3,18,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(948,'3DNNBIF3X','BATIK SUPERVOIL FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(949,'RTNBIF4X2','BATIK SUPERVOIL FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(950,'3DNNBIF1X','BATIK SUPERVOIL FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(951,'KNBIFRME2','BATIK SUPERVOIL FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(952,'PBNBIFR4X1','BATIK SUPERVOIL FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(953,'VBNBIF1X1','BATIK SUPERVOIL FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(954,'VBNBIFME1','BATIK SUPERVOIL FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(955,'RTNBIF4X5','BATIK SUPERVOIL FROCK',3,18,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(956,'RTNBIF3X1','BATIK SUPERVOIL FROCK',3,18,'','','',7650.00,0.00,0.00,'pcs',1,NOW()),(957,'1DTNBIFR2X','BATIK SUPERVOIL FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(958,'2DNNBIFLR','BATIK SUPERVOIL FROCK',3,18,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(959,'RTNBIF2X2','BATIK SUPERVOIL FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(960,'KNBIFR2X1','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(961,'VBNBIFRME2','BATIK SUPERVOIL FROCK',3,18,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(962,'PBNBIFRLR1','BATIK SUPERVOIL FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(963,'PBNBIFR3X2','BATIK SUPERVOIL FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(964,'RTNBIFLR1','BATIK SUPERVOIL FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(965,'RTNBIF2X1','BATIK SUPERVOIL FROCK',3,18,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(966,'RTNBIF3X5','BATIK SUPERVOIL FROCK',3,18,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(967,'RNNBIFRLR1','BATIK SUPERVOIL FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(968,'VBNBIFRLR1','BATIK SUPERVOIL FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(969,'RTNBIF2X3','BATIK SUPERVOIL FROCK',3,18,'','','',7650.00,0.00,0.00,'pcs',1,NOW()),(970,'PBNBIFRME2','BATIK SUPERVOIL FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(971,'KNBIFR1X2','BATIK SUPERVOIL FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(972,'KNBIFRLR1','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(973,'RTNBIF6X1','BATIK SUPERVOIL FROCK',3,18,'','','',7650.00,0.00,0.00,'pcs',1,NOW()),(974,'KNBIFR3X1','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(975,'1DNNBIFLR','BATIK SUPERVOIL FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(976,'RTNBIF5X2','BATIK SUPERVOIL FROCK',3,18,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(977,'RTNBIFRLR2','BATIK SUPERVOIL FROCK',3,18,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(978,'3DNNBIF4X','BATIK SUPERVOIL FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(979,'KNBIFRLR4','BATIK SUPERVOIL FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(980,'KNBIFRME3','BATIK SUPERVOIL FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(981,'PBNBIFR2X1','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(982,'4DNNBIF2X','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(983,'KNBIFRSM2','BATIK SUPERVOIL FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(984,'KNBIFRLR2','BATIK SUPERVOIL FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(985,'2DNNBIF1X','BATIK SUPERVOIL FROCK',3,18,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(986,'RTNBIF1X3','BATIK SUPERVOIL FROCK',3,18,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(987,'KNBIFR1X1','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(988,'RTNBIFME2','BATIK SUPERVOIL FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(989,'3DNNBIF2X','BATIK SUPERVOIL FROCK',3,18,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(990,'PBNBIFR1X1','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(991,'RTNBIFLR2','BATIK SUPERVOIL FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(992,'RTNBIF1X1','BATIK SUPERVOIL FROCK',3,18,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(993,'RTNBIFSM2','BATIK SUPERVOIL FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(994,'PBNBIFR5X1','BATIK SUPERVOIL FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(995,'RTNBIF1X4','BATIK SUPERVOIL FROCK',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(996,'RTNBIF4X3','BATIK SUPERVOIL FROCK',3,18,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(997,'PBNBIFR1X2','BATIK SUPERVOIL FROCK',3,18,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(998,'RTNBIF2X5','BATIK SUPERVOIL FROCK',3,18,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(999,'PBNBIFR3X1','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1000,'VBNBIFRSM3','BATIK SUPERVOIL FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW());
INSERT INTO `ezy_pos_items` VALUES (1001,'KNBIFR3X2','BATIK SUPERVOIL FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1002,'1DNNBIF3X','BATIK SUPERVOIL FROCK',3,18,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1003,'2DNNBIF2X','BATIK SUPERVOIL FROCK',3,18,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(1004,'4DNNBIFLR','BATIK SUPERVOIL FROCK',3,18,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1005,'RTNBIFSM3','BATIK SUPERVOIL FROCK',3,18,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(1006,'PBNBIFR6X1','BATIK SUPERVOIL FROCK',3,18,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(1007,'VBNBIFRSM2','BATIK SUPERVOIL FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1008,'PBNBIFRSM3','BATIK SUPERVOIL FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1009,'VBNBIF2X1','BATIK SUPERVOIL FROCK',3,18,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1010,'PBNBIFRSM4','BATIK SUPERVOIL FROCK',3,18,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1011,'1DTNBVFR2X','BATIK VISCOS FROCK',3,18,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(1012,'RTNBVF1X1','BATIK VISCOS FROCK',3,18,'','','',9750.00,0.00,0.00,'pcs',1,NOW()),(1013,'RTNBVFSM1','BATIK VISCOS FROCK',3,18,'','','',9750.00,0.00,0.00,'pcs',1,NOW()),(1014,'KNVBFR1X1','BATIK VISCOS FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1015,'RTNBVFRLR1','BATIK VISCOS FROCK',3,18,'','','',11250.00,0.00,0.00,'pcs',1,NOW()),(1016,'RTNBVFR1X','BATIK VISCOS FROCK',3,18,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(1017,'RTNBVFME1','BATIK VISCOS FROCK',3,18,'','','',9750.00,0.00,0.00,'pcs',1,NOW()),(1018,'RTNBVFLR1','BATIK VISCOS FROCK',3,18,'','','',9750.00,0.00,0.00,'pcs',1,NOW()),(1019,'1DTNBVFR3X','BATIK VISCOS FROCK',3,18,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(1020,'RTNBVFR1X1','BATIK VISCOS FROCK',3,18,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(1021,'KNVBFRLR1','BATIK VISCOS FROCK',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1022,'RTNBVF3X1','BATIK VISCOS FROCK',3,18,'','','',9750.00,0.00,0.00,'pcs',1,NOW()),(1023,'RTNBVF2X1','BATIK VISCOS FROCK',3,18,'','','',9750.00,0.00,0.00,'pcs',1,NOW()),(1024,'KNVBFRME1','BATIK VISCOS FROCK',3,18,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1025,'ANSIDR1X5','SILK  DRESS',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1026,'ANSIDR3X4','SILK  DRESS',3,18,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(1027,'ANSIDRME2','SILK  DRESS',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1028,'ANSIDRSM2','SILK  DRESS',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1029,'ANSIDR4X2','SILK  DRESS',3,18,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(1030,'ANSIDRLR4','SILK  DRESS',3,18,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1031,'ANSIDR2X4','SILK  DRESS',3,18,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(1032,'ANSIDR3X5','SILK DRESS',3,18,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1033,'HNSIDR5X2','SILK DRESS',3,18,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(1034,'HNSIDR6X1','SILK DRESS',3,18,'','','',7650.00,0.00,0.00,'pcs',1,NOW()),(1035,'ANSIDR6X1','SILK DRESS',3,18,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(1036,'ANSIDR4X3','SILK DRESS',3,18,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(1037,'ANSIDR5X1','SILK DRESS',3,18,'','','',7950.00,0.00,0.00,'pcs',1,NOW()),(1038,'1DTBSELDR','SILK ELASTIC DRESS',3,18,'','','',18750.00,0.00,0.00,'pcs',1,NOW()),(1039,'KKBLKULR1','BATIK SUPERLONE KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1040,'KKBLKU2X1','BATIK SUPERLONE KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1041,'KKBLKU1X1','BATIK SUPERLONE KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1042,'RTNBIKUME1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1043,'OSBIKULR1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1044,'RTNBIKUME2','BATIK SUPERVOIL L/KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1045,'RTNBIKULR1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1046,'RTNBIKU3X1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1047,'RTNBIKUSM1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1048,'RTNBIKU2X1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(1049,'RTNBIKUSM2','BATIK SUPERVOIL L/KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1050,'RTNBIKU1X2','BATIK SUPERVOIL L/KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1051,'PBNBIKU2X1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(1052,'PBNBIKUSM1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1053,'RTBIKULR2','BATIK SUPERVOIL L/KURTHA',3,19,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1054,'RTBIKULR1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1055,'RTNBIKUSM3','BATIK SUPERVOIL L/KURTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1056,'PBBIKULR1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1057,'OSBIKU3X1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1058,'PBNBIKU1X1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1059,'OSBIKU2X1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1060,'PBNBIKUSM2','BATIK SUPERVOIL L/KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1061,'RTNBIKU2X2','BATIK SUPERVOIL L/KURTHA',3,19,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1062,'RTNBIKU1X1','BATIK SUPERVOIL L/KURTHA',3,19,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(1063,'SRKCKUME1','COTTON KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1064,'SRKCKU1X1','COTTON KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1065,'SFNCKUSM2','COTTON KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1066,'SRKCKULR1','COTTON KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1067,'SRKCKU2X1','COTTON KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1068,'SRKCKU4X1','COTTON KURTHA',3,19,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(1069,'SRKCKU3X1','COTTON KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1070,'SFKUKU1X2','COTTON KURUTHA',3,19,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1071,'SFKSIKU3X1','COTTON KURUTHA',3,19,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1072,'SFKUKU2X2','COTTON KURUTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1073,'SFKUKU3X2','COTTON KURUTHA',3,19,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1074,'SFNCKU3X1','COTTON KURUTHA WHITE',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1075,'SFNCKUXL1','COTTON KURUTHA WHITE',3,19,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1076,'SFNCKULR2','COTTON KURUTHA WHITE',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1077,'SFNCKU4X1','COTTON KURUTHA WHITE',3,19,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1078,'SFNCKUME1','COTTON KURUTHA WHITE',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1079,'SFNCKU2X1','COTTON KURUTHA WHITE',3,19,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1080,'SFNCKUSM1','COTTON KURUTHA WHITE',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1081,'SRKCKU1X2','COTTON L / KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1082,'SFKCKU2X6','COTTON L / KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1083,'SRKCKULR2','COTTON L / KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1084,'OSCOKUME18','COTTON LADEIS KURTHA',3,19,'','','',1750.00,0.00,0.00,'pcs',1,NOW()),(1085,'OSCOKULR9','COTTON LADEIS KURTHA',3,19,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1086,'OSCOKUME11','COTTON LADEIS KURTHA',3,19,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1087,'OSCOKUME9','COTTON LADEIS KURTHA',3,19,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1088,'OSCOKUME7','COTTON LADEIS KURTHA',3,19,'','','',1950.00,0.00,0.00,'pcs',1,NOW()),(1089,'OSCOKUME3','COTTON LADEIS KURTHA',3,19,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(1090,'OSCOKUME4','COTTON LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1091,'OSCOKUSM6','COTTON LADEIS KURTHA',3,19,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1092,'OSCOKUSM2','COTTON LADEIS KURTHA',3,19,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1093,'OSCOKUSM1','COTTON LADEIS KURTHA',3,19,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1094,'SFKCKU2X3','COTTON LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1095,'OSCOKU3X9','COTTON LADEIS KURTHA',3,19,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1096,'SFKCKUSM2','COTTON LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1097,'SFKCKU2X4','COTTON LADEIS KURTHA',3,19,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1098,'OSCOKUME12','COTTON LADEIS KURTHA',3,19,'','','',2350.00,0.00,0.00,'pcs',1,NOW()),(1099,'OSCOKU4X9','COTTON LADEIS KURTHA',3,19,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1100,'OSCOKU6X5','COTTON LADEIS KURTHA',3,19,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1101,'OSCOKULR10','COTTON LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1102,'OSCOKU2X15','COTTON LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1103,'OSCOKUME2','COTTON LADEIS KURTHA',3,19,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1104,'OSCOKU5X6','COTTON LADEIS KURTHA',3,19,'','','',4350.00,0.00,0.00,'pcs',1,NOW()),(1105,'OSCOKU5X10','COTTON LADEIS KURTHA',3,19,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(1106,'OSCOKU3X8','COTTON LADEIS KURTHA',3,19,'','','',5100.00,0.00,0.00,'pcs',1,NOW()),(1107,'OSCOKU1X7','COTTON LADEIS KURTHA',3,19,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(1108,'OSCOKU3X13','COTTON LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1109,'OSCOKUME6','COTTON LADEIS KURTHA',3,19,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(1110,'OSCOKUME14','COTTON LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1111,'OSCOKUSM3','COTTON LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1112,'SFKCKULR4','COTTON LADEIS KURTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1113,'OSCOKUSM8','COTTON LADEIS KURTHA',3,19,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1114,'OSCOKU2X16','COTTON LADEIS KURTHA',3,19,'','','',2250.00,0.00,0.00,'pcs',1,NOW()),(1115,'SFKCKUSM3','COTTON LADEIS KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1116,'OSCOKUSM7','COTTON LADEIS KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1117,'OSCOKUME17','COTTON LADEIS KURTHA',3,19,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1118,'OSCOKU5X14','COTTON LADEIS KURTHA',3,19,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(1119,'OSCOKU5X15','COTTON LADEIS KURTHA',3,19,'','','',3400.00,0.00,0.00,'pcs',1,NOW()),(1120,'OSCOKUME15','COTTON LADEIS KURTHA',3,19,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1121,'OSCOKU6X3','COTTON LADEIS KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1122,'OSCOKU1X12','COTTON LADEIS KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1123,'OSCOKU4X7','COTTON LADEIS KURTHA',3,19,'','','',2500.00,0.00,0.00,'pcs',1,NOW()),(1124,'OSCOKU6X8','COTTON LADEIS KURTHA',3,19,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(1125,'OSCOKU4X2','COTTON LADEIS KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1126,'OSCOKU3X14','COTTON LADEIS KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1127,'OSCOKU2X11','COTTON LADEIS KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1128,'OSCOKUME8','COTTON LADEIS KURTHA',3,19,'','','',2500.00,0.00,0.00,'pcs',1,NOW()),(1129,'SFKCKU1X2','COTTON LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1130,'OSCOKU4X13','COTTON LADEIS KURTHA',3,19,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(1131,'OSCOKU2X7','COTTON LADEIS KURTHA',3,19,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1132,'OSCOKU1X4','COTTON LADEIS KURTHA',3,19,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1133,'OSCOKU5X5','COTTON LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1134,'OSCOKULR13','COTTON LADEIS KURTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1135,'OSCOKU5X13','COTTON LADEIS KURTHA',3,19,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1136,'OSCOKU2X6','COTTON LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1137,'OSCOKUSM9','COTTON LADEIS KURTHA',3,19,'','','',1950.00,0.00,0.00,'pcs',1,NOW()),(1138,'OSCOKU4X12','COTTON LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1139,'OSCOKU5X12','COTTON LADEIS KURTHA',3,19,'','','',3550.00,0.00,0.00,'pcs',1,NOW()),(1140,'OSCOKU2X13','COTTON LADEIS KURTHA',3,19,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1141,'OSCOKULR7','COTTON LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1142,'OSCOKU1X1','COTTON LADEIS KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1143,'SFKCKULR3','COTTON LADEIS KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1144,'OSCOKU6X2','COTTON LADEIS KURTHA',3,19,'','','',3550.00,0.00,0.00,'pcs',1,NOW()),(1145,'OSCOKU3X10','COTTON LADEIS KURTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1146,'OSCOKUME16','COTTON LADEIS KURTHA',3,19,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1147,'SFKCKU2X5','COTTON LADEIS KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1148,'OSCOKUSM11','COTTON LADEIS KURTHA',3,19,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(1149,'OSCOKUSM10','COTTON LADEIS KURTHA',3,19,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1150,'SFKCKULR5','COTTON LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1151,'OSCOKU5X11','COTTON LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1152,'SFKCKU1X3','COTTON LADEIS KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1153,'OSCOKU1X6','COTTON LADEIS KURTHA',3,19,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1154,'OSCOKUME13','COTTON LADEIS KURTHA',3,19,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(1155,'OSCOKU2X12','COTTON LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1156,'OSCOKU3X5','COTTON LADEIS KURTHA',3,19,'','','',3550.00,0.00,0.00,'pcs',1,NOW()),(1157,'OSCOKU3X3','COTTON LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1158,'OSCOKU6X6','COTTON LADEIS KURTHA',3,19,'','','',4350.00,0.00,0.00,'pcs',1,NOW()),(1159,'OSCOKU6X4','COTTON LADEIS KURTHA',3,19,'','','',2700.00,0.00,0.00,'pcs',1,NOW()),(1160,'OSCOKU3X16','COTTON LADEIS KURTHA',3,19,'','','',6500.00,0.00,0.00,'pcs',1,NOW()),(1161,'OSCOKU1X9','COTTON LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1162,'OSCOKU3X11','COTTON LADEIS KURTHA',3,19,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1163,'OSCOKULR6','COTTON LADEIS KURTHA',3,19,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1164,'OSCOKU3X12','COTTON LADEIS KURTHA',3,19,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1165,'OSCOKULR2','COTTON LADEIS KURTHA',3,19,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1166,'OSCOKU3X7','COTTON LADEIS KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1167,'SFKCKULR2','COTTON LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1168,'OSCOKULR8','COTTON LADEIS KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1169,'SFKCKU2X2','COTTON LADEIS KURTHA',3,19,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1170,'OSCOKU4X11','COTTON LADEIS KURTHA',3,19,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1171,'OSCOKU5X4','COTTON LADEIS KURTHA',3,19,'','','',5100.00,0.00,0.00,'pcs',1,NOW()),(1172,'OSCOKU3X4','COTTON LADEIS KURTHA',3,19,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1173,'SFKCKU1X5','COTTON LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1174,'OSCOKUSM5','COTTON LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1175,'OSCOKU4X1','COTTON LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1176,'SFKCKU3X3','COTTON LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1177,'OSCOKULR12','COTTON LADEIS KURTHA',3,19,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(1178,'OSCOKU4X4','COTTON LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1179,'SFKCKUME2','COTTON LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1180,'OSCOKULR14','COTTON LADEIS KURTHA',3,19,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(1181,'OSCOKU4X10','COTTON LADEIS KURTHA',3,19,'','','',6500.00,0.00,0.00,'pcs',1,NOW()),(1182,'OSCOKU5X8','COTTON LADEIS KURTHA',3,19,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1183,'OSCOKU6X1','COTTON LADEIS KURTHA',3,19,'','','',6500.00,0.00,0.00,'pcs',1,NOW()),(1184,'OSCOKULR5','COTTON LADEIS KURTHA',3,19,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1185,'OSCOKULR4','COTTON LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1186,'OSCOKU6X7','COTTON LADEIS KURTHA',3,19,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1187,'OSCOKU5X7','COTTON LADEIS KURTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1188,'OSCOKU5X3','COTTON LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1189,'OSCOKU5X1','COTTON LADEIS KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1190,'OSCOKU1X13','COTTON LADEIS KURTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1191,'OSCOKU1X10','COTTON LADEIS KURTHA',3,19,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1192,'OSCOKUME1','COTTON LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1193,'OSCOKU2X9','COTTON LADEIS KURTHA',3,19,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1194,'OSCOKU6X9','COTTON LADEIS KURTHA',3,19,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1195,'SFKCKUME3','COTTON LADEIS KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1196,'SFKCKUME4','COTTON LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1197,'OSCOKU1X3','COTTON LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1198,'OSCOKU2X1','COTTON LADEIS KURTHA',3,19,'','','',4550.00,0.00,0.00,'pcs',1,NOW()),(1199,'OSCOKU4X6','COTTON LADEIS KURTHA',3,19,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1200,'OSCOKU1X5','COTTON LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW());
INSERT INTO `ezy_pos_items` VALUES (1201,'OSCOKU4X5','COTTON LADEIS KURTHA',3,19,'','','',3350.00,0.00,0.00,'pcs',1,NOW()),(1202,'OSCOKU4X3','COTTON LADEIS KURTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1203,'OSCOKU2X3','COTTON LADEIS KURTHA',3,19,'','','',2500.00,0.00,0.00,'pcs',1,NOW()),(1204,'OSCOKU6X10','COTTON LADEIS KURTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1205,'OSCOKU4X16','COTTON LADEIS KURTHA',3,19,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1206,'SFKCKU1X4','COTTON LADEIS KURTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1207,'OSCOKUME5','COTTON LADEIS KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1208,'OSCOKUSM4','COTTON LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1209,'OSCOKU3X15','COTTON LADEIS KURTHA',3,19,'','','',3100.00,0.00,0.00,'pcs',1,NOW()),(1210,'OSCOKU2X5','COTTON LADEIS KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1211,'OSCOKU1X2','COTTON LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1212,'OSCOKU4X15','COTTON LADEIS KURTHA',3,19,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(1213,'OSCOKULR1','COTTON LADEIS KURTHA',3,19,'','','',1950.00,0.00,0.00,'pcs',1,NOW()),(1214,'OSCOKULR11','COTTON LADEIS KURTHA',3,19,'','','',4350.00,0.00,0.00,'pcs',1,NOW()),(1215,'OSCOKU2X14','COTTON LADEIS KURTHA',3,19,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1216,'OSCOKU5X9','COTTON LADEIS KURTHA',3,19,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1217,'OSCOKU3X1','COTTON LADEIS KURTHA',3,19,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1218,'OSCOKU1X11','COTTON LADEIS KURTHA',3,19,'','','',3100.00,0.00,0.00,'pcs',1,NOW()),(1219,'OSCOKUME10','COTTON LADEIS KURTHA',3,19,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1220,'OSCOKU5X2','COTTON LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1221,'OSCOKU3X6','COTTON LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1222,'OSCOKU3X2','COTTON LADEIS KURTHA',3,19,'','','',4550.00,0.00,0.00,'pcs',1,NOW()),(1223,'OSCOKU2X10','COTTON LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1224,'OSCOKU2X8','COTTON LADEIS KURTHA',3,19,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1225,'OSCOKU2X2','COTTON LADEIS KURTHA',3,19,'','','',2650.00,0.00,0.00,'pcs',1,NOW()),(1226,'OSCOKU4X14','COTTON LADEIS KURTHA',3,19,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1227,'OSCOKU1X8','COTTON LADEIS KURTHA',3,19,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1228,'OSCOKU2X4','COTTON LADEIS KURTHA',3,19,'','','',4350.00,0.00,0.00,'pcs',1,NOW()),(1229,'OSCOKU4X8','COTTON LADEIS KURTHA',3,19,'','','',2350.00,0.00,0.00,'pcs',1,NOW()),(1230,'OSCOKULR3','COTTON LADEIS KURTHA',3,19,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(1231,'SFKCKULR1','COTTON LADIES KURTHA',3,19,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(1232,'SFKCKU6X1','COTTON LADIES KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1233,'SFKCKU3X1','COTTON LADIES KURTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1234,'OSKCKUME1','COTTON LADIES KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1235,'SFKCKU4X2','COTTON LADIES KURTHA',3,19,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1236,'SFKCKU2X1','COTTON LADIES KURTHA',3,19,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1237,'SFKCKUSM1','COTTON LADIES KURTHA',3,19,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(1238,'SFNCKULR1','COTTON LADIES KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1239,'SRNCKU2X1','COTTON LADIES KURTHA',3,19,'','','',5100.00,0.00,0.00,'pcs',1,NOW()),(1240,'SFNCKU1X1','COTTON LADIES KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1241,'SFKCKU1X1','COTTON LADIES KURTHA',3,19,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(1242,'SFKCKUME1','COTTON LADIES KURTHA',3,19,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(1243,'SRNCKU1X1','COTTON LADIES KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1244,'SRNCKULR1','COTTON LADIES KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1245,'SFKCKU3X2','COTTON LADIES KURTHA',3,19,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1246,'SFKCKU4X1','COTTON LADIES KURTHA',3,19,'','','',6500.00,0.00,0.00,'pcs',1,NOW()),(1247,'SFKCKU5X1','COTTON LADIES KURTHA',3,19,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1248,'OSSALEKU6','LADIES KURTHA',3,19,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1249,'OSSALEKU1','LADIES KURTHA',3,19,'','','',1500.00,0.00,0.00,'pcs',1,NOW()),(1250,'OSSALEKU5','LADIES KURTHA',3,19,'','','',2500.00,0.00,0.00,'pcs',1,NOW()),(1251,'OSSALEKU2','LADIES KURTHA',3,19,'','','',1750.00,0.00,0.00,'pcs',1,NOW()),(1252,'OSSALEKU4','LADIES KURTHA',3,19,'','','',2250.00,0.00,0.00,'pcs',1,NOW()),(1253,'OSSALEKU7','LADIES KURTHA',3,19,'','','',3000.00,0.00,0.00,'pcs',1,NOW()),(1254,'OSSALEKU3','LADIES KURTHA',3,19,'','','',2000.00,0.00,0.00,'pcs',1,NOW()),(1255,'OSSIKU4X3','SILK LADEIS KURTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1256,'OSSIKUME8','SILK LADEIS KURTHA',3,19,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1257,'OSSIKUSM5','SILK LADEIS KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1258,'OSSIKUSM3','SILK LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1259,'OSSIKU5X1','SILK LADEIS KURTHA',3,19,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1260,'OSSIKU5X2','SILK LADEIS KURTHA',3,19,'','','',5100.00,0.00,0.00,'pcs',1,NOW()),(1261,'OSSIKU1X10','SILK LADEIS KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1262,'OSSIKU3X4','SILK LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1263,'HFKSIKULR2','SILK LADEIS KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1264,'AKSIKU2X1','SILK LADEIS KURTHA',3,19,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(1265,'OSSIKU1X2','SILK LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1266,'OSSIKU1X5','SILK LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1267,'OSSIKU1X3','SILK LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1268,'OSSIKU1X1','SILK LADEIS KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1269,'OSSIKU1X4','SILK LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1270,'OSSIKUME5','SILK LADEIS KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1271,'OSSIKU4X2','SILK LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1272,'OSSIKU5X4','SILK LADEIS KURTHA',3,19,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1273,'OSSIKUME2','SILK LADEIS KURTHA',3,19,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1274,'OSSIKUME13','SILK LADEIS KURTHA',3,19,'','','',3500.00,0.00,0.00,'pcs',1,NOW()),(1275,'OSSIKU2X6','SILK LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1276,'OSSIKU3X6','SILK LADEIS KURTHA',3,19,'','','',4350.00,0.00,0.00,'pcs',1,NOW()),(1277,'OSSIKU6X1','SILK LADEIS KURTHA',3,19,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1278,'OSSIKUME9','SILK LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1279,'OSSIKUME12','SILK LADEIS KURTHA',3,19,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1280,'OSSIKUME7','SILK LADEIS KURTHA',3,19,'','','',2500.00,0.00,0.00,'pcs',1,NOW()),(1281,'OSSIKU5X6','SILK LADEIS KURTHA',3,19,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(1282,'OSSIKU6X4','SILK LADEIS KURTHA',3,19,'','','',6500.00,0.00,0.00,'pcs',1,NOW()),(1283,'OSSIKULR5','SILK LADEIS KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1284,'OSSIKUSM2','SILK LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1285,'OSSIKULR4','SILK LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1286,'OSSIKU2X3','SILK LADEIS KURTHA',3,19,'','','',6850.00,0.00,0.00,'pcs',1,NOW()),(1287,'OSSIKULR3','SILK LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1288,'OSSIKUSM4','SILK LADEIS KURTHA',3,19,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1289,'OSSIKULR9','SILK LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1290,'OSSIKU4X1','SILK LADEIS KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1291,'OSSIKU4X5','SILK LADEIS KURTHA',3,19,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1292,'OSSIKU6X2','SILK LADEIS KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1293,'OSSIKU1X7','SILK LADEIS KURTHA',3,19,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1294,'OSSIKU2X2','SILK LADEIS KURTHA',3,19,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1295,'OSSIKUME4','SILK LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1296,'OSSIKU6X3','SILK LADEIS KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1297,'OSSIKU2X1','SILK LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1298,'HFKSIKU2X1','SILK LADEIS KURTHA',3,19,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1299,'OSSIKUME3','SILK LADEIS KURTHA',3,19,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1300,'OSSIKU1X6','SILK LADEIS KURTHA',3,19,'','','',3500.00,0.00,0.00,'pcs',1,NOW()),(1301,'OSSIKU5X7','SILK LADEIS KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1302,'HFKSIKUME1','SILK LADEIS KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1303,'OSSIKUME6','SILK LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1304,'OSSIKU5X11','SILK LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1305,'OSSIKU2X5','SILK LADEIS KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1306,'HFKSIKULR1','SILK LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1307,'HFKSIKU1X1','SILK LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1308,'OSSIKU5X3','SILK LADEIS KURTHA',3,19,'','','',6500.00,0.00,0.00,'pcs',1,NOW()),(1309,'OSSIKU3X2','SILK LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1310,'OSSIKU3X7','SILK LADEIS KURTHA',3,19,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1311,'AKSIKULR1','SILK LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1312,'AKSIKU1X1','SILK LADEIS KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1313,'OSSIKU3X1','SILK LADEIS KURTHA',3,19,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1314,'OSSIKU3X5','SILK LADEIS KURTHA',3,19,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1315,'OSSIKULR1','SILK LADEIS KURTHA',3,19,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1316,'OSSIKULR2','SILK LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1317,'OSSIKU1X9','SILK LADEIS KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1318,'OSSIKUME11','SILK LADEIS KURTHA',3,19,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1319,'OSSIKU5X5','SILK LADEIS KURTHA',3,19,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1320,'OSSIKUSM8','SILK LADEIS KURTHA',3,19,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1321,'AKSIKU4X1','SILK LADEIS KURTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1322,'OSSIKUME1','SILK LADEIS KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1323,'AKSIKU3X2','SILK LADEIS KURTHA',3,19,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1324,'HFKSIKU1X2','SILK LADEIS KURTHA',3,19,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1325,'OSSIKULR7','SILK LADEIS KURTHA',3,19,'','','',2500.00,0.00,0.00,'pcs',1,NOW()),(1326,'OSSIKULR6','SILK LADEIS KURTHA',3,19,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1327,'OSSIKUSM7','SILK LADEIS KURTHA',3,19,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1328,'OSSIKU5X10','SILK LADEIS KURTHA',3,19,'','','',3100.00,0.00,0.00,'pcs',1,NOW()),(1329,'OSSIKU5X8','SILK LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1330,'OSSIKU1X8','SILK LADEIS KURTHA',3,19,'','','',4350.00,0.00,0.00,'pcs',1,NOW()),(1331,'OSSIKUME10','SILK LADEIS KURTHA',3,19,'','','',4550.00,0.00,0.00,'pcs',1,NOW()),(1332,'OSSIKU4X4','SILK LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1333,'OSSIKULR8','SILK LADEIS KURTHA',3,19,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1334,'OSSIKU2X4','SILK LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1335,'OSSIKU3X3','SILK LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1336,'HFKSIKU3X1','SILK LADEIS KURTHA',3,19,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1337,'OSSIKU5X9','SILK LADEIS KURTHA',3,19,'','','',4350.00,0.00,0.00,'pcs',1,NOW()),(1338,'AKSIKUME1','SILK LADEIS KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1339,'OSSIKUSM6','SILK LADEIS KURTHA',3,19,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1340,'AKSIKU3X1','SILK LADIES KURTHA',3,19,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1341,'SRNSKULR1','SILK LADIES KURTHA',3,19,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1342,'SRNSKU1X1','SILK LADIES KURTHA',3,19,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1343,'PBBCLNFS1','BATIK COTTON LUNGIE',3,20,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1344,'RTNBILNM2','BATIK LUNGIE',3,20,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1345,'RTNSLNXL1','BATIK LUNGIE',3,20,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1346,'RTNSLNME1','BATIK LUNGIE',3,20,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1347,'RTNBILNL1','BATIK LUNGIE',3,20,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1348,'RTNBILN1X','BATIK LUNGIE',3,20,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1349,'RTNSLNL01','BATIK SUPERVOIL LUNGIE',3,20,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1350,'RTNSLN201','BATIK SUPERVOIL LUNGIE',3,20,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1351,'SFKCLN5X1','COTTON LUNGE',3,20,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1352,'SFKCLN4X1','COTTON LUNGIE',3,20,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1353,'SFKCLN1X1','COTTON LUNGIE',3,20,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1354,'SFRCLN2X1','COTTON LUNGIE',3,20,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(1355,'SFKCLNME1','COTTON LUNGIE',3,20,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1356,'SFKCLNSM1','COTTON LUNGIE',3,20,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1357,'SFRCLNLR1','COTTON LUNGIE',3,20,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1358,'SFRCLN3X1','COTTON LUNGIE',3,20,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(1359,'SFRCLNME1','COTTON LUNGIE',3,20,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1360,'SFKCLN3X1','COTTON LUNGIE',3,20,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(1361,'SFRCLNSM1','COTTON LUNGIE',3,20,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1362,'SFRCLN1X1','COTTON LUNGIE',3,20,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1363,'SFKCLNLR1','COTTON LUNGIE',3,20,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1364,'SFKCLN2X1','COTTON LUNGIE',3,20,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(1365,'HFKSILNLR1','SILK LUNGIE',3,20,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1366,'AKSILNLR2','SILK LUNGIE',3,20,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1367,'HFKSILN1X1','SILK LUNGIE',3,20,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1368,'HFKSILN4X1','SILK LUNGIE',3,20,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1369,'AKSILNME1','SILK LUNGIE',3,20,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1370,'AKSILNLR1','SILK LUNGIE',3,20,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1371,'HFKSILNME1','SILK LUNGIE',3,20,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1372,'HFKSILNSM1','SILK LUNGIE',3,20,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1373,'HFKSILN3X1','SILK LUNGIE',3,20,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1374,'AKSILNME2','SILK LUNGIE',3,20,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1375,'AKSILN2X1','SILK LUNGIE',3,20,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1376,'AKSILN1X1','SILK LUNGIE',3,20,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1377,'HFKSILN2X1','SILK LUNGIE',3,20,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1378,'SRWCLN3X1','WORKMENT COTTON LUNGIE',3,20,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(1379,'SRWCLNME1','WORKMENT COTTON LUNGIE',3,20,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1380,'SRWCLNSM1','WORKMENT COTTON LUNGIE',3,20,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1381,'SRWCLN1X1','WORKMENT COTTON LUNGIE',3,20,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1382,'SRWCLN4X1','WORKMENT COTTON LUNGIE',3,20,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(1383,'SRWCLN2X1','WORKMENT COTTON LUNGIE',3,20,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1384,'SRWCLNLR1','WORKMENT COTTON LUNGIE',3,20,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1385,'HFWSLNLR1','WORKMENT SILK LUNGIE',3,20,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1386,'HFWSLN1X1','WORKMENT SILK LUNGIE',3,20,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1387,'HFWSLNSM1','WORKMENT SILK LUNGIE',3,20,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1388,'HFWSLNME1','WORKMENT SILK LUNGIE',3,20,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1389,'HFWSLN4X1','WORKMENT SILK LUNGIE',3,20,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1390,'HFWSLN3X1','WORKMENT SILK LUNGIE',3,20,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1391,'HFWSLN2X1','WORKMENT SILK LUNGIE',3,20,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(1392,'KNBLPT2X1','BATIK SUPERLONE PANT',3,21,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1393,'KNBLPTME1','BATIK SUPERLONE PANT',3,21,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1394,'KNBLPT1X1','BATIK SUPERLONE PANT',3,21,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1395,'KNBLPTLR1','BATIK SUPERLONE PANT',3,21,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1396,'SFNCPTME1','COTTON BAGGIE PANT',3,21,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1397,'SFNCPT3X1','COTTON BAGGIE PANT',3,21,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1398,'SFNCPT1X1','COTTON BAGGIE PANT',3,21,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1399,'SFNCPTLR1','COTTON BAGGIE PANT',3,21,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1400,'SRNCPTME1','COTTON BAGGIE PANT',3,21,'','','',5250.00,0.00,0.00,'pcs',1,NOW());
INSERT INTO `ezy_pos_items` VALUES (1401,'SFNCPT4X1','COTTON BAGGIE PANT',3,21,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1402,'SFNCPT2X1','COTTON BAGGIE PANT',3,21,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1403,'SFNCPTSM2','COTTON BAGGIE PANT',3,21,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1404,'SFNCPT5X1','COTTON L / PANT',3,21,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1405,'SFNCPT4X2','COTTON L / PANT',3,21,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1406,'SFNCPT3X2','COTTON L / PANT',3,21,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1407,'SFNCPTME2','COTTON L / PANT',3,21,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1408,'SFNCPTSM1','COTTON L / PANT',3,21,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1409,'SFNCPTLR2','COTTON L / PANT',3,21,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1410,'SFNCPT1X2','COTTON L / PANT',3,21,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1411,'SFNCPT2X2','COTTON L / PANT',3,21,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1412,'SRNCPTLR1','COTTON LADIES PANT',3,21,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1413,'SRNCPT2X1','COTTON LADIES PANT',3,21,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(1414,'SRNCPT4X1','COTTON LADIES PANT',3,21,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1415,'SRNCPT3X1','COTTON LADIES PANT',3,21,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(1416,'SRNCPT1X1','COTTON LADIES PANT',3,21,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1417,'SFNSPT4X1','SILK LADIES PANT',3,21,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1418,'SFNSPT3X1','SILK LADIES PANT',3,21,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1419,'SFNSPT1X1','SILK LADIES PANT',3,21,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1420,'SFNSPT2X1','SILK LADIES PANT',3,21,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1421,'SFNSPTLR1','SILK LADIES PANT',3,21,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1422,'SFNSPTME1','SILK LADIES PANT',3,21,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1423,'PBBISKBL1','BATIK BABY KIT',3,22,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1424,'KNPOBLLR1','BATIK PONCHO',3,22,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1425,'KNPOBLME1','BATIK PONCHO',3,22,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1426,'SNPOBL3X1','BATIK PONCHO',3,22,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1427,'KNPOBL3X1','BATIK PONCHO',3,22,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1428,'KNPOBL1X1','BATIK PONCHO',3,22,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1429,'KNPOBL2X1','BATIK PONCHO',3,22,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1430,'SMBCSA002','BATIK COTTON 2COLOURS SAREE',3,23,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1431,'SMBCSA001','BATIK COTTON 3COLOURS SAREE',3,23,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(1432,'SMBCSA004','BATIK COTTON SAREE',3,23,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1433,'SMBCSA003','BATIK COTTON SAREE',3,23,'','','',5500.00,0.00,0.00,'pcs',1,NOW()),(1434,'SMBCSA005','BATIK COTTON SAREE',3,23,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1435,'1DTBKCOSA','BATIK COTTON SAREE',3,23,'','','',9750.00,0.00,0.00,'pcs',1,NOW()),(1436,'SMBCSA007','BATIK COTTON SAREE',3,23,'','','',8750.00,0.00,0.00,'pcs',1,NOW()),(1437,'BGBKCOSA2','BATIK COTTON SAREE',3,23,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1438,'SBBKCOSA3','BATIK COTTON SAREE',3,23,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1439,'SBBKCOSA2','BATIK COTTON SAREE',3,23,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1440,'SBBKCOSA1','BATIK COTTON SAREE',3,23,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1441,'BGBKCOSA1','BATIK COTTON SAREE',3,23,'','','',8750.00,0.00,0.00,'pcs',1,NOW()),(1442,'2DTBSSA','BATIK SILK SAREE',3,23,'','','',59500.00,0.00,0.00,'pcs',1,NOW()),(1443,'3DTBSSA03','BATIK SILK SAREE',3,23,'','','',35950.00,0.00,0.00,'pcs',1,NOW()),(1444,'1DTBSSA01','BATIK SILK SAREE',3,23,'','','',59950.00,0.00,0.00,'pcs',1,NOW()),(1445,'RTBSISA01','BATIK SILK SAREE',3,23,'','','',44500.00,0.00,0.00,'pcs',1,NOW()),(1446,'RTBSSA001','BATIK SILK SAREE',3,23,'','','',47500.00,0.00,0.00,'pcs',1,NOW()),(1447,'2DTBSSA02','BATIK SILK SAREE',3,23,'','','',37750.00,0.00,0.00,'pcs',1,NOW()),(1448,'1DTBSSA','BATIK SILK SAREE',3,23,'','','',69500.00,0.00,0.00,'pcs',1,NOW()),(1449,'RTBSISA02','BATIK SILK SAREE',3,23,'','','',49750.00,0.00,0.00,'pcs',1,NOW()),(1450,'RTBSCMSA1','BATIK SILKCOTTON SAREE',3,23,'','','',12750.00,0.00,0.00,'pcs',1,NOW()),(1451,'RTBSCMSA2','BATIK SILKCOTTON SAREE',3,23,'','','',18500.00,0.00,0.00,'pcs',1,NOW()),(1452,'SBBISA3C1','BATIK SUPEROIL SAARE',3,23,'','','',8750.00,0.00,0.00,'pcs',1,NOW()),(1453,'1DTBKBISA','BATIK SUPERVOIL SAREE',3,23,'','','',9750.00,0.00,0.00,'pcs',1,NOW()),(1454,'SBBKSVSA2','BATIK SUPERVOIL SAREE',3,23,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1455,'SBBKSVSA1','BATIK SUPERVOIL SAREE',3,23,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1456,'RTBKSVSA2','BATIK SUPERVOIL SAREE',3,23,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(1457,'SBBISA2CL','BATIK SUPERVOIL SAREE',3,23,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1458,'1DTBKSVSA','BATIK SUPERVOIL SAREE',3,23,'','','',9750.00,0.00,0.00,'pcs',1,NOW()),(1459,'BGBKSVSA1','BATIK SUPERVOIL SAREE',3,23,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1460,'RTBISA001','BATIK SUPERVOIL SAREE',3,23,'','','',13950.00,0.00,0.00,'pcs',1,NOW()),(1461,'SBBISA3CL','BATIK SUPERVOIL SAREE',3,23,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(1462,'RTBKSVSA1','BATIK SUPERVOIL SAREE',3,23,'','','',13950.00,0.00,0.00,'pcs',1,NOW()),(1463,'RTBISA002','BATIK SUPERVOIL SAREE',3,23,'','','',13950.00,0.00,0.00,'pcs',1,NOW()),(1464,'KBBKSVSA1','BATIK SUPERVOIL SAREE',3,23,'','','',10750.00,0.00,0.00,'pcs',1,NOW()),(1465,'PBBISA001','BATIK SUPERVOIL SAREE',3,23,'','','',10750.00,0.00,0.00,'pcs',1,NOW()),(1466,'1DNBKSVSA','BATIK SUPERVOIL SAREE',3,23,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(1467,'ABCOSA003','COTTON PUNI SAREE',3,23,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1468,'ABCOSA005','COTTON PUNI SAREE',3,23,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(1469,'MCCOSA002','COTTON SAREE',3,23,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1470,'JHCOSA008','COTTON SAREE',3,23,'','','',9750.00,0.00,0.00,'pcs',1,NOW()),(1471,'ACOSA0008','COTTON SAREE',3,23,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1472,'JHCOSA007','COTTON SAREE',3,23,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1473,'JLCOSA005','COTTON SAREE',3,23,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1474,'JLCOSA004','COTTON SAREE',3,23,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(1475,'JHCOSA006','COTTON SAREE',3,23,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1476,'ACOSA0001','COTTON SAREE',3,23,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(1477,'JLCOSA003','COTTON SAREE',3,23,'','','',8750.00,0.00,0.00,'pcs',1,NOW()),(1478,'SRCOSA001','COTTON SAREE',3,23,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1479,'ACOSA0005','COTTON SAREE',3,23,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1480,'BHCOSA001','COTTON SAREE',3,23,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1481,'JHCOSA005','COTTON SAREE',3,23,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1482,'ACOSA0004','COTTON SAREE',3,23,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1483,'JLCOSA002','COTTON SAREE',3,23,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1484,'OSCOSA001','COTTON SAREE',3,23,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1485,'BHCOSA002','COTTON SAREE',3,23,'','','',9750.00,0.00,0.00,'pcs',1,NOW()),(1486,'ACOSA0006','COTTON SAREE',3,23,'','','',8750.00,0.00,0.00,'pcs',1,NOW()),(1487,'ACOSA0003','COTTON SAREE',3,23,'','','',7950.00,0.00,0.00,'pcs',1,NOW()),(1488,'JHCOSA004','COTTON SAREE',3,23,'','','',8750.00,0.00,0.00,'pcs',1,NOW()),(1489,'ACOSA0007','COTTON SAREE',3,23,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1490,'JHCOSA009','COTTON SAREE',3,23,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1491,'KHCOSA001','COTTON SAREE',3,23,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1492,'OSSALESA1','COTTON SAREE',3,23,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1493,'ABCOSA002','COTTON SAREE',3,23,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1494,'JLCOSA001','COTTON SAREE',3,23,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1495,'TLCOSA001','COTTON SAREE',3,23,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1496,'MCCOSA001','COTTON SAREE',3,23,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1497,'JHCOSA002','COTTON SAREE',3,23,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1498,'JHCOSA003','COTTON SAREE',3,23,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(1499,'JLSCWCSA5','COTTON SAREE',3,23,'','','',13950.00,0.00,0.00,'pcs',1,NOW()),(1500,'ACOSA0002','COTTON SAREE',3,23,'','','',7450.00,0.00,0.00,'pcs',1,NOW()),(1501,'ABCOSA001','COTTON SAREE',3,23,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1502,'JHCOSA001','COTTON SAREE',3,23,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1503,'MZCOSA001','COTTON SAREE',3,23,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1504,'ACOSA0010','COTTON SAREE',3,23,'','','',8750.00,0.00,0.00,'pcs',1,NOW()),(1505,'ACOSA0009','COTTON SAREE',3,23,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(1506,'ABCOSA004','COTTON WORK SAREE',3,23,'','','',8750.00,0.00,0.00,'pcs',1,NOW()),(1507,'JMDESISA10','DESIGN SILK SAREE',3,23,'','','',14750.00,0.00,0.00,'pcs',1,NOW()),(1508,'JMDESISA09','DESIGN SILK SAREE',3,23,'','','',16950.00,0.00,0.00,'pcs',1,NOW()),(1509,'JMDESISA07','DESIGN SILK SAREE',3,23,'','','',9750.00,0.00,0.00,'pcs',1,NOW()),(1510,'JMDESISA05','DESIGN SILK SAREE',3,23,'','','',17750.00,0.00,0.00,'pcs',1,NOW()),(1511,'JMDESISA04','DESIGN SILK SAREE',3,23,'','','',10950.00,0.00,0.00,'pcs',1,NOW()),(1512,'JMDESISA03','DESIGN SILK SAREE',3,23,'','','',19750.00,0.00,0.00,'pcs',1,NOW()),(1513,'JMDESISA06','DESIGN SILK SAREE',3,23,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(1514,'JMDESISA13','DESIGN SILK SAREE',3,23,'','','',19950.00,0.00,0.00,'pcs',1,NOW()),(1515,'JMDESISA02','DESIGN SILK SAREE',3,23,'','','',18750.00,0.00,0.00,'pcs',1,NOW()),(1516,'JMDESISA08','DESIGN SILK SAREE',3,23,'','','',17950.00,0.00,0.00,'pcs',1,NOW()),(1517,'JMDESISA11','DESING SILK SAREE',3,23,'','','',12750.00,0.00,0.00,'pcs',1,NOW()),(1518,'JMDESISA01','DESING SILK SAREE',3,23,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(1519,'JMDESISA12','DESING SILK SAREE',3,23,'','','',15750.00,0.00,0.00,'pcs',1,NOW()),(1520,'JMDESISA14','DESING SILK SAREE',3,23,'','','',18250.00,0.00,0.00,'pcs',1,NOW()),(1521,'APWCOSA01','PACTHWORKMENT SAREE',3,23,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(1522,'JLPWSSA01','PERL WORK SILK SAREE',3,23,'','','',12750.00,0.00,0.00,'pcs',1,NOW()),(1523,'SMPCOSA01','PLAIN COTTON SAREE',3,23,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1524,'TLPOSA002','POLLYESTER SAREE',3,23,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1525,'TLPOSA001','POLLYESTER SAREE',3,23,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1526,'OSPOSA001','POLLYESTER SAREE',3,23,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1527,'RYPYSA001','POLLYESTER SAREE',3,23,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1528,'RYPYSA002','POLLYESTER SAREE',3,23,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(1529,'SMPRSA001','PRINTED COTTON SAREE',3,23,'','','',6250.00,0.00,0.00,'pcs',1,NOW()),(1530,'JLPCSA001','PUNI COTTON SAREE',3,23,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(1531,'ASISA0004','SILK SAREE',3,23,'','','',9950.00,0.00,0.00,'pcs',1,NOW()),(1532,'JLSISA003','SILK SAREE',3,23,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(1533,'ASISA0017','SILK SAREE',3,23,'','','',10250.00,0.00,0.00,'pcs',1,NOW()),(1534,'MCSISA001','SILK SAREE',3,23,'','','',9450.00,0.00,0.00,'pcs',1,NOW()),(1535,'ASISA0012','SILK SAREE',3,23,'','','',22500.00,0.00,0.00,'pcs',1,NOW()),(1536,'ASISA0016','SILK SAREE',3,23,'','','',17950.00,0.00,0.00,'pcs',1,NOW()),(1537,'JHSISA005','SILK SAREE',3,23,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(1538,'ASISA0015','SILK SAREE',3,23,'','','',9750.00,0.00,0.00,'pcs',1,NOW()),(1539,'ASISA0002','SILK SAREE',3,23,'','','',10750.00,0.00,0.00,'pcs',1,NOW()),(1540,'ASISA0013','SILK SAREE',3,23,'','','',27500.00,0.00,0.00,'pcs',1,NOW()),(1541,'ASISA0008','SILK SAREE',3,23,'','','',10950.00,0.00,0.00,'pcs',1,NOW()),(1542,'ASISA0007','SILK SAREE',3,23,'','','',9450.00,0.00,0.00,'pcs',1,NOW()),(1543,'VSSISA004','SILK SAREE',3,23,'','','',13950.00,0.00,0.00,'pcs',1,NOW()),(1544,'MZSISA002','SILK SAREE',3,23,'','','',10450.00,0.00,0.00,'pcs',1,NOW()),(1545,'ASISA0006','SILK SAREE',3,23,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(1546,'VSSISA003','SILK SAREE',3,23,'','','',16750.00,0.00,0.00,'pcs',1,NOW()),(1547,'VSSISA002','SILK SAREE',3,23,'','','',17750.00,0.00,0.00,'pcs',1,NOW()),(1548,'VSSISA001','SILK SAREE',3,23,'','','',14750.00,0.00,0.00,'pcs',1,NOW()),(1549,'MZSISA001','SILK SAREE',3,23,'','','',9950.00,0.00,0.00,'pcs',1,NOW()),(1550,'JHSISA003','SILK SAREE',3,23,'','','',10450.00,0.00,0.00,'pcs',1,NOW()),(1551,'JHSISA001','SILK SAREE',3,23,'','','',9950.00,0.00,0.00,'pcs',1,NOW()),(1552,'JLSISA001','SILK SAREE',3,23,'','','',9950.00,0.00,0.00,'pcs',1,NOW()),(1553,'ASISA0014','SILK SAREE',3,23,'','','',24500.00,0.00,0.00,'pcs',1,NOW()),(1554,'ASISA0011','SILK SAREE',3,23,'','','',12750.00,0.00,0.00,'pcs',1,NOW()),(1555,'ASISA0005','SILK SAREE',3,23,'','','',13750.00,0.00,0.00,'pcs',1,NOW()),(1556,'JLSISA004','SILK SAREE',3,23,'','','',12750.00,0.00,0.00,'pcs',1,NOW()),(1557,'ASISA0003','SILK SAREE',3,23,'','','',13950.00,0.00,0.00,'pcs',1,NOW()),(1558,'ASISA0001','SILK SAREE',3,23,'','','',10450.00,0.00,0.00,'pcs',1,NOW()),(1559,'JLSISA002','SILK SAREE',3,23,'','','',9250.00,0.00,0.00,'pcs',1,NOW()),(1560,'VSSISA005','SILK SAREE',3,23,'','','',13750.00,0.00,0.00,'pcs',1,NOW()),(1561,'ABSISA002','SILK SAREE',3,23,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(1562,'VSSISA008','SILK SAREE',3,23,'','','',18950.00,0.00,0.00,'pcs',1,NOW()),(1563,'VSSISA006','SILK SAREE',3,23,'','','',18750.00,0.00,0.00,'pcs',1,NOW()),(1564,'VSSISA007','SILK SAREE',3,23,'','','',15950.00,0.00,0.00,'pcs',1,NOW()),(1565,'ABSISA001','SILK SAREE',3,23,'','','',9950.00,0.00,0.00,'pcs',1,NOW()),(1566,'ASISA0010','SILK SAREE',3,23,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(1567,'ASISA0009','SILK SAREE',3,23,'','','',9250.00,0.00,0.00,'pcs',1,NOW()),(1568,'JHSISA002','SILK SAREE',3,23,'','','',13750.00,0.00,0.00,'pcs',1,NOW()),(1569,'JHSISA004','SILK SAREE',3,23,'','','',10750.00,0.00,0.00,'pcs',1,NOW()),(1570,'ABWCOSA02','WORKMENT COTTON SAREE',3,23,'','','',9250.00,0.00,0.00,'pcs',1,NOW()),(1571,'JLSCWCSA4','WORKMENT COTTON SAREE',3,23,'','','',14750.00,0.00,0.00,'pcs',1,NOW()),(1572,'JLWCSA003','WORKMENT COTTON SAREE',3,23,'','','',8250.00,0.00,0.00,'pcs',1,NOW()),(1573,'JLWCSA002','WORKMENT COTTON SAREE',3,23,'','','',7950.00,0.00,0.00,'pcs',1,NOW()),(1574,'JLWCSA001','WORKMENT COTTON SAREE',3,23,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(1575,'JLSCWCSA1','WORKMENT COTTON SAREE',3,23,'','','',16750.00,0.00,0.00,'pcs',1,NOW()),(1576,'JHSCWCSA3','WORKMENT COTTON SAREE',3,23,'','','',14750.00,0.00,0.00,'pcs',1,NOW()),(1577,'JHSCWCSA2','WORKMENT COTTON SAREE',3,23,'','','',16750.00,0.00,0.00,'pcs',1,NOW()),(1578,'ABWCOSA01','WORKMENT COTTON SAREE',3,23,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(1579,'JLSCWCSA2','WORKMENT COTTON SAREE',3,23,'','','',15750.00,0.00,0.00,'pcs',1,NOW()),(1580,'JHSCWCSA1','WORKMENT COTTON SAREE',3,23,'','','',15750.00,0.00,0.00,'pcs',1,NOW()),(1581,'JLSCWCSA3','WORKMENT COTTON SAREE',3,23,'','','',16750.00,0.00,0.00,'pcs',1,NOW()),(1582,'JLSYWSSA3','WORKMENT SILK SAREE',3,23,'','','',27500.00,0.00,0.00,'pcs',1,NOW()),(1583,'JLSYWSSA2','WORKMENT SILK SAREE',3,23,'','','',22500.00,0.00,0.00,'pcs',1,NOW()),(1584,'JLSYWSSA1','WORKMENT SILK SAREE',3,23,'','','',24500.00,0.00,0.00,'pcs',1,NOW()),(1585,'ASYWSISA1','WORKMENT SILK SAREE',3,23,'','','',27500.00,0.00,0.00,'pcs',1,NOW()),(1586,'JHSYWSSA3','WORKMENT SILK SAREE',3,23,'','','',24750.00,0.00,0.00,'pcs',1,NOW()),(1587,'JHSYWSSA2','WORKMENT SILK SAREE',3,23,'','','',27750.00,0.00,0.00,'pcs',1,NOW()),(1588,'JHSYWSSA1','WORKMENT SILK SAREE',3,23,'','','',28750.00,0.00,0.00,'pcs',1,NOW()),(1589,'ASCWSISA3','WORKMENT SILK SAREE',3,23,'','','',25750.00,0.00,0.00,'pcs',1,NOW()),(1590,'ASCWSISA2','WORKMENT SILK SAREE',3,23,'','','',27500.00,0.00,0.00,'pcs',1,NOW()),(1591,'ASCWSISA1','WORKMENT SILK SAREE',3,23,'','','',24500.00,0.00,0.00,'pcs',1,NOW()),(1592,'ASCWSISA4','WORKMENT SILK SAREE',3,23,'','','',24750.00,0.00,0.00,'pcs',1,NOW()),(1593,'KHWSISA01','WORKMENT SILK SAREE',3,23,'','','',12750.00,0.00,0.00,'pcs',1,NOW()),(1594,'JLWSSA002','WORKMENT SILK SAREE',3,23,'','','',13750.00,0.00,0.00,'pcs',1,NOW()),(1595,'JLWSSA001','WORKMENT SILK SAREE',3,23,'','','',12950.00,0.00,0.00,'pcs',1,NOW()),(1596,'ASCWSISA5','WORKMENT SILK SAREE',3,23,'','','',22500.00,0.00,0.00,'pcs',1,NOW()),(1597,'JHSCWSSA1','WORKMENT SILK SAREE',3,23,'','','',24500.00,0.00,0.00,'pcs',1,NOW()),(1598,'ACOSW0001','COTTON SHAWL',3,24,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1599,'JHCOSW001','COTTON SHAWL',3,24,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1600,'JHSISW001','SILK SHAWL',3,24,'','','',4250.00,0.00,0.00,'pcs',1,NOW());
INSERT INTO `ezy_pos_items` VALUES (1601,'JHSISW002','SILK SHAWL',3,24,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1602,'ASISW0001','SILK SHAWL',3,24,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1603,'1DTNBSSKFS','BATIK SILK SKIRT',3,25,'','','',16750.00,0.00,0.00,'pcs',1,NOW()),(1604,'1DTBSSKFS','BATIK SILK SKIRT',3,25,'','','',13750.00,0.00,0.00,'pcs',1,NOW()),(1605,'1DTNBVSKFS','BATIK VISCOS SKIRT',3,25,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(1606,'2DTNBVSKFS','BATIK VISCOS SKIRT',3,25,'','','',8950.00,0.00,0.00,'pcs',1,NOW()),(1607,'PBBWSK004','BATIK WRAPAROUND SKIRT',3,25,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1608,'PBBWSK003','BATIK WRAPAROUND SKIRT',3,25,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1609,'PBBWSK002','BATIK WRAPAROUND SKIRT',3,25,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1610,'KRJBWSK01','BATIK WRAPAROUND SKIRT',3,25,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1611,'1DNRBLSKF','BATIK WRAPAROUND SKIRT',3,25,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1612,'PBBWSK001','BATIK WRAPAROUND SKIRT',3,25,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1613,'SRKCSK2X1','COTTON SKIRT',3,25,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1614,'SRKCSK1X1','COTTON SKIRT',3,25,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1615,'SRKCSKLR1','COTTON SKIRT',3,25,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1616,'OSCOSKFS1','COTTON SKIRT',3,25,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1617,'OSSWSKFS1','SILK WRAPAROUND SKIRT',3,25,'','','',7750.00,0.00,0.00,'pcs',1,NOW()),(1618,'OSSALESK2','SKIRT',3,25,'','','',1750.00,0.00,0.00,'pcs',1,NOW()),(1619,'OSSALESK3','SKIRT',3,25,'','','',2000.00,0.00,0.00,'pcs',1,NOW()),(1620,'OSSALESK1','SKIRT',3,25,'','','',1500.00,0.00,0.00,'pcs',1,NOW()),(1621,'RUBKBG2C1','2 COLOUR BATIK BAG',4,26,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1622,'RUBKBG2C2','2 COLOUR BATIK BAG',4,26,'','','',2850.00,0.00,0.00,'pcs',1,NOW()),(1623,'RUBKBG3C1','3 COLOUR BATIK BAG',4,26,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1624,'RUEDBG001','ELEPHANT DESIGN BAG',4,26,'','','',2850.00,0.00,0.00,'pcs',1,NOW()),(1625,'RUEDBG002','ELEPHANT DESIGN BAG',4,26,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1626,'RUJBKBG01','JAWA BATIK BAG',4,26,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1627,'RULBG0002','LUNCH BAG',4,26,'','','',1500.00,0.00,0.00,'pcs',1,NOW()),(1628,'RULBG0001','LUNCH BAG',4,26,'','','',1650.00,0.00,0.00,'pcs',1,NOW()),(1629,'PPHBG0002','PEARL WORK HANDBAG',4,26,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1630,'PPHBG0003','PEARL WORK HANDBAG',4,26,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1631,'PPHBG0001','PEARL WORK HANDBAG',4,26,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1632,'PPHBG0004','PEARL WORK HANDBAG',4,26,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1633,'PPHBG0005','PEARL WORK HANDBAG',4,26,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1634,'CLCSTS001','COMFORT SHEET SET',4,27,'','','',11750.00,0.00,0.00,'pcs',1,NOW()),(1635,'JTCOST001','COMFORT SHEET SET',4,27,'','','',15950.00,0.00,0.00,'pcs',1,NOW()),(1636,'COS0003','COMFORT SHEET SET',4,27,'','','',11250.00,0.00,0.00,'pcs',1,NOW()),(1637,'JTCOBS02','COTTON BED SHEET',4,27,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1638,'JTCOBS01','COTTON BED SHEET',4,27,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1639,'GCHLBSKN1','COTTON BEDSHEET 100X110',4,27,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1640,'GCHLBSSN1','COTTON BEDSHEET 60X90',4,27,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1641,'JTCOBSSN1','COTTON BEDSHEET 72X90',4,27,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1642,'GCHLBSQN1','COTTON BEDSHEET 90X100',4,27,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1643,'OGCOBC001','BOLSTER COVER',4,28,'','','',775.00,0.00,0.00,'pcs',1,NOW()),(1644,'OGCOBC003','BOLSTER COVER',4,28,'','','',1450.00,0.00,0.00,'pcs',1,NOW()),(1645,'OGCOBC002','BOLSTER COVER',4,28,'','','',1250.00,0.00,0.00,'pcs',1,NOW()),(1646,'HCC0019','CUSHION COVER',4,29,'','','',1450.00,0.00,0.00,'pcs',1,NOW()),(1647,'JDMBM0003','3 PSC BATH MATE 16*24',4,30,'','','',1450.00,0.00,0.00,'pcs',1,NOW()),(1648,'JDMBM0004','3 PSC BATH MATE 20*32',4,30,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(1649,'JDMBM0005','BATH MATE 18*28',4,30,'','','',1850.00,0.00,0.00,'pcs',1,NOW()),(1650,'JDMDM0001','DIAMOUND 16*24',4,30,'','','',975.00,0.00,0.00,'pcs',1,NOW()),(1651,'JDMDM0002','DIAMOUND 20*32',4,30,'','','',1300.00,0.00,0.00,'pcs',1,NOW()),(1652,'JDMTR0002','DOOR MATE 22*55',4,30,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(1653,'JDMTR0001','DOOR MATE 22*55',4,30,'','','',1850.00,0.00,0.00,'pcs',1,NOW()),(1654,'JDMNW0002','DOOR MATE 24*48',4,30,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1655,'JDMNW0001','DOOR MATE 24*72',4,30,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1656,'JDMPN0001','DOOR MATE 48*72',4,30,'','','',9250.00,0.00,0.00,'pcs',1,NOW()),(1657,'JDMHS0002','HASINA  20*32',4,30,'','','',1950.00,0.00,0.00,'pcs',1,NOW()),(1658,'JDMHS0003','HASINA  24*36',4,30,'','','',2650.00,0.00,0.00,'pcs',1,NOW()),(1659,'JDMHS0004','HASINA  24*48',4,30,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1660,'JDMHS0005','HASINA  48*72',4,30,'','','',9950.00,0.00,0.00,'pcs',1,NOW()),(1661,'JDMHS0001','HASINA 16*24',4,30,'','','',1250.00,0.00,0.00,'pcs',1,NOW()),(1662,'JDMHS0006','HASINA 72*72',4,30,'','','',15750.00,0.00,0.00,'pcs',1,NOW()),(1663,'JDMHS0007','HASINA 80*100',4,30,'','','',22450.00,0.00,0.00,'pcs',1,NOW()),(1664,'JDMLP0001','LOOP CUT 14*24',4,30,'','','',975.00,0.00,0.00,'pcs',1,NOW()),(1665,'JDMPC0001','POP CORN 16*24',4,30,'','','',975.00,0.00,0.00,'pcs',1,NOW()),(1666,'CLFTSM001','FITTED SHEET 60X75X10',4,31,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1667,'CLFTME001','FITTED SHEET 60X78X10',4,31,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1668,'CLFTLR001','FITTED SHEET 72X75X10',4,31,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1669,'CLFT1X001','FITTED SHEET 72X78X10',4,31,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1670,'CLFT2X001','FITTED SHEET 75X78X10',4,31,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(1671,'GSCPC0001','COTTON PILLOW  COVER',4,32,'','','',750.00,0.00,0.00,'pcs',1,NOW()),(1672,'GCHLPC001','COTTON PILLOW  COVER',4,32,'','','',850.00,0.00,0.00,'pcs',1,NOW()),(1673,'JTCOPC001','COTTON PILLOW COVER',4,32,'','','',650.00,0.00,0.00,'pcs',1,NOW()),(1674,'BMCHSE001','SERVIATE',4,33,'','','',275.00,0.00,0.00,'pcs',1,NOW()),(1675,'BMCHTC014','TABLE CLOTH 54*72',4,34,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1676,'BMCHTC012','OVEL TABLE CLOTH 54*108',4,34,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1677,'BMCHTC001','OVEL TABLE CLOTH 54*90',4,34,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1678,'BMCHTC002','ROUND TABLE CLOTH 54*54',4,34,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1679,'BMCHTC013','ROUND TABLE CLOTH 54*72',4,34,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1680,'BMCHTC010','TABLE CLOTH 54*108',4,34,'','','',6450.00,0.00,0.00,'pcs',1,NOW()),(1681,'BMCHTC011','TABLE CLOTH 54*108',4,34,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1682,'BMCHTC007','TABLE CLOTH 54*24',4,34,'','','',2850.00,0.00,0.00,'pcs',1,NOW()),(1683,'BMCHTC015','TABLE CLOTH 54*36',4,34,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1684,'BMCHTC006','TABLE CLOTH 54*36',4,34,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1685,'BMCHTC003','TABLE CLOTH 54*54',4,34,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1686,'BMCHTC005','TABLE CLOTH 54*54',4,34,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1687,'BMCHTC008','TABLE CLOTH 54*54',4,34,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1688,'BMCHTC004','TABLE CLOTH 54*90',4,34,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1689,'BMCHTC009','TABLE CLOTH 54*90',4,34,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1690,'JRTMST001','TABLE MATE SET',4,35,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1691,'JTCRTW001','CAMERON TOWEL 30*60',4,36,'','','',2250.00,0.00,0.00,'pcs',1,NOW()),(1692,'JTCOTW001','COTTON TOWEL',4,36,'','','',1250.00,0.00,0.00,'pcs',1,NOW()),(1693,'JTJJTW001','JJ TOWEL',4,36,'','','',1450.00,0.00,0.00,'pcs',1,NOW()),(1694,'JTRJTW001','TOWEL',4,36,'','','',975.00,0.00,0.00,'pcs',1,NOW()),(1695,'JTWSTW001','WISPER TOWEL 30*60',4,36,'','','',2250.00,0.00,0.00,'pcs',1,NOW()),(1696,'JDMBM0002','BATH MATE 20*32',5,37,'','','',1450.00,0.00,0.00,'pcs',1,NOW()),(1697,'JDMBM0001','BATH MATE 20*38',5,37,'','','',1250.00,0.00,0.00,'pcs',1,NOW()),(1698,'JDMBL0001','DOOR MATE',5,37,'','','',1150.00,0.00,0.00,'pcs',1,NOW()),(1699,'JDMTR0004','DOOR MATE 18*28',5,37,'','','',850.00,0.00,0.00,'pcs',1,NOW()),(1700,'JDMTR0003','DOOR MATE 24*72',5,37,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1701,'OSMCBKUME1','COTTON BOY KURTHA',5,38,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(1702,'OSMCBKU1X1','COTTON BOY KURTHA',5,38,'','','',2550.00,0.00,0.00,'pcs',1,NOW()),(1703,'OSMCBKULR1','COTTON BOY KURTHA',5,38,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(1704,'OSMCBKU2X2','COTTON BOY KURTHA',5,38,'','','',2350.00,0.00,0.00,'pcs',1,NOW()),(1705,'OSMCBKU2X1','COTTON BOY KURTHA',5,38,'','','',2550.00,0.00,0.00,'pcs',1,NOW()),(1706,'ARSBKUME1','SILK BOY KURTHA',5,38,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1707,'3DNRBCSG','BATIK SARONG',5,39,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(1708,'2DNRBCSG1','BATIK SARONG',5,39,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1709,'KRBLSG003','BATIK SUPERLONE SARONG',5,39,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1710,'ARCOBSG01','COTTON BOY SARONG',5,39,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(1711,'JLCOSG001','COTTON SARONG',5,39,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1712,'OSIBBSTSM1','BATIK  BOY SHIRT',5,40,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1713,'OSIBBSTME2','BATIK BOY SHIRT',5,40,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1714,'OSIBBSTLR1','BATIK BOY SHIRT',5,40,'','','',1450.00,0.00,0.00,'pcs',1,NOW()),(1715,'OSIBBST1X2','BATIK BOY SHIRT',5,40,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1716,'OSIBBSTSM2','BATIK BOY SHIRT',5,40,'','','',1450.00,0.00,0.00,'pcs',1,NOW()),(1717,'OSIBBSTME1','BATIK BOY SHIRT',5,40,'','','',1450.00,0.00,0.00,'pcs',1,NOW()),(1718,'OSIBBST2X1','BATIK BOY SHIRT',5,40,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1719,'OSIBBST1X1','BATIK BOY SHIRT',5,40,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1720,'OSIBBSTLR2','BATIK BOY SHIRT',5,40,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1721,'PBIBLST2XL','BATIK SHIRT',5,40,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(1722,'1DNBCST02','BATIK SHIRT',5,40,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1723,'KBIBKSTXL','BOY SHIRT',5,40,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1724,'OSICBSTME4','COTTON BOY SHIRT',5,40,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1725,'OSICBSTME3','COTTON BOY SHIRT',5,40,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1726,'OSICBSTSM1','COTTON BOY SHIRT',5,40,'','','',2250.00,0.00,0.00,'pcs',1,NOW()),(1727,'OSICBSTLR2','COTTON BOY SHIRT',5,40,'','','',2350.00,0.00,0.00,'pcs',1,NOW()),(1728,'OSICBST2X1','COTTON BOY SHIRT',5,40,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1729,'OSICBST2X2','COTTON BOY SHIRT',5,40,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1730,'OSICBSTME2','COTTON BOY SHIRT',5,40,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(1731,'OSICBSTSM2','COTTON BOY SHIRT',5,40,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1732,'OSICBSTSM4','COTTON BOY SHIRT',5,40,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1733,'OSICBST1X2','COTTON BOY SHIRT',5,40,'','','',2850.00,0.00,0.00,'pcs',1,NOW()),(1734,'OSICBST1X1','COTTON BOY SHIRT',5,40,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(1735,'OSICBSTLR3','COTTON BOY SHIRT',5,40,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1736,'OSICBST1X5','COTTON BOY SHIRT',5,40,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(1737,'OSICBSTLR1','COTTON BOY SHIRT',5,40,'','','',2550.00,0.00,0.00,'pcs',1,NOW()),(1738,'OSICBSTME1','COTTON BOY SHIRT',5,40,'','','',2550.00,0.00,0.00,'pcs',1,NOW()),(1739,'OSICBSTSM3','COTTON BOY SHIRT',5,40,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(1740,'OSICBST1X3','COTTON BOY SHIRT',5,40,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1741,'OSICBST1X4','COTTON BOY SHIRT',5,40,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1742,'SFICSTME4','COTTON SHIRT',5,40,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1743,'SFICST2X3','COTTON SHIRT',5,40,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1744,'OSISBST1X2','SILK BOY SHIRT',5,40,'','','',3100.00,0.00,0.00,'pcs',1,NOW()),(1745,'OSISBST2X1','SILK BOY SHIRT',5,40,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1746,'OSISBST1X1','SILK BOY SHIRT',5,40,'','','',2550.00,0.00,0.00,'pcs',1,NOW()),(1747,'OSISBSTLR1','SILK BOY SHIRT',5,40,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(1748,'1DNRBCSG','BATIK COTTON SARONG',5,41,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1749,'SBCOSG001','BATIK COTTON SARONG',5,41,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1750,'SBCOSG002','BATIK COTTON SARONG',5,41,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1751,'KIBLSG001','BATIK SARONG',5,41,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1752,'KRBLSG001','BATIK SARONG',5,41,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1753,'PBBKSG001','BATIK SARONG',5,41,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(1754,'KRBLSG002','BATIK SARONG',5,41,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1755,'RTIBCSG01','BATIK SARONG',5,41,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1756,'RTBKSG001','BATIK SARONG',5,41,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1757,'KBIBKSG01','BOY SARONG',5,41,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1758,'SRCOSG001','COTTON SARONG',5,41,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1759,'SFCOSG002','COTTON SARONG',5,41,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1760,'SRRCOSG01','COTTON SARONG',5,41,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(1761,'JHCOSG002','COTTON SARONG',5,41,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1762,'SFCOSG001','COTTON SARONG',5,41,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1763,'SRCOSG002','COTTON SARONG',5,41,'','','',3550.00,0.00,0.00,'pcs',1,NOW()),(1764,'JHCOJFSG1','COTTON SARONG',5,41,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1765,'JHCOPMSG1','COTTON SARONG',5,41,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1766,'JHCOCWSG1','COTTON SARONG',5,41,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1767,'OSCOSG001','COTTON SARONG',5,41,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1768,'ACOSG0001','COTTON SARONG',5,41,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(1769,'JHCOSG001','COTTON SARONG',5,41,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1770,'ACOSG0002','COTTON SARONG',5,41,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1771,'YSCODT001','DHOTIS',5,41,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1772,'ASISG0003','SILK SARONG',5,41,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1773,'JLSISG001','SILK SARONG',5,41,'','','',3900.00,0.00,0.00,'pcs',1,NOW()),(1774,'ASISG0002','SILK SARONG',5,41,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1775,'HFRSISG01','SILK SARONG',5,41,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1776,'ASISG0001','SILK SARONG',5,41,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1777,'JLWCSG001','WORKMENT COTTON SARONG',5,41,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1778,'SRWCOSG01','WORKMENTCOTTON SARONG',5,41,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1779,'KIBCSTSM1','BATIK COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1780,'KIBCSTME1','BATIK COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1781,'1DNIBCSTLR','BATIK COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1782,'RTIBCSTME1','BATIK COTTON SHIRT',5,42,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1783,'RTIBCSTME2','BATIK COTTON SHIRT',5,42,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1784,'RTIBCSTLR2','BATIK COTTON SHIRT',5,42,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1785,'SMIBCST2X1','BATIK COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1786,'KIBCST1X1','BATIK COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1787,'RTIBCST1X1','BATIK COTTON SHIRT',5,42,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1788,'RTIBCSTLR1','BATIK COTTON SHIRT',5,42,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1789,'1DNIBCST2X','BATIK COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1790,'RTIBBCSTME1','BATIK COTTON SHIRT',5,42,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1791,'RTIBCST1X2','BATIK COTTON SHIRT',5,42,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1792,'RTIBCST3X1','BATIK COTTON SHIRT',5,42,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1793,'RTIBCST2X1','BATIK COTTON SHIRT',5,42,'','','',6950.00,0.00,0.00,'pcs',1,NOW()),(1794,'1DNIBCST1X','BATIK COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1795,'1DNIBCSTME','BATIK COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1796,'KIBCSTLR1','BATIK COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1797,'RTIBCST3X2','BATIK COTTON SHIRT',5,42,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1798,'KIBCST2X1','BATIK COTTON SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1799,'RTIBCST1X3','BATIK COTTON SHIRT',5,42,'','','',7250.00,0.00,0.00,'pcs',1,NOW()),(1800,'RTIBCST2X2','BATIK COTTON SHIRT',5,42,'','','',7250.00,0.00,0.00,'pcs',1,NOW());
INSERT INTO `ezy_pos_items` VALUES (1801,'2DNRBLSTLR','BATIK L/SLEEVE SHIRT',5,42,'','','',6750.00,0.00,0.00,'pcs',1,NOW()),(1802,'OSIBKSTSM1','BATIK L/SLEEVE SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1803,'1DIBLSTLR','BATIK SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1804,'1DIBLST1X','BATIK SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1805,'1DIBLSTME','BATIK SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1806,'1DNIST1X1','BATIK SUPERLONE L/SLEEVE SHIRT',5,42,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1807,'1DNISTME1','BATIK SUPERLONE L/SLEEVE SHIRT',5,42,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1808,'1DNISTLR1','BATIK SUPERLONE L/SLEEVE SHIRT',5,42,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(1809,'KIBLSTLR1','BATIK SUPERLONE SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1810,'PBIBLSTME1','BATIK SUPERLONE SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1811,'KIBLSTME1','BATIK SUPERLONE SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1812,'PBIBLSTSM1','BATIK SUPERLONE SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1813,'PBIBLSTLR1','BATIK SUPERLONE SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1814,'KIBLST2X1','BATIK SUPERLONE SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1815,'KIBLST3X1','BATIK SUPERLONE SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1816,'PBIBLST2X1','BATIK SUPERLONE SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1817,'PBIBLST1X1','BATIK SUPERLONE SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1818,'KIBLSTSM1','BATIK SUPERLONE SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1819,'1DIBLST2X','BATIK SUPERLONE SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1820,'1DIBLSTSM','BATIK SUPERLONE SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1821,'KIBLST1X1','BATIK SUPERLONE SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1822,'1DIBLST3X','BATIK SUPERLONE SHIRT',5,42,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(1823,'PBIBISTME1','BATIK SUPERVOIL SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1824,'PBIBIST2X1','BATIK SUPERVOIL SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1825,'PBIBIST1X1','BATIK SUPERVOIL SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1826,'PBIBISTLR1','BATIK SUPERVOIL SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1827,'PBIBIST3X1','BATIK SUPERVOIL SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1828,'PBIBISTSM1','BATIK SUPERVOIL SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1829,'KBIBKSTLR','BOY SHIRT',5,42,'','','',2950.00,0.00,0.00,'pcs',1,NOW()),(1830,'OSDCSTME1','COTTON  SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1831,'OSRCSTSM4','COTTON  SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1832,'OSMCSTSM8','COTTON  SHIRT',5,42,'','','',2750.00,0.00,0.00,'pcs',1,NOW()),(1833,'OSRCSTSM3','COTTON  SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1834,'OSMCLSTLR3','COTTON L / SLEEVE SHIRT',5,42,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1835,'OSMCLSTLR2','COTTON L / SLEEVE SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1836,'OSMCLST2X3','COTTON L / SLEEVE SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1837,'OSMCLSTME2','COTTON L / SLEEVE SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1838,'OSMCLST3X2','COTTON L / SLEEVE SHIRT',5,42,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(1839,'OSMCLST2X4','COTTON L / SLEEVE SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1840,'OSMCLSTSM3','COTTON L/ SLEEVE SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1841,'OSMCLSTLR1','COTTON L/SLEEVE SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1842,'OSMCLSTME1','COTTON L/SLEEVE SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1843,'OSMCLST1X1','COTTON L/SLEEVE SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1844,'SFICLST2X1','COTTON L/SLEEVE SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1845,'AICLST2X1','COTTON L/SLEEVE SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1846,'SRICLST3X1','COTTON L/SLEEVE SHIRT',5,42,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(1847,'SRICLST2X1','COTTON L/SLEEVE SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1848,'OSMCLST2X2','COTTON L/SLEEVE SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1849,'AMCLSTLR1','COTTON L/SLEEVE SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1850,'SRICLST1X1','COTTON L/SLEEVE SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1851,'SRICLSTME1','COTTON L/SLEEVE SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1852,'AICLST1X1','COTTON L/SLEEVE SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1853,'AICLSTME1','COTTON L/SLEEVE SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1854,'SFICLST1X1','COTTON L/SLEEVE SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1855,'SFICLSTLR1','COTTON L/SLEEVE SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1856,'SFICLSTME1','COTTON L/SLEEVE SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1857,'SRICLSTLR1','COTTON L/SLEEVE SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1858,'OSMCLST1X2','COTTON L/SLEEVE SHIRT',5,42,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1859,'OSMCLST2X1','COTTON L/SLEEVE SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1860,'OSMCLST3X1','COTTON L/SLEEVE SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1861,'OSMCLSTSM1','COTTON L/SLEEVE SHIRT',5,42,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1862,'SFICLST3X1','COTTON L/SLEEVE SHIRT',5,42,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(1863,'OSMCLSTSM2','COTTON L/SLEEVE SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1864,'OSICST2X4','COTTON SHIRT',5,42,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1865,'SFICSTME2','COTTON SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1866,'SFICSTSM1','COTTON SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1867,'SFICST1X1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1868,'SRMCST1X1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1869,'OSMCST3X8','COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1870,'OSMCST4X2','COTTON SHIRT',5,42,'','','',5100.00,0.00,0.00,'pcs',1,NOW()),(1871,'OSICST2X3','COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1872,'OSICSTSM1','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1873,'OSMCST4X1','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1874,'SFMCSTME1','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1875,'OSMCST2X7','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1876,'OSICST1X4','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1877,'OSMCST4X4','COTTON SHIRT',5,42,'','','',3550.00,0.00,0.00,'pcs',1,NOW()),(1878,'OSMCST2X8','COTTON SHIRT',5,42,'','','',2450.00,0.00,0.00,'pcs',1,NOW()),(1879,'SFICSTLR1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1880,'SFICSTME1','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1881,'SAICSTLR1','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1882,'SAICSTSM1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1883,'SFMCST1X1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1884,'OSMCSTSM9','COTTON SHIRT',5,42,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1885,'OSMCST4X5','COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1886,'OSICST3X1','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1887,'SFICSTLR3','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1888,'SAICST2X1','COTTON SHIRT',5,42,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1889,'OSMCST4X7','COTTON SHIRT',5,42,'','','',4350.00,0.00,0.00,'pcs',1,NOW()),(1890,'SAICST3X1','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1891,'SAICST1X1','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1892,'OSMCST3X2','COTTON SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1893,'OSMCST2X9','COTTON SHIRT',5,42,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1894,'OSICSTSM2','COTTON SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1895,'OSMCST4X9','COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1896,'OSICST2X2','COTTON SHIRT',5,42,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1897,'OSMCSTME7','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1898,'OSICSTSM3','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1899,'AMCOSTLR1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1900,'SFICSTME3','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1901,'AVCOSTLR1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1902,'SRRCSTLR1','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1903,'SRRCSTSM1','COTTON SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1904,'OSRCSTSM1','COTTON SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1905,'OSMCSTME8','COTTON SHIRT',5,42,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1906,'OSMCST5X4','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1907,'SRRCSTLR2','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1908,'AJMCST1X1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1909,'OSMCST3X5','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1910,'OSDCSTLR2','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1911,'OSICSTLR2','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1912,'OSICSTME4','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1913,'SRICSTME1','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1914,'OSMCSTSM7','COTTON SHIRT',5,42,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(1915,'OSMCST3X4','COTTON SHIRT',5,42,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1916,'SFMCSTLR1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1917,'OSICSTLR3','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1918,'SFICSTLR2','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1919,'OSICST2X5','COTTON SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1920,'SFMCST2X1','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1921,'SFICST4X1','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1922,'SFICST2X1','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1923,'OSMCSTSM5','COTTON SHIRT',5,42,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(1924,'SRICST1X1','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1925,'OSMCSTLR4','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1926,'OSMCSTLR2','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1927,'OSICST1X3','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1928,'SRMCSTLR1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1929,'SRMCSTSM1','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1930,'OSMCST3X6','COTTON SHIRT',5,42,'','','',5100.00,0.00,0.00,'pcs',1,NOW()),(1931,'OSICST2X1','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1932,'SFMCST4X1','COTTON SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1933,'SRMCST2X1','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1934,'SRMCST4X1','COTTON SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1935,'SFMCST5X1','COTTON SHIRT',5,42,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1936,'AJMCST2X1','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1937,'AJMCSTLR1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1938,'AJMCST3X1','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1939,'SRICSTSM1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1940,'AJMCST5X1','COTTON SHIRT',5,42,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1941,'AJMCST4X1','COTTON SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(1942,'AJMCSTSM1','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1943,'OSMCST4X3','COTTON SHIRT',5,42,'','','',4350.00,0.00,0.00,'pcs',1,NOW()),(1944,'OSMCST3X3','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1945,'SFICST2X2','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1946,'SAICSTME1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1947,'OSMCST2X1','COTTON SHIRT',5,42,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(1948,'OSMCST3X7','COTTON SHIRT',5,42,'','','',3450.00,0.00,0.00,'pcs',1,NOW()),(1949,'OSMCSTSM3','COTTON SHIRT',5,42,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1950,'OSDCST1X1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1951,'OSMCST5X1','COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1952,'OSMCST5X2','COTTON SHIRT',5,42,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1953,'OSMCST1X6','COTTON SHIRT',5,42,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1954,'SRRCSTME1','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1955,'OSMCST5X5','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1956,'OSMCST5X3','COTTON SHIRT',5,42,'','','',4350.00,0.00,0.00,'pcs',1,NOW()),(1957,'OSMCST4X8','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1958,'OSMCST4X6','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1959,'OSMCSTME6','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1960,'OSICST3X2','COTTON SHIRT',5,42,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1961,'OSMCST2X6','COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(1962,'OSMCSTSM6','COTTON SHIRT',5,42,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(1963,'OSMCSTSM1','COTTON SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1964,'OSMCSTME1','COTTON SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1965,'OSMCST2X3','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1966,'OSMCSTME2','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1967,'OSMCSTME3','COTTON SHIRT',5,42,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1968,'OSMCSTSM2','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1969,'OSDCSTSM1','COTTON SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1970,'OSMCSTLR5','COTTON SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1971,'AJMCSTME1','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1972,'SRMCST5X1','COTTON SHIRT',5,42,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(1973,'SRMCST3X1','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1974,'SRMCSTME1','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1975,'SFMCST3X1','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1976,'OSMCSTSM4','COTTON SHIRT',5,42,'','','',3250.00,0.00,0.00,'pcs',1,NOW()),(1977,'SFMCSTSM1','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1978,'OSICSTME3','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1979,'OSICSTME2','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1980,'OSICSTME1','COTTON SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1981,'OSRCSTLR1','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(1982,'OSMCSTLR1','COTTON SHIRT',5,42,'','','',3750.00,0.00,0.00,'pcs',1,NOW()),(1983,'OSMCSTLR3','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1984,'OSMCSTME4','COTTON SHIRT',5,42,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(1985,'SRRCST1X1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1986,'OSMCSTME5','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1987,'OSMCST1X1','COTTON SHIRT',5,42,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1988,'OSMCST1X2','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1989,'OSMCST1X3','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1990,'OSICST1X2','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1991,'OSMCST1X4','COTTON SHIRT',5,42,'','','',3850.00,0.00,0.00,'pcs',1,NOW()),(1992,'OSICSTLR1','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1993,'OSMCST1X5','COTTON SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(1994,'OSICST1X1','COTTON SHIRT',5,42,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1995,'OSMCST2X2','COTTON SHIRT',5,42,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(1996,'OSMCST2X5','COTTON SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(1997,'OSDCSTLR1','COTTON SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(1998,'OSMCST2X4','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(1999,'SFICST5X1','COTTON SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(2000,'OSMCST3X1','COTTON SHIRT',5,42,'','','',4650.00,0.00,0.00,'pcs',1,NOW());
INSERT INTO `ezy_pos_items` VALUES (2001,'SRICSTLR1','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(2002,'OSRCSTSM2','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(2003,'SFICST3X1','COTTON SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(2004,'SRICSTME2','COTTON SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(2005,'OSMSIST5X1','SILK  SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(2006,'OSMSIST4X1','SILK  SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(2007,'OSMSLSTME2','SILK L / SLEEVE SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(2008,'OSMSLSTLR1','SILK L / SLEEVE SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(2009,'OSMSLST2X3','SILK L / SLEEVE SHIRT',5,42,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(2010,'OSMSLST1X1','SILK L / SLEEVE SHIRT',5,42,'','','',5350.00,0.00,0.00,'pcs',1,NOW()),(2011,'OSMSLSTME1','SILK L / SLEEVE SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(2012,'OSMSLST2X4','SILK L / SLEEVE SHIRT',5,42,'','','',5850.00,0.00,0.00,'pcs',1,NOW()),(2013,'OSMSLST3X1','SILK L / SLEEVE SHIRT',5,42,'','','',5950.00,0.00,0.00,'pcs',1,NOW()),(2014,'OSMSLST2X2','SILK L/SLEEVE SHIRT',5,42,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(2015,'OSMSLST2X1','SILK L/SLEEVE SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(2016,'OSMSLSTSM1','SILK L/SLEEVE SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(2017,'OSMSIST2X7','SILK SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(2018,'OSMSIST2X8','SILK SHIRT',5,42,'','','',3650.00,0.00,0.00,'pcs',1,NOW()),(2019,'OSISIST2X1','SILK SHIRT',5,42,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(2020,'OSMSIST2X4','SILK SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(2021,'OSMSIST3X2','SILK SHIRT',5,42,'','','',5650.00,0.00,0.00,'pcs',1,NOW()),(2022,'OSMSISTSM2','SILK SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(2023,'OSMSISTSM3','SILK SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(2024,'OSMSIST4X3','SILK SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(2025,'OSMSISTME5','SILK SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(2026,'OSMSIST4X4','SILK SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(2027,'HISIST1X1','SILK SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(2028,'AVSISTLR1','SILK SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(2029,'ARSISTSM1','SILK SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(2030,'OSMSISTSM4','SILK SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(2031,'OSMSISTME3','SILK SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(2032,'OSMSIST2X1','SILK SHIRT',5,42,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(2033,'ARSISTME1','SILK SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(2034,'OSMSISTLR1','SILK SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(2035,'ARSISTLR1','SILK SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(2036,'OSMSISTME2','SILK SHIRT',5,42,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(2037,'OSMSISTLR2','SILK SHIRT',5,42,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(2038,'OSMSISTLR3','SILK SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(2039,'AJMSISTSM1','SILK SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(2040,'AJMSISTLR1','SILK SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(2041,'AJMSIST2X1','SILK SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(2042,'OSMSISTME1','SILK SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(2043,'AJMSIST3X1','SILK SHIRT',5,42,'','','',5750.00,0.00,0.00,'pcs',1,NOW()),(2044,'OSMSIST1X6','SILK SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(2045,'OSMSIST4X2','SILK SHIRT',5,42,'','','',4550.00,0.00,0.00,'pcs',1,NOW()),(2046,'OSMSIST2X5','SILK SHIRT',5,42,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(2047,'OSMSISTLR4','SILK SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(2048,'OSMSIST2X3','SILK SHIRT',5,42,'','','',4350.00,0.00,0.00,'pcs',1,NOW()),(2049,'OSISIST1X1','SILK SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(2050,'OSRSISTSM1','SILK SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(2051,'OSMSIST3X1','SILK SHIRT',5,42,'','','',5100.00,0.00,0.00,'pcs',1,NOW()),(2052,'OSMSISTSM1','SILK SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(2053,'OSMSIST1X4','SILK SHIRT',5,42,'','','',4850.00,0.00,0.00,'pcs',1,NOW()),(2054,'OSMSIST1X2','SILK SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(2055,'OSMSIST1X1','SILK SHIRT',5,42,'','','',4750.00,0.00,0.00,'pcs',1,NOW()),(2056,'OSRSISTLR1','SILK SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(2057,'OSISISTME1','SILK SHIRT',5,42,'','','',4100.00,0.00,0.00,'pcs',1,NOW()),(2058,'AJMSIST1X1','SILK SHIRT',5,42,'','','',5250.00,0.00,0.00,'pcs',1,NOW()),(2059,'AJMSISTME1','SILK SHIRT',5,42,'','','',4950.00,0.00,0.00,'pcs',1,NOW()),(2060,'OSMSIST1X5','SILK SHIRT',5,42,'','','',4250.00,0.00,0.00,'pcs',1,NOW()),(2061,'OSISISTME2','SILK SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(2062,'OSISISTLR1','SILK SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW()),(2063,'OSMSIST1X3','SILK SHIRT',5,42,'','','',4650.00,0.00,0.00,'pcs',1,NOW()),(2064,'OSMSIST2X6','SILK SHIRT',5,42,'','','',4450.00,0.00,0.00,'pcs',1,NOW()),(2065,'OSMSISTME4','SILK SHIRT',5,42,'','','',3950.00,0.00,0.00,'pcs',1,NOW()),(2066,'OSMSIST2X2','SILK SHIRT',5,42,'','','',5450.00,0.00,0.00,'pcs',1,NOW());
/*!40000 ALTER TABLE `ezy_pos_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_payment_methods`
--

DROP TABLE IF EXISTS `ezy_pos_payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_payment_methods` (
  `pm_id` int NOT NULL AUTO_INCREMENT,
  `pm_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pm_commission_pct` double(5,2) NOT NULL DEFAULT '0.00',
  `pm_commission_fixed` double(10,2) NOT NULL DEFAULT '0.00',
  `pm_status` tinyint NOT NULL DEFAULT '1',
  `pm_createdat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_payment_methods`
--

LOCK TABLES `ezy_pos_payment_methods` WRITE;
/*!40000 ALTER TABLE `ezy_pos_payment_methods` DISABLE KEYS */;
INSERT INTO `ezy_pos_payment_methods` VALUES (1,'Visa/Master',2.50,10.00,1,'2026-03-06 20:20:17'),(2,'FriMi',1.50,5.00,1,'2026-03-06 20:20:17'),(3,'PayHere',3.00,0.00,1,'2026-03-06 20:20:17');
/*!40000 ALTER TABLE `ezy_pos_payment_methods` ENABLE KEYS */;
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
-- Table structure for table `ezy_pos_prodsale`
--

DROP TABLE IF EXISTS `ezy_pos_prodsale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_prodsale` (
  `prodsale_id` int NOT NULL AUTO_INCREMENT,
  `prodsale_code` varchar(50) NOT NULL,
  `prodsale_cus_id` int NOT NULL DEFAULT '0',
  `prodsale_store_id` int NOT NULL DEFAULT '0',
  `prodsale_date` date NOT NULL,
  `prodsale_delivery_date` date DEFAULT NULL,
  `prodsale_material_cost` double(20,2) DEFAULT '0.00',
  `prodsale_tailoring_charge` double(20,2) DEFAULT '0.00',
  `prodsale_total` double(20,2) DEFAULT '0.00',
  `prodsale_paid` double(20,2) DEFAULT '0.00',
  `prodsale_balance` double(20,2) DEFAULT '0.00',
  `prodsale_notes` text,
  `prodsale_status` varchar(30) DEFAULT 'Pending',
  `prodsale_createdby` int DEFAULT '0',
  `prodsale_createdat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `prodsale_updatedat` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`prodsale_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_prodsale`
--

LOCK TABLES `ezy_pos_prodsale` WRITE;
/*!40000 ALTER TABLE `ezy_pos_prodsale` DISABLE KEYS */;
-- Test data cleared from `ezy_pos_prodsale`
/*!40000 ALTER TABLE `ezy_pos_prodsale` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_prodsale_items`
--

DROP TABLE IF EXISTS `ezy_pos_prodsale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_prodsale_items` (
  `prodsaleitem_id` int NOT NULL AUTO_INCREMENT,
  `prodsaleitem_prodsale_id` int NOT NULL,
  `prodsaleitem_item_id` int NOT NULL,
  `prodsaleitem_qty` double(20,2) DEFAULT '1.00',
  `prodsaleitem_unit_price` double(20,2) DEFAULT '0.00',
  `prodsaleitem_total` double(20,2) DEFAULT '0.00',
  `prodsaleitem_type` varchar(20) DEFAULT 'material',
  `prodsaleitem_status` int DEFAULT '1',
  PRIMARY KEY (`prodsaleitem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_prodsale_items`
--

LOCK TABLES `ezy_pos_prodsale_items` WRITE;
/*!40000 ALTER TABLE `ezy_pos_prodsale_items` DISABLE KEYS */;
-- Test data cleared from `ezy_pos_prodsale_items`
/*!40000 ALTER TABLE `ezy_pos_prodsale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_prodsale_services`
--

DROP TABLE IF EXISTS `ezy_pos_prodsale_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_prodsale_services` (
  `prodsvc_id` int NOT NULL AUTO_INCREMENT,
  `prodsvc_prodsale_id` int NOT NULL,
  `prodsvc_description` varchar(250) NOT NULL,
  `prodsvc_charge` double(20,2) DEFAULT '0.00',
  `prodsvc_status` int DEFAULT '1',
  PRIMARY KEY (`prodsvc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_prodsale_services`
--

LOCK TABLES `ezy_pos_prodsale_services` WRITE;
/*!40000 ALTER TABLE `ezy_pos_prodsale_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_prodsale_services` ENABLE KEYS */;
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
  `prod_grn_id` int DEFAULT NULL COMMENT 'FK to ezy_pos_grns.grn_id',
  `prod_store_id` int NOT NULL DEFAULT '0' COMMENT 'Source store for materials',
  `prod_output_store_id` int NOT NULL DEFAULT '0' COMMENT 'Destination store for GRN',
  PRIMARY KEY (`prod_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_production`
--

LOCK TABLES `ezy_pos_production` WRITE;
/*!40000 ALTER TABLE `ezy_pos_production` DISABLE KEYS */;
-- Test data cleared from `ezy_pos_production`
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_production_materials`
--

LOCK TABLES `ezy_pos_production_materials` WRITE;
/*!40000 ALTER TABLE `ezy_pos_production_materials` DISABLE KEYS */;
-- Test data cleared from `ezy_pos_production_materials`
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
  `sale_payment_method` int DEFAULT NULL,
  `sale_commission` double(10,2) DEFAULT '0.00',
  PRIMARY KEY (`sale_id`),
  KEY `idx_sale_cus_id` (`sale_cus_id`),
  KEY `idx_sale_date` (`sale_date`),
  KEY `idx_sale_status` (`sale_status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_sale`
--

LOCK TABLES `ezy_pos_sale` WRITE;
/*!40000 ALTER TABLE `ezy_pos_sale` DISABLE KEYS */;
-- Test data cleared from `ezy_pos_sale`
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_sale_item`
--

LOCK TABLES `ezy_pos_sale_item` WRITE;
/*!40000 ALTER TABLE `ezy_pos_sale_item` DISABLE KEYS */;
-- Test data cleared from `ezy_pos_sale_item`
/*!40000 ALTER TABLE `ezy_pos_sale_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ezy_pos_sale_payments`
--

DROP TABLE IF EXISTS `ezy_pos_sale_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ezy_pos_sale_payments` (
  `sp_id` int NOT NULL AUTO_INCREMENT,
  `sp_sale_id` int NOT NULL,
  `sp_pm_id` int NOT NULL,
  `sp_amount` double(50,2) NOT NULL DEFAULT '0.00',
  `sp_commission` double(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`sp_id`),
  KEY `idx_sale` (`sp_sale_id`),
  KEY `idx_pm` (`sp_pm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_sale_payments`
--

LOCK TABLES `ezy_pos_sale_payments` WRITE;
/*!40000 ALTER TABLE `ezy_pos_sale_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezy_pos_sale_payments` ENABLE KEYS */;
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
  `stock_store_id` int NOT NULL DEFAULT '0',
  `stock_qty` double(30,2) NOT NULL,
  `stock_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`stock_id`),
  KEY `idx_stock_item_store` (`stock_itm_id`,`stock_store_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_stock`
--

LOCK TABLES `ezy_pos_stock` WRITE;
/*!40000 ALTER TABLE `ezy_pos_stock` DISABLE KEYS */;
-- Cleared test data
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
  `stocklog_store_id` int NOT NULL DEFAULT '0',
  `stocklog_qty` double(250,2) NOT NULL,
  `stocklog_grnID` int NOT NULL,
  `stocklog_saleID` int NOT NULL,
  `stocklog_return_sup_retrnID` int NOT NULL,
  `stocklog_return_supID` int NOT NULL DEFAULT '0',
  `stocklog_return_cus_retrnID` int NOT NULL,
  `stocklog_return_cusID` int NOT NULL DEFAULT '0',
  `stocklog_status` tinyint(1) NOT NULL,
  `stocklog_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`stocklog_id`),
  KEY `idx_stocklog_store` (`stocklog_store_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_stock_log`
--

LOCK TABLES `ezy_pos_stock_log` WRITE;
/*!40000 ALTER TABLE `ezy_pos_stock_log` DISABLE KEYS */;
-- Cleared test data
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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ezy_pos_stores`
--

LOCK TABLES `ezy_pos_stores` WRITE;
/*!40000 ALTER TABLE `ezy_pos_stores` DISABLE KEYS */;
INSERT INTO `ezy_pos_stores` VALUES (6,'Main Store','Handloom Gallery','','0000000000','0000000000','','','',1,'2026-03-06 12:57:56'),(7,'Branch Store','Test Branch','','0000000000','0000000000','','','',1,'2026-03-10 01:44:25');
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
INSERT INTO `ezy_pos_subcategories` VALUES (1,1,'BATIK COTTON',1,NOW()),(2,1,'BATIK SILK',1,NOW()),(3,1,'BATIK SILKCOTTON MIX',1,NOW()),(4,1,'BATIK SUPERLONE',1,NOW()),(5,1,'BATIK SUPERVOIL',1,NOW()),(6,1,'BATIK VISCOS',1,NOW()),(7,1,'COTTON HANDLOOM',1,NOW()),(8,1,'SLIK HANDLOOM',1,NOW()),(9,2,'EARINGS',1,NOW()),(10,2,'JEWELLERY',1,NOW()),(11,3,'BLOUSE',1,NOW()),(12,3,'CAFTAN',1,NOW()),(13,3,'CROP TOP',1,NOW()),(14,3,'DHOTIS',1,NOW()),(15,3,'DRESS',1,NOW()),(16,3,'GIRL FROCK',1,NOW()),(17,3,'GIRL KURTHA',1,NOW()),(18,3,'LADIES FROCK',1,NOW()),(19,3,'LADIES KURTHA',1,NOW()),(20,3,'LUNGIE',1,NOW()),(21,3,'PANT',1,NOW()),(22,3,'PONCHO',1,NOW()),(23,3,'SAREE',1,NOW()),(24,3,'SHAWL',1,NOW()),(25,3,'SKIRT',1,NOW()),(26,4,'BAG',1,NOW()),(27,4,'BED SHEET',1,NOW()),(28,4,'BOLISTER COVER',1,NOW()),(29,4,'CUSHION COVER',1,NOW()),(30,4,'DOOR MATE',1,NOW()),(31,4,'FITTED SHEET',1,NOW()),(32,4,'PILLOW COVER',1,NOW()),(33,4,'SERVIATE',1,NOW()),(34,4,'TABLE CLOTH',1,NOW()),(35,4,'TABLE MATE',1,NOW()),(36,4,'TOWEL',1,NOW()),(37,5,'UNCATEGORIZED',1,NOW()),(38,5,'BOY KURTHA',1,NOW()),(39,5,'BOY SARONG',1,NOW()),(40,5,'BOY SHIRT',1,NOW()),(41,5,'GENT SARONG',1,NOW()),(42,5,'GENT SHIRT',1,NOW());
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
INSERT INTO `ezy_pos_users` VALUES (138,'admin','System Admin','0192023a7bbd73250516f069df18b500',1,1);
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

-- Dump completed on 2026-03-11  9:40:20
