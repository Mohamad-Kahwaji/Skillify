@extends('admin.layout')
@section('title', 'Service Details')
@section('breadcrumb', 'Service Details')

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">{{ $service->name }}</div>
    <div class="page-sub">{{ $service->category }} &rarr; {{ $service->subcategory }}</div>
  </div>
  <div style="display:flex;gap:8px;">
    <form method="POST" action="{{ route('admin.services.toggle', $service->id) }}">
      @csrf @method('PATCH')
      <button type="submit" class="{{ $service->is_active ? 'btn-danger' : 'btn-primary' }}" style="font-size:13px;">
        <i class="ti {{ $service->is_active ? 'ti-ban' : 'ti-circle-check' }}"></i>
        {{ $service->is_active ? 'Deactivate' : 'Activate' }}
      </button>
    </form>
    <a href="{{ route('admin.services.index') }}" class="btn-ghost">
      <i class="ti ti-arrow-right"></i> Back
    </a>
  </div>
</div>

<div class="content-grid" style="grid-template-columns:1fr 300px;">

  {{-- Main Info --}}
  <div class="card">
    <div class="card-head">
      <span class="card-title">Service Information</span>
      <span class="badge {{ $service->is_active ? 'active' : 'inactive' }}">
        {{ $service->is_active ? 'Active' : 'Inactive' }}
      </span>
    </div>

    @if($service->image)
    <div style="padding:16px 20px 0;">
      <img src="{{ $service->image }}" alt="{{ $service->name }}"
           style="width:100%;max-height:240px;object-fit:cover;border-radius:10px;border:0.5px solid var(--border);">
    </div>
    @endif

    <div style="padding:20px;">
      <div style="margin-bottom:16px;">
        <div style="font-size:11px;font-weight:500;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Description</div>
        <p style="font-size:13px;color:var(--text-secondary);line-height:1.7;">{{ $service->description ?? 'No description provided.' }}</p>
      </div>
    </div>
  </div>

  {{-- Side Info --}}
  <div style="display:flex;flex-direction:column;gap:14px;">
    <div class="card">
      <div class="card-head"><span class="card-title">Details</span></div>
      <div style="padding:16px;">
        @php
        $rows = [
          ['icon' => 'ti-category',    'label' => 'Category',    'value' => $service->category],
          ['icon' => 'ti-category-2',  'label' => 'Subcategory', 'value' => $service->subcategory],
          ['icon' => 'ti-map-pin',     'label' => 'City',        'value' => $service->city],
          ['icon' => 'ti-coin',        'label' => 'Price',       'value' => number_format($service->price) . ' ' . strtoupper($service->price_type)],
          ['icon' => 'ti-calendar',    'label' => 'Created',     'value' => $service->created_at->format('M d, Y')],
        ];
        @endphp
        @foreach($rows as $row)
        <div style="display:flex;align-items:center;gap:8px;padding:9px 0;border-bottom:0.5px solid var(--border);">
          <i class="ti {{ $row['icon'] }}" style="font-size:16px;color:var(--text-muted);flex-shrink:0;width:20px;"></i>
          <span style="font-size:12px;color:var(--text-secondary);width:90px;flex-shrink:0;">{{ $row['label'] }}</span>
          <span style="font-size:13px;font-weight:500;">{{ $row['value'] }}</span>
        </div>
        @endforeach
        <div style="display:flex;align-items:center;gap:8px;padding:9px 0;">
          <i class="ti ti-toggle-right" style="font-size:16px;color:var(--text-muted);flex-shrink:0;width:20px;"></i>
          <span style="font-size:12px;color:var(--text-secondary);width:90px;flex-shrink:0;">Status</span>
          <span class="badge {{ $service->is_active ? 'active' : 'inactive' }}">
            {{ $service->is_active ? 'Active' : 'Inactive' }}
          </span>
        </div>
      </div>
    </div>

    <div class="card" style="padding:16px;">
      <form method="POST" action="{{ route('admin.services.destroy', $service->id) }}"
            onsubmit="return confirm('Permanently delete this service?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-danger" style="width:100%;justify-content:center;">
          <i class="ti ti-trash"></i> Delete Service
        </button>
      </form>
    </div>
  </div>

</div>
@endsection
