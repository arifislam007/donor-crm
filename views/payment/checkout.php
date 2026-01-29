<?php
/**
 * Payment Checkout View
 * NGO Donor Management System
 */

$title = 'Payment - Sombhabona';
?>

<!-- Page Header -->
<section class="hero-gradient text-white py-12">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Complete Your Donation</h1>
        <p class="text-xl text-gray-200">Choose your payment method</p>
    </div>
</section>

<!-- Wave divider -->
<div class="relative">
    <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 40L60 33.3C120 26.7 240 13.3 360 10C480 6.67 600 13.3 720 20C840 26.7 960 33.3 1080 33.3C1200 33.3 1320 26.7 1380 23.3L1440 20V40H1380C1320 40 1200 40 1080 40C960 40 840 40 720 40C600 40 480 40 360 40C240 40 120 40 60 40H0Z" fill="#f9fafb"/>
    </svg>
</div>

<!-- Payment Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Order Summary -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>
                
                <div class="border-b border-gray-200 pb-4 mb-4">
                    <?php if ($project): ?>
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-ngo-primary to-ngo-dark rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-hands-helping text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800"><?= htmlspecialchars($project->title) ?></p>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars(substr($project->short_description, 0, 50)) ?>...</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-ngo-primary to-ngo-dark rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-heart text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">General Donation</p>
                                <p class="text-sm text-gray-500">Support our mission</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Donation Amount</span>
                    <span class="text-2xl font-bold text-ngo-primary">$<?= number_format($amount, 2) ?></span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Exchange Rate</span>
                    <span class="text-gray-500">1 USD = ৳110 BDT</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <span class="text-lg font-bold text-gray-800">Total in BDT</span>
                    <span class="text-3xl font-bold text-ngo-primary">৳<?= number_format($amountBDT, 2) ?></span>
                </div>
            </div>
            
            <!-- Payment Methods -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Select Payment Method</h2>
                
                <form method="POST" action="/payment/process">
                    <input type="hidden" name="amount" value="<?= $amount ?>">
                    <input type="hidden" name="project_id" value="<?= $project->id ?? '' ?>">
                    
                    <div class="space-y-3 mb-6">
                        <?php foreach ($methods as $key => $method): ?>
                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-ngo-primary transition <?= $key === 'sslcommerz' ? 'border-ngo-primary bg-ngo-primary/5' : '' ?>">
                                <input type="radio" name="payment_method" value="<?= $key ?>" class="mr-4" <?= $key === 'sslcommerz' ? 'checked' : '' ?>>
                                <div class="flex items-center flex-1">
                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas <?= $method['icon'] ?> text-ngo-primary text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800"><?= $method['name'] ?></p>
                                        <p class="text-sm text-gray-500"><?= $method['description'] ?></p>
                                    </div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Anonymous checkbox -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="anonymous" class="mr-3 w-4 h-4 text-ngo-primary rounded">
                            <span class="text-gray-600">Make this donation anonymous</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="w-full btn-primary text-white py-4 rounded-lg font-bold text-lg hover:shadow-xl transition">
                        <i class="fas fa-lock mr-2"></i>Proceed to Pay ৳<?= number_format($amountBDT, 0) ?>
                    </button>
                    
                    <p class="text-center text-sm text-gray-500 mt-4">
                        <i class="fas fa-shield-alt mr-1"></i>Secure payment powered by SSLCommerz
                    </p>
                </form>
            </div>
        </div>
        
        <!-- Back Link -->
        <div class="text-center mt-8">
            <a href="<?= $project ? '/projects/' . htmlspecialchars($project->slug) : '/projects' ?>" class="text-ngo-primary hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to <?= $project ? 'Project' : 'Projects' ?>
            </a>
        </div>
    </div>
</section>
