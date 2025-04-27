-- Database structure for hungry_panda

-- Create database (run this separately if needed)
-- CREATE DATABASE hungry_panda CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Menu categories table
CREATE TABLE menu_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT
);

-- Menu items table
CREATE TABLE menu_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_path VARCHAR(255),
    is_available BOOLEAN DEFAULT 1,
    FOREIGN KEY (category_id) REFERENCES menu_categories(category_id) ON DELETE SET NULL
);

-- Orders table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    delivery_address TEXT,
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Order items table
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    item_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES menu_items(item_id) ON DELETE CASCADE
);

-- Contact messages table
CREATE TABLE contacts (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert menu categories
INSERT INTO menu_categories (name, description) VALUES
('Appetizers', 'Delicious starters to begin your meal'),
('Main Dishes', 'Hearty and filling main courses'),
('Drinks', 'Refreshing beverages'),
('Desserts', 'Sweet treats to finish your meal');

-- Insert menu items
INSERT INTO menu_items (category_id, name, description, price, image_path, is_available) VALUES
(1, 'Edamame', 'Steamed young soybeans in pods, lightly salted', 4.95, 'assets/food/ede.jpg', 1),
(1, 'Gyoza', 'Pan-fried dumplings filled with pork and vegetables', 7.50, 'assets/food/gyoza.jpg', 1),
(1, 'Miso Soup', 'Traditional Japanese soup with tofu, seaweed, and green onions', 3.50, 'assets/food/miso.jpg', 1),
(1, 'Takoyaki', 'Ball-shaped Japanese snack filled with octopus', 8.98, 'assets/food/tako.jpeg', 1),
(2, 'Fried Rice', 'Stir-fried rice with vegetables and egg', 2.50, 'assets/food/fried-rice.jpg', 1),
(2, 'Bibimbap', 'Korean rice dish with assorted vegetables and egg', 10.95, 'assets/food/bibimbap.jpg', 1),
(2, 'Katsu Curry', 'Japanese curry with breaded pork cutlet and rice', 15.60, 'assets/food/katsu-curry.jpeg', 1),
(2, 'Meal Deal', 'Special meal combo with appetizer, main dish, and drink', 13.50, 'assets/food/mix-1.jpg', 1),
(3, 'Fanta', 'Orange fizzy drink', 2.00, 'assets/food/fanta.jpg', 1),
(3, 'Coke Zero', 'Sugar-free cola', 2.00, 'assets/food/coke-zero.jpg', 1),
(3, 'Pepsi', 'Refreshing cola drink', 2.00, 'assets/food/pepsi-max.png', 1),
(3, '7UP', 'Lemon-lime soda', 2.00, 'assets/food/7-up.jpg', 1);

-- Create admin user (password: admin123)
INSERT INTO users (username, password, email, is_admin) VALUES
('admin', '$2y$10$KWxbkEG6jQIJZpKXs3xVYeOjhvG.f6fPPiP00eZ8EOZVpmaDZ3TsC', 'admin@hungrypanda.com', 1);
