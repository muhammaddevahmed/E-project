-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:8111
-- Generation Time: Apr 10, 2025 at 02:26 PM
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
(13, 'Dolls', 'images/categories/1742733247_Dolls.jpg', '2025-03-23 12:34:07'),
(14, 'School Bags', 'images/categories/1742733280_School Bags.jpg', '2025-03-23 12:34:40'),
(15, 'Sports Items', 'images/categories/1742733311_Sports items.jpg', '2025-03-23 12:35:11'),
(16, 'Stationary Items', 'images/categories/1742733334_Stationary items.jpg', '2025-03-23 12:35:34'),
(17, 'Beauty Products', 'images/categories/1742733358_Beauty Products.jpg', '2025-03-23 12:35:58'),
(18, 'Wallets', 'images/categories/1742733383_Wallets.jpg', '2025-03-23 12:36:23'),
(19, 'Hand Bags', 'images/categories/1742733409_Hand bags.jpg', '2025-03-23 12:36:49'),
(20, 'Files', 'images/categories/1742733436_Files.jpg', '2025-03-23 12:37:16'),
(21, 'Greeting Cards', 'images/categories/1742733468_Greeting Cards.jpg', '2025-03-23 12:37:48'),
(22, 'Gift Articles', 'images/categories/1742733494_Gifts Articles.jpg', '2025-03-23 12:38:14'),
(23, 'Arts and Crafts', 'images/categories/1742733521_Arts.jpg', '2025-03-23 12:38:41');

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
(5, 'Muhammad Ahmed ', 'ahmed@gmail.com', 12, 'good', 5, '2025-04-07 18:31:29');

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
  `payment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `delivery_type`, `product_id`, `order_number`, `u_name`, `u_email`, `p_name`, `p_price`, `p_qty`, `date_time`, `status`, `u_id`, `payment_id`) VALUES
('p-1600002-67f79c', 'p', '1600002', '67f79c65', 'Muhammad Ahmed', 'ahmed@gmail.com', 'GeoMaster Geometry Box', 30, 1, '2025-04-10 10:24:37', 'pending', 12, 27);

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
(25, 'paypal', 40.00, 'pending', '2025-04-10 10:04:26', 'Muhammad', 'Ahmed', 'pakistan', 'House No N1819/A metrovill 3rd Gulzar-e-hijri Karchi', 'Karachi', 'SD', '4667', '+923442681140', 'ahmed@gmail.com', 'hi', NULL, NULL, NULL, NULL, NULL),
(26, 'paypal', 70.00, 'pending', '2025-04-10 10:08:29', 'Muhammad', 'Ahmed', 'pakistan', 'House No N1819/A metrovill 3rd Gulzar-e-hijri Karchi', 'Karachi', 'Sindh', '24455', '+923442681140', 'ahmed@gmail.com', 'hi', NULL, NULL, NULL, NULL, NULL),
(27, 'paypal', 30.00, 'pending', '2025-04-10 10:24:37', 'Muhammad', 'Ahmed', 'pakistan', 'House No N1819/A metrovill 3rd Gulzar-e-hijri Karchi', 'Karachi', 'SD', '24455', '+923442681140', 'ahmed@gmail.com', 'hi', NULL, NULL, NULL, NULL, NULL);

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
('1300001', 'Charm Doll', 'Charm Doll is a beautifully crafted doll designed to bring joy and warmth. With delicate features and soft fabric, it is perfect for cuddling. The doll\'s charming outfit adds to its elegance, making it a wonderful companion. Its expressive eyes and gentle smile create an endearing presence. Made from high-quality materials, it ensures durability and comfort. Charm Doll is ideal for both playtime and display. It can be a cherished keepsake for years to come. The lightweight design makes it easy to carry everywhere. Whether as a gift or a personal collectible, it brings happiness. Its timeless appeal makes it suitable for all ages. The doll’s intricate detailing showcases fine craftsmanship. It encourages imaginative play and storytelling. Charm Doll is designed to be soft and huggable. It creates a sense of comfort and companionship. The charming outfit is removable for customization. It is safe and non-toxic for worry-free play. A perfect choice for doll lovers of all ages. Charm Doll adds elegance to any collection. Its soft texture provides a soothing touch. A wonderful gift for birthdays or special occasions. Charm Doll is a symbol of beauty and warmth.', 25.00, 10, 13, 1, 'images/products/1742735863_Doll 1.jpg', '2025-03-23 13:17:43'),
('1300002', 'Magic Doll', 'Magic Doll is a delightful companion designed to spark imagination and joy. With its enchanting design, it captures hearts instantly. The soft and cuddly texture makes it perfect for hugs. Its beautifully crafted outfit adds a touch of elegance. The expressive eyes and sweet smile bring it to life. Made from high-quality, durable materials, it ensures long-lasting fun. Magic Doll is lightweight and easy to carry anywhere. It inspires creativity and storytelling in children. The doll’s unique charm makes it a perfect collectible. Whether for playtime or display, it stands out beautifully. Safe and non-toxic, it’s ideal for all ages. The detailed craftsmanship showcases its fine artistry. Its removable outfit allows for fun customization. Magic Doll offers comfort and warmth to every owner. A perfect gift for birthdays and special occasions. It brings happiness and companionship to every moment. Designed to be soft and huggable for endless cuddles. Its timeless appeal makes it a treasured keepsake. Magic Doll brightens up any room with its charm. A true symbol of magic, love, and wonder.', 20.00, 12, 13, 2, 'images/products/1742735955_Doll 2.jpg', '2025-03-23 13:19:15'),
('1300003', 'Sweet Doll', 'Sweet Doll is a lovable companion designed to bring warmth and joy. Its soft and cuddly texture makes it perfect for hugs. The delicate facial features give it an adorable charm. Dressed in a beautifully designed outfit, it stands out. Its gentle smile and bright eyes create a heartwarming presence. Made from high-quality materials, it ensures durability and comfort. Sweet Doll is lightweight and easy to carry anywhere. It encourages creativity and imaginative play for children. The doll’s charming design makes it a perfect collectible. Whether for playtime or display, it adds a special touch. Safe and non-toxic, it is suitable for all ages. The fine craftsmanship highlights its beauty and elegance. Its removable outfit allows for fun customization. Sweet Doll offers a sense of comfort and companionship. A perfect gift for birthdays and special occasions. It brings happiness and warmth to every moment. Designed to be soft and huggable for endless cuddles. Its timeless appeal makes it a treasured keepsake. Sweet Doll adds love and charm to any collection. A true symbol of innocence, joy, and care.', 35.00, 15, 13, 2, 'images/products/1742736043_Doll 3.jpg', '2025-03-23 13:20:43'),
('1300004', 'Star Doll', 'Star Doll is a magical companion that shines with charm and beauty. Its soft and plush texture makes it perfect for cuddles. The delicate facial details give it an adorable and lifelike appeal. Dressed in a stylish outfit, it adds a touch of elegance. Its bright eyes sparkle with warmth and happiness. Made from high-quality materials, it ensures durability and comfort. Star Doll is lightweight and easy to carry anywhere. It inspires creativity and imaginative storytelling in children. The doll’s enchanting design makes it a perfect collectible. Whether for playtime or display, it stands out beautifully. Safe and non-toxic, making it suitable for all ages. The fine craftsmanship highlights its attention to detail. Its removable outfit allows for fun dress-up customization. Star Doll brings a sense of joy and companionship. A perfect gift for birthdays and special occasions. It spreads happiness and warmth with its charming presence. Designed to be soft and huggable for endless cuddles. Its timeless appeal makes it a cherished keepsake. Star Doll adds magic and love to any collection. A true symbol of dreams, wonder, and imagination.', 27.00, 10, 13, 3, 'images/products/1742736123_Doll 4.jpg', '2025-03-23 13:22:03'),
('1300005', 'Lovely Doll', 'Lovely Doll is a heartwarming companion filled with charm and sweetness. Its soft and plush texture makes it perfect for cuddling. The delicate facial features give it an adorable and lifelike appeal. Dressed in a beautifully designed outfit, it radiates elegance. Its bright, expressive eyes create a warm and joyful presence. Made from high-quality, durable materials, it ensures long-lasting comfort. Lovely Doll is lightweight and easy to carry anywhere. It inspires creativity and imaginative storytelling in children. The doll’s charming design makes it a delightful collectible. Whether for playtime or display, it stands out beautifully. Safe and non-toxic, making it suitable for all ages. The detailed craftsmanship highlights its beauty and uniqueness. Its removable outfit allows for fun dress-up customization. Lovely Doll brings a sense of warmth and companionship. A perfect gift for birthdays and special occasions. It spreads happiness and love with its delightful presence. Designed to be soft and huggable for endless cuddles. Its timeless appeal makes it a treasured keepsake. Lovely Doll adds love and charm to any collection. A true symbol of innocence, kindness, and joy.', 18.00, 5, 13, 1, 'images/products/1742736226_Doll 5.jpg', '2025-03-23 13:23:47'),
('1300006', 'Barbie Doll', 'Barbie Doll is a timeless icon of fashion, beauty, and imagination. With her signature elegance, she inspires creativity and storytelling. Her stylish outfits reflect the latest fashion trends. Designed with attention to detail, she radiates charm and sophistication. Barbie’s bright eyes and warm smile bring her to life. She encourages children to dream big and explore new possibilities. Made from high-quality materials, she ensures durability and lasting play. Her flexible limbs allow for dynamic poses and endless fun. Barbie comes with accessories that enhance imaginative role-playing. She represents confidence, ambition, and self-expression. Whether a princess, doctor, or entrepreneur, she embraces all roles. Her diverse collection showcases different cultures and career aspirations. Barbie’s ever-evolving styles keep her fresh and exciting. She is more than a doll—she is an inspiration. A perfect gift for kids and collectors alike. Her beautiful outfits can be mixed and matched. She fosters creativity through fashion and storytelling. Barbie brings joy to every playtime adventure. A true symbol of glamour, empowerment, and endless possibilities.', 40.00, 20, 13, 4, 'images/products/1742736314_Doll 6.jpg', '2025-03-23 13:25:14'),
('1300007', 'TrendBackpack', 'TrendBackpack is the perfect blend of style and functionality for students and professionals. It features a spacious main compartment to store books, notebooks, and gadgets. Multiple pockets help keep smaller essentials organized and easy to access. Made from high-quality, durable materials, it ensures long-lasting use. The padded shoulder straps provide extra comfort for all-day carrying. A sturdy top handle offers a convenient alternative carrying option. The lightweight design makes it easy to carry without strain. A dedicated laptop sleeve protects devices during travel. Water-resistant fabric helps keep belongings safe from unexpected spills or rain. The sleek and modern design suits all ages and styles. Strong and smooth zippers allow easy access to stored items. Side mesh pockets are perfect for carrying water bottles and small accessories. Breathable back padding enhances airflow for maximum comfort. Available in multiple trendy colors and designs. A compact yet spacious layout accommodates all daily essentials. An anti-theft pocket provides added security for valuables. Reflective details improve visibility for nighttime safety. TrendBackpack is ideal for school, work, and travel. It offers the perfect balance of practicality and fashion. A must-have for those who want to stay stylish and organized.', 50.00, 20, 14, 2, 'images/products/1742736932_Bag 3.jpg', '2025-03-23 13:35:32'),
('1400001', 'SmartPack Bag', 'SmartPack Bag is designed for students who need both style and functionality. It offers a spacious main compartment to store books, notebooks, and other essentials. The bag features multiple pockets to keep items organized and easily accessible. Made from durable, high-quality materials, it ensures long-lasting use. The padded shoulder straps provide comfort for everyday carrying. A reinforced handle adds convenience for quick grabs on the go. Its lightweight design makes it easy to carry without strain. The bag includes a dedicated laptop sleeve for extra protection. A water-resistant exterior keeps belongings safe in unexpected weather. Stylish and modern, it suits students of all ages. The strong zippers ensure smooth opening and closing. Side mesh pockets offer space for water bottles and snacks. Breathable back padding enhances comfort during long school days. Available in multiple colors and designs to match personal style. The compact yet spacious design fits everything needed for class. An anti-theft pocket provides extra security for valuable items. Reflective strips improve visibility for safety during late walks. The SmartPack Bag combines durability with a trendy appearance. Ideal for school, travel, and daily commutes. It’s the perfect companion for students on the move.', 40.00, 14, 14, 3, 'images/products/1742736693_Bag 1.jpg', '2025-03-23 13:31:33'),
('1400002', 'EduGear Bag', 'EduGear Bag is designed for students who need reliability and style. It features a spacious main compartment to hold books, notebooks, and school supplies. Multiple pockets ensure easy organization of smaller essentials. Made from high-quality, durable materials, it withstands daily wear and tear. Padded shoulder straps provide comfort for long hours of carrying. A sturdy top handle offers an alternative carrying option. The lightweight design prevents unnecessary strain on the shoulders. A dedicated laptop sleeve keeps devices secure and protected. Water-resistant fabric helps protect belongings from unexpected spills or rain. Sleek and modern, it suits students of all ages. Strong zippers allow smooth and easy access to contents. Side mesh pockets are perfect for holding water bottles and snacks. Breathable back padding enhances comfort and airflow. Available in various colors and patterns to match personal preferences. A compact yet spacious design accommodates all school essentials. An anti-theft pocket ensures extra security for valuable items. Reflective strips increase visibility for added safety. The EduGear Bag blends durability with a trendy look. Ideal for school, travel, and daily commutes. It’s the perfect companion for students on the go.', 40.00, 13, 14, 3, 'images/products/1742736866_Bag 2.jpg', '2025-03-23 13:34:26'),
('1400008', 'StudyMate Bag', 'StudyMate Bag is designed for students who need a reliable and stylish backpack for daily use. It features a spacious main compartment to store books, notebooks, and gadgets. Multiple pockets help keep school supplies and personal items neatly organized. Made from durable, high-quality materials, it ensures long-lasting performance. The padded shoulder straps provide comfort for extended carrying. A sturdy top handle offers an alternative carrying option when needed. Its lightweight design makes it easy to carry without added strain. A dedicated laptop sleeve keeps devices secure and protected. Water-resistant fabric helps protect belongings from spills and light rain. A sleek and modern design makes it suitable for students of all ages. Strong and smooth zippers allow easy access to essentials. Side mesh pockets are perfect for carrying water bottles or small accessories. Breathable back padding enhances airflow for improved comfort. Available in various stylish colors to match personal preferences. A compact yet spacious design accommodates all school essentials. An anti-theft pocket ensures added security for valuable items. Reflective details increase visibility for nighttime safety. StudyMate Bag is ideal for school, college, and daily commutes. It perfectly balances functionality and fashion for students. A must-have companion for organized and hassle-free learning.', 45.00, 15, 14, 3, 'images/products/1742737171_Bag 4.jpg', '2025-03-23 13:39:31'),
('1400009', 'ScholarPack', 'ScholarPack is a smart and durable backpack designed for students who need both style and functionality. It features a spacious main compartment to store textbooks, notebooks, and stationery. Multiple pockets help keep smaller essentials organized and easily accessible. Made from high-quality, durable materials, it ensures long-lasting use. The padded shoulder straps provide extra comfort for carrying heavy loads. A sturdy top handle offers an easy carrying alternative when needed. Its lightweight design reduces strain and makes daily commutes easier. A dedicated laptop sleeve protects electronic devices during travel. Water-resistant fabric keeps belongings safe from light rain and accidental spills. A sleek and modern design makes it suitable for students of all ages. Strong and smooth zippers ensure hassle-free access to stored items. Side mesh pockets are perfect for carrying water bottles and umbrellas. Breathable back padding enhances airflow for maximum comfort. Available in multiple trendy colors to match personal style. A compact yet spacious layout accommodates all daily essentials. An anti-theft pocket provides additional security for valuables. Reflective details improve visibility for added safety at night. ScholarPack is perfect for school, college, and everyday use. It offers the ideal combination of practicality, comfort, and fashion. A must-have for students who want to stay organized and prepared.', 35.00, 30, 14, 2, 'images/products/1742737235_Bag 5.jpg', '2025-03-23 13:40:35'),
('1400010', 'LearnEase Bag', 'LearnEase Bag is the perfect companion for students who value comfort and organization. Designed with a spacious main compartment, it easily fits books, notebooks, and essential school supplies. Multiple storage pockets help keep everything neatly arranged for quick access. Crafted from high-quality, durable materials, it withstands daily wear and tear. The padded shoulder straps offer extra support for a comfortable carrying experience. A reinforced top handle provides an easy grab-and-go option. The lightweight construction minimizes strain, making it ideal for long school days. A dedicated laptop sleeve ensures safe and secure device storage. Water-resistant fabric protects belongings from unexpected spills and light rain. The modern and stylish design suits students of all ages. Smooth, high-quality zippers allow effortless opening and closing. Side mesh pockets are perfect for holding water bottles or small accessories. The breathable back padding promotes airflow, reducing heat and discomfort. Available in a variety of trendy colors to match personal style preferences. A compact yet spacious design provides ample room for school essentials. An anti-theft pocket adds an extra layer of security for valuables. Reflective elements improve nighttime visibility for added safety. LearnEase Bag is perfect for school, college, or daily commuting. It combines durability, convenience, and style in one practical package. A must-have backpack for students who want to stay organized and comfortable.', 42.00, 10, 14, 3, 'images/products/1742737291_Bag 6.jpg', '2025-03-23 13:41:31'),
('1500001', 'Cricket Kit Bag', 'PowerPlay Kit Bag is designed for cricketers who need a spacious and durable storage solution. It features a large main compartment to accommodate bats, pads, gloves, and other essential gear. Multiple zippered pockets help organize smaller accessories like balls, grips, and protective gear. Made from high-quality, tear-resistant material, it ensures long-lasting performance. Padded shoulder straps and grab handles provide comfortable carrying options. A ventilated shoe compartment keeps footwear separate and fresh. Smooth, heavy-duty zippers allow easy access to all stored items. Reinforced stitching adds extra durability to withstand heavy loads. The water-resistant fabric protects equipment from light rain and moisture. A trolley system with wheels is available in select models for effortless transport. Stylish and sleek design makes it suitable for professional and amateur players. Spacious bat sleeves offer secure storage for multiple bats. Adjustable straps ensure a snug and comfortable fit while carrying. Side mesh pockets provide additional space for water bottles or personal items. A structured base helps maintain the bag’s shape and stability. Reflective detailing enhances visibility for safety during evening practice. The lightweight design minimizes strain while carrying heavy gear. Available in a variety of colors to match team spirit and personal preference. Perfect for training sessions, matches, and travel. PowerPlay Kit Bag is the ultimate companion for cricketers who demand convenience, durability, and style.', 60.00, 30, 15, 5, 'images/products/1742740879_Sports 1.jpg', '2025-03-23 14:41:19'),
('1500002', 'Pakistan Cricket Jearsy', 'GreenSpirit Jersey is designed for passionate Pakistan sports fans and athletes. Made from high-quality, breathable fabric, it keeps you cool and comfortable during intense matches. The lightweight material allows maximum flexibility and movement. A moisture-wicking design helps absorb sweat, keeping you dry. The iconic green and white color combination represents Pakistan’s pride. The jersey features a stylish and modern fit for a professional look. Durable stitching ensures long-lasting wear, even in tough conditions. A soft, skin-friendly texture prevents irritation during extended use. The Pakistan emblem is proudly displayed on the chest for national pride. Available in various sizes for players and fans of all ages. The fabric is easy to wash and retains its vibrant color after multiple uses. Mesh ventilation panels enhance airflow for added comfort. An athletic cut provides a perfect fit for both training and casual wear. Ideal for cricket, football, and other sports events. Lightweight construction reduces fatigue during extended play. The jersey’s fade-resistant print ensures long-lasting style. Reinforced seams prevent wear and tear from frequent use. A stylish collar and sleeve design enhance its sporty appeal. Perfect for supporting the Pakistan team during international tournaments. GreenSpirit Jersey is a must-have for every sports enthusiast who loves Pakistan!', 70.00, 50, 15, 10, 'images/products/1742742015_Sports 2.jpg', '2025-03-23 15:00:15'),
('1500003', 'ProCricket Kit', 'ProCricket Kit is the ultimate gear set for every aspiring and professional cricketer. It includes all essential equipment to enhance performance and ensure safety on the field. The high-quality cricket bat is crafted from premium willow for maximum power and durability. Lightweight batting pads provide superior protection without compromising movement. Comfortable batting gloves offer a strong grip and prevent hand injuries. A sturdy helmet with a protective grille ensures head safety against fast bowlers. The durable thigh guard and arm guard add extra defense against impact. A well-cushioned wicket-keeping set is included for those behind the stumps. The cricket ball is made of high-grade leather for consistent bounce and performance. A spacious and durable cricket kit bag allows easy storage and transport of gear. The kit also includes essential accessories like grips, a chest guard, and bails. Designed for players of all levels, from beginners to professionals. The materials used in the kit ensure long-lasting durability and comfort. A water-resistant outer layer protects the equipment from moisture and dust. The ergonomic design of each item allows for a comfortable fit and ease of movement. Ventilated padding in protective gear ensures breathability during long matches. The kit complies with international cricket safety standards. Available in different sizes to suit junior and senior players. An adjustable strap system in pads and gloves provides a customized fit. A stylish and professional look adds confidence on the field. ProCricket Kit is the perfect choice for every cricket enthusiast looking for quality and performance!', 100.00, 20, 15, 30, 'images/products/1742742124_sports 3.jpg', '2025-03-23 15:02:04'),
('1500004', 'Football', 'ProKick Football Kit is designed for players of all levels, offering high-quality gear to enhance performance and comfort on the field. The kit includes a premium football made from durable, weather-resistant material for consistent play. Lightweight yet sturdy shin guards provide excellent protection against tackles. A pair of breathable football socks ensures comfort and prevents blisters during long matches. The professional-grade jersey and shorts are made from moisture-wicking fabric to keep players cool and dry. Football boots with firm grip soles offer superior traction on various playing surfaces. The kit also features goalkeeper gloves with strong grip and cushioning for better ball control. A spacious football bag allows easy storage and transport of all gear. The included water bottle keeps players hydrated during intense training and matches. An adjustable training bib is provided for team drills and practice sessions. High-quality ankle support ensures stability and prevents injuries. The kit is designed to be lightweight, ensuring ease of movement and agility. Available in different sizes to fit both junior and senior players. The durable stitching and reinforced materials extend the lifespan of the gear. A sleek, professional design gives players confidence on the field. The football meets international match standards for optimal performance. Elastic waistbands on shorts provide a secure and comfortable fit. The breathable mesh panels in the jersey enhance ventilation. Quick-dry fabric technology helps players stay fresh throughout the game. The ProKick Football Kit is perfect for training, league matches, and casual play. With this kit, every player is ready to dominate the game with style and confidence!', 80.00, 50, 15, 6, 'images/products/1742742271_Sports 4.jpg', '2025-03-23 15:04:31'),
('1500005', 'StrikePro Soccer Cleats', 'StrikePro Soccer Cleats are engineered for ultimate performance, speed, and comfort on the field. Designed with high-quality synthetic leather, they provide a snug fit and excellent ball control. The lightweight construction ensures agility, allowing players to move swiftly without feeling weighed down. A durable rubber outsole with molded studs offers superior traction on both natural grass and artificial turf. The breathable mesh lining keeps feet cool and prevents moisture buildup during intense matches. Padded insoles provide extra cushioning for long-lasting comfort. The reinforced toe cap enhances durability, reducing wear and tear from powerful kicks. Flexible ankle support offers stability without restricting movement. StrikePro Cleats feature an ergonomic design that reduces pressure on the feet, minimizing fatigue. The lace-up closure ensures a secure and customizable fit. Water-resistant materials help maintain grip and control in wet conditions. The cleats are available in various colors and sizes to match different playing styles. Their dynamic design enhances speed and maneuverability, perfect for fast-paced games. The non-slip inner sole prevents foot sliding inside the shoe for better stability. StrikePro Soccer Cleats are suitable for both amateur and professional players. Designed with shock-absorbing technology, they reduce impact strain on feet and legs. A stylish, modern look makes them a favorite among soccer enthusiasts. The cleats comply with international soccer standards for optimal gameplay. Ideal for practice sessions, competitive matches, and casual play. With StrikePro Soccer Cleats, every step on the field is powerful, precise, and performance-driven!\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', 120.00, 30, 15, 12, 'images/products/1742742481_Sports 5.jpg', '2025-03-23 15:08:01'),
('1500006', 'PowerBlade Hockey Stick', 'The PowerBlade Hockey Stick is designed for players who demand precision, power, and control. Crafted from high-quality carbon fiber and reinforced composite materials, it offers exceptional durability and flexibility. The lightweight design ensures quick handling, allowing for smooth dribbling and powerful shots. The ergonomically designed grip provides a comfortable hold, reducing hand fatigue during intense matches. The curved blade enhances ball control, making passes and flicks more accurate. Its aerodynamic shaft minimizes air resistance, improving swing speed and agility. The stick’s balanced weight distribution allows for better maneuverability on the field. The reinforced toe area increases durability against rough surfaces and impacts. Ideal for both beginner and professional players, it adapts to different playing styles. The anti-slip texture ensures a firm grip even in wet conditions. PowerBlade Hockey Stick is engineered for both defensive and offensive plays, providing versatility on the field. Designed for excellent shock absorption, it reduces vibrations for a smoother playing experience. Available in multiple sizes to suit players of all ages and skill levels. The sleek and modern design gives it a professional look, making it a top choice for serious athletes. Its advanced materials enhance energy transfer, maximizing shot power with minimal effort. Suitable for both turf and grass fields, delivering consistent performance. The PowerBlade is built to endure rigorous training sessions and competitive games. Designed with cutting-edge technology, it meets international hockey standards. Whether you’re mastering drag flicks or defending the goal, the PowerBlade Hockey Stick is your key to dominating the game!\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', 60.00, 25, 15, 5, 'images/products/1742742600_sports 6.jpg', '2025-03-23 15:10:00'),
('1600001', 'NoteMaster Notebook', 'The NoteMaster Notebook is the perfect companion for students, professionals, and creatives alike. Designed with high-quality, smooth pages, it ensures a seamless writing experience. The durable hardcover protects your notes from wear and tear, making it ideal for everyday use. With a sturdy spiral binding, the pages lie flat for easy writing and sketching. The premium paper prevents ink bleeding, making it suitable for all types of pens and pencils. Available in multiple sizes, it fits easily into backpacks, purses, and briefcases. The sleek and modern design adds a touch of elegance to your stationery collection. Whether for school, work, or personal journaling, it helps keep your thoughts organized. The lightweight yet durable construction makes it perfect for on-the-go use. With ruled, plain, and dotted page options, it caters to various writing and drawing needs. Designed for long-term use, the high-quality binding ensures pages stay intact. The soft, smooth texture of the paper enhances writing comfort. Its eco-friendly materials make it a sustainable choice for conscious consumers. The NoteMaster Notebook is an essential tool for brainstorming, note-taking, and creative expression. Perfect for students preparing for exams, professionals jotting down ideas, or artists sketching on the go. The elegant cover designs add a stylish touch to your daily essentials. Ideal for keeping track of schedules, to-do lists, and important reminders. The compact size makes it easy to carry anywhere without hassle. Whether you\'re in a lecture hall, office, or coffee shop, NoteMaster keeps your notes organized and accessible!', 10.00, 70, 16, 0, 'images/products/1742743394_Stationary 1.jpg', '2025-03-23 15:23:14'),
('1600002', 'GeoMaster Geometry Box', 'The GeoMaster Geometry Box is the ultimate toolset for students, engineers, and artists. It includes high-quality, precision-engineered instruments for accurate measurements and drawings. The set features a durable compass, divider, ruler, protractor, and set squares for all your geometric needs. Made from strong, rust-resistant metal and sturdy plastic, it ensures long-lasting use. The smooth-rotating compass allows for effortless circles and arcs. The ruler and set squares have clear, easy-to-read markings for precise measurements. The protractor ensures perfect angles, making it ideal for math and design work. The divider is great for measuring distances with accuracy. A sharp, durable pencil and eraser are included for immediate use. The compact, lightweight box keeps all tools neatly organized and easy to carry. Designed for students of all ages, from primary school to advanced mathematics. The ergonomic grip on the compass ensures comfortable handling. Its sleek and modern design makes it an essential addition to any stationery kit. Ideal for geometry, technical drawing, engineering, and architectural work. The sturdy case prevents damage and keeps instruments secure during transport. Perfect for school exams, art projects, and professional drafting tasks. The non-slip design of the tools ensures stability while working on detailed designs. Helps improve accuracy in mathematical calculations and creative designs. Whether you\'re solving geometric problems or crafting intricate sketches, GeoMaster delivers precision and reliability.', 30.00, 20, 16, 1, 'images/products/1742743467_stationary 2.jpg', '2025-03-23 15:24:27'),
('1600003', 'QuickCalc Calculator', 'The QuickCalc Calculator is a reliable and efficient tool for students, professionals, and everyday users. Designed for fast and accurate calculations, it features a clear LCD display with large, easy-to-read numbers. The responsive keypad ensures smooth and effortless input, making complex calculations simple. It supports basic arithmetic functions as well as advanced mathematical operations like percentages, square roots, and memory recall. The lightweight and compact design make it easy to carry in a backpack, briefcase, or pocket. Powered by both solar energy and a long-lasting battery, it ensures uninterrupted usage. The durable construction guarantees long-term reliability, even with frequent use. Ideal for students solving math problems, business professionals handling finances, and engineers performing quick calculations. The ergonomic layout allows for comfortable use, reducing hand fatigue during extended calculations. The auto power-off feature helps conserve battery life when not in use. Perfect for home, office, school, and travel. QuickCalc is a must-have for anyone needing a dependable and accurate calculator.', 20.00, 30, 16, 2, 'images/products/1742743630_stationary 3.jpg', '2025-03-23 15:27:10'),
('1600004', 'InkFlow Pens', 'The InkFlow Pen is a smooth and reliable writing tool designed for students, professionals, and everyday users. It features a sleek, ergonomic design for a comfortable grip, reducing hand fatigue during long writing sessions. The high-quality ink flows effortlessly, ensuring clean, consistent lines without smudging or skipping. Its durable tip provides precision for both quick notes and detailed writing. The pen is lightweight yet sturdy, making it easy to carry in pockets, bags, or notebooks. Available in multiple ink colors, it suits various writing needs, from exams and office work to creative journaling. The quick-dry ink prevents smears, making it ideal for left-handed users. With a stylish design and professional appearance, the InkFlow Pen is perfect for school, office, and personal use. Whether you\'re signing documents, sketching ideas, or writing your thoughts, this pen delivers a seamless and enjoyable writing experience.', 10.00, 100, 16, 0, 'images/products/1742743676_stationary for.jpg', '2025-03-23 15:27:56'),
('1600005', 'ClipGrip Stapler', 'The ClipGrip Stapler is a sturdy and efficient tool designed for seamless paper binding. Its ergonomic design ensures a comfortable grip, reducing hand strain during extended use. Built with a durable metal mechanism, it provides smooth and jam-free stapling for both home and office needs. The high-capacity staple tray holds more staples, minimizing the need for frequent refills. With a strong binding capability, it securely fastens multiple pages without tearing or misalignment. Its compact and lightweight design makes it easy to carry in bags, drawers, or desks. The non-slip base ensures stability while stapling, preventing slips and misfires. Compatible with standard-size staples, it is ideal for school projects, office documents, and household organization. The easy-to-use loading mechanism allows for quick staple replacement. Available in various colors and finishes, it adds a stylish touch to any workspace. Whether for professional reports, student assignments, or home organization, the ClipGrip Stapler is a reliable companion for everyday use.', 18.00, 50, 16, 1, 'images/products/1742743831_stationary 5.jpg', '2025-03-23 15:30:31'),
('1600006', 'CreativeHue Color Box', 'The CreativeHue Color Box is a perfect companion for artists, students, and hobbyists who love to bring their imagination to life. It features a vibrant selection of high-quality colors, offering smooth blending and rich pigmentation. The sturdy and compact box keeps colors organized and easily accessible, making it ideal for school, home, or travel. Designed for effortless application, these colors work well on paper, sketchbooks, and craft projects. The set includes a variety of shades, allowing endless creativity in painting, shading, and detailing. Safe and non-toxic, it is suitable for all age groups, ensuring a fun and safe coloring experience. Whether for school assignments, professional artwork, or casual doodling, the CreativeHue Color Box adds a splash of inspiration to every creation.', 25.00, 40, 16, 2, 'images/products/1742743888_stationary 6.jpg', '2025-03-23 15:31:28');

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
(15, '1600003', 'Ahmed', 5, 'good', '2025-04-05 18:37:45'),
(16, '1600002', 'ali', 2, 'Excellent', '2025-04-05 18:38:07'),
(17, '1300001', 'Ahmed', 4, 'new', '2025-04-05 18:49:31'),
(18, '1600006', 'khan', 5, 'good', '2025-04-05 18:58:50'),
(19, '1400009', 'latif', 5, 'good', '2025-04-05 18:59:39'),
(20, '1500005', 'Ahmed', 4, 'good', '2025-04-07 18:29:54'),
(21, '1600005', 'latif', 5, 'Excellent', '2025-04-09 05:24:55');

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
(12, 'ahmed3', '$2y$10$dDKNeuM5zz74XoHhLUg3nO9kpcafOReKHwaYjY9rJxa7WcSnrIsDq', 'Muhammad Ahmed', 'ahmed@gmail.com', '+923442681140', 'customer', 'Rawalpindi', '2025-04-03 05:40:21'),
(15, 'ahmed12', '$2y$10$YRTe7hwPacLVGAdPfUwnOuiEfBWfq8oDVbnYaixr3.iZl4EwcSo7.', ' Ahmed Khan', 'm.ahmed.uh72@gmail.com', '+923442681140', 'customer', 'Karachi', '2025-04-06 19:35:43'),
(16, 'kinza', '$2y$10$t2JtB/emCCJZoQUkV6a0COfpgXd7vydhgEJPt4UhUGrl7JxuXFpnG', 'kinza khan', 'kinza@gmail.com', '', 'customer', 'Karachi', '2025-04-10 04:24:03');

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
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `stock_updates`
--
ALTER TABLE `stock_updates`
  MODIFY `stock_update_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
