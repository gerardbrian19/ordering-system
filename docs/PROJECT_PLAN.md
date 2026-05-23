# ShopEase — Simple Ecommerce App
### College Thesis Project · PHP + MySQL · No Framework

---

## 🎯 Project Overview

A lightweight ecommerce web app built with pure PHP, MySQL, HTML, CSS (Tailwind), and vanilla JS.
No Laravel. No XAMPP. No Apache. Just PHP's built-in server + MySQL via Homebrew.

---

## 🛠️ Tech Stack

| Layer       | Technology                          |
|-------------|-------------------------------------|
| Language    | PHP 8.5                             |
| Database    | MySQL 9.6 (Homebrew)                |
| Styling     | Tailwind CSS v3 (CDN)               |
| Scripting   | Vanilla JavaScript (ES6+)           |
| Server      | PHP Built-in Dev Server             |
| Icons       | Heroicons / Font Awesome CDN        |

---

## 📁 Folder Structure

```
inventory-management/
├── plan/                        ← project planning docs
│   ├── PROJECT_PLAN.md
│   └── UI_DESIGN.md
├── .github/
│   └── copilot-instructions.md  ← Copilot coding guidelines
├── config/
│   └── config.php               ← DB credentials, app constants
├── includes/
│   ├── db.php                   ← PDO database connection
│   ├── auth.php                 ← session auth helpers
│   └── functions.php            ← reusable helpers
├── public/                      ← web root (serve from here)
│   ├── index.php                ← home / product listing
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── product.php              ← single product detail
│   ├── cart.php
│   ├── checkout.php
│   ├── orders.php               ← customer order history
│   └── assets/
│       ├── css/
│       │   └── style.css        ← custom overrides
│       └── js/
│           └── app.js           ← cart logic, UI interactions
└── admin/
    ├── index.php                ← admin dashboard
    ├── products.php             ← manage products (CRUD)
    └── orders.php               ← manage orders
```

---

## 📄 Pages

### Customer-Facing
| Page           | File                 | Status   |
|----------------|----------------------|----------|
| Home / Listing | `public/index.php`   | UI Done  |
| Login          | `public/login.php`   | UI Done  |
| Register       | `public/register.php`| Pending  |
| Product Detail | `public/product.php` | Pending  |
| Cart           | `public/cart.php`    | Pending  |
| Checkout       | `public/checkout.php`| Pending  |
| Order History  | `public/orders.php`  | Pending  |

### Admin Panel
| Page            | File                   | Status  |
|-----------------|------------------------|---------|
| Dashboard       | `admin/index.php`      | Pending |
| Product Manager | `admin/products.php`   | Pending |
| Order Manager   | `admin/orders.php`     | Pending |

---

## 🗄️ Database Schema (Planned)

```sql
-- Users
CREATE TABLE users (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  email       VARCHAR(150) UNIQUE NOT NULL,
  password    VARCHAR(255) NOT NULL,  -- bcrypt hashed
  role        ENUM('customer', 'admin') DEFAULT 'customer',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products
CREATE TABLE products (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(200) NOT NULL,
  description TEXT,
  price       DECIMAL(10,2) NOT NULL,
  stock       INT DEFAULT 0,
  image_url   VARCHAR(255),
  category    VARCHAR(100),
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders
CREATE TABLE orders (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT NOT NULL,
  total       DECIMAL(10,2) NOT NULL,
  status      ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order Items
CREATE TABLE order_items (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  order_id    INT NOT NULL,
  product_id  INT NOT NULL,
  quantity    INT NOT NULL,
  unit_price  DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);
```

---

## 🚀 How to Run Locally

```bash
# 1. Start MySQL
brew services start mysql

# 2. Run PHP dev server (from project root)
php -S localhost:8000 -t public/

# 3. Open in browser
open http://localhost:8000
```

---

## 🔐 Security Checklist

- [ ] Passwords hashed with `password_hash()` (bcrypt)
- [ ] PDO prepared statements for all DB queries
- [ ] `session_regenerate_id()` on login
- [ ] CSRF tokens on all forms
- [ ] Input validation and sanitization
- [ ] Admin routes protected by role check

---

## 📅 Build Order

1. ✅ Plan + folder structure
2. ✅ Login page UI
3. ✅ Home page UI
4. ⬜ DB connection + config
5. ⬜ Auth (register, login, logout)
6. ⬜ Product listing (dynamic from DB)
7. ⬜ Cart (session-based)
8. ⬜ Checkout + order saving
9. ⬜ Admin panel
