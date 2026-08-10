-- v8: Payments taken (or refunded) against a Return / Exchange.
--
-- Needed so that:
--   * an exchange where the new items cost MORE than the returned ones can collect
--     the difference through the same payment-method popup used on a sale, and
--   * the Cash Flow report can show that money by method, with the card reference.
--
-- Safe to run more than once: CREATE TABLE IF NOT EXISTS is idempotent.
-- The ALTER statements will report "Duplicate column name" on a second run,
-- which can be ignored.

CREATE TABLE IF NOT EXISTS `ezy_pos_return_payments` (
  `rp_id`         INT AUTO_INCREMENT PRIMARY KEY,
  `rp_ret_id`     INT NOT NULL,
  -- 'in'  = customer paid the difference (money into the till)
  -- 'out' = refund handed back to the customer (money out of the till)
  `rp_direction`  VARCHAR(3)     NOT NULL DEFAULT 'in',
  `rp_method`     VARCHAR(100)   NOT NULL DEFAULT 'Cash',
  `rp_reference`  VARCHAR(100)   DEFAULT NULL COMMENT 'Card machine reference / cheque no',
  `rp_amount`     DOUBLE(15,2)   NOT NULL DEFAULT 0,
  `rp_created_at` DATETIME       DEFAULT CURRENT_TIMESTAMP,
  `rp_created_by` INT            DEFAULT 0,
  KEY `idx_rp_ret_id` (`rp_ret_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Returns need to record which store handled them so the branch filter on the
-- Cash Flow report can include them. (Added in v4 for some installs - ignore the
-- duplicate-column error if it is already there.)
ALTER TABLE `ezy_pos_returns` ADD COLUMN `ret_store_id` INT DEFAULT 0;
