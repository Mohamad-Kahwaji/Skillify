@extends('super_admin.layout')

@section('title', 'إضافة أدمن')
@section('breadcrumb', 'إضافة أدمن جديد')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">إضافة مدير جديد</div>
    <div class="page-sub">سيتمكن هذا المدير من الوصول إلى لوحة التحكم</div>
  </div>
  <a href="{{ route('super_admin.admins.index') }}" class="btn-primary" style="background:var(--bg-sunken);color:var(--text-secondary);border:0.5px solid var(--border-md);">
    <i class="ti ti-arrow-right"></i> رجوع
  </a>
</div>

<div class="card" style="max-width:520px;">
  <div class="card-head">
    <div class="card-title">بيانات المدير</div>
  </div>
  <div style="padding:24px;">
    @if($errors->any())
      <div class="alert error"><i class="ti ti-alert-circle"></i> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('super_admin.admins.store') }}">
      @csrf

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div style="margin-bottom:16px;">
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">الاسم الأول</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-user" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="text" name="first_name" value="{{ old('first_name') }}" required
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
          @error('first_name')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
        </div>
        <div style="margin-bottom:16px;">
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">الاسم الأخير</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-user" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="text" name="last_name" value="{{ old('last_name') }}" required
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
          @error('last_name')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
        </div>
      </div>

      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">البريد الإلكتروني</label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-mail" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <input type="email" name="email" value="{{ old('email') }}" required
                 style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
        </div>
        @error('email')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">رقم الهاتف (اختياري)</label>
        <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
          <i class="ti ti-phone" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
          <input type="text" name="phone" value="{{ old('phone') }}"
                 style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
        </div>
      </div>

      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">الدور</label>
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
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">كلمة المرور</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-lock" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="password" name="password" required minlength="8"
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
          @error('password')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
        </div>
        <div>
          <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:6px;">تأكيد كلمة المرور</label>
          <div style="display:flex;align-items:center;background:var(--bg-sunken);border:0.5px solid var(--border-md);border-radius:8px;overflow:hidden;">
            <i class="ti ti-lock-check" style="padding:0 11px;font-size:16px;color:var(--text-muted);"></i>
            <input type="password" name="password_confirmation" required
                   style="flex:1;border:none;outline:none;background:transparent;padding:10px 12px 10px 0;font-size:13px;color:var(--text-primary);font-family:var(--font);">
          </div>
        </div>
      </div>

      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
        <i class="ti ti-user-plus"></i> إنشاء المدير
      </button>
    </form>
  </div>
</div>

@endsection
