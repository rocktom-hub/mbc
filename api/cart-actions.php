<?php
// api/cart-actions.php
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$response = ['success' => false, 'message' => 'Invalid action'];

if ($action === 'add') {
    $foodId = (int)($_POST['food_id'] ?? 0);
    $qty = (int)($_POST['quantity'] ?? 1);
    if ($foodId > 0) {
        if (addToCart($foodId, $qty)) {
            $response = [
                'success' => true,
                'message' => 'Item added to cart!',
                'cart_count' => getCartCount(),
                'cart_subtotal' => formatPrice(getCartSubtotal())
            ];
        } else {
            $response['message'] = 'Item not available.';
        }
    }
} elseif ($action === 'update') {
    $foodId = (int)($_POST['food_id'] ?? 0);
    $qty = (int)($_POST['quantity'] ?? 1);
    updateCartQuantity($foodId, $qty);
    $response = [
        'success' => true,
        'message' => 'Cart updated',
        'cart_count' => getCartCount(),
        'cart_subtotal' => formatPrice(getCartSubtotal())
    ];
} elseif ($action === 'remove') {
    $foodId = (int)($_POST['food_id'] ?? 0);
    removeFromCart($foodId);
    $response = [
        'success' => true,
        'message' => 'Item removed from cart',
        'cart_count' => getCartCount(),
        'cart_subtotal' => formatPrice(getCartSubtotal())
    ];
} elseif ($action === 'clear') {
    clearCart();
    $response = [
        'success' => true,
        'message' => 'Cart cleared',
        'cart_count' => 0,
        'cart_subtotal' => formatPrice(0)
    ];
}

echo json_encode($response);
exit;
