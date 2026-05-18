-- Returns & Exchanges Module
-- EzyPOS v4 Migration
-- Run this SQL on the ezypos database

CREATE TABLE IF NOT EXISTS ezy_pos_returns (
    ret_id INT AUTO_INCREMENT PRIMARY KEY,
    ret_sale_id INT NOT NULL,
    ret_type ENUM('full_return','partial_return','exchange') NOT NULL DEFAULT 'partial_return',
    ret_refund_amount DECIMAL(12,2) DEFAULT 0,
    ret_exchange_amount DECIMAL(12,2) DEFAULT 0,
    ret_net_amount DECIMAL(12,2) DEFAULT 0,
    ret_reason TEXT,
    ret_created_by INT,
    ret_created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    ret_status TINYINT DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ezy_pos_return_items (
    ri_id INT AUTO_INCREMENT PRIMARY KEY,
    ri_return_id INT NOT NULL,
    ri_item_id INT NOT NULL,
    ri_item_name VARCHAR(255),
    ri_qty DECIMAL(12,2) NOT NULL,
    ri_price DECIMAL(12,2),
    ri_discount DECIMAL(12,2) DEFAULT 0,
    ri_total DECIMAL(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ezy_pos_exchange_items (
    ei_id INT AUTO_INCREMENT PRIMARY KEY,
    ei_return_id INT NOT NULL,
    ei_item_id INT NOT NULL,
    ei_item_name VARCHAR(255),
    ei_qty DECIMAL(12,2) NOT NULL,
    ei_price DECIMAL(12,2) NOT NULL,
    ei_discount DECIMAL(12,2) DEFAULT 0,
    ei_total DECIMAL(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Add return tracking columns to sale table
-- NOTE: Some MySQL versions (< 8.0.1) do not support IF NOT EXISTS on ALTER TABLE.
-- If you get a syntax error, run the ALTER statements individually and
-- ignore "Duplicate column name" errors if the columns already exist.
ALTER TABLE ezy_pos_sale ADD COLUMN IF NOT EXISTS sale_return_status VARCHAR(20) DEFAULT NULL;
ALTER TABLE ezy_pos_sale ADD COLUMN IF NOT EXISTS sale_last_modified DATETIME DEFAULT NULL;
