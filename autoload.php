<?php
/**
 * Simple Autoloader
 * NGO Donor Management System
 */

// Load .env file if it exists
if (file_exists(APP_PATH . '/.env')) {
    $lines = file(APP_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

spl_autoload_register(function ($class) {
    // Handle classes without namespace (global namespace)
    if (strpos($class, '\\') === false) {
        // Check models directory
        $modelFile = APP_PATH . '/models/' . $class . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return true;
        }
        
        // Check core directory
        $coreFile = APP_PATH . '/core/' . $class . '.php';
        if (file_exists($coreFile)) {
            require_once $coreFile;
            return true;
        }
        
        // Check services directory
        $serviceFile = APP_PATH . '/services/' . $class . '.php';
        if (file_exists($serviceFile)) {
            require_once $serviceFile;
            return true;
        }
        
        return;
    }
    
    // Handle namespaced classes
    $prefix = 'controllers\\';
    $baseDir = APP_PATH . '/controllers/';
    
    // Check if the class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) === 0) {
        // Get the relative class name
        $relativeClass = substr($class, $len);
        
        // Replace namespace separator with directory separator
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        
        // If the file exists, require it
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    // Handle models namespace
    if (strncmp('models\\', $class, 7) === 0) {
        $className = substr($class, 7);
        $file = APP_PATH . '/models/' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    // Handle core namespace
    if (strncmp('core\\', $class, 5) === 0) {
        $className = substr($class, 5);
        $file = APP_PATH . '/core/' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    // Handle services namespace
    if (strncmp('services\\', $class, 9) === 0) {
        $className = substr($class, 9);
        $file = APP_PATH . '/services/' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
});

// Load helpers
require_once APP_PATH . '/helpers.php';
