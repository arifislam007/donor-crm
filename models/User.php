<?php
/**
 * User Model
 * NGO Donor Management System
 */

class User extends Model {
    protected static $table = 'users';
    
    protected $attributes = [
        'id' => null,
        'name' => '',
        'email' => '',
        'password' => '',
        'phone' => '',
        'address' => '',
        'country' => '',
        'role' => 'donor',
        'status' => 'active',
        'email_verified_at' => null,
        'remember_token' => '',
        'created_at' => null,
        'updated_at' => null,
    ];
    
    public function setPassword($password) {
        $this->attributes['password'] = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }
    
    public function verifyPassword($password) {
        return password_verify($password, $this->attributes['password']);
    }
    
    public function isAdmin() {
        return $this->attributes['role'] === 'admin';
    }
    
    public function isActive() {
        return $this->attributes['status'] === 'active';
    }
    
    public function donations() {
        return Donation::where(['donor_id' => $this->id]);
    }
    
    public function totalDonated() {
        $db = Database::getInstance();
        $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM donations WHERE donor_id = ? AND payment_status = 'completed'";
        $result = $db->query($sql, [$this->id])->fetch();
        return (float) $result['total'];
    }
    
    public function getFullName() {
        return $this->attributes['name'];
    }
    
    public static function findByEmail($email) {
        return self::findBy('email', $email);
    }
    
    public static function createUser($data) {
        $user = new User($data);
        if (isset($data['password'])) {
            $user->setPassword($data['password']);
        }
        $user->save();
        return $user;
    }
}
