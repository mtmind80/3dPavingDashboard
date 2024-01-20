/*
 Navicat MySQL Data Transfer

 Source Server         : LocaMYSQL
 Source Server Type    : MySQL
 Source Server Version : 50740 (5.7.40)
 Source Host           : localhost:3306
 Source Schema         : cocobeach

 Target Server Type    : MySQL
 Target Server Version : 50740 (5.7.40)
 File Encoding         : 65001

 Date: 11/01/2024 21:48:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for accepted_documents
-- ----------------------------
DROP TABLE IF EXISTS `accepted_documents`;
CREATE TABLE `accepted_documents`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `extension` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `old_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for actions
-- ----------------------------
DROP TABLE IF EXISTS `actions`;
CREATE TABLE `actions`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `action` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `actions_action_unique`(`action`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for change_orders
-- ----------------------------
DROP TABLE IF EXISTS `change_orders`;
CREATE TABLE `change_orders`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_master_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `new_proposal_id` bigint(20) NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `change_orders_job_master_id_index`(`job_master_id`) USING BTREE,
  INDEX `change_orders_proposal_id_index`(`proposal_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for contact_notes
-- ----------------------------
DROP TABLE IF EXISTS `contact_notes`;
CREATE TABLE `contact_notes`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `contact_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `note` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `contact_notes_contact_id_index`(`contact_id`) USING BTREE,
  INDEX `contact_notes_created_by_index`(`created_by`) USING BTREE,
  CONSTRAINT `contact_notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for contact_types
-- ----------------------------
DROP TABLE IF EXISTS `contact_types`;
CREATE TABLE `contact_types`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for contacts
-- ----------------------------
DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `contact_type_id` bigint(20) UNSIGNED NOT NULL,
  `related_to` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `lead_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `email` varchar(125) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `alt_email` varchar(125) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `alt_phone` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `address1` varchar(110) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address2` varchar(110) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `city` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `postal_code` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `state` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FL',
  `county` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `billing_address1` varchar(225) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `billing_address2` varchar(225) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `billing_city` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `billing_postal_code` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `billing_state` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'FL',
  `contact` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `note` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `old_id` bigint(20) UNSIGNED NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14472 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for contractor_types
-- ----------------------------
DROP TABLE IF EXISTS `contractor_types`;
CREATE TABLE `contractor_types`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for contractors
-- ----------------------------
DROP TABLE IF EXISTS `contractors`;
CREATE TABLE `contractors`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `address_line1` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `address_line2` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `city` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `state` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FL',
  `postal_code` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `email` varchar(125) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `note` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `overhead` double NOT NULL DEFAULT 0,
  `contractor_type_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `old_id` bigint(20) UNSIGNED NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11170 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for counties
-- ----------------------------
DROP TABLE IF EXISTS `counties`;
CREATE TABLE `counties`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `zip` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lat` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lng` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `county` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1035 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for duplocations
-- ----------------------------
DROP TABLE IF EXISTS `duplocations`;
CREATE TABLE `duplocations`  (
  `bad` bigint(20) NULL DEFAULT NULL,
  `gid` bigint(20) NULL DEFAULT NULL,
  `addr` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `postal_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for equipment
-- ----------------------------
DROP TABLE IF EXISTS `equipment`;
CREATE TABLE `equipment`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rate_type` enum('per hour','per day') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rate` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `min_cost` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `do_not_use` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 92 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for labor_rates
-- ----------------------------
DROP TABLE IF EXISTS `labor_rates`;
CREATE TABLE `labor_rates`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rate` double(8, 2) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for lead_notes
-- ----------------------------
DROP TABLE IF EXISTS `lead_notes`;
CREATE TABLE `lead_notes`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `note` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `lead_notes_lead_id_index`(`lead_id`) USING BTREE,
  INDEX `lead_notes_created_by_index`(`created_by`) USING BTREE,
  CONSTRAINT `lead_notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for lead_sources
-- ----------------------------
DROP TABLE IF EXISTS `lead_sources`;
CREATE TABLE `lead_sources`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `lead_sources_name_unique`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for lead_status
-- ----------------------------
DROP TABLE IF EXISTS `lead_status`;
CREATE TABLE `lead_status`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for leads
-- ----------------------------
DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `contact_type_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `email` varchar(125) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `community_name` varchar(225) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `address1` varchar(110) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address2` varchar(110) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `city` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `postal_code` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `state` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FL',
  `county` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `status_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `assigned_to` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `worked_before` tinyint(1) NULL DEFAULT 0,
  `worked_before_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `previous_assigned_to` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `type_of_work_needed` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `lead_source` varchar(125) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `how_related` varchar(125) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `onsite` tinyint(1) NOT NULL DEFAULT 0,
  `best_days` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `leads_created_by_index`(`created_by`) USING BTREE,
  INDEX `leads_status_id_index`(`status_id`) USING BTREE,
  INDEX `leads_assigned_to_index`(`assigned_to`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for letters
-- ----------------------------
DROP TABLE IF EXISTS `letters`;
CREATE TABLE `letters`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `text` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for location_types
-- ----------------------------
DROP TABLE IF EXISTS `location_types`;
CREATE TABLE `location_types`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for locations
-- ----------------------------
DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `address_line1` varchar(110) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address_line2` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `city` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `state` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'Florida',
  `postal_code` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `county` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'Broward',
  `location_type_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `parcel_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `note` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `locations_address_line1_unique`(`address_line1`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8391 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for materials
-- ----------------------------
DROP TABLE IF EXISTS `materials`;
CREATE TABLE `materials`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cost` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `service_category_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for media_types
-- ----------------------------
DROP TABLE IF EXISTS `media_types`;
CREATE TABLE `media_types`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `section` int(11) NOT NULL DEFAULT 1,
  `old_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 771 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for newcontacttypes
-- ----------------------------
DROP TABLE IF EXISTS `newcontacttypes`;
CREATE TABLE `newcontacttypes`  (
  `Contact_Type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `contact_id` bigint(20) NOT NULL,
  `contact_type_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`contact_id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for office_locations
-- ----------------------------
DROP TABLE IF EXISTS `office_locations`;
CREATE TABLE `office_locations`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `manager` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for payments
-- ----------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_type` enum('Deposit','Interim Payment','Additional Payment','Final Payment') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `payment` double(8, 2) UNSIGNED NOT NULL,
  `check_no` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `note` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for permit_notes
-- ----------------------------
DROP TABLE IF EXISTS `permit_notes`;
CREATE TABLE `permit_notes`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `permit_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `note` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `fee` double(8, 2) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `permit_notes_created_by_foreign`(`created_by`) USING BTREE,
  INDEX `permit_notes_permit_id_index`(`permit_id`) USING BTREE,
  CONSTRAINT `permit_notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for permits
-- ----------------------------
DROP TABLE IF EXISTS `permits`;
CREATE TABLE `permits`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `proposal_detail_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `status` enum('Approved','Completed','Not Submitted','Submitted','Under Review','Comments') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Not Submitted',
  `type` enum('Building','Engineering','Miscellaneous') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `number` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `expires_on` date NULL DEFAULT NULL,
  `county` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `city` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `last_updated_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `permits_created_by_foreign`(`created_by`) USING BTREE,
  INDEX `permits_proposal_id_index`(`proposal_id`) USING BTREE,
  CONSTRAINT `permits_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `tokenable_type` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token`) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_actions
-- ----------------------------
DROP TABLE IF EXISTS `proposal_actions`;
CREATE TABLE `proposal_actions`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `action_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `note` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `proposal_actions_action_id_foreign`(`action_id`) USING BTREE,
  INDEX `proposal_actions_created_by_foreign`(`created_by`) USING BTREE,
  CONSTRAINT `proposal_actions_action_id_foreign` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `proposal_actions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 30727 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_detail_additional_costs
-- ----------------------------
DROP TABLE IF EXISTS `proposal_detail_additional_costs`;
CREATE TABLE `proposal_detail_additional_costs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_detail_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NULL DEFAULT 1,
  `amount` double(8, 2) NULL DEFAULT 0.00,
  `type` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Dump Fee',
  `description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `proposal_detail_additional_costs_proposal_detail_id_foreign`(`proposal_detail_id`) USING BTREE,
  CONSTRAINT `proposal_detail_additional_costs_proposal_detail_id_foreign` FOREIGN KEY (`proposal_detail_id`) REFERENCES `proposal_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1327 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_detail_equipment
-- ----------------------------
DROP TABLE IF EXISTS `proposal_detail_equipment`;
CREATE TABLE `proposal_detail_equipment`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_detail_id` bigint(20) UNSIGNED NOT NULL,
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `hours` double UNSIGNED NULL DEFAULT NULL,
  `days` double UNSIGNED NULL DEFAULT NULL,
  `number_of_units` double UNSIGNED NULL DEFAULT NULL,
  `rate_type` enum('per hour','per day') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rate` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `proposal_detail_equipment_proposal_detail_id_foreign`(`proposal_detail_id`) USING BTREE,
  INDEX `proposal_detail_equipment_equipment_id_foreign`(`equipment_id`) USING BTREE,
  CONSTRAINT `proposal_detail_equipment_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `proposal_detail_equipment_proposal_detail_id_foreign` FOREIGN KEY (`proposal_detail_id`) REFERENCES `proposal_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 13050 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_detail_labor
-- ----------------------------
DROP TABLE IF EXISTS `proposal_detail_labor`;
CREATE TABLE `proposal_detail_labor`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_detail_id` bigint(20) UNSIGNED NOT NULL,
  `labor_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rate_per_hour` double(8, 2) UNSIGNED NOT NULL,
  `number` int(11) NOT NULL DEFAULT 0,
  `days` double UNSIGNED NOT NULL DEFAULT 0,
  `hours` double UNSIGNED NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `proposal_detail_labor_proposal_detail_id_foreign`(`proposal_detail_id`) USING BTREE,
  CONSTRAINT `proposal_detail_labor_proposal_detail_id_foreign` FOREIGN KEY (`proposal_detail_id`) REFERENCES `proposal_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 12861 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_detail_statuses
-- ----------------------------
DROP TABLE IF EXISTS `proposal_detail_statuses`;
CREATE TABLE `proposal_detail_statuses`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_detail_striping_services
-- ----------------------------
DROP TABLE IF EXISTS `proposal_detail_striping_services`;
CREATE TABLE `proposal_detail_striping_services`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_detail_id` bigint(20) UNSIGNED NOT NULL,
  `striping_service_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cost` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `proposal_detail_striping_services_proposal_detail_id_foreign`(`proposal_detail_id`) USING BTREE,
  CONSTRAINT `proposal_detail_striping_services_proposal_detail_id_foreign` FOREIGN KEY (`proposal_detail_id`) REFERENCES `proposal_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 432795 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_detail_subcontractors
-- ----------------------------
DROP TABLE IF EXISTS `proposal_detail_subcontractors`;
CREATE TABLE `proposal_detail_subcontractors`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_detail_id` bigint(20) UNSIGNED NOT NULL,
  `contractor_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `cost` double NULL DEFAULT 0,
  `overhead` double UNSIGNED NULL DEFAULT 0,
  `havebid` tinyint(1) NOT NULL DEFAULT 0,
  `accepted` tinyint(1) NULL DEFAULT 0,
  `attached_bid` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `proposal_detail_subcontractors_proposal_detail_id_foreign`(`proposal_detail_id`) USING BTREE,
  CONSTRAINT `proposal_detail_subcontractors_proposal_detail_id_foreign` FOREIGN KEY (`proposal_detail_id`) REFERENCES `proposal_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9682 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_detail_vehicles
-- ----------------------------
DROP TABLE IF EXISTS `proposal_detail_vehicles`;
CREATE TABLE `proposal_detail_vehicles`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_detail_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_name` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `number_of_vehicles` double UNSIGNED NULL DEFAULT NULL,
  `days` double UNSIGNED NOT NULL DEFAULT 0,
  `hours` double UNSIGNED NULL DEFAULT 0,
  `rate_per_hour` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `created_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `proposal_detail_vehicles_proposal_detail_id_foreign`(`proposal_detail_id`) USING BTREE,
  CONSTRAINT `proposal_detail_vehicles_proposal_detail_id_foreign` FOREIGN KEY (`proposal_detail_id`) REFERENCES `proposal_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 14132 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_details
-- ----------------------------
DROP TABLE IF EXISTS `proposal_details`;
CREATE TABLE `proposal_details`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `change_order_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `services_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `contractor_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `contractor_bid` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `status_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `location_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `fieldmanager_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `second_fieldmanager_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `cost` decimal(14, 2) UNSIGNED NULL DEFAULT 0.00,
  `material_cost` decimal(13, 2) UNSIGNED NULL DEFAULT 0.00,
  `service_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `service_desc` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `bill_after` tinyint(1) NOT NULL DEFAULT 0,
  `dsort` int(10) UNSIGNED NULL DEFAULT 0,
  `linear_feet` double UNSIGNED NULL DEFAULT 0,
  `cost_per_linear_feet` double UNSIGNED NULL DEFAULT 0,
  `square_feet` double UNSIGNED NULL DEFAULT 0,
  `square_yards` double UNSIGNED NULL DEFAULT 0,
  `cubic_yards` double UNSIGNED NULL DEFAULT 0,
  `tons` double UNSIGNED NULL DEFAULT 0,
  `loads` double UNSIGNED NULL DEFAULT 0,
  `locations` double UNSIGNED NULL DEFAULT 0,
  `depth` double UNSIGNED NULL DEFAULT 0,
  `profit` double NULL DEFAULT 0,
  `days` double UNSIGNED NULL DEFAULT 0,
  `cost_per_day` double UNSIGNED NULL DEFAULT 0,
  `break_even` double NULL DEFAULT 0,
  `primer` double UNSIGNED NULL DEFAULT 0,
  `yield` double UNSIGNED NULL DEFAULT 0,
  `fast_set` double UNSIGNED NULL DEFAULT 0,
  `additive` double UNSIGNED NULL DEFAULT 0,
  `sealer` double UNSIGNED NULL DEFAULT 0,
  `sand` double UNSIGNED NULL DEFAULT 0,
  `phases` double UNSIGNED NULL DEFAULT 0,
  `overhead` double NULL DEFAULT 0,
  `catchbasins` int(10) UNSIGNED NULL DEFAULT 0,
  `proposal_text` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `alt_desc` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `proposal_note` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `proposal_field_note` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `scheduled_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `completed_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `completed_date` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `start_date` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `end_date` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `proposal_details_proposal_id_foreign`(`proposal_id`) USING BTREE,
  CONSTRAINT `proposal_details_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 35444 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_materials
-- ----------------------------
DROP TABLE IF EXISTS `proposal_materials`;
CREATE TABLE `proposal_materials`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `service_category_id` bigint(20) UNSIGNED NULL DEFAULT 0,
  `cost` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 196891 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_media
-- ----------------------------
DROP TABLE IF EXISTS `proposal_media`;
CREATE TABLE `proposal_media`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `proposal_detail_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `media_type_id` int(11) NOT NULL DEFAULT 1,
  `description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `file_type` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `file_path` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `original_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `file_ext` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `file_size` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `IsImage` tinyint(1) NOT NULL DEFAULT 0,
  `image_height` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `image_width` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `admin_only` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2501 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_notes
-- ----------------------------
DROP TABLE IF EXISTS `proposal_notes`;
CREATE TABLE `proposal_notes`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `reminder` tinyint(1) NULL DEFAULT 0,
  `remindersent` tinyint(1) NULL DEFAULT 0,
  `reminder_date` date NULL DEFAULT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `note` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `proposal_notes_proposal_id_index`(`proposal_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_statuses
-- ----------------------------
DROP TABLE IF EXISTS `proposal_statuses`;
CREATE TABLE `proposal_statuses`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposal_terms
-- ----------------------------
DROP TABLE IF EXISTS `proposal_terms`;
CREATE TABLE `proposal_terms`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `text` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `section` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for proposals
-- ----------------------------
DROP TABLE IF EXISTS `proposals`;
CREATE TABLE `proposals`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_master_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `name` varchar(225) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `proposal_statuses_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `rejected_reason` varchar(225) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `proposal_date` datetime NOT NULL DEFAULT '2023-12-30 00:55:25',
  `sale_date` datetime NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `last_updated_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `contact_id` bigint(20) UNSIGNED NOT NULL,
  `customer_staff_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `salesmanager_id` bigint(20) UNSIGNED NULL DEFAULT 10,
  `salesperson_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `location_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `lead_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `changeorder_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `discount` int(10) UNSIGNED NULL DEFAULT NULL,
  `progressive_billing` tinyint(1) NULL DEFAULT 0,
  `mot_required` tinyint(1) NULL DEFAULT 0,
  `permit_required` tinyint(1) NULL DEFAULT 0,
  `nto_required` tinyint(1) NULL DEFAULT 0,
  `on_alert` tinyint(1) NULL DEFAULT 0,
  `alert_reason` varchar(225) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `old_id` bigint(20) UNSIGNED NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `proposals_location_id_foreign`(`location_id`) USING BTREE,
  INDEX `proposals_job_master_id_index`(`job_master_id`) USING BTREE,
  INDEX `proposals_contact_id_index`(`contact_id`) USING BTREE,
  INDEX `proposals_salesmanager_id_index`(`salesmanager_id`) USING BTREE,
  INDEX `proposals_salesperson_id_index`(`salesperson_id`) USING BTREE,
  CONSTRAINT `proposals_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `proposals_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 11831 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for service_categories
-- ----------------------------
DROP TABLE IF EXISTS `service_categories`;
CREATE TABLE `service_categories`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for service_schedule
-- ----------------------------
DROP TABLE IF EXISTS `service_schedule`;
CREATE TABLE `service_schedule`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_detail_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `note` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for services
-- ----------------------------
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `service_category_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `service_template` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `service_text_en` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `service_text_es` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `percent_overhead` int(10) UNSIGNED NOT NULL DEFAULT 30,
  `min_cost` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `payload` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE INDEX `sessions_id_unique`(`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for states
-- ----------------------------
DROP TABLE IF EXISTS `states`;
CREATE TABLE `states`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `state` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for striping_costs
-- ----------------------------
DROP TABLE IF EXISTS `striping_costs`;
CREATE TABLE `striping_costs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `striping_service_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cost` double(8, 2) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 188 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for striping_services
-- ----------------------------
DROP TABLE IF EXISTS `striping_services`;
CREATE TABLE `striping_services`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dsort` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for terms
-- ----------------------------
DROP TABLE IF EXISTS `terms`;
CREATE TABLE `terms`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `text` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `section` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for terms_of_service
-- ----------------------------
DROP TABLE IF EXISTS `terms_of_service`;
CREATE TABLE `terms_of_service`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `text` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `section` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lname` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `rate_per_hour` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `role_id` int(11) NOT NULL DEFAULT 99,
  `sales_goals` int(11) NULL DEFAULT 5000000,
  `old_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 40023 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for vehicle_logs
-- ----------------------------
DROP TABLE IF EXISTS `vehicle_logs`;
CREATE TABLE `vehicle_logs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicles_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `type` enum('Repair','Maintenance','Purchase','Oil Change','Insurance','Accident') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `vehicle_logs_created_by_foreign`(`created_by`) USING BTREE,
  CONSTRAINT `vehicle_logs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for vehicle_types
-- ----------------------------
DROP TABLE IF EXISTS `vehicle_types`;
CREATE TABLE `vehicle_types`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `rate` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for vehicles
-- ----------------------------
DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE `vehicles`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_types_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `office_location_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for vendor_types
-- ----------------------------
DROP TABLE IF EXISTS `vendor_types`;
CREATE TABLE `vendor_types`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for vendors
-- ----------------------------
DROP TABLE IF EXISTS `vendors`;
CREATE TABLE `vendors`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `address_line1` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `address_line2` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `city` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `state` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FL',
  `postal_code` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `email` varchar(125) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `note` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `vendor_type_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `old_id` bigint(20) UNSIGNED NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2450 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for web_config_bak
-- ----------------------------
DROP TABLE IF EXISTS `web_config_bak`;
CREATE TABLE `web_config_bak`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `web_config_bak_key_unique`(`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for web_configs
-- ----------------------------
DROP TABLE IF EXISTS `web_configs`;
CREATE TABLE `web_configs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `web_configs_key_unique`(`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for workorder_equipment
-- ----------------------------
DROP TABLE IF EXISTS `workorder_equipment`;
CREATE TABLE `workorder_equipment`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `proposal_detail_id` bigint(20) UNSIGNED NOT NULL,
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `report_date` date NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `hours` double UNSIGNED NULL DEFAULT NULL,
  `number_of_units` double UNSIGNED NULL DEFAULT NULL,
  `rate_type` enum('per hour','per day') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rate` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for workorder_materials
-- ----------------------------
DROP TABLE IF EXISTS `workorder_materials`;
CREATE TABLE `workorder_materials`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `proposal_detail_id` bigint(20) UNSIGNED NOT NULL,
  `report_date` date NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `note` varchar(220) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `quantity` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `cost` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for workorder_subcontractors
-- ----------------------------
DROP TABLE IF EXISTS `workorder_subcontractors`;
CREATE TABLE `workorder_subcontractors`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `proposal_detail_id` bigint(20) UNSIGNED NOT NULL,
  `contractor_id` bigint(20) UNSIGNED NOT NULL,
  `report_date` date NOT NULL,
  `created_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `cost` double(8, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `workorder_subcontractors_contractor_id_foreign`(`contractor_id`) USING BTREE,
  CONSTRAINT `workorder_subcontractors_contractor_id_foreign` FOREIGN KEY (`contractor_id`) REFERENCES `contractors` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for workorder_timesheets
-- ----------------------------
DROP TABLE IF EXISTS `workorder_timesheets`;
CREATE TABLE `workorder_timesheets`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `proposal_detail_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `report_date` date NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `actual_hours` double UNSIGNED NOT NULL DEFAULT 0,
  `rate` double(8, 2) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `workorder_timesheets_proposal_id_foreign`(`proposal_id`) USING BTREE,
  INDEX `workorder_timesheets_proposal_detail_id_foreign`(`proposal_detail_id`) USING BTREE,
  INDEX `workorder_timesheets_employee_id_foreign`(`employee_id`) USING BTREE,
  INDEX `workorder_timesheets_created_by_foreign`(`created_by`) USING BTREE,
  CONSTRAINT `workorder_timesheets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `workorder_timesheets_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `workorder_timesheets_proposal_detail_id_foreign` FOREIGN KEY (`proposal_detail_id`) REFERENCES `proposal_details` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `workorder_timesheets_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Table structure for workorder_vehicles
-- ----------------------------
DROP TABLE IF EXISTS `workorder_vehicles`;
CREATE TABLE `workorder_vehicles`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `proposal_detail_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `report_date` date NOT NULL,
  `vehicle_name` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `number_of_vehicles` double UNSIGNED NULL DEFAULT NULL,
  `note` varchar(220) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
