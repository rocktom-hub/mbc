-- MBC (Malek Bateta Center) Database Dump for phpMyAdmin
-- Database: `mbc_db`

CREATE DATABASE IF NOT EXISTS `mbc_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mbc_db`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phone` VARCHAR(20) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
  `address` TEXT DEFAULT NULL,
  `status` ENUM('active', 'blocked') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE IF NOT EXISTS `foods` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `is_available` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(30) NOT NULL UNIQUE,
  `user_id` INT DEFAULT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_email` VARCHAR(100) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `delivery_address` TEXT NOT NULL,
  `order_type` ENUM('Home Delivery', 'Takeaway', 'Dine-in') DEFAULT 'Home Delivery',
  `payment_method` ENUM('Cash on Delivery', 'UPI', 'Credit/Debit Card') DEFAULT 'Cash on Delivery',
  `subtotal` DECIMAL(10,2) NOT NULL,
  `tax_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `delivery_fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('Pending', 'Preparing', 'Out for Delivery', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Pending',
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `food_id` INT DEFAULT NULL,
  `food_name` VARCHAR(150) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `quantity` INT NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `rating` INT NOT NULL DEFAULT 5,
  `comment` TEXT NOT NULL,
  `status` ENUM('approved', 'pending', 'rejected') DEFAULT 'approved',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `category` VARCHAR(100) DEFAULT 'General',
  `image` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `subject` VARCHAR(150) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('unread', 'read') DEFAULT 'unread',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `setting_key` VARCHAR(50) PRIMARY KEY,
  `setting_value` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- SEED DATA INSERTION
-- --------------------------------------------------------

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('restaurant_name', 'MBC (Malek Bateta Center)'),
('tagline', 'Traditional Taste of Dhoraji Since 1983'),
('phone', '+91 98765 43210'),
('email', 'info@malekbatetacenter.com'),
('address', '123 Main Road, Dhoraji, Gujarat, 360410'),
('opening_hours', '10:00 AM - 10:00 PM'),
('delivery_fee', '30.00'),
('tax_rate', '5.00')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `address`, `status`) VALUES
(1, 'Admin User', 'admin@mbc.com', '+91 98765 43210', '$2y$10$4.6G/YVzYnIqL4dM02kC4u9aE/.yO/t4Z82T1/nZl9p3U/2tY8y0y', 'admin', 'MBC Head Office, Dhoraji', 'active'),
(2, 'Aarav Patel', 'aarav@gmail.com', '+91 98989 89898', '$2y$10$4.6G/YVzYnIqL4dM02kC4u9aE/.yO/t4Z82T1/nZl9p3U/2tY8y0y', 'customer', 'Station Road, Dhoraji, Gujarat', 'active'),
(3, 'Priya Sharma', 'priya@gmail.com', '+91 97777 66666', '$2y$10$4.6G/YVzYnIqL4dM02kC4u9aE/.yO/t4Z82T1/nZl9p3U/2tY8y0y', 'customer', 'MG Road, Dhoraji, Gujarat', 'active')
ON DUPLICATE KEY UPDATE `id`=`id`;

INSERT INTO `categories` (`id`, `name`, `description`, `image`, `status`) VALUES
(1, 'Bateta Special', 'Signature traditional Dhoraji Bateta dishes made with authentic spices', 'cat_bateta.jpg', 'active'),
(2, 'Combos & Meals', 'Value combos served with gravy, bhungla, and cold drinks', 'cat_combos.jpg', 'active'),
(3, 'Snacks & Sides', 'Crunchy bhungla, fried items, and savory appetizers', 'cat_snacks.jpg', 'active'),
(4, 'Beverages', 'Refreshing cold drinks, buttermilk, and sodas', 'cat_drinks.jpg', 'active')
ON DUPLICATE KEY UPDATE `id`=`id`;

INSERT INTO `foods` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_featured`, `is_available`) VALUES
(1, 1, 'Dhoraji Bateta Special', 'Classic spicy garlic potato curry served with spicy tangy red gravy and crisp bhungla.', 80.00, 'dhoraji_bateta.jpg', 1, 1),
(2, 1, 'Chana Bateta', 'Rich savory combination of boiled chickpeas and crushed potatoes topped with coriander & tamarind sauce.', 90.00, 'chana_bateta.jpg', 1, 1),
(3, 1, 'Sweet Bateta', 'Mildly spiced sweet-tangy gravy potato special prepared with secret Dhoraji spice blend.', 85.00, 'sweet_bateta.jpg', 1, 1),
(4, 2, 'Bhungla Bateta Combo', 'Large portion of Special Dhoraji Bateta served with 12 pcs crunchy Bhungla and 1 Chhas.', 120.00, 'bhungla_combo.jpg', 1, 1),
(5, 3, 'Extra Bhungla Packet', '15 pieces of crispy golden fried yellow pipe bhungla.', 30.00, 'extra_bhungla.jpg', 0, 1),
(6, 3, 'Spicy Masala Wings', 'Deep-fried spicy chicken wings marinated in authentic Dhoraji spices.', 140.00, 'masala_wings.jpg', 1, 1),
(7, 4, 'Chhas (Masala Buttermilk)', 'Chilled homemade digestive buttermilk spiced with roasted cumin and mint.', 20.00, 'chhas.jpg', 0, 1),
(8, 4, 'Cold Drink (300ml)', 'Assorted chilled carbonated soft drinks.', 30.00, 'cold_drink.jpg', 0, 1)
ON DUPLICATE KEY UPDATE `id`=`id`;

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `customer_name`, `customer_email`, `customer_phone`, `delivery_address`, `order_type`, `payment_method`, `subtotal`, `tax_amount`, `delivery_fee`, `total_amount`, `status`, `created_at`) VALUES
(1001, 'MBC-849201', 2, 'Aarav Patel', 'aarav@gmail.com', '+91 98989 89898', 'Station Road, Dhoraji, Gujarat', 'Home Delivery', 'Cash on Delivery', 200.00, 10.00, 30.00, 240.00, 'Completed', '2026-09-14 14:30:00'),
(1002, 'MBC-849202', 2, 'Aarav Patel', 'aarav@gmail.com', '+91 98989 89898', 'Station Road, Dhoraji, Gujarat', 'Home Delivery', 'UPI', 170.00, 8.50, 30.00, 208.50, 'Pending', '2026-09-15 10:15:00'),
(1003, 'MBC-849203', 3, 'Priya Sharma', 'priya@gmail.com', '+91 97777 66666', 'MG Road, Dhoraji, Gujarat', 'Takeaway', 'Cash on Delivery', 140.00, 7.00, 0.00, 147.00, 'Preparing', '2026-09-15 11:45:00'),
(1004, 'MBC-849204', 2, 'Aarav Patel', 'aarav@gmail.com', '+91 98989 89898', 'Station Road, Dhoraji, Gujarat', 'Home Delivery', 'UPI', 160.00, 8.00, 30.00, 198.00, 'Completed', '2026-09-12 19:20:00')
ON DUPLICATE KEY UPDATE `id`=`id`;

INSERT INTO `order_items` (`order_id`, `food_id`, `food_name`, `price`, `quantity`, `total_price`) VALUES
(1001, 1, 'Dhoraji Bateta Special', 80.00, 2, 160.00),
(1001, 7, 'Chhas (Masala Buttermilk)', 20.00, 2, 40.00),
(1002, 2, 'Chana Bateta', 90.00, 1, 90.00),
(1002, 1, 'Dhoraji Bateta Special', 80.00, 1, 80.00),
(1003, 6, 'Spicy Masala Wings', 140.00, 1, 140.00),
(1004, 4, 'Bhungla Bateta Combo', 120.00, 1, 120.00),
(1004, 8, 'Cold Drink (300ml)', 30.00, 1, 30.00)
ON DUPLICATE KEY UPDATE `id`=`id`;

INSERT INTO `reviews` (`user_id`, `customer_name`, `rating`, `comment`, `status`) VALUES
(2, 'Aarav Patel', 5, 'The authentic taste of Dhoraji Bateta! Red gravy and crispy bhungla combo is unmatched!', 'approved'),
(3, 'Priya Sharma', 5, 'Best fast food spot in town since childhood. Very hygienic and fast delivery.', 'approved')
ON DUPLICATE KEY UPDATE `id`=`id`;

INSERT INTO `gallery` (`title`, `category`, `image`) VALUES
('Signature Dhoraji Bateta Bowl', 'Dishes', 'gallery1.jpg'),
('Fresh Ingredients & Spices', 'Kitchen', 'gallery2.jpg'),
('Bhungla Bateta Special Serving', 'Dishes', 'gallery3.jpg'),
('Our Traditional Counter', 'Store', 'gallery4.jpg')
ON DUPLICATE KEY UPDATE `id`=`id`;
