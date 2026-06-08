@extends('admin.layout')
@section('title', 'Reports')
@section('breadcrumb', 'Reports')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Reports</div>
    <div class="page-sub">Review flagged content and user reports</div>
  </div>
  @if($reports->count() > 0)
    <span class="badge blocked" style="font-size:13px;padding:6px 14px;">
      {{ $reports->count() }} Open
    </span>
  @endif
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">All Reports ({{ $reports->count() }})</span>
  </div>
  <div class="table-toolbar">
    <div class="search-field">
      <i class="ti ti-search"></i>
      <input type="text" id="q" placeholder="Search reason or reporter…">
    </div>
    <span class="tbl-count" id="tbl-count"></span>
  </div>
  <table class="data-table" id="tbl">
    <thead>
      <tr>
        <th>Report</th>
        <th>Post</th>
        <th>Reporter</th>
        <th>Date</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($reports as $report)
      <tr data-search="{{ strtolower(($report->reason ?? '') . ' ' . ($report->description ?? '') . ' ' . ($report->user?->name ?? '')) }}">
        <td>
          <div class="cell-user">
            <div style="width:32px;height:32px;border-radius:8px;background:#FCEBEB;color:#E24B4A;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;">
              <i class="ti ti-flag"></i>
            </div>
            <div>
              <div class="cell-name">{{ $report->reason ?? 'Report' }}</div>
              <div class="cell-email">{{ Str::limit($report->description ?? '', 50) }}</div>
            </div>
          </div>
        </td>
        <td>
          @if($report->post_id)
            <a href="{{ route('admin.reports.post', $report->post_id) }}"
               style="display:inline-flex;align-items:center;gap:4px;color:var(--accent);font-size:12px;font-weight:500;">
              <i class="ti ti-external-link" style="font-size:13px;"></i>
              Post #{{ $report->post_id }}
            </a>
          @else
            <span style="color:var(--text-muted);">—</span>
          @endif
        </td>
        <td>
          @if($report->user)
            <div class="cell-user" style="gap:7px;">
              <div class="avatar" style="background:#8B5CF6;width:24px;height:24px;font-size:10px;">
                {{ strtoupper(substr($report->user->name, 0, 1)) }}
              </div>
              <span style="font-size:12px;color:var(--text-secondary);">{{ $report->user->name }}</span>
            </div>
          @else
            <span style="color:var(--text-muted);">—</span>
          @endif
        </td>
        <td style="color:var(--text-muted);font-size:12px;">{{ $report->created_at->format('M d, Y') }}</td>
        <td>
          @if($report->post_id)
            <a href="{{ route('admin.reports.post', $report->post_id) }}" class="btn-ghost" style="padding:5px 12px;font-size:11px;">
              <i class="ti ti-eye" style="font-size:13px;"></i> View Post
            </a>
          @endif
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5">
          <div class="empty-state">
            <i class="ti ti-circle-check" style="color:var(--accent);"></i>
            No reports found
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
