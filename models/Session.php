<?php
/**
 * Session Model
 * NGO Donor Management System
 */

class Session {
    private static $sessionStarted = false;
    
    public static function start() {
        if (self::$sessionStarted === false) {
            session_start();
            self::$sessionStarted = true;
        }
    }
    
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        self::start();
        unset($_SESSION[$key]);
    }
    
    public static function forget() {
        self::start();
        session_unset();
    }
    
    public static function destroy() {
        self::start();
        session_destroy();
        self::$sessionStarted = false;
    }
    
    public static function regenerate() {
        self::start();
        session_regenerate_id(true);
    }
    
    // User-specific methods
    public static function setUser(User $user) {
        self::set('user_id', $user->id);
        self::set('user_name', $user->name);
        self::set('user_email', $user->email);
        self::set('user_role', $user->role);
    }
    
    public static function getUser() {
        $userId = self::get('user_id');
        if ($userId) {
            return User::find($userId);
        }
        return null;
    }
    
    public static function isLoggedIn() {
        return self::has('user_id');
    }
    
    public static function isAdmin() {
        return self::get('user_role') === 'admin';
    }
    
    public static function logout() {
        self::forget();
        self::destroy();
    }
}
