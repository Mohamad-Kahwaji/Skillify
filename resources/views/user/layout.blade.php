<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'My Account') — Skillify</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root {
      --font: 'Cairo','Inter', sans-serif;
      /* Brand */
      --accent:        #0D9488;
      --accent-hover:  #0F766E;
      --accent-bg:     #F0FDFA;
      --teal-900:    #134E4A;
      /* Backgrounds — neutral, not crypto-blue */
      --bg-base:       #F8FAFC;
      --bg-surface:    #ffffff;
      --bg-sunken:     #F1F5F9;
      --bg-hover:      #E2E8F0;
      /* Borders */
      --border:        rgba(0,0,0,0.07);
      --border-md:     rgba(0,0,0,0.12);
      /* Text */
      --text-primary:  #0F172A;
      --text-secondary:#475569;
      --text-muted:    #94A3B8;
      /* Semantic status colors */
      --green-50:      #F0FDF4;
      --green-700:     #15803D;
      --amber-50:      #FFFBEB;
      --amber-700:     #B45309;
      --red-50:        #FEF2F2;
      --red-400:       #F87171;
      --red-700:       #B91C1C;
      /* Radii */
      --radius-sm: 6px;
      --radius-md: 10px;
      --radius-lg: 14px;
      --header-h:  60px;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; }
    body { font-family: var(--font); font-size: 14px; line-height: 1.6; color: var(--text-primary); background: var(--bg-base); }
    a { text-decoration: none; color: inherit; }
    button { font-family: var(--font); cursor: pointer; }

    /* ── HEADER ── */
    --header-h: 64px;
    .header {
      height: var(--header-h);
      background: #fff;
      border-bottom: 1px solid rgba(0,0,0,0.06);
      box-shadow: 0 1px 12px rgba(0,0,0,0.05);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 28px; position: sticky; top: 0; z-index: 90;
      gap: 16px;
    }

    /* Brand */
    .header-brand { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .brand-icon {
      width: 38px; height: 38px; border-radius: 11px;
      background: linear-gradient(135deg, var(--accent), #0F766E);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 20px;
      box-shadow: 0 3px 10px rgba(13,148,136,0.30);
    }
    .brand-name {
      font-size: 18px; font-weight: 800; color: #0F172A;
      letter-spacing: -0.4px; font-family: 'Cairo',sans-serif;
    }

    /* Nav */
    .header-nav { display: flex; align-items: center; gap: 2px; flex: 1; justify-content: center; }
    .nav-link {
      display: flex; align-items: center; gap: 6px;
      padding: 7px 13px; border-radius: 10px;
      font-size: 13px; font-weight: 600; color: #64748B;
      transition: all 0.15s; white-space: nowrap; position: relative;
    }
    .nav-link i { font-size: 16px; }
    .nav-link:hover { background: #F1F5F9; color: #0F172A; }
    .nav-link.active {
      background: linear-gradient(135deg,rgba(13,148,136,0.12),rgba(13,148,136,0.07));
      color: var(--accent);
      font-weight: 700;
    }
    .nav-link.active::after {
      content: '';
      position: absolute; bottom: -3px; right: 50%; left: 50%;
      height: 2px; background: var(--accent); border-radius: 2px;
      transform: translateX(50%);
      width: 24px; margin-right: -12px;
    }

    /* User section */
    .header-user { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .user-avatar {
      width: 38px; height: 38px; border-radius: 50%;
      background: linear-gradient(135deg, var(--accent), #0F766E);
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; font-weight: 800; color: #fff;
      box-shadow: 0 3px 10px rgba(13,148,136,0.25);
      flex-shrink: 0;
    }
    .user-info { line-height: 1.25; }
    .user-name  { font-size: 13px; font-weight: 700; color: #0F172A; }
    .user-email { font-size: 11px; color: #94A3B8; }
    .logout-btn {
      display: flex; align-items: center; justify-content: center;
      width: 34px; height: 34px; border-radius: 9px;
      color: #94A3B8; background: none; border: 1px solid rgba(0,0,0,0.08);
      transition: all 0.15s; font-size: 16px;
    }
    .logout-btn:hover { background: #FEF2F2; color: #DC2626; border-color: #FECACA; }

    /* Divider */
    .nav-divider {
      width: 1px; height: 20px; background: rgba(0,0,0,0.08); margin: 0 4px; flex-shrink: 0;
    }

    /* MAIN */
    .main { max-width: 1100px; margin: 0 auto; padding: 28px 24px; display: flex; flex-direction: column; gap: 20px; }
    .page-title { font-size: 20px; font-weight: 600; letter-spacing: -0.3px; }
    .page-sub   { font-size: 13px; color: var(--text-secondary); margin-top: 2px; }

    /* CARD */
    .card { background: var(--bg-surface); border: 0.5px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; }
    .card-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 0.5px solid var(--border); }
    .card-title { font-size: 14px; font-weight: 600; }
    .card-body  { padding: 20px; }

    /* BADGE */
    .badge { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 500; padding: 3px 9px; border-radius: 20px; }
    .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; opacity: 0.8; }
    .badge.active   { background: var(--green-50);  color: var(--green-700); }
    .badge.approved { background: var(--green-50);  color: var(--green-700); }
    .badge.pending  { background: var(--amber-50);  color: var(--amber-700); }
    .badge.inactive { background: var(--red-50);    color: var(--red-700); }
    .badge.rejected { background: var(--red-50);    color: var(--red-700); }

    /* ALERTS */
    .alert { padding: 12px 16px; border-radius: var(--radius-md); margin-bottom: 16px; font-size: 13px; border: 0.5px solid transparent; }
    .alert.success { background: var(--green-50);  color: var(--green-700); border-color: #BBF7D0; }
    .alert.error   { background: var(--red-50);    color: var(--red-700);   border-color: #FECACA; }
    .alert.warning { background: var(--amber-50);  color: var(--amber-700); border-color: #FDE68A; }

    /* BUTTONS */
    .btn {
      display: inline-flex; align-items: center; gap: 7px;
      padding: 9px 18px; border-radius: var(--radius-md);
      font-size: 13px; font-weight: 600; cursor: pointer;
      border: none; transition: background 0.15s, box-shadow 0.15s, transform 0.1s;
    }
    .btn:active { transform: translateY(1px); }
    .btn-primary {
      background: var(--accent); color: #fff;
      box-shadow: 0 1px 3px rgba(13,148,136,.3);
    }
    .btn-primary:hover {
      background: var(--accent-hover);
      box-shadow: 0 4px 12px rgba(79,70,229,.35);
    }
    .btn-outline {
      background: transparent; color: var(--accent);
      border: 1.5px solid var(--accent);
    }
    .btn-outline:hover { background: var(--accent-bg); }
    .btn-ghost {
      background: transparent; color: var(--text-secondary);
      border: 1px solid var(--border-md);
    }
    .btn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); }

    /* INFO ROW */
    .info-row { display: flex; align-items: center; gap: 8px; padding: 10px 0; border-bottom: 0.5px solid var(--border); font-size: 13px; }
    .info-row:last-child { border-bottom: none; }
    .info-label { width: 140px; color: var(--text-secondary); flex-shrink: 0; }
    .info-value { color: var(--text-primary); font-weight: 500; }

    /* NOTIF BADGE on nav link */
    .nav-link { position: relative; }
    .notif-badge {
      position: absolute; top: 2px; right: 2px;
      min-width: 17px; height: 17px;
      background: var(--red-400); color: #fff;
      border-radius: 20px; font-size: 10px; font-weight: 700;
      display: none; align-items: center; justify-content: center;
      padding: 0 4px; border: 2px solid var(--bg-surface);
      line-height: 1;
    }
    .notif-badge.visible { display: inline-flex; }

    /* TOAST CONTAINER */
    .toast-stack {
      position: fixed; bottom: 24px; left: 24px;
      z-index: 9999;
      display: flex; flex-direction: column; gap: 10px;
      pointer-events: none;
    }
    .toast {
      display: flex; align-items: center; gap: 12px;
      background: var(--bg-surface);
      border: 0.5px solid var(--border-md);
      border-right: 3px solid var(--accent);
      border-radius: 12px;
      padding: 12px 14px 12px 12px;
      box-shadow: 0 8px 24px rgba(0,0,0,.12);
      min-width: 280px; max-width: 340px;
      pointer-events: all;
      cursor: pointer;
      animation: toastIn 0.3s cubic-bezier(.34,1.56,.64,1) both;
      transition: opacity 0.25s, transform 0.25s;
    }
    .toast.hiding {
      opacity: 0;
      transform: translateX(-16px);
    }
    @keyframes toastIn {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .toast-avatar {
      width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; font-weight: 600; color: #fff;
    }
    .toast-body { flex: 1; min-width: 0; }
    .toast-title { font-size: 13px; font-weight: 600; margin-bottom: 2px; }
    .toast-msg {
      font-size: 12px; color: var(--text-secondary);
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .toast-close {
      background: none; border: none; font-size: 16px;
      color: var(--text-muted); padding: 2px; flex-shrink: 0;
      border-radius: 4px; transition: color 0.12s;
    }
    .toast-close:hover { color: var(--text-primary); }

    @media (max-width: 640px) {
      .header-nav { display: none; }
      .user-name, .user-email { display: none; }
      .toast-stack { left: 12px; right: 12px; bottom: 16px; }
      .toast { min-width: unset; }
    }

  </style>
  @yield('styles')
</head>
<body>

{{-- HEADER --}}
@php
  $authUser = Auth::guard('users')->user();
  $initials = strtoupper(substr($authUser->first_name ?? 'U', 0, 1) . substr($authUser->last_name ?? '', 0, 1));
@endphp
<header class="header">

  {{-- Brand --}}
  <a href="{{ route('user.dashboard') }}" class="header-brand" style="text-decoration:none;">
    <div class="brand-icon"><i class="ti ti-school"></i></div>
    <div class="brand-name">Skillify</div>
  </a>

  {{-- Nav --}}
  <nav class="header-nav">
    <a href="{{ route('user.dashboard') }}"
       class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
      <i class="ti ti-home"></i> الرئيسية
    </a>
    <a href="{{ route('user.explore') }}"
       class="nav-link {{ request()->routeIs('user.explore') ? 'active' : '' }}">
      <i class="ti ti-compass"></i> استكشاف
    </a>
    <a href="{{ route('user.services') }}"
       class="nav-link {{ request()->routeIs('user.services') ? 'active' : '' }}">
      <i class="ti ti-briefcase"></i> الخدمات
    </a>
    <a href="{{ route('user.my-services.list') }}"
       class="nav-link {{ request()->routeIs('user.my-services.*') ? 'active' : '' }}">
      <i class="ti ti-list-check"></i> خدماتي
    </a>

    <div class="nav-divider"></div>

    <a href="{{ route('user.posts') }}"
       class="nav-link {{ request()->routeIs('user.posts') ? 'active' : '' }}">
      <i class="ti ti-pencil"></i> منشوراتي
    </a>
    <a href="{{ route('user.all-posts') }}"
       class="nav-link {{ request()->routeIs('user.all-posts') ? 'active' : '' }}">
      <i class="ti ti-layout-list"></i> المنشورات
    </a>
    <a href="{{ route('user.community-posts') }}"
       class="nav-link {{ request()->routeIs('user.community-posts') ? 'active' : '' }}">
      <i class="ti ti-users"></i> المجتمع
    </a>

    <div class="nav-divider"></div>

    <a href="{{ route('user.ads') }}"
       class="nav-link {{ request()->routeIs('user.ads') ? 'active' : '' }}">
      <i class="ti ti-speakerphone"></i> الإعلانات
    </a>
    <a href="{{ route('user.profile') }}"
       class="nav-link {{ request()->routeIs('user.profile') || request()->routeIs('user.identity.*') ? 'active' : '' }}">
      <i class="ti ti-user-circle"></i> ملفي
    </a>
    <a id="nav-messages-link" href="{{ route('user.conversations') }}"
       class="nav-link {{ request()->routeIs('user.conversations') || request()->routeIs('user.chat') ? 'active' : '' }}"
       style="position:relative;">
      <i class="ti ti-message-circle"></i> الرسائل
      <span class="notif-badge" id="notif-badge"></span>
    </a>
  </nav>

  {{-- User section --}}
  <div class="header-user">
    <x-notifications />
    <div class="nav-divider"></div>
    <div class="user-avatar">{{ $initials }}</div>
    <div class="user-info">
      <div class="user-name">{{ $authUser->first_name ?? '' }} {{ $authUser->last_name ?? '' }}</div>
      <div class="user-email">{{ $authUser->email ?? '' }}</div>
    </div>
    <form method="POST" action="{{ route('user.logout') }}">
      @csrf
      <button type="submit" class="logout-btn" title="تسجيل الخروج">
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

{{-- Toast container --}}
<div class="toast-stack" id="toast-stack"></div>

@vite(['resources/js/app.js'])

@stack('scripts')

<script>
(function () {
  const ME_ID       = {{ Auth::guard('users')->id() }};
  const CSRF        = document.querySelector('meta[name="csrf-token"]').content;
  const UNREAD_URL  = '{{ route("user.messages.unread") }}';
  const AVATAR_COLORS = ['#0D9488','#0891B2','#3B82F6','#0891B2','#059669','#D97706','#DC2626'];

  const badge      = document.getElementById('notif-badge');
  const toastStack = document.getElementById('toast-stack');
  const msgLink    = document.getElementById('nav-messages-link');

  // ── Load unread count ──────────────────────────────────
  async function loadUnread() {
    try {
      const r = await fetch(UNREAD_URL, {
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
      });
      const { count } = await r.json();
      setbadge(count);
    } catch (_) {}
  }

  function setbadge(n) {
    if (n > 0) {
      badge.textContent = n > 99 ? '99+' : n;
      badge.classList.add('visible');
    } else {
      badge.classList.remove('visible');
    }
  }
  // alias
  function setbadgeAlias(n) { setbadge(n); }
  window.__setNotifBadge = setbadge;

  loadUnread();

  // ── Toast helper ───────────────────────────────────────
  function showToast({ senderName, preview, conversationId, senderId }) {
    const color  = AVATAR_COLORS[senderId % AVATAR_COLORS.length];
    const initial = senderName ? senderName.charAt(0).toUpperCase() : '?';

    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `
      <div class="toast-avatar" style="background:${color};">${initial}</div>
      <div class="toast-body">
        <div class="toast-title">${escHtml(senderName)}</div>
        <div class="toast-msg">${escHtml(preview || 'New message')}</div>
      </div>
      <button class="toast-close" aria-label="Close"><i class="ti ti-x"></i></button>
    `;

    // Click → go to chat
    toast.addEventListener('click', (e) => {
      if (!e.target.closest('.toast-close')) {
        window.location.href = '/user/chat/' + conversationId;
      }
    });

    // Close button
    toast.querySelector('.toast-close').addEventListener('click', () => dismissToast(toast));

    toastStack.appendChild(toast);

    // Auto-dismiss after 5 s
    const timer = setTimeout(() => dismissToast(toast), 5000);
    toast._timer = timer;
  }

  function dismissToast(toast) {
    clearTimeout(toast._timer);
    toast.classList.add('hiding');
    toast.addEventListener('transitionend', () => toast.remove(), { once: true });
  }

  function escHtml(str) {
    return String(str ?? '')
      .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  }

  // ── Echo listener ──────────────────────────────────────
  if (window.Echo) {
    window.Echo.private(`notifications.${ME_ID}`)
      .listen('.new.message', (data) => {
        if (!data) return;

        // Update badge
        const cur = parseInt(badge.textContent || '0', 10) || 0;
        setbadge(cur + 1);

        // Update messages link href to this conversation
        if (data.conversation_id) {
          msgLink.href = '/user/chat/' + data.conversation_id;
        }

        showToast({
          senderName:     data.sender_name || 'User',
          preview:        data.message_text
                            ? data.message_text.substring(0, 60)
                            : (data.file_name ? '📎 ' + data.file_name : 'New message'),
          conversationId: data.conversation_id,
          senderId:       data.user_id || 0,
        });
      });
  }
})();
</script>

@yield('scripts')
</body>
</html>
