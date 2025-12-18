CREATE DATABASE IF NOT EXISTS crud_app;

USE crud_app;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO products (name, price, category, description) VALUES
('Laptop Dell XPS 13', 999.99, 'Electronics', 'High-performance laptop with Intel i7 processor and 16GB RAM'),
('Wireless Mouse', 29.99, 'Electronics', 'Ergonomic wireless mouse with 2.4GHz connection'),
('USB-C Cable', 14.99, 'Accessories', 'Durable USB-C charging and data transfer cable'),
('Monitor 4K', 399.99, 'Electronics', '27-inch 4K monitor with HDR support and USB-C'),
('Keyboard Mechanical', 149.99, 'Accessories', 'RGB mechanical keyboard with Cherry MX switches'),
('External SSD 1TB', 129.99, 'Storage', 'Portable external SSD with 1TB storage capacity'),
('Desk Lamp LED', 49.99, 'Office Supplies', 'Adjustable LED desk lamp with USB charging port'),
('Office Chair', 299.99, 'Furniture', 'Ergonomic office chair with lumbar support');
