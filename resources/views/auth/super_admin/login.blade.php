<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Super Admin — Hirfa</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font: 'Inter', sans-serif;
      --green-400: #1D9E75; --green-600: #0F6E56; --green-800: #085041;
      --red-400: #E24B4A; --red-50: #FCEBEB; --red-800: #791F1F;
      --bg-base: #0d1f18; --bg-surface: #132318; --bg-sunken: #0a1a13;
      --border: rgba(255,255,255,0.07); --border-md: rgba(255,255,255,0.12);
      --text-primary: #e6f2ee; --text-secondary: #9FE1CB; --text-muted: #4d8c74;
      --accent: #1D9E75; --accent-hover: #5DCAA5;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: var(--font); font-size: 14px; line-height: 1.6; color: var(--text-primary); background: var(--bg-base); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .wrap { width: 100%; max-width: 400px; padding: 24px; }
    .brand { text-align: center; margin-bottom: 28px; }
    .brand-icon { width: 54px; height: 54px; border-radius: 16px; background: linear-gradient(135deg, var(--accent), var(--green-800)); display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 26px; margin-bottom: 12px; box-shadow: 0 0 28px rgba(29,158,117,.35); }
    .brand-name { font-size: 22px; font-weight: 600; letter-spacing: -0.4px; }
    .brand-badge { display: inline-flex; align-items: center; gap: 5px; background: rgba(29,158,117,.15); border: 0.5px solid rgba(29,158,117,.3); color: var(--accent); font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px; margin-top: 6px; letter-spacing: 0.5px; text-transform: uppercase; }
    .card { background: var(--bg-surface); border: 0.5px solid var(--border); border-radius: 16px; padding: 28px; box-shadow: 0 8px 32px rgba(0,0,0,.4); }
    .card-title { font-size: 16px; font-weight: 600; margin-bottom: 4px; }
    .card-sub   { font-size: 13px; color: var(--text-secondary); margin-bottom: 24px; }
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 12px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px; }
    .input-wrap { display: flex; align-items: center; background: var(--bg-sunken); border: 0.5px solid var(--border-md); border-radius: 8px; overflow: hidden; transition: border-color 0.15s; }
    .input-wrap:focus-within { border-color: var(--accent); }
    .input-wrap > i { padding: 0 11px; font-size: 16px; color: var(--text-muted); flex-shrink: 0; }
    .input-wrap input { flex: 1; border: none; outline: none; background: transparent; padding: 10px 12px 10px 0; font-size: 13px; color: var(--text-primary); font-family: var(--font); }
    .input-wrap input::placeholder { color: var(--text-muted); }
    .btn-submit { width: 100%; padding: 11px; background: var(--accent); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; font-family: var(--font); cursor: pointer; transition: background 0.15s; margin-top: 4px; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-submit:hover { background: var(--accent-hover); }
    .alert { padding: 10px 14px; border-radius: 8px; font-size: 12px; margin-bottom: 16px; }
    .alert.error { background: var(--red-50); color: var(--red-800); }
    .field-error { font-size: 11px; color: var(--red-400); margin-top: 5px; }
    .divider { text-align: center; margin-top: 20px; padding-top: 16px; border-top: 0.5px solid var(--border); font-size: 12px; color: var(--text-muted); }
    .divider a { color: var(--accent); text-decoration: none; }
    .divider a:hover { color: var(--accent-hover); }
  </style>
</head>
<body>

<div class="wrap">
  <div class="brand">
    <div class="brand-icon"><i class="ti ti-shield-check"></i></div>
    <div class="brand-name">Hirfa</div>
    <div class="brand-badge"><i class="ti ti-crown" style="font-size:10px;"></i> Super Admin</div>
  </div>

  <div class="card">
    <div class="card-title">Sign In</div>
    <div class="card-sub">Super Admin control panel</div>

    @if(session('error'))
      <div class="alert error"><i class="ti ti-alert-circle"></i> {{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('super_admin.login.post') }}">
      @csrf

      <div class="form-group">
        <label class="form-label">Email Address</label>
        <div class="input-wrap">
          <i class="ti ti-mail"></i>
          <input type="email" name="email" placeholder="super@hirfa.com" value="{{ old('email') }}" autofocus required>
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

      <button type="submit" class="btn-submit">
        <i class="ti ti-login"></i> Sign In
      </button>
    </form>

    <div class="divider">
      Admin panel? <a href="{{ route('admin.login') }}">Click here</a>
    </div>
  </div>
</div>

</body>
</html>
