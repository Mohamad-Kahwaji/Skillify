<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In — Hirfa</title>
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
    body { font-family: var(--font); font-size: 14px; line-height: 1.6; color: var(--text-primary); background: var(--bg-base); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .wrap { width: 100%; max-width: 400px; padding: 24px; }
    .brand { text-align: center; margin-bottom: 28px; }
    .brand-icon { width: 52px; height: 52px; border-radius: 16px; background: var(--accent); display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; margin-bottom: 12px; }
    .brand-name { font-size: 22px; font-weight: 600; letter-spacing: -0.4px; }
    .brand-sub  { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
    .card { background: var(--bg-surface); border: 0.5px solid var(--border); border-radius: 16px; padding: 28px; }
    .card-title { font-size: 16px; font-weight: 600; margin-bottom: 4px; }
    .card-sub   { font-size: 13px; color: var(--text-secondary); margin-bottom: 24px; }
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 12px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px; }
    .input-wrap { display: flex; align-items: center; background: var(--bg-sunken); border: 0.5px solid var(--border-md); border-radius: 8px; overflow: hidden; transition: border-color 0.15s; }
    .input-wrap:focus-within { border-color: var(--accent); }
    .input-wrap > i { padding: 0 11px; font-size: 16px; color: var(--text-muted); flex-shrink: 0; }
    .input-wrap input { flex: 1; border: none; outline: none; background: transparent; padding: 10px 12px 10px 0; font-size: 13px; color: var(--text-primary); font-family: var(--font); }
    .input-wrap input::placeholder { color: var(--text-muted); }
    .btn-submit { width: 100%; padding: 11px; background: var(--accent); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; font-family: var(--font); cursor: pointer; transition: background 0.15s; margin-top: 4px; }
    .btn-submit:hover { background: var(--accent-hover); }
    .alert { padding: 10px 14px; border-radius: 8px; font-size: 12px; margin-bottom: 16px; }
    .alert.error   { background: var(--red-50); color: var(--red-800); }
    .field-error   { font-size: 11px; color: var(--red-400); margin-top: 5px; }
    .footer-link { text-align: center; margin-top: 20px; padding-top: 16px; border-top: 0.5px solid var(--border); font-size: 13px; color: var(--text-secondary); }
    .footer-link a { color: var(--accent); text-decoration: none; font-weight: 500; }
    .footer-link a:hover { color: var(--accent-hover); }
  </style>
</head>
<body>

<div class="wrap">
  <div class="brand">
    <div class="brand-icon"><i class="ti ti-tool"></i></div>
    <div class="brand-name">Hirfa</div>
    <div class="brand-sub">Welcome back</div>
  </div>

  <div class="card">
    <div class="card-title">Sign In</div>
    <div class="card-sub">Enter your phone number and password</div>

    @if(session('error'))
      <div class="alert error"><i class="ti ti-alert-circle"></i> {{ session('error') }}</div>
    @endif
    @error('account')
      <div class="alert error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('user.login.post') }}">
      @csrf

      <div class="form-group">
        <label class="form-label">Phone Number</label>
        <div class="input-wrap">
          <i class="ti ti-phone"></i>
          <input type="tel" name="phone" placeholder="09xxxxxxxx"
                 value="{{ old('phone') }}" autofocus required>
        </div>
        @error('phone')<div class="field-error">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>
        <div class="input-wrap">
          <i class="ti ti-lock"></i>
          <input type="password" name="password" placeholder="••••••••" required>
        </div>
        @error('password')<div class="field-error">{{ $message }}</div>@enderror
      </div>

      <button type="submit" class="btn-submit">Sign In</button>
    </form>

    <div class="footer-link">
      Don't have an account? <a href="{{ route('user.register') }}">Create one</a>
    </div>
  </div>
</div>

</body>
</html>
