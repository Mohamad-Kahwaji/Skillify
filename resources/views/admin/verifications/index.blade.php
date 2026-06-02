@extends('admin.layout')

@section('title', 'Verifications')
@section('breadcrumb', 'Verifications')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Verifications</div>
    <div class="page-sub">Review and approve pending worker verifications</div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">Pending Verifications ({{ $pending->count() }})</span>
  </div>
  <table class="data-table">
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
      <tr>
        <td>
          <div class="cell-user">
            <div class="panel-icon" style="background:#FAEEDA;color:#BA7517;border-radius:8px;width:32px;height:32px;display:flex;align-items:center;justify-content:center;">
              <i class="ti ti-id-badge"></i>
            </div>
            <div>
              <div class="cell-name">{{ $biz->name }}</div>
              <div class="cell-email">{{ $biz->name_job }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">{{ $biz->activity }}</td>
        <td style="color:var(--text-muted);">{{ $biz->number }}</td>
        <td style="color:var(--text-muted);">{{ $biz->created_at->diffForHumans() }}</td>
        <td>
          <div style="display:flex;gap:6px;">
            <form method="POST" action="{{ route('admin.verifications.approve', $biz->id) }}">
              @csrf @method('PATCH')
              <button type="submit" class="btn-primary" style="padding:6px 14px;font-size:12px;">Approve</button>
            </form>
            <form method="POST" action="{{ route('admin.verifications.reject', $biz->id) }}">
              @csrf @method('PATCH')
              <button type="submit" class="btn-danger">Reject</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="5"><div class="empty-state"><i class="ti ti-circle-check"></i>No pending verifications</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
