<?php
/**
 * Home Controller - Public Pages
 * NGO Donor Management System
 */

namespace controllers;

class HomeController extends \Controller {
    
    public function index() {
        $featuredProjects = \Project::getActiveProjects()->take(3);
        
        $this->view('home', [
            'featuredProjects' => $featuredProjects,
            'totalDonations' => \Donation::getTotalDonations(),
            'donorCount' => \Donation::getDonorCount(),
            'projectCount' => \Project::getActiveProjects()->count(),
        ]);
    }
    
    public function projects() {
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 9;
        $status = $_GET['status'] ?? 'active';
        
        $db = \Database::getInstance();
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM projects WHERE status = ? ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $db->query($sql, [$status, $perPage, $offset]);
        $results = $stmt->fetchAll();
        
        $projects = [];
        foreach ($results as $result) {
            $projects[] = new \Project($result);
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as count FROM projects WHERE status = ?";
        $total = $db->query($countSql, [$status])->fetch()['count'];
        
        $this->view('projects.index', [
            'projects' => new \Collection($projects),
            'currentPage' => $page,
            'totalPages' => ceil($total / $perPage),
            'status' => $status,
        ]);
    }
    
    public function showProject($slug) {
        $project = \Project::findBySlug($slug);
        
        if (!$project) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }
        
        $recentDonations = \Donation::getDonationsByProject($project->id);
        $donations = $recentDonations->filter(function($d) {
            return $d->anonymous_donation == 0;
        })->take(5);
        
        $this->view('projects.show', [
            'project' => $project,
            'recentDonations' => $donations,
        ]);
    }
    
    public function about() {
        $this->view('about');
    }
    
    public function contact() {
        $this->view('contact');
    }
}
