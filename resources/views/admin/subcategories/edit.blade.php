@extends('admin.layout')
@section('title', 'Edit Subcategory')
@section('breadcrumb', 'Edit Subcategory')

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">Edit Subcategory</div>
    <div class="page-sub">{{ $subcategory->name_en }} — {{ $subcategory->name_ar }}</div>
  </div>
  <a href="{{ route('admin.subcategories.index') }}" class="btn-ghost">
    <i class="ti ti-arrow-right"></i> Back
  </a>
</div>

<div class="card" style="max-width:520px;">
  <div class="card-head"><span class="card-title">Subcategory Details</span></div>
  <div style="padding:24px;">
    @if($errors->any())
      <div class="alert error"><i class="ti ti-alert-circle"></i> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.subcategories.update', $subcategory->id) }}">
      @csrf @method('PUT')

      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Parent Category</label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-category" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <select name="category_id" required
                  style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ $subcategory->category_id == $cat->id ? 'selected' : '' }}>
                {{ $cat->name_en }} — {{ $cat->name_ar }}
              </option>
            @endforeach
          </select>
        </div>
        @error('category_id')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Name (Arabic)</label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-typography" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <input type="text" name="name_ar" value="{{ old('name_ar', $subcategory->name_ar) }}" required dir="rtl"
                 style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
        </div>
        @error('name_ar')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:24px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Name (English)</label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-typography" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <input type="text" name="name_en" value="{{ old('name_en', $subcategory->name_en) }}" required
                 style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
        </div>
        @error('name_en')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <div style="display:flex;gap:10px;">
        <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">
          <i class="ti ti-check"></i> Save Changes
        </button>
        <a href="{{ route('admin.subcategories.index') }}" class="btn-ghost">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
