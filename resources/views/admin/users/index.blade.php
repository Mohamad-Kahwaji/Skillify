@extends('admin.layout')

@section('title', 'Users')
@section('breadcrumb', 'Users')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Users</div>
    <div class="page-sub">Manage all registered users on the platform</div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">All Users ({{ $users->count() }})</span>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>User</th>
        <th>Status</th>
        <th>Joined</th>
        <th>Actions</th>
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
        <td><span class="badge {{ $user->status ?? 'active' }}">{{ ucfirst($user->status ?? 'active') }}</span></td>
        <td style="color:var(--text-muted);">{{ $user->created_at->format('M d, Y') }}</td>
        <td>
          <div style="display:flex;gap:6px;align-items:center;">
            @if(($user->status ?? 'active') !== 'active')
              <form method="POST" action="{{ route('admin.users.activate', $user->id) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn-ghost" style="padding:5px 10px;font-size:11px;">Activate</button>
              </form>
            @else
              <form method="POST" action="{{ route('admin.users.deactivate', $user->id) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn-ghost" style="padding:5px 10px;font-size:11px;">Deactivate</button>
              </form>
            @endif
            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                  onsubmit="return confirm('Delete {{ $user->name }}?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="4"><div class="empty-state"><i class="ti ti-users"></i>No users found</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
