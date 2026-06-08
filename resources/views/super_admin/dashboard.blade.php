@extends('super_admin.layout')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('styles')
.stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
@media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
.stat-card { position: relative; overflow: hidden; }
.stat-card .bg-icon {
  position: absolute; bottom: -8px; right: -8px;
  font-size: 68px; opacity: 0.04; pointer-events: none;
}
.stat-delta {
  display: inline-flex; align-items: center; gap: 3px;
  font-size: 11px; padding: 2px 8px; border-radius: 20px; font-weight: 500;
}
.stat-delta.up     { background: rgba(29,158,117,.18);  color: var(--accent-hover); }
.stat-delta.warn   { background: rgba(239,159,39,.18);  color: #fac775; }
.stat-delta.red    { background: rgba(226,75,74,.18);   color: #f09595; }
.stat-delta.purple { background: rgba(139,92,246,.18);  color: #a78bfa; }
.stat-delta.blue   { background: rgba(59,130,246,.18);  color: #60a5fa; }
.stat-delta.muted  { background: rgba(255,255,255,.07); color: var(--text-muted); }

.content-grid { display: grid; grid-template-columns: 1fr 310px; gap: 16px; align-items: start; }
@media (max-width: 900px) { .content-grid { grid-template-columns: 1fr; } }

.side-card { background: var(--bg-surface); border: 0.5px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; }
.side-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 0.5px solid var(--border); }
.side-head-title { font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
.side-row {
  display: flex; align-items: center; gap: 10px;
  padding: 11px 16px; border-bottom: 0.5px solid var(--border);
  transition: background 0.1s;
}
.side-row:last-child { border-bottom: none; }
.side-icon {
  width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center; font-size: 15px;
}
.side-info { flex: 1; min-width: 0; }
.side-name { font-size: 13px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.side-meta { font-size: 11px; color: var(--text-muted); }
.side-empty { padding: 28px 16px; text-align: center; color: var(--text-muted); font-size: 13px; }
.side-empty i { font-size: 28px; display: block; margin-bottom: 6px; color: var(--accent); }
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-head">
  <div>
    <div class="page-title">Welcome, {{ Auth::guard('super_admins')->user()->first_name }}</div>
    <div class="page-sub">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</div>
  </div>
  <a href="{{ route('super_admin.admins.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Add Admin
  </a>
</div>

{{-- Stats Grid --}}
<div class="stats-grid">

  {{-- Admins --}}
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon green"><i class="ti ti-user-shield"></i></div>
      <span class="stat-delta up">Platform</span>
    </div>
    <div class="stat-value">{{ $admins->count() }}</div>
    <div class="stat-label">Total Admins</div>
    <i class="ti ti-user-shield bg-icon"></i>
  </div>

  {{-- Users --}}
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon blue"><i class="ti ti-users"></i></div>
      @if($newUsersThisWeek > 0)
        <span class="stat-delta blue">+{{ $newUsersThisWeek }} this week</span>
      @else
        <span class="stat-delta muted">No new</span>
      @endif
    </div>
    <div class="stat-value">{{ number_format($totalUsers) }}</div>
    <div class="stat-label">Total Users</div>
    <i class="ti ti-users bg-icon"></i>
  </div>

  {{-- Businesses --}}
  <div class="stat-card" style="{{ $pendingWorkers > 0 ? 'border-color:rgba(239,159,39,.35);' : '' }}">
    <div class="stat-top">
      <div class="stat-icon amber"><i class="ti ti-briefcase"></i></div>
      @if($pendingWorkers > 0)
        <span class="stat-delta warn">{{ $pendingWorkers }} pending</span>
      @else
        <span class="stat-delta up">{{ $activeWorkers }} active</span>
      @endif
    </div>
    <div class="stat-value">{{ number_format($totalBiz) }}</div>
    <div class="stat-label">Registered Businesses</div>
    <i class="ti ti-briefcase bg-icon"></i>
  </div>

  {{-- Posts --}}
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon green"><i class="ti ti-file-text"></i></div>
      <span class="stat-delta up">{{ $postsThisMonth }} this month</span>
    </div>
    <div class="stat-value">{{ number_format($totalPosts) }}</div>
    <div class="stat-label">Total Posts</div>
    <i class="ti ti-file-text bg-icon"></i>
  </div>

  {{-- Reports --}}
  <div class="stat-card" style="{{ $pendingReports > 0 ? 'border-color:rgba(226,75,74,.35);' : '' }}">
    <div class="stat-top">
      <div class="stat-icon" style="background:rgba(226,75,74,.15);color:#f09595;"><i class="ti ti-flag"></i></div>
      @if($pendingReports > 0)
        <span class="stat-delta red">Needs review</span>
      @else
        <span class="stat-delta muted">All clear</span>
      @endif
    </div>
    <div class="stat-value">{{ number_format($pendingReports) }}</div>
    <div class="stat-label">Open Reports</div>
    <i class="ti ti-flag bg-icon"></i>
  </div>

  {{-- Roles & Permissions --}}
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon" style="background:rgba(139,92,246,.15);color:#a78bfa;"><i class="ti ti-lock-access"></i></div>
      <span class="stat-delta purple">{{ $totalPermissions }} permissions</span>
    </div>
    <div class="stat-value">{{ $totalRoles }}</div>
    <div class="stat-label">Roles Configured</div>
    <i class="ti ti-lock-access bg-icon"></i>
  </div>

</div>

{{-- Content Grid --}}
<div class="content-grid">

  {{-- Admins Table --}}
  <div class="card">
    <div class="card-head">
      <span class="card-title">Admins</span>
      <a href="{{ route('super_admin.admins.index') }}" style="font-size:12px;color:var(--accent);">View All →</a>
    </div>
    <div class="table-toolbar">
      <div class="search-field">
        <i class="ti ti-search"></i>
        <input type="text" id="q" placeholder="Search admins…">
      </div>
      <span class="tbl-count" id="tbl-count"></span>
    </div>
    <table class="data-table" id="tbl">
      <thead>
        <tr>
          <th>Admin</th>
          <th>Role</th>
          <th>Status</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        @forelse($admins->take(8) as $admin)
        @php
          $colors = ['#1D9E75','#3B82F6','#8B5CF6','#F59E0B','#EF4444'];
          $color  = $colors[$admin->id % 5];
          $active = ($admin->status ?? 'active') === 'active';
        @endphp
        <tr data-search="{{ strtolower($admin->first_name . ' ' . $admin->last_name . ' ' . $admin->email) }}">
          <td>
            <div class="cell-user">
              <div class="avatar" style="background:{{ $color }};">{{ strtoupper(substr($admin->first_name, 0, 1)) }}</div>
              <div>
                <div class="cell-name">{{ $admin->first_name }} {{ $admin->last_name }}</div>
                <div class="cell-email">{{ $admin->email }}</div>
              </div>
            </div>
          </td>
          <td><span class="badge admin">{{ $admin->role ?? 'admin' }}</span></td>
          <td>
            @if($active)
              <span class="badge active">Active</span>
            @else
              <span class="badge inactive">Inactive</span>
            @endif
          </td>
          <td style="color:var(--text-muted);font-size:12px;">{{ $admin->created_at->format('Y/m/d') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="4" style="text-align:center;padding:48px;color:var(--text-muted);">
            <i class="ti ti-user-shield" style="font-size:32px;display:block;margin-bottom:8px;"></i>
            No admins yet
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Right Column --}}
  <div style="display:flex;flex-direction:column;gap:14px;">

    {{-- Pending Verifications --}}
    <div class="side-card">
      <div class="side-head">
        <span class="side-head-title">
          Verifications
          @if($pendingVerifications->count() > 0)
            <span class="badge pending">{{ $pendingVerifications->count() }}</span>
          @endif
        </span>
        <a href="{{ route('admin.verifications.index') }}" style="font-size:12px;color:var(--accent);">View</a>
      </div>
      @forelse($pendingVerifications as $biz)
      <div class="side-row">
        <div class="side-icon" style="background:rgba(239,159,39,.15);color:#fac775;">
          <i class="ti ti-briefcase"></i>
        </div>
        <div class="side-info">
          <div class="side-name">{{ $biz->name }}</div>
          <div class="side-meta">{{ $biz->activity ?? '—' }} · {{ $biz->created_at->diffForHumans(null, true) }}</div>
        </div>
      </div>
      @empty
      <div class="side-empty">
        <i class="ti ti-circle-check"></i>
        No pending requests
      </div>
      @endforelse
    </div>

    {{-- Recent Reports --}}
    <div class="side-card">
      <div class="side-head">
        <span class="side-head-title">
          Reports
          @if($pendingReports > 0)
            <span class="badge blocked">{{ $pendingReports }}</span>
          @endif
        </span>
        <a href="{{ route('admin.reports.index') }}" style="font-size:12px;color:var(--accent);">View</a>
      </div>
      @forelse($recentReports as $report)
      <div class="side-row">
        <div class="side-icon" style="background:rgba(226,75,74,.12);color:#f09595;">
          <i class="ti ti-flag"></i>
        </div>
        <div class="side-info">
          <div class="side-name">{{ $report->reason ?? 'Report' }}</div>
          <div class="side-meta">
            Post #{{ $report->post_id }}
            @if($report->user) · {{ $report->user->name }} @endif
          </div>
        </div>
        @if($report->post_id)
          <a href="{{ route('admin.reports.post', $report->post_id) }}"
             style="font-size:11px;color:var(--accent);flex-shrink:0;">View</a>
        @endif
      </div>
      @empty
      <div class="side-empty">
        <i class="ti ti-circle-check"></i>
        No reports
      </div>
      @endforelse
    </div>

    {{-- Quick Links --}}
    <div class="side-card">
      <div class="side-head">
        <span class="side-head-title">Access Control</span>
      </div>
      <div style="padding:12px;display:flex;flex-direction:column;gap:6px;">
        <a href="{{ route('super_admin.roles.index') }}"
           style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;background:rgba(139,92,246,.08);color:#a78bfa;font-size:13px;font-weight:500;transition:background .12s;"
           onmouseover="this.style.background='rgba(139,92,246,.14)'" onmouseout="this.style.background='rgba(139,92,246,.08)'">
          <i class="ti ti-lock-access" style="font-size:17px;"></i>
          <span style="flex:1;">Manage Roles</span>
          <span style="font-size:11px;background:rgba(139,92,246,.15);padding:2px 8px;border-radius:20px;">{{ $totalRoles }}</span>
        </a>
        <a href="{{ route('super_admin.permissions.index') }}"
           style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;background:rgba(29,158,117,.08);color:var(--accent-hover);font-size:13px;font-weight:500;transition:background .12s;"
           onmouseover="this.style.background='rgba(29,158,117,.14)'" onmouseout="this.style.background='rgba(29,158,117,.08)'">
          <i class="ti ti-key" style="font-size:17px;"></i>
          <span style="flex:1;">Manage Permissions</span>
          <span style="font-size:11px;background:rgba(29,158,117,.15);padding:2px 8px;border-radius:20px;">{{ $totalPermissions }}</span>
        </a>
        <a href="{{ route('super_admin.admins.index') }}"
           style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;background:rgba(59,130,246,.08);color:#60a5fa;font-size:13px;font-weight:500;transition:background .12s;"
           onmouseover="this.style.background='rgba(59,130,246,.14)'" onmouseout="this.style.background='rgba(59,130,246,.08)'">
          <i class="ti ti-user-shield" style="font-size:17px;"></i>
          <span style="flex:1;">Manage Admins</span>
          <span style="font-size:11px;background:rgba(59,130,246,.15);padding:2px 8px;border-radius:20px;">{{ $admins->count() }}</span>
        </a>
      </div>
    </div>

  </div>
</div>

@endsection

@section('scripts')
<script>
(function(){
  const q   = document.getElementById('q');
  const cnt = document.getElementById('tbl-count');
  function render(){
    const v = q.value.toLowerCase();
    let n = 0;
    document.querySelectorAll('#tbl tbody tr[data-search]').forEach(r => {
      const ok = r.dataset.search.includes(v);
      r.style.display = ok ? '' : 'none';
      if(ok) n++;
    });
    if(cnt) cnt.textContent = n + ' result' + (n !== 1 ? 's' : '');
  }
  q.addEventListener('input', render);
  render();
})();
</script>
@endsection
