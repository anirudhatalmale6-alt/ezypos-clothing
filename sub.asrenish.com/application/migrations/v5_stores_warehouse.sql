-- Store/Warehouse Setup Migration
-- EzyPOS v5
-- Run this SQL on the ezypos database

-- Add warehouse flag column to stores table
ALTER TABLE ezy_pos_stores ADD COLUMN store_is_warehouse TINYINT DEFAULT 0;

-- Insert default warehouse and 2 stores (if they don't already exist)
-- Check current count first; only insert if table has no active stores
-- Run these manually - adjust names/addresses as needed:

INSERT INTO ezy_pos_stores (store_name, store_address, store_tel, store_is_warehouse, store_status)
VALUES ('Main Warehouse', 'Warehouse Address', '', 1, 1);

INSERT INTO ezy_pos_stores (store_name, store_address, store_tel, store_is_warehouse, store_status)
VALUES ('Store A', 'Store A Address', '', 0, 1);

INSERT INTO ezy_pos_stores (store_name, store_address, store_tel, store_is_warehouse, store_status)
VALUES ('Store B', 'Store B Address', '', 0, 1);

-- NOTE: If you already have stores in the table, you can skip the INSERT statements above.
-- Instead, just update one of your existing stores to be the warehouse:
-- UPDATE ezy_pos_stores SET store_is_warehouse = 1 WHERE store_id = <your_warehouse_store_id>;
