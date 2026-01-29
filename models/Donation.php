<?php
/**
 * Donation Model
 * NGO Donor Management System
 */

class Donation extends Model {
    protected static $table = 'donations';
    
    protected $attributes = [
        'id' => null,
        'donor_id' => null,
        'project_id' => null,
        'amount' => 0,
        'currency' => 'USD',
        'payment_method' => '',
        'payment_status' => 'pending',
        'transaction_id' => '',
        'anonymous_donation' => 0,
        'message' => '',
        'receipt_sent' => 0,
        'created_at' => null,
        'updated_at' => null,
    ];
    
    public function getDonor() {
        return User::find($this->donor_id);
    }
    
    public function getProject() {
        if ($this->project_id) {
            return Project::find($this->project_id);
        }
        return null;
    }
    
    public function isCompleted() {
        return $this->payment_status === 'completed';
    }
    
    public function isPending() {
        return $this->payment_status === 'pending';
    }
    
    public function isFailed() {
        return $this->payment_status === 'failed';
    }
    
    public function getStatusLabel() {
        $labels = [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
        ];
        return $labels[$this->payment_status] ?? $this->payment_status;
    }
    
    public function markAsCompleted($transactionId = null) {
        $this->attributes['payment_status'] = 'completed';
        if ($transactionId) {
            $this->attributes['transaction_id'] = $transactionId;
        }
        
        // Update project's raised amount
        if ($this->project_id) {
            $project = Project::find($this->project_id);
            if ($project) {
                $project->raised_amount += $this->amount;
                $project->save();
            }
        }
        
        return $this->save();
    }
    
    public function markAsFailed() {
        $this->attributes['payment_status'] = 'failed';
        return $this->save();
    }
    
    public function generateTransactionId() {
        return 'DON-' . strtoupper(uniqid() . bin2hex(random_bytes(4)));
    }
    
    public static function createDonation($data) {
        $donation = new Donation($data);
        $donation->transaction_id = $donation->generateTransactionId();
        $donation->save();
        return $donation;
    }
    
    public static function getTotalDonations() {
        $db = Database::getInstance();
        $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM donations WHERE payment_status = 'completed'";
        $result = $db->query($sql)->fetch();
        return (float) $result['total'];
    }
    
    public static function getDonorCount() {
        $db = Database::getInstance();
        $sql = "SELECT COUNT(DISTINCT donor_id) as count FROM donations WHERE payment_status = 'completed'";
        $result = $db->query($sql)->fetch();
        return (int) $result['count'];
    }
    
    public static function getDonationsByDonor($donorId) {
        return self::where(['donor_id' => $donorId]);
    }
    
    public static function getRecentDonations($limit = 10) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM donations ORDER BY created_at DESC LIMIT ?";
        $stmt = $db->query($sql, [$limit]);
        $results = $stmt->fetchAll();
        
        $donations = [];
        foreach ($results as $result) {
            $donations[] = new Donation($result);
        }
        
        return new Collection($donations);
    }
    
    public static function getDonationsByProject($projectId) {
        return self::where(['project_id' => $projectId]);
    }
}
