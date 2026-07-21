<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>كلمة مرور جديدة — Skillify</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font:           'Cairo', sans-serif;
      --accent:         #7C3AED;
      --accent-hover:   #6D28D9;
      --accent-light:   #A78BFA;
      --accent-dim:     rgba(124,58,237,.14);
      --accent-border:  rgba(167,139,250,.28);
      --accent-glow:    rgba(124,58,237,.22);
      --bg-deep:        #070614;
      --bg-panel:       #0C0A1F;
      --bg-surface:     #100E26;
      --bg-field:       #0A0818;
      --border:         rgba(255,255,255,.055);
      --border-md:      rgba(255,255,255,.10);
      --border-focus:   rgba(124,58,237,.55);
      --text-primary:   #EDE9FE;
      --text-secondary: #8B80C0;
      --text-muted:     #3D3568;
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

    .auth-shell { display: flex; width: 100%; min-height: 100vh; flex-direction: row-reverse; }

    .auth-panel {
      width: 420px; flex-shrink: 0;
      background: var(--bg-panel);
      border-left: 0.5px solid var(--border);
      display: flex; flex-direction: column; justify-content: space-between;
      padding: 52px 44px; position: relative; overflow: hidden;
    }
    .auth-panel::before {
      content: ''; position: absolute; top: -140px; right: -120px;
      width: 420px; height: 420px; border-radius: 50%;
      background: radial-gradient(circle, rgba(124,58,237,.18) 0%, transparent 68%);
      pointer-events: none;
    }
    .auth-panel::after {
      content: ''; position: absolute; bottom: -100px; left: -100px;
      width: 340px; height: 340px; border-radius: 50%;
      background: radial-gradient(circle, rgba(167,139,250,.07) 0%, transparent 65%);
      pointer-events: none;
    }

    .panel-top { position: relative; z-index: 1; }
    .panel-brand { display: flex; align-items: center; gap: 13px; margin-bottom: 56px; }
    .panel-badge {
      display: inline-flex; align-items: center; gap: 5px;
      background: var(--accent-dim); border: 0.5px solid var(--accent-border);
      color: var(--accent-light); font-size: 10px; font-weight: 700;
      padding: 3px 9px; border-radius: 20px; letter-spacing: 0.7px; text-transform: uppercase;
    }
    .panel-headline {
      font-size: 26px; font-weight: 800; color: #fff;
      line-height: 1.3; letter-spacing: -0.5px; margin-bottom: 12px;
    }
    .panel-headline span { color: var(--accent-light); }
    .panel-desc { font-size: 13px; color: var(--text-secondary); line-height: 1.85; }

    .panel-tips { display: flex; flex-direction: column; gap: 12px; margin-top: 32px; }
    .tip { display: flex; align-items: center; gap: 10px; font-size: 12.5px; color: var(--text-secondary); }
    .tip i { font-size: 15px; color: var(--accent-light); flex-shrink: 0; }

    .panel-bottom { position: relative; z-index: 1; }
    .panel-footer { font-size: 11px; color: rgba(255,255,255,.14); margin-top: 24px; }

    .auth-form {
      flex: 1; display: flex; align-items: center; justify-content: center;
      padding: 40px 24px; background: var(--bg-surface); position: relative;
    }
    .auth-form::before {
      content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 2px;
      background: linear-gradient(90deg, transparent, var(--accent) 50%, transparent);
      opacity: 0.35;
    }
    .form-wrap { width: 100%; max-width: 380px; }

    .form-eyebrow {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--accent-dim); color: var(--accent-light); border: 0.5px solid var(--accent-border);
      font-size: 10.5px; font-weight: 700; padding: 4px 11px;
      border-radius: 20px; letter-spacing: 0.6px; text-transform: uppercase;
      margin-bottom: 16px;
    }
    .form-title { font-size: 24px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.4px; margin-bottom: 7px; }
    .form-sub { font-size: 13px; color: var(--text-secondary); margin-bottom: 30px; line-height: 1.7; }

    .alert {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 12px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 22px; line-height: 1.5;
    }
    .alert i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .alert.error { background: var(--red-bg); color: var(--red-text); border: 0.5px solid var(--red-border); }

    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); margin-bottom: 8px; }
    .input-wrap {
      display: flex; align-items: center; background: var(--bg-field);
      border: 1px solid var(--border-md); border-radius: 11px; overflow: hidden;
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    .input-wrap:focus-within { border-color: var(--border-focus); box-shadow: 0 0 0 3px rgba(124,58,237,.10); }
    .input-icon { padding: 0 13px; font-size: 16px; color: var(--text-muted); flex-shrink: 0; }
    .input-wrap input {
      flex: 1; border: none; outline: none; background: transparent;
      padding: 12px 12px 12px 0; font-size: 13.5px; color: var(--text-primary); font-family: var(--font);
    }
    .input-wrap input::placeholder { color: var(--text-muted); }
    .pw-toggle {
      padding: 0 13px; background: none; border: none; color: var(--text-muted); font-size: 16px;
      flex-shrink: 0; display: flex; align-items: center; transition: color 0.12s;
    }
    .pw-toggle:hover { color: var(--text-primary); }
    .field-error { display: flex; align-items: center; gap: 5px; font-size: 11px; color: var(--red-text); margin-top: 7px; }
    .field-error::before { content:''; width:4px; height:4px; border-radius:50%; background:var(--red-text); flex-shrink:0; }

    .strength-bar { margin-top: 8px; display: flex; gap: 4px; }
    .strength-seg { height: 3px; flex: 1; border-radius: 4px; background: var(--border-md); transition: background 0.2s; }
    .strength-label { font-size: 11px; color: var(--text-muted); margin-top: 5px; }
    .strength-0 .strength-seg                 { background: var(--border-md); }
    .strength-1 .strength-seg:nth-child(1)     { background: var(--red-text); }
    .strength-2 .strength-seg:nth-child(-n+2)  { background: #F59E0B; }
    .strength-3 .strength-seg:nth-child(-n+3)  { background: var(--accent); }
    .strength-4 .strength-seg                  { background: var(--accent); }

    .btn-submit {
      width: 100%; padding: 13px; background: var(--accent); color: #fff;
      border: none; border-radius: 11px; font-size: 14px; font-weight: 700; font-family: var(--font);
      cursor: pointer; transition: background 0.15s, box-shadow 0.15s, transform 0.1s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 3px 16px rgba(124,58,237,.30); letter-spacing: 0.2px;
    }
    .btn-submit:hover { background: var(--accent-hover); box-shadow: 0 6px 22px rgba(124,58,237,.40); transform: translateY(-1px); }
    .btn-submit:active { transform: translateY(0); }
    .btn-submit.loading { pointer-events: none; opacity: 0.72; }
    .btn-submit .spinner {
      display: none; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,.3);
      border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite;
    }
    .btn-submit.loading .spinner { display: block; }
    .btn-submit.loading .btn-text { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .form-footer { text-align: center; margin-top: 22px; font-size: 12px; color: var(--text-muted); }
    .form-footer a { color: var(--accent-light); font-weight: 600; transition: color 0.12s; }
    .form-footer a:hover { color: #fff; }

    @media (max-width: 820px) {
      .auth-panel { display: none; }
      .auth-shell { flex-direction: column; }
    }
  </style>
</head>
<body>

<div class="auth-shell">

  {{-- ══════════════════ Brand Panel (right in RTL) ══════════════════ --}}
  <div class="auth-panel">
    <div class="panel-top">

      <div class="panel-brand">
        <img src="/images/logo.png" alt="Skillify" style="height:40px;width:auto;" />
        <div class="panel-badge"><i class="ti ti-crown" style="font-size:9px;"></i> المدير العام</div>
      </div>

      <div class="panel-headline">كلمة مرور <span>جديدة وآمنة</span></div>
      <div class="panel-desc">تم التحقق من هويتك بنجاح، اختر كلمة مرور قوية لحماية حساب المدير العام.</div>

      <div class="panel-tips">
        <div class="tip"><i class="ti ti-circle-check"></i> 8 أحرف على الأقل</div>
        <div class="tip"><i class="ti ti-circle-check"></i> مزيج من الأحرف الكبيرة والصغيرة</div>
        <div class="tip"><i class="ti ti-circle-check"></i> أرقام أو رموز خاصة</div>
      </div>

    </div>

    <div class="panel-bottom">
      <div class="panel-footer">© {{ date('Y') }} Skillify. جميع الحقوق محفوظة.</div>
    </div>
  </div>

  {{-- ══════════════════ Form Area (left in RTL) ══════════════════ --}}
  <div class="auth-form">
    <div class="form-wrap">

      <div class="form-eyebrow"><i class="ti ti-crown" style="font-size:9px;"></i> بوابة المدير العام</div>
      <div class="form-title">كلمة المرور الجديدة</div>
      <div class="form-sub">يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.</div>

      @if($errors->any())
        <div class="alert error">
          <i class="ti ti-alert-circle"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('super_admin.reset-password.update') }}" id="resetForm">
        @csrf

        <div class="form-group">
          <label class="form-label">كلمة المرور الجديدة</label>
          <div class="input-wrap">
            <i class="ti ti-lock input-icon"></i>
            <input type="password" id="password" name="password"
                   placeholder="••••••••" required minlength="8"
                   oninput="checkStrength(this.value)" autocomplete="new-password" autofocus>
            <button type="button" class="pw-toggle" onclick="togglePassword('password', this)">
              <i class="ti ti-eye"></i>
            </button>
          </div>
          <div class="strength-bar strength-0" id="strengthBar">
            <div class="strength-seg"></div><div class="strength-seg"></div>
            <div class="strength-seg"></div><div class="strength-seg"></div>
          </div>
          <div class="strength-label" id="strengthLabel">أدخل كلمة مرور</div>
          @error('password')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label class="form-label">تأكيد كلمة المرور</label>
          <div class="input-wrap">
            <i class="ti ti-lock-check input-icon"></i>
            <input type="password" name="password_confirmation"
                   placeholder="أعد كتابة كلمة المرور" required autocomplete="new-password">
          </div>
        </div>

        <button type="submit" class="btn-submit" id="submitBtn">
          <div class="spinner"></div>
          <span class="btn-text"><i class="ti ti-check" style="font-size:16px;"></i> حفظ كلمة المرور</span>
        </button>
      </form>

      <div class="form-footer">
        <a href="{{ route('super_admin.login') }}">← العودة لتسجيل الدخول</a>
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

  function checkStrength(val) {
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const labels = ['أدخل كلمة مرور', 'ضعيفة جداً', 'يمكن أن تكون أقوى', 'جيدة', 'قوية جداً'];
    bar.className = 'strength-bar strength-' + score;
    label.textContent = labels[score];
  }

  document.getElementById('resetForm').addEventListener('submit', function () {
    document.getElementById('submitBtn').classList.add('loading');
  });
</script>

</body>
</html>
