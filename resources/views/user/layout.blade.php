<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'حسابي') — Hirfa</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root {
      --font: 'Inter', sans-serif;
      --green-50:  #E1F5EE;
      --green-400: #1D9E75;
      --green-600: #0F6E56;
      --green-800: #085041;
      --red-400:   #E24B4A;
      --red-50:    #FCEBEB;
      --red-800:   #791F1F;
      --header-h:  60px;
      --radius-sm: 6px;
      --radius-md: 10px;
      --radius-lg: 14px;
      --bg-base:       #F1EFE8;
      --bg-surface:    #ffffff;
      --bg-sunken:     #F8F7F4;
      --bg-hover:      #F0EEE8;
      --border:        rgba(0,0,0,0.08);
      --border-md:     rgba(0,0,0,0.13);
      --text-primary:  #1a1a1a;
      --text-secondary:#5F5E5A;
      --text-muted:    #B4B2A9;
      --accent:        #1D9E75;
      --accent-hover:  #0F6E56;
      --accent-bg:     #E1F5EE;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; }
    body { font-family: var(--font); font-size: 14px; line-height: 1.6; color: var(--text-primary); background: var(--bg-base); }
    a { text-decoration: none; color: inherit; }
    button { font-family: var(--font); cursor: pointer; }

    /* HEADER */
    .header {
      height: var(--header-h);
      background: var(--bg-surface);
      border-bottom: 0.5px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 24px; position: sticky; top: 0; z-index: 90;
    }
    .header-brand { display: flex; align-items: center; gap: 10px; }
    .brand-icon {
      width: 34px; height: 34px; border-radius: 10px;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 18px;
    }
    .brand-name { font-size: 16px; font-weight: 600; letter-spacing: -0.3px; }
    .header-nav { display: flex; align-items: center; gap: 4px; }
    .nav-link {
      display: flex; align-items: center; gap: 6px;
      padding: 7px 12px; border-radius: var(--radius-sm);
      font-size: 13px; color: var(--text-secondary);
      transition: background 0.12s, color 0.12s;
    }
    .nav-link:hover  { background: var(--bg-hover); color: var(--text-primary); }
    .nav-link.active { background: var(--accent-bg); color: var(--green-800); font-weight: 500; }
    .nav-link > i    { font-size: 16px; }
    .header-user { display: flex; align-items: center; gap: 12px; }
    .user-avatar {
      width: 34px; height: 34px; border-radius: 50%;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; font-weight: 600; color: #fff;
    }
    .user-name  { font-size: 13px; font-weight: 500; }
    .user-email { font-size: 11px; color: var(--text-muted); }
    .logout-btn {
      display: flex; align-items: center; gap: 6px;
      padding: 7px 12px; border-radius: var(--radius-sm);
      font-size: 13px; color: var(--text-secondary);
      background: none; border: none;
      transition: background 0.12s, color 0.12s;
    }
    .logout-btn:hover { background: var(--red-50); color: var(--red-800); }

    /* MAIN */
    .main { max-width: 960px; margin: 0 auto; padding: 28px 24px; display: flex; flex-direction: column; gap: 20px; }
    .page-title { font-size: 20px; font-weight: 600; letter-spacing: -0.3px; }
    .page-sub   { font-size: 13px; color: var(--text-secondary); margin-top: 2px; }

    /* CARD */
    .card { background: var(--bg-surface); border: 0.5px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; }
    .card-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 0.5px solid var(--border); }
    .card-title { font-size: 14px; font-weight: 600; }
    .card-body  { padding: 20px; }

    /* BADGE */
    .badge { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 500; padding: 3px 9px; border-radius: 20px; }
    .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; opacity: 0.7; }
    .badge.active  { background: var(--green-50); color: var(--green-800); }
    .badge.pending { background: #FAEEDA; color: #633806; }

    /* ALERTS */
    .alert { padding: 12px 16px; border-radius: var(--radius-md); margin-bottom: 16px; font-size: 13px; }
    .alert.success { background: var(--green-50); color: var(--green-800); border: 0.5px solid #9FE1CB; }
    .alert.error   { background: var(--red-50); color: var(--red-800); }

    /* INFO ROW */
    .info-row { display: flex; align-items: center; gap: 8px; padding: 10px 0; border-bottom: 0.5px solid var(--border); font-size: 13px; }
    .info-row:last-child { border-bottom: none; }
    .info-label { width: 140px; color: var(--text-secondary); flex-shrink: 0; }
    .info-value { color: var(--text-primary); font-weight: 500; }

    @media (max-width: 640px) {
      .header-nav { display: none; }
      .user-name, .user-email { display: none; }
    }

    @yield('styles')
  </style>
</head>
<body>

{{-- HEADER --}}
<header class="header">
  <div class="header-brand">
    <div class="brand-icon"><i class="ti ti-tool"></i></div>
    <div class="brand-name">Hirfa</div>
  </div>

  <nav class="header-nav">
    <a href="{{ route('user.dashboard') }}"
       class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
      <i class="ti ti-home"></i> الرئيسية
    </a>
    <a href="{{ route('user.profile') }}"
       class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
      <i class="ti ti-user"></i> ملفي الشخصي
    </a>
  </nav>

  <div class="header-user">
    <div class="user-avatar">
      {{ strtoupper(substr(Auth::guard('users')->user()->first_name ?? 'U', 0, 1)) }}
    </div>
    <div>
      <div class="user-name">{{ Auth::guard('users')->user()->first_name ?? '' }} {{ Auth::guard('users')->user()->last_name ?? '' }}</div>
      <div class="user-email">{{ Auth::guard('users')->user()->email ?? '' }}</div>
    </div>
    <form method="POST" action="{{ route('user.logout') }}">
      @csrf
      <button type="submit" class="logout-btn">
        <i class="ti ti-logout"></i>
      </button>
    </form>
  </div>
</header>

{{-- MAIN --}}
<main class="main">
  @if(session('success'))
    <div class="alert success"><i class="ti ti-check"></i> {{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert error"><i class="ti ti-alert-circle"></i> {{ session('error') }}</div>
  @endif

  @yield('content')
</main>

@yield('scripts')
</body>
</html>
