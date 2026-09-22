-- =====================================================================
-- EzyPOS v17 migration
--   A discount on an Advance Exchange, and a discount on a tailoring
--   order. Both take either a flat amount or a percentage, exactly like
--   the discount on the Sales window.
--
-- SAFE TO RUN: every statement only ADDS a column and gives it a default
-- of zero. No existing exchange, order, payment or stock figure is read
-- or changed. Anything already in place reports "Duplicate column name"
-- and is skipped, which is harmless.
-- =====================================================================

-- ---------------------------------------------------------------------
-- 1. Advance Exchange
--    adv_discount      the amount taken off, ALWAYS in rupees. A
--                      percentage is worked out and stored as rupees so
--                      that a later change to a price can never quietly
--                      change what the customer was charged.
--    adv_discount_type what was typed - 'flat' or 'percentage'. Kept
--                      only so the slip can say "10% off" rather than
--                      just the rupee figure.
--    adv_discount_rate the number that was typed, when it was a
--                      percentage.
-- ---------------------------------------------------------------------
ALTER TABLE ezy_pos_adv_return ADD COLUMN adv_discount      DECIMAL(14,2) NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_adv_return ADD COLUMN adv_discount_type VARCHAR(12)   NOT NULL DEFAULT 'flat';
ALTER TABLE ezy_pos_adv_return ADD COLUMN adv_discount_rate DECIMAL(9,2)  NOT NULL DEFAULT 0;

-- ---------------------------------------------------------------------
-- 2. Tailoring orders
--    Same three columns, same meaning.
-- ---------------------------------------------------------------------
ALTER TABLE ezy_pos_prodsale ADD COLUMN prodsale_discount      DECIMAL(14,2) NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_prodsale ADD COLUMN prodsale_discount_type VARCHAR(12)   NOT NULL DEFAULT 'flat';
ALTER TABLE ezy_pos_prodsale ADD COLUMN prodsale_discount_rate DECIMAL(9,2)  NOT NULL DEFAULT 0;
