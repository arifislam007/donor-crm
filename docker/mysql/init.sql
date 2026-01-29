-- NGO Donor Management System Database Initialization
-- This script runs automatically when MySQL container is first created

-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS ngo_donor_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE ngo_donor_system;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    country VARCHAR(100),
    role ENUM('donor', 'admin') DEFAULT 'donor',
    status ENUM('active', 'inactive', 'blocked') DEFAULT 'active',
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create projects table
CREATE TABLE IF NOT EXISTS projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    short_description VARCHAR(500),
    full_description TEXT,
    target_amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    raised_amount DECIMAL(15, 2) DEFAULT 0.00,
    image_path VARCHAR(255),
    status ENUM('draft', 'active', 'completed', 'paused') DEFAULT 'draft',
    start_date DATE DEFAULT NULL,
    end_date DATE DEFAULT NULL,
    created_by BIGINT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create donations table
CREATE TABLE IF NOT EXISTS donations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    donor_id BIGINT UNSIGNED NOT NULL,
    project_id BIGINT UNSIGNED DEFAULT NULL,
    amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    currency VARCHAR(3) DEFAULT 'USD',
    payment_method ENUM('credit_card', 'bank_transfer', 'paypal', 'cash', 'other') DEFAULT 'cash',
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    transaction_id VARCHAR(255) UNIQUE,
    anonymous_donation TINYINT(1) DEFAULT 0,
    message TEXT,
    receipt_sent TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create email_logs table
CREATE TABLE IF NOT EXISTS email_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipient_email VARCHAR(255) NOT NULL,
    recipient_name VARCHAR(255),
    subject VARCHAR(500) NOT NULL,
    email_type ENUM('donation_confirmation', 'donation_receipt', 'project_update', 'admin_notification', 'password_reset', 'welcome') NOT NULL,
    related_id BIGINT UNSIGNED DEFAULT NULL,
    related_type VARCHAR(100),
    status ENUM('sent', 'failed', 'pending') DEFAULT 'pending',
    error_message TEXT,
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create admin user (password: admin123)
INSERT INTO users (name, email, password, role, status) 
VALUES ('Administrator', 'admin@ngodonation.org', '$2y$10$IGnqou9bx6moRE20yTwmN.bOmIEbUJVU2nOwYzbQ34W20V81WY6pO', 'admin', 'active')
ON DUPLICATE KEY UPDATE name=name;

-- Create sample projects
INSERT INTO projects (title, slug, short_description, full_description, target_amount, status) VALUES
('Education for All', 'education-for-all', 'Providing quality education to underprivileged children in rural areas.', 
 'Our Education for All program aims to provide quality education to children in underserved rural communities. We build schools, train teachers, and provide educational materials to ensure every child has access to learning opportunities.\n\nThrough this initiative, we have already helped over 5,000 children receive an education. Your donation will help us expand our reach to more communities.',
 50000.00, 'active'),
('Clean Water Initiative', 'clean-water-initiative', 'Building wells and water purification systems in communities without clean water.',
 'Access to clean water is a basic human right. Our Clean Water Initiative focuses on drilling wells and installing water purification systems in communities that lack access to safe drinking water.\n\nEach project provides clean water to approximately 500 people. Join us in bringing this essential resource to those in need.',
 75000.00, 'active'),
('Healthcare Access Program', 'healthcare-access-program', 'Providing medical care and health education to remote villages.',
 'Many rural communities lack access to basic healthcare. Our Healthcare Access Program sends medical teams to provide checkups, treatments, and health education to remote villages.\n\nYour support helps us cover the cost of medical supplies, transportation, and healthcare worker training.',
 100000.00, 'active')
ON DUPLICATE KEY UPDATE title=title;
