SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

/* 1. TABLE: tenants */
CREATE TABLE IF NOT EXISTS `tenants` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `specialty_title` VARCHAR(150) DEFAULT NULL,
  `programs` JSON DEFAULT NULL,
  `competency_type` VARCHAR(50) DEFAULT NULL,
  `settings` JSON DEFAULT NULL,
  `domain` VARCHAR(255) NOT NULL,
  `logo` VARCHAR(255) NOT NULL DEFAULT '',
  `theme_color` VARCHAR(20) NOT NULL DEFAULT '#28a745',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tenants_domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 2. TABLE: roles */
CREATE TABLE IF NOT EXISTS `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(50) NOT NULL,
  `slug` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_roles_tenant_id` (`tenant_id`),
  CONSTRAINT `fk_roles_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 3. TABLE: user_types */
CREATE TABLE IF NOT EXISTS `user_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_types_tenant_name` (`tenant_id`, `name`),
  CONSTRAINT `fk_user_types_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 4. TABLE: users */
CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `user_type_id` BIGINT UNSIGNED NOT NULL,
  `role_id` BIGINT UNSIGNED DEFAULT NULL,
  `supervisor_id` BIGINT UNSIGNED DEFAULT NULL,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_tenant_username` (`tenant_id`, `username`),
  KEY `idx_users_tenant` (`tenant_id`),
  KEY `idx_users_type` (`user_type_id`),
  KEY `idx_users_role` (`role_id`),
  KEY `idx_users_supervisor` (`supervisor_id`),
  KEY `idx_users_remember_token` (`remember_token`),
  CONSTRAINT `fk_users_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_users_user_type` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_users_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 5. TABLE: role_user */
CREATE TABLE IF NOT EXISTS `role_user` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `role_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_user` (`user_id`, `role_id`),
  KEY `idx_role_user_user` (`user_id`),
  KEY `idx_role_user_role` (`role_id`),
  CONSTRAINT `fk_role_user_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_role_user_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 6. TABLE: supervisor_trainee */
CREATE TABLE IF NOT EXISTS `supervisor_trainee` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `supervisor_id` BIGINT UNSIGNED NOT NULL,
  `trainee_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_supervisor_trainee` (`supervisor_id`, `trainee_id`),
  KEY `idx_sup_trainee_sup` (`supervisor_id`),
  KEY `idx_sup_trainee_trainee` (`trainee_id`),
  CONSTRAINT `fk_sup_trainee_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_sup_trainee_trainee` FOREIGN KEY (`trainee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 6. TABLE: user_profiles */
CREATE TABLE IF NOT EXISTS `user_profiles` (
  `user_id` BIGINT UNSIGNED NOT NULL,
  `full_name` VARCHAR(120) NOT NULL DEFAULT '',
  `phone` VARCHAR(30) NOT NULL DEFAULT '',
  `bio` VARCHAR(500) NOT NULL DEFAULT '',
  `profile_image` VARCHAR(255) NOT NULL DEFAULT '',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 7. TABLE: training_entries */
CREATE TABLE IF NOT EXISTS `training_entries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `supervisor_id` BIGINT UNSIGNED DEFAULT NULL,
  `entry_type` VARCHAR(20) NOT NULL DEFAULT 'training',
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
  `program` VARCHAR(20) NOT NULL DEFAULT '',
  `com_ids` JSON DEFAULT NULL,
  `com_detail_ids` JSON DEFAULT NULL,
  `alt_procedure` VARCHAR(500) NOT NULL DEFAULT '',
  `std_post` VARCHAR(10) NOT NULL DEFAULT 'No',
  `entry_status` VARCHAR(40) NOT NULL DEFAULT 'Draft',
  `supervisor_remarks` TEXT DEFAULT NULL,
  `approved_at` DATETIME DEFAULT NULL,
  `approved_by` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_training_tenant` (`tenant_id`),
  KEY `idx_training_user` (`user_id`),
  KEY `idx_training_supervisor` (`supervisor_id`),
  KEY `idx_training_status` (`entry_status`),
  KEY `idx_training_program` (`program`),
  KEY `idx_training_approved_by` (`approved_by`),
  CONSTRAINT `fk_training_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_training_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_training_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_training_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 8. TABLE: rotational_entries */
CREATE TABLE IF NOT EXISTS `rotational_entries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `supervisor_id` BIGINT UNSIGNED DEFAULT NULL,
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
  `program` VARCHAR(20) NOT NULL DEFAULT '',
  `rot_ids` JSON DEFAULT NULL,
  `rot_detail_ids` JSON DEFAULT NULL,
  `alt_procedure` VARCHAR(500) NOT NULL DEFAULT '',
  `std_post` VARCHAR(10) NOT NULL DEFAULT 'No',
  `entry_status` VARCHAR(40) NOT NULL DEFAULT 'Draft',
  `supervisor_remarks` TEXT DEFAULT NULL,
  `approved_at` DATETIME DEFAULT NULL,
  `approved_by` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rotational_tenant` (`tenant_id`),
  KEY `idx_rotational_user` (`user_id`),
  KEY `idx_rotational_supervisor` (`supervisor_id`),
  KEY `idx_rotational_status` (`entry_status`),
  KEY `idx_rotational_program` (`program`),
  KEY `idx_rotational_approved_by` (`approved_by`),
  CONSTRAINT `fk_rotational_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rotational_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rotational_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_rotational_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 9. TABLE: journal_entries */
CREATE TABLE IF NOT EXISTS `journal_entries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `supervisor_id` BIGINT UNSIGNED DEFAULT NULL,
  `date_of_diss` DATE DEFAULT NULL,
  `fac_by` VARCHAR(255) NOT NULL DEFAULT '',
  `ref_of_art_disc` TEXT DEFAULT NULL,
  `topic` VARCHAR(500) NOT NULL DEFAULT '',
  `ref_article` VARCHAR(500) NOT NULL DEFAULT '',
  `program` VARCHAR(20) NOT NULL DEFAULT '',
  `std_post` VARCHAR(10) NOT NULL DEFAULT 'No',
  `entry_status` VARCHAR(40) NOT NULL DEFAULT 'Draft',
  `supervisor_remarks` TEXT DEFAULT NULL,
  `approved_at` DATETIME DEFAULT NULL,
  `approved_by` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_journal_tenant` (`tenant_id`),
  KEY `idx_journal_user` (`user_id`),
  KEY `idx_journal_supervisor` (`supervisor_id`),
  KEY `idx_journal_program` (`program`),
  KEY `idx_journal_approved_by` (`approved_by`),
  CONSTRAINT `fk_journal_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_journal_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_journal_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_journal_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 10. TABLE: presented_entries */
CREATE TABLE IF NOT EXISTS `presented_entries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `supervisor_id` BIGINT UNSIGNED DEFAULT NULL,
  `rec_date` DATE DEFAULT NULL,
  `rec_title` VARCHAR(500) NOT NULL DEFAULT '',
  `rec_venue` VARCHAR(500) NOT NULL DEFAULT '',
  `conf_name` TEXT DEFAULT NULL,
  `rec_type` VARCHAR(50) NOT NULL DEFAULT '',
  `program` VARCHAR(20) NOT NULL DEFAULT '',
  `std_post` VARCHAR(10) NOT NULL DEFAULT 'No',
  `entry_status` VARCHAR(40) NOT NULL DEFAULT 'Draft',
  `supervisor_remarks` TEXT DEFAULT NULL,
  `approved_at` DATETIME DEFAULT NULL,
  `approved_by` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_presented_tenant` (`tenant_id`),
  KEY `idx_presented_user` (`user_id`),
  KEY `idx_presented_supervisor` (`supervisor_id`),
  KEY `idx_presented_program` (`program`),
  KEY `idx_presented_approved_by` (`approved_by`),
  CONSTRAINT `fk_presented_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_presented_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_presented_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_presented_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 11. TABLE: published_entries */
CREATE TABLE IF NOT EXISTS `published_entries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `supervisor_id` BIGINT UNSIGNED DEFAULT NULL,
  `pub_date` DATE DEFAULT NULL,
  `pub_title` VARCHAR(500) NOT NULL DEFAULT '',
  `full_ref` TEXT DEFAULT NULL,
  `pub_journal` VARCHAR(500) NOT NULL DEFAULT '',
  `pub_authors` VARCHAR(500) NOT NULL DEFAULT '',
  `program` VARCHAR(20) NOT NULL DEFAULT '',
  `std_post` VARCHAR(10) NOT NULL DEFAULT 'No',
  `entry_status` VARCHAR(40) NOT NULL DEFAULT 'Draft',
  `supervisor_remarks` TEXT DEFAULT NULL,
  `approved_at` DATETIME DEFAULT NULL,
  `approved_by` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_published_tenant` (`tenant_id`),
  KEY `idx_published_user` (`user_id`),
  KEY `idx_published_supervisor` (`supervisor_id`),
  KEY `idx_published_program` (`program`),
  KEY `idx_published_approved_by` (`approved_by`),
  CONSTRAINT `fk_published_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_published_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_published_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_published_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 12. TABLE: suggestions */
CREATE TABLE IF NOT EXISTS `suggestions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `suggestion_text` MEDIUMTEXT NOT NULL,
  `program` VARCHAR(20) NOT NULL DEFAULT '',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_suggestions_tenant` (`tenant_id`),
  KEY `idx_suggestions_user` (`user_id`),
  CONSTRAINT `fk_suggestions_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_suggestions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 13. SEED DATA */
INSERT INTO `tenants` (`id`, `name`, `specialty_title`, `programs`, `competency_type`, `settings`, `domain`, `logo`, `theme_color`, `is_active`, `created_at`, `updated_at`)
VALUES
(1, 'ePortal – Internal Medicine', 'INTERNAL MEDICINE', '{"MD": "MD (Internal Medicine)", "IMM": "IMM (Internal Medicine)"}', 'fcps_imm', '{"portal_title": "ePortal – Internal Medicine"}', 'cpsp1.test', 'assets/tenants/cpsp1/logo.png', '#2E7D32', 1, NOW(), NOW()),
(2, 'ePortal – Obstetrics & Gynaecology', 'GYNAECOLOGY & OBSTETRICS', '{"MS": "MS (Gynaecology & Obstetrics)", "DGO": "DGO (Gynaecology & Obstetrics)"}', 'obgyn', '{"portal_title": "Gynae & OBS Portal"}', 'cpsp2.test', 'assets/tenants/cpsp2/logo.png', '#28a745', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

INSERT INTO `roles` (`id`, `tenant_id`, `name`, `slug`, `description`, `created_at`, `updated_at`)
VALUES
(1, NULL, 'Trainee', 'trainee', 'Postgraduate medical resident/trainee', NOW(), NOW()),
(2, NULL, 'Supervisor', 'supervisor', 'Clinical supervisor and consultant', NOW(), NOW()),
(3, NULL, 'Fellow', 'fellow', 'Post-fellowship subspecialty trainee', NOW(), NOW()),
(4, NULL, 'Administrator', 'administrator', 'System administrator', NOW(), NOW()),
(5, 1, 'Trainee', 'trainee', 'Postgraduate medical resident/trainee', NOW(), NOW()),
(6, 1, 'Supervisor', 'supervisor', 'Clinical supervisor and consultant', NOW(), NOW()),
(7, 1, 'Fellow', 'fellow', 'Post-fellowship subspecialty trainee', NOW(), NOW()),
(8, 1, 'Administrator', 'administrator', 'System administrator', NOW(), NOW()),
(9, 2, 'Trainee', 'trainee', 'Postgraduate medical resident/trainee', NOW(), NOW()),
(10, 2, 'Supervisor', 'supervisor', 'Clinical supervisor and consultant', NOW(), NOW()),
(11, 2, 'Fellow', 'fellow', 'Post-fellowship subspecialty trainee', NOW(), NOW()),
(12, 2, 'Administrator', 'administrator', 'System administrator', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

INSERT INTO `user_types` (`id`, `tenant_id`, `name`, `created_at`, `updated_at`)
VALUES
(1, 1, 'Trainee', NOW(), NOW()),
(2, 1, 'Supervisor', NOW(), NOW()),
(3, 1, 'Fellow', NOW(), NOW()),
(4, 2, 'Trainee', NOW(), NOW()),
(5, 2, 'Supervisor', NOW(), NOW()),
(6, 2, 'Fellow', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

INSERT INTO `users` (`id`, `tenant_id`, `user_type_id`, `role_id`, `supervisor_id`, `username`, `email`, `password`, `created_at`, `updated_at`)
VALUES
(1, 1, 1, 5, 2, '2022-23675', 'trainee.demo@cpsp1.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(2, 1, 2, 6, NULL, 'supervisor01', 'supervisor.demo@cpsp1.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(3, 1, 3, 7, NULL, 'fellow01', 'fellow.demo@cpsp1.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(4, 1, 1, 5, 2, '2011-2686', 'salar.trainee@cpsp1.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(5, 1, 1, 5, 2, 'trainee1', 'trainee1@cpsp1.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(6, 1, 1, 5, 13, 'trainee2', 'trainee2@cpsp1.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(13, 1, 2, 6, NULL, 'supervisor02', 'supervisor2@cpsp1.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(15, 1, 2, 6, NULL, 'asghar', 'asghar@cpsp1.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(16, 1, 2, 6, NULL, 'hafiz', 'hafiz@cpsp1.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(17, 1, 1, 5, 15, 'salochina', 'salochina@cpsp1.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(18, 1, 1, 5, 15, 'maria', 'maria@cpsp1.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(19, 1, 1, 5, 16, 'amarta', 'amarta@cpsp1.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(20, 1, 1, 5, 16, 'iqra', 'iqra@cpsp1.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(7, 2, 4, 9, 8, '2022-23675', 'trainee.demo@cpsp2.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(8, 2, 5, 10, NULL, 'supervisor01', 'supervisor.demo@cpsp2.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(9, 2, 6, 11, NULL, 'fellow01', 'fellow.demo@cpsp2.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(10, 2, 4, 9, 8, '2011-2686', 'salar.trainee@cpsp2.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(11, 2, 4, 9, 8, 'trainee1', 'trainee1@cpsp2.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(12, 2, 4, 9, 14, 'trainee2', 'trainee2@cpsp2.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW()),
(14, 2, 5, 10, NULL, 'supervisor02', 'supervisor2@cpsp2.local', '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm', NOW(), NOW())
ON DUPLICATE KEY UPDATE `email`=VALUES(`email`), `supervisor_id`=VALUES(`supervisor_id`);

INSERT INTO `role_user` (`user_id`, `role_id`, `created_at`, `updated_at`)
VALUES
(1, 5, NOW(), NOW()), (2, 6, NOW(), NOW()), (3, 7, NOW(), NOW()),
(4, 5, NOW(), NOW()), (5, 5, NOW(), NOW()), (6, 5, NOW(), NOW()), (13, 6, NOW(), NOW()),
(15, 6, NOW(), NOW()), (16, 6, NOW(), NOW()), (17, 5, NOW(), NOW()), (18, 5, NOW(), NOW()), (19, 5, NOW(), NOW()), (20, 5, NOW(), NOW()),
(7, 9, NOW(), NOW()), (8, 10, NOW(), NOW()), (9, 11, NOW(), NOW()),
(10, 9, NOW(), NOW()), (11, 9, NOW(), NOW()), (12, 9, NOW(), NOW()), (14, 10, NOW(), NOW())
ON DUPLICATE KEY UPDATE `role_id`=VALUES(`role_id`);

INSERT INTO `user_profiles` (`user_id`, `full_name`, `phone`, `bio`, `profile_image`, `created_at`, `updated_at`)
VALUES
(1, 'Dr. Trainee Demo', '+92-300-0000000', 'Trainee in Internal Medicine.', '', NOW(), NOW()),
(2, 'Dr. Supervisor Demo', '+92-300-1111111', 'Supervisor 1', '', NOW(), NOW()),
(3, 'Dr. Fellow Demo', '+92-300-2222222', '', '', NOW(), NOW()),
(4, 'Dr. Salar', '', '', '', NOW(), NOW()),
(5, 'Trainee 1', '+92-300-1000001', 'Trainee Resident in Medicine (Supervised by Dr. Supervisor Demo).', '', NOW(), NOW()),
(6, 'Trainee 2', '+92-300-1000002', 'Trainee Resident in Medicine (Supervised by Dr. Supervisor Two).', '', NOW(), NOW()),
(13, 'Dr. Supervisor Two', '+92-300-3333333', 'Supervisor 2', '', NOW(), NOW()),
(15, 'Dr. Asghar', '+92-300-1112233', 'Consultant & Clinical Supervisor', '', NOW(), NOW()),
(16, 'Dr. Hafiz', '+92-300-1114455', 'Consultant & Clinical Supervisor', '', NOW(), NOW()),
(17, 'Dr. Salochina', '+92-300-2220001', 'Trainee Resident in Medicine (Supervised by Dr. Asghar).', '', NOW(), NOW()),
(18, 'Dr. Maria', '+92-300-2220002', 'Trainee Resident in Medicine (Supervised by Dr. Asghar).', '', NOW(), NOW()),
(19, 'Dr. Amarta', '+92-300-2220003', 'Trainee Resident in Medicine (Supervised by Dr. Hafiz).', '', NOW(), NOW()),
(20, 'Dr. Iqra', '+92-300-2220004', 'Trainee Resident in Medicine (Supervised by Dr. Hafiz).', '', NOW(), NOW()),
(7, 'Dr. Trainee Demo', '+92-300-0000000', 'Trainee in Obstetrics & Gynaecology.', '', NOW(), NOW()),
(8, 'Dr. Supervisor Demo', '+92-300-1111111', 'Supervisor 1', '', NOW(), NOW()),
(9, 'Dr. Fellow Demo', '+92-300-2222222', '', '', NOW(), NOW()),
(10, 'Dr. Salar', '', '', '', NOW(), NOW()),
(11, 'Trainee 1', '+92-300-1000001', 'Trainee Resident in Obs & Gynae (Supervised by Dr. Supervisor Demo).', '', NOW(), NOW()),
(12, 'Trainee 2', '+92-300-1000002', 'Trainee Resident in Obs & Gynae (Supervised by Dr. Supervisor Two).', '', NOW(), NOW()),
(14, 'Dr. Supervisor Two', '+92-300-3333333', 'Supervisor 2', '', NOW(), NOW())
ON DUPLICATE KEY UPDATE `full_name`=VALUES(`full_name`);

INSERT INTO `supervisor_trainee` (`supervisor_id`, `trainee_id`, `created_at`, `updated_at`)
VALUES
(2, 1, NOW(), NOW()),
(2, 4, NOW(), NOW()),
(2, 5, NOW(), NOW()),
(13, 6, NOW(), NOW()),
(15, 17, NOW(), NOW()),
(15, 18, NOW(), NOW()),
(16, 19, NOW(), NOW()),
(16, 20, NOW(), NOW()),
(8, 7, NOW(), NOW()),
(8, 10, NOW(), NOW()),
(8, 11, NOW(), NOW()),
(14, 12, NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at`=NOW();

SET FOREIGN_KEY_CHECKS = 1;

