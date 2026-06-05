@extends('admin.layout')
@section('title', 'Employees')
@section('breadcrumb', 'Employees')

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">Employees</div>
    <div class="page-sub">{{ $employees->count() }} employee(s) registered</div>
  </div>
</div>

<div class="card">
  <table class="data-table">
    <thead>
      <tr>
        <th>User</th>
        <th>Profession</th>
        <th>National ID</th>
        <th>Registered</th>
      </tr>
    </thead>
    <tbody>
      @forelse($employees as $emp)
      <tr>
        <td>
          <div class="cell-user">
            <div class="avatar" style="background:var(--accent);">
              {{ strtoupper(substr($emp->user?->first_name ?? 'E', 0, 1)) }}
            </div>
            <div>
              <div class="cell-name">{{ $emp->user?->first_name }} {{ $emp->user?->last_name }}</div>
              <div class="cell-email">{{ $emp->user?->email }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">{{ $emp->profession ?? '—' }}</td>
        <td>
          <code style="font-size:12px;background:var(--bg-sunken);padding:2px 8px;border-radius:4px;">
            {{ $emp->national_id }}
          </code>
        </td>
        <td style="color:var(--text-muted);font-size:12px;">{{ $emp->created_at->format('Y/m/d') }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="4">
          <div class="empty-state">
            <i class="ti ti-user-check"></i>
            No employees registered
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
