-- =====================================================================
-- Item 4: Production Module - Complete Redesign
-- =====================================================================
-- New workflow: 1 Raw Material = Many Output Items, 1 Gate Pass = Many
-- Production Records. These new tables back the redesigned module and do
-- NOT touch the old production tables, so existing data is preserved.
-- Safe to run multiple times (CREATE TABLE IF NOT EXISTS).
-- =====================================================================

-- Gate pass: the dispatch document that groups one or more productions.
CREATE TABLE IF NOT EXISTS `ezy_pos_mfg_gatepass` (
  `gp_id` INT AUTO_INCREMENT PRIMARY KEY,
  `gp_code` VARCHAR(30) NOT NULL,
  `gp_store_id` INT NOT NULL,
  `gp_status` VARCHAR(30) NOT NULL DEFAULT 'Draft',
  `gp_notes` TEXT NULL,
  `gp_prepared_by` VARCHAR(120) NULL,
  `gp_approved_by` VARCHAR(120) NULL,
  `gp_createdby` INT NULL,
  `gp_createdat` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `gp_dispatchedat` DATETIME NULL,
  `gp_completedat` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Production record: one raw material (Fabric) turned into many output items.
CREATE TABLE IF NOT EXISTS `ezy_pos_mfg_production` (
  `p_id` INT AUTO_INCREMENT PRIMARY KEY,
  `p_code` VARCHAR(30) NOT NULL,
  `p_gp_id` INT NOT NULL,
  `p_store_id` INT NOT NULL,
  `p_raw_item_id` INT NULL,
  `p_raw_qty` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `p_raw_uom` VARCHAR(30) NULL,
  `p_raw_grn_cost` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `p_material_used` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `p_subtotal` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `p_overall_total` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `p_final_bill` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `p_remaining_raw` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `p_returned_raw` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `p_damaged_raw` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `p_status` VARCHAR(30) NOT NULL DEFAULT 'Draft',
  `p_notes` TEXT NULL,
  `p_createdby` INT NULL,
  `p_createdat` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `p_completedat` DATETIME NULL,
  KEY `idx_gp` (`p_gp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Output items produced from the raw material.
CREATE TABLE IF NOT EXISTS `ezy_pos_mfg_output` (
  `o_id` INT AUTO_INCREMENT PRIMARY KEY,
  `o_p_id` INT NOT NULL,
  `o_item_id` INT NOT NULL,
  `o_material_used` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `o_qty_produced` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `o_additional` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `o_tailoring` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `o_material_cost` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `o_allocated_charge` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `o_total` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `o_grn_cost` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `o_received_qty` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `o_damaged_qty` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `o_remarks` VARCHAR(255) NULL,
  KEY `idx_p` (`o_p_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Overall production charges (Transport, Loading, Delivery, Misc...).
CREATE TABLE IF NOT EXISTS `ezy_pos_mfg_charge` (
  `c_id` INT AUTO_INCREMENT PRIMARY KEY,
  `c_p_id` INT NOT NULL,
  `c_description` VARCHAR(150) NULL,
  `c_amount` DOUBLE(15,2) NOT NULL DEFAULT 0,
  KEY `idx_p` (`c_p_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Receiving history for output items (received vs damaged).
CREATE TABLE IF NOT EXISTS `ezy_pos_mfg_receiving` (
  `r_id` INT AUTO_INCREMENT PRIMARY KEY,
  `r_p_id` INT NOT NULL,
  `r_o_id` INT NOT NULL,
  `r_item_id` INT NOT NULL,
  `r_produced` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `r_received` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `r_damaged` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `r_remarks` VARCHAR(255) NULL,
  `r_by` INT NULL,
  `r_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Damaged stock record (raw material and finished goods) for audit/reporting.
CREATE TABLE IF NOT EXISTS `ezy_pos_mfg_damaged` (
  `d_id` INT AUTO_INCREMENT PRIMARY KEY,
  `d_p_id` INT NOT NULL,
  `d_item_id` INT NOT NULL,
  `d_type` VARCHAR(20) NOT NULL,
  `d_qty` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `d_store_id` INT NULL,
  `d_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
