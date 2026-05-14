-- Migration: Create published_entries table
-- Run this ONCE against your cpsp_eportal database.
-- Usage: mysql -u root cpsp_eportal < migrate_published_entries.sql

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `published_entries` (
  `id`               INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `user_id`          INT UNSIGNED  NOT NULL,
  `pub_date`         DATE          DEFAULT NULL           COMMENT 'Published Date',
  `pub_title`        VARCHAR(500)  NOT NULL DEFAULT ''    COMMENT 'Title',
  `full_ref`         TEXT          NOT NULL               COMMENT 'Full Reference',
  `std_post`         VARCHAR(10)   NOT NULL DEFAULT 'No'  COMMENT 'Send to Supervisor',
  `entry_status`     VARCHAR(40)   NOT NULL DEFAULT 'Draft',
  `approved_at`      DATETIME      DEFAULT NULL,
  `created_at`       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_published_user`   (`user_id`),
  KEY `idx_published_status` (`entry_status`),
  CONSTRAINT `fk_published_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- Verify
SELECT 'published_entries table ready.' AS status;
SHOW COLUMNS FROM published_entries;
