<?php
/**
 * Helper Functions
 * NGO Donor Management System
 */

/**
 * Get environment variable or return default
 */
function env($key, $default = null) {
    $value = isset($_ENV[$key]) ? $_ENV[$key] : (isset($_SERVER[$key]) ? $_SERVER[$key] : $default);
    
    // Handle boolean env values
    if ($value === 'true') return true;
    if ($value === 'false') return false;
    
    return $value;
}

/**
 * Format currency (BDT - Bangladeshi Taka)
 */
function formatCurrency($amount, $currency = 'BDT') {
    if ($currency === 'BDT') {
        return '৳' . number_format($amount, 2);
    }
    return '$' . number_format($amount, 2);
}

/**
 * Convert USD to BDT
 */
function convertToBDT($usdAmount, $exchangeRate = 110) {
    return $usdAmount * $exchangeRate;
}

/**
 * Convert BDT to USD
 */
function convertToUSD($bdtAmount, $exchangeRate = 110) {
    return $bdtAmount / $exchangeRate;
}

/**
 * Format date
 */
function formatDate($date, $format = 'M j, Y') {
    if (empty($date)) {
        return '-';
    }
    return date($format, strtotime($date));
}

/**
 * Truncate text
 */
function truncate($text, $length = 100, $append = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $append;
}

/**
 * Generate random string
 */
function randomString($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Sanitize output
 */
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect with flash message
 */
function redirect($url, $message = null, $type = 'success') {
    if ($message) {
        Session::set($type, $message);
    }
    header("Location: $url");
    exit;
}

/**
 * dd() - Dump and Die
 */
function dd(...$vars) {
    foreach ($vars as $var) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
    exit;
}
