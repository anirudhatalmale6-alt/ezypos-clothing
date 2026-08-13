-- =====================================================================
-- EzyPOS v9 migration
--   1. Per-store bill numbers, e.g. HG-B-001 (B = Battaramulla)
--   2. Store credit vs cash on returns / exchanges
--   3. Privilege columns for every remaining page
--
-- SAFE TO RUN: every statement either ADDS a column/table or fills in the
-- new columns it just added. Nothing existing is edited or deleted - no
-- sale, item, customer, payment or stock figure is touched. Take a backup
-- first anyway, as you would with any change.
--
-- Run this whole file once on the live database.
-- =====================================================================


-- ---------------------------------------------------------------------
-- 1. PER-STORE BILL NUMBERS
--
--    Printed form:  <prefix>-<store letter>-<number>     e.g.  HG-B-001
--
--    The store letter is the first letter of the store name. If two stores
--    start with the same letter the second one gets a digit after it
--    (B, B2, B3...). Codes are worked out and saved the first time a bill
--    is printed, and you can change any of them by hand afterwards in
--    ezy_pos_stores.store_bill_code.
--
--    Each store counts from 1 on its own, so Battaramulla and Kotte both
--    start at 001 and never clash.
--
--    ezy_pos_bill_counters holds one counter row per store so two tills
--    saving at the same instant can never be handed the same number.
-- ---------------------------------------------------------------------
ALTER TABLE ezy_pos_stores ADD COLUMN store_bill_code VARCHAR(10) DEFAULT NULL;

ALTER TABLE ezy_pos_sale ADD COLUMN sale_bill_seq INT DEFAULT NULL;
ALTER TABLE ezy_pos_sale ADD COLUMN sale_bill_no VARCHAR(30) DEFAULT NULL;

CREATE TABLE IF NOT EXISTS ezy_pos_bill_counters (
    bc_store_id INT NOT NULL,
    bc_last_no  INT NOT NULL DEFAULT 0,
    PRIMARY KEY (bc_store_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Give every existing sale its store's running number, oldest first, so old
-- bills reprint with a sensible number instead of a blank.
-- (Written as a plain counting subquery so it runs on MySQL 5.6/5.7/8 alike.)
UPDATE ezy_pos_sale s
SET s.sale_bill_seq = (
        SELECT COUNT(*) FROM (SELECT sale_id, sale_location FROM ezy_pos_sale) x
        WHERE x.sale_location = s.sale_location AND x.sale_id <= s.sale_id
    );

-- Seed the counters so the next new bill carries on from there.
INSERT INTO ezy_pos_bill_counters (bc_store_id, bc_last_no)
SELECT sale_location, MAX(sale_bill_seq) FROM ezy_pos_sale
WHERE sale_location IS NOT NULL
GROUP BY sale_location
ON DUPLICATE KEY UPDATE bc_last_no = VALUES(bc_last_no);

-- Two bills in the same store can never share a number.
ALTER TABLE ezy_pos_sale ADD UNIQUE INDEX idx_sale_bill_no (sale_bill_no);

-- The printed prefix. Change this one row to rename HG to anything else -
-- new bills pick it up immediately.
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
-- Created here too, so this file still runs cleanly if v8 was skipped.
CREATE TABLE IF NOT EXISTS `ezy_pos_return_payments` (
  `rp_id`         INT AUTO_INCREMENT PRIMARY KEY,
  `rp_ret_id`     INT NOT NULL,
  `rp_direction`  VARCHAR(3)     NOT NULL DEFAULT 'in',
  `rp_method`     VARCHAR(100)   NOT NULL DEFAULT 'Cash',
  `rp_reference`  VARCHAR(100)   DEFAULT NULL COMMENT 'Card machine reference / cheque no',
  `rp_amount`     DOUBLE(15,2)   NOT NULL DEFAULT 0,
  `rp_created_at` DATETIME       DEFAULT CURRENT_TIMESTAMP,
  `rp_created_by` INT            DEFAULT 0,
  KEY `idx_rp_ret_id` (`rp_ret_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE ezy_pos_returns ADD COLUMN ret_refund_mode VARCHAR(20) NOT NULL DEFAULT 'cash';
ALTER TABLE ezy_pos_return_payments ADD COLUMN rp_in_cashflow TINYINT(1) NOT NULL DEFAULT 1;


-- ---------------------------------------------------------------------
-- 3. PRIVILEGES FOR THE REMAINING PAGES
--    Everything here defaults to 0 (hidden) except for admins, who are
--    never checked against these columns. No existing user loses anything.
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
