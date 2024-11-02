-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 31, 2024 at 08:44 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cocobeach`
--

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
CREATE TABLE IF NOT EXISTS `materials` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb3_unicode_ci NOT NULL,
  `cost` double(8,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `service_category_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `name`, `cost`, `service_category_id`) VALUES
(1, 'Sealer (per gal)', 4.40, 8),
(2, 'Sand (lbs.)', 0.15, 8),
(3, 'Additive (per gal)', 18.00, 8),
(4, 'Oil Spot Primer (per gal)', 18.00, 8),
(5, 'Fastset (per gal)', 16.00, 8),
(6, 'Base Rock (Broward & Dade) per ton', 32.00, 7),
(7, 'Base Rock (Palm Beach) per ton', 42.00, 7),
(8, 'Asphalt per ton', 101.00, 1),
(9, 'Concrete (Curb Mix) per cubic yard', 230.00, 2),
(10, 'Concrete (Drum Mix) per cubic yard', 230.00, 2),
(11, 'Mesh - Fiber per sq ft', 8.50, 8),
(12, 'Mesh - Wire per sq ft', 7.50, 7),
(13, 'Rebar Per linear Foot', 8.50, 7),
(14, 'Tack (per gallon)', 9.00, 8),
(15, 'Paving Asphalt', 96.00, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
