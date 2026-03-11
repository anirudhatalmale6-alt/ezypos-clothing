-- =============================================
-- Handloom Gallery - Migration Script v2
-- Run this on your existing database to add
-- Production Sale (Tailoring Order) feature
-- =============================================

-- 1. Update CASH customer credit limit to 0
UPDATE ezy_pos_customers SET cus_creditlimit = 0.00 WHERE UPPER(cus_name) = 'CASH';

-- 2. Production Sale (Tailoring Order) Tables
CREATE TABLE IF NOT EXISTS `ezy_pos_prodsale` (
  `prodsale_id` int(11) NOT NULL AUTO_INCREMENT,
  `prodsale_code` varchar(50) DEFAULT NULL,
  `prodsale_cus_id` int(11) DEFAULT NULL,
  `prodsale_store_id` int(11) DEFAULT 0,
  `prodsale_date` date DEFAULT NULL,
  `prodsale_delivery_date` date DEFAULT NULL,
  `prodsale_material_cost` double(20,2) DEFAULT 0.00,
  `prodsale_tailoring_charge` double(20,2) DEFAULT 0.00,
  `prodsale_service_charges` double(20,2) DEFAULT 0.00,
  `prodsale_total` double(20,2) DEFAULT 0.00,
  `prodsale_paid` double(20,2) DEFAULT 0.00,
  `prodsale_balance` double(20,2) DEFAULT 0.00,
  `prodsale_notes` text,
  `prodsale_status` varchar(30) DEFAULT 'Pending',
  `prodsale_createdby` int(11) DEFAULT NULL,
  `prodsale_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `prodsale_updatedat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`prodsale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ezy_pos_prodsale_items` (
  `prodsaleitem_id` int(11) NOT NULL AUTO_INCREMENT,
  `prodsaleitem_prodsale_id` int(11) DEFAULT NULL,
  `prodsaleitem_item_id` int(11) DEFAULT NULL,
  `prodsaleitem_qty` double(20,2) DEFAULT 0.00,
  `prodsaleitem_unit_price` double(20,2) DEFAULT 0.00,
  `prodsaleitem_total` double(20,2) DEFAULT 0.00,
  `prodsaleitem_type` varchar(30) DEFAULT 'material',
  `prodsaleitem_status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`prodsaleitem_id`),
  KEY `idx_prodsaleitem_prodsale` (`prodsaleitem_prodsale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ezy_pos_prodsale_services` (
  `prodsvc_id` int(11) NOT NULL AUTO_INCREMENT,
  `prodsvc_prodsale_id` int(11) DEFAULT NULL,
  `prodsvc_description` varchar(255) DEFAULT NULL,
  `prodsvc_charge` double(20,2) DEFAULT 0.00,
  `prodsvc_status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`prodsvc_id`),
  KEY `idx_prodsvc_prodsale` (`prodsvc_prodsale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Done!
