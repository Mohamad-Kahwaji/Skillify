<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Super Admin Sign In — Hirfa</title>
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
      --red-50:        #FCEBEB;
      --red-800:       #791F1F;
      --green-50:      #E1F5EE;
      --green-800:     #085041;
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

    /* ── Split shell ─────────────────────────────────── */
    .auth-shell { display: flex; width: 100%; min-height: 100vh; }

    /* ── Left panel ──────────────────────────────────── */
    .auth-panel {
      width: 440px; flex-shrink: 0;
      background: var(--panel-bg);
      display: flex; flex-direction: column;
      justify-content: space-between;
      padding: 48px 44px;
      position: relative; overflow: hidden;
      border-right: 0.5px solid var(--border);
    }
    .auth-panel::before {
      content: '';
      position: absolute; top: -120px; left: -120px;
      width: 400px; height: 400px; border-radius: 50%;
      background: radial-gradient(circle, rgba(29,158,117,.12) 0%, transparent 70%);
      pointer-events: none;
    }
    .auth-panel::after {
      content: '';
      position: absolute; bottom: -100px; right: -100px;
      width: 360px; height: 360px; border-radius: 50%;
      background: radial-gradient(circle, rgba(29,158,117,.06) 0%, transparent 70%);
      pointer-events: none;
    }
    .panel-top { position: relative; z-index: 1; }
    .panel-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 52px; }
    .panel-icon {
      width: 42px; height: 42px; border-radius: 12px;
      background: linear-gradient(135deg, var(--accent) 0%, #085041 100%);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 20px;
      box-shadow: 0 0 20px rgba(29,158,117,.30);
    }
    .panel-brand-name { font-size: 20px; font-weight: 700; color: #fff; letter-spacing: -0.3px; }
    .panel-crown {
      display: inline-flex; align-items: center; gap: 5px;
      background: var(--accent-dim); border: 0.5px solid var(--accent-border);
      color: var(--accent); font-size: 10px; font-weight: 600;
      padding: 3px 9px; border-radius: 20px; letter-spacing: 0.8px; text-transform: uppercase;
    }

    .panel-headline { font-size: 28px; font-weight: 700; color: #fff; line-height: 1.25; letter-spacing: -0.6px; margin: 20px 0 12px; }
    .panel-desc { font-size: 13px; color: var(--text-secondary); line-height: 1.8; margin-bottom: 40px; }

    .panel-features { display: flex; flex-direction: column; gap: 14px; }
    .feature {
      display: flex; align-items: center; gap: 12px;
      padding: 12px 14px; border-radius: 10px;
      background: rgba(255,255,255,0.03);
      border: 0.5px solid var(--border);
    }
    .feature-icon {
      width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
      background: var(--accent-dim);
      border: 0.5px solid var(--accent-border);
      display: flex; align-items: center; justify-content: center;
      font-size: 15px; color: var(--accent);
    }
    .feature-text { font-size: 12px; color: var(--text-secondary); }
    .feature-title { font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 1px; }

    .panel-bottom { position: relative; z-index: 1; }
    .panel-footer-text { font-size: 11px; color: rgba(255,255,255,0.18); }

    /* ── Right form ──────────────────────────────────── */
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
    .alert.error   { background: rgba(226,75,74,.12);  color: #f09595; border: 0.5px solid rgba(226,75,74,.25); }
    .alert.success { background: var(--accent-dim);    color: var(--accent-hover); border: 0.5px solid var(--accent-border); }

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

    .field-error {
      display: flex; align-items: center; gap: 5px;
      font-size: 11px; color: #f09595; margin-top: 6px;
    }
    .field-error::before { content: ''; width: 4px; height: 4px; border-radius: 50%; background: #f09595; flex-shrink: 0; }

    /* ── Remember me ─────────────────────────────────── */
    .remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 22px; }
    .checkbox-wrap { position: relative; width: 16px; height: 16px; flex-shrink: 0; }
    .checkbox-wrap input { position: absolute; opacity: 0; width: 0; height: 0; }
    .checkbox-custom {
      position: absolute; inset: 0; border-radius: 4px;
      border: 1.5px solid var(--border-md);
      background: var(--bg-sunken);
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

    /* ── Submit ──────────────────────────────────────── */
    .btn-submit {
      width: 100%; padding: 12px;
      background: var(--accent); color: #fff;
      border: none; border-radius: 10px;
      font-size: 14px; font-weight: 600; font-family: var(--font);
      cursor: pointer; transition: background 0.15s, box-shadow 0.15s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 2px 12px rgba(29,158,117,.25);
    }
    .btn-submit:hover { background: #18875f; box-shadow: 0 4px 18px rgba(29,158,117,.35); }
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

    /* ── Footer ──────────────────────────────────────── */
    .form-footer { text-align: center; margin-top: 20px; font-size: 12px; color: var(--text-muted); }
    .form-footer a { color: var(--accent); font-weight: 500; }
    .form-footer a:hover { color: var(--accent-hover); }

    @media (max-width: 820px) {
      .auth-panel { display: none; }
    }
  </style>
</head>
<body>

<div class="auth-shell">

  {{-- ── Left Brand Panel ─────────────────────────── --}}
  <div class="auth-panel">
    <div class="panel-top">
      <div class="panel-brand">
        <div class="panel-icon"><i class="ti ti-shield-check"></i></div>
        <div>
          <div class="panel-brand-name">Hirfa</div>
          <div class="panel-crown"><i class="ti ti-crown" style="font-size:9px;"></i> Super Admin</div>
        </div>
      </div>

      <div class="panel-headline">Root-level<br>platform control.</div>
      <div class="panel-desc">Manage admins, permissions, roles, and every aspect of the Hirfa platform from one secure place.</div>

      <div class="panel-features">
        <div class="feature">
          <div class="feature-icon"><i class="ti ti-user-shield"></i></div>
          <div>
            <div class="feature-title">Admin Management</div>
            <div class="feature-text">Create, activate, and configure admin accounts</div>
          </div>
        </div>
        <div class="feature">
          <div class="feature-icon"><i class="ti ti-key"></i></div>
          <div>
            <div class="feature-title">Roles & Permissions</div>
            <div class="feature-text">Fine-grained access control for every role</div>
          </div>
        </div>
        <div class="feature">
          <div class="feature-icon"><i class="ti ti-speakerphone"></i></div>
          <div>
            <div class="feature-title">System Announcements</div>
            <div class="feature-text">Broadcast messages to all platform users</div>
          </div>
        </div>
      </div>
    </div>

    <div class="panel-bottom">
      <div class="panel-footer-text">© {{ date('Y') }} Hirfa Platform. Restricted access.</div>
    </div>
  </div>

  {{-- ── Right Form ────────────────────────────────── --}}
  <div class="auth-form">
    <div class="form-wrap">

      <div class="form-header">
        <div class="form-eyebrow"><i class="ti ti-crown" style="font-size:9px;"></i> Super Admin Portal</div>
        <div class="form-title">Restricted access</div>
        <div class="form-sub">Authenticate with your super admin credentials to proceed.</div>
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

      <form method="POST" action="{{ route('super_admin.login.post') }}" id="loginForm">
        @csrf

        <div class="form-group">
          <label class="form-label">
            <span>Email Address</span>
          </label>
          <div class="input-wrap">
            <i class="ti ti-mail input-icon"></i>
            <input type="email" name="email" placeholder="super@hirfa.com"
                   value="{{ old('email') }}" autofocus autocomplete="email" required>
          </div>
          @error('email')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label class="form-label">
            <span>Password</span>
            <a href="{{ route('super_admin.forgot-password') }}">Forgot password?</a>
          </label>
          <div class="input-wrap">
            <i class="ti ti-lock input-icon"></i>
            <input type="password" id="password" name="password"
                   placeholder="••••••••" autocomplete="current-password" required>
            <button type="button" class="pw-toggle" onclick="togglePassword('password', this)">
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
          <span class="btn-text"><i class="ti ti-shield-check" style="font-size:16px;"></i> Sign In Securely</span>
        </button>
      </form>

      <div class="form-footer">
        Admin panel? <a href="{{ route('admin.login') }}">Sign in here →</a>
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
