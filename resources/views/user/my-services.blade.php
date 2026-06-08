@extends('user.layout')

@section('title', 'My Services')

@section('styles')
<style>
/* ── Layout ───────────────────────────────────────── */
.page-head {
  display:flex; align-items:flex-start; justify-content:space-between;
  gap:12px; flex-wrap:wrap;
}
.btn-primary {
  display:inline-flex; align-items:center; gap:6px;
  background:var(--accent); color:#fff; border:none; cursor:pointer;
  padding:9px 18px; border-radius:var(--radius-md);
  font-size:13px; font-weight:600; font-family:var(--font);
  transition:background .12s; text-decoration:none; white-space:nowrap;
}
.btn-primary:hover { background:var(--accent-hover); }

/* ── Notice Banners ───────────────────────────────── */
.notice-banner {
  display:flex; align-items:flex-start; gap:12px;
  padding:12px 16px; border-radius:var(--radius-md);
  font-size:13px; line-height:1.5;
}
.notice-banner.pending  { background:#FFFBEB; border:.5px solid #FDE68A; color:#78350F; }
.notice-banner.rejected { background:var(--red-50); border:.5px solid #FECACA; color:var(--red-800); }
.notice-banner i { font-size:17px; flex-shrink:0; margin-top:1px; }

/* ── Filter Tabs ──────────────────────────────────── */
.filter-tabs {
  display:flex; gap:6px; flex-wrap:wrap; align-items:center;
}
.filter-tab {
  display:inline-flex; align-items:center; gap:5px;
  padding:6px 14px; border-radius:20px; font-size:12px; font-weight:500;
  border:.5px solid var(--border-md); background:var(--bg-surface);
  color:var(--text-secondary); cursor:pointer; transition:all .12s;
}
.filter-tab:hover { border-color:var(--accent); color:var(--accent); }
.filter-tab.active {
  background:var(--accent); border-color:var(--accent);
  color:#fff; box-shadow:0 2px 8px rgba(29,158,117,.25);
}
.filter-tab .tab-count {
  font-size:10px; font-weight:700;
  padding:1px 6px; border-radius:20px;
  background:rgba(255,255,255,.2);
}
.filter-tab:not(.active) .tab-count {
  background:var(--bg-sunken); color:var(--text-muted);
}

/* ── Grid ─────────────────────────────────────────── */
.svc-grid {
  display:grid;
  grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));
  gap:16px;
}

/* ── Card ─────────────────────────────────────────── */
.svc-card {
  background:var(--bg-surface);
  border:.5px solid var(--border);
  border-radius:var(--radius-lg);
  overflow:hidden; display:flex; flex-direction:column;
  transition:border-color .15s, box-shadow .15s;
  position:relative;
}
.svc-card:hover {
  border-color:var(--accent);
  box-shadow:0 4px 18px rgba(29,158,117,.1);
}
.svc-card.hidden-card { display:none; }

.svc-status-ribbon {
  position:absolute; top:10px; right:10px; z-index:2;
}

.svc-thumb {
  width:100%; height:160px;
  background:var(--bg-sunken);
  overflow:hidden; display:flex; align-items:center; justify-content:center;
  font-size:38px; color:var(--text-muted);
}
.svc-thumb img { width:100%; height:100%; object-fit:cover; display:block; }

.svc-body { padding:14px 16px; flex:1; display:flex; flex-direction:column; gap:7px; }
.svc-name { font-size:14px; font-weight:700; }
.svc-meta { display:flex; flex-wrap:wrap; gap:5px; }
.svc-tag {
  display:inline-flex; align-items:center; gap:3px;
  font-size:11px; color:var(--text-muted);
  background:var(--bg-sunken); padding:2px 7px;
  border-radius:20px; border:.5px solid var(--border);
}
.svc-tag i { font-size:11px; }
.svc-desc {
  font-size:12px; color:var(--text-secondary); line-height:1.55;
  display:-webkit-box; -webkit-line-clamp:2;
  -webkit-box-orient:vertical; overflow:hidden;
}

.svc-footer {
  padding:11px 16px;
  border-top:.5px solid var(--border);
  display:flex; align-items:center; justify-content:space-between; gap:8px;
}
.svc-price { font-size:15px; font-weight:800; color:var(--accent); }
.svc-price small { font-size:10px; font-weight:400; color:var(--text-muted); }

/* ── Badges ───────────────────────────────────────── */
.badge {
  display:inline-flex; align-items:center; gap:4px;
  font-size:11px; font-weight:600; padding:3px 10px; border-radius:20px;
}
.badge::before { content:''; width:5px; height:5px; border-radius:50%; background:currentColor; opacity:.7; }
.badge.approved { background:var(--green-50);  color:var(--green-800); }
.badge.active   { background:var(--green-50);  color:var(--green-800); }
.badge.pending  { background:#FEF3C7;           color:#92400E; }
.badge.rejected { background:var(--red-50);     color:var(--red-800); }
.badge.inactive { background:#F3F4F6;           color:#6B7280; }

/* ── Action Buttons ───────────────────────────────── */
.act-btn {
  display:inline-flex; align-items:center; justify-content:center;
  width:30px; height:30px; border-radius:var(--radius-sm);
  border:.5px solid var(--border-md); background:none;
  color:var(--text-secondary); cursor:pointer; transition:all .12s; font-size:14px;
}
.act-btn:hover           { background:var(--bg-hover); color:var(--text-primary); }
.act-btn.del:hover       { background:var(--red-50); color:var(--red-400); border-color:var(--red-400); }

/* ── Empty State ──────────────────────────────────── */
.empty-state {
  grid-column:1/-1;
  padding:64px 24px; text-align:center;
  background:var(--bg-surface); border:.5px solid var(--border);
  border-radius:var(--radius-lg); color:var(--text-muted);
}
.empty-state i { font-size:48px; display:block; margin-bottom:12px; opacity:.3; }
.empty-state p { font-size:14px; margin-bottom:16px; }
</style>
@endsection

@section('content')

@php
  $total    = $services->count();
  $approved = $services->whereIn('status', ['approved', null])->filter(fn($s) => in_array($s->status, ['approved']) || ($s->status === null && $s->is_active))->count();
  $pending  = $services->where('status', 'pending')->count();
  $rejected = $services->where('status', 'rejected')->count();
  $inactive = $services->where('is_active', false)->where('status', null)->count();
  $activeFilter = request('filter', 'all');
@endphp

{{-- ── Header ──────────────────────────────────────── --}}
<div class="page-head">
  <div>
    <div class="page-title">My Services</div>
    <div class="page-sub">{{ $total }} service(s) you have listed</div>
  </div>
  @can('my_services.create')
  <a href="{{ route('user.profile') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Add Service
  </a>
  @endcan
</div>

{{-- ── Banners for pending / rejected ─────────────── --}}
@if($pending)
<div class="notice-banner pending">
  <i class="ti ti-clock"></i>
  <span><strong>{{ $pending }} service(s) under review.</strong> The admin will process them shortly.</span>
</div>
@endif

@if($rejected)
<div class="notice-banner rejected">
  <i class="ti ti-circle-x"></i>
  <span><strong>{{ $rejected }} service(s) rejected.</strong> Please contact support for more information.</span>
</div>
@endif

{{-- ── Filter Tabs ──────────────────────────────────── --}}
@if($total > 0)
<div class="filter-tabs">
  <button class="filter-tab {{ $activeFilter === 'all'      ? 'active' : '' }}" onclick="filterCards('all')">
    <i class="ti ti-layout-grid"></i> All
    <span class="tab-count">{{ $total }}</span>
  </button>
  <button class="filter-tab {{ $activeFilter === 'approved' ? 'active' : '' }}" onclick="filterCards('approved')">
    <i class="ti ti-circle-check"></i> Approved
    <span class="tab-count">{{ $approved }}</span>
  </button>
  @if($pending)
  <button class="filter-tab {{ $activeFilter === 'pending'  ? 'active' : '' }}" onclick="filterCards('pending')">
    <i class="ti ti-clock"></i> Under Review
    <span class="tab-count">{{ $pending }}</span>
  </button>
  @endif
  @if($rejected)
  <button class="filter-tab {{ $activeFilter === 'rejected' ? 'active' : '' }}" onclick="filterCards('rejected')">
    <i class="ti ti-circle-x"></i> Rejected
    <span class="tab-count">{{ $rejected }}</span>
  </button>
  @endif
  @if($inactive)
  <button class="filter-tab {{ $activeFilter === 'inactive' ? 'active' : '' }}" onclick="filterCards('inactive')">
    <i class="ti ti-eye-off"></i> Inactive
    <span class="tab-count">{{ $inactive }}</span>
  </button>
  @endif
</div>
@endif

{{-- ── Cards Grid ───────────────────────────────────── --}}
<div class="svc-grid" id="svc-grid">
  @forelse($services as $svc)
  @php
    $img = $svc->image
      ? (str_starts_with($svc->image, 'http') ? $svc->image : asset('storage/'.$svc->image))
      : null;

    $statusKey = match($svc->status ?? '') {
      'pending'  => 'pending',
      'rejected' => 'rejected',
      'approved' => 'approved',
      default    => $svc->is_active ? 'approved' : 'inactive',
    };
    $statusLabel = match($statusKey) {
      'pending'  => 'Under Review',
      'rejected' => 'Rejected',
      'approved' => 'Approved',
      'inactive' => 'Inactive',
      default    => 'Approved',
    };
  @endphp

  <div class="svc-card" data-status="{{ $statusKey }}">

    {{-- Status ribbon --}}
    <div class="svc-status-ribbon">
      <span class="badge {{ $statusKey }}">{{ $statusLabel }}</span>
    </div>

    {{-- Thumbnail --}}
    <div class="svc-thumb">
      @if($img)
        <img src="{{ $img }}" alt="{{ $svc->name }}"
             onerror="this.style.display='none';this.parentElement.innerHTML+='<i class=\'ti ti-tool\'></i>'">
      @else
        <i class="ti ti-tool"></i>
      @endif
    </div>

    {{-- Body --}}
    <div class="svc-body">
      <div class="svc-name">{{ $svc->name }}</div>
      <div class="svc-meta">
        @if($svc->category)
          <span class="svc-tag"><i class="ti ti-tag"></i> {{ $svc->category }}</span>
        @endif
        @if($svc->subcategory)
          <span class="svc-tag"><i class="ti ti-point"></i> {{ $svc->subcategory }}</span>
        @endif
        @if($svc->city)
          <span class="svc-tag"><i class="ti ti-map-pin"></i> {{ $svc->city }}</span>
        @endif
      </div>
      @if($svc->description)
        <div class="svc-desc">{{ $svc->description }}</div>
      @endif
    </div>

    {{-- Footer --}}
    <div class="svc-footer">
      <div class="svc-price">
        {{ number_format($svc->price, 0) }}
        <small>{{ $svc->price_type === 'usd' ? 'USD' : 'SYP' }}</small>
      </div>
      <div style="display:flex;gap:5px;">
        @can('my_services.delete')
        <form method="POST" action="{{ route('user.my-services.destroy', $svc->id) }}"
              onsubmit="return confirm('Delete this service?')">
          @csrf @method('DELETE')
          <button type="submit" class="act-btn del" title="Delete">
            <i class="ti ti-trash"></i>
          </button>
        </form>
        @endcan
      </div>
    </div>
  </div>

  @empty
  <div class="empty-state">
    <i class="ti ti-tool-off"></i>
    <p>You haven't added any services yet.</p>
    @can('my_services.create')
    <a href="{{ route('user.profile') }}" class="btn-primary">
      <i class="ti ti-plus"></i> Add Your First Service
    </a>
    @endcan
  </div>
  @endforelse

  {{-- No results after filter --}}
  <div class="empty-state" id="no-filter-results" style="display:none;">
    <i class="ti ti-filter-off"></i>
    <p>No services match this filter.</p>
  </div>
</div>

@endsection

@section('scripts')
<script>
  const INIT_FILTER = '{{ $activeFilter }}';

  function filterCards(status) {
    const cards   = document.querySelectorAll('.svc-card[data-status]');
    const noRes   = document.getElementById('no-filter-results');
    const tabs    = document.querySelectorAll('.filter-tab');

    tabs.forEach(t => t.classList.remove('active'));
    event?.currentTarget?.classList.add('active');

    let visible = 0;
    cards.forEach(card => {
      const show = status === 'all' || card.dataset.status === status;
      card.classList.toggle('hidden-card', !show);
      if (show) visible++;
    });

    noRes.style.display = visible === 0 ? 'block' : 'none';
  }

  document.addEventListener('DOMContentLoaded', () => {
    if (INIT_FILTER !== 'all') filterCards(INIT_FILTER);
  });
</script>
@endsection
