<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel - NGO Donor System' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white min-h-screen fixed left-0 top-0">
            <div class="p-6 border-b border-gray-700">
                <a href="/admin" class="flex items-center space-x-2">
                    <i class="fas fa-hand-holding-heart text-2xl text-emerald-400"></i>
                    <span class="text-xl font-bold">Admin Panel</span>
                </a>
            </div>
            
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="/admin" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition <?= strpos($_SERVER['REQUEST_URI'], '/admin') === strlen('/admin') || $_SERVER['REQUEST_URI'] === '/admin' ? 'bg-gray-700' : '' ?>">
                            <i class="fas fa-tachometer-alt w-6"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/donors" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition <?= strpos($_SERVER['REQUEST_URI'], '/admin/donors') !== false ? 'bg-gray-700' : '' ?>">
                            <i class="fas fa-users w-6"></i>
                            <span>Donors</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/donations" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition <?= strpos($_SERVER['REQUEST_URI'], '/admin/donations') !== false ? 'bg-gray-700' : '' ?>">
                            <i class="fas fa-gift w-6"></i>
                            <span>Donations</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/projects" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition <?= strpos($_SERVER['REQUEST_URI'], '/admin/projects') !== false ? 'bg-gray-700' : '' ?>">
                            <i class="fas fa-folder w-6"></i>
                            <span>Projects</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/emails" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition <?= strpos($_SERVER['REQUEST_URI'], '/admin/emails') !== false ? 'bg-gray-700' : '' ?>">
                            <i class="fas fa-envelope w-6"></i>
                            <span>Emails</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/payment-logs" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition <?= strpos($_SERVER['REQUEST_URI'], '/admin/payment-logs') !== false ? 'bg-gray-700' : '' ?>">
                            <i class="fas fa-list-alt w-6"></i>
                            <span>Payment Logs</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/settings" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition <?= strpos($_SERVER['REQUEST_URI'], '/admin/settings') !== false ? 'bg-gray-700' : '' ?>">
                            <i class="fas fa-cog w-6"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
                
                <div class="mt-8 pt-4 border-t border-gray-700">
                    <a href="/" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                        <i class="fas fa-external-link-alt w-6"></i>
                        <span>View Site</span>
                    </a>
                    <a href="/logout" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                        <i class="fas fa-sign-out-alt w-6"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="ml-64 flex-grow">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm py-4 px-6 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800"><?= $title ?? 'Dashboard' ?></h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">
                        <i class="fas fa-user-circle mr-1"></i>
                        <?= htmlspecialchars(Session::get('user_name')) ?>
                    </span>
                </div>
            </header>
            
            <!-- Flash Messages -->
            <?php if (Session::has('success')): ?>
                <div class="max-w-7xl mx-auto px-6 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline"><?= htmlspecialchars(Session::get('success')) ?></span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <i class="fas fa-times" onclick="this.parentElement.parentElement.style.display='none'"></i>
                        </span>
                    </div>
                </div>
                <?php Session::remove('success'); ?>
            <?php endif; ?>
            
            <?php if (Session::has('error')): ?>
                <div class="max-w-7xl mx-auto px-6 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline"><?= htmlspecialchars(Session::get('error')) ?></span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <i class="fas fa-times" onclick="this.parentElement.parentElement.style.display='none'"></i>
                        </span>
                    </div>
                </div>
                <?php Session::remove('error'); ?>
            <?php endif; ?>
            
            <!-- Page Content -->
            <div class="p-6">
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>
</body>
</html>
