-- =====================================================================
-- Additional Changes Batch B: Tailor selection
-- =====================================================================
-- Adds the "Is Tailor" flag to suppliers and a tailor reference on both
-- Production records and Tailoring orders. Plain ADD COLUMN statements
-- (client DB compatibility) - a duplicate-column line errors harmlessly.
-- =====================================================================

-- Supplier "Is Tailor" flag (tailors are picked from the supplier list)
ALTER TABLE `ezy_pos_suppliers` ADD COLUMN `sup_is_tailor` TINYINT(1) NOT NULL DEFAULT 0;

-- Tailor selected for a production (Supplier where Is Tailor = 1)
ALTER TABLE `ezy_pos_mfg_production` ADD COLUMN `p_tailor_id` INT NULL;

-- Tailor assigned to a tailoring order (assigned later, during processing)
ALTER TABLE `ezy_pos_prodsale` ADD COLUMN `prodsale_tailor_id` INT NULL;
