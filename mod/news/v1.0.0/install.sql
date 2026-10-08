-- Таблица новостей
CREATE TABLE IF NOT EXISTS `mod_news` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `announce` text,
  `body` text NOT NULL,
  `is_active_sw` enum('Y','N') NOT NULL DEFAULT 'Y',
  `created_at` datetime DEFAULT NULL,
  `updated_by` int unsigned DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;