<?php
/**
 * Email Service - SendGrid Integration
 * NGO Donor Management System
 */

class EmailService {
    private $apiKey;
    private $fromEmail;
    private $fromName;
    
    public function __construct() {
        $this->apiKey = env('SENDGRID_API_KEY', '');
        $this->fromEmail = env('MAIL_FROM_ADDRESS', 'noreply@ngodonation.org');
        $this->fromName = env('MAIL_FROM_NAME', 'NGO Donation System');
    }
    
    public function sendDonationConfirmation($donation) {
        $donor = $donation->getDonor();
        $project = $donation->getProject();
        
        $subject = 'Donation Confirmation - Thank You!';
        $message = $this->getDonationConfirmationMessage($donor, $donation, $project);
        
        return $this->sendEmail($donor->email, $donor->name, $subject, $message, 'donation_confirmation', $donation->id, 'donation');
    }
    
    public function sendDonationReceipt($donation) {
        $donor = $donation->getDonor();
        $project = $donation->getProject();
        
        $subject = 'Your Donation Receipt';
        $receiptHtml = $this->generateReceiptHtml($donor, $donation, $project);
        
        return $this->sendEmail($donor->email, $donor->name, $subject, $receiptHtml, 'donation_receipt', $donation->id, 'donation');
    }
    
    public function sendProjectUpdate($project, $donors) {
        $subject = 'Project Update: ' . $project->title;
        $message = $this->getProjectUpdateMessage($project);
        
        foreach ($donors as $donor) {
            $this->sendEmail($donor->email, $donor->name, $subject, $message, 'project_update', $project->id, 'project');
        }
    }
    
    public function sendWelcomeEmail($user) {
        $subject = 'Welcome to Our Donor Community!';
        $message = $this->getWelcomeMessage($user);
        
        return $this->sendEmail($user->email, $user->name, $subject, $message, 'welcome', $user->id, 'user');
    }
    
    public function sendCustomEmail($user, $subject, $message) {
        return $this->sendEmail($user->email, $user->name, $subject, $message, 'admin_notification', $user->id, 'user');
    }
    
    private function sendEmail($toEmail, $toName, $subject, $htmlContent, $emailType, $relatedId, $relatedType) {
        // Log the email attempt
        $emailLog = EmailLog::logEmail([
            'recipient_email' => $toEmail,
            'recipient_name' => $toName,
            'subject' => $subject,
            'email_type' => $emailType,
            'related_id' => $relatedId,
            'related_type' => $relatedType,
            'status' => 'pending',
        ]);
        
        // Send via SendGrid API
        $result = $this->sendgridSend($toEmail, $toName, $subject, $htmlContent);
        
        if ($result['success']) {
            $emailLog->markAsSent();
        } else {
            $emailLog->markAsFailed($result['error'] ?? 'Unknown error');
        }
        
        return $result['success'];
    }
    
    private function sendgridSend($toEmail, $toName, $subject, $htmlContent) {
        if (empty($this->apiKey)) {
            // Development mode - just log it
            error_log("Email would be sent to: $toEmail");
            error_log("Subject: $subject");
            return ['success' => true];
        }
        
        $url = 'https://api.sendgrid.com/v3/mail/send';
        
        $data = [
            'personalizations' => [
                [
                    'to' => [['email' => $toEmail, 'name' => $toName]],
                    'subject' => $subject,
                ],
            ],
            'from' => [
                'email' => $this->fromEmail,
                'name' => $this->fromName,
            ],
            'content' => [
                [
                    'type' => 'text/html',
                    'value' => $htmlContent,
                ],
            ],
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode >= 200 && $httpCode < 300) {
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => 'SendGrid API error: ' . $response];
    }
    
    private function getDonationConfirmationMessage($donor, $donation, $project) {
        $projectName = $project ? $project->title : 'General Fund';
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Donation Confirmation</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: #10b981; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;'>
                <h1 style='margin: 0;'>Thank You for Your Donation!</h1>
            </div>
            <div style='background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px;'>
                <p>Dear {$donor->name},</p>
                <p>We're deeply grateful for your generous donation of <strong>\${$donation->amount}</strong> to the <strong>{$projectName}</strong> project.</p>
                
                <div style='background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #e5e7eb;'>
                    <h3 style='margin-top: 0; color: #374151;'>Donation Details</h3>
                    <p><strong>Amount:</strong> \${$donation->amount}</p>
                    <p><strong>Project:</strong> {$projectName}</p>
                    <p><strong>Date:</strong> {$donation->created_at}</p>
                    <p><strong>Transaction ID:</strong> {$donation->transaction_id}</p>
                </div>
                
                <p>Your contribution helps us make a real difference in the lives of those we serve.</p>
                <p>We'll keep you updated on the progress of this project and how your donation is being used.</p>
                
                <hr style='border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;'>
                
                <p style='color: #6b7280; font-size: 14px;'>
                    With gratitude,<br>
                    <strong>NGO Donation Team</strong>
                </p>
            </div>
        </body>
        </html>
        ";
    }
    
    private function getWelcomeMessage($user) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Welcome</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: #3b82f6; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;'>
                <h1 style='margin: 0;'>Welcome to Our Donor Community!</h1>
            </div>
            <div style='background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px;'>
                <p>Dear {$user->name},</p>
                <p>Thank you for joining our donor community! We're thrilled to have you with us.</p>
                <p>As a registered donor, you can now:</p>
                <ul>
                    <li>Browse our ongoing projects</li>
                    <li>Make donations to causes you care about</li>
                    <li>Track your donation history</li>
                    <li>Download receipts for your contributions</li>
                </ul>
                <p style='text-align: center; margin-top: 30px;'>
                    <a href='" . getenv('APP_URL') . "/projects' style='background: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;'>Browse Projects</a>
                </p>
                <p style='color: #6b7280; font-size: 14px; margin-top: 30px;'>
                    With gratitude,<br>
                    <strong>NGO Donation Team</strong>
                </p>
            </div>
        </body>
        </html>
        ";
    }
    
    private function getProjectUpdateMessage($project) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Project Update</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: #8b5cf6; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;'>
                <h1 style='margin: 0;'>Project Update</h1>
            </div>
            <div style='background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px;'>
                <p>Dear Donor,</p>
                <p>We're excited to share an update on the project you supported!</p>
                
                <h2 style='color: #374151;'>{$project->title}</h2>
                <p>{$project->short_description}</p>
                
                <div style='background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #e5e7eb;'>
                    <p><strong>Progress:</strong></p>
                    <div style='background: #e5e7eb; height: 20px; border-radius: 10px; overflow: hidden;'>
                        <div style='background: #10b981; height: 100%; width: {$project->getProgressPercentage()}%;'></div>
                    </div>
                    <p style='text-align: center; margin-top: 10px;'>\${$project->raised_amount} raised of \${$project->target_amount} ({$project->getProgressPercentage()}%)</p>
                </div>
                
                <p style='text-align: center; margin-top: 30px;'>
                    <a href='" . getenv('APP_URL') . "/projects/{$project->slug}' style='background: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;'>View Project</a>
                </p>
                
                <p style='color: #6b7280; font-size: 14px; margin-top: 30px;'>
                    Thank you for being part of this journey!<br>
                    <strong>NGO Donation Team</strong>
                </p>
            </div>
        </body>
        </html>
        ";
    }
    
    private function generateReceiptHtml($donor, $donation, $project) {
        $projectName = $project ? $project->title : 'General Fund';
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Donation Receipt</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='border: 2px solid #374151; padding: 30px; border-radius: 8px;'>
                <div style='text-align: center; border-bottom: 2px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px;'>
                    <h1 style='margin: 0; color: #374151;'>OFFICIAL DONATION RECEIPT</h1>
                </div>
                
                <div style='margin-bottom: 20px;'>
                    <p><strong>NGO Donation System</strong></p>
                    <p style='color: #6b7280;'>This receipt is official for tax purposes.</p>
                </div>
                
                <div style='background: #f3f4f6; padding: 15px; border-radius: 6px; margin-bottom: 20px;'>
                    <p style='margin: 5px 0;'><strong>Receipt #:</strong> {$donation->transaction_id}</p>
                    <p style='margin: 5px 0;'><strong>Date:</strong> {$donation->created_at}</p>
                </div>
                
                <div style='margin-bottom: 20px;'>
                    <h3 style='border-bottom: 1px solid #e5e7eb; padding-bottom: 10px;'>DONOR INFORMATION</h3>
                    <p><strong>Name:</strong> {$donor->name}</p>
                    <p><strong>Email:</strong> {$donor->email}</p>
                </div>
                
                <div style='margin-bottom: 20px;'>
                    <h3 style='border-bottom: 1px solid #e5e7eb; padding-bottom: 10px;'>DONATION DETAILS</h3>
                    <p><strong>Project:</strong> {$projectName}</p>
                    <p><strong>Amount:</strong> \${$donation->amount}</p>
                    <p><strong>Payment Method:</strong> " . ucfirst($donation->payment_method) . "</p>
                </div>
                
                <div style='text-align: center; padding-top: 20px; border-top: 2px solid #e5e7eb;'>
                    <p style='color: #10b981; font-size: 18px;'><strong>Thank you for your generous donation!</strong></p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
