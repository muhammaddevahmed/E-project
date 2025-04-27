-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2025 at 03:15 PM
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
(38, 'Sports Goods', 'images/categories/1744391527_67f94d6743bac.jpg', '2025-04-11 17:11:41'),
(39, 'School Bags', 'images/categories/cat_67f94d7bc96338.85020885.jpg', '2025-04-11 17:12:27'),
(40, 'Stationary Items', 'images/categories/cat_67f94db55fbad6.95442037.jpg', '2025-04-11 17:13:25'),
(41, 'Beauty Products', 'images/categories/cat_67f94dc1286602.40167716.jpg', '2025-04-11 17:13:37'),
(42, 'Wallets', 'images/categories/cat_67f94dca068f93.47291578.jpg', '2025-04-11 17:13:46'),
(43, 'Hand Bags', 'images/categories/cat_67f94dd67d7ca6.15525991.jpg', '2025-04-11 17:13:58'),
(44, 'Files', 'images/categories/cat_67f94ddeb9e078.14095804.jpg', '2025-04-11 17:14:06'),
(45, 'Dolls', 'images/categories/cat_67f94de91240e9.64676002.jpg', '2025-04-11 17:14:17'),
(46, 'Greeting Cards', 'images/categories/cat_67f94df8d21355.11492890.jpg', '2025-04-11 17:14:32'),
(47, 'Gifts Packages', 'images/categories/cat_67f94e04eccb04.97492310.jpg', '2025-04-11 17:14:44'),
(48, 'Arts and Crafts', 'images/categories/cat_67f94e11a832c9.45901203.jpg', '2025-04-11 17:14:57');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `delivery_id` int(11) NOT NULL,
  `order_id` char(16) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` char(7) NOT NULL,
  `delivery_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `estimated_delivery_date` date DEFAULT NULL,
  `actual_delivery_date` date DEFAULT NULL,
  `delivery_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `read_status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`delivery_id`, `order_id`, `user_id`, `product_id`, `delivery_status`, `estimated_delivery_date`, `actual_delivery_date`, `delivery_notes`, `created_at`, `updated_at`, `read_status`) VALUES
(3, 'c-4800005-67f964', 17, '4800005', 'delivered', '2025-04-26', '2025-04-26', 'Soon', '2025-04-11 18:58:42', '2025-04-25 12:52:57', 1),
(4, 'c-3800002-67f967', 17, '3800002', 'shipped', '2025-04-19', '2025-04-25', 'hi', '2025-04-11 19:04:20', '2025-04-22 12:01:39', 1),
(5, 'c-3800005-68076e', 17, '3800005', 'delivered', '2025-05-03', '2025-05-05', 'good', '2025-04-22 10:28:40', '2025-04-25 12:53:36', 1),
(6, 'p-4000003-680b6f', 17, '4000003', 'delivered', '2025-04-29', '2025-04-30', 'soon', '2025-04-25 11:18:23', '2025-04-25 11:59:41', 0),
(7, 'c-3900001-680b7b', 17, '3900001', 'shipped', '2025-04-30', '2025-05-01', 'soon', '2025-04-25 12:09:06', '2025-04-25 12:50:15', 0),
(8, 'c-3800005-680e1f', 17, '3800005', 'processing', '2025-04-28', '2025-04-29', NULL, '2025-04-27 12:21:17', '2025-04-27 12:21:17', 0),
(9, 'c-4800004-680e1f', 17, '4800004', 'delivered', '2025-04-29', '2025-04-30', NULL, '2025-04-27 12:21:34', '2025-04-27 12:21:34', 0);

--
-- Triggers `deliveries`
--
DELIMITER $$
CREATE TRIGGER `after_delivery_status_update` AFTER UPDATE ON `deliveries` FOR EACH ROW BEGIN
    IF NEW.delivery_status != OLD.delivery_status THEN
        INSERT INTO notifications (user_id, title, message, related_table, related_id)
        VALUES (
            NEW.user_id,
            'Delivery Status Updated',
            CONCAT('Your delivery for order #', NEW.order_id, ' is now ', NEW.delivery_status),
            'deliveries',
            NEW.delivery_id
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `user_id`, `role`) VALUES
(1, 18, 'manager');

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
(7, 'Muhammad Ahmed ', 'ahmed@gmail.com', 17, 'Great', 5, '2025-04-11 18:53:35');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `related_table` varchar(50) NOT NULL,
  `related_id` varchar(50) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `u_id` int(11) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `delivery_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `read_status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `delivery_type`, `product_id`, `order_number`, `u_name`, `u_email`, `p_name`, `p_price`, `p_qty`, `date_time`, `status`, `u_id`, `payment_id`, `delivery_status`, `read_status`) VALUES
('c-3800002-67f967', 'c', '3800002', '67f96715', 'Muhammad Ahmed', 'ahmed@gmail.com', 'Pakistan Champions Trophy 2025 Jersey', 50, 2, '2025-04-11 19:01:41', 'accepted', 17, 49, 'shipped', 1),
('c-3800005-68076e', 'c', '3800005', '68076ee6', 'Muhammad Ahmed', 'ahmed@gmail.com', 'VelocityPro Football Cleats', 40, 3, '2025-04-22 10:26:46', 'accepted', 17, 50, 'delivered', 1),
('c-3800005-680e1f', 'c', '3800005', '680e1f01', 'Muhammad Ahmed', 'ahmed@gmail.com', 'VelocityPro Football Cleats', 40, 3, '2025-04-27 12:11:45', 'accepted', 17, 54, 'processing', 0),
('c-3900001-680b7b', 'c', '3900001', '680b7b05', 'Muhammad Ahmed', 'ahmed@gmail.com', 'StarPop Backpack', 50, 3, '2025-04-25 12:07:33', 'accepted', 17, 52, 'shipped', 0),
('c-4400004-680e09', 'c', '4400004', '680e0965', 'Muhammad Ahmed', 'ahmed@gmail.com', 'ClearView Transparent File', 13, 2, '2025-04-27 10:39:33', 'accepted', 17, 53, 'pending', 0),
('c-4400005-680e09', 'c', '4400005', '680e0965', 'Muhammad Ahmed', 'ahmed@gmail.com', 'ZipGuard Executive File', 14, 3, '2025-04-27 10:39:33', 'accepted', 17, 53, 'pending', 0),
('c-4800002-680e09', 'c', '4800002', '680e0965', 'Muhammad Ahmed', 'ahmed@gmail.com', 'Adventure Awaits – Cartoon Painting', 80, 2, '2025-04-27 10:39:33', 'accepted', 17, 53, 'pending', 0),
('c-4800004-680e1f', 'c', '4800004', '680e1f01', 'Muhammad Ahmed', 'ahmed@gmail.com', 'Toon Town Fun – Cartoon Painting', 90, 2, '2025-04-27 12:11:45', 'accepted', 17, 54, 'delivered', 0),
('c-4800005-67f964', 'c', '4800005', '67f964e3', 'Muhammad Ahmed', 'ahmed@gmail.com', ' Dreamy Creatures – Cartoon Painting', 100, 2, '2025-04-11 18:52:19', 'accepted', 17, 48, 'delivered', 1),
('p-4000003-680b6f', 'p', '4000003', '680b6f35', 'Muhammad Ahmed', 'ahmed@gmail.com', 'CalcMaster Scientific Calculator', 30, 3, '2025-04-25 11:17:09', 'accepted', 17, 51, 'delivered', 0);

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `after_order_status_update` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF NEW.status != OLD.status OR NEW.delivery_status != OLD.delivery_status THEN
        INSERT INTO notifications (user_id, title, message, related_table, related_id)
        VALUES (
            NEW.u_id,
            'Order Status Updated',
            CONCAT('Your order #', NEW.order_id, ' status is now ', NEW.status, ' and delivery status is ', NEW.delivery_status),
            'orders',
            NEW.order_id
        );
    END IF;
END
$$
DELIMITER ;
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
  `bank_name` varchar(100) DEFAULT NULL,
  `read_status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `payment_method`, `amount`, `payment_status`, `payment_date`, `first_name`, `last_name`, `country`, `address`, `city`, `state`, `postcode`, `phone`, `email`, `order_notes`, `card_number`, `expiry_date`, `cvv`, `check_number`, `bank_name`, `read_status`) VALUES
(48, 'cash_on_delivery', 200.00, 'completed', '2025-04-11 18:52:19', 'Muhammad', 'Ahmed', 'pakistan', 'House No N1819/A metrovill 3rd Gulzar-e-hijri Karchi', 'karachi', 'Sindh', '4667', '+923442681140', 'ahmed@gmail.com', 'hi', NULL, NULL, NULL, NULL, NULL, 1),
(49, 'cash_on_delivery', 100.00, 'completed', '2025-04-11 19:01:41', 'Muhammad', 'Ahmed', 'pakistan', 'House No N1819/A metrovill 3rd Gulzar-e-hijri Karchi', 'Karachi', 'Sindh', '24455', '+923442681140', 'ahmed@gmail.com', 'hi', NULL, NULL, NULL, NULL, NULL, 1),
(50, 'credit_card', 120.00, 'completed', '2025-04-22 10:26:46', 'Muhammad', 'Ahmed', 'Pakistan', 'Karachi', 'Karachi', 'sindh', '537384', '7843894390439', 'ahmed@gmail.com', 'good', '4363794040578494', '12/30', '567', NULL, NULL, 1),
(51, 'paypal', 90.00, 'completed', '2025-04-25 11:17:09', 'Muhammad', 'Ahmed', 'Pakistan', 'Karachi', 'Karachi', 'sindh', '537384', '7843894390439', 'ahmed@gmail.com', 'hi', NULL, NULL, NULL, NULL, NULL, 0),
(52, 'cash_on_delivery', 150.00, 'completed', '2025-04-25 12:07:33', 'Muhammad', 'Ahmed', 'Pakistan', 'Karachi', 'Karachi', 'sindh', '537384', '7843894390439', 'ahmed@gmail.com', 'good', NULL, NULL, NULL, NULL, NULL, 0),
(53, 'cash_on_delivery', 193.80, 'failed', '2025-04-27 10:39:33', 'Muhammad', 'Ahmed', 'Pakistan', 'Karachi', 'Karachi', 'sindh', '537384', '7843894390439', 'ahmed@gmail.com', 'hi', NULL, NULL, NULL, NULL, NULL, 0),
(54, 'cash_on_delivery', 255.00, 'completed', '2025-04-27 12:11:45', 'Muhammad', 'Ahmed', 'Pakistan', 'Karachi', 'Karachi', 'sindh', '537384', '7843894390439', 'ahmed@gmail.com', '', NULL, NULL, NULL, NULL, NULL, 0);

--
-- Triggers `payments`
--
DELIMITER $$
CREATE TRIGGER `after_payment_status_update` AFTER UPDATE ON `payments` FOR EACH ROW BEGIN
    IF NEW.payment_status != OLD.payment_status THEN
        -- Get user_id from orders table
        SET @user_id = (SELECT u_id FROM orders WHERE payment_id = NEW.payment_id LIMIT 1);
        
        INSERT INTO notifications (user_id, title, message, related_table, related_id)
        VALUES (
            @user_id,
            'Payment Status Updated',
            CONCAT('Your payment for order #', (SELECT order_id FROM orders WHERE payment_id = NEW.payment_id LIMIT 1), ' is now ', NEW.payment_status),
            'payments',
            NEW.payment_id
        );
    END IF;
END
$$
DELIMITER ;

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
('3800001', 'Cricket Pro Kit Bag', 'The Cricket Pro Kit Bag is the ultimate companion for any cricketer, offering both style and functionality. Designed with durable, high-quality fabric, this bag can withstand the wear and tear of regular sports use. Spacious enough to carry all your cricket essentials, it features multiple compartments for bats, pads, gloves, and protective gear. The large main compartment holds your bat securely, while side pockets are perfect for storing smaller items like gloves, caps, and socks. A specialized pocket for shoes ensures your gear stays organized and odor-free. The bag’s reinforced bottom helps maintain structure and protect your equipment. Padded shoulder straps and a top handle allow for easy and comfortable carrying, whether you’re heading to practice or a match. Its ventilated design promotes airflow, keeping your gear fresh. The robust zippers ensure that everything stays secure, while the water-resistant exterior keeps your equipment dry on rainy days. Available in vibrant, sporty colors, the Cricket Pro Kit Bag is both eye-catching and practical. With plenty of room for all your cricket gear, it’s perfect for players at every level. The bag is easy to clean and maintain, ensuring long-lasting use. Whether you\'re a beginner or a professional, this bag will help keep you organized and ready for the game. It also features a name label, so you never have to worry about losing your bag. Compact enough for easy storage, yet roomy enough for all your gear, the Cricket Pro Kit Bag is the ideal choice for any cricketer.', 70.00, 5, 38, 2, 'images/products/1744392883_Sports 1.jpg', '2025-04-11 17:34:43'),
('3800002', 'Pakistan Champions Trophy 2025 Jersey', 'The Pakistan Champions Trophy 2025 Shirt is designed to capture the essence of national pride and the spirit of cricket. Featuring a bold and striking green color scheme, this shirt represents the energy, passion, and resilience of the Pakistani cricket team. Made from high-quality, breathable fabric, it ensures comfort and durability, whether you\'re cheering in the stands or playing your own match. The shirt is adorned with the iconic Pakistan Cricket Board (PCB) crest on the chest, alongside the official Champions Trophy 2025 logo, making it a true collector\'s item. The slim-fit design offers a modern look while maintaining flexibility and freedom of movement. A lightweight, moisture-wicking material keeps you cool and dry, even during intense moments. The short-sleeve style and round neckline make it versatile for various occasions, from casual outings to sporting events. Ideal for both players and fans, this shirt combines performance with national pride. Its sleek design includes subtle accents of white and gold to reflect the elegance of the tournament. The vibrant green color symbolizes the unity and strength of the team, inspiring fans to support Pakistan on its journey to victory. Whether you\'re wearing it for game day or as a memento of the tournament, the Pakistan Champions Trophy 2025 Shirt is a must-have for cricket enthusiasts. With its timeless design, this shirt allows fans to show their unwavering support in style. Available in a range of sizes, it fits comfortably and looks great on all ages. Don’t miss out on this iconic piece to commemorate Pakistan’s participation in the 2025 Champions Trophy.', 50.00, 28, 38, 2, 'images/products/1744392976_Sports 2.jpg', '2025-04-11 17:36:16'),
('3800003', 'Cricket Equipments', 'Hard ball cricket gears are essential for any cricketer looking to play the game at a competitive level, offering safety, performance, and durability. These gears are specifically designed to protect players from the impact of hard cricket balls, ensuring they can perform at their best while staying safe. The gear set typically includes a high-quality bat, protective gloves, a helmet, pads, and thigh guards, all crafted from premium materials. The cricket bat is designed with a strong willow handle and a large sweet spot, giving players the power and control they need. The gloves are made with leather palms and soft inner linings for maximum comfort and grip, while the helmets feature strong faceguards for protection from bouncers and fast deliveries. The thigh pads are lightweight and offer high protection without compromising on movement. Shin pads are available in different sizes to ensure a perfect fit, offering comprehensive protection for the legs. A chest guard and arm guard complete the set, providing full protection for the body. The materials used are not only durable but also lightweight, ensuring that the player doesn’t feel weighed down during play. The gear’s design also prioritizes ventilation, with breathable fabrics used to prevent overheating. Whether you\'re an amateur or professional cricketer, these hard ball cricket gears are designed to enhance performance, offer safety, and last through rigorous use on the field. Ideal for all levels of cricket, these gears offer a combination of innovation and tradition, allowing players to focus on the game with confidence.', 100.00, 10, 38, 5, 'images/products/1744393069_sports 3.jpg', '2025-04-11 17:37:49'),
('3800004', ' ProStrike Football', 'The ProStrike Football is built for players who demand precision, durability, and superior performance. Crafted with a high-quality synthetic leather cover, this football is designed to withstand the toughest of matches, whether on grass, turf, or indoor surfaces. Its advanced grip technology ensures that players have better control, whether they’re passing, kicking, or making quick moves on the field. The ball’s bladder is made from durable latex, providing excellent air retention and maintaining shape over extended use. The ProStrike Football is engineered for optimal bounce, allowing for consistent play and accurate passes. Its vibrant, eye-catching design with bold patterns makes it easy to spot in any condition, while the textured surface ensures a firm grip, even in wet weather. Whether you’re training or playing in a competitive game, this ball offers superior flight stability, making long-distance kicks more accurate and precise. The ball\'s stitched seams provide enhanced durability and prevent wear and tear, ensuring it stays in top condition game after game. Available in standard sizes for all levels of play, from youth leagues to professional matches, the ProStrike Football offers the perfect balance of performance and value. Whether you\'re practicing your footwork, working on passing accuracy, or gearing up for a match, the ProStrike Football is the ideal choice for players who want to elevate their game. Lightweight and responsive, it offers the perfect feel, making it a must-have for any football enthusiast.', 40.00, 10, 38, 2, 'images/products/1744393171_Sports 4.jpg', '2025-04-11 17:39:31'),
('3800005', 'VelocityPro Football Cleats', 'The VelocityPro Football Cleats are engineered to deliver top-tier performance on the field, ensuring you stay quick, agile, and comfortable during every play. Crafted with a lightweight yet durable design, these cleats provide superior traction, allowing you to accelerate, decelerate, and change directions with ease. The cleats feature a high-quality synthetic upper that offers flexibility and support while reducing weight, giving you the freedom to move swiftly without compromising on stability. The innovative stud configuration on the outsole ensures optimal grip on both grass and turf, preventing slippage during quick cuts and sprints. The cushioned insole and padded ankle collar provide additional comfort, reducing the risk of blisters and foot fatigue, so you can focus on the game. A breathable mesh lining helps to keep your feet cool and dry, even during intense gameplay, by wicking away moisture and promoting airflow. The sleek, modern design with bold accents gives you a stylish edge on the field, while the reinforced toe cap adds durability and protection against wear and tear. Whether you’re an attacking player looking for speed or a defender relying on stability, the VelocityPro Football Cleats are designed to meet the needs of all positions. The shoes come in various sizes to ensure the perfect fit for players of all ages and skill levels. With their lightweight construction, high-performance features, and stylish design, these cleats are the ultimate choice for any serious football player. Get ready to elevate your game and dominate the field with the VelocityPro Football Cleats.\r\n\r\n', 40.00, 4, 38, 3, 'images/products/1744393225_Sports 5.jpg', '2025-04-11 17:40:25'),
('3900001', 'StarPop Backpack', 'StarPop Backpack is a burst of color and cool design, made to brighten every school day. With bold star patterns and vibrant trims, it’s perfect for kids who love to stand out. The bag is crafted from durable, water-resistant fabric to handle every adventure. Padded shoulder straps offer all-day comfort, even on the busiest days. A spacious main compartment fits books, folders, and a laptop sleeve. The front zip pocket is great for snacks, pens, and small essentials. Two side mesh pockets easily hold water bottles or umbrellas. A reinforced top handle allows for easy grab-and-go moments. The zipper is smooth and sturdy, designed for daily use. StarPop’s fun design boosts confidence and self-expression. The interior lining is easy to clean and wipe down. Bright interior accents make finding things inside a breeze. Lightweight and comfortable, it’s ideal for school, travel, or playdates. The back panel is breathable for extra comfort on warm days. Reflective strips add safety for early mornings or late afternoons. Great for kids aged 6 and up. StarPop Backpack mixes style and practicality effortlessly. Available in multiple colors to suit every personality. Whether in class or on the go, StarPop is ready for anything. A fun, reliable bag kids will love to carry every day.', 50.00, 7, 39, 3, 'images/products/1744392678_67f951e631982.jpg', '2025-04-11 17:26:01'),
('3900002', 'DinoDash Bag', 'DinoDash Bag is perfect for young adventurers who dream of roaring through school in dino-style. Covered in bold dinosaur prints and earthy colors, it captures attention instantly. The water-resistant outer layer keeps contents safe from rain or spills. Padded straps and back support ensure comfort during long school days. A roomy main compartment fits books, lunchboxes, and supplies with ease. Front and side pockets hold essentials like pencils, sanitizer, or snacks. Durable zippers make opening and closing smooth and simple. The top loop handle is great for hanging or carrying. DinoDash is lightweight but strong enough for everyday use. Easy-to-clean inside and out—perfect for messy explorers. Designed with safety in mind, it includes reflective detailing. Inner name label for personalization helps avoid mix-ups. DinoDash sparks imagination with every wear. It’s built to survive both classrooms and playgrounds. The dino pattern is fun but never over-the-top. Ideal for kids aged 5 to 10. Whether heading to school or a playdate, DinoDash adds adventure. Great as a birthday or back-to-school gift. Helps kids feel confident and prepared. Roar into learning with DinoDash—where fun meets function.', 40.00, 10, 39, 2, 'images/products/1744392395_Bag 2.jpg', '2025-04-11 17:26:35'),
('3900003', 'SkyWhirl Backpack', 'SkyWhirl Backpack is a dreamy school companion with soft clouds, rainbows, and floating balloons. It’s perfect for kids who love daydreams and gentle designs. The smooth, pastel finish gives it a calming, magical look. Crafted with lightweight, durable materials to last through every school year. Large enough to hold books, notebooks, and even a change of clothes. The padded laptop sleeve protects electronics safely. Mesh side pockets hold water bottles for easy access. A smaller front pocket keeps essentials neatly organized. Ergonomic straps reduce shoulder strain for growing kids. The breathable back panel adds comfort for long walks or rides. Easy-glide zippers make access smooth and kid-friendly. Inside lining is soft and wipe-clean for easy maintenance. A name tag slot inside makes it personal and unique. SkyWhirl encourages imagination and creativity wherever it goes. Subtle glitter details give it a magical touch. Ideal for kids aged 6 and up. Lightweight yet roomy enough for every day. Pairs well with matching lunchboxes and accessories. Spark joy and wonder with every trip to school. SkyWhirl is more than a backpack—it’s a cloud of comfort and fun.', 60.00, 12, 39, 3, 'images/products/1744392447_67f950ff8f0e7.jpg', '2025-04-11 17:27:10'),
('3900004', 'RoboRush Bag', 'RoboRush Bag is designed for tech-loving kids with a sleek, futuristic look. The robotic print and neon accents make it stand out in any classroom. Built from strong, weather-resistant fabric to protect inside contents. Spacious interior fits books, gadgets, and folders with ease. Built-in tech pouch for tablets or small laptops. Front organizer pocket for pencils, chargers, and cards. Side compartments for bottles or mini umbrellas. Reflective details for safety during dark mornings. Padded straps and back support for growing shoulders. Extra-strong zippers that hold up to daily use. RoboRush is lightweight and smartly designed. Inside pockets help keep items sorted and easy to find. The bold design inspires curiosity and confidence. Comes with a built-in key holder and name label. Wipe-clean interior makes spills easy to manage. Ideal for ages 7 and up. Stands upright easily for quick access. Pairs great with tech-themed accessories. Perfect for kids who love coding, robots, and discovery. RoboRush powers every school day with energy and style.', 40.00, 10, 39, 3, 'images/products/1744392715_67f9520b6362e.jpg', '2025-04-11 17:29:27'),
('3900005', ' BerryBloom Backpack', 'BerryBloom Backpack is a fresh and fun choice for young nature lovers. Featuring a delightful mix of berry prints and blooming flowers, it’s sweet and stylish. Soft pinks, purples, and greens give it a calm, joyful vibe. Made with eco-friendly, sturdy fabric that holds up well. Padded straps adjust for the perfect fit. Spacious main section holds books, art supplies, or a change of clothes. Front zip pocket is great for lunch cards or small gadgets. Side pockets hold bottles, umbrellas, or snacks. Inside mesh pouch keeps small items tidy. Durable stitching makes it ready for everyday wear and tear. Zippers are smooth and strong for little hands. Easy to clean, inside and out. Comes with a floral name label for personalization. Ideal for ages 5–9. Lightweight and comfy for all-day use. BerryBloom encourages a love for nature and color. Comes in eco-conscious packaging. Perfect for school, parks, or weekend adventures. Great gift for spring or back-to-school season. With BerryBloom, every day feels like a garden walk.', 45.00, 10, 39, 1, 'images/products/1744392613_Bag 6.jpg', '2025-04-11 17:30:13'),
('4000001', 'NoteMaster Premium Notebook', 'The NoteMaster Premium Notebook is designed for those who appreciate quality, functionality, and style in their everyday writing tools. Featuring a sleek, hardcover design, this notebook provides both durability and sophistication, making it ideal for students, professionals, and creatives alike. The pages are made from high-quality, acid-free paper, ensuring smooth writing and preventing ink from bleeding through. Whether you\'re jotting down notes, sketching ideas, or writing a journal entry, the pages provide a premium writing experience. The notebook includes a convenient elastic closure to keep it securely closed when not in use, protecting your notes from external damage. Inside, you\'ll find a set of perfectly aligned pages with a subtle grid pattern, ideal for structured writing or drawing. The compact size makes it easy to carry in a backpack, briefcase, or even your purse, so you can take it anywhere you go. The elegant design includes a built-in ribbon marker, making it easy to find your place whenever you need it. Whether you\'re taking lecture notes, planning a project, or organizing your thoughts, the NoteMaster Premium Notebook is the perfect companion for staying organized and focused. The notebook is available in various colors, allowing you to choose the one that best suits your style. With its stylish look, practical features, and high-quality paper, the NoteMaster Premium Notebook is an essential tool for anyone who values efficiency and elegance in their everyday writing. Elevate your note-taking experience with the NoteMaster Premium Notebook, designed to inspire creativity and productivity.', 10.00, 30, 40, 0, 'images/products/1744393327_Stationary 1.jpg', '2025-04-11 17:42:07'),
('4000002', 'PrecisionPro Geometry Box', 'The PrecisionPro Geometry Box is the ultimate tool for students, professionals, and artists alike, offering precision and reliability for all your geometric needs. Made from durable, high-quality materials, this compact set includes all the essential tools for accurate measurements and drawings. It features a sturdy compass for perfect circles, a set square for precise angles, a protractor for accurate measurements, and a ruler with both metric and imperial scales for versatility. The box also includes a pencil, eraser, and sharpener, ensuring that you\'re fully equipped for any task. The tools are made from lightweight yet durable materials, making them easy to handle while maintaining long-lasting performance. The clear plastic design of the tools allows for easy visibility of your measurements and lines, enhancing the accuracy of your work. The entire set comes neatly packed in a sleek, portable case, which keeps the tools organized and easily accessible. Whether you’re working on math assignments, architectural designs, or technical drawings, the PrecisionPro Geometry Box ensures that every task is completed with exactness. The box is designed to be compact and lightweight, making it easy to carry to school, college, or office. The high-quality build of the tools provides a smooth and comfortable writing experience, helping you to work efficiently for long hours. With the PrecisionPro Geometry Box, you’ll always have the right tool for the job, ensuring your measurements are spot-on and your designs flawless. Ideal for students, professionals, and anyone who values precision, this geometry box is an indispensable addition to your stationery collection.', 20.00, 10, 40, 1, 'images/products/1744393388_stationary 2.jpg', '2025-04-11 17:43:08'),
('4000003', 'CalcMaster Scientific Calculator', 'The CalcMaster Scientific Calculator is a powerful and versatile tool designed to meet the needs of students, professionals, and anyone requiring advanced mathematical functions. Equipped with a user-friendly interface and a durable, compact design, this calculator is perfect for a wide range of tasks, from simple arithmetic to complex scientific and engineering calculations. Featuring a large, easy-to-read display, the CalcMaster allows you to view results clearly, even with multiple operations at once. With over 200 built-in functions, including trigonometry, logarithms, exponents, and statistics, it provides the functionality needed for higher-level mathematics and science work. The calculator also includes a memory function for storing intermediate results, making it easier to work through multi-step problems without losing track of your calculations. Its sleek, ergonomic design fits comfortably in your hand, and the buttons are tactile and responsive, ensuring quick and accurate inputs. Powered by long-lasting batteries, the CalcMaster Scientific Calculator ensures reliability throughout intense study sessions or professional use. The lightweight construction and protective cover make it ideal for carrying in a backpack or briefcase without the risk of damage. Whether you\'re a student tackling algebra and calculus or a professional working with data analysis and engineering calculations, the CalcMaster is designed to provide accuracy and efficiency. Its intuitive layout and responsive keys make it an essential tool for anyone in need of reliable mathematical computing. The CalcMaster Scientific Calculator is perfect for school, college, or work, offering all the functions you need in one easy-to-use device.', 30.00, 9, 40, 1, 'images/products/1744393449_stationary 3.jpg', '2025-04-11 17:44:09'),
('4000004', ' StapleMaster Premium Stapler', 'The StapleMaster Premium Stapler is a high-quality, reliable tool designed to handle all your stapling needs with ease and precision. Whether you\'re at home, in the office, or at school, this stapler provides smooth, consistent performance for binding papers together quickly and securely. Built with durable metal construction, it ensures long-lasting use without compromising on strength. The ergonomic design fits comfortably in your hand, reducing hand strain even after repeated use. With its easy-to-load staple chamber, reloading staples is a breeze, and the mechanism ensures jam-free stapling every time. The stapler is capable of stapling a range of paper sizes and thicknesses, making it versatile for different tasks—whether you’re stapling a single sheet or a thicker stack of documents. The compact size makes it portable, so you can carry it with you in a briefcase or drawer, perfect for work or on-the-go use. Featuring a sleek, modern design, the StapleMaster adds a touch of professionalism to any workspace. The adjustable anvil allows for both permanent and temporary stapling, giving you the flexibility to meet different needs. The non-slip base ensures stability and prevents sliding while stapling, keeping your work surface tidy and secure. With its sturdy build and smooth operation, the StapleMaster Premium Stapler is the ideal choice for anyone seeking a dependable and efficient stapling solution. Available in a range of stylish colors, it fits seamlessly into any office or home environment. Say goodbye to frustrating paper jams and inconsistent stapling with the StapleMaster Premium Stapler, designed to make your paperwork tasks easier and more efficient.', 12.00, 10, 40, -1, 'images/products/1744393509_stationary 5.jpg', '2025-04-11 17:45:09'),
('4000005', 'ColorMaster PE Color Box', 'The ColorMaster PE Color Box is the perfect storage solution for artists, students, and crafters who need a convenient and durable way to organize their colors. Made from high-quality polyethylene (PE), this color box offers both strength and flexibility, ensuring that your materials stay safe and intact. Its sturdy, impact-resistant design makes it ideal for frequent use and long-term storage. The ColorMaster PE Color Box features a spacious interior with adjustable compartments, allowing you to neatly store and organize markers, crayons, colored pencils, paints, and other art supplies. The smooth, easy-to-clean surface helps maintain the box’s aesthetic and functionality, making it a long-lasting tool for any workspace. The box is lightweight yet tough, making it easy to carry to school, art classes, or outdoor crafting sessions. The secure locking mechanism ensures that your items stay safely enclosed while you’re on the move. The box is available in a variety of sizes, catering to different storage needs, from small, personal collections to larger, professional setups. With its vibrant color options, the ColorMaster PE Color Box adds a touch of fun and creativity to your workspace, inspiring your artistic endeavors. Its sleek, modern design is both practical and stylish, making it a great addition to any desk or storage area. Whether you’re an artist, a student, or a hobbyist, this box helps keep your art supplies organized, accessible, and easy to find. Say goodbye to cluttered drawers and disorganized supplies with the ColorMaster PE Color Box—a must-have for anyone who values order and creativity in their work.', 25.00, 5, 40, 1, 'images/products/1744393558_stationary 6.jpg', '2025-04-11 17:45:58'),
('4000006', ' SlimFit Wallet', 'The SlimFit Wallet is the perfect choice for those who prefer a minimalist, streamlined approach to carrying their essentials. Made from premium synthetic leather, this wallet is lightweight, durable, and built for comfort. Its ultra-slim design fits easily in your pocket or bag without adding unnecessary bulk. Despite its compact size, the SlimFit Wallet features multiple card slots, a full-length bill compartment, and an RFID-blocking layer to protect your personal information. This wallet’s clean lines and smooth finish offer a modern aesthetic that suits both casual and formal looks. Ideal for anyone who wants a sleek, practical solution for everyday use, the SlimFit Wallet keeps your essentials organized while maintaining a low profile. Whether you’re running errands or going on a trip, the SlimFit Wallet ensures that your valuables are safe, secure, and easily accessible.', 20.00, 10, 40, 1, 'images/products/1744393926_4.jpg', '2025-04-11 17:52:06'),
('4100001', ' Glossé Touch', 'Glossé Touch nail polish is your go-to for radiant, salon-quality nails.\r\nWith an ultra-glossy finish, it offers vibrant color in just one stroke.\r\nDesigned for long wear, this polish resists chips and fading effortlessly.\r\nIts smooth texture glides on evenly, giving you a professional finish at home.\r\nChoose from a wide range of shades—from classic reds to bold neons.\r\nQuick-drying formula saves you time without compromising on quality.\r\nThe brush is designed for precise, streak-free application every time.\r\nPerfect for everyday wear or glamming up your special occasions.\r\nNon-toxic and cruelty-free, it\'s a polish you can feel good about.\r\nGlossé Touch also nourishes nails with vitamin-rich ingredients.\r\nYou’ll love how strong and shiny your nails look and feel.\r\nGreat for all nail types, from natural to acrylic.\r\nIdeal for gifting or personal pampering sessions.\r\nMake your nails the highlight of your style.\r\nPairs beautifully with all outfits and moods.\r\nReapply-free color that lasts up to 7 days.\r\nBeautifully packaged in a sleek glass bottle.\r\nEasy to remove without staining nails.\r\nPerfect for travel, work, or parties.\r\nWith Glossé Touch, confidence is just a coat away.', 30.00, 10, 41, 2, 'images/products/1744396083_1.jpg', '2025-04-11 18:28:03'),
('4100002', 'RadiantFix Face Base', 'RadiantFix Face Base is your ultimate skin-perfecting foundation for a flawless, all-day glow.This lightweight, breathable formula melts into the skin, blurring\r\nimperfections effortlessly.\r\nIt provides medium to full buildable coverage without feeling heavy or cakey.\r\nInfused with hydrating agents and vitamin E, it keeps your skin soft and nourished.\r\nPerfect for all skin types, including sensitive and combination skin.\r\nRadiantFix adapts to your natural tone, delivering a smooth, even finish.\r\nIt controls excess oil while maintaining a healthy, dewy glow.\r\nFormulated to last up to 12 hours without fading or creasing.\r\nThe texture is silky and blends beautifully with brushes, sponges, or fingers.\r\nIt minimizes the appearance of pores and fine lines instantly.\r\nGreat as a daily base or for special events when you need a flawless look.\r\nFree from parabens, sulfates, and harsh chemicals—safe for everyday use.\r\nDermatologically tested and cruelty-free for conscious beauty lovers.\r\nLeaves your skin photo-ready with a soft-focus effect.\r\nAvailable in a wide range of inclusive shades for every skin tone.\r\nPairs perfectly with concealer, blush, or highlighter.\r\nNon-comedogenic—won’t clog pores or cause breakouts.\r\nTravel-friendly packaging with a spill-proof pump.\r\nRadiantFix enhances your natural beauty with minimal effort.\r\nOne base, endless possibilities—let your glow do the talking.', 50.00, 12, 41, 2, 'images/products/1744396173_2.webp', '2025-04-11 18:29:33'),
('4100003', 'DreamDust Eyeshadow Palette', 'DreamDust Eyeshadow Palette is your ultimate go-to for creating mesmerizing eye looks.\r\nPacked with richly pigmented shades, it offers both bold and neutral tones.\r\nFrom soft mattes to dazzling shimmers, each color blends like a dream.\r\nThe buttery formula glides effortlessly, delivering intense color in just one swipe.\r\nPerfect for day-to-night transitions, special occasions, or daily glam.\r\nLong-lasting and crease-resistant, your eye look stays flawless for hours.\r\nIdeal for all skin tones with a carefully curated shade range.\r\nUse dry for soft looks or wet for vibrant, dramatic effects.\r\nHoused in a sleek, travel-friendly case with a built-in mirror.\r\nWhether you\'re a beginner or a makeup pro, it\'s easy to work with.\r\nEach shade is buildable and blendable for endless creativity.\r\nInfused with skin-loving ingredients for comfort and wearability.\r\nNo fallout, no patchiness—just smooth, seamless color.\r\nCruelty-free and free from parabens or harsh additives.\r\nGreat for gifting or treating yourself to a beauty upgrade.\r\nPair it with eyeliner and mascara for the complete look.\r\nExperiment with smoky eyes, cut creases, or subtle highlights.\r\nA must-have palette for every makeup lover\'s collection.\r\nUnleash your inner artist and express your unique style.\r\nWith DreamDust, your eyes will always do the talking.', 70.00, 10, 41, 3, 'images/products/1744396246_3.webp', '2025-04-11 18:30:46'),
('4100004', 'LuxeMatte Lipstick', 'LuxeMatte Lipstick is where rich color meets a smooth, velvety finish.\r\nCrafted for bold, beautiful lips, it delivers intense pigment in a single swipe.\r\nThe lightweight formula glides effortlessly, hugging your lips with comfort.\r\nInfused with nourishing oils and vitamin E to keep lips soft and hydrated.\r\nExperience a luxurious matte look without any dryness or cracking.\r\nSmudge-proof and long-lasting—stays put through meals and moments.\r\nAvailable in a stunning range of shades, from classic reds to trendy nudes.\r\nDesigned for all skin tones, every color pops beautifully.\r\nIts sleek, magnetic case is perfect for on-the-go touch-ups.\r\nA must-have in your everyday or glam beauty routine.\r\nGentle on lips, free from parabens and harmful chemicals.\r\nThe pointed bullet allows for precise application without a liner.\r\nBuildable coverage lets you go bold or keep it natural.\r\nPerfect for date nights, workdays, or weekend outings.\r\nPairs beautifully with gloss or lip liner for custom looks.\r\nCruelty-free and crafted with love for conscious beauty lovers.\r\nLeaves lips feeling silky-smooth all day long.\r\nNo feathering, no fading—just vibrant, comfortable color.\r\nTurn heads and express confidence with every smile.\r\nWith LuxeMatte, your lips lead the way in style.', 40.00, 12, 41, 1, 'images/products/1744396303_4.webp', '2025-04-11 18:31:43'),
('4100005', 'GlowNest Facial Cream', 'GlowNest Facial Cream is your daily dose of hydration and radiance in a jar.\r\nFormulated with skin-loving ingredients like hyaluronic acid and aloe vera.\r\nDeeply nourishes and revitalizes dull, tired skin for a natural glow.\r\nIts lightweight, non-greasy texture absorbs instantly without clogging pores.\r\nPerfect for morning and night use—your skin will thank you.\r\nPacked with antioxidants to protect against environmental damage.\r\nFights dryness, rough patches, and uneven skin tone effectively.\r\nLeaves your skin feeling soft, smooth, and visibly healthier.\r\nDermatologically tested for all skin types, including sensitive skin.\r\nFree from parabens, sulfates, and artificial fragrances.\r\nGives you a radiant, dewy finish without looking oily.\r\nIdeal as a base under makeup for a flawless look.\r\nReduces the appearance of fine lines and fatigue over time.\r\nCalms and soothes with natural botanical extracts.\r\nNon-comedogenic and cruelty-free—kind to skin and animals.\r\nBeautifully packaged and perfect for your skincare shelf.\r\nGlowNest is more than a cream—it\'s self-care in every swipe.\r\nLocks in moisture all day, even in dry or harsh weather.\r\nUse it daily to refresh your skin and boost your confidence.\r\nWith GlowNest, your skin glows brighter every day.', 30.00, 12, 41, 0, 'images/products/1744396374_5.webp', '2025-04-11 18:32:54'),
('4200001', 'liteLeather Wallet', 'The EliteLeather Wallet is the epitome of luxury and functionality, designed for those who appreciate craftsmanship and style. Made from premium, genuine leather, this wallet combines durability with a timeless aesthetic. The soft texture and rich color of the leather develop a unique patina over time, making it even more beautiful with age. The slim design allows for easy pocket storage without sacrificing capacity, featuring multiple card slots, a cash compartment, and a coin pocket. Whether you\'re heading to a business meeting or going out for the weekend, the EliteLeather Wallet exudes elegance and practicality. Its RFID-blocking technology ensures that your personal information stays safe from digital theft. The wallet also includes a clear ID window for easy access to your driver’s license or ID card. Ideal for professionals and those who appreciate understated luxury, the EliteLeather Wallet offers the perfect balance of form and function, making it a must-have accessory.', 20.00, 15, 42, 1, 'images/products/1744393704_1.jpg', '2025-04-11 17:48:24'),
('4200002', 'UrbanTech Wallet', 'The UrbanTech Wallet is designed for the modern, tech-savvy individual who values both convenience and security. Crafted with durable, water-resistant materials, this wallet offers protection from the elements while maintaining a sleek, minimalist design. It features an integrated RFID-blocking lining to protect your sensitive credit card information from electronic theft. Inside, you\'ll find several card slots, a zippered coin compartment, and a dedicated space for cash and receipts. The wallet’s slim profile fits comfortably in your back pocket or front pocket without adding bulk, making it ideal for everyday use. The UrbanTech Wallet’s tech-friendly design also includes a built-in tracking tag, so you’ll never lose it again. Simply sync it with your phone to keep tabs on your wallet’s location. Whether you\'re commuting to work or traveling across town, the UrbanTech Wallet is perfect for those who appreciate modern functionality and security.', 15.00, 8, 42, 0, 'images/products/1744393774_2.jpg', '2025-04-11 17:49:34'),
('4200003', 'ClassicCedar Wallet', 'The ClassicCedar Wallet combines timeless elegance with practical organization, making it a perfect choice for anyone who loves simplicity. Made from high-quality cedar leather, this wallet is smooth to the touch yet durable enough to withstand everyday use. Its classic bifold design opens to reveal multiple card slots, a full-length cash pocket, and a hidden coin compartment. The rich cedar color gives the wallet a distinguished look that pairs well with both casual and formal attire. Whether you\'re out for a lunch date or headed to the office, the ClassicCedar Wallet ensures you have everything organized and easily accessible. Its sleek, compact design fits comfortably in your pocket or bag without feeling bulky. This wallet’s minimalist approach to organization helps you streamline your essentials while keeping things secure and tidy. Designed for individuals who appreciate tradition and quality, the ClassicCedar Wallet adds a touch of sophistication to your everyday carry.', 18.00, 7, 42, 1, 'images/products/1744393873_3.jpg', '2025-04-11 17:51:13'),
('4200004', 'Wanderer Travel Wallet', 'The Wanderer Travel Wallet is designed for the globetrotter who values both functionality and style when exploring new destinations. Crafted from durable nylon with leather accents, this wallet is built to withstand the rigors of travel while offering the perfect amount of organization. It features a spacious design with multiple card slots, a secure passport pocket, a zippered coin section, and a full-length cash compartment to keep your money safe and accessible. The Wanderer Travel Wallet also includes a dedicated slot for your boarding pass and tickets, making it easy to access when you\'re in a hurry. Its RFID-blocking technology protects your personal information from unauthorized scans, ensuring peace of mind as you travel. Whether you\'re navigating airports, exploring new cities, or heading out for an adventure, the Wanderer Travel Wallet is the ideal companion for keeping your travel essentials organized and secure.', 25.00, 12, 42, 2, 'images/products/1744393960_5.jpg', '2025-04-11 17:52:40'),
('4300001', 'ChicAura Handbag', 'The ChicAura Handbag is the ultimate blend of elegance and modern design. Crafted from premium faux leather, this handbag features a smooth finish and a sleek, structured silhouette, making it perfect for both day and evening use. Its spacious interior offers plenty of room to carry your essentials—keys, phone, makeup, and wallet—while maintaining a compact and stylish appearance. The adjustable shoulder strap ensures comfort, while the sturdy top handles provide an alternative carrying option for added versatility. The ChicAura Handbag comes with gold-tone hardware and a signature logo charm, adding a touch of luxury to its design. Whether you\'re attending a meeting, a dinner date, or an evening out, the ChicAura Handbag complements any outfit, making you stand out with its refined and contemporary appeal. The interior is lined with a soft fabric to keep your belongings safe, while the exterior features a minimalist design that pairs well with both casual and formal attire. With a variety of color options available, the ChicAura Handbag is the perfect accessory for every occasion, bringing a touch of class to your wardrobe.', 80.00, 10, 43, 3, 'images/products/1744394412_1.jpg', '2025-04-11 18:00:12'),
('4300002', 'BellaVita Tote', 'The BellaVita Tote is a perfect blend of practicality and sophistication, making it an essential piece for any woman on the go. Made from high-quality canvas with leather accents, this spacious tote provides ample room for all your daily essentials, from your laptop and documents to your wallet, makeup, and water bottle. The sturdy handles are designed for easy carrying, while the detachable crossbody strap gives you the option to go hands-free. The BellaVita Tote features multiple interior pockets to keep your items organized and within reach, ensuring that your essentials are always easy to find. Whether you\'re heading to work, running errands, or enjoying a weekend getaway, this tote is the ideal companion. The stylish design, combined with the comfortable, adjustable straps, makes this bag perfect for every occasion, from casual outings to more formal events. The BellaVita Tote is available in a range of vibrant colors, allowing you to choose the perfect match for your personal style. With its elegant yet functional design, this tote combines timeless appeal with modern convenience, ensuring you look chic while staying organized.\r\n\r\n', 100.00, 10, 43, 1, 'images/products/1744394498_2.jpg', '2025-04-11 18:01:38'),
('4300003', 'LuxeVibe Clutch', 'The LuxeVibe Clutch is the epitome of elegance and luxury, perfect for those special occasions when you want to make a statement. Crafted from fine satin fabric with intricate beadwork, this clutch exudes timeless glamour. The compact design is ideal for carrying just the essentials—your phone, lipstick, cards, and a small mirror. The clutch features a secure clasp closure to keep your items safe, while the delicate chain strap offers versatility, allowing you to wear it as a shoulder bag or carry it as a classic clutch. Whether you\'re attending a formal event, a wedding, or a cocktail party, the LuxeVibe Clutch adds an extra touch of sophistication to your ensemble. Its minimalist design is adorned with subtle embellishments, enhancing the overall aesthetic while keeping it chic and understated. This clutch is a must-have for fashion-forward women who want to elevate their look with a statement accessory that complements any evening attire.', 70.00, 12, 43, 3, 'images/products/1744394533_3.jpg', '2025-04-11 18:02:13'),
('4300004', 'UrbanEdge Crossbody', 'The UrbanEdge Crossbody is the perfect handbag for the modern woman who loves to combine style with convenience. Crafted from durable leather and designed with a sleek, minimalist approach, this crossbody bag is ideal for daily use. With an adjustable strap for comfort, the bag sits securely on your shoulder or across your body, allowing you to keep your hands free while carrying your essentials. Inside, you\'ll find a spacious compartment with multiple smaller pockets to organize your phone, wallet, makeup, and keys. The exterior features a front zippered pocket for quick access to items like your sunglasses or transit card. The UrbanEdge Crossbody comes in a variety of classic and bold colors, making it easy to pair with any outfit, from casual wear to evening attire. Whether you\'re running errands, commuting to work, or enjoying a day out, this crossbody provides the perfect balance of practicality and style, ensuring you\'re always ready for whatever the day brings.', 60.00, 8, 43, 1, 'images/products/1744394569_4.jpg', '2025-04-11 18:02:49'),
('4300005', 'Name: EverGlam Satchel', 'The EverGlam Satchel is a sophisticated, versatile handbag that exudes timeless charm. Made from high-quality leather, this satchel offers a perfect balance of elegance and functionality. The structured design features a spacious main compartment that can hold your essentials, such as your wallet, phone, makeup, and more, while keeping everything neatly organized with multiple interior pockets. The dual top handles provide a comfortable grip, while the detachable shoulder strap allows for hands-free carrying. The EverGlam Satchel is adorned with subtle gold-tone hardware and a signature charm, giving it a luxurious touch. Ideal for both professional and casual settings, this satchel easily transitions from office wear to weekend outings. The sophisticated design complements any outfit, adding a refined touch to your ensemble. With its durable leather construction and chic, modern aesthetic, the EverGlam Satchel is a must-have accessory for any woman who values both style and practicality.', 80.00, 16, 43, 1, 'images/products/1744394607_5.jpg', '2025-04-11 18:03:27'),
('4400001', 'ProGuard File Mate', 'ProGuard File Mate is designed for professionals who value neat, secure storage.\r\nMade from durable hardbound material that resists wear and tear.\r\nIdeal for school, office, or home document organization.\r\nThe strong spine ensures the file keeps its shape over time.\r\nComes in classic, formal colors perfect for corporate use.\r\nHolds A4 papers, certificates, or project reports with ease.\r\nThe smooth finish gives it a polished, executive look.\r\nFeatures a front pocket for labeling or quick-access documents.\r\nIdeal for presentations, portfolios, or academic work.\r\nFirm grip ensures pages stay flat and undamaged.\r\nGreat for storing resumes, financial reports, or legal papers.\r\nCompact enough to fit in most briefcases or bags.\r\nReinforced edges prevent bending or curling.\r\nEco-friendly construction with long-lasting materials.\r\nEasy to stack or shelve thanks to its slim design.\r\nReduces paper clutter and keeps everything sorted.\r\nAvailable in multiple shades to suit different needs.\r\nA must-have for meetings and professional organization.\r\nCombines functionality with classic style.\r\nStay organized, look sharp with ProGuard.', 10.00, 20, 44, 0, 'images/products/1744396799_file 1.jpg', '2025-04-11 18:39:59'),
('4400002', 'FlexiSnap Organizer', 'FlexiSnap Organizer brings flexibility and style to your daily storage needs.\r\nMade from high-quality PP plastic that’s soft yet durable.\r\nDesigned with a snap-button closure to secure contents easily.\r\nBright, youthful colors make it a favorite for students and creatives.\r\nSlim and lightweight—ideal for on-the-go users.\r\nPerfect for organizing notes, loose papers, or handouts.\r\nIncludes inside pockets to separate different subjects or topics.\r\nWater-resistant and dust-proof for long-lasting protection.\r\nEasy to wipe clean and maintain.\r\nA4-compatible and ideal for schoolbags or handbags.\r\nSnap shuts tightly to prevent accidental paper loss.\r\nGreat for exam prep, projects, or personal planners.\r\nIdeal for schools, coaching centers, or home study setups.\r\nVibrant design adds fun to boring paperwork.\r\nTough enough for everyday use, soft enough to handle with ease.\r\nEncourages neatness and document safety.\r\nReusable and eco-conscious choice for sustainable users.\r\nKeeps your essentials compact and together.\r\nPerfect companion for study sessions or casual office work.\r\nFlexiSnap is organization made colorful and convenient.\r\n\r\n', 12.00, 8, 44, 0, 'images/products/1744396841_file 2.jpg', '2025-04-11 18:40:41'),
('4400003', 'ClassEase Ring File', 'ClassEase Ring File makes paper management simple and effective.\r\nBuilt with a sturdy 2-ring mechanism to hold documents in place.\r\nPerfect for schoolwork, training manuals, or financial records.\r\nSpacious enough to accommodate hundreds of A4 sheets.\r\nReinforced spine for stability and durability.\r\nLabel holder on the spine allows easy identification.\r\nComes in formal and fun colors for all users.\r\nMade with thick cover panels that resist bending.\r\nGreat for long-term document storage and archiving.\r\nIdeal for classroom, boardroom, or home office.\r\nPerfect for subject-wise filing or project organization.\r\nEasy-to-open rings make adding or removing papers simple.\r\nPrevents tearing and creasing of documents.\r\nCan hold punched plastic sleeves for extra protection.\r\nStacks neatly on bookshelves or in file cabinets.\r\nGood for keeping worksheets, receipts, or catalogues.\r\nNo-fuss design—just reliable, practical use.\r\nAn essential tool for professionals and students alike.\r\nBuilt to last with high functionality in mind.\r\nClassEase keeps your paperwork under control.', 15.00, 10, 44, 0, 'images/products/1744396878_file 3.jpg', '2025-04-11 18:41:18'),
('4400004', 'ClearView Transparent File', 'ClearView Transparent File is the perfect blend of style and functionality.\r\nCrafted from premium-quality, see-through plastic that is both flexible and durable.\r\nIdeal for school, office, or personal organization needs.\r\nThe transparent design allows for quick identification of contents.\r\nNo more flipping through files—just grab and go.\r\nSleek, minimalistic look that suits modern workspaces.\r\nWater-resistant material protects your documents from accidental spills.\r\nEquipped with a smooth zipper or snap closure for secure storage.\r\nHolds multiple A4-size papers, booklets, or notes comfortably.\r\nLightweight and easy to carry in backpacks or briefcases.\r\nWon’t crease or tear like paper folders—made for repeated use.\r\nPerfect for storing certificates, forms, resumes, or artwork.\r\nRounded edges and soft feel make it safe for kids and professionals alike.\r\nDoesn’t take up much space—compact but spacious inside.\r\nGreat for color-coded organization when paired with multiple files.\r\nTransparent surface can be labeled with stickers or markers.\r\nEco-friendly and reusable—cutting down on waste.\r\nPerfect for interviews, meetings, and travel.\r\nA must-have tool for anyone who loves tidy, clear arrangements.\r\nWith ClearView, what you need is always in sight.', 13.00, 23, 44, 0, 'images/products/1744396922_file 4.jpg', '2025-04-11 18:42:02'),
('4400005', 'ZipGuard Executive File', 'ZipGuard Executive File is built for the professional on the move.\r\nFeatures a full zip-around closure that keeps documents secure and private.\r\nCrafted with a textured leather-look outer shell for a premium feel.\r\nIncludes compartments for papers, business cards, pens, and USBs.\r\nA true all-in-one file that replaces bulky folders and cases.\r\nIdeal for meetings, travel, and conferences.\r\nOrganize resumes, contracts, and project notes in one place.\r\nDesigned to impress—sleek and refined in every detail.\r\nDurable stitching ensures long-lasting daily use.\r\nPerfect for executives, students, and freelancers.\r\nSpacious yet slim enough to fit in your briefcase.\r\nA zip pocket allows you to store smaller valuables safely.\r\nMakes a great corporate gift or professional accessory.\r\nKeeps you prepared and organized wherever you go.\r\nProtects documents from dust, folds, and spills.\r\nSoft interior lining prevents paper damage.\r\nWorks great as a mobile office file.\r\nPremium look that matches your ambition.\r\nAvailable in classy shades like black, brown, and navy.\r\nWith ZipGuard, carry confidence in every zip.', 14.00, 2, 44, 0, 'images/products/1744396961_file 5.jpg', '2025-04-11 18:42:41');
INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `stock_quantity`, `category_id`, `warranty_period`, `image_path`, `created_at`) VALUES
('4500001', 'Lulu Doll', 'Lulu Doll is a sweet and stylish companion, perfect for little ones who love soft and cuddly friends. Dressed in a pastel floral dress with delicate lace trim, she brings a gentle charm to every playtime. Her big embroidered eyes and stitched smile give her a warm, friendly expression. Lulu’s silky yarn hair is fun to comb and style, encouraging creativity. She comes with a tiny matching handbag, adding a playful touch to her outfit. Crafted from soft, high-quality materials, Lulu is lightweight and easy for small hands to hold. Her outfit is removable with simple Velcro, making dress-up time easy and fun. Designed to be hugged, carried, and loved, she makes a perfect bedtime buddy. Lulu helps spark imagination and emotional growth through role-play. Safe for ages 3 and up, she meets all child safety standards. She’s machine washable, making cleanup a breeze for parents. Whether it’s a tea party or a cozy nap, Lulu is always ready to join in. Her charming design makes her a beautiful display piece too. She comes in a lovely gift box, ideal for birthdays and holidays. Lulu is part of the adorable “Sweethearts” collection. With her soft shoes and attention to detail, she stands out in any doll lineup. She can sit upright for display or storytime. Perfect for travel, Lulu fits neatly into backpacks and totes. A true best friend in every way, Lulu Doll brings comfort, joy, and endless fun.', 20.00, 10, 45, 1, 'images/products/1744392033_67f94f61e19f6.jpg', '2025-04-11 17:19:53'),
('4500002', 'Nina Doll', 'Nina Doll is a bright and cheerful friend with a personality as sunny as her yellow dress. Her playful charm makes her the perfect companion for every little adventure. With soft plush arms and a gentle smile, Nina is designed for hugs, cuddles, and comfort. Her big eyes and rosy cheeks bring her character to life, while her soft hair ribbons add a fun touch. She’s made from child-safe, high-quality materials, ensuring she’s as durable as she is adorable. Nina’s lightweight build makes her easy to carry anywhere—from road trips to sleepovers. Her removable dress fastens with Velcro, making outfit changes quick and simple. She encourages role-play, empathy, and storytelling in young minds. Whether it’s a tea party or a day at the park, Nina is always ready to join the fun. Her classic look makes her both timeless and stylish. Nina comes in a gift-ready box, making her an ideal present. Machine washable and easy to clean, she’s loved by parents too. Her shoes feature tiny stitched hearts, adding a delicate detail. She can sit on her own, perfect for storytime or display. Nina’s soothing softness makes her a great bedtime buddy. She’s safe for children aged 3 and up. Lightweight yet sturdy, she’s built to last through years of play. Nina is part of the “Joyful Hearts” doll collection. Every child will love having her as their first best friend.', 30.00, 7, 45, 3, 'images/products/1744392091_Doll 1.jpg', '2025-04-11 17:21:31'),
('4500003', 'Zoe Doll', 'Zoe Doll is a sparkle of fun and energy, made for kids who love adventure and imagination. Dressed in a cool polka-dot jumper and bright shoes, she brings a playful spirit to every moment. Her wide eyes and cheeky grin make her an instant favorite. Zoe’s curly yarn hair is full of personality and style, perfect for pretend salon time. Made from soft, durable fabric, she’s cozy to cuddle and safe to play with. She’s light enough for tiny hands to hold and carry everywhere. Zoe inspires creativity and storytelling with her bold look and cheerful vibe. Her Velcro-fastened outfit makes dressing up easy and interactive. She promotes social development through pretend play and friendship. Zoe is a great travel buddy—she fits easily into backpacks and loves adventures. Her fabric shoes feature fun embroidered stars. Designed for children aged 3+, she’s made with safety in mind. Easy to clean and machine washable, she’s also parent-friendly. Zoe comes packaged in a colorful display box that makes gifting simple. She’s great for both play and decoration in a child’s room. Perfect for playdates, sleepovers, and birthday surprises. Zoe can sit upright and be posed for fun photo moments. She belongs to the “Bright Buddies” collection. A bold best friend for any child, Zoe Doll brings laughter, love, and joy.', 15.00, 15, 45, 1, 'images/products/1744392141_Doll 3.jpg', '2025-04-11 17:22:21'),
('4500004', 'Mimi Doll', 'Mimi Doll is a soft, cuddly sweetheart designed for gentle hugs and endless love. Dressed in a light pink dress with a frilly hem, she’s the definition of adorable. Mimi’s calm, comforting expression makes her an ideal naptime companion. Her soft, braided hair and little bow make her instantly lovable. Crafted with extra care and premium materials, Mimi is gentle on young skin. She’s the perfect size for toddlers and preschoolers to hold and cuddle. Lightweight and safe, she’s great for quiet playtime and bedtime routines. Her Velcro-back dress is easy to remove, encouraging independent play. Mimi inspires nurturing behavior and emotional connection in children. She helps little ones express care, friendship, and affection. Her pastel colors create a soothing, calming presence in any room. She’s machine washable, so she’s easy to keep clean. Mimi’s soft shoes have heart-shaped stitching for added charm. Packaged in a lovely pink gift box, she’s perfect for birthdays and special occasions. Suitable for ages 3 and up, she meets all safety standards. Mimi is part of the “Cuddle Moments” collection. Whether she’s tucked in at night or going on daytime adventures, Mimi is always there. Her softness makes her a treasured snuggle buddy. She can sit upright or rest beside your child at night. A true comfort friend, Mimi Doll brings peace, care, and love.', 25.00, 45, 45, 4, 'images/products/1744392180_Doll 4.jpg', '2025-04-11 17:23:00'),
('4500005', 'Kiki Doll', 'Kiki Doll is full of fun, flair, and personality. Dressed in a funky lavender outfit with a cute tutu skirt, she’s perfect for little ones with big imaginations. Her sparkly eyes and bright smile add magic to every moment. Kiki’s soft, bouncy pigtails make her look playful and energetic. She’s crafted from soft, plush fabric that’s perfect for hugs and cuddles. Lightweight and safe, she’s ideal for toddlers and young kids. Her dress features star-patterned details and fastens easily with Velcro. Kiki encourages self-expression and confidence through creative play. She’s great for dancing, make-believe, and bedtime chats. Machine washable and made to last, Kiki is easy for parents to care for. Her shoes have stitched stars for that extra sparkle. She comes packaged in a colorful gift box, great for giving. Kiki is part of the “Little Dreamers” collection. She’s designed to inspire fun, storytelling, and friendship. She fits easily into travel bags for play on the go. Whether it’s a sleepover or a playdate, Kiki is always ready. She helps develop emotional and social skills through play. Kiki can sit upright and pose for pretend photo shoots. Her bold style makes her stand out in any doll collection. Made for ages 3+, she’s soft, safe, and oh-so-stylish. With Kiki Doll, every day is a new adventure full of joy.', 18.00, 9, 45, 1, 'images/products/1744392232_Doll 5.jpg', '2025-04-11 17:23:52'),
('4600001', 'Heartfelt Moments Greeting Card', 'The Heartfelt Moments Greeting Card is designed to convey warmth and emotion, making it the perfect choice for expressing your love, appreciation, or congratulations. Whether it\'s a birthday, anniversary, or special occasion, this card offers a personal touch that will leave a lasting impression. The front features a beautifully illustrated design with vibrant colors, capturing the essence of the moment. Inside, you\'ll find ample space to write a heartfelt message, allowing you to customize the sentiment for your loved one. Made from high-quality, eco-friendly cardstock, this greeting card is both sturdy and luxurious. The fine details and thoughtful design make it a standout choice for anyone looking to make their message extra special. Whether you\'re sending it to a friend, family member, or significant other, the Heartfelt Moments Greeting Card is a beautiful way to mark life\'s precious moments with a personal touch.', 10.00, 20, 46, 0, 'images/products/1744395092_1.jpg', '2025-04-11 18:11:32'),
('4600002', 'Joyful Wishes Greeting Card', 'The Joyful Wishes Greeting Card is all about celebrating the happiness of life\'s milestones, big or small. With its cheerful design and vibrant hues, this card brings joy and excitement to any occasion. From birthdays to weddings, graduations to holidays, the Joyful Wishes Greeting Card is versatile enough to suit a variety of celebrations. The intricate illustrations on the front are paired with a warm, heartfelt message that expresses well wishes and positivity. The inside is left blank, giving you plenty of room to write your own personal message, making it truly unique and special. Crafted with premium paper, this greeting card feels luxurious to the touch, adding an extra layer of elegance to your sentiment. Whether you\'re sending congratulations or simply spreading joy, the Joyful Wishes Greeting Card is a perfect choice for sharing happiness with those you care about.', 5.00, 10, 46, 0, 'images/products/1744395200_2.jpg', '2025-04-11 18:13:20'),
('4600003', 'Elegant Embrace Greeting Card', 'The Elegant Embrace Greeting Card is designed for those who appreciate subtle beauty and understated sophistication. This card is perfect for expressing sympathy, love, or appreciation in a graceful and meaningful way. The minimalist design features delicate illustrations with soft, muted tones that evoke a sense of calm and warmth. The elegant typography adds to the card’s refined appearance, making it suitable for both formal and personal occasions. Whether you\'re sending condolences, a thank-you note, or simply reaching out to show you care, the Elegant Embrace Greeting Card delivers your message with class. The high-quality paper gives it a substantial, premium feel, ensuring your thoughtful message is delivered in the best possible way. Inside, there’s ample space for you to write a personalized note, adding your own heartfelt sentiment. This card is ideal for those who value simplicity and elegance in their communication.', 8.00, 7, 46, 0, 'images/products/1744395250_3.jpg', '2025-04-11 18:14:10'),
('4600004', ' Bright Beginnings Greeting Card', 'The Bright Beginnings Greeting Card is perfect for celebrating new beginnings, whether it\'s a new job, a new home, or a fresh chapter in someone\'s life. With a design that features fresh, vibrant colors and uplifting imagery, this card is a great way to send your best wishes for success and happiness. The front of the card is adorned with a bright, optimistic message that reflects a sense of hope and excitement for the future. The inside is left blank for you to write your own personal note, allowing you to express your unique thoughts and well wishes. Made with high-quality, textured cardstock, this card offers a premium feel and durability. Whether you\'re celebrating a graduation, engagement, or simply sending a note of encouragement, the Bright Beginnings Greeting Card is a perfect way to show your support and optimism for the future.', 12.00, 10, 46, 0, 'images/products/1744395286_4.jpg', '2025-04-11 18:14:46'),
('4600005', ' Love & Laughter Greeting Card', 'The Love & Laughter Greeting Card is designed to spread positivity, joy, and love, making it perfect for any occasion that celebrates life and happiness. Whether it’s a birthday, anniversary, or just a random act of kindness, this card brings a smile to anyone\'s face. The playful illustrations on the front feature bright colors and whimsical designs, paired with a message that conveys warmth and affection. Inside, you\'ll find plenty of space to add your own personal message, ensuring that your greeting is as unique as the person you\'re sending it to. The high-quality cardstock provides a luxurious touch, while the smooth finish makes it easy to write your message with ease. The Love & Laughter Greeting Card is an ideal choice for sending best wishes, love, or a simple expression of joy to those you care about. Whether you\'re marking a milestone or just spreading some cheer, this card is sure to brighten someone’s day.', 15.00, 20, 46, 0, 'images/products/1744395325_5.jpg', '2025-04-11 18:15:25'),
('4700001', 'LuxeWrap Gift Box', 'LuxeWrap Gift Box adds a touch of elegance and sophistication to any present. Designed with sturdy materials and a premium matte finish, it offers a luxurious unboxing experience. The magnetic flap closure adds ease and style, while the satin ribbon enhances its charm. Perfect for birthdays, anniversaries, weddings, and festive occasions, LuxeWrap gives your gift an upscale look. The box is available in multiple colors and sizes to match different themes and items. The interior features a soft lining, ideal for fragile or delicate gifts like jewelry, watches, or perfumes. It’s reusable and eco-friendly, making it a sustainable choice for stylish gift-giving. Whether you\'re gifting a loved one or packaging a corporate token, LuxeWrap makes the moment feel extra special. Its sleek design ensures the packaging is just as memorable as the gift inside.', 10.00, 10, 47, 1, 'images/products/1744395749_1.jpg', '2025-04-11 18:22:29'),
('4700002', 'BloomNest Wrap ', 'BloomNest Wrap Set brings floral elegance to your gift-giving. Each set includes wrapping sheets, tags, and matching ribbons in beautiful botanical prints. The paper is thick, smooth, and tear-resistant, ensuring your gifts stay perfectly wrapped. Ideal for spring events, birthdays, or Mother\'s Day, BloomNest adds a fresh, natural touch. The coordinated colors and patterns make your present stand out while looking cohesive and curated. With eco-conscious ink and recyclable material, it’s both pretty and planet-friendly.', 15.00, 20, 47, 0, 'images/products/1744395785_2.jpg', '2025-04-11 18:23:05'),
('4700003', 'SparkWrap Metallic Roll', 'SparkWrap Metallic Roll is perfect for those who love a bit of shimmer. This high-shine wrapping paper comes in gold, silver, rose gold, and more. Durable, non-tear foil with a peel-resistant finish ensures a smooth wrap and a glamorous look. It’s ideal for holidays, milestone birthdays, or glam-themed events. Easy to fold and crease, it wraps any shape cleanly. Each roll is long-lasting, giving you value and elegance.', 12.00, 9, 47, 0, 'images/products/1744395827_3.jpg', '2025-04-11 18:23:47'),
('4700004', 'KraftKlassic Box Set', 'KraftKlassic Box Set combines rustic charm with modern design. These earthy-toned kraft paper boxes are sturdy, foldable, and come with twine, tags, and fillers. Great for handmade gifts, treats, or eco-conscious packaging. The minimalist design makes them perfect for custom stamps or stickers. A great pick for sustainable gifting with style.', 20.00, 5, 47, 1, 'images/products/1744395859_4.jpg', '2025-04-11 18:24:19'),
('4700005', 'RibbonRealm Deluxe ', 'RibbonRealm Deluxe Set brings luxury to your wraps with a collection of satin, grosgrain, and velvet ribbons. Each spool features high-quality, non-fray ribbon in coordinated tones. Whether you\'re finishing a wrapped box, tying a bouquet, or sealing a bag, this set elevates the look. Perfect for DIY gift wrapping or professional use.', 8.00, 5, 47, 0, 'images/products/1744395892_5.jpg', '2025-04-11 18:24:52'),
('4800001', 'Whimsical Wonders  Painting', 'Whimsical Wonders is a vibrant and playful cartoon painting that adds a sense of fun and creativity to any space.\r\nFilled with exaggerated characters and bright, bold colors, this artwork instantly grabs attention.\r\nEach character is full of personality, capturing the essence of lightheartedness and joy.\r\nFrom quirky animals to imaginative creatures, the painting brings a sense of whimsy to any room.\r\nThe vibrant palette of colors creates a cheerful and inviting atmosphere, perfect for children\'s rooms.\r\nIts exaggerated expressions and whimsical settings spark the imagination, making it perfect for kids and adults alike.\r\nThe playful nature of this painting encourages creativity and fun, making it ideal for a playroom or nursery.\r\nIts lively and carefree design makes it a perfect addition to any space that needs an uplifting touch.\r\nThe artwork features a variety of adorable cartoon characters, each one more charming than the last.\r\nThe vivid colors are bold and engaging, filling the room with an infectious sense of energy.\r\nWhether hanging on a wall in the living room, a play area, or a bedroom, it will instantly brighten the mood.\r\nIt’s a timeless piece that continues to bring joy and laughter, year after year.\r\nDesigned to appeal to all ages, it’s a great gift for anyone who loves fun, quirky art.\r\nWhimsical Wonders works perfectly in a space where imagination and creativity are encouraged.\r\nThis painting captures the essence of youthful imagination, inviting viewers to explore their own creativity.\r\nIts lighthearted theme makes it a great conversation starter and adds a personal touch to any room.\r\nThe fun, exaggerated details invite you to look closer, discovering new elements every time.\r\nWhimsical Wonders isn’t just a painting; it’s an experience—one that will make everyone smile.\r\nIts universal charm and lively spirit ensure that it fits perfectly in any modern, playful, or colorful decor.\r\nThis piece is perfect for anyone looking to add a splash of joy and fun to their surroundings.', 70.00, 10, 48, 3, 'images/products/1744397261_Art 1.jpg', '2025-04-11 18:47:41'),
('4800002', 'Adventure Awaits – Cartoon Painting', 'Adventure Awaits is a dynamic and action-packed cartoon painting that brings the thrill of exploration to life.\r\nThis artwork features a group of brave characters embarking on a whimsical journey through fantastical lands.\r\nBold lines and vivid colors emphasize the excitement of the adventure, drawing viewers into its world.\r\nFrom treasure maps to magical creatures, this painting is a perfect fit for any space full of curiosity and wonder.\r\nThe vibrant colors create an energetic atmosphere, inspiring a sense of adventure in every viewer.\r\nEach character’s expressive face tells a story of courage and determination as they embark on their quest.\r\nThe painting’s whimsical setting captures the magic of the unknown, sparking the imagination of both children and adults.\r\nIdeal for game rooms, creative spaces, or classrooms, it encourages dreamers to explore their own potential.\r\nAdventure Awaits is a beautiful blend of art and storytelling, each brushstroke adding depth to the adventure.\r\nThe lively and action-filled design makes it an eye-catching piece that is sure to grab attention.\r\nWhether hung in a child\'s room or an office, it instills a sense of excitement and possibility.\r\nThe playful and imaginative characters invite you to see the world in a new light, full of adventure.\r\nThis painting transforms any space into an inspiring environment, motivating everyone to chase their dreams.\r\nAdventure Awaits is perfect for those who believe in the power of imagination and the beauty of exploration.\r\nIts theme of discovery and joy makes it a great gift for adventurers and wanderers at heart.\r\nThe bold and bright design instantly energizes any room, making it a great addition to dynamic spaces.\r\nThis piece is a reminder that every journey holds the possibility for discovery and fun.\r\nIdeal for classrooms, offices, or bedrooms, it encourages a sense of curiosity and excitement.\r\nAdventure Awaits is more than just a painting; it’s an invitation to embark on a journey of imagination.\r\nWhether you’re young or young at heart, this painting brings adventure into your everyday life.', 80.00, 3, 48, 1, 'images/products/1744397311_Art 2.jpg', '2025-04-11 18:48:31'),
('4800003', 'Laugh Out Loud – Cartoon Painting', 'Laugh Out Loud is a cartoon painting that embodies the true spirit of humor and joy.\r\nFilled with exaggerated characters and funny situations, this piece is sure to bring a smile to anyone’s face.\r\nThe whimsical characters are caught in comical predicaments, with exaggerated expressions that evoke laughter.\r\nThe bold use of color and dynamic design adds to the playful nature of the artwork.\r\nEach character\'s hilarious antics create an atmosphere of fun and lightheartedness that fills any room.\r\nPerfect for playrooms, living rooms, or offices, it is a constant reminder to find humor in everyday life.\r\nThe vibrant hues enhance the playful tone of the painting, making it an uplifting addition to any space.\r\nThe exaggerated designs and cartoonish elements bring out the childlike joy in viewers.\r\nWhether hung in a family room or a fun, casual workspace, this painting will instantly lighten the mood.\r\nIts humorous vibe encourages creativity and fun, making it a great piece for creative minds.\r\nLaugh Out Loud is not just a painting—it’s a daily dose of joy and positivity.\r\nIdeal for kids and adults alike, it spreads laughter and cheer wherever it’s placed.\r\nIts lighthearted theme makes it a perfect gift for anyone who appreciates comedy and vibrant art.\r\nThis painting is a playful, whimsical escape into a world where laughter is the best medicine.\r\nIts quirky characters invite viewers to step into a world of pure joy and fun.\r\nThe exaggerated expressions and playful design make this a great conversation starter.\r\nLaugh Out Loud brings a sense of humor to spaces where people gather and share moments of joy.\r\nPerfect for anyone who loves to laugh, this painting adds a burst of personality and warmth.\r\nIt’s a great reminder to not take life too seriously and to embrace the fun moments.\r\nHang it in your favorite spot to brighten your day with a cheerful, comedic vibe.', 120.00, 4, 48, 1, 'images/products/1744397367_Art 3.jpg', '2025-04-11 18:49:27'),
('4800004', 'Toon Town Fun – Cartoon Painting', 'Toon Town Fun is a colorful and lively cartoon painting that brings the joy of animated characters to life.\r\nWith bold outlines and exaggerated features, this painting depicts a bustling cartoon town filled with action.\r\nFrom wacky animals to playful characters, each figure has a story to tell, adding depth and personality to the painting.\r\nThe playful design and vivid colors infuse energy into any room, making it ideal for spaces that encourage creativity.\r\nThis artwork brings the charm of animated worlds into real life, perfect for children\'s rooms or fun spaces.\r\nThe exaggerated designs and quirky characters create a whimsical vibe that’s perfect for young and old alike.\r\nThe bright color scheme energizes any room, sparking imagination and creating a happy, joyful environment.\r\nToon Town Fun captures the carefree essence of cartoons and brings it into your home or office.\r\nIdeal for spaces that need a touch of fun and excitement, it’s a great piece to inspire creativity.\r\nIts lively atmosphere makes it an excellent addition to a classroom, playroom, or any area designed for children.\r\nThis painting encourages viewers to tap into their own sense of fun and embrace their imagination.\r\nToon Town Fun is a great gift for anyone who loves cartoons or enjoys whimsical, colorful art.\r\nIts simple yet captivating design makes it an attention-grabbing piece in any setting.\r\nThis artwork works beautifully in bedrooms, living rooms, or creative spaces where laughter and joy are always welcome.\r\nWith its friendly characters and bright design, it will appeal to people of all ages.\r\nWhether you\'re a cartoon enthusiast or just love fun, lively art, this painting adds character to any space.\r\nThe fun, animated style invites you to leave the ordinary behind and step into a world of imagination.\r\nToon Town Fun is sure to bring a smile to anyone’s face with its lighthearted, carefree charm.\r\nThis piece is more than just a decoration—it’s a gateway to a joyful, colorful world.\r\nAdd it to your collection and let the fun begin in your home.', 90.00, 6, 48, 2, 'images/products/1744397405_Art 4.jpg', '2025-04-11 18:50:05'),
('4800005', 'Dreamy Creatures – Cartoon Painting', 'Dreamy Creatures is a soft, serene cartoon painting that captures the magic of fantastical beings and whimsical worlds.\r\nGentle pastel colors and subtle details create a peaceful atmosphere, ideal for relaxation and creativity.\r\nThe painting features mystical creatures, each more enchanting than the last, inviting viewers into a world of imagination.\r\nWith its calming palette and dreamlike setting, Dreamy Creatures is perfect for bedrooms, nurseries, or spaces dedicated to relaxation.\r\nThe soft hues and delicate lines give this painting a tranquil and peaceful vibe, making it ideal for a calming space.\r\nThe whimsical creatures are friendly and comforting, adding a touch of fantasy and wonder to your room.\r\nPerfect for anyone who enjoys gentle, imaginative art, this painting evokes feelings of serenity and joy.\r\nIts subtle yet captivating design makes it a great choice for creating a peaceful ambiance in any room.\r\nThe dreamy creatures in this painting are a reminder of the beauty and magic that imagination can create.\r\nIdeal for nurseries or children’s rooms, Dreamy Creatures sparks creativity in a soft, gentle way.\r\nThis artwork works beautifully in spaces designed for relaxation, such as reading corners or meditation areas.\r\nThe soothing colors and graceful figures bring a sense of calm to any space.\r\nDreamy Creatures is a fantastic choice for anyone who wants to add a touch of peaceful fantasy to their home.\r\nThe delicate details and soft tones create an atmosphere of serenity and quiet beauty.\r\nPerfect for bedrooms, meditation spaces, or even office areas where calm and creativity are essential.\r\nThis painting offers a moment of calm in the busy world, allowing you to escape into a dreamlike realm.\r\nDreamy Creatures is a timeless piece that will continue to captivate and inspire, making it perfect for any age.\r\nThe gentle beauty of this piece is perfect for creating a peaceful sanctuary in your home.\r\nAdd a little magic and tranquility to your space with Dreamy Creatures.', 12000.00, 3, 48, 1, 'images/products/1744397439_Art 5.jpg', '2025-04-11 18:50:39');

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
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `code_id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `discount_percent` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_used` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promo_codes`
--

INSERT INTO `promo_codes` (`code_id`, `code`, `discount_percent`, `request_id`, `user_id`, `created_by`, `created_at`, `expires_at`, `is_used`) VALUES
(10, 'PROMO944E2197', 15, 23, 17, 20, '2025-04-27 13:04:36', '2025-05-04 13:04:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `promo_code_requests`
--

CREATE TABLE `promo_code_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promo_code_requests`
--

INSERT INTO `promo_code_requests` (`request_id`, `user_id`, `request_date`, `status`) VALUES
(23, 17, '2025-04-27 18:04:14', 'approved');

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
  `return_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`return_id`, `order_id`, `product_id`, `reason`, `return_status`, `return_date`, `read_status`) VALUES
(17, 'c-4800005-67f964', '4800005', 'Defected', 'rejected', '2025-04-11 18:53:54', 1),
(18, 'c-3800002-67f967', '3800002', 'defected', 'rejected', '2025-04-25 11:16:03', 0),
(19, 'c-3800002-67f967', '3800002', 'defected\r\n', 'approved', '2025-04-25 12:10:11', 0);

--
-- Triggers `returns`
--
DELIMITER $$
CREATE TRIGGER `after_return_status_update` AFTER UPDATE ON `returns` FOR EACH ROW BEGIN
    IF NEW.return_status != OLD.return_status THEN
        -- Get user_id from orders table
        SET @user_id = (SELECT u_id FROM orders WHERE order_id = NEW.order_id LIMIT 1);
        
        INSERT INTO notifications (user_id, title, message, related_table, related_id)
        VALUES (
            @user_id,
            'Return Status Updated',
            CONCAT('Your return for order #', NEW.order_id, ' is now ', NEW.return_status),
            'returns',
            NEW.return_id
        );
    END IF;
END
$$
DELIMITER ;

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
(24, '4800005', 'Ahmed', 5, 'Excellent', '2025-04-11 18:53:18'),
(25, '3800005', 'Muhammad Ahmed', 4, 'great\r\n', '2025-04-22 10:25:27');

-- --------------------------------------------------------

--
-- Table structure for table `stock_update`
--

CREATE TABLE `stock_update` (
  `update_id` int(11) NOT NULL,
  `product_id` char(7) NOT NULL,
  `previous_quantity` int(11) NOT NULL,
  `new_quantity` int(11) NOT NULL,
  `quantity_change` int(11) NOT NULL,
  `update_reason` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_update`
--

INSERT INTO `stock_update` (`update_id`, `product_id`, `previous_quantity`, `new_quantity`, `quantity_change`, `update_reason`, `updated_at`) VALUES
(4, '4800003', 2, 0, -2, 'out of stock', '2025-04-25 05:42:59'),
(5, '4800003', 0, 4, 4, 'Out of stock', '2025-04-25 11:54:17');

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
(17, 'Ahmed', '$2y$10$BkJ8.7ZwIta2GSF/4zAy2Otzk0eaGz/uu2Qw/IyPzrqWPQDl/Y4Xm', 'Muhammad Ahmed', 'ahmed@gmail.com', '', 'customer', '', '2025-04-10 17:47:14'),
(18, 'Ali12', '$2y$10$s4uywapiZ/JPr/AXhygzRODTdyIso8Qdcc2UXvE/VPBX/CLZoMPpO', 'Ali Khan', 'ali@gmail.com', '+923442681140', 'employee', 'Karachi', '2025-04-10 19:35:44'),
(20, 'Administarator', '$2y$10$lCQ.4Olkv0N.f9GX.CPkcuOPWYPStW.eBH11pAvWgpDj/F20w1Ywq', 'Admin', 'admin@gmail.com', NULL, 'admin', NULL, '2025-04-11 13:31:17');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` char(7) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_payments` (`payment_id`);

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
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`code_id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `promo_code_requests`
--
ALTER TABLE `promo_code_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `stock_update`
--
ALTER TABLE `stock_update`
  ADD PRIMARY KEY (`update_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD UNIQUE KEY `unique_wishlist_item` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `code_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `promo_code_requests`
--
ALTER TABLE `promo_code_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `stock_update`
--
ALTER TABLE `stock_update`
  MODIFY `update_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `deliveries_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `deliveries_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

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
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_payments` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`payment_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD CONSTRAINT `fk_promo_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_promo_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `promo_code_requests`
--
ALTER TABLE `promo_code_requests`
  ADD CONSTRAINT `promo_code_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

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
-- Constraints for table `stock_update`
--
ALTER TABLE `stock_update`
  ADD CONSTRAINT `stock_update_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
