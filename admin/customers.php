<?php
// admin/customers.php - Manage Customers Page
$pageTitle = "Manage Customers - Admin MBC";
require_once __DIR__ . '/../config/helpers.php';
requireAdmin();

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    $userId = (int)$_POST['user_id'];
    $newStatus = sanitize($_POST['status']);
    $stmt = $db->prepare("UPDATE users SET status = ? WHERE id = ? AND role = 'customer'");
    $stmt->execute([$newStatus, $userId]);
    setFlash('info', 'Customer status updated.');
    header('Location: ' . baseUrl('admin/customers.php'));
    exit;
}

$customers = $db->query("SELECT u.*, COUNT(o.id) AS total_orders FROM users u LEFT JOIN orders o ON u.id = o.user_id WHERE u.role = 'customer' GROUP BY u.id ORDER BY u.id DESC")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<section class="py-10 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <div class="flex-grow space-y-6">
                <div class="mbc-card p-5 bg-white rounded-2xl border border-[#E8DCCB] shadow">
                    <h2 class="font-serif-heading font-extrabold text-2xl text-[#3A1812]">Registered Customers</h2>
                    <p class="text-xs text-gray-500">View customer accounts, contact details, and manage account status</p>
                </div>

                <div class="mbc-card bg-white rounded-2xl border border-[#E8DCCB] shadow overflow-hidden">
                    <table class="w-full text-left mbc-table text-xs">
                        <thead>
                            <tr>
                                <th>Customer ID</th>
                                <th>Name</th>
                                <th>Email / Phone</th>
                                <th>Total Orders</th>
                                <th>Registration Date</th>
                                <th>Account Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $cust): ?>
                                <tr>
                                    <td class="font-bold text-[#3A1812]">#<?php echo sprintf("%04d", $cust['id']); ?></td>
                                    <td><strong class="text-[#3A1812]"><?php echo htmlspecialchars($cust['name']); ?></strong></td>
                                    <td>
                                        <span class="block text-gray-700"><?php echo htmlspecialchars($cust['email']); ?></span>
                                        <span class="text-[10px] text-gray-500"><?php echo htmlspecialchars($cust['phone']); ?></span>
                                    </td>
                                    <td class="font-bold text-[#D97706]"><?php echo $cust['total_orders']; ?> orders</td>
                                    <td class="text-gray-600"><?php echo date('d M Y', strtotime($cust['created_at'])); ?></td>
                                    <td>
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full <?php echo $cust['status'] === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'; ?>">
                                            <?php echo ucfirst($cust['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <form action="<?php echo baseUrl('admin/customers.php'); ?>" method="POST" class="inline">
                                            <input type="hidden" name="toggle_status" value="1">
                                            <input type="hidden" name="user_id" value="<?php echo $cust['id']; ?>">
                                            <input type="hidden" name="status" value="<?php echo $cust['status'] === 'active' ? 'blocked' : 'active'; ?>">
                                            <button type="submit" class="px-3 py-1 text-[11px] font-bold rounded-lg <?php echo $cust['status'] === 'active' ? 'bg-rose-50 text-rose-700 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'; ?>">
                                                <?php echo $cust['status'] === 'active' ? 'Block User' : 'Unblock'; ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
