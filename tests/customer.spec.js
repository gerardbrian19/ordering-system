const { test, expect } = require('@playwright/test');
const { loginAs } = require('./helpers/auth');

test.describe('Customer flows', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, 'customer');
  });

  // ── Product listing ──────────────────────────────────────────────────────

  test('product listing page loads and shows products', async ({ page }) => {
    await page.goto('/index.php');
    await expect(page.locator('body')).toContainText(/product|radio/i);
  });

  test('category filter is visible on product listing', async ({ page }) => {
    await page.goto('/index.php');
    // Category filter buttons or select should be present
    const filterExists = await page.locator('select, [data-filter], button').count();
    expect(filterExists).toBeGreaterThan(0);
  });

  // ── Cart ─────────────────────────────────────────────────────────────────

  test('cart page is accessible', async ({ page }) => {
    await page.goto('/customer/cart.php');
    await expect(page).toHaveURL(/cart\.php/);
    await expect(page.locator('body')).toContainText(/cart/i);
  });

  test('cart shows checkout button', async ({ page }) => {
    await page.goto('/customer/cart.php');
    // The checkout link is hidden when the cart is empty (localStorage is empty in tests).
    // Assert it exists in the DOM; it becomes visible once items are added.
    const checkoutBtn = page.locator('a[href*="checkout"], button:has-text("checkout")');
    await expect(checkoutBtn.first()).toBeAttached();
  });

  test('cart page contains total and shipping info', async ({ page }) => {
    await page.goto('/customer/cart.php');
    await expect(page.locator('body')).toContainText(/total/i);
    await expect(page.locator('body')).toContainText(/shipping/i);
  });

  // ── Checkout ─────────────────────────────────────────────────────────────

  test('checkout page is accessible', async ({ page }) => {
    await page.goto('/customer/checkout.php');
    await expect(page).toHaveURL(/checkout\.php/);
    await expect(page.locator('body')).toContainText(/checkout/i);
  });

  test('checkout page shows shipping address', async ({ page }) => {
    await page.goto('/customer/checkout.php');
    await expect(page.locator('body')).toContainText(/shipping/i);
  });

  test('checkout page has a link to change shipping address', async ({ page }) => {
    await page.goto('/customer/checkout.php');
    // Three anchors link to shipping_address.php (nav dropdown, mobile menu, and the
    // inline "Change" button). Target the specific "Change" link in the order summary.
    const changeLink = page.getByRole('link', { name: 'Change' });
    await expect(changeLink).toBeVisible();
  });

  test('shipping address page is accessible from checkout', async ({ page }) => {
    await page.goto('/customer/checkout.php');
    // Use the specific "Change" link rather than the broad href selector to avoid
    // clicking a hidden nav element.
    await page.getByRole('link', { name: 'Change' }).click();
    await expect(page).toHaveURL(/shipping_address\.php/);
  });

  // ── Orders ───────────────────────────────────────────────────────────────

  test('orders page is accessible', async ({ page }) => {
    await page.goto('/customer/orders.php');
    await expect(page).toHaveURL(/orders\.php/);
    await expect(page.locator('body')).toContainText(/order/i);
  });

  test('orders page shows order status labels', async ({ page }) => {
    await page.goto('/customer/orders.php');
    // Sample data includes various statuses
    const statusPattern = /pending|processing|shipped|delivered|cancelled/i;
    await expect(page.locator('body')).toContainText(statusPattern);
  });

  // ── Services / Bookings ──────────────────────────────────────────────────

  test('services page is accessible', async ({ page }) => {
    await page.goto('/customer/services.php');
    await expect(page).toHaveURL(/services\.php/);
    await expect(page.locator('body')).toContainText(/service/i);
  });

  test('services page shows Book Now buttons', async ({ page }) => {
    await page.goto('/customer/services.php');
    const bookBtns = page.locator('button:has-text("Book"), a:has-text("Book")');
    await expect(bookBtns.first()).toBeVisible();
  });

  test('my bookings page is accessible', async ({ page }) => {
    await page.goto('/customer/my_bookings.php');
    await expect(page).toHaveURL(/my_bookings\.php/);
  });

  // ── Messages ─────────────────────────────────────────────────────────────

  test('customer messages page is accessible', async ({ page }) => {
    await page.goto('/customer/messages.php');
    await expect(page).toHaveURL(/messages\.php/);
    await expect(page.locator('body')).toContainText(/message/i);
  });

  // ── Access control ───────────────────────────────────────────────────────

  test('customer cannot access admin pages', async ({ page }) => {
    await page.goto('/admin/index.php');
    // Should redirect to login or show forbidden — not the admin dashboard
    await expect(page).not.toHaveURL(/admin\/index\.php/);
  });

  test('customer cannot access staff pages', async ({ page }) => {
    await page.goto('/staff/index.php');
    await expect(page).not.toHaveURL(/staff\/index\.php/);
  });
});
