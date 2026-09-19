<?php
// customer/profile.php - Edit Profile Page
$pageTitle = "My Profile - MBC";
require_once __DIR__ . '/../config/helpers.php';
requireAuth();

$db = getDB();
$userId = currentUserId();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');

    if (empty($name) || empty($phone)) {
        $error = "Name and Phone number are required.";
    } else {
        $stmt = $db->prepare("UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $address, $userId]);
        $_SESSION['user_name'] = $name;
        $success = "Profile updated successfully!";
    }
}

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

require_once __DIR__ . '/../includes/header.php';
?>

<section class="py-10 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <div class="flex-grow space-y-6">
                <div class="mbc-card p-6 bg-white rounded-2xl border border-[#E8DCCB] shadow space-y-6">
                    <h2 class="font-serif-heading font-extrabold text-xl text-[#3A1812] border-b border-[#E8DCCB] pb-3">Edit Profile Information</h2>

                    <?php if ($success): ?>
                        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-xl"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-xl"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="<?php echo baseUrl('customer/profile.php'); ?>" method="POST" class="space-y-4 max-w-lg">
                        <div>
                            <label class="block text-xs font-bold text-[#3A1812] mb-1">Full Name</label>
                            <input type="text" name="name" required value="<?php echo htmlspecialchars($user['name']); ?>" class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#3A1812] mb-1">Email Address (Cannot be changed)</label>
                            <input type="email" disabled value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-4 py-2.5 text-xs bg-gray-100 border border-gray-300 rounded-xl font-medium text-gray-500 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#3A1812] mb-1">Phone Number</label>
                            <input type="text" name="phone" required value="<?php echo htmlspecialchars($user['phone']); ?>" class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#3A1812] mb-1">Default Delivery Address</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="bg-[#D97706] hover:bg-[#B45309] text-white font-extrabold text-xs px-6 py-3 rounded-xl transition shadow">
                            Save Profile Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
