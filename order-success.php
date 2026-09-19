<?php
// order-success.php - Order Receipt Confirmation Page
$pageTitle = "Order Receipt - MBC Malek Bateta Center";
require_once __DIR__ . '/includes/header.php';

$orderNum = sanitize($_GET['order'] ?? '');
if (empty($orderNum)) {
    header('Location: ' . baseUrl('index.php'));
    exit;
}

$db = getDB();
$stmt = $db->prepare("SELECT * FROM orders WHERE order_number = ?");
$stmt->execute([$orderNum]);
$order = $stmt->fetch();

if (!$order) {
    echo '<div class="p-8 text-center font-bold text-rose-600">Order not found.</div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$itemStmt = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
$itemStmt->execute([$order['id']]);
$items = $itemStmt->fetchAll();
?>

<section class="py-12 bg-[#FFFBF4]">
    <div class="max-w-3xl mx-auto px-4">
        
        <div class="mbc-card bg-white p-8 rounded-3xl border border-[#E8DCCB] shadow-xl space-y-8" id="printable-invoice">
            
            <!-- Success Icon & Header -->
            <div class="text-center space-y-2 pb-6 border-b border-[#E8DCCB]">
                <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full mx-auto flex items-center justify-center text-3xl shadow">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h2 class="font-serif-heading font-extrabold text-2xl text-[#3A1812]">Order Placed Successfully!</h2>
                <p class="text-xs text-gray-500">Thank you for ordering from <strong>MBC (Malek Bateta Center)</strong></p>
                <div class="inline-block bg-[#FAF4EB] border border-[#E8DCCB] px-4 py-1.5 rounded-full text-xs font-bold text-[#D97706] mt-2">
                    Order ID: <?php echo htmlspecialchars($order['order_number']); ?>
                </div>
            </div>

            <!-- Order Status Progress Timeline -->
            <div class="bg-[#FAF4EB] p-6 rounded-2xl border border-[#E8DCCB] space-y-4">
                <h4 class="font-bold text-xs uppercase tracking-wider text-[#3A1812] text-center">Live Order Tracking</h4>
                
                <div class="flex items-center justify-between text-center relative px-4">
                    <div class="z-10 flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold shadow">1</div>
                        <span class="text-[11px] font-bold mt-1 text-[#3A1812]">Order Placed</span>
                    </div>

                    <div class="z-10 flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full <?php echo in_array($order['status'], ['Preparing', 'Out for Delivery', 'Completed']) ? 'bg-emerald-600 text-white' : 'bg-gray-300 text-gray-600'; ?> flex items-center justify-center text-xs font-bold shadow">2</div>
                        <span class="text-[11px] font-bold mt-1 text-[#3A1812]">Preparing</span>
                    </div>

                    <div class="z-10 flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full <?php echo in_array($order['status'], ['Out for Delivery', 'Completed']) ? 'bg-emerald-600 text-white' : 'bg-gray-300 text-gray-600'; ?> flex items-center justify-center text-xs font-bold shadow">3</div>
                        <span class="text-[11px] font-bold mt-1 text-[#3A1812]">Out for Delivery</span>
                    </div>

                    <div class="z-10 flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full <?php echo $order['status'] === 'Completed' ? 'bg-emerald-600 text-white' : 'bg-gray-300 text-gray-600'; ?> flex items-center justify-center text-xs font-bold shadow">4</div>
                        <span class="text-[11px] font-bold mt-1 text-[#3A1812]">Completed</span>
                    </div>
                </div>
            </div>

            <!-- Customer & Delivery Summary -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-[#FAF4EB] p-4 rounded-xl border border-[#E8DCCB]">
                <div>
                    <span class="text-gray-500 font-semibold block">Customer Name:</span>
                    <strong class="text-[#3A1812]"><?php echo htmlspecialchars($order['customer_name']); ?></strong>
                    <span class="text-gray-500 font-semibold block mt-2">Phone:</span>
                    <strong class="text-[#3A1812]"><?php echo htmlspecialchars($order['customer_phone']); ?></strong>
                </div>
                <div>
                    <span class="text-gray-500 font-semibold block">Order Type & Payment:</span>
                    <strong class="text-[#3A1812]"><?php echo htmlspecialchars($order['order_type']); ?> (<?php echo htmlspecialchars($order['payment_method']); ?>)</strong>
                    <span class="text-gray-500 font-semibold block mt-2">Delivery Address:</span>
                    <strong class="text-[#3A1812]"><?php echo htmlspecialchars($order['delivery_address']); ?></strong>
                </div>
            </div>

            <!-- Itemized Table -->
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

            <!-- Calculations -->
            <div class="text-xs space-y-1.5 max-w-xs ml-auto text-right font-medium">
                <div class="flex justify-between"><span>Subtotal:</span><strong><?php echo formatPrice($order['subtotal']); ?></strong></div>
                <div class="flex justify-between"><span>Tax:</span><strong><?php echo formatPrice($order['tax_amount']); ?></strong></div>
                <div class="flex justify-between"><span>Delivery Fee:</span><strong><?php echo formatPrice($order['delivery_fee']); ?></strong></div>
                <div class="flex justify-between text-base font-extrabold text-[#D97706] pt-2 border-t border-[#E8DCCB]">
                    <span>Total Amount:</span>
                    <span><?php echo formatPrice($order['total_amount']); ?></span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-4 border-t border-[#E8DCCB]">
                <button onclick="window.print()" class="bg-[#FAF4EB] hover:bg-[#E8DCCB] text-[#3A1812] border border-[#3A1812] px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2">
                    <i class="fa-solid fa-print"></i> Print Receipt
                </button>
                <a href="<?php echo isLoggedIn() ? baseUrl('customer/orders.php') : baseUrl('menu.php'); ?>" class="bg-[#D97706] hover:bg-[#B45309] text-white px-5 py-2 rounded-xl text-xs font-extrabold transition">
                    <?php echo isLoggedIn() ? 'View My Orders' : 'Back to Menu'; ?>
                </a>
            </div>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
