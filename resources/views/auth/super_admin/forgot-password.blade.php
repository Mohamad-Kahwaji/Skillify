<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password — Hirfa Super Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root{--font:'Inter',sans-serif;--accent:#1D9E75;--accent-hover:#5DCAA5;--red-400:#E24B4A;--red-50:#FCEBEB;--red-800:#791F1F;--bg-base:#0d1f18;--bg-surface:#132318;--bg-sunken:#0a1a13;--border:rgba(255,255,255,.07);--border-md:rgba(255,255,255,.12);--text-primary:#e6f2ee;--text-secondary:#9FE1CB;--text-muted:#4d8c74;}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:var(--font);font-size:14px;line-height:1.6;color:var(--text-primary);background:var(--bg-base);min-height:100vh;display:flex;align-items:center;justify-content:center;}
    .wrap{width:100%;max-width:400px;padding:24px;}
    .brand{text-align:center;margin-bottom:28px;}
    .brand-icon{width:54px;height:54px;border-radius:16px;background:linear-gradient(135deg,var(--accent),#085041);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:26px;margin-bottom:12px;box-shadow:0 0 28px rgba(29,158,117,.35);}
    .brand-name{font-size:22px;font-weight:600;}
    .brand-badge{display:inline-flex;align-items:center;gap:5px;background:rgba(29,158,117,.15);border:0.5px solid rgba(29,158,117,.3);color:var(--accent);font-size:11px;font-weight:500;padding:3px 10px;border-radius:20px;margin-top:6px;letter-spacing:.5px;text-transform:uppercase;}
    .card{background:var(--bg-surface);border:0.5px solid var(--border);border-radius:16px;padding:28px;box-shadow:0 8px 32px rgba(0,0,0,.4);}
    .card-title{font-size:16px;font-weight:600;margin-bottom:4px;}
    .card-sub{font-size:13px;color:var(--text-secondary);margin-bottom:24px;}
    .form-group{margin-bottom:16px;}
    .form-label{display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;}
    .input-wrap{display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;transition:border-color .15s;}
    .input-wrap:focus-within{border-color:var(--accent);}
    .input-wrap>i{padding:0 11px;font-size:16px;color:var(--text-muted);flex-shrink:0;}
    .input-wrap input{flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);}
    .btn-submit{width:100%;padding:11px;background:var(--accent);color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:500;font-family:var(--font);cursor:pointer;transition:background .15s;margin-top:4px;}
    .btn-submit:hover{background:var(--accent-hover);}
    .alert{padding:10px 14px;border-radius:8px;font-size:12px;margin-bottom:16px;}
    .alert.error{background:var(--red-50);color:var(--red-800);}
    .alert.success{background:rgba(29,158,117,.12);color:var(--accent-hover);}
    .field-error{font-size:11px;color:var(--red-400);margin-top:5px;}
    .back-link{text-align:center;margin-top:16px;font-size:13px;color:var(--text-secondary);}
    .back-link a{color:var(--accent);text-decoration:none;}
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
    <div class="card-title">Forgot Password?</div>
    <div class="card-sub">Enter your email to receive a reset link</div>

    @if(session('status'))
      <div class="alert success"><i class="ti ti-check"></i> {{ session('status') }}</div>
    @endif
    @if($errors->any())
      <div class="alert error"><i class="ti ti-alert-circle"></i> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('super_admin.forgot-password.send') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Email Address</label>
        <div class="input-wrap">
          <i class="ti ti-mail"></i>
          <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="super@hirfa.com">
        </div>
        @error('email')<div class="field-error">{{ $message }}</div>@enderror
      </div>
      <button type="submit" class="btn-submit">Send Reset Link</button>
    </form>
    <div class="back-link"><a href="{{ route('super_admin.login') }}">← Back to Sign In</a></div>
  </div>
</div>
</body>
</html>
