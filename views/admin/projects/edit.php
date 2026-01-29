<?php
/**
 * Admin Edit Project View
 * NGO Donor Management System
 */

ob_start();
?>

<div class="mb-6">
    <a href="/admin/projects" class="text-emerald-600 hover:text-emerald-800 mb-2 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Projects
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Edit Project</h1>
</div>

<div class="bg-white rounded-xl shadow-lg p-6">
    <form method="POST" action="/admin/projects/<?= $project->id ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Project Title *</label>
                <input type="text" name="title" required 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    value="<?= htmlspecialchars($project->title) ?>">
            </div>
            
            <!-- Short Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                <input type="text" name="short_description" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    value="<?= htmlspecialchars($project->short_description) ?>">
            </div>
            
            <!-- Full Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Description</label>
                <textarea name="full_description" rows="5"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"><?= htmlspecialchars($project->full_description) ?></textarea>
            </div>
            
            <!-- Target Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Amount ($)</label>
                <input type="number" name="target_amount" step="0.01" min="0"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    value="<?= $project->target_amount ?>">
            </div>
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="draft" <?= $project->status === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="active" <?= $project->status === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="completed" <?= $project->status === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="archived" <?= $project->status === 'archived' ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>
            
            <!-- Start Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    value="<?= $project->start_date ?>">
            </div>
            
            <!-- End Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    value="<?= $project->end_date ?>">
            </div>
        </div>
        
        <!-- Project Stats -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-medium text-gray-800 mb-2">Current Statistics</h3>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-2xl font-bold text-emerald-600">$<?= number_format($project->raised_amount, 2) ?></p>
                    <p class="text-sm text-gray-500">Raised</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= number_format($project->donor_count) ?></p>
                    <p class="text-sm text-gray-500">Donors</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= number_format($project->donation_count) ?></p>
                    <p class="text-sm text-gray-500">Donations</p>
                </div>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="mt-6 flex justify-end gap-4">
            <a href="/admin/projects" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">
                Save Changes
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
$title = 'Edit Project - Admin Panel';
require_once APP_PATH . '/views/layouts/admin.php';
