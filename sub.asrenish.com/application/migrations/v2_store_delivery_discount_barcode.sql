-- =============================================
-- v2 Migration: Store-based stock, Delivery charges, Discount types
-- Run these on your existing database
-- =============================================

-- FEATURE 2: Delivery Companies master table
CREATE TABLE IF NOT EXISTS `ezy_pos_delivery_companies` (
  `dc_id` int NOT NULL AUTO_INCREMENT,
  `dc_name` varchar(150) NOT NULL,
  `dc_contact` varchar(100) DEFAULT NULL,
  `dc_status` tinyint(1) NOT NULL DEFAULT '1',
  `dc_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`dc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- FEATURE 2: Add delivery charge fields to sales table
ALTER TABLE `ezy_pos_sale` ADD COLUMN `sale_delivery_company_id` int DEFAULT NULL COMMENT 'FK to delivery companies';
ALTER TABLE `ezy_pos_sale` ADD COLUMN `sale_delivery_charge` double(20,2) NOT NULL DEFAULT '0.00' COMMENT 'Delivery charge amount';

-- FEATURE 3: Add discount type fields
ALTER TABLE `ezy_pos_sale` ADD COLUMN `sale_discount_type` varchar(10) NOT NULL DEFAULT 'percentage' COMMENT 'percentage or flat';
ALTER TABLE `ezy_pos_sale_item` ADD COLUMN `saleitem_discount_type` varchar(10) NOT NULL DEFAULT 'percentage' COMMENT 'percentage or flat';
ALTER TABLE `ezy_pos_grns` ADD COLUMN `grn_discount_type` varchar(10) NOT NULL DEFAULT 'percentage' COMMENT 'percentage or flat';
ALTER TABLE `ezy_pos_grn_item` ADD COLUMN `grnitm_discount_type` varchar(10) NOT NULL DEFAULT 'percentage' COMMENT 'percentage or flat';
ALTER TABLE `ezy_pos_production` ADD COLUMN `prod_discount` double(20,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `ezy_pos_production` ADD COLUMN `prod_discount_type` varchar(10) NOT NULL DEFAULT 'percentage' COMMENT 'percentage or flat';

-- Payment Methods
CREATE TABLE IF NOT EXISTS `ezy_pos_payment_methods` (
  `pm_id` int NOT NULL AUTO_INCREMENT,
  `pm_name` varchar(100) NOT NULL,
  `pm_commission_pct` double(10,2) NOT NULL DEFAULT '0.00',
  `pm_commission_fixed` double(10,2) NOT NULL DEFAULT '0.00',
  `pm_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ezy_pos_sale_payments` (
  `sp_id` int NOT NULL AUTO_INCREMENT,
  `sp_sale_id` int NOT NULL,
  `sp_pm_id` int NOT NULL,
  `sp_amount` double(20,2) NOT NULL DEFAULT '0.00',
  `sp_commission` double(20,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`sp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `ezy_pos_sale` ADD COLUMN `sale_commission` double(20,2) NOT NULL DEFAULT '0.00';
