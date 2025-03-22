-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:8111
-- Generation Time: Mar 22, 2025 at 05:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arts_stationary_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `image_path`, `created_at`) VALUES
(5, 'Notebook', 'images/categories/1742319889_notebook.jpg', '2025-03-18 17:44:49'),
(6, 'Pens', 'images/categories/1742320020_pens.jpg', '2025-03-18 17:47:00'),
(11, 'Gift Items', 'images/categories/1742386021_gift items.png', '2025-03-19 12:07:01'),
(12, 'Gift Items', 'images/categories/1742386113_gift items.jpg', '2025-03-19 12:08:33');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback_text` text NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `name`, `email`, `user_id`, `feedback_text`, `rating`, `submitted_at`) VALUES
(1, 'Muhammad Ahmed ', 'ahmed@gmail.com', 7, 'good work', 4, '2025-03-22 11:09:49'),
(2, 'Muhammad Ahmed ', 'admin@gmail.com', 7, 'nice', 3, '2025-03-22 11:16:39'),
(3, 'Muhammad Ahmed ', 'ahmed@gmail.com', 7, 'no', 2, '2025-03-22 11:19:00'),
(4, 'Muhammad Ahmed ', 'ahmed@gmail.com', 7, 'no way', 3, '2025-03-22 11:21:06');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` char(16) NOT NULL,
  `delivery_type` char(1) NOT NULL,
  `product_id` char(7) NOT NULL,
  `order_number` char(8) NOT NULL,
  `u_name` varchar(200) NOT NULL,
  `u_email` varchar(200) NOT NULL,
  `p_name` varchar(200) NOT NULL,
  `p_price` int(11) NOT NULL,
  `p_qty` int(11) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(200) NOT NULL DEFAULT 'pending',
  `u_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `delivery_type`, `product_id`, `order_number`, `u_name`, `u_email`, `p_name`, `p_price`, `p_qty`, `date_time`, `status`, `u_id`) VALUES
('c-1100003-67de07', 'c', '1100003', '67de07c8', 'Muhammad Ahmed', 'ahmed@gmail.com', 'Notebook', 340, 1, '2025-03-22 00:43:52', 'pending', 7),
('c-1100004-67de07', 'c', '1100004', '67de07c8', 'Muhammad Ahmed', 'ahmed@gmail.com', 'Notebook', 340, 2, '2025-03-22 00:43:52', 'pending', 7),
('c-1200001-67de98', 'c', '1200001', '67de9855', 'Muhammad Ahmed', 'm.ahmed.uh72@gmail.com', 'Gift', 20, 3, '2025-03-22 11:00:37', 'pending', 7),
('c-1200001-67dea0', 'c', '1200001', '67dea0eb', 'Shariq Khan', 'Shariq@123', 'Gift', 20, 2, '2025-03-22 11:37:15', 'pending', 6),
('c-1200002-67de07', 'c', '1200002', '67de07c8', 'Muhammad Ahmed', 'ahmed@gmail.com', 'Gift 1', 20, 1, '2025-03-22 00:43:52', 'pending', 7),
('c-1200002-67de08', 'c', '1200002', '67de0815', 'Muhammad Ahmed', 'm.ahmed.uh72@gmail.com', 'Gift 1', 20, 1, '2025-03-22 00:45:09', 'pending', 7),
('c-1200002-67de09', 'c', '1200002', '67de0989', 'Muhammad Ahmed', 'ahmed@gmail.com', 'Gift 1', 20, 1, '2025-03-22 00:51:21', 'pending', 7),
('c-1200002-67de96', 'c', '1200002', '67de968d', 'Muhammad Ahmed', 'admin@gmail.com', 'Gift 1', 20, 3, '2025-03-22 10:53:01', 'pending', 7),
('c-1200002-67de97', 'c', '1200002', '67de9719', 'Muhammad Ahmed', 'ahmed@gmail.com', 'Gift 1', 20, 3, '2025-03-22 10:55:21', 'pending', 7),
('p-1100001-67de8c', 'p', '1100001', '67de8cff', 'Muhammad Ahmed', 'ahmed@gmail.com', 'Notebook', 340, 2, '2025-03-22 10:12:15', 'pending', 7),
('p-1100003-67de07', 'p', '1100003', '67de070b', 'Muhammad Ahmed', 'ahmed@gmail.com', 'Notebook', 340, 1, '2025-03-22 00:40:43', 'pending', 7),
('p-1100004-67de07', 'p', '1100004', '67de070b', 'Muhammad Ahmed', 'ahmed@gmail.com', 'Notebook', 340, 2, '2025-03-22 00:40:43', 'pending', 7),
('p-1100004-67de8d', 'p', '1100004', '67de8df2', 'Muhammad Ahmed', 'admin@gmail.com', 'Notebook', 340, 2, '2025-03-22 10:16:18', 'pending', 7),
('p-1200001-67de8c', 'p', '1200001', '67de8c93', 'Muhammad Ahmed', 'ahmed@gmail.com', 'Gift', 20, 2, '2025-03-22 10:10:27', 'pending', 7),
('p-1200002-67de07', 'p', '1200002', '67de070b', 'Muhammad Ahmed', 'ahmed@gmail.com', 'Gift 1', 20, 1, '2025-03-22 00:40:43', 'pending', 7),
('p-1200002-67de91', 'p', '1200002', '67de914e', 'Muhammad Ahmed', 'admin@gmail.com', 'Gift 1', 20, 2, '2025-03-22 10:30:38', 'pending', 7);

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `before_insert_orders` BEFORE INSERT ON `orders` FOR EACH ROW BEGIN
    SET NEW.order_id = CONCAT(NEW.delivery_type, '-', NEW.product_id, '-', NEW.order_number);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postcode` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `order_notes` text DEFAULT NULL,
  `card_number` varchar(20) DEFAULT NULL,
  `expiry_date` varchar(10) DEFAULT NULL,
  `cvv` varchar(5) DEFAULT NULL,
  `check_number` varchar(50) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `payment_method`, `amount`, `payment_status`, `payment_date`, `first_name`, `last_name`, `country`, `address`, `city`, `state`, `postcode`, `phone`, `email`, `order_notes`, `card_number`, `expiry_date`, `cvv`, `check_number`, `bank_name`) VALUES
(1, 'cash_on_delivery', 60.00, 'pending', '2025-03-22 10:53:01', 'Muhammad ', 'Ahmed', 'pakistan', 'House No N1819/A metrovill 3rd Gulzar-e-hijri Karchi', 'Karachi', 'LA', '24455', '3120290828', 'admin@gmail.com', 'hi', NULL, NULL, NULL, NULL, NULL),
(2, 'credit_card', 60.00, 'pending', '2025-03-22 10:55:21', 'Muhammad ', 'Ahmed', 'pakistan', 'House No N1819/A metrovill 3rd Gulzar-e-hijri Karchi', 'Rawalpindi', 'LA', '4667', '3442681140', 'ahmed@gmail.com', 'hi', '4365465768789799', '02/28', '456', NULL, NULL),
(3, 'check_payment', 60.00, 'pending', '2025-03-22 11:00:37', 'Muhammad ', 'Ahmed', 'pakistan', 'House No N1819/A metrovill 3rd Gulzar-e-hijri Karchi', 'Rawalpindi', 'Pakistan/Sindh', '24455', '+9237843950954', 'm.ahmed.uh72@gmail.com', 'hi', NULL, NULL, NULL, '3494390540-540-540544554', 'faysal Bank'),
(4, 'cash_on_delivery', 40.00, 'pending', '2025-03-22 11:37:15', 'Shariq', 'Khan', 'pakistan', 'qjksdllsd', 'Rawalpindi', 'USA/LA', '364837', '3442681140', 'Shariq@123', 'good', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` char(7) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `warranty_period` int(11) DEFAULT 0,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `stock_quantity`, `category_id`, `warranty_period`, `image_path`, `created_at`) VALUES
('0500001', 'Notebook', 'Here\'s a **30-line description** for a **notebook**:  \r\n\r\n---\r\n\r\nA **notebook** is a must-have stationery item used for writing, drawing, and organizing thoughts. It comes in various sizes, from **pocket-sized** to **large A4 formats**. The pages can be **ruled, blank, or dotted**, catering to different needs like journaling, sketching, or note-taking.  \r\n\r\nNotebooks are often **spiral-bound, hardcover, or softcover**, providing durability and flexibility. **Spiral notebooks** allow easy page-turning, while **hardcover ones** offer sturdiness. Some come with **perforated pages**, making it easy to tear them out neatly.  \r\n\r\nThey are used by **students, professionals, and creatives** for various purposes. **Students** use them for lecture notes, **professionals** for meeting summaries, and **artists** for sketches. Many notebooks also have **index pages, bookmarks, and elastic closures** to keep them organized.  \r\n\r\nModern notebooks include **eco-friendly options** made from **recycled paper** and **water-resistant covers** for durability. Some even feature **pre-printed templates** like to-do lists, planners, and bullet journal grids.  \r\n\r\nAvailable in **plain, colorful, and custom designs**, notebooks can reflect personal style. Whether for **study, work, or creativity**, a good notebook is an essential tool for capturing ideas anytime, anywhere.\r\n\r\nAn **electric shock pen** is a classic prank gadget designed to surprise unsuspecting victims with a harmless but shocking jolt. It looks just like a regular pen, making it perfect for tricking friends, family, or colleagues. When someone tries to use it, they get a small electric shock instead of writing.  \r\n\r\nMade from durable plastic and metal, this pen is lightweight and easy to carry. The **shock mechanism** is safely enclosed inside, delivering a quick pulse upon pressing the top or attempting to remove the cap. The shock is **harmless** and **low voltage**, ensuring safety while providing a fun, unexpected reaction.  \r\n\r\nPerfect for pranksters, this gadget works great in office environments, schools, or social gatherings. It’s a fantastic conversation starter and an ideal gag gift. However, it is **not recommended for young children, individuals with heart conditions, or those with pacemakers**.  \r\n\r\nThis fun trick pen requires **small button-cell batteries**, which are usually included. The pen itself may or may not be functional for actual writing, depending on the design.  \r\n\r\nA **must-have prank toy**, this electric shock pen guarantees laughter and unforgettable reactions!', 70.00, 70, 5, 0, 'images/products/1742322116_Notebook1.jpg', '2025-03-18 18:21:56'),
('0600002', 'Electric pen 2', 'Electric Shock Pen – A Fun Prank Gadget!**  \r\n\r\nAn **electric shock pen** is a classic prank gadget designed to surprise unsuspecting victims with a harmless but shocking jolt. It looks just like a regular pen, making it perfect for tricking friends, family, or colleagues. When someone tries to use it, they get a small electric shock instead of writing.  \r\n\r\nMade from durable plastic and metal, this pen is lightweight and easy to carry. The **shock mechanism** is safely enclosed inside, delivering a quick pulse upon pressing the top or attempting to remove the cap. The shock is **harmless** and **low voltage**, ensuring safety while providing a fun, unexpected reaction.  \r\n\r\nPerfect for pranksters, this gadget works great in office environments, schools, or social gatherings. It’s a fantastic conversation starter and an ideal gag gift. However, it is **not recommended for young children, individuals with heart conditions, or those with pacemakers**.  \r\n\r\nThis fun trick pen requires **small button-cell batteries**, which are usually included. The pen itself may or may not be functional for actual writing, depending on the design.  \r\n\r\nA **must-have prank toy**, this electric shock pen guarantees laughter and unforgettable reactions!', 60.00, 70, 6, 0, 'images/products/1742321967_elec2.jpg', '2025-03-18 18:19:27'),
('0600003', 'Electric pen', 'hkk', 50.00, 23, 6, 5, 'images/products/1742398405_elec2.jpg', '2025-03-19 15:33:25'),
('0600004', 'Electric pen', 'hkk', 50.00, 23, 6, 5, 'images/products/1742398410_elec2.jpg', '2025-03-19 15:33:30'),
('0600005', 'Electric pen', 'hkk', 50.00, 23, 6, 5, 'images/products/1742398413_elec2.jpg', '2025-03-19 15:33:33'),
('0600006', 'Electric pen', 'hkk', 50.00, 23, 6, 5, 'images/products/1742398416_elec2.jpg', '2025-03-19 15:33:36'),
('1100001', 'Notebook', 'gjj', 340.00, 56, 11, 6, 'images/products/1742398463_pens.jpg', '2025-03-19 15:34:23'),
('1100002', 'Notebook', 'gjj', 340.00, 56, 11, 6, 'images/products/1742398466_pens.jpg', '2025-03-19 15:34:26'),
('1100003', 'Notebook', 'gjj', 340.00, 56, 11, 6, 'images/products/1742398469_pens.jpg', '2025-03-19 15:34:29'),
('1100004', 'Notebook', 'gjj', 340.00, 56, 11, 6, 'images/products/1742398472_pens.jpg', '2025-03-19 15:34:32'),
('1200001', 'Gift', 'hi', 20.00, 10, 12, 1, 'images/products/1742386180_gift items.jpg', '2025-03-19 12:09:40'),
('1200002', 'Gift 1', 'hi', 20.00, 10, 12, 1, 'images/products/1742386347_gift items.png', '2025-03-19 12:12:27');

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `before_insert_products` BEFORE INSERT ON `products` FOR EACH ROW BEGIN
    DECLARE last_number INT;
    DECLARE category_prefix CHAR(2);

    -- Fetch the last product number from the same category
    SELECT COALESCE(MAX(CAST(SUBSTRING(product_id, 3, 5) AS UNSIGNED)), 0) + 1 
    INTO last_number 
    FROM Products 
    WHERE category_id = NEW.category_id;

    -- Get the category_id as a 2-digit prefix
    SET category_prefix = LPAD(NEW.category_id, 2, '0');

    -- Set the product_id as "XXYYYYY" format (Category Code + 5-digit Product Number)
    SET NEW.product_id = CONCAT(category_prefix, LPAD(last_number, 5, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `return_id` int(11) NOT NULL,
  `order_id` char(16) NOT NULL,
  `product_id` char(7) NOT NULL,
  `reason` text NOT NULL,
  `return_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `return_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`return_id`, `order_id`, `product_id`, `reason`, `return_status`, `return_date`) VALUES
(1, 'c-1100003-67de07', '0500001', 'defected', 'pending', '2025-03-22 11:41:25'),
(3, 'c-1200001-67dea0', '1200001', 'defected', 'pending', '2025-03-22 12:09:52');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` char(7) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `review_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `product_id`, `user_name`, `rating`, `review_text`, `created_at`) VALUES
(1, '1200002', 'Ahmed', 4, 'very good', '2025-03-20 14:47:39'),
(2, '1200002', 'ali', 1, 'very bad', '2025-03-20 14:48:03'),
(3, '1200002', 'khan', 3, 'average', '2025-03-20 14:48:34'),
(4, '1200002', 'latif', 4, 'good', '2025-03-20 14:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `stock_updates`
--

CREATE TABLE `stock_updates` (
  `stock_update_id` int(11) NOT NULL,
  `product_id` char(7) NOT NULL,
  `change_in_quantity` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `update_reason` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `user_type` enum('admin','employee','customer') NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `full_name`, `email`, `phone`, `user_type`, `address`, `created_at`) VALUES
(6, 'Shariq2', '$2y$10$yCQQfPi/kIpDTUWWl1a14ul8z.jj5ER1XoVeHjSe/9V6/2jmjWPPi', 'Shariq Khan', 'admin@gmail.com', '3120290828', 'customer', 'Karachi', '2025-03-19 14:45:02'),
(7, 'Ahmed', '$2y$10$v5u2rqNlYPcYQmi.dcZTMeDMysstyW1SBt8aSXfO8w/TG238kPqaK', 'Muhammad Ahmed', 'ahmed@gmail.com', '03442681140', 'customer', 'karachi', '2025-03-21 23:07:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `stock_updates`
--
ALTER TABLE `stock_updates`
  ADD PRIMARY KEY (`stock_update_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_updates`
--
ALTER TABLE `stock_updates`
  MODIFY `stock_update_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `returns_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_updates`
--
ALTER TABLE `stock_updates`
  ADD CONSTRAINT `stock_updates_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `stock_updates_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
