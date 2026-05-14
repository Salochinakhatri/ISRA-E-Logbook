-- CPSP ePortal e-Log Book – MySQL schema (XAMPP / WAMP / Laragon)
-- Create database first: CREATE DATABASE cpsp_eportal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Then import: mysql -u root cpsp_eportal < database.sql

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `training_entries`;
DROP TABLE IF EXISTS `user_profiles`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `user_types`;

CREATE TABLE `user_types` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_types_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

INSERT INTO `user_types` (`id`, `name`) VALUES
  (1, 'Trainee'),
  (2, 'Supervisor'),
  (3, 'Fellow')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Demo password for all sample users: Password123!
INSERT INTO `users` (`user_type_id`, `username`, `email`, `password`) VALUES
  (1, '2022-23675', 'trainee.demo@cpsp.local', '$2y$10$y.foHzRPTDRL3/F/y7AIJOLJDfCZRyT0c0v5/lhODXghlOourdcWa'),
  (2, 'supervisor01', 'supervisor.demo@cpsp.local', '$2y$10$y.foHzRPTDRL3/F/y7AIJOLJDfCZRyT0c0v5/lhODXghlOourdcWa'),
  (3, 'fellow01', 'fellow.demo@cpsp.local', '$2y$10$y.foHzRPTDRL3/F/y7AIJOLJDfCZRyT0c0v5/lhODXghlOourdcWa')
ON DUPLICATE KEY UPDATE
  `email` = VALUES(`email`),
  `password` = VALUES(`password`),
  `user_type_id` = VALUES(`user_type_id`);

INSERT INTO `user_profiles` (`user_id`, `full_name`, `phone`, `bio`, `profile_image`) VALUES
  (1, 'Dr. Trainee Demo', '+92-300-0000000', 'Trainee in Internal Medicine, focused on structured competency-based training and evidence-based documentation.', ''),
  (2, 'Dr. Supervisor Demo', '+92-300-1111111', 'Supervisor account for review and approval workflow.', ''),
  (3, 'Dr. Fellow Demo', '+92-300-2222222', 'Fellow account for training and supervision support.', '')
ON DUPLICATE KEY UPDATE
  `full_name` = VALUES(`full_name`),
  `phone` = VALUES(`phone`),
  `bio` = VALUES(`bio`),
  `profile_image` = VALUES(`profile_image`);

CREATE TABLE IF NOT EXISTS `training_entries` (
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
  `approved_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_training_user` (`user_id`),
  KEY `idx_training_status` (`entry_status`),
  CONSTRAINT `fk_training_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
