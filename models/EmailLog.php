<?php
/**
 * EmailLog Model
 * NGO Donor Management System
 */

class EmailLog extends Model {
    protected static string $table = 'email_logs';
    
    protected $attributes = [
        'id' => null,
        'recipient_email' => '',
        'recipient_name' => '',
        'subject' => '',
        'email_type' => '',
        'related_id' => null,
        'related_type' => '',
        'status' => 'pending',
        'error_message' => '',
        'sent_at' => null,
        'created_at' => null,
    ];
    
    public function markAsSent() {
        $this->attributes['status'] = 'sent';
        $this->attributes['sent_at'] = date('Y-m-d H:i:s');
        return $this->save();
    }
    
    public function markAsFailed($errorMessage) {
        $this->attributes['status'] = 'failed';
        $this->attributes['error_message'] = $errorMessage;
        return $this->save();
    }
    
    public static function logEmail($data) {
        $emailLog = new EmailLog($data);
        $emailLog->save();
        return $emailLog;
    }
    
    public static function getByType($type) {
        return self::where(['email_type' => $type]);
    }
    
    public static function getRecentLogs($limit = 50) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM email_logs ORDER BY created_at DESC LIMIT ?";
        $stmt = $db->query($sql, [$limit]);
        $results = $stmt->fetchAll();
        
        $logs = [];
        foreach ($results as $result) {
            $logs[] = new EmailLog($result);
        }
        
        return new Collection($logs);
    }
}
