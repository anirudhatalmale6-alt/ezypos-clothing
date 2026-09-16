-- v16 - Advance Return becomes Advance Exchange
--
-- The page already took goods back. Now the customer can take goods away in
-- their place, and pay the difference by any payment method or with a gift
-- voucher, the same way the Sales window works.
--
-- Nothing already recorded changes. An existing Advance Return is simply an
-- exchange with no outgoing items, which is exactly what these defaults say.
--
-- Safe to run twice: each ALTER fails with "Duplicate column name" on the
-- second run and nothing else depends on it.

-- ---------------------------------------------------------------- header
-- What the customer took away, and what that left to settle.
ALTER TABLE ezy_pos_adv_return ADD COLUMN adv_exchange_total DECIMAL(14,2) NOT NULL DEFAULT 0.00;
-- Positive: the customer owes us. Negative: we owe the customer.
ALTER TABLE ezy_pos_adv_return ADD COLUMN adv_net_amount DECIMAL(14,2) NOT NULL DEFAULT 0.00;
ALTER TABLE ezy_pos_adv_return ADD COLUMN adv_type VARCHAR(20) NOT NULL DEFAULT 'return';

-- ------------------------------------------------------- outgoing items
-- The goods going OUT. Kept in its own table rather than a flag on the
-- existing one, so a returned line and a sold line can never be confused by
-- a report that forgets to check the flag.
CREATE TABLE IF NOT EXISTS ezy_pos_adv_exchange_item (
  adve_id        INT NOT NULL AUTO_INCREMENT,
  adve_adv_id    INT NOT NULL,
  adve_item_id   INT NOT NULL,
  adve_item_code VARCHAR(100) NOT NULL DEFAULT '',
  adve_item_name VARCHAR(255) NOT NULL DEFAULT '',
  adve_qty       DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  adve_price     DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  adve_total     DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (adve_id),
  KEY idx_adve_adv (adve_adv_id),
  KEY idx_adve_item (adve_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------- payments
-- How the difference was settled. One row per method, so a customer can pay
-- part cash and part card exactly as they can on a sale. Direction says which
-- way the money went, because an exchange can end either way.
CREATE TABLE IF NOT EXISTS ezy_pos_adv_payment (
  advp_id        INT NOT NULL AUTO_INCREMENT,
  advp_adv_id    INT NOT NULL,
  advp_method    VARCHAR(50) NOT NULL DEFAULT 'Cash',
  advp_reference VARCHAR(100) NOT NULL DEFAULT '',   -- card machine ref / cheque no / voucher card
  advp_amount    DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  advp_direction VARCHAR(4) NOT NULL DEFAULT 'in',   -- in = customer paid, out = we refunded
  advp_gc_id     INT NULL,                           -- the gift card, when paid by voucher
  advp_created_at DATETIME NOT NULL,
  PRIMARY KEY (advp_id),
  KEY idx_advp_adv (advp_adv_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Everything already on the table is a plain return: nothing went out, and the
-- net is what was refunded.
UPDATE ezy_pos_adv_return SET adv_type = 'return', adv_net_amount = -adv_total
 WHERE adv_net_amount = 0 AND adv_total > 0;
