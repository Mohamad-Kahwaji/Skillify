@php
    if (auth('users')->check()) {
        $authId   = auth('users')->id();
        $channel  = "users.{$authId}.notifications";
        $prefix   = '/user';
    } elseif (auth('admins')->check()) {
        $authId   = auth('admins')->id();
        $channel  = "admins.{$authId}.notifications";
        $prefix   = '/admin';
    } elseif (auth('super_admins')->check()) {
        $authId   = auth('super_admins')->id();
        $channel  = "superadmins.{$authId}.notifications";
        $prefix   = '/super-admin';
    } else {
        $authId = null;
    }
@endphp

@if($authId)
<style>
  .nw { position: relative; display: inline-flex; }
  .nw-btn {
    position: relative;
    width: 34px; height: 34px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px;
    border: 0.5px solid var(--border, rgba(0,0,0,0.08));
    background: var(--bg-sunken, #F8F7F4);
    color: var(--text-secondary, #5F5E5A);
    font-size: 17px; cursor: pointer;
    transition: background 0.12s, color 0.12s;
  }
  .nw-btn:hover { background: var(--bg-hover, #F0EEE8); color: var(--text-primary, #1a1a1a); }
  .nw-badge {
    position: absolute; top: 4px; right: 4px;
    min-width: 16px; height: 16px;
    background: #E24B4A; color: #fff;
    border-radius: 20px; font-size: 9px; font-weight: 700;
    display: none; align-items: center; justify-content: center;
    padding: 0 3px; border: 1.5px solid var(--bg-surface, #fff);
    line-height: 1;
  }
  .nw-badge.on { display: inline-flex; }
  .nw-dropdown {
    display: none;
    position: absolute; top: calc(100% + 8px); right: 0;
    width: 320px;
    background: var(--bg-surface, #fff);
    border: 0.5px solid var(--border-md, rgba(0,0,0,0.13));
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,.12);
    z-index: 9999; overflow: hidden;
  }
  .nw-dropdown.open { display: block; }
  .nw-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 14px;
    border-bottom: 0.5px solid var(--border, rgba(0,0,0,0.08));
  }
  .nw-head-title { font-size: 13px; font-weight: 600; color: var(--text-primary, #1a1a1a); }
  .nw-read-all {
    font-size: 11px; font-weight: 500;
    color: var(--accent, #1D9E75);
    background: none; border: none; cursor: pointer; font-family: inherit;
  }
  .nw-read-all:hover { color: var(--accent-hover, #0F6E56); }
  .nw-list { max-height: 340px; overflow-y: auto; scrollbar-width: thin; }
  .nw-item {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 11px 14px;
    border-bottom: 0.5px solid var(--border, rgba(0,0,0,0.08));
    cursor: pointer; transition: background 0.1s;
  }
  .nw-item:last-child { border-bottom: none; }
  .nw-item:hover { background: var(--bg-sunken, #F8F7F4); }
  .nw-item.unread { background: var(--accent-bg, #E1F5EE); }
  .nw-item.unread:hover { background: #d4f0e6; }
  .nw-item-icon {
    width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
    background: var(--accent-bg, #E1F5EE); color: var(--accent, #1D9E75);
    display: flex; align-items: center; justify-content: center; font-size: 15px;
  }
  .nw-item.unread .nw-item-icon { background: var(--accent, #1D9E75); color: #fff; }
  .nw-item-body { flex: 1; min-width: 0; }
  .nw-item-title { font-size: 12px; font-weight: 600; color: var(--text-primary, #1a1a1a); margin-bottom: 2px; }
  .nw-item-msg {
    font-size: 12px; color: var(--text-secondary, #5F5E5A);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .nw-item-time { font-size: 10px; color: var(--text-muted, #B4B2A9); margin-top: 3px; }
  .nw-empty {
    padding: 32px 20px; text-align: center;
    font-size: 12px; color: var(--text-muted, #B4B2A9);
  }
  .nw-empty i { font-size: 28px; display: block; margin-bottom: 6px; }
</style>

<div class="nw" id="nw">
  <button class="nw-btn" id="nw-btn" type="button" aria-label="Notifications">
    <i class="ti ti-bell"></i>
    <span class="nw-badge" id="nw-badge"></span>
  </button>

  <div class="nw-dropdown" id="nw-dropdown">
    <div class="nw-head">
      <span class="nw-head-title">Notifications</span>
      <button class="nw-read-all" id="nw-read-all" type="button">Mark all read</button>
    </div>
    <div class="nw-list" id="nw-list">
      <div class="nw-empty"><i class="ti ti-loader"></i>Loading...</div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function () {
  const CSRF    = document.querySelector('meta[name="csrf-token"]').content;
  const PREFIX  = '{{ $prefix }}';
  const CHANNEL = '{{ $channel }}';

  const btnEl      = document.getElementById('nw-btn');
  const dropdown   = document.getElementById('nw-dropdown');
  const list       = document.getElementById('nw-list');
  const badge      = document.getElementById('nw-badge');
  const readAllBtn = document.getElementById('nw-read-all');

  let unread = 0;

  // ── Toggle dropdown ──────────────────────────────────────
  btnEl.addEventListener('click', (e) => {
    e.stopPropagation();
    dropdown.classList.toggle('open');
  });
  document.addEventListener('click', (e) => {
    if (!document.getElementById('nw').contains(e.target)) {
      dropdown.classList.remove('open');
    }
  });

  // ── Badge ────────────────────────────────────────────────
  function setBadge(n) {
    unread = Math.max(0, n);
    badge.textContent = unread > 99 ? '99+' : unread;
    badge.classList.toggle('on', unread > 0);
  }

  // ── Escape HTML ──────────────────────────────────────────
  function esc(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  }

  // ── Relative time ────────────────────────────────────────
  function ago(dateStr) {
    const s = Math.floor((Date.now() - new Date(dateStr)) / 1000);
    if (s < 60)    return 'just now';
    if (s < 3600)  return Math.floor(s / 60) + 'm ago';
    if (s < 86400) return Math.floor(s / 3600) + 'h ago';
    return Math.floor(s / 86400) + 'd ago';
  }

  // ── Build item element ───────────────────────────────────
  function makeItem(n) {
    const d  = n.data || n;
    const el = document.createElement('div');
    el.className = 'nw-item' + (n.read_at ? '' : ' unread');
    el.dataset.id = n.id || '';
    el.innerHTML = `
      <div class="nw-item-icon"><i class="ti ti-bell-ringing"></i></div>
      <div class="nw-item-body">
        <div class="nw-item-title">${esc(d.title || 'Notification')}</div>
        <div class="nw-item-msg">${esc(d.message || '')}</div>
        <div class="nw-item-time">${ago(n.created_at || new Date())}</div>
      </div>`;
    if (!n.read_at && n.id) {
      el.addEventListener('click', () => markRead(n.id, el));
    }
    return el;
  }

  // ── Load unread notifications ────────────────────────────
  async function loadUnread() {
    try {
      const res  = await fetch(PREFIX + '/notifications/unread', {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
      });
      const json = await res.json();
      setBadge(json.unread_count || 0);
      renderList(json.unread_notifications || []);
    } catch (_) {}
  }

  function renderList(items) {
    list.innerHTML = '';
    if (!items.length) {
      list.innerHTML = '<div class="nw-empty"><i class="ti ti-bell-off"></i>No new notifications</div>';
      return;
    }
    items.forEach(n => list.appendChild(makeItem(n)));
  }

  // ── Mark single as read ──────────────────────────────────
  async function markRead(id, el) {
    if (!el.classList.contains('unread')) return;
    try {
      await fetch(PREFIX + '/notifications/' + id + '/read', {
        method: 'PATCH',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
      });
      el.classList.remove('unread');
      setBadge(unread - 1);
    } catch (_) {}
  }

  // ── Mark all as read ─────────────────────────────────────
  readAllBtn.addEventListener('click', async () => {
    try {
      await fetch(PREFIX + '/notifications/read-all', {
        method: 'PATCH',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
      });
      list.querySelectorAll('.nw-item.unread').forEach(el => el.classList.remove('unread'));
      setBadge(0);
    } catch (_) {}
  });

  // ── Prepend real-time notification ───────────────────────
  function prependNotif(data) {
    const emptyEl = list.querySelector('.nw-empty');
    if (emptyEl) emptyEl.remove();
    list.prepend(makeItem({
      id: data.id || null,
      data: data,
      read_at: null,
      created_at: new Date().toISOString(),
    }));
    setBadge(unread + 1);
  }

  // ── Echo listener ─────────────────────────────────────────
  if (window.Echo) {
    window.Echo.private(CHANNEL).notification(data => prependNotif(data));
  }

  loadUnread();
})();
</script>
@endpush
@endif
