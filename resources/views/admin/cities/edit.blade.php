@extends('admin.layout')
@section('title', 'Edit City')
@section('breadcrumb', 'Edit City')

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">Edit City</div>
    <div class="page-sub">{{ $city->name_en }} — {{ $city->governorate_en }}</div>
  </div>
  <a href="{{ route('admin.cities.index') }}" class="btn-ghost">
    <i class="ti ti-arrow-right"></i> Back
  </a>
</div>

<div class="card" style="max-width:560px;">
  <div class="card-head"><span class="card-title">City Information</span></div>
  <div style="padding:24px;">
    @if($errors->any())
      <div class="alert error"><i class="ti ti-alert-circle"></i> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.cities.update', $city->id) }}">
      @csrf @method('PUT')

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div style="margin-bottom:16px;">
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">City Name (English)</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-map-pin" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="text" name="name_en" value="{{ old('name_en', $city->name_en) }}" required
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
          @error('name_en')<div style="font-size:11px;color:var(--red-400);margin-top:4px;">{{ $message }}</div>@enderror
        </div>
        <div style="margin-bottom:16px;">
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">City Name (Arabic)</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-map-pin" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="text" name="name_ar" value="{{ old('name_ar', $city->name_ar) }}" required dir="rtl"
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
          @error('name_ar')<div style="font-size:11px;color:var(--red-400);margin-top:4px;">{{ $message }}</div>@enderror
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div style="margin-bottom:16px;">
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Governorate (English)</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-building" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="text" name="governorate_en" value="{{ old('governorate_en', $city->governorate_en) }}" required
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
          @error('governorate_en')<div style="font-size:11px;color:var(--red-400);margin-top:4px;">{{ $message }}</div>@enderror
        </div>
        <div style="margin-bottom:16px;">
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Governorate (Arabic)</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-building" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="text" name="governorate_ar" value="{{ old('governorate_ar', $city->governorate_ar) }}" required dir="rtl"
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
          @error('governorate_ar')<div style="font-size:11px;color:var(--red-400);margin-top:4px;">{{ $message }}</div>@enderror
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px;">
        <div>
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Latitude</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-location" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="number" name="latitude" value="{{ old('latitude', $city->latitude) }}" required step="0.0000001"
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
          @error('latitude')<div style="font-size:11px;color:var(--red-400);margin-top:4px;">{{ $message }}</div>@enderror
        </div>
        <div>
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Longitude</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-location" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="number" name="longitude" value="{{ old('longitude', $city->longitude) }}" required step="0.0000001"
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
          @error('longitude')<div style="font-size:11px;color:var(--red-400);margin-top:4px;">{{ $message }}</div>@enderror
        </div>
      </div>

      <div style="display:flex;gap:10px;">
        <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">
          <i class="ti ti-check"></i> Save Changes
        </button>
        <a href="{{ route('admin.cities.index') }}" class="btn-ghost">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
