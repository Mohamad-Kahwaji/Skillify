<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login — Hirfa</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font: 'Inter', sans-serif;
      --green-50:  #E1F5EE;
      --green-400: #1D9E75;
      --green-600: #0F6E56;
      --green-800: #085041;
      --green-900: #04342C;
      --red-400:   #E24B4A;
      --red-50:    #FCEBEB;
      --red-800:   #791F1F;
    }
    [data-theme="light"] {
      --bg-base:       #F1EFE8;
      --bg-surface:    #ffffff;
      --bg-sunken:     #F8F7F4;
      --border:        rgba(0,0,0,0.08);
      --border-md:     rgba(0,0,0,0.13);
      --text-primary:  #1a1a1a;
      --text-secondary:#5F5E5A;
      --text-muted:    #B4B2A9;
      --accent:        #1D9E75;
      --accent-hover:  #0F6E56;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: var(--font); font-size: 14px; line-height: 1.6;
      color: var(--text-primary); background: var(--bg-base);
      min-height: 100vh; display: flex; align-items: center; justify-content: center;
    }
    .login-wrap {
      width: 100%; max-width: 400px; padding: 24px;
    }
    .login-brand {
      text-align: center; margin-bottom: 28px;
    }
    .brand-icon {
      width: 48px; height: 48px; border-radius: 14px;
      background: var(--accent);
      display: inline-flex; align-items: center; justify-content: center;
      color: #fff; font-size: 22px; margin-bottom: 12px;
    }
    .brand-name { font-size: 22px; font-weight: 600; letter-spacing: -0.4px; }
    .brand-sub  { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
    .login-card {
      background: var(--bg-surface);
      border: 0.5px solid var(--border);
      border-radius: 14px; padding: 28px;
    }
    .login-title { font-size: 16px; font-weight: 600; margin-bottom: 4px; }
    .login-sub   { font-size: 13px; color: var(--text-secondary); margin-bottom: 24px; }
    .form-group  { margin-bottom: 16px; }
    .form-label  { display: block; font-size: 12px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px; }
    .input-wrap  {
      display: flex; align-items: center;
      background: var(--bg-sunken);
      border: 0.5px solid var(--border-md);
      border-radius: 8px; overflow: hidden;
      transition: border-color 0.15s;
    }
    .input-wrap:focus-within { border-color: var(--accent); }
    .input-wrap > i { padding: 0 11px; font-size: 16px; color: var(--text-muted); flex-shrink: 0; }
    .input-wrap input {
      flex: 1; border: none; outline: none;
      background: transparent; padding: 10px 12px 10px 0;
      font-size: 13px; color: var(--text-primary);
      font-family: var(--font);
    }
    .input-wrap input::placeholder { color: var(--text-muted); }
    .btn-submit {
      width: 100%; padding: 11px;
      background: var(--accent); color: #fff;
      border: none; border-radius: 8px;
      font-size: 14px; font-weight: 500;
      font-family: var(--font); cursor: pointer;
      transition: background 0.15s; margin-top: 4px;
    }
    .btn-submit:hover { background: var(--accent-hover); }
    .alert {
      padding: 10px 14px; border-radius: 8px;
      font-size: 12px; margin-bottom: 16px;
    }
    .alert.error { background: var(--red-50); color: var(--red-800); }
    .field-error { font-size: 11px; color: var(--red-400); margin-top: 5px; }
  </style>
</head>
<body>

<div class="login-wrap">
  <div class="login-brand">
    <div class="brand-icon"><i class="ti ti-tool"></i></div>
    <div class="brand-name">Hirfa</div>
    <div class="brand-sub">Admin Panel</div>
  </div>

  <div class="login-card">
    <div class="login-title">Sign in</div>
    <div class="login-sub">Enter your admin credentials to continue</div>

    @if(session('error'))
      <div class="alert error"><i class="ti ti-alert-circle"></i> {{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}">
      @csrf

      <div class="form-group">
        <label class="form-label">Email</label>
        <div class="input-wrap">
          <i class="ti ti-mail"></i>
          <input type="email" name="email" placeholder="admin@hirfa.com"
                 value="{{ old('email') }}" autofocus required>
        </div>
        @error('email')<div class="field-error">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>
        <div class="input-wrap">
          <i class="ti ti-lock"></i>
          <input type="password" name="password" placeholder="••••••••" required>
        </div>
        @error('password')<div class="field-error">{{ $message }}</div>@enderror
      </div>

      <button type="submit" class="btn-submit">Sign in</button>
    </form>
  </div>
</div>

</body>
</html>
