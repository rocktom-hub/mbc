<?php
// index.php - Home Page
$pageTitle = "Home - Bateta Made the Dhoraji Way";
require_once __DIR__ . '/includes/header.php';

$db = getDB();

// Fetch featured food items
$stmt = $db->query("SELECT f.*, c.name AS category_name FROM foods f LEFT JOIN categories c ON f.category_id = c.id WHERE f.is_available = 1 AND f.is_featured = 1 ORDER BY f.id ASC LIMIT 6");
$featuredFoods = $stmt->fetchAll();

// Fetch approved reviews
$reviewStmt = $db->query("SELECT * FROM reviews WHERE status = 'approved' ORDER BY id DESC LIMIT 3");
$reviews = $reviewStmt->fetchAll();
?>

<!-- Hero Banner Section matching Design Image 2 -->
<section class="bg-gradient-to-br from-[#FAF4EB] via-[#FFFBF4] to-[#F3E6D5] py-16 md:py-24 border-b border-[#E8DCCB] relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <!-- Left Text Content -->
            <div class="space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 bg-[#3A1812] text-[#FBBF24] px-4 py-1.5 rounded-full text-xs font-bold shadow-sm">
                    <i class="fa-solid fa-crown text-xs"></i>
                    <span>Traditional Taste of Dhoraji Since 1983</span>
                </div>
                
                <h1 class="font-serif-heading text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#3A1812] leading-tight">
                    Bateta Made the <br class="hidden sm:inline">
                    <span class="text-[#D97706] italic">Dhoraji Way</span>
                </h1>
                
                <p class="text-base sm:text-lg text-gray-700 max-w-xl font-medium leading-relaxed mx-auto lg:mx-0">
                    Serving authentic & delicious Dhoraji Bateta prepared with rich garlic spices, tangy red gravy, and golden crispy bhungla. Experiencing the original legacy recipe perfected over 40+ years.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4">
                    <a href="<?php echo baseUrl('menu.php'); ?>" class="w-full sm:w-auto bg-[#D97706] hover:bg-[#B45309] text-white font-extrabold text-base px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center justify-center gap-3">
                        <i class="fa-solid fa-utensils"></i>
                        <span>Order Now</span>
                    </a>
                    <a href="<?php echo baseUrl('about.php'); ?>" class="w-full sm:w-auto bg-[#FAF4EB] hover:bg-[#E8DCCB] text-[#3A1812] border-2 border-[#3A1812] font-bold text-base px-8 py-4 rounded-xl transition flex items-center justify-center gap-2">
                        <span>Our Heritage</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Stats Highlights -->
                <div class="grid grid-cols-3 gap-4 pt-8 border-t border-[#E8DCCB]/80 max-w-lg">
                    <div>
                        <span class="block text-2xl sm:text-3xl font-extrabold text-[#3A1812]">1983</span>
                        <span class="text-xs font-semibold text-gray-600">Established</span>
                    </div>
                    <div>
                        <span class="block text-2xl sm:text-3xl font-extrabold text-[#D97706]">40+</span>
                        <span class="text-xs font-semibold text-gray-600">Years of Taste</span>
                    </div>
                    <div>
                        <span class="block text-2xl sm:text-3xl font-extrabold text-[#3A1812]">50k+</span>
                        <span class="text-xs font-semibold text-gray-600">Happy Foodies</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Dish Illustration / Graphics -->
            <div class="relative flex justify-center">
                <div class="w-80 h-80 sm:w-96 sm:h-96 rounded-full bg-[#E8DCCB]/40 absolute -z-10 animate-pulse"></div>
                <div class="mbc-card p-4 bg-white rounded-3xl shadow-2xl border-4 border-[#D97706] max-w-md w-full transform rotate-1 hover:rotate-0 transition duration-300">
                    <img src="<?php echo baseUrl('assets/images/dhoraji_bateta.svg'); ?>" alt="Dhoraji Bateta Special" class="w-full h-72 object-cover rounded-2xl">
                    <div class="mt-4 flex items-center justify-between px-2">
                        <div>
                            <span class="text-xs font-bold text-[#D97706] uppercase tracking-wider">#1 Bestseller</span>
                            <h3 class="font-serif-heading font-bold text-lg text-[#3A1812]">Dhoraji Bateta Special</h3>
                        </div>
                        <span class="bg-[#3A1812] text-[#FBBF24] font-extrabold px-3 py-1.5 rounded-lg text-sm">₹ 80.00</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Why Choose MBC? Section -->
<section class="py-16 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-extrabold text-[#D97706] uppercase tracking-widest block mb-1">Authentic Quality</span>
            <h2 class="font-serif-heading text-3xl sm:text-4xl font-extrabold text-[#3A1812]">Why Choose MBC?</h2>
            <div class="w-20 h-1 bg-[#D97706] mx-auto mt-3 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Feature 1 -->
            <div class="mbc-card p-6 rounded-2xl hover:shadow-lg transition border border-[#E8DCCB] bg-[#FAF4EB]">
                <div class="w-12 h-12 bg-[#3A1812] text-[#FBBF24] rounded-xl flex items-center justify-center text-xl mb-4 shadow">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h3 class="font-bold text-lg text-[#3A1812] mb-2">Established 1983</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Carrying forward over 4 decades of traditional recipe heritage from Dhoraji, Gujarat.</p>
            </div>

            <!-- Feature 2 -->
            <div class="mbc-card p-6 rounded-2xl hover:shadow-lg transition border border-[#E8DCCB] bg-[#FAF4EB]">
                <div class="w-12 h-12 bg-[#D97706] text-white rounded-xl flex items-center justify-center text-xl mb-4 shadow">
                    <i class="fa-solid fa-pepper-hot"></i>
                </div>
                <h3 class="font-bold text-lg text-[#3A1812] mb-2">Authentic Red Gravy</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Prepared daily with authentic garlic spices, dry red chillies, and savory tanginess.</p>
            </div>

            <!-- Feature 3 -->
            <div class="mbc-card p-6 rounded-2xl hover:shadow-lg transition border border-[#E8DCCB] bg-[#FAF4EB]">
                <div class="w-12 h-12 bg-[#3A1812] text-[#FBBF24] rounded-xl flex items-center justify-center text-xl mb-4 shadow">
                    <i class="fa-solid fa-[#FBBF24] fa-bowl-food"></i>
                </div>
                <h3 class="font-bold text-lg text-[#3A1812] mb-2">Crispy Bhungla Combo</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Signature crunchy pipe bhungla paired with hot, flavorful potato curry.</p>
            </div>

            <!-- Feature 4 -->
            <div class="mbc-card p-6 rounded-2xl hover:shadow-lg transition border border-[#E8DCCB] bg-[#FAF4EB]">
                <div class="w-12 h-12 bg-[#D97706] text-white rounded-xl flex items-center justify-center text-xl mb-4 shadow">
                    <i class="fa-solid fa-hand-holding-hand"></i>
                </div>
                <h3 class="font-bold text-lg text-[#3A1812] mb-2">Fresh Ingredients</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Only fresh potatoes, pure oils, and hand-ground spices used every day.</p>
            </div>

            <!-- Feature 5 -->
            <div class="mbc-card p-6 rounded-2xl hover:shadow-lg transition border border-[#E8DCCB] bg-[#FAF4EB]">
                <div class="w-12 h-12 bg-[#3A1812] text-[#FBBF24] rounded-xl flex items-center justify-center text-xl mb-4 shadow">
                    <i class="fa-solid fa-pump-soap"></i>
                </div>
                <h3 class="font-bold text-lg text-[#3A1812] mb-2">100% Hygiene Food</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Clean kitchen practices ensuring maximum freshness and health standards.</p>
            </div>

            <!-- Feature 6 -->
            <div class="mbc-card p-6 rounded-2xl hover:shadow-lg transition border border-[#E8DCCB] bg-[#FAF4EB]">
                <div class="w-12 h-12 bg-[#D97706] text-white rounded-xl flex items-center justify-center text-xl mb-4 shadow">
                    <i class="fa-solid fa-[#D97706] fa-[#D97706] fa-motorcycle"></i>
                </div>
                <h3 class="font-bold text-lg text-[#3A1812] mb-2">Hot Delivery & Takeaway</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Get your hot food delivered straight to your doorstep or ready for pickup.</p>
            </div>
        </div>
    </div>
</section>

<!-- Signature Food Menu Section -->
<section class="py-16 bg-[#FAF4EB] border-t border-b border-[#E8DCCB]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-xs font-extrabold text-[#D97706] uppercase tracking-widest block mb-1">Popular Choice</span>
                <h2 class="font-serif-heading text-3xl sm:text-4xl font-extrabold text-[#3A1812]">Our Signature Items</h2>
            </div>
            <a href="<?php echo baseUrl('menu.php'); ?>" class="inline-flex items-center gap-2 text-[#D97706] hover:text-[#B45309] font-bold text-sm mt-4 md:mt-0">
                <span>View Full Menu</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($featuredFoods as $food): 
                $imgPath = str_replace('.jpg', '.svg', $food['image']);
                if (!file_exists(__DIR__ . '/assets/images/' . $imgPath)) {
                    $imgPath = 'dhoraji_bateta.svg';
                }
            ?>
                <div class="mbc-card bg-white rounded-2xl overflow-hidden hover:shadow-xl transition flex flex-col justify-between border border-[#E8DCCB]">
                    <div class="relative">
                        <img src="<?php echo baseUrl('assets/images/' . $imgPath); ?>" alt="<?php echo htmlspecialchars($food['name']); ?>" class="w-full h-48 object-cover">
                        <span class="absolute top-3 left-3 bg-[#3A1812] text-[#FBBF24] text-[10px] font-bold px-2.5 py-1 rounded-full shadow">
                            <?php echo htmlspecialchars($food['category_name'] ?? 'Special'); ?>
                        </span>
                    </div>
                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="font-serif-heading font-bold text-lg text-[#3A1812] mb-1">
                                <?php echo htmlspecialchars($food['name']); ?>
                            </h3>
                            <p class="text-xs text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                                <?php echo htmlspecialchars($food['description']); ?>
                            </p>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-[#F3E6D5]">
                            <span class="text-lg font-extrabold text-[#D97706]">
                                <?php echo formatPrice($food['price']); ?>
                            </span>
                            <button onclick="quickAddToCart(<?php echo $food['id']; ?>)" class="bg-[#3A1812] hover:bg-[#D97706] text-white px-3.5 py-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow">
                                <i class="fa-solid fa-cart-plus"></i> Add
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Customer Testimonials Section -->
<section class="py-16 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-12">
            <span class="text-xs font-extrabold text-[#D97706] uppercase tracking-widest block mb-1">Customer Reviews</span>
            <h2 class="font-serif-heading text-3xl font-extrabold text-[#3A1812]">What Our Foodies Say</h2>
            <div class="w-16 h-1 bg-[#D97706] mx-auto mt-3 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($reviews as $rev): ?>
                <div class="mbc-card p-6 bg-[#FAF4EB] rounded-2xl border border-[#E8DCCB] space-y-4">
                    <div class="flex items-center gap-1 text-amber-500">
                        <?php for($i=1; $i<=5; $i++): ?>
                            <i class="fa-solid fa-star text-xs <?php echo $i <= $rev['rating'] ? 'text-amber-500' : 'text-gray-300'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="text-xs text-gray-700 italic leading-relaxed">
                        "<?php echo htmlspecialchars($rev['comment']); ?>"
                    </p>
                    <div class="flex items-center gap-3 pt-3 border-t border-[#E8DCCB]">
                        <div class="w-9 h-9 bg-[#3A1812] text-[#FBBF24] rounded-full flex items-center justify-center font-bold text-xs">
                            <?php echo strtoupper(substr($rev['customer_name'], 0, 1)); ?>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-[#3A1812]"><?php echo htmlspecialchars($rev['customer_name']); ?></h4>
                            <span class="text-[10px] text-gray-500">Verified Customer</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Quick Add JS Script -->
<script>
function quickAddToCart(foodId) {
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
            alert('Item added to your cart!');
        } else {
            alert(data.message || 'Error adding item');
        }
    })
    .catch(err => console.error(err));
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
