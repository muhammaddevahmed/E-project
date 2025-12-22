-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2025 at 06:46 PM
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
(50, 'Cosmetic Products', 'images/categories/cat_69480384cfa365.89756226.webp', '2025-12-21 14:26:12'),
(51, 'Stationary Items', 'images/categories/cat_694803dadb9404.42651118.webp', '2025-12-21 14:27:38');

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
(17, 'c-5000001-694832', 34, '5000001', 'processing', '2025-12-24', '2025-12-26', NULL, '2025-12-21 17:48:39', '2025-12-21 17:48:39', 0);

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
(6, 33, 'manager');

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

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `title`, `message`, `related_table`, `related_id`, `is_read`, `created_at`) VALUES
(83, 34, 'Payment Status Updated', 'Your payment for order #c-5000001-694832 is now completed', 'payments', '64', 0, '2025-12-21 17:47:47'),
(84, 34, 'Order Status Updated', 'Your order #c-5000001-694832 status is now accepted and delivery status is processing', 'orders', 'c-5000001-694832', 0, '2025-12-21 17:48:39');

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
  `read_status` tinyint(4) DEFAULT 0,
  `decline_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `delivery_type`, `product_id`, `order_number`, `u_name`, `u_email`, `p_name`, `p_price`, `p_qty`, `date_time`, `status`, `u_id`, `payment_id`, `delivery_status`, `read_status`, `decline_reason`) VALUES
('c-5000001-694832', 'c', '5000001', '69483263', 'Muhammad Ahmed', 'm.ahmed.uh72@gmail.com', 'Matte Velvet Lipstick (Nude Rose)', 1200, 2, '2025-12-21 17:46:11', 'accepted', 34, 64, 'processing', 0, NULL),
('c-5100006-694832', 'c', '5100006', '69483263', 'Muhammad Ahmed', 'm.ahmed.uh72@gmail.com', 'Professional Dual-Tip Alcohol Marker Set (80 Colors)', 1500, 3, '2025-12-21 17:46:11', 'accepted', 34, 64, 'pending', 0, NULL);

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
(64, 'cash_on_delivery', 6900.00, 'completed', '2025-12-21 17:46:11', 'Muhammad', 'Ahmed', 'Pakistan', 'Karachi', 'Karachi', 'sindh', '24674', '+923442681140', 'm.ahmed.uh72@gmail.com', 'good', NULL, NULL, NULL, NULL, NULL, 0);

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
('5000001', 'Matte Velvet Lipstick (Nude Rose)', 'This matte velvet lipstick in a soft nude rose shade delivers a luxurious, long-lasting finish for everyday elegance. Its creamy formula glides on smoothly, providing full coverage with a comfortable, non-drying feel on the lips. The rich pigmentation ensures bold color payoff in one swipe, while the velvet-matte texture offers a sophisticated, modern look. Housed in a sleek black case for a premium feel and easy portability. Ideal for professional settings, daily wear, or evening outings needing subtle yet refined lip color. A must-have essential that combines comfort, durability, and timeless style for any makeup collection.', 1200.00, 13, 50, 1, 'images/products/1766327338_Mate.jpg', '2025-12-21 14:28:58'),
('5000002', 'Long-Wear Even Finish Foundation ', 'This long-wear even finish foundation with SPF 15 offers flawless, natural-looking coverage that lasts all day. Its lightweight, buildable formula blends seamlessly into the skin for a smooth, matte finish without feeling heavy. Enriched with broad-spectrum sun protection, it shields against harmful UV rays while controlling oil and minimizing imperfections. Ideal for daily professional or casual wear, providing comfortable, breathable coverage in various skin tones. Delivers a polished, even complexion with effortless application and long-lasting wear. A premium essential for achieving radiant, protected skin in any routine.', 1800.00, 25, 50, 2, 'images/products/1766327434_Foundation.jpg', '2025-12-21 14:30:34'),
('5000003', 'Compact Pressed Powder with Mirror', 'This compact pressed powder with mirror is a must-have for achieving a flawless, matte finish on the go. Its finely milled formula provides lightweight, buildable coverage to control shine, blur imperfections, and set makeup effortlessly. The included mirror and soft sponge applicator make touch-ups quick and convenient throughout the day. Perfect for all skin types, it delivers a smooth, natural look without caking or creasing. Housed in a sleek, portable black case that fits easily into any bag or purse. An essential beauty staple for maintaining polished, shine-free skin in professional or daily routines.', 999.00, 12, 50, 1, 'images/products/1766327513_powder.jpg', '2025-12-21 14:31:53'),
('5000004', 'Professional Makeup Brush Set (12 Pieces)', 'This professional makeup brush set includes 12 high-quality brushes designed for flawless application and blending. Featuring soft synthetic bristles in various shapes and sizes for face, eyes, and lips—powder, foundation, blush, contour, eyeshadow, and precision detailing. The elegant metallic handles in gold, rose gold, and silver provide a comfortable grip and durable performance. Ideal for beauty enthusiasts, makeup artists, or daily routines needing versatile tools. Delivers smooth, streak-free results with both cream and powder products. A complete, premium collection that elevates any makeup experience with precision and style.', 3200.00, 22, 50, 3, 'images/products/1766327607_brush.jpg', '2025-12-21 14:33:28'),
('5000005', 'Maxi Volume and Curl Mascara', 'This maxi volume and curl mascara transforms lashes with dramatic length, intense volume, and lasting curl in one easy application. Its rich black formula coats each lash evenly without clumping, while the specially designed curved wand lifts and separates for a wide-eyed, defined look. Lightweight and buildable, it provides all-day wear that resists smudging and flaking. Perfect for both natural daytime enhancement or bold evening drama. Housed in a sleek, modern tube for effortless portability and precise control. An essential beauty staple for achieving fuller, more expressive lashes instantly.', 1499.00, 17, 50, 3, 'images/products/1766327826_Mascara.jpg', '2025-12-21 14:37:06'),
('5000006', 'Smudge-Proof Liquid Eyeliner Pen', 'This smudge-proof liquid eyeliner pen delivers sharp, precise lines for bold and defined eye looks that last all day. Its intense black, matte formula glides on smoothly with a flexible felt tip for effortless control—perfect for creating thin lines or dramatic wings. Quick-drying and highly pigmented, it resists smearing, fading, or transferring even in humid conditions. The elegant black and gold design offers a comfortable grip and professional feel. Ideal for daily wear, office makeup, or evening glamour without the need for touch-ups. A reliable essential for achieving flawless, long-wearing eyeliner results every time.', 1300.00, 18, 50, 1, 'images/products/1766327976_EyeLiner.jpg', '2025-12-21 14:39:36'),
('5000007', 'Eyeconic Kajal Eyeliner (Green)', 'This Eyeconic Kajal eyeliner in vibrant green delivers bold, intense color with a smooth, retractable twist-up design for effortless application. Its creamy, highly pigmented formula glides on easily along the waterline or eyelids, providing long-lasting, smudge-proof wear throughout the day. Perfect for creating striking eye looks, subtle definition, or dramatic accents in everyday or party makeup. The convenient no-sharpen mechanism ensures precision without mess or wastage. Ideal for those seeking rich color payoff and comfortable, all-day hold. A versatile, modern essential for enhancing eyes with a pop of refreshing green.', 1800.00, 8, 50, 2, 'images/products/1766328112_Kajal.jpg', '2025-12-21 14:41:52'),
('5000008', 'Luxury 9-Shade Eyeshadow Palette (Pink & Purple Tones)', 'This luxury 9-shade eyeshadow palette features a stunning mix of matte, shimmer, and glitter finishes in soft pink, lavender, and deep purple tones. Highly pigmented and blendable formulas deliver intense color payoff with minimal fallout for effortless day-to-night looks. Includes versatile neutrals for base and transition, alongside bold metallics and glitters for added dimension and glamour. Housed in an elegant compact with a large mirror, perfect for on-the-go application. Ideal for creating romantic, sophisticated, or dramatic eye makeup suitable for any occasion. A premium must-have that combines quality, versatility, and timeless elegance in one beautiful palette.', 5500.00, 30, 50, 8, 'images/products/1766328206_Eyeshadow.jpg', '2025-12-21 14:43:26'),
('5000009', 'Professional Nail Polish Collection Set (20 Shades)', 'This professional nail polish collection set includes 20 vibrant and versatile shades ranging from bold reds and deep purples to soft pastels, nudes, and metallics. Each high-quality formula delivers rich pigmentation, smooth application, and long-lasting shine without chipping. Perfect for creating endless manicure looks for everyday wear, office, parties, or special occasions. The wide variety allows seamless matching with any outfit or mood while maintaining a salon-quality finish at home. Compact bottles make storage easy and travel-friendly. An essential set for beauty enthusiasts seeking variety, durability, and professional results in one complete collection.', 7500.00, 22, 50, 6, 'images/products/1766328451_nail polish.jpg', '2025-12-21 14:47:31'),
('5000010', 'Prada Refine Primer', 'This Prada Refine Primer is a luxurious makeup base designed to perfect and prolong your foundation for a flawless, radiant complexion. Its lightweight, silky texture smooths over pores, fine lines, and imperfections while creating an even canvas for seamless makeup application. The subtle green tint helps neutralize redness and brighten dull skin tones for a natural, refined glow. Infused with high-end skincare benefits, it hydrates and preps the skin without feeling heavy or greasy. Ideal for daily professional use or special occasions requiring long-lasting, polished makeup. A premium essential that elevates any beauty routine with sophisticated Italian luxury.', 2300.00, 18, 50, 7, 'images/products/1766328523_Primer.jpg', '2025-12-21 14:48:43'),
('5100001', 'Premium Blue Ballpoint Pen', 'This premium blue ballpoint pen offers a smooth and reliable writing experience for everyday professional use. It features a vibrant blue barrel with a comfortable rubber grip for extended writing sessions without fatigue. The sturdy metal clip ensures secure attachment to notebooks or pockets, while the retractable tip prevents ink leaks. Ideal for office work, meetings, note-taking, or signing documents with consistent, bold blue lines. Sleek and modern design combines style with functionality for professionals and students alike. A must-have essential that delivers precision and comfort in every stroke.', 230.00, 21, 51, 0, 'images/products/1766328977_pen.jpg', '2025-12-21 14:56:17'),
('5100002', 'Classic Wooden Graphite Pencil', 'This classic wooden graphite pencil is a timeless essential for professionals, students, and artists seeking reliable writing and sketching performance. It features a high-quality hexagonal barrel in traditional yellow with a comfortable grip, a durable pink eraser, and a finely sharpened graphite lead for smooth, precise lines. Perfect for note-taking, drafting, exams, or detailed drawings in office or academic settings. The sturdy construction ensures long-lasting use without frequent sharpening. An everyday must-have that combines simplicity, functionality, and professional elegance for any workspace or study routine.', 20.00, 50, 51, 0, 'images/products/1766329067_pencil.jpg', '2025-12-21 14:57:47'),
('5100003', 'Transparent 30cm Ruler', 'This transparent 30cm ruler is a precise and durable tool essential for professionals, students, and designers. It features clear markings in both centimeters and inches for accurate measurements in drafting, technical drawing, or everyday office tasks. The see-through design allows easy alignment over documents, graphs, or artwork without obstruction. Made from high-quality shatter-resistant plastic for long-lasting use. Ideal for architecture, engineering, schooling, or any workspace requiring straight edges and reliable scaling. A simple yet indispensable addition to any desk or pencil case.\r\n', 89.00, 18, 51, 0, 'images/products/1766329149_scale.jpg', '2025-12-21 14:59:09'),
('5100004', 'Pack of 4 Colorful Pencil Sharpeners', 'This pack of 4 colorful pencil sharpeners is a reliable and practical addition to any workspace or study setup. Each sharpener comes in vibrant colors—blue, green, red, and yellow—with a sturdy plastic body and a sharp steel blade for clean and precise sharpening. The compact, lightweight design makes them easy to carry in pencil cases or store on desks. Suitable for standard-sized pencils, they ensure consistent performance without breakage. An essential, affordable tool for maintaining sharp pencils during writing, drawing, or professional tasks. Keeps your stationery ready for everyday use with minimal effort.', 60.00, 23, 51, 0, 'images/products/1766329251_sharpner.jpg', '2025-12-21 15:00:51'),
('5100005', 'Premium Rubber Eraser', 'This premium rubber eraser delivers clean and smudge-free corrections for everyday writing and drawing tasks. Its high-quality soft rubber material gently lifts graphite or ink marks without damaging paper surfaces. The multi-colored design with a protective sleeve keeps it clean and prevents crumbling during use. Ideal for professionals, students, and artists handling notes, sketches, or documents in office or academic environments. Ensures precise erasing with minimal residue for a polished finish every time. A reliable, long-lasting essential for any stationery collection.', 10.00, 50, 51, 0, 'images/products/1766329330_Eraser.jpg', '2025-12-21 15:02:10'),
('5100006', 'Professional Dual-Tip Alcohol Marker Set (80 Colors)', 'This professional dual-tip alcohol marker set offers 80 vibrant, high-quality colors perfect for designers, artists, and illustrators. Each marker features a broad chisel tip for large area coverage and a fine bullet tip for precise detailing and outlining. The fast-drying, blendable ink ensures smooth transitions, layering, and professional-grade results on various surfaces. Ideal for graphic design, illustration, sketching, coloring books, and creative projects in studio or office settings. Comes organized in a convenient stand for easy color selection and storage. A versatile, long-lasting tool that delivers rich, consistent color output for all skill levels.\r\n', 1500.00, 22, 51, 1, 'images/products/1766329409_marker.jpg', '2025-12-21 15:03:29'),
('5100007', 'Hot Glue Gun (40W)', 'This reliable 40W hot glue gun is an essential tool for DIY enthusiasts, crafters, and professionals tackling repairs or creative projects. It heats up quickly to melt standard glue sticks for strong, fast-bonding adhesion on wood, fabric, plastic, and more. The ergonomic handle and precision nozzle ensure comfortable grip and controlled application with minimal drips. Features a built-in stand for safe placement during use and a trigger feed for easy operation. Ideal for home repairs, school projects, floral arrangements, or handmade crafts. A versatile, durable tool that delivers professional results in any workshop or office setting.', 1200.00, 15, 51, 2, 'images/products/1766329477_glue gun.jpg', '2025-12-21 15:04:37'),
('5100008', 'Executive Leather Notebook with Pen', 'This executive leather notebook with pen is a sophisticated choice for professionals handling meetings, planning, or daily journaling. It features a premium hardcover design with blank, high-quality pages ready for notes, ideas, or sketches. The included sleek black ballpoint pen with a comfortable grip rests perfectly on the open book, ensuring you\'re always prepared to write. Ideal for business executives, managers, or students in higher education seeking a polished and reliable stationery companion. Combines timeless elegance with practical functionality for any office or workspace. A refined essential that enhances productivity and organization in professional settings.', 400.00, 15, 51, 0, 'images/products/1766329637_Notebook.jpg', '2025-12-21 15:07:17'),
('5100009', 'Black Fountain Pen Ink Bottle (30ml)', 'This premium black fountain pen ink bottle provides rich, smooth-flowing ink ideal for calligraphy, professional writing, and daily journaling. The high-quality, pigment-based formula ensures deep black color, quick drying, and resistance to fading over time. Housed in a sturdy glass bottle with a secure screw cap to prevent leaks and spills. Perfect for refilling fountain pens, dip pens, or brush lettering tools in office or creative setups. Delivers consistent performance without clogging nibs for a superior writing experience. An essential supply for writers, artists, and professionals who value elegant and reliable ink.\r\n', 70.00, 30, 51, 0, 'images/products/1766329709_ink.jpg', '2025-12-21 15:08:29'),
('5100010', 'Dollar Pointer Fineliner Pen (Blue)', 'This Dollar Pointer fineliner pen in blue is a reliable choice for precise writing, drawing, and note-taking in professional or academic settings. It features a durable metal-encased tip for fine, consistent lines and smooth ink flow without skipping or bleeding. The ergonomic barrel with grip zones ensures comfortable handling during extended use. Comes with a secure cap to prevent drying and for easy portability in pockets or planners. Ideal for office documentation, journaling, sketching outlines, or detailed work requiring accuracy. A high-quality, everyday pen that delivers sharp, professional results with long-lasting performance.', 30.00, 20, 51, 0, 'images/products/1766329789_pointer.jpg', '2025-12-21 15:09:49');

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
(30, '5100003', 'Muhammad Ahmed', 5, 'The product is good', '2025-12-21 17:45:07');

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
(20, 'Administarator', '$2y$10$lCQ.4Olkv0N.f9GX.CPkcuOPWYPStW.eBH11pAvWgpDj/F20w1Ywq', 'Admin', 'admin@gmail.com', NULL, 'admin', NULL, '2025-04-11 13:31:17'),
(33, 'ali1306', '$2y$10$TyJDwtp31IL8VMSr1coJSurWRFDP0RD/sOmd0Fil5CkQm/n0iAcKG', 'Ali Khan', 'ali@gmail.com', '035478595', 'employee', 'Karachi,Pakistan', '2025-12-21 15:11:19'),
(34, 'muhammadahmed2004', '$2y$10$uANMIy3x5.aMZL8LsqXKteL.e6U3xnN2EYa00BlgFJzuHFneUFsiC', 'Muhammad Ahmed', 'm.ahmed.uh72@gmail.com', '+923442681140', 'customer', 'Karachi', '2025-12-21 15:13:07');

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
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `code_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `promo_code_requests`
--
ALTER TABLE `promo_code_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `stock_update`
--
ALTER TABLE `stock_update`
  MODIFY `update_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
