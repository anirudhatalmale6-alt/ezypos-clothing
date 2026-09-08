-- v15 - the Advance Return module
--
-- A counter return that does not start from a bill. The customer walks in with
-- goods, the cashier scans them, sets the quantities, and refunds. It is kept
-- completely separate from the existing Returns / Exchange module: its own
-- tables, its own page, its own permission. Nothing about those is touched.
--
-- Safe to run twice. The tables are IF NOT EXISTS and the permission column
-- fails with "Duplicate column name" without affecting anything else.

CREATE TABLE IF NOT EXISTS ezy_pos_adv_return (
  adv_id            INT NOT NULL AUTO_INCREMENT,
  adv_ref_no        VARCHAR(30)  NOT NULL DEFAULT '',   -- AR-1-0001, per branch
  adv_seq           INT          NOT NULL DEFAULT 0,
  adv_store_id      INT          NOT NULL DEFAULT 0,    -- branch taking the goods back
  adv_cus_id        INT          NULL,                  -- optional; required for store credit
  adv_bill_ref      VARCHAR(50)  NOT NULL DEFAULT '',   -- the bill number if the customer has it
  adv_sale_id       INT          NULL,                  -- resolved sale, when the bill was found
  adv_without_bill  TINYINT(1)   NOT NULL DEFAULT 1,    -- 1 = no bill produced
  adv_total         DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  adv_refund_mode   VARCHAR(20)  NOT NULL DEFAULT 'cash', -- cash | store_credit
  adv_restock       TINYINT(1)   NOT NULL DEFAULT 1,    -- 0 = damaged, do not put back
  adv_reason        VARCHAR(255) NOT NULL DEFAULT '',
  adv_status        TINYINT(1)   NOT NULL DEFAULT 1,    -- 0 = cancelled
  adv_created_by    INT          NOT NULL DEFAULT 0,
  adv_created_at    DATETIME     NOT NULL,
  PRIMARY KEY (adv_id),
  KEY idx_adv_store (adv_store_id),
  KEY idx_adv_created (adv_created_at),
  KEY idx_adv_sale (adv_sale_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ezy_pos_adv_return_item (
  advi_id        INT NOT NULL AUTO_INCREMENT,
  advi_adv_id    INT NOT NULL,
  advi_item_id   INT NOT NULL,
  advi_item_code VARCHAR(100) NOT NULL DEFAULT '',
  advi_item_name VARCHAR(255) NOT NULL DEFAULT '',
  advi_qty       DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  advi_price     DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  advi_total     DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (advi_id),
  KEY idx_advi_adv (advi_adv_id),
  KEY idx_advi_item (advi_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Its own permission, separate from priv_returns and priv_exchanges on purpose:
-- a user given Advance Return does not thereby get the Returns screen, and vice
-- versa.
ALTER TABLE ezy_pos_privileges ADD COLUMN priv_advreturn TINYINT(1) NOT NULL DEFAULT 0;
