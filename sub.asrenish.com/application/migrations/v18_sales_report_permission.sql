-- =====================================================================
-- EzyPOS v18 migration
--   The Sales Report page is now two pages:
--     * Sales Reprint - the old page, bill lookup and reprint, plus the
--       Print cash flow button. It keeps the existing permission
--       priv_re_salesReport.
--     * Sales Report  - a new page: totals, gift voucher sales, and a
--       breakdown by payment method, with date and payment filters. It
--       gets its OWN permission, so the two can be given separately.
--
-- SAFE TO RUN: adds one column and nothing else. The column starts at 0,
-- so nobody gains access to the new page until it is ticked for them.
-- Administrators see it regardless, as they do with every page.
-- Anything already in place reports "Duplicate column name" and is
-- skipped, which is harmless.
-- =====================================================================

ALTER TABLE ezy_pos_privileges ADD COLUMN priv_re_salesSummary TINYINT NOT NULL DEFAULT 0;

-- Nobody is given the new page automatically. Whoever had the old Sales
-- Report keeps Sales Reprint exactly as before, which is the page they
-- have been using; the new one is handed out deliberately.
