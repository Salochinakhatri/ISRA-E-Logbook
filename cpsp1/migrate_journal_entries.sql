-- Migration: Create journal_entries table
-- Run this ONCE against your cpsp_eportal database.
-- Usage: mysql -u root cpsp_eportal < migrate_journal_entries.sql

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `journal_entries` (
  `id`               INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `user_id`          INT UNSIGNED  NOT NULL,
  `date_of_diss`     DATE          DEFAULT NULL           COMMENT 'Date of Discussion',
  `fac_by`           VARCHAR(255)  NOT NULL DEFAULT ''    COMMENT 'Facilitated by',
  `ref_of_art_disc`  TEXT          NOT NULL               COMMENT 'Full reference of article discussed',
  `std_post`         VARCHAR(10)   NOT NULL DEFAULT 'No'  COMMENT 'Send to Supervisor',
  `entry_status`     VARCHAR(40)   NOT NULL DEFAULT 'Draft',
  `approved_at`      DATETIME      DEFAULT NULL,
  `created_at`       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_journal_user`   (`user_id`),
  KEY `idx_journal_status` (`entry_status`),
  CONSTRAINT `fk_journal_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- Verify
SELECT 'journal_entries table ready.' AS status;
SHOW COLUMNS FROM journal_entries;
