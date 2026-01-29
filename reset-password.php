<?php
/**
 * Password Reset Script
 * Run this to reset admin password
 */

require_once __DIR__ . '/autoload.php';

// Generate new password hash for "admin123"
$newHash = password_hash('admin123', PASSWORD_DEFAULT);
echo "New password hash: $newHash\n\n";

try {
    $db = \Database::getInstance();
    
    // Update the admin user password
    $sql = "UPDATE users SET password = ? WHERE email = 'admin@ngodonation.org'";
    $stmt = $db->query($sql, [$newHash]);
    
    $affected = $stmt->rowCount();
    echo "Rows affected: $affected\n";
    
    if ($affected > 0) {
        echo "Password updated successfully!\n";
        
        // Verify the update
        $user = \User::findByEmail('admin@ngodonation.org');
        if ($user) {
            $verify = $user->verifyPassword('admin123');
            echo "Verification test: " . ($verify ? "PASSED" : "FAILED") . "\n";
        }
    } else {
        echo "No rows updated. Admin user may not exist.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
