-- Fix: flat discount amounts were being clamped to 999.99 because the discount
-- columns were DOUBLE(5,2) (max 999.99). Widen them to hold real amounts, and add
-- the discount_type columns so receipts can show a % sign only for percentage discounts.
--
-- Safe to run more than once: the MODIFY statements are idempotent; the ADD COLUMN
-- statements will report "Duplicate column name" on a second run, which can be ignored.

ALTER TABLE ezy_pos_sale       MODIFY sale_discount     DOUBLE(15,2) DEFAULT 0;
ALTER TABLE ezy_pos_sale_item  MODIFY saleitem_discount DOUBLE(15,2) DEFAULT 0;
ALTER TABLE ezy_pos_grns       MODIFY grn_discount      DOUBLE(15,2) DEFAULT 0;
ALTER TABLE ezy_pos_grn_item   MODIFY grnitm_discount   DOUBLE(15,2) DEFAULT 0;

ALTER TABLE ezy_pos_sale       ADD COLUMN sale_discount_type     VARCHAR(20) DEFAULT 'percentage';
ALTER TABLE ezy_pos_sale_item  ADD COLUMN saleitem_discount_type VARCHAR(20) DEFAULT 'percentage';
ALTER TABLE ezy_pos_grns       ADD COLUMN grn_discount_type      VARCHAR(20) DEFAULT 'percentage';
ALTER TABLE ezy_pos_grn_item   ADD COLUMN grnitm_discount_type   VARCHAR(20) DEFAULT 'percentage';
