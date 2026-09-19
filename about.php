<?php
// about.php - Our Story & Heritage Page
$pageTitle = "About Our Story - MBC Malek Bateta Center";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Banner -->
<section class="bg-[#3A1812] text-white py-14 border-b-4 border-[#D97706]">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <span class="text-xs font-bold text-[#FBBF24] uppercase tracking-widest block mb-1">Our Heritage</span>
        <h1 class="font-serif-heading text-4xl font-extrabold text-white">Traditional Taste of Dhoraji Since 1983</h1>
    </div>
</section>

<!-- Our Story Content -->
<section class="py-16 bg-[#FFFBF4]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
            <div class="space-y-6">
                <span class="text-xs font-extrabold text-[#D97706] uppercase tracking-wider block">Est. 1983 in Dhoraji</span>
                <h2 class="font-serif-heading text-3xl font-extrabold text-[#3A1812] leading-tight">
                    Preserving the Legacy of Original Dhoraji Bateta & Gravy
                </h2>
                <p class="text-sm text-gray-700 leading-relaxed">
                    Malek Bateta Center (MBC) started as a small street corner kitchen in Dhoraji, Gujarat in 1983 with a simple vision: to prepare the most delicious, savory, spicy garlic potato curry paired with fresh golden bhungla (pipe snacks).
                </p>
                <p class="text-sm text-gray-700 leading-relaxed">
                    Over four decades later, our secret blend of dry red chillies, roasted garlic, tamarind tanginess, and slow-cooked gravy remains unchanged. Today, MBC is a trusted food spot visited by generations of food lovers!
                </p>
            </div>
            
            <div class="mbc-card p-6 bg-[#FAF4EB] rounded-3xl border-2 border-[#D97706] shadow-xl text-center space-y-4">
                <div class="w-20 h-20 bg-[#3A1812] text-[#FBBF24] rounded-full mx-auto flex items-center justify-center text-3xl font-serif-heading font-extrabold shadow-md">
                    MBC
                </div>
                <h3 class="font-serif-heading font-bold text-xl text-[#3A1812]">Traditional Taste of Dhoraji</h3>
                <p class="text-xs text-gray-600 italic">"Taste the authentic flavor that has satisfied thousands of food lovers for over 40 years."</p>
                <div class="pt-4 border-t border-[#E8DCCB] grid grid-cols-3 gap-2">
                    <div>
                        <span class="block text-xl font-bold text-[#D97706]">1983</span>
                        <span class="text-[10px] text-gray-500 uppercase font-semibold">Origin</span>
                    </div>
                    <div>
                        <span class="block text-xl font-bold text-[#3A1812]">40+</span>
                        <span class="text-[10px] text-gray-500 uppercase font-semibold">Years</span>
                    </div>
                    <div>
                        <span class="block text-xl font-bold text-[#D97706]">100%</span>
                        <span class="text-[10px] text-gray-500 uppercase font-semibold">Authentic</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Core Values Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-8 border-t border-[#E8DCCB]">
            <div class="mbc-card p-6 bg-white rounded-2xl border border-[#E8DCCB] text-center space-y-3">
                <div class="w-12 h-12 bg-[#3A1812] text-[#FBBF24] rounded-full mx-auto flex items-center justify-center text-xl">
                    <i class="fa-solid fa-[#FBBF24] fa-pepper-hot"></i>
                </div>
                <h4 class="font-bold text-base text-[#3A1812]">Authentic Taste</h4>
                <p class="text-xs text-gray-600">Strictly following our traditional 1983 recipe without shortcuts.</p>
            </div>

            <div class="mbc-card p-6 bg-white rounded-2xl border border-[#E8DCCB] text-center space-y-3">
                <div class="w-12 h-12 bg-[#D97706] text-white rounded-full mx-auto flex items-center justify-center text-xl">
                    <i class="fa-solid fa-pump-soap"></i>
                </div>
                <h4 class="font-bold text-base text-[#3A1812]">Clean Hygiene</h4>
                <p class="text-xs text-gray-600">Fresh daily ingredients cooked in a pristine hygienic kitchen.</p>
            </div>

            <div class="mbc-card p-6 bg-white rounded-2xl border border-[#E8DCCB] text-center space-y-3">
                <div class="w-12 h-12 bg-[#3A1812] text-[#FBBF24] rounded-full mx-auto flex items-center justify-center text-xl">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <h4 class="font-bold text-base text-[#3A1812]">Customer First</h4>
                <p class="text-xs text-gray-600">Friendly service, fast preparation, and warm hospitality.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
