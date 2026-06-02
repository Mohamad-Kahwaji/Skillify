@extends('super_admin.layout')

@section('title', 'Dashboard')
@section('breadcrumb', 'لوحة التحكم')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">مرحباً بك، {{ Auth::guard('super_admins')->user()->first_name }}</div>
    <div class="page-sub">نظرة عامة على النظام</div>
  </div>
  <a href="{{ route('super_admin.admins.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> إضافة أدمن
  </a>
</div>

{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon green"><i class="ti ti-user-shield"></i></div>
    </div>
    <div class="stat-value">{{ $admins->count() }}</div>
    <div class="stat-label">إجمالي المديرين</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon blue"><i class="ti ti-users"></i></div>
    </div>
    <div class="stat-value">{{ $totalUsers }}</div>
    <div class="stat-label">إجمالي المستخدمين</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon amber"><i class="ti ti-briefcase"></i></div>
    </div>
    <div class="stat-value">{{ $totalBiz }}</div>
    <div class="stat-label">الأعمال المسجلة</div>
  </div>
</div>

{{-- Admins Table --}}
<div class="card">
  <div class="card-head">
    <div class="card-title">المديرون</div>
    <a href="{{ route('super_admin.admins.index') }}" style="font-size:12px;color:var(--accent);">عرض الكل</a>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>المدير</th>
        <th>الدور</th>
        <th>تاريخ الإنشاء</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($admins->take(8) as $admin)
      <tr>
        <td>
          <div class="cell-user">
            <div class="avatar" style="background:var(--accent);">
              {{ strtoupper(substr($admin->first_name, 0, 1)) }}
            </div>
            <div>
              <div class="cell-name">{{ $admin->first_name }} {{ $admin->last_name }}</div>
              <div class="cell-email">{{ $admin->email }}</div>
            </div>
          </div>
        </td>
        <td><span class="badge admin">{{ $admin->role ?? 'admin' }}</span></td>
        <td style="color:var(--text-muted);">{{ $admin->created_at->format('Y/m/d') }}</td>
        <td>
          <form method="POST" action="{{ route('super_admin.admins.destroy', $admin) }}"
                onsubmit="return confirm('حذف هذا الأدمن؟')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger">
              <i class="ti ti-trash"></i> حذف
            </button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--text-muted);">لا يوجد مديرون بعد</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
