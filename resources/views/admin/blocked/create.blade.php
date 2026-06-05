@extends('admin.layout')
@section('title', 'Block User')
@section('breadcrumb', 'Block User')

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">Block a User</div>
    <div class="page-sub">The user will be deactivated immediately</div>
  </div>
  <a href="{{ route('admin.blocked.index') }}" class="btn-ghost">
    <i class="ti ti-arrow-right"></i> Back
  </a>
</div>

<div class="card" style="max-width:520px;">
  <div class="card-head">
    <span class="card-title">Block Details</span>
  </div>
  <div style="padding:24px;">
    @if($errors->any())
      <div class="alert error"><i class="ti ti-alert-circle"></i> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.blocked.store') }}">
      @csrf

      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">User to Block</label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-user" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <select name="user_id" required
                  style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
            <option value="">Select user...</option>
            @foreach($users as $user)
              <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                {{ $user->first_name }} {{ $user->last_name }} — {{ $user->email }}
              </option>
            @endforeach
          </select>
        </div>
        @error('user_id')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Block Reason</label>
        <div style="background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <textarea name="reason" rows="3" required placeholder="Describe why this user is being blocked..."
                    style="width:100%;border:none;outline:none;background:transparent;padding:10px 14px;font-size:13px;color:var(--text-primary);font-family:var(--font);resize:vertical;">{{ old('reason') }}</textarea>
        </div>
        @error('reason')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:24px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">Block Date <span style="color:var(--text-muted);font-weight:400;">(optional — defaults to today)</span></label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-calendar" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <input type="date" name="blocker_date" value="{{ old('blocker_date') }}"
                 style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
        </div>
      </div>

      <div style="background:#FAEEDA;border-radius:10px;padding:12px 14px;margin-bottom:20px;font-size:12px;color:#633806;display:flex;gap:8px;align-items:flex-start;">
        <i class="ti ti-alert-triangle" style="font-size:16px;flex-shrink:0;margin-top:1px;"></i>
        <span>This action will immediately deactivate the user's account. They will be logged out and unable to access the platform.</span>
      </div>

      <div style="display:flex;gap:10px;">
        <button type="submit" style="flex:1;padding:11px;background:var(--red-400);color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:500;font-family:var(--font);cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
          <i class="ti ti-ban"></i> Block User
        </button>
        <a href="{{ route('admin.blocked.index') }}" class="btn-ghost">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
