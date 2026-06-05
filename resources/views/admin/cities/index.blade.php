@extends('admin.layout')
@section('title', 'Cities')
@section('breadcrumb', 'Cities')

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">Cities</div>
    <div class="page-sub">{{ $cities->count() }} cities registered</div>
  </div>
  <a href="{{ route('admin.cities.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Add City
  </a>
</div>

@if(session('success'))
  <div class="alert success"><i class="ti ti-check"></i> {{ session('success') }}</div>
@endif

<div class="card">
  <div class="card-head">
    <span class="card-title">All Cities</span>
    <span style="font-size:12px;color:var(--text-muted);">Grouped by governorate</span>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>City</th>
        <th>Governorate</th>
        <th>Coordinates</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($cities as $city)
      <tr>
        <td>
          <div style="font-weight:500;">{{ $city->name_en }}</div>
          <div style="font-size:11px;color:var(--text-muted);" dir="rtl">{{ $city->name_ar }}</div>
        </td>
        <td>
          <div style="font-size:13px;">{{ $city->governorate_en }}</div>
          <div style="font-size:11px;color:var(--text-muted);" dir="rtl">{{ $city->governorate_ar }}</div>
        </td>
        <td>
          <code style="font-size:11px;background:var(--bg-sunken);padding:3px 8px;border-radius:4px;color:var(--text-secondary);">
            {{ $city->latitude }}, {{ $city->longitude }}
          </code>
        </td>
        <td>
          <div style="display:flex;gap:6px;">
            <a href="{{ route('admin.cities.edit', $city->id) }}" class="btn-ghost" style="padding:5px 12px;font-size:12px;">
              <i class="ti ti-edit"></i> Edit
            </a>
            <form method="POST" action="{{ route('admin.cities.destroy', $city->id) }}"
                  onsubmit="return confirm('Delete {{ $city->name_en }}?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger"><i class="ti ti-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="4"><div class="empty-state"><i class="ti ti-map-pin"></i>No cities added yet</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
