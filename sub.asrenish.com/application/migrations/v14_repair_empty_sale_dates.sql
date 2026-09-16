-- v14 - put a real date back on the bills that were saved without one
--
-- THE FAULT
-- On the sales screen the date was only read inside the add-item form. A bill
-- with no stock line on it - a gift voucher sold on its own is the usual case -
-- never ran that form, so the date was posted empty and MySQL stored
-- 0000-00-00.
--
-- Nothing warns you, because the bill saves and prints. But every report filters
-- on "date BETWEEN from AND to", and 0000-00-00 is inside no range anyone can
-- pick. So the sale is invisible in the Cash Flow report, in Today's Summary and
-- in Payments Received - for good. The Sales Report reads sale_createdat
-- instead, which is why the same sale shows up there and nowhere else.
--
-- THE REPAIR
-- sale_createdat is a timestamp written by the database itself, so it is right
-- even when sale_date is not. These statements copy the date part of it onto the
-- bill and onto the payment rows that were filed under the same empty date.
--
-- It only touches rows that are already broken. A bill with a real date on it is
-- left exactly as it is. Safe to run twice - the second run finds nothing to do.
--
-- NOTE ON THE ZERO DATE
-- On MySQL 8 the server usually runs with NO_ZERO_DATE, and then even WRITING
-- '0000-00-00' in a WHERE clause is refused with "Incorrect date value" - the
-- repair fails before it starts. So the broken rows are found with
-- YEAR(sale_date) < 2000, which never mentions the value at all, and the
-- session is relaxed for the length of this file.

SET @OLD_SQL_MODE = @@SESSION.sql_mode;
SET SESSION sql_mode = '';

-- 1. the bills themselves
UPDATE ezy_pos_sale
   SET sale_date = DATE(sale_createdat)
 WHERE (sale_date IS NULL OR YEAR(sale_date) < 2000)
   AND sale_createdat IS NOT NULL
   AND YEAR(sale_createdat) >= 2000;

-- 2. the cash / credit line filed against those bills
UPDATE ezy_pos_cus_payment p
  JOIN ezy_pos_sale s ON s.sale_id = p.cus_pay_saleid
   SET p.cus_pay_paiddate = s.sale_date
 WHERE (p.cus_pay_paiddate IS NULL OR YEAR(p.cus_pay_paiddate) < 2000)
   AND YEAR(s.sale_date) >= 2000;

-- 3. the payment log, which is what "Payments Received" adds up
UPDATE ezy_pos_cus_paymnt_log l
  JOIN ezy_pos_sale s ON s.sale_id = l.pymntlog_saleid
   SET l.pymntlog_date = s.sale_date
 WHERE (l.pymntlog_date IS NULL OR YEAR(l.pymntlog_date) < 2000)
   AND YEAR(s.sale_date) >= 2000;

-- 4. cheques taken on those bills
UPDATE ezy_pos_cus_cheque c
  JOIN ezy_pos_sale s ON s.sale_id = c.cus_cheque_saleid
   SET c.cus_cheque_givendate = s.sale_date
 WHERE (c.cus_cheque_givendate IS NULL OR YEAR(c.cus_cheque_givendate) < 2000)
   AND YEAR(s.sale_date) >= 2000;

SET SESSION sql_mode = @OLD_SQL_MODE;
