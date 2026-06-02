@extends('admin.layout')

@section('title', 'Reports')
@section('breadcrumb', 'Reports')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Reports</div>
    <div class="page-sub">Review flagged content and user reports</div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">All Reports ({{ $reports->count() }})</span>
  </div>
  <table class="data-table">
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
      <tr>
        <td>
          <div class="cell-user">
            <div class="panel-icon" style="background:#FCEBEB;color:#E24B4A;border-radius:8px;width:32px;height:32px;display:flex;align-items:center;justify-content:center;">
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
               style="color:var(--accent);font-size:12px;">Post #{{ $report->post_id }}</a>
          @else
            <span style="color:var(--text-muted);">—</span>
          @endif
        </td>
        <td style="color:var(--text-secondary);">
          @if($report->user){{ $report->user->name }}@else —@endif
        </td>
        <td style="color:var(--text-muted);">{{ $report->created_at->format('M d, Y') }}</td>
        <td>
          @if($report->post_id)
            <a href="{{ route('admin.reports.post', $report->post_id) }}" class="btn-ghost" style="padding:5px 10px;font-size:11px;">
              View Post Reports
            </a>
          @endif
        </td>
      </tr>
      @empty
      <tr><td colspan="5"><div class="empty-state"><i class="ti ti-flag"></i>No reports found</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
