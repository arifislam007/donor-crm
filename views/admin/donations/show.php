<?php
/**
 * Admin Donation Show View
 * NGO Donor Management System
 */

ob_start();
?>

<div class="mb-6">
    <a href="/admin/donations" class="text-emerald-600 hover:text-emerald-800 mb-2 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Donations
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Donation Details</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Donation Info -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Donation Information</h2>
        
        <div class="space-y-4">
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-500">Amount</span>
                <span class="font-bold text-2xl text-emerald-600">$<?= number_format($donation->amount, 2) ?></span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-500">Status</span>
                <span class="px-3 py-1 text-sm rounded-full <?= $donation->isCompleted() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                    <?= htmlspecialchars($donation->getStatusLabel()) ?>
                </span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-500">Date</span>
                <span class="font-medium"><?= formatDate($donation->created_at, 'F j, Y') ?></span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-500">Anonymous</span>
                <span class="font-medium"><?= $donation->anonymous_donation ? 'Yes' : 'No' ?></span>
            </div>
            <?php if ($donation->payment_method): ?>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-500">Payment Method</span>
                <span class="font-medium"><?= htmlspecialchars(ucfirst($donation->payment_method)) ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Update Status Form -->
        <form method="POST" action="/admin/donations/<?= $donation->id ?>/status" class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
            <div class="flex gap-3">
                <select name="status" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="pending" <?= $donation->payment_status === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="completed" <?= $donation->payment_status === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="failed" <?= $donation->payment_status === 'failed' ? 'selected' : '' ?>>Failed</option>
                    <option value="refunded" <?= $donation->payment_status === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                </select>
                <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
    
    <!-- Donor & Project Info -->
    <div class="space-y-6">
        <!-- Donor -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Donor Information</h2>
            <?php if ($donor): ?>
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mr-4">
                        <i class="fas fa-user text-emerald-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800"><?= htmlspecialchars($donor->name) ?></p>
                        <p class="text-gray-500"><?= htmlspecialchars($donor->email) ?></p>
                    </div>
                </div>
                <a href="/admin/donors/<?= $donor->id ?>" class="text-emerald-600 hover:text-emerald-800 text-sm">
                    <i class="fas fa-external-link-alt mr-1"></i> View Full Profile
                </a>
            <?php else: ?>
                <p class="text-gray-500">Anonymous donation - no donor information available.</p>
            <?php endif; ?>
        </div>
        
        <!-- Project -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Project</h2>
            <?php if ($project): ?>
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                        <i class="fas fa-folder text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800"><?= htmlspecialchars($project->title) ?></p>
                        <p class="text-gray-500">Target: <?= formatCurrency($project->target_amount) ?></p>
                    </div>
                </div>
                <a href="/projects/<?= htmlspecialchars($project->slug) ?>" class="text-emerald-600 hover:text-emerald-800 text-sm">
                    <i class="fas fa-external-link-alt mr-1"></i> View Project
                </a>
            <?php else: ?>
                <p class="text-gray-500">General donation (no specific project).</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Donation Details - Admin Panel';
require_once APP_PATH . '/views/layouts/admin.php';
