<?php
/**
 * Application Entry Point
 * NGO Donor Management System
 */

// Define base path
define('APP_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// Load autoloader
require_once APP_PATH . '/autoload.php';

// Start session
Session::start();

// Create router
$router = new Router();

// Define routes

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/projects', 'HomeController@projects');
$router->get('/projects/{slug}', 'HomeController@showProject');
$router->get('/about', 'HomeController@about');
$router->get('/contact', 'HomeController@contact');

// Auth routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');
$router->get('/forgot-password', 'AuthController@showForgotPassword');

// Donor routes (authenticated)
$router->get('/dashboard', 'DonorController@dashboard');
$router->get('/donation-history', 'DonorController@showDonationHistory');
$router->get('/donation-history/{id}', 'DonorController@showDonation');
$router->get('/donate/{projectId}', 'DonorController@showDonate');
$router->post('/donate', 'DonorController@processDonation');
$router->get('/donation/success/{id}', 'DonorController@donationSuccess');
$router->get('/profile', 'DonorController@showProfile');
$router->post('/profile', 'DonorController@updateProfile');

// Admin routes (admin authenticated)
$router->get('/admin', 'AdminController@dashboard');
$router->get('/admin/donors', 'AdminController@donors');
$router->get('/admin/donors/{id}', 'AdminController@showDonor');
$router->post('/admin/donors/{id}', 'AdminController@updateDonor');
$router->get('/admin/donations', 'AdminController@donations');
$router->get('/admin/donations/{id}', 'AdminController@showDonation');
$router->post('/admin/donations/{id}', 'AdminController@updateDonationStatus');
$router->get('/admin/projects', 'AdminController@projects');
$router->get('/admin/projects/create', 'AdminController@showCreateProject');
$router->post('/admin/projects', 'AdminController@createProject');
$router->get('/admin/projects/{id}/edit', 'AdminController@showEditProject');
$router->post('/admin/projects/{id}', 'AdminController@updateProject');
$router->get('/admin/projects/{id}/delete', 'AdminController@deleteProject');
$router->get('/admin/emails', 'AdminController@emails');
$router->get('/admin/emails/send', 'AdminController@showSendEmail');
$router->post('/admin/emails/send', 'AdminController@sendEmail');
$router->get('/admin/settings', 'AdminController@settings');
$router->post('/admin/settings', 'AdminController@updateSettings');
$router->get('/admin/payment-logs', 'AdminController@paymentLogs');

// Payment routes
$router->get('/payment/checkout', 'PaymentController@checkout');
$router->post('/payment/checkout', 'PaymentController@checkout');
$router->post('/payment/process', 'PaymentController@process');
$router->get('/payment/mock/{donationId}', 'PaymentController@mockPayment');
$router->get('/payment/mock/{donationId}/success', 'PaymentController@simulateSuccess');
$router->get('/payment/success', 'PaymentController@success');
$router->get('/payment/fail', 'PaymentController@fail');
$router->get('/payment/cancel', 'PaymentController@cancel');
$router->post('/payment/ipn', 'PaymentController@ipn');

// Dispatch the request
$router->dispatch();
