@extends('admin.layout')
@section('title', 'Business Account Requests')
@section('breadcrumb', 'Business Account Requests')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Business Account Requests</div>
    <div class="page-sub">Review and approve pending business account applications</div>
  </div>
  @if($pending->count() > 0)
    <span class="badge pending" style="font-size:13px;padding:6px 14px;">
      {{ $pending->count() }} Pending
    </span>
  @endif
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">Pending Business Account Requests</span>
  </div>
  <div class="table-toolbar">
    <div class="search-field">
      <i class="ti ti-search"></i>
      <input type="text" id="q" placeholder="Search business name or activity…">
    </div>
    <span class="tbl-count" id="tbl-count"></span>
  </div>
  <table class="data-table" id="tbl">
    <thead>
      <tr>
        <th>Worker</th>
        <th>Activity</th>
        <th>Phone</th>
        <th>Submitted</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($pending as $biz)
      <tr data-search="{{ strtolower(($biz->name ?? '') . ' ' . ($biz->name_job ?? '') . ' ' . ($biz->activity ?? '')) }}">
        <td>
          <div class="cell-user">
            <div style="width:34px;height:34px;border-radius:8px;background:#FAEEDA;color:#BA7517;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
              <i class="ti ti-id-badge"></i>
            </div>
            <div>
              <div class="cell-name">{{ $biz->name }}</div>
              <div class="cell-email">{{ $biz->name_job }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);font-size:12px;">{{ $biz->activity }}</td>
        <td style="color:var(--text-muted);font-size:12px;">{{ $biz->number }}</td>
        <td style="color:var(--text-muted);font-size:12px;">{{ $biz->created_at->diffForHumans() }}</td>
        <td>
          <div style="display:flex;gap:6px;">
            <form method="POST" action="{{ route('admin.verifications.approve', $biz->id) }}">
              @csrf @method('PATCH')
              <button type="submit" class="btn-primary" style="padding:6px 14px;font-size:12px;">
                <i class="ti ti-check" style="font-size:13px;"></i> Approve
              </button>
            </form>
            <form method="POST" action="{{ route('admin.verifications.reject', $biz->id) }}">
              @csrf @method('PATCH')
              <button type="submit" class="btn-danger">
                <i class="ti ti-x" style="font-size:13px;"></i> Reject
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5">
          <div class="empty-state">
            <i class="ti ti-circle-check" style="color:var(--accent);"></i>
            No pending verifications
          </div>
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
  const cnt = document.getElementById('tbl-count');
  function render(q){
    let n = 0;
    document.querySelectorAll('#tbl tbody tr[data-search]').forEach(r => {
      const ok = r.dataset.search.includes(q);
      r.style.display = ok ? '' : 'none';
      if(ok) n++;
    });
    if(cnt) cnt.textContent = n + ' result' + (n !== 1 ? 's' : '');
  }
  document.getElementById('q').addEventListener('input', e => render(e.target.value.toLowerCase()));
  render('');
})();
</script>
@endsection
