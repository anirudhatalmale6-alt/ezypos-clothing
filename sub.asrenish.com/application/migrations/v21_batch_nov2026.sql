-- =====================================================================
-- EzyPOS v21 migration (November 2026 batch)
--
-- Supplier returns keep their GRN and store, so a return can be opened
-- and edited again (until now a return was never tied to its GRN).
--
-- SAFE TO RUN: new columns and tables only, all with defaults. Nothing
-- existing is changed or deleted. Run it again and anything already in
-- place reports "Duplicate column name" / "already exists" and is skipped,
-- which is harmless.
-- =====================================================================

-- Supplier returns: which GRN, which store, a note, when last edited.
ALTER TABLE ezy_pos_sup_return ADD COLUMN suprtrn_grnID INT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_sup_return ADD COLUMN suprtrn_store_id INT NOT NULL DEFAULT 0;
ALTER TABLE ezy_pos_sup_return ADD COLUMN suprtrn_note VARCHAR(255) NULL;
