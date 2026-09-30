# 🥔 Mamdev Bhungla Bateta Center (MBC)

An online food ordering and restaurant management web application built for **Mamdev Bhungla Bateta Center (MBC)** — famous for authentic Dhoraji Bateta, Bhungla Bateta combos, and spicy Kathiyawadi street food delicacies.

---

## 🚀 Features

### 🛍️ Customer Portal & Ordering
- **Interactive Menu**: Browse food items by category (Bateta Specials, Combos, Snacks, Drinks) with real-time search & filters.
- **Cart & Fast Checkout**: Seamless shopping cart with quantity adjustments, custom notes, delivery address form, and instant order placement.
- **Order Tracking & Details**: View real-time order status (Pending, Confirmed, Preparing, Out for Delivery, Delivered), item breakdown, and invoice generation.
- **Customer Dashboard**: Manage profile details, view order history, and update password.
- **Photo Gallery**: Explore restaurant delicacies and community snapshots.
- **Customer Reviews & Ratings**: Submit and display genuine customer feedback.

### 🛠️ Admin Dashboard
- **Analytics Overview**: Live counters for total revenue, active orders, customer count, and food items.
- **Food Menu Management**: Add, edit, delete, and toggle availability for dishes with direct image uploads and previews.
- **Category Management**: Organize dishes into custom categories.
- **Order Management**: Track orders, update status workflow, and view printable invoices.
- **Customer Management**: View registered customers and their contact information.
- **Review Moderation**: Approve, manage, and moderate customer reviews.
- **Store Settings**: Update restaurant timings, contact information, address, and delivery fees.

---

## 💻 Tech Stack

- **Backend**: PHP (PDO MySQL)
- **Database**: MySQL / MariaDB (`mbc_db`)
- **Frontend**: Tailwind CSS (CDN), Alpine.js
- **Icons & Fonts**: FontAwesome 6, Google Fonts (Plus Jakarta Sans)
- **Local Dev Environment**: Laragon / XAMPP / WampServer

---

## 📂 Project Structure

```text
mbc/
├── admin/                  # Admin dashboard & management pages
│   ├── includes/           # Admin sidebar & layout components
│   ├── categories.php      # Category CRUD
│   ├── customers.php       # Registered customers directory
│   ├── foods.php           # Food menu management & image upload
│   ├── gallery.php         # Gallery image management
│   ├── index.php           # Admin analytics dashboard
│   ├── orders.php          # Order processing & status workflow
│   ├── print-invoice.php   # Printable customer receipts / bills
│   ├── reviews.php         # Customer reviews management
│   └── settings.php        # Store configuration & info
├── api/
│   └── cart-actions.php    # Async cart endpoints
├── assets/
│   ├── css/                # Custom CSS styles
│   └── images/             # Food photos, logos, and vector assets
├── config/
│   ├── db.php              # Database connection & auto-migration
│   └── helpers.php         # Authentication, image resolver & helper functions
├── customer/               # Customer account portal
│   ├── includes/           # Customer portal sidebar
│   ├── change-password.php # Update password
│   ├── index.php           # Customer dashboard overview
│   ├── order-detail.php    # Single order breakdown
│   ├── orders.php          # Order history
│   └── profile.php         # Customer profile editor
├── database/
│   └── mbc_db.sql          # MySQL database schema & sample data
├── includes/
│   ├── header.php          # Main navigation bar
│   └── footer.php          # Main site footer
├── about.php               # About Us story
├── cart.php                # Shopping cart page
├── checkout.php            # Order checkout & payment selection
├── contact.php             # Contact Us page
├── gallery.php             # Food photo gallery
├── index.php               # Homepage with hero section & featured items
├── login.php               # User login
├── logout.php              # User session logout
├── menu.php                # Full restaurant food menu
├── order-success.php       # Post-checkout confirmation screen
└── register.php            # New customer registration
```

---

## ⚙️ Installation & Setup

### Prerequisites
- PHP 8.0 or newer
- MySQL / MariaDB server
- Web server (Laragon, XAMPP, Apache, or built-in PHP server)

### Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/rocktom-hub/mbc.git
   cd mbc
   ```

2. **Place in Web Root**
   - For **Laragon**: place folder in `C:\laragon\www\mbc`
   - For **XAMPP**: place folder in `htdocs\mbc`

3. **Database Configuration**
   - Open MySQL via phpMyAdmin or MySQL CLI.
   - Create a database named `mbc_db`:
     ```sql
     CREATE DATABASE mbc_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```
   - Import `database/mbc_db.sql` into `mbc_db`.
   - Update database credentials in `config/db.php` if different from default:
     ```php
     define('DB_HOST', '127.0.0.1');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'mbc_db');
     define('DB_PORT', '3306');
     ```

4. **Run the Application**
   - Access via browser:
     ```text
     http://localhost/mbc
     ```
   - (Or `http://mbc.test` if using Laragon virtual hosts)

---

## 👤 Default Credentials

- **Admin Login**:
  - Email: `admin@mbc.com`
  - Password: `admin` (or configured password in `mbc_db.sql`)
- **Customer Login**:
  - Use any test account from `mbc_db.sql` or register a new account on `/register.php`.

---

## 📄 License
This project is proprietary and built for Mamdev Bhungla Bateta Center (MBC). All rights reserved.
