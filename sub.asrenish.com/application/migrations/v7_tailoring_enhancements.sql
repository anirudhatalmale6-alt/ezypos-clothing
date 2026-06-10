-- Tailoring Order Enhancements Migration
-- EzyPOS v7
-- Run this SQL on the ezypos database

-- Add pickup store column to production sale table
ALTER TABLE ezy_pos_prodsale ADD COLUMN prodsale_pickup_store_id INT DEFAULT NULL;

-- Add payment method tracking to production sale payments
-- (tracks how each payment was made: Cash, Cheque, Koko, etc.)
CREATE TABLE IF NOT EXISTS ezy_pos_prodsale_payments (
    psp_id INT AUTO_INCREMENT PRIMARY KEY,
    psp_prodsale_id INT NOT NULL,
    psp_amount DECIMAL(12,2) NOT NULL,
    psp_method VARCHAR(50) DEFAULT 'Cash',
    psp_card_ref VARCHAR(100) DEFAULT NULL,
    psp_created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    psp_created_by INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
