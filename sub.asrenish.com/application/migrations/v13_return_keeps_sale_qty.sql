-- v13 - a return no longer rewrites the sale it came from
--
-- Until now the Customer Return screen subtracted the returned pieces from
-- ezy_pos_sale_item.saleitem_quantity, because that was the only record of how
-- much of a bill was still outstanding. The side effect was that the Sales
-- Report showed a bill for 1 piece when 2 had actually been sold and 1 brought
-- back - the sale had been rewritten rather than adjusted.
--
-- The return is now kept as its own record against the bill, which needs the
-- bill number storing on it. Everything else stays where it is.
--
-- Safe to run twice: the ALTER fails with "Duplicate column name" and nothing
-- else in the file depends on it.

ALTER TABLE ezy_pos_cus_return ADD COLUMN cusrtrn_saleID INT NOT NULL DEFAULT 0;

ALTER TABLE ezy_pos_cus_return ADD INDEX idx_cusrtrn_sale (cusrtrn_saleID);
