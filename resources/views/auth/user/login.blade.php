<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In — Hirfa</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font:         'Inter', sans-serif;
      --accent:       #1D9E75;
      --accent-hover: #0F6E56;
      --accent-light: #E1F5EE;
      --red-400:      #E24B4A;
      --red-50:       #FCEBEB;
      --red-800:      #791F1F;
      --green-50:     #E1F5EE;
      --green-800:    #085041;
      --bg:           #F4F2EB;
      --surface:      #ffffff;
      --sunken:       #F8F7F4;
      --border:       rgba(0,0,0,0.08);
      --border-md:    rgba(0,0,0,0.13);
      --txt:          #1a1a1a;
      --txt-2:        #5F5E5A;
      --txt-3:        #B4B2A9;
      --panel-bg:     #04342C;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: var(--font); font-size: 14px; line-height: 1.6; }
    body { display: flex; background: var(--bg); color: var(--txt); min-height: 100vh; }
    a { text-decoration: none; color: inherit; }
    button { font-family: var(--font); cursor: pointer; }

    /* ── Split layout ─────────────────────────────── */
    .page { display: flex; width: 100%; min-height: 100vh; }

    /* Left panel */
    .panel-left {
      width: 400px; flex-shrink: 0;
      background: var(--panel-bg);
      display: flex; flex-direction: column;
      padding: 48px 40px;
      position: sticky; top: 0; height: 100vh;
      overflow: hidden;
    }
    .panel-left::before {
      content: '';
      position: absolute; inset: 0;
      background:
        radial-gradient(ellipse at 20% 80%, rgba(29,158,117,.25) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 10%, rgba(29,158,117,.12) 0%, transparent 50%);
      pointer-events: none;
    }
    .pl-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 56px; position: relative; }
    .pl-icon  {
      width: 42px; height: 42px; border-radius: 12px;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 20px;
    }
    .pl-name { font-size: 18px; font-weight: 700; color: #fff; letter-spacing: -0.3px; }
    .pl-tagline {
      font-size: 26px; font-weight: 700; line-height: 1.3;
      color: #fff; letter-spacing: -0.5px; margin-bottom: 12px; position: relative;
    }
    .pl-tagline span { color: var(--accent); }
    .pl-desc { font-size: 13px; color: rgba(255,255,255,.55); line-height: 1.7; margin-bottom: 44px; position: relative; }
    .pl-features { display: flex; flex-direction: column; gap: 18px; position: relative; }
    .pl-feat { display: flex; align-items: flex-start; gap: 12px; }
    .pl-feat-icon {
      width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
      background: rgba(29,158,117,.18);
      display: flex; align-items: center; justify-content: center;
      font-size: 16px; color: var(--accent); margin-top: 2px;
    }
    .pl-feat-title { font-size: 13px; font-weight: 600; color: rgba(255,255,255,.9); }
    .pl-feat-desc  { font-size: 12px; color: rgba(255,255,255,.45); margin-top: 1px; }
    .pl-bottom {
      margin-top: auto; padding-top: 32px;
      border-top: 0.5px solid rgba(255,255,255,.08);
      font-size: 12px; color: rgba(255,255,255,.3); position: relative;
    }

    /* Right panel */
    .panel-right {
      flex: 1; display: flex; align-items: center;
      justify-content: center; padding: 48px 32px;
    }
    .form-wrap { width: 100%; max-width: 400px; }

    /* Header */
    .form-header { margin-bottom: 32px; }
    .form-title { font-size: 24px; font-weight: 700; letter-spacing: -0.4px; margin-bottom: 6px; }
    .form-sub   { font-size: 13px; color: var(--txt-2); }

    /* Alerts */
    .alert {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 12px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 20px;
    }
    .alert i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .alert.error   { background: var(--red-50);   color: var(--red-800);   border: 0.5px solid rgba(226,75,74,.2); }
    .alert.success { background: var(--green-50); color: var(--green-800); border: 0.5px solid rgba(29,158,117,.3); }

    /* Form fields */
    .form-group { margin-bottom: 18px; }
    .form-label {
      display: flex; align-items: center; justify-content: space-between;
      font-size: 12px; font-weight: 600; color: var(--txt-2); margin-bottom: 7px;
    }
    .form-label a { font-weight: 500; color: var(--accent); font-size: 11px; }
    .form-label a:hover { color: var(--accent-hover); }
    .input-wrap {
      display: flex; align-items: center;
      background: var(--sunken);
      border: 0.5px solid var(--border-md);
      border-radius: 10px; overflow: hidden;
      transition: border-color .15s, box-shadow .15s;
    }
    .input-wrap:focus-within {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(29,158,117,.1);
    }
    .input-wrap > i { padding: 0 12px; font-size: 16px; color: var(--txt-3); flex-shrink: 0; }
    .input-wrap input {
      flex: 1; border: none; outline: none; background: transparent;
      padding: 11px 12px 11px 0; font-size: 13px;
      color: var(--txt); font-family: var(--font);
    }
    .input-wrap input::placeholder { color: var(--txt-3); }
    .pw-toggle {
      padding: 0 12px; font-size: 16px; color: var(--txt-3);
      cursor: pointer; border: none; background: none; flex-shrink: 0;
      transition: color .12s; display: flex; align-items: center;
    }
    .pw-toggle:hover { color: var(--txt); }
    .field-error {
      display: flex; align-items: center; gap: 4px;
      font-size: 11px; color: var(--red-400); margin-top: 5px;
    }
    .field-error::before { content: ''; width: 4px; height: 4px; border-radius: 50%; background: var(--red-400); flex-shrink: 0; }

    /* Remember */
    .remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 22px; }
    .checkbox-wrap { position: relative; width: 16px; height: 16px; flex-shrink: 0; }
    .checkbox-wrap input { position: absolute; opacity: 0; width: 0; height: 0; }
    .checkbox-custom {
      position: absolute; inset: 0; border-radius: 4px;
      border: 1.5px solid var(--border-md); background: var(--sunken);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; transition: all .15s;
    }
    .checkbox-wrap input:checked + .checkbox-custom { background: var(--accent); border-color: var(--accent); }
    .checkbox-custom::after {
      content: ''; display: none;
      width: 8px; height: 5px;
      border-left: 2px solid #fff; border-bottom: 2px solid #fff;
      transform: rotate(-45deg) translateY(-1px);
    }
    .checkbox-wrap input:checked + .checkbox-custom::after { display: block; }
    .remember-label { font-size: 13px; color: var(--txt-2); cursor: pointer; }

    /* Submit */
    .btn-submit {
      width: 100%; padding: 12px;
      background: var(--accent); color: #fff;
      border: none; border-radius: 10px;
      font-size: 14px; font-weight: 600; font-family: var(--font);
      cursor: pointer; transition: background .15s, transform .1s, box-shadow .15s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 2px 8px rgba(29,158,117,.3);
    }
    .btn-submit:hover  { background: var(--accent-hover); box-shadow: 0 4px 14px rgba(29,158,117,.35); }
    .btn-submit:active { transform: scale(.99); }
    .btn-submit.loading { pointer-events: none; opacity: .75; }
    .btn-submit .spinner {
      display: none; width: 16px; height: 16px;
      border: 2px solid rgba(255,255,255,.3);
      border-top-color: #fff; border-radius: 50%;
      animation: spin .7s linear infinite;
    }
    .btn-submit.loading .spinner { display: block; }
    .btn-submit.loading .btn-text { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Footer */
    .footer-link {
      text-align: center; margin-top: 22px;
      padding-top: 18px; border-top: 0.5px solid var(--border);
      font-size: 13px; color: var(--txt-2);
    }
    .footer-link a { color: var(--accent); font-weight: 500; }
    .footer-link a:hover { color: var(--accent-hover); }

    /* Responsive */
    @media (max-width: 860px) { .panel-left { display: none; } }
    @media (max-width: 480px) { .panel-right { padding: 32px 20px; } }
  </style>
</head>
<body>

<div class="page">

  {{-- ── Left Brand Panel ── --}}
  <aside class="panel-left">
    <div class="pl-brand">
      <div class="pl-icon"><i class="ti ti-tool"></i></div>
      <span class="pl-name">Hirfa</span>
    </div>

    <h2 class="pl-tagline">Welcome<br>back to <span>Hirfa</span>.</h2>
    <p class="pl-desc">Sign in to manage your profile, explore services, and connect with skilled craftsmen in your area.</p>

    <div class="pl-features">
      <div class="pl-feat">
        <div class="pl-feat-icon"><i class="ti ti-tools"></i></div>
        <div>
          <div class="pl-feat-title">Browse Services</div>
          <div class="pl-feat-desc">Explore hundreds of skilled professionals</div>
        </div>
      </div>
      <div class="pl-feat">
        <div class="pl-feat-icon"><i class="ti ti-message-circle"></i></div>
        <div>
          <div class="pl-feat-title">Direct Chat</div>
          <div class="pl-feat-desc">Message workers instantly before booking</div>
        </div>
      </div>
      <div class="pl-feat">
        <div class="pl-feat-icon"><i class="ti ti-bell"></i></div>
        <div>
          <div class="pl-feat-title">Real-time Updates</div>
          <div class="pl-feat-desc">Get notified about your requests instantly</div>
        </div>
      </div>
    </div>

    <div class="pl-bottom">© {{ date('Y') }} Hirfa. All rights reserved.</div>
  </aside>

  {{-- ── Right Form Panel ── --}}
  <main class="panel-right">
    <div class="form-wrap">

      <div class="form-header">
        <div class="form-title">Sign in to your account</div>
        <div class="form-sub">Enter your phone number and password to continue</div>
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
      @error('account')
        <div class="alert error">
          <i class="ti ti-alert-circle"></i>
          <span>{{ $message }}</span>
        </div>
      @enderror

      <form method="POST" action="{{ route('user.login.post') }}" id="loginForm">
        @csrf

        <div class="form-group">
          <label class="form-label"><span>Phone Number</span></label>
          <div class="input-wrap">
            <i class="ti ti-phone"></i>
            <input type="tel" name="phone" placeholder="09xxxxxxxx"
                   value="{{ old('phone') }}" autofocus autocomplete="tel" required>
          </div>
          @error('phone')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label class="form-label">
            <span>Password</span>
            <a href="{{ route('user.forgot-password') }}">Forgot password?</a>
          </label>
          <div class="input-wrap">
            <i class="ti ti-lock"></i>
            <input type="password" id="password" name="password"
                   placeholder="••••••••" autocomplete="current-password" required>
            <button type="button" class="pw-toggle" onclick="togglePw('password', this)">
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

      <div class="footer-link">
        Don't have an account? <a href="{{ route('user.register') }}">Create one →</a>
      </div>

    </div>
  </main>

</div>

<script>
  function togglePw(id, btn) {
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
