<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Dashboard') — Hirfa Super Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root {
      --font: 'Inter', sans-serif;
      --green-50:  #E1F5EE;
      --green-100: #9FE1CB;
      --green-400: #1D9E75;
      --green-600: #0F6E56;
      --green-800: #085041;
      --red-400:   #E24B4A;
      --red-50:    #FCEBEB;
      --red-800:   #791F1F;
      --sidebar-w: 240px;
      --header-h:  56px;
      --radius-sm: 6px;
      --radius-md: 10px;
      --radius-lg: 14px;

      --bg-base:       #080f0b;
      --bg-surface:    #0e1a14;
      --bg-sunken:     #0a1510;
      --bg-hover:      #111f17;
      --border:        rgba(255,255,255,0.06);
      --border-md:     rgba(255,255,255,0.10);
      --text-primary:  #e6f2ee;
      --text-secondary:#9FE1CB;
      --text-muted:    #4d8c74;
      --sidebar-bg:    #020c07;
      --sidebar-text:  rgba(255,255,255,0.88);
      --sidebar-dim:   rgba(255,255,255,0.38);
      --sidebar-rule:  rgba(255,255,255,0.06);
      --sidebar-active:rgba(29,158,117,0.18);
      --sidebar-hover: rgba(255,255,255,0.04);
      --accent:        #1D9E75;
      --accent-hover:  #5DCAA5;
      --accent-bg:     #04342C;
      --accent-txt:    #9FE1CB;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; }
    body { font-family: var(--font); font-size: 14px; line-height: 1.6; color: var(--text-primary); background: var(--bg-base); }
    a { text-decoration: none; color: inherit; }
    button { font-family: var(--font); cursor: pointer; }

    .shell {
      display: grid;
      grid-template-columns: var(--sidebar-w) 1fr;
      grid-template-rows: var(--header-h) 1fr;
      grid-template-areas: "sidebar header" "sidebar main";
      min-height: 100vh;
    }

    /* SIDEBAR */
    .sidebar {
      grid-area: sidebar;
      background: var(--sidebar-bg);
      display: flex; flex-direction: column;
      position: sticky; top: 0; height: 100vh;
      overflow-y: auto; scrollbar-width: none; z-index: 100;
    }
    .sidebar::-webkit-scrollbar { display: none; }
    .sidebar-brand {
      padding: 18px 20px 16px;
      border-bottom: 0.5px solid var(--sidebar-rule);
      display: flex; align-items: center; gap: 10px; flex-shrink: 0;
    }
    .brand-icon {
      width: 32px; height: 32px; border-radius: 8px;
      background: linear-gradient(135deg, var(--accent), var(--green-800));
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 16px; flex-shrink: 0;
      box-shadow: 0 0 12px rgba(29,158,117,.4);
    }
    .brand-name { font-size: 16px; font-weight: 600; color: #fff; line-height: 1.1; }
    .brand-badge {
      display: inline-flex; align-items: center; gap: 3px;
      font-size: 9px; letter-spacing: 1px; text-transform: uppercase;
      background: rgba(29,158,117,.2); color: var(--accent);
      padding: 2px 7px; border-radius: 20px; margin-top: 2px;
    }
    .sidebar-nav {
      flex: 1; padding: 12px 10px;
      display: flex; flex-direction: column; gap: 2px;
    }
    .nav-label-group {
      font-size: 10px; letter-spacing: 1.5px; color: var(--sidebar-dim);
      text-transform: uppercase; padding: 10px 10px 4px; font-weight: 500;
    }
    .nav-sep { height: 0.5px; background: var(--sidebar-rule); margin: 4px 10px; }
    .nav-item {
      display: flex; align-items: center; gap: 9px;
      padding: 9px 10px; border-radius: var(--radius-sm);
      color: var(--sidebar-text); font-size: 13px;
      border: none; background: none; width: 100%;
      text-align: right; transition: background 0.12s; cursor: pointer;
    }
    .nav-item:hover  { background: var(--sidebar-hover); }
    .nav-item.active { background: var(--sidebar-active); color: var(--accent-hover); }
    .nav-item > i    { font-size: 17px; flex-shrink: 0; }
    .nav-item .lbl   { flex: 1; }
    .sidebar-footer {
      padding: 10px 12px 14px;
      border-top: 0.5px solid var(--sidebar-rule); flex-shrink: 0;
    }
    .admin-row {
      display: flex; align-items: center; gap: 9px;
      padding: 8px 10px; border-radius: var(--radius-sm);
    }
    .admin-avatar {
      width: 30px; height: 30px; border-radius: 50%;
      background: linear-gradient(135deg, var(--accent), var(--green-800));
      display: flex; align-items: center; justify-content: center;
      font-size: 12px; font-weight: 600; color: #fff; flex-shrink: 0;
    }
    .admin-name { font-size: 12px; font-weight: 500; color: var(--sidebar-text); }
    .admin-role { font-size: 10px; color: var(--accent); }

    /* HEADER */
    .header {
      grid-area: header; height: var(--header-h);
      background: var(--bg-surface);
      border-bottom: 0.5px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 24px; position: sticky; top: 0; z-index: 90; gap: 16px;
    }
    .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--text-secondary); }
    .breadcrumb-sep { color: var(--text-muted); }
    .breadcrumb .current { color: var(--text-primary); font-weight: 500; }
    .header-actions { display: flex; align-items: center; gap: 8px; }
    .icon-btn {
      width: 34px; height: 34px;
      display: flex; align-items: center; justify-content: center;
      border-radius: 8px; border: 0.5px solid var(--border);
      background: var(--bg-sunken); color: var(--text-secondary);
      font-size: 17px; transition: background 0.12s;
    }
    .icon-btn:hover { background: var(--bg-hover); color: var(--text-primary); }

    /* MAIN */
    .main { grid-area: main; padding: 24px; display: flex; flex-direction: column; gap: 20px; }
    .page-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; }
    .page-title { font-size: 20px; font-weight: 600; letter-spacing: -0.3px; }
    .page-sub   { font-size: 13px; color: var(--text-secondary); margin-top: 2px; }
    .btn-primary {
      display: inline-flex; align-items: center; gap: 7px;
      background: var(--accent); color: #fff;
      border: none; border-radius: var(--radius-md);
      padding: 9px 18px; font-size: 13px; font-weight: 500;
      transition: background 0.15s;
    }
    .btn-primary:hover { background: var(--accent-hover); }
    .btn-danger {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--red-50); color: var(--red-800);
      border: none; border-radius: var(--radius-md);
      padding: 6px 12px; font-size: 12px; font-weight: 500;
      transition: background 0.12s;
    }
    .btn-danger:hover { background: #f9d5d5; }

    /* CARD */
    .card {
      background: var(--bg-surface);
      border: 0.5px solid var(--border);
      border-radius: var(--radius-lg); overflow: hidden;
    }
    .card-head {
      display: flex; align-items: center; justify-content: space-between;
      padding: 14px 20px; border-bottom: 0.5px solid var(--border);
    }
    .card-title { font-size: 14px; font-weight: 600; }

    /* STATS */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; }
    .stat-card {
      background: var(--bg-surface);
      border: 0.5px solid var(--border);
      border-radius: var(--radius-lg); padding: 16px 18px;
    }
    .stat-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
    .stat-icon { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 17px; }
    .stat-icon.green { background: rgba(29,158,117,.15); color: var(--accent); }
    .stat-icon.blue  { background: rgba(59,130,246,.15);  color: #60a5fa; }
    .stat-icon.amber { background: rgba(239,159,39,.15);  color: #fac775; }
    .stat-value { font-size: 26px; font-weight: 600; letter-spacing: -0.5px; line-height: 1; margin-bottom: 4px; }
    .stat-label { font-size: 12px; color: var(--text-secondary); }

    /* TABLE */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th {
      text-align: right; padding: 10px 20px;
      font-size: 11px; letter-spacing: 0.5px; color: var(--text-muted);
      font-weight: 500; text-transform: uppercase;
      border-bottom: 0.5px solid var(--border); background: var(--bg-sunken);
    }
    .data-table td { padding: 11px 20px; font-size: 13px; border-bottom: 0.5px solid var(--border); }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: var(--bg-sunken); }
    .cell-user { display: flex; align-items: center; gap: 9px; }
    .avatar { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; color: #fff; flex-shrink: 0; }
    .cell-name  { font-weight: 500; font-size: 13px; }
    .cell-email { font-size: 11px; color: var(--text-muted); }
    .badge { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 500; padding: 3px 9px; border-radius: 20px; }
    .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; opacity: 0.7; }
    .badge.active { background: rgba(29,158,117,.15); color: var(--accent-hover); }
    .badge.admin  { background: rgba(59,130,246,.15); color: #60a5fa; }

    /* ALERTS */
    .alert { padding: 12px 16px; border-radius: var(--radius-md); margin-bottom: 16px; font-size: 13px; }
    .alert.success { background: rgba(29,158,117,.12); color: var(--accent-hover); border: 0.5px solid rgba(29,158,117,.2); }
    .alert.error   { background: var(--red-50); color: var(--red-800); }

    @media (max-width: 720px) {
      .shell { grid-template-columns: 1fr; grid-template-areas: "header" "main"; }
      .sidebar { display: none; }
    }

    @yield('styles')
  </style>
</head>
<body>
<div class="shell">

  {{-- SIDEBAR --}}
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="brand-icon"><i class="ti ti-shield-check"></i></div>
      <div>
        <div class="brand-name">Hirfa</div>
        <div class="brand-badge"><i class="ti ti-crown" style="font-size:8px;"></i> Super Admin</div>
      </div>
    </div>

    <nav class="sidebar-nav">
      <span class="nav-label-group">نظرة عامة</span>
      <a href="{{ route('super_admin.dashboard') }}"
         class="nav-item {{ request()->routeIs('super_admin.dashboard') ? 'active' : '' }}">
        <i class="ti ti-layout-dashboard"></i><span class="lbl">لوحة التحكم</span>
      </a>

      <div class="nav-sep"></div>
      <span class="nav-label-group">إدارة</span>

      <a href="{{ route('super_admin.admins.index') }}"
         class="nav-item {{ request()->routeIs('super_admin.admins.*') ? 'active' : '' }}">
        <i class="ti ti-user-shield"></i><span class="lbl">المديرون</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="admin-row">
        <div class="admin-avatar">
          {{ strtoupper(substr(Auth::guard('super_admins')->user()->first_name ?? 'S', 0, 1)) }}
        </div>
        <div>
          <div class="admin-name">
            {{ Auth::guard('super_admins')->user()->first_name ?? 'Super' }}
            {{ Auth::guard('super_admins')->user()->last_name ?? '' }}
          </div>
          <div class="admin-role">Super Admin</div>
        </div>
      </div>
      <form method="POST" action="{{ route('super_admin.logout') }}" style="margin-top:6px;">
        @csrf
        <button type="submit" class="nav-item" style="color:rgba(255,255,255,0.45);">
          <i class="ti ti-logout"></i><span class="lbl">تسجيل الخروج</span>
        </button>
      </form>
    </div>
  </aside>

  {{-- HEADER --}}
  <header class="header">
    <nav class="breadcrumb">
      <i class="ti ti-home" style="font-size:14px;"></i>
      <span class="breadcrumb-sep">/</span>
      <span class="current">@yield('breadcrumb', 'Dashboard')</span>
    </nav>
    <div class="header-actions">
      <a href="{{ route('admin.login') }}" class="icon-btn" title="لوحة الأدمن">
        <i class="ti ti-external-link"></i>
      </a>
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

</div>
@yield('scripts')
</body>
</html>
