<?php
/**
 * Admin Donor Show View
 * NGO Donor Management System
 */

ob_start();
?>

<div class="mb-6">
    <a href="/admin/donors" class="text-emerald-600 hover:text-emerald-800 mb-2 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Donors
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Donor Details</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Donor Info -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="text-center mb-6">
                <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user text-emerald-600 text-3xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($donor->name) ?></h2>
                <p class="text-gray-500"><?= htmlspecialchars($donor->email) ?></p>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-500">Phone</span>
                    <span class="font-medium"><?= htmlspecialchars($donor->phone ?: '-') ?></span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-500">Status</span>
                    <span class="px-2 py-1 text-xs rounded-full <?= $donor->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                        <?= htmlspecialchars(ucfirst($donor->status)) ?>
                    </span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-500">Joined</span>
                    <span class="font-medium"><?= formatDate($donor->created_at) ?></span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">Total Donated</span>
                    <span class="font-bold text-emerald-600">$<?= number_format($totalDonated, 2) ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Donor Donations -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Donation History</h2>
            </div>
            
            <?php if ($donations->isEmpty()): ?>
                <div class="p-12 text-center">
                    <i class="fas fa-gift text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No donations yet.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($donations as $donation): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <?php $project = $donation->getProject(); ?>
                                        <?php if ($project): ?>
                                            <a href="/projects/<?= htmlspecialchars($project->slug) ?>" class="text-emerald-600 hover:underline">
                                                <?= htmlspecialchars($project->title) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-500">General</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-emerald-600">
                                        $<?= number_format($donation->amount, 2) ?>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <?= formatDate($donation->created_at) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full <?= $donation->isCompleted() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                            <?= htmlspecialchars($donation->getStatusLabel()) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Donor Details - Admin Panel';
require_once APP_PATH . '/views/layouts/admin.php';
