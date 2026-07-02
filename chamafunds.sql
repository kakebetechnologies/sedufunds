-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2026 at 09:13 AM
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
-- Database: `chamafunds`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `target_type` varchar(50) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `target_name` varchar(255) DEFAULT NULL,
  `changes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`changes`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`log_id`, `admin_id`, `action`, `target_type`, `target_id`, `target_name`, `changes`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'Approved Withdrawal', 'withdrawal', 1, 'Family Medical Fund - UGX 500,000', '{\"status\": \"pending\", \"new_status\": \"approved\"}', '192.168.1.100', 'Chrome/120.0.0.0', '2026-06-23 14:26:32'),
(2, 1, 'Approved Withdrawal', 'withdrawal', 2, 'Family Medical Fund - UGX 250,000', '{\"status\": \"pending\", \"new_status\": \"approved\"}', '192.168.1.100', 'Chrome/120.0.0.0', '2026-06-23 14:26:32'),
(3, 1, 'Logged In', 'user', 1, 'Admin Kakebe', '{\"ip\": \"192.168.1.100\"}', '192.168.1.100', 'Chrome/120.0.0.0', '2026-06-23 14:26:32'),
(4, 1, 'Featured Campaign', 'campaign', 1, 'Family Medical Fund for Baby Grace', '{\"is_featured\": \"false\", \"new_is_featured\": \"true\"}', '192.168.1.100', 'Chrome/120.0.0.0', '2026-06-23 14:26:32');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `notif_id` int(11) NOT NULL,
  `type` enum('new_campaign','new_donation','new_user','withdrawal','flag','system') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT '',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `campaign_id` int(11) NOT NULL,
  `campaigner_id` int(11) NOT NULL,
  `display_name` varchar(120) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `goal_amount` decimal(15,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'UGX',
  `raised_amount` decimal(15,2) DEFAULT 0.00,
  `contributor_count` int(11) DEFAULT 0,
  `image_url` varchar(500) DEFAULT NULL,
  `mobile_money_number` varchar(20) NOT NULL,
  `mobile_money_network` varchar(50) NOT NULL,
  `status` enum('draft','active','paused','suspended','completed','flagged') DEFAULT 'draft',
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_date` timestamp NULL DEFAULT NULL,
  `country` varchar(50) NOT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `view_count` int(11) DEFAULT 0,
  `share_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `multiple_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`multiple_images`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`campaign_id`, `campaigner_id`, `display_name`, `title`, `slug`, `description`, `category`, `goal_amount`, `currency`, `raised_amount`, `contributor_count`, `image_url`, `mobile_money_number`, `mobile_money_network`, `status`, `start_date`, `end_date`, `country`, `is_featured`, `view_count`, `share_count`, `created_at`, `updated_at`, `multiple_images`) VALUES
(1, 2, NULL, 'Family Medical Fund for Baby Grace', 'family-medical-fund-baby-grace', 'Our daughter Grace was born with a condition that requires immediate surgery. The total cost is UGX 5,000,000. We have raised some funds from family but we need help from our wider community. Every contribution brings us closer to saving our baby girl.', 'Medical', 5000000.00, 'UGX', 400000.00, 3, 'https://images.unsplash.com/photo-1582750433449-648ed127bb54?w=800', '256712345678', 'MTN Mobile Money', 'active', '2026-06-23 14:26:32', NULL, 'Uganda', 1, 1246, 89, '2026-06-23 14:26:32', '2026-06-23 23:47:47', NULL),
(2, 3, NULL, 'Clean Water Borehole for Kibera Community', 'clean-water-borehole-kibera', 'Access to clean water is a daily challenge for the Kibera community. We are raising funds to drill a borehole that will serve over 500 families. This project will transform lives and provide sustainable access to safe drinking water.', 'Community', 8000000.00, 'KES', 1000000.00, 3, 'https://images.unsplash.com/photo-1548839140-29a749e1cf4d?w=800', '254712345678', 'Airtel Money', 'active', '2026-06-23 14:26:32', NULL, 'Kenya', 1, 2301, 156, '2026-06-23 14:26:32', '2026-06-28 17:40:12', NULL),
(3, 2, NULL, 'Education Scholarship for 10 Bright Students', 'education-scholarship-10-students', 'We are raising funds to provide full scholarships for 10 academically gifted students from low-income families. Each scholarship covers tuition, books, and supplies for one academic year. Help us invest in the future leaders of Uganda.', 'Education', 3000000.00, 'UGX', 450000.00, 3, 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800', '256789012345', 'MTN Mobile Money', 'active', '2026-06-23 14:26:32', NULL, 'Uganda', 0, 886, 43, '2026-06-23 14:26:32', '2026-06-23 20:49:36', NULL),
(4, 3, NULL, 'Emergency Flood Relief for Kisumu Families', 'emergency-flood-relief-kisumu', 'Heavy rains have caused devastating floods in Kisumu, displacing over 200 families. We are raising emergency funds to provide food, shelter, and essential supplies to those affected. Every donation makes a difference in helping these families rebuild.', 'Emergency', 10000000.00, 'KES', 1750000.00, 3, 'https://images.unsplash.com/photo-1534274988757-a28bf1a57c17?w=800', '254789012345', 'Safaricom M-PESA', 'active', '2026-06-23 14:26:32', NULL, 'Kenya', 0, 3457, 201, '2026-06-23 14:26:32', '2026-06-23 22:35:00', NULL),
(5, 2, NULL, 'Sedricks School Fees', 'sedricks-school-fees-52ccd', 'Sedrick is raising funds to help him complete his examinations and continue the academic program he has started at the university. He is seeking support to enable him to successfully achieve this goal. Any contribution, big or small, will make a meaningful difference and is deeply appreciated. Thank you for your generosity and support.', 'Education', 1500000.00, 'UGX', 11000.00, 2, '/chama/uploads/campaigns/camp_6a3ac78952e22.jpg', '0777676206', 'MTN Mobile Money', 'active', '2026-06-23 17:51:05', '2026-07-08 21:00:00', 'Uganda', 0, 40, 0, '2026-06-23 17:51:05', '2026-07-01 07:11:09', NULL),
(6, 5, NULL, 'Johnson Opio Heart Surgery', 'johnson-opio-heart-surgery-f0173', 'This fundraising campaign is dedicated to supporting a child in need of urgent heart surgery. The child requires specialized medical care and treatment to improve their health and give them a chance at a better future. We are seeking your support to help cover the medical expenses and ensure the surgery can be carried out successfully. Any contribution, no matter the amount, will make a life-changing difference and is deeply appreciated. Thank you for your kindness, generosity, and support.', 'Medical', 5000000.00, 'UGX', 1010000.00, 2, '/chama/uploads/campaigns/camp_6_1782240285.png', '0777676206', 'MTN Mobile Money', 'active', '2026-06-23 18:26:14', '2026-06-29 21:00:00', 'Uganda', 0, 13, 0, '2026-06-23 18:26:14', '2026-07-01 07:11:13', NULL),
(7, 6, NULL, 'family support for sedu', 'family-support-for-sedu-8025d', 'kqhdkshkhSD ds\r\n JHFKA;HDF', 'Family', 5000000.00, 'UGX', 0.00, 0, '/chama/uploads/campaigns/camp_6_1782257625_0.png', '0777676206', 'MTN Mobile Money', 'active', '2026-06-23 23:33:45', '2026-06-25 21:00:00', 'Uganda', 0, 9, 0, '2026-06-23 23:33:45', '2026-06-23 23:52:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `campaign_categories`
--

CREATE TABLE `campaign_categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `icon` varchar(10) DEFAULT '­ƒôî',
  `color_class` varchar(30) DEFAULT 'badge-other',
  `description` varchar(255) DEFAULT '',
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaign_categories`
--

INSERT INTO `campaign_categories` (`category_id`, `name`, `slug`, `icon`, `color_class`, `description`, `is_active`, `sort_order`, `created_at`) VALUES
(1, 'Medical', 'medical', '­ƒÅÑ', 'badge-medical', '', 1, 1, '2026-06-23 19:02:57'),
(2, 'Education', 'education', '­ƒôÜ', 'badge-education', '', 1, 2, '2026-06-23 19:02:57'),
(3, 'Community', 'community', '­ƒÆº', 'badge-community', '', 1, 3, '2026-06-23 19:02:57'),
(4, 'Family', 'family', '­ƒæ¿ÔÇì­ƒæ', 'badge-family', '', 1, 4, '2026-06-23 19:02:57'),
(5, 'Business', 'business', '­ƒÆ╝', 'badge-business', '', 1, 5, '2026-06-23 19:02:57'),
(6, 'Emergency', 'emergency', '­ƒåÿ', 'badge-emergency', '', 1, 6, '2026-06-23 19:02:57'),
(7, 'Marriage', 'marriage', '­ƒÆì', 'badge-family', '', 1, 7, '2026-06-23 19:02:57'),
(8, 'Funeral', 'funeral', '­ƒòè´©Å', 'badge-other', '', 1, 8, '2026-06-23 19:02:57'),
(9, 'Agriculture', 'agriculture', '­ƒî¥', 'badge-community', '', 1, 9, '2026-06-23 19:02:57'),
(10, 'Religion', 'religion', 'Ôø¬', 'badge-other', '', 1, 10, '2026-06-23 19:02:57'),
(11, 'Sports', 'sports', 'ÔÜ¢', 'badge-other', '', 1, 11, '2026-06-23 19:02:57'),
(12, 'Other', 'other', '­ƒôî', 'badge-other', '', 1, 12, '2026-06-23 19:02:57');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_images`
--

CREATE TABLE `campaign_images` (
  `image_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `is_cover` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaign_images`
--

INSERT INTO `campaign_images` (`image_id`, `campaign_id`, `image_url`, `is_cover`, `sort_order`, `caption`, `created_at`) VALUES
(1, 1, 'https://images.unsplash.com/photo-1582750433449-648ed127bb54?w=800', 1, 0, NULL, '2026-06-23 23:23:25'),
(2, 2, 'https://images.unsplash.com/photo-1548839140-29a749e1cf4d?w=800', 1, 0, NULL, '2026-06-23 23:23:25'),
(3, 3, 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800', 1, 0, NULL, '2026-06-23 23:23:25'),
(4, 4, 'https://images.unsplash.com/photo-1534274988757-a28bf1a57c17?w=800', 1, 0, NULL, '2026-06-23 23:23:25'),
(5, 5, '/chama/uploads/campaigns/camp_6a3ac78952e22.jpg', 1, 0, NULL, '2026-06-23 23:23:25'),
(6, 6, '/chama/uploads/campaigns/camp_6_1782240285.png', 1, 0, NULL, '2026-06-23 23:23:25'),
(8, 7, '/chama/uploads/campaigns/camp_6_1782257625_0.png', 1, 0, NULL, '2026-06-23 23:33:45'),
(9, 7, '/chama/uploads/campaigns/camp_6_1782257625_1.jpg', 0, 1, NULL, '2026-06-23 23:33:45'),
(10, 7, '/chama/uploads/campaigns/camp_6_1782257625_2.png', 0, 2, NULL, '2026-06-23 23:33:45');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(100) NOT NULL,
  `country_code` varchar(2) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `currency_symbol` varchar(5) NOT NULL,
  `payment_partner` varchar(50) DEFAULT NULL,
  `api_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`api_config`)),
  `is_active` tinyint(1) DEFAULT 1,
  `campaign_count` int(11) DEFAULT 0,
  `user_count` int(11) DEFAULT 0,
  `fee_percentage` decimal(5,2) DEFAULT 7.50,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_id`, `country_name`, `country_code`, `currency_code`, `currency_symbol`, `payment_partner`, `api_config`, `is_active`, `campaign_count`, `user_count`, `fee_percentage`, `created_at`) VALUES
(1, 'Uganda', 'UG', 'UGX', 'UGX', 'PawaPay', NULL, 1, 2, 3, 7.50, '2026-06-23 14:26:32'),
(2, 'Kenya', 'KE', 'KES', 'KSh', 'PawaPay', NULL, 1, 2, 1, 7.50, '2026-06-23 14:26:32'),
(3, 'Rwanda', 'RW', 'RWF', 'RWF', 'PawaPay', NULL, 1, 0, 0, 7.50, '2026-06-23 14:26:32'),
(4, 'Nigeria', 'NG', 'NGN', '₦', 'PawaPay', NULL, 1, 0, 0, 7.50, '2026-06-23 14:26:32');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `donor_name` varchar(100) DEFAULT NULL,
  `donor_email` varchar(100) DEFAULT NULL,
  `donor_phone` varchar(20) NOT NULL,
  `is_anonymous` tinyint(1) DEFAULT 0,
  `amount` decimal(15,2) NOT NULL,
  `fee_percentage` decimal(5,2) NOT NULL DEFAULT 7.50,
  `fee_amount` decimal(15,2) GENERATED ALWAYS AS (`amount` * (`fee_percentage` / 100)) STORED,
  `net_amount` decimal(15,2) GENERATED ALWAYS AS (`amount` - `amount` * (`fee_percentage` / 100)) STORED,
  `tip_amount` decimal(15,2) DEFAULT 0.00,
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `transaction_reference` varchar(100) DEFAULT NULL,
  `mobile_money_network` varchar(50) NOT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pesapal_tracking_id` varchar(100) DEFAULT NULL,
  `currency` varchar(3) DEFAULT 'UGX'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`donation_id`, `campaign_id`, `donor_id`, `donor_name`, `donor_email`, `donor_phone`, `is_anonymous`, `amount`, `fee_percentage`, `tip_amount`, `status`, `transaction_reference`, `mobile_money_network`, `payment_date`, `created_at`, `pesapal_tracking_id`, `currency`) VALUES
(1, 1, 4, 'Grace Achieng', 'grace.achieng@gmail.com', '256789012345', 0, 250000.00, 7.50, 0.00, 'completed', 'MMT-UG-2026-001', 'MTN Mobile Money', '2026-01-15 07:30:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(2, 1, NULL, 'Peter Okello', 'peter.okello@gmail.com', '256701234567', 0, 100000.00, 7.50, 5000.00, 'completed', 'MMT-UG-2026-002', 'MTN Mobile Money', '2026-01-16 11:20:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(3, 1, NULL, 'Anonymous Donor', NULL, '256702345678', 1, 50000.00, 7.50, 0.00, 'completed', 'MMT-UG-2026-003', 'Airtel Money', '2026-01-17 06:45:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(4, 1, NULL, 'James Ssemakula', 'james.ssemakula@gmail.com', '256703456789', 0, 75000.00, 7.50, 2000.00, 'pending', 'MMT-UG-2026-004', 'MTN Mobile Money', '2026-01-18 13:10:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(5, 2, NULL, 'David Mwangi', 'david.mwangi@gmail.com', '254723456789', 0, 500000.00, 7.50, 0.00, 'completed', 'MMT-KE-2026-005', 'Safaricom M-PESA', '2026-01-14 08:00:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(6, 2, NULL, 'Alice Wanjiku', 'alice.wanjiku@gmail.com', '254734567890', 0, 300000.00, 7.50, 10000.00, 'completed', 'MMT-KE-2026-006', 'Airtel Money', '2026-01-15 05:30:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(7, 2, NULL, 'Anonymous Donor', NULL, '254745678901', 1, 200000.00, 7.50, 0.00, 'completed', 'MMT-KE-2026-007', 'Safaricom M-PESA', '2026-01-16 10:15:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(8, 2, NULL, 'Samuel Kiprop', 'samuel.kiprop@gmail.com', '254756789012', 0, 150000.00, 7.50, 0.00, 'pending', 'MMT-KE-2026-008', 'Airtel Money', '2026-01-17 07:45:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(9, 3, 4, 'Grace Achieng', 'grace.achieng@gmail.com', '256789012345', 0, 200000.00, 7.50, 5000.00, 'completed', 'MMT-UG-2026-009', 'MTN Mobile Money', '2026-01-12 06:00:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(10, 3, NULL, 'Martha Nambooze', 'martha.nambooze@gmail.com', '256707890123', 0, 150000.00, 7.50, 0.00, 'completed', 'MMT-UG-2026-010', 'Airtel Money', '2026-01-13 08:30:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(11, 3, NULL, 'Anonymous Donor', NULL, '256708901234', 1, 100000.00, 7.50, 0.00, 'completed', 'MMT-UG-2026-011', 'MTN Mobile Money', '2026-01-14 12:20:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(12, 3, NULL, 'Robert Kato', 'robert.kato@gmail.com', '256709012345', 0, 80000.00, 7.50, 3000.00, 'pending', 'MMT-UG-2026-012', 'MTN Mobile Money', '2026-01-15 09:00:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(13, 4, NULL, 'Faith Akinyi', 'faith.akinyi@gmail.com', '254767890123', 0, 1000000.00, 7.50, 20000.00, 'completed', 'MMT-KE-2026-013', 'Safaricom M-PESA', '2026-01-10 04:00:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(14, 4, NULL, 'Anonymous Donor', NULL, '254778901234', 1, 500000.00, 7.50, 0.00, 'completed', 'MMT-KE-2026-014', 'Airtel Money', '2026-01-11 06:30:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(15, 4, NULL, 'Joseph Odhiambo', 'joseph.odhiambo@gmail.com', '254789012345', 0, 250000.00, 7.50, 0.00, 'completed', 'MMT-KE-2026-015', 'Safaricom M-PESA', '2026-01-12 11:45:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(16, 4, NULL, 'Dorothy Atieno', 'dorothy.atieno@gmail.com', '254790123456', 0, 300000.00, 7.50, 5000.00, 'pending', 'MMT-KE-2026-016', 'Airtel Money', '2026-01-13 05:15:00', '2026-06-23 14:26:32', NULL, 'UGX'),
(17, 5, 1, 'Sedu Otolo', 'ot.sedrick@gmail.com', '0777676206', 1, 10000.00, 7.50, 0.00, 'completed', 'MMT-SED-2026-6A3AC81201483', 'MTN Mobile Money', '2026-06-23 17:53:22', '2026-06-23 17:53:22', NULL, 'UGX'),
(18, 6, NULL, 'Sedu Otolo', 'ot.sedrick@gmail.com', '0777676206', 0, 10000.00, 7.50, 0.00, 'completed', 'MMT-JOH-2026-6A3AD2EF29E6E', 'MTN Mobile Money', '2026-06-23 18:39:43', '2026-06-23 18:39:43', NULL, 'UGX'),
(19, 6, NULL, 'Sedu Otolo', 'ot.sedrick@gmail.com', '0777676206', 0, 1000000.00, 7.50, 0.00, 'completed', 'MMT-JOH-2026-6A3AD30102B75', 'MTN Mobile Money', '2026-06-23 18:40:01', '2026-06-23 18:40:01', NULL, 'UGX'),
(20, 5, NULL, 'Sedu Otolo', 'ot.sedrick@gmail.com', '079123453', 0, 1000.00, 7.50, 0.00, 'completed', 'MMT-SED-2026-6A44B1F83EE9D', 'MTN Mobile Money', '2026-07-01 06:21:44', '2026-07-01 06:21:44', NULL, 'UGX'),
(21, 5, NULL, 'Sedu Otolo', 'ot.sedrick@gmail.com', '0791213453', 0, 1000.00, 7.50, 0.00, 'pending', 'CF_6a44b9b9e3afc_1782888889', 'MTN Mobile Money', NULL, '2026-07-01 06:54:49', NULL, 'UGX'),
(22, 5, NULL, 'Anonymous', 'ot.sedrick@gmail.com', '0791213453', 1, 1000.00, 7.50, 0.00, 'pending', 'CF_6a44b9d443130_1782888916', 'MTN Mobile Money', NULL, '2026-07-01 06:55:16', NULL, 'UGX'),
(23, 6, NULL, 'Sedu Otolo', 'ot.sedrick@gmail.com', '0777676206', 0, 10000.00, 7.50, 0.00, 'pending', 'CF_6a44bd9993558_1782889881', 'MTN Mobile Money', NULL, '2026-07-01 07:11:21', NULL, 'UGX');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `sent_via_email` tinyint(1) DEFAULT 0,
  `sent_via_sms` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `type`, `title`, `message`, `link`, `is_read`, `sent_via_email`, `sent_via_sms`, `created_at`) VALUES
(1, 2, 'donation', 'New Donation Received!', 'Grace Achieng just donated UGX 250,000 to your campaign \"Family Medical Fund for Baby Grace\"', '/campaign/1', 0, 1, 0, '2026-01-15 07:31:00'),
(2, 2, 'withdrawal', 'Withdrawal Approved', 'Your withdrawal of UGX 500,000 for \"Family Medical Fund for Baby Grace\" has been approved and is being processed.', '/withdrawals', 0, 1, 1, '2026-01-20 07:05:00'),
(3, 3, 'donation', 'New Donation Received!', 'Anonymous Donor just donated KES 200,000 to your campaign \"Clean Water Borehole for Kibera Community\"', '/campaign/2', 0, 1, 0, '2026-01-16 10:16:00'),
(4, 4, 'donation', 'Donation Confirmed', 'Your donation of UGX 250,000 to \"Family Medical Fund for Baby Grace\" was successful. Thank you!', '/campaign/1', 1, 1, 1, '2026-01-15 07:32:00'),
(5, 2, 'donation', 'New Donation Received!', 'Anonymous Donor just donated 10,000 to your campaign \"Sedricks School Fees\"', '/chama/campaign-detail.php?id=5', 0, 0, 0, '2026-06-23 17:53:22'),
(6, 5, 'donation', 'New Donation Received!', 'Sedu Otolo just donated 10,000 to your campaign \"Johnson Opio Heart Surgery\"', '/chama/campaign-detail.php?id=6', 0, 0, 0, '2026-06-23 18:39:43'),
(7, 5, 'donation', 'New Donation Received!', 'Sedu Otolo just donated 1,000,000 to your campaign \"Johnson Opio Heart Surgery\"', '/chama/campaign-detail.php?id=6', 0, 0, 0, '2026-06-23 18:40:01'),
(8, 2, 'donation', 'New Donation Received!', 'Sedu Otolo just donated 1,000 to your campaign \"Sedricks School Fees\"', '/chama/campaign-detail.php?id=5', 0, 0, 0, '2026-07-01 06:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `platform_settings`
--

CREATE TABLE `platform_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) DEFAULT 'general',
  `is_encrypted` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `platform_settings`
--

INSERT INTO `platform_settings` (`setting_id`, `setting_key`, `setting_value`, `setting_group`, `is_encrypted`, `updated_at`) VALUES
(1, 'platform_name', 'ChamaFunds', 'general', 0, '2026-06-23 14:26:32'),
(2, 'platform_tagline', 'Pool Money Together for What Matters Most', 'general', 0, '2026-06-23 14:26:32'),
(3, 'platform_email', 'support@chamafunds.com', 'general', 0, '2026-06-23 14:26:32'),
(4, 'platform_phone', '+256700000001', 'general', 0, '2026-06-23 14:26:32'),
(5, 'platform_fee', '7.5', 'fees', 0, '2026-06-23 14:26:32'),
(6, 'fee_applied_at', 'withdrawal', 'fees', 0, '2026-06-23 14:26:32'),
(7, 'maintenance_mode', 'false', 'security', 0, '2026-06-23 14:26:32'),
(8, 'max_donation_amount', '1000000', 'payments', 0, '2026-06-23 14:26:32'),
(9, 'min_donation_amount', '1000', 'payments', 0, '2026-06-23 14:26:32'),
(10, 'session_timeout', '60', 'security', 0, '2026-06-23 14:26:32'),
(11, 'default_country', 'Uganda', 'general', 0, '2026-06-23 14:26:32'),
(12, 'two_factor_enabled', 'false', 'security', 0, '2026-06-23 14:26:32'),
(13, 'email_notifications_enabled', 'true', 'notifications', 0, '2026-06-23 14:26:32'),
(14, 'sms_notifications_enabled', 'true', 'notifications', 0, '2026-06-23 14:26:32'),
(15, 'default_currency', 'UGX', 'general', 0, '2026-06-23 14:26:32'),
(16, 'pesapal_ipn_id', '', 'payment', 0, '2026-07-01 06:44:09');

-- --------------------------------------------------------

--
-- Table structure for table `pledges`
--

CREATE TABLE `pledges` (
  `pledge_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `pledger_name` varchar(100) NOT NULL,
  `pledger_phone` varchar(20) DEFAULT NULL,
  `pledger_email` varchar(100) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('pending','paid','reminded','cancelled') DEFAULT 'pending',
  `reminder_sent_count` int(11) DEFAULT 0,
  `last_reminder_sent` timestamp NULL DEFAULT NULL,
  `paid_donation_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pledges`
--

INSERT INTO `pledges` (`pledge_id`, `campaign_id`, `pledger_name`, `pledger_phone`, `pledger_email`, `amount`, `status`, `reminder_sent_count`, `last_reminder_sent`, `paid_donation_id`, `created_at`) VALUES
(1, 1, 'Mary Adong', '256798012345', 'mary.adong@gmail.com', 50000.00, 'pending', 1, '2026-01-18 05:00:00', NULL, '2026-01-10 09:00:00'),
(2, 1, 'John Opio', '256799123456', 'john.opio@gmail.com', 75000.00, 'reminded', 2, '2026-01-19 06:00:00', NULL, '2026-01-09 11:30:00'),
(3, 2, 'Esther Kamau', '254780123456', 'esther.kamau@gmail.com', 100000.00, 'pending', 0, NULL, NULL, '2026-01-12 07:00:00'),
(4, 3, 'David Balidawa', '256701234567', 'david.balidawa@gmail.com', 50000.00, 'paid', 1, '2026-01-14 05:00:00', NULL, '2026-01-05 13:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','campaigner','donor') NOT NULL DEFAULT 'donor',
  `country` varchar(50) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_verified` tinyint(1) DEFAULT 0,
  `two_factor_enabled` tinyint(1) DEFAULT 0,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `phone`, `password_hash`, `role`, `country`, `avatar_url`, `is_active`, `is_verified`, `two_factor_enabled`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Admin Kakebe', 'admin@chamafunds.com', '256700000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Uganda', 'https://i.pravatar.cc/150?img=1', 1, 1, 0, '2026-06-28 17:40:26', '2026-06-23 14:26:32', '2026-06-28 17:40:26'),
(2, 'Sarah Nakato', 'sarah.nakato@gmail.com', '256712345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'campaigner', 'Uganda', 'https://i.pravatar.cc/150?img=2', 1, 1, 0, '2026-06-23 17:43:07', '2026-06-23 14:26:32', '2026-06-23 17:43:07'),
(3, 'John Mwangi', 'john.mwangi@gmail.com', '254712345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'campaigner', 'Kenya', 'https://i.pravatar.cc/150?img=3', 1, 1, 0, NULL, '2026-06-23 14:26:32', '2026-06-23 14:26:32'),
(4, 'Grace Achieng', 'grace.achieng@gmail.com', '256789012345', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', 'Uganda', 'https://i.pravatar.cc/150?img=4', 1, 1, 0, NULL, '2026-06-23 14:26:32', '2026-06-23 14:26:32'),
(5, 'Sedu Otolo', 'ot.sedrick@gmail.com', '0777676206', '$2y$10$dQtNDar9Uzt9xNjdX7KpsO0IlUkbO1D6R.1VEhVKAb2.hi/qOYfEa', 'campaigner', 'Uganda', NULL, 1, 0, 0, '2026-06-28 17:42:24', '2026-06-23 18:25:09', '2026-06-28 17:42:24'),
(6, 'Lamex Odongo', 'lamex1999@gmail.com', '256777562909', '$2y$10$ql6tUH74Grn67dcNBNiaZOXMgDCVAs7sCvlemr5CegPK4OOYrgyuq', 'campaigner', 'Uganda', NULL, 1, 0, 0, NULL, '2026-06-23 22:53:15', '2026-06-23 22:53:15');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `withdrawal_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `campaigner_id` int(11) NOT NULL,
  `gross_amount` decimal(15,2) NOT NULL,
  `fee_percentage` decimal(5,2) NOT NULL DEFAULT 7.50,
  `fee_amount` decimal(15,2) GENERATED ALWAYS AS (`gross_amount` * (`fee_percentage` / 100)) STORED,
  `net_amount` decimal(15,2) GENERATED ALWAYS AS (`gross_amount` - `gross_amount` * (`fee_percentage` / 100)) STORED,
  `mobile_money_number` varchar(20) NOT NULL,
  `mobile_money_network` varchar(50) NOT NULL,
  `status` enum('pending','approved','completed','rejected') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `transaction_reference` varchar(100) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`withdrawal_id`, `campaign_id`, `campaigner_id`, `gross_amount`, `fee_percentage`, `mobile_money_number`, `mobile_money_network`, `status`, `approved_by`, `approved_at`, `rejection_reason`, `admin_notes`, `transaction_reference`, `completed_at`, `requested_at`, `updated_at`) VALUES
(1, 1, 2, 500000.00, 7.50, '256712345678', 'MTN Mobile Money', 'completed', 1, '2026-01-20 07:00:00', NULL, 'Approved - first withdrawal', 'MMT-WD-UG-2026-001', '2026-01-20 09:00:00', '2026-01-19 05:00:00', '2026-06-23 14:26:32'),
(2, 1, 2, 250000.00, 7.50, '256712345678', 'MTN Mobile Money', 'approved', 1, '2026-01-22 06:00:00', NULL, 'Approved - second withdrawal', NULL, NULL, '2026-01-21 08:00:00', '2026-06-23 14:26:32'),
(3, 2, 3, 800000.00, 7.50, '254712345678', 'Safaricom M-PESA', 'pending', NULL, NULL, NULL, 'Awaiting approval', NULL, NULL, '2026-01-22 11:30:00', '2026-06-23 14:26:32'),
(4, 3, 2, 300000.00, 7.50, '256789012345', 'MTN Mobile Money', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-23 06:00:00', '2026-06-23 14:26:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_logs_admin` (`admin_id`),
  ADD KEY `idx_logs_target` (`target_type`,`target_id`),
  ADD KEY `idx_logs_created` (`created_at`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`notif_id`),
  ADD KEY `idx_admin_notif_read` (`is_read`),
  ADD KEY `idx_admin_notif_type` (`type`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`campaign_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `campaigner_id` (`campaigner_id`),
  ADD KEY `idx_campaigns_slug` (`slug`),
  ADD KEY `idx_campaigns_status` (`status`),
  ADD KEY `idx_campaigns_category` (`category`),
  ADD KEY `idx_campaigns_country` (`country`),
  ADD KEY `idx_campaigns_end_date` (`end_date`);

--
-- Indexes for table `campaign_categories`
--
ALTER TABLE `campaign_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `campaign_images`
--
ALTER TABLE `campaign_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `idx_campaign_images` (`campaign_id`),
  ADD KEY `idx_cover` (`campaign_id`,`is_cover`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`),
  ADD UNIQUE KEY `country_name` (`country_name`),
  ADD UNIQUE KEY `country_code` (`country_code`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `idx_donations_campaign` (`campaign_id`),
  ADD KEY `idx_donations_donor` (`donor_id`),
  ADD KEY `idx_donations_status` (`status`),
  ADD KEY `idx_donations_created` (`created_at`),
  ADD KEY `idx_pesapal_tracking` (`pesapal_tracking_id`),
  ADD KEY `idx_donations_pesapal_tracking` (`pesapal_tracking_id`),
  ADD KEY `idx_donations_pesapal` (`pesapal_tracking_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `idx_notifications_user` (`user_id`),
  ADD KEY `idx_notifications_read` (`is_read`);

--
-- Indexes for table `platform_settings`
--
ALTER TABLE `platform_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `pledges`
--
ALTER TABLE `pledges`
  ADD PRIMARY KEY (`pledge_id`),
  ADD KEY `paid_donation_id` (`paid_donation_id`),
  ADD KEY `idx_pledges_campaign` (`campaign_id`),
  ADD KEY `idx_pledges_status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_phone` (`phone`),
  ADD KEY `idx_users_role` (`role`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`withdrawal_id`),
  ADD KEY `campaigner_id` (`campaigner_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_withdrawals_campaign` (`campaign_id`),
  ADD KEY `idx_withdrawals_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `campaign_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `campaign_categories`
--
ALTER TABLE `campaign_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `campaign_images`
--
ALTER TABLE `campaign_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `platform_settings`
--
ALTER TABLE `platform_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pledges`
--
ALTER TABLE `pledges`
  MODIFY `pledge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `withdrawal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `campaigns_ibfk_1` FOREIGN KEY (`campaigner_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `campaign_images`
--
ALTER TABLE `campaign_images`
  ADD CONSTRAINT `campaign_images_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`campaign_id`) ON DELETE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`campaign_id`),
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`donor_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `pledges`
--
ALTER TABLE `pledges`
  ADD CONSTRAINT `pledges_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`campaign_id`),
  ADD CONSTRAINT `pledges_ibfk_2` FOREIGN KEY (`paid_donation_id`) REFERENCES `donations` (`donation_id`);

--
-- Constraints for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD CONSTRAINT `withdrawals_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`campaign_id`),
  ADD CONSTRAINT `withdrawals_ibfk_2` FOREIGN KEY (`campaigner_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `withdrawals_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
