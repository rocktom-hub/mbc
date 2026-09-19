<?php
// customer/orders.php - Order History List
$pageTitle = "My Orders - MBC";
require_once __DIR__ . '/../config/helpers.php';
requireAuth();

$db = getDB();
$userId = currentUserId();

$statusFilter = sanitize($_GET['status'] ?? '');

$sql = "SELECT * FROM orders WHERE user_id = ?";
$params = [$userId];

if (!empty($statusFilter)) {
    $sql .= " AND status = ?";
    $params[] = $statusFilter;
}

$sql .= " ORDER BY id DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<section class="py-10 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <div class="flex-grow space-y-6">
                <div class="mbc-card bg-white rounded-2xl border border-[#E8DCCB] shadow overflow-hidden">
                    <div class="p-5 border-b border-[#E8DCCB] bg-[#FAF4EB] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h2 class="font-serif-heading font-extrabold text-xl text-[#3A1812]">My Order History</h2>
                        
                        <!-- Status Filter -->
                        <form action="<?php echo baseUrl('customer/orders.php'); ?>" method="GET" class="flex items-center gap-2">
                            <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-white border border-[#E8DCCB] rounded-xl font-medium focus:outline-none text-[#3A1812]">
                                <option value="">All Statuses</option>
                                <option value="Pending" <?php echo $statusFilter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Preparing" <?php echo $statusFilter === 'Preparing' ? 'selected' : ''; ?>>Preparing</option>
                                <option value="Out for Delivery" <?php echo $statusFilter === 'Out for Delivery' ? 'selected' : ''; ?>>Out for Delivery</option>
                                <option value="Completed" <?php echo $statusFilter === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="Cancelled" <?php echo $statusFilter === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left mbc-table text-xs">
                            <thead>
                                <tr>
                                    <th>Order Number</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Payment</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-8 text-gray-500">No orders found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($orders as $ord): ?>
                                        <tr>
                                            <td class="font-bold text-[#3A1812]"><?php echo htmlspecialchars($ord['order_number']); ?></td>
                                            <td class="text-gray-600"><?php echo date('d M Y, h:i A', strtotime($ord['created_at'])); ?></td>
                                            <td><span class="font-semibold text-gray-700"><?php echo htmlspecialchars($ord['order_type']); ?></span></td>
                                            <td><span class="text-gray-600"><?php echo htmlspecialchars($ord['payment_method']); ?></span></td>
                                            <td class="font-extrabold text-[#D97706]"><?php echo formatPrice($ord['total_amount']); ?></td>
                                            <td><?php echo renderStatusBadge($ord['status']); ?></td>
                                            <td class="text-center">
                                                <a href="<?php echo baseUrl('customer/order-detail.php?id=' . $ord['id']); ?>" class="bg-[#3A1812] hover:bg-[#D97706] text-white text-[11px] font-bold px-3 py-1.5 rounded-lg transition inline-block">
                                                    View Receipt
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
