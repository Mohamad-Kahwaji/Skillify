@extends('user.layout')

@section('title', 'Services')

@section('styles')
<style>
.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 18px;
}
.svc-card {
  background: var(--bg-surface);
  border: .5px solid var(--border);
  border-radius: var(--radius-lg);
  overflow: hidden;
  display: flex; flex-direction: column;
  transition: border-color .15s, box-shadow .15s, transform .15s;
}
.svc-card:hover {
  border-color: var(--accent);
  box-shadow: 0 6px 24px rgba(5,150,105,.12);
  transform: translateY(-2px);
}
.svc-img {
  width: 100%; height: 170px;
  background: var(--bg-sunken);
  display: flex; align-items: center; justify-content: center;
  font-size: 40px; color: var(--text-muted); overflow: hidden;
}
.svc-img img { width:100%;height:100%;object-fit:cover;display:block; }
.svc-body { padding: 14px 16px; flex: 1; display: flex; flex-direction: column; gap: 7px; }
.svc-name { font-size: 14px; font-weight: 700; line-height: 1.35; }
.svc-desc {
  font-size: 12px; color: var(--text-secondary); line-height: 1.55; flex: 1;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.svc-tags { display: flex; gap: 5px; flex-wrap: wrap; margin-top: 2px; }
.svc-tag {
  display: inline-flex; align-items: center; gap: 3px;
  font-size: 10px; background: var(--bg-sunken); color: var(--text-muted);
  padding: 2px 7px; border-radius: 20px; border: .5px solid var(--border);
}
.svc-tag i { font-size: 11px; }
.svc-footer {
  padding: 11px 16px;
  border-top: .5px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
  gap: 8px;
}
.svc-price { font-size: 16px; font-weight: 800; color: var(--accent); }
.svc-price small { font-size: 10px; font-weight: 400; color: var(--text-muted); }
.svc-owner {
  display: flex; align-items: center; gap: 6px;
  font-size: 12px; color: var(--text-secondary);
}
.svc-owner-av {
  width: 26px; height: 26px; border-radius: 50%;
  color: #fff; display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 700; flex-shrink: 0;
}
.btn-detail {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 11px; font-weight: 600; padding: 5px 11px;
  border-radius: var(--radius-sm); border: 1px solid var(--accent);
  color: var(--accent); background: transparent; cursor: pointer;
  transition: background .12s, color .12s; white-space: nowrap;
}
.btn-detail:hover { background: var(--accent); color: #fff; }

/* ── Filters ── */
.filters-bar { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.search-wrap { flex: 1; min-width: 200px; position: relative; }
.search-wrap i { position:absolute;top:50%;transform:translateY(-50%);left:12px;font-size:15px;color:var(--text-muted);pointer-events:none; }
.search-inp {
  width:100%;padding:9px 14px 9px 36px;
  border:.5px solid var(--border-md);border-radius:var(--radius-md);
  background:var(--bg-surface);font-family:var(--font);font-size:13px;
  color:var(--text-primary);outline:none;transition:border-color .12s;
}
.search-inp:focus { border-color:var(--accent); }
.filter-sel {
  padding:8px 12px;border:.5px solid var(--border-md);border-radius:var(--radius-sm);
  background:var(--bg-surface);font-family:var(--font);font-size:12px;
  color:var(--text-secondary);outline:none;cursor:pointer;
}
.empty-state { text-align:center;padding:64px 24px;color:var(--text-muted);grid-column:1/-1; }
.empty-state i { font-size:48px;display:block;margin-bottom:12px;opacity:.3; }
.empty-state p { font-size:14px; }

/* ── Detail Modal ── */
.svc-modal-backdrop {
  display: none; position: fixed; inset: 0; z-index: 1000;
  background: rgba(0,0,0,.55); backdrop-filter: blur(3px);
  align-items: center; justify-content: center; padding: 16px;
}
.svc-modal-backdrop.open { display: flex; }
.svc-modal {
  background: var(--bg-surface);
  border-radius: 16px;
  width: 100%; max-width: 820px;
  max-height: 92vh; overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0,0,0,.28);
  animation: modalIn .2s ease;
}
@keyframes modalIn {
  from { opacity:0; transform:translateY(14px) scale(.97); }
  to   { opacity:1; transform:translateY(0)    scale(1);   }
}
.svc-modal-header {
  display: flex; align-items: flex-start; justify-content: space-between;
  padding: 16px 20px 12px; border-bottom: .5px solid var(--border);
  gap: 12px; flex-shrink: 0;
}
.svc-modal-title { font-size: 16px; font-weight: 700; line-height: 1.3; }
.svc-modal-close {
  background: var(--bg-sunken); border: none; cursor: pointer;
  width: 28px; height: 28px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: var(--text-secondary); flex-shrink: 0; transition: background .12s;
}
.svc-modal-close:hover { background: var(--border-md); }
.detail-banner {
  width: 100%; height: 180px; flex-shrink: 0;
  background: var(--bg-sunken); overflow: hidden;
  border-bottom: .5px solid var(--border); position: relative;
}
.detail-banner img { width:100%;height:100%;object-fit:cover;display:block; }
.detail-banner-fallback {
  width:100%;height:100%;
  display:flex;align-items:center;justify-content:center;
  font-size:52px;font-weight:800;color:#fff;letter-spacing:-2px;
}
.svc-modal-body {
  display: grid; grid-template-columns: 42% 58%;
  min-height: 420px;
}
@media (max-width: 600px) { .svc-modal-body { grid-template-columns: 1fr; } }
.svc-modal-left { border-right: .5px solid var(--border); }
.map-placeholder {
  height: 420px; background: var(--bg-sunken);
  display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 6px;
  color: var(--text-muted);
}
.mp-title { font-size: 11px; text-transform: uppercase; letter-spacing: .06em; font-weight: 600; }
.mp-val   { font-size: 14px; font-weight: 600; color: var(--text-secondary); }
.svc-modal-right { padding: 16px 18px; display: flex; flex-direction: column; gap: 14px; }
.detail-price-row { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
.detail-price-badge {
  display: inline-flex; align-items: baseline; gap: 4px;
  font-size: 28px; font-weight: 800; color: var(--accent);
}
.detail-price-badge small { font-size: 13px; font-weight: 500; color: var(--text-muted); }
.detail-status-badge {
  display: inline-flex; align-items: center; gap: 4px;
  font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px;
  background: var(--green-50); color: var(--green-800);
  border: .5px solid rgba(5,150,105,.2); white-space: nowrap;
}
.detail-section-label {
  font-size: 10px; text-transform: uppercase; letter-spacing: .08em;
  color: var(--text-muted); font-weight: 600; margin-bottom: 7px;
}
.detail-desc { font-size: 13px; color: var(--text-secondary); line-height: 1.65; }
.info-block { display: flex; flex-direction: column; }
.info-grid  { display: flex; flex-direction: column; gap: 5px; }
.info-row   { display: flex; align-items: baseline; gap: 6px; min-height: 22px; }
.info-lbl {
  font-size: 11px; color: var(--text-muted); white-space: nowrap;
  min-width: 76px; display: flex; align-items: center; gap: 4px; flex-shrink: 0;
}
.info-lbl i { font-size: 12px; color: var(--accent); }
.info-val { font-size: 12px; color: var(--text-primary); line-height: 1.4; }
.info-val.fw { font-weight: 700; }
.detail-divider { height: .5px; background: var(--border); }
.detail-owner { display: flex; align-items: flex-start; gap: 10px; }
.detail-owner-av {
  width: 36px; height: 36px; border-radius: 50%;
  color: #fff; display: flex; align-items: center; justify-content: center;
  font-size: 14px; font-weight: 700; flex-shrink: 0; margin-top: 2px;
}
.detail-business {
  background: var(--bg-sunken); border-radius: 10px;
  padding: 11px 12px; display: flex; gap: 10px; align-items: flex-start;
}
.detail-biz-av {
  width: 38px; height: 38px; border-radius: 8px; flex-shrink: 0;
  background: var(--bg-surface); border: .5px solid var(--border);
  overflow: hidden; display: flex; align-items: center; justify-content: center;
  font-size: 16px; color: var(--text-muted);
}
.detail-biz-av img { width:100%;height:100%;object-fit:cover; }
.detail-biz-desc { font-size: 11px; color: var(--text-muted); line-height: 1.5; }
.detail-biz-badge {
  display: inline-flex; align-items: center; gap: 3px;
  font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
  background: var(--green-50); color: var(--green-800);
}
.svc-modal-footer {
  padding: 12px 20px; border-top: .5px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
  gap: 10px; flex-shrink: 0;
}
.btn-contact {
  display: inline-flex; align-items: center; gap: 6px;
  background: var(--accent); color: #fff; border: none; cursor: pointer;
  padding: 9px 18px; border-radius: var(--radius-sm);
  font-size: 13px; font-weight: 600; font-family: var(--font);
  transition: background .12s;
}
.btn-contact:hover { background: #047857; }
.btn-close-md {
  background: var(--bg-sunken); border: .5px solid var(--border-md);
  color: var(--text-secondary); cursor: pointer;
  padding: 9px 16px; border-radius: var(--radius-sm);
  font-size: 13px; font-family: var(--font); transition: background .12s;
}
.btn-close-md:hover { background: var(--border); }
.detail-location-text {
  display: flex; align-items: center; gap: 5px;
  font-size: 12px; color: var(--text-muted);
}
.detail-location-text i { color: var(--accent); }
@keyframes spin { to { transform: rotate(360deg); } }
.svc-modal-loader {
  padding: 60px 20px; text-align: center; color: var(--text-muted);
}
.svc-modal-loader i { font-size: 32px; display: block; margin-bottom: 12px; }
</style>
@endsection

@section('content')

<div>
  <div class="page-title">Available Services</div>
  <div class="page-sub">{{ $services->count() }} service(s) from craftsmen</div>
</div>

{{-- Filters --}}
<div class="filters-bar">
  <div class="search-wrap">
    <i class="ti ti-search"></i>
    <input type="text" class="search-inp" id="svc-search" placeholder="Search services...">
  </div>
  <select class="filter-sel" id="filter-city">
    <option value="">All Cities</option>
    @foreach($services->pluck('city')->filter()->unique()->sort() as $city)
      <option value="{{ $city }}">{{ $city }}</option>
    @endforeach
  </select>
  <select class="filter-sel" id="filter-currency">
    <option value="">All Currencies</option>
    <option value="usd">USD</option>
    <option value="syp">SYP</option>
  </select>
</div>

{{-- Grid --}}
<div class="services-grid" id="svc-grid">
  @forelse($services as $svc)
  @php
    $avColors = ['#1D9E75','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899'];
    $avColor  = $avColors[($svc->user_id ?? $svc->id) % count($avColors)];
    $initial  = strtoupper(mb_substr($svc->user?->first_name ?? 'H', 0, 1));
  @endphp
  <div class="svc-card"
       data-name="{{ strtolower($svc->name) }}"
       data-city="{{ strtolower($svc->city ?? '') }}"
       data-currency="{{ $svc->price_type }}">
    <div class="svc-img">
      @if($svc->image)
        @php $cardImg = str_starts_with($svc->image,'http') ? $svc->image : asset('storage/'.$svc->image); @endphp
        <img src="{{ $cardImg }}" alt="{{ $svc->name }}"
             onerror="this.style.display='none';this.parentElement.innerHTML+='<i class=\'ti ti-tool\'></i>'">
      @else
        <i class="ti ti-tool"></i>
      @endif
    </div>
    <div class="svc-body">
      <div class="svc-name">{{ $svc->name }}</div>
      @if($svc->description)
        <div class="svc-desc">{{ $svc->description }}</div>
      @endif
      <div class="svc-tags">
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
    </div>
    <div class="svc-footer">
      <div class="svc-owner">
        <div class="svc-owner-av" style="background:{{ $avColor }};">{{ $initial }}</div>
        <span>{{ $svc->user?->first_name ?? 'Hirfa' }}</span>
      </div>
      <div style="display:flex;align-items:center;gap:8px;">
        <div class="svc-price">
          {{ number_format($svc->price, 0) }}
          <small>{{ $svc->price_type === 'usd' ? 'USD' : 'SYP' }}</small>
        </div>
        <button class="btn-detail" onclick="openSvcDetail({{ $svc->id }})">
          <i class="ti ti-eye"></i> Details
        </button>
      </div>
    </div>
  </div>
  @empty
    <div class="empty-state">
      <i class="ti ti-briefcase-off"></i>
      <p>No services available yet.</p>
    </div>
  @endforelse
</div>

{{-- Detail Modal --}}
<div class="svc-modal-backdrop" id="svc-detail-modal" onclick="onBackdropClick(event)">
  <div class="svc-modal" id="svc-modal-inner">
    <div class="svc-modal-loader" id="svc-modal-loader">
      <i class="ti ti-loader-2" style="animation:spin .8s linear infinite;"></i>
      <span>Loading...</span>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
/* ── Filters ── */
const search = document.getElementById('svc-search');
const cityF  = document.getElementById('filter-city');
const currF  = document.getElementById('filter-currency');

function applyFilters() {
  const q    = search.value.toLowerCase();
  const city = cityF.value.toLowerCase();
  const curr = currF.value;
  document.querySelectorAll('.svc-card').forEach(card => {
    const show = (!q    || card.dataset.name.includes(q))
              && (!city || card.dataset.city.includes(city))
              && (!curr || card.dataset.currency === curr);
    card.style.display = show ? '' : 'none';
  });
}
search.addEventListener('input',  applyFilters);
cityF.addEventListener('change',  applyFilters);
currF.addEventListener('change',  applyFilters);

/* ── Modal ── */
function closeSvcModal() {
  document.getElementById('svc-detail-modal').classList.remove('open');
  document.body.style.overflow = '';
}

function onBackdropClick(e) {
  if (e.target === document.getElementById('svc-detail-modal')) closeSvcModal();
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeSvcModal();
});

async function openSvcDetail(id) {
  const inner = document.getElementById('svc-modal-inner');

  inner.innerHTML = `<div class="svc-modal-loader">
    <i class="ti ti-loader-2" style="font-size:32px;display:block;margin-bottom:10px;animation:spin .8s linear infinite;"></i>
    <span style="font-size:13px;">Loading...</span>
  </div>`;

  document.getElementById('svc-detail-modal').classList.add('open');
  document.body.style.overflow = 'hidden';

  try {
    const res = await fetch(`{{ url('user/services') }}/${id}/details`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    if (!res.ok) throw new Error(res.status);
    inner.innerHTML = await res.text();
  } catch (err) {
    inner.innerHTML = `<div class="svc-modal-loader">
      <i class="ti ti-alert-circle" style="font-size:32px;display:block;margin-bottom:10px;color:#EF4444;"></i>
      <span style="font-size:13px;color:#EF4444;">Failed to load details.</span><br>
      <button class="btn-close-md" style="margin-top:14px;" onclick="closeSvcModal()">Close</button>
    </div>`;
  }
}
</script>
@endsection
