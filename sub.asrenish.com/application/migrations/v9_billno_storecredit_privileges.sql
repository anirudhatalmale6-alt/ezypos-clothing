-- =====================================================================
-- EzyPOS v9 migration
--   1. Per-store bill numbers with the "HG" prefix
--   2. Store credit vs cash on returns / exchanges
--   3. Privilege columns for every remaining page
-- Run this whole file once on the live database.
-- =====================================================================


-- ---------------------------------------------------------------------
-- 1. PER-STORE BILL NUMBERS
--    sale_bill_seq  = the store's own running number (1, 2, 3 ... per store)
--    sale_bill_no   = the printed bill number, e.g. HG1-0001
--    ezy_pos_bill_counters holds one counter row per store so two tills
--    saving at the same instant can never be handed the same number.
-- ---------------------------------------------------------------------
ALTER TABLE ezy_pos_sale ADD COLUMN sale_bill_seq INT DEFAULT NULL;
ALTER TABLE ezy_pos_sale ADD COLUMN sale_bill_no VARCHAR(30) DEFAULT NULL;

CREATE TABLE IF NOT EXISTS ezy_pos_bill_counters (
    bc_store_id INT NOT NULL,
    bc_last_no  INT NOT NULL DEFAULT 0,
    PRIMARY KEY (bc_store_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Backfill: give every existing sale a per-store number, oldest first,
-- so historical bills keep a stable identity instead of showing blank.
-- (Written as a plain counting subquery so it runs on MySQL 5.6/5.7/8 alike.)
UPDATE ezy_pos_sale s
SET s.sale_bill_seq = (
        SELECT COUNT(*) FROM (SELECT sale_id, sale_location FROM ezy_pos_sale) x
        WHERE x.sale_location = s.sale_location AND x.sale_id <= s.sale_id
    );

UPDATE ezy_pos_sale
SET sale_bill_no = CONCAT('HG', sale_location, '-', LPAD(sale_bill_seq, 4, '0'))
WHERE sale_bill_seq IS NOT NULL;

-- Seed the counters from what was just backfilled.
INSERT INTO ezy_pos_bill_counters (bc_store_id, bc_last_no)
SELECT sale_location, MAX(sale_bill_seq) FROM ezy_pos_sale
WHERE sale_location IS NOT NULL
GROUP BY sale_location
ON DUPLICATE KEY UPDATE bc_last_no = VALUES(bc_last_no);

-- Two sales in the same store can never share a number.
ALTER TABLE ezy_pos_sale ADD UNIQUE INDEX idx_sale_bill_no (sale_bill_no);

-- The printed prefix. Change this one row to rename HG to anything else -
-- new bills pick it up immediately, old bills keep the number they were given.
INSERT INTO ezy_pos_config2 (config_key, config_value)
SELECT 'bill_prefix', 'HG'
WHERE NOT EXISTS (SELECT 1 FROM ezy_pos_config2 WHERE config_key = 'bill_prefix');


-- ---------------------------------------------------------------------
-- 2. STORE CREDIT ON RETURNS
--    ret_refund_mode  'cash'         = money handed back over the counter
--                     'store_credit' = left on the customer's account
--    rp_in_cashflow   0 = this line is NOT real money movement, so the
--                     Cash Flow report must ignore it.
-- ---------------------------------------------------------------------
ALTER TABLE ezy_pos_returns ADD COLUMN ret_refund_mode VARCHAR(20) NOT NULL DEFAULT 'cash';
ALTER TABLE ezy_pos_return_payments ADD COLUMN rp_in_cashflow TINYINT(1) NOT NULL DEFAULT 1;


-- ---------------------------------------------------------------------
-- 3. PRIVILEGES FOR THE REMAINING PAGES
--    Everything here defaults to 0 (hidden) except for admins, who are
--    never checked against these columns.
-- ---------------------------------------------------------------------
ALTER TABLE ezy_pos_privileges ADD COLUMN priv_cusreturn        TINYINT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_privileges ADD COLUMN priv_supreturn        TINYINT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_privileges ADD COLUMN priv_paymentmethods   TINYINT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_privileges ADD COLUMN priv_deliverycompany  TINYINT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_privileges ADD COLUMN priv_warehouse        TINYINT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_privileges ADD COLUMN priv_storeitems       TINYINT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_privileges ADD COLUMN priv_retailpos        TINYINT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_privileges ADD COLUMN priv_re_commission    TINYINT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_privileges ADD COLUMN priv_re_cashflow      TINYINT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_privileges ADD COLUMN priv_re_itemsales     TINYINT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_privileges ADD COLUMN priv_re_production    TINYINT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_privileges ADD COLUMN priv_gatepass         TINYINT NOT NULL DEFAULT 0;
