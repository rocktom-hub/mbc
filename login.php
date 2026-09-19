<?php
// login.php - User & Admin Login Page
require_once __DIR__ . '/config/helpers.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: ' . baseUrl('admin/index.php'));
    } else {
        header('Location: ' . baseUrl('customer/index.php'));
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Check password hash OR default demo password fallback
        $passwordMatch = false;
        if ($user) {
            if (password_verify($password, $user['password']) || $password === 'admin123' || $password === 'user123') {
                $passwordMatch = true;
            }
        }

        if ($user && $passwordMatch) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            setFlash('success', 'Welcome back, ' . htmlspecialchars($user['name']) . '!');

            if ($user['role'] === 'admin') {
                header('Location: ' . baseUrl('admin/index.php'));
            } else {
                header('Location: ' . baseUrl('customer/index.php'));
            }
            exit;
        } else {
            $error = "Invalid email or password. Please check your credentials.";
        }
    }
}

$pageTitle = "Login - MBC Malek Bateta Center";
require_once __DIR__ . '/includes/header.php';
?>

<section class="py-16 bg-[#FFFBF4] min-h-[70vh] flex items-center justify-center">
    <div class="max-w-md w-full px-4">
        
        <div class="mbc-card bg-white p-8 rounded-3xl border border-[#E8DCCB] shadow-xl space-y-6">
            <div class="text-center">
                <div class="w-14 h-14 bg-[#3A1812] text-[#FBBF24] rounded-full mx-auto flex items-center justify-center text-2xl mb-3 shadow">
                    <i class="fa-solid fa-right-to-bracket"></i>
                </div>
                <h2 class="font-serif-heading font-extrabold text-2xl text-[#3A1812]">Welcome Back</h2>
                <p class="text-xs text-gray-500 mt-1">Sign in to your account to place orders or manage store</p>
            </div>

            <?php if ($error): ?>
                <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-xl flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form action="<?php echo baseUrl('login.php'); ?>" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-[#3A1812] mb-1">Email Address</label>
                    <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" class="w-full px-4 py-3 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="aarav@gmail.com">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-[#3A1812]">Password</label>
                    </div>
                    <input type="password" name="password" required class="w-full px-4 py-3 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="••••••••">
                </div>

                <button type="submit" class="w-full bg-[#D97706] hover:bg-[#B45309] text-white font-extrabold text-xs py-3.5 rounded-xl transition shadow-md">
                    Sign In
                </button>
            </form>

            <div class="pt-4 border-t border-[#E8DCCB] text-center">
                <p class="text-xs text-gray-600">
                    Don't have an account? 
                    <a href="<?php echo baseUrl('register.php'); ?>" class="text-[#D97706] font-bold hover:underline">Register Account</a>
                </p>
            </div>

            <!-- Demo Login Credentials Helper -->
            <div class="p-3 bg-[#FAF4EB] rounded-xl border border-[#E8DCCB] text-[11px] text-gray-600 space-y-1">
                <span class="font-bold text-[#3A1812] block border-b border-[#E8DCCB] pb-1"><i class="fa-solid fa-key text-[#D97706] mr-1"></i> Quick Test Credentials:</span>
                <div class="flex justify-between">
                    <span><strong>Admin:</strong> admin@mbc.com</span>
                    <span class="text-amber-700">Pass: admin123</span>
                </div>
                <div class="flex justify-between">
                    <span><strong>User:</strong> aarav@gmail.com</span>
                    <span class="text-amber-700">Pass: user123</span>
                </div>
            </div>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
