-- =====================================================================
-- EzyPOS v12 migration
--   Parent categories and subcategories for expenses.
--
-- SAFE TO RUN: it adds one column and nothing else. No expense, category,
-- sale, item, customer, payment, return or stock figure is edited or
-- deleted. Take a backup first anyway, as you would with any change.
--
-- Every expense category you have today becomes a PARENT category
-- (expencat_parent_id = 0). Every expense already booked keeps pointing
-- at exactly the same category it pointed at before, so nothing in the
-- Expense Report changes.
--
-- Run this whole file once on the live database.
-- =====================================================================


-- ---------------------------------------------------------------------
-- 1. PARENT CATEGORY
--
--    One self-referencing column keeps both levels in the one table.
--      0            = this row is a parent category (Transportation)
--      any other id = this row is a subcategory of that parent (Fuel)
--
--    Two levels only. The Masters screen refuses to put a subcategory
--    underneath another subcategory.
--
--    DEFAULT 0 is what makes this safe: the categories that are already
--    there become parents automatically, which is what they have always
--    behaved as.
-- ---------------------------------------------------------------------
ALTER TABLE ezy_pos_expense_cat ADD COLUMN expencat_parent_id INT NOT NULL DEFAULT 0;


-- ---------------------------------------------------------------------
-- 2. LOOKUP INDEX
--
--    The Expense screen asks "which subcategories belong to this parent?"
--    every time the parent dropdown is changed. This makes that instant
--    instead of a full table scan.
-- ---------------------------------------------------------------------
ALTER TABLE ezy_pos_expense_cat ADD INDEX idx_expencat_parent (expencat_parent_id);
