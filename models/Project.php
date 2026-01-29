<?php
/**
 * Project Model
 * NGO Donor Management System
 */

class Project extends Model {
    protected static $table = 'projects';
    
    protected $attributes = [
        'id' => null,
        'title' => '',
        'slug' => '',
        'short_description' => '',
        'full_description' => '',
        'target_amount' => 0,
        'raised_amount' => 0,
        'image_path' => '',
        'status' => 'draft',
        'start_date' => null,
        'end_date' => null,
        'created_by' => null,
        'created_at' => null,
        'updated_at' => null,
    ];
    
    public function getProgressPercentage() {
        if ($this->target_amount <= 0) {
            return 0;
        }
        return min(100, round(($this->raised_amount / $this->target_amount) * 100, 2));
    }
    
    public function getRemainingAmount() {
        return max(0, $this->target_amount - $this->raised_amount);
    }
    
    public function isActive() {
        return $this->status === 'active';
    }
    
    public function isCompleted() {
        return $this->status === 'completed';
    }
    
    public function donations() {
        return Donation::where(['project_id' => $this->id]);
    }
    
    public function donorCount() {
        $db = Database::getInstance();
        $sql = "SELECT COUNT(DISTINCT donor_id) as count FROM donations WHERE project_id = ? AND payment_status = 'completed'";
        $result = $db->query($sql, [$this->id])->fetch();
        return (int) $result['count'];
    }
    
    public function getStatusLabel() {
        $labels = [
            'draft' => 'Draft',
            'active' => 'Active',
            'completed' => 'Completed',
            'paused' => 'Paused',
        ];
        return $labels[$this->status] ?? $this->status;
    }
    
    public static function findBySlug($slug) {
        return self::findBy('slug', $slug);
    }
    
    public static function getActiveProjects() {
        return self::where(['status' => 'active']);
    }
    
    public static function createProject($data) {
        $project = new Project($data);
        
        // Generate slug from title if not provided
        if (empty($project->slug)) {
            $project->slug = self::generateSlug($data['title']);
        }
        
        $project->save();
        return $project;
    }
    
    private static function generateSlug($title) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $db = Database::getInstance();
        
        // Check if slug exists
        $sql = "SELECT COUNT(*) as count FROM projects WHERE slug LIKE ?";
        $result = $db->query($sql, [$slug . '%'])->fetch();
        
        if ($result['count'] > 0) {
            $slug .= '-' . ($result['count'] + 1);
        }
        
        return $slug;
    }
}
