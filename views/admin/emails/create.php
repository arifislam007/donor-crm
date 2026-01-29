<?php
/**
 * Admin Send Email View
 * NGO Donor Management System
 */

ob_start();
?>

<div class="mb-6">
    <a href="/admin/emails" class="text-emerald-600 hover:text-emerald-800 mb-2 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Email Logs
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Send Email to Donors</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Email Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <form method="POST" action="/admin/emails/send">
                <!-- Email Type -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Type</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="email_type" value="custom" checked class="mr-3">
                            <div>
                                <p class="font-medium text-gray-800">Custom Email</p>
                                <p class="text-sm text-gray-500">Write your own message</p>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Recipient -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Send To</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="recipient_type" value="all" checked class="mr-3">
                            <div>
                                <p class="font-medium text-gray-800">All Active Donors</p>
                                <p class="text-sm text-gray-500"><?= count($donors) ?> donors</p>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Show donor selection if donors exist -->
                <?php if (!$donors->isEmpty()): ?>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Or Select Specific Donor</label>
                    <select name="recipient_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- Select Donor --</option>
                        <?php foreach ($donors as $donor): ?>
                            <option value="<?= $donor->id ?>"><?= htmlspecialchars($donor->name) ?> (<?= htmlspecialchars($donor->email) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <!-- Subject -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" name="subject" required 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        placeholder="Enter email subject">
                </div>
                
                <!-- Message -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" rows="8" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        placeholder="Write your message here..."></textarea>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end gap-4">
                    <a href="/admin/emails" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">
                        <i class="fas fa-paper-plane mr-2"></i> Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Info Sidebar -->
    <div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="font-bold text-gray-800 mb-4">Email Guidelines</h3>
            <ul class="space-y-3 text-sm text-gray-600">
                <li class="flex items-start">
                    <i class="fas fa-check text-emerald-500 mt-1 mr-2"></i>
                    Keep subject lines clear and concise
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-emerald-500 mt-1 mr-2"></i>
                    Personalize messages when possible
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-emerald-500 mt-1 mr-2"></i>
                    Include calls to action
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-emerald-500 mt-1 mr-2"></i>
                    Test emails before mass sending
                </li>
            </ul>
            
            <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Emails are sent via SendGrid. Check the email logs for delivery status.
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Send Email - Admin Panel';
require_once APP_PATH . '/views/layouts/admin.php';
