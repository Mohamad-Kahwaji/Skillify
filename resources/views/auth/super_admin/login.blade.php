<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>دخول المدير العام — Skillify</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font:           'Cairo', sans-serif;
      /* ── Violet brand (matches SuperAdminLayout sidebar) ── */
      --accent:         #7C3AED;
      --accent-hover:   #6D28D9;
      --accent-light:   #A78BFA;
      --accent-dim:     rgba(124,58,237,.14);
      --accent-border:  rgba(167,139,250,.28);
      --accent-glow:    rgba(124,58,237,.22);
      /* ── Backgrounds ── */
      --bg-deep:        #070614;
      --bg-panel:       #0C0A1F;
      --bg-surface:     #100E26;
      --bg-field:       #0A0818;
      /* ── Borders ── */
      --border:         rgba(255,255,255,.055);
      --border-md:      rgba(255,255,255,.10);
      --border-focus:   rgba(124,58,237,.55);
      /* ── Text ── */
      --text-primary:   #EDE9FE;
      --text-secondary: #8B80C0;
      --text-muted:     #3D3568;
      /* ── Status ── */
      --red-text:       #F9A8A8;
      --red-bg:         rgba(220,38,38,.10);
      --red-border:     rgba(220,38,38,.22);
      --green-text:     #A7F3D0;
      --green-bg:       rgba(16,185,129,.10);
      --green-border:   rgba(16,185,129,.22);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: var(--font); font-size: 14px; line-height: 1.6; }
    body { display: flex; background: var(--bg-deep); color: var(--text-primary); }
    a { text-decoration: none; color: inherit; }
    button { font-family: var(--font); cursor: pointer; }

    /* ════════════════════════════════════════
       Shell
    ════════════════════════════════════════ */
    .auth-shell {
      display: flex; width: 100%; min-height: 100vh;
      /* Right panel first (RTL: brand is on right) */
      flex-direction: row-reverse;
    }

    /* ════════════════════════════════════════
       Brand panel (right in RTL)
    ════════════════════════════════════════ */
    .auth-panel {
      width: 420px; flex-shrink: 0;
      background: var(--bg-panel);
      border-left: 0.5px solid var(--border);
      display: flex; flex-direction: column;
      justify-content: space-between;
      padding: 52px 44px;
      position: relative; overflow: hidden;
    }
    /* Glow blobs */
    .auth-panel::before {
      content: '';
      position: absolute; top: -140px; right: -120px;
      width: 420px; height: 420px; border-radius: 50%;
      background: radial-gradient(circle, rgba(124,58,237,.18) 0%, transparent 68%);
      pointer-events: none;
    }
    .auth-panel::after {
      content: '';
      position: absolute; bottom: -100px; left: -100px;
      width: 340px; height: 340px; border-radius: 50%;
      background: radial-gradient(circle, rgba(167,139,250,.07) 0%, transparent 65%);
      pointer-events: none;
    }

    .panel-top { position: relative; z-index: 1; }

    /* Brand row */
    .panel-brand { display: flex; align-items: center; gap: 13px; margin-bottom: 56px; }
    .panel-icon {
      width: 44px; height: 44px; border-radius: 13px; flex-shrink: 0;
      background: linear-gradient(135deg, var(--accent) 0%, #4C1D95 100%);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 20px;
      box-shadow: 0 0 24px var(--accent-glow);
    }
    .panel-brand-name {
      font-size: 20px; font-weight: 800; color: #fff; letter-spacing: -0.3px;
    }
    .panel-badge {
      display: inline-flex; align-items: center; gap: 5px;
      background: var(--accent-dim); border: 0.5px solid var(--accent-border);
      color: var(--accent-light); font-size: 10px; font-weight: 700;
      padding: 3px 9px; border-radius: 20px; letter-spacing: 0.7px; text-transform: uppercase;
      margin-top: 3px;
    }

    .panel-headline {
      font-size: 26px; font-weight: 800; color: #fff;
      line-height: 1.3; letter-spacing: -0.5px; margin: 20px 0 12px;
    }
    .panel-headline span { color: var(--accent-light); }
    .panel-desc {
      font-size: 13px; color: var(--text-secondary);
      line-height: 1.85; margin-bottom: 40px;
    }

    /* Feature cards */
    .panel-features { display: flex; flex-direction: column; gap: 12px; }
    .feature {
      display: flex; align-items: center; gap: 13px;
      padding: 13px 15px; border-radius: 12px;
      background: rgba(124,58,237,.06);
      border: 0.5px solid var(--border);
      transition: border-color 0.15s, background 0.15s;
    }
    .feature:hover {
      background: rgba(124,58,237,.10);
      border-color: var(--accent-border);
    }
    .feature-icon {
      width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
      background: var(--accent-dim); border: 0.5px solid var(--accent-border);
      display: flex; align-items: center; justify-content: center;
      font-size: 15px; color: var(--accent-light);
    }
    .feature-title  { font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 1px; }
    .feature-text   { font-size: 11.5px; color: var(--text-secondary); line-height: 1.5; }

    /* Divider */
    .panel-divider {
      height: 0.5px; background: var(--border); margin: 36px 0;
    }

    /* Security badge row */
    .security-row {
      display: flex; align-items: center; gap: 8px;
      font-size: 11.5px; color: var(--text-muted);
    }
    .security-dot {
      width: 6px; height: 6px; border-radius: 50%;
      background: var(--accent-light); box-shadow: 0 0 6px var(--accent-glow);
      flex-shrink: 0; animation: pulse-dot 2s ease-in-out infinite;
    }
    @keyframes pulse-dot {
      0%, 100% { opacity: 1; }
      50%       { opacity: 0.4; }
    }

    .panel-bottom { position: relative; z-index: 1; }
    .panel-footer  { font-size: 11px; color: rgba(255,255,255,.14); margin-top: 24px; }

    /* ════════════════════════════════════════
       Form area (left in RTL)
    ════════════════════════════════════════ */
    .auth-form {
      flex: 1; display: flex; align-items: center; justify-content: center;
      padding: 40px 24px; background: var(--bg-surface);
      position: relative;
    }
    /* Subtle top-right accent */
    .auth-form::before {
      content: '';
      position: absolute; top: 0; left: 0;
      width: 100%; height: 2px;
      background: linear-gradient(90deg, transparent, var(--accent) 50%, transparent);
      opacity: 0.35;
    }

    .form-wrap { width: 100%; max-width: 380px; }

    /* Header */
    .form-eyebrow {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--accent-dim); color: var(--accent-light);
      border: 0.5px solid var(--accent-border);
      font-size: 10.5px; font-weight: 700; padding: 4px 11px;
      border-radius: 20px; letter-spacing: 0.6px; text-transform: uppercase;
      margin-bottom: 16px;
    }
    .form-title {
      font-size: 24px; font-weight: 800;
      color: var(--text-primary); letter-spacing: -0.4px; margin-bottom: 7px;
    }
    .form-sub { font-size: 13px; color: var(--text-secondary); margin-bottom: 30px; line-height: 1.7; }

    /* Alerts */
    .alert {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 12px 14px; border-radius: 10px;
      font-size: 13px; margin-bottom: 22px; line-height: 1.5;
    }
    .alert i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .alert.error   { background: var(--red-bg);   color: var(--red-text);   border: 0.5px solid var(--red-border); }
    .alert.success { background: var(--green-bg); color: var(--green-text); border: 0.5px solid var(--green-border); }

    /* Fields */
    .form-group { margin-bottom: 20px; }
    .form-label {
      display: flex; align-items: center; justify-content: space-between;
      font-size: 12px; font-weight: 700; color: var(--text-secondary);
      margin-bottom: 8px; letter-spacing: 0.15px;
    }
    .form-label a {
      font-weight: 600; color: var(--accent-light); font-size: 11.5px;
      transition: color 0.12s;
    }
    .form-label a:hover { color: #fff; }

    .input-wrap {
      display: flex; align-items: center;
      background: var(--bg-field);
      border: 1px solid var(--border-md);
      border-radius: 11px; overflow: hidden;
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    .input-wrap:focus-within {
      border-color: var(--border-focus);
      box-shadow: 0 0 0 3px rgba(124,58,237,.10);
    }
    .input-icon { padding: 0 13px; font-size: 16px; color: var(--text-muted); flex-shrink: 0; }
    .input-wrap input {
      flex: 1; border: none; outline: none;
      background: transparent; padding: 12px 12px 12px 0;
      font-size: 13.5px; color: var(--text-primary); font-family: var(--font);
    }
    .input-wrap input::placeholder { color: var(--text-muted); }
    .pw-toggle {
      padding: 0 13px; background: none; border: none;
      color: var(--text-muted); font-size: 16px; flex-shrink: 0;
      display: flex; align-items: center;
      transition: color 0.12s;
    }
    .pw-toggle:hover { color: var(--text-primary); }
    .field-error {
      display: flex; align-items: center; gap: 5px;
      font-size: 11px; color: var(--red-text); margin-top: 7px;
    }
    .field-error::before {
      content: ''; width: 4px; height: 4px; border-radius: 50%;
      background: var(--red-text); flex-shrink: 0;
    }

    /* Remember */
    .remember-row { display: flex; align-items: center; gap: 9px; margin-bottom: 24px; }
    .checkbox-wrap { position: relative; width: 17px; height: 17px; flex-shrink: 0; }
    .checkbox-wrap input { position: absolute; opacity: 0; width: 0; height: 0; }
    .checkbox-custom {
      position: absolute; inset: 0; border-radius: 5px;
      border: 1.5px solid var(--border-md);
      background: var(--bg-field);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; transition: all 0.15s;
    }
    .checkbox-wrap input:checked + .checkbox-custom {
      background: var(--accent); border-color: var(--accent);
      box-shadow: 0 0 8px var(--accent-glow);
    }
    .checkbox-custom::after {
      content: ''; display: none;
      width: 8px; height: 5px;
      border-left: 2px solid #fff; border-bottom: 2px solid #fff;
      transform: rotate(-45deg) translateY(-1px);
    }
    .checkbox-wrap input:checked + .checkbox-custom::after { display: block; }
    .remember-label { font-size: 13px; color: var(--text-secondary); cursor: pointer; }

    /* Submit */
    .btn-submit {
      width: 100%; padding: 13px;
      background: var(--accent); color: #fff;
      border: none; border-radius: 11px;
      font-size: 14px; font-weight: 700; font-family: var(--font);
      cursor: pointer; transition: background 0.15s, box-shadow 0.15s, transform 0.1s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 3px 16px rgba(124,58,237,.30);
      letter-spacing: 0.2px;
    }
    .btn-submit:hover {
      background: var(--accent-hover);
      box-shadow: 0 6px 22px rgba(124,58,237,.40);
      transform: translateY(-1px);
    }
    .btn-submit:active { transform: translateY(0); }
    .btn-submit.loading { pointer-events: none; opacity: 0.72; }
    .btn-submit .spinner {
      display: none; width: 16px; height: 16px;
      border: 2px solid rgba(255,255,255,.3);
      border-top-color: #fff; border-radius: 50%;
      animation: spin 0.7s linear infinite;
    }
    .btn-submit.loading .spinner { display: block; }
    .btn-submit.loading .btn-text { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Footer */
    .form-footer {
      text-align: center; margin-top: 22px;
      font-size: 12px; color: var(--text-muted);
    }
    .form-footer a { color: var(--accent-light); font-weight: 600; transition: color 0.12s; }
    .form-footer a:hover { color: #fff; }

    @media (max-width: 820px) {
      .auth-panel  { display: none; }
      .auth-shell  { flex-direction: column; }
    }
  </style>
</head>
<body>

<div class="auth-shell">

  {{-- ══════════════════ Brand Panel (right in RTL) ══════════════════ --}}
  <div class="auth-panel">
    <div class="panel-top">

      <div class="panel-brand">
        <div class="panel-icon"><i class="ti ti-shield-lock"></i></div>
        <div>
          <div class="panel-brand-name">Skillify</div>
          <div class="panel-badge"><i class="ti ti-crown" style="font-size:9px;"></i> المدير العام</div>
        </div>
      </div>

      <div class="panel-headline">
        تحكم <span>شامل وكامل</span><br>بالمنصة.
      </div>
      <div class="panel-desc">
        أدر المشرفين، الصلاحيات، الأدوار، وكل جوانب منصة Skillify
        من مكان واحد آمن ومحمي.
      </div>

      <div class="panel-features">
        <div class="feature">
          <div class="feature-icon"><i class="ti ti-user-shield"></i></div>
          <div>
            <div class="feature-title">إدارة المشرفين</div>
            <div class="feature-text">إنشاء وتفعيل وتهيئة حسابات المشرفين</div>
          </div>
        </div>
        <div class="feature">
          <div class="feature-icon"><i class="ti ti-key"></i></div>
          <div>
            <div class="feature-title">الأدوار والصلاحيات</div>
            <div class="feature-text">تحكم دقيق في صلاحيات الوصول لكل دور</div>
          </div>
        </div>
        <div class="feature">
          <div class="feature-icon"><i class="ti ti-speakerphone"></i></div>
          <div>
            <div class="feature-title">إعلانات النظام</div>
            <div class="feature-text">إرسال رسائل إلى جميع مستخدمي المنصة</div>
          </div>
        </div>
      </div>

      <div class="panel-divider"></div>

      <div class="security-row">
        <div class="security-dot"></div>
        جميع الجلسات مشفرة ومحمية — TLS 1.3
      </div>

    </div>

    <div class="panel-bottom">
      <div class="panel-footer">© {{ date('Y') }} Skillify. جميع الحقوق محفوظة.</div>
    </div>
  </div>

  {{-- ══════════════════ Form Area (left in RTL) ══════════════════ --}}
  <div class="auth-form">
    <div class="form-wrap">

      <div class="form-eyebrow">
        <i class="ti ti-crown" style="font-size:9px;"></i> بوابة المدير العام
      </div>
      <div class="form-title">وصول مقيّد</div>
      <div class="form-sub">أدخل بيانات حساب المدير العام للمتابعة.</div>

      @if(session('error'))
        <div class="alert error">
          <i class="ti ti-alert-circle"></i>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      @if(session('status'))
        <div class="alert success">
          <i class="ti ti-circle-check"></i>
          <span>{{ session('status') }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('super_admin.login.post') }}" id="loginForm">
        @csrf

        {{-- Email --}}
        <div class="form-group">
          <label class="form-label"><span>البريد الإلكتروني</span></label>
          <div class="input-wrap">
            <i class="ti ti-mail input-icon"></i>
            <input type="email" name="email"
                   placeholder="super@skillify.com"
                   value="{{ old('email') }}"
                   autofocus autocomplete="email" required />
          </div>
          @error('email')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
          <label class="form-label">
            <span>كلمة المرور</span>
            <a href="{{ route('super_admin.forgot-password') }}">نسيت كلمة المرور؟</a>
          </label>
          <div class="input-wrap">
            <i class="ti ti-lock input-icon"></i>
            <input type="password" id="password" name="password"
                   placeholder="••••••••" autocomplete="current-password" required />
            <button type="button" class="pw-toggle" onclick="togglePassword('password', this)">
              <i class="ti ti-eye"></i>
            </button>
          </div>
          @error('password')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        {{-- Remember --}}
        <div class="remember-row">
          <label class="checkbox-wrap">
            <input type="checkbox" name="remember" />
            <div class="checkbox-custom"></div>
          </label>
          <span class="remember-label">ابقَ متصلاً</span>
        </div>

        <button type="submit" class="btn-submit" id="submitBtn">
          <div class="spinner"></div>
          <span class="btn-text">
            <i class="ti ti-shield-check" style="font-size:16px;"></i>
            دخول آمن
          </span>
        </button>
      </form>

      <div class="form-footer">
        لوحة الأدمن؟ <a href="{{ route('admin.login') }}">سجّل دخول هنا ←</a>
      </div>

    </div>
  </div>

</div>

<script>
  function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'ti ti-eye' : 'ti ti-eye-off';
  }
  document.getElementById('loginForm').addEventListener('submit', function () {
    document.getElementById('submitBtn').classList.add('loading');
  });
</script>

</body>
</html>
