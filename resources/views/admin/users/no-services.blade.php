@extends('admin.layout')

@section('title', 'Users Without Services')
@section('breadcrumb', 'Users Without Services')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Users Without Services</div>
    <div class="page-sub">{{ $users->count() }} user(s) have not added any service yet</div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">Users ({{ $users->count() }})</span>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>User</th>
        <th>City</th>
        <th>Status</th>
        <th>Joined</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $user)
      <tr>
        <td>
          <div class="cell-user">
            <div class="avatar" style="background:#1D9E75;">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
            <div>
              <div class="cell-name">{{ $user->name }}</div>
              <div class="cell-email">{{ $user->email }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">{{ $user->city ?? '—' }}</td>
        <td><span class="badge {{ $user->status ?? 'active' }}">{{ ucfirst($user->status ?? 'active') }}</span></td>
        <td style="color:var(--text-muted);">{{ $user->created_at->format('M d, Y') }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="4">
          <div class="empty-state">
            <i class="ti ti-circle-check" style="color:var(--accent);"></i>
            All users have at least one service
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
