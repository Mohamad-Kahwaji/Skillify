<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password — Hirfa Super Admin</title>
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
    .panel-tips { display: flex; flex-direction: column; gap: 10px; margin-top: 32px; }
    .tip { display: flex; align-items: center; gap: 10px; font-size: 12px; color: var(--text-secondary); }
    .tip i { font-size: 15px; color: var(--accent); }
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
    .form-sub   { font-size: 13px; color: var(--text-secondary); }

    .alert {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 12px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 20px;
    }
    .alert i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .alert.error { background: rgba(226,75,74,.12); color: #f09595; border: 0.5px solid rgba(226,75,74,.25); }

    .form-group  { margin-bottom: 18px; }
    .form-label  { display: block; font-size: 12px; font-weight: 600; color: var(--text-secondary); margin-bottom: 7px; }
    .input-wrap {
      display: flex; align-items: center;
      background: var(--bg-sunken);
      border: 1px solid var(--border-md);
      border-radius: 10px; overflow: hidden;
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    .input-wrap:focus-within {
      border-color: var(--border-focus);
      box-shadow: 0 0 0 3px rgba(29,158,117,.08);
    }
    .input-wrap .input-icon { padding: 0 12px; font-size: 16px; color: var(--text-muted); flex-shrink: 0; }
    .input-wrap input {
      flex: 1; border: none; outline: none;
      background: transparent; padding: 11px 12px 11px 0;
      font-size: 13px; color: var(--text-primary); font-family: var(--font);
    }
    .input-wrap input::placeholder { color: var(--text-muted); }
    .pw-toggle {
      padding: 0 12px; background: none; border: none;
      color: var(--text-muted); font-size: 16px; flex-shrink: 0;
      display: flex; align-items: center; transition: color 0.12s;
    }
    .pw-toggle:hover { color: var(--text-primary); }
    .field-error { display: flex; align-items: center; gap: 5px; font-size: 11px; color: #f09595; margin-top: 6px; }
    .field-error::before { content: ''; width: 4px; height: 4px; border-radius: 50%; background: #f09595; flex-shrink: 0; }

    .strength-bar { margin-top: 8px; display: flex; gap: 4px; }
    .strength-seg { height: 3px; flex: 1; border-radius: 4px; background: var(--border-md); transition: background 0.2s; }
    .strength-label { font-size: 11px; color: var(--text-muted); margin-top: 4px; }
    .strength-1 .strength-seg:nth-child(1)     { background: var(--red-400); }
    .strength-2 .strength-seg:nth-child(-n+2)  { background: #F59E0B; }
    .strength-3 .strength-seg:nth-child(-n+3)  { background: var(--accent); }
    .strength-4 .strength-seg                  { background: var(--accent); }

    .btn-submit {
      width: 100%; padding: 12px;
      background: var(--accent); color: #fff;
      border: none; border-radius: 10px;
      font-size: 14px; font-weight: 600; font-family: var(--font);
      cursor: pointer; transition: background 0.15s, box-shadow 0.15s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 2px 12px rgba(29,158,117,.20);
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
      <div class="panel-headline">Set a new<br>password</div>
      <div class="panel-desc">Choose a strong password to protect your super admin account.</div>
      <div class="panel-tips">
        <div class="tip"><i class="ti ti-check"></i> At least 8 characters</div>
        <div class="tip"><i class="ti ti-check"></i> Mix uppercase & lowercase</div>
        <div class="tip"><i class="ti ti-check"></i> Include numbers or symbols</div>
        <div class="tip"><i class="ti ti-check"></i> Never reuse old passwords</div>
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
        <div class="form-title">Create new password</div>
        <div class="form-sub">Your identity has been verified. Your new password must be at least 8 characters.</div>
      </div>

      @if($errors->any())
        <div class="alert error">
          <i class="ti ti-alert-circle"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('super_admin.reset-password.update') }}" id="resetForm">
        @csrf

        <div class="form-group">
          <label class="form-label">New Password</label>
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
            <div class="strength-seg"></div>
            <div class="strength-seg"></div>
            <div class="strength-seg"></div>
            <div class="strength-seg"></div>
          </div>
          <div class="strength-label" id="strengthLabel">Enter a password</div>
          @error('password')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Confirm New Password</label>
          <div class="input-wrap">
            <i class="ti ti-lock-check input-icon"></i>
            <input type="password" name="password_confirmation"
                   placeholder="••••••••" required autocomplete="new-password">
          </div>
        </div>

        <button type="submit" class="btn-submit" id="submitBtn">
          <div class="spinner"></div>
          <span class="btn-text"><i class="ti ti-check" style="font-size:16px;"></i> Reset Password</span>
        </button>
      </form>

      <div class="back-link">
        <a href="{{ route('super_admin.login') }}">← Back to Sign In</a>
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
    if (val.length >= 8)           score++;
    if (/[A-Z]/.test(val))         score++;
    if (/[0-9]/.test(val))         score++;
    if (/[^A-Za-z0-9]/.test(val))  score++;
    const labels = ['Enter a password', 'Too weak', 'Could be stronger', 'Getting better', 'Strong password'];
    bar.className = 'strength-bar strength-' + score;
    label.textContent = labels[score];
  }

  document.getElementById('resetForm').addEventListener('submit', function () {
    document.getElementById('submitBtn').classList.add('loading');
  });
</script>

</body>
</html>
