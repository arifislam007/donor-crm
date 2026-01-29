<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sombhabona - Making a Difference' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ngo-primary': '#1a5f2a',
                        'ngo-secondary': '#f59e0b',
                        'ngo-dark': '#0f3d1f',
                    }
                }
            }
        }
    </script>
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #0f3d1f 0%, #1a5f2a 50%, #2d7a3d 100%);
        }
        .btn-primary {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col font-sans">
    <!-- Top Bar -->
    <div class="bg-ngo-dark text-white py-2 text-sm">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span><i class="fas fa-envelope mr-2"></i>info@sombhabona.org</span>
                <span><i class="fas fa-phone mr-2"></i>+880 2 55050011</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="#" class="hover:text-ngo-secondary transition"><i class="fab fa-facebook"></i></a>
                <a href="#" class="hover:text-ngo-secondary transition"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-ngo-secondary transition"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-ngo-primary rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-white text-lg"></i>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-ngo-primary">Sombhabona</span>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Together We Can</p>
                    </div>
                </a>
                
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="/" class="text-gray-700 hover:text-ngo-primary font-medium transition <?= $_SERVER['REQUEST_URI'] === '/' ? 'text-ngo-primary' : '' ?>">Home</a>
                    <a href="/projects" class="text-gray-700 hover:text-ngo-primary font-medium transition <?= strpos($_SERVER['REQUEST_URI'], '/projects') !== false ? 'text-ngo-primary' : '' ?>">Our Projects</a>
                    <a href="/about" class="text-gray-700 hover:text-ngo-primary font-medium transition <?= $_SERVER['REQUEST_URI'] === '/about' ? 'text-ngo-primary' : '' ?>">About Us</a>
                    <a href="/contact" class="text-gray-700 hover:text-ngo-primary font-medium transition <?= $_SERVER['REQUEST_URI'] === '/contact' ? 'text-ngo-primary' : '' ?>">Contact</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <?php if (Session::isLoggedIn()): ?>
                        <a href="/dashboard" class="text-gray-700 hover:text-ngo-primary font-medium transition">
                            <i class="fas fa-user-circle mr-1"></i>
                            <?= htmlspecialchars(Session::get('user_name')) ?>
                        </a>
                        <a href="/logout" class="btn-primary text-white px-5 py-2 rounded-lg font-medium hover:shadow-lg transition">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="/login" class="text-gray-700 hover:text-ngo-primary font-medium transition">Login</a>
                        <a href="/register" class="btn-primary text-white px-5 py-2 rounded-lg font-medium hover:shadow-lg transition">
                            <i class="fas fa-user-plus mr-2"></i>Donor Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (Session::has('success')): ?>
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars(Session::get('success')) ?></span>
            </div>
        </div>
        <?php Session::remove('success'); ?>
    <?php endif; ?>
    
    <?php if (Session::has('error')): ?>
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars(Session::get('error')) ?></span>
            </div>
        </div>
        <?php Session::remove('error'); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="flex-grow">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-ngo-dark text-white">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-ngo-secondary rounded-full flex items-center justify-center">
                            <i class="fas fa-heart text-ngo-dark text-lg"></i>
                        </div>
                        <div>
                            <span class="text-xl font-bold">Sombhabona</span>
                            <p class="text-xs text-gray-400">Together We Can</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        We are dedicated to creating positive change in communities through sustainable development, education, and humanitarian support.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-bold mb-4 text-ngo-secondary">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="/projects" class="text-gray-400 hover:text-white transition text-sm"><i class="fas fa-chevron-right mr-2 text-xs"></i>Our Projects</a></li>
                        <li><a href="/about" class="text-gray-400 hover:text-white transition text-sm"><i class="fas fa-chevron-right mr-2 text-xs"></i>About Us</a></li>
                        <li><a href="/contact" class="text-gray-400 hover:text-white transition text-sm"><i class="fas fa-chevron-right mr-2 text-xs"></i>Contact</a></li>
                        <li><a href="/register" class="text-gray-400 hover:text-white transition text-sm"><i class="fas fa-chevron-right mr-2 text-xs"></i>Become a Donor</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-bold mb-4 text-ngo-secondary">Contact Us</h4>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-ngo-secondary"></i>
                            <span>House #15, Road #10, Sector #6, Uttara, Dhaka-1230</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3 text-ngo-secondary"></i>
                            <span>+880 2 55050011</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-ngo-secondary"></i>
                            <span>info@sombhabona.org</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Newsletter -->
                <div>
                    <h4 class="text-lg font-bold mb-4 text-ngo-secondary">Newsletter</h4>
                    <p class="text-gray-400 text-sm mb-4">Subscribe to receive updates about our work.</p>
                    <form class="flex">
                        <input type="email" placeholder="Your email" class="flex-1 px-4 py-2 rounded-l-lg text-gray-800 focus:outline-none">
                        <button type="submit" class="btn-primary px-4 py-2 rounded-r-lg">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400 text-sm">&copy; <?= date('Y') ?> Sombhabona. All rights reserved. | A non-profit organization</p>
            </div>
        </div>
    </footer>
</body>
</html>
