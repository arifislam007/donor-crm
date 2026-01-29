<?php
/**
 * Admin Create Project View
 * NGO Donor Management System
 */

ob_start();
?>

<div class="mb-6">
    <a href="/admin/projects" class="text-emerald-600 hover:text-emerald-800 mb-2 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Projects
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Create New Project</h1>
</div>

<div class="bg-white rounded-xl shadow-lg p-6">
    <form method="POST" action="/admin/projects">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Project Title *</label>
                <input type="text" name="title" required 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="Enter project title">
            </div>
            
            <!-- Short Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                <input type="text" name="short_description" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="Brief description (shown in project lists)">
            </div>
            
            <!-- Full Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Description</label>
                <textarea name="full_description" rows="5"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="Detailed project description"></textarea>
            </div>
            
            <!-- Target Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Amount ($)</label>
                <input type="number" name="target_amount" step="0.01" min="0"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="0.00">
            </div>
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
            
            <!-- Start Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            
            <!-- End Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="mt-6 flex justify-end gap-4">
            <a href="/admin/projects" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">
                Create Project
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
$title = 'Create Project - Admin Panel';
require_once APP_PATH . '/views/layouts/admin.php';
