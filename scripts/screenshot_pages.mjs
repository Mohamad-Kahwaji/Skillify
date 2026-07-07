import { chromium } from 'playwright';

const BASE = 'http://127.0.0.1:8123';
const OUT_DIR = 'storage/app/public/galleries';

const browser = await chromium.launch();
const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });

// Landing page (public)
await page.goto(BASE + '/', { waitUntil: 'networkidle', timeout: 30000 });
await page.waitForTimeout(1000);
await page.screenshot({ path: `${OUT_DIR}/portfolio_khaled_1.png` });
console.log('Saved portfolio_khaled_1.png (landing)');

// Log in as the demo user
await page.goto(BASE + '/login', { waitUntil: 'networkidle', timeout: 30000 });
await page.fill('input[type="tel"]', '70123456');
await page.fill('input[type="password"]', 'Demo@12345');
await Promise.all([
    page.waitForNavigation({ waitUntil: 'networkidle', timeout: 30000 }).catch(() => {}),
    page.click('button[type="submit"]'),
]);
await page.waitForTimeout(1500);

const authedPages = [
    { path: '/user/dashboard', name: 'portfolio_khaled_2.png' },
    { path: '/user/explore', name: 'portfolio_khaled_3.png' },
    { path: '/user/community-posts', name: 'portfolio_khaled_4.png' },
];

for (const p of authedPages) {
    await page.goto(BASE + p.path, { waitUntil: 'networkidle', timeout: 30000 });
    await page.waitForTimeout(1200);
    await page.screenshot({ path: `${OUT_DIR}/${p.name}` });
    console.log('Saved', p.name, 'from', p.path);
}

await browser.close();
