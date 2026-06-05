@extends('super_admin.layout')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Welcome, {{ Auth::guard('super_admins')->user()->first_name }}</div>
    <div class="page-sub">System overview</div>
  </div>
  <a href="{{ route('super_admin.admins.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Add Admin
  </a>
</div>

{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon green"><i class="ti ti-user-shield"></i></div>
    </div>
    <div class="stat-value">{{ $admins->count() }}</div>
    <div class="stat-label">Total Admins</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon blue"><i class="ti ti-users"></i></div>
    </div>
    <div class="stat-value">{{ $totalUsers }}</div>
    <div class="stat-label">Total Users</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon amber"><i class="ti ti-briefcase"></i></div>
    </div>
    <div class="stat-value">{{ $totalBiz }}</div>
    <div class="stat-label">Registered Businesses</div>
  </div>
</div>

{{-- Admins Table --}}
<div class="card">
  <div class="card-head">
    <div class="card-title">Admins</div>
    <a href="{{ route('super_admin.admins.index') }}" style="font-size:12px;color:var(--accent);">View All</a>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Admin</th>
        <th>Role</th>
        <th>Created</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($admins->take(8) as $admin)
      <tr>
        <td>
          <div class="cell-user">
            <div class="avatar" style="background:var(--accent);">
              {{ strtoupper(substr($admin->first_name, 0, 1)) }}
            </div>
            <div>
              <div class="cell-name">{{ $admin->first_name }} {{ $admin->last_name }}</div>
              <div class="cell-email">{{ $admin->email }}</div>
            </div>
          </div>
        </td>
        <td><span class="badge admin">{{ $admin->role ?? 'admin' }}</span></td>
        <td style="color:var(--text-muted);">{{ $admin->created_at->format('Y/m/d') }}</td>
        <td>
          <form method="POST" action="{{ route('super_admin.admins.destroy', $admin) }}"
                onsubmit="return confirm('Delete this admin?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger">
              <i class="ti ti-trash"></i> Delete
            </button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--text-muted);">No admins yet</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
