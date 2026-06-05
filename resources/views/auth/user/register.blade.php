<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Account — Hirfa</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font: 'Inter', sans-serif;
      --green-400: #1D9E75; --green-600: #0F6E56; --green-800: #085041;
      --red-400: #E24B4A; --red-50: #FCEBEB; --red-800: #791F1F;
      --bg-base: #F1EFE8; --bg-surface: #ffffff; --bg-sunken: #F8F7F4;
      --border: rgba(0,0,0,0.08); --border-md: rgba(0,0,0,0.13);
      --text-primary: #1a1a1a; --text-secondary: #5F5E5A; --text-muted: #B4B2A9;
      --accent: #1D9E75; --accent-hover: #0F6E56;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: var(--font); font-size: 14px; line-height: 1.6; color: var(--text-primary); background: var(--bg-base); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 32px 16px; }
    .wrap { width: 100%; max-width: 480px; }
    .brand { text-align: center; margin-bottom: 28px; }
    .brand-icon { width: 52px; height: 52px; border-radius: 16px; background: var(--accent); display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; margin-bottom: 12px; }
    .brand-name { font-size: 22px; font-weight: 600; letter-spacing: -0.4px; }
    .brand-sub  { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
    .card { background: var(--bg-surface); border: 0.5px solid var(--border); border-radius: 16px; padding: 28px; }
    .card-title { font-size: 16px; font-weight: 600; margin-bottom: 4px; }
    .card-sub   { font-size: 13px; color: var(--text-secondary); margin-bottom: 24px; }
    .form-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 12px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px; }
    .input-wrap { display: flex; align-items: center; background: var(--bg-sunken); border: 0.5px solid var(--border-md); border-radius: 8px; overflow: hidden; transition: border-color 0.15s; }
    .input-wrap:focus-within { border-color: var(--accent); }
    .input-wrap > i { padding: 0 11px; font-size: 16px; color: var(--text-muted); flex-shrink: 0; }
    .input-wrap input, .input-wrap select { flex: 1; border: none; outline: none; background: transparent; padding: 10px 12px 10px 0; font-size: 13px; color: var(--text-primary); font-family: var(--font); appearance: none; }
    .input-wrap input::placeholder { color: var(--text-muted); }
    .input-wrap select { cursor: pointer; }
    .select-wrap { position: relative; }
    .select-wrap::after { content: '\ea77'; font-family: 'tabler-icons'; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-size: 14px; color: var(--text-muted); pointer-events: none; }
    .btn-submit { width: 100%; padding: 11px; background: var(--accent); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; font-family: var(--font); cursor: pointer; transition: background 0.15s; margin-top: 4px; }
    .btn-submit:hover { background: var(--accent-hover); }
    .alert { padding: 10px 14px; border-radius: 8px; font-size: 12px; margin-bottom: 16px; }
    .alert.error { background: var(--red-50); color: var(--red-800); }
    .field-error { font-size: 11px; color: var(--red-400); margin-top: 5px; }
    .footer-link { text-align: center; margin-top: 20px; padding-top: 16px; border-top: 0.5px solid var(--border); font-size: 13px; color: var(--text-secondary); }
    .footer-link a { color: var(--accent); text-decoration: none; font-weight: 500; }
    .footer-link a:hover { color: var(--accent-hover); }
    .section-sep { font-size: 11px; font-weight: 500; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin: 8px 0 16px; display: flex; align-items: center; gap: 8px; }
    .section-sep::before, .section-sep::after { content: ''; flex: 1; height: 0.5px; background: var(--border); }
    @media (max-width: 480px) { .form-row { grid-template-columns: 1fr; } }
  </style>
</head>
<body>

<div class="wrap">
  <div class="brand">
    <div class="brand-icon"><i class="ti ti-tool"></i></div>
    <div class="brand-name">Hirfa</div>
    <div class="brand-sub">Join the skills & crafts platform</div>
  </div>

  <div class="card">
    <div class="card-title">Create Account</div>
    <div class="card-sub">Fill in your details to get started</div>

    @if($errors->any())
      <div class="alert error">
        <i class="ti ti-alert-circle"></i> {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('user.register.post') }}">
      @csrf

      <div class="section-sep">Personal Information</div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">First Name</label>
          <div class="input-wrap">
            <i class="ti ti-user"></i>
            <input type="text" name="first_name" placeholder="John" value="{{ old('first_name') }}" required>
          </div>
          @error('first_name')<div class="field-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Last Name</label>
          <div class="input-wrap">
            <i class="ti ti-user"></i>
            <input type="text" name="last_name" placeholder="Doe" value="{{ old('last_name') }}" required>
          </div>
          @error('last_name')<div class="field-error">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Gender</label>
          <div class="input-wrap select-wrap">
            <i class="ti ti-gender-bigender"></i>
            <select name="gender" required>
              <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select</option>
              <option value="male"   {{ old('gender') === 'male'   ? 'selected' : '' }}>Male</option>
              <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
            </select>
          </div>
          @error('gender')<div class="field-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Date of Birth</label>
          <div class="input-wrap">
            <i class="ti ti-calendar"></i>
            <input type="date" name="birthdate" value="{{ old('birthdate') }}">
          </div>
          @error('birthdate')<div class="field-error">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">City</label>
        <div class="input-wrap">
          <i class="ti ti-map-pin"></i>
          <input type="text" name="city" placeholder="Damascus" value="{{ old('city') }}" required>
        </div>
        @error('city')<div class="field-error">{{ $message }}</div>@enderror
      </div>

      <div class="section-sep">Account Details</div>

      <div class="form-group">
        <label class="form-label">Phone Number</label>
        <div class="input-wrap">
          <i class="ti ti-phone"></i>
          <input type="text" name="phone" placeholder="09xxxxxxxx" value="{{ old('phone') }}" required>
        </div>
        @error('phone')<div class="field-error">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label class="form-label">Email Address</label>
        <div class="input-wrap">
          <i class="ti ti-mail"></i>
          <input type="email" name="email" placeholder="example@email.com" value="{{ old('email') }}" required>
        </div>
        @error('email')<div class="field-error">{{ $message }}</div>@enderror
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Password</label>
          <div class="input-wrap">
            <i class="ti ti-lock"></i>
            <input type="password" name="password" placeholder="••••••••" required minlength="8">
          </div>
          @error('password')<div class="field-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Confirm Password</label>
          <div class="input-wrap">
            <i class="ti ti-lock-check"></i>
            <input type="password" name="password_confirmation" placeholder="••••••••" required>
          </div>
        </div>
      </div>

      <button type="submit" class="btn-submit">Create Account</button>
    </form>

    <div class="footer-link">
      Already have an account? <a href="{{ route('user.login') }}">Sign in</a>
    </div>
  </div>
</div>

</body>
</html>
