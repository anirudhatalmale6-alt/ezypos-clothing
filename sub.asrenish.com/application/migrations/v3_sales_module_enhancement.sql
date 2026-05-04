-- =============================================
-- v3 Migration: Sales Module Enhancement
-- Sale types, online sales, card references, SMS, mandatory phone
-- =============================================

-- Sale type and online sale ID on sales table
ALTER TABLE `ezy_pos_sale` ADD COLUMN `sale_type` varchar(20) NOT NULL DEFAULT 'cash' COMMENT 'cash, card, credit, online';
ALTER TABLE `ezy_pos_sale` ADD COLUMN `sale_online_id` varchar(100) DEFAULT NULL COMMENT 'External online sale reference ID';
ALTER TABLE `ezy_pos_sale` ADD COLUMN `sale_customer_phone` varchar(30) DEFAULT NULL COMMENT 'Phone number captured at sale time';

-- Card machine reference IDs per payment
ALTER TABLE `ezy_pos_sale_payments` ADD COLUMN `sp_card_ref` varchar(100) DEFAULT NULL COMMENT 'Card machine receipt/reference ID';

-- Hutch SMS config rows (insert only if not exists)
INSERT INTO `ezy_pos_config2` (`config_key`, `config_value`) SELECT 'sms_username', 'handloomgallery825@gmail.com' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `ezy_pos_config2` WHERE `config_key`='sms_username');
INSERT INTO `ezy_pos_config2` (`config_key`, `config_value`) SELECT 'sms_password', 'HG633@siva' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `ezy_pos_config2` WHERE `config_key`='sms_password');
INSERT INTO `ezy_pos_config2` (`config_key`, `config_value`) SELECT 'sms_mask', 'Handloom Gallery' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `ezy_pos_config2` WHERE `config_key`='sms_mask');
INSERT INTO `ezy_pos_config2` (`config_key`, `config_value`) SELECT 'sms_campaign', 'Handloom Gallery Receipt' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `ezy_pos_config2` WHERE `config_key`='sms_campaign');

-- SMS log table
CREATE TABLE IF NOT EXISTS `ezy_pos_sms_log` (
  `smslog_id` int NOT NULL AUTO_INCREMENT,
  `smslog_sale_id` int NOT NULL,
  `smslog_phone` varchar(30) NOT NULL,
  `smslog_message` text,
  `smslog_status` varchar(50) DEFAULT NULL COMMENT 'success/error',
  `smslog_response` text,
  `smslog_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`smslog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
