-- =============================================================================
-- DATABASE SETUP FOR GYNAE & OBS (gynae&obs)
-- =============================================================================
CREATE DATABASE IF NOT EXISTS `gynae&obs` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `gynae&obs`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `suggestions`;
DROP TABLE IF EXISTS `training_entries`;
DROP TABLE IF EXISTS `published_entries`;
DROP TABLE IF EXISTS `presented_entries`;
DROP TABLE IF EXISTS `journal_entries`;
DROP TABLE IF EXISTS `rotational_entries`;
DROP TABLE IF EXISTS `traninguro_entries`;
DROP TABLE IF EXISTS `tainingobs_entries`;
DROP TABLE IF EXISTS `user_profiles`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `user_types`;

CREATE TABLE `user_types` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_types_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_types` (`id`, `name`) VALUES (1, 'Trainee'), (2, 'Supervisor'), (3, 'Fellow');

CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_type_id` INT UNSIGNED NOT NULL,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_username` (`username`),
  KEY `idx_users_type` (`user_type_id`),
  KEY `idx_remember_token` (`remember_token`),
  CONSTRAINT `fk_users_user_type` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_profiles` (
  `user_id` INT UNSIGNED NOT NULL,
  `full_name` VARCHAR(120) NOT NULL DEFAULT '',
  `phone` VARCHAR(30) NOT NULL DEFAULT '',
  `bio` VARCHAR(500) NOT NULL DEFAULT '',
  `profile_image` VARCHAR(255) NOT NULL DEFAULT '',
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `traninguro_entries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `form_type` VARCHAR(10) NOT NULL DEFAULT '',
  `hospt_reg_no` VARCHAR(120) NOT NULL DEFAULT '',
  `date_of_admission` DATE DEFAULT NULL,
  `pt_gender` VARCHAR(20) NOT NULL DEFAULT '',
  `pt_age` VARCHAR(20) NOT NULL DEFAULT '',
  `pt_age_type` VARCHAR(30) NOT NULL DEFAULT 'Year[s]',
  `pt_diagnosis` VARCHAR(500) NOT NULL DEFAULT '',
  `under_sup_name` VARCHAR(255) NOT NULL DEFAULT '',
  `level_id` VARCHAR(20) NOT NULL DEFAULT '',
  `outcome_id` VARCHAR(20) NOT NULL DEFAULT '',
  `brief_desc` MEDIUMTEXT NOT NULL,
  `entry_for_prog_id` VARCHAR(10) NOT NULL DEFAULT '',
  `com_ids` JSON DEFAULT NULL,
  `com_detail_ids` JSON DEFAULT NULL,
  `alt_procedure` VARCHAR(500) NOT NULL DEFAULT '',
  `std_post` VARCHAR(10) NOT NULL DEFAULT 'No',
  `entry_status` VARCHAR(40) NOT NULL DEFAULT 'Draft',
  `fcps_program` VARCHAR(50) NOT NULL DEFAULT 'urogyn',
  `approved_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_training_user` (`user_id`),
  KEY `idx_training_status` (`entry_status`),
  KEY `idx_training_program` (`fcps_program`),
  CONSTRAINT `fk_traninguro_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tainingobs_entries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `form_type` VARCHAR(10) NOT NULL DEFAULT '',
  `hospt_reg_no` VARCHAR(120) NOT NULL DEFAULT '',
  `date_of_admission` DATE DEFAULT NULL,
  `pt_gender` VARCHAR(20) NOT NULL DEFAULT '',
  `pt_age` VARCHAR(20) NOT NULL DEFAULT '',
  `pt_age_type` VARCHAR(30) NOT NULL DEFAULT 'Year[s]',
  `pt_diagnosis` VARCHAR(500) NOT NULL DEFAULT '',
  `under_sup_name` VARCHAR(255) NOT NULL DEFAULT '',
  `level_id` VARCHAR(20) NOT NULL DEFAULT '',
  `outcome_id` VARCHAR(20) NOT NULL DEFAULT '',
  `brief_desc` MEDIUMTEXT NOT NULL,
  `entry_for_prog_id` VARCHAR(10) NOT NULL DEFAULT '',
  `com_ids` JSON DEFAULT NULL,
  `com_detail_ids` JSON DEFAULT NULL,
  `alt_procedure` VARCHAR(500) NOT NULL DEFAULT '',
  `std_post` VARCHAR(10) NOT NULL DEFAULT 'No',
  `entry_status` VARCHAR(40) NOT NULL DEFAULT 'Draft',
  `fcps_program` VARCHAR(50) NOT NULL DEFAULT 'obgyn',
  `approved_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_training_user` (`user_id`),
  KEY `idx_training_status` (`entry_status`),
  KEY `idx_training_program` (`fcps_program`),
  CONSTRAINT `fk_tainingobs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `rotational_entries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `entry_type` VARCHAR(20) NOT NULL DEFAULT '',
  `hospital_name` VARCHAR(255) NOT NULL DEFAULT '',
  `department` VARCHAR(255) NOT NULL DEFAULT '',
  `from_date` DATE DEFAULT NULL,
  `to_date` DATE DEFAULT NULL,
  `date_of_admission` DATE DEFAULT NULL,
  `hospt_reg_no` VARCHAR(120) NOT NULL DEFAULT '',
  `pt_gender` VARCHAR(20) NOT NULL DEFAULT '',
  `pt_age` VARCHAR(20) NOT NULL DEFAULT '',
  `pt_age_type` VARCHAR(30) NOT NULL DEFAULT 'Year[s]',
  `pt_diagnosis` VARCHAR(500) NOT NULL DEFAULT '',
  `under_sup_name` VARCHAR(255) NOT NULL DEFAULT '',
  `level_id` VARCHAR(20) NOT NULL DEFAULT '',
  `outcome_id` VARCHAR(20) NOT NULL DEFAULT '',
  `brief_desc` MEDIUMTEXT NOT NULL,
  `entry_for_prog_id` VARCHAR(10) NOT NULL DEFAULT '',
  `com_ids` JSON DEFAULT NULL,
  `com_detail_ids` JSON DEFAULT NULL,
  `alt_procedure` VARCHAR(500) NOT NULL DEFAULT '',
  `std_post` VARCHAR(10) NOT NULL DEFAULT 'No',
  `entry_status` VARCHAR(40) NOT NULL DEFAULT 'Draft',
  `fcps_program` VARCHAR(50) NOT NULL DEFAULT 'urogyn',
  `approved_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rotational_user` (`user_id`),
  KEY `idx_rotational_status` (`entry_status`),
  KEY `idx_rotational_program` (`fcps_program`),
  CONSTRAINT `fk_rotational_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `journal_entries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `date_of_diss` DATE DEFAULT NULL,
  `fac_by` VARCHAR(255) NOT NULL DEFAULT '',
  `topic` VARCHAR(500) NOT NULL DEFAULT '',
  `ref_article` VARCHAR(500) NOT NULL DEFAULT '',
  `std_post` VARCHAR(10) NOT NULL DEFAULT 'No',
  `entry_status` VARCHAR(40) NOT NULL DEFAULT 'Draft',
  `fcps_program` VARCHAR(50) NOT NULL DEFAULT 'urogyn',
  `approved_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_journal_user` (`user_id`),
  KEY `idx_journal_program` (`fcps_program`),
  CONSTRAINT `fk_journal_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `presented_entries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `rec_date` DATE DEFAULT NULL,
  `rec_title` VARCHAR(500) NOT NULL DEFAULT '',
  `rec_venue` VARCHAR(500) NOT NULL DEFAULT '',
  `rec_type` VARCHAR(50) NOT NULL DEFAULT '',
  `std_post` VARCHAR(10) NOT NULL DEFAULT 'No',
  `entry_status` VARCHAR(40) NOT NULL DEFAULT 'Draft',
  `fcps_program` VARCHAR(50) NOT NULL DEFAULT 'urogyn',
  `approved_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_presented_user` (`user_id`),
  KEY `idx_presented_program` (`fcps_program`),
  CONSTRAINT `fk_presented_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `published_entries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `pub_date` DATE DEFAULT NULL,
  `pub_title` VARCHAR(500) NOT NULL DEFAULT '',
  `pub_journal` VARCHAR(500) NOT NULL DEFAULT '',
  `pub_authors` VARCHAR(500) NOT NULL DEFAULT '',
  `std_post` VARCHAR(10) NOT NULL DEFAULT 'No',
  `entry_status` VARCHAR(40) NOT NULL DEFAULT 'Draft',
  `fcps_program` VARCHAR(50) NOT NULL DEFAULT 'urogyn',
  `approved_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_published_user` (`user_id`),
  KEY `idx_published_program` (`fcps_program`),
  CONSTRAINT `fk_published_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `suggestions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `suggestion` MEDIUMTEXT NOT NULL,
  `fcps_program` VARCHAR(50) NOT NULL DEFAULT 'urogyn',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_suggestions_user` (`user_id`),
  CONSTRAINT `fk_suggestions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Seeding Users ────────────────────────────────────────────────────────────
INSERT INTO `users` (`user_type_id`, `username`, `email`, `password`) VALUES
  (1, '2022-23675', 'trainee.demo@cpsp.local', '$2y$10$y.foHzRPTDRL3/F/y7AIJOLJDfCZRyT0c0v5/lhODXghlOourdcWa'),
  (2, 'supervisor01', 'supervisor.demo@cpsp.local', '$2y$10$y.foHzRPTDRL3/F/y7AIJOLJDfCZRyT0c0v5/lhODXghlOourdcWa'),
  (3, 'fellow01', 'fellow.demo@cpsp.local', '$2y$10$y.foHzRPTDRL3/F/y7AIJOLJDfCZRyT0c0v5/lhODXghlOourdcWa'),
  (1, '2011-2686', 'salar.trainee@cpsp.local', '$2y$10$3AzAjOi0MulX5v/fRAZ3GOAj/OEaW3t.68RZCMAQUH1/eHR9x7fWS');

INSERT INTO `user_profiles` (`user_id`, `full_name`, `phone`, `bio`, `profile_image`)
SELECT u.id, 'Dr. Trainee Demo', '+92-300-0000000', 'Trainee in Obstetrics & Gynaecology / Urogynaecology.', ''
FROM `users` u WHERE u.username = '2022-23675'
ON DUPLICATE KEY UPDATE `full_name` = VALUES(`full_name`), `phone` = VALUES(`phone`);

INSERT INTO `user_profiles` (`user_id`, `full_name`, `phone`, `bio`, `profile_image`)
SELECT u.id, 'Dr. Salar', '', '', ''
FROM `users` u WHERE u.username = '2011-2686'
ON DUPLICATE KEY UPDATE `full_name` = VALUES(`full_name`);

SET FOREIGN_KEY_CHECKS = 1;
