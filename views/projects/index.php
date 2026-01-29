<?php
/**
 * Projects Index View - Redesigned
 * NGO Donor Management System
 */

$title = 'Our Projects - Sombhabona';
?>

<!-- Page Header -->
<section class="hero-gradient text-white py-16">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Projects</h1>
        <p class="text-xl text-gray-200 max-w-2xl mx-auto">Discover the initiatives we're working on and find ways to contribute to causes that matter.</p>
    </div>
</section>

<!-- Wave divider -->
<div class="relative">
    <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 60L60 50C120 40 240 20 360 15C480 10 600 15 720 20C840 25 960 30 1080 30C1200 30 1320 25 1380 22.5L1440 20V60H1380C1320 60 1200 60 1080 60C960 60 840 60 720 60C600 60 480 60 360 60C240 60 120 60 60 60H0Z" fill="#f9fafb"/>
    </svg>
</div>

<!-- Projects Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Filter Tabs -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <a href="?status=all" class="px-6 py-2 rounded-full font-medium transition <?= ($status ?? 'active') === 'all' ? 'bg-ngo-primary text-white' : 'bg-white text-gray-700 hover:bg-ngo-primary hover:text-white' ?>">
                All Projects
            </a>
            <a href="?status=active" class="px-6 py-2 rounded-full font-medium transition <?= ($status ?? 'active') === 'active' ? 'bg-ngo-primary text-white' : 'bg-white text-gray-700 hover:bg-ngo-primary hover:text-white' ?>">
                Active
            </a>
            <a href="?status=completed" class="px-6 py-2 rounded-full font-medium transition <?= ($status ?? '') === 'completed' ? 'bg-ngo-primary text-white' : 'bg-white text-gray-700 hover:bg-ngo-primary hover:text-white' ?>">
                Completed
            </a>
            <a href="?status=draft" class="px-6 py-2 rounded-full font-medium transition <?= ($status ?? '') === 'draft' ? 'bg-ngo-primary text-white' : 'bg-white text-gray-700 hover:bg-ngo-primary hover:text-white' ?>">
                Upcoming
            </a>
        </div>

        <!-- Projects Grid -->
        <?php if ($projects->isEmpty()): ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-folder-open text-5xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No Projects Found</h3>
                <p class="text-gray-500">There are no projects matching your criteria at the moment.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($projects as $project): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                        <div class="h-48 bg-gradient-to-br from-ngo-primary to-ngo-dark relative overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-hands-helping text-6xl text-white opacity-30"></i>
                            </div>
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/90 text-ngo-dark px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    <?= htmlspecialchars(ucfirst($project->status)) ?>
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
                                    <span class="font-semibold text-ngo-primary">$<?= number_format($project->raised_amount) ?></span>
                                    <span>of $<?= number_format($project->target_amount) ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-ngo-primary to-ngo-secondary h-3 rounded-full transition-all duration-500" style="width: <?= min(100, $progress) ?>%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span><?= min(100, $progress) ?>% funded</span>
                                    <span><?= $project->donor_count ?? 0 ?> donors</span>
                                </div>
                            </div>
                            
                            <a href="/projects/<?= htmlspecialchars($project->slug) ?>" class="block w-full bg-ngo-primary text-white text-center py-3 rounded-lg font-medium hover:bg-ngo-dark transition">
                                <i class="fas fa-donate mr-2"></i>Donate to This Project
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="mt-12 flex justify-center">
                    <div class="flex gap-2">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1 ?>&status=<?= $status ?? 'active' ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-chevron-left mr-2"></i>Previous
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?= $i ?>&status=<?= $status ?? 'active' ?>" class="px-4 py-2 rounded-lg transition <?= $i === $currentPage ? 'bg-ngo-primary text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-ngo-primary hover:text-white' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1 ?>&status=<?= $status ?? 'active' ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                Next<i class="fas fa-chevron-right ml-2"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gray-800 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Don't See a Project That Speaks to You?</h2>
        <p class="text-gray-300 mb-8">You can still make a difference with a general donation that helps us support communities in need.</p>
        <a href="/register" class="btn-primary text-white px-8 py-4 rounded-lg font-bold text-lg hover:shadow-xl transition">
            <i class="fas fa-heart mr-2"></i>Make a General Donation
        </a>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/layouts/main.php';
