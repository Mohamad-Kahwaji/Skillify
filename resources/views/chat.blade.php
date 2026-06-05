<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ $otherUser->first_name }} {{ $otherUser->last_name }} — Hirfa Chat</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
  <style>
    :root {
      --font: 'Inter', sans-serif;
      --accent:        #1D9E75;
      --accent-hover:  #0F6E56;
      --accent-light:  #E1F5EE;
      --green-800:     #085041;
      --sidebar-w:     300px;
      --header-h:      60px;
      --input-h:       68px;
      --bg:            #F1EFE8;
      --surface:       #ffffff;
      --sunken:        #F8F7F4;
      --border:        rgba(0,0,0,0.07);
      --border-md:     rgba(0,0,0,0.12);
      --text:          #1a1a1a;
      --text-sec:      #5F5E5A;
      --text-muted:    #B4B2A9;
      --bubble-sent:   #1D9E75;
      --bubble-recv:   #ffffff;
      --msg-sent-text: #ffffff;
      --msg-recv-text: #1a1a1a;
      --red:           #E24B4A;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; overflow: hidden; font-family: var(--font); font-size: 14px; color: var(--text); background: var(--bg); }
    a { text-decoration: none; color: inherit; }
    button { font-family: var(--font); cursor: pointer; }

    /* ── SHELL ──────────────────────────────────── */
    .shell {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    /* ── SIDEBAR ────────────────────────────────── */
    .sidebar {
      width: var(--sidebar-w);
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      background: var(--surface);
      border-left: 0.5px solid var(--border);
      height: 100vh;
    }
    .sidebar-head {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 0 16px;
      height: var(--header-h);
      border-bottom: 0.5px solid var(--border);
      flex-shrink: 0;
    }
    .brand-icon {
      width: 32px; height: 32px; border-radius: 9px;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 16px; flex-shrink: 0;
    }
    .brand-name { font-size: 15px; font-weight: 600; flex: 1; }
    .icon-btn {
      width: 32px; height: 32px;
      display: flex; align-items: center; justify-content: center;
      border-radius: 8px; border: none; background: none;
      color: var(--text-sec); font-size: 18px;
      transition: background 0.12s;
    }
    .icon-btn:hover { background: var(--sunken); }

    .search-wrap {
      padding: 10px 12px;
      border-bottom: 0.5px solid var(--border);
      flex-shrink: 0;
    }
    .search-inner {
      display: flex; align-items: center;
      background: var(--sunken); border: 0.5px solid var(--border-md);
      border-radius: 8px; padding: 0 10px; gap: 6px;
    }
    .search-inner i { font-size: 15px; color: var(--text-muted); }
    .search-inner input {
      flex: 1; border: none; outline: none; background: transparent;
      padding: 8px 4px; font-size: 13px; color: var(--text);
      font-family: var(--font);
    }
    .search-inner input::placeholder { color: var(--text-muted); }

    .conv-list {
      flex: 1;
      overflow-y: auto;
      scrollbar-width: thin;
      scrollbar-color: var(--border-md) transparent;
    }
    .conv-list::-webkit-scrollbar { width: 4px; }
    .conv-list::-webkit-scrollbar-thumb { background: var(--border-md); border-radius: 4px; }

    .conv-item {
      display: flex; align-items: center; gap: 10px;
      padding: 12px 16px;
      border-bottom: 0.5px solid var(--border);
      cursor: pointer;
      transition: background 0.1s;
      position: relative;
    }
    .conv-item:hover { background: var(--sunken); }
    .conv-item.active { background: var(--accent-light); }
    .conv-item.active .conv-name { color: var(--green-800); }

    .conv-avatar {
      width: 42px; height: 42px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 15px; font-weight: 600; color: #fff; flex-shrink: 0;
    }
    .conv-info { flex: 1; min-width: 0; }
    .conv-name { font-size: 13px; font-weight: 500; margin-bottom: 2px; }
    .conv-last {
      font-size: 12px; color: var(--text-muted);
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .conv-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }
    .conv-time { font-size: 11px; color: var(--text-muted); }

    .sidebar-user {
      padding: 12px 16px;
      border-top: 0.5px solid var(--border);
      display: flex; align-items: center; gap: 9px;
      flex-shrink: 0;
    }
    .me-avatar {
      width: 34px; height: 34px; border-radius: 50%;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; font-weight: 600; color: #fff; flex-shrink: 0;
    }
    .me-name  { font-size: 13px; font-weight: 500; flex: 1; }
    .me-email { font-size: 11px; color: var(--text-muted); }
    .logout-btn {
      background: none; border: none; font-size: 18px;
      color: var(--text-muted); padding: 4px;
      border-radius: 6px; transition: color 0.12s, background 0.12s;
    }
    .logout-btn:hover { color: var(--red); background: #FCEBEB; }

    /* ── CHAT MAIN ───────────────────────────────── */
    .chat-main {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
      background: var(--bg);
    }

    /* Chat Header */
    .chat-header {
      height: var(--header-h);
      background: var(--surface);
      border-bottom: 0.5px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 20px; flex-shrink: 0;
    }
    .chat-header-user { display: flex; align-items: center; gap: 10px; }
    .other-avatar {
      width: 38px; height: 38px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; font-weight: 600; color: #fff; flex-shrink: 0;
      position: relative;
    }
    .online-dot {
      position: absolute; bottom: 1px; right: 1px;
      width: 9px; height: 9px; border-radius: 50%;
      background: var(--accent); border: 1.5px solid var(--surface);
    }
    .other-name  { font-size: 14px; font-weight: 600; }
    .other-status { font-size: 11px; color: var(--accent); }
    .chat-header-actions { display: flex; gap: 4px; }

    /* Messages Area */
    .messages-area {
      flex: 1;
      overflow-y: auto;
      padding: 20px 24px;
      display: flex;
      flex-direction: column;
      gap: 4px;
      scrollbar-width: thin;
      scrollbar-color: var(--border-md) transparent;
    }
    .messages-area::-webkit-scrollbar { width: 4px; }
    .messages-area::-webkit-scrollbar-thumb { background: var(--border-md); border-radius: 4px; }

    .date-sep {
      text-align: center;
      margin: 12px 0 8px;
    }
    .date-sep span {
      font-size: 11px; color: var(--text-muted);
      background: var(--surface); border: 0.5px solid var(--border);
      padding: 3px 12px; border-radius: 20px;
    }

    .msg-row {
      display: flex;
      align-items: flex-end;
      gap: 8px;
      max-width: 70%;
    }
    .msg-row.sent    { align-self: flex-start; flex-direction: row-reverse; }
    .msg-row.received { align-self: flex-end; }

    .msg-avatar {
      width: 28px; height: 28px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 600; color: #fff; flex-shrink: 0;
    }
    .msg-avatar.hidden { visibility: hidden; }

    .msg-content { display: flex; flex-direction: column; gap: 2px; }

    .msg-sender-name {
      font-size: 11px; color: var(--text-muted);
      margin-bottom: 2px; padding: 0 4px;
    }
    .msg-row.sent .msg-sender-name { text-align: right; }

    .bubble {
      padding: 10px 14px;
      border-radius: 18px;
      font-size: 13px;
      line-height: 1.5;
      max-width: 100%;
      word-break: break-word;
      position: relative;
    }
    .msg-row.sent .bubble {
      background: var(--bubble-sent);
      color: var(--msg-sent-text);
      border-bottom-right-radius: 4px;
    }
    .msg-row.received .bubble {
      background: var(--bubble-recv);
      color: var(--msg-recv-text);
      border-bottom-left-radius: 4px;
      border: 0.5px solid var(--border);
      box-shadow: 0 1px 3px rgba(0,0,0,.05);
    }

    /* File bubbles */
    .file-bubble {
      padding: 10px 12px;
      border-radius: 12px;
      display: flex; align-items: center; gap: 10px;
      min-width: 200px; max-width: 280px;
    }
    .msg-row.sent .file-bubble {
      background: rgba(255,255,255,.15);
      border: 0.5px solid rgba(255,255,255,.2);
    }
    .msg-row.received .file-bubble {
      background: var(--sunken);
      border: 0.5px solid var(--border);
    }
    .file-icon-wrap {
      width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center; font-size: 18px;
    }
    .msg-row.sent .file-icon-wrap { background: rgba(255,255,255,.2); color: #fff; }
    .msg-row.received .file-icon-wrap { background: var(--accent-light); color: var(--accent); }
    .file-info { flex: 1; min-width: 0; }
    .file-name { font-size: 12px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .file-dl   { font-size: 11px; opacity: .75; }

    /* Image bubble */
    .img-bubble {
      border-radius: 12px;
      overflow: hidden;
      max-width: 260px;
      cursor: pointer;
    }
    .img-bubble img {
      width: 100%; display: block;
      transition: opacity 0.15s;
    }
    .img-bubble:hover img { opacity: 0.9; }

    /* Video bubble */
    .video-bubble {
      border-radius: 12px; overflow: hidden; max-width: 280px;
    }
    .video-bubble video { width: 100%; display: block; }

    /* Message time */
    .msg-time {
      font-size: 10px; color: var(--text-muted);
      padding: 0 4px; margin-top: 2px;
      display: flex; align-items: center; gap: 4px;
    }
    .msg-row.sent .msg-time { justify-content: flex-end; }

    /* Empty state */
    .msgs-empty {
      flex: 1; display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      gap: 10px; color: var(--text-muted);
    }
    .msgs-empty i { font-size: 48px; opacity: .4; }
    .msgs-empty p { font-size: 13px; }

    /* ── INPUT AREA ─────────────────────────────── */
    .chat-input-wrap {
      background: var(--surface);
      border-top: 0.5px solid var(--border);
      padding: 12px 16px;
      flex-shrink: 0;
    }

    /* File preview */
    .file-preview-bar {
      display: none;
      align-items: center; gap: 10px;
      padding: 8px 12px; margin-bottom: 8px;
      background: var(--sunken); border: 0.5px solid var(--border-md);
      border-radius: 10px;
    }
    .file-preview-bar.visible { display: flex; }
    .fp-thumb {
      width: 40px; height: 40px; border-radius: 8px; object-fit: cover;
      border: 0.5px solid var(--border);
    }
    .fp-icon {
      width: 40px; height: 40px; border-radius: 8px;
      background: var(--accent-light); color: var(--accent);
      display: flex; align-items: center; justify-content: center; font-size: 20px;
    }
    .fp-name { flex: 1; font-size: 12px; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .fp-clear {
      background: none; border: none; font-size: 18px;
      color: var(--text-muted); padding: 2px;
      border-radius: 4px; transition: color 0.12s;
    }
    .fp-clear:hover { color: var(--red); }

    .chat-form {
      display: flex; align-items: center; gap: 8px;
    }
    .attach-btn {
      width: 38px; height: 38px; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      border-radius: 10px; border: 0.5px solid var(--border-md);
      background: var(--sunken); color: var(--text-sec); font-size: 20px;
      transition: border-color 0.12s, color 0.12s;
    }
    .attach-btn:hover { border-color: var(--accent); color: var(--accent); }
    .msg-input-wrap {
      flex: 1; display: flex; align-items: center;
      background: var(--sunken); border: 0.5px solid var(--border-md);
      border-radius: 20px; padding: 0 14px; gap: 8px;
      transition: border-color 0.15s;
    }
    .msg-input-wrap:focus-within { border-color: var(--accent); }
    .msg-input {
      flex: 1; border: none; outline: none; background: transparent;
      padding: 9px 0; font-size: 13px; color: var(--text);
      font-family: var(--font); resize: none; max-height: 120px;
    }
    .msg-input::placeholder { color: var(--text-muted); }
    .send-btn {
      width: 38px; height: 38px; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      border-radius: 50%; border: none;
      background: var(--accent); color: #fff; font-size: 18px;
      transition: background 0.15s, transform 0.1s;
    }
    .send-btn:hover  { background: var(--accent-hover); }
    .send-btn:active { transform: scale(0.93); }
    .send-btn:disabled { background: var(--text-muted); cursor: not-allowed; }

    /* ── LIGHTBOX ───────────────────────────────── */
    .lightbox {
      display: none;
      position: fixed; inset: 0; z-index: 1000;
      background: rgba(0,0,0,.85);
      align-items: center; justify-content: center;
    }
    .lightbox.open { display: flex; }
    .lightbox img {
      max-width: 90vw; max-height: 90vh;
      border-radius: 8px; object-fit: contain;
    }
    .lightbox-close {
      position: absolute; top: 20px; left: 20px;
      background: rgba(255,255,255,.1); border: none;
      color: #fff; font-size: 22px; width: 40px; height: 40px;
      border-radius: 50%; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
    }

    @keyframes toastIn {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── RESPONSIVE ─────────────────────────────── */
    @media (max-width: 720px) {
      .sidebar { display: none; }
    }
  </style>
</head>
<body>

@php
  $me = Auth::guard('users')->user();
  $avatarColors = ['#1D9E75','#0F6E56','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899'];
  $myColor    = $avatarColors[$me->id % count($avatarColors)];
  $otherColor = $avatarColors[$otherUser->id % count($avatarColors)];
@endphp

<div class="shell">

  {{-- ── SIDEBAR ─────────────────────────────────────────── --}}
  <aside class="sidebar">

    <div class="sidebar-head">
      <div class="brand-icon"><i class="ti ti-tool"></i></div>
      <span class="brand-name">Hirfa Chat</span>
    </div>

    <div class="search-wrap">
      <div class="search-inner">
        <i class="ti ti-search"></i>
        <input type="text" id="conv-search" placeholder="Search conversations..." />
      </div>
    </div>

    <div class="conv-list" id="conv-list">
      @forelse($conversations as $conv)
        @php
          $otherId   = $conv->user_id_1 == $me->id ? $conv->user_id_2 : $conv->user_id_1;
          $other     = $conv->user_id_1 == $me->id ? $conv->userTwo : $conv->userOne;
          $isActive  = $conv->id == $conversation->id;
          $color     = $avatarColors[$otherId % count($avatarColors)];
          $lastAt    = $conv->last_message_at
                          ? \Carbon\Carbon::parse($conv->last_message_at)
                          : $conv->created_at;
          $timeStr   = $lastAt->isToday()
                          ? $lastAt->format('H:i')
                          : ($lastAt->isYesterday() ? 'Yesterday' : $lastAt->format('d/m'));
        @endphp
        <a href="{{ route('user.chat', $conv->id) }}"
           class="conv-item {{ $isActive ? 'active' : '' }}"
           data-name="{{ strtolower($other->first_name . ' ' . $other->last_name) }}">
          <div class="conv-avatar" style="background:{{ $color }};">
            {{ strtoupper(substr($other->first_name ?? 'U', 0, 1)) }}
          </div>
          <div class="conv-info">
            <div class="conv-name">{{ $other->first_name }} {{ $other->last_name }}</div>
            <div class="conv-last">{{ $conv->last_message ?? 'Start a conversation' }}</div>
          </div>
          <div class="conv-meta">
            <span class="conv-time">{{ $timeStr }}</span>
          </div>
        </a>
      @empty
        <div style="padding:32px;text-align:center;color:var(--text-muted);font-size:13px;">
          <i class="ti ti-message-off" style="font-size:36px;display:block;margin-bottom:8px;opacity:.4;"></i>
          No conversations yet
        </div>
      @endforelse
    </div>

    <div class="sidebar-user">
      <div class="me-avatar" style="background:{{ $myColor }};">
        {{ strtoupper(substr($me->first_name, 0, 1)) }}
      </div>
      <div style="flex:1;min-width:0;">
        <div class="me-name">{{ $me->first_name }} {{ $me->last_name }}</div>
        <div class="me-email">{{ $me->email }}</div>
      </div>
      <form method="POST" action="{{ route('user.logout') }}">
        @csrf
        <button type="submit" class="logout-btn" title="Sign Out">
          <i class="ti ti-logout"></i>
        </button>
      </form>
    </div>

  </aside>

  {{-- ── CHAT MAIN ──────────────────────────────────────── --}}
  <div class="chat-main">

    {{-- Chat Header --}}
    <header class="chat-header">
      <div class="chat-header-user">
        <div class="other-avatar" style="background:{{ $otherColor }};">
          {{ strtoupper(substr($otherUser->first_name, 0, 1)) }}
          <span class="online-dot"></span>
        </div>
        <div>
          <div class="other-name">{{ $otherUser->first_name }} {{ $otherUser->last_name }}</div>
          <div class="other-status">Online</div>
        </div>
      </div>
      <div class="chat-header-actions">
        <a href="{{ route('user.dashboard') }}" class="icon-btn" title="Home">
          <i class="ti ti-home"></i>
        </a>
      </div>
    </header>

    {{-- Messages --}}
    <div class="messages-area" id="messages-area">
      @if($messages->isEmpty())
        <div class="msgs-empty">
          <i class="ti ti-message-circle"></i>
          <p>Start a conversation with {{ $otherUser->first_name }}</p>
        </div>
      @else
        @php $prevDate = null; @endphp
        @foreach($messages as $msg)
          @php
            $isSent   = $msg->user_id == $me->id;
            $msgDate  = $msg->created_at->toDateString();
            $showDate = $msgDate !== $prevDate;
            $prevDate = $msgDate;
            $msgUser  = $msg->user;
            $msgColor = $avatarColors[($msg->user_id ?? 0) % count($avatarColors)];
          @endphp

          @if($showDate)
            <div class="date-sep">
              <span>
                @if($msg->created_at->isToday()) Today
                @elseif($msg->created_at->isYesterday()) Yesterday
                @else {{ $msg->created_at->format('d/m/Y') }}
                @endif
              </span>
            </div>
          @endif

          <div class="msg-row {{ $isSent ? 'sent' : 'received' }}"
               data-id="{{ $msg->id }}">

            @if(!$isSent)
              <div class="msg-avatar" style="background:{{ $otherColor }};">
                {{ strtoupper(substr($otherUser->first_name, 0, 1)) }}
              </div>
            @endif

            <div class="msg-content">
              @if($msg->file_type === 'image' && $msg->file_path)
                <div class="img-bubble" onclick="openLightbox('{{ Storage::url($msg->file_path) }}')">
                  <img src="{{ Storage::url($msg->file_path) }}"
                       alt="{{ $msg->file_name }}"
                       loading="lazy" />
                </div>
              @elseif($msg->file_type === 'video' && $msg->file_path)
                <div class="video-bubble bubble" style="padding:0;">
                  <video controls src="{{ Storage::url($msg->file_path) }}"></video>
                </div>
              @elseif($msg->file_type && $msg->file_path)
                <div class="bubble" style="padding:0;">
                  <a href="{{ Storage::url($msg->file_path) }}" target="_blank"
                     style="text-decoration:none;color:inherit;" download="{{ $msg->file_name }}">
                    <div class="file-bubble">
                      <div class="file-icon-wrap">
                        <i class="ti {{ $msg->file_type === 'pdf' ? 'ti-file-type-pdf' : 'ti-file' }}"></i>
                      </div>
                      <div class="file-info">
                        <div class="file-name">{{ $msg->file_name }}</div>
                        <div class="file-dl">Click to download</div>
                      </div>
                      <i class="ti ti-download" style="font-size:16px;opacity:.6;"></i>
                    </div>
                  </a>
                </div>
              @elseif($msg->message_text)
                <div class="bubble" dir="auto">{{ $msg->message_text }}</div>
              @endif

              <div class="msg-time">
                {{ $msg->created_at->format('H:i') }}
                @if($isSent)<i class="ti ti-checks" style="font-size:12px;"></i>@endif
              </div>
            </div>

          </div>
        @endforeach
      @endif
    </div>

    {{-- Input Area --}}
    <div class="chat-input-wrap">
      <div class="file-preview-bar" id="file-preview-bar">
        <div id="fp-media"></div>
        <div class="fp-name" id="fp-name"></div>
        <button type="button" class="fp-clear" onclick="clearFile()" title="Remove">
          <i class="ti ti-x"></i>
        </button>
      </div>

      <form class="chat-form" id="chat-form" onsubmit="sendMessage(event)">
        <input type="file" id="file-input" hidden accept="image/*,video/*,.pdf,.doc,.docx,.zip,.txt" />

        <button type="button" class="attach-btn" onclick="document.getElementById('file-input').click()" title="Attach file">
          <i class="ti ti-paperclip"></i>
        </button>

        <div class="msg-input-wrap">
          <textarea id="msg-text" class="msg-input" rows="1"
                    placeholder="Type a message..."
                    onkeydown="handleKey(event)"></textarea>
        </div>

        <button type="submit" class="send-btn" id="send-btn" title="Send">
          <i class="ti ti-send"></i>
        </button>
      </form>
    </div>

  </div>
</div>

{{-- Lightbox --}}
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
  <button class="lightbox-close" onclick="closeLightbox()"><i class="ti ti-x"></i></button>
  <img id="lightbox-img" src="" alt="" onclick="event.stopPropagation()" />
</div>

<script>
  // ── Config ──────────────────────────────────────────────────
  const CONVERSATION_ID = {{ $conversation->id }};
  const MY_ID           = {{ $me->id }};
  const MY_INITIAL      = '{{ strtoupper(substr($me->first_name, 0, 1)) }}';
  const MY_COLOR        = '{{ $myColor }}';
  const OTHER_INITIAL   = '{{ strtoupper(substr($otherUser->first_name, 0, 1)) }}';
  const OTHER_COLOR     = '{{ $otherColor }}';
  const SEND_URL        = '{{ route("user.messages.store") }}';
  const CSRF            = document.querySelector('meta[name="csrf-token"]').content;
  const STORAGE_BASE    = '{{ rtrim(config("app.url"), "/") }}/storage/';
  const AVATAR_COLORS   = {!! json_encode($avatarColors) !!};

  // ── DOM refs ─────────────────────────────────────────────────
  const messagesArea  = document.getElementById('messages-area');
  const msgInput      = document.getElementById('msg-text');
  const fileInput     = document.getElementById('file-input');
  const filePreview   = document.getElementById('file-preview-bar');
  const fpName        = document.getElementById('fp-name');
  const fpMedia       = document.getElementById('fp-media');
  const sendBtn       = document.getElementById('send-btn');
  const convSearch    = document.getElementById('conv-search');

  let selectedFile = null;

  // ── Scroll to bottom ─────────────────────────────────────────
  function scrollToBottom(smooth = false) {
    messagesArea.scrollTo({ top: messagesArea.scrollHeight, behavior: smooth ? 'smooth' : 'instant' });
  }
  scrollToBottom();

  // ── Auto-resize textarea ─────────────────────────────────────
  msgInput.addEventListener('input', () => {
    msgInput.style.height = 'auto';
    msgInput.style.height = Math.min(msgInput.scrollHeight, 120) + 'px';
  });

  // ── Send on Enter (Shift+Enter = newline) ────────────────────
  function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      document.getElementById('chat-form').dispatchEvent(new Event('submit'));
    }
  }

  // ── File selection ───────────────────────────────────────────
  fileInput.addEventListener('change', () => {
    const file = fileInput.files[0];
    if (!file) return;
    selectedFile = file;
    fpName.textContent = file.name;
    fpMedia.innerHTML = '';

    if (file.type.startsWith('image/')) {
      const img = document.createElement('img');
      img.className = 'fp-thumb';
      img.src = URL.createObjectURL(file);
      fpMedia.appendChild(img);
    } else {
      const icon = document.createElement('div');
      icon.className = 'fp-icon';
      icon.innerHTML = '<i class="ti ti-file"></i>';
      fpMedia.appendChild(icon);
    }
    filePreview.classList.add('visible');
  });

  function clearFile() {
    selectedFile = null;
    fileInput.value = '';
    filePreview.classList.remove('visible');
    fpMedia.innerHTML = '';
    fpName.textContent = '';
  }

  // ── Send message ─────────────────────────────────────────────
  async function sendMessage(e) {
    e.preventDefault();
    const text = msgInput.value.trim();
    if (!text && !selectedFile) return;

    sendBtn.disabled = true;

    const formData = new FormData();
    formData.append('conversation_id', CONVERSATION_ID);
    if (text)         formData.append('message_text', text);
    if (selectedFile) formData.append('file', selectedFile);

    // Optimistic render
    const tempId = 'temp-' + Date.now();
    const optimisticEl = buildMessageEl({
      id: tempId,
      user_id: MY_ID,
      message_text: text || null,
      file_type: selectedFile ? detectFileType(selectedFile.type) : null,
      file_name: selectedFile ? selectedFile.name : null,
      file_url:  selectedFile ? URL.createObjectURL(selectedFile) : null,
      created_at: new Date().toISOString(),
    }, true);
    appendMessage(optimisticEl);
    scrollToBottom(true);

    // Reset input
    msgInput.value = '';
    msgInput.style.height = 'auto';
    clearFile();

    try {
      const resp = await fetch(SEND_URL, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF },
        body: formData,
      });
      if (!resp.ok) throw new Error('Send failed');
      const msg = await resp.json();

      // Replace optimistic with real
      const el = document.querySelector(`[data-id="${tempId}"]`);
      if (el) el.dataset.id = msg.id;
    } catch (err) {
      const el = document.querySelector(`[data-id="${tempId}"]`);
      if (el) {
        el.style.opacity = '0.5';
        el.title = 'Failed to send';
      }
    } finally {
      sendBtn.disabled = false;
      msgInput.focus();
    }
  }

  function detectFileType(mime) {
    if (mime.startsWith('image/')) return 'image';
    if (mime.startsWith('video/')) return 'video';
    if (mime === 'application/pdf') return 'pdf';
    return 'file';
  }

  // ── Build message element ─────────────────────────────────────
  function buildMessageEl(msg, isMine) {
    const row = document.createElement('div');
    row.className = `msg-row ${isMine ? 'sent' : 'received'}`;
    row.dataset.id = msg.id;

    const color  = isMine ? MY_COLOR : OTHER_COLOR;
    const initial = isMine ? MY_INITIAL : OTHER_INITIAL;

    let avatarHtml = '';
    if (!isMine) {
      avatarHtml = `<div class="msg-avatar" style="background:${OTHER_COLOR};">${OTHER_INITIAL}</div>`;
    }

    let bodyHtml = '';
    if (msg.file_type === 'image' && (msg.file_url || msg.file_path)) {
      const url = msg.file_url || (STORAGE_BASE + msg.file_path);
      bodyHtml = `<div class="img-bubble" onclick="openLightbox('${url}')">
                    <img src="${url}" alt="${msg.file_name || ''}" loading="lazy" />
                  </div>`;
    } else if (msg.file_type === 'video' && (msg.file_url || msg.file_path)) {
      const url = msg.file_url || (STORAGE_BASE + msg.file_path);
      bodyHtml = `<div class="video-bubble bubble" style="padding:0;">
                    <video controls src="${url}"></video>
                  </div>`;
    } else if ((msg.file_type === 'pdf' || msg.file_type === 'file') && (msg.file_url || msg.file_path)) {
      const url = msg.file_url || (STORAGE_BASE + msg.file_path);
      const icon = msg.file_type === 'pdf' ? 'ti-file-type-pdf' : 'ti-file';
      bodyHtml = `<div class="bubble" style="padding:0;">
                    <a href="${url}" target="_blank" style="text-decoration:none;color:inherit;" download="${msg.file_name || ''}">
                      <div class="file-bubble">
                        <div class="file-icon-wrap"><i class="ti ${icon}"></i></div>
                        <div class="file-info">
                          <div class="file-name">${msg.file_name || 'File'}</div>
                          <div class="file-dl">Click to download</div>
                        </div>
                        <i class="ti ti-download" style="font-size:16px;opacity:.6;"></i>
                      </div>
                    </a>
                  </div>`;
    } else if (msg.message_text) {
      const escaped = msg.message_text
        .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
        .replace(/\n/g,'<br>');
      bodyHtml = `<div class="bubble" dir="auto">${escaped}</div>`;
    }

    const timeStr = new Date(msg.created_at).toLocaleTimeString('ar', { hour:'2-digit', minute:'2-digit', hour12:false });
    const checksHtml = isMine ? `<i class="ti ti-checks" style="font-size:12px;"></i>` : '';

    row.innerHTML = `
      ${avatarHtml}
      <div class="msg-content">
        ${bodyHtml}
        <div class="msg-time">${timeStr} ${checksHtml}</div>
      </div>
    `;
    return row;
  }

  function appendMessage(el) {
    // Remove empty state if present
    const empty = messagesArea.querySelector('.msgs-empty');
    if (empty) empty.remove();
    messagesArea.appendChild(el);
  }

  // ── Mark as read on load ─────────────────────────────────────
  const MARK_READ_URL = '{{ route("user.messages.read", $conversation->id) }}';

  async function markConversationRead() {
    try {
      await fetch(MARK_READ_URL, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      });
      // Reset badge in layout if present
      if (window.__setNotifBadge) window.__setNotifBadge(0);
    } catch (_) {}
  }
  markConversationRead();

  // ── Toast (for messages from other conversations) ─────────────
  const AVATAR_COLORS_CHAT = {!! json_encode($avatarColors) !!};

  function showChatToast({ senderName, preview, conversationId, senderId }) {
    const color   = AVATAR_COLORS_CHAT[senderId % AVATAR_COLORS_CHAT.length];
    const initial = senderName ? senderName.charAt(0).toUpperCase() : '?';

    // Create or reuse container
    let stack = document.getElementById('chat-toast-stack');
    if (!stack) {
      stack = document.createElement('div');
      stack.id = 'chat-toast-stack';
      stack.style.cssText = 'position:fixed;bottom:88px;left:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;';
      document.body.appendChild(stack);
    }

    const toast = document.createElement('div');
    toast.style.cssText = `
      display:flex;align-items:center;gap:10px;
      background:#fff;border:0.5px solid rgba(0,0,0,.1);border-right:3px solid #1D9E75;
      border-radius:12px;padding:10px 12px;
      box-shadow:0 8px 24px rgba(0,0,0,.12);
      min-width:260px;max-width:320px;pointer-events:all;cursor:pointer;
      animation:toastIn 0.3s cubic-bezier(.34,1.56,.64,1) both;
      font-family:'Inter',sans-serif;
    `;
    toast.innerHTML = `
      <div style="width:36px;height:36px;border-radius:50%;background:${color};
                  display:flex;align-items:center;justify-content:center;
                  font-size:13px;font-weight:600;color:#fff;flex-shrink:0;">${initial}</div>
      <div style="flex:1;min-width:0;">
        <div style="font-size:13px;font-weight:600;margin-bottom:2px;">${escHtml(senderName)}</div>
        <div style="font-size:12px;color:#5F5E5A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escHtml(preview)}</div>
      </div>
      <button style="background:none;border:none;font-size:16px;color:#B4B2A9;padding:2px;cursor:pointer;border-radius:4px;">
        <i class="ti ti-x"></i>
      </button>
    `;
    toast.addEventListener('click', (e) => {
      if (!e.target.closest('button')) window.location.href = '/user/chat/' + conversationId;
    });
    toast.querySelector('button').addEventListener('click', () => {
      toast.style.opacity = '0'; toast.style.transform = 'translateX(-12px)';
      setTimeout(() => toast.remove(), 250);
    });

    stack.appendChild(toast);
    const t = setTimeout(() => {
      toast.style.transition = 'opacity .25s, transform .25s';
      toast.style.opacity = '0'; toast.style.transform = 'translateX(-12px)';
      setTimeout(() => toast.remove(), 250);
    }, 5000);
    toast._t = t;
  }

  // ── Laravel Echo — Real-time ─────────────────────────────────
  if (window.Echo) {
    // Incoming message in THIS conversation
    window.Echo.private(`conversation.${CONVERSATION_ID}`)
      .listen('.message.sent', (data) => {
        if (data.user_id == MY_ID) return;

        const el = buildMessageEl({
          id:           data.message_id,
          user_id:      data.user_id,
          message_text: data.message_text,
          file_type:    data.file_type,
          file_name:    data.file_name,
          file_path:    data.file_path,
          created_at:   data.send_date || new Date().toISOString(),
        }, false);

        appendMessage(el);
        scrollToBottom(true);

        // Mark as read immediately since we're in the conversation
        markConversationRead();

        // Update sidebar last message preview
        const convItem = document.querySelector(`.conv-item[href*="/chat/${CONVERSATION_ID}"]`);
        if (convItem) {
          const lastEl = convItem.querySelector('.conv-last');
          if (lastEl) lastEl.textContent = data.message_text || '📎 File';
        }
      });

    // Notifications from OTHER conversations
    window.Echo.private(`notifications.${MY_ID}`)
      .listen('.new.message', (data) => {
        if (!data || data.conversation_id == CONVERSATION_ID) return;

        showChatToast({
          senderName:     data.sender_name || 'User',
          preview:        data.message_text
                            ? data.message_text.substring(0, 60)
                            : (data.file_name ? '📎 ' + data.file_name : 'New message'),
          conversationId: data.conversation_id,
          senderId:       data.user_id || 0,
        });

        // Update sidebar: highlight conv + show last message
        const convItem = document.querySelector(`.conv-item[href*="/chat/${data.conversation_id}"]`);
        if (convItem) {
          const lastEl = convItem.querySelector('.conv-last');
          if (lastEl) lastEl.textContent = data.message_text || '📎 File';
          const timeEl = convItem.querySelector('.conv-time');
          if (timeEl) timeEl.textContent = 'Now';
          // Move to top of list
          const list = document.getElementById('conv-list');
          list.prepend(convItem);
        }
      });
  }

  // ── Sidebar search ───────────────────────────────────────────
  convSearch.addEventListener('input', () => {
    const q = convSearch.value.toLowerCase();
    document.querySelectorAll('.conv-item').forEach(item => {
      const name = item.dataset.name || '';
      item.style.display = name.includes(q) ? '' : 'none';
    });
  });

  // ── Helpers ──────────────────────────────────────────────────
  function escHtml(str) {
    return String(str ?? '')
      .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
      .replace(/"/g,'&quot;');
  }

  // ── Lightbox ─────────────────────────────────────────────────
  function openLightbox(url) {
    document.getElementById('lightbox-img').src = url;
    document.getElementById('lightbox').classList.add('open');
  }
  function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.getElementById('lightbox-img').src = '';
  }
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>

{{-- Echo script --}}
@vite(['resources/js/app.js'])

</body>
</html>
