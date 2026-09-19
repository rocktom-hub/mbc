<?php
// admin/settings.php - Store Settings Management
$pageTitle = "Store Settings - Admin MBC";
require_once __DIR__ . '/../config/helpers.php';
requireAdmin();

$db = getDB();
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settingsToUpdate = [
        'restaurant_name' => sanitize($_POST['restaurant_name'] ?? ''),
        'tagline' => sanitize($_POST['tagline'] ?? ''),
        'phone' => sanitize($_POST['phone'] ?? ''),
        'email' => sanitize($_POST['email'] ?? ''),
        'address' => sanitize($_POST['address'] ?? ''),
        'opening_hours' => sanitize($_POST['opening_hours'] ?? ''),
        'delivery_fee' => (float)($_POST['delivery_fee'] ?? 30.00),
        'tax_rate' => (float)($_POST['tax_rate'] ?? 5.00)
    ];

    $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    foreach ($settingsToUpdate as $key => $val) {
        $stmt->execute([$key, $val]);
    }

    setFlash('success', 'Store settings updated successfully!');
    header('Location: ' . baseUrl('admin/settings.php'));
    exit;
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="py-10 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <div class="flex-grow space-y-6">
                <div class="mbc-card p-6 bg-white rounded-2xl border border-[#E8DCCB] shadow space-y-6">
                    <h2 class="font-serif-heading font-extrabold text-2xl text-[#3A1812] border-b border-[#E8DCCB] pb-3">Restaurant Settings</h2>

                    <form action="<?php echo baseUrl('admin/settings.php'); ?>" method="POST" class="space-y-4 max-w-2xl text-xs">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Restaurant Name *</label>
                                <input type="text" name="restaurant_name" required value="<?php echo htmlspecialchars(getSetting('restaurant_name')); ?>" class="w-full px-4 py-2.5 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium">
                            </div>
                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Tagline</label>
                                <input type="text" name="tagline" value="<?php echo htmlspecialchars(getSetting('tagline')); ?>" class="w-full px-4 py-2.5 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Contact Phone *</label>
                                <input type="text" name="phone" required value="<?php echo htmlspecialchars(getSetting('phone')); ?>" class="w-full px-4 py-2.5 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium">
                            </div>
                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Contact Email *</label>
                                <input type="email" name="email" required value="<?php echo htmlspecialchars(getSetting('email')); ?>" class="w-full px-4 py-2.5 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-[#3A1812] mb-1">Full Restaurant Address *</label>
                            <textarea name="address" rows="2" required class="w-full px-4 py-2.5 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium"><?php echo htmlspecialchars(getSetting('address')); ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Opening Hours</label>
                                <input type="text" name="opening_hours" value="<?php echo htmlspecialchars(getSetting('opening_hours')); ?>" class="w-full px-4 py-2.5 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium">
                            </div>
                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Delivery Fee (₹)</label>
                                <input type="number" step="0.50" name="delivery_fee" value="<?php echo htmlspecialchars(getSetting('delivery_fee')); ?>" class="w-full px-4 py-2.5 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium">
                            </div>
                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Tax Rate (%)</label>
                                <input type="number" step="0.10" name="tax_rate" value="<?php echo htmlspecialchars(getSetting('tax_rate')); ?>" class="w-full px-4 py-2.5 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-[#E8DCCB]">
                            <button type="submit" class="bg-[#D97706] hover:bg-[#B45309] text-white font-extrabold text-xs px-6 py-3 rounded-xl transition shadow">
                                <i class="fa-solid fa-floppy-disk mr-1"></i> Save Store Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
