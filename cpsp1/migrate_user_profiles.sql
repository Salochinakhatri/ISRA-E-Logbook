-- Run this if your existing DB lacks user_profiles table.
-- Usage: mysql -u root cpsp_eportal < migrate_user_profiles.sql

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `user_profiles` (
  `user_id` INT UNSIGNED NOT NULL,
  `full_name` VARCHAR(120) NOT NULL DEFAULT '',
  `phone` VARCHAR(30) NOT NULL DEFAULT '',
  `bio` VARCHAR(500) NOT NULL DEFAULT '',
  `profile_image` VARCHAR(255) NOT NULL DEFAULT '',
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_profiles` (`user_id`, `full_name`, `phone`, `bio`, `profile_image`)
SELECT u.id, '', '', '', ''
FROM users u
LEFT JOIN user_profiles p ON p.user_id = u.id
WHERE p.user_id IS NULL;
