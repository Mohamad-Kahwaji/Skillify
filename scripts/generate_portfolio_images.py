"""
Generate placeholder business logo + portfolio gallery images for demo/screenshot purposes.
Usage: python scripts/generate_portfolio_images.py
"""

import os
from PIL import Image, ImageDraw, ImageFont
import arabic_reshaper
from bidi.algorithm import get_display

BUSINESS_DIR = os.path.join(os.path.dirname(__file__), '..', 'storage', 'app', 'public', 'businesses')
GALLERY_DIR  = os.path.join(os.path.dirname(__file__), '..', 'storage', 'app', 'public', 'galleries')
os.makedirs(BUSINESS_DIR, exist_ok=True)
os.makedirs(GALLERY_DIR, exist_ok=True)

FONT_CANDIDATES = [
    'C:/Windows/Fonts/tahoma.ttf',
    'C:/Windows/Fonts/arial.ttf',
    'C:/Windows/Fonts/calibri.ttf',
]

def load_font(size):
    for path in FONT_CANDIDATES:
        if os.path.exists(path):
            try:
                return ImageFont.truetype(path, size)
            except Exception:
                continue
    return ImageFont.load_default()

def arabic(text):
    reshaped = arabic_reshaper.reshape(str(text))
    return get_display(reshaped)

def draw_ar(draw, pos, text, font, fill, anchor='mm'):
    draw.text(pos, arabic(text), font=font, fill=fill, anchor=anchor)

def gradient_bg(w, h, c1, c2):
    base = Image.new('RGB', (w, h), c1)
    top = Image.new('RGB', (w, h), c2)
    mask = Image.new('L', (w, h))
    mask_data = []
    for y in range(h):
        mask_data.extend([int(255 * (y / h))] * w)
    mask.putdata(mask_data)
    base.paste(top, (0, 0), mask)
    return base

# ── Business logo (avatar-style, initials) ──────────────────────────
logo = gradient_bg(500, 500, (124, 58, 237), (109, 40, 217))
d = ImageDraw.Draw(logo)
font_big = load_font(160)
draw_ar(d, (250, 250), "خ.خ", font_big, (255, 255, 255))
logo_path = os.path.join(BUSINESS_DIR, 'khaled_khateeb_logo.png')
logo.save(logo_path)
print('Saved', logo_path)

# ── Portfolio project mockups ────────────────────────────────────────
projects = [
    {'title': 'منصة تجارة إلكترونية', 'sub': 'React · Laravel · MySQL', 'c1': (30, 41, 59), 'c2': (51, 65, 85)},
    {'title': 'تطبيق حجوزات مطاعم',   'sub': 'Vue.js · Node.js · MongoDB', 'c1': (12, 74, 110), 'c2': (2, 132, 199)},
    {'title': 'لوحة تحكم إدارية',      'sub': 'Inertia.js · React · Tailwind', 'c1': (76, 29, 149), 'c2': (124, 58, 237)},
    {'title': 'موقع شركة عقارية',      'sub': 'Next.js · Prisma · PostgreSQL', 'c1': (6, 78, 59), 'c2': (5, 150, 105)},
]

W, H = 900, 600
for i, p in enumerate(projects, start=1):
    img = gradient_bg(W, H, p['c1'], p['c2'])
    d = ImageDraw.Draw(img)

    # fake browser chrome bar
    d.rectangle([40, 40, W - 40, 100], fill=(255, 255, 255, 255))
    for j, cx in enumerate([70, 100, 130]):
        d.ellipse([cx - 8, 62, cx + 8, 78], fill=[(239, 68, 68), (245, 158, 11), (34, 197, 94)][j])

    # fake content blocks (browser body)
    d.rectangle([40, 100, W - 40, H - 40], fill=(255, 255, 255))
    d.rectangle([70, 140, W - 70, 190], fill=(226, 232, 240))
    d.rectangle([70, 210, 420, 420], fill=(203, 213, 225))
    d.rectangle([450, 210, W - 70, 300], fill=(226, 232, 240))
    d.rectangle([450, 320, W - 70, 420], fill=(226, 232, 240))

    font_title = load_font(34)
    font_sub = load_font(20)
    draw_ar(d, (W / 2, H - 20), p['title'], font_title, (255, 255, 255))
    draw_ar(d, (W / 2, H - 55), p['sub'], font_sub, (226, 232, 240))

    path = os.path.join(GALLERY_DIR, f'portfolio_khaled_{i}.png')
    img.save(path)
    print('Saved', path)
