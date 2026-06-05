@extends('admin.layout')
@section('title', 'Blocked Users')
@section('breadcrumb', 'Blocked Users')

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">Blocked Users</div>
    <div class="page-sub">{{ $blocked->count() }} accounts currently blocked</div>
  </div>
  <a href="{{ route('admin.blocked.create') }}" class="btn-primary">
    <i class="ti ti-ban"></i> Block User
  </a>
</div>

@if(session('success'))
  <div class="alert success"><i class="ti ti-check"></i> {{ session('success') }}</div>
@endif

<div class="card">
  <table class="data-table">
    <thead>
      <tr>
        <th>Blocked User</th>
        <th>Reason</th>
        <th>Blocked By</th>
        <th>Block Date</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($blocked as $record)
      <tr>
        <td>
          <div class="cell-user">
            <div class="avatar" style="background:var(--red-400);">
              {{ strtoupper(substr($record->user?->first_name ?? 'U', 0, 1)) }}
            </div>
            <div>
              <div class="cell-name">{{ $record->user?->first_name }} {{ $record->user?->last_name }}</div>
              <div class="cell-email">{{ $record->user?->email }}</div>
            </div>
          </div>
        </td>
        <td style="max-width:200px;">
          <span style="font-size:12px;color:var(--text-secondary);">{{ Str::limit($record->reason, 60) }}</span>
        </td>
        <td style="font-size:12px;color:var(--text-secondary);">
          {{ $record->admin?->first_name }} {{ $record->admin?->last_name }}
        </td>
        <td style="font-size:12px;color:var(--text-muted);">
          {{ $record->blocker_date ? \Carbon\Carbon::parse($record->blocker_date)->format('M d, Y') : $record->created_at->format('M d, Y') }}
        </td>
        <td><span class="badge blocked">Blocked</span></td>
        <td>
          <form method="POST" action="{{ route('admin.blocked.destroy', $record->id) }}"
                onsubmit="return confirm('Unblock {{ $record->user?->first_name }}?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-ghost" style="padding:5px 12px;font-size:12px;color:var(--green-600);border-color:var(--green-600);">
              <i class="ti ti-lock-open"></i> Unblock
            </button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6">
          <div class="empty-state">
            <i class="ti ti-circle-check" style="color:var(--accent);"></i>
            No blocked users
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
