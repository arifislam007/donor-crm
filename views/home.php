<?php
/**
 * Home Page View - Redesigned
 * NGO Donor Management System
 */

$title = 'Sombhabona - Together We Can Make a Difference';
?>

<!-- Hero Section -->
<section class="hero-gradient text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 py-24 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    Together We Can <span class="text-ngo-secondary">Make a Difference</span>
                </h1>
                <p class="text-xl text-gray-200 mb-8 leading-relaxed">
                    Join our community of donors and help create lasting change in the lives of those who need it most. Every contribution counts.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/projects" class="btn-primary text-white px-8 py-4 rounded-lg font-bold text-lg text-center hover:shadow-xl transition">
                        <i class="fas fa-search mr-2"></i>Browse Projects
                    </a>
                    <a href="/register" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg text-center hover:bg-white hover:text-ngo-primary transition">
                        <i class="fas fa-heart mr-2"></i>Become a Donor
                    </a>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8">
                    <div class="grid grid-cols-2 gap-6 text-center">
                        <div class="bg-white/20 rounded-xl p-6">
                            <div class="text-4xl font-bold text-ngo-secondary mb-2"><?= formatCurrency($totalDonations) ?></div>
                            <div class="text-sm text-gray-200">Total Raised</div>
                        </div>
                        <div class="bg-white/20 rounded-xl p-6">
                            <div class="text-4xl font-bold text-ngo-secondary mb-2"><?= number_format($donorCount) ?></div>
                            <div class="text-sm text-gray-200">Active Donors</div>
                        </div>
                        <div class="bg-white/20 rounded-xl p-6">
                            <div class="text-4xl font-bold text-ngo-secondary mb-2"><?= number_format($projectCount) ?></div>
                            <div class="text-sm text-gray-200">Projects</div>
                        </div>
                        <div class="bg-white/20 rounded-xl p-6">
                            <div class="text-4xl font-bold text-ngo-secondary mb-2"><?= number_format(\Donation::getDonorCount()) ?></div>
                            <div class="text-sm text-gray-200">Lives Impacted</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Wave divider -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f9fafb"/>
        </svg>
    </div>
</section>

<!-- Stats Bar -->
<section class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-3xl font-bold text-ngo-primary"><?= formatCurrency($totalDonations) ?></div>
                <div class="text-gray-600 text-sm">Total Donations</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-ngo-primary"><?= number_format($donorCount) ?></div>
                <div class="text-gray-600 text-sm">Active Donors</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-ngo-primary"><?= number_format($projectCount) ?></div>
                <div class="text-gray-600 text-sm">Projects</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-ngo-primary">100%</div>
                <div class="text-gray-600 text-sm">Impact</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Projects -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Our Projects</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Discover our ongoing initiatives and see how your donation can create real change in communities.</p>
        </div>
        
        <?php if ($featuredProjects->isEmpty()): ?>
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-folder-open text-4xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-lg">No projects available at the moment.</p>
                <p class="text-gray-400 text-sm mt-2">Check back soon for new initiatives.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($featuredProjects as $project): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                        <div class="h-48 bg-gradient-to-br from-ngo-primary to-ngo-dark relative overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-hands-helping text-6xl text-white opacity-30"></i>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="bg-ngo-secondary text-ngo-dark px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    <?= htmlspecialchars($project->status) ?>
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-ngo-primary transition">
                                <?= htmlspecialchars($project->title) ?>
                            </h3>
                            <p class="text-gray-600 mb-4 text-sm line-clamp-3">
                                <?= htmlspecialchars($project->short_description) ?>
                            </p>
                            
                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <?php $progress = $project->getProgressPercentage(); ?>
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span class="font-semibold text-ngo-primary"><?= formatCurrency($project->raised_amount) ?></span>
                                    <span>of <?= formatCurrency($project->target_amount) ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-ngo-primary to-ngo-secondary h-3 rounded-full transition-all duration-500" style="width: <?= min(100, $progress) ?>%"></div>
                                </div>
                                <div class="text-right text-xs text-gray-500 mt-1"><?= min(100, $progress) ?>% funded</div>
                            </div>
                            
                            <a href="/projects/<?= htmlspecialchars($project->slug) ?>" class="block w-full bg-ngo-primary text-white text-center py-3 rounded-lg font-medium hover:bg-ngo-dark transition">
                                <i class="fas fa-donate mr-2"></i>Donate Now
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-10">
                <a href="/projects" class="inline-flex items-center bg-gray-800 text-white px-8 py-3 rounded-lg font-medium hover:bg-gray-700 transition">
                    View All Projects <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- What We Do Section -->
<section class="py-16 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">What We Do</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">We work across multiple sectors to create sustainable impact in communities.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-graduation-cap text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Education</h3>
                <p class="text-gray-600 text-sm">Providing quality education and learning opportunities for children and adults in underserved communities.</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heartbeat text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Healthcare</h3>
                <p class="text-gray-600 text-sm">Ensuring access to essential healthcare services and promoting wellness in rural areas.</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-leaf text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Environment</h3>
                <p class="text-gray-600 text-sm">Implementing sustainable development projects and environmental conservation initiatives.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-ngo-primary text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Make an Impact?</h2>
        <p class="text-xl text-gray-200 mb-8">Your generosity can change lives. Join thousands of donors who are making a difference every day.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="/register" class="btn-primary text-white px-8 py-4 rounded-lg font-bold text-lg hover:shadow-xl transition">
                <i class="fas fa-heart mr-2"></i>Donate Now
            </a>
            <a href="/contact" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-ngo-primary transition">
                <i class="fas fa-envelope mr-2"></i>Contact Us
            </a>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/layouts/main.php';
