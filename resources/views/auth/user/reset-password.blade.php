<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password — Hirfa</title>
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
    .pl-tips { display: flex; flex-direction: column; gap: 11px; position: relative; }
    .pl-tip  { display: flex; align-items: flex-start; gap: 10px; }
    .pl-tip-dot {
      width: 6px; height: 6px; border-radius: 50%;
      background: var(--accent); flex-shrink: 0; margin-top: 7px;
    }
    .pl-tip-text { font-size: 12px; color: rgba(255,255,255,.45); }
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

    /* ── Form header ── */
    .form-header { margin-bottom: 32px; }
    .form-title { font-size: 24px; font-weight: 700; letter-spacing: -0.4px; margin-bottom: 6px; }
    .form-sub   { font-size: 13px; color: var(--txt-2); }

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

    /* ── Password strength ── */
    .strength-bar {
      display: flex; gap: 4px; margin-top: 8px;
    }
    .strength-seg {
      flex: 1; height: 3px; border-radius: 2px;
      background: var(--border-md); transition: background .25s;
    }
    .strength-seg.weak   { background: #E24B4A; }
    .strength-seg.fair   { background: #F59E0B; }
    .strength-seg.good   { background: #3B82F6; }
    .strength-seg.strong { background: var(--accent); }
    .strength-label {
      font-size: 11px; color: var(--txt-3); margin-top: 5px; height: 16px;
      transition: color .2s;
    }

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

    .back-link {
      text-align: center; margin-top: 20px;
      font-size: 13px; color: var(--txt-2);
    }
    .back-link a { color: var(--accent); font-weight: 500; }
    .back-link a:hover { color: var(--accent-hover); }

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

    <h2 class="pl-tagline">Create a<br><span>strong password</span>.</h2>
    <p class="pl-desc">Choose a new password that keeps your account secure. You'll use it to sign in next time.</p>

    <div class="pl-tips">
      <div class="pl-tip"><div class="pl-tip-dot"></div><span class="pl-tip-text">Use at least 8 characters</span></div>
      <div class="pl-tip"><div class="pl-tip-dot"></div><span class="pl-tip-text">Mix uppercase and lowercase letters</span></div>
      <div class="pl-tip"><div class="pl-tip-dot"></div><span class="pl-tip-text">Include numbers and symbols</span></div>
      <div class="pl-tip"><div class="pl-tip-dot"></div><span class="pl-tip-text">Avoid using your name or phone number</span></div>
    </div>

    <div class="pl-bottom">© {{ date('Y') }} Hirfa. All rights reserved.</div>
  </aside>

  {{-- ── Right Panel ── --}}
  <main class="panel-right">
    <div class="form-wrap">

      <div class="form-header">
        <div class="form-title">Set a new password</div>
        <div class="form-sub">Enter a strong password to secure your account</div>
      </div>

      @if($errors->any())
        <div class="alert error">
          <i class="ti ti-alert-circle"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('user.reset-password.update') }}" id="resetForm">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
          <label class="form-label">New Password</label>
          <div class="input-wrap">
            <i class="ti ti-lock"></i>
            <input type="password" id="pw1" name="password"
                   placeholder="••••••••" required minlength="8"
                   autocomplete="new-password" oninput="checkStrength(this.value)"
                   autofocus>
            <button type="button" class="pw-toggle" onclick="togglePw('pw1', this)">
              <i class="ti ti-eye"></i>
            </button>
          </div>
          <div class="strength-bar">
            <div class="strength-seg" id="s1"></div>
            <div class="strength-seg" id="s2"></div>
            <div class="strength-seg" id="s3"></div>
            <div class="strength-seg" id="s4"></div>
          </div>
          <div class="strength-label" id="strengthLabel"></div>
          @error('password')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Confirm New Password</label>
          <div class="input-wrap">
            <i class="ti ti-lock-check"></i>
            <input type="password" id="pw2" name="password_confirmation"
                   placeholder="••••••••" required autocomplete="new-password">
            <button type="button" class="pw-toggle" onclick="togglePw('pw2', this)">
              <i class="ti ti-eye"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-submit" id="submitBtn">
          <div class="spinner"></div>
          <span class="btn-text"><i class="ti ti-key" style="font-size:16px;"></i> Reset Password</span>
        </button>
      </form>

      <div class="back-link">
        <a href="{{ route('user.login') }}"><i class="ti ti-arrow-left" style="font-size:12px;"></i> Back to Sign In</a>
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

  function checkStrength(val) {
    const segs   = [document.getElementById('s1'), document.getElementById('s2'),
                    document.getElementById('s3'), document.getElementById('s4')];
    const label  = document.getElementById('strengthLabel');
    const colors = ['weak', 'fair', 'good', 'strong'];
    const labels = ['Too weak', 'Could be stronger', 'Almost there', 'Strong password'];

    let score = 0;
    if (val.length >= 8)                          score++;
    if (/[A-Z]/.test(val) && /[a-z]/.test(val))  score++;
    if (/[0-9]/.test(val))                        score++;
    if (/[^A-Za-z0-9]/.test(val))                score++;

    segs.forEach((s, i) => {
      s.className = 'strength-seg';
      if (i < score) s.classList.add(colors[score - 1]);
    });

    label.textContent = val.length ? labels[score - 1] || labels[0] : '';
    const labelColors = { weak: '#E24B4A', fair: '#F59E0B', good: '#3B82F6', strong: '#1D9E75' };
    label.style.color = score ? labelColors[colors[score - 1]] : '';
  }

  document.getElementById('resetForm').addEventListener('submit', function () {
    document.getElementById('submitBtn').classList.add('loading');
  });
</script>

</body>
</html>
