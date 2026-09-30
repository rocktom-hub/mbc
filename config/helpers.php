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

// Smart Image URL Resolver
function getImageUrl($imageName, $default = 'dhoraji_bateta.svg') {
    if (empty($imageName)) {
        return baseUrl('assets/images/' . $default);
    }

    $imageName = trim($imageName);

    if (filter_var($imageName, FILTER_VALIDATE_URL)) {
        return $imageName;
    }

    $baseDir = __DIR__ . '/../assets/images/';

    // 1. Direct file check
    if (file_exists($baseDir . $imageName) && !is_dir($baseDir . $imageName)) {
        return baseUrl('assets/images/' . $imageName);
    }

    // 2. Check if inside uploads/ subdirectory
    if (file_exists($baseDir . 'uploads/' . $imageName) && !is_dir($baseDir . 'uploads/' . $imageName)) {
        return baseUrl('assets/images/uploads/' . $imageName);
    }

    // 3. Check with alternate extensions (e.g. stored as .jpg but actual file is .png or vice versa)
    $pathInfo = pathinfo($imageName);
    $filenameWithoutExt = $pathInfo['filename'] ?? '';
    if (!empty($filenameWithoutExt)) {
        $extensions = ['png', 'jpg', 'jpeg', 'webp', 'svg'];
        foreach ($extensions as $ext) {
            $testFile = $filenameWithoutExt . '.' . $ext;
            if (file_exists($baseDir . $testFile)) {
                return baseUrl('assets/images/' . $testFile);
            }
            if (file_exists($baseDir . 'uploads/' . $testFile)) {
                return baseUrl('assets/images/uploads/' . $testFile);
            }
        }
    }

    // 4. Check seed SVG fallback (e.g. chana_bateta.jpg -> chana_bateta.svg)
    $svgName = preg_replace('/\.(jpg|jpeg|png)$/i', '.svg', $imageName);
    if ($svgName !== $imageName && file_exists($baseDir . $svgName)) {
        return baseUrl('assets/images/' . $svgName);
    }

    // 5. Default fallback
    if (file_exists($baseDir . $default)) {
        return baseUrl('assets/images/' . $default);
    }
    return baseUrl('assets/images/dhoraji_bateta.svg');
}

// Upload Image File Helper
function uploadImageFile($file, $targetDir = null) {
    if (!$targetDir) {
        $targetDir = __DIR__ . '/../assets/images/';
    }

    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'error' => 'Invalid file parameter.'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        switch ($file['error']) {
            case UPLOAD_ERR_NO_FILE:
                return ['success' => false, 'error' => 'No file was uploaded.'];
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return ['success' => false, 'error' => 'File size exceeds maximum allowed limit.'];
            default:
                return ['success' => false, 'error' => 'File upload error code: ' . $file['error']];
        }
    }

    // Max 10MB
    if ($file['size'] > 10 * 1024 * 1024) {
        return ['success' => false, 'error' => 'File size exceeds 10MB.'];
    }

    $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
        'image/svg+xml' => 'svg'
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    // Also check extension
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!isset($allowedMimes[$mimeType]) && !in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'])) {
        return ['success' => false, 'error' => 'Invalid file format. Allowed: JPG, PNG, WEBP, GIF, SVG.'];
    }

    $safeExt = $allowedMimes[$mimeType] ?? ($ext === 'jpeg' ? 'jpg' : $ext);
    $origName = pathinfo($file['name'], PATHINFO_FILENAME);
    $slugName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($origName));
    if (empty($slugName)) {
        $slugName = 'food';
    }
    
    // Generate safe filename with timestamp
    $fileName = $slugName . '_' . time() . '.' . $safeExt;
    $targetPath = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $fileName;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $moved = false;
    if (is_uploaded_file($file['tmp_name'])) {
        $moved = move_uploaded_file($file['tmp_name'], $targetPath);
    } else {
        $moved = @copy($file['tmp_name'], $targetPath) || @rename($file['tmp_name'], $targetPath);
    }

    if (!$moved) {
        return ['success' => false, 'error' => 'Failed to save uploaded file. Check folder permissions.'];
    }

    return ['success' => true, 'filename' => $fileName];
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
