-- =====================================================================
-- EzyPOS v19 migration
--   The Super Admin feature switches.
--
-- The same software is sold to different kinds of business, so every
-- feature now has a switch deciding whether this shop gets it. Nothing
-- is deleted by a switch - the tables, the rows and the history all stay
-- exactly where they are, and switching back on restores the feature as
-- it was.
--
-- SAFE TO RUN: this only writes settings rows into ezy_pos_config2, the
-- key/value table the shop name and bill prefix already live in. No new
-- table, no column added to anything, no existing row touched.
--
-- EVERY DEFAULT BELOW IS WHAT THE SYSTEM DOES TODAY. Running this
-- changes nothing about how the shop works. The two that start at 0 -
-- direct GRN and single location - start there precisely because today
-- the goods go through the warehouse and the screens ask which branch.
--
-- Run it twice and nothing happens the second time: each row is only
-- written if it is not already there, so a setting you have changed is
-- never reset by re-running this.
-- =====================================================================

INSERT INTO ezy_pos_config2 (config_key, config_value)
SELECT * FROM (SELECT 'feature_grn_direct_to_store' AS k, '0' AS v) AS t
WHERE NOT EXISTS (SELECT 1 FROM ezy_pos_config2 WHERE config_key = 'feature_grn_direct_to_store');

INSERT INTO ezy_pos_config2 (config_key, config_value)
SELECT * FROM (SELECT 'feature_single_location' AS k, '0' AS v) AS t
WHERE NOT EXISTS (SELECT 1 FROM ezy_pos_config2 WHERE config_key = 'feature_single_location');

INSERT INTO ezy_pos_config2 (config_key, config_value)
SELECT * FROM (SELECT 'feature_production' AS k, '1' AS v) AS t
WHERE NOT EXISTS (SELECT 1 FROM ezy_pos_config2 WHERE config_key = 'feature_production');

INSERT INTO ezy_pos_config2 (config_key, config_value)
SELECT * FROM (SELECT 'feature_tailoring' AS k, '1' AS v) AS t
WHERE NOT EXISTS (SELECT 1 FROM ezy_pos_config2 WHERE config_key = 'feature_tailoring');

INSERT INTO ezy_pos_config2 (config_key, config_value)
SELECT * FROM (SELECT 'feature_loyalty' AS k, '1' AS v) AS t
WHERE NOT EXISTS (SELECT 1 FROM ezy_pos_config2 WHERE config_key = 'feature_loyalty');

INSERT INTO ezy_pos_config2 (config_key, config_value)
SELECT * FROM (SELECT 'feature_labeljoy' AS k, '1' AS v) AS t
WHERE NOT EXISTS (SELECT 1 FROM ezy_pos_config2 WHERE config_key = 'feature_labeljoy');

INSERT INTO ezy_pos_config2 (config_key, config_value)
SELECT * FROM (SELECT 'feature_stocktransfer' AS k, '1' AS v) AS t
WHERE NOT EXISTS (SELECT 1 FROM ezy_pos_config2 WHERE config_key = 'feature_stocktransfer');

INSERT INTO ezy_pos_config2 (config_key, config_value)
SELECT * FROM (SELECT 'feature_supplier_return' AS k, '1' AS v) AS t
WHERE NOT EXISTS (SELECT 1 FROM ezy_pos_config2 WHERE config_key = 'feature_supplier_return');

INSERT INTO ezy_pos_config2 (config_key, config_value)
SELECT * FROM (SELECT 'feature_delivery' AS k, '1' AS v) AS t
WHERE NOT EXISTS (SELECT 1 FROM ezy_pos_config2 WHERE config_key = 'feature_delivery');
