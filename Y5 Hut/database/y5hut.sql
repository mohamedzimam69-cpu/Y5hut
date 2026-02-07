-- Y5 Hut Mobile Shop Database
-- Create database
CREATE DATABASE IF NOT EXISTS y5hut_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE y5hut_db;

-- Users table (for admin)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'manager') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    category_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    original_price DECIMAL(10,2),
    description TEXT,
    specifications JSON,
    image VARCHAR(255),
    gallery JSON,
    stock_quantity INT DEFAULT 0,
    status ENUM('active', 'inactive', 'out_of_stock') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    meta_title VARCHAR(200),
    meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_status (status),
    INDEX idx_featured (featured)
);

-- Messages table (contact form submissions)
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created (created_at)
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, email, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@y5hut.lk', 'admin');

-- Insert default categories
INSERT INTO categories (name, slug, description) VALUES 
('Samsung', 'samsung', 'Samsung mobile phones and devices'),
('iPhone', 'iphone', 'Apple iPhone series'),
('Redmi', 'redmi', 'Xiaomi Redmi series phones'),
('Accessories', 'accessories', 'Mobile phone accessories and gadgets');

-- Insert sample products
INSERT INTO products (name, slug, category_id, price, original_price, description, specifications, image, featured) VALUES 
('Samsung Galaxy S24', 'samsung-galaxy-s24', 1, 189900.00, 199900.00, 'Latest Samsung flagship with advanced AI features', 
 JSON_OBJECT('display', '6.2 Dynamic AMOLED', 'processor', 'Exynos 2400', 'ram', '8GB', 'storage', '256GB', 'camera', '50MP Triple Camera'), 
 'samsung-s24.jpg', TRUE),

('Samsung Galaxy A54', 'samsung-galaxy-a54', 1, 89900.00, 94900.00, 'Mid-range Samsung with excellent camera performance', 
 JSON_OBJECT('display', '6.4 Super AMOLED', 'processor', 'Exynos 1380', 'ram', '8GB', 'storage', '128GB', 'camera', '50MP Triple Camera'), 
 'samsung-a54.jpg', TRUE),

('iPhone 15', 'iphone-15', 2, 329900.00, 339900.00, 'Latest iPhone with USB-C and advanced camera system', 
 JSON_OBJECT('display', '6.1 Super Retina XDR', 'processor', 'A16 Bionic', 'ram', '6GB', 'storage', '128GB', 'camera', '48MP Dual Camera'), 
 'iphone-15.jpg', TRUE),

('iPhone 14', 'iphone-14', 2, 289900.00, 299900.00, 'Powerful iPhone with excellent performance and camera', 
 JSON_OBJECT('display', '6.1 Super Retina XDR', 'processor', 'A15 Bionic', 'ram', '6GB', 'storage', '128GB', 'camera', '12MP Dual Camera'), 
 'iphone-14.jpg', FALSE),

('Redmi Note 13 Pro', 'redmi-note-13-pro', 3, 74900.00, 79900.00, 'Feature-packed Redmi with 200MP camera', 
 JSON_OBJECT('display', '6.67 AMOLED', 'processor', 'MediaTek Dimensity 7200', 'ram', '8GB', 'storage', '256GB', 'camera', '200MP Triple Camera'), 
 'redmi-note13-pro.jpg', TRUE),

('Redmi Note 12', 'redmi-note-12', 3, 54900.00, 59900.00, 'Affordable Redmi with great value for money', 
 JSON_OBJECT('display', '6.67 AMOLED', 'processor', 'Snapdragon 685', 'ram', '6GB', 'storage', '128GB', 'camera', '50MP Triple Camera'), 
 'redmi-note12.jpg', FALSE),

('Samsung Fast Charger 25W', 'samsung-fast-charger-25w', 4, 3500.00, 4000.00, 'Original Samsung fast charger with USB-C cable', 
 JSON_OBJECT('type', 'USB-C Fast Charger', 'power', '25W', 'compatibility', 'Samsung Galaxy Series', 'cable', 'USB-C to USB-C'), 
 'samsung-charger.jpg', FALSE),

('Wireless Earbuds', 'wireless-earbuds', 4, 4900.00, 5500.00, 'High-quality wireless earbuds with noise cancellation', 
 JSON_OBJECT('type', 'Bluetooth Earbuds', 'battery', '6 Hours + Case', 'connectivity', 'Bluetooth 5.0', 'features', 'Touch Control, Noise Cancellation'), 
 'wireless-earbuds.jpg', FALSE);

-- Create indexes for better performance
CREATE INDEX idx_products_price ON products(price);
CREATE INDEX idx_products_name ON products(name);
CREATE FULLTEXT INDEX idx_products_search ON products(name, description);

-- Create view for active products with category info
CREATE VIEW active_products AS
SELECT 
    p.*,
    c.name as category_name,
    c.slug as category_slug
FROM products p
JOIN categories c ON p.category_id = c.id
WHERE p.status = 'active' AND c.status = 'active';

-- Create view for featured products
CREATE VIEW featured_products AS
SELECT * FROM active_products WHERE featured = TRUE;