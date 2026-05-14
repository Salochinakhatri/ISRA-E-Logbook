-- Migration: Create rotational_entries table (separate from training_entries)
-- Run this ONCE against your cpsp_eportal database.
-- Usage (phpMyAdmin SQL tab, or terminal):
--   mysql -u root cpsp_eportal < migrate_rotational_entries.sql

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `rotational_entries` (
  `id`                INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  `user_id`           INT UNSIGNED      NOT NULL,
  `form_type`         VARCHAR(10)       NOT NULL DEFAULT '',
  `hospt_reg_no`      VARCHAR(120)      NOT NULL DEFAULT '',
  `date_of_admission` DATE              DEFAULT NULL,
  `pt_gender`         VARCHAR(20)       NOT NULL DEFAULT '',
  `pt_age`            VARCHAR(20)       NOT NULL DEFAULT '',
  `pt_age_type`       VARCHAR(30)       NOT NULL DEFAULT 'Year[s]',
  `pt_diagnosis`      VARCHAR(500)      NOT NULL DEFAULT '',
  `under_sup_name`    VARCHAR(255)      NOT NULL DEFAULT '',
  `level_id`          VARCHAR(20)       NOT NULL DEFAULT '',
  `outcome_id`        VARCHAR(20)       NOT NULL DEFAULT '',
  `brief_desc`        MEDIUMTEXT        NOT NULL,
  `entry_for_prog_id` VARCHAR(10)       NOT NULL DEFAULT '',
  `rot_ids`           JSON              DEFAULT NULL  COMMENT 'Selected competency group IDs (rot_id[])',
  `rot_detail_ids`    JSON              DEFAULT NULL  COMMENT 'Selected competency detail IDs (rot_detail_id[])',
  `alt_procedure`     VARCHAR(500)      NOT NULL DEFAULT '',
  `std_post`          VARCHAR(10)       NOT NULL DEFAULT 'No',
  `entry_status`      VARCHAR(40)       NOT NULL DEFAULT 'Draft',
  `approved_at`       DATETIME          DEFAULT NULL,
  `created_at`        TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rot_user`   (`user_id`),
  KEY `idx_rot_status` (`entry_status`),
  CONSTRAINT `fk_rotational_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- Verify
SELECT 'rotational_entries table ready.' AS status;
SHOW COLUMNS FROM rotational_entries;
