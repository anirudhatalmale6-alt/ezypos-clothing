DROP TABLE IF EXISTS ezy_pos_bank;

CREATE TABLE `ezy_pos_bank` (
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(250) NOT NULL,
  `bank_branch` varchar(250) NOT NULL,
  `bank_accname` varchar(250) NOT NULL,
  `bank_accnumber` varchar(250) NOT NULL,
  `bank_createdby` int(11) NOT NULL,
  `bank_status` tinyint(2) NOT NULL,
  PRIMARY KEY (`bank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_bank VALUES("1","BOC","Fort","Main Account","1255430001","89","0"),
("2","BOC","Ja ela","Sub Account","12554321554","89","1"),
("3","BOC","Fort2","Main Account2","125543215542","89","0"),
("4","BOC","Fort2","Main Account2","125543215542","89","0"),
("5","BOC","Fort","Main Account","125543215542","89","1");



DROP TABLE IF EXISTS ezy_pos_categories;

CREATE TABLE `ezy_pos_categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(250) NOT NULL,
  `cat_status` int(1) NOT NULL DEFAULT 1,
  `cat_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_categories VALUES("1","Phone","1","2019-12-03 16:15:50"),
("2","Computer","1","2019-12-03 16:16:13"),
("3","Smart Watch","0","2019-12-10 11:44:09"),
("4","Smart Watch","1","2019-12-13 10:06:59");



DROP TABLE IF EXISTS ezy_pos_company;

CREATE TABLE `ezy_pos_company` (
  `com_id` int(11) NOT NULL,
  `com_name` varchar(250) NOT NULL,
  `com_address` varchar(250) DEFAULT NULL,
  `com_telephone` varchar(17) DEFAULT NULL,
  `com_mobile` varchar(17) DEFAULT NULL,
  `com_email` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS ezy_pos_config2;

CREATE TABLE `ezy_pos_config2` (
  `config_id` int(3) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(250) NOT NULL,
  `config_value` varchar(250) NOT NULL,
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_config2 VALUES("7","name","Company Name"),
("8","addressLine1","12/34,Bankshall Street"),
("9","addressLine2","Colombo 11"),
("10","tel","011234567"),
("11","mob","0771234567"),
("12","footer","Thank You , Come Again");



DROP TABLE IF EXISTS ezy_pos_cur_grn_vs_sale;

CREATE TABLE `ezy_pos_cur_grn_vs_sale` (
  `grnvssale_id` int(11) NOT NULL AUTO_INCREMENT,
  `grnvssale_curQtyWithGrnID` int(11) NOT NULL,
  `grnvssale_itmID` int(11) NOT NULL,
  `grnvssale_saleID` int(11) NOT NULL,
  `grnvssale_status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`grnvssale_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_cur_grn_vs_sale VALUES("1","1","3","1","1"),
("2","2","3","1","1"),
("3","1","3","2","1"),
("4","2","3","2","1"),
("5","4","3","2","1"),
("6","3","2","2","1"),
("7","3","2","3","1"),
("8","6","1","3","1"),
("9","3","2","4","1"),
("10","6","1","5","1"),
("11","6","1","6","1"),
("12","7","2","7","1"),
("13","14","8","8","1"),
("14","6","1","9","1"),
("15","6","1","10","1"),
("16","6","1","11","1"),
("17","7","2","12","1"),
("18","4","3","13","1"),
("19","6","1","14","1");



DROP TABLE IF EXISTS ezy_pos_currentqtywithgrn;

CREATE TABLE `ezy_pos_currentqtywithgrn` (
  `cur_id` int(11) NOT NULL AUTO_INCREMENT,
  `cur_grnID` int(11) NOT NULL,
  `cur_itmID` int(11) NOT NULL,
  `cur_grnQty` double(250,2) NOT NULL,
  `cur_grnPrice` double(30,2) NOT NULL,
  `cur_grnTotal` double(30,2) NOT NULL,
  `cur_currentQTY` double(250,2) NOT NULL,
  `cur_status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`cur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_currentqtywithgrn VALUES("1","1","3","5.00","12500.00","62500.00","0.00","1"),
("2","2","3","5.00","13500.00","67500.00","0.00","1"),
("3","2","2","8.00","17500.00","140000.00","0.00","1"),
("4","3","3","2.00","25000.00","50000.00","0.00","1"),
("5","4","3","3.00","17500.00","52500.00","3.00","1"),
("6","5","1","10.00","100000.00","750000.00","0.00","1"),
("7","5","2","15.00","25000.00","363750.00","13.00","1"),
("8","6","1","10.00","12000.00","120000.00","10.00","1"),
("9","6","2","5.00","15000.00","75000.00","5.00","1"),
("10","7","2","10.00","12000.00","114000.00","10.00","1"),
("11","7","3","10.00","10500.00","105000.00","10.00","1"),
("12","8","7","10.00","12000.00","120000.00","10.00","1"),
("13","9","9","10.00","24000.00","240000.00","10.00","1"),
("14","10","8","10.00","82000.00","820000.00","9.00","1"),
("15","11","6","100.00","550.00","55000.00","100.00","1"),
("16","12","8","10.00","45000.00","450000.00","10.00","1");



DROP TABLE IF EXISTS ezy_pos_cus_balnce;

CREATE TABLE `ezy_pos_cus_balnce` (
  `bal_id` int(11) NOT NULL AUTO_INCREMENT,
  `bal_cusid` int(11) NOT NULL,
  `bal_amount` double(50,2) NOT NULL,
  PRIMARY KEY (`bal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_cus_balnce VALUES("1","4","-5000.00");



DROP TABLE IF EXISTS ezy_pos_cus_cheque;

CREATE TABLE `ezy_pos_cus_cheque` (
  `cus_cheque_id` int(11) NOT NULL AUTO_INCREMENT,
  `cus_cheque_saleid` int(11) NOT NULL,
  `cus_cheque_amount` double(50,2) NOT NULL,
  `cus_cheque_bank` varchar(250) NOT NULL,
  `cus_cheque_num` varchar(250) NOT NULL,
  `cus_cheque_date` date NOT NULL,
  `cus_cheque_givendate` date NOT NULL,
  `cus_cheque_status` tinyint(1) NOT NULL,
  `cus_cheque_transferred` tinyint(4) NOT NULL,
  PRIMARY KEY (`cus_cheque_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_cus_cheque VALUES("1","7","45000.00","BOC","1234456we","2020-08-31","2020-08-31","1","0"),
("2","8","74500.00","NTB","ntb123","2020-09-09","2020-09-09","1","0"),
("3","0","1350.00","HSBC","hsbc123","2020-09-09","2020-09-09","1","1"),
("4","13","20000.00","Sampath Bank","sampath 1234","2020-09-10","2020-09-10","1","0"),
("5","14","17500.00","Sampath Bank","sampath1236","2020-09-10","2020-09-10","1","0");



DROP TABLE IF EXISTS ezy_pos_cus_chequelog;

CREATE TABLE `ezy_pos_cus_chequelog` (
  `cheqlog_id` int(11) NOT NULL AUTO_INCREMENT,
  `cheqlog_chequeid` int(11) NOT NULL,
  `cheqlog_saleid` int(11) NOT NULL,
  `cheqlog_amount` double(50,2) NOT NULL,
  PRIMARY KEY (`cheqlog_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_cus_chequelog VALUES("1","3","3","1000.00"),
("2","3","4","350.00");



DROP TABLE IF EXISTS ezy_pos_cus_payment;

CREATE TABLE `ezy_pos_cus_payment` (
  `cus_pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `cus_pay_saleid` int(11) NOT NULL,
  `cus_pay_cash` double(50,2) NOT NULL,
  `cus_pay_credit` double(50,2) NOT NULL,
  `cus_pay_paiddate` date NOT NULL,
  PRIMARY KEY (`cus_pay_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_cus_payment VALUES("1","1","140000.00","0.00","2020-01-29"),
("2","2","333625.00","0.00","2020-02-12"),
("3","3","117750.00","0.00","2020-08-17"),
("4","4","42000.00","400.00","2020-08-25"),
("5","5","35000.00","0.00","2020-08-25"),
("6","6","30000.00","5000.00","2020-08-28"),
("7","7","0.00","0.00","2020-08-31"),
("8","8","26200.00","4300.00","2020-09-09"),
("9","9","35000.00","0.00","2020-09-10"),
("10","10","35000.00","0.00","2020-09-10"),
("11","11","70000.00","0.00","2020-09-10"),
("12","12","45000.00","0.00","2020-09-10"),
("13","13","0.00","0.00","2020-09-10"),
("14","14","15000.00","2500.00","2020-09-10");



DROP TABLE IF EXISTS ezy_pos_cus_paymnt_log;

CREATE TABLE `ezy_pos_cus_paymnt_log` (
  `pymntlog_id` int(11) NOT NULL AUTO_INCREMENT,
  `pymntlog_saleid` int(11) NOT NULL,
  `pymntlog_amount` double(50,2) NOT NULL,
  `pymntlog_date` date NOT NULL,
  PRIMARY KEY (`pymntlog_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_cus_paymnt_log VALUES("1","1","140000.00","2020-01-29"),
("2","2","333625.00","2020-02-12"),
("3","3","117000.00","2020-08-17"),
("4","4","42000.00","2020-08-25"),
("5","5","35000.00","2020-08-25"),
("6","6","30000.00","2020-08-28"),
("7","7","0.00","2020-08-31"),
("8","8","25000.00","2020-09-09"),
("9","8","1200.00","2020-09-09"),
("10","3","750.00","2020-09-09"),
("11","9","35000.00","2020-09-10"),
("12","10","35000.00","2020-09-10"),
("13","11","70000.00","2020-09-10"),
("14","12","45000.00","2020-09-10"),
("15","13","0.00","2020-09-10"),
("16","14","15000.00","2020-09-10");



DROP TABLE IF EXISTS ezy_pos_cus_return;

CREATE TABLE `ezy_pos_cus_return` (
  `cusrtrn_id` int(11) NOT NULL AUTO_INCREMENT,
  `cusrtrn_cusID` int(11) NOT NULL,
  `cusrtrn_totalRtrn` double(20,2) NOT NULL,
  `cusrtrn_createdby` int(11) NOT NULL,
  `cusrtrn_status` tinyint(1) NOT NULL,
  `cusrtrn_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cusrtrn_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_cus_return VALUES("1","1","20000.00","89","1","2020-01-29 23:56:54"),
("2","1","80000.00","89","1","2020-01-29 23:59:41"),
("3","1","40000.00","89","1","2020-01-30 00:00:34");



DROP TABLE IF EXISTS ezy_pos_cus_return_item;

CREATE TABLE `ezy_pos_cus_return_item` (
  `retrn_itm_id` int(11) NOT NULL AUTO_INCREMENT,
  `retrn_itm_retrnID` int(11) NOT NULL,
  `retrn_itm_itmID` int(11) NOT NULL,
  `retrn_itm_rQty` double(250,2) NOT NULL,
  `retrn_itm_rAmount` double(30,2) NOT NULL,
  `retrn_itm_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`retrn_itm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_cus_return_item VALUES("1","1","3","1.00","20000.00","2020-01-29 23:56:54"),
("2","2","3","4.00","80000.00","2020-01-29 23:59:41"),
("3","3","3","2.00","40000.00","2020-01-30 00:00:34");



DROP TABLE IF EXISTS ezy_pos_customers;

CREATE TABLE `ezy_pos_customers` (
  `cus_id` int(11) NOT NULL AUTO_INCREMENT,
  `cus_name` varchar(200) NOT NULL,
  `cus_address` varchar(250) NOT NULL,
  `cus_contact` varchar(20) NOT NULL,
  `cus_balance` decimal(30,2) NOT NULL,
  `cus_creditlimit` double(30,2) NOT NULL,
  `cus_status` int(1) NOT NULL DEFAULT 1,
  `cus_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cus_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_customers VALUES("1","Wishwa","125/25 church lane,dehiwala","0755236905","0.00","0.00","1","2019-12-03 16:24:56"),
("2","Don Buddhi","125/25 church lane,","0768450000","500.00","1500.00","0","2019-12-13 13:08:46"),
("3","Don Wishwa","125/25 church lane,","0768450000","0.00","2000.00","1","2019-12-13 13:13:20"),
("4","Wishwa Buddhi","120 abc City plaza colombo","+94 755236903","1000.00","15000.00","1","2020-08-20 10:39:25");



DROP TABLE IF EXISTS ezy_pos_employe_salary;

CREATE TABLE `ezy_pos_employe_salary` (
  `empsalary_id` int(11) NOT NULL AUTO_INCREMENT,
  `empsalary_staffid` int(11) NOT NULL,
  `empsalary_month` tinyint(2) NOT NULL,
  `empsalary_year` smallint(5) NOT NULL,
  `empsalary_balance` double(30,2) NOT NULL,
  `empsalary_basicsalary` double(30,2) NOT NULL,
  `empsalary_food` double(30,2) NOT NULL,
  `empsalary_travel` double(30,2) NOT NULL,
  `empsalary_other` double(30,2) NOT NULL,
  `empsalary_ot` double(30,2) NOT NULL,
  `empsalary_bonus` double(30,2) NOT NULL,
  `empsalary_total` double(30,2) NOT NULL,
  `empsalary_createdby` int(11) NOT NULL,
  `empsalary_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`empsalary_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_employe_salary VALUES("1","1","8","2020","0.00","35000.00","300.00","250.00","3500.00","1750.00","250.00","41050.00","89","1"),
("2","1","7","2020","0.00","35000.00","300.00","250.00","3500.00","0.00","1500.00","40550.00","89","1");



DROP TABLE IF EXISTS ezy_pos_employe_salary_log;

CREATE TABLE `ezy_pos_employe_salary_log` (
  `emp_slry_log_expense_id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_slry_log_expense_tblid` int(11) NOT NULL,
  `emp_slry_log_empsalry_tblid` int(11) NOT NULL,
  `emp_slry_log_empid` int(11) NOT NULL,
  `emp_slry_log_month` tinyint(2) NOT NULL,
  `emp_slry_log_year` smallint(5) NOT NULL,
  `emp_slry_log_amount` double(50,2) NOT NULL,
  `emp_slry_log_balanceofmonth` double(50,2) NOT NULL,
  `emp_slry_log_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`emp_slry_log_expense_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_employe_salary_log VALUES("1","7","1","1","8","2020","10000.00","0.00","1"),
("2","8","1","1","8","2020","20000.00","0.00","1"),
("3","9","2","1","7","2020","25000.00","0.00","1"),
("4","10","2","1","7","2020","25000.00","0.00","1");



DROP TABLE IF EXISTS ezy_pos_expen_cheque;

CREATE TABLE `ezy_pos_expen_cheque` (
  `expen_chq_id` int(11) NOT NULL AUTO_INCREMENT,
  `expen_chq_expntblid` int(11) NOT NULL,
  `expen_chq_amount` double(50,2) NOT NULL,
  `expen_chq_bank` varchar(250) NOT NULL,
  `expen_chq_chq_no` varchar(20) NOT NULL,
  `expen_chq_date` date NOT NULL,
  `expen_chq_status` tinyint(2) NOT NULL,
  PRIMARY KEY (`expen_chq_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_expen_cheque VALUES("1","2","3200.00","5","boc123","2020-09-07","1"),
("2","6","32000.00","5","boc124","2020-09-08","1"),
("3","7","10000.00","5","boc125","2020-09-08","1"),
("4","11","45000.00","5","boc127","2020-09-10","1"),
("5","13","2500.00","5","boc12356765","2020-09-11","1");



DROP TABLE IF EXISTS ezy_pos_expense;

CREATE TABLE `ezy_pos_expense` (
  `expen_id` int(11) NOT NULL AUTO_INCREMENT,
  `expen_expencat_id` int(11) NOT NULL,
  `expen_description` text NOT NULL,
  `expen_amount` double(30,2) NOT NULL,
  `expen_date` date NOT NULL,
  `expen_createdby` int(11) NOT NULL,
  `expen_status` tinyint(1) NOT NULL DEFAULT 1,
  `expen_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`expen_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_expense VALUES("1","2","August Building Rent","45000.00","2020-09-03","89","1","2020-09-07 11:21:38"),
("2","3","August Water Bill","3200.00","2020-09-06","89","1","2020-09-07 11:36:23"),
("3","4","August Electricity Bill","5300.00","2020-09-07","89","1","2020-09-07 12:18:28"),
("4","3","August Water Bill 2","1200.00","2020-09-08","89","1","2020-09-08 14:42:42"),
("5","4","August Electricity Bill 2","4500.00","2020-09-08","89","1","2020-09-08 14:51:11"),
("6","5","August Rent","32000.00","2020-09-08","89","1","2020-09-08 15:09:44"),
("7","1","August salary","10000.00","2020-09-08","89","1","2020-09-08 15:24:55"),
("8","1","Salary","20000.00","2020-09-09","89","1","2020-09-09 10:50:46"),
("9","1","July Staff User Salary","25000.00","2020-09-10","89","1","2020-09-10 16:08:47"),
("10","1","July Staff User Salary","25000.00","2020-09-10","89","1","2020-09-10 16:08:59"),
("11","2","July Building Rent","45000.00","2020-09-10","89","1","2020-09-10 16:10:41"),
("12","3","July Water Bill","5300.00","2020-09-11","89","1","2020-09-11 10:31:51"),
("13","4","July Electricity","2500.00","2020-09-11","89","1","2020-09-11 10:34:23");



DROP TABLE IF EXISTS ezy_pos_expense_cat;

CREATE TABLE `ezy_pos_expense_cat` (
  `expencat_id` int(11) NOT NULL AUTO_INCREMENT,
  `expencat_catname` varchar(250) NOT NULL,
  `expencat_status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`expencat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_expense_cat VALUES("1","Employee Salary","1"),
("2","Rent","1"),
("3","waterBill","1"),
("4","Electricity Bill","1"),
("5","Rent","1"),
("6","Other","1");



DROP TABLE IF EXISTS ezy_pos_grn_item;

CREATE TABLE `ezy_pos_grn_item` (
  `grnitm_id` int(11) NOT NULL AUTO_INCREMENT,
  `grnitm_grn_id` int(11) NOT NULL,
  `grnitm_itemid` int(11) NOT NULL,
  `grnitm_price` double(50,2) NOT NULL,
  `grnitm_quantity` double(250,2) NOT NULL,
  `grnitm_total` double(50,2) NOT NULL,
  `grnitm_discount` double(5,2) NOT NULL,
  `grnitm_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`grnitm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_grn_item VALUES("1","1","3","12500.00","5.00","62500.00","0.00","2020-01-29 23:52:37"),
("2","2","3","13500.00","5.00","67500.00","0.00","2020-01-29 23:54:12"),
("3","2","2","17500.00","8.00","140000.00","0.00","2020-01-29 23:54:13"),
("4","3","3","25000.00","2.00","50000.00","0.00","2020-02-06 14:13:25"),
("5","4","3","17500.00","3.00","52500.00","0.00","2020-02-06 14:20:58"),
("6","5","1","100000.00","10.00","750000.00","25.00","2020-08-17 10:37:45"),
("7","5","2","25000.00","15.00","363750.00","3.00","2020-08-17 10:37:45"),
("8","6","1","12000.00","10.00","120000.00","0.00","2020-08-25 11:24:12"),
("9","6","2","15000.00","5.00","75000.00","0.00","2020-08-25 11:24:12"),
("10","7","2","12000.00","10.00","114000.00","5.00","2020-09-07 04:47:53"),
("11","7","3","10500.00","10.00","105000.00","0.00","2020-09-07 04:47:53"),
("12","8","7","12000.00","10.00","120000.00","0.00","2020-09-07 04:53:32"),
("13","9","9","24000.00","10.00","240000.00","0.00","2020-09-08 16:07:20"),
("14","10","8","82000.00","10.00","820000.00","0.00","2020-09-09 11:49:53"),
("15","11","6","550.00","100.00","55000.00","0.00","2020-09-10 16:13:37"),
("16","12","8","45000.00","10.00","450000.00","0.00","2020-09-10 16:57:07");



DROP TABLE IF EXISTS ezy_pos_grns;

CREATE TABLE `ezy_pos_grns` (
  `grn_id` int(11) NOT NULL AUTO_INCREMENT,
  `grn_code` varchar(50) NOT NULL,
  `grn_supplier_id` int(11) NOT NULL,
  `grn_grandtotal` double(50,2) NOT NULL,
  `grn_subtotal` double(50,2) NOT NULL,
  `grn_discount` double(5,2) NOT NULL,
  `grn_less` double(10,2) NOT NULL,
  `grn_createdby` int(11) NOT NULL,
  `grn_date` date NOT NULL,
  `grn_status` tinyint(1) NOT NULL,
  `grn_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`grn_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_grns VALUES("1","WBJ1","1","62500.00","62500.00","0.00","0.00","89","2020-01-29","1","2020-01-29 23:52:37"),
("2","WBJ2","1","207500.00","207500.00","0.00","0.00","89","2020-01-29","1","2020-01-29 23:54:12"),
("3","WBJ2","1","50000.00","50000.00","0.00","0.00","89","2020-02-06","1","2020-02-06 14:13:24"),
("4","WBJ3","1","52500.00","52500.00","0.00","0.00","89","2020-02-06","1","2020-02-06 14:20:58"),
("5","123","1","991237.50","1113750.00","11.00","0.00","114","2020-08-17","1","2020-08-17 10:37:45"),
("6","wish123","2","195000.00","195000.00","0.00","0.00","89","2020-08-25","1","2020-08-25 11:24:12"),
("7","wi123","1","186150.00","219000.00","15.00","0.00","89","2020-09-07","1","2020-09-07 04:47:53"),
("8","wi124","1","120000.00","120000.00","0.00","0.00","89","2020-09-07","1","2020-09-07 04:53:31"),
("9","wish 12345","2","216000.00","240000.00","10.00","0.00","89","2020-09-08","1","2020-09-08 16:07:20"),
("10","wish 12346","2","779000.00","820000.00","5.00","0.00","89","2020-09-09","1","2020-09-09 11:49:53"),
("11","wish 123477","1","55000.00","55000.00","0.00","0.00","89","2020-09-10","1","2020-09-10 16:13:37"),
("12","wish 12348","1","450000.00","450000.00","0.00","0.00","89","2020-09-10","1","2020-09-10 16:57:07");



DROP TABLE IF EXISTS ezy_pos_insuffistocksale;

CREATE TABLE `ezy_pos_insuffistocksale` (
  `insuffi_id` int(11) NOT NULL AUTO_INCREMENT,
  `insuffi_saleid` int(11) NOT NULL,
  `insuffi_itmid` int(11) NOT NULL,
  `insuffi_qty` double(250,2) NOT NULL,
  `insuffi_newqty` double(250,2) NOT NULL,
  `insuffi_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `insuffi_grnstatus` tinyint(1) NOT NULL DEFAULT 0,
  `insuffi_salestatus` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`insuffi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS ezy_pos_items;

CREATE TABLE `ezy_pos_items` (
  `itm_id` int(11) NOT NULL AUTO_INCREMENT,
  `itm_code` varchar(250) NOT NULL,
  `itm_name` varchar(250) NOT NULL,
  `itm_category` int(11) NOT NULL,
  `itm_subcategory` int(11) NOT NULL,
  `itm_brand` varchar(250) NOT NULL,
  `itm_manufacture` varchar(250) NOT NULL,
  `itm_description` text NOT NULL,
  `itm_sellingprice` double(30,2) NOT NULL,
  `itm_quantity` double(250,2) NOT NULL,
  `itm_reorderlevel` double(250,2) NOT NULL,
  `itm_uom` varchar(250) NOT NULL,
  `itm_status` int(1) NOT NULL DEFAULT 1,
  `itm_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`itm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_items VALUES("1","ITEM w7","Samsung A50","1","1","Samsung","Samsung PLC(China)","Smart Phone manufactured by Samsung.","35000.00","0.00","5.00","Item","1","2019-12-03 16:14:58"),
("2","ITEM w2","Motorola One Action","1","1","Motorola","Motorola China","Motorola One Action. Released 2019, August. 176g, 9.2mm thickness. Android 9.0; Android One. 128GB storage, microSD slot.","45000.00","0.00","5.00","Item","1","2019-12-03 16:31:35"),
("3","ITEM w3","Huawei Y6","1","1","Huawei","Huawei China","Super phone,","20000.00","0.00","5.00","Unit","1","2019-12-05 16:04:32"),
("4","ITEM w4","MI Band v3","4","2","Mi","Mi Tech China","Description","5000.00","0.00","3.00","Unit","1","2019-12-10 11:42:43"),
("5","ITEM w5","LETSCOM Fitness Tracker HR","4","4","LETSCOM","LETSCOM China","LETSCOM Smart Watch Fitness Tracker Heart Rate Monitor Step Calorie Counter Sleep Monitor Music Control IP68 Water Resistant 1.3\" Color Touch Screen Activity Tracking Pedometer for Women Men ","37.99","0.00","5.00","Item","1","2019-12-13 12:04:46"),
("6","ITEM w6","Camera Watch","4","0","Common","Common","Product Description","1500.00","0.00","0.00","Item","1","2019-12-13 12:15:20"),
("7","ITEM w1","Huawei Y6 pro","1","1","Huawei","Huawei China","wertyuiop","45000.00","0.00","5.00","Item","1","2019-12-19 11:03:39"),
("8","ITEM w8","Apple 11 Max","1","1","Samsung","Samsung PLC(China)","ccccccccc","105000.00","0.00","0.00","Item","1","2019-12-19 12:31:55"),
("9","ITEM w9","Huawei P30 Lite ","1","1","Huawei","Huawei China","Huawei P30 Lite  is the lite version of flagship P30.","40000.00","0.00","3.00","Item","1","2020-01-07 11:15:34");



DROP TABLE IF EXISTS ezy_pos_privileges;

CREATE TABLE `ezy_pos_privileges` (
  `priv_id` int(11) NOT NULL AUTO_INCREMENT,
  `priv_userid` int(11) NOT NULL,
  `priv_item` tinyint(1) NOT NULL DEFAULT 0,
  `priv_category` tinyint(1) NOT NULL DEFAULT 0,
  `priv_customer` tinyint(1) NOT NULL DEFAULT 0,
  `priv_supplier` tinyint(1) NOT NULL DEFAULT 0,
  `priv_store` tinyint(1) NOT NULL DEFAULT 0,
  `priv_staff` tinyint(1) NOT NULL DEFAULT 0,
  `priv_tax` tinyint(1) NOT NULL DEFAULT 0,
  `priv_register` tinyint(1) NOT NULL DEFAULT 0,
  `priv_expense_cat` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`priv_id`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_privileges VALUES("51","89","1","1","1","1","1","1","1","1","1"),
("81","120","0","0","0","0","1","0","0","0","0"),
("80","119","1","0","0","0","1","0","0","0","0"),
("79","118","1","1","1","1","1","1","0","0","0"),
("78","117","1","1","1","1","1","1","1","0","0");



DROP TABLE IF EXISTS ezy_pos_sale;

CREATE TABLE `ezy_pos_sale` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_cus_id` int(11) NOT NULL,
  `sale_grandtotal` double(50,2) NOT NULL,
  `sale_subtotal` double(50,2) NOT NULL,
  `sale_discount` double(5,2) NOT NULL,
  `sale_less` double(10,2) NOT NULL,
  `sale_createdby` int(11) NOT NULL,
  `sale_date` date NOT NULL,
  `sale_location` int(11) NOT NULL,
  `sale_status` tinyint(4) NOT NULL DEFAULT 1,
  `sale_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`sale_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_sale VALUES("1","1","0.00","0.00","0.00","0.00","89","2020-01-29","3","1","2020-01-29 23:54:49"),
("2","1","333625.00","392500.00","15.00","0.00","89","2020-02-12","1","1","2020-02-12 10:21:38"),
("3","1","118750.00","125000.00","5.00","0.00","114","2020-08-17","2","1","2020-08-17 10:41:56"),
("4","1","42750.00","45000.00","5.00","0.00","89","2020-08-25","2","1","2020-08-25 12:06:09"),
("5","1","35000.00","35000.00","0.00","0.00","89","2020-09-04","1","1","2020-09-04 12:10:15"),
("6","4","35000.00","35000.00","0.00","0.00","89","2020-08-28","3","1","2020-08-28 16:05:53"),
("7","1","40500.00","45000.00","10.00","0.00","89","2020-09-04","3","1","2020-09-04 14:14:32"),
("8","3","105000.00","105000.00","0.00","0.00","89","2020-09-09","3","1","2020-09-09 14:39:24"),
("9","1","35000.00","35000.00","0.00","0.00","89","2020-09-10","3","1","2020-09-10 12:03:46"),
("10","1","35000.00","35000.00","0.00","0.00","89","2020-09-10","3","1","2020-09-10 12:15:58"),
("11","1","70000.00","70000.00","0.00","0.00","89","2020-09-10","3","1","2020-09-10 13:22:54"),
("12","1","45000.00","45000.00","0.00","0.00","89","2020-09-10","3","1","2020-09-10 13:27:21"),
("13","3","20000.00","20000.00","0.00","0.00","89","2020-09-10","2","1","2020-09-10 16:02:11"),
("14","3","35000.00","35000.00","0.00","0.00","89","2020-09-10","3","1","2020-09-10 16:03:52");



DROP TABLE IF EXISTS ezy_pos_sale_item;

CREATE TABLE `ezy_pos_sale_item` (
  `saleitem_id` int(11) NOT NULL AUTO_INCREMENT,
  `saleitem_sale_id` int(11) NOT NULL,
  `saleitem_item_id` int(11) NOT NULL,
  `saleitem_price` double(50,2) NOT NULL,
  `saleitem_quantity` double(250,2) NOT NULL,
  `saleitem_total` double(50,2) NOT NULL,
  `saleitem_discount` double(5,2) NOT NULL,
  `saleitem_ctreatedat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`saleitem_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_sale_item VALUES("1","1","3","20000.00","0.00","0.00","0.00","2020-01-29 23:54:49"),
("2","2","3","20000.00","10.00","190000.00","5.00","2020-02-12 10:21:39"),
("3","2","2","45000.00","5.00","202500.00","10.00","2020-02-12 10:21:39"),
("4","3","2","45000.00","2.00","90000.00","0.00","2020-08-17 10:41:56"),
("5","3","1","35000.00","1.00","35000.00","0.00","2020-08-17 10:41:56"),
("6","4","2","45000.00","1.00","45000.00","0.00","2020-08-25 12:06:09"),
("7","5","1","35000.00","1.00","35000.00","0.00","2020-08-25 12:10:15"),
("8","6","1","35000.00","1.00","35000.00","0.00","2020-08-28 16:05:53"),
("9","7","2","45000.00","1.00","45000.00","0.00","2020-08-31 14:14:32"),
("10","8","8","105000.00","1.00","105000.00","0.00","2020-09-09 14:39:25"),
("11","9","1","35000.00","1.00","35000.00","0.00","2020-09-10 12:03:47"),
("12","10","1","35000.00","1.00","35000.00","0.00","2020-09-10 12:15:58"),
("13","11","1","35000.00","2.00","70000.00","0.00","2020-09-10 13:22:54"),
("14","12","2","45000.00","1.00","45000.00","0.00","2020-09-10 13:27:22"),
("15","13","3","20000.00","1.00","20000.00","0.00","2020-09-10 16:02:12"),
("16","14","1","35000.00","1.00","35000.00","0.00","2020-09-10 16:03:53");



DROP TABLE IF EXISTS ezy_pos_staffs;

CREATE TABLE `ezy_pos_staffs` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_name` varchar(250) NOT NULL,
  `staff_address` varchar(250) NOT NULL,
  `staff_contact` varchar(20) NOT NULL,
  `staff_basicsalary` double(50,2) NOT NULL,
  `staff_food` double(50,2) NOT NULL,
  `staff_travel` double(50,2) NOT NULL,
  `staff_other` double(50,2) NOT NULL,
  `staff_otperhour` double(50,2) NOT NULL,
  `staff_status` int(1) NOT NULL DEFAULT 1,
  `staff_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`staff_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_staffs VALUES("1","Staff User","120 abc City plaza colombo","0768453735","35000.00","300.00","250.00","3500.00","175.00","1","2020-08-17 10:47:27");



DROP TABLE IF EXISTS ezy_pos_stock;

CREATE TABLE `ezy_pos_stock` (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_itm_id` int(11) NOT NULL,
  `stock_qty` double(30,2) NOT NULL,
  `stock_status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`stock_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_stock VALUES("1","3","13.00","1"),
("2","2","28.00","1"),
("3","1","10.00","1"),
("4","7","10.00","1"),
("5","9","10.00","1"),
("6","8","19.00","1"),
("7","6","100.00","1");



DROP TABLE IF EXISTS ezy_pos_stock_log;

CREATE TABLE `ezy_pos_stock_log` (
  `stocklog_id` int(11) NOT NULL AUTO_INCREMENT,
  `stocklog_itmid` int(11) NOT NULL,
  `stocklog_qty` double(250,2) NOT NULL,
  `stocklog_grnID` int(11) NOT NULL,
  `stocklog_saleID` int(11) NOT NULL,
  `stocklog_return_sup_retrnID` int(11) NOT NULL,
  `stocklog_return_supID` int(11) NOT NULL DEFAULT 0,
  `stocklog_return_cus_retrnID` int(11) NOT NULL,
  `stocklog_return_cusID` int(11) NOT NULL DEFAULT 0,
  `stocklog_status` tinyint(1) NOT NULL,
  `stocklog_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`stocklog_id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_stock_log VALUES("1","3","5.00","1","0","0","0","0","0","1","2020-01-29 23:52:37"),
("2","3","5.00","2","0","0","0","0","0","1","2020-01-29 23:54:13"),
("3","2","8.00","2","0","0","0","0","0","1","2020-01-29 23:54:13"),
("4","3","7.00","0","1","0","0","0","0","1","2020-01-29 23:54:50"),
("5","3","1.00","0","0","0","0","1","1","1","2020-01-29 23:56:59"),
("6","3","4.00","0","0","0","0","2","1","1","2020-01-29 23:59:45"),
("7","3","2.00","0","0","0","0","3","1","1","2020-01-30 00:00:36"),
("8","3","1.00","0","0","1","1","0","0","1","2020-01-30 10:05:47"),
("9","3","2.00","3","0","0","0","0","0","1","2020-02-06 14:13:26"),
("10","3","3.00","4","0","0","0","0","0","1","2020-02-06 14:20:59"),
("11","3","10.00","0","2","0","0","0","0","1","2020-02-12 10:21:39"),
("12","2","5.00","0","2","0","0","0","0","1","2020-02-12 10:21:39"),
("13","1","10.00","5","0","0","0","0","0","1","2020-08-17 10:37:45"),
("14","2","15.00","5","0","0","0","0","0","1","2020-08-17 10:37:45"),
("15","2","2.00","0","3","0","0","0","0","1","2020-08-17 10:41:56"),
("16","1","1.00","0","3","0","0","0","0","1","2020-08-17 10:41:56"),
("17","1","2.00","0","0","2","1","0","0","1","2020-08-17 11:02:55"),
("18","1","10.00","6","0","0","0","0","0","1","2020-08-25 11:24:12"),
("19","2","5.00","6","0","0","0","0","0","1","2020-08-25 11:24:12"),
("20","2","1.00","0","4","0","0","0","0","1","2020-08-25 12:06:09"),
("21","1","1.00","0","5","0","0","0","0","1","2020-08-25 12:10:15"),
("22","1","1.00","0","6","0","0","0","0","1","2020-08-28 16:05:53"),
("23","2","1.00","0","7","0","0","0","0","1","2020-08-31 14:14:32"),
("24","2","10.00","7","0","0","0","0","0","1","2020-09-07 04:47:53"),
("25","3","10.00","7","0","0","0","0","0","1","2020-09-07 04:47:53"),
("26","7","10.00","8","0","0","0","0","0","1","2020-09-07 04:53:32"),
("27","9","10.00","9","0","0","0","0","0","1","2020-09-08 16:07:20"),
("28","8","10.00","10","0","0","0","0","0","1","2020-09-09 11:49:54"),
("29","8","1.00","0","8","0","0","0","0","1","2020-09-09 14:39:25"),
("30","1","1.00","0","9","0","0","0","0","1","2020-09-10 12:03:47"),
("31","1","1.00","0","10","0","0","0","0","1","2020-09-10 12:15:58"),
("32","1","2.00","0","11","0","0","0","0","1","2020-09-10 13:22:55"),
("33","2","1.00","0","12","0","0","0","0","1","2020-09-10 13:27:22"),
("34","3","1.00","0","13","0","0","0","0","1","2020-09-10 16:02:12"),
("35","1","1.00","0","14","0","0","0","0","1","2020-09-10 16:03:53"),
("36","6","100.00","11","0","0","0","0","0","1","2020-09-10 16:13:37"),
("37","8","10.00","12","0","0","0","0","0","1","2020-09-10 16:57:08");



DROP TABLE IF EXISTS ezy_pos_stores;

CREATE TABLE `ezy_pos_stores` (
  `store_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_name` varchar(200) NOT NULL,
  `store_address` varchar(250) NOT NULL,
  `store_address2` varchar(250) NOT NULL,
  `store_tel` varchar(15) NOT NULL,
  `store_mobile` varchar(15) NOT NULL,
  `store_mobile2` varchar(15) NOT NULL,
  `store_fax` varchar(15) NOT NULL,
  `store_email` varchar(200) NOT NULL,
  `store_status` int(1) NOT NULL DEFAULT 1,
  `store_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`store_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_stores VALUES("1","Main Store","150/155","church lane,Ja-ela","0755236903","0755236903","0755236903","0755236904","mainstore@gmail.com","1","2019-12-17 20:03:15"),
("2","Store Ja-ela","120/12 church lane",",wawala ,Ja-ela","0755236911","0755236922","0755236933","(123) 456-7890","storejaela@gmail.com","1","2020-01-21 12:24:28"),
("3","Store Kandana","125/25 church lane,","Kandana","0755236903","0755236903","+94768453735","(075) 523-6903","storekandana@gmail.com","1","2020-01-23 11:32:54");



DROP TABLE IF EXISTS ezy_pos_subcategories;

CREATE TABLE `ezy_pos_subcategories` (
  `sub_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_cat_id` int(11) NOT NULL,
  `sub_cat_name` varchar(250) NOT NULL,
  `sub_cat_status` int(1) NOT NULL DEFAULT 1,
  `sub_cat_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`sub_cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_subcategories VALUES("1","1","Smart Phone","1","2019-12-04 04:29:58"),
("2","4","AIPS Smart Watch","1","2019-12-10 14:34:08"),
("3","4","Camera Smart Watch","1","2019-12-10 14:38:11"),
("4","4","Fitness Tracker Smart Watch","1","2019-12-13 12:02:19");



DROP TABLE IF EXISTS ezy_pos_sup_balnce;

CREATE TABLE `ezy_pos_sup_balnce` (
  `supbal_id` int(11) NOT NULL AUTO_INCREMENT,
  `supbal_supid` int(11) NOT NULL,
  `supbal_amount` double(50,2) NOT NULL,
  PRIMARY KEY (`supbal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS ezy_pos_sup_cash_payment;

CREATE TABLE `ezy_pos_sup_cash_payment` (
  `supcash_id` int(11) NOT NULL AUTO_INCREMENT,
  `supcash_supid` int(11) NOT NULL,
  `supcash_amount` float NOT NULL,
  `supcash_date` datetime NOT NULL,
  `supcash_status` int(11) NOT NULL,
  PRIMARY KEY (`supcash_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_sup_cash_payment VALUES("1","1","62500","2020-01-29 00:00:00","1"),
("2","1","157500","2020-01-29 00:00:00","1"),
("3","1","23500","2020-02-06 00:00:00","1"),
("4","1","23500","2020-02-06 00:00:00","1"),
("5","1","990000","2020-08-17 00:00:00","1"),
("6","2","190000","2020-08-25 00:00:00","1"),
("7","1","180000","2020-09-07 00:00:00","1"),
("8","1","5000","2020-09-07 00:00:00","1"),
("9","1","110000","2020-09-07 00:00:00","1"),
("10","1","7887.5","2020-09-08 00:00:00","1"),
("11","1","25000","2020-09-08 00:00:00","1"),
("12","2","55000","2020-09-08 00:00:00","1"),
("13","2","650000","2020-09-08 00:00:00","1"),
("14","1","49500","2020-09-10 00:00:00","1"),
("15","1","200000","2020-09-10 00:00:00","1");



DROP TABLE IF EXISTS ezy_pos_sup_cheque;

CREATE TABLE `ezy_pos_sup_cheque` (
  `sup_cheque_id` int(11) NOT NULL AUTO_INCREMENT,
  `sup_cheque_grnid` int(11) NOT NULL,
  `sup_cheque_amount` double(50,2) NOT NULL,
  `sup_cheque_bank` varchar(250) NOT NULL,
  `sup_cheque_num` varchar(250) NOT NULL,
  `sup_cheque_our0_cuscheqtblid` tinyint(1) NOT NULL,
  `sup_cheque_date` date NOT NULL,
  `sup_cheque_givendate` date NOT NULL,
  `sup_cheque_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`sup_cheque_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_sup_cheque VALUES("1","0","15500.00","5","wb126","0","2020-09-08","2020-09-08","1"),
("2","9","120000.00","5","wi127","0","2020-09-09","2020-09-08","1"),
("3","11","1350.00","HSBC","hsbc123","3","2020-09-09","2020-09-10","1"),
("4","12","205000.00","5","boc123123","0","2020-09-10","2020-09-10","1");



DROP TABLE IF EXISTS ezy_pos_sup_chequelog;

CREATE TABLE `ezy_pos_sup_chequelog` (
  `supchqlog_id` int(11) NOT NULL AUTO_INCREMENT,
  `supchqlog_chqid` int(11) NOT NULL,
  `supchqlog_grnid` int(11) NOT NULL,
  `supchqlog_amnt` double(50,2) NOT NULL,
  `supchqlog_givndate` datetime NOT NULL,
  `supchqlog_status` varchar(10) NOT NULL,
  PRIMARY KEY (`supchqlog_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_sup_chequelog VALUES("1","1","2","15500.00","2020-09-08 00:00:00","1"),
("2","2","9","120000.00","2020-09-08 00:00:00","1"),
("3","3","11","1350.00","2020-09-10 00:00:00","1"),
("4","4","12","205000.00","2020-09-10 00:00:00","1");



DROP TABLE IF EXISTS ezy_pos_sup_payment;

CREATE TABLE `ezy_pos_sup_payment` (
  `sup_pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `sup_pay_grnid` int(11) NOT NULL,
  `sup_pay_cash` double(50,2) NOT NULL,
  `sup_pay_credit` double(50,2) NOT NULL,
  PRIMARY KEY (`sup_pay_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_sup_payment VALUES("1","1","62500.00","0.00"),
("2","2","190387.50","1612.50"),
("3","3","23500.00","26500.00"),
("4","4","23500.00","29000.00"),
("5","5","990000.00","1237.50"),
("6","6","190000.00","5000.00"),
("7","7","185000.00","1150.00"),
("8","8","110000.00","10000.00"),
("9","9","55000.00","41000.00"),
("10","10","650000.00","129000.00"),
("11","11","49500.00","4150.00"),
("12","12","200000.00","45000.00");



DROP TABLE IF EXISTS ezy_pos_sup_paymentcashlog;

CREATE TABLE `ezy_pos_sup_paymentcashlog` (
  `paycashlog_id` int(11) NOT NULL AUTO_INCREMENT,
  `paycashlog_grnid` int(11) NOT NULL,
  `paycashlog_cashid` int(11) NOT NULL,
  `paycashlog_amount` double(50,2) NOT NULL,
  `paycashlog_date` date NOT NULL,
  `paycashlog_status` int(11) NOT NULL,
  PRIMARY KEY (`paycashlog_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_sup_paymentcashlog VALUES("1","1","1","62500.00","2020-01-29","1"),
("2","2","2","157500.00","2020-01-29","1"),
("3","3","3","23500.00","2020-02-06","1"),
("4","4","4","23500.00","2020-02-06","1"),
("5","5","5","990000.00","2020-08-17","1"),
("6","6","6","190000.00","2020-08-25","1"),
("7","7","7","180000.00","2020-09-07","1"),
("8","7","8","5000.00","2020-09-07","1"),
("9","8","9","110000.00","2020-09-07","1"),
("10","2","10","7887.50","2020-09-08","1"),
("11","2","11","25000.00","2020-09-08","1"),
("12","9","12","55000.00","2020-09-08","1"),
("13","10","13","650000.00","2020-09-09","1"),
("14","11","14","49500.00","2020-09-10","1"),
("15","12","15","200000.00","2020-09-10","1");



DROP TABLE IF EXISTS ezy_pos_sup_return;

CREATE TABLE `ezy_pos_sup_return` (
  `suprtrn_id` int(11) NOT NULL AUTO_INCREMENT,
  `suprtrn_supID` int(11) NOT NULL,
  `suprtrn_totalRtrn` double(30,2) NOT NULL,
  `suprtrn_createdby` int(11) NOT NULL,
  `suprtrn_status` tinyint(1) NOT NULL DEFAULT 1,
  `suprtrn_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`suprtrn_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_sup_return VALUES("1","1","12500.00","89","1","2020-01-30 10:05:47"),
("2","1","148333.33","114","1","2020-08-17 11:02:55");



DROP TABLE IF EXISTS ezy_pos_sup_return_item;

CREATE TABLE `ezy_pos_sup_return_item` (
  `supRtrn_itm_id` int(11) NOT NULL AUTO_INCREMENT,
  `supRtrn_itm_supRtrnID` int(11) NOT NULL,
  `supRtrn_itm_itmID` int(11) NOT NULL,
  `supRtrn_itm_rQty` double(250,2) NOT NULL,
  `supRtrn_itm_rAmount` double(30,2) NOT NULL,
  `supRtrn_itm_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`supRtrn_itm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_sup_return_item VALUES("1","1","3","1.00","12500.00","2020-01-30 10:05:47"),
("2","2","1","2.00","148333.33","2020-08-17 11:02:55");



DROP TABLE IF EXISTS ezy_pos_suppliers;

CREATE TABLE `ezy_pos_suppliers` (
  `sup_id` int(11) NOT NULL AUTO_INCREMENT,
  `sup_name` varchar(200) NOT NULL,
  `sup_address` varchar(250) NOT NULL,
  `sup_contact` int(20) NOT NULL,
  `sup_balance` double(50,2) NOT NULL,
  `sup_status` int(1) NOT NULL DEFAULT 1,
  `sup_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`sup_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_suppliers VALUES("1","SUPPLIER wishwa","125/25 church lane,","755236905","120537.50","1","2019-12-03 16:25:47"),
("2","SUPPLIER wish","125/25 church lane,","768451111","175000.00","1","2019-12-13 13:16:07");



DROP TABLE IF EXISTS ezy_pos_taxs;

CREATE TABLE `ezy_pos_taxs` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_name` varchar(200) NOT NULL,
  `tax_value` double(50,2) NOT NULL,
  `tax_status` int(1) NOT NULL DEFAULT 1,
  `tax_createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`tax_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_taxs VALUES("1","VAT","1000.00","1","2019-12-13 13:22:15"),
("2","Tax 1","2500.00","0","2019-12-13 13:23:18");



DROP TABLE IF EXISTS ezy_pos_user_store;

CREATE TABLE `ezy_pos_user_store` (
  `user_store_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `user_store_status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`user_store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_user_store VALUES("7","117","1","1"),
("8","117","2","1"),
("9","117","3","1"),
("10","118","2","1"),
("11","120","3","1"),
("17","119","1","1");



DROP TABLE IF EXISTS ezy_pos_users;

CREATE TABLE `ezy_pos_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_username` varchar(250) NOT NULL,
  `user_name` varchar(250) NOT NULL,
  `user_password` varchar(250) NOT NULL,
  `user_role` int(1) NOT NULL,
  `user_status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=121 DEFAULT CHARSET=latin1;

INSERT INTO ezy_pos_users VALUES("89","admin","Admin","202cb962ac59075b964b07152d234b70","1","1"),
("120","user_kandana","Kandana User","202cb962ac59075b964b07152d234b70","0","1"),
("119","main_user","Main Stores User","202cb962ac59075b964b07152d234b70","0","1"),
("118","user_jaela","User Ja ela","202cb962ac59075b964b07152d234b70","0","1"),
("117","wishwa_admin","Admin Wishwa","202cb962ac59075b964b07152d234b70","1","1");



