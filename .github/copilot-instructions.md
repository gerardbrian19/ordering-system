# ShopEase — Copilot Coding Instructions

## Project Identity
- **Name:** ShopEase (Simple Ecommerce — College Thesis)
- **Stack:** PHP 8.5 · MySQL 9.6 · Tailwind CSS (CDN) · Vanilla JS
- **No frameworks:** No Laravel, no Symfony, no Composer autoloading
- **Dev server:** `php -S localhost:8000 -t public/`

---

## File Structure Conventions
- All web-accessible files live in `public/`
- Shared logic lives in `includes/` (never web-accessible)
- Database config lives in `config/config.php`
- Admin pages live in `admin/`

---

## PHP Conventions
- Use PHP 8.x features: named arguments, match expressions, null coalescing
- Always use **PDO** with prepared statements — never raw `mysqli` queries or string interpolation in SQL
- Hash passwords with `password_hash($password, PASSWORD_BCRYPT)`
- Verify with `password_verify($input, $hash)`
- Regenerate session ID on login: `session_regenerate_id(true)`
- Start every protected page with a session check at the top
- Use `require_once` for includes, not `include`
- Keep PHP logic at the top of `.php` files, HTML output below

### DB Connection Pattern
```php
// includes/db.php
require_once __DIR__ . '/../config/config.php';
$pdo = new PDO(
    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
    DB_USER, DB_PASS,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
);
```

### Auth Guard Pattern
```php
// At top of protected pages
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
```

---

## HTML / Tailwind Conventions
- Use **Tailwind CSS CDN** (`<script src="https://cdn.tailwindcss.com"></script>`)
- All pages use a consistent `<head>` template (charset, viewport, title, Tailwind)
- Class order: layout → spacing → color → typography → state
- Prefer Tailwind utility classes over custom CSS
- Custom CSS only in `public/assets/css/style.css` for things Tailwind can't do

---

## JavaScript Conventions
- Vanilla JS only — no jQuery, no npm, no bundler
- Use `const`/`let`, arrow functions, template literals, `async/await`
- Cart state is stored in `localStorage` as a JSON array
- Use `fetch()` for any AJAX calls to PHP endpoints
- Add event listeners with `addEventListener`, not inline `onclick`

---

## Security Rules (Always Enforce)
- NEVER put user input directly into SQL — use PDO placeholders
- NEVER output user input directly into HTML — use `htmlspecialchars()`
- Add CSRF tokens to all POST forms
- Admin pages must check `$_SESSION['role'] === 'admin'`
- Validate all inputs server-side, client-side validation is supplementary only

---

## Naming Conventions
| Thing         | Convention              | Example                  |
|---------------|-------------------------|--------------------------|
| PHP files     | `snake_case.php`        | `product_detail.php`     |
| CSS classes   | Tailwind utilities      | `bg-indigo-600`          |
| JS functions  | `camelCase`             | `addToCart()`            |
| DB tables     | `snake_case` plural     | `order_items`            |
| DB columns    | `snake_case`            | `created_at`             |
| PHP variables | `$camelCase`            | `$productList`           |
| Constants     | `UPPER_SNAKE_CASE`      | `DB_HOST`                |

---

## Design System (Quick Reference)
- Primary color: `indigo-600` / hover: `indigo-700`
- Accent: `orange-500`
- Background: `gray-50`
- Cards: `bg-white rounded-xl shadow-sm border border-gray-100`
- Inputs: `border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500`
- Primary button: `bg-indigo-600 text-white font-semibold rounded-lg px-4 py-2 hover:bg-indigo-700 transition`

---

## What NOT to Do
- Do not use `echo` for large HTML blocks — use PHP close/open tags
- Do not use `$_GET`/`$_POST` without validation
- Do not store plain text passwords
- Do not add `var_dump` or `print_r` debug output in production paths
- Do not use inline styles when Tailwind classes exist
