<?php
// customer/order-detail.php - Customer View Order Details
$pageTitle = "Order Details - MBC";
require_once __DIR__ . '/../config/helpers.php';
requireAuth();

$orderId = (int)($_GET['id'] ?? 0);
$db = getDB();

$stmt = $db->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, currentUserId()]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: ' . baseUrl('customer/orders.php'));
    exit;
}

$itemStmt = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
$itemStmt->execute([$orderId]);
$items = $itemStmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<section class="py-10 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <div class="flex-grow space-y-6">
                <div class="mbc-card bg-white p-6 rounded-2xl border border-[#E8DCCB] shadow space-y-6" id="printable-invoice">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-[#E8DCCB] pb-4 gap-4">
                        <div>
                            <span class="text-xs font-bold text-[#D97706] uppercase tracking-wider block">Order Receipt</span>
                            <h2 class="font-serif-heading font-extrabold text-2xl text-[#3A1812]"><?php echo htmlspecialchars($order['order_number']); ?></h2>
                            <span class="text-xs text-gray-500">Placed on <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></span>
                        </div>
                        <div class="flex items-center gap-3">
                            <?php echo renderStatusBadge($order['status']); ?>
                            <button onclick="window.print()" class="bg-[#FAF4EB] text-[#3A1812] border border-[#3A1812] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#E8DCCB]">
                                <i class="fa-solid fa-print mr-1"></i> Print
                            </button>
                        </div>
                    </div>

                    <!-- Details Box -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-[#FAF4EB] p-4 rounded-xl border border-[#E8DCCB]">
                        <div>
                            <span class="text-gray-500 font-semibold block">Customer Info:</span>
                            <strong class="text-[#3A1812]"><?php echo htmlspecialchars($order['customer_name']); ?></strong> (<?php echo htmlspecialchars($order['customer_phone']); ?>)
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold block">Delivery Address:</span>
                            <strong class="text-[#3A1812]"><?php echo htmlspecialchars($order['delivery_address']); ?></strong>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left mbc-table text-xs">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $it): ?>
                                    <tr>
                                        <td class="font-bold text-[#3A1812]"><?php echo htmlspecialchars($it['food_name']); ?></td>
                                        <td class="text-center"><?php echo formatPrice($it['price']); ?></td>
                                        <td class="text-center"><?php echo $it['quantity']; ?></td>
                                        <td class="text-right font-bold text-[#D97706]"><?php echo formatPrice($it['total_price']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="text-xs space-y-1 max-w-xs ml-auto text-right font-medium">
                        <div class="flex justify-between"><span>Subtotal:</span><strong><?php echo formatPrice($order['subtotal']); ?></strong></div>
                        <div class="flex justify-between"><span>Tax:</span><strong><?php echo formatPrice($order['tax_amount']); ?></strong></div>
                        <div class="flex justify-between"><span>Delivery Fee:</span><strong><?php echo formatPrice($order['delivery_fee']); ?></strong></div>
                        <div class="flex justify-between text-base font-extrabold text-[#D97706] pt-2 border-t border-[#E8DCCB]">
                            <span>Grand Total:</span>
                            <span><?php echo formatPrice($order['total_amount']); ?></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
