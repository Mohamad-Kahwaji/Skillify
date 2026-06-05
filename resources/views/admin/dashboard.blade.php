@extends('admin.layout')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('styles')
<style>
  .alert-banner {
    display: flex; align-items: center; gap: 12px;
    background: #FAEEDA; border: 0.5px solid #F5C87A;
    border-radius: var(--radius-md); padding: 12px 18px;
    font-size: 13px; color: #633806;
  }
  .alert-banner i { font-size: 18px; color: #BA7517; flex-shrink: 0; }
  .alert-banner-actions { display: flex; gap: 8px; margin-right: auto; }
  .alert-banner a {
    font-size: 12px; font-weight: 500; color: #633806;
    background: rgba(186,117,23,.12); border-radius: 6px;
    padding: 4px 10px; transition: background 0.12s;
  }
  .alert-banner a:hover { background: rgba(186,117,23,.22); }

  .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
  @media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(3, 1fr); } }
  @media (max-width: 720px)  { .stats-grid { grid-template-columns: 1fr 1fr; } }

  .stat-card { position: relative; overflow: hidden; }
  .stat-card .stat-bg-icon {
    position: absolute; bottom: -6px; right: -6px;
    font-size: 64px; color: currentColor; opacity: 0.04;
    pointer-events: none;
  }
  .stat-delta {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: 11px; padding: 2px 7px; border-radius: 20px; font-weight: 500;
  }
  .stat-delta.up   { background: var(--green-50); color: var(--green-800); }
  .stat-delta.warn { background: #FAEEDA; color: #633806; }
  .stat-delta.red  { background: var(--red-50); color: var(--red-800); }
  .stat-delta.neutral { background: var(--bg-sunken); color: var(--text-muted); }

  .content-grid { display: grid; grid-template-columns: 1fr 320px; gap: 16px; align-items: start; }
  @media (max-width: 960px) { .content-grid { grid-template-columns: 1fr; } }

  .panel-right { display: flex; flex-direction: column; gap: 14px; }

  .verif-item {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; border-bottom: 0.5px solid var(--border);
    transition: background 0.1s;
  }
  .verif-item:last-child { border-bottom: none; }
  .verif-icon {
    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 16px;
  }
  .verif-info { flex: 1; min-width: 0; }
  .verif-name { font-size: 13px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .verif-meta { font-size: 11px; color: var(--text-muted); }
  .verif-actions { display: flex; gap: 5px; flex-shrink: 0; }
  .btn-approve {
    display: flex; align-items: center; gap: 4px;
    background: var(--green-50); color: var(--green-800);
    border: none; border-radius: 6px;
    padding: 4px 10px; font-size: 11px; font-weight: 500;
    font-family: var(--font); cursor: pointer;
    transition: background 0.12s;
  }
  .btn-approve:hover { background: #c3eedd; }
  .btn-reject {
    display: flex; align-items: center;
    background: var(--red-50); color: var(--red-800);
    border: none; border-radius: 6px;
    padding: 4px 8px; font-size: 13px;
    font-family: var(--font); cursor: pointer;
    transition: background 0.12s;
  }
  .btn-reject:hover { background: #f9d5d5; }

  .report-item {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 16px; border-bottom: 0.5px solid var(--border);
  }
  .report-item:last-child { border-bottom: none; }
  .report-icon {
    width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 14px;
    background: var(--red-50); color: var(--red-400);
  }
  .report-info { flex: 1; min-width: 0; }
  .report-reason { font-size: 12px; font-weight: 500; }
  .report-meta   { font-size: 11px; color: var(--text-muted); }
  .report-time   { font-size: 11px; color: var(--text-muted); flex-shrink: 0; }

  .post-row { display: flex; align-items: center; gap: 10px; padding: 11px 20px; border-bottom: 0.5px solid var(--border); }
  .post-row:last-child { border-bottom: none; }
  .post-row:hover { background: var(--bg-sunken); }
  .post-icon { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; background: var(--accent-bg); color: var(--accent-txt); flex-shrink: 0; }
  .post-title { font-size: 13px; font-weight: 500; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .post-meta  { font-size: 11px; color: var(--text-muted); margin-top: 1px; }
  .post-time  { font-size: 11px; color: var(--text-muted); flex-shrink: 0; }

  .quick-actions { display: flex; gap: 10px; flex-wrap: wrap; }
  .qa-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--bg-surface); border: 0.5px solid var(--border-md);
    border-radius: var(--radius-md); padding: 10px 16px;
    font-size: 13px; color: var(--text-secondary);
    transition: border-color 0.12s, color 0.12s, background 0.12s;
  }
  .qa-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-bg); }
  .qa-btn > i { font-size: 17px; }
</style>
@endsection

@section('content')

{{-- Alert Banner --}}
@if($pendingWorkers > 0 || $pendingReports > 0)
<div class="alert-banner">
  <i class="ti ti-alert-triangle"></i>
  <div>
    <strong>Requires your attention:</strong>
    @if($pendingWorkers > 0) {{ $pendingWorkers }} pending verification request @endif
    @if($pendingWorkers > 0 && $pendingReports > 0) · @endif
    @if($pendingReports > 0) {{ $pendingReports }} report awaiting review @endif
  </div>
  <div class="alert-banner-actions">
    @if($pendingWorkers > 0)
      <a href="{{ route('admin.verifications.index') }}"><i class="ti ti-id-badge"></i> Review Verifications</a>
    @endif
    @if($pendingReports > 0)
      <a href="{{ route('admin.reports.index') }}"><i class="ti ti-flag"></i> Reports</a>
    @endif
  </div>
</div>
@endif

{{-- Page Header --}}
<div class="page-head">
  <div>
    <div class="page-title">Dashboard</div>
    <div class="page-sub">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</div>
  </div>
  <div style="display:flex;gap:8px;">
    <a href="{{ route('admin.ads.create') }}" class="btn-primary">
      <i class="ti ti-plus"></i> New Ad
    </a>
  </div>
</div>

{{-- Stats Grid --}}
<div class="stats-grid">

  {{-- Users --}}
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon green"><i class="ti ti-users"></i></div>
      @if($newUsersThisWeek > 0)
        <span class="stat-delta up"><i class="ti ti-trending-up" style="font-size:10px;"></i> +{{ $newUsersThisWeek }}</span>
      @else
        <span class="stat-delta neutral">This week</span>
      @endif
    </div>
    <div class="stat-value">{{ number_format($totalUsers) }}</div>
    <div class="stat-label">Total Users</div>
    <i class="ti ti-users stat-bg-icon"></i>
  </div>

  {{-- Workers --}}
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon coral"><i class="ti ti-tools"></i></div>
      @if($pendingWorkers > 0)
        <span class="stat-delta warn"><i class="ti ti-clock" style="font-size:10px;"></i> {{ $pendingWorkers }} pending</span>
      @endif
    </div>
    <div class="stat-value">{{ number_format($activeWorkers) }}</div>
    <div class="stat-label">Active Workers</div>
    <i class="ti ti-tools stat-bg-icon"></i>
  </div>

  {{-- Verifications --}}
  <div class="stat-card" style="{{ $pendingWorkers > 0 ? 'border-color:rgba(239,159,39,.35);' : '' }}">
    <div class="stat-top">
      <div class="stat-icon amber"><i class="ti ti-id-badge"></i></div>
      @if($pendingWorkers > 0)
        <span class="stat-delta warn">Requires review</span>
      @else
        <span class="stat-delta neutral">None pending</span>
      @endif
    </div>
    <div class="stat-value">{{ $pendingWorkers }}</div>
    <div class="stat-label">Verification Requests</div>
    <i class="ti ti-id-badge stat-bg-icon"></i>
  </div>

  {{-- Posts --}}
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon green"><i class="ti ti-file-text"></i></div>
      <span class="stat-delta up">{{ now()->format('M') }}</span>
    </div>
    <div class="stat-value">{{ number_format($postsThisMonth) }}</div>
    <div class="stat-label">Posts This Month</div>
    <i class="ti ti-file-text stat-bg-icon"></i>
  </div>

  {{-- Reports --}}
  <div class="stat-card" style="{{ $pendingReports > 0 ? 'border-color:rgba(226,75,74,.35);' : '' }}">
    <div class="stat-top">
      <div class="stat-icon red"><i class="ti ti-flag"></i></div>
      @if($pendingReports > 0)
        <span class="stat-delta red">Action required</span>
      @else
        <span class="stat-delta neutral">No reports</span>
      @endif
    </div>
    <div class="stat-value">{{ number_format($pendingReports) }}</div>
    <div class="stat-label">Reports</div>
    <i class="ti ti-flag stat-bg-icon"></i>
  </div>

  {{-- Ads --}}
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon amber"><i class="ti ti-speakerphone"></i></div>
      <span class="stat-delta up">Active</span>
    </div>
    <div class="stat-value">{{ $activeAds }}</div>
    <div class="stat-label">Active Ads</div>
    <i class="ti ti-speakerphone stat-bg-icon"></i>
  </div>

</div>

{{-- Quick Actions --}}
<div class="quick-actions">
  <a href="{{ route('admin.verifications.index') }}" class="qa-btn">
    <i class="ti ti-id-badge"></i> Account Verifications
    @if($pendingWorkers > 0)
      <span class="badge pending" style="font-size:10px;padding:1px 7px;">{{ $pendingWorkers }}</span>
    @endif
  </a>
  <a href="{{ route('admin.reports.index') }}" class="qa-btn">
    <i class="ti ti-flag"></i> Reports
    @if($pendingReports > 0)
      <span class="badge blocked" style="font-size:10px;padding:1px 7px;">{{ $pendingReports }}</span>
    @endif
  </a>
  <a href="{{ route('admin.users.index') }}" class="qa-btn">
    <i class="ti ti-users"></i> Manage Users
  </a>
  <a href="{{ route('admin.workers.index') }}" class="qa-btn">
    <i class="ti ti-tools"></i> Manage Workers
  </a>
  <a href="{{ route('admin.ads.create') }}" class="qa-btn">
    <i class="ti ti-speakerphone"></i> Create Ad
  </a>
  <a href="{{ route('admin.posts.index') }}" class="qa-btn">
    <i class="ti ti-file-text"></i> Posts
  </a>
</div>

{{-- Main Content --}}
<div class="content-grid">

  {{-- Left Column --}}
  <div style="display:flex;flex-direction:column;gap:14px;">

    {{-- Recent Users --}}
    <div class="card">
      <div class="card-head">
        <span class="card-title">Latest Registered Users</span>
        <a href="{{ route('admin.users.index') }}" class="card-action">View All</a>
      </div>
      <table class="data-table">
        <thead>
          <tr>
            <th>User</th>
            <th>City</th>
            <th>Status</th>
            <th>Joined</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentUsers as $user)
          <tr>
            <td>
              <div class="cell-user">
                <div class="avatar" style="background:var(--accent);">
                  {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                </div>
                <div>
                  <div class="cell-name">{{ $user->first_name }} {{ $user->last_name }}</div>
                  <div class="cell-email">{{ $user->email }}</div>
                </div>
              </div>
            </td>
            <td style="color:var(--text-secondary);font-size:12px;">{{ $user->city ?? '—' }}</td>
            <td>
              <span class="badge {{ $user->status ?? 'active' }}">
                {{ $user->status === 'active' ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td style="color:var(--text-muted);font-size:12px;">
              {{ $user->created_at->diffForHumans() }}
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4">
              <div class="empty-state"><i class="ti ti-users"></i>No users yet</div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Recent Posts --}}
    <div class="card">
      <div class="card-head">
        <span class="card-title">Latest Posts</span>
        <a href="{{ route('admin.posts.index') }}" class="card-action">View All</a>
      </div>
      @forelse($recentPosts as $post)
      <div class="post-row">
        <div class="post-icon"><i class="ti ti-file-text"></i></div>
        <div style="flex:1;min-width:0;">
          <div class="post-title">{{ $post->title ?? 'Untitled Post' }}</div>
          <div class="post-meta">
            by {{ $post->user?->first_name ?? 'Unknown' }}
            @if($post->views) · {{ $post->views }} views @endif
          </div>
        </div>
        <div class="post-time">{{ $post->created_at->diffForHumans(null, true) }}</div>
      </div>
      @empty
      <div class="empty-state"><i class="ti ti-file-text"></i>No posts yet</div>
      @endforelse
    </div>

  </div>

  {{-- Right Column --}}
  <div class="panel-right">

    {{-- Pending Verifications --}}
    <div class="card">
      <div class="card-head">
        <span class="card-title">
          Verification Requests
          @if($pendingVerifications->count() > 0)
            <span class="badge pending" style="margin-right:6px;">{{ $pendingVerifications->count() }}</span>
          @endif
        </span>
        <a href="{{ route('admin.verifications.index') }}" class="card-action">View All</a>
      </div>

      @forelse($pendingVerifications as $biz)
      <div class="verif-item">
        <div class="verif-icon" style="background:#FAEEDA;color:#BA7517;">
          <i class="ti ti-briefcase"></i>
        </div>
        <div class="verif-info">
          <div class="verif-name">{{ $biz->name }}</div>
          <div class="verif-meta">{{ $biz->activity ?? '—' }} · {{ $biz->created_at->diffForHumans(null, true) }}</div>
        </div>
        <div class="verif-actions">
          <form method="POST" action="{{ route('admin.verifications.approve', $biz->id) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn-approve" title="Approve">
              <i class="ti ti-check" style="font-size:12px;"></i> Approve
            </button>
          </form>
          <form method="POST" action="{{ route('admin.verifications.reject', $biz->id) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn-reject" title="Reject">
              <i class="ti ti-x" style="font-size:13px;"></i>
            </button>
          </form>
        </div>
      </div>
      @empty
      <div class="empty-state" style="padding:24px;">
        <i class="ti ti-circle-check" style="color:var(--accent);"></i>
        <div style="font-size:13px;margin-top:6px;">No pending requests</div>
      </div>
      @endforelse
    </div>

    {{-- Recent Reports --}}
    <div class="card">
      <div class="card-head">
        <span class="card-title">
          Recent Reports
          @if($recentReports->count() > 0)
            <span class="badge blocked" style="margin-right:6px;">{{ $recentReports->count() }}</span>
          @endif
        </span>
        <a href="{{ route('admin.reports.index') }}" class="card-action">View All</a>
      </div>

      @forelse($recentReports as $report)
      <div class="report-item">
        <div class="report-icon"><i class="ti ti-flag"></i></div>
        <div class="report-info">
          <div class="report-reason">{{ $report->reason ?? 'Report' }}</div>
          <div class="report-meta">
            Post #{{ $report->post_id }}
            @if($report->user) · {{ $report->user->first_name }} @endif
          </div>
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
          <span class="report-time">{{ $report->created_at->diffForHumans(null, true) }}</span>
          <a href="{{ route('admin.reports.post', $report->post_id) }}"
             style="font-size:11px;color:var(--accent);">View</a>
        </div>
      </div>
      @empty
      <div class="empty-state" style="padding:24px;">
        <i class="ti ti-circle-check" style="color:var(--accent);"></i>
        <div style="font-size:13px;margin-top:6px;">No reports</div>
      </div>
      @endforelse
    </div>

  </div>

</div>

@endsection
