<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password — Hirfa</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font:         'Inter', sans-serif;
      --accent:       #1D9E75;
      --accent-hover: #0F6E56;
      --red-50:       #FCEBEB;
      --red-400:      #E24B4A;
      --red-800:      #791F1F;
      --bg:           #F4F2EB;
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

    .page { display: flex; width: 100%; min-height: 100vh; }

    /* ── Left panel ── */
    .panel-left {
      width: 400px; flex-shrink: 0;
      background: var(--panel-bg);
      display: flex; flex-direction: column;
      padding: 48px 40px;
      position: sticky; top: 0; height: 100vh; overflow: hidden;
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
    .pl-desc { font-size: 13px; color: rgba(255,255,255,.55); line-height: 1.7; margin-bottom: 40px; position: relative; }
    .pl-note {
      background: rgba(29,158,117,.12);
      border: 0.5px solid rgba(29,158,117,.25);
      border-radius: 12px; padding: 16px 18px; position: relative;
    }
    .pl-note-title {
      font-size: 12px; font-weight: 600; color: rgba(255,255,255,.8);
      margin-bottom: 10px; display: flex; align-items: center; gap: 7px;
    }
    .pl-note-title i { font-size: 15px; color: var(--accent); }
    .pl-note-item { display: flex; align-items: center; gap: 9px; margin-bottom: 8px; }
    .pl-note-item:last-child { margin-bottom: 0; }
    .pl-note-item i { font-size: 13px; color: rgba(29,158,117,.8); flex-shrink: 0; }
    .pl-note-item span { font-size: 12px; color: rgba(255,255,255,.45); }
    .pl-bottom {
      margin-top: auto; padding-top: 32px;
      border-top: 0.5px solid rgba(255,255,255,.08);
      font-size: 12px; color: rgba(255,255,255,.3); position: relative;
    }

    /* ── Right panel ── */
    .panel-right {
      flex: 1; display: flex; align-items: center;
      justify-content: center; padding: 48px 32px;
    }
    .form-wrap { width: 100%; max-width: 400px; }

    /* ── Success state ── */
    .success-state { text-align: center; }
    .success-icon {
      width: 64px; height: 64px; border-radius: 18px;
      background: #E1F5EE;
      display: inline-flex; align-items: center; justify-content: center;
      font-size: 30px; color: var(--accent); margin-bottom: 20px;
    }
    .success-title { font-size: 22px; font-weight: 700; letter-spacing: -0.4px; margin-bottom: 8px; }
    .success-desc  { font-size: 13px; color: var(--txt-2); margin-bottom: 28px; line-height: 1.7; }

    /* ── Form header ── */
    .form-header { margin-bottom: 32px; }
    .form-title { font-size: 24px; font-weight: 700; letter-spacing: -0.4px; margin-bottom: 6px; }
    .form-sub   { font-size: 13px; color: var(--txt-2); line-height: 1.6; }

    /* ── Alerts ── */
    .alert {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 12px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 20px;
    }
    .alert i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .alert.error { background: var(--red-50); color: var(--red-800); border: 0.5px solid rgba(226,75,74,.2); }

    /* ── Fields ── */
    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 12px; font-weight: 600; color: var(--txt-2); margin-bottom: 7px; }
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
    .field-error {
      display: flex; align-items: center; gap: 4px;
      font-size: 11px; color: var(--red-400); margin-top: 5px;
    }
    .field-error::before { content: ''; width: 4px; height: 4px; border-radius: 50%; background: var(--red-400); flex-shrink: 0; }

    /* ── Buttons ── */
    .btn-submit {
      width: 100%; padding: 12px;
      background: var(--accent); color: #fff;
      border: none; border-radius: 10px;
      font-size: 14px; font-weight: 600; font-family: var(--font);
      cursor: pointer; transition: background .15s, box-shadow .15s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 2px 8px rgba(29,158,117,.3);
    }
    .btn-submit:hover  { background: var(--accent-hover); box-shadow: 0 4px 14px rgba(29,158,117,.35); }
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
    .btn-ghost {
      width: 100%; padding: 11px;
      background: transparent; color: var(--txt-2);
      border: 0.5px solid var(--border-md); border-radius: 10px;
      font-size: 13px; font-weight: 500; font-family: var(--font);
      cursor: pointer; transition: all .15s;
      display: flex; align-items: center; justify-content: center; gap: 7px;
    }
    .btn-ghost:hover { background: var(--sunken); color: var(--txt); }

    .footer-link {
      text-align: center; margin-top: 20px;
      font-size: 13px; color: var(--txt-2);
    }
    .footer-link a { color: var(--accent); font-weight: 500; }
    .footer-link a:hover { color: var(--accent-hover); }

    @media (max-width: 860px) { .panel-left { display: none; } }
    @media (max-width: 480px) { .panel-right { padding: 32px 20px; } }
  </style>
</head>
<body>

<div class="page">

  {{-- ── Left Panel ── --}}
  <aside class="panel-left">
    <div class="pl-brand">
      <div class="pl-icon"><i class="ti ti-tool"></i></div>
      <span class="pl-name">Hirfa</span>
    </div>

    <h2 class="pl-tagline">No worries,<br>we've got <span>you covered</span>.</h2>
    <p class="pl-desc">Enter your phone number and we'll send you a link to reset your password quickly and securely.</p>

    <div class="pl-note">
      <div class="pl-note-title"><i class="ti ti-shield-check"></i> How it works</div>
      <div class="pl-note-item"><i class="ti ti-circle-check"></i><span>Enter your registered phone number</span></div>
      <div class="pl-note-item"><i class="ti ti-circle-check"></i><span>Receive a secure reset link via SMS</span></div>
      <div class="pl-note-item"><i class="ti ti-circle-check"></i><span>Create a new password and sign in</span></div>
    </div>

    <div class="pl-bottom">© {{ date('Y') }} Hirfa. All rights reserved.</div>
  </aside>

  {{-- ── Right Panel ── --}}
  <main class="panel-right">
    <div class="form-wrap">

      @if(session('status'))

        <div class="success-state">
          <div class="success-icon"><i class="ti ti-mail-check"></i></div>
          <div class="success-title">Check your messages</div>
          <div class="success-desc">
            {{ session('status') }}<br>
            Didn't receive it? Check your spam or try again.
          </div>
          <a href="{{ route('user.login') }}" class="btn-ghost">
            <i class="ti ti-arrow-left"></i> Back to Sign In
          </a>
        </div>

      @else

        <div class="form-header">
          <div class="form-title">Forgot your password?</div>
          <div class="form-sub">Enter your phone number and we'll send you a reset link to get back in.</div>
        </div>

        @if($errors->any())
          <div class="alert error">
            <i class="ti ti-alert-circle"></i>
            <span>{{ $errors->first() }}</span>
          </div>
        @endif

        <form method="POST" action="{{ route('user.forgot-password.send') }}" id="forgotForm">
          @csrf

          <div class="form-group">
            <label class="form-label">Phone Number</label>
            <div class="input-wrap">
              <i class="ti ti-phone"></i>
              <input type="tel" name="phone" placeholder="09xxxxxxxx"
                     value="{{ old('phone') }}" autofocus required>
            </div>
            @error('phone')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn-submit" id="submitBtn">
            <div class="spinner"></div>
            <span class="btn-text"><i class="ti ti-send" style="font-size:16px;"></i> Send Reset Link</span>
          </button>
        </form>

        <div class="footer-link">
          Remembered your password? <a href="{{ route('user.login') }}">Sign in →</a>
        </div>

      @endif

    </div>
  </main>

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
