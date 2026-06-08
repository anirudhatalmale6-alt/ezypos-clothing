-- Stock Transfer Module Migration
-- EzyPOS v6
-- Run this SQL on the ezypos database

-- Transfer header table
CREATE TABLE IF NOT EXISTS ezy_pos_stock_transfer (
    transfer_id INT AUTO_INCREMENT PRIMARY KEY,
    transfer_from_store INT NOT NULL,
    transfer_to_store INT NOT NULL,
    transfer_status ENUM('Pending','Accepted','Rejected') DEFAULT 'Pending',
    transfer_notes TEXT NULL,
    transfer_created_by INT NOT NULL,
    transfer_created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    transfer_accepted_by INT NULL,
    transfer_accepted_at DATETIME NULL,
    transfer_total DECIMAL(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Transfer items table
CREATE TABLE IF NOT EXISTS ezy_pos_stock_transfer_items (
    sti_id INT AUTO_INCREMENT PRIMARY KEY,
    sti_transfer_id INT NOT NULL,
    sti_item_id INT NOT NULL,
    sti_item_name VARCHAR(255) NOT NULL,
    sti_qty DECIMAL(10,2) NOT NULL DEFAULT 0,
    sti_price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (sti_transfer_id) REFERENCES ezy_pos_stock_transfer(transfer_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
