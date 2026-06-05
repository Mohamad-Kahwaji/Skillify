@extends('admin.layout')
@section('title', 'Categories')
@section('breadcrumb', 'Categories')

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">Categories</div>
    <div class="page-sub">{{ $categories->count() }} category(s) registered</div>
  </div>
</div>

<div class="content-grid" style="grid-template-columns:1fr 360px;">

  <div class="card">
    <div class="card-head">
      <span class="card-title">Categories List</span>
    </div>
    <table class="data-table">
      <thead>
        <tr><th>#</th><th>Name</th><th>Business Type</th><th></th></tr>
      </thead>
      <tbody>
        @forelse($categories as $cat)
        <tr>
          <td style="color:var(--text-muted);">{{ $cat->id }}</td>
          <td>
            <div style="font-weight:500;">{{ $cat->name_ar }}</div>
            <div style="font-size:11px;color:var(--text-muted);">{{ $cat->name_en }}</div>
          </td>
          <td>
            <span class="badge active">{{ $cat->activeTypebusiness?->name_ar ?? '—' }}</span>
          </td>
          <td>
            <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}"
                  onsubmit="return confirm('Delete this category?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger"><i class="ti ti-trash"></i> Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="4"><div class="empty-state"><i class="ti ti-category"></i>No categories yet</div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="card" style="padding:24px;">
    <div class="card-title" style="margin-bottom:20px;">Add Category</div>
    @if(session('success'))
      <div class="alert success"><i class="ti ti-check"></i> {{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.categories.store') }}">
      @csrf

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Business Type</label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-briefcase" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <select name="active_typebusiness_id" required
                  style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
            <option value="">Select business type</option>
            @foreach($businessTypes as $bt)
              <option value="{{ $bt->id }}" {{ old('active_typebusiness_id') == $bt->id ? 'selected' : '' }}>
                {{ $bt->name_ar }}
              </option>
            @endforeach
          </select>
        </div>
        @error('active_typebusiness_id')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Name (Arabic)</label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-typography" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <input type="text" name="name_ar" value="{{ old('name_ar') }}" required
                 style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
        </div>
        @error('name_ar')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:20px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Name (English)</label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-typography" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <input type="text" name="name_en" value="{{ old('name_en') }}" required
                 style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
        </div>
        @error('name_en')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
        <i class="ti ti-plus"></i> Add Category
      </button>
    </form>
  </div>

</div>
@endsection
