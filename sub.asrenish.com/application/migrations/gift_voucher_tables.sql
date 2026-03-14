-- =============================================
-- Gift Voucher Module - New Tables
-- Run these on your existing database
-- =============================================

-- 1. Voucher Categories (price tiers)
CREATE TABLE IF NOT EXISTS `ezy_pos_voucher_categories` (
  `vcat_id` int NOT NULL AUTO_INCREMENT,
  `vcat_name` varchar(100) NOT NULL COMMENT 'e.g. Gift Voucher Rs.1000',
  `vcat_value` double(20,2) NOT NULL COMMENT 'Face value of voucher',
  `vcat_barcode` varchar(100) DEFAULT NULL COMMENT 'Common barcode for this price tier',
  `vcat_is_oneoff` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=one-time use, 0=balance can carry forward',
  `vcat_has_expiry` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=has expiry, 0=never expires',
  `vcat_expiry_days` int DEFAULT NULL COMMENT 'Days from issue date to expiry',
  `vcat_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `vcat_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`vcat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Individual Gift Cards
CREATE TABLE IF NOT EXISTS `ezy_pos_gift_cards` (
  `gc_id` int NOT NULL AUTO_INCREMENT,
  `gc_vcat_id` int NOT NULL COMMENT 'FK to voucher category',
  `gc_card_number` varchar(50) NOT NULL COMMENT 'Unique card number',
  `gc_original_value` double(20,2) NOT NULL COMMENT 'Original face value',
  `gc_remaining_value` double(20,2) NOT NULL COMMENT 'Remaining balance (for multi-use)',
  `gc_status` enum('Available','Sold','Redeemed','Expired') NOT NULL DEFAULT 'Available',
  `gc_sold_sale_id` int DEFAULT NULL COMMENT 'Sale ID when card was sold',
  `gc_sold_date` datetime DEFAULT NULL,
  `gc_expiry_date` date DEFAULT NULL COMMENT 'NULL = never expires',
  `gc_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`gc_id`),
  UNIQUE KEY `uq_card_number` (`gc_card_number`),
  KEY `idx_vcat` (`gc_vcat_id`),
  KEY `idx_status` (`gc_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Voucher Redemptions (tracks each use)
CREATE TABLE IF NOT EXISTS `ezy_pos_voucher_redemptions` (
  `vr_id` int NOT NULL AUTO_INCREMENT,
  `vr_gc_id` int NOT NULL COMMENT 'FK to gift card',
  `vr_sale_id` int DEFAULT NULL COMMENT 'Sale where voucher was redeemed',
  `vr_amount` double(20,2) NOT NULL COMMENT 'Amount redeemed',
  `vr_redeemed_by` int NOT NULL COMMENT 'User who processed',
  `vr_redeemedat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`vr_id`),
  KEY `idx_gc` (`vr_gc_id`),
  KEY `idx_sale` (`vr_sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
