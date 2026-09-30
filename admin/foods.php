<?php
// admin/foods.php - Manage Foods CRUD with File Upload
$pageTitle = "Manage Foods - Admin MBC";
require_once __DIR__ . '/../config/helpers.php';
requireAdmin();

$db = getDB();

// Handle Form Submissions: Add / Edit / Delete / Toggle Availability
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $name = sanitize($_POST['name'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $price = (float)($_POST['price'] ?? 0);
        $description = sanitize($_POST['description'] ?? '');
        $image = sanitize($_POST['image'] ?? 'dhoraji_bateta.jpg');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isAvailable = isset($_POST['is_available']) ? 1 : 0;

        // Handle image file upload if provided
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = uploadImageFile($_FILES['image_file']);
            if ($uploadResult['success']) {
                $image = $uploadResult['filename'];
            } else {
                setFlash('danger', 'Image upload failed: ' . $uploadResult['error']);
                header('Location: ' . baseUrl('admin/foods.php'));
                exit;
            }
        } elseif (empty($image)) {
            $image = 'dhoraji_bateta.jpg';
        }

        if ($action === 'add') {
            $stmt = $db->prepare("INSERT INTO foods (category_id, name, description, price, image, is_featured, is_available) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$categoryId, $name, $description, $price, $image, $isFeatured, $isAvailable]);
            setFlash('success', 'Food item added successfully!');
        } else {
            $id = (int)$_POST['id'];
            $stmt = $db->prepare("UPDATE foods SET category_id = ?, name = ?, description = ?, price = ?, image = ?, is_featured = ?, is_available = ? WHERE id = ?");
            $stmt->execute([$categoryId, $name, $description, $price, $image, $isFeatured, $isAvailable, $id]);
            setFlash('success', 'Food item updated successfully!');
        }
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id'];
        $stmt = $db->prepare("DELETE FROM foods WHERE id = ?");
        $stmt->execute([$id]);
        setFlash('info', 'Food item deleted.');
    } elseif ($action === 'toggle_stock') {
        $id = (int)$_POST['id'];
        $status = (int)$_POST['status'];
        $stmt = $db->prepare("UPDATE foods SET is_available = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        setFlash('success', 'Stock status updated.');
    }
    header('Location: ' . baseUrl('admin/foods.php'));
    exit;
}

// Fetch Foods & Categories
$categories = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
$foods = $db->query("SELECT f.*, c.name AS category_name FROM foods f LEFT JOIN categories c ON f.category_id = c.id ORDER BY f.id DESC")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<section class="py-10 bg-[#FFFBF4]" x-data="{ 
    modalOpen: false, 
    editMode: false, 
    imagePreview: null,
    form: { id: 0, name: '', category_id: '<?php echo $categories[0]['id'] ?? 1; ?>', price: '', description: '', image: 'dhoraji_bateta.jpg', is_featured: false, is_available: true },
    openAddModal() {
        this.editMode = false;
        this.imagePreview = null;
        this.form = { id: 0, name: '', category_id: '<?php echo $categories[0]['id'] ?? 1; ?>', price: '', description: '', image: 'dhoraji_bateta.jpg', is_featured: false, is_available: true };
        this.modalOpen = true;
    },
    openEditModal(item, imgUrl) {
        this.editMode = true;
        this.form = Object.assign({}, item);
        this.imagePreview = imgUrl;
        this.modalOpen = true;
    },
    previewImage(e) {
        const file = e.target.files[0];
        if (file) {
            this.imagePreview = URL.createObjectURL(file);
        }
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <div class="flex-grow space-y-6">
                
                <div class="mbc-card p-5 bg-white rounded-2xl border border-[#E8DCCB] shadow flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="font-serif-heading font-extrabold text-2xl text-[#3A1812]">Manage Food Items</h2>
                        <p class="text-xs text-gray-500">Add, edit, or update menu dish pricing, images & availability</p>
                    </div>
                    <button @click="openAddModal()" class="bg-[#D97706] hover:bg-[#B45309] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Add New Food Item
                    </button>
                </div>

                <!-- Table -->
                <div class="mbc-card bg-white rounded-2xl border border-[#E8DCCB] shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left mbc-table text-xs">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Bestseller</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($foods as $food): 
                                    $imgUrl = getImageUrl($food['image']);
                                ?>
                                    <tr>
                                        <td>
                                            <img src="<?php echo $imgUrl; ?>" class="w-12 h-12 rounded-lg object-cover border border-[#E8DCCB]">
                                        </td>
                                        <td>
                                            <strong class="text-[#3A1812] block"><?php echo htmlspecialchars($food['name']); ?></strong>
                                            <span class="text-[10px] text-gray-500 line-clamp-1"><?php echo htmlspecialchars($food['description'] ?? ''); ?></span>
                                        </td>
                                        <td><span class="font-bold text-gray-700"><?php echo htmlspecialchars($food['category_name'] ?? 'Uncategorized'); ?></span></td>
                                        <td class="font-extrabold text-[#D97706]"><?php echo formatPrice($food['price']); ?></td>
                                        <td>
                                            <form action="<?php echo baseUrl('admin/foods.php'); ?>" method="POST" class="inline">
                                                <input type="hidden" name="action" value="toggle_stock">
                                                <input type="hidden" name="id" value="<?php echo $food['id']; ?>">
                                                <input type="hidden" name="status" value="<?php echo $food['is_available'] ? 0 : 1; ?>">
                                                <button type="submit" class="px-2.5 py-1 text-[10px] font-bold rounded-full <?php echo $food['is_available'] ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'; ?>">
                                                    <?php echo $food['is_available'] ? 'Available' : 'Out of Stock'; ?>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <?php echo $food['is_featured'] ? '<span class="text-amber-600 font-bold"><i class="fa-solid fa-star"></i> Featured</span>' : '<span class="text-gray-400">Regular</span>'; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button @click="openEditModal(<?php echo htmlspecialchars(json_encode($food)); ?>, '<?php echo $imgUrl; ?>')" class="text-blue-600 hover:text-blue-800 font-bold p-1" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <form action="<?php echo baseUrl('admin/foods.php'); ?>" method="POST" onsubmit="return confirm('Delete this food item?');" class="inline">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $food['id']; ?>">
                                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold p-1" title="Delete">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add/Edit Modal with File Upload -->
                <div x-show="modalOpen" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
                    <div class="bg-white rounded-3xl max-w-lg w-full p-6 border-2 border-[#D97706] shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-center border-b border-[#E8DCCB] pb-3">
                            <h3 class="font-serif-heading font-bold text-lg text-[#3A1812]" x-text="editMode ? 'Edit Food Item' : 'Add New Food Item'"></h3>
                            <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
                        </div>

                        <form action="<?php echo baseUrl('admin/foods.php'); ?>" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                            <input type="hidden" name="action" :value="editMode ? 'edit' : 'add'">
                            <input type="hidden" name="id" :value="form.id">

                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Item Name *</label>
                                <input type="text" name="name" required x-model="form.name" class="w-full px-4 py-2 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium focus:outline-none focus:border-[#D97706]">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-[#3A1812] mb-1">Category *</label>
                                    <select name="category_id" x-model="form.category_id" class="w-full px-3 py-2 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium focus:outline-none focus:border-[#D97706]">
                                        <?php foreach ($categories as $c): ?>
                                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-bold text-[#3A1812] mb-1">Price (₹) *</label>
                                    <input type="number" step="0.50" name="price" required x-model="form.price" class="w-full px-4 py-2 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium focus:outline-none focus:border-[#D97706]">
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Description</label>
                                <textarea name="description" rows="2" x-model="form.description" class="w-full px-4 py-2 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium focus:outline-none focus:border-[#D97706]"></textarea>
                            </div>

                            <!-- Upload Image Field with Live Preview -->
                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Food Image</label>
                                <div class="flex items-center gap-4 p-3 bg-[#FAF4EB] rounded-2xl border border-[#E8DCCB]">
                                    <div class="relative w-16 h-16 rounded-xl border-2 border-dashed border-[#D97706] bg-white flex items-center justify-center overflow-hidden shrink-0">
                                        <template x-if="imagePreview">
                                            <img :src="imagePreview" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!imagePreview">
                                            <i class="fa-solid fa-image text-gray-400 text-2xl"></i>
                                        </template>
                                    </div>
                                    <div class="flex-grow">
                                        <input type="file" name="image_file" accept="image/*" @change="previewImage($event)" class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#D97706] file:text-white hover:file:bg-[#B45309] file:cursor-pointer">
                                        <p class="text-[10px] text-gray-400 mt-1">Upload JPG, PNG, WEBP (Max 10MB)</p>
                                    </div>
                                </div>
                                <input type="hidden" name="image" x-model="form.image">
                            </div>

                            <div class="flex gap-6 items-center pt-2">
                                <label class="flex items-center gap-2 cursor-pointer font-bold text-[#3A1812]">
                                    <input type="checkbox" name="is_featured" value="1" :checked="form.is_featured == 1" class="accent-[#D97706]">
                                    <span>Bestseller / Featured</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer font-bold text-[#3A1812]">
                                    <input type="checkbox" name="is_available" value="1" :checked="form.is_available == 1" class="accent-[#D97706]">
                                    <span>In Stock (Available)</span>
                                </label>
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t border-[#E8DCCB]">
                                <button type="button" @click="modalOpen = false" class="px-4 py-2 border border-gray-300 rounded-xl text-gray-600 font-bold">Cancel</button>
                                <button type="submit" class="px-5 py-2 bg-[#D97706] hover:bg-[#B45309] text-white rounded-xl font-bold">Save Item</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
