@extends('super_admin.layout')

@section('title', 'Add Admin')
@section('breadcrumb', 'Add New Admin')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Add New Admin</div>
    <div class="page-sub">This admin will have access to the control panel</div>
  </div>
  <a href="{{ route('super_admin.admins.index') }}" class="btn-primary" style="background:var(--bg-sunken);color:var(--text-secondary);border:0.5px solid var(--border-md);">
    <i class="ti ti-arrow-left"></i> Back
  </a>
</div>

<div class="card" style="max-width:520px;">
  <div class="card-head">
    <div class="card-title">Admin Details</div>
  </div>
  <div style="padding:24px;">
    @if($errors->any())
      <div class="alert error"><i class="ti ti-alert-circle"></i> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('super_admin.admins.store') }}">
      @csrf

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div style="margin-bottom:16px;">
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">First Name</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-user" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="text" name="first_name" value="{{ old('first_name') }}" required
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
          @error('first_name')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
        </div>
        <div style="margin-bottom:16px;">
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Last Name</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-user" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="text" name="last_name" value="{{ old('last_name') }}" required
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
          @error('last_name')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
        </div>
      </div>

      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Email Address</label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-mail" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <input type="email" name="email" value="{{ old('email') }}" required
                 style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
        </div>
        @error('email')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Phone Number <span style="opacity:.6;">(optional)</span></label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-phone" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <input type="text" name="phone" value="{{ old('phone') }}"
                 style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
        </div>
      </div>

      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Role</label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-shield" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <select name="role" required
                  style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
            <option value="admin"     {{ old('role') === 'admin'     ? 'selected' : '' }}>Admin</option>
            <option value="moderator" {{ old('role') === 'moderator' ? 'selected' : '' }}>Moderator</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
        <div>
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Password</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-lock" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="password" name="password" required minlength="8"
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
          @error('password')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
        </div>
        <div>
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Confirm Password</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-lock-check" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="password" name="password_confirmation" required
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
        </div>
      </div>

      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
        <i class="ti ti-user-plus"></i> Create Admin
      </button>
    </form>
  </div>
</div>

@endsection
