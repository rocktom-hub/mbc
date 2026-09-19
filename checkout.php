<?php
// checkout.php - Order Checkout & Online Payment Page
require_once __DIR__ . '/config/helpers.php';

$cart = getCart();
if (empty($cart)) {
    header('Location: ' . baseUrl('menu.php'));
    exit;
}

$subtotal = getCartSubtotal();
$deliveryFee = (float)getSetting('delivery_fee', '30.00');
$taxRate = (float)getSetting('tax_rate', '5.00');
$taxAmount = ($subtotal * $taxRate) / 100;
$grandTotal = $subtotal + $deliveryFee + $taxAmount;

// Default pre-fills if logged in
$defaultName = currentUserName();
$defaultEmail = currentUserEmail();
$defaultPhone = '';
$defaultAddress = '';

if (isLoggedIn()) {
    $db = getDB();
    $stmt = $db->prepare("SELECT phone, address FROM users WHERE id = ?");
    $stmt->execute([currentUserId()]);
    if ($u = $stmt->fetch()) {
        $defaultPhone = $u['phone'];
        $defaultAddress = $u['address'];
    }
}

$error = '';

// Handle Form Submission BEFORE HTML output to prevent "headers already sent" error
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = sanitize($_POST['customer_name'] ?? '');
    $customerEmail = sanitize($_POST['customer_email'] ?? '');
    $customerPhone = sanitize($_POST['customer_phone'] ?? '');
    $deliveryAddress = sanitize($_POST['delivery_address'] ?? '');
    $orderType = sanitize($_POST['order_type'] ?? 'Home Delivery');
    $paymentMethod = sanitize($_POST['payment_method'] ?? 'Cash on Delivery');
    $notes = sanitize($_POST['notes'] ?? '');
    $transactionRef = sanitize($_POST['transaction_ref'] ?? '');

    if (!empty($transactionRef)) {
        $notes .= " | Payment Ref: " . $transactionRef;
    }

    if (empty($customerName) || empty($customerEmail) || empty($customerPhone) || empty($deliveryAddress)) {
        $error = "Please complete all customer & delivery address details.";
    } else {
        try {
            $db = getDB();
            $db->beginTransaction();

            $orderNumber = 'MBC-' . rand(100000, 999999);
            $userId = isLoggedIn() ? currentUserId() : null;

            // Handle takeaway/dine-in fee logic
            $actualDeliveryFee = ($orderType === 'Home Delivery') ? $deliveryFee : 0.00;
            $finalTotal = $subtotal + $taxAmount + $actualDeliveryFee;

            $stmt = $db->prepare("INSERT INTO orders (order_number, user_id, customer_name, customer_email, customer_phone, delivery_address, order_type, payment_method, subtotal, tax_amount, delivery_fee, total_amount, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?)");
            $stmt->execute([
                $orderNumber,
                $userId,
                $customerName,
                $customerEmail,
                $customerPhone,
                $deliveryAddress,
                $orderType,
                $paymentMethod,
                $subtotal,
                $taxAmount,
                $actualDeliveryFee,
                $finalTotal,
                $notes
            ]);

            $orderId = $db->lastInsertId();

            // Insert order items
            $itemStmt = $db->prepare("INSERT INTO order_items (order_id, food_id, food_name, price, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($cart as $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                $itemStmt->execute([
                    $orderId,
                    $item['id'],
                    $item['name'],
                    $item['price'],
                    $item['quantity'],
                    $itemTotal
                ]);
            }

            $db->commit();
            clearCart();

            // Redirect cleanly without header error
            header('Location: ' . baseUrl('order-success.php?order=' . $orderNumber));
            exit;

        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $error = "Failed to place order: " . $e->getMessage();
        }
    }
}

// Require Header AFTER POST processing
$pageTitle = "Checkout & Online Payment - MBC Malek Bateta Center";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Banner -->
<section class="bg-[#3A1812] text-white py-10 border-b-4 border-[#D97706]">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="font-serif-heading text-3xl font-extrabold text-[#FBBF24]">Checkout & Payment Options</h1>
        <p class="text-xs text-gray-300 mt-1">Provide delivery address & complete secure online payment</p>
    </div>
</section>

<section class="py-12 bg-[#FFFBF4]" x-data="{ paymentType: 'COD', isProcessing: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <?php if ($error): ?>
            <div class="p-4 mb-6 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <form action="<?php echo baseUrl('checkout.php'); ?>" method="POST" @submit="isProcessing = true">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Delivery & Customer Information -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- 1. Order Type -->
                    <div class="mbc-card bg-white p-6 rounded-2xl border border-[#E8DCCB] shadow space-y-3">
                        <h3 class="font-serif-heading font-bold text-lg text-[#3A1812] border-b border-[#E8DCCB] pb-2">1. Select Order Type</h3>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="border-2 border-[#D97706] bg-[#FAF4EB] p-3 rounded-xl cursor-pointer flex flex-col items-center justify-center text-center">
                                <input type="radio" name="order_type" value="Home Delivery" checked class="accent-[#D97706] mb-1">
                                <span class="font-bold text-xs text-[#3A1812]">Home Delivery</span>
                                <span class="text-[10px] text-gray-500">Delivered hot</span>
                            </label>
                            <label class="border border-[#E8DCCB] hover:border-[#D97706] p-3 rounded-xl cursor-pointer flex flex-col items-center justify-center text-center">
                                <input type="radio" name="order_type" value="Takeaway" class="accent-[#D97706] mb-1">
                                <span class="font-bold text-xs text-[#3A1812]">Takeaway</span>
                                <span class="text-[10px] text-gray-500">Pickup at counter</span>
                            </label>
                            <label class="border border-[#E8DCCB] hover:border-[#D97706] p-3 rounded-xl cursor-pointer flex flex-col items-center justify-center text-center">
                                <input type="radio" name="order_type" value="Dine-in" class="accent-[#D97706] mb-1">
                                <span class="font-bold text-xs text-[#3A1812]">Dine-in</span>
                                <span class="text-[10px] text-gray-500">Eat at restaurant</span>
                            </label>
                        </div>
                    </div>

                    <!-- 2. Customer Info -->
                    <div class="mbc-card bg-white p-6 rounded-2xl border border-[#E8DCCB] shadow space-y-4">
                        <h3 class="font-serif-heading font-bold text-lg text-[#3A1812] border-b border-[#E8DCCB] pb-2">2. Customer & Delivery Address</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-[#3A1812] mb-1">Full Name *</label>
                                <input type="text" name="customer_name" required value="<?php echo htmlspecialchars($defaultName); ?>" class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#3A1812] mb-1">Email Address *</label>
                                <input type="email" name="customer_email" required value="<?php echo htmlspecialchars($defaultEmail); ?>" class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#3A1812] mb-1">Phone Number (For Tracking & SMS) *</label>
                            <input type="text" name="customer_phone" required value="<?php echo htmlspecialchars($defaultPhone); ?>" class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="+91 98989 89898">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#3A1812] mb-1">Full Delivery Address *</label>
                            <textarea name="delivery_address" rows="3" required class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="Street, House/Flat No, Area, Dhoraji..."><?php echo htmlspecialchars($defaultAddress); ?></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#3A1812] mb-1">Special Instructions / Notes</label>
                            <input type="text" name="notes" class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="e.g. Extra spicy red gravy, separate bhungla packet...">
                        </div>
                    </div>

                    <!-- 3. Dynamic Payment Methods & Online Payment -->
                    <div class="mbc-card bg-white p-6 rounded-2xl border border-[#E8DCCB] shadow space-y-4">
                        <h3 class="font-serif-heading font-bold text-lg text-[#3A1812] border-b border-[#E8DCCB] pb-2">3. Payment Methods</h3>
                        
                        <div class="space-y-3">
                            <!-- Option A: Cash on Delivery -->
                            <label @click="paymentType = 'COD'" class="flex items-center p-3.5 border rounded-xl cursor-pointer transition" :class="paymentType === 'COD' ? 'border-[#D97706] bg-[#FAF4EB]' : 'border-[#E8DCCB]'">
                                <input type="radio" name="payment_method" value="Cash on Delivery" checked class="accent-[#D97706] mr-3">
                                <div>
                                    <span class="block font-bold text-xs text-[#3A1812]"><i class="fa-solid fa-money-bill-wave text-emerald-600 mr-2"></i>Cash on Delivery (COD)</span>
                                    <span class="text-[10px] text-gray-500">Pay cash upon delivery or pickup counter</span>
                                </div>
                            </label>

                            <!-- Option B: Online UPI Payment (QR Code / Apps) -->
                            <label @click="paymentType = 'UPI'" class="flex items-center p-3.5 border rounded-xl cursor-pointer transition" :class="paymentType === 'UPI' ? 'border-[#D97706] bg-[#FAF4EB]' : 'border-[#E8DCCB]'">
                                <input type="radio" name="payment_method" value="UPI" class="accent-[#D97706] mr-3">
                                <div>
                                    <span class="block font-bold text-xs text-[#3A1812]"><i class="fa-solid fa-qrcode text-[#D97706] mr-2"></i>UPI / QR Code (GPay / PhonePe / Paytm)</span>
                                    <span class="text-[10px] text-gray-500">Scan QR code or pay directly to <strong>mbc@upi</strong></span>
                                </div>
                            </label>

                            <!-- Option C: Online Credit/Debit Card / NetBanking -->
                            <label @click="paymentType = 'CARD'" class="flex items-center p-3.5 border rounded-xl cursor-pointer transition" :class="paymentType === 'CARD' ? 'border-[#D97706] bg-[#FAF4EB]' : 'border-[#E8DCCB]'">
                                <input type="radio" name="payment_method" value="Credit/Debit Card" class="accent-[#D97706] mr-3">
                                <div>
                                    <span class="block font-bold text-xs text-[#3A1812]"><i class="fa-solid fa-credit-card text-blue-600 mr-2"></i>Credit / Debit Card / NetBanking</span>
                                    <span class="text-[10px] text-gray-500">Instant online card verification & payment</span>
                                </div>
                            </label>
                        </div>

                        <!-- Dynamic Interactive Details for UPI Online Payment -->
                        <div x-show="paymentType === 'UPI'" class="p-4 bg-[#FAF4EB] rounded-xl border border-[#E8DCCB] space-y-3 mt-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-[#3A1812]">MBC Official Merchant UPI:</span>
                                <span class="bg-[#3A1812] text-[#FBBF24] font-extrabold px-3 py-1 rounded text-xs select-all">mbc@upi</span>
                            </div>
                            <div class="text-center p-3 bg-white rounded-lg border border-[#E8DCCB]">
                                <p class="text-[11px] text-gray-600 mb-2 font-semibold">Scan QR Code using GPay, PhonePe, Paytm, or BHIM:</p>
                                <svg class="w-32 h-32 mx-auto" viewBox="0 0 100 100" fill="#3A1812">
                                    <rect width="100" height="100" fill="#FFFFFF" />
                                    <path d="M10 10 h30 v30 h-30 z M15 15 h20 v20 h-20 z M20 20 h10 v10 h-10 z" />
                                    <path d="M60 10 h30 v30 h-30 z M65 15 h20 v20 h-20 z M70 20 h10 v10 h-10 z" />
                                    <path d="M10 60 h30 v30 h-30 z M15 65 h20 v20 h-20 z M20 70 h10 v10 h-10 z" />
                                    <rect x="50" y="50" width="10" height="10" />
                                    <rect x="70" y="50" width="15" height="15" />
                                    <rect x="50" y="70" width="20" height="20" />
                                    <rect x="80" y="80" width="10" height="10" />
                                </svg>
                                <span class="text-xs font-bold text-[#D97706] block mt-1">Total to Pay: <?php echo formatPrice($grandTotal); ?></span>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-[#3A1812] mb-1">UPI Transaction Reference / UTR Number (Optional)</label>
                                <input type="text" name="transaction_ref" class="w-full px-3 py-2 text-xs bg-white border border-[#E8DCCB] rounded-lg font-medium" placeholder="e.g. 429183928102">
                            </div>
                        </div>

                        <!-- Dynamic Interactive Details for Credit / Debit Card Online Payment -->
                        <div x-show="paymentType === 'CARD'" class="p-4 bg-[#FAF4EB] rounded-xl border border-[#E8DCCB] space-y-3 mt-3">
                            <span class="text-xs font-bold text-[#3A1812] block border-b border-[#E8DCCB] pb-1"><i class="fa-solid fa-[#D97706] fa-shield-halved text-[#D97706] mr-1"></i> Secure Online Card Gateway</span>
                            <div>
                                <label class="block text-[11px] font-bold text-[#3A1812] mb-1">Cardholder Name</label>
                                <input type="text" class="w-full px-3 py-2 text-xs bg-white border border-[#E8DCCB] rounded-lg font-medium" placeholder="Aarav Patel">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-[#3A1812] mb-1">Card Number</label>
                                    <input type="text" maxlength="19" class="w-full px-3 py-2 text-xs bg-white border border-[#E8DCCB] rounded-lg font-medium" placeholder="4111 •••• •••• 1111">
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[11px] font-bold text-[#3A1812] mb-1">Expiry</label>
                                        <input type="text" placeholder="MM/YY" class="w-full px-3 py-2 text-xs bg-white border border-[#E8DCCB] rounded-lg font-medium">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-[#3A1812] mb-1">CVV</label>
                                        <input type="password" maxlength="4" placeholder="•••" class="w-full px-3 py-2 text-xs bg-white border border-[#E8DCCB] rounded-lg font-medium">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Order Summary Sidebar -->
                <div>
                    <div class="mbc-card bg-white p-6 rounded-2xl border border-[#E8DCCB] shadow-lg sticky top-24 space-y-4">
                        <h3 class="font-serif-heading font-bold text-lg text-[#3A1812] border-b border-[#E8DCCB] pb-3">Your Order Summary</h3>

                        <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                            <?php foreach ($cart as $item): ?>
                                <div class="flex justify-between items-center text-xs">
                                    <div>
                                        <span class="font-bold text-[#3A1812]"><?php echo htmlspecialchars($item['name']); ?></span>
                                        <span class="text-gray-500 text-[10px] block">Qty: <?php echo $item['quantity']; ?></span>
                                    </div>
                                    <span class="font-extrabold text-[#D97706]"><?php echo formatPrice($item['price'] * $item['quantity']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="pt-3 border-t border-[#E8DCCB] space-y-1.5 text-xs text-gray-600">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="font-bold text-[#3A1812]"><?php echo formatPrice($subtotal); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tax (<?php echo $taxRate; ?>%)</span>
                                <span class="font-bold text-[#3A1812]"><?php echo formatPrice($taxAmount); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Delivery Fee</span>
                                <span class="font-bold text-[#3A1812]"><?php echo formatPrice($deliveryFee); ?></span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-[#E8DCCB] flex justify-between items-center text-sm font-extrabold text-[#3A1812]">
                            <span>Total Amount</span>
                            <span class="text-xl text-[#D97706]"><?php echo formatPrice($grandTotal); ?></span>
                        </div>

                        <button type="submit" :disabled="isProcessing" class="w-full bg-[#D97706] hover:bg-[#B45309] text-white font-extrabold text-sm py-4 rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-lock" x-show="!isProcessing"></i>
                            <i class="fa-solid fa-spinner animate-spin" x-show="isProcessing" style="display: none;"></i>
                            <span x-text="isProcessing ? 'Processing Online Order...' : 'Pay & Place Order'"></span>
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
