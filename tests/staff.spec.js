const { test, expect } = require('@playwright/test');
const { loginAs } = require('./helpers/auth');

test.describe('Staff flows', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, 'staff');
  });

  // ── Dashboard ─────────────────────────────────────────────────────────────

  test('staff dashboard loads with KPI cards', async ({ page }) => {
    await page.goto('/staff/index.php');
    await expect(page).toHaveURL(/staff\/index\.php/);
    await expect(page.locator('body')).toContainText(/pending orders/i);
    await expect(page.locator('body')).toContainText(/low stock/i);
  });

  test('staff dashboard shows unread messages count', async ({ page }) => {
    await page.goto('/staff/index.php');
    await expect(page.locator('body')).toContainText(/message/i);
  });

  // ── Inventory ─────────────────────────────────────────────────────────────

  test('inventory page is accessible', async ({ page }) => {
    await page.goto('/staff/inventory.php');
    await expect(page).toHaveURL(/staff\/inventory\.php/);
    await expect(page.locator('body')).toContainText(/inventory/i);
  });

  test('inventory page shows product list', async ({ page }) => {
    await page.goto('/staff/inventory.php');
    // Sample data has 12 products
    const rows = page.locator('table tbody tr, [data-product]');
    const count = await rows.count();
    expect(count).toBeGreaterThan(0);
  });

  test('inventory page shows stock summary cards', async ({ page }) => {
    await page.goto('/staff/inventory.php');
    await expect(page.locator('body')).toContainText(/total products/i);
    await expect(page.locator('body')).toContainText(/in stock/i);
  });

  test('inventory page is read-only for staff', async ({ page }) => {
    await page.goto('/staff/inventory.php');
    // No add/edit/delete buttons — staff is view-only
    const editBtn = page.locator('button:has-text("Edit"), a:has-text("Edit")');
    const deleteBtn = page.locator('button:has-text("Delete"), a:has-text("Delete")');
    await expect(editBtn).toHaveCount(0);
    await expect(deleteBtn).toHaveCount(0);
  });

  // ── Orders ────────────────────────────────────────────────────────────────

  test('staff orders page is accessible', async ({ page }) => {
    await page.goto('/staff/orders.php');
    await expect(page).toHaveURL(/staff\/orders\.php/);
    await expect(page.locator('body')).toContainText(/order/i);
  });

  // ── Messages ─────────────────────────────────────────────────────────────

  test('staff messages page is accessible', async ({ page }) => {
    await page.goto('/staff/messages.php');
    await expect(page).toHaveURL(/staff\/messages\.php/);
    await expect(page.locator('body')).toContainText(/message/i);
  });

  // ── Navigation ────────────────────────────────────────────────────────────

  test('staff nav links are present on dashboard', async ({ page }) => {
    await page.goto('/staff/index.php');
    // Use .first() — href matches both the sidebar nav link and dashboard card links.
    await expect(page.locator('a[href*="inventory"]').first()).toBeVisible();
    await expect(page.locator('a[href*="orders"]').first()).toBeVisible();
  });

  // ── Access control ───────────────────────────────────────────────────────

  test('staff cannot access admin pages', async ({ page }) => {
    await page.goto('/admin/index.php');
    await expect(page).not.toHaveURL(/admin\/index\.php/);
  });

  test('staff cannot access admin products management', async ({ page }) => {
    await page.goto('/admin/products.php');
    await expect(page).not.toHaveURL(/admin\/products\.php/);
  });
});
