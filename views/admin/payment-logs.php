<?php
/**
 * Admin Payment Logs View
 * NGO Donor Management System
 */

ob_start();
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="/admin" class="text-emerald-600 hover:text-emerald-800 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Payment Logs</h1>
        <p class="text-gray-600">View all payment gateway transactions</p>
    </div>
    <a href="/admin/settings" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
        <i class="fas fa-cog mr-2"></i> Gateway Settings
    </a>
</div>

<!-- Payment Logs Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gateway</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if ($paymentLogs->isEmpty()): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-list-alt text-6xl text-gray-300 mb-4"></i>
                            <p>No payment logs found.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($paymentLogs as $log): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                <?= $log->id ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium text-gray-800"><?= $log->getGatewayLabel() ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($log->is_sandbox): ?>
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Sandbox</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Live</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-mono text-sm">
                                <?= htmlspecialchars($log->transaction_id ?: '-') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    <?= $log->status === 'success' ? 'bg-green-100 text-green-800' : 
                                        ($log->status === 'failed' ? 'bg-red-100 text-red-800' : 
                                        ($log->status === 'cancelled' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800')) ?>">
                                    <?= htmlspecialchars($log->getStatusLabel()) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                <?= formatDate($log->created_at) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button onclick="viewLogDetails(<?= $log->id ?>)" class="text-emerald-600 hover:text-emerald-800 mr-3">
                                    <i class="fas fa-eye"></i> View
                                </button>
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

<!-- Log Details Modal -->
<div id="logModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full mx-4 max-h-[80vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Payment Log Details</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6" id="logDetails">
            <!-- Details will be loaded here -->
        </div>
    </div>
</div>

<script>
function viewLogDetails(logId) {
    // Fetch log details via AJAX or include data in page
    const modal = document.getElementById('logModal');
    const details = document.getElementById('logDetails');
    
    // For now, show loading
    details.innerHTML = '<p class="text-center text-gray-500">Loading...</p>';
    modal.classList.remove('hidden');
    
    // You can implement AJAX fetch here or render details server-side
    // For simplicity, we'll just show a placeholder
    details.innerHTML = `
        <div class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-4xl text-emerald-600 mb-4"></i>
            <p class="text-gray-500">Loading payment log details...</p>
        </div>
    `;
}

function closeModal() {
    document.getElementById('logModal').classList.add('hidden');
}

// Close modal on outside click
document.getElementById('logModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/layouts/admin.php';
