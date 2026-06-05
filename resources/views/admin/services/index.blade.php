@extends('admin.layout')
@section('title', 'Services')
@section('breadcrumb', 'Services')

@section('styles')
<style>
  .status-toggle {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;
    border: none; cursor: pointer; font-family: var(--font);
    transition: background 0.12s;
  }
  .status-toggle.active   { background: var(--green-50); color: var(--green-800); }
  .status-toggle.inactive { background: var(--bg-sunken); color: var(--text-muted); border: 0.5px solid var(--border-md); }
  .filter-tabs { display: flex; gap: 6px; }
  .filter-tab {
    padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 500;
    border: 0.5px solid var(--border-md); background: var(--bg-sunken);
    color: var(--text-secondary); cursor: pointer; transition: all 0.12s; text-decoration: none;
  }
  .filter-tab.active, .filter-tab:hover { background: var(--accent-bg); color: var(--accent); border-color: var(--accent); }
</style>
@endsection

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">Services</div>
    <div class="page-sub">
      {{ $services->count() }} total &middot;
      {{ $services->where('is_active', true)->count() }} active &middot;
      {{ $services->where('is_active', false)->count() }} inactive
    </div>
  </div>
  <div class="filter-tabs">
    <a href="{{ route('admin.services.index') }}" class="filter-tab active">All</a>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">All Services</span>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Service</th>
        <th>Category</th>
        <th>City</th>
        <th>Price</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($services as $svc)
      <tr>
        <td>
          <div class="cell-user">
            @if($svc->image)
              <img src="{{ $svc->image }}" alt="" style="width:36px;height:36px;border-radius:8px;object-fit:cover;flex-shrink:0;">
            @else
              <div class="avatar" style="background:var(--accent);border-radius:8px;width:36px;height:36px;">
                {{ strtoupper(substr($svc->name, 0, 1)) }}
              </div>
            @endif
            <div>
              <div class="cell-name">{{ $svc->name }}</div>
              <div class="cell-email">{{ Str::limit($svc->description, 45) }}</div>
            </div>
          </div>
        </td>
        <td>
          <div style="font-size:12px;font-weight:500;">{{ $svc->category }}</div>
          <div style="font-size:11px;color:var(--text-muted);">{{ $svc->subcategory }}</div>
        </td>
        <td style="color:var(--text-secondary);font-size:12px;">{{ $svc->city }}</td>
        <td>
          <span style="font-weight:600;font-size:13px;">{{ number_format($svc->price) }}</span>
          <span style="font-size:11px;color:var(--text-muted);margin-right:2px;">{{ strtoupper($svc->price_type) }}</span>
        </td>
        <td>
          <form method="POST" action="{{ route('admin.services.toggle', $svc->id) }}">
            @csrf @method('PATCH')
            <button type="submit" class="status-toggle {{ $svc->is_active ? 'active' : 'inactive' }}">
              <i class="ti {{ $svc->is_active ? 'ti-circle-check' : 'ti-circle-x' }}" style="font-size:14px;"></i>
              {{ $svc->is_active ? 'Active' : 'Inactive' }}
            </button>
          </form>
        </td>
        <td>
          <div style="display:flex;gap:5px;">
            <a href="{{ route('admin.services.show', $svc->id) }}" class="btn-ghost" style="padding:5px 10px;font-size:12px;">
              <i class="ti ti-eye"></i>
            </a>
            <form method="POST" action="{{ route('admin.services.destroy', $svc->id) }}"
                  onsubmit="return confirm('Delete this service?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger"><i class="ti ti-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6"><div class="empty-state"><i class="ti ti-list-check"></i>No services found</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
