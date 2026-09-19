<?php
// includes/header.php
require_once __DIR__ . '/../config/helpers.php';
$cartCount = getCartCount();
$currentScript = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - MBC Malek Bateta Center' : 'MBC (Malek Bateta Center) - Traditional Taste of Dhoraji'; ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="<?php echo baseUrl('assets/css/style.css'); ?>">
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen flex flex-col bg-[#FFFBF4] text-[#2A1712]">

    <!-- Top Announcement Bar -->
    <div class="bg-[#3A1812] text-[#F3EBDD] text-xs py-2 px-4 border-b border-[#52251B]">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-2">
            <div class="flex items-center gap-2">
                <span class="bg-[#D97706] text-white px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">Est. 1983</span>
                <span>Traditional Taste of Dhoraji Since 1983</span>
            </div>
            <div class="flex items-center gap-6">
                <span><i class="fa-solid fa-phone text-[#D97706] mr-1"></i> +91 98765 43210</span>
                <span><i class="fa-solid fa-clock text-[#D97706] mr-1"></i> 10:00 AM - 10:00 PM</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="bg-[#FAF4EB] border-b border-[#E8DCCB] sticky top-0 z-40 shadow-sm" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <a href="<?php echo baseUrl('index.php'); ?>" class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-[#3A1812] rounded-full flex items-center justify-center border-2 border-[#D97706] shadow-md">
                        <i class="fa-solid fa-[#D97706] fa-bowl-food text-2xl text-[#D97706]"></i>
                    </div>
                    <div>
                        <span class="block font-serif-heading font-extrabold text-2xl tracking-tight text-[#3A1812]">MBC</span>
                        <span class="block text-[10px] font-extrabold uppercase tracking-widest text-[#D97706]">Malek Bateta Center</span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center space-x-8 font-semibold text-sm">
                    <a href="<?php echo baseUrl('index.php'); ?>" class="<?php echo $currentScript == 'index.php' ? 'text-[#D97706] font-bold border-b-2 border-[#D97706] pb-1' : 'text-[#3A1812] hover:text-[#D97706] transition'; ?>">Home</a>
                    <a href="<?php echo baseUrl('menu.php'); ?>" class="<?php echo $currentScript == 'menu.php' ? 'text-[#D97706] font-bold border-b-2 border-[#D97706] pb-1' : 'text-[#3A1812] hover:text-[#D97706] transition'; ?>">Our Menu</a>
                    <a href="<?php echo baseUrl('about.php'); ?>" class="<?php echo $currentScript == 'about.php' ? 'text-[#D97706] font-bold border-b-2 border-[#D97706] pb-1' : 'text-[#3A1812] hover:text-[#D97706] transition'; ?>">About Us</a>
                    <a href="<?php echo baseUrl('gallery.php'); ?>" class="<?php echo $currentScript == 'gallery.php' ? 'text-[#D97706] font-bold border-b-2 border-[#D97706] pb-1' : 'text-[#3A1812] hover:text-[#D97706] transition'; ?>">Gallery</a>
                    <a href="<?php echo baseUrl('contact.php'); ?>" class="<?php echo $currentScript == 'contact.php' ? 'text-[#D97706] font-bold border-b-2 border-[#D97706] pb-1' : 'text-[#3A1812] hover:text-[#D97706] transition'; ?>">Contact</a>
                </nav>

                <!-- Right Action Icons & User Account -->
                <div class="flex items-center gap-4">
                    <!-- Cart Button -->
                    <a href="<?php echo baseUrl('cart.php'); ?>" class="relative p-2 text-[#3A1812] hover:text-[#D97706] transition" title="Shopping Cart">
                        <i class="fa-solid fa-cart-shopping text-xl"></i>
                        <span id="cart-badge-count" class="absolute -top-1 -right-1 bg-[#D97706] text-white text-[11px] font-extrabold w-5 h-5 rounded-full flex items-center justify-center border-2 border-[#FAF4EB]">
                            <?php echo $cartCount; ?>
                        </span>
                    </a>

                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <a href="<?php echo baseUrl('admin/index.php'); ?>" class="bg-[#3A1812] hover:bg-[#2D0B07] text-[#FAF4EB] text-xs px-3.5 py-2 rounded-lg font-bold flex items-center gap-2 transition shadow-sm border border-[#D97706]">
                                <i class="fa-solid fa-user-shield text-[#D97706]"></i>
                                Admin Panel
                            </a>
                        <?php else: ?>
                            <div class="relative" x-data="{ userMenu: false }">
                                <button @click="userMenu = !userMenu" class="bg-[#F3E6D5] hover:bg-[#E8DCCB] text-[#3A1812] text-xs px-3.5 py-2 rounded-lg font-bold flex items-center gap-2 transition">
                                    <i class="fa-solid fa-user-circle text-base text-[#D97706]"></i>
                                    <span><?php echo htmlspecialchars(currentUserName()); ?></span>
                                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                </button>
                                <div x-show="userMenu" @click.away="userMenu = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 border border-[#E8DCCB] z-50">
                                    <a href="<?php echo baseUrl('customer/index.php'); ?>" class="block px-4 py-2 text-xs font-semibold text-[#3A1812] hover:bg-[#FAF4EB]"><i class="fa-solid fa-gauge mr-2 text-[#D97706]"></i> Dashboard</a>
                                    <a href="<?php echo baseUrl('customer/orders.php'); ?>" class="block px-4 py-2 text-xs font-semibold text-[#3A1812] hover:bg-[#FAF4EB]"><i class="fa-solid fa-box-archive mr-2 text-[#D97706]"></i> My Orders</a>
                                    <a href="<?php echo baseUrl('customer/profile.php'); ?>" class="block px-4 py-2 text-xs font-semibold text-[#3A1812] hover:bg-[#FAF4EB]"><i class="fa-solid fa-id-card mr-2 text-[#D97706]"></i> Edit Profile</a>
                                    <hr class="my-1 border-gray-200">
                                    <a href="<?php echo baseUrl('logout.php'); ?>" class="block px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50"><i class="fa-solid fa-right-from-bracket mr-2"></i> Logout</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="hidden sm:flex items-center gap-2">
                            <a href="<?php echo baseUrl('login.php'); ?>" class="text-xs font-bold text-[#3A1812] hover:text-[#D97706] px-3 py-2">Login</a>
                            <a href="<?php echo baseUrl('register.php'); ?>" class="bg-[#D97706] hover:bg-[#B45309] text-white text-xs px-4 py-2 rounded-lg font-bold transition shadow">Register</a>
                        </div>
                    <?php endif; ?>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-[#3A1812] focus:outline-none">
                        <i class="fa-solid fa-bars text-xl" x-show="!mobileMenu"></i>
                        <i class="fa-solid fa-xmark text-xl" x-show="mobileMenu"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <div x-show="mobileMenu" class="md:hidden bg-[#FAF4EB] border-t border-[#E8DCCB] px-4 pt-3 pb-6 space-y-2">
            <a href="<?php echo baseUrl('index.php'); ?>" class="block px-3 py-2 rounded-md font-bold text-[#3A1812] hover:bg-[#F3E6D5]">Home</a>
            <a href="<?php echo baseUrl('menu.php'); ?>" class="block px-3 py-2 rounded-md font-bold text-[#3A1812] hover:bg-[#F3E6D5]">Our Menu</a>
            <a href="<?php echo baseUrl('about.php'); ?>" class="block px-3 py-2 rounded-md font-bold text-[#3A1812] hover:bg-[#F3E6D5]">About Us</a>
            <a href="<?php echo baseUrl('gallery.php'); ?>" class="block px-3 py-2 rounded-md font-bold text-[#3A1812] hover:bg-[#F3E6D5]">Gallery</a>
            <a href="<?php echo baseUrl('contact.php'); ?>" class="block px-3 py-2 rounded-md font-bold text-[#3A1812] hover:bg-[#F3E6D5]">Contact</a>
            <?php if (!isLoggedIn()): ?>
                <div class="pt-2 border-t border-[#E8DCCB] flex gap-2">
                    <a href="<?php echo baseUrl('login.php'); ?>" class="w-1/2 text-center py-2 border border-[#3A1812] rounded font-bold text-xs">Login</a>
                    <a href="<?php echo baseUrl('register.php'); ?>" class="w-1/2 text-center py-2 bg-[#D97706] text-white rounded font-bold text-xs">Register</a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Flash Notification Display -->
    <?php if ($flash = getFlash()): ?>
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="p-4 rounded-lg flex items-center justify-between shadow-sm <?php 
                echo $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 
                    ($flash['type'] === 'danger' ? 'bg-rose-50 text-rose-800 border border-rose-200' : 'bg-amber-50 text-amber-800 border border-amber-200'); 
            ?>">
                <div class="flex items-center gap-3 font-semibold text-sm">
                    <i class="fa-solid <?php echo $flash['type'] === 'success' ? 'fa-circle-check text-emerald-600' : 'fa-triangle-exclamation text-amber-600'; ?>"></i>
                    <span><?php echo htmlspecialchars($flash['message']); ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Body Container -->
    <main class="flex-grow">
