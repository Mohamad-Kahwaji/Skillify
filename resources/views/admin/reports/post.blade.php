@extends('admin.layout')

@section('title', 'Post Reports')
@section('breadcrumb', 'Reports')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Reports for Post #{{ $reports->first()->post_id ?? '' }}</div>
    <div class="page-sub">All reports submitted for this post</div>
  </div>
  <a href="{{ route('admin.reports.index') }}" class="btn-ghost">
    <i class="ti ti-arrow-left" style="font-size:15px;"></i> Back to Reports
  </a>
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">Reports ({{ $reports->count() }})</span>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Reason</th>
        <th>Reporter</th>
        <th>Date</th>
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
              <div class="cell-email">{{ $report->description ?? '' }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">
          @if($report->user){{ $report->user->name }}@else —@endif
        </td>
        <td style="color:var(--text-muted);">{{ $report->created_at->format('M d, Y H:i') }}</td>
      </tr>
      @empty
      <tr><td colspan="3"><div class="empty-state"><i class="ti ti-flag"></i>No reports for this post</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
