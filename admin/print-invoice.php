<?php
// admin/print-invoice.php - Printable Order Receipt Invoice
require_once __DIR__ . '/../config/helpers.php';
requireAdmin();

$orderId = (int)($_GET['id'] ?? 0);
$db = getDB();

$stmt = $db->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found.");
}

$itemStmt = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
$itemStmt->execute([$orderId]);
$items = $itemStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - <?php echo htmlspecialchars($order['order_number']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: sans-serif; background: #fff; color: #000; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="p-8 max-w-xl mx-auto border border-gray-300 rounded-xl my-6">

    <div class="flex justify-between items-center no-print mb-6 pb-4 border-b">
        <a href="<?php echo baseUrl('admin/orders.php'); ?>" class="text-xs font-bold text-gray-600 hover:text-black"><i class="fa-solid fa-arrow-left"></i> Back to Orders</a>
        <button onclick="window.print()" class="bg-[#3A1812] text-white px-4 py-2 rounded text-xs font-bold"><i class="fa-solid fa-print"></i> Print Receipt</button>
    </div>

    <!-- Header -->
    <div class="text-center pb-4 border-b border-gray-300">
        <h1 class="text-2xl font-bold text-[#3A1812]">MBC (Malek Bateta Center)</h1>
        <p class="text-xs text-gray-600">Traditional Taste of Dhoraji Since 1983</p>
        <p class="text-xs text-gray-500 mt-1"><?php echo getSetting('address'); ?> | Tel: <?php echo getSetting('phone'); ?></p>
    </div>

    <!-- Order Info -->
    <div class="py-4 border-b border-gray-300 flex justify-between text-xs">
        <div>
            <p><strong>Order #:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
            <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
        </div>
        <div class="text-right">
            <p><strong>Date:</strong> <?php echo date('d M Y h:i A', strtotime($order['created_at'])); ?></p>
            <p><strong>Type:</strong> <?php echo htmlspecialchars($order['order_type']); ?></p>
            <p><strong>Payment:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
        </div>
    </div>

    <!-- Delivery Address -->
    <div class="py-2 border-b border-gray-300 text-xs">
        <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['delivery_address']); ?></p>
        <?php if (!empty($order['notes'])): ?>
            <p class="text-amber-700 mt-1"><strong>Notes:</strong> <?php echo htmlspecialchars($order['notes']); ?></p>
        <?php endif; ?>
    </div>

    <!-- Table -->
    <table class="w-full text-left text-xs my-4 border-collapse">
        <thead>
            <tr class="border-b border-gray-400 font-bold">
                <th class="py-2">Item Name</th>
                <th class="text-center py-2">Qty</th>
                <th class="text-right py-2">Price</th>
                <th class="text-right py-2">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $it): ?>
                <tr class="border-b border-gray-200">
                    <td class="py-2 font-semibold"><?php echo htmlspecialchars($it['food_name']); ?></td>
                    <td class="text-center py-2"><?php echo $it['quantity']; ?></td>
                    <td class="text-right py-2"><?php echo formatPrice($it['price']); ?></td>
                    <td class="text-right py-2 font-bold"><?php echo formatPrice($it['total_price']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Total -->
    <div class="text-xs space-y-1 text-right max-w-xs ml-auto pt-2 border-t border-gray-400">
        <div class="flex justify-between"><span>Subtotal:</span><span><?php echo formatPrice($order['subtotal']); ?></span></div>
        <div class="flex justify-between"><span>Tax:</span><span><?php echo formatPrice($order['tax_amount']); ?></span></div>
        <div class="flex justify-between"><span>Delivery Fee:</span><span><?php echo formatPrice($order['delivery_fee']); ?></span></div>
        <div class="flex justify-between text-sm font-bold border-t pt-1 border-gray-400">
            <span>Grand Total:</span>
            <span><?php echo formatPrice($order['total_amount']); ?></span>
        </div>
    </div>

    <div class="text-center pt-6 text-[10px] text-gray-500 border-t border-gray-300 mt-6">
        <p>Thank you for choosing MBC (Malek Bateta Center)!</p>
    </div>

</body>
</html>
