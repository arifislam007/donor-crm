<?php
/**
 * Payment Gateway Service
 * Supports Bangladeshi payment methods: SSLCommerz, Nagad, Bkash, Rocket
 * NGO Donor Management System
 */

namespace services;

class PaymentGateway {
    
    private $config;
    private $exchangeRate = 110; // 1 USD = 110 BDT
    
    public function __construct() {
        $this->config = [
            'sslcommerz' => [
                'store_id' => env('SSLCOMMERZ_STORE_ID', ''),
                'store_passwd' => env('SSLCOMMERZ_STORE_PASSWORD', ''),
                'sandbox' => env('SSLCOMMERZ_SANDBOX', true),
            ],
            'nagad' => [
                'merchant_id' => env('NAGAD_MERCHANT_ID', ''),
                'merchant_key' => env('NAGAD_MERCHANT_KEY', ''),
                'sandbox' => env('NAGAD_SANDBOX', true),
            ],
            'bkash' => [
                'app_key' => env('BKASH_APP_KEY', ''),
                'app_secret' => env('BKASH_APP_SECRET', ''),
                'username' => env('BKASH_USERNAME', ''),
                'password' => env('BKASH_PASSWORD', ''),
                'sandbox' => env('BKASH_SANDBOX', true),
            ],
            'rocket' => [
                'merchant_id' => env('ROCKET_MERCHANT_ID', ''),
                'merchant_key' => env('ROCKET_MERCHANT_KEY', ''),
                'sandbox' => env('ROCKET_SANDBOX', true),
            ],
        ];
    }
    
    /**
     * Get available payment methods
     */
    public function getAvailableMethods() {
        return [
            'sslcommerz' => [
                'name' => 'SSLCommerz',
                'icon' => 'fa-credit-card',
                'description' => 'Credit/Debit Card, Net Banking, Mobile Banking',
            ],
            'nagad' => [
                'name' => 'Nagad',
                'icon' => 'fa-wallet',
                'description' => 'Nagad Digital Wallet',
            ],
            'bkash' => [
                'name' => 'bKash',
                'icon' => 'fa-mobile-alt',
                'description' => 'bKash Mobile Wallet',
            ],
            'rocket' => [
                'name' => 'Rocket',
                'icon' => 'fa-rocket',
                'description' => 'DBBL Rocket',
            ],
        ];
    }
    
    /**
     * Initialize payment
     */
    public function initializePayment($amount, $method, $donationId, $description = 'Donation') {
        $amountBDT = $this->convertToBDT($amount);
        
        switch ($method) {
            case 'sslcommerz':
                return $this->initSSLCommerz($amountBDT, $donationId, $description);
            case 'nagad':
                return $this->initNagad($amountBDT, $donationId, $description);
            case 'bkash':
                return $this->initBkash($amountBDT, $donationId, $description);
            case 'rocket':
                return $this->initRocket($amountBDT, $donationId, $description);
            default:
                throw new \Exception("Unsupported payment method: $method");
        }
    }
    
    /**
     * Initialize SSLCommerz payment
     */
    private function initSSLCommerz($amount, $donationId, $description) {
        $storeId = $this->config['sslcommerz']['store_id'];
        $storePass = $this->config['sslcommerz']['store_passwd'];
        $sandbox = $this->config['sslcommerz']['sandbox'];
        
        $url = $sandbox 
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';
        
        $postData = [
            'store_id' => $storeId,
            'store_passwd' => $storePass,
            'total_amount' => $amount,
            'currency' => 'BDT',
            'tran_id' => 'DON-' . $donationId . '-' . time(),
            'success_url' => env('APP_URL', 'http://localhost:8080') . '/payment/success',
            'fail_url' => env('APP_URL', 'http://localhost:8080') . '/payment/fail',
            'cancel_url' => env('APP_URL', 'http://localhost:8080') . '/payment/cancel',
            'ipn_url' => env('APP_URL', 'http://localhost:8080') . '/payment/ipn',
            'product_name' => $description,
            'product_category' => 'donation',
            'emi_option' => 0,
        ];
        
        // For demo purposes, return mock response
        if (empty($storeId) || empty($storePass)) {
            return $this->mockResponse('sslcommerz', $amount, $donationId);
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        if (isset($result['GatewayPageURL'])) {
            return [
                'success' => true,
                'method' => 'sslcommerz',
                'redirect_url' => $result['GatewayPageURL'],
                'transaction_id' => $postData['tran_id'],
            ];
        }
        
        return [
            'success' => false,
            'method' => 'sslcommerz',
            'error' => $result['failedreason'] ?? 'Payment initialization failed',
        ];
    }
    
    /**
     * Initialize Nagad payment
     */
    private function initNagad($amount, $donationId, $description) {
        $merchantId = $this->config['nagad']['merchant_id'];
        $merchantKey = $this->config['nagad']['merchant_key'];
        $sandbox = $this->config['nagad']['sandbox'];
        
        // For demo purposes, return mock response
        if (empty($merchantId) || empty($merchantKey)) {
            return $this->mockResponse('nagad', $amount, $donationId);
        }
        
        // Nagad API integration would go here
        // This is a simplified version
        
        return $this->mockResponse('nagad', $amount, $donationId);
    }
    
    /**
     * Initialize bKash payment
     */
    private function initBkash($amount, $donationId, $description) {
        $appKey = $this->config['bkash']['app_key'];
        $appSecret = $this->config['bkash']['app_secret'];
        $username = $this->config['bkash']['username'];
        $password = $this->config['bkash']['password'];
        $sandbox = $this->config['bkash']['sandbox'];
        
        // For demo purposes, return mock response
        if (empty($appKey) || empty($appSecret)) {
            return $this->mockResponse('bkash', $amount, $donationId);
        }
        
        // bKash API integration would go here
        // This is a simplified version
        
        return $this->mockResponse('bkash', $amount, $donationId);
    }
    
    /**
     * Initialize Rocket payment
     */
    private function initRocket($amount, $donationId, $description) {
        $merchantId = $this->config['rocket']['merchant_id'];
        $merchantKey = $this->config['rocket']['merchant_key'];
        $sandbox = $this->config['rocket']['sandbox'];
        
        // For demo purposes, return mock response
        if (empty($merchantId) || empty($merchantKey)) {
            return $this->mockResponse('rocket', $amount, $donationId);
        }
        
        // Rocket API integration would go here
        // This is a simplified version
        
        return $this->mockResponse('rocket', $amount, $donationId);
    }
    
    /**
     * Mock response for demo/testing
     */
    private function mockResponse($method, $amount, $donationId) {
        return [
            'success' => true,
            'method' => $method,
            'redirect_url' => env('APP_URL', 'http://localhost:8080') . '/payment/mock/' . $donationId . '?method=' . $method . '&amount=' . $amount,
            'transaction_id' => 'MOCK-' . strtoupper($method) . '-' . $donationId . '-' . time(),
            'mock' => true,
        ];
    }
    
    /**
     * Verify payment
     */
    public function verifyPayment($method, $transactionId) {
        switch ($method) {
            case 'sslcommerz':
                return $this->verifySSLCommerz($transactionId);
            case 'nagad':
                return $this->verifyNagad($transactionId);
            case 'bkash':
                return $this->verifyBkash($transactionId);
            case 'rocket':
                return $this->verifyRocket($transactionId);
            default:
                return ['success' => false, 'error' => 'Unknown method'];
        }
    }
    
    /**
     * Verify SSLCommerz payment
     */
    private function verifySSLCommerz($transactionId) {
        $storeId = $this->config['sslcommerz']['store_id'];
        $storePass = $this->config['sslcommerz']['store_passwd'];
        
        if (empty($storeId) || empty($storePass)) {
            // Mock verification
            return [
                'success' => true,
                'method' => 'sslcommerz',
                'transaction_id' => $transactionId,
                'amount' => 0,
                'status' => 'VALID',
            ];
        }
        
        $url = 'https://sandbox.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php';
        $postData = [
            'store_id' => $storeId,
            'store_passwd' => $storePass,
            'merchant_trans_id' => $transactionId,
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        return [
            'success' => isset($result['status']) && $result['status'] === 'VALID',
            'method' => 'sslcommerz',
            'transaction_id' => $transactionId,
            'amount' => $result['amount'] ?? 0,
            'status' => $result['status'] ?? 'UNKNOWN',
        ];
    }
    
    /**
     * Verify Nagad payment
     */
    private function verifyNagad($transactionId) {
        // Mock verification
        return [
            'success' => true,
            'method' => 'nagad',
            'transaction_id' => $transactionId,
            'amount' => 0,
            'status' => 'VALID',
        ];
    }
    
    /**
     * Verify bKash payment
     */
    private function verifyBkash($transactionId) {
        // Mock verification
        return [
            'success' => true,
            'method' => 'bkash',
            'transaction_id' => $transactionId,
            'amount' => 0,
            'status' => 'COMPLETED',
        ];
    }
    
    /**
     * Verify Rocket payment
     */
    private function verifyRocket($transactionId) {
        // Mock verification
        return [
            'success' => true,
            'method' => 'rocket',
            'transaction_id' => $transactionId,
            'amount' => 0,
            'status' => 'SUCCESS',
        ];
    }
    
    /**
     * Convert USD to BDT
     */
    public function convertToBDT($usdAmount) {
        return $usdAmount * $this->exchangeRate;
    }
    
    /**
     * Convert BDT to USD
     */
    public function convertToUSD($bdtAmount) {
        return $bdtAmount / $this->exchangeRate;
    }
    
    /**
     * Get exchange rate
     */
    public function getExchangeRate() {
        return $this->exchangeRate;
    }
}
