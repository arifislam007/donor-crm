<?php
/**
 * Admin Settings View - Payment Gateway Configuration
 * NGO Donor Management System
 */

ob_start();
?>

<div class="mb-6">
    <a href="/admin" class="text-emerald-600 hover:text-emerald-800 mb-2 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Payment Gateway Settings</h1>
    <p class="text-gray-600">Configure your payment gateway credentials and sandbox mode</p>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<form method="POST" action="/admin/settings" class="space-y-6">
    <!-- Payment Mode -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Payment Mode</h2>
        <div class="flex gap-4">
            <label class="flex items-center">
                <input type="radio" name="payment_mode" value="sandbox" <?= ($settings['mode'] ?? 'sandbox') === 'sandbox' ? 'checked' : '' ?> class="mr-2">
                <span class="font-medium">Sandbox (Testing)</span>
            </label>
            <label class="flex items-center">
                <input type="radio" name="payment_mode" value="live" <?= ($settings['mode'] ?? '') === 'live' ? 'checked' : '' ?> class="mr-2">
                <span class="font-medium">Live (Production)</span>
            </label>
        </div>
        <p class="text-sm text-gray-500 mt-2">Use sandbox mode for testing with dummy transactions.</p>
    </div>

    <!-- SSLCommerz -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">SSLCommerz</h2>
            <label class="flex items-center">
                <input type="checkbox" name="sslcommerz_sandbox" <?= ($settings['sslcommerz']['sandbox'] ?? true) ? 'checked' : '' ?> class="mr-2 w-4 h-4 text-emerald-600 rounded">
                <span class="text-sm">Sandbox Mode</span>
            </label>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Store ID</label>
                <input type="text" name="sslcommerz_store_id" value="<?= htmlspecialchars($settings['sslcommerz']['store_id'] ?? '') ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="your_store_id">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Store Password</label>
                <input type="password" name="sslcommerz_store_password" value="<?= htmlspecialchars($settings['sslcommerz']['store_password'] ?? '') ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="your_store_password">
            </div>
        </div>
    </div>

    <!-- Nagad -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">Nagad</h2>
            <label class="flex items-center">
                <input type="checkbox" name="nagad_sandbox" <?= ($settings['nagad']['sandbox'] ?? true) ? 'checked' : '' ?> class="mr-2 w-4 h-4 text-emerald-600 rounded">
                <span class="text-sm">Sandbox Mode</span>
            </label>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Merchant ID</label>
                <input type="text" name="nagad_merchant_id" value="<?= htmlspecialchars($settings['nagad']['merchant_id'] ?? '') ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="your_merchant_id">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Merchant Number</label>
                <input type="text" name="nagad_merchant_number" value="<?= htmlspecialchars($settings['nagad']['merchant_number'] ?? '') ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="01XXXXXXXXX">
            </div>
        </div>
    </div>

    <!-- Bkash -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">Bkash</h2>
            <label class="flex items-center">
                <input type="checkbox" name="bkash_sandbox" <?= ($settings['bkash']['sandbox'] ?? true) ? 'checked' : '' ?> class="mr-2 w-4 h-4 text-emerald-600 rounded">
                <span class="text-sm">Sandbox Mode</span>
            </label>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">App Key</label>
                <input type="text" name="bkash_app_key" value="<?= htmlspecialchars($settings['bkash']['app_key'] ?? '') ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="your_app_key">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">App Secret</label>
                <input type="password" name="bkash_app_secret" value="<?= htmlspecialchars($settings['bkash']['app_secret'] ?? '') ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="your_app_secret">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text" name="bkash_username" value="<?= htmlspecialchars($settings['bkash']['username'] ?? '') ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="your_username">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="bkash_password" value="<?= htmlspecialchars($settings['bkash']['password'] ?? '') ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="your_password">
            </div>
        </div>
    </div>

    <!-- Rocket -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">Rocket (DBBL)</h2>
            <label class="flex items-center">
                <input type="checkbox" name="rocket_sandbox" <?= ($settings['rocket']['sandbox'] ?? true) ? 'checked' : '' ?> class="mr-2 w-4 h-4 text-emerald-600 rounded">
                <span class="text-sm">Sandbox Mode</span>
            </label>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Merchant ID</label>
                <input type="text" name="rocket_merchant_id" value="<?= htmlspecialchars($settings['rocket']['merchant_id'] ?? '') ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="your_merchant_id">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Merchant Number</label>
                <input type="text" name="rocket_merchant_number" value="<?= htmlspecialchars($settings['rocket']['merchant_number'] ?? '') ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="01XXXXXXXXX">
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end">
        <button type="submit" class="bg-emerald-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-emerald-700 transition">
            <i class="fas fa-save mr-2"></i> Save Settings
        </button>
    </div>
</form>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/layouts/admin.php';
