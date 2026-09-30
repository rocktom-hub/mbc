<?php
// admin/gallery.php - Manage Gallery Photos with File Upload
$pageTitle = "Gallery - Admin MBC";
require_once __DIR__ . '/../config/helpers.php';
requireAdmin();

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $title = sanitize($_POST['title'] ?? '');
        $category = sanitize($_POST['category'] ?? 'Dishes');
        $image = sanitize($_POST['image'] ?? 'bread-bateta.jpg');

        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = uploadImageFile($_FILES['image_file']);
            if ($uploadResult['success']) {
                $image = $uploadResult['filename'];
            } else {
                setFlash('danger', 'Photo upload failed: ' . $uploadResult['error']);
                header('Location: ' . baseUrl('admin/gallery.php'));
                exit;
            }
        }

        $stmt = $db->prepare("INSERT INTO gallery (title, category, image) VALUES (?, ?, ?)");
        $stmt->execute([$title, $category, $image]);
        setFlash('success', 'Gallery photo added successfully.');
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id'];
        $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        setFlash('info', 'Gallery item deleted.');
    }
    header('Location: ' . baseUrl('admin/gallery.php'));
    exit;
}

$gallery = $db->query("SELECT * FROM gallery ORDER BY id DESC")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<section class="py-10 bg-[#FFFBF4]" x-data="{ 
    addModal: false,
    imagePreview: null,
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
                <div class="mbc-card p-5 bg-white rounded-2xl border border-[#E8DCCB] shadow flex justify-between items-center">
                    <div>
                        <h2 class="font-serif-heading font-extrabold text-2xl text-[#3A1812]">Gallery Management</h2>
                        <p class="text-xs text-gray-500">Upload & manage food and restaurant store photos</p>
                    </div>
                    <button @click="imagePreview = null; addModal = true" class="bg-[#D97706] hover:bg-[#B45309] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Add Photo
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <?php foreach ($gallery as $g): ?>
                        <div class="mbc-card bg-white rounded-2xl overflow-hidden border border-[#E8DCCB] shadow relative group">
                            <img src="<?php echo getImageUrl($g['image']); ?>" class="w-full h-44 object-cover">
                            <div class="p-3 flex justify-between items-center bg-[#FAF4EB]">
                                <div>
                                    <span class="text-[10px] font-bold text-[#D97706] block"><?php echo htmlspecialchars($g['category']); ?></span>
                                    <h4 class="font-bold text-xs text-[#3A1812]"><?php echo htmlspecialchars($g['title']); ?></h4>
                                </div>
                                <form action="<?php echo baseUrl('admin/gallery.php'); ?>" method="POST" onsubmit="return confirm('Delete item?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $g['id']; ?>">
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-bold p-1"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Add Modal with File Upload -->
                <div x-show="addModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
                    <div class="bg-white rounded-3xl max-w-md w-full p-6 border-2 border-[#D97706] shadow-2xl space-y-4">
                        <div class="flex justify-between items-center border-b border-[#E8DCCB] pb-3">
                            <h3 class="font-serif-heading font-bold text-lg text-[#3A1812]">Add Gallery Photo</h3>
                            <button @click="addModal = false" class="text-gray-400"><i class="fa-solid fa-xmark text-xl"></i></button>
                        </div>
                        <form action="<?php echo baseUrl('admin/gallery.php'); ?>" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                            <input type="hidden" name="action" value="add">
                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Title *</label>
                                <input type="text" name="title" required placeholder="e.g. Delicious Bateta" class="w-full px-4 py-2 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium focus:outline-none focus:border-[#D97706]">
                            </div>
                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Category</label>
                                <select name="category" class="w-full px-3 py-2 bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl font-medium focus:outline-none focus:border-[#D97706]">
                                    <option value="Dishes">Dishes</option>
                                    <option value="Combo">Combo</option>
                                    <option value="Kitchen">Kitchen</option>
                                    <option value="Store">Store</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-[#3A1812] mb-1">Select Photo *</label>
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
                                        <input type="file" name="image_file" accept="image/*" required @change="previewImage($event)" class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#D97706] file:text-white hover:file:bg-[#B45309] file:cursor-pointer">
                                        <p class="text-[10px] text-gray-400 mt-1">Upload JPG, PNG, WEBP (Max 10MB)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end gap-3 pt-3">
                                <button type="button" @click="addModal = false" class="px-4 py-2 border rounded-xl font-bold">Cancel</button>
                                <button type="submit" class="px-5 py-2 bg-[#D97706] text-white font-bold rounded-xl hover:bg-[#B45309]">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
