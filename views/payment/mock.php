<?php
/**
 * Mock Payment View (for testing)
 * NGO Donor Management System
 */

$title = 'Complete Payment - Sombhabona';
?>

<!-- Mock Payment Section -->
<section class="py-12 bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <!-- Payment Method Icon -->
            <div class="w-20 h-20 bg-ngo-primary rounded-full flex items-center justify-center mx-auto mb-6">
                <?php $icons = [
                    'sslcommerz' => 'fa-credit-card',
                    'nagad' => 'fa-wallet',
                    'bkash' => 'fa-mobile-alt',
                    'rocket' => 'fa-rocket',
                ]; ?>
                <i class="fas <?= $icons[$method] ?? 'fa-credit-card' ?> text-white text-3xl"></i>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Complete Your Payment</h1>
            <p class="text-gray-600 mb-6">Testing mode - Simulate a successful payment</p>
            
            <!-- Order Details -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Amount</span>
                    <span class="text-xl font-bold text-ngo-primary">৳<?= number_format($amount, 2) ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Payment Method</span>
                    <span class="font-medium text-gray-800"><?= ucfirst($method) ?></span>
                </div>
            </div>
            
            <!-- Simulate Payment Buttons -->
            <div class="space-y-3">
                <a href="/payment/mock/<?= $donation->id ?>/success?method=<?= $method ?>" 
                   class="block w-full bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 transition">
                    <i class="fas fa-check-circle mr-2"></i>Simulate Success
                </a>
                <a href="/payment/fail?tran_id=<?= $donation->transaction_id ?>" 
                   class="block w-full bg-red-600 text-white py-3 rounded-lg font-bold hover:bg-red-700 transition">
                    <i class="fas fa-times-circle mr-2"></i>Simulate Failure
                </a>
                <a href="/dashboard" 
                   class="block w-full bg-gray-500 text-white py-3 rounded-lg font-bold hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Cancel
                </a>
            </div>
            
            <p class="text-xs text-gray-500 mt-6">
                This is a mock payment page for testing purposes. 
                In production, this would redirect to the actual payment gateway.
            </p>
        </div>
    </div>
</section>
