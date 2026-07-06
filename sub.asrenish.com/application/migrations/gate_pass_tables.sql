-- Gate Pass tables for Production module
-- Run this SQL on the database to add gate pass functionality

CREATE TABLE IF NOT EXISTS ezy_pos_gate_pass (
    gp_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    gp_code VARCHAR(50) NOT NULL,
    gp_prod_id INT(11) NOT NULL,
    gp_date DATE NOT NULL,
    gp_store_id INT(11) NOT NULL,
    gp_issued_by INT(11) NOT NULL,
    gp_notes TEXT,
    gp_status ENUM('Issued','Partially Returned','Fully Returned','Cancelled') DEFAULT 'Issued',
    gp_total DOUBLE(30,2) DEFAULT 0.00,
    gp_createdat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_gp_prod(gp_prod_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ezy_pos_gate_pass_items (
    gpitem_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    gpitem_gp_id INT(11) NOT NULL,
    gpitem_item_id INT(11) NOT NULL,
    gpitem_qty DOUBLE(10,2) NOT NULL,
    gpitem_returned_qty DOUBLE(10,2) DEFAULT 0.00,
    gpitem_unit_price DOUBLE(30,2) NOT NULL,
    gpitem_total DOUBLE(30,2) NOT NULL,
    gpitem_uom VARCHAR(50) DEFAULT '',
    gpitem_createdat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_gpitem_gp(gpitem_gp_id),
    INDEX idx_gpitem_item(gpitem_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Link production materials to gate passes (optional column)
ALTER TABLE ezy_pos_production_materials
    ADD COLUMN prodmat_gp_id INT(11) DEFAULT NULL AFTER prodmat_total;
