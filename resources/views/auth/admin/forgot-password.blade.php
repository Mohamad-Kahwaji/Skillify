<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password — Hirfa Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font:         'Inter', sans-serif;
      --accent:       #1D9E75;
      --accent-hover: #0F6E56;
      --accent-light: #E1F5EE;
      --panel-bg:     #04342C;
      --red-400:      #E24B4A;
      --red-50:       #FCEBEB;
      --red-800:      #791F1F;
      --green-50:     #E1F5EE;
      --green-800:    #085041;
      --bg-base:      #F4F2EC;
      --bg-surface:   #ffffff;
      --bg-sunken:    #F8F7F3;
      --border:       rgba(0,0,0,0.08);
      --border-md:    rgba(0,0,0,0.12);
      --text-primary: #111827;
      --text-secondary:#6B7280;
      --text-muted:   #9CA3AF;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: var(--font); font-size: 14px; line-height: 1.6; }
    body { display: flex; background: var(--bg-base); color: var(--text-primary); }
    a { text-decoration: none; color: inherit; }
    button { font-family: var(--font); cursor: pointer; }

    .auth-shell { display: flex; width: 100%; min-height: 100vh; }

    .auth-panel {
      width: 420px; flex-shrink: 0;
      background: var(--panel-bg);
      display: flex; flex-direction: column;
      justify-content: space-between;
      padding: 48px 40px;
      position: relative; overflow: hidden;
    }
    .auth-panel::before {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(ellipse at 0% 0%, rgba(29,158,117,.18) 0%, transparent 60%);
      pointer-events: none;
    }
    .auth-panel::after {
      content: '';
      position: absolute; bottom: -80px; right: -80px;
      width: 280px; height: 280px; border-radius: 50%;
      background: rgba(29,158,117,.08);
      pointer-events: none;
    }
    .panel-top { position: relative; z-index: 1; }
    .panel-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 56px; }
    .panel-icon {
      width: 40px; height: 40px; border-radius: 12px;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 20px;
    }
    .panel-brand-name { font-size: 20px; font-weight: 700; color: #fff; letter-spacing: -0.3px; }
    .panel-headline { font-size: 26px; font-weight: 700; color: #fff; line-height: 1.3; letter-spacing: -0.5px; margin-bottom: 12px; }
    .panel-desc { font-size: 13px; color: rgba(255,255,255,0.5); line-height: 1.7; }
    .panel-bottom { position: relative; z-index: 1; }
    .panel-footer-text { font-size: 11px; color: rgba(255,255,255,0.25); }

    .auth-form {
      flex: 1; display: flex; align-items: center; justify-content: center;
      padding: 40px 24px; background: var(--bg-surface);
    }
    .form-wrap { width: 100%; max-width: 380px; }

    .form-header { margin-bottom: 32px; }
    .form-eyebrow {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--accent-light); color: var(--green-800);
      font-size: 11px; font-weight: 600; padding: 4px 10px;
      border-radius: 20px; letter-spacing: 0.5px; text-transform: uppercase;
      margin-bottom: 14px;
    }
    .form-title { font-size: 22px; font-weight: 700; letter-spacing: -0.4px; color: var(--text-primary); margin-bottom: 6px; }
    .form-sub   { font-size: 13px; color: var(--text-secondary); line-height: 1.6; }

    .alert {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 12px 14px; border-radius: 10px;
      font-size: 13px; margin-bottom: 20px;
    }
    .alert i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .alert.error   { background: var(--red-50);   color: var(--red-800);   border: 0.5px solid #fbb; }
    .alert.success { background: var(--green-50); color: var(--green-800); border: 0.5px solid #9FE1CB; }

    /* Success state */
    .success-state {
      text-align: center; padding: 24px 0;
    }
    .success-icon {
      width: 64px; height: 64px; border-radius: 50%;
      background: var(--accent-light);
      display: inline-flex; align-items: center; justify-content: center;
      font-size: 30px; color: var(--accent);
      margin-bottom: 20px;
    }
    .success-title { font-size: 18px; font-weight: 700; margin-bottom: 8px; color: var(--text-primary); }
    .success-desc  { font-size: 13px; color: var(--text-secondary); line-height: 1.7; }

    .form-group  { margin-bottom: 18px; }
    .form-label  { display: block; font-size: 12px; font-weight: 600; color: var(--text-secondary); margin-bottom: 7px; letter-spacing: 0.2px; }
    .input-wrap {
      display: flex; align-items: center;
      background: var(--bg-sunken);
      border: 1px solid var(--border-md);
      border-radius: 10px; overflow: hidden;
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    .input-wrap:focus-within {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(29,158,117,.10);
    }
    .input-wrap .input-icon { padding: 0 12px; font-size: 16px; color: var(--text-muted); flex-shrink: 0; }
    .input-wrap input {
      flex: 1; border: none; outline: none;
      background: transparent; padding: 11px 12px 11px 0;
      font-size: 13px; color: var(--text-primary); font-family: var(--font);
    }
    .input-wrap input::placeholder { color: var(--text-muted); }
    .field-error {
      display: flex; align-items: center; gap: 5px;
      font-size: 11px; color: var(--red-400); margin-top: 6px;
    }
    .field-error::before { content: ''; width: 4px; height: 4px; border-radius: 50%; background: var(--red-400); flex-shrink: 0; }

    .btn-submit {
      width: 100%; padding: 12px;
      background: var(--accent); color: #fff;
      border: none; border-radius: 10px;
      font-size: 14px; font-weight: 600; font-family: var(--font);
      cursor: pointer; transition: background 0.15s, box-shadow 0.15s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 2px 8px rgba(29,158,117,.3);
    }
    .btn-submit:hover { background: var(--accent-hover); box-shadow: 0 4px 14px rgba(29,158,117,.35); }
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

    .back-link {
      text-align: center; margin-top: 20px;
      font-size: 13px; color: var(--text-secondary);
    }
    .back-link a { color: var(--accent); font-weight: 500; }
    .back-link a:hover { color: var(--accent-hover); }

    @media (max-width: 800px) {
      .auth-panel { display: none; }
      .auth-form  { background: var(--bg-base); }
    }
  </style>
</head>
<body>

<div class="auth-shell">

  <div class="auth-panel">
    <div class="panel-top">
      <div class="panel-brand">
        <div class="panel-icon"><i class="ti ti-tool"></i></div>
        <div class="panel-brand-name">Hirfa</div>
      </div>
      <div class="panel-headline">Account<br>recovery</div>
      <div class="panel-desc">Enter your registered email address and we'll send you a link to reset your admin password securely.</div>
    </div>
    <div class="panel-bottom">
      <div class="panel-footer-text">© {{ date('Y') }} Hirfa Platform. All rights reserved.</div>
    </div>
  </div>

  <div class="auth-form">
    <div class="form-wrap">

      <div class="form-header">
        <div class="form-eyebrow"><i class="ti ti-lock" style="font-size:10px;"></i> Admin Access</div>
        <div class="form-title">Forgot your password?</div>
        <div class="form-sub">No worries. Enter your email and we'll send you a reset link.</div>
      </div>

      @if(session('status'))
        <div class="success-state">
          <div class="success-icon"><i class="ti ti-mail-check"></i></div>
          <div class="success-title">Check your inbox</div>
          <div class="success-desc">We've sent a password reset link to your email address. The link will expire in 60 minutes.</div>
        </div>
      @else

        @if($errors->any())
          <div class="alert error">
            <i class="ti ti-alert-circle"></i>
            <span>{{ $errors->first() }}</span>
          </div>
        @endif

        <form method="POST" action="{{ route('admin.forgot-password.send') }}" id="forgotForm">
          @csrf
          <div class="form-group">
            <label class="form-label">Email Address</label>
            <div class="input-wrap">
              <i class="ti ti-mail input-icon"></i>
              <input type="email" name="email" value="{{ old('email') }}"
                     placeholder="admin@hirfa.com" autofocus required>
            </div>
            @error('email')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn-submit" id="submitBtn">
            <div class="spinner"></div>
            <span class="btn-text"><i class="ti ti-send" style="font-size:15px;"></i> Send Reset Link</span>
          </button>
        </form>

      @endif

      <div class="back-link">
        <a href="{{ route('admin.login') }}">← Back to Sign In</a>
      </div>

    </div>
  </div>

</div>

<script>
  const form = document.getElementById('forgotForm');
  if (form) {
    form.addEventListener('submit', function () {
      document.getElementById('submitBtn').classList.add('loading');
    });
  }
</script>

</body>
</html>
