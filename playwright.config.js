// @ts-check
const { defineConfig, devices } = require('@playwright/test');

/**
 * Playwright configuration for ShopEase E2E tests.
 * Dev server: php -S localhost:8000 -t public/
 * @see https://playwright.dev/docs/test-configuration
 */
module.exports = defineConfig({
  testDir: './tests',

  // Run tests sequentially — PHP session state can conflict with parallel runs
  fullyParallel: false,
  workers: 1,

  // No retries during development
  retries: 0,

  reporter: [['list'], ['html', { open: 'never' }]],

  use: {
    baseURL: 'http://localhost:8000',

    // Keep traces on first retry for debugging
    trace: 'on-first-retry',

    // Screenshots on failure
    screenshot: 'only-on-failure',
  },

  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
  ],

  // Automatically start the PHP dev server before running tests.
  // Remove this block if you prefer to start the server manually.
  webServer: {
    command: 'php -S localhost:8000 -t public/',
    url: 'http://localhost:8000',
    reuseExistingServer: true,
    stdout: 'ignore',
    stderr: 'pipe',
  },
});
