-- =====================================================================
-- EzyPOS v21 migration (November 2026 batch)
--
-- Supplier returns keep their GRN and store, so a return can be opened
-- and edited again (until now a return was never tied to its GRN).
--
-- SAFE TO RUN: new columns and tables only, all with defaults. Nothing
-- existing is changed or deleted. Run it again and anything already in
-- place reports "Duplicate column name" / "already exists" and is skipped,
-- which is harmless.
-- =====================================================================

-- Supplier returns: which GRN, which store, a note, when last edited.
ALTER TABLE ezy_pos_sup_return ADD COLUMN suprtrn_grnID INT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_sup_return ADD COLUMN suprtrn_store_id INT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_sup_return ADD COLUMN suprtrn_note VARCHAR(255) NULL;

-- Customer payments: one receipt per payment, the methods it was paid by
-- (cash, cheque, or any payment method from Masters), and which bills /
-- opening balance it settled. The discount settles part of what is owed
-- without any money changing hands.
CREATE TABLE IF NOT EXISTS ezy_pos_cus_receipt (
    rcpt_id         INT AUTO_INCREMENT PRIMARY KEY,
    rcpt_cus_id     INT NOT NULL,
    rcpt_date       DATE NOT NULL,
    rcpt_store_id   INT NOT NULL DEFAULT 0,
    rcpt_amount     DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    rcpt_discount   DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    rcpt_note       VARCHAR(255) NULL,
    rcpt_status     TINYINT NOT NULL DEFAULT 1,
    rcpt_createdby  INT NOT NULL DEFAULT 0,
    rcpt_createdat  DATETIME NOT NULL,
    KEY idx_rcpt_cus (rcpt_cus_id),
    KEY idx_rcpt_date (rcpt_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ezy_pos_cus_receipt_pay (
    rp_id          INT AUTO_INCREMENT PRIMARY KEY,
    rp_rcpt_id     INT NOT NULL,
    rp_method      VARCHAR(20) NOT NULL,
    rp_pm_id       INT NOT NULL DEFAULT 0,
    rp_amount      DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    rp_reference   VARCHAR(100) NULL,
    rp_bank        VARCHAR(100) NULL,
    rp_cheque_date DATE NULL,
    KEY idx_rp_rcpt (rp_rcpt_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ezy_pos_cus_receipt_alloc (
    ra_id       INT AUTO_INCREMENT PRIMARY KEY,
    ra_rcpt_id  INT NOT NULL,
    ra_sale_id  INT NOT NULL DEFAULT 0,
    ra_amount   DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    KEY idx_ra_rcpt (ra_rcpt_id),
    KEY idx_ra_sale (ra_sale_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- A cheque taken on a customer payment points at its receipt, not at a bill.
ALTER TABLE ezy_pos_cus_cheque ADD COLUMN cus_cheque_rcpt_id INT NOT NULL DEFAULT 0;

-- Sales representatives: a master list, picked on the sale window. The bill
-- shows the rep's name instead of the cashier's login name.
CREATE TABLE IF NOT EXISTS ezy_pos_sales_reps (
    rep_id        INT AUTO_INCREMENT PRIMARY KEY,
    rep_name      VARCHAR(120) NOT NULL,
    rep_phone     VARCHAR(40) NULL,
    rep_status    TINYINT NOT NULL DEFAULT 1,
    rep_createdat DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE ezy_pos_sale ADD COLUMN sale_rep_id INT NOT NULL DEFAULT 0;
