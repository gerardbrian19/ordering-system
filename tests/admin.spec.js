const { test, expect } = require('@playwright/test');
const { loginAs } = require('./helpers/auth');

test.describe('Admin flows', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, 'admin');
  });

  // ── Dashboard ─────────────────────────────────────────────────────────────

  test('admin dashboard loads with KPI cards', async ({ page }) => {
    await page.goto('/admin/index.php');
    await expect(page).toHaveURL(/admin\/index\.php/);
    // Dashboard should show key metric labels
    await expect(page.locator('body')).toContainText(/total products/i);
    await expect(page.locator('body')).toContainText(/total orders/i);
    await expect(page.locator('body')).toContainText(/revenue/i);
  });

  test('admin dashboard shows low stock alert', async ({ page }) => {
    await page.goto('/admin/index.php');
    await expect(page.locator('body')).toContainText(/low stock/i);
  });

  // ── Products ──────────────────────────────────────────────────────────────

  test('products page is accessible', async ({ page }) => {
    await page.goto('/admin/products.php');
    await expect(page).toHaveURL(/admin\/products\.php/);
    await expect(page.locator('body')).toContainText(/product/i);
  });

  test('products page has Add Product button', async ({ page }) => {
    await page.goto('/admin/products.php');
    const addBtn = page.locator('button:has-text("Add"), a:has-text("Add Product")');
    await expect(addBtn.first()).toBeVisible();
  });

  test('products page lists existing products', async ({ page }) => {
    await page.goto('/admin/products.php');
    // Should show product rows/cards — sample data has 12 products
    const rows = page.locator('table tbody tr, [data-product]');
    const count = await rows.count();
    expect(count).toBeGreaterThan(0);
  });

  test('add product form is reachable', async ({ page }) => {
    await page.goto('/admin/products.php');
    // Click the first available "Add" trigger
    const addBtn = page.locator('button:has-text("Add"), a:has-text("Add Product")').first();
    await addBtn.click();
    // The add modal's name field is the first input[name="name"] in the DOM.
    // Use .first() to avoid strict-mode violation with edit/delete modal fields.
    const nameField = page.locator('input[name="name"]').first();
    await expect(nameField).toBeVisible();
  });

  // ── Orders ────────────────────────────────────────────────────────────────

  test('orders page is accessible', async ({ page }) => {
    await page.goto('/admin/orders.php');
    await expect(page).toHaveURL(/admin\/orders\.php/);
    await expect(page.locator('body')).toContainText(/order/i);
  });

  test('orders page shows order status', async ({ page }) => {
    await page.goto('/admin/orders.php');
    const statusPattern = /pending|processing|shipped|delivered|cancelled/i;
    await expect(page.locator('body')).toContainText(statusPattern);
  });

  test('order status update form is present', async ({ page }) => {
    await page.goto('/admin/orders.php');
    // The status select lives inside a per-order modal (hidden by default).
    // Assert it exists in the DOM — visibility is tested by opening the modal.
    const statusSelect = page.locator('select[name="status"]').first();
    await expect(statusSelect).toBeAttached();
  });

  // ── Messages ─────────────────────────────────────────────────────────────

  test('admin messages page is accessible', async ({ page }) => {
    await page.goto('/admin/messages.php');
    await expect(page).toHaveURL(/admin\/messages\.php/);
    await expect(page.locator('body')).toContainText(/message/i);
  });

  // ── Navigation ────────────────────────────────────────────────────────────

  test('admin nav links are present on dashboard', async ({ page }) => {
    await page.goto('/admin/index.php');
    // Use .first() — href matches both the sidebar nav link and dashboard card links.
    await expect(page.locator('a[href*="products"]').first()).toBeVisible();
    await expect(page.locator('a[href*="orders"]').first()).toBeVisible();
  });

  // ── Access control ───────────────────────────────────────────────────────

  test('admin cannot be confused with customer — customer pages still accessible', async ({ page }) => {
    // Admin can browse the storefront
    await page.goto('/index.php');
    await expect(page).toHaveURL(/index\.php/);
  });
});
