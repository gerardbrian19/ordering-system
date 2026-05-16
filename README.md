# ShopEase — Ordering & Inventory Management System

A lightweight ecommerce and ordering web application built as a college thesis project. No frameworks — just pure PHP, MySQL, Tailwind CSS, and vanilla JavaScript.

---

## Tech Stack

| Layer      | Technology                  |
|------------|-----------------------------|
| Backend    | PHP 8.5                     |
| Database   | MySQL 9.6                   |
| Styling    | Tailwind CSS v3 (CDN)       |
| Scripting  | Vanilla JavaScript (ES6+)   |
| Dev Server | PHP Built-in Server         |
| Icons      | Heroicons / Font Awesome CDN|

---

## Features

- **Customer Portal** — Browse products, manage cart, place orders, track order history
- **Admin Panel** — Full product CRUD, order management, user oversight, messaging
- **Staff Portal** — Inventory management, order processing, internal messaging
- **Authentication** — Role-based access control (customer, staff, admin)
- **Security** — CSRF protection, bcrypt password hashing, PDO prepared statements

---

## Prerequisites

- **PHP 8.5+** — [php.net/downloads](https://www.php.net/downloads)
- **MySQL 9.6+** — Install via Homebrew (macOS) or MySQL Installer (Windows)
- A terminal / command prompt

### Install on macOS (Homebrew)

```bash
brew install php
brew install mysql
brew services start mysql
```

### Install on Windows

Download and install:
- PHP: https://windows.php.net/download
- MySQL: https://dev.mysql.com/downloads/installer

---

## Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/gerardbrian19/ordering-system.git
cd ordering-system
```

### 2. Configure the Database

Open `config/config.php` and update the credentials to match your environment:

```php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'shopease');
define('DB_USER', 'root');
define('DB_PASS', '');          // your MySQL password here
```

### 3. Create the Database

Log into MySQL and create the database:

```bash
mysql -u root -p
```

```sql
CREATE DATABASE shopease CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shopease;
```

Then run the schema below (or import a provided `.sql` file if included):

```sql
CREATE TABLE users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(150) UNIQUE NOT NULL,
  password   VARCHAR(255) NOT NULL,
  role       ENUM('customer', 'staff', 'admin') DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

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

CREATE TABLE orders (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT NOT NULL,
  total      DECIMAL(10,2) NOT NULL,
  status     ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  order_id   INT NOT NULL,
  product_id INT NOT NULL,
  quantity   INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);
```

---

## Running the App

```bash
# From the project root
php -S localhost:8000 -t public/
```

Then open your browser and go to:

```
http://localhost:8000
```

---

## Project Structure

```
ordering-system/
├── config/
│   └── config.php          ← DB credentials & app constants
├── includes/
│   ├── db.php              ← PDO database connection
│   ├── auth.php            ← Auth guards & session helpers
│   ├── functions.php       ← Reusable utility functions
│   ├── admin_nav.php       ← Admin navigation partial
│   ├── staff_nav.php       ← Staff navigation partial
│   └── customer_nav.php    ← Customer navigation partial
├── public/                 ← Web root (PHP server serves this)
│   ├── index.php           ← Home / product listing
│   ├── login.php
│   ├── logout.php
│   ├── cart.php
│   ├── checkout.php
│   ├── orders.php          ← Customer order history
│   ├── services.php
│   ├── messages.php
│   ├── my_bookings.php
│   ├── shipping_address.php
│   ├── order_confirmation.php
│   ├── admin/              ← Admin panel pages
│   │   ├── index.php
│   │   ├── products.php
│   │   ├── orders.php
│   │   └── messages.php
│   ├── staff/              ← Staff panel pages
│   │   ├── index.php
│   │   ├── inventory.php
│   │   ├── orders.php
│   │   └── messages.php
│   └── assets/
│       ├── css/style.css
│       └── js/app.js
└── plan/
    ├── PROJECT_PLAN.md
    └── UI_DESIGN.md
```

---

## User Roles

| Role       | Access                                              |
|------------|-----------------------------------------------------|
| `customer` | Browse products, cart, checkout, order history      |
| `staff`    | Inventory management, order processing, messages    |
| `admin`    | Full access — products, orders, users, messages     |

---

## Security

- Passwords hashed with `password_hash()` (bcrypt)
- All DB queries use PDO prepared statements — no raw SQL injection vectors
- CSRF tokens on all POST forms
- `session_regenerate_id(true)` called on login
- Role checks enforced server-side on every protected page

---

## License

This project is for academic/thesis purposes only.
