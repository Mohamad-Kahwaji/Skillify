@extends('admin.layout')

@section('title', 'Workers')
@section('breadcrumb', 'Workers')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Workers</div>
    <div class="page-sub">Manage worker profiles and business listings</div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">
      All Workers ({{ $businesses->whereNull('deleted_at')->count() }})
      @if($businesses->whereNotNull('deleted_at')->count() > 0)
        <span style="font-size:12px;font-weight:400;color:var(--text-muted);margin-left:8px;">
          · {{ $businesses->whereNotNull('deleted_at')->count() }} deleted
        </span>
      @endif
    </span>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Business</th>
        <th>Activity</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($businesses as $biz)
      <tr style="{{ $biz->trashed() ? 'opacity:0.55;' : '' }}">
        <td>
          <div class="cell-user">
            <div class="avatar" style="background:{{ $biz->trashed() ? '#B4B2A9' : '#D85A30' }};">
              {{ strtoupper(substr($biz->name, 0, 2)) }}
            </div>
            <div>
              <div class="cell-name" style="{{ $biz->trashed() ? 'text-decoration:line-through;' : '' }}">
                {{ $biz->name }}
              </div>
              <div class="cell-email">{{ $biz->name_job }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">{{ $biz->activity }}</td>
        <td style="color:var(--text-muted);font-size:12px;">{{ $biz->number }}</td>
        <td>
          @if($biz->trashed())
            <span class="badge blocked">Deleted</span>
          @else
            <span class="badge {{ $biz->status }}">{{ ucfirst($biz->status) }}</span>
          @endif
        </td>
        <td>
          <div style="display:flex;gap:6px;align-items:center;">
            @if(!$biz->trashed())
              @if($biz->status !== 'active')
                <form method="POST" action="{{ route('admin.workers.approve', $biz->id) }}">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn-ghost" style="padding:5px 10px;font-size:11px;color:var(--green-600);">Approve</button>
                </form>
              @endif
              @if($biz->status !== 'rejected')
                <form method="POST" action="{{ route('admin.workers.reject', $biz->id) }}">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn-ghost" style="padding:5px 10px;font-size:11px;">Reject</button>
                </form>
              @endif
              <form method="POST" action="{{ route('admin.workers.destroy', $biz->id) }}"
                    onsubmit="return confirm('Delete {{ $biz->name }}? The record will be kept for reference.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">Delete</button>
              </form>
            @else
              <span style="font-size:11px;color:var(--text-muted);">
                Deleted {{ $biz->deleted_at->diffForHumans() }}
              </span>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="5"><div class="empty-state"><i class="ti ti-tools"></i>No workers found</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
