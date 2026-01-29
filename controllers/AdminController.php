<?php
/**
 * Admin Controller - Admin Portal Features
 * NGO Donor Management System
 */

namespace controllers;

class AdminController extends \Controller {
    
    public function __construct() {
        $this->requireAdmin();
        $this->layout = 'layouts/admin';
    }
    
    public function dashboard() {
        $totalDonations = \Donation::getTotalDonations();
        $donorCount = \Donation::getDonorCount();
        $projectCount = \Project::all()->count();
        $activeProjectCount = \Project::getActiveProjects()->count();
        
        $recentDonations = \Donation::getRecentDonations(5);
        
        $this->view('admin.dashboard', [
            'totalDonations' => $totalDonations,
            'donorCount' => $donorCount,
            'projectCount' => $projectCount,
            'activeProjectCount' => $activeProjectCount,
            'recentDonations' => $recentDonations,
        ]);
    }
    
    // ========== Donor Management ==========
    
    public function donors() {
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 10;
        
        $result = \User::paginate($page, $perPage);
        $donors = $result['data']->filter(function($u) {
            return $u->role === 'donor';
        });
        
        $this->view('admin.donors.index', [
            'donors' => $donors,
            'currentPage' => $page,
            'totalPages' => $result['totalPages'],
        ]);
    }
    
    public function showDonor($id) {
        $donor = \User::find($id);
        
        if (!$donor || $donor->role !== 'donor') {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }
        
        $donations = \Donation::getDonationsByDonor($id);
        $totalDonated = $donor->totalDonated();
        
        $this->view('admin.donors.show', [
            'donor' => $donor,
            'donations' => $donations,
            'totalDonated' => $totalDonated,
        ]);
    }
    
    public function updateDonor($id) {
        $donor = \User::find($id);
        
        if (!$donor) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }
        
        $donor->name = $_POST['name'] ?? $donor->name;
        $donor->email = $_POST['email'] ?? $donor->email;
        $donor->phone = $_POST['phone'] ?? $donor->phone;
        $donor->status = $_POST['status'] ?? $donor->status;
        $donor->save();
        
        $this->with('success', 'Donor updated successfully.')
            ->redirect('/admin/donors/' . $id);
    }
    
    // ========== Donation Management ==========
    
    public function donations() {
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 10;
        
        $result = \Donation::paginate($page, $perPage);
        
        $this->view('admin.donations.index', [
            'donations' => $result['data'],
            'currentPage' => $page,
            'totalPages' => $result['totalPages'],
        ]);
    }
    
    public function showDonation($id) {
        $donation = \Donation::find($id);
        
        if (!$donation) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }
        
        $donor = $donation->getDonor();
        $project = $donation->getProject();
        
        $this->view('admin.donations.show', [
            'donation' => $donation,
            'donor' => $donor,
            'project' => $project,
        ]);
    }
    
    public function updateDonationStatus($id) {
        $donation = \Donation::find($id);
        
        if (!$donation) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }
        
        $status = $_POST['status'] ?? $donation->payment_status;
        $donation->payment_status = $status;
        $donation->save();
        
        $this->with('success', 'Donation status updated.')
            ->redirect('/admin/donations/' . $id);
    }
    
    // ========== Project Management ==========
    
    public function projects() {
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 10;
        
        $result = \Project::paginate($page, $perPage);
        
        $this->view('admin.projects.index', [
            'projects' => $result['data'],
            'currentPage' => $page,
            'totalPages' => $result['totalPages'],
        ]);
    }
    
    public function showCreateProject() {
        $this->view('admin.projects.create');
    }
    
    public function createProject() {
        $data = [
            'title' => $_POST['title'] ?? '',
            'short_description' => $_POST['short_description'] ?? '',
            'full_description' => $_POST['full_description'] ?? '',
            'target_amount' => (float) ($_POST['target_amount'] ?? 0),
            'status' => $_POST['status'] ?? 'draft',
            'start_date' => $_POST['start_date'] ?? null,
            'end_date' => $_POST['end_date'] ?? null,
            'created_by' => \Session::get('user_id'),
        ];
        
        if (empty($data['title'])) {
            $this->with('error', 'Project title is required.')
                ->redirect('/admin/projects/create');
        }
        
        $project = \Project::createProject($data);
        
        $this->with('success', 'Project created successfully.')
            ->redirect('/admin/projects');
    }
    
    public function showEditProject($id) {
        $project = \Project::find($id);
        
        if (!$project) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }
        
        $this->view('admin.projects.edit', ['project' => $project]);
    }
    
    public function updateProject($id) {
        $project = \Project::find($id);
        
        if (!$project) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }
        
        $project->title = $_POST['title'] ?? $project->title;
        $project->short_description = $_POST['short_description'] ?? $project->short_description;
        $project->full_description = $_POST['full_description'] ?? $project->full_description;
        $project->target_amount = (float) ($_POST['target_amount'] ?? $project->target_amount);
        $project->status = $_POST['status'] ?? $project->status;
        $project->start_date = $_POST['start_date'] ?? $project->start_date;
        $project->end_date = $_POST['end_date'] ?? $project->end_date;
        $project->save();
        
        $this->with('success', 'Project updated successfully.')
            ->redirect('/admin/projects');
    }
    
    public function deleteProject($id) {
        $project = \Project::find($id);
        
        if ($project) {
            $project->delete();
            $this->with('success', 'Project deleted successfully.');
        }
        
        $this->redirect('/admin/projects');
    }
    
    // ========== Email Management ==========
    
    public function emails() {
        $logs = \EmailLog::getRecentLogs(50);
        
        $this->view('admin.emails.index', ['logs' => $logs]);
    }
    
    public function showSendEmail() {
        $donors = \User::where(['role' => 'donor', 'status' => 'active']);
        $this->view('admin.emails.create', ['donors' => $donors]);
    }
    
    public function sendEmail() {
        $emailType = $_POST['email_type'] ?? 'custom';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';
        $recipientId = $_POST['recipient_id'] ?? null;
        
        if ($emailType === 'custom') {
            if ($recipientId) {
                $donor = \User::find($recipientId);
                if ($donor) {
                    $emailService = new \EmailService();
                    $emailService->sendCustomEmail($donor, $subject, $message);
                }
            } else {
                // Send to all active donors
                $donors = \User::where(['role' => 'donor', 'status' => 'active']);
                $emailService = new \EmailService();
                foreach ($donors as $donor) {
                    $emailService->sendCustomEmail($donor, $subject, $message);
                }
            }
        }
        
        $this->with('success', 'Email(s) sent successfully.')
            ->redirect('/admin/emails');
    }
}
