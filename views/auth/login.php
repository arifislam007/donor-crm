<?php
/**
 * Login View - Redesigned
 * NGO Donor Management System
 */

$title = 'Sign In - Sombhabona';
?>

<!-- Page Header -->
<section class="hero-gradient text-white py-12">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Welcome Back</h1>
        <p class="text-xl text-gray-200">Sign in to access your donor dashboard</p>
    </div>
</section>

<!-- Wave divider -->
<div class="relative">
    <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 40L60 33.3C120 26.7 240 13.3 360 10C480 6.67 600 13.3 720 20C840 26.7 960 33.3 1080 33.3C1200 33.3 1320 26.7 1380 23.3L1440 20V40H1380C1320 40 1200 40 1080 40C960 40 840 40 720 40C600 40 480 40 360 40C240 40 120 40 60 40H0Z" fill="#f9fafb"/>
    </svg>
</div>

<!-- Login Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-ngo-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-sign-in-alt text-white text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Sign In</h2>
                <p class="text-gray-500 mt-2">Enter your credentials to continue</p>
            </div>
            
            <form method="POST" action="/login">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="email" required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ngo-primary"
                            placeholder="you@example.com" value="<?= $this->old('email') ?>">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" id="password" required
                            class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ngo-primary"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="mr-2 w-4 h-4 text-ngo-primary rounded">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="/forgot-password" class="text-sm text-ngo-primary hover:underline">Forgot password?</a>
                </div>
                
                <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-bold text-lg hover:shadow-xl transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600">Don't have an account? 
                    <a href="/register" class="text-ngo-primary font-medium hover:underline">Register as a donor</a>
                </p>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-500 mb-4">Are you an admin?</p>
                <a href="/admin/login" class="text-ngo-primary font-medium hover:underline">
                    <i class="fas fa-user-shield mr-1"></i>Admin Login
                </a>
            </div>
        </div>
    </div>
</section>

<script>
function togglePassword() {
    const password = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/layouts/main.php';
