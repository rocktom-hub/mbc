<?php
// admin/orders.php - Manage Orders Page
$pageTitle = "Manage Orders - Admin MBC";
require_once __DIR__ . '/../config/helpers.php';
requireAdmin();

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = (int)$_POST['order_id'];
    $status = sanitize($_POST['status']);
    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $orderId]);
    setFlash('success', 'Order status updated.');
    header('Location: ' . baseUrl('admin/orders.php'));
    exit;
}

$statusFilter = sanitize($_GET['status'] ?? '');
$search = sanitize($_GET['search'] ?? '');

$sql = "SELECT * FROM orders WHERE 1=1";
$params = [];

if (!empty($statusFilter)) {
    $sql .= " AND status = ?";
    $params[] = $statusFilter;
}

if (!empty($search)) {
    $sql .= " AND (order_number LIKE ? OR customer_name LIKE ? OR customer_phone LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
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
                
                <div class="mbc-card p-5 bg-white rounded-2xl border border-[#E8DCCB] shadow flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h2 class="font-serif-heading font-extrabold text-2xl text-[#3A1812]">Manage Customer Orders</h2>
                        <p class="text-xs text-gray-500">Filter, search, and update live order delivery status</p>
                    </div>

                    <form action="<?php echo baseUrl('admin/orders.php'); ?>" method="GET" class="flex flex-wrap items-center gap-2">
                        <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-bold text-[#3A1812]">
                            <option value="">All Statuses</option>
                            <option value="Pending" <?php echo $statusFilter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Preparing" <?php echo $statusFilter === 'Preparing' ? 'selected' : ''; ?>>Preparing</option>
                            <option value="Out for Delivery" <?php echo $statusFilter === 'Out for Delivery' ? 'selected' : ''; ?>>Out for Delivery</option>
                            <option value="Completed" <?php echo $statusFilter === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="Cancelled" <?php echo $statusFilter === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>

                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Order # / Name / Phone" class="px-3 py-2 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium">
                        <button type="submit" class="px-3 py-2 bg-[#3A1812] text-white text-xs font-bold rounded-xl"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                </div>

                <div class="mbc-card bg-white rounded-2xl border border-[#E8DCCB] shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left mbc-table text-xs">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Customer Info</th>
                                    <th>Type</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($orders)): ?>
                                    <tr><td colspan="7" class="text-center py-8 text-gray-500">No orders found.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($orders as $ord): ?>
                                        <tr>
                                            <td class="font-bold text-[#3A1812]"><?php echo htmlspecialchars($ord['order_number']); ?></td>
                                            <td class="text-gray-600"><?php echo date('d M Y, h:i A', strtotime($ord['created_at'])); ?></td>
                                            <td>
                                                <strong class="block text-[#3A1812]"><?php echo htmlspecialchars($ord['customer_name']); ?></strong>
                                                <span class="text-[10px] text-gray-500 block"><?php echo htmlspecialchars($ord['customer_phone']); ?></span>
                                            </td>
                                            <td><span class="font-semibold text-gray-700"><?php echo htmlspecialchars($ord['order_type']); ?></span></td>
                                            <td class="font-extrabold text-[#D97706]"><?php echo formatPrice($ord['total_amount']); ?></td>
                                            <td>
                                                <form action="<?php echo baseUrl('admin/orders.php'); ?>" method="POST">
                                                    <input type="hidden" name="update_status" value="1">
                                                    <input type="hidden" name="order_id" value="<?php echo $ord['id']; ?>">
                                                    <select name="status" onchange="this.form.submit()" class="px-2 py-1 text-[11px] font-bold border border-[#E8DCCB] rounded-lg bg-[#FAF4EB]">
                                                        <option value="Pending" <?php echo $ord['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="Preparing" <?php echo $ord['status'] === 'Preparing' ? 'selected' : ''; ?>>Preparing</option>
                                                        <option value="Out for Delivery" <?php echo $ord['status'] === 'Out for Delivery' ? 'selected' : ''; ?>>Out for Delivery</option>
                                                        <option value="Completed" <?php echo $ord['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                        <option value="Cancelled" <?php echo $ord['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="<?php echo baseUrl('admin/print-invoice.php?id=' . $ord['id']); ?>" target="_blank" class="bg-[#3A1812] hover:bg-[#D97706] text-white text-[11px] font-bold px-3 py-1.5 rounded-lg transition inline-block">
                                                    <i class="fa-solid fa-print"></i> Invoice
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
