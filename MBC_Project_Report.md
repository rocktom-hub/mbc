import os
import base64

scratch_dir = r"C:\Users\Atik-01\.gemini\antigravity\brain\9be89ed1-ccbd-4c98-b722-e6037355ccc3\scratch"
output_html = r"d:\mbc\MBC_Project_Report.html"

def get_base64_img(img_name):
    path = os.path.join(scratch_dir, img_name)
    if os.path.exists(path):
        with open(path, "rb") as f:
            encoded = base64.b64encode(f.read()).decode("utf-8")
            return f"data:image/png;base64,{encoded}"
    return ""

img_er = get_base64_img("er_diagram.png")
img_dfd0 = get_base64_img("dfd_level_0.png")
img_dfd1 = get_base64_img("dfd_level_1.png")
img_arch = get_base64_img("arch_diagram.png")
img_home = get_base64_img("screen_home.png")
img_menu = get_base64_img("screen_menu.png")
img_checkout = get_base64_img("screen_checkout.png")
img_admin_dash = get_base64_img("screen_admin_dashboard.png")
img_admin_ord = get_base64_img("screen_admin_orders.png")

html_content = f"""<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MBC Project Report - Malek Bateta Center</title>
    <style>
        body {{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2A1712;
            background-color: #F4F1EA;
            margin: 0;
            padding: 40px 20px;
        }}
        .container {{
            max-width: 1000px;
            margin: 0 auto;
            background: #FFFFFF;
            padding: 60px 80px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-radius: 8px;
        }}
        .title-page {{
            text-align: center;
            padding: 60px 0 40px 0;
            border-bottom: 3px double #3A1812;
            margin-bottom: 40px;
        }}
        .title-page h1 {{
            font-family: Georgia, serif;
            color: #3A1812;
            font-size: 32px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }}
        .title-page h2 {{
            color: #D97706;
            font-size: 20px;
            font-weight: 500;
            margin-top: 0;
        }}
        .meta-box {{
            margin-top: 40px;
            font-size: 14px;
            color: #555555;
            background: #FFFBF4;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #D97706;
            display: inline-block;
            text-align: left;
        }}
        h1.section-title {{
            font-family: Georgia, serif;
            color: #3A1812;
            border-bottom: 2px solid #3A1812;
            padding-bottom: 8px;
            margin-top: 50px;
            font-size: 24px;
        }}
        h2.subsection-title {{
            color: #D97706;
            margin-top: 30px;
            font-size: 18px;
        }}
        h3.sub-subsection-title {{
            color: #2D0B07;
            margin-top: 20px;
            font-size: 16px;
        }}
        p {{
            margin-bottom: 16px;
            text-align: justify;
        }}
        .img-container {{
            text-align: center;
            margin: 30px 0;
        }}
        .img-container img {{
            max-width: 100%;
            height: auto;
            border: 1px solid #E8DCCB;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }}
        .caption {{
            font-size: 13px;
            font-style: italic;
            color: #3A1812;
            margin-top: 8px;
            font-weight: 600;
        }}
        table {{
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 14px;
        }}
        th {{
            background-color: #3A1812;
            color: #FFFFFF;
            text-align: left;
            padding: 12px 15px;
            font-weight: 600;
        }}
        td {{
            padding: 10px 15px;
            border-bottom: 1px solid #E8DCCB;
        }}
        tr:nth-child(even) {{
            background-color: #FFFBF4;
        }}
        ul {{
            margin-bottom: 20px;
        }}
        li {{
            margin-bottom: 8px;
        }}
        @media print {{
            body {{ background: #FFF; padding: 0; }}
            .container {{ box-shadow: none; padding: 0; max-width: 100%; }}
        }}
    </style>
</head>
<body>

<div class="container">
    <div class="title-page">
        <h1>MBC (MALEK BATETA CENTER)<br>FOOD ORDERING & RESTAURANT MANAGEMENT SYSTEM</h1>
        <h2>Comprehensive Software Engineering Project Report</h2>
        <p style="text-align: center; font-style: italic;">Covering SDLC, System Architecture, ERD, DFDs, Data Dictionary & UI Screenshots</p>
        
        <div class="meta-box">
            <strong>Project Title:</strong> MBC Online Food Ordering System<br>
            <strong>Tech Stack:</strong> PHP 8.x, MySQL, HTML5, CSS3, JavaScript, Bootstrap 5<br>
            <strong>Prepared For:</strong> Academic & Technical Project Submission<br>
            <strong>Date:</strong> September 2026
        </div>
    </div>

    <h1 class="section-title">Executive Summary</h1>
    <p>The Malek Bateta Center (MBC) Online Food Ordering and Restaurant Management System is a web application designed to digitize ordering, inventory, and customer management for a famous traditional eatery based in Dhoraji, Gujarat (established in 1983).</p>
    <p>The primary objective of this project is to provide customers with an intuitive online menu ordering platform featuring real-time cart handling, takeaway/delivery order options, and flexible payment methods (Cash on Delivery, UPI). Simultaneously, it equips restaurant administrators with a comprehensive management portal to monitor live customer orders, manage food item categories, update order fulfillment status, update site settings, and print formatted tax invoices.</p>

    <h1 class="section-title">Chapter 1: System Overview & Project Scope</h1>
    <h2 class="subsection-title">1.1 Background & Problem Statement</h2>
    <p>Traditional food outlets face significant operational challenges when relying on manual pen-and-paper order taking, telephone calls, and physical queues. Key bottlenecks include order entry errors, communication delays during peak hours, inability to track live order delivery status, and fragmented sales records.</p>
    
    <h2 class="subsection-title">1.2 Objectives</h2>
    <ul>
        <li>Provide an accessible, responsive web application for customers to browse food items, customize orders, and place online food orders seamlessly.</li>
        <li>Centralize order processing into a unified real-time admin portal with instant status updates (Pending â†’ Preparing â†’ Out for Delivery â†’ Completed).</li>
        <li>Automate tax and delivery fee calculations with dynamic invoice print generation.</li>
        <li>Maintain an audit trail of user registrations, reviews, contact messages, and item catalogs in a normalized relational database.</li>
    </ul>

    <h1 class="section-title">Chapter 2: Software Development Life Cycle (SDLC)</h1>
    <p>The MBC Food Ordering System was built following an Agile-Waterfall Hybrid SDLC Model. This approach combined structured phase-by-phase design deliverables with iterative sprint execution.</p>
    <ul>
        <li><strong>Phase 1: Requirement Analysis & Feasibility Study â€”</strong> Stakeholder interviews with restaurant staff and customers to capture core operational requirements.</li>
        <li><strong>Phase 2: System & Architectural Design â€”</strong> High-level system architectural models, process flow specifications, and DFDs.</li>
        <li><strong>Phase 3: Database Design & Data Modeling â€”</strong> Normalized database tables (3NF) in MySQL with PK/FK constraints.</li>
        <li><strong>Phase 4: System Implementation & Coding â€”</strong> Backend logic in PHP with PDO/MySQLi and Bootstrap 5 frontend UI.</li>
        <li><strong>Phase 5: Verification & System Testing â€”</strong> Component unit testing, integration testing, and User Acceptance Testing (UAT).</li>
        <li><strong>Phase 6: Deployment & Maintenance â€”</strong> Apache web server & MySQL deployment with routine backup protocols.</li>
    </ul>

    <h1 class="section-title">Chapter 3: System Architecture & Data Flow Diagrams</h1>
    <h2 class="subsection-title">3.1 3-Tier System Architecture</h2>
    <div class="img-container">
        <img src="{img_arch}" alt="3-Tier System Architecture">
        <div class="caption">Figure 3.1: 3-Tier System Architecture Model for MBC Project</div>
    </div>

    <h2 class="subsection-title">3.2 DFD Level 0 (Context Diagram)</h2>
    <div class="img-container">
        <img src="{img_dfd0}" alt="DFD Level 0">
        <div class="caption">Figure 3.2: Data Flow Diagram (DFD) - Level 0 Context Diagram</div>
    </div>

    <h2 class="subsection-title">3.3 DFD Level 1 Diagram</h2>
    <div class="img-container">
        <img src="{img_dfd1}" alt="DFD Level 1">
        <div class="caption">Figure 3.3: Data Flow Diagram (DFD) - Level 1 Detailed Process Model</div>
    </div>

    <h1 class="section-title">Chapter 4: Database Design & Data Dictionary</h1>
    <h2 class="subsection-title">4.1 Entity-Relationship (ER) Diagram</h2>
    <div class="img-container">
        <img src="{img_er}" alt="ER Diagram">
        <div class="caption">Figure 4.1: Entity-Relationship (ER) Diagram for Database `mbc_db`</div>
    </div>

    <h2 class="subsection-title">4.2 Data Dictionary (All 9 Database Tables)</h2>
    
    <h3 class="sub-subsection-title">Table 1: `users`</h3>
    <table>
        <tr><th>Field Name</th><th>Data Type</th><th>Constraint</th><th>Default</th><th>Description</th></tr>
        <tr><td>id</td><td>INT</td><td>PK, AUTO_INC</td><td>NULL</td><td>Unique user identifier</td></tr>
        <tr><td>name</td><td>VARCHAR(100)</td><td>NOT NULL</td><td>None</td><td>Full customer or admin name</td></tr>
        <tr><td>email</td><td>VARCHAR(100)</td><td>NOT NULL, UNIQUE</td><td>None</td><td>User login email address</td></tr>
        <tr><td>phone</td><td>VARCHAR(20)</td><td>NOT NULL</td><td>None</td><td>Contact phone number</td></tr>
        <tr><td>password</td><td>VARCHAR(255)</td><td>NOT NULL</td><td>None</td><td>Bcrypt hashed password</td></tr>
        <tr><td>role</td><td>ENUM</td><td>NOT NULL</td><td>'customer'</td><td>User role ('admin', 'customer')</td></tr>
        <tr><td>address</td><td>TEXT</td><td>NULL</td><td>NULL</td><td>Default delivery address</td></tr>
        <tr><td>status</td><td>ENUM</td><td>NOT NULL</td><td>'active'</td><td>Account state ('active', 'blocked')</td></tr>
        <tr><td>created_at</td><td>TIMESTAMP</td><td>NOT NULL</td><td>CURRENT_TIMESTAMP</td><td>Registration timestamp</td></tr>
    </table>

    <h3 class="sub-subsection-title">Table 2: `categories`</h3>
    <table>
        <tr><th>Field Name</th><th>Data Type</th><th>Constraint</th><th>Default</th><th>Description</th></tr>
        <tr><td>id</td><td>INT</td><td>PK, AUTO_INC</td><td>NULL</td><td>Category primary key</td></tr>
        <tr><td>name</td><td>VARCHAR(100)</td><td>NOT NULL</td><td>None</td><td>Category title (e.g. Bateta Special)</td></tr>
        <tr><td>description</td><td>VARCHAR(255)</td><td>NULL</td><td>NULL</td><td>Brief category summary</td></tr>
        <tr><td>image</td><td>VARCHAR(255)</td><td>NULL</td><td>NULL</td><td>Category banner image filename</td></tr>
        <tr><td>status</td><td>ENUM</td><td>NOT NULL</td><td>'active'</td><td>Visibility ('active', 'inactive')</td></tr>
        <tr><td>created_at</td><td>TIMESTAMP</td><td>NOT NULL</td><td>CURRENT_TIMESTAMP</td><td>Creation timestamp</td></tr>
    </table>

    <h3 class="sub-subsection-title">Table 3: `foods`</h3>
    <table>
        <tr><th>Field Name</th><th>Data Type</th><th>Constraint</th><th>Default</th><th>Description</th></tr>
        <tr><td>id</td><td>INT</td><td>PK, AUTO_INC</td><td>NULL</td><td>Food item primary key</td></tr>
        <tr><td>category_id</td><td>INT</td><td>FK -> categories(id)</td><td>None</td><td>Associated category ID</td></tr>
        <tr><td>name</td><td>VARCHAR(150)</td><td>NOT NULL</td><td>None</td><td>Dish name</td></tr>
        <tr><td>description</td><td>TEXT</td><td>NULL</td><td>NULL</td><td>Detailed food description</td></tr>
        <tr><td>price</td><td>DECIMAL(10,2)</td><td>NOT NULL</td><td>None</td><td>Unit selling price</td></tr>
        <tr><td>image</td><td>VARCHAR(255)</td><td>NULL</td><td>NULL</td><td>Item image filename</td></tr>
        <tr><td>is_featured</td><td>TINYINT(1)</td><td>DEFAULT 0</td><td>0</td><td>Featured item flag (1=Yes, 0=No)</td></tr>
        <tr><td>is_available</td><td>TINYINT(1)</td><td>DEFAULT 1</td><td>1</td><td>Stock availability (1=Yes, 0=No)</td></tr>
        <tr><td>created_at</td><td>TIMESTAMP</td><td>NOT NULL</td><td>CURRENT_TIMESTAMP</td><td>Record creation timestamp</td></tr>
    </table>

    <h3 class="sub-subsection-title">Table 4: `orders`</h3>
    <table>
        <tr><th>Field Name</th><th>Data Type</th><th>Constraint</th><th>Default</th><th>Description</th></tr>
        <tr><td>id</td><td>INT</td><td>PK, AUTO_INC</td><td>NULL</td><td>Order primary key</td></tr>
        <tr><td>order_number</td><td>VARCHAR(30)</td><td>NOT NULL, UNIQUE</td><td>None</td><td>Formatted order code (e.g. MBC-849201)</td></tr>
        <tr><td>user_id</td><td>INT</td><td>FK -> users(id)</td><td>NULL</td><td>Customer account ID</td></tr>
        <tr><td>customer_name</td><td>VARCHAR(100)</td><td>NOT NULL</td><td>None</td><td>Customer full name</td></tr>
        <tr><td>customer_email</td><td>VARCHAR(100)</td><td>NOT NULL</td><td>None</td><td>Customer contact email</td></tr>
        <tr><td>customer_phone</td><td>VARCHAR(20)</td><td>NOT NULL</td><td>None</td><td>Delivery contact phone</td></tr>
        <tr><td>delivery_address</td><td>TEXT</td><td>NOT NULL</td><td>None</td><td>Complete physical shipping address</td></tr>
        <tr><td>order_type</td><td>ENUM</td><td>DEFAULT</td><td>'Home Delivery'</td><td>Type ('Home Delivery', 'Takeaway', 'Dine-in')</td></tr>
        <tr><td>payment_method</td><td>ENUM</td><td>DEFAULT</td><td>'Cash on Delivery'</td><td>Payment ('Cash on Delivery', 'UPI', etc)</td></tr>
        <tr><td>subtotal</td><td>DECIMAL(10,2)</td><td>NOT NULL</td><td>None</td><td>Total items sum</td></tr>
        <tr><td>tax_amount</td><td>DECIMAL(10,2)</td><td>NOT NULL</td><td>0.00</td><td>Calculated tax (5% GST)</td></tr>
        <tr><td>delivery_fee</td><td>DECIMAL(10,2)</td><td>NOT NULL</td><td>0.00</td><td>Flat delivery fee</td></tr>
        <tr><td>total_amount</td><td>DECIMAL(10,2)</td><td>NOT NULL</td><td>None</td><td>Final grand total</td></tr>
        <tr><td>status</td><td>ENUM</td><td>NOT NULL</td><td>'Pending'</td><td>Status ('Pending', 'Preparing', 'Completed')</td></tr>
        <tr><td>created_at</td><td>TIMESTAMP</td><td>NOT NULL</td><td>CURRENT_TIMESTAMP</td><td>Order placement timestamp</td></tr>
    </table>

    <h3 class="sub-subsection-title">Table 5: `order_items`</h3>
    <table>
        <tr><th>Field Name</th><th>Data Type</th><th>Constraint</th><th>Default</th><th>Description</th></tr>
        <tr><td>id</td><td>INT</td><td>PK, AUTO_INC</td><td>NULL</td><td>Order item line ID</td></tr>
        <tr><td>order_id</td><td>INT</td><td>FK -> orders(id)</td><td>None</td><td>Parent order reference ID</td></tr>
        <tr><td>food_id</td><td>INT</td><td>FK -> foods(id)</td><td>NULL</td><td>Purchased food item ID</td></tr>
        <tr><td>food_name</td><td>VARCHAR(150)</td><td>NOT NULL</td><td>None</td><td>Historical food item title</td></tr>
        <tr><td>price</td><td>DECIMAL(10,2)</td><td>NOT NULL</td><td>None</td><td>Item price at time of purchase</td></tr>
        <tr><td>quantity</td><td>INT</td><td>NOT NULL</td><td>None</td><td>Quantity ordered</td></tr>
        <tr><td>total_price</td><td>DECIMAL(10,2)</td><td>NOT NULL</td><td>None</td><td>Line item total (price * quantity)</td></tr>
    </table>

    <h3 class="sub-subsection-title">Table 6: `reviews`</h3>
    <table>
        <tr><th>Field Name</th><th>Data Type</th><th>Constraint</th><th>Default</th><th>Description</th></tr>
        <tr><td>id</td><td>INT</td><td>PK, AUTO_INC</td><td>NULL</td><td>Review primary key</td></tr>
        <tr><td>user_id</td><td>INT</td><td>FK -> users(id)</td><td>NULL</td><td>Reviewer user ID</td></tr>
        <tr><td>customer_name</td><td>VARCHAR(100)</td><td>NOT NULL</td><td>None</td><td>Display name</td></tr>
        <tr><td>rating</td><td>INT</td><td>NOT NULL</td><td>5</td><td>Star rating scale (1-5)</td></tr>
        <tr><td>comment</td><td>TEXT</td><td>NOT NULL</td><td>None</td><td>Review feedback text</td></tr>
        <tr><td>status</td><td>ENUM</td><td>DEFAULT</td><td>'approved'</td><td>Moderation ('approved', 'pending', 'rejected')</td></tr>
        <tr><td>created_at</td><td>TIMESTAMP</td><td>NOT NULL</td><td>CURRENT_TIMESTAMP</td><td>Review date</td></tr>
    </table>

    <h3 class="sub-subsection-title">Table 7: `gallery`</h3>
    <table>
        <tr><th>Field Name</th><th>Data Type</th><th>Constraint</th><th>Default</th><th>Description</th></tr>
        <tr><td>id</td><td>INT</td><td>PK, AUTO_INC</td><td>NULL</td><td>Gallery image ID</td></tr>
        <tr><td>title</td><td>VARCHAR(150)</td><td>NOT NULL</td><td>None</td><td>Image caption/title</td></tr>
        <tr><td>category</td><td>VARCHAR(100)</td><td>DEFAULT</td><td>'General'</td><td>Category classification</td></tr>
        <tr><td>image</td><td>VARCHAR(255)</td><td>NOT NULL</td><td>None</td><td>Stored image filename</td></tr>
        <tr><td>created_at</td><td>TIMESTAMP</td><td>NOT NULL</td><td>CURRENT_TIMESTAMP</td><td>Upload date</td></tr>
    </table>

    <h3 class="sub-subsection-title">Table 8: `contact_messages`</h3>
    <table>
        <tr><th>Field Name</th><th>Data Type</th><th>Constraint</th><th>Default</th><th>Description</th></tr>
        <tr><td>id</td><td>INT</td><td>PK, AUTO_INC</td><td>NULL</td><td>Message primary key</td></tr>
        <tr><td>name</td><td>VARCHAR(100)</td><td>NOT NULL</td><td>None</td><td>Sender full name</td></tr>
        <tr><td>email</td><td>VARCHAR(100)</td><td>NOT NULL</td><td>None</td><td>Sender email</td></tr>
        <tr><td>phone</td><td>VARCHAR(20)</td><td>NULL</td><td>NULL</td><td>Sender phone</td></tr>
        <tr><td>subject</td><td>VARCHAR(150)</td><td>NULL</td><td>NULL</td><td>Message subject line</td></tr>
        <tr><td>message</td><td>TEXT</td><td>NOT NULL</td><td>None</td><td>Full message body</td></tr>
        <tr><td>status</td><td>ENUM</td><td>DEFAULT</td><td>'unread'</td><td>Read status ('unread', 'read')</td></tr>
        <tr><td>created_at</td><td>TIMESTAMP</td><td>NOT NULL</td><td>CURRENT_TIMESTAMP</td><td>Sent timestamp</td></tr>
    </table>

    <h3 class="sub-subsection-title">Table 9: `settings`</h3>
    <table>
        <tr><th>Field Name</th><th>Data Type</th><th>Constraint</th><th>Default</th><th>Description</th></tr>
        <tr><td>setting_key</td><td>VARCHAR(50)</td><td>PK</td><td>None</td><td>Unique system configuration key</td></tr>
        <tr><td>setting_value</td><td>TEXT</td><td>NOT NULL</td><td>None</td><td>Configuration key value</td></tr>
    </table>

    <h1 class="section-title">Chapter 5: User Interface & Screenshots</h1>
    <h2 class="subsection-title">5.1 Customer Landing Page</h2>
    <div class="img-container">
        <img src="{img_home}" alt="Landing Page">
        <div class="caption">Figure 5.1: Customer Landing Page (`index.php`)</div>
    </div>

    <h2 class="subsection-title">5.2 Food Menu & Interactive Cart Page</h2>
    <div class="img-container">
        <img src="{img_menu}" alt="Menu Page">
        <div class="caption">Figure 5.2: Interactive Food Menu Catalog (`menu.php`)</div>
    </div>

    <h2 class="subsection-title">5.3 Order Checkout & Summary Page</h2>
    <div class="img-container">
        <img src="{img_checkout}" alt="Checkout Page">
        <div class="caption">Figure 5.3: Order Checkout & Payment Selection (`checkout.php`)</div>
    </div>

    <h2 class="subsection-title">5.4 Administrator Portal Dashboard</h2>
    <div class="img-container">
        <img src="{img_admin_dash}" alt="Admin Dashboard">
        <div class="caption">Figure 5.4: Admin Control Panel Dashboard (`admin/index.php`)</div>
    </div>

    <h2 class="subsection-title">5.5 Live Order Management Screen</h2>
    <div class="img-container">
        <img src="{img_admin_ord}" alt="Admin Orders">
        <div class="caption">Figure 5.5: Live Order Processing & Invoice Screen (`admin/orders.php`)</div>
    </div>

    <h1 class="section-title">Chapter 6: System Testing & Verification Matrix</h1>
    <table>
        <tr><th>Test Case ID</th><th>Module</th><th>Test Scenario</th><th>Expected Result</th><th>Status</th></tr>
        <tr><td>TC-001</td><td>User Auth</td><td>Submit registration with valid email & phone</td><td>Account created, redirect to login</td><td><strong style="color:#27C93F;">PASSED</strong></td></tr>
        <tr><td>TC-002</td><td>User Auth</td><td>Attempt login with incorrect password</td><td>Display error alert 'Invalid credentials'</td><td><strong style="color:#27C93F;">PASSED</strong></td></tr>
        <tr><td>TC-003</td><td>Menu</td><td>Filter dishes by 'Bateta Special' category</td><td>Grid updates showing only Bateta dishes</td><td><strong style="color:#27C93F;">PASSED</strong></td></tr>
        <tr><td>TC-004</td><td>Cart</td><td>Add 2x Dhoraji Bateta Special to cart</td><td>Session cart updates subtotal to â‚¹160.00</td><td><strong style="color:#27C93F;">PASSED</strong></td></tr>
        <tr><td>TC-005</td><td>Checkout</td><td>Confirm order with COD payment option</td><td>Order saved in DB, cart cleared</td><td><strong style="color:#27C93F;">PASSED</strong></td></tr>
        <tr><td>TC-006</td><td>Admin</td><td>Change order status to 'Completed'</td><td>Database updated, status badge turns green</td><td><strong style="color:#27C93F;">PASSED</strong></td></tr>
        <tr><td>TC-007</td><td>Invoice</td><td>Click 'Print Invoice' for order MBC-849201</td><td>Opens formatted printable invoice window</td><td><strong style="color:#27C93F;">PASSED</strong></td></tr>
    </table>

    <h1 class="section-title">Chapter 7: Conclusion & Future Scope</h1>
    <p>The MBC Food Ordering and Restaurant Management System successfully digitizes traditional restaurant workflows into a modern, responsive web application. The platform streamlines online food ordering for customers and provides administrators with an efficient order fulfillment dashboard.</p>
</div>

</body>
</html>
"""

with open(output_html, "w", encoding="utf-8") as f:
    f.write(html_content)

print(f"HTML Report generated successfully at: {output_html}")
