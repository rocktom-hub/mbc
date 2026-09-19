<?php
// register.php - Customer Account Registration Page
require_once __DIR__ . '/config/helpers.php';

if (isLoggedIn()) {
    header('Location: ' . baseUrl('customer/index.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        $db = getDB();
        // Check if email already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "An account with this email already exists.";
        } else {
            $hashedPass = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $db->prepare("INSERT INTO users (name, email, phone, password, role, address, status) VALUES (?, ?, ?, ?, 'customer', ?, 'active')");
            $stmt->execute([$name, $email, $phone, $hashedPass, $address]);
            
            $newId = $db->lastInsertId();

            $_SESSION['user_id'] = $newId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'customer';

            setFlash('success', 'Account registered successfully! Welcome to MBC.');
            header('Location: ' . baseUrl('customer/index.php'));
            exit;
        }
    }
}

$pageTitle = "Register - MBC Malek Bateta Center";
require_once __DIR__ . '/includes/header.php';
?>

<section class="py-16 bg-[#FFFBF4] min-h-[75vh] flex items-center justify-center">
    <div class="max-w-lg w-full px-4">
        
        <div class="mbc-card bg-white p-8 rounded-3xl border border-[#E8DCCB] shadow-xl space-y-6">
            <div class="text-center">
                <div class="w-14 h-14 bg-[#D97706] text-white rounded-full mx-auto flex items-center justify-center text-2xl mb-3 shadow">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <h2 class="font-serif-heading font-extrabold text-2xl text-[#3A1812]">Create Account</h2>
                <p class="text-xs text-gray-500 mt-1">Join MBC to order food online & track orders</p>
            </div>

            <?php if ($error): ?>
                <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-xl flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form action="<?php echo baseUrl('register.php'); ?>" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-[#3A1812] mb-1">Full Name *</label>
                    <input type="text" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="Aarav Patel">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#3A1812] mb-1">Email Address *</label>
                        <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="aarav@gmail.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#3A1812] mb-1">Phone Number *</label>
                        <input type="text" name="phone" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="+91 98989 89898">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#3A1812] mb-1">Delivery Address</label>
                    <textarea name="address" rows="2" class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="Street, Area, Dhoraji..."><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#3A1812] mb-1">Password *</label>
                        <input type="password" name="password" required class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="Min. 6 chars">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#3A1812] mb-1">Confirm Password *</label>
                        <input type="password" name="confirm_password" required class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="Repeat password">
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#3A1812] hover:bg-[#D97706] text-white font-extrabold text-xs py-3.5 rounded-xl transition shadow-md">
                    Register Account
                </button>
            </form>

            <div class="pt-4 border-t border-[#E8DCCB] text-center">
                <p class="text-xs text-gray-600">
                    Already have an account? 
                    <a href="<?php echo baseUrl('login.php'); ?>" class="text-[#D97706] font-bold hover:underline">Sign In Here</a>
                </p>
            </div>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
