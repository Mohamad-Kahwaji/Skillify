@extends('user.layout')

@section('title', 'Home')

@section('styles')
<style>
  .welcome-banner {
    background: linear-gradient(135deg, var(--green-600) 0%, var(--green-400) 100%);
    border-radius: var(--radius-lg);
    padding: 28px 28px;
    color: #fff;
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px;
  }
  .welcome-banner h2 { font-size: 20px; font-weight: 600; margin-bottom: 4px; }
  .welcome-banner p  { font-size: 13px; opacity: 0.85; }
  .welcome-icon { font-size: 48px; opacity: 0.25; flex-shrink: 0; }

  .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
  @media(max-width:640px){ .stats-grid { grid-template-columns: 1fr 1fr; } }
  .stat-card {
    background: var(--bg-surface);
    border: 0.5px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 18px 20px;
    display: flex; align-items: center; gap: 14px;
  }
  .stat-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .stat-icon.green  { background: var(--accent-bg); color: var(--accent); }
  .stat-icon.blue   { background: #EFF6FF; color: #2563EB; }
  .stat-icon.purple { background: #F5F3FF; color: #7C3AED; }
  .stat-num   { font-size: 22px; font-weight: 700; line-height: 1; }
  .stat-label { font-size: 12px; color: var(--text-secondary); margin-top: 2px; }

  .shortcuts-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
  @media(max-width:480px){ .shortcuts-grid { grid-template-columns: 1fr; } }
  .shortcut-btn {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 16px;
    border-radius: var(--radius-md);
    background: var(--bg-sunken);
    border: 0.5px solid var(--border);
    color: var(--text-primary);
    transition: background 0.12s, border-color 0.12s;
    font-size: 13px; font-weight: 500;
  }
  .shortcut-btn:hover { background: var(--bg-hover); border-color: var(--border-md); }
  .shortcut-btn i { font-size: 20px; color: var(--accent); flex-shrink: 0; }
  .shortcut-btn .sc-sub { font-size: 11px; color: var(--text-muted); font-weight: 400; }

  .recent-post {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 12px 0; border-bottom: 0.5px solid var(--border);
  }
  .recent-post:last-child { border-bottom: none; }
  .post-dot {
    width: 32px; height: 32px; border-radius: 8px;
    background: var(--accent-bg); color: var(--accent);
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0; margin-top: 1px;
  }
  .post-title { font-size: 13px; font-weight: 500; }
  .post-meta  { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
</style>
@endsection

@section('content')

@php $user = Auth::guard('users')->user(); @endphp

{{-- Welcome Banner --}}
<div class="welcome-banner">
  <div>
    <h2>Welcome, {{ $user->first_name }} 👋</h2>
    <p>{{ now()->format('l, F j, Y') }}</p>
  </div>
  <i class="ti ti-tool welcome-icon"></i>
</div>

{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon green"><i class="ti ti-file-text"></i></div>
    <div>
      <div class="stat-num">{{ $postsCount }}</div>
      <div class="stat-label">My Posts</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon blue"><i class="ti ti-message-circle"></i></div>
    <div>
      <div class="stat-num">{{ $conversationsCount }}</div>
      <div class="stat-label">Conversations</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon purple"><i class="ti ti-briefcase"></i></div>
    <div>
      <div class="stat-num">{{ $servicesCount }}</div>
      <div class="stat-label">Available Services</div>
    </div>
  </div>
</div>

{{-- Quick Shortcuts --}}
<div class="card">
  <div class="card-head">
    <div class="card-title">Quick Links</div>
  </div>
  <div class="card-body">
    <div class="shortcuts-grid">
      <a href="{{ route('user.explore') }}" class="shortcut-btn">
        <i class="ti ti-search"></i>
        <div>
          <div>Explore Craftsmen</div>
          <div class="sc-sub">Browse businesses & workers</div>
        </div>
      </a>
      <a href="{{ route('user.services') }}" class="shortcut-btn">
        <i class="ti ti-briefcase"></i>
        <div>
          <div>Available Services</div>
          <div class="sc-sub">Find the right service</div>
        </div>
      </a>
      <a href="{{ route('user.posts') }}" class="shortcut-btn">
        <i class="ti ti-file-text"></i>
        <div>
          <div>My Posts</div>
          <div class="sc-sub">View all your posts</div>
        </div>
      </a>
      <a href="{{ route('user.profile') }}" class="shortcut-btn">
        <i class="ti ti-user-edit"></i>
        <div>
          <div>My Profile</div>
          <div class="sc-sub">View your account details</div>
        </div>
      </a>
    </div>
  </div>
</div>

{{-- Recent Posts --}}
@if($recentPosts->isNotEmpty())
<div class="card">
  <div class="card-head">
    <div class="card-title">Recent Posts</div>
    <a href="{{ route('user.posts') }}" style="font-size:12px;color:var(--accent);">View all</a>
  </div>
  <div class="card-body" style="padding:8px 20px;">
    @foreach($recentPosts as $post)
    <div class="recent-post">
      <div class="post-dot"><i class="ti ti-file-text"></i></div>
      <div style="flex:1;min-width:0;">
        <div class="post-title">{{ $post->title ?? 'Untitled Post' }}</div>
        <div class="post-meta">
          {{ $post->created_at->format('Y/m/d') }}
          &nbsp;·&nbsp;
          <span class="badge {{ $post->status === 'active' ? 'active' : 'pending' }}" style="font-size:10px;padding:2px 7px;">
            {{ $post->status === 'active' ? 'Active' : 'Under Review' }}
          </span>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endif

@endsection
