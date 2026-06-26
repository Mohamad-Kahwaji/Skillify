@extends('user.layout')

@section('title', 'Explore Craftsmen')

@section('styles')
<style>
  .search-bar { display: flex; gap: 10px; align-items: center; }
  .search-wrap { flex: 1; position: relative; }
  .search-wrap i { position: absolute; top: 50%; transform: translateY(-50%); left: 12px; font-size: 16px; color: var(--text-muted); pointer-events: none; }
  .search-input { width: 100%; padding: 9px 14px 9px 38px; border: 0.5px solid var(--border-md); border-radius: var(--radius-md); background: var(--bg-surface); font-family: var(--font); font-size: 13px; color: var(--text-primary); outline: none; transition: border-color 0.12s; }
  .search-input:focus { border-color: var(--accent); }
  .filter-row { display: flex; gap: 8px; flex-wrap: wrap; }
  .chip { padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; border: 0.5px solid var(--border-md); background: var(--bg-surface); color: var(--text-secondary); cursor: pointer; transition: all 0.12s; white-space: nowrap; }
  .chip:hover  { border-color: var(--accent); color: var(--accent); }
  .chip.active { background: var(--accent-bg); border-color: var(--accent); color: var(--teal-900); }
  .workers-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px; }
  .worker-card { background: var(--bg-surface); border: 0.5px solid var(--border); border-radius: var(--radius-lg); padding: 20px; display: flex; flex-direction: column; gap: 12px; transition: border-color 0.15s, box-shadow 0.15s; }
  .worker-card:hover { border-color: var(--border-md); box-shadow: 0 4px 16px rgba(0,0,0,0.07); }
  .worker-head { display: flex; align-items: center; gap: 12px; }
  .worker-avatar { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; color: #fff; flex-shrink: 0; }
  .worker-name  { font-size: 14px; font-weight: 600; }
  .worker-job   { font-size: 12px; color: var(--text-secondary); }
  .worker-desc  { font-size: 12px; color: var(--text-secondary); line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
  .worker-meta  { display: flex; gap: 10px; flex-wrap: wrap; }
  .worker-tag   { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; color: var(--text-secondary); }
  .worker-tag i { font-size: 13px; color: var(--text-muted); }
  .worker-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 8px; border-top: 0.5px solid var(--border); }
  .chat-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: var(--radius-sm); background: var(--accent); color: #fff; font-size: 12px; font-weight: 500; border: none; cursor: pointer; transition: background 0.12s; }
  .chat-btn:hover { background: var(--accent-hover); }
  .av-0{background:#0D9488;}.av-1{background:#3B82F6;}.av-2{background:#8B5CF6;}.av-3{background:#F59E0B;}.av-4{background:#EF4444;}.av-5{background:#EC4899;}.av-6{background:#0F766E;}
  .empty-state { text-align: center; padding: 60px 24px; color: var(--text-muted); }
  .empty-state i { font-size: 48px; display: block; margin-bottom: 12px; opacity: .35; }
  .empty-state p { font-size: 14px; }
</style>
@endsection

@section('content')

<div>
  <div class="page-title">Explore Craftsmen</div>
  <div class="page-sub">Browse available businesses and workers</div>
</div>

<form method="GET" action="{{ route('user.explore') }}">
  <div class="search-bar">
    <div class="search-wrap">
      <i class="ti ti-search"></i>
      <input type="text" name="q" class="search-input" placeholder="Search by name or activity..." value="{{ request('q') }}">
    </div>
  </div>

  @if($activities->isNotEmpty())
  <div class="filter-row" style="margin-top:12px;">
    <a href="{{ route('user.explore', array_filter(['q' => request('q')])) }}"
       class="chip {{ !request('activity') ? 'active' : '' }}">All</a>
    @foreach($activities as $activity)
    <a href="{{ route('user.explore', array_filter(['q' => request('q'), 'activity' => $activity])) }}"
       class="chip {{ request('activity') === $activity ? 'active' : '' }}">{{ $activity }}</a>
    @endforeach
  </div>
  @endif
</form>

<div style="font-size:12px;color:var(--text-muted);">{{ $businesses->total() }} result(s)</div>

@if($businesses->isEmpty())
  <div class="empty-state">
    <i class="ti ti-users-off"></i>
    <p>No craftsmen found matching your search</p>
  </div>
@else
  <div class="workers-grid">
    @foreach($businesses as $business)
    @php $av = 'av-' . ($business->id % 7); $initial = strtoupper(mb_substr($business->name ?? 'H', 0, 1)); @endphp
    <div class="worker-card">
      <div class="worker-head">
        <div class="worker-avatar {{ $av }}">{{ $initial }}</div>
        <div>
          <div class="worker-name">{{ $business->name }}</div>
          <div class="worker-job">{{ $business->name_job ?? '—' }}</div>
        </div>
      </div>
      @if($business->description)
      <div class="worker-desc">{{ $business->description }}</div>
      @endif
      <div class="worker-meta">
        @if($business->activity)
        <span class="worker-tag"><i class="ti ti-tag"></i> {{ $business->activity }}</span>
        @endif
        @if($business->number)
        <span class="worker-tag"><i class="ti ti-phone"></i> {{ $business->number }}</span>
        @endif
        <span class="badge {{ $business->status === 'active' ? 'active' : 'pending' }}">
          {{ $business->status === 'active' ? 'Active' : 'Under Review' }}
        </span>
      </div>
      <div class="worker-footer">
        <span style="font-size:11px;color:var(--text-muted);">
          <i class="ti ti-clock" style="vertical-align:middle;"></i>
          Joined {{ $business->created_at->diffForHumans() }}
        </span>
        @if($business->conversationId)
          <a href="{{ route('user.chat', $business->conversationId) }}" class="chat-btn">
            <i class="ti ti-message-circle"></i> Message
          </a>
        @else
          <form method="POST" action="{{ route('user.chat.start') }}">
            @csrf
            <input type="hidden" name="business_user_id" value="{{ $business->user_id }}">
            <button type="submit" class="chat-btn"><i class="ti ti-message-circle"></i> Message</button>
          </form>
        @endif
      </div>
    </div>
    @endforeach
  </div>
  @if($businesses->hasPages())
  <div style="display:flex;justify-content:center;margin-top:8px;">{{ $businesses->withQueryString()->links() }}</div>
  @endif
@endif

@endsection
