-- Hostinger MySQL Compatible Schema
-- Removes CHECK constraints and FULLTEXT (not supported on all shared hosts)

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

-- Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('buyer','supplier','admin') DEFAULT 'buyer',
    email_verified TINYINT(1) DEFAULT 0,
    reset_token VARCHAR(64) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_active TIMESTAMP NULL,
    UNIQUE KEY email (email),
    KEY idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Locations
CREATE TABLE IF NOT EXISTS locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    UNIQUE KEY slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Suppliers
CREATE TABLE IF NOT EXISTS suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    business_name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    category_id INT DEFAULT NULL,
    subcategory VARCHAR(255) DEFAULT NULL,
    location_id INT DEFAULT NULL,
    address TEXT,
    phone VARCHAR(50) DEFAULT NULL,
    whatsapp VARCHAR(50) DEFAULT NULL,
    telegram VARCHAR(50) DEFAULT NULL,
    email VARCHAR(255) DEFAULT NULL,
    website VARCHAR(255) DEFAULT NULL,
    description TEXT,
    opening_hours TEXT,
    delivery_available TINYINT(1) DEFAULT 0,
    bulk_available TINYINT(1) DEFAULT 0,
    logo VARCHAR(255) DEFAULT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    is_verified TINYINT(1) DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    is_premium TINYINT(1) DEFAULT 0,
    plan ENUM('free','verified','premium','enterprise') DEFAULT 'free',
    plan_expires DATE DEFAULT NULL,
    featured_until DATE DEFAULT NULL,
    view_count INT DEFAULT 0,
    contact_click_count INT DEFAULT 0,
    response_rate INT DEFAULT 0,
    response_time_hours INT DEFAULT 0,
    last_active TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_status (status),
    KEY idx_category (category_id),
    KEY idx_location (location_id),
    KEY idx_verified (is_verified),
    KEY idx_featured (is_featured),
    UNIQUE KEY slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Supplier Photos
CREATE TABLE IF NOT EXISTS supplier_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    caption VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Buyer Requests
CREATE TABLE IF NOT EXISTS buyer_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    category_id INT DEFAULT NULL,
    location_id INT DEFAULT NULL,
    quantity VARCHAR(100) DEFAULT NULL,
    budget VARCHAR(100) DEFAULT NULL,
    urgency ENUM('today','this_week','flexible') DEFAULT 'flexible',
    description TEXT,
    photo VARCHAR(255) DEFAULT NULL,
    status ENUM('open','closed','fulfilled') DEFAULT 'open',
    contact_method ENUM('phone','whatsapp','telegram','email') DEFAULT 'phone',
    contact_value VARCHAR(255) DEFAULT NULL,
    privacy ENUM('public','private') DEFAULT 'public',
    is_pinned TINYINT(1) DEFAULT 0,
    pinned_until DATE DEFAULT NULL,
    view_count INT DEFAULT 0,
    quote_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_status (status),
    KEY idx_category (category_id),
    KEY idx_location (location_id),
    KEY idx_urgency (urgency)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Quotes
CREATE TABLE IF NOT EXISTS quotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    supplier_id INT NOT NULL,
    price VARCHAR(100) DEFAULT NULL,
    delivery_time VARCHAR(100) DEFAULT NULL,
    message TEXT,
    status ENUM('pending','accepted','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_request (request_id),
    KEY idx_supplier (supplier_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Reviews
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    user_id INT NOT NULL,
    request_id INT DEFAULT NULL,
    rating INT NOT NULL,
    comment TEXT,
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_supplier (supplier_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Saved Suppliers
CREATE TABLE IF NOT EXISTS saved_suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    supplier_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_save (user_id, supplier_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin Logs
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    target_type VARCHAR(50) DEFAULT NULL,
    target_id INT DEFAULT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Payments
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    supplier_id INT DEFAULT NULL,
    type ENUM('verification','premium','featured','pin_request') NOT NULL,
    amount VARCHAR(50) DEFAULT NULL,
    status ENUM('pending','confirmed','rejected') DEFAULT 'pending',
    notes TEXT,
    paid_via VARCHAR(50) DEFAULT NULL,
    confirmed_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Popular Searches
CREATE TABLE IF NOT EXISTS popular_searches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    query VARCHAR(255) NOT NULL,
    search_type ENUM('supplier','request') DEFAULT 'supplier',
    count INT DEFAULT 1,
    last_searched TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_count (count)
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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Supplier Views
CREATE TABLE IF NOT EXISTS supplier_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_supplier_date (supplier_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed default categories
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
('Solar & Electrical','solar-electrical',18);

-- Seed default locations
INSERT INTO locations (name, slug, sort_order) VALUES
('Bole','bole',1),
('Merkato','merkato',2),
('Kazanchis','kazanchis',3),
('Megenagna','megenagna',4),
('CMC','cmc',5),
('Piassa','piassa',6),
('Sarbet','sarbet',7);

-- Seed admin user (password: password)
INSERT INTO users (full_name, email, phone, password_hash, role, email_verified) VALUES
('Admin User', 'admin@ethiomarket.com', '0911111111', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;