<?php
// cart.php - Shopping Cart Page
$pageTitle = "Your Shopping Cart - MBC Malek Bateta Center";
require_once __DIR__ . '/includes/header.php';

$cart = getCart();
$subtotal = getCartSubtotal();
$deliveryFee = (float)getSetting('delivery_fee', '30.00');
$taxRate = (float)getSetting('tax_rate', '5.00');
$taxAmount = ($subtotal * $taxRate) / 100;
$grandTotal = $subtotal > 0 ? ($subtotal + $deliveryFee + $taxAmount) : 0;
?>

<!-- Banner -->
<section class="bg-[#3A1812] text-white py-10 border-b-4 border-[#D97706]">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="font-serif-heading text-3xl font-extrabold text-[#FBBF24]">Your Shopping Cart</h1>
        <p class="text-xs text-gray-300 mt-1">Review your selected items before proceeding to checkout</p>
    </div>
</section>

<section class="py-12 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <?php if (empty($cart)): ?>
            <div class="mbc-card bg-white p-12 rounded-3xl text-center max-w-lg mx-auto border border-[#E8DCCB] space-y-4">
                <div class="w-16 h-16 bg-[#FAF4EB] text-[#D97706] rounded-full mx-auto flex items-center justify-center text-3xl">
                    <i class="fa-solid fa-basket-shopping"></i>
                </div>
                <h3 class="font-serif-heading font-bold text-2xl text-[#3A1812]">Your Cart is Empty</h3>
                <p class="text-xs text-gray-500">Looks like you haven't added any Dhoraji Bateta dishes yet.</p>
                <a href="<?php echo baseUrl('menu.php'); ?>" class="inline-block bg-[#D97706] hover:bg-[#B45309] text-white font-extrabold text-xs px-6 py-3 rounded-xl shadow transition">
                    Browse Our Menu
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Items Table -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="mbc-card bg-white rounded-2xl overflow-hidden border border-[#E8DCCB] shadow">
                        <table class="w-full text-left border-collapse mbc-table">
                            <thead>
                                <tr>
                                    <th>Food Item</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $id => $item): 
                                    $itemTotal = $item['price'] * $item['quantity'];
                                ?>
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <img src="<?php echo getImageUrl($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-12 h-12 rounded-lg object-cover border border-[#E8DCCB]">
                                                <span class="font-bold text-xs text-[#3A1812]"><?php echo htmlspecialchars($item['name']); ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center font-semibold text-xs text-gray-700">
                                            <?php echo formatPrice($item['price']); ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="inline-flex items-center border border-[#E8DCCB] rounded-lg bg-[#FAF4EB]">
                                                <button onclick="updateQty(<?php echo $id; ?>, <?php echo $item['quantity'] - 1; ?>)" class="px-2.5 py-1 text-xs font-bold text-[#3A1812] hover:bg-[#E8DCCB] rounded-l">-</button>
                                                <span class="px-3 text-xs font-bold text-[#3A1812]"><?php echo $item['quantity']; ?></span>
                                                <button onclick="updateQty(<?php echo $id; ?>, <?php echo $item['quantity'] + 1; ?>)" class="px-2.5 py-1 text-xs font-bold text-[#3A1812] hover:bg-[#E8DCCB] rounded-r">+</button>
                                            </div>
                                        </td>
                                        <td class="text-right font-extrabold text-xs text-[#D97706]">
                                            <?php echo formatPrice($itemTotal); ?>
                                        </td>
                                        <td class="text-center">
                                            <button onclick="removeItem(<?php echo $id; ?>)" class="text-rose-500 hover:text-rose-700 text-xs p-1" title="Remove">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="<?php echo baseUrl('menu.php'); ?>" class="text-xs font-bold text-[#3A1812] hover:text-[#D97706] flex items-center gap-2">
                            <i class="fa-solid fa-arrow-left"></i> Continue Shopping
                        </a>
                        <button onclick="clearCartAll()" class="text-xs font-bold text-rose-600 hover:text-rose-800">
                            <i class="fa-solid fa-broom mr-1"></i> Clear Cart
                        </button>
                    </div>
                </div>

                <!-- Order Summary Side Card -->
                <div>
                    <div class="mbc-card bg-white p-6 rounded-2xl border border-[#E8DCCB] shadow-lg space-y-4">
                        <h3 class="font-serif-heading font-bold text-lg text-[#3A1812] border-b border-[#E8DCCB] pb-3">Order Summary</h3>

                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-bold text-[#3A1812]"><?php echo formatPrice($subtotal); ?></span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Estimated Tax (<?php echo $taxRate; ?>%)</span>
                                <span class="font-bold text-[#3A1812]"><?php echo formatPrice($taxAmount); ?></span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Delivery Charge</span>
                                <span class="font-bold text-[#3A1812]"><?php echo formatPrice($deliveryFee); ?></span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-[#E8DCCB] flex justify-between items-center text-sm font-extrabold text-[#3A1812]">
                            <span>Grand Total</span>
                            <span class="text-xl text-[#D97706]"><?php echo formatPrice($grandTotal); ?></span>
                        </div>

                        <a href="<?php echo baseUrl('checkout.php'); ?>" class="block w-full bg-[#D97706] hover:bg-[#B45309] text-white text-center font-extrabold text-xs py-3.5 rounded-xl shadow transition">
                            Proceed to Checkout <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

            </div>
        <?php endif; ?>

    </div>
</section>

<script>
function updateQty(id, qty) {
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('food_id', id);
    formData.append('quantity', qty);

    fetch('<?php echo baseUrl("api/cart-actions.php"); ?>', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(() => location.reload());
}

function removeItem(id) {
    if(confirm('Remove this item from cart?')) {
        const formData = new FormData();
        formData.append('action', 'remove');
        formData.append('food_id', id);

        fetch('<?php echo baseUrl("api/cart-actions.php"); ?>', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(() => location.reload());
    }
}

function clearCartAll() {
    if(confirm('Clear your cart?')) {
        const formData = new FormData();
        formData.append('action', 'clear');

        fetch('<?php echo baseUrl("api/cart-actions.php"); ?>', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(() => location.reload());
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
