<?php
// contact.php - Contact Us Page
$pageTitle = "Contact Us - MBC Malek Bateta Center";
require_once __DIR__ . '/includes/header.php';

$successMsg = '';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $errorMsg = "Please fill in all required fields (Name, Email, Message).";
    } else {
        try {
            $db = getDB();
            $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $subject, $message]);
            $successMsg = "Thank you! Your message has been sent successfully. We will contact you soon.";
        } catch (Exception $e) {
            $errorMsg = "Failed to send message. Please try again later.";
        }
    }
}
?>

<!-- Banner -->
<section class="bg-[#3A1812] text-white py-12 border-b-4 border-[#D97706]">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="font-serif-heading text-4xl font-extrabold text-[#FBBF24] mb-2">Visit / Contact Us</h1>
        <p class="text-xs sm:text-sm text-gray-300">We would love to hear from you. Order inquiries or feedback are always welcome!</p>
    </div>
</section>

<section class="py-16 bg-[#FFFBF4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Contact Info Sidebar Cards -->
            <div class="space-y-6">
                <div class="mbc-card p-6 bg-[#FAF4EB] rounded-2xl border border-[#E8DCCB] flex items-start gap-4">
                    <div class="w-12 h-12 bg-[#3A1812] text-[#FBBF24] rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-[#3A1812] mb-1">Our Location</h4>
                        <p class="text-xs text-gray-600 leading-relaxed"><?php echo getSetting('address', '123 Main Road, Dhoraji, Gujarat, 360410'); ?></p>
                    </div>
                </div>

                <div class="mbc-card p-6 bg-[#FAF4EB] rounded-2xl border border-[#E8DCCB] flex items-start gap-4">
                    <div class="w-12 h-12 bg-[#D97706] text-white rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-[#3A1812] mb-1">Phone Number</h4>
                        <p class="text-xs text-gray-600 leading-relaxed"><?php echo getSetting('phone', '+91 98765 43210'); ?></p>
                        <span class="text-[10px] text-[#D97706] font-bold">Call for Instant Takeaway</span>
                    </div>
                </div>

                <div class="mbc-card p-6 bg-[#FAF4EB] rounded-2xl border border-[#E8DCCB] flex items-start gap-4">
                    <div class="w-12 h-12 bg-[#3A1812] text-[#FBBF24] rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-[#3A1812] mb-1">Operating Hours</h4>
                        <p class="text-xs text-gray-600 leading-relaxed"><?php echo getSetting('opening_hours', '10:00 AM - 10:00 PM'); ?></p>
                        <span class="text-[10px] text-emerald-600 font-bold">Open 7 Days a Week</span>
                    </div>
                </div>
            </div>

            <!-- Contact Message Form -->
            <div class="lg:col-span-2">
                <div class="mbc-card p-8 bg-white rounded-2xl border border-[#E8DCCB] shadow-lg">
                    <h3 class="font-serif-heading font-bold text-2xl text-[#3A1812] mb-2">Send Us a Message</h3>
                    <p class="text-xs text-gray-500 mb-6">Have questions about catering, feedback, or special orders? Fill out the form below.</p>

                    <?php if ($successMsg): ?>
                        <div class="p-4 mb-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-base text-emerald-600"></i>
                            <span><?php echo $successMsg; ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($errorMsg): ?>
                        <div class="p-4 mb-6 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-base text-rose-600"></i>
                            <span><?php echo $errorMsg; ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo baseUrl('contact.php'); ?>" method="POST" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-[#3A1812] mb-1">Your Full Name *</label>
                                <input type="text" name="name" required class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="e.g. Aarav Patel">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#3A1812] mb-1">Email Address *</label>
                                <input type="email" name="email" required class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="aarav@gmail.com">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-[#3A1812] mb-1">Phone Number</label>
                                <input type="text" name="phone" class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="+91 98989 89898">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#3A1812] mb-1">Subject</label>
                                <input type="text" name="subject" class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="Order Inquiry / Catering">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#3A1812] mb-1">Your Message *</label>
                            <textarea name="message" rows="4" required class="w-full px-4 py-2.5 text-xs bg-[#FAF4EB] border border-[#E8DCCB] rounded-xl focus:outline-none focus:border-[#D97706] font-medium" placeholder="Write your message here..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="bg-[#3A1812] hover:bg-[#D97706] text-white font-bold text-xs px-6 py-3 rounded-xl transition shadow-md flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
