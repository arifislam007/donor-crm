<?php
/**
 * Donor Dashboard View
 * NGO Donor Management System
 */

$title = 'Dashboard - Sombhabona';
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">My Dashboard</h1>
        <p class="text-gray-600">Welcome back, <?= htmlspecialchars($user->name) ?>!</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mr-4">
                    <i class="fas fa-dollar-sign text-emerald-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Donated</p>
                    <p class="text-2xl font-bold text-gray-800">$<?= number_format($totalDonated, 2) ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                    <i class="fas fa-gift text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Donations Made</p>
                    <p class="text-2xl font-bold text-gray-800"><?= $donationCount ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mr-4">
                    <i class="fas fa-heart text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Impact</p>
                    <p class="text-2xl font-bold text-gray-800">Making a Difference</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="/projects" class="flex items-center p-4 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition">
                <i class="fas fa-search text-emerald-600 text-xl mr-3"></i>
                <span class="font-medium text-gray-800">Browse Projects</span>
            </a>
            <a href="/donation-history" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <i class="fas fa-history text-blue-600 text-xl mr-3"></i>
                <span class="font-medium text-gray-800">View History</span>
            </a>
            <a href="/profile" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                <i class="fas fa-user-cog text-purple-600 text-xl mr-3"></i>
                <span class="font-medium text-gray-800">Edit Profile</span>
            </a>
        </div>
    </div>
    
    <!-- Recent Donations -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Recent Donations</h2>
                <a href="/donation-history" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        
        <?php if ($recentDonations->isEmpty()): ?>
            <div class="p-8 text-center">
                <i class="fas fa-gift text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 mb-4">You haven't made any donations yet.</p>
                <a href="/projects" class="inline-block bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">
                    Browse Projects
                </a>
            </div>
        <?php else: ?>
            <div class="divide-y divide-gray-200">
                <?php foreach ($recentDonations as $donation): ?>
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full <?= $donation->isCompleted() ? 'bg-green-100' : 'bg-yellow-100' ?> flex items-center justify-center mr-3">
                                <i class="fas <?= $donation->isCompleted() ? 'fa-check text-green-600' : 'fa-clock text-yellow-600' ?>"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">
                                    <?= $donation->project_id ? htmlspecialchars($donation->getProject()->title) : 'General Donation' ?>
                                </p>
                                <p class="text-sm text-gray-500"><?= date('M j, Y', strtotime($donation->created_at)) ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-emerald-600">$<?= number_format($donation->amount, 2) ?></p>
                            <span class="text-xs px-2 py-1 rounded-full <?= $donation->isCompleted() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                <?= htmlspecialchars($donation->getStatusLabel()) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/layouts/main.php';
