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
-- 0. CATCHING UP ON EARLIER MIGRATIONS
--
-- The older migration files were run through phpMyAdmin, and phpMyAdmin
-- STOPS at the first error. Those files begin with columns that already
-- existed, so the run stopped there and everything after it was silently
-- skipped - which is why ezy_pos_returns.ret_store_id was never created
-- even though it appears in both v4 and v8.
--
-- Everything here is repeated from v4 and v8. Anything already in place
-- reports "Duplicate column name" and is skipped, which is harmless.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS ezy_pos_returns (
    ret_id INT AUTO_INCREMENT PRIMARY KEY,
    ret_sale_id INT NOT NULL,
    ret_type ENUM('full_return','partial_return','exchange') NOT NULL DEFAULT 'partial_return',
    ret_refund_amount DECIMAL(12,2) DEFAULT 0,
    ret_exchange_amount DECIMAL(12,2) DEFAULT 0,
    ret_net_amount DECIMAL(12,2) DEFAULT 0,
    ret_reason TEXT,
    ret_created_by INT,
    ret_created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    ret_status TINYINT DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ezy_pos_return_items (
    ri_id INT AUTO_INCREMENT PRIMARY KEY,
    ri_return_id INT NOT NULL,
    ri_item_id INT NOT NULL,
    ri_item_name VARCHAR(255),
    ri_qty DECIMAL(12,2) NOT NULL,
    ri_price DECIMAL(12,2),
    ri_discount DECIMAL(12,2) DEFAULT 0,
    ri_total DECIMAL(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ezy_pos_exchange_items (
    ei_id INT AUTO_INCREMENT PRIMARY KEY,
    ei_return_id INT NOT NULL,
    ei_item_id INT NOT NULL,
    ei_item_name VARCHAR(255),
    ei_qty DECIMAL(12,2) NOT NULL,
    ei_price DECIMAL(12,2) NOT NULL,
    ei_discount DECIMAL(12,2) DEFAULT 0,
    ei_total DECIMAL(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Which branch handled the return. Without it a refund cannot be tied to a
-- branch at all, and the Cash Flow branch filter has nothing to work with.
ALTER TABLE ezy_pos_returns ADD COLUMN ret_store_id INT DEFAULT 0;

-- Return tracking on the sale itself.
ALTER TABLE ezy_pos_sale ADD COLUMN sale_return_status VARCHAR(20) DEFAULT NULL;
ALTER TABLE ezy_pos_sale ADD COLUMN sale_last_modified DATETIME DEFAULT NULL;


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
