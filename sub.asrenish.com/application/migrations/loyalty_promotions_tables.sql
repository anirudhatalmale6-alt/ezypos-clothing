-- =====================================================================
-- Point 8 (Customer Loyalty) + Point 9 (Promotions) migration
-- Run this once on the EzyPOS database. All statements are additive and
-- safe to run on the existing database (IF NOT EXISTS / nullable columns).
-- =====================================================================

-- ---------- LOYALTY ----------

-- Points balance held on each customer (running balance)
ALTER TABLE ezy_pos_customers
    ADD COLUMN cus_loyalty_points DOUBLE(15,2) DEFAULT 0.00;

-- Every earn / redeem / manual adjustment is recorded here
CREATE TABLE IF NOT EXISTS ezy_pos_loyalty_ledger (
    ll_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    ll_cus_id INT(11) NOT NULL,
    ll_sale_id INT(11) DEFAULT NULL,
    ll_type ENUM('earn','redeem','adjust') NOT NULL,
    ll_points DOUBLE(15,2) NOT NULL,          -- +ve for earn/adjust-up, -ve for redeem
    ll_amount DOUBLE(15,2) DEFAULT 0.00,       -- currency value tied to the row
    ll_balance_after DOUBLE(15,2) DEFAULT 0.00,
    ll_note VARCHAR(255) DEFAULT '',
    ll_createdby INT(11) DEFAULT NULL,
    ll_createdat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ll_cus(ll_cus_id),
    INDEX idx_ll_sale(ll_sale_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---------- PROMOTIONS ----------

CREATE TABLE IF NOT EXISTS ezy_pos_promotions (
    promo_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    promo_name VARCHAR(150) NOT NULL,
    promo_type ENUM('percentage','fixed') NOT NULL DEFAULT 'percentage',
    promo_value DOUBLE(15,2) NOT NULL DEFAULT 0.00,
    promo_scope ENUM('bill','item','category','payment') NOT NULL DEFAULT 'bill',
    promo_target_id INT(11) DEFAULT NULL,      -- item / category / payment-method id (NULL for bill)
    promo_min_bill DOUBLE(15,2) DEFAULT 0.00,   -- minimum subtotal to qualify
    promo_start_date DATE DEFAULT NULL,
    promo_end_date DATE DEFAULT NULL,
    promo_priority INT(11) DEFAULT 0,
    promo_auto TINYINT(1) DEFAULT 1,            -- 1 = auto-apply in sales
    promo_status TINYINT(1) DEFAULT 1,          -- 1 = active
    promo_createdby INT(11) DEFAULT NULL,
    promo_createdat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_promo_status(promo_status),
    INDEX idx_promo_scope(promo_scope)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Which promotions were applied to each sale (audit + reporting)
CREATE TABLE IF NOT EXISTS ezy_pos_sale_promotions (
    spr_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    spr_sale_id INT(11) NOT NULL,
    spr_promo_id INT(11) DEFAULT NULL,
    spr_promo_name VARCHAR(150) DEFAULT '',
    spr_scope VARCHAR(30) DEFAULT '',
    spr_discount DOUBLE(15,2) DEFAULT 0.00,
    spr_createdat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_spr_sale(spr_sale_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Record loyalty redemption / promo discount on the sale itself (optional columns)
ALTER TABLE ezy_pos_sale ADD COLUMN sale_loyalty_redeemed DOUBLE(15,2) DEFAULT 0.00;
ALTER TABLE ezy_pos_sale ADD COLUMN sale_loyalty_points_used DOUBLE(15,2) DEFAULT 0.00;
ALTER TABLE ezy_pos_sale ADD COLUMN sale_loyalty_points_earned DOUBLE(15,2) DEFAULT 0.00;
ALTER TABLE ezy_pos_sale ADD COLUMN sale_promo_discount DOUBLE(15,2) DEFAULT 0.00;
