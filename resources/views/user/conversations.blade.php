@extends('user.layout')

@section('title', 'Messages')

@section('styles')
<style>
.conv-list-wrap { display: flex; flex-direction: column; gap: 0; }

.conv-row {
  display: flex; align-items: center; gap: 14px;
  padding: 16px 20px;
  border-bottom: .5px solid var(--border);
  text-decoration: none; color: inherit;
  transition: background .1s;
  position: relative;
}
.conv-row:last-child { border-bottom: none; }
.conv-row:hover { background: var(--bg-sunken); }

.conv-avatar {
  width: 46px; height: 46px; border-radius: 50%; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 17px; font-weight: 700; color: #fff;
}
.conv-info { flex: 1; min-width: 0; }
.conv-name { font-size: 14px; font-weight: 600; }
.conv-last {
  font-size: 12px; color: var(--text-muted); margin-top: 2px;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.conv-meta { flex-shrink: 0; text-align: right; }
.conv-time { font-size: 11px; color: var(--text-muted); }
.conv-unread {
  display: inline-flex; align-items: center; justify-content: center;
  width: 18px; height: 18px; border-radius: 50%;
  background: var(--accent); color: #fff;
  font-size: 10px; font-weight: 700;
  margin-top: 4px;
}

.empty-conv {
  text-align: center; padding: 64px 24px;
  color: var(--text-muted);
}
.empty-conv i { font-size: 48px; display: block; margin-bottom: 12px; opacity: .3; }
.empty-conv p { font-size: 14px; margin-bottom: 16px; }

.av-0 { background: #0D9488; }
.av-1 { background: #3B82F6; }
.av-2 { background: #8B5CF6; }
.av-3 { background: #F59E0B; }
.av-4 { background: #EF4444; }
.av-5 { background: #EC4899; }
.av-6 { background: #0F766E; }
</style>
@endsection

@section('content')

@php $userId = Auth::guard('users')->id(); @endphp

<div>
  <div class="page-title">Messages</div>
  <div class="page-sub">{{ $conversations->count() }} conversation(s)</div>
</div>

<div class="card">
  @if($conversations->isEmpty())
    <div class="empty-conv">
      <i class="ti ti-message-off"></i>
      <p>No conversations yet.</p>
      <a href="{{ route('user.explore') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:8px;background:var(--accent);color:#fff;font-size:13px;font-weight:500;text-decoration:none;border:none;">
        <i class="ti ti-search"></i> Explore Craftsmen
      </a>
    </div>
  @else
    <div class="conv-list-wrap">
      @foreach($conversations as $conv)
        @php
          $other    = $conv->user_id_1 == $userId ? $conv->userTwo : $conv->userOne;
          $avClass  = 'av-' . ($other->id % 7);
          $initial  = strtoupper(mb_substr($other->first_name ?? 'U', 0, 1));
          $lastAt   = $conv->last_message_at
                        ? \Carbon\Carbon::parse($conv->last_message_at)
                        : $conv->created_at;
          $timeStr  = $lastAt->isToday()
                        ? $lastAt->format('H:i')
                        : ($lastAt->isYesterday() ? 'Yesterday' : $lastAt->format('M d'));
        @endphp
        <a href="{{ route('user.chat', $conv->id) }}" class="conv-row">
          <div class="conv-avatar {{ $avClass }}">{{ $initial }}</div>
          <div class="conv-info">
            <div class="conv-name">{{ $other->first_name }} {{ $other->last_name }}</div>
            <div class="conv-last">
              {{ $conv->last_message ?? 'Start a conversation' }}
            </div>
          </div>
          <div class="conv-meta">
            <div class="conv-time">{{ $timeStr }}</div>
          </div>
          <i class="ti ti-chevron-right" style="font-size:14px;color:var(--text-muted);flex-shrink:0;"></i>
        </a>
      @endforeach
    </div>
  @endif
</div>

@endsection
