<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>التحقق من الرمز — Skillify</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font:          'Cairo', sans-serif;
      --accent:        #0D9488;
      --accent-hover:  #0F766E;
      --accent-light:  #2DD4BF;
      --accent-dim:    rgba(13,148,136,.12);
      --accent-border: rgba(45,212,191,.25);
      --accent-glow:   rgba(13,148,136,.20);
      --bg-panel:      #0A111F;
      --bg-base:       #F1F5F9;
      --bg-surface:    #FFFFFF;
      --bg-field:      #F8FAFC;
      --border:        rgba(255,255,255,.06);
      --border-md:     rgba(0,0,0,.11);
      --border-focus:  rgba(13,148,136,.50);
      --text-primary:  #0F172A;
      --text-secondary:#475569;
      --text-muted:    #94A3B8;
      --text-panel:    #CBD5E1;
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

    .auth-shell { display: flex; width: 100%; min-height: 100vh; flex-direction: row-reverse; }

    .auth-panel {
      width: 400px; flex-shrink: 0;
      background: var(--bg-panel);
      border-left: 0.5px solid rgba(255,255,255,.05);
      display: flex; flex-direction: column; justify-content: space-between;
      padding: 52px 44px; position: relative; overflow: hidden;
    }
    .auth-panel::before {
      content: ''; position: absolute; top: -120px; right: -120px;
      width: 380px; height: 380px; border-radius: 50%;
      background: radial-gradient(circle, rgba(13,148,136,.15) 0%, transparent 65%);
      pointer-events: none;
    }
    .auth-panel::after {
      content: ''; position: absolute; bottom: -80px; left: -80px;
      width: 300px; height: 300px; border-radius: 50%;
      background: radial-gradient(circle, rgba(45,212,191,.05) 0%, transparent 65%);
      pointer-events: none;
    }

    .panel-top { position: relative; z-index: 1; }
    .panel-brand { display: flex; align-items: center; gap: 13px; margin-bottom: 52px; }
    .panel-badge {
      display: inline-flex; align-items: center; gap: 5px;
      background: var(--accent-dim); border: 0.5px solid var(--accent-border);
      color: var(--accent-light); font-size: 10px; font-weight: 700;
      padding: 3px 9px; border-radius: 20px; letter-spacing: 0.7px; text-transform: uppercase;
    }
    .panel-headline {
      font-size: 25px; font-weight: 800; color: #fff;
      line-height: 1.3; letter-spacing: -0.4px; margin-bottom: 12px;
    }
    .panel-headline span { color: var(--accent-light); }
    .panel-desc { font-size: 13px; color: var(--text-panel); line-height: 1.85; opacity: 0.65; }

    .panel-note {
      margin-top: 36px; padding: 16px; border-radius: 12px;
      background: rgba(255,255,255,.03); border: 0.5px solid rgba(255,255,255,.06);
      font-size: 12px; color: var(--text-panel); line-height: 1.7;
      display: flex; gap: 10px;
    }
    .panel-note i { color: var(--accent-light); font-size: 16px; flex-shrink: 0; margin-top: 1px; }

    .panel-bottom { position: relative; z-index: 1; }
    .panel-footer { font-size: 11px; color: rgba(255,255,255,.15); margin-top: 24px; }

    .auth-form {
      flex: 1; display: flex; align-items: center; justify-content: center;
      padding: 40px 24px; background: var(--bg-surface); position: relative;
    }
    .auth-form::before {
      content: ''; position: absolute; top: 0; right: 0; left: 0; height: 3px;
      background: linear-gradient(90deg, transparent, var(--accent) 50%, transparent);
      opacity: 0.30;
    }
    .form-wrap { width: 100%; max-width: 380px; }

    .form-eyebrow {
      display: inline-flex; align-items: center; gap: 6px;
      background: #F0FDFA; color: #134E4A; border: 0.5px solid #9FE1CB;
      font-size: 10.5px; font-weight: 700; padding: 4px 11px;
      border-radius: 20px; letter-spacing: 0.5px; text-transform: uppercase;
      margin-bottom: 16px;
    }
    .form-title { font-size: 24px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.4px; margin-bottom: 7px; }
    .form-sub { font-size: 13px; color: var(--text-secondary); margin-bottom: 30px; line-height: 1.7; }
    .form-sub b { color: var(--text-primary); font-weight: 700; }

    .alert {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 12px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 22px; line-height: 1.5;
    }
    .alert i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .alert.error   { background: var(--red-bg);   color: var(--red-text);   border: 0.5px solid var(--red-border); }
    .alert.success { background: var(--green-bg); color: var(--green-text); border: 0.5px solid var(--green-border); }

    .otp-row { display: flex; justify-content: center; gap: 10px; margin-bottom: 8px; direction: ltr; }
    .otp-box {
      width: 48px; height: 56px; text-align: center;
      font-size: 20px; font-weight: 700; font-family: var(--font);
      background: var(--bg-field); border: 1px solid var(--border-md);
      border-radius: 11px; color: var(--text-primary);
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    .otp-box:focus { outline: none; border-color: var(--border-focus); box-shadow: 0 0 0 3px rgba(13,148,136,.09); }
    .otp-box.error { border-color: var(--red-text); }

    .field-error {
      display: flex; align-items: center; justify-content: center; gap: 5px;
      font-size: 11px; color: var(--red-text); margin-top: 4px; margin-bottom: 16px;
    }
    .field-error::before { content: ''; width: 4px; height: 4px; border-radius: 50%; background: var(--red-text); flex-shrink: 0; }

    .btn-submit {
      width: 100%; padding: 13px; background: var(--accent); color: #fff;
      border: none; border-radius: 11px; font-size: 14px; font-weight: 700; font-family: var(--font);
      cursor: pointer; transition: background 0.15s, box-shadow 0.15s, transform 0.1s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 3px 14px rgba(13,148,136,.28); letter-spacing: 0.2px; margin-top: 8px;
    }
    .btn-submit:hover { background: var(--accent-hover); box-shadow: 0 5px 20px rgba(13,148,136,.38); transform: translateY(-1px); }
    .btn-submit:active { transform: translateY(0); }
    .btn-submit.loading { pointer-events: none; opacity: 0.72; }
    .btn-submit .spinner {
      display: none; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,.3);
      border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite;
    }
    .btn-submit.loading .spinner { display: block; }
    .btn-submit.loading .btn-text { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .resend-row { text-align: center; margin-top: 22px; font-size: 13px; color: var(--text-secondary); }
    .resend-row button { background: none; border: none; color: var(--accent); font-weight: 600; font-size: 13px; }
    .resend-row button:hover { color: var(--accent-hover); }
    .resend-row button:disabled { color: var(--text-muted); cursor: default; }

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
        <img src="/images/logo.png" alt="Skillify" style="height:40px;width:auto;" />
        <div class="panel-badge"><i class="ti ti-shield-check" style="font-size:9px;"></i> لوحة الإدارة</div>
      </div>

      <div class="panel-headline">تحقق <span>من هويتك</span></div>
      <div class="panel-desc">
        أرسلنا رمز تحقق من 6 أرقام إلى بريدك الإلكتروني للتأكد من هويتك قبل تغيير كلمة المرور.
      </div>

      <div class="panel-note">
        <i class="ti ti-shield-lock"></i>
        <span>لا تشارك هذا الرمز مع أي شخص، فريق Skillify لن يطلبه منك أبداً.</span>
      </div>

    </div>

    <div class="panel-bottom">
      <div class="panel-footer">© {{ date('Y') }} Skillify. جميع الحقوق محفوظة.</div>
    </div>
  </div>

  {{-- ══════════════════ Form Area ══════════════════ --}}
  <div class="auth-form">
    <div class="form-wrap">

      <div class="form-eyebrow"><i class="ti ti-lock" style="font-size:10px;"></i> لوحة الإدارة</div>
      <div class="form-title">أدخل رمز التحقق</div>
      <div class="form-sub">أرسلنا رمزاً من 6 أرقام إلى <b dir="ltr">{{ $email }}</b></div>

      @if($errors->any() && !$errors->has('code'))
        <div class="alert error">
          <i class="ti ti-alert-circle"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.verify-otp.post') }}" id="otpForm">
        @csrf
        <input type="hidden" name="code" id="codeField">

        <div class="otp-row" id="otpRow">
          <input class="otp-box" type="text" inputmode="numeric" maxlength="1" autofocus>
          <input class="otp-box" type="text" inputmode="numeric" maxlength="1">
          <input class="otp-box" type="text" inputmode="numeric" maxlength="1">
          <input class="otp-box" type="text" inputmode="numeric" maxlength="1">
          <input class="otp-box" type="text" inputmode="numeric" maxlength="1">
          <input class="otp-box" type="text" inputmode="numeric" maxlength="1">
        </div>
        @error('code')
          <div class="field-error">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn-submit" id="submitBtn">
          <div class="spinner"></div>
          <span class="btn-text"><i class="ti ti-shield-check" style="font-size:15px;"></i> تحقق من الرمز</span>
        </button>
      </form>

      <div class="resend-row">
        <span id="countdownWrap">يمكنك طلب رمز جديد بعد <b id="countdown">60</b> ثانية</span>
        <form method="POST" action="{{ route('admin.forgot-password.send') }}" id="resendForm" style="display:none;">
          @csrf
          <input type="hidden" name="email" value="{{ $email }}">
          <button type="submit" id="resendBtn">إعادة إرسال الرمز</button>
        </form>
      </div>

      <div class="form-footer">
        <a href="{{ route('admin.login') }}">← العودة لتسجيل الدخول</a>
      </div>

    </div>
  </div>

</div>

<script>
  const boxes = Array.from(document.querySelectorAll('.otp-box'));
  const codeField = document.getElementById('codeField');
  const form = document.getElementById('otpForm');
  const submitBtn = document.getElementById('submitBtn');

  function currentCode() { return boxes.map(b => b.value).join(''); }

  function trySubmit() {
    const code = currentCode();
    if (code.length === 6) {
      codeField.value = code;
      submitBtn.classList.add('loading');
      form.submit();
    }
  }

  boxes.forEach((box, i) => {
    box.addEventListener('input', () => {
      box.value = box.value.replace(/\D/g, '').slice(-1);
      if (box.value && i < boxes.length - 1) boxes[i + 1].focus();
      trySubmit();
    });
    box.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !box.value && i > 0) boxes[i - 1].focus();
    });
    box.addEventListener('paste', (e) => {
      e.preventDefault();
      const pasted = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6);
      if (!pasted) return;
      pasted.split('').forEach((d, idx) => { if (boxes[idx]) boxes[idx].value = d; });
      boxes[Math.min(pasted.length, boxes.length) - 1]?.focus();
      trySubmit();
    });
  });

  @if($errors->has('code'))
    boxes.forEach(b => b.classList.add('error'));
  @endif

  let seconds = 60;
  const countdownEl = document.getElementById('countdown');
  const countdownWrap = document.getElementById('countdownWrap');
  const resendForm = document.getElementById('resendForm');

  const timer = setInterval(() => {
    seconds--;
    countdownEl.textContent = seconds;
    if (seconds <= 0) {
      clearInterval(timer);
      countdownWrap.style.display = 'none';
      resendForm.style.display = 'block';
    }
  }, 1000);
</script>

</body>
</html>
