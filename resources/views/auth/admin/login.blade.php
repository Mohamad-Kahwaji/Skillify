<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>دخول المشرف — Skillify</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font:          'Cairo', sans-serif;
      /* ── Teal brand (matches AdminLayout sidebar #0F172A + active #2DD4BF) ── */
      --accent:        #0D9488;
      --accent-hover:  #0F766E;
      --accent-light:  #2DD4BF;
      --accent-dim:    rgba(13,148,136,.12);
      --accent-border: rgba(45,212,191,.25);
      --accent-glow:   rgba(13,148,136,.20);
      /* ── Panel: matches AdminLayout sidebar #0F172A ── */
      --bg-panel:      #0A111F;
      --bg-base:       #F1F5F9;
      --bg-surface:    #FFFFFF;
      --bg-field:      #F8FAFC;
      /* ── Borders ── */
      --border:        rgba(255,255,255,.06);
      --border-md:     rgba(0,0,0,.11);
      --border-focus:  rgba(13,148,136,.50);
      /* ── Text ── */
      --text-primary:  #0F172A;
      --text-secondary:#475569;
      --text-muted:    #94A3B8;
      --text-panel:    #CBD5E1;
      --text-panel-dim:#475569;
      /* ── Status ── */
      --red-text:      #B91C1C;
      --red-bg:        #FEF2F2;
      --red-border:    #FECACA;
      --green-text:    #134E4A;
      --green-bg:      #F0FDF4;
      --green-border:  #9FE1CB;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: var(--font); font-size: 14px; line-height: 1.6; }
    body { display: flex; background: var(--bg-base); color: var(--text-primary); }
    a { text-decoration: none; color: inherit; }
    button { font-family: var(--font); cursor: pointer; }

    /* ════════════════════════════════════════
       Shell — brand on right (RTL)
    ════════════════════════════════════════ */
    .auth-shell {
      display: flex; width: 100%; min-height: 100vh;
      flex-direction: row-reverse;
    }

    /* ════════════════════════════════════════
       Brand panel
    ════════════════════════════════════════ */
    .auth-panel {
      width: 400px; flex-shrink: 0;
      background: var(--bg-panel);
      border-left: 0.5px solid rgba(255,255,255,.05);
      display: flex; flex-direction: column;
      justify-content: space-between;
      padding: 52px 44px;
      position: relative; overflow: hidden;
    }
    .auth-panel::before {
      content: '';
      position: absolute; top: -120px; right: -120px;
      width: 380px; height: 380px; border-radius: 50%;
      background: radial-gradient(circle, rgba(13,148,136,.15) 0%, transparent 65%);
      pointer-events: none;
    }
    .auth-panel::after {
      content: '';
      position: absolute; bottom: -80px; left: -80px;
      width: 300px; height: 300px; border-radius: 50%;
      background: radial-gradient(circle, rgba(45,212,191,.05) 0%, transparent 65%);
      pointer-events: none;
    }

    .panel-top { position: relative; z-index: 1; }

    .panel-brand { display: flex; align-items: center; gap: 13px; margin-bottom: 52px; }
    .panel-icon {
      width: 44px; height: 44px; border-radius: 13px; flex-shrink: 0;
      background: linear-gradient(135deg, var(--accent) 0%, #134E4A 100%);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 20px;
      box-shadow: 0 0 22px var(--accent-glow);
    }
    .panel-brand-name { font-size: 20px; font-weight: 800; color: #fff; letter-spacing: -0.3px; }
    .panel-badge {
      display: inline-flex; align-items: center; gap: 5px;
      background: var(--accent-dim); border: 0.5px solid var(--accent-border);
      color: var(--accent-light); font-size: 10px; font-weight: 700;
      padding: 3px 9px; border-radius: 20px; letter-spacing: 0.7px; text-transform: uppercase;
      margin-top: 3px;
    }

    .panel-headline {
      font-size: 25px; font-weight: 800; color: #fff;
      line-height: 1.3; letter-spacing: -0.4px; margin: 20px 0 12px;
    }
    .panel-headline span { color: var(--accent-light); }
    .panel-desc {
      font-size: 13px; color: var(--text-panel);
      line-height: 1.85; margin-bottom: 36px;
      opacity: 0.65;
    }

    .panel-features { display: flex; flex-direction: column; gap: 14px; }
    .feature {
      display: flex; align-items: center; gap: 13px;
      padding: 13px 15px; border-radius: 12px;
      background: rgba(255,255,255,.03);
      border: 0.5px solid rgba(255,255,255,.06);
      transition: background 0.15s, border-color 0.15s;
    }
    .feature:hover {
      background: rgba(13,148,136,.08);
      border-color: var(--accent-border);
    }
    .feature-icon {
      width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
      background: rgba(13,148,136,.12);
      border: 0.5px solid rgba(45,212,191,.20);
      display: flex; align-items: center; justify-content: center;
      font-size: 15px; color: var(--accent-light);
    }
    .feature-title { font-size: 13px; font-weight: 700; color: #E2E8F0; margin-bottom: 1px; }
    .feature-text  { font-size: 11.5px; color: rgba(255,255,255,.40); line-height: 1.5; }

    .panel-divider { height: 0.5px; background: rgba(255,255,255,.07); margin: 32px 0; }

    .security-row {
      display: flex; align-items: center; gap: 8px;
      font-size: 11.5px; color: rgba(255,255,255,.25);
    }
    .security-dot {
      width: 6px; height: 6px; border-radius: 50%;
      background: var(--accent-light);
      box-shadow: 0 0 6px var(--accent-glow);
      flex-shrink: 0; animation: pulse-dot 2.2s ease-in-out infinite;
    }
    @keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:.35} }

    .panel-bottom { position: relative; z-index: 1; }
    .panel-footer { font-size: 11px; color: rgba(255,255,255,.15); margin-top: 24px; }

    /* ════════════════════════════════════════
       Form area
    ════════════════════════════════════════ */
    .auth-form {
      flex: 1; display: flex; align-items: center; justify-content: center;
      padding: 40px 24px; background: var(--bg-surface);
      position: relative;
    }
    .auth-form::before {
      content: '';
      position: absolute; top: 0; right: 0; left: 0;
      height: 3px;
      background: linear-gradient(90deg, transparent, var(--accent) 50%, transparent);
      opacity: 0.30;
    }

    .form-wrap { width: 100%; max-width: 380px; }

    .form-eyebrow {
      display: inline-flex; align-items: center; gap: 6px;
      background: #F0FDFA; color: #134E4A;
      border: 0.5px solid #9FE1CB;
      font-size: 10.5px; font-weight: 700; padding: 4px 11px;
      border-radius: 20px; letter-spacing: 0.5px; text-transform: uppercase;
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
      margin-bottom: 8px;
    }
    .form-label a { font-weight: 600; color: var(--accent); font-size: 11.5px; transition: color 0.12s; }
    .form-label a:hover { color: var(--accent-hover); }

    .input-wrap {
      display: flex; align-items: center;
      background: var(--bg-field);
      border: 1px solid var(--border-md);
      border-radius: 11px; overflow: hidden;
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    .input-wrap:focus-within {
      border-color: var(--border-focus);
      box-shadow: 0 0 0 3px rgba(13,148,136,.09);
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
      display: flex; align-items: center; transition: color 0.12s;
    }
    .pw-toggle:hover { color: var(--text-primary); }
    .field-error {
      display: flex; align-items: center; gap: 5px;
      font-size: 11px; color: var(--red-text); margin-top: 7px;
    }
    .field-error::before { content:''; width:4px; height:4px; border-radius:50%; background:var(--red-text); flex-shrink:0; }

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
    .checkbox-wrap input:checked + .checkbox-custom { background: var(--accent); border-color: var(--accent); }
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
      box-shadow: 0 3px 14px rgba(13,148,136,.28);
      letter-spacing: 0.2px;
    }
    .btn-submit:hover {
      background: var(--accent-hover);
      box-shadow: 0 5px 20px rgba(13,148,136,.38);
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

    .form-footer { text-align: center; margin-top: 22px; font-size: 12px; color: var(--text-muted); }
    .form-footer a { color: var(--accent); font-weight: 600; transition: color 0.12s; }
    .form-footer a:hover { color: var(--accent-hover); }

    @media (max-width: 800px) {
      .auth-panel { display: none; }
      .auth-shell { flex-direction: column; }
    }
  </style>
</head>
<body>

<div class="auth-shell">

  {{-- ══════════════════ Brand Panel ══════════════════ --}}
  <div class="auth-panel">
    <div class="panel-top">

      <div class="panel-brand">
        <div class="panel-icon"><i class="ti ti-tool"></i></div>
        <div>
          <div class="panel-brand-name">Skillify</div>
          <div class="panel-badge"><i class="ti ti-shield-check" style="font-size:9px;"></i> لوحة الإدارة</div>
        </div>
      </div>

      <div class="panel-headline">
        أدر منصتك<br>
        <span>بثقة واحترافية.</span>
      </div>
      <div class="panel-desc">
        لوحة الإدارة تمنحك سيطرة كاملة على المستخدمين،
        الخدمات، التحقق من الحسابات، والمحتوى.
      </div>

      <div class="panel-features">
        <div class="feature">
          <div class="feature-icon"><i class="ti ti-users"></i></div>
          <div>
            <div class="feature-title">إدارة المستخدمين</div>
            <div class="feature-text">متابعة كاملة لحسابات المستخدمين والحرفيين</div>
          </div>
        </div>
        <div class="feature">
          <div class="feature-icon"><i class="ti ti-chart-bar"></i></div>
          <div>
            <div class="feature-title">التقارير والإحصاءات</div>
            <div class="feature-text">بيانات حية عن نشاط المنصة ومعدلات الاستخدام</div>
          </div>
        </div>
        <div class="feature">
          <div class="feature-icon"><i class="ti ti-shield-check"></i></div>
          <div>
            <div class="feature-title">التحقق والمراجعة</div>
            <div class="feature-text">قبول أو رفض طلبات التسجيل والخدمات</div>
          </div>
        </div>
      </div>

      <div class="panel-divider"></div>

      <div class="security-row">
        <div class="security-dot"></div>
        جلسات مشفرة ومحمية — TLS 1.3
      </div>

    </div>

    <div class="panel-bottom">
      <div class="panel-footer">© {{ date('Y') }} Skillify. جميع الحقوق محفوظة.</div>
    </div>
  </div>

  {{-- ══════════════════ Form Area ══════════════════ --}}
  <div class="auth-form">
    <div class="form-wrap">

      <div class="form-eyebrow">
        <i class="ti ti-lock" style="font-size:10px;"></i> لوحة الإدارة
      </div>
      <div class="form-title">مرحباً بعودتك</div>
      <div class="form-sub">سجّل دخول للوصول إلى لوحة إدارة Skillify.</div>

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

      <form method="POST" action="{{ route('admin.login.post') }}" id="loginForm">
        @csrf

        {{-- Email --}}
        <div class="form-group">
          <label class="form-label"><span>البريد الإلكتروني</span></label>
          <div class="input-wrap">
            <i class="ti ti-mail input-icon"></i>
            <input type="email" name="email"
                   placeholder="admin@skillify.com"
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
            <a href="{{ route('admin.forgot-password') }}">نسيت كلمة المرور؟</a>
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
            <i class="ti ti-login" style="font-size:16px;"></i>
            تسجيل الدخول
          </span>
        </button>
      </form>

      <div class="form-footer">
        بوابة المدير العام؟ <a href="{{ route('super_admin.login') }}">الدخول هنا ←</a>
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
