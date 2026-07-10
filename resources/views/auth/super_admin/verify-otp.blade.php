<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verify Code — Hirfa Super Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font:          'Inter', sans-serif;
      --accent:        #1D9E75;
      --accent-hover:  #5DCAA5;
      --accent-dim:    rgba(29,158,117,.15);
      --accent-border: rgba(29,158,117,.30);
      --red-400:       #E24B4A;
      --panel-bg:      #060f0c;
      --bg-base:       #0b1812;
      --bg-surface:    #101f18;
      --bg-sunken:     #0a1610;
      --border:        rgba(255,255,255,0.06);
      --border-md:     rgba(255,255,255,0.10);
      --border-focus:  rgba(29,158,117,.50);
      --text-primary:  #e8f5ef;
      --text-secondary:#7db89a;
      --text-muted:    #3d6b56;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: var(--font); font-size: 14px; line-height: 1.6; }
    body { display: flex; background: var(--bg-base); color: var(--text-primary); }
    a { text-decoration: none; color: inherit; }
    button { font-family: var(--font); cursor: pointer; }

    .auth-shell { display: flex; width: 100%; min-height: 100vh; }

    .auth-panel {
      width: 440px; flex-shrink: 0;
      background: var(--panel-bg);
      display: flex; flex-direction: column; justify-content: space-between;
      padding: 48px 44px;
      position: relative; overflow: hidden;
      border-right: 0.5px solid var(--border);
    }
    .auth-panel::before {
      content: ''; position: absolute; top: -120px; left: -120px;
      width: 400px; height: 400px; border-radius: 50%;
      background: radial-gradient(circle, rgba(29,158,117,.10) 0%, transparent 70%);
      pointer-events: none;
    }
    .panel-top { position: relative; z-index: 1; }
    .panel-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 52px; }
    .panel-icon {
      width: 42px; height: 42px; border-radius: 12px;
      background: linear-gradient(135deg, var(--accent) 0%, #085041 100%);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 20px;
      box-shadow: 0 0 20px rgba(29,158,117,.25);
    }
    .panel-brand-name { font-size: 20px; font-weight: 700; color: #fff; }
    .panel-crown {
      display: inline-flex; align-items: center; gap: 5px;
      background: var(--accent-dim); border: 0.5px solid var(--accent-border);
      color: var(--accent); font-size: 10px; font-weight: 600;
      padding: 3px 9px; border-radius: 20px; letter-spacing: 0.8px; text-transform: uppercase;
    }
    .panel-headline { font-size: 28px; font-weight: 700; color: #fff; line-height: 1.25; letter-spacing: -0.6px; margin: 20px 0 12px; }
    .panel-desc { font-size: 13px; color: var(--text-secondary); line-height: 1.8; }
    .panel-note {
      margin-top: 32px; padding: 16px;
      background: rgba(255,255,255,.03); border: 0.5px solid var(--border);
      border-radius: 10px; font-size: 12px; color: var(--text-secondary);
      display: flex; gap: 10px;
    }
    .panel-note i { color: var(--accent); font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .panel-bottom { position: relative; z-index: 1; }
    .panel-footer-text { font-size: 11px; color: rgba(255,255,255,0.18); }

    .auth-form {
      flex: 1; display: flex; align-items: center; justify-content: center;
      padding: 40px 24px; background: var(--bg-surface);
    }
    .form-wrap { width: 100%; max-width: 380px; }

    .form-header { margin-bottom: 32px; }
    .form-eyebrow {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--accent-dim); color: var(--accent);
      border: 0.5px solid var(--accent-border);
      font-size: 11px; font-weight: 600; padding: 4px 10px;
      border-radius: 20px; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 14px;
    }
    .form-title { font-size: 22px; font-weight: 700; letter-spacing: -0.4px; color: var(--text-primary); margin-bottom: 6px; }
    .form-sub   { font-size: 13px; color: var(--text-secondary); line-height: 1.6; }
    .form-sub b { color: var(--text-primary); font-weight: 600; }

    .alert {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 12px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 20px;
    }
    .alert i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .alert.error { background: rgba(226,75,74,.12); color: #f09595; border: 0.5px solid rgba(226,75,74,.25); }

    .otp-row { display: flex; justify-content: center; gap: 10px; margin-bottom: 8px; direction: ltr; }
    .otp-box {
      width: 48px; height: 56px; text-align: center;
      font-size: 20px; font-weight: 700; font-family: var(--font);
      background: var(--bg-sunken); border: 1px solid var(--border-md);
      border-radius: 10px; color: var(--text-primary);
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    .otp-box:focus {
      outline: none; border-color: var(--border-focus);
      box-shadow: 0 0 0 3px rgba(29,158,117,.08);
    }
    .otp-box.error { border-color: var(--red-400); }

    .field-error {
      display: flex; align-items: center; justify-content: center; gap: 5px;
      font-size: 11px; color: #f09595; margin-top: 4px; margin-bottom: 16px;
    }
    .field-error::before { content: ''; width: 4px; height: 4px; border-radius: 50%; background: #f09595; flex-shrink: 0; }

    .btn-submit {
      width: 100%; padding: 12px;
      background: var(--accent); color: #fff;
      border: none; border-radius: 10px;
      font-size: 14px; font-weight: 600; font-family: var(--font);
      cursor: pointer; transition: background 0.15s, box-shadow 0.15s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 2px 12px rgba(29,158,117,.20);
      margin-top: 8px;
    }
    .btn-submit:hover { background: #18875f; }
    .btn-submit.loading { pointer-events: none; opacity: 0.75; }
    .btn-submit .spinner {
      display: none; width: 16px; height: 16px;
      border: 2px solid rgba(255,255,255,.3);
      border-top-color: #fff; border-radius: 50%;
      animation: spin 0.7s linear infinite;
    }
    .btn-submit.loading .spinner { display: block; }
    .btn-submit.loading .btn-text { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .resend-row { text-align: center; margin-top: 20px; font-size: 13px; color: var(--text-secondary); }
    .resend-row button { background: none; border: none; color: var(--accent); font-weight: 500; font-size: 13px; }
    .resend-row button:hover { color: var(--accent-hover); }
    .resend-row button:disabled { color: var(--text-muted); cursor: default; }

    .back-link { text-align: center; margin-top: 20px; font-size: 13px; color: var(--text-muted); }
    .back-link a { color: var(--accent); font-weight: 500; }
    .back-link a:hover { color: var(--accent-hover); }

    @media (max-width: 820px) { .auth-panel { display: none; } }
  </style>
</head>
<body>

<div class="auth-shell">

  <div class="auth-panel">
    <div class="panel-top">
      <div class="panel-brand">
        <div class="panel-icon"><i class="ti ti-shield-check"></i></div>
        <div>
          <div class="panel-brand-name">Hirfa</div>
          <div class="panel-crown"><i class="ti ti-crown" style="font-size:9px;"></i> Super Admin</div>
        </div>
      </div>
      <div class="panel-headline">Verify your<br>identity</div>
      <div class="panel-desc">We sent a 6-digit verification code to your email to confirm it's really you before resetting your password.</div>
      <div class="panel-note">
        <i class="ti ti-shield-lock"></i>
        <span>Codes expire shortly and can only be used once. Never share this code with anyone.</span>
      </div>
    </div>
    <div class="panel-bottom">
      <div class="panel-footer-text">© {{ date('Y') }} Hirfa Platform. Restricted access.</div>
    </div>
  </div>

  <div class="auth-form">
    <div class="form-wrap">

      <div class="form-header">
        <div class="form-eyebrow"><i class="ti ti-crown" style="font-size:9px;"></i> Super Admin Portal</div>
        <div class="form-title">Enter verification code</div>
        <div class="form-sub">We sent a 6-digit code to <b dir="ltr">{{ $email }}</b></div>
      </div>

      @if($errors->any())
        <div class="alert error">
          <i class="ti ti-alert-circle"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('super_admin.verify-otp.post') }}" id="otpForm">
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

        <button type="submit" class="btn-submit" id="submitBtn">
          <div class="spinner"></div>
          <span class="btn-text"><i class="ti ti-shield-check" style="font-size:15px;"></i> Verify Code</span>
        </button>
      </form>

      <div class="resend-row">
        <span id="countdownWrap">Request a new code in <b id="countdown">60</b>s</span>
        <form method="POST" action="{{ route('super_admin.forgot-password.send') }}" id="resendForm" style="display:none;">
          @csrf
          <input type="hidden" name="email" value="{{ $email }}">
          <button type="submit" id="resendBtn">Resend code</button>
        </form>
      </div>

      <div class="back-link">
        <a href="{{ route('super_admin.login') }}">← Back to Sign In</a>
      </div>

    </div>
  </div>

</div>

<script>
  const boxes = Array.from(document.querySelectorAll('.otp-box'));
  const codeField = document.getElementById('codeField');
  const form = document.getElementById('otpForm');
  const submitBtn = document.getElementById('submitBtn');

  function currentCode() {
    return boxes.map(b => b.value).join('');
  }

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
      if (e.key === 'Backspace' && !box.value && i > 0) {
        boxes[i - 1].focus();
      }
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

  // Resend countdown
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
