<?php
// customer/index.php - Customer Dashboard
$pageTitle = "Customer Dashboard - MBC";
require_once __DIR__ . '/../config/helpers.php';
requireAuth();

$db = getDB();
$userId = currentUserId();

// Customer Statistics
$totalOrders = $db->query("SELECT COUNT(*) FROM orders WHERE user_id = $userId")->fetchColumn();
$processingOrders = $db->query("SELECT COUNT(*) FROM orders WHERE user_id = $userId AND status IN ('Pending', 'Preparing', 'Out for Delivery')")->fetchColumn();
$completedOrders = $db->query("SELECT COUNT(*) FROM orders WHERE user_id = $userId AND status = 'Completed'")->fetchColumn();
$totalSpent = $db->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE user_id = $userId AND status = 'Completed'")->fetchColumn();

// Fetch Recent 5 Orders
$stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT 5");
$stmt->execute([$userId]);
$recentOrders = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<section class="py-10 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- Main Content Area -->
            <div class="flex-grow space-y-6">
                
                <!-- Dashboard Welcome Banner -->
                <div class="mbc-card p-6 bg-gradient-to-r from-[#FAF4EB] to-[#FFFBF4] rounded-2xl border border-[#E8DCCB] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="font-serif-heading font-extrabold text-2xl text-[#3A1812]">Dashboard Overview</h1>
                        <p class="text-xs text-gray-500">Welcome back, <strong><?php echo htmlspecialchars(currentUserName()); ?></strong>!</p>
                    </div>
                    <a href="<?php echo baseUrl('menu.php'); ?>" class="bg-[#D97706] hover:bg-[#B45309] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow">
                        <i class="fa-solid fa-plus mr-1"></i> New Order
                    </a>
                </div>

                <!-- 4 Stat Cards Matching Image 1 -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <!-- Stat 1 -->
                    <div class="mbc-card p-4 bg-white rounded-2xl border border-[#E8DCCB] space-y-1">
                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Total Orders</span>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-extrabold text-[#3A1812]"><?php echo sprintf("%02d", $totalOrders); ?></span>
                            <div class="w-8 h-8 rounded-lg bg-[#FAF4EB] text-[#3A1812] flex items-center justify-center text-xs font-bold">
                                <i class="fa-solid fa-bag-shopping"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Stat 2 -->
                    <div class="mbc-card p-4 bg-white rounded-2xl border border-[#E8DCCB] space-y-1">
                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Processing</span>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-extrabold text-[#D97706]"><?php echo sprintf("%02d", $processingOrders); ?></span>
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-[#D97706] flex items-center justify-center text-xs font-bold">
                                <i class="fa-solid fa-spinner animate-spin"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Stat 3 -->
                    <div class="mbc-card p-4 bg-white rounded-2xl border border-[#E8DCCB] space-y-1">
                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Completed</span>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-extrabold text-emerald-600"><?php echo sprintf("%02d", $completedOrders); ?></span>
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Stat 4 -->
                    <div class="mbc-card p-4 bg-white rounded-2xl border border-[#E8DCCB] space-y-1">
                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Total Spent</span>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-extrabold text-[#3A1812]"><?php echo formatPrice($totalSpent); ?></span>
                            <div class="w-8 h-8 rounded-lg bg-[#FAF4EB] text-[#D97706] flex items-center justify-center text-xs font-bold">
                                <i class="fa-solid fa-indian-rupee-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table Matching Image 1 -->
                <div class="mbc-card bg-white rounded-2xl border border-[#E8DCCB] overflow-hidden shadow">
                    <div class="p-5 border-b border-[#E8DCCB] flex justify-between items-center bg-[#FAF4EB]">
                        <h3 class="font-serif-heading font-bold text-base text-[#3A1812]">Recent Orders</h3>
                        <a href="<?php echo baseUrl('customer/orders.php'); ?>" class="text-xs font-bold text-[#D97706] hover:underline">View All</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left mbc-table text-xs">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Order Type</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentOrders)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-6 text-gray-500">No recent orders found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentOrders as $ord): ?>
                                        <tr>
                                            <td class="font-bold text-[#3A1812]"><?php echo htmlspecialchars($ord['order_number']); ?></td>
                                            <td class="text-gray-600"><?php echo date('d M Y, h:i A', strtotime($ord['created_at'])); ?></td>
                                            <td><span class="font-semibold text-gray-700"><?php echo htmlspecialchars($ord['order_type']); ?></span></td>
                                            <td class="font-extrabold text-[#D97706]"><?php echo formatPrice($ord['total_amount']); ?></td>
                                            <td><?php echo renderStatusBadge($ord['status']); ?></td>
                                            <td class="text-center">
                                                <a href="<?php echo baseUrl('customer/order-detail.php?id=' . $ord['id']); ?>" class="bg-[#3A1812] hover:bg-[#D97706] text-white text-[11px] font-bold px-3 py-1.5 rounded-lg transition">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
