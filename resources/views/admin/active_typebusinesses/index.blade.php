@extends('admin.layout')
@section('title', 'Business Types')
@section('breadcrumb', 'Business Types')

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">Business Types</div>
    <div class="page-sub">{{ $types->count() }} type(s) — profession, craft, workshop, company</div>
  </div>
</div>

<div class="content-grid" style="grid-template-columns:1fr 340px;">

  <div class="card">
    <div class="card-head"><span class="card-title">Types List</span></div>
    <table class="data-table">
      <thead>
        <tr><th>#</th><th>Name (Arabic)</th><th>Name (English)</th><th></th></tr>
      </thead>
      <tbody>
        @forelse($types as $type)
        <tr>
          <td style="color:var(--text-muted);">{{ $type->id }}</td>
          <td style="font-weight:500;">{{ $type->name_ar }}</td>
          <td style="color:var(--text-secondary);">{{ $type->name_en }}</td>
          <td>
            <form method="POST" action="{{ route('admin.active_typebusinesses.destroy', $type->id) }}"
                  onsubmit="return confirm('Delete this type?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger"><i class="ti ti-trash"></i> Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="4"><div class="empty-state"><i class="ti ti-list"></i>No types yet</div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="card" style="padding:24px;">
    <div class="card-title" style="margin-bottom:20px;">Add New Type</div>
    @if(session('success'))
      <div class="alert success"><i class="ti ti-check"></i> {{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.active_typebusinesses.store') }}">
      @csrf
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Name (Arabic)</label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-typography" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <input type="text" name="name_ar" value="{{ old('name_ar') }}" required placeholder="e.g. Company (in Arabic)"
                 style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
        </div>
        @error('name_ar')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>
      <div style="margin-bottom:20px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Name (English)</label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-typography" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <input type="text" name="name_en" value="{{ old('name_en') }}" required placeholder="e.g. Company"
                 style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
        </div>
        @error('name_en')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>
      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
        <i class="ti ti-plus"></i> Add
      </button>
    </form>
  </div>

</div>
@endsection
