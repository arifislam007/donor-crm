<?php
/**
 * Project Show View - Redesigned
 * NGO Donor Management System
 */

$title = htmlspecialchars($project->title) . ' - Sombhabona';
?>

<!-- Page Header -->
<section class="hero-gradient text-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <a href="/projects" class="inline-flex items-center text-gray-200 hover:text-white mb-4 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Projects
        </a>
        <h1 class="text-3xl md:text-4xl font-bold mb-2"><?= htmlspecialchars($project->title) ?></h1>
        <div class="flex flex-wrap items-center gap-4 text-gray-200">
            <span class="bg-white/20 px-3 py-1 rounded-full text-sm">
                <i class="fas fa-tag mr-1"></i><?= htmlspecialchars(ucfirst($project->status)) ?>
            </span>
            <span><i class="fas fa-calendar mr-1"></i> Started: <?= date('M j, Y', strtotime($project->created_at)) ?></span>
            <?php if ($project->end_date): ?>
                <span><i class="fas fa-calendar-check mr-1"></i> Ends: <?= date('M j, Y', strtotime($project->end_date)) ?></span>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Wave divider -->
<div class="relative">
    <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 40L60 33.3C120 26.7 240 13.3 360 10C480 6.67 600 13.3 720 20C840 26.7 960 33.3 1080 33.3C1200 33.3 1320 26.7 1380 23.3L1440 20V40H1380C1320 40 1200 40 1080 40C960 40 840 40 720 40C600 40 480 40 360 40C240 40 120 40 60 40H0Z" fill="#f9fafb"/>
    </svg>
</div>

<!-- Project Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Project Image -->
                <div class="bg-gradient-to-br from-ngo-primary to-ngo-dark rounded-xl h-64 flex items-center justify-center mb-8">
                    <i class="fas fa-hands-helping text-8xl text-white opacity-30"></i>
                </div>
                
                <!-- Description -->
                <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">About This Project</h2>
                    <div class="prose prose-lg text-gray-600">
                        <?= nl2br(htmlspecialchars($project->full_description ?: $project->short_description)) ?>
                    </div>
                </div>
                
                <!-- Recent Donations -->
                <?php if (!$recentDonations->isEmpty()): ?>
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Recent Donations</h2>
                    <div class="space-y-4">
                        <?php foreach ($recentDonations as $donation): ?>
                            <div class="flex items-center justify-between py-4 border-b border-gray-100 last:border-0">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-ngo-primary/10 flex items-center justify-center mr-4">
                                        <i class="fas fa-user text-ngo-primary"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">
                                            <?= $donation->anonymous_donation ? 'Anonymous' : htmlspecialchars($donation->getDonor()->name) ?>
                                        </p>
                                        <p class="text-sm text-gray-500"><?= formatDate($donation->created_at) ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-ngo-primary text-lg"><?= formatCurrency($donation->amount) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Donation Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Support This Project</h3>
                    
                    <!-- Progress -->
                    <div class="mb-6">
                        <?php $progress = $project->getProgressPercentage(); ?>
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span class="font-semibold text-ngo-primary text-lg">$<?= number_format($project->raised_amount) ?></span>
                            <span>of $<?= number_format($project->target_amount) ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-gradient-to-r from-ngo-primary to-ngo-secondary h-4 rounded-full transition-all duration-500" style="width: <?= min(100, $progress) ?>%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                            <span><?= min(100, $progress) ?>% funded</span>
                            <span><?= $project->donor_count ?? 0 ?> donors</span>
                        </div>
                    </div>
                    
                    <!-- Quick Amounts -->
                    <form action="/donate" method="POST" class="mb-4">
                        <input type="hidden" name="project_id" value="<?= $project->id ?>">
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <button type="button" onclick="setAmount(500)" class="amount-btn py-2 border-2 border-gray-200 rounded-lg font-medium hover:border-ngo-primary hover:text-ngo-primary transition" data-amount="500">৳500</button>
                            <button type="button" onclick="setAmount(1000)" class="amount-btn py-2 border-2 border-gray-200 rounded-lg font-medium hover:border-ngo-primary hover:text-ngo-primary transition" data-amount="1000">৳1000</button>
                            <button type="button" onclick="setAmount(2000)" class="amount-btn py-2 border-2 border-gray-200 rounded-lg font-medium hover:border-ngo-primary hover:text-ngo-primary transition" data-amount="2000">৳2000</button>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Custom Amount</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">৳</span>
                                <input type="number" name="amount" id="customAmount" min="1" step="0.01" required
                                    class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ngo-primary"
                                    placeholder="Enter amount">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="anonymous" class="mr-2 w-4 h-4 text-ngo-primary rounded">
                                <span class="text-sm text-gray-600">Make this donation anonymous</span>
                            </label>
                        </div>
                        <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-bold text-lg hover:shadow-xl transition">
                            <i class="fas fa-donate mr-2"></i>Donate Now
                        </button>
                    </form>
                    
                    <div class="text-center text-sm text-gray-500">
                        <p><i class="fas fa-lock mr-1"></i>Secure payment via Stripe</p>
                    </div>
                </div>
                
                <!-- Share -->
                <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                    <h4 class="font-bold text-gray-800 mb-4">Share This Project</h4>
                    <div class="flex justify-center gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?= urlencode('Help support this project: ' . $project->title) ?>&url=<?= urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" class="w-10 h-10 bg-blue-400 text-white rounded-full flex items-center justify-center hover:bg-blue-500 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text=<?= urlencode('Check out this project: ' . $project->title . ' - ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function setAmount(amount) {
    document.getElementById('customAmount').value = amount;
    // Remove active class from all buttons
    document.querySelectorAll('.amount-btn').forEach(btn => {
        btn.classList.remove('border-ngo-primary', 'text-ngo-primary');
        btn.classList.add('border-gray-200');
    });
    // Add active class to clicked button
    event.target.classList.remove('border-gray-200');
    event.target.classList.add('border-ngo-primary', 'text-ngo-primary');
}
</script>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/layouts/main.php';
