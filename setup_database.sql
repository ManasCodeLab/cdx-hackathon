-- Create and use database
CREATE DATABASE IF NOT EXISTS `if0_34529456_cdx_hack`;
USE `if0_34529456_cdx_hack`;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS `registrations`;
DROP TABLE IF EXISTS `teams`;
DROP TABLE IF EXISTS `coupons`;
DROP TABLE IF EXISTS `contact_messages`;

-- Users/Registrations table
CREATE TABLE `registrations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `github_username` VARCHAR(50) NOT NULL,
    `registration_type` ENUM('solo', 'team') NOT NULL,
    `team_name` VARCHAR(100) NULL,
    `team_members` TEXT NULL,
    `amount_paid` DECIMAL(10,2) NOT NULL,
    `coupon_code` VARCHAR(20) NULL,
    `registration_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending'
);

-- Teams table
CREATE TABLE `teams` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `team_name` VARCHAR(100) NOT NULL,
    `team_members` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('active', 'inactive') DEFAULT 'active'
);

-- Coupons table
CREATE TABLE `coupons` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(20) NOT NULL UNIQUE,
    `discount_percentage` INT NOT NULL,
    `valid_from` TIMESTAMP NOT NULL,
    `valid_until` TIMESTAMP NOT NULL,
    `max_uses` INT DEFAULT NULL,
    `current_uses` INT DEFAULT 0,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `is_active` TINYINT(1) DEFAULT 1
);

-- Contact Messages table
CREATE TABLE `contact_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('new', 'read', 'replied') DEFAULT 'new'
);

-- Insert default coupon (MANAS80)
INSERT INTO `coupons` (
    `code`, 
    `discount_percentage`, 
    `valid_from`, 
    `valid_until`, 
    `max_uses`, 
    `status`, 
    `is_active`
) VALUES (
    'MANAS80', 
    80, 
    CURRENT_TIMESTAMP, 
    '2025-07-05 00:00:00', 
    NULL, 
    'active', 
    1
); 