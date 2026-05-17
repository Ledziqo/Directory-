-- Hostinger Compatible Schema (no CREATE DATABASE)
-- Run this directly in your existing "Directory" database via phpMyAdmin

-- Users (unified accounts)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('buyer','supplier','admin') DEFAULT 'buyer',
    email_verified TINYINT(1) DEFAULT 0,
    reset_token VARCHAR(64) NULL DEFAULT NULL,
    reset_expires DATETIME NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_active TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Locations (Addis Ababa neighborhoods/areas)
CREATE TABLE IF NOT EXISTS locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Supplier Profiles
CREATE TABLE IF NOT EXISTS suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    business_name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    category_id INT,
    subcategory VARCHAR(255),
    location_id INT,
    address TEXT,
    phone VARCHAR(50),
    whatsapp VARCHAR(50),
    telegram VARCHAR(50),
    email VARCHAR(255),
    website VARCHAR(255),
    description TEXT,
    opening_hours TEXT,
    delivery_available TINYINT(1) DEFAULT 0,
    bulk_available TINYINT(1) DEFAULT 0,
    logo VARCHAR(255),
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    is_verified TINYINT(1) DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    is_premium TINYINT(1) DEFAULT 0,
    plan ENUM('free','verified','premium','enterprise') DEFAULT 'free',
    plan_expires DATE NULL,
    featured_until DATE NULL,
    view_count INT DEFAULT 0,
    contact_click_count INT DEFAULT 0,
    response_rate INT DEFAULT 0,
    response_time_hours INT DEFAULT 0,
    last_active TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (location_id) REFERENCES locations(id),
    INDEX idx_status (status),
    INDEX idx_category (category_id),
    INDEX idx_location (location_id),
    INDEX idx_verified (is_verified),
    INDEX idx_featured (is_featured),
    FULLTEXT INDEX ft_search (business_name, description, subcategory)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Supplier Photos
CREATE TABLE IF NOT EXISTS supplier_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Buyer Requests
CREATE TABLE IF NOT EXISTS buyer_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    category_id INT,
    location_id INT,
    quantity VARCHAR(100),
    budget VARCHAR(100),
    urgency ENUM('today','this_week','flexible') DEFAULT 'flexible',
    description TEXT,
    photo VARCHAR(255),
    status ENUM('open','closed','fulfilled') DEFAULT 'open',
    contact_method ENUM('phone','whatsapp','telegram','email') DEFAULT 'phone',
    contact_value VARCHAR(255),
    privacy ENUM('public','private') DEFAULT 'public',
    is_pinned TINYINT(1) DEFAULT 0,
    pinned_until DATE NULL,
    view_count INT DEFAULT 0,
    quote_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (location_id) REFERENCES locations(id),
    INDEX idx_status (status),
    INDEX idx_category (category_id),
    INDEX idx_location (location_id),
    INDEX idx_urgency (urgency),
    FULLTEXT INDEX ft_search (title, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Quotes (Supplier responses to requests)
CREATE TABLE IF NOT EXISTS quotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    supplier_id INT NOT NULL,
    price VARCHAR(100),
    delivery_time VARCHAR(100),
    message TEXT,
    status ENUM('pending','accepted','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES buyer_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE,
    INDEX idx_request (request_id),
    INDEX idx_supplier (supplier_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Reviews
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    user_id INT NOT NULL,
    request_id INT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (request_id) REFERENCES buyer_requests(id) ON DELETE SET NULL,
    INDEX idx_supplier (supplier_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Saved Suppliers
CREATE TABLE IF NOT EXISTS saved_suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    supplier_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_save (user_id, supplier_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin Logs
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    target_type VARCHAR(50),
    target_id INT,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Payments (manual tracking)
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    supplier_id INT NULL,
    type ENUM('verification','premium','featured','pin_request') NOT NULL,
    amount VARCHAR(50),
    status ENUM('pending','confirmed','rejected') DEFAULT 'pending',
    notes TEXT,
    paid_via VARCHAR(50),
    confirmed_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL,
    FOREIGN KEY (confirmed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Popular Searches
CREATE TABLE IF NOT EXISTS popular_searches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    query VARCHAR(255) NOT NULL,
    search_type ENUM('supplier','request') DEFAULT 'supplier',
    count INT DEFAULT 1,
    last_searched TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_count (count)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Reports
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT NOT NULL,
    target_type ENUM('supplier','request','quote') NOT NULL,
    target_id INT NOT NULL,
    reason VARCHAR(255) NOT NULL,
    details TEXT,
    status ENUM('pending','resolved','dismissed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Supplier Views (analytics)
CREATE TABLE IF NOT EXISTS supplier_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_supplier_date (supplier_id, created_at),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default categories
INSERT INTO categories (name, slug, sort_order) VALUES
('Car Parts & Accessories','car-parts',1),
('Construction Materials','construction',2),
('Printing & Packaging','printing-packaging',3),
('Hotel & Restaurant Supplies','hotel-restaurant',4),
('Office Supplies','office-supplies',5),
('Furniture','furniture',6),
('Electronics','electronics',7),
('Machinery & Tools','machinery-tools',8),
('Importers & Wholesalers','importers-wholesalers',9),
('Professional Services','services',10),
('Agriculture & Farming','agriculture-farming',11),
('Textiles & Garments','textiles-garments',12),
('Cleaning & Sanitation','cleaning-sanitation',13),
('Medical & Pharmacy Supplies','medical-pharmacy',14),
('Beauty & Salon Supplies','beauty-salon',15),
('Event & Catering Supplies','event-catering',16),
('Logistics & Delivery','logistics-delivery',17),
('Solar & Electrical','solar-electrical',18)
ON DUPLICATE KEY UPDATE name=name;

-- Insert default locations
INSERT INTO locations (name, slug, sort_order) VALUES
('Bole','bole',1),
('Merkato','merkato',2),
('Kazanchis','kazanchis',3),
('Megenagna','megenagna',4),
('CMC','cmc',5),
('Piassa','piassa',6),
('Sarbet','sarbet',7)
ON DUPLICATE KEY UPDATE name=name;

-- Insert preview admin user (password: password)
INSERT INTO users (full_name, email, phone, password_hash, role, email_verified) VALUES
('Admin User', 'admin@ethiomarket.com', '0911111111', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1)
ON DUPLICATE KEY UPDATE email=email;