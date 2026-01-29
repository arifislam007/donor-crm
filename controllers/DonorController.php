<?php
/**
 * Donor Controller - Donor Portal Features
 * NGO Donor Management System
 */

namespace controllers;

class DonorController extends \Controller {
    
    public function __construct() {
        $this->requireLogin();
    }
    
    public function dashboard() {
        $user = \Session::getUser();
        
        $totalDonated = $user->totalDonated();
        $donations = \Donation::getDonationsByDonor($user->id);
        $recentDonations = $donations->take(5);
        $donationCount = $donations->count();
        
        $this->view('donor.dashboard', [
            'user' => $user,
            'totalDonated' => $totalDonated,
            'recentDonations' => $recentDonations,
            'donationCount' => $donationCount,
        ]);
    }
    
    public function showDonationHistory() {
        $user = \Session::getUser();
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 10;
        
        $result = \Donation::paginate($page, $perPage);
        $donations = $result['data']->filter(function($d) use ($user) {
            return $d->donor_id == $user->id;
        });
        
        $this->view('donor.donations.index', [
            'donations' => $donations,
            'currentPage' => $page,
            'totalPages' => $result['totalPages'],
        ]);
    }
    
    public function showDonation($id) {
        $user = \Session::getUser();
        $donation = \Donation::find($id);
        
        if (!$donation || $donation->donor_id != $user->id) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }
        
        $project = $donation->getProject();
        
        $this->view('donor.donations.show', [
            'donation' => $donation,
            'project' => $project,
        ]);
    }
    
    public function showDonate($projectId) {
        $project = \Project::find($projectId);
        
        if (!$project || !$project->isActive()) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }
        
        $this->view('donor.donate', [
            'project' => $project,
        ]);
    }
    
    public function processDonation() {
        $user = \Session::getUser();
        
        $data = [
            'donor_id' => $user->id,
            'project_id' => $_POST['project_id'] ?? null,
            'amount' => (float) ($_POST['amount'] ?? 0),
            'currency' => 'USD',
            'payment_method' => $_POST['payment_method'] ?? 'cash',
            'anonymous_donation' => isset($_POST['anonymous']) ? 1 : 0,
            'message' => $_POST['message'] ?? '',
        ];
        
        // Validation
        if ($data['amount'] <= 0) {
            $this->with('error', 'Please enter a valid donation amount.')
                ->redirect('/donate/' . $data['project_id']);
        }
        
        // Create donation
        $donation = \Donation::createDonation($data);
        
        // In a real application, you would integrate with a payment gateway here
        // For now, we'll mark it as completed (simulating successful payment)
        $donation->markAsCompleted();
        
        // Send confirmation email
        $emailService = new \EmailService();
        $emailService->sendDonationConfirmation($donation);
        
        $this->with('success', 'Thank you for your donation!')
            ->redirect('/donation/success/' . $donation->id);
    }
    
    public function donationSuccess($id) {
        $donation = \Donation::find($id);
        
        if (!$donation) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }
        
        $project = $donation->getProject();
        
        $this->view('donor.donations.success', [
            'donation' => $donation,
            'project' => $project,
        ]);
    }
    
    public function showProfile() {
        $user = \Session::getUser();
        $this->view('donor.profile.edit', ['user' => $user]);
    }
    
    public function updateProfile() {
        $user = \Session::getUser();
        
        $user->name = $_POST['name'] ?? $user->name;
        $user->phone = $_POST['phone'] ?? $user->phone;
        $user->address = $_POST['address'] ?? $user->address;
        $user->country = $_POST['country'] ?? $user->country;
        $user->save();
        
        $this->with('success', 'Profile updated successfully.')
            ->redirect('/profile');
    }
}
