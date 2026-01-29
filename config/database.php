<?php
/**
 * Database Configuration
 * NGO Donor Management System
 * Supports MySQL and PostgreSQL
 */

return [
    'driver' => 'pgsql',
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', '5432'),
    'database' => env('DB_DATABASE', 'ngo_donor_system'),
    'username' => env('DB_USERNAME', 'postgres'),
    'password' => env('DB_PASSWORD', 'postgres'),
    'charset' => 'utf8',
    'schema' => 'public',
    'prefix' => '',
];
