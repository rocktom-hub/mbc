<?php
// admin/includes/sidebar.php
$currentAdminScript = basename($_SERVER['SCRIPT_NAME']);
?>
<aside class="w-full lg:w-64 mbc-sidebar text-[#FAF4EB] rounded-2xl p-6 shadow-xl space-y-6 shrink-0">
    
    <!-- Admin Header Badge -->
    <div class="flex items-center gap-3 pb-6 border-b border-[#52251B]">
        <div class="w-12 h-12 rounded-full bg-[#D97706] text-white font-serif-heading font-extrabold flex items-center justify-center text-xl shadow border-2 border-[#FBBF24]">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <div class="overflow-hidden">
            <h4 class="font-bold text-sm text-white truncate">Admin Panel</h4>
            <span class="text-[10px] text-[#FBBF24] font-semibold block truncate">Malek Bateta Center</span>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="space-y-1 text-xs font-bold">
        <a href="<?php echo baseUrl('admin/index.php'); ?>" class="flex items-center gap-3 px-4 py-3 mbc-sidebar-item <?php echo $currentAdminScript == 'index.php' ? 'active' : 'text-gray-300 hover:text-white'; ?>">
            <i class="fa-solid fa-chart-line text-sm text-[#D97706]"></i>
            <span>Dashboard Overview</span>
        </a>
        <a href="<?php echo baseUrl('admin/foods.php'); ?>" class="flex items-center gap-3 px-4 py-3 mbc-sidebar-item <?php echo $currentAdminScript == 'foods.php' ? 'active' : 'text-gray-300 hover:text-white'; ?>">
            <i class="fa-solid fa-bowl-food text-sm text-[#D97706]"></i>
            <span>Manage Foods</span>
        </a>
        <a href="<?php echo baseUrl('admin/categories.php'); ?>" class="flex items-center gap-3 px-4 py-3 mbc-sidebar-item <?php echo $currentAdminScript == 'categories.php' ? 'active' : 'text-gray-300 hover:text-white'; ?>">
            <i class="fa-solid fa-layer-group text-sm text-[#D97706]"></i>
            <span>Food Categories</span>
        </a>
        <a href="<?php echo baseUrl('admin/orders.php'); ?>" class="flex items-center gap-3 px-4 py-3 mbc-sidebar-item <?php echo in_array($currentAdminScript, ['orders.php', 'print-invoice.php']) ? 'active' : 'text-gray-300 hover:text-white'; ?>">
            <i class="fa-solid fa-[#D97706] fa-bag-shopping text-sm text-[#D97706]"></i>
            <span>Manage Orders</span>
        </a>
        <a href="<?php echo baseUrl('admin/customers.php'); ?>" class="flex items-center gap-3 px-4 py-3 mbc-sidebar-item <?php echo $currentAdminScript == 'customers.php' ? 'active' : 'text-gray-300 hover:text-white'; ?>">
            <i class="fa-solid fa-users text-sm text-[#D97706]"></i>
            <span>Manage Customers</span>
        </a>
        <a href="<?php echo baseUrl('admin/reviews.php'); ?>" class="flex items-center gap-3 px-4 py-3 mbc-sidebar-item <?php echo $currentAdminScript == 'reviews.php' ? 'active' : 'text-gray-300 hover:text-white'; ?>">
            <i class="fa-solid fa-star text-sm text-[#D97706]"></i>
            <span>Customer Reviews</span>
        </a>
        <a href="<?php echo baseUrl('admin/gallery.php'); ?>" class="flex items-center gap-3 px-4 py-3 mbc-sidebar-item <?php echo $currentAdminScript == 'gallery.php' ? 'active' : 'text-gray-300 hover:text-white'; ?>">
            <i class="fa-solid fa-images text-sm text-[#D97706]"></i>
            <span>Gallery Photos</span>
        </a>
        <a href="<?php echo baseUrl('admin/settings.php'); ?>" class="flex items-center gap-3 px-4 py-3 mbc-sidebar-item <?php echo $currentAdminScript == 'settings.php' ? 'active' : 'text-gray-300 hover:text-white'; ?>">
            <i class="fa-solid fa-sliders text-sm text-[#D97706]"></i>
            <span>Store Settings</span>
        </a>
    </nav>

    <div class="pt-6 border-t border-[#52251B]">
        <a href="<?php echo baseUrl('logout.php'); ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-900/40 hover:bg-rose-800 text-rose-200 font-bold text-xs transition">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout Admin</span>
        </a>
    </div>

</aside>
