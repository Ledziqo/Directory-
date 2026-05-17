-- Schema updates for Phase 1 features

-- Password reset tokens
ALTER TABLE users ADD COLUMN reset_token VARCHAR(64) NULL DEFAULT NULL AFTER email_verified;
ALTER TABLE users ADD COLUMN reset_expires DATETIME NULL DEFAULT NULL AFTER reset_token;

-- Create supplier_views table for real analytics
CREATE TABLE IF NOT EXISTS supplier_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_supplier_date (supplier_id, created_at),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add index for autocomplete performance
ALTER TABLE suppliers ADD FULLTEXT INDEX ft_autocomplete (business_name, subcategory, description);