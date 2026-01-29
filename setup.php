<?php
/**
 * Database Setup Script
 * NGO Donor Management System
 * Run this script to create the database tables (PostgreSQL)
 */

// Define application path
define('APP_PATH', __DIR__);

require_once __DIR__ . '/autoload.php';

echo "Setting up NGO Donor Management System database (PostgreSQL)...\n\n";

try {
    $config = require_once __DIR__ . '/config/database.php';
    
    // Connect without database first
    $dsn = sprintf(
        'pgsql:host=%s;port=%s',
        $config['host'],
        $config['port']
    );
    
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    // Create database if not exists
    $dbName = $config['database'];
    $pdo->exec("CREATE DATABASE \"$dbName\"");
    echo "✓ Database '$dbName' created\n";
    
} catch (PDOException $e) {
    // Database might already exist, try to continue
    if (strpos($e->getMessage(), 'already exists') === false) {
        echo "Note: " . $e->getMessage() . "\n";
    }
}

try {
    $config = require_once __DIR__ . '/config/database.php';
    
    // Connect to the database
    $dsn = sprintf(
        'pgsql:host=%s;port=%s;dbname=%s',
        $config['host'],
        $config['port'],
        $config['database']
    );
    
    $db = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    echo "✓ Connected to database\n";
    
    // Create users table
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20) DEFAULT '',
            address TEXT,
            country VARCHAR(100) DEFAULT '',
            role VARCHAR(50) DEFAULT 'donor',
            status VARCHAR(50) DEFAULT 'active',
            email_verified_at TIMESTAMP NULL,
            remember_token VARCHAR(100) DEFAULT '',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Users table created\n";
    
    // Create projects table
    $db->exec("
        CREATE TABLE IF NOT EXISTS projects (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            short_description VARCHAR(500) DEFAULT '',
            full_description TEXT,
            target_amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
            raised_amount DECIMAL(15, 2) DEFAULT 0.00,
            image_path VARCHAR(255) DEFAULT '',
            status VARCHAR(50) DEFAULT 'draft',
            start_date DATE DEFAULT NULL,
            end_date DATE DEFAULT NULL,
            created_by INTEGER DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "✓ Projects table created\n";
    
    // Create donations table
    $db->exec("
        CREATE TABLE IF NOT EXISTS donations (
            id SERIAL PRIMARY KEY,
            donor_id INTEGER NOT NULL,
            project_id INTEGER DEFAULT NULL,
            amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
            currency VARCHAR(3) DEFAULT 'USD',
            payment_method VARCHAR(50) DEFAULT 'cash',
            payment_status VARCHAR(50) DEFAULT 'pending',
            transaction_id VARCHAR(255) UNIQUE DEFAULT '',
            anonymous_donation BOOLEAN DEFAULT FALSE,
            message TEXT,
            receipt_sent BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
        )
    ");
    echo "✓ Donations table created\n";
    
    // Create email_logs table
    $db->exec("
        CREATE TABLE IF NOT EXISTS email_logs (
            id SERIAL PRIMARY KEY,
            recipient_email VARCHAR(255) NOT NULL,
            recipient_name VARCHAR(255) DEFAULT '',
            subject VARCHAR(500) NOT NULL,
            email_type VARCHAR(100) NOT NULL,
            related_id INTEGER DEFAULT NULL,
            related_type VARCHAR(100) DEFAULT '',
            status VARCHAR(50) DEFAULT 'pending',
            error_message TEXT,
            sent_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Email logs table created\n";
    
    // Create payment_logs table
    $db->exec("
        CREATE TABLE IF NOT EXISTS payment_logs (
            id SERIAL PRIMARY KEY,
            donation_id INTEGER NOT NULL,
            gateway VARCHAR(50) NOT NULL,
            is_sandbox BOOLEAN DEFAULT TRUE,
            transaction_id VARCHAR(255) DEFAULT '',
            request_data TEXT,
            response_data TEXT,
            status VARCHAR(50) DEFAULT 'pending',
            error_message TEXT,
            ipn_data TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (donation_id) REFERENCES donations(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Payment logs table created\n";
    
    // Create settings table
    $db->exec("
        CREATE TABLE IF NOT EXISTS settings (
            id SERIAL PRIMARY KEY,
            key VARCHAR(100) UNIQUE NOT NULL,
            value TEXT DEFAULT '',
            type VARCHAR(50) DEFAULT 'string',
            description VARCHAR(255) DEFAULT '',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Settings table created\n";
    
    // Insert default payment gateway settings
    $settings = [
        ['key' => 'payment_mode', 'value' => 'sandbox', 'type' => 'string', 'description' => 'Payment mode: sandbox or live'],
        ['key' => 'sslcommerz_store_id', 'value' => '', 'type' => 'string', 'description' => 'SSLCommerz Store ID'],
        ['key' => 'sslcommerz_store_password', 'value' => '', 'type' => 'string', 'description' => 'SSLCommerz Store Password'],
        ['key' => 'sslcommerz_sandbox', 'value' => 'true', 'type' => 'boolean', 'description' => 'SSLCommerz Sandbox Mode'],
        ['key' => 'nagad_merchant_id', 'value' => '', 'type' => 'string', 'description' => 'Nagad Merchant ID'],
        ['key' => 'nagad_merchant_number', 'value' => '', 'type' => 'string', 'description' => 'Nagad Merchant Number'],
        ['key' => 'nagad_sandbox', 'value' => 'true', 'type' => 'boolean', 'description' => 'Nagad Sandbox Mode'],
        ['key' => 'bkash_app_key', 'value' => '', 'type' => 'string', 'description' => 'Bkash App Key'],
        ['key' => 'bkash_app_secret', 'value' => '', 'type' => 'string', 'description' => 'Bkash App Secret'],
        ['key' => 'bkash_username', 'value' => '', 'type' => 'string', 'description' => 'Bkash Username'],
        ['key' => 'bkash_password', 'value' => '', 'type' => 'string', 'description' => 'Bkash Password'],
        ['key' => 'bkash_sandbox', 'value' => 'true', 'type' => 'boolean', 'description' => 'Bkash Sandbox Mode'],
        ['key' => 'rocket_merchant_id', 'value' => '', 'type' => 'string', 'description' => 'Rocket Merchant ID'],
        ['key' => 'rocket_merchant_number', 'value' => '', 'type' => 'string', 'description' => 'Rocket Merchant Number'],
        ['key' => 'rocket_sandbox', 'value' => 'true', 'type' => 'boolean', 'description' => 'Rocket Sandbox Mode'],
    ];
    
    foreach ($settings as $setting) {
        $stmt = $db->prepare("
            INSERT INTO settings (key, value, type, description) 
            VALUES (:key, :value, :type, :description)
            ON CONFLICT (key) DO UPDATE SET value = :value
        ");
        $stmt->execute($setting);
    }
    echo "✓ Default payment settings created\n";
    
    // Create admin user
    $adminExists = $db->query("SELECT COUNT(*) FROM users WHERE email = 'admin@ngodonation.org'")->fetchColumn();
    
    if ($adminExists == 0) {
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $db->exec("
            INSERT INTO users (name, email, password, role, status) 
            VALUES ('Administrator', 'admin@ngodonation.org', '$password', 'admin', 'active')
        ");
        echo "✓ Admin user created (email: admin@ngodonation.org, password: admin123)\n";
    } else {
        echo "✓ Admin user already exists\n";
    }
    
    // Create sample projects
    $projectCount = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    
    if ($projectCount == 0) {
        $projects = [
            [
                'title' => 'Education for All',
                'slug' => 'education-for-all',
                'short_description' => 'Providing quality education to underprivileged children in rural areas.',
                'full_description' => "Our Education for All program aims to provide quality education to children in underserved rural communities. We build schools, train teachers, and provide educational materials to ensure every child has access to learning opportunities.\n\nThrough this initiative, we've already helped over 5,000 children receive an education. Your donation will help us expand our reach to more communities.",
                'target_amount' => 5000000,
                'status' => 'active',
            ],
            [
                'title' => 'Clean Water Initiative',
                'slug' => 'clean-water-initiative',
                'short_description' => 'Building wells and water purification systems in communities without clean water.',
                'full_description' => "Access to clean water is a basic human right. Our Clean Water Initiative focuses on drilling wells and installing water purification systems in communities that lack access to safe drinking water.\n\nEach project provides clean water to approximately 500 people. Join us in bringing this essential resource to those in need.",
                'target_amount' => 7500000,
                'status' => 'active',
            ],
            [
                'title' => 'Healthcare Access Program',
                'slug' => 'healthcare-access-program',
                'short_description' => 'Providing medical care and health education to remote villages.',
                'full_description' => "Many rural communities lack access to basic healthcare. Our Healthcare Access Program sends medical teams to provide checkups, treatments, and health education to remote villages.\n\nYour support helps us cover the cost of medical supplies, transportation, and healthcare worker training.",
                'target_amount' => 10000000,
                'status' => 'active',
            ],
        ];
        
        foreach ($projects as $project) {
            $stmt = $db->prepare("
                INSERT INTO projects (title, slug, short_description, full_description, target_amount, status) 
                VALUES (:title, :slug, :short_description, :full_description, :target_amount, :status)
            ");
            $stmt->execute($project);
        }
        echo "✓ Sample projects created\n";
    } else {
        echo "✓ Projects already exist\n";
    }
    
    echo "\n========================================\n";
    echo "Database setup completed successfully!\n";
    echo "========================================\n";
    echo "\nAdmin Login:\n";
    echo "  Email: admin@ngodonation.org\n";
    echo "  Password: admin123\n";
    echo "\n";
    
} catch (PDOException $e) {
    echo "✗ Database setup failed: " . $e->getMessage() . "\n";
    exit(1);
}
