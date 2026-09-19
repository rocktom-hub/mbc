<?php
// includes/footer.php
?>
    </main> <!-- End Main -->

    <!-- Footer -->
    <footer class="bg-[#3A1812] text-[#F3EBDD] pt-12 pb-6 border-t-4 border-[#D97706] mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                <!-- Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#D97706] rounded-full flex items-center justify-center text-white">
                            <i class="fa-solid fa-bowl-food text-xl"></i>
                        </div>
                        <span class="font-serif-heading font-extrabold text-2xl text-white">MBC</span>
                    </div>
                    <p class="text-xs text-gray-300 leading-relaxed">
                        <strong>Malek Bateta Center</strong> has been serving the original & authentic savory Dhoraji Bateta since 1983. Taste our secret garlic gravy & crispy bhungla combo!
                    </p>
                    <div class="flex space-x-3 text-[#D97706]">
                        <a href="#" class="w-8 h-8 rounded-full bg-[#52251B] flex items-center justify-center hover:bg-[#D97706] hover:text-white transition"><i class="fa-brands fa-facebook-f text-xs"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-[#52251B] flex items-center justify-center hover:bg-[#D97706] hover:text-white transition"><i class="fa-brands fa-instagram text-xs"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-[#52251B] flex items-center justify-center hover:bg-[#D97706] hover:text-white transition"><i class="fa-brands fa-whatsapp text-xs"></i></a>
                    </div>
                </div>

                <!-- Quick Navigation -->
                <div>
                    <h4 class="font-serif-heading text-base font-bold text-white mb-4 border-b border-[#52251B] pb-2 inline-block">Quick Links</h4>
                    <ul class="space-y-2 text-xs text-gray-300">
                        <li><a href="<?php echo baseUrl('index.php'); ?>" class="hover:text-[#D97706] transition"><i class="fa-solid fa-chevron-right text-[10px] text-[#D97706] mr-2"></i>Home Page</a></li>
                        <li><a href="<?php echo baseUrl('menu.php'); ?>" class="hover:text-[#D97706] transition"><i class="fa-solid fa-chevron-right text-[10px] text-[#D97706] mr-2"></i>Our Full Menu</a></li>
                        <li><a href="<?php echo baseUrl('about.php'); ?>" class="hover:text-[#D97706] transition"><i class="fa-solid fa-chevron-right text-[10px] text-[#D97706] mr-2"></i>About Our Story</a></li>
                        <li><a href="<?php echo baseUrl('gallery.php'); ?>" class="hover:text-[#D97706] transition"><i class="fa-solid fa-chevron-right text-[10px] text-[#D97706] mr-2"></i>Photo Gallery</a></li>
                        <li><a href="<?php echo baseUrl('contact.php'); ?>" class="hover:text-[#D97706] transition"><i class="fa-solid fa-chevron-right text-[10px] text-[#D97706] mr-2"></i>Contact Us</a></li>
                    </ul>
                </div>

                <!-- Opening Hours -->
                <div>
                    <h4 class="font-serif-heading text-base font-bold text-white mb-4 border-b border-[#52251B] pb-2 inline-block">Opening Hours</h4>
                    <ul class="space-y-2 text-xs text-gray-300">
                        <li class="flex justify-between py-1 border-b border-[#52251B]/50">
                            <span>Monday - Sunday:</span>
                            <span class="font-semibold text-[#FBBF24]">10:00 AM - 10:00 PM</span>
                        </li>
                        <li class="flex justify-between py-1 border-b border-[#52251B]/50">
                            <span>Takeaway & Delivery:</span>
                            <span class="font-semibold text-emerald-400">Available Daily</span>
                        </li>
                        <li class="flex justify-between py-1">
                            <span>Dine-in Service:</span>
                            <span class="font-semibold text-white">Open</span>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="font-serif-heading text-base font-bold text-white mb-4 border-b border-[#52251B] pb-2 inline-block">Visit & Reach Us</h4>
                    <ul class="space-y-3 text-xs text-gray-300">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot text-[#D97706] mt-1"></i>
                            <span>123 Main Road, Dhoraji, Gujarat, 360410</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-phone text-[#D97706]"></i>
                            <span>+91 98765 43210</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-envelope text-[#D97706]"></i>
                            <span>info@malekbatetacenter.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-6 border-t border-[#52251B] flex flex-col sm:flex-row items-center justify-between text-xs text-gray-400 gap-4">
                <p>&copy; <?php echo date('Y'); ?> <strong>MBC (Malek Bateta Center)</strong>. All Rights Reserved. Traditional Taste of Dhoraji Since 1983.</p>
                <div class="flex items-center space-x-4">
                    <a href="<?php echo baseUrl('login.php'); ?>" class="hover:text-white transition"><i class="fa-solid fa-user-lock mr-1"></i> Staff Login</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Cart Dynamic Script -->
    <script>
    function updateCartCount(count) {
        const badge = document.getElementById('cart-badge-count');
        if(badge) badge.innerText = count;
    }
    </script>
</body>
</html>
