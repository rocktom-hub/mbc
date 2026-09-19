<?php
// admin/categories.php - Food Categories Management
$pageTitle = "Food Categories - Admin MBC";
require_once __DIR__ . '/../config/helpers.php';
requireAdmin();

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name = sanitize($_POST['name']);
        $description = sanitize($_POST['description']);
        $stmt = $db->prepare("INSERT INTO categories (name, description, status) VALUES (?, ?, 'active')");
        $stmt->execute([$name, $description]);
        setFlash('success', 'Category added.');
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id'];
        $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        setFlash('info', 'Category deleted.');
    }
    header('Location: ' . baseUrl('admin/categories.php'));
    exit;
}

$categories = $db->query("SELECT c.*, COUNT(f.id) AS total_foods FROM categories c LEFT JOIN foods f ON c.id = f.category_id GROUP BY c.id ORDER BY c.id ASC")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<section class="py-10 bg-[#FFFBF4]" x-data="{ addModal: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <div class="flex-grow space-y-6">
                
                <div class="mbc-card p-5 bg-white rounded-2xl border border-[#E8DCCB] shadow flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="font-serif-heading font-extrabold text-2xl text-[#3A1812]">Manage Food Categories</h2>
                        <p class="text-xs text-gray-500">Organize food items by categories</p>
                    </div>
                    <button @click="addModal = true" class="bg-[#D97706] hover:bg-[#B45309] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Add Category
                    </button>
                </div>

                <div class="mbc-card bg-white rounded-2xl border border-[#E8DCCB] shadow overflow-hidden">
                    <table class="w-full text-left mbc-table text-xs">
                        <thead>
                            <tr>
                                <th>Category ID</th>
                                <th>Category Name</th>
                                <th>Description</th>
                                <th>Total Foods</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td class="font-bold text-[#3A1812]">#<?php echo $cat['id']; ?></td>
                                    <td><strong class="text-[#3A1812]"><?php echo htmlspecialchars($cat['name']); ?></strong></td>
                                    <td class="text-gray-600"><?php echo htmlspecialchars($cat['description']); ?></td>
                                    <td><span class="bg-[#FAF4EB] border border-[#E8DCCB] font-bold px-2.5 py-1 rounded-full text-[#D97706]"><?php echo $cat['total_foods']; ?> items</span></td>
                                    <td class="text-center">
                                        <form action="<?php echo baseUrl('admin/categories.php'); ?>" method="POST" onsubmit="return confirm('Delete category?');" class="inline">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold p-1">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal -->
                <div x-show="addModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
                    <div class="bg-white rounded-3xl max-w-md w-full p-6 border-2 border-[#D97706] shadow-2xl space-y-4">
                        <div class="flex justify-between items-center border-b border-[#E8DCCB] pb-3">
                            <h3 class="font-serif-heading font-bold text-lg text-[#3A1812]">Add Category</h3>
                            <button @click="addModal = false" class="text-gray-400"><i class="fa-solid fa-xmark text-xl"></i></button>
                        </div>
                        <form action="<?php echo baseUrl('admin/categories.php'); ?>" method="POST" class="space-y-4 text-xs">
                            <input type="hidden" name="action" value="add">
                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Category Name *</label>
                                <input type="text" name="name" required class="w-full px-4 py-2 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium">
                            </div>
                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Description</label>
                                <textarea name="description" rows="2" class="w-full px-4 py-2 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium"></textarea>
                            </div>
                            <div class="flex justify-end gap-3 pt-3">
                                <button type="button" @click="addModal = false" class="px-4 py-2 border rounded-xl font-bold">Cancel</button>
                                <button type="submit" class="px-5 py-2 bg-[#D97706] text-white font-bold rounded-xl">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
