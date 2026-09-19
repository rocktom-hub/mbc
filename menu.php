<?php
// menu.php - Interactive Food Menu Page
$pageTitle = "Our Full Menu - MBC Malek Bateta Center";
require_once __DIR__ . '/includes/header.php';

$db = getDB();

// Selected category filter
$catId = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch all active categories
$categories = $db->query("SELECT * FROM categories WHERE status = 'active' ORDER BY id ASC")->fetchAll();

// Build Query for Foods
$sql = "SELECT f.*, c.name AS category_name FROM foods f LEFT JOIN categories c ON f.category_id = c.id WHERE f.is_available = 1";
$params = [];

if ($catId > 0) {
    $sql .= " AND f.category_id = ?";
    $params[] = $catId;
}

if (!empty($search)) {
    $sql .= " AND (f.name LIKE ? OR f.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY f.is_featured DESC, f.id ASC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$foods = $stmt->fetchAll();
?>

<!-- Menu Banner -->
<section class="bg-[#3A1812] text-white py-12 border-b-4 border-[#D97706]">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="font-serif-heading text-4xl font-extrabold text-[#FBBF24] mb-2">Our Delicious Menu</h1>
        <p class="text-xs sm:text-sm text-gray-300 max-w-lg mx-auto">Explore authentic Dhoraji Bateta recipes, savory combo meals, crisp bhungla packets, and chilled buttermilk.</p>
    </div>
</section>

<!-- Filter & Search Bar Section -->
<section class="py-8 bg-[#FAF4EB] border-b border-[#E8DCCB] sticky top-20 z-30 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Category Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 scrollbar-none">
                <a href="<?php echo baseUrl('menu.php'); ?>" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap <?php echo $catId == 0 ? 'bg-[#D97706] text-white shadow-md' : 'bg-white text-[#3A1812] hover:bg-[#F3E6D5] border border-[#E8DCCB]'; ?>">
                    <i class="fa-solid fa-border-all mr-1.5"></i> All Items
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="<?php echo baseUrl('menu.php?cat=' . $cat['id']); ?>" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap <?php echo $catId == $cat['id'] ? 'bg-[#D97706] text-white shadow-md' : 'bg-white text-[#3A1812] hover:bg-[#F3E6D5] border border-[#E8DCCB]'; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Search Form -->
            <form action="<?php echo baseUrl('menu.php'); ?>" method="GET" class="w-full md:w-72 flex items-center">
                <?php if ($catId > 0): ?>
                    <input type="hidden" name="cat" value="<?php echo $catId; ?>">
                <?php endif; ?>
                <div class="relative w-full">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search Bateta, Wings..." class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium text-[#3A1812]">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                </div>
            </form>

        </div>
    </div>
</section>

<!-- Food Items Grid -->
<section class="py-12 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <?php if (empty($foods)): ?>
            <div class="text-center py-16 bg-white rounded-2xl border border-[#E8DCCB] p-8 max-w-md mx-auto">
                <i class="fa-solid fa-utensils text-4xl text-[#D97706] mb-3"></i>
                <h3 class="font-bold text-lg text-[#3A1812] mb-1">No Menu Items Found</h3>
                <p class="text-xs text-gray-500 mb-4">Try searching for something else or clear filters.</p>
                <a href="<?php echo baseUrl('menu.php'); ?>" class="inline-block bg-[#3A1812] text-white text-xs font-bold px-4 py-2 rounded-lg">View All Items</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($foods as $food): 
                    $imgPath = str_replace('.jpg', '.svg', $food['image']);
                    if (!file_exists(__DIR__ . '/assets/images/' . $imgPath)) {
                        $imgPath = 'dhoraji_bateta.svg';
                    }
                ?>
                    <div class="mbc-card bg-white rounded-2xl overflow-hidden hover:shadow-xl transition flex flex-col justify-between border border-[#E8DCCB]">
                        <div class="relative">
                            <img src="<?php echo baseUrl('assets/images/' . $imgPath); ?>" alt="<?php echo htmlspecialchars($food['name']); ?>" class="w-full h-48 object-cover">
                            <?php if ($food['is_featured']): ?>
                                <span class="absolute top-3 left-3 bg-[#D97706] text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full shadow uppercase tracking-wider">
                                    Bestseller
                                </span>
                            <?php endif; ?>
                            <span class="absolute top-3 right-3 bg-[#3A1812] text-[#FBBF24] text-[10px] font-bold px-2.5 py-1 rounded-full shadow">
                                <?php echo htmlspecialchars($food['category_name']); ?>
                            </span>
                        </div>
                        <div class="p-5 flex-grow flex flex-col justify-between space-y-3">
                            <div>
                                <h3 class="font-serif-heading font-bold text-lg text-[#3A1812] mb-1">
                                    <?php echo htmlspecialchars($food['name']); ?>
                                </h3>
                                <p class="text-xs text-gray-600 leading-relaxed line-clamp-2">
                                    <?php echo htmlspecialchars($food['description']); ?>
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-[#F3E6D5]">
                                <div>
                                    <span class="text-xs text-gray-400 block font-medium">Price</span>
                                    <span class="text-lg font-extrabold text-[#D97706]">
                                        <?php echo formatPrice($food['price']); ?>
                                    </span>
                                </div>
                                <button onclick="addToCartMenu(<?php echo $food['id']; ?>)" class="bg-[#3A1812] hover:bg-[#D97706] text-white px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow">
                                    <i class="fa-solid fa-cart-plus"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<script>
function addToCartMenu(foodId) {
    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('food_id', foodId);
    formData.append('quantity', 1);

    fetch('<?php echo baseUrl("api/cart-actions.php"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            updateCartCount(data.cart_count);
            alert('Added to cart!');
        } else {
            alert(data.message);
        }
    })
    .catch(err => console.error(err));
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
