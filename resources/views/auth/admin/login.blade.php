<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Sign In — Hirfa</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font:          'Inter', sans-serif;
      --accent:        #1D9E75;
      --accent-hover:  #0F6E56;
      --accent-light:  #E1F5EE;
      --panel-bg:      #04342C;
      --panel-bg2:     #062e26;
      --red-400:       #E24B4A;
      --red-50:        #FCEBEB;
      --red-800:       #791F1F;
      --green-50:      #E1F5EE;
      --green-800:     #085041;
      --bg-base:       #F4F2EC;
      --bg-surface:    #ffffff;
      --bg-sunken:     #F8F7F3;
      --border:        rgba(0,0,0,0.08);
      --border-md:     rgba(0,0,0,0.12);
      --text-primary:  #111827;
      --text-secondary:#6B7280;
      --text-muted:    #9CA3AF;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: var(--font); font-size: 14px; line-height: 1.6; }
    body { display: flex; background: var(--bg-base); color: var(--text-primary); }
    a { text-decoration: none; color: inherit; }
    button { font-family: var(--font); cursor: pointer; }

    /* ── Split shell ─────────────────────────────────── */
    .auth-shell {
      display: flex; width: 100%; min-height: 100vh;
    }

    /* ── Left panel ──────────────────────────────────── */
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
      background:
        radial-gradient(ellipse at 0% 0%,   rgba(29,158,117,.18) 0%, transparent 60%),
        radial-gradient(ellipse at 100% 100%, rgba(4,52,44,.9)    0%, transparent 70%);
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
    .panel-brand {
      display: flex; align-items: center; gap: 12px;
      margin-bottom: 56px;
    }
    .panel-icon {
      width: 40px; height: 40px; border-radius: 12px;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 20px;
    }
    .panel-brand-name { font-size: 20px; font-weight: 700; color: #fff; letter-spacing: -0.3px; }

    .panel-headline { font-size: 26px; font-weight: 700; color: #fff; line-height: 1.3; letter-spacing: -0.5px; margin-bottom: 12px; }
    .panel-desc { font-size: 13px; color: rgba(255,255,255,0.5); line-height: 1.7; margin-bottom: 40px; }

    .panel-features { display: flex; flex-direction: column; gap: 16px; }
    .feature {
      display: flex; align-items: center; gap: 12px;
    }
    .feature-icon {
      width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
      background: rgba(255,255,255,0.07);
      border: 0.5px solid rgba(255,255,255,0.10);
      display: flex; align-items: center; justify-content: center;
      font-size: 16px; color: var(--accent);
    }
    .feature-text { font-size: 13px; color: rgba(255,255,255,0.65); }

    .panel-bottom { position: relative; z-index: 1; }
    .panel-footer-text { font-size: 11px; color: rgba(255,255,255,0.25); }

    /* ── Right form ──────────────────────────────────── */
    .auth-form {
      flex: 1; display: flex; align-items: center; justify-content: center;
      padding: 40px 24px;
      background: var(--bg-surface);
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
    .form-sub   { font-size: 13px; color: var(--text-secondary); }

    /* ── Alerts ──────────────────────────────────────── */
    .alert {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 12px 14px; border-radius: 10px;
      font-size: 13px; margin-bottom: 20px;
    }
    .alert i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .alert.error   { background: var(--red-50);   color: var(--red-800);   border: 0.5px solid #fbb; }
    .alert.success { background: var(--green-50); color: var(--green-800); border: 0.5px solid #9FE1CB; }

    /* ── Form groups ─────────────────────────────────── */
    .form-group  { margin-bottom: 18px; }
    .form-label  {
      display: flex; align-items: center; justify-content: space-between;
      font-size: 12px; font-weight: 600; color: var(--text-secondary);
      margin-bottom: 7px; letter-spacing: 0.2px;
    }
    .form-label a { font-weight: 500; color: var(--accent); font-size: 11px; }
    .form-label a:hover { color: var(--accent-hover); }
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
    .input-wrap .input-icon {
      padding: 0 12px; font-size: 16px; color: var(--text-muted); flex-shrink: 0;
    }
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

    .field-error {
      display: flex; align-items: center; gap: 5px;
      font-size: 11px; color: var(--red-400); margin-top: 6px;
    }
    .field-error::before { content: ''; width: 4px; height: 4px; border-radius: 50%; background: var(--red-400); flex-shrink: 0; }

    /* ── Remember me ─────────────────────────────────── */
    .remember-row {
      display: flex; align-items: center; gap: 8px;
      margin-bottom: 22px;
    }
    .checkbox-wrap {
      position: relative; width: 16px; height: 16px; flex-shrink: 0;
    }
    .checkbox-wrap input {
      position: absolute; opacity: 0; width: 0; height: 0;
    }
    .checkbox-custom {
      position: absolute; inset: 0; border-radius: 4px;
      border: 1.5px solid var(--border-md);
      background: var(--bg-sunken);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; transition: all 0.15s;
    }
    .checkbox-wrap input:checked + .checkbox-custom {
      background: var(--accent); border-color: var(--accent);
    }
    .checkbox-custom::after {
      content: ''; display: none;
      width: 8px; height: 5px;
      border-left: 2px solid #fff; border-bottom: 2px solid #fff;
      transform: rotate(-45deg) translateY(-1px);
    }
    .checkbox-wrap input:checked + .checkbox-custom::after { display: block; }
    .remember-label { font-size: 13px; color: var(--text-secondary); cursor: pointer; }

    /* ── Submit ──────────────────────────────────────── */
    .btn-submit {
      width: 100%; padding: 12px;
      background: var(--accent); color: #fff;
      border: none; border-radius: 10px;
      font-size: 14px; font-weight: 600; font-family: var(--font);
      cursor: pointer; transition: background 0.15s, transform 0.1s, box-shadow 0.15s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 2px 8px rgba(29,158,117,.3);
    }
    .btn-submit:hover { background: var(--accent-hover); box-shadow: 0 4px 14px rgba(29,158,117,.35); }
    .btn-submit:active { transform: scale(0.99); }
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

    /* ── Super admin link ────────────────────────────── */
    .form-footer {
      text-align: center; margin-top: 24px;
      font-size: 12px; color: var(--text-muted);
    }
    .form-footer a { color: var(--accent); font-weight: 500; }
    .form-footer a:hover { color: var(--accent-hover); }

    /* ── Responsive ──────────────────────────────────── */
    @media (max-width: 800px) {
      .auth-panel { display: none; }
      .auth-form  { background: var(--bg-base); }
    }
  </style>
</head>
<body>

<div class="auth-shell">

  {{-- ── Left Brand Panel ─────────────────────────── --}}
  <div class="auth-panel">
    <div class="panel-top">
      <div class="panel-brand">
        <div class="panel-icon"><i class="ti ti-tool"></i></div>
        <div class="panel-brand-name">Hirfa</div>
      </div>

      <div class="panel-headline">Manage your<br>platform with<br>confidence.</div>
      <div class="panel-desc">The Hirfa Admin Panel gives you full control over users, services, verifications, and platform content.</div>

      <div class="panel-features">
        <div class="feature">
          <div class="feature-icon"><i class="ti ti-users"></i></div>
          <div class="feature-text">Complete user & worker management</div>
        </div>
        <div class="feature">
          <div class="feature-icon"><i class="ti ti-chart-bar"></i></div>
          <div class="feature-text">Real-time analytics & reporting</div>
        </div>
        <div class="feature">
          <div class="feature-icon"><i class="ti ti-shield-check"></i></div>
          <div class="feature-text">Secure role-based access control</div>
        </div>
      </div>
    </div>

    <div class="panel-bottom">
      <div class="panel-footer-text">© {{ date('Y') }} Hirfa Platform. All rights reserved.</div>
    </div>
  </div>

  {{-- ── Right Form ────────────────────────────────── --}}
  <div class="auth-form">
    <div class="form-wrap">

      <div class="form-header">
        <div class="form-eyebrow"><i class="ti ti-lock" style="font-size:10px;"></i> Admin Access</div>
        <div class="form-title">Welcome back</div>
        <div class="form-sub">Sign in to your admin account to continue</div>
      </div>

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

        <div class="form-group">
          <label class="form-label">
            <span>Email Address</span>
          </label>
          <div class="input-wrap">
            <i class="ti ti-mail input-icon"></i>
            <input type="email" name="email" placeholder="admin@hirfa.com"
                   value="{{ old('email') }}" autofocus autocomplete="email" required>
          </div>
          @error('email')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label class="form-label">
            <span>Password</span>
            <a href="{{ route('admin.forgot-password') }}">Forgot password?</a>
          </label>
          <div class="input-wrap">
            <i class="ti ti-lock input-icon"></i>
            <input type="password" id="password" name="password"
                   placeholder="••••••••" autocomplete="current-password" required>
            <button type="button" class="pw-toggle" onclick="togglePassword('password', this)" title="Show password">
              <i class="ti ti-eye"></i>
            </button>
          </div>
          @error('password')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        <div class="remember-row">
          <label class="checkbox-wrap">
            <input type="checkbox" name="remember">
            <div class="checkbox-custom"></div>
          </label>
          <span class="remember-label">Keep me signed in</span>
        </div>

        <button type="submit" class="btn-submit" id="submitBtn">
          <div class="spinner"></div>
          <span class="btn-text"><i class="ti ti-login" style="font-size:16px;"></i> Sign In</span>
        </button>
      </form>

      <div class="form-footer">
        Not an admin? <a href="{{ route('super_admin.login') }}">Super Admin Portal →</a>
      </div>

    </div>
  </div>

</div>

<script>
  function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
      input.type = 'text';
      icon.className = 'ti ti-eye-off';
    } else {
      input.type = 'password';
      icon.className = 'ti ti-eye';
    }
  }

  document.getElementById('loginForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    btn.classList.add('loading');
  });
</script>

</body>
</html>
