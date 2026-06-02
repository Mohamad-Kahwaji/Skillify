@extends('admin.layout')

@section('title', 'New Ad')
@section('breadcrumb', 'Ads / New')

@section('styles')
<style>
  .form-card { max-width: 640px; }
  .form-group { margin-bottom: 16px; }
  .form-label { display: block; font-size: 12px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px; }
  .form-control {
    width: 100%; padding: 9px 12px;
    background: var(--bg-sunken);
    border: 0.5px solid var(--border-md);
    border-radius: var(--radius-sm);
    font-size: 13px; color: var(--text-primary);
    font-family: var(--font);
    transition: border-color 0.15s;
    outline: none;
  }
  .form-control:focus { border-color: var(--accent); }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .form-footer { display: flex; gap: 10px; padding: 16px 20px; border-top: 0.5px solid var(--border); }
</style>
@endsection

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">New Advertisement</div>
    <div class="page-sub">Create a new sponsored advertisement</div>
  </div>
  <a href="{{ route('admin.ads.index') }}" class="btn-ghost">
    <i class="ti ti-arrow-left" style="font-size:15px;"></i> Back
  </a>
</div>

<div class="card form-card">
  <form method="POST" action="{{ route('admin.ads.store') }}">
    @csrf
    <input type="hidden" name="admin_id" value="{{ Auth::guard('admins')->id() }}">

    <div style="padding:20px;">
      <div class="form-group">
        <label class="form-label">Title *</label>
        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
        @error('title')<div style="color:var(--red-400);font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Company Name</label>
        <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}">
      </div>

      <div class="form-group">
        <label class="form-label">Image URL</label>
        <input type="text" name="image" class="form-control" value="{{ old('image') }}">
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Start Date</label>
          <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
        </div>
        <div class="form-group">
          <label class="form-label">End Date</label>
          <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
          <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="active"  {{ old('status') === 'active'  ? 'selected' : '' }}>Active</option>
        </select>
      </div>
    </div>

    <div class="form-footer">
      <button type="submit" class="btn-primary"><i class="ti ti-plus"></i> Create Ad</button>
      <a href="{{ route('admin.ads.index') }}" class="btn-ghost">Cancel</a>
    </div>
  </form>
</div>

@endsection
