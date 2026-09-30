<?php
// gallery.php - Photo Gallery Page
$pageTitle = "Photo Gallery - MBC Malek Bateta Center";
require_once __DIR__ . '/includes/header.php';

$db = getDB();
$galleryItems = $db->query("SELECT * FROM gallery ORDER BY id DESC")->fetchAll();
?>

<!-- Banner -->
<section class="bg-[#3A1812] text-white py-12 border-b-4 border-[#D97706]">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="font-serif-heading text-4xl font-extrabold text-[#FBBF24] mb-2">Our Food & Kitchen Gallery</h1>
        <p class="text-xs sm:text-sm text-gray-300">A glimpse into our freshly prepared dishes, kitchen, and traditional counter.</p>
    </div>
</section>

<!-- Gallery Grid -->
<section class="py-12 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($galleryItems as $item): ?>
                <div class="mbc-card bg-white rounded-2xl overflow-hidden shadow hover:shadow-xl transition border border-[#E8DCCB] group">
                    <div class="relative overflow-hidden h-56">
                        <img src="<?php echo getImageUrl($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                            <span class="text-xs font-bold bg-[#D97706] px-3 py-1.5 rounded-full shadow"><i class="fa-solid fa-eye mr-1"></i> Preview</span>
                        </div>
                    </div>
                    <div class="p-4 bg-[#FAF4EB]">
                        <span class="text-[10px] font-bold text-[#D97706] uppercase tracking-wider block mb-1"><?php echo htmlspecialchars($item['category']); ?></span>
                        <h4 class="font-bold text-sm text-[#3A1812]"><?php echo htmlspecialchars($item['title']); ?></h4>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
