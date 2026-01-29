<?php
/**
 * Admin Projects Index View
 * NGO Donor Management System
 */

ob_start();
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manage Projects</h1>
        <p class="text-gray-600">View and manage all projects</p>
    </div>
    <a href="/admin/projects/create" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
        <i class="fas fa-plus mr-2"></i> Create Project
    </a>
</div>

<!-- Projects Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raised</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if ($projects->isEmpty()): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-folder text-6xl text-gray-300 mb-4"></i>
                            <p>No projects found.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($projects as $project): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-folder text-purple-600"></i>
                                    </div>
                                    <div>
                                        <a href="/projects/<?= htmlspecialchars($project->slug) ?>" class="font-medium text-gray-800 hover:text-emerald-600">
                                            <?= htmlspecialchars($project->title) ?>
                                        </a>
                                        <p class="text-sm text-gray-500"><?= htmlspecialchars(substr($project->short_description, 0, 50)) ?>...</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">
                                <?= formatCurrency($project->target_amount) ?>
                            </td>
                            <td class="px-6 py-4 font-bold text-emerald-600">
                                <?= formatCurrency($project->raised_amount) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php $progress = $project->target_amount > 0 ? min(100, ($project->raised_amount / $project->target_amount) * 100) : 0; ?>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-emerald-600 h-2 rounded-full" style="width: <?= $progress ?>%"></div>
                                </div>
                                <span class="text-xs text-gray-500 mt-1"><?= number_format($progress, 0) ?>%</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    <?= $project->status === 'active' ? 'bg-green-100 text-green-800' : 
                                        ($project->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                        ($project->status === 'draft' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800')) ?>">
                                    <?= htmlspecialchars(ucfirst($project->status)) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="/admin/projects/<?= $project->id ?>/edit" class="text-blue-600 hover:text-blue-800 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="/projects/<?= htmlspecialchars($project->slug) ?>" class="text-emerald-600 hover:text-emerald-800">
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
$title = 'Projects - Admin Panel';
require_once APP_PATH . '/views/layouts/admin.php';
