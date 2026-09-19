<?php
// admin/reviews.php - Customer Reviews Management
$pageTitle = "Reviews - Admin MBC";
require_once __DIR__ . '/../config/helpers.php';
requireAdmin();

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $status = sanitize($_POST['status']);
    if ($_POST['action'] === 'delete') {
        $stmt = $db->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        setFlash('info', 'Review deleted.');
    } else {
        $stmt = $db->prepare("UPDATE reviews SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        setFlash('success', 'Review status updated.');
    }
    header('Location: ' . baseUrl('admin/reviews.php'));
    exit;
}

$reviews = $db->query("SELECT * FROM reviews ORDER BY id DESC")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<section class="py-10 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <div class="flex-grow space-y-6">
                <div class="mbc-card p-5 bg-white rounded-2xl border border-[#E8DCCB] shadow">
                    <h2 class="font-serif-heading font-extrabold text-2xl text-[#3A1812]">Customer Reviews</h2>
                    <p class="text-xs text-gray-500">Approve or moderate customer feedback shown on home page</p>
                </div>

                <div class="mbc-card bg-white rounded-2xl border border-[#E8DCCB] shadow overflow-hidden">
                    <table class="w-full text-left mbc-table text-xs">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reviews as $rev): ?>
                                <tr>
                                    <td><strong class="text-[#3A1812]"><?php echo htmlspecialchars($rev['customer_name']); ?></strong></td>
                                    <td>
                                        <div class="flex text-amber-500 text-[10px]">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i class="fa-solid fa-star <?php echo $i <= $rev['rating'] ? 'text-amber-500' : 'text-gray-300'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="text-gray-700 italic max-w-xs">"<?php echo htmlspecialchars($rev['comment']); ?>"</td>
                                    <td>
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full <?php echo $rev['status'] === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'; ?>">
                                            <?php echo ucfirst($rev['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <form action="<?php echo baseUrl('admin/reviews.php'); ?>" method="POST" class="inline">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="id" value="<?php echo $rev['id']; ?>">
                                                <input type="hidden" name="status" value="<?php echo $rev['status'] === 'approved' ? 'pending' : 'approved'; ?>">
                                                <button type="submit" class="px-2.5 py-1 text-[10px] font-bold rounded-lg border <?php echo $rev['status'] === 'approved' ? 'border-amber-400 text-amber-700 hover:bg-amber-50' : 'border-emerald-400 text-emerald-700 hover:bg-emerald-50'; ?>">
                                                    <?php echo $rev['status'] === 'approved' ? 'Hide' : 'Approve'; ?>
                                                </button>
                                            </form>
                                            <form action="<?php echo baseUrl('admin/reviews.php'); ?>" method="POST" onsubmit="return confirm('Delete review?');" class="inline">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $rev['id']; ?>">
                                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold p-1"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
