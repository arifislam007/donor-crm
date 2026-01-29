<?php
/**
 * Admin Donations Index View
 * NGO Donor Management System
 */

ob_start();
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manage Donations</h1>
        <p class="text-gray-600">View and track all donations</p>
    </div>
</div>

<!-- Donations Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if ($donations->isEmpty()): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-gift text-6xl text-gray-300 mb-4"></i>
                            <p>No donations found.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($donations as $donation): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-gray-500 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">
                                            <?= $donation->anonymous_donation ? 'Anonymous' : htmlspecialchars($donation->getDonor()->name) ?>
                                        </p>
                                        <p class="text-sm text-gray-500"><?= $donation->anonymous_donation ? '' : $donation->getDonor()->email ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php $project = $donation->getProject(); ?>
                                <?php if ($project): ?>
                                    <a href="/projects/<?= htmlspecialchars($project->slug) ?>" class="text-emerald-600 hover:underline">
                                        <?= htmlspecialchars($project->title) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-500">General</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-emerald-600">
                                <?= formatCurrency($donation->amount) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                <?= formatDate($donation->created_at) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full <?= $donation->isCompleted() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                    <?= htmlspecialchars($donation->getStatusLabel()) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="/admin/donations/<?= $donation->id ?>" class="text-emerald-600 hover:text-emerald-800">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-600">
                    Page <?= $currentPage ?> of <?= $totalPages ?>
                </p>
                <div class="flex gap-2">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?= $currentPage - 1 ?>" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                            Previous
                        </a>
                    <?php endif; ?>
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?= $currentPage + 1 ?>" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                            Next
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
$title = 'Donations - Admin Panel';
require_once APP_PATH . '/views/layouts/admin.php';
