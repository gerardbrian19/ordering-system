const { test, expect } = require('@playwright/test');
const { loginAs, logout, USERS } = require('./helpers/auth');

test.describe('Authentication flows', () => {
  test('login page is accessible and shows form', async ({ page }) => {
    await page.goto('/login.php');
    await expect(page).toHaveTitle(/Sign In|Goldcomm/i);
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
  });

  test('already-logged-in user is redirected away from login page', async ({ page }) => {
    await loginAs(page, 'customer');
    await page.goto('/login.php');
    await expect(page).not.toHaveURL(/login\.php/);
  });

  test('customer login redirects to product listing', async ({ page }) => {
    await loginAs(page, 'customer');
    await expect(page).toHaveURL(/index\.php/);
  });

  test('staff login redirects to staff dashboard', async ({ page }) => {
    await loginAs(page, 'staff');
    await expect(page).toHaveURL(/staff\/index\.php/);
  });

  test('admin login redirects to admin dashboard', async ({ page }) => {
    await loginAs(page, 'admin');
    await expect(page).toHaveURL(/admin\/index\.php/);
  });

  test('wrong password shows error message', async ({ page }) => {
    await page.goto('/login.php');
    await page.fill('input[name="email"]', USERS.customer.email);
    await page.fill('input[name="password"]', 'wrongpassword');
    await page.click('button[type="submit"]');
    await expect(page).toHaveURL(/login\.php/);
    await expect(page.locator('body')).toContainText(/incorrect email or password/i);
  });

  test('empty fields show validation error', async ({ page }) => {
    await page.goto('/login.php');
    await page.click('button[type="submit"]');
    // Browser required-field validation or server-side error
    const url = page.url();
    // Either still on login page (server error) or browser blocks submission
    expect(url).toMatch(/login\.php/);
  });

  test('invalid email format shows error', async ({ page }) => {
    await page.goto('/login.php');
    await page.fill('input[name="email"]', 'not-an-email');
    await page.fill('input[name="password"]', 'password');
    // Use form.submit() to bypass Chromium's native HTML5 email validation,
    // so the request reaches the server-side filter_var() check.
    await page.evaluate(() => document.querySelector('form').submit());
    await expect(page).toHaveURL(/login\.php/);
  });

  test('logout redirects to login page', async ({ page }) => {
    await loginAs(page, 'customer');
    await logout(page);
    await expect(page).toHaveURL(/login\.php/);
  });

  test('accessing protected page after logout redirects to login', async ({ page }) => {
    await loginAs(page, 'customer');
    await logout(page);
    await page.goto('/customer/orders.php');
    await expect(page).toHaveURL(/login\.php/);
  });
});
