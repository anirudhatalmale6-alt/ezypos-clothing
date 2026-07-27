-- =====================================================================
-- Additional Changes Batch A: Production payments + manual gate pass
-- =====================================================================
-- Adds a payment ledger for the Production module (mirrors the Tailoring
-- payment workflow: Cash / Cheque, multiple payments, history, balance)
-- and paid/balance snapshot columns on the production record.
-- Plain ADD COLUMN statements (client DB compatibility). If a column
-- already exists the line errors harmlessly - run the rest.
-- =====================================================================

-- Paid / outstanding snapshot on each production
ALTER TABLE `ezy_pos_mfg_production` ADD COLUMN `p_paid` DOUBLE(15,2) NOT NULL DEFAULT 0;
ALTER TABLE `ezy_pos_mfg_production` ADD COLUMN `p_balance` DOUBLE(15,2) NOT NULL DEFAULT 0;

-- Production payment ledger (same shape as tailoring payments)
CREATE TABLE IF NOT EXISTS `ezy_pos_mfg_payment` (
  `mp_id` INT AUTO_INCREMENT PRIMARY KEY,
  `mp_p_id` INT NOT NULL,
  `mp_amount` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `mp_method` VARCHAR(30) NOT NULL DEFAULT 'Cash',
  `mp_ref` VARCHAR(120) NULL,
  `mp_created_by` INT NULL,
  `mp_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_p` (`mp_p_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
