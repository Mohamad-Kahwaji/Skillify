@extends('user.layout')

@section('title', 'Service Requests Status')

@section('styles')
<style>
.status-list { display: flex; flex-direction: column; gap: 12px; }

.status-item {
  background: var(--bg-surface);
  border: .5px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 16px 18px;
  display: flex; align-items: center; gap: 14px;
  transition: border-color .15s;
}
.status-item:hover { border-color: var(--border-md); }

.status-thumb {
  width: 52px; height: 52px; border-radius: 10px; flex-shrink: 0;
  background: var(--bg-sunken); overflow: hidden;
  display: flex; align-items: center; justify-content: center;
  font-size: 22px; color: var(--text-muted);
}
.status-thumb img { width:100%;height:100%;object-fit:cover; }

.status-info { flex: 1; min-width: 0; }
.status-name { font-size: 14px; font-weight: 700; }
.status-meta {
  display: flex; flex-wrap: wrap; gap: 5px; margin-top: 5px;
  font-size: 11px; color: var(--text-muted);
}
.status-tag {
  display:inline-flex;align-items:center;gap:3px;
  background:var(--bg-sunken);padding:2px 7px;
  border-radius:20px;border:.5px solid var(--border);
}
.status-tag i { font-size:11px; }

.status-right {
  display: flex; flex-direction: column; align-items: flex-end; gap: 6px;
  flex-shrink: 0;
}
.svc-price { font-size: 15px; font-weight: 800; color: var(--accent); }
.svc-price small { font-size: 10px; font-weight: 400; color: var(--text-muted); }

.badge { display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:4px 11px;border-radius:20px; }
.badge::before { content:'';width:5px;height:5px;border-radius:50%;background:currentColor;opacity:.7; }
.badge.pending  { background:#FEF3C7;       color:#92400E; }
.badge.rejected { background:var(--red-50); color:var(--red-800); }

.status-date { font-size: 11px; color: var(--text-muted); }

.notice-banner {
  padding: 14px 16px; border-radius: var(--radius-md);
  display: flex; align-items: flex-start; gap: 12px;
  font-size: 13px;
}
.notice-banner.pending  { background:#FFFBEB;border:.5px solid #FDE68A;color:#78350F; }
.notice-banner.rejected { background:var(--red-50);border:.5px solid #FECACA;color:var(--red-800); }
.notice-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }

.empty-state {
  padding: 64px 24px; text-align: center;
  background: var(--bg-surface); border: .5px solid var(--border);
  border-radius: var(--radius-lg); color: var(--text-muted);
}
.empty-state i { font-size: 48px; display: block; margin-bottom: 12px; opacity: .3; }
.empty-state p { font-size: 14px; }
</style>
@endsection

@section('content')

<div>
  <div class="page-title">Service Requests Status</div>
  <div class="page-sub">Your services that are pending review or have been rejected</div>
</div>

@if(session('success'))
  <div style="padding:12px 16px;border-radius:var(--radius-md);background:var(--green-50);color:var(--teal-900);border:.5px solid #9FE1CB;font-size:13px;">
    {{ session('success') }}
  </div>
@endif

@if($services->isEmpty())
  <div class="empty-state">
    <i class="ti ti-circle-check" style="opacity:.25;color:var(--accent);"></i>
    <p>No pending or rejected services.</p>
  </div>
@else

  {{-- Summary notices --}}
  @php
    $pendingCount  = $services->where('status', 'pending')->count();
    $rejectedCount = $services->where('status', 'rejected')->count();
  @endphp

  @if($pendingCount)
    <div class="notice-banner pending">
      <i class="ti ti-clock notice-icon"></i>
      <span><strong>{{ $pendingCount }} service(s) under review.</strong> The admin will process them shortly.</span>
    </div>
  @endif
  @if($rejectedCount)
    <div class="notice-banner rejected">
      <i class="ti ti-circle-x notice-icon"></i>
      <span><strong>{{ $rejectedCount }} service(s) rejected.</strong> Please contact support for more information.</span>
    </div>
  @endif

  <div class="status-list">
    @foreach($services as $svc)
    @php
      $img = $svc->image
        ? (str_starts_with($svc->image,'http') ? $svc->image : asset('storage/'.$svc->image))
        : null;
    @endphp
    <div class="status-item">
      <div class="status-thumb">
        @if($img)
          <img src="{{ $img }}" alt="{{ $svc->name }}"
               onerror="this.style.display='none';this.parentElement.innerHTML+='<i class=\'ti ti-tool\'></i>'">
        @else
          <i class="ti ti-tool"></i>
        @endif
      </div>
      <div class="status-info">
        <div class="status-name">{{ $svc->name }}</div>
        <div class="status-meta">
          @if($svc->category)
            <span class="status-tag"><i class="ti ti-tag"></i> {{ $svc->category }}</span>
          @endif
          @if($svc->subcategory)
            <span class="status-tag"><i class="ti ti-point"></i> {{ $svc->subcategory }}</span>
          @endif
          @if($svc->city)
            <span class="status-tag"><i class="ti ti-map-pin"></i> {{ $svc->city }}</span>
          @endif
        </div>
      </div>
      <div class="status-right">
        <div class="svc-price">
          {{ number_format($svc->price, 0) }}
          <small>{{ $svc->price_type === 'usd' ? 'USD' : 'SYP' }}</small>
        </div>
        <span class="badge {{ $svc->status }}">
          {{ $svc->status === 'pending' ? 'Under Review' : 'Rejected' }}
        </span>
        <div class="status-date">{{ $svc->created_at->format('M d, Y') }}</div>
      </div>
    </div>
    @endforeach
  </div>

@endif

@endsection
