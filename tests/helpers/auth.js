/**
 * Auth helper for ShopEase Playwright tests.
 * Handles login for each role using the static credentials from login_handler.php.
 */

const USERS = {
  customer: { email: 'customer@shopease.com', password: 'customer123', name: 'Juan dela Cruz' },
  staff:    { email: 'staff@shopease.com',    password: 'staff123',    name: 'Maria Santos' },
  admin:    { email: 'admin@shopease.com',     password: 'admin123',    name: 'Admin User' },
};

const REDIRECTS = {
  customer: '/index.php',
  staff:    '/staff/index.php',
  admin:    '/admin/index.php',
};

/**
 * Navigates to the login page and submits credentials for the given role.
 * Waits until the post-login page has loaded.
 *
 * @param {import('@playwright/test').Page} page
 * @param {'customer' | 'staff' | 'admin'} role
 */
async function loginAs(page, role) {
  const user = USERS[role];
  await page.goto('/login.php');
  await page.fill('input[name="email"]', user.email);
  await page.fill('input[name="password"]', user.password);
  await page.click('button[type="submit"]');
  await page.waitForURL('**' + REDIRECTS[role]);
}

/**
 * Logs the current user out by navigating to logout.php.
 *
 * @param {import('@playwright/test').Page} page
 */
async function logout(page) {
  await page.goto('/logout.php');
  await page.waitForURL('**/login.php');
}

module.exports = { loginAs, logout, USERS, REDIRECTS };
