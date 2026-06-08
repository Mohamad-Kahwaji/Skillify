<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Account — Hirfa</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font: 'Inter', sans-serif;
      --accent:        #1D9E75;
      --accent-hover:  #0F6E56;
      --accent-light:  #E1F5EE;
      --red-400:  #E24B4A;
      --red-50:   #FCEBEB;
      --red-800:  #791F1F;
      --bg:       #F4F2EB;
      --surface:  #ffffff;
      --sunken:   #F8F7F4;
      --border:   rgba(0,0,0,0.08);
      --border-md:rgba(0,0,0,0.13);
      --txt:      #1a1a1a;
      --txt-2:    #5F5E5A;
      --txt-3:    #B4B2A9;
      --panel-bg: #04342C;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; }
    body { font-family: var(--font); font-size: 14px; line-height: 1.6; color: var(--txt); background: var(--bg); display: flex; min-height: 100vh; }

    /* ── SPLIT LAYOUT ── */
    .page { display: flex; width: 100%; min-height: 100vh; }

    /* LEFT PANEL */
    .panel-left {
      width: 380px; flex-shrink: 0;
      background: var(--panel-bg);
      display: flex; flex-direction: column;
      padding: 48px 40px;
      position: sticky; top: 0; height: 100vh;
      overflow: hidden;
    }
    .panel-left::before {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(ellipse at 20% 80%, rgba(29,158,117,.25) 0%, transparent 60%),
                  radial-gradient(ellipse at 80% 10%, rgba(29,158,117,.12) 0%, transparent 50%);
      pointer-events: none;
    }
    .pl-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 56px; position: relative; }
    .pl-icon  {
      width: 42px; height: 42px; border-radius: 12px;
      background: var(--accent); display: flex; align-items: center;
      justify-content: center; color: #fff; font-size: 20px;
    }
    .pl-name { font-size: 18px; font-weight: 700; color: #fff; letter-spacing: -0.3px; }
    .pl-tagline {
      font-size: 26px; font-weight: 700; line-height: 1.3;
      color: #fff; letter-spacing: -0.5px; margin-bottom: 12px;
      position: relative;
    }
    .pl-tagline span { color: var(--accent); }
    .pl-desc { font-size: 13px; color: rgba(255,255,255,.55); line-height: 1.7; margin-bottom: 44px; position: relative; }
    .pl-features { display: flex; flex-direction: column; gap: 18px; position: relative; }
    .pl-feat { display: flex; align-items: flex-start; gap: 12px; }
    .pl-feat-icon {
      width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
      background: rgba(29,158,117,.18); display: flex; align-items: center;
      justify-content: center; font-size: 16px; color: var(--accent);
      margin-top: 2px;
    }
    .pl-feat-title { font-size: 13px; font-weight: 600; color: rgba(255,255,255,.9); }
    .pl-feat-desc  { font-size: 12px; color: rgba(255,255,255,.45); margin-top: 1px; }
    .pl-bottom {
      margin-top: auto; padding-top: 32px;
      border-top: 0.5px solid rgba(255,255,255,.08);
      font-size: 12px; color: rgba(255,255,255,.3);
      position: relative;
    }

    /* RIGHT PANEL */
    .panel-right {
      flex: 1; display: flex; align-items: flex-start;
      justify-content: center; padding: 48px 32px;
      overflow-y: auto;
    }
    .form-wrap { width: 100%; max-width: 480px; }

    /* HEADER */
    .form-header { margin-bottom: 32px; }
    .form-title { font-size: 22px; font-weight: 700; letter-spacing: -0.4px; color: var(--txt); }
    .form-sub   { font-size: 13px; color: var(--txt-2); margin-top: 4px; }
    .progress-bar {
      display: flex; gap: 4px; margin-top: 20px;
    }
    .progress-seg {
      height: 3px; border-radius: 20px; flex: 1;
      background: var(--border-md); transition: background .3s;
    }
    .progress-seg.active { background: var(--accent); }

    /* SECTION DIVIDER */
    .section-div {
      display: flex; align-items: center; gap: 10px;
      margin: 24px 0 20px;
    }
    .section-div::before, .section-div::after { content: ''; flex: 1; height: 0.5px; background: var(--border); }
    .section-div span {
      font-size: 10px; font-weight: 600; letter-spacing: 1px;
      text-transform: uppercase; color: var(--txt-3);
      white-space: nowrap;
    }

    /* FORM FIELDS */
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-group { margin-bottom: 16px; }
    .form-label {
      display: flex; align-items: center; gap: 5px;
      font-size: 12px; font-weight: 500; color: var(--txt-2); margin-bottom: 7px;
    }
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
    .input-wrap input,
    .input-wrap select {
      flex: 1; border: none; outline: none; background: transparent;
      padding: 11px 12px 11px 0; font-size: 13px; color: var(--txt);
      font-family: var(--font); appearance: none; -webkit-appearance: none;
    }
    .input-wrap input::placeholder { color: var(--txt-3); }
    .select-arrow { padding: 0 12px; font-size: 13px; color: var(--txt-3); pointer-events: none; flex-shrink: 0; }
    .pw-toggle {
      padding: 0 12px; font-size: 16px; color: var(--txt-3);
      cursor: pointer; border: none; background: none; flex-shrink: 0;
      transition: color .12s; display: flex; align-items: center;
    }
    .pw-toggle:hover { color: var(--accent); }
    .field-error { font-size: 11px; color: var(--red-400); margin-top: 5px; display: flex; align-items: center; gap: 4px; }
    .field-error::before { content: '\eab0'; font-family: 'tabler-icons'; font-size: 12px; }

    /* ALERT */
    .alert { display: flex; align-items: center; gap: 10px; padding: 12px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; }
    .alert.error { background: var(--red-50); color: var(--red-800); border: 0.5px solid rgba(226,75,74,.2); }

    /* SUBMIT */
    .btn-submit {
      width: 100%; padding: 12px;
      background: var(--accent); color: #fff;
      border: none; border-radius: 10px;
      font-size: 14px; font-weight: 600; font-family: var(--font);
      cursor: pointer; transition: background .15s, transform .1s, box-shadow .15s;
      margin-top: 6px; letter-spacing: 0.1px;
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-submit:hover  { background: var(--accent-hover); box-shadow: 0 4px 14px rgba(29,158,117,.35); }
    .btn-submit:active { transform: scale(.99); }

    /* FOOTER LINK */
    .footer-link {
      text-align: center; margin-top: 22px;
      font-size: 13px; color: var(--txt-2);
    }
    .footer-link a { color: var(--accent); font-weight: 500; text-decoration: none; }
    .footer-link a:hover { color: var(--accent-hover); text-decoration: underline; }

    /* TERMS NOTE */
    .terms-note { font-size: 11px; color: var(--txt-3); text-align: center; margin-top: 14px; line-height: 1.6; }
    .terms-note a { color: var(--txt-2); }

    /* RESPONSIVE */
    @media (max-width: 860px) {
      .panel-left { display: none; }
    }
    @media (max-width: 480px) {
      .form-row { grid-template-columns: 1fr; }
      .panel-right { padding: 32px 20px; }
    }
  </style>
</head>
<body>

<div class="page">

  {{-- ── LEFT BRAND PANEL ── --}}
  <aside class="panel-left">
    <div class="pl-brand">
      <div class="pl-icon"><i class="ti ti-tool"></i></div>
      <span class="pl-name">Hirfa</span>
    </div>

    <h2 class="pl-tagline">Find skilled <span>craftsmen</span><br>near you.</h2>
    <p class="pl-desc">Connect with verified professionals, browse services, and hire with confidence — all on one platform.</p>

    <div class="pl-features">
      <div class="pl-feat">
        <div class="pl-feat-icon"><i class="ti ti-shield-check"></i></div>
        <div>
          <div class="pl-feat-title">Verified Professionals</div>
          <div class="pl-feat-desc">Every worker is reviewed and verified</div>
        </div>
      </div>
      <div class="pl-feat">
        <div class="pl-feat-icon"><i class="ti ti-message-circle"></i></div>
        <div>
          <div class="pl-feat-title">Direct Messaging</div>
          <div class="pl-feat-desc">Chat directly with craftsmen before hiring</div>
        </div>
      </div>
      <div class="pl-feat">
        <div class="pl-feat-icon"><i class="ti ti-map-pin"></i></div>
        <div>
          <div class="pl-feat-title">Local Discovery</div>
          <div class="pl-feat-desc">Find talent in your city instantly</div>
        </div>
      </div>
      <div class="pl-feat">
        <div class="pl-feat-icon"><i class="ti ti-briefcase"></i></div>
        <div>
          <div class="pl-feat-title">Showcase Your Work</div>
          <div class="pl-feat-desc">Build a profile and grow your business</div>
        </div>
      </div>
    </div>

    <div class="pl-bottom">© {{ date('Y') }} Hirfa. All rights reserved.</div>
  </aside>

  {{-- ── RIGHT FORM PANEL ── --}}
  <main class="panel-right">
    <div class="form-wrap">

      <div class="form-header">
        <div class="form-title">Create your account</div>
        <div class="form-sub">Join thousands of craftsmen and clients on Hirfa</div>
        <div class="progress-bar">
          <div class="progress-seg active"></div>
          <div class="progress-seg active"></div>
          <div class="progress-seg" id="seg3"></div>
        </div>
      </div>

      @if($errors->any())
        <div class="alert error">
          <i class="ti ti-alert-circle" style="font-size:18px;flex-shrink:0;"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('user.register.post') }}" id="reg-form">
        @csrf

        <div class="section-div"><span>Personal Information</span></div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">First Name</label>
            <div class="input-wrap">
              <i class="ti ti-user"></i>
              <input type="text" name="first_name" placeholder="Ahmad" value="{{ old('first_name') }}" required autocomplete="given-name">
            </div>
            @error('first_name')<div class="field-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Last Name</label>
            <div class="input-wrap">
              <i class="ti ti-user"></i>
              <input type="text" name="last_name" placeholder="Khalil" value="{{ old('last_name') }}" required autocomplete="family-name">
            </div>
            @error('last_name')<div class="field-error">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Gender</label>
            <div class="input-wrap">
              <i class="ti ti-gender-bigender"></i>
              <select name="gender" required>
                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select</option>
                <option value="male"   {{ old('gender') === 'male'   ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
              </select>
              <span class="select-arrow"><i class="ti ti-chevron-down"></i></span>
            </div>
            @error('gender')<div class="field-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Date of Birth</label>
            <div class="input-wrap">
              <i class="ti ti-calendar"></i>
              <input type="date" name="birthdate" value="{{ old('birthdate') }}" autocomplete="bday">
            </div>
            @error('birthdate')<div class="field-error">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">City</label>
          <div class="input-wrap">
            <i class="ti ti-map-pin"></i>
            <input type="text" name="city" placeholder="Amman" value="{{ old('city') }}" required autocomplete="address-level2">
          </div>
          @error('city')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="section-div"><span>Account Details</span></div>

        <div class="form-group">
          <label class="form-label">Phone Number</label>
          <div class="input-wrap">
            <i class="ti ti-phone"></i>
            <input type="text" name="phone" placeholder="07xxxxxxxx" value="{{ old('phone') }}" required autocomplete="tel">
          </div>
          @error('phone')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
          <label class="form-label">Email Address</label>
          <div class="input-wrap">
            <i class="ti ti-mail"></i>
            <input type="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required autocomplete="email">
          </div>
          @error('email')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Password</label>
            <div class="input-wrap">
              <i class="ti ti-lock"></i>
              <input type="password" id="pw1" name="password" placeholder="Min 8 characters" required minlength="8" autocomplete="new-password">
              <button type="button" class="pw-toggle" onclick="togglePw('pw1',this)"><i class="ti ti-eye"></i></button>
            </div>
            @error('password')<div class="field-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <div class="input-wrap">
              <i class="ti ti-lock-check"></i>
              <input type="password" id="pw2" name="password_confirmation" placeholder="Repeat password" required autocomplete="new-password">
              <button type="button" class="pw-toggle" onclick="togglePw('pw2',this)"><i class="ti ti-eye"></i></button>
            </div>
          </div>
        </div>

        <button type="submit" class="btn-submit">
          <i class="ti ti-user-plus" style="font-size:17px;"></i>
          Create Account
        </button>
      </form>

      <div class="footer-link">
        Already have an account? <a href="{{ route('user.login') }}">Sign in</a>
      </div>
      <div class="terms-note">
        By creating an account you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
      </div>

    </div>
  </main>

</div>

<script>
function togglePw(id, btn) {
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

// Animate progress bar when password fields get focus
document.getElementById('pw1').addEventListener('focus', () => {
  document.getElementById('seg3').classList.add('active');
});
</script>
</body>
</html>
