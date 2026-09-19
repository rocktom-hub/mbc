<?php
// admin/index.php - Admin Dashboard
$pageTitle = "Admin Dashboard - MBC";
require_once __DIR__ . '/../config/helpers.php';
requireAdmin();

$db = getDB();

// Handle AJAX or Form Status Update directly
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = sanitize($_POST['status']);
    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $orderId]);
    setFlash('success', 'Order status updated to ' . $newStatus);
    header('Location: ' . baseUrl('admin/index.php'));
    exit;
}

// Fetch Admin Metrics
$totalOrders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = $db->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status = 'Completed'")->fetchColumn();
$totalCustomers = $db->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$totalItems = $db->query("SELECT COUNT(*) FROM foods")->fetchColumn();
$totalCategories = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();

// Status counts
$pendingCount = $db->query("SELECT COUNT(*) FROM orders WHERE status = 'Pending'")->fetchColumn();
$preparingCount = $db->query("SELECT COUNT(*) FROM orders WHERE status = 'Preparing'")->fetchColumn();
$outDeliveryCount = $db->query("SELECT COUNT(*) FROM orders WHERE status = 'Out for Delivery'")->fetchColumn();
$completedCount = $db->query("SELECT COUNT(*) FROM orders WHERE status = 'Completed'")->fetchColumn();

// Recent 5 Orders
$recentOrders = $db->query("SELECT * FROM orders ORDER BY id DESC LIMIT 5")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<section class="py-10 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- Main Content -->
            <div class="flex-grow space-y-6">
                
                <!-- Welcome Banner -->
                <div class="mbc-card p-6 bg-gradient-to-r from-[#FAF4EB] to-[#FFFBF4] rounded-2xl border border-[#E8DCCB] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="font-serif-heading font-extrabold text-2xl text-[#3A1812]">Admin Dashboard Overview</h1>
                        <p class="text-xs text-gray-500">Live metrics & store performance summary</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="<?php echo baseUrl('admin/foods.php'); ?>" class="bg-[#3A1812] hover:bg-[#D97706] text-white text-xs font-bold px-3.5 py-2 rounded-xl transition shadow">
                            <i class="fa-solid fa-plus mr-1"></i> Add Food Item
                        </a>
                        <a href="<?php echo baseUrl('admin/orders.php'); ?>" class="bg-[#D97706] hover:bg-[#B45309] text-white text-xs font-bold px-3.5 py-2 rounded-xl transition shadow">
                            <i class="fa-solid fa-list-check mr-1"></i> All Orders
                        </a>
                    </div>
                </div>

                <!-- 5 Stat Cards Matching Reference Image 1 -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                    <div class="mbc-card p-4 bg-white rounded-2xl border border-[#E8DCCB] space-y-1">
                        <span class="text-[10px] font-bold text-gray-500 uppercase block">Total Orders</span>
                        <span class="text-2xl font-extrabold text-[#3A1812]"><?php echo sprintf("%02d", $totalOrders); ?></span>
                    </div>
                    <div class="mbc-card p-4 bg-white rounded-2xl border border-[#E8DCCB] space-y-1">
                        <span class="text-[10px] font-bold text-gray-500 uppercase block">Total Revenue</span>
                        <span class="text-lg font-extrabold text-[#D97706]"><?php echo formatPrice($totalRevenue); ?></span>
                    </div>
                    <div class="mbc-card p-4 bg-white rounded-2xl border border-[#E8DCCB] space-y-1">
                        <span class="text-[10px] font-bold text-gray-500 uppercase block">Customers</span>
                        <span class="text-2xl font-extrabold text-[#3A1812]"><?php echo sprintf("%02d", $totalCustomers); ?></span>
                    </div>
                    <div class="mbc-card p-4 bg-white rounded-2xl border border-[#E8DCCB] space-y-1">
                        <span class="text-[10px] font-bold text-gray-500 uppercase block">Total Items</span>
                        <span class="text-2xl font-extrabold text-[#3A1812]"><?php echo sprintf("%02d", $totalItems); ?></span>
                    </div>
                    <div class="mbc-card p-4 bg-white rounded-2xl border border-[#E8DCCB] space-y-1 col-span-2 sm:col-span-1">
                        <span class="text-[10px] font-bold text-gray-500 uppercase block">Categories</span>
                        <span class="text-2xl font-extrabold text-[#3A1812]"><?php echo sprintf("%02d", $totalCategories); ?></span>
                    </div>
                </div>

                <!-- Interactive Analytics Charts Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Line Chart: Monthly Revenue -->
                    <div class="md:col-span-2 mbc-card bg-white p-5 rounded-2xl border border-[#E8DCCB] shadow space-y-3">
                        <h3 class="font-serif-heading font-bold text-sm text-[#3A1812] flex items-center justify-between">
                            <span>Monthly Sales & Revenue Trend</span>
                            <span class="text-[10px] bg-[#FAF4EB] px-2 py-0.5 rounded text-[#D97706] font-bold">2026</span>
                        </h3>
                        <div class="h-56">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <!-- Doughnut Chart: Orders Breakdown -->
                    <div class="mbc-card bg-white p-5 rounded-2xl border border-[#E8DCCB] shadow space-y-3">
                        <h3 class="font-serif-heading font-bold text-sm text-[#3A1812]">Order Status Breakdown</h3>
                        <div class="h-56 flex items-center justify-center">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>

                </div>

                <!-- Recent Orders Management Table -->
                <div class="mbc-card bg-white rounded-2xl border border-[#E8DCCB] overflow-hidden shadow">
                    <div class="p-5 border-b border-[#E8DCCB] bg-[#FAF4EB] flex justify-between items-center">
                        <h3 class="font-serif-heading font-bold text-base text-[#3A1812]">Recent Customer Orders</h3>
                        <a href="<?php echo baseUrl('admin/orders.php'); ?>" class="text-xs font-bold text-[#D97706] hover:underline">Manage All Orders</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left mbc-table text-xs">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Order Type</th>
                                    <th>Amount</th>
                                    <th>Current Status</th>
                                    <th>Update Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentOrders as $ord): ?>
                                    <tr>
                                        <td class="font-bold text-[#3A1812]"><?php echo htmlspecialchars($ord['order_number']); ?></td>
                                        <td>
                                            <strong class="block text-[#3A1812]"><?php echo htmlspecialchars($ord['customer_name']); ?></strong>
                                            <span class="text-[10px] text-gray-500"><?php echo htmlspecialchars($ord['customer_phone']); ?></span>
                                        </td>
                                        <td><span class="font-semibold text-gray-700"><?php echo htmlspecialchars($ord['order_type']); ?></span></td>
                                        <td class="font-extrabold text-[#D97706]"><?php echo formatPrice($ord['total_amount']); ?></td>
                                        <td><?php echo renderStatusBadge($ord['status']); ?></td>
                                        <td>
                                            <form action="<?php echo baseUrl('admin/index.php'); ?>" method="POST" class="inline-block">
                                                <input type="hidden" name="update_status" value="1">
                                                <input type="hidden" name="order_id" value="<?php echo $ord['id']; ?>">
                                                <select name="status" onchange="this.form.submit()" class="px-2 py-1 text-[11px] border border-[#E8DCCB] rounded-lg font-bold text-[#3A1812] bg-[#FAF4EB] focus:outline-none">
                                                    <option value="Pending" <?php echo $ord['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="Preparing" <?php echo $ord['status'] === 'Preparing' ? 'selected' : ''; ?>>Preparing</option>
                                                    <option value="Out for Delivery" <?php echo $ord['status'] === 'Out for Delivery' ? 'selected' : ''; ?>>Out for Delivery</option>
                                                    <option value="Completed" <?php echo $ord['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="Cancelled" <?php echo $ord['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo baseUrl('admin/print-invoice.php?id=' . $ord['id']); ?>" target="_blank" class="bg-[#3A1812] hover:bg-[#D97706] text-white text-[10px] font-bold px-2.5 py-1.5 rounded-lg transition inline-block">
                                                <i class="fa-solid fa-print"></i> Receipt
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>

<!-- Initialize Chart.js -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Line Chart
    const ctxLine = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            datasets: [{
                label: 'Revenue (₹)',
                data: [12000, 19000, 15000, 24000, 32000, 28000, 41000, 48000, <?php echo $totalRevenue; ?>],
                borderColor: '#D97706',
                backgroundColor: 'rgba(217, 119, 6, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Doughnut Chart
    const ctxPie = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Preparing', 'Out for Delivery', 'Completed'],
            datasets: [{
                data: [<?php echo $pendingCount; ?>, <?php echo $preparingCount; ?>, <?php echo $outDeliveryCount; ?>, <?php echo $completedCount; ?>],
                backgroundColor: ['#F59E0B', '#3B82F6', '#8B5CF6', '#10B981']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { font: { size: 10 } } } }
        }
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
