# ShopEase — Technical Roadmap & Infrastructure Notes

---

## 📧 Email Service

### Recommended Providers
| Provider | Free Tier | Use Case |
|---|---|---|
| **Resend** | 3,000/month | Production — best deliverability, simple REST API |
| **Brevo (Sendinblue)** | 300/day | Alternative with SMTP support |
| **Gmail SMTP** | 500/day | Development & testing only |

### Setup Checklist
- [ ] Choose provider: Resend (prod) or Gmail SMTP (dev)
- [ ] Download PHPMailer (single file, no Composer required)
- [ ] Add SMTP constants to `config/config.php`
  ```php
  define('SMTP_HOST', 'smtp.gmail.com');
  define('SMTP_USER', 'your@email.com');
  define('SMTP_PASS', 'your-app-password');
  define('SMTP_FROM', 'noreply@shopease.com');
  define('SMTP_PORT', 587);
  ```
- [ ] Create `includes/mailer.php` with a `sendMail(string $to, string $subject, string $htmlBody): bool` helper
- [ ] Create email templates for:
  - Order confirmation / invoice
  - OTP / email verification
  - Password reset

### OTP Flow
1. Generate: `$otp = random_int(100000, 999999)`
2. Store in `otp_tokens` table with a 15-minute expiry
3. Send via `sendMail()`
4. Verify on form submit → mark as used → delete row

---

## 🗄️ Database Migrations

### How It Works (No Framework)
Since ShopEase uses no framework, migrations are **versioned SQL files** tracked by a `migrations` table.

**Bootstrap once (run manually):**
```sql
CREATE TABLE migrations (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  filename  VARCHAR(255) UNIQUE NOT NULL,
  ran_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Run migrations:**
```bash
php migrate.php
```

**`migrate.php` logic:** reads `migrations/*.sql` in order, skips already-ran files, records each run in the `migrations` table.

### Migration Files to Create (`migrations/`)
- [ ] `001_create_users.sql`
- [ ] `002_create_products.sql`
- [ ] `003_create_orders.sql`
- [ ] `004_create_order_items.sql`
- [ ] `005_create_messages.sql`
- [ ] `006_create_otp_tokens.sql`
- [ ] `007_create_shipping_addresses.sql`
- [ ] `008_add_indexes.sql` — `users.email`, `orders.user_id`, `orders.status`, `products.category`

### Migration Workflow Rules
- **Never edit** a migration file that has already been run — create a new numbered file instead
- Migration filenames must be zero-padded and sequential: `001_`, `002_`, etc.
- Always test migrations locally before running on production

---

## ⚡ Performance & Scaling

### Database
- [ ] Add indexes on all foreign keys and frequently filtered columns
  ```sql
  ALTER TABLE orders ADD INDEX idx_user_id (user_id);
  ALTER TABLE orders ADD INDEX idx_status (status);
  ALTER TABLE order_items ADD INDEX idx_order_id (order_id);
  ALTER TABLE users ADD INDEX idx_email (email);
  ```
- [ ] Use `LIMIT` on every list/pagination query — never `SELECT *` unbounded
- [ ] Cache the product catalog in `$_SESSION` for the request lifetime to reduce repeated reads

### OTP / Email Abuse Prevention
- [ ] Create a `rate_limits` table (keyed by IP + action)
- [ ] Block > 5 OTP requests per IP per hour
- [ ] Add a 60-second cooldown between resend attempts

### Server (Production)
- [ ] Switch from PHP built-in server (`php -S`) to **Nginx + PHP-FPM**
- [ ] PHP-FPM manages a worker pool — handles concurrent requests properly
- [ ] Tune `pm.max_children` in `php-fpm.conf` based on available RAM (rule of thumb: RAM MB ÷ 30)

---

## 🌐 Hosting

### Recommended Platforms
| Platform | Cost | Best For |
|---|---|---|
| **Railway** | Free / $5/mo | Fastest deploy — Git push → live. MySQL add-on available. |
| **DigitalOcean Droplet** | $6/mo | Full control, closest to real production, good for thesis demo |
| **Render** | Free tier | Alternative to Railway, free tier sleeps after inactivity |
| **Hostinger** | $2–4/mo | Shared hosting, easiest for non-developers |

**Thesis recommendation:** Railway for speed, or DigitalOcean for demonstrating real-world setup at defense.

### Production Deployment Checklist
- [ ] Set up **Nginx + PHP-FPM** (not the built-in PHP server)
- [ ] Enable **HTTPS** with Let's Encrypt via Certbot (free)
  ```bash
  sudo certbot --nginx -d yourdomain.com
  ```
- [ ] Move DB credentials out of `config.php` into **environment variables**
  ```php
  define('DB_PASS', $_ENV['DB_PASS'] ?? '');
  ```
- [ ] Set `display_errors = Off` and `log_errors = On` in `php.ini`
- [ ] Block direct access to sensitive directories via Nginx:
  ```nginx
  location ~* ^/(includes|config|migrations)/ { deny all; }
  ```
- [ ] Set up a **cron job** for daily database backups:
  ```bash
  0 2 * * * mysqldump -u root -p shopease > /backups/shopease_$(date +\%F).sql
  ```
- [ ] Enable **MySQL slow query log** to catch performance issues early

---

## 🔐 Security Hardening (Pre-Launch)

- [ ] Confirm CSRF tokens on every POST form
- [ ] Confirm `htmlspecialchars()` on every user-supplied output
- [ ] Set `session.cookie_httponly = 1` and `session.cookie_secure = 1` in `php.ini`
- [ ] Set `session.cookie_samesite = Strict`
- [ ] Add `X-Content-Type-Options: nosniff` and `X-Frame-Options: DENY` response headers
- [ ] Validate all file uploads (type, size, store outside web root)
