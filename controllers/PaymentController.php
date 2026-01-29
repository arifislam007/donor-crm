<?php
/**
 * Payment Controller
 * NGO Donor Management System
 */

namespace controllers;

class PaymentController extends \Controller {
    
    private $paymentGateway;
    
    public function __construct() {
        $this->paymentGateway = new \services\PaymentGateway();
    }
    
    /**
     * Show payment checkout page
     */
    public function checkout($projectId = null) {
        $amount = (float) ($_POST['amount'] ?? 0);
        $projectId = $projectId ?? ($_POST['project_id'] ?? null);
        
        if ($amount <= 0) {
            $this->with('error', 'Please enter a valid amount.')->redirect('/projects' . ($projectId ? '/' . \Project::find($projectId)->slug : ''));
        }
        
        $project = null;
        if ($projectId) {
            $project = \Project::find($projectId);
        }
        
        $methods = $this->paymentGateway->getAvailableMethods();
        
        $this->view('payment.checkout', [
            'amount' => $amount,
            'project' => $project,
            'methods' => $methods,
            'amountBDT' => $this->paymentGateway->convertToBDT($amount),
        ]);
    }
    
    /**
     * Process payment
     */
    public function process() {
        $amount = (float) ($_POST['amount'] ?? 0);
        $method = $_POST['payment_method'] ?? '';
        $projectId = $_POST['project_id'] ?? null;
        $anonymous = isset($_POST['anonymous']);
        
        if ($amount <= 0 || empty($method)) {
            $this->with('error', 'Invalid payment details.')->redirect('/projects' . ($projectId ? '/' . \Project::find($projectId)->slug : ''));
        }
        
        // Create pending donation
        $donation = \Donation::create([
            'user_id' => Session::isLoggedIn() ? Session::get('user_id') : null,
            'project_id' => $projectId,
            'amount' => $amount,
            'payment_method' => $method,
            'payment_status' => 'pending',
            'anonymous_donation' => $anonymous,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        
        // Initialize payment
        $result = $this->paymentGateway->initializePayment(
            $amount,
            $method,
            $donation->id,
            $projectId ? 'Donation to ' . \Project::find($projectId)->title : 'General Donation'
        );
        
        if ($result['success']) {
            // Store transaction ID
            $donation->transaction_id = $result['transaction_id'];
            $donation->save();
            
            // Redirect to payment gateway
            $this->redirect($result['redirect_url']);
        } else {
            $donation->delete();
            $this->with('error', $result['error'] ?? 'Payment initialization failed.')->redirect('/payment/checkout');
        }
    }
    
    /**
     * Mock payment page for testing
     */
    public function mockPayment($donationId) {
        $donation = \Donation::find($donationId);
        
        if (!$donation) {
            $this->with('error', 'Donation not found.')->redirect('/');
        }
        
        $method = $_GET['method'] ?? 'sslcommerz';
        $amount = $_GET['amount'] ?? $this->paymentGateway->convertToBDT($donation->amount);
        
        $this->view('payment.mock', [
            'donation' => $donation,
            'method' => $method,
            'amount' => $amount,
        ]);
    }
    
    /**
     * Simulate successful payment
     */
    public function simulateSuccess($donationId) {
        $donation = \Donation::find($donationId);
        
        if (!$donation) {
            $this->with('error', 'Donation not found.')->redirect('/');
        }
        
        // Update donation status
        $donation->payment_status = 'completed';
        $donation->save();
        
        // Update project raised amount
        if ($donation->project_id) {
            $project = \Project::find($donation->project_id);
            if ($project) {
                $project->raised_amount += $donation->amount;
                $project->donation_count += 1;
                $project->donor_count = \Donation::where(['project_id' => $project->id])
                    ->filter(function($d) { return !$d->anonymous_donation; })
                    ->count();
                $project->save();
            }
        }
        
        // Send confirmation email
        if (!$donation->anonymous_donation && $donation->user_id) {
            $user = \User::find($donation->user_id);
            if ($user) {
                $emailService = new \services\EmailService();
                $emailService->sendDonationConfirmation($user, $donation);
            }
        }
        
        $this->with('success', 'Payment successful! Thank you for your donation.')
            ->redirect('/dashboard');
    }
    
    /**
     * Payment success callback (from payment gateway)
     */
    public function success() {
        $tranId = $_POST['tran_id'] ?? $_GET['tran_id'] ?? '';
        
        // Find donation by transaction ID
        $donation = \Donation::where(['transaction_id' => $tranId])->first();
        
        if ($donation) {
            // Verify payment
            $result = $this->paymentGateway->verifyPayment($donation->payment_method, $tranId);
            
            if ($result['success']) {
                $donation->payment_status = 'completed';
                $donation->save();
                
                // Update project
                if ($donation->project_id) {
                    $project = \Project::find($donation->project_id);
                    if ($project) {
                        $project->raised_amount += $donation->amount;
                        $project->donation_count += 1;
                        $project->donor_count = \Donation::where(['project_id' => $project->id])
                            ->filter(function($d) { return !$d->anonymous_donation; })
                            ->count();
                        $project->save();
                    }
                }
                
                $this->with('success', 'Payment successful! Thank you for your donation.')
                    ->redirect('/dashboard');
            }
        }
        
        $this->with('error', 'Payment verification failed.')->redirect('/dashboard');
    }
    
    /**
     * Payment failure callback
     */
    public function fail() {
        $tranId = $_POST['tran_id'] ?? $_GET['tran_id'] ?? '';
        
        $donation = \Donation::where(['transaction_id' => $tranId])->first();
        
        if ($donation) {
            $donation->payment_status = 'failed';
            $donation->save();
        }
        
        $this->with('error', 'Payment failed. Please try again.')
            ->redirect('/dashboard');
    }
    
    /**
     * Payment cancel callback
     */
    public function cancel() {
        $tranId = $_POST['tran_id'] ?? $_GET['tran_id'] ?? '';
        
        $donation = \Donation::where(['transaction_id' => $tranId])->first();
        
        if ($donation) {
            $donation->payment_status = 'cancelled';
            $donation->save();
        }
        
        $this->with('error', 'Payment was cancelled.')
            ->redirect('/dashboard');
    }
    
    /**
     * IPN callback
     */
    public function ipn() {
        $tranId = $_POST['tran_id'] ?? '';
        
        // Log IPN for debugging
        error_log("Payment IPN received: " . $tranId);
        
        // Process IPN based on payment method
        // This would typically verify the payment and update status
        
        echo 'IPN received';
    }
}
