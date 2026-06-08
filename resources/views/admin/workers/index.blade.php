@extends('admin.layout')
@section('title', 'Service Providers')
@section('breadcrumb', 'Service Providers')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Service Providers</div>
    <div class="page-sub">Manage worker profiles and business listings</div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">All Service Providers ({{ $businesses->whereNull('deleted_at')->count() }})</span>
  </div>
  <div class="table-toolbar">
    <div class="search-field">
      <i class="ti ti-search"></i>
      <input type="text" id="q" placeholder="Search name, activity, phone…">
    </div>
    <div class="filter-chips" id="chips">
      <button class="chip on" data-s="">All</button>
      <button class="chip" data-s="active">Active ({{ $businesses->where('status','active')->count() }})</button>
      <button class="chip" data-s="pending">Pending ({{ $businesses->where('status','pending')->count() }})</button>
      <button class="chip" data-s="rejected">Rejected ({{ $businesses->where('status','rejected')->count() }})</button>
    </div>
    <span class="tbl-count" id="tbl-count"></span>
  </div>
  <table class="data-table" id="tbl">
    <thead>
      <tr>
        <th>Business</th>
        <th>Activity</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($businesses as $biz)
      @php
        $rowStatus = $biz->trashed() ? 'deleted' : ($biz->status ?? 'pending');
      @endphp
      <tr data-s="{{ $rowStatus }}"
          data-search="{{ strtolower($biz->name . ' ' . $biz->activity . ' ' . $biz->number . ' ' . $biz->name_job) }}"
          style="{{ $biz->trashed() ? 'opacity:0.5;' : '' }}">
        <td>
          <div class="cell-user">
            <div class="avatar" style="background:{{ $biz->trashed() ? '#B4B2A9' : '#D85A30' }};border-radius:8px;">
              {{ strtoupper(substr($biz->name, 0, 2)) }}
            </div>
            <div>
              <div class="cell-name" style="{{ $biz->trashed() ? 'text-decoration:line-through;' : '' }}">
                {{ $biz->name }}
              </div>
              <div class="cell-email">{{ $biz->name_job }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);font-size:12px;">{{ $biz->activity }}</td>
        <td style="color:var(--text-muted);font-size:12px;">{{ $biz->number }}</td>
        <td>
          @if($biz->trashed())
            <span class="badge blocked">Deleted</span>
          @else
            <span class="badge {{ $biz->status }}">{{ ucfirst($biz->status) }}</span>
          @endif
        </td>
        <td>
          <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
            @if(!$biz->trashed())
              @if($biz->status !== 'active')
                <form method="POST" action="{{ route('admin.workers.approve', $biz->id) }}">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn-ghost" style="padding:5px 10px;font-size:11px;color:var(--accent);border-color:var(--accent);">
                    <i class="ti ti-check" style="font-size:12px;"></i> Approve
                  </button>
                </form>
              @endif
              @if($biz->status !== 'rejected')
                <form method="POST" action="{{ route('admin.workers.reject', $biz->id) }}">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn-ghost" style="padding:5px 10px;font-size:11px;">Reject</button>
                </form>
              @endif
              <a href="{{ route('admin.workers.show', $biz->id) }}" class="btn-ghost" style="padding:5px 10px;font-size:11px;">
                <i class="ti ti-eye" style="font-size:13px;"></i>
              </a>
              <form method="POST" action="{{ route('admin.workers.destroy', $biz->id) }}"
                    onsubmit="return confirm('Delete {{ addslashes($biz->name) }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger" title="Delete">
                  <i class="ti ti-trash" style="font-size:13px;"></i>
                </button>
              </form>
            @else
              <span style="font-size:11px;color:var(--text-muted);">
                Deleted {{ $biz->deleted_at->diffForHumans() }}
              </span>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5">
          <div class="empty-state"><i class="ti ti-tools"></i>No workers found</div>
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
