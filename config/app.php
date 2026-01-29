<?php
/**
 * Application Configuration
 * NGO Donor Management System
 */

return [
    'name' => env('APP_NAME', 'NGO Donor System'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'UTC',
    'locale' => 'en',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
];
