@extends('super_admin.layout')

@section('title', 'Admins')
@section('breadcrumb', 'Admins')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Manage Admins</div>
    <div class="page-sub">{{ $admins->count() }} admin(s) registered</div>
  </div>
  <a href="{{ route('super_admin.admins.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Add Admin
  </a>
</div>

<div class="card">
  <table class="data-table">
    <thead>
      <tr>
        <th>Admin</th>
        <th>Phone</th>
        <th>Role</th>
        <th>Created</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($admins as $admin)
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
        <td style="color:var(--text-secondary);">{{ $admin->phone ?? '—' }}</td>
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
      <tr>
        <td colspan="5" style="text-align:center;padding:48px;color:var(--text-muted);">
          <i class="ti ti-user-shield" style="font-size:32px;display:block;margin-bottom:8px;"></i>
          No admins yet
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
