<?php
/**
 * Register View - Redesigned
 * NGO Donor Management System
 */

$title = 'Register as a Donor - Sombhabona';
?>

<!-- Page Header -->
<section class="hero-gradient text-white py-12">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Become a Donor</h1>
        <p class="text-xl text-gray-200">Join our community and make a difference</p>
    </div>
</section>

<!-- Wave divider -->
<div class="relative">
    <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 40L60 33.3C120 26.7 240 13.3 360 10C480 6.67 600 13.3 720 20C840 26.7 960 33.3 1080 33.3C1200 33.3 1320 26.7 1380 23.3L1440 20V40H1380C1320 40 1200 40 1080 40C960 40 840 40 720 40C600 40 480 40 360 40C240 40 120 40 60 40H0Z" fill="#f9fafb"/>
    </svg>
</div>

<!-- Register Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-lg mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-ngo-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-plus text-ngo-dark text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Create Your Account</h2>
                <p class="text-gray-500 mt-2">Start your journey as a donor today</p>
            </div>
            
            <form method="POST" action="/register">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="name" required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ngo-primary"
                            placeholder="John Doe" value="<?= $this->old('name') ?>">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-phone"></i>
                        </span>
                        <input type="tel" name="phone"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ngo-primary"
                            placeholder="+880 1XXXXXXXXX" value="<?= $this->old('phone') ?>">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" id="password" required minlength="6"
                            class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ngo-primary"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password_confirmation" id="passwordConfirm" required
                            class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ngo-primary"
                            placeholder="••••••••">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" name="terms" required class="mr-3 mt-1 w-4 h-4 text-ngo-primary rounded">
                        <span class="text-sm text-gray-600">
                            I agree to the <a href="#" class="text-ngo-primary hover:underline">Terms of Service</a> 
                            and <a href="#" class="text-ngo-primary hover:underline">Privacy Policy</a>
                        </span>
                    </label>
                </div>
                
                <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-bold text-lg hover:shadow-xl transition">
                    <i class="fas fa-user-plus mr-2"></i>Create Account
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600">Already have an account? 
                    <a href="/login" class="text-ngo-primary font-medium hover:underline">Sign in</a>
                </p>
            </div>
        </div>
        
        <!-- Benefits -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h3 class="font-bold text-gray-800 mb-4 text-center">Why Register?</h3>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Track Your Donations</p>
                        <p class="text-sm text-gray-500">View your complete donation history anytime</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Receive Updates</p>
                        <p class="text-sm text-gray-500">Get news about projects you support</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Tax Benefits</p>
                        <p class="text-sm text-gray-500">Receive receipts for tax deductions</p>
                    </div>
                </div>
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
