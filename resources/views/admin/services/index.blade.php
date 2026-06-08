@extends('admin.layout')
@section('title', 'Services')
@section('breadcrumb', 'Services')

@section('styles')
.status-pill {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;
  border: none; cursor: pointer; font-family: var(--font); transition: opacity 0.12s;
}
.status-pill.active   { background: var(--green-50); color: var(--green-800); }
.status-pill.inactive { background: var(--bg-sunken); color: var(--text-muted); border: 0.5px solid var(--border-md); }
.status-pill:hover { opacity: 0.8; }
@endsection

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Services</div>
    <div class="page-sub">
      {{ $services->count() }} total ·
      {{ $services->where('is_active', true)->count() }} active ·
      {{ $services->where('is_active', false)->count() }} inactive
    </div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">All Services</span>
  </div>
  <div class="table-toolbar">
    <div class="search-field">
      <i class="ti ti-search"></i>
      <input type="text" id="q" placeholder="Search name, category, city…">
    </div>
    <div class="filter-chips" id="chips">
      <button class="chip on" data-s="">All ({{ $services->count() }})</button>
      <button class="chip" data-s="1">Active ({{ $services->where('is_active', true)->count() }})</button>
      <button class="chip" data-s="0">Inactive ({{ $services->where('is_active', false)->count() }})</button>
    </div>
    <span class="tbl-count" id="tbl-count"></span>
  </div>
  <table class="data-table" id="tbl">
    <thead>
      <tr>
        <th>Service</th>
        <th>Category</th>
        <th>City</th>
        <th>Price</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($services as $svc)
      <tr data-s="{{ $svc->is_active ? '1' : '0' }}"
          data-search="{{ strtolower(($svc->name ?? '') . ' ' . ($svc->category ?? '') . ' ' . ($svc->subcategory ?? '') . ' ' . ($svc->city ?? '') . ' ' . ($svc->description ?? '')) }}">
        <td>
          <div class="cell-user">
            @if($svc->image)
              <img src="{{ $svc->image }}" alt=""
                   style="width:36px;height:36px;border-radius:8px;object-fit:cover;flex-shrink:0;">
            @else
              <div class="avatar" style="background:var(--accent);border-radius:8px;width:36px;height:36px;font-size:14px;">
                {{ strtoupper(substr($svc->name ?? 'S', 0, 1)) }}
              </div>
            @endif
            <div>
              <div class="cell-name">{{ $svc->name }}</div>
              <div class="cell-email">{{ Str::limit($svc->description ?? '', 45) }}</div>
            </div>
          </div>
        </td>
        <td>
          <div style="font-size:12px;font-weight:500;">{{ $svc->category }}</div>
          <div style="font-size:11px;color:var(--text-muted);">{{ $svc->subcategory }}</div>
        </td>
        <td style="color:var(--text-secondary);font-size:12px;">{{ $svc->city }}</td>
        <td>
          <span style="font-weight:600;font-size:13px;">{{ number_format($svc->price) }}</span>
          <span style="font-size:10px;color:var(--text-muted);margin-right:2px;">{{ strtoupper($svc->price_type ?? '') }}</span>
        </td>
        <td>
          <form method="POST" action="{{ route('admin.services.toggle', $svc->id) }}">
            @csrf @method('PATCH')
            <button type="submit" class="status-pill {{ $svc->is_active ? 'active' : 'inactive' }}">
              <i class="ti {{ $svc->is_active ? 'ti-circle-check' : 'ti-circle-x' }}" style="font-size:14px;"></i>
              {{ $svc->is_active ? 'Active' : 'Inactive' }}
            </button>
          </form>
        </td>
        <td>
          <div style="display:flex;gap:5px;">
            <a href="{{ route('admin.services.show', $svc->id) }}" class="btn-ghost" style="padding:5px 10px;font-size:12px;" title="View">
              <i class="ti ti-eye"></i>
            </a>
            <form method="POST" action="{{ route('admin.services.destroy', $svc->id) }}"
                  onsubmit="return confirm('Delete this service?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger" title="Delete">
                <i class="ti ti-trash"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6">
          <div class="empty-state"><i class="ti ti-list-check"></i>No services found</div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection

@section('scripts')
<script>
(function(){
  let sts = '', q = '';
  const rows = () => [...document.querySelectorAll('#tbl tbody tr[data-s]')];
  const cnt  = document.getElementById('tbl-count');
  function render(){
    let n = 0;
    rows().forEach(r => {
      const ok = r.dataset.search.includes(q) && (!sts || r.dataset.s === sts);
      r.style.display = ok ? '' : 'none';
      if(ok) n++;
    });
    if(cnt) cnt.textContent = n + ' result' + (n !== 1 ? 's' : '');
  }
  document.getElementById('q').addEventListener('input', e => { q = e.target.value.toLowerCase(); render(); });
  document.getElementById('chips').querySelectorAll('.chip').forEach(c => c.addEventListener('click', () => {
    document.querySelectorAll('#chips .chip').forEach(x => x.classList.remove('on'));
    c.classList.add('on'); sts = c.dataset.s; render();
  }));
  render();
})();
</script>
@endsection
