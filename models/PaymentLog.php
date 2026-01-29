<?php
/**
 * PaymentLog Model
 * NGO Donor Management System
 */

class PaymentLog extends Model
{
    protected static string $table = 'payment_logs';
    
    protected array $fillable = [
        'donation_id',
        'gateway',
        'is_sandbox',
        'transaction_id',
        'request_data',
        'response_data',
        'status',
        'error_message',
        'ipn_data'
    ];
    
    /**
     * Get the donation for this payment log
     */
    public function getDonation(): ?Donation
    {
        return Donation::find($this->donation_id);
    }
    
    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'success' => 'Success',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
            default => 'Unknown'
        };
    }
    
    /**
     * Get gateway label
     */
    public function getGatewayLabel(): string
    {
        return match($this->gateway) {
            'sslcommerz' => 'SSLCommerz',
            'nagad' => 'Nagad',
            'bkash' => 'Bkash',
            'rocket' => 'Rocket',
            'mock' => 'Mock Payment',
            default => ucfirst($this->gateway)
        };
    }
    
    /**
     * Check if payment was successful
     */
    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }
    
    /**
     * Log a payment request
     */
    public static function logRequest(int $donationId, string $gateway, array $requestData, string $transactionId = ''): self
    {
        $isSandbox = Setting::isSandbox($gateway);
        
        return self::create([
            'donation_id' => $donationId,
            'gateway' => $gateway,
            'is_sandbox' => $isSandbox,
            'transaction_id' => $transactionId,
            'request_data' => json_encode($requestData),
            'status' => 'pending'
        ]);
    }
    
    /**
     * Log a payment response
     */
    public function logResponse(array $responseData, string $status = 'pending'): void
    {
        $this->update([
            'response_data' => json_encode($responseData),
            'status' => $status
        ]);
    }
    
    /**
     * Mark as successful
     */
    public function markSuccess(array $responseData = []): void
    {
        $this->update([
            'response_data' => json_encode($responseData),
            'status' => 'success'
        ]);
    }
    
    /**
     * Mark as failed
     */
    public function markFailed(string $errorMessage, array $responseData = []): void
    {
        $this->update([
            'response_data' => json_encode($responseData),
            'status' => 'failed',
            'error_message' => $errorMessage
        ]);
    }
    
    /**
     * Log IPN data
     */
    public function logIpn(array $ipnData): void
    {
        $this->update([
            'ipn_data' => json_encode($ipnData)
        ]);
    }
}
