import os
from PIL import Image, ImageDraw, ImageFont
import arabic_reshaper
from bidi.algorithm import get_display

BUSINESS_DIR = os.path.join(os.path.dirname(__file__), '..', 'storage', 'app', 'public', 'businesses')
os.makedirs(BUSINESS_DIR, exist_ok=True)

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
    return get_display(arabic_reshaper.reshape(str(text)))

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

people = [
    {'slug': 'sara_masri',   'initials': 'س.م', 'c1': (190, 24, 93),  'c2': (219, 39, 119)},
    {'slug': 'yousef_najjar','initials': 'ي.ن', 'c1': (2, 132, 199),  'c2': (14, 165, 233)},
    {'slug': 'reem_halabi',  'initials': 'ر.ح', 'c1': (5, 150, 105),  'c2': (16, 185, 129)},
]

for p in people:
    logo = gradient_bg(500, 500, p['c1'], p['c2'])
    d = ImageDraw.Draw(logo)
    font_big = load_font(160)
    draw_ar(d, (250, 250), p['initials'], font_big, (255, 255, 255))
    path = os.path.join(BUSINESS_DIR, f"{p['slug']}_logo.png")
    logo.save(path)
    print('Saved', path)
