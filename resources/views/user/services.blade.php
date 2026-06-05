@extends('user.layout')

@section('title', 'Services')

@section('styles')
<style>
  .search-wrap { position: relative; }
  .search-wrap i { position: absolute; top: 50%; transform: translateY(-50%); left: 12px; font-size: 16px; color: var(--text-muted); pointer-events: none; }
  .search-input { width: 100%; padding: 9px 14px 9px 38px; border: 0.5px solid var(--border-md); border-radius: var(--radius-md); background: var(--bg-surface); font-family: var(--font); font-size: 13px; color: var(--text-primary); outline: none; transition: border-color 0.12s; }
  .search-input:focus { border-color: var(--accent); }
  .filters-row { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
  .filter-select { padding: 7px 12px; border-radius: var(--radius-sm); border: 0.5px solid var(--border-md); background: var(--bg-surface); font-family: var(--font); font-size: 12px; color: var(--text-secondary); outline: none; cursor: pointer; }
  .filter-select:focus { border-color: var(--accent); }
  .chip { padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; border: 0.5px solid var(--border-md); background: var(--bg-surface); color: var(--text-secondary); cursor: pointer; transition: all 0.12s; white-space: nowrap; display: inline-block; }
  .chip:hover  { border-color: var(--accent); color: var(--accent); }
  .chip.active { background: var(--accent-bg); border-color: var(--accent); color: var(--green-800); }
  .services-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(270px, 1fr)); gap: 14px; }
  .service-card { background: var(--bg-surface); border: 0.5px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; transition: border-color 0.15s, box-shadow 0.15s; display: flex; flex-direction: column; }
  .service-card:hover { border-color: var(--border-md); box-shadow: 0 4px 16px rgba(0,0,0,0.07); }
  .service-img { width: 100%; height: 150px; background: var(--bg-sunken); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 36px; }
  .service-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .service-body { padding: 16px; flex: 1; display: flex; flex-direction: column; gap: 8px; }
  .service-name { font-size: 14px; font-weight: 600; }
  .service-desc { font-size: 12px; color: var(--text-secondary); line-height: 1.55; flex: 1; }
  .service-meta { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
  .meta-tag { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; color: var(--text-muted); }
  .meta-tag i { font-size: 13px; }
  .service-footer { padding: 12px 16px; border-top: 0.5px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
  .price-tag { font-size: 15px; font-weight: 700; color: var(--accent); }
  .price-type { font-size: 11px; color: var(--text-muted); font-weight: 400; }
  .empty-state { text-align: center; padding: 60px 24px; color: var(--text-muted); }
  .empty-state i { font-size: 48px; display: block; margin-bottom: 12px; opacity: .35; }
  .empty-state p { font-size: 14px; }
</style>
@endsection

@section('content')

<div>
  <div class="page-title">Available Services</div>
  <div class="page-sub">Browse and search for the right service</div>
</div>

<form method="GET" action="{{ route('user.services') }}">
  <div style="display:flex;flex-direction:column;gap:10px;">
    <div class="search-wrap">
      <i class="ti ti-search"></i>
      <input type="text" name="q" class="search-input" placeholder="Search services..." value="{{ request('q') }}">
    </div>
    <div class="filters-row">
      <select name="city" class="filter-select" onchange="this.form.submit()">
        <option value="">All Cities</option>
        @foreach($cities as $city)
          <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
        @endforeach
      </select>
      <select name="price_type" class="filter-select" onchange="this.form.submit()">
        <option value="">All Currencies</option>
        <option value="usd" {{ request('price_type') === 'usd' ? 'selected' : '' }}>USD</option>
        <option value="syp" {{ request('price_type') === 'syp' ? 'selected' : '' }}>SYP</option>
      </select>
      @if(request('q') || request('city') || request('price_type') || request('category'))
        <a href="{{ route('user.services') }}" class="chip" style="color:var(--red-400);border-color:var(--red-400);">
          <i class="ti ti-x" style="font-size:11px;"></i> Clear Filters
        </a>
      @endif
    </div>
    @if($categories->isNotEmpty())
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <a href="{{ route('user.services', array_filter(['q'=>request('q'),'city'=>request('city'),'price_type'=>request('price_type')])) }}"
         class="chip {{ !request('category') ? 'active' : '' }}">All</a>
      @foreach($categories as $cat)
      <a href="{{ route('user.services', array_filter(['q'=>request('q'),'city'=>request('city'),'price_type'=>request('price_type'),'category'=>$cat])) }}"
         class="chip {{ request('category') === $cat ? 'active' : '' }}">{{ $cat }}</a>
      @endforeach
    </div>
    @endif
  </div>
</form>

<div style="font-size:12px;color:var(--text-muted);">{{ $services->total() }} service(s)</div>

@if($services->isEmpty())
  <div class="empty-state">
    <i class="ti ti-briefcase-off"></i>
    <p>No services found matching your criteria</p>
  </div>
@else
  <div class="services-grid">
    @foreach($services as $service)
    <div class="service-card">
      <div class="service-img">
        @if($service->image)
          <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}">
        @else
          <i class="ti ti-tool"></i>
        @endif
      </div>
      <div class="service-body">
        <div class="service-name">{{ $service->name }}</div>
        @if($service->description)
          <div class="service-desc">{{ $service->description }}</div>
        @endif
        <div class="service-meta">
          @if($service->category)
            <span class="meta-tag"><i class="ti ti-tag"></i> {{ $service->category }}</span>
          @endif
          @if($service->subcategory)
            <span class="meta-tag"><i class="ti ti-point"></i> {{ $service->subcategory }}</span>
          @endif
          @if($service->city)
            <span class="meta-tag"><i class="ti ti-map-pin"></i> {{ $service->city }}</span>
          @endif
        </div>
      </div>
      <div class="service-footer">
        <div>
          <span class="price-tag">{{ number_format($service->price, 0) }}</span>
          <span class="price-type">{{ $service->price_type === 'usd' ? 'USD' : 'SYP' }}</span>
        </div>
        <span class="badge {{ $service->is_active ? 'active' : 'pending' }}">
          {{ $service->is_active ? 'Available' : 'Unavailable' }}
        </span>
      </div>
    </div>
    @endforeach
  </div>
  @if($services->hasPages())
  <div style="display:flex;justify-content:center;margin-top:8px;">{{ $services->withQueryString()->links() }}</div>
  @endif
@endif

@endsection
