<?php
/**
 * 404 Error View
 * NGO Donor Management System
 */

$title = 'Page Not Found - Sombhabona';
?>

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="text-center">
        <div class="mb-8">
            <i class="fas fa-exclamation-triangle text-9xl text-yellow-500"></i>
        </div>
        <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
        <p class="text-2xl text-gray-600 mb-8">Page Not Found</p>
        <p class="text-gray-500 mb-8">The page you're looking for doesn't exist or has been moved.</p>
        <a href="/" class="inline-block bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 transition">
            <i class="fas fa-home mr-2"></i> Go Home
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/layouts/main.php';
