<?php
// config/helpers.php - General Helper Functions & Session Setup
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

// Base URL detection
function baseUrl($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    // Normalize root path
    $base = rtrim($protocol . "://" . $host . rtrim(str_replace('\\', '/', $scriptDir), '/'), '/');
    
    // If inside admin or customer subfolder, adjust root
    if (strpos($_SERVER['SCRIPT_NAME'], '/admin') !== false) {
        $base = dirname($base);
    } elseif (strpos($_SERVER['SCRIPT_NAME'], '/customer') !== false) {
        $base = dirname($base);
    }
    
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

// Sanitize user inputs
function sanitize($input) {
    if (is_array($input)) {
        foreach ($input as $key => $val) {
            $input[$key] = sanitize($val);
        }
        return $input;
    }
    return htmlspecialchars(trim($input ?? ''), ENT_QUOTES, 'UTF-8');
}

// Currency Formatter
function formatPrice($amount) {
    return '₹ ' . number_format((float)$amount, 2);
}

// Fetch Restaurant Setting
function getSetting($key, $default = '') {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}

// Auth Helpers
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function currentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function currentUserName() {
    return $_SESSION['user_name'] ?? 'Guest';
}

function currentUserEmail() {
    return $_SESSION['user_email'] ?? '';
}

function requireAuth() {
    if (!isLoggedIn()) {
        setFlash('warning', 'Please login to access this page.');
        header('Location: ' . baseUrl('login.php'));
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        setFlash('danger', 'Admin access required.');
        header('Location: ' . baseUrl('login.php'));
        exit;
    }
}

// Flash Messages
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type, // success, danger, warning, info
        'message' => $message
    ];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Cart Management (Session Based)
function getCart() {
    return $_SESSION['cart'] ?? [];
}

function getCartCount() {
    $cart = getCart();
    $count = 0;
    foreach ($cart as $item) {
        $count += $item['quantity'];
    }
    return $count;
}

function getCartSubtotal() {
    $cart = getCart();
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += ($item['price'] * $item['quantity']);
    }
    return $subtotal;
}

function addToCart($foodId, $qty = 1) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM foods WHERE id = ? AND is_available = 1");
    $stmt->execute([$foodId]);
    $food = $stmt->fetch();

    if (!$food) return false;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$foodId])) {
        $_SESSION['cart'][$foodId]['quantity'] += $qty;
    } else {
        $_SESSION['cart'][$foodId] = [
            'id' => $food['id'],
            'name' => $food['name'],
            'price' => (float)$food['price'],
            'image' => $food['image'],
            'quantity' => (int)$qty
        ];
    }
    return true;
}

function updateCartQuantity($foodId, $qty) {
    if (isset($_SESSION['cart'][$foodId])) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$foodId]);
        } else {
            $_SESSION['cart'][$foodId]['quantity'] = (int)$qty;
        }
    }
}

function removeFromCart($foodId) {
    if (isset($_SESSION['cart'][$foodId])) {
        unset($_SESSION['cart'][$foodId]);
    }
}

function clearCart() {
    $_SESSION['cart'] = [];
}

// Render Order Status Badge
function renderStatusBadge($status) {
    switch ($status) {
        case 'Pending':
            return '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 border border-amber-300">Pending</span>';
        case 'Preparing':
            return '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-300">Preparing</span>';
        case 'Out for Delivery':
            return '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-300">Out for Delivery</span>';
        case 'Completed':
            return '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300">Completed</span>';
        case 'Cancelled':
            return '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800 border border-rose-300">Cancelled</span>';
        default:
            return '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-300">' . htmlspecialchars($status) . '</span>';
    }
}
