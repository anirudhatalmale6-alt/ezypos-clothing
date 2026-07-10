-- =====================================================================
-- Item 1: Per-module user permissions
-- =====================================================================
-- Adds fine-grained privilege columns so these modules can be completely
-- hidden from the menu AND blocked on direct URL access unless the user
-- has been granted the permission:
--   Gift Vouchers, Returns, Exchanges, Stock Transfers,
--   Customer Loyalty, Promotions, LabelJoy API.
--
-- Default value 0 = NOT granted. Admin users (user_role = 1) always have
-- full access and bypass these checks.
--
-- NOTE: run each line once. If a column already exists on your database,
-- that single line will report "Duplicate column" - it is safe to ignore
-- that one line and continue with the rest.
-- =====================================================================

ALTER TABLE `ezy_pos_privileges` ADD COLUMN `priv_giftvoucher`   TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `ezy_pos_privileges` ADD COLUMN `priv_returns`       TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `ezy_pos_privileges` ADD COLUMN `priv_exchanges`     TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `ezy_pos_privileges` ADD COLUMN `priv_stocktransfer` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `ezy_pos_privileges` ADD COLUMN `priv_loyalty`       TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `ezy_pos_privileges` ADD COLUMN `priv_promotions`    TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `ezy_pos_privileges` ADD COLUMN `priv_labeljoy`      TINYINT(1) NOT NULL DEFAULT 0;

-- Production / Tailoring privilege columns. These may already exist from an
-- earlier update - if so, these two lines will report "Duplicate column" and
-- can be safely ignored. If they do NOT exist yet, these lines create them so
-- admins can grant Production / Tailoring access to staff.
ALTER TABLE `ezy_pos_privileges` ADD COLUMN `priv_production` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `ezy_pos_privileges` ADD COLUMN `priv_tailoring`  TINYINT(1) NOT NULL DEFAULT 0;
