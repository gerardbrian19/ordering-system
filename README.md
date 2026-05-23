# Goldcomm — Ordering & Inventory Management System

A lightweight ecommerce and ordering web application built as a college thesis project. No web framework — pure PHP 8.5 with PDO, PHPMailer for transactional email, and Phinx for database migrations.

---

## Tech Stack

| Layer       | Technology                      |
|-------------|----------------------------------|
| Backend     | PHP 8.5                          |
| Database    | MySQL 9.6                        |
| Styling     | Tailwind CSS v3 (CDN)            |
| Scripting   | Vanilla JavaScript (ES6+)        |
| Email       | PHPMailer v7.1 (SMTP)            |
| Migrations  | Phinx v0.16                      |
| Environment | vlucas/phpdotenv v5              |
| Logging     | Monolog v3                       |
| Testing     | Playwright (Node.js E2E)         |
| Dev Server  | PHP Built-in Server              |
| Icons       | Heroicons / Font Awesome CDN     |

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

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Configure the Environment

Copy the example env file and fill in your credentials:

```bash
cp .env.example .env
```

Then open `.env` and set your values:

```
DB_HOST=127.0.0.1
DB_NAME=goldcomm
DB_USER=root
DB_PASS=
APP_NAME=Goldcomm
APP_URL=http://localhost:8000
```

### 4. Create the Database

```bash
mysql -u root -p -e "CREATE DATABASE goldcomm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 5. Run Migrations

```bash
vendor/bin/phinx migrate
```

---

## Running the App

### Start the database

```bash
brew services start mysql        # macOS (Homebrew)
```

### Start the dev server

```bash
php -S localhost:8000 -t public/
```

Then open **http://localhost:8000** in your browser.

### Stop the dev server

Press `Ctrl+C` in the terminal running the server.

### Stop the database

```bash
brew services stop mysql         # macOS (Homebrew)
```

---

## Migrations

### Run all pending migrations

```bash
vendor/bin/phinx migrate
```

### Create a new migration

```bash
vendor/bin/phinx create YourMigrationName
```

The new file will be created in `db/migrations/`. Edit it to define your `up()` and `down()` methods.

### Rollback the last migration

```bash
vendor/bin/phinx rollback
```

---

## Project Structure

```
inventory-management/
├── .env                        ← Local environment variables (never committed)
├── .env.example                ← Environment variable template
├── composer.json               ← PHP dependencies (PHPMailer, Phinx, dotenv)
├── phinx.php                   ← Phinx migration config
├── package.json                ← Node dev dependencies (Playwright)
├── playwright.config.js        ← E2E test config
│
├── config/
│   └── config.php              ← Loads .env, defines DB_* & APP_* constants
│
├── db/
│   └── migrations/             ← Phinx migration files
│
├── docs/
│   ├── PROJECT_PLAN.md
│   ├── TECHNICAL_ROADMAP.md
│   └── UI_DESIGN.md
│
├── includes/                   ← Server-side logic (never web-accessible)
│   ├── db.php                  ← PDO connection
│   ├── auth.php                ← Auth guards & CSRF helpers
│   ├── session.php             ← Centralised session bootstrap (HttpOnly, SameSite)
│   ├── mailer.php              ← PHPMailer wrapper — sendMail() helper
│   ├── functions.php           ← Shared utility functions
│   ├── layouts/
│   │   ├── admin/
│   │   │   ├── nav.php         ← Admin sidebar + HTML shell (opened)
│   │   │   └── footer.php      ← Closes HTML shell
│   │   ├── staff/
│   │   │   ├── nav.php
│   │   │   └── footer.php
│   │   └── customer/
│   │       ├── nav.php
│   │       └── footer.php
│   └── repositories/           ← DB query functions per domain
│       ├── users.php
│       ├── products.php
│       ├── orders.php
│       └── messages.php
│
├── public/                     ← Web root (PHP built-in server serves this)
│   ├── index.php               ← Home / product listing
│   ├── login.php
│   ├── login_handler.php
│   ├── logout.php
│   ├── admin/
│   │   ├── index.php           ← Admin dashboard
│   │   ├── products.php
│   │   ├── orders.php
│   │   └── messages.php
│   ├── staff/
│   │   ├── index.php           ← Staff dashboard
│   │   ├── inventory.php
│   │   ├── orders.php
│   │   └── messages.php
│   ├── customer/
│   │   ├── cart.php
│   │   ├── checkout.php
│   │   ├── orders.php
│   │   ├── order_confirmation.php
│   │   ├── services.php
│   │   ├── messages.php
│   │   ├── my_bookings.php
│   │   └── shipping_address.php
│   └── assets/
│       ├── css/style.css
│       ├── js/app.js
│       └── images/uploads/     ← Uploaded product images (gitignored)
│
└── tests/                      ← Playwright E2E tests
    ├── auth.spec.js
    ├── admin.spec.js
    ├── staff.spec.js
    ├── customer.spec.js
    └── helpers/
        └── auth.js
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
