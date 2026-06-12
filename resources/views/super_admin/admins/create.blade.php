@extends('super_admin.layout')

@section('title', 'Add Admin')
@section('breadcrumb', 'Add New Admin')

@section('styles')
.create-wrap { max-width: 560px; }
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.field { margin-bottom: 18px; }
.field-label {
  display: block; font-size: 12px; font-weight: 600;
  color: var(--text-secondary); margin-bottom: 7px; letter-spacing: 0.2px;
}
.field-optional { font-size: 10px; font-weight: 400; color: var(--text-muted); margin-left: 4px; }
.field-input {
  display: flex; align-items: center;
  background: var(--bg-sunken);
  border: 0.5px solid var(--border-md);
  border-radius: 9px; overflow: hidden;
  transition: border-color 0.15s, box-shadow 0.15s;
}
.field-input:focus-within {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(29,158,117,.10);
}
.field-input > i { padding: 0 11px; font-size: 16px; color: var(--text-muted); flex-shrink: 0; }
.field-input input,
.field-input select {
  flex: 1; border: none; outline: none;
  background: transparent; padding: 10px 12px 10px 0;
  font-size: 13px; color: var(--text-primary); font-family: var(--font);
}
.field-input select option { background: var(--bg-surface); }
.pw-toggle {
  padding: 0 11px; background: none; border: none;
  color: var(--text-muted); font-size: 16px; flex-shrink: 0;
  display: flex; align-items: center; cursor: pointer; transition: color 0.12s;
}
.pw-toggle:hover { color: var(--text-primary); }
.field-error {
  display: flex; align-items: center; gap: 5px;
  font-size: 11px; color: #f09595; margin-top: 6px;
}
.field-error::before { content: ''; width: 4px; height: 4px; border-radius: 50%; background: #f09595; flex-shrink: 0; }
.form-divider {
  height: 0.5px; background: var(--border); margin: 24px 0;
}
.form-section-title {
  font-size: 11px; font-weight: 600; color: var(--text-muted);
  letter-spacing: 1px; text-transform: uppercase; margin-bottom: 16px;
}
.btn-create {
  display: inline-flex; align-items: center; justify-content: center; gap: 8px;
  width: 100%; padding: 12px;
  background: var(--accent); color: #fff;
  border: none; border-radius: 9px;
  font-size: 14px; font-weight: 600; font-family: var(--font);
  cursor: pointer; transition: background 0.15s, box-shadow 0.15s;
  box-shadow: 0 2px 10px rgba(29,158,117,.20);
}
.btn-create:hover { background: var(--accent-hover); box-shadow: 0 4px 14px rgba(29,158,117,.28); }
.btn-create.loading { pointer-events: none; opacity: 0.75; }
.btn-create .spinner {
  display: none; width: 16px; height: 16px;
  border: 2px solid rgba(255,255,255,.3);
  border-top-color: #fff; border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
.btn-create.loading .spinner { display: block; }
.btn-create.loading .btn-label { display: none; }
@keyframes spin { to { transform: rotate(360deg); } }
@endsection

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Add New Admin</div>
    <div class="page-sub">New admin will have access to the control panel based on their assigned role</div>
  </div>
  <a href="{{ route('super_admin.admins.index') }}" class="btn-ghost">
    <i class="ti ti-arrow-left"></i> Back to Admins
  </a>
</div>

<div class="card create-wrap">
  <div class="card-head">
    <span class="card-title">Admin Account Details</span>
  </div>
  <div style="padding: 24px;">

    @if($errors->any())
      <div class="alert error" style="margin-bottom:20px;">
        <i class="ti ti-alert-circle"></i>
        <span>{{ $errors->first() }}</span>
      </div>
    @endif

    <form method="POST" action="{{ route('super_admin.admins.store') }}" id="createForm">
      @csrf

      {{-- Name --}}
      <div class="form-section-title">Personal Information</div>
      <div class="form-grid-2">
        <div class="field">
          <label class="field-label">First Name</label>
          <div class="field-input">
            <i class="ti ti-user"></i>
            <input type="text" name="first_name" value="{{ old('first_name') }}"
                   placeholder="John" required autofocus>
          </div>
          @error('first_name')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>
        <div class="field">
          <label class="field-label">Last Name</label>
          <div class="field-input">
            <i class="ti ti-user"></i>
            <input type="text" name="last_name" value="{{ old('last_name') }}"
                   placeholder="Doe" required>
          </div>
          @error('last_name')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="field">
        <label class="field-label">Email Address</label>
        <div class="field-input">
          <i class="ti ti-mail"></i>
          <input type="email" name="email" value="{{ old('email') }}"
                 placeholder="admin@hirfa.com" required>
        </div>
        @error('email')
          <div class="field-error">{{ $message }}</div>
        @enderror
      </div>

      <div class="field">
        <label class="field-label">Phone Number <span class="field-optional">(optional)</span></label>
        <div class="field-input">
          <i class="ti ti-phone"></i>
          <input type="text" name="phone" value="{{ old('phone') }}"
                 placeholder="+961 xx xxx xxx">
        </div>
      </div>

      <div class="form-divider"></div>

      {{-- Role & Access --}}
      <div class="form-section-title">Role & Access</div>
      <div class="field">
        <label class="field-label">Assign Role</label>
        <div class="field-input">
          <i class="ti ti-shield"></i>
          <select name="role" required>
            <option value="admin"     {{ old('role') === 'admin'     ? 'selected' : '' }}>Admin</option>
            <option value="moderator" {{ old('role') === 'moderator' ? 'selected' : '' }}>Moderator</option>
          </select>
        </div>
        @error('role')
          <div class="field-error">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-divider"></div>

      {{-- Password --}}
      <div class="form-section-title">Security</div>
      <div class="form-grid-2">
        <div class="field">
          <label class="field-label">Password</label>
          <div class="field-input">
            <i class="ti ti-lock"></i>
            <input type="password" id="pw1" name="password"
                   placeholder="••••••••" required minlength="8" autocomplete="new-password">
            <button type="button" class="pw-toggle" onclick="togglePw('pw1', this)">
              <i class="ti ti-eye"></i>
            </button>
          </div>
          @error('password')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>
        <div class="field">
          <label class="field-label">Confirm Password</label>
          <div class="field-input">
            <i class="ti ti-lock-check"></i>
            <input type="password" id="pw2" name="password_confirmation"
                   placeholder="••••••••" required autocomplete="new-password">
            <button type="button" class="pw-toggle" onclick="togglePw('pw2', this)">
              <i class="ti ti-eye"></i>
            </button>
          </div>
        </div>
      </div>

      <button type="submit" class="btn-create" id="submitBtn">
        <div class="spinner"></div>
        <span class="btn-label"><i class="ti ti-user-plus" style="font-size:16px;"></i> Create Admin Account</span>
      </button>
    </form>

  </div>
</div>

@endsection

@section('scripts')
<script>
  function togglePw(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'ti ti-eye' : 'ti ti-eye-off';
  }
  document.getElementById('createForm').addEventListener('submit', function () {
    document.getElementById('submitBtn').classList.add('loading');
  });
</script>
@endsection
