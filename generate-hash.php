<?php
/**
 * Password Hash Generator
 * Generates a proper bcrypt hash for "admin123"
 */

echo "Password: admin123\n";
echo "Hash: " . password_hash('admin123', PASSWORD_DEFAULT) . "\n";
