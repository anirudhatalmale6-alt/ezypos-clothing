-- =====================================================================
-- EzyPOS v11 migration
--   Discount on exchanges - the same discount the Sales window has.
--
-- SAFE TO RUN: every statement only ADDS a column. Nothing existing is
-- edited or deleted - no sale, item, customer, payment, return or stock
-- figure is touched. Take a backup first anyway, as you would with any
-- change.
--
-- The system works with or without this file. Without it the discount
-- still comes off the exchange total correctly; these columns only record
-- HOW MUCH was taken off and whether it was a percentage or a flat
-- amount, so a past exchange can be read back later.
--
-- Run this whole file once on the live database.
-- =====================================================================


-- ---------------------------------------------------------------------
-- 1. DISCOUNT ON THE EXCHANGE AS A WHOLE
--
--    ret_exchange_amount is already NET of this. These two columns are
--    the record of what was given away, the same as sale_discount on a
--    normal bill.
-- ---------------------------------------------------------------------
ALTER TABLE ezy_pos_returns ADD COLUMN ret_exchange_discount DECIMAL(12,2) NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_returns ADD COLUMN ret_exchange_discount_type VARCHAR(20) NOT NULL DEFAULT 'flat';


-- ---------------------------------------------------------------------
-- 2. DISCOUNT ON A SINGLE EXCHANGE LINE
--
--    ei_discount already existed but was always written as 0, because the
--    exchange screen had nowhere to type a discount. It is now filled in.
--    This column says whether that number is a percentage or an amount,
--    exactly as on a sale line.
-- ---------------------------------------------------------------------
ALTER TABLE ezy_pos_exchange_items ADD COLUMN ei_discount_type VARCHAR(20) NOT NULL DEFAULT 'percentage';
