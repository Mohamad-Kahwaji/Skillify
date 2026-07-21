<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Dashboard') — Skillify Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root {
      --font: 'Inter', sans-serif;
      --teal-50:  #F0FDFA;
      --teal-100: #C7D2FE;
      --teal-500: #0D9488;
      --teal-700: #0F766E;
      --teal-900: #134E4A;
      --indigo-900: #134E4A;
      --coral-50:  #FAECE7;
      --coral-400: #D85A30;
      --amber-400: #EF9F27;
      --red-400:   #E24B4A;
      --red-50:    #FCEBEB;
      --red-800:   #791F1F;
      --sidebar-w: 240px;
      --header-h:  56px;
      --radius-sm: 6px;
      --radius-md: 10px;
      --radius-lg: 14px;
    }
    [data-theme="light"] {
      --bg-base:       #F8FAFB;
      --bg-surface:    #ffffff;
      --bg-sunken:     #F0FDFA;
      --bg-hover:      #CCFBF1;
      --border:        rgba(0,0,0,0.08);
      --border-md:     rgba(0,0,0,0.13);
      --text-primary:  #1a1a1a;
      --text-secondary:#5F5E5A;
      --text-muted:    #B4B2A9;
      --sidebar-bg:    #134E4A;
      --sidebar-text:  rgba(255,255,255,0.85);
      --sidebar-dim:   rgba(255,255,255,0.45);
      --sidebar-rule:  rgba(255,255,255,0.08);
      --sidebar-active:rgba(255,255,255,0.12);
      --sidebar-hover: rgba(255,255,255,0.07);
      --accent:        #0D9488;
      --accent-hover:  #0F766E;
      --accent-bg:     #F0FDFA;
      --accent-txt:    #134E4A;
    }
    [data-theme="dark"] {
      --bg-base:       #0D1F1E;
      --bg-surface:    #0D2B27;
      --bg-sunken:     #0D2B27;
      --bg-hover:      #0D2B27;
      --border:        rgba(255,255,255,0.07);
      --border-md:     rgba(255,255,255,0.12);
      --text-primary:  #e8e8ff;
      --text-secondary:#a5b4fc;
      --text-muted:    #0D9488;
      --sidebar-bg:    #0D1F1E;
      --sidebar-text:  rgba(255,255,255,0.90);
      --sidebar-dim:   rgba(255,255,255,0.38);
      --sidebar-rule:  rgba(255,255,255,0.07);
      --sidebar-active:rgba(255,255,255,0.10);
      --sidebar-hover: rgba(255,255,255,0.05);
      --accent:        #0D9488;
      --accent-hover:  #818cf8;
      --accent-bg:     #134E4A;
      --accent-txt:    #a5b4fc;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; }
    body {
      font-family: var(--font);
      font-size: 14px;
      line-height: 1.6;
      color: var(--text-primary);
      background: var(--bg-base);
      transition: background 0.2s, color 0.2s;
    }
    a { text-decoration: none; color: inherit; }
    button { font-family: var(--font); cursor: pointer; }

    /* SHELL */
    .shell {
      display: grid;
      grid-template-columns: var(--sidebar-w) 1fr;
      grid-template-rows: var(--header-h) 1fr auto;
      grid-template-areas: "sidebar header" "sidebar main" "sidebar footer";
      min-height: 100vh;
    }

    /* SIDEBAR */
    .sidebar {
      grid-area: sidebar;
      background: var(--sidebar-bg);
      display: flex; flex-direction: column;
      position: sticky; top: 0;
      height: 100vh; overflow-y: auto;
      scrollbar-width: none;
      transition: background 0.2s;
      z-index: 100;
    }
    .sidebar::-webkit-scrollbar { display: none; }
    .sidebar-brand {
      padding: 18px 20px 16px;
      border-bottom: 0.5px solid var(--sidebar-rule);
      display: flex; align-items: center; gap: 10px; flex-shrink: 0;
    }
    .brand-icon {
      width: 32px; height: 32px; border-radius: 8px;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 16px; flex-shrink: 0;
    }
    .brand-name { font-size: 16px; font-weight: 600; color: #fff; line-height: 1.1; letter-spacing: -0.3px; }
    .brand-sub  { font-size: 10px; letter-spacing: 1.5px; color: var(--sidebar-dim); text-transform: uppercase; }
    .sidebar-nav {
      flex: 1; padding: 12px 10px;
      display: flex; flex-direction: column; gap: 2px;
      overflow-y: auto; scrollbar-width: none;
    }
    .sidebar-nav::-webkit-scrollbar { display: none; }
    .nav-label-group {
      font-size: 10px; letter-spacing: 1.5px;
      color: var(--sidebar-dim); text-transform: uppercase;
      padding: 10px 10px 4px; font-weight: 500;
    }
    .nav-sep { height: 0.5px; background: var(--sidebar-rule); margin: 4px 10px; }
    .nav-item {
      display: flex; align-items: center; gap: 9px;
      padding: 9px 10px; border-radius: var(--radius-sm);
      color: var(--sidebar-text); font-size: 13px;
      border: none; background: none; width: 100%;
      text-align: left; transition: background 0.12s; cursor: pointer;
    }
    .nav-item:hover  { background: var(--sidebar-hover); }
    .nav-item.active { background: var(--sidebar-active); color: #fff; box-shadow: inset 3px 0 0 var(--accent); }
    .nav-item > i    { font-size: 17px; flex-shrink: 0; }
    .nav-item .lbl   { flex: 1; }
    .nav-badge {
      font-size: 10px; font-weight: 600;
      padding: 1px 7px; border-radius: 20px;
      background: rgba(255,255,255,0.10); color: var(--sidebar-text);
    }
    .nav-badge.warn   { background: rgba(239,159,39,0.22); color: #FAC775; }
    .nav-badge.danger { background: rgba(226,75,74,0.22);  color: #F09595; }
    .sidebar-footer {
      padding: 10px 12px 14px;
      border-top: 0.5px solid var(--sidebar-rule); flex-shrink: 0;
    }
    .admin-row {
      display: flex; align-items: center; gap: 9px;
      padding: 8px 10px; border-radius: var(--radius-sm);
      transition: background 0.12s; cursor: pointer;
    }
    .admin-row:hover { background: var(--sidebar-hover); }
    .admin-avatar {
      width: 30px; height: 30px; border-radius: 50%;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      font-size: 12px; font-weight: 600; color: #fff; flex-shrink: 0;
    }
    .admin-name { font-size: 12px; font-weight: 500; color: var(--sidebar-text); }
    .admin-role { font-size: 10px; color: var(--sidebar-dim); }
    .admin-more { font-size: 14px; color: var(--sidebar-dim); margin-right: auto; }

    /* HEADER */
    .header {
      grid-area: header; height: var(--header-h);
      background: var(--bg-surface);
      border-bottom: 0.5px solid var(--border);
      display: flex; align-items: center;
      justify-content: space-between;
      padding: 0 24px;
      position: sticky; top: 0; z-index: 90;
      transition: background 0.2s, border-color 0.2s;
      gap: 16px;
    }
    .breadcrumb {
      display: flex; align-items: center; gap: 6px;
      font-size: 13px; color: var(--text-secondary);
    }
    .breadcrumb-sep { color: var(--text-muted); font-size: 12px; }
    .breadcrumb .current { color: var(--text-primary); font-weight: 500; }
    .search-wrap {
      display: flex; align-items: center;
      background: var(--bg-sunken);
      border: 0.5px solid var(--border);
      border-radius: 8px; overflow: hidden;
      transition: border-color 0.15s;
    }
    .search-wrap:focus-within { border-color: var(--accent); }
    .search-wrap > i { padding: 0 10px; font-size: 15px; color: var(--text-muted); }
    .search-wrap input {
      border: none; outline: none; background: transparent;
      padding: 7px 10px 7px 0; font-size: 13px;
      color: var(--text-primary); width: 190px;
      font-family: var(--font);
    }
    .search-wrap input::placeholder { color: var(--text-muted); }
    .header-actions { display: flex; align-items: center; gap: 8px; }
    .icon-btn {
      width: 34px; height: 34px;
      display: flex; align-items: center; justify-content: center;
      border-radius: 8px;
      border: 0.5px solid var(--border);
      background: var(--bg-sunken);
      color: var(--text-secondary);
      font-size: 17px; position: relative;
      transition: background 0.12s, border-color 0.12s, color 0.12s;
    }
    .icon-btn:hover { background: var(--bg-hover); border-color: var(--border-md); color: var(--text-primary); }
    .notif-dot {
      position: absolute; top: 5px; right: 5px;
      width: 7px; height: 7px; border-radius: 50%;
      background: var(--red-400);
      border: 1.5px solid var(--bg-surface);
    }
    .mode-pill {
      display: flex; align-items: center; gap: 6px;
      background: var(--bg-sunken);
      border: 0.5px solid var(--border);
      border-radius: 8px; padding: 0 12px; height: 34px;
      font-size: 12px; color: var(--text-secondary);
      transition: border-color 0.12s, color 0.12s;
    }
    .mode-pill:hover { border-color: var(--accent); color: var(--accent); }
    .mode-pill > i { font-size: 15px; }

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
      transition: background 0.15s; white-space: nowrap;
    }
    .btn-primary:hover { background: var(--accent-hover); }
    .btn-primary > i  { font-size: 16px; }
    .btn-ghost {
      display: inline-flex; align-items: center; gap: 6px;
      background: transparent;
      border: 0.5px solid var(--border-md);
      border-radius: var(--radius-md);
      padding: 8px 14px; font-size: 13px;
      color: var(--text-secondary);
      transition: background 0.12s, border-color 0.12s;
    }
    .btn-ghost:hover { background: var(--bg-hover); border-color: var(--accent); color: var(--accent); }
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
      transition: background 0.2s, border-color 0.2s;
    }
    .card-head {
      display: flex; align-items: center;
      justify-content: space-between;
      padding: 14px 20px;
      border-bottom: 0.5px solid var(--border);
    }
    .card-title { font-size: 14px; font-weight: 600; }
    .card-action { font-size: 12px; color: var(--accent); background: none; border: none; font-family: var(--font); }
    .card-action:hover { color: var(--accent-hover); }

    /* TABLE */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th {
      text-align: left; padding: 10px 20px;
      font-size: 11px; letter-spacing: 0.5px;
      color: var(--text-muted); font-weight: 500;
      text-transform: uppercase;
      border-bottom: 0.5px solid var(--border);
      background: var(--bg-sunken);
    }
    .data-table td { padding: 11px 20px; font-size: 13px; border-bottom: 0.5px solid var(--border); }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: var(--bg-sunken); }
    .cell-user { display: flex; align-items: center; gap: 9px; }
    .avatar {
      width: 30px; height: 30px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 600; color: #fff; flex-shrink: 0;
    }
    .cell-name  { font-weight: 500; font-size: 13px; }
    .cell-email { font-size: 11px; color: var(--text-muted); }

    /* BADGES */
    .badge {
      display: inline-flex; align-items: center; gap: 4px;
      font-size: 11px; font-weight: 500;
      padding: 3px 9px; border-radius: 20px;
    }
    .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; opacity: 0.7; }
    .badge.active   { background: var(--teal-50);  color: var(--teal-900); }
    .badge.pending  { background: #FAEEDA;           color: #633806; }
    .badge.blocked  { background: var(--red-50);     color: var(--red-800); }
    .badge.rejected { background: var(--red-50);     color: var(--red-800); }
    .badge.inactive { background: var(--bg-sunken);  color: var(--text-muted); }

    /* STATS */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; }
    .stat-card {
      background: var(--bg-surface);
      border: 0.5px solid var(--border);
      border-radius: var(--radius-lg); padding: 16px 18px;
      transition: background 0.2s, border-color 0.2s;
    }
    .stat-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
    .stat-icon { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 17px; }
    .stat-icon.green { background: var(--teal-50);  color: var(--teal-500); }
    .stat-icon.coral { background: var(--coral-50);  color: var(--coral-400); }
    .stat-icon.amber { background: #FAEEDA; color: #BA7517; }
    .stat-icon.red   { background: var(--red-50);    color: var(--red-400); }
    .trend { font-size: 11px; padding: 2px 7px; border-radius: 20px; font-weight: 500; }
    .trend.up   { background: var(--teal-50); color: var(--teal-900); }
    .trend.down { background: var(--red-50);   color: var(--red-800); }
    .stat-value { font-size: 26px; font-weight: 600; letter-spacing: -0.5px; line-height: 1; margin-bottom: 4px; }
    .stat-label { font-size: 12px; color: var(--text-secondary); }

    /* CONTENT GRID */
    .content-grid { display: grid; grid-template-columns: 1fr 310px; gap: 16px; align-items: start; }
    .panel-right { display: flex; flex-direction: column; gap: 16px; }
    .panel-list  { padding: 0; }
    .panel-item {
      display: flex; align-items: center; gap: 10px;
      padding: 11px 18px;
      border-bottom: 0.5px solid var(--border);
      transition: background 0.1s; cursor: pointer;
    }
    .panel-item:last-child { border-bottom: none; }
    .panel-item:hover { background: var(--bg-sunken); }
    .panel-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
    .panel-info  { flex: 1; min-width: 0; }
    .panel-name  { font-size: 13px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .panel-meta  { font-size: 11px; color: var(--text-muted); }
    .panel-time  { font-size: 11px; color: var(--text-muted); flex-shrink: 0; }

    /* FOOTER */
    .footer {
      grid-area: footer;
      background: var(--bg-surface);
      border-top: 0.5px solid var(--border);
      padding: 14px 24px;
      display: flex; align-items: center;
      justify-content: space-between; gap: 16px;
      transition: background 0.2s, border-color 0.2s;
    }
    .footer-left  { display: flex; align-items: center; gap: 16px; }
    .footer-brand { font-size: 13px; font-weight: 600; color: var(--accent); }
    .footer-copy  { font-size: 12px; color: var(--text-muted); }
    .footer-links { display: flex; gap: 16px; }
    .footer-links a { font-size: 12px; color: var(--text-muted); transition: color 0.12s; }
    .footer-links a:hover { color: var(--accent); }
    .footer-right { display: flex; align-items: center; gap: 7px; }
    .status-dot   { width: 7px; height: 7px; border-radius: 50%; background: var(--accent); }
    .status-lbl   { font-size: 12px; color: var(--text-muted); }

    /* ALERTS */
    .alert {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 12px 16px; border-radius: var(--radius-md); margin-bottom: 16px; font-size: 13px;
    }
    .alert > i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .alert.success { background: var(--teal-50); color: var(--teal-900); border: 0.5px solid var(--teal-100); }
    .alert.error   { background: var(--red-50);   color: var(--red-800);   border: 0.5px solid #fcc; }

    /* EMPTY STATE */
    .empty-state { padding: 40px; text-align: center; color: var(--text-muted); }
    .empty-state i { font-size: 36px; margin-bottom: 10px; display: block; }

    /* TABLE TOOLBAR */
    .table-toolbar {
      display: flex; align-items: center; gap: 10px;
      padding: 12px 20px; border-bottom: 0.5px solid var(--border);
      flex-wrap: wrap;
    }
    .search-field {
      display: flex; align-items: center;
      background: var(--bg-sunken); border: 0.5px solid var(--border);
      border-radius: 8px; overflow: hidden; width: 220px; flex-shrink: 0;
      transition: border-color 0.15s;
    }
    .search-field:focus-within { border-color: var(--accent); }
    .search-field > i { padding: 0 10px; font-size: 15px; color: var(--text-muted); flex-shrink: 0; }
    .search-field input {
      border: none; outline: none; background: transparent;
      padding: 7px 8px 7px 0; font-size: 13px;
      color: var(--text-primary); width: 100%; font-family: var(--font);
    }
    .search-field input::placeholder { color: var(--text-muted); }
    .filter-chips { display: flex; gap: 5px; flex-wrap: wrap; }
    .chip {
      padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;
      border: 0.5px solid var(--border-md); background: transparent;
      color: var(--text-secondary); cursor: pointer; transition: all 0.12s; font-family: var(--font);
    }
    .chip.on { background: var(--accent-bg); color: var(--accent); border-color: var(--accent); }
    .chip:not(.on):hover { background: var(--bg-hover); color: var(--text-primary); }
    .tbl-count { font-size: 12px; color: var(--text-muted); white-space: nowrap; padding: 0 4px; margin-right: auto; }

    /* MODAL */
    .modal-overlay {
      display:none; position:fixed; inset:0; z-index:300;
      background:rgba(0,0,0,.55); backdrop-filter:blur(4px);
      align-items:center; justify-content:center; padding:20px;
    }
    .modal-overlay.open { display:flex; }
    .modal-box {
      background:var(--bg-surface); border:0.5px solid var(--border-md);
      border-radius:16px; width:100%; max-width:560px;
      overflow:hidden; animation:mfadeUp .2s ease;
      max-height:90vh; display:flex; flex-direction:column;
    }
    @keyframes mfadeUp {
      from { opacity:0; transform:translateY(24px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .modal-head {
      display:flex; align-items:center; justify-content:space-between;
      padding:16px 20px; border-bottom:0.5px solid var(--border); flex-shrink:0;
    }
    .modal-title { font-size:15px; font-weight:600; display:flex; align-items:center; gap:8px; }
    .modal-close {
      width:28px; height:28px; border-radius:8px;
      border:none; background:none; color:var(--text-secondary);
      font-size:17px; display:flex; align-items:center; justify-content:center;
      cursor:pointer; transition:background .12s; font-family:var(--font);
    }
    .modal-close:hover { background:var(--bg-hover); }
    .modal-body { padding:20px; overflow-y:auto; flex:1; display:flex; flex-direction:column; gap:14px; }
    .modal-foot {
      padding:14px 20px; border-top:0.5px solid var(--border);
      display:flex; justify-content:flex-end; gap:8px; flex-shrink:0;
    }
    .form-label { display:block; font-size:12px; font-weight:500; color:var(--text-secondary); margin-bottom:6px; }
    .form-field {
      display:flex; align-items:center;
      background:var(--bg-sunken); border:0.5px solid var(--border-md);
      border-radius:8px; overflow:hidden; transition:border-color .15s;
    }
    .form-field:focus-within { border-color:var(--accent); }
    .form-field > i { padding:0 11px; font-size:16px; color:var(--text-muted); flex-shrink:0; }
    .form-field input, .form-field select, .form-field textarea {
      flex:1; border:none; outline:none; background:transparent;
      padding:10px 12px 10px 0; font-size:13px; color:var(--text-primary);
      font-family:var(--font); resize:none;
    }
    .form-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }

    /* RESPONSIVE */
    @media (max-width: 960px) { .content-grid { grid-template-columns: 1fr; } }
    @media (max-width: 720px) {
      .shell { grid-template-columns: 1fr; grid-template-areas: "header" "main" "footer"; }
      .sidebar { display: none; }
      .search-wrap { display: none; }
      .footer-links { display: none; }
    }

    @yield('styles')
  </style>
</head>
<body>

<div class="shell">

  {{-- SIDEBAR --}}
  <aside class="sidebar">
    <div class="sidebar-brand" style="flex-direction:column; align-items:flex-start; gap:6px;">
      <img src="/images/logo.png" alt="Skillify" style="height:30px;width:auto;" />
      <div class="brand-sub">Admin Panel</div>
    </div>

    <nav class="sidebar-nav">
      <span class="nav-label-group">Overview</span>

      <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="ti ti-layout-dashboard"></i>
        <span class="lbl">Dashboard</span>
      </a>

      @canany(['businesses.view','verifications.view','users.view','blocked.view','services.view'])
      <div class="nav-sep"></div>
      <span class="nav-label-group">Management</span>

      @can('businesses.view')
      <a href="{{ route('admin.workers.index') }}" class="nav-item {{ request()->routeIs('admin.workers.*') ? 'active' : '' }}">
        <i class="ti ti-tools"></i>
        <span class="lbl">Service Providers</span>
      </a>
      @endcan

      @can('verifications.view')
      <a href="{{ route('admin.verifications.index') }}" class="nav-item {{ request()->routeIs('admin.verifications.*') ? 'active' : '' }}">
        <i class="ti ti-id-badge"></i>
        <span class="lbl">Business Requests</span>
      </a>
      @endcan

      @can('users.view')
      <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="ti ti-user"></i>
        <span class="lbl">Users</span>
      </a>
      @endcan

      @can('blocked.view')
      <a href="{{ route('admin.blocked.index') }}" class="nav-item {{ request()->routeIs('admin.blocked.*') ? 'active' : '' }}">
        <i class="ti ti-ban"></i>
        <span class="lbl">Blocked</span>
      </a>
      @endcan

      @can('services.view')
      <a href="{{ route('admin.services.index') }}" class="nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
        <i class="ti ti-list-check"></i>
        <span class="lbl">Services</span>
      </a>
      @endcan
      @endcanany

      @canany(['posts.view_all','reports.view','ads.view'])
      <div class="nav-sep"></div>
      <span class="nav-label-group">Content</span>

      @can('posts.view_all')
      <a href="{{ route('admin.posts.index') }}" class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
        <i class="ti ti-file-text"></i>
        <span class="lbl">Posts</span>
      </a>
      @endcan

      @can('reports.view')
      <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="ti ti-flag"></i>
        <span class="lbl">Reports</span>
      </a>
      @endcan

      @can('ads.view')
      <a href="{{ route('admin.ads.index') }}" class="nav-item {{ request()->routeIs('admin.ads.*') ? 'active' : '' }}">
        <i class="ti ti-speakerphone"></i>
        <span class="lbl">Ads</span>
      </a>
      @endcan
      @endcanany

      @canany(['active_type_businesses.view','active_types.view','categories.view','subcategories.view','cities.view'])
      <div class="nav-sep"></div>
      <span class="nav-label-group">Reference Data</span>

      @can('active_type_businesses.view')
      <a href="{{ route('admin.active_typebusinesses.index') }}"
         class="nav-item {{ request()->routeIs('admin.active_typebusinesses.*') ? 'active' : '' }}">
        <i class="ti ti-briefcase"></i>
        <span class="lbl">Business Types</span>
      </a>
      @endcan

      @can('active_types.view')
      <a href="{{ route('admin.active_types.index') }}"
         class="nav-item {{ request()->routeIs('admin.active_types.*') ? 'active' : '' }}">
        <i class="ti ti-tag"></i>
        <span class="lbl">Activity Types</span>
      </a>
      @endcan

      @can('categories.view')
      <a href="{{ route('admin.categories.index') }}"
         class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="ti ti-category"></i>
        <span class="lbl">Categories</span>
      </a>
      @endcan

      @can('subcategories.view')
      <a href="{{ route('admin.subcategories.index') }}"
         class="nav-item {{ request()->routeIs('admin.subcategories.*') ? 'active' : '' }}">
        <i class="ti ti-category-2"></i>
        <span class="lbl">Subcategories</span>
      </a>
      @endcan

      @can('cities.view')
      <a href="{{ route('admin.cities.index') }}"
         class="nav-item {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
        <i class="ti ti-map-pin"></i>
        <span class="lbl">Cities</span>
      </a>
      @endcan
      @endcanany

    </nav>

    <div class="sidebar-footer">
      <div class="admin-row">
        <div class="admin-avatar">{{ strtoupper(substr(Auth::guard('admins')->user()->first_name ?? 'A', 0, 1)) }}</div>
        <div style="flex:1;min-width:0;">
          <div class="admin-name">{{ Auth::guard('admins')->user()->first_name ?? 'Admin' }} {{ Auth::guard('admins')->user()->last_name ?? '' }}</div>
          <div class="admin-role">{{ Auth::guard('admins')->user()->role ?? 'Admin' }}</div>
        </div>
        <i class="ti ti-dots admin-more"></i>
      </div>
      <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:6px;">
        @csrf
        <button type="submit" class="nav-item" style="color:rgba(255,255,255,0.5);">
          <i class="ti ti-logout"></i>
          <span class="lbl">Logout</span>
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
      <div class="search-wrap">
        <i class="ti ti-search"></i>
        <input type="text" placeholder="Quick search..." />
      </div>
      <x-notifications />
      <button class="icon-btn" title="Messages">
        <i class="ti ti-message"></i>
      </button>
      <button class="mode-pill" onclick="toggleTheme()">
        <i class="ti ti-moon" id="mode-icon"></i>
        <span id="mode-txt">Dark</span>
      </button>
    </div>
  </header>

  {{-- MAIN --}}
  <main class="main">
    @if(session('success'))
      <div class="alert success">
        <i class="ti ti-circle-check"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif
    @if(session('error'))
      <div class="alert error">
        <i class="ti ti-alert-circle"></i>
        <span>{{ session('error') }}</span>
      </div>
    @endif

    @yield('content')
  </main>

  {{-- FOOTER --}}
  <footer class="footer">
    <div class="footer-left">
      <span class="footer-brand">Skillify</span>
      <span class="footer-copy">© {{ date('Y') }} Skillify Platform. All rights reserved.</span>
    </div>
    <div class="footer-links">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="#">Support</a>
    </div>
    <div class="footer-right">
      <div class="status-dot"></div>
      <span class="status-lbl">All systems operational</span>
    </div>
  </footer>

</div>

<script>
  var dark = localStorage.getItem('theme') === 'dark';
  if (dark) applyTheme(true);

  function applyTheme(d) {
    document.documentElement.setAttribute('data-theme', d ? 'dark' : 'light');
    var icon = document.getElementById('mode-icon');
    var txt  = document.getElementById('mode-txt');
    if (icon) icon.className = d ? 'ti ti-sun' : 'ti ti-moon';
    if (txt)  txt.textContent  = d ? 'Light' : 'Dark';
  }
  function toggleTheme() {
    dark = !dark;
    localStorage.setItem('theme', dark ? 'dark' : 'light');
    applyTheme(dark);
  }
</script>
@vite(['resources/js/app.js'])
@stack('scripts')
@yield('scripts')
</body>
</html>
