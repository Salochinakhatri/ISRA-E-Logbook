-- Migration: Add entry_type column to training_entries
-- Run this ONCE against your cpsp_eportal database.
-- Usage: mysql -u root cpsp_eportal < migrate_rotational_entry_type.sql

-- 1. Add entry_type column (if not already present)
ALTER TABLE `training_entries`
    ADD COLUMN IF NOT EXISTS `entry_type` VARCHAR(20) NOT NULL DEFAULT 'training'
    AFTER `user_id`;

-- 2. Add an index to speed up filtering by type
ALTER TABLE `training_entries`
    ADD INDEX IF NOT EXISTS `idx_training_entry_type` (`entry_type`);

-- 3. Back-fill any existing rows that have no type yet
UPDATE `training_entries`
SET `entry_type` = 'training'
WHERE `entry_type` = '' OR `entry_type` IS NULL;

-- Verify
SELECT entry_type, COUNT(*) AS cnt
FROM training_entries
GROUP BY entry_type;
