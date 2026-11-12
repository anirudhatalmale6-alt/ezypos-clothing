-- =====================================================================
-- EzyPOS v22 migration
--   Advance Exchange: a discount on the goods COMING BACK, as well as the
--   one already there on the goods going out. Used when the goods were
--   bought at a discount, so the refund is what the customer actually paid.
--
-- SAFE TO RUN: three columns, all defaulting to nothing. Existing records
-- are unchanged. Run again and they report "Duplicate column name", which
-- is harmless.
-- =====================================================================

ALTER TABLE ezy_pos_adv_return ADD COLUMN adv_ret_discount DECIMAL(14,2) NOT NULL DEFAULT 0.00;
ALTER TABLE ezy_pos_adv_return ADD COLUMN adv_ret_discount_type VARCHAR(20) NOT NULL DEFAULT 'flat';
ALTER TABLE ezy_pos_adv_return ADD COLUMN adv_ret_discount_rate DECIMAL(14,2) NOT NULL DEFAULT 0.00;
