"""
Generate realistic-looking Syrian national ID card images for seeder test data.
Usage: python scripts/generate_id_images.py
"""

import os
import sys
from PIL import Image, ImageDraw, ImageFont
import arabic_reshaper
from bidi.algorithm import get_display

OUTPUT_DIR = os.path.join(os.path.dirname(__file__), '..', 'storage', 'app', 'public', 'identity')
os.makedirs(OUTPUT_DIR, exist_ok=True)

# Try Arabic-capable fonts on Windows
FONT_CANDIDATES = [
    'C:/Windows/Fonts/arabtype.ttf',
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
    """Reshape and apply BiDi to Arabic text for correct PIL rendering."""
    reshaped = arabic_reshaper.reshape(str(text))
    return get_display(reshaped)

def draw_ar(draw, pos, text, font, fill, anchor='rm'):
    """Draw Arabic text with proper shaping."""
    draw.text(pos, arabic(text), font=font, fill=fill, anchor=anchor)

# ── ID Card data ──────────────────────────────────────────────────────────────
USERS = [
    {'slug': 'hanin_hassan',    'name': 'حنين الحسن',    'id': '010503045612', 'gender': 'أنثى',  'type': 'national_id', 'dob': '1990-05-03', 'expiry': '2029-08-15'},
    {'slug': 'mohammad_ali',    'name': 'محمد العلي',    'id': '020401078934', 'gender': 'ذكر',   'type': 'national_id', 'dob': '1985-01-04', 'expiry': '2027-03-20'},
    {'slug': 'omar_mohammad',   'name': 'عمر المحمد',    'id': 'P12938745',    'gender': 'ذكر',   'type': 'passport',    'dob': '1992-03-12', 'expiry': '2030-11-01'},
    {'slug': 'ali_ahmad',       'name': 'علي الأحمد',   'id': '030204056712', 'gender': 'ذكر',   'type': 'national_id', 'dob': '1988-04-02', 'expiry': '2028-05-10'},
    {'slug': 'khalid_khateeb',  'name': 'خالد الخطيب',  'id': '040106089023', 'gender': 'ذكر',   'type': 'national_id', 'dob': '1991-06-01', 'expiry': '2026-09-30'},
    {'slug': 'sara_zahrani',    'name': 'سارة الزهراني', 'id': '050307034561', 'gender': 'أنثى',  'type': 'national_id', 'dob': '1995-07-03', 'expiry': '2025-12-01'},
    {'slug': 'yousef_husayni',  'name': 'يوسف الحسيني', 'id': '060205067890', 'gender': 'ذكر',   'type': 'national_id', 'dob': '1987-05-02', 'expiry': '2028-06-30'},
    {'slug': 'rana_omar',       'name': 'رنا العمر',     'id': '070108023456', 'gender': 'أنثى',  'type': 'national_id', 'dob': '1993-08-01', 'expiry': '2026-12-01'},
]

# ── Colours ───────────────────────────────────────────────────────────────────
BG_GREEN   = (0,  110,  60)
BG_DARK    = (0,   70,  35)
BG_LIGHT   = (230, 255, 235)
GOLD       = (200, 160,  10)
WHITE      = (255, 255, 255)
DARK_TEXT  = (20,  20,  20)
GRAY       = (120, 120, 120)
RED        = (180,  30,  30)

W, H = 640, 400   # card dimensions (px)

def make_front(user):
    img = Image.new('RGB', (W, H), color=BG_LIGHT)
    draw = ImageDraw.Draw(img)

    # Header bar
    draw.rectangle([0, 0, W, 80], fill=BG_GREEN)
    # Footer bar
    draw.rectangle([0, H-50, W, H], fill=BG_DARK)

    # Header text
    f_title = load_font(22)
    f_label = load_font(16)
    f_value = load_font(20)
    f_big   = load_font(26)
    f_small = load_font(13)

    card_title = 'بطاقة الهوية الوطنية' if user['type'] == 'national_id' else 'جواز السفر'
    draw_ar(draw, (W - 20, 30), 'الجمهورية العربية السورية', font=f_label, fill=WHITE)
    draw_ar(draw, (W - 20, 55), card_title, font=f_title, fill=GOLD)

    # Gold side stripe
    draw.rectangle([0, 0, 12, H], fill=GOLD)

    # Divider line
    draw.line([20, 90, W-20, 90], fill=BG_GREEN, width=2)

    # Fields — right-aligned Arabic labels + values
    right = W - 30
    fields = [
        ('الاسم الكامل',  user['name']),
        ('رقم الهوية',    user['id']),
        ('الجنس',         user['gender']),
        ('تاريخ الميلاد', user['dob']),
        ('تاريخ الانتهاء',user['expiry']),
    ]

    y = 105
    for label, value in fields:
        draw_ar(draw, (right, y),      label, font=f_small, fill=GRAY)
        draw_ar(draw, (right, y + 20), value, font=f_value, fill=DARK_TEXT)
        y += 58

    # Photo placeholder
    draw.rectangle([30, 100, 155, 250], fill=(200, 210, 200), outline=BG_GREEN, width=2)
    draw_ar(draw, (93, 175), 'صورة', font=f_label, fill=GRAY, anchor='mm')

    # MRZ-style footer text
    mrz = f"{user['id']:<20}  {user['name'][:20]}"
    draw.text((20, H - 35), mrz, font=load_font(11), fill=(180, 230, 180))

    return img


def make_back(user):
    img = Image.new('RGB', (W, H), color=BG_LIGHT)
    draw = ImageDraw.Draw(img)

    draw.rectangle([0, 0, W, 60], fill=BG_DARK)
    draw.rectangle([0, H-40, W, H], fill=BG_GREEN)
    draw.rectangle([0, 0, 12, H], fill=GOLD)

    f_label = load_font(16)
    f_value = load_font(18)
    f_small = load_font(13)

    draw_ar(draw, (W - 20, 30), 'الوجه الخلفي — بطاقة الهوية', font=f_label, fill=WHITE)

    right = W - 30
    y = 80

    back_fields = [
        ('رقم الوثيقة',    user['id']),
        ('الجنسية',        'سورية'),
        ('مكان الإصدار',   'دمشق'),
        ('تاريخ الإصدار',  '2020-01-15'),
        ('تاريخ الانتهاء', user['expiry']),
    ]

    for label, value in back_fields:
        draw_ar(draw, (right, y),      label, font=f_small, fill=GRAY)
        draw_ar(draw, (right, y + 22), value, font=f_value, fill=DARK_TEXT)
        y += 60

    # Barcode placeholder
    for i in range(0, 100, 4):
        w = 2 if i % 8 == 0 else 1
        draw.rectangle([30 + i*2, 260, 30 + i*2 + w, 320], fill=DARK_TEXT)

    draw_ar(draw, (W//2, H - 20), 'وزارة الداخلية', font=f_small, fill=WHITE, anchor='mm')

    return img


# ── Generate all images ───────────────────────────────────────────────────────
for user in USERS:
    front = make_front(user)
    back  = make_back(user)

    front_path = os.path.join(OUTPUT_DIR, f"id_front_{user['slug']}.png")
    back_path  = os.path.join(OUTPUT_DIR, f"id_back_{user['slug']}.png")

    front.save(front_path)
    back.save(back_path)
    print(f"OK: {user['slug']}")

print("Done! Images saved to: " + OUTPUT_DIR)
