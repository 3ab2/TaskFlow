-- Update user role to admin
UPDATE users SET role = 'admin' WHERE email = '3ab2uelkarch2006@gmail.com';

-- Verify if admin_activity table exists and create it if not
CREATE TABLE IF NOT EXISTS `admin_activity` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `admin_id` INT NOT NULL,
    `action` VARCHAR(50) NOT NULL,
    `details` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
); 