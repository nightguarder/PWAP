-- Notes table to store Markdown content (simplified without categories/tags)
CREATE TABLE IF NOT EXISTS `Notes` (
  `note_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` LONGTEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_encrypted` BOOLEAN DEFAULT FALSE,
  FOREIGN KEY (`user_id`) REFERENCES `Accounts`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
