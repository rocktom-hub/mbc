<?php
// customer/includes/sidebar.php
$currentCustomerScript = basename($_SERVER['SCRIPT_NAME']);
?>
<aside class="w-full lg:w-64 mbc-sidebar text-[#FAF4EB] rounded-2xl p-6 shadow-xl space-y-6 shrink-0">
    
    <!-- User Profile Header -->
    <div class="flex items-center gap-3 pb-6 border-b border-[#52251B]">
        <div class="w-12 h-12 rounded-full bg-[#D97706] text-white font-serif-heading font-extrabold flex items-center justify-center text-xl shadow">
            <?php echo strtoupper(substr(currentUserName(), 0, 1)); ?>
        </div>
        <div class="overflow-hidden">
            <h4 class="font-bold text-sm text-white truncate"><?php echo htmlspecialchars(currentUserName()); ?></h4>
            <span class="text-[10px] text-gray-300 block truncate"><?php echo htmlspecialchars(currentUserEmail()); ?></span>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="space-y-1 text-xs font-bold">
        <a href="<?php echo baseUrl('customer/index.php'); ?>" class="flex items-center gap-3 px-4 py-3 mbc-sidebar-item <?php echo $currentCustomerScript == 'index.php' ? 'active' : 'text-gray-300 hover:text-white'; ?>">
            <i class="fa-solid fa-gauge-high text-sm"></i>
            <span>Dashboard Overview</span>
        </a>
        <a href="<?php echo baseUrl('customer/profile.php'); ?>" class="flex items-center gap-3 px-4 py-3 mbc-sidebar-item <?php echo $currentCustomerScript == 'profile.php' ? 'active' : 'text-gray-300 hover:text-white'; ?>">
            <i class="fa-solid fa-user text-sm"></i>
            <span>My Profile</span>
        </a>
        <a href="<?php echo baseUrl('customer/orders.php'); ?>" class="flex items-center gap-3 px-4 py-3 mbc-sidebar-item <?php echo in_array($currentCustomerScript, ['orders.php', 'order-detail.php']) ? 'active' : 'text-gray-300 hover:text-white'; ?>">
            <i class="fa-solid fa-box-archive text-sm"></i>
            <span>My Orders / History</span>
        </a>
        <a href="<?php echo baseUrl('customer/change-password.php'); ?>" class="flex items-center gap-3 px-4 py-3 mbc-sidebar-item <?php echo $currentCustomerScript == 'change-password.php' ? 'active' : 'text-gray-300 hover:text-white'; ?>">
            <i class="fa-solid fa-key text-sm"></i>
            <span>Change Password</span>
        </a>
    </nav>

    <div class="pt-6 border-t border-[#52251B]">
        <a href="<?php echo baseUrl('logout.php'); ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-900/40 hover:bg-rose-800 text-rose-200 font-bold text-xs transition">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout Account</span>
        </a>
    </div>

</aside>
