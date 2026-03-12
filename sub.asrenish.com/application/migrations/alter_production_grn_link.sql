-- Add Production-GRN link and store columns to ezy_pos_production
ALTER TABLE `ezy_pos_production` ADD COLUMN `prod_grn_id` int DEFAULT NULL COMMENT 'FK to ezy_pos_grns.grn_id - created on completion' AFTER `prod_completedat`;
ALTER TABLE `ezy_pos_production` ADD COLUMN `prod_store_id` int NOT NULL DEFAULT '0' COMMENT 'Source store for materials' AFTER `prod_grn_id`;
ALTER TABLE `ezy_pos_production` ADD COLUMN `prod_output_store_id` int NOT NULL DEFAULT '0' COMMENT 'Destination store for finished GRN' AFTER `prod_store_id`;

-- Add cur_store_id to currentqtywithgrn if not exists
ALTER TABLE `ezy_pos_currentqtywithgrn` ADD COLUMN `cur_store_id` int NOT NULL DEFAULT '0' AFTER `cur_currentQTY`;
