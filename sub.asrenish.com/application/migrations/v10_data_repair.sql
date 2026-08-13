-- =====================================================================
-- EzyPOS v10 - DATA REPAIR
--
-- Repairs records that were written wrong by bugs now fixed in the code.
-- Run this ONCE, AFTER v9 and after the new files are in place.
--
-- TAKE A DATABASE BACKUP FIRST. Unlike v9, this file does change existing
-- rows - that is the whole point of it - so a backup matters here.
--
-- Parts 1 to 3 are safe and recommended.
-- Part 4 changes sales figures, so it is left commented out with a SELECT
-- to look at first. Read the notes before running it.
-- =====================================================================


-- ---------------------------------------------------------------------
-- PART 1 - RETURNS THAT RECORDED A REFUND OF 0
--
-- The old code added up the wrong field when writing the return header, so
-- ezy_pos_returns.ret_refund_amount was saved as 0 on every return - which
-- is why a 5,250 return against a 7,250 exchange showed "Return Total 0"
-- and asked for the whole 7,250 instead of 2,000.
--
-- The individual item lines were saved CORRECTLY, so the true figure is
-- simply the sum of those lines. Nothing is guessed here.
-- ---------------------------------------------------------------------

-- Look first: these are the returns that will be corrected.
SELECT r.ret_id,
       r.ret_sale_id,
       r.ret_type,
       r.ret_refund_amount                 AS stored_refund_wrong,
       COALESCE(SUM(ri.ri_total), 0)       AS real_refund,
       r.ret_exchange_amount,
       r.ret_net_amount                    AS stored_net_wrong,
       COALESCE(SUM(ri.ri_total), 0) - r.ret_exchange_amount AS real_net
FROM ezy_pos_returns r
LEFT JOIN ezy_pos_return_items ri ON ri.ri_return_id = r.ret_id
GROUP BY r.ret_id, r.ret_sale_id, r.ret_type, r.ret_refund_amount,
         r.ret_exchange_amount, r.ret_net_amount
HAVING stored_refund_wrong <> real_refund;

-- Apply the correction.
UPDATE ezy_pos_returns r
JOIN (
    SELECT ri_return_id, COALESCE(SUM(ri_total), 0) AS real_refund
    FROM ezy_pos_return_items
    GROUP BY ri_return_id
) t ON t.ri_return_id = r.ret_id
SET r.ret_refund_amount = t.real_refund,
    r.ret_net_amount    = t.real_refund - r.ret_exchange_amount
WHERE r.ret_refund_amount <> t.real_refund;


-- ---------------------------------------------------------------------
-- PART 2 - RETURNS WITH NO BRANCH ON THEM
--
-- Returns taken before the branch column existed have ret_store_id = 0, so
-- they belong to no branch and disappear from every branch filter. Give
-- them the branch that sold the original bill.
-- ---------------------------------------------------------------------
UPDATE ezy_pos_returns r
JOIN ezy_pos_sale s ON s.sale_id = r.ret_sale_id
SET r.ret_store_id = s.sale_location
WHERE (r.ret_store_id IS NULL OR r.ret_store_id = 0)
  AND s.sale_location > 0;


-- ---------------------------------------------------------------------
-- PART 3 - GIFT VOUCHER SALES WITH NO BRANCH ON THEM
--
-- A voucher-only sale never recorded its branch (the branch was only picked
-- up when a stock item was added, and a voucher sale adds none). Those bills
-- were saved against branch 0. Confirmed by the client: they were sold at
-- ETHULKOTTE.
--
-- If the branch is named differently in your Stores list, change the name in
-- the two places below to match it exactly.
-- ---------------------------------------------------------------------

-- Look first: these are the bills with no branch.
SELECT sale_id, sale_date, sale_grandtotal, sale_location, sale_bill_no
FROM ezy_pos_sale
WHERE sale_location IS NULL OR sale_location = 0;

UPDATE ezy_pos_sale
SET sale_location = (SELECT store_id FROM ezy_pos_stores WHERE store_name = 'Ethulkotte' LIMIT 1)
WHERE (sale_location IS NULL OR sale_location = 0)
  AND EXISTS (SELECT 1 FROM ezy_pos_stores WHERE store_name = 'Ethulkotte');

-- Those bills now need their branch bill number (they were given none, or one
-- built from branch 0). Renumber them at the end of that branch's sequence.
SET @st = (SELECT store_id FROM ezy_pos_stores WHERE store_name = 'Ethulkotte' LIMIT 1);

UPDATE ezy_pos_sale s
SET s.sale_bill_seq = (
        SELECT COUNT(*) FROM (SELECT sale_id, sale_location FROM ezy_pos_sale) x
        WHERE x.sale_location = s.sale_location AND x.sale_id <= s.sale_id
    )
WHERE s.sale_location = @st;

UPDATE ezy_pos_sale SET sale_bill_no = NULL WHERE sale_location = @st;

INSERT INTO ezy_pos_bill_counters (bc_store_id, bc_last_no)
SELECT sale_location, MAX(sale_bill_seq) FROM ezy_pos_sale
WHERE sale_location = @st
GROUP BY sale_location
ON DUPLICATE KEY UPDATE bc_last_no = VALUES(bc_last_no);
-- sale_bill_no is left empty on purpose: the bill number is then rebuilt from
-- the branch letter and the running number whenever the bill is opened, so it
-- comes out as HG-E-001 and so on.


-- ---------------------------------------------------------------------
-- PART 4 - OPTIONAL, READ THIS FIRST
--
-- On a straight return (no exchange) the old code worked the net out as 0,
-- so it never reduced the original bill's grand total. Those bills still
-- show their full value in the Sales Report even though goods came back.
--
-- This is left commented out because it CHANGES YOUR SALES FIGURES for past
-- periods. Run the SELECT, check the list against what you know, and only
-- then uncomment the UPDATE.
--
-- It only touches bills where the whole refund is still sitting in the
-- grand total, and never takes a bill below zero.
-- ---------------------------------------------------------------------
SELECT s.sale_id, s.sale_bill_no, s.sale_date,
       s.sale_grandtotal        AS current_total,
       SUM(r.ret_refund_amount) AS refunded,
       s.sale_grandtotal - SUM(r.ret_refund_amount) AS total_after
FROM ezy_pos_sale s
JOIN ezy_pos_returns r ON r.ret_sale_id = s.sale_id AND r.ret_status = 1
WHERE r.ret_type <> 'exchange'
GROUP BY s.sale_id, s.sale_bill_no, s.sale_date, s.sale_grandtotal
HAVING refunded > 0 AND total_after >= 0;

-- UPDATE ezy_pos_sale s
-- JOIN (
--     SELECT ret_sale_id, SUM(ret_refund_amount) AS refunded
--     FROM ezy_pos_returns
--     WHERE ret_status = 1 AND ret_type <> 'exchange'
--     GROUP BY ret_sale_id
-- ) t ON t.ret_sale_id = s.sale_id
-- SET s.sale_grandtotal = s.sale_grandtotal - t.refunded
-- WHERE s.sale_grandtotal - t.refunded >= 0;
