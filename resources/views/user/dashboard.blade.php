@extends('user.layout')
@section('title', 'Dashboard')

@section('styles')
<style>
  /* ── Welcome ── */
  .welcome-banner {
    background: linear-gradient(135deg, #0D9488 0%, #0891B2 100%);
    border-radius: var(--radius-lg);
    padding: 24px 28px;
    color: #fff;
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    box-shadow: 0 4px 20px rgba(79,70,229,.25);
  }
  .welcome-banner h2 { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
  .welcome-banner p  { font-size: 13px; opacity: 0.80; }
  .welcome-icon { font-size: 52px; opacity: 0.18; flex-shrink: 0; }

  /* ── Stats ── */
  .stats-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; }
  @media(max-width:640px){ .stats-grid { grid-template-columns: 1fr 1fr; } }
  .stat-card {
    background: var(--bg-surface); border: 1px solid var(--border);
    border-radius: var(--radius-lg); padding: 18px 20px;
    display: flex; align-items: center; gap: 14px;
    transition: box-shadow 0.15s;
  }
  .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.07); }
  .stat-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .stat-icon.indigo { background: #F0FDFA; color: #0D9488; }
  .stat-icon.blue   { background: #EFF6FF; color: #2563EB; }
  .stat-icon.violet { background: #F5F3FF; color: #0891B2; }
  .stat-num   { font-size: 24px; font-weight: 700; line-height: 1; color: var(--text-primary); }
  .stat-label { font-size: 12px; color: var(--text-secondary); margin-top: 3px; }

  /* ── Section header ── */
  .section-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 16px;
  }
  .section-title { font-size: 16px; font-weight: 700; color: var(--text-primary); }
  .section-link  { font-size: 12px; color: var(--accent); font-weight: 600; }
  .section-link:hover { text-decoration: underline; }

  /* ── Service cards ── */
  .services-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; }
  @media(max-width:900px){ .services-grid { grid-template-columns: repeat(2,1fr); } }
  @media(max-width:560px){ .services-grid { grid-template-columns: 1fr; } }
  .service-card {
    background: var(--bg-surface); border: 1px solid var(--border);
    border-radius: var(--radius-lg); overflow: hidden;
    transition: box-shadow 0.15s, transform 0.15s;
  }
  .service-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.09); transform: translateY(-2px); }
  .service-img {
    width: 100%; height: 140px; object-fit: cover;
    background: var(--bg-sunken);
  }
  .service-img-placeholder {
    width: 100%; height: 140px; background: linear-gradient(135deg, #F0FDFA, #F5F3FF);
    display: flex; align-items: center; justify-content: center; font-size: 36px;
  }
  .service-body { padding: 14px 16px; }
  .service-cat  { font-size: 11px; color: var(--accent); font-weight: 600; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.4px; }
  .service-name { font-size: 14px; font-weight: 600; line-height: 1.4; margin-bottom: 6px; color: var(--text-primary); }
  .service-desc { font-size: 12px; color: var(--text-secondary); line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
  .service-footer { display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; border-top: 1px solid var(--border); }
  .service-price { font-size: 15px; font-weight: 700; color: var(--text-primary); }
  .service-price-type { font-size: 11px; color: var(--text-muted); font-weight: 400; }

  /* ── Professional cards ── */
  .pro-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; }
  @media(max-width:900px){ .pro-grid { grid-template-columns: repeat(2,1fr); } }
  @media(max-width:560px){ .pro-grid { grid-template-columns: 1fr; } }
  .pro-card {
    background: var(--bg-surface); border: 1px solid var(--border);
    border-radius: var(--radius-lg); padding: 20px 16px;
    display: flex; flex-direction: column; align-items: center; text-align: center;
    transition: box-shadow 0.15s, transform 0.15s;
  }
  .pro-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.09); transform: translateY(-2px); }
  .pro-avatar {
    width: 60px; height: 60px; border-radius: 16px; object-fit: cover;
    background: var(--accent-bg); margin-bottom: 12px;
    border: 2px solid var(--border);
  }
  .pro-name { font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 3px; }
  .pro-job  { font-size: 12px; color: var(--accent); font-weight: 600; margin-bottom: 6px; }
  .pro-activity { font-size: 11px; color: var(--text-muted); }
  .pro-online { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #22C55E; margin-right: 4px; }

  /* ── Ads ── */
  .ads-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; }
  @media(max-width:900px){ .ads-grid { grid-template-columns: repeat(2,1fr); } }
  @media(max-width:560px){ .ads-grid { grid-template-columns: 1fr; } }
  .ad-card {
    background: var(--bg-surface); border: 1px solid var(--border);
    border-radius: var(--radius-lg); overflow: hidden;
    transition: box-shadow 0.15s;
  }
  .ad-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.09); }
  .ad-img { width: 100%; height: 120px; object-fit: cover; }
  .ad-placeholder { width: 100%; height: 120px; background: linear-gradient(135deg, #134E4A, #0D9488); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 32px; }
  .ad-body { padding: 14px 16px; }
  .ad-company { font-size: 11px; color: var(--accent); font-weight: 600; margin-bottom: 5px; }
  .ad-title { font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 4px; }
  .ad-desc  { font-size: 12px; color: var(--text-secondary); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

  /* ── Empty state ── */
  .empty-state {
    padding: 40px 20px; text-align: center;
    color: var(--text-muted); font-size: 13px;
  }
  .empty-state i { font-size: 36px; margin-bottom: 10px; display: block; }

  /* ── Quick links ── */
  .shortcuts-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; }
  @media(max-width:700px){ .shortcuts-grid { grid-template-columns: repeat(2,1fr); } }
  .shortcut-btn {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    padding: 16px 12px; border-radius: var(--radius-md);
    background: var(--bg-surface); border: 1px solid var(--border);
    color: var(--text-primary); font-size: 12px; font-weight: 500;
    text-align: center; transition: background 0.12s, border-color 0.12s, box-shadow 0.12s;
  }
  .shortcut-btn:hover {
    background: var(--accent-bg); border-color: var(--accent);
    color: var(--accent); box-shadow: 0 2px 8px rgba(79,70,229,.12);
  }
  .shortcut-btn i { font-size: 24px; color: var(--accent); }
</style>
@endsection

@section('content')
@php $user = Auth::guard('users')->user(); @endphp

{{-- Welcome --}}
<div class="welcome-banner">
  <div>
    <h2>Welcome back, {{ $user->first_name }}! 👋</h2>
    <p>{{ now()->format('l, F j, Y') }} — Here's what's new on Skillify</p>
  </div>
  <i class="ti ti-sparkles welcome-icon"></i>
</div>

{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon indigo"><i class="ti ti-file-text"></i></div>
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
    <div class="stat-icon violet"><i class="ti ti-briefcase"></i></div>
    <div>
      <div class="stat-num">{{ $servicesCount }}</div>
      <div class="stat-label">Available Services</div>
    </div>
  </div>
</div>

{{-- Quick Links --}}
<div class="card">
  <div class="card-head">
    <div class="card-title">Quick Actions</div>
  </div>
  <div class="card-body">
    <div class="shortcuts-grid">
      <a href="{{ route('user.explore') }}" class="shortcut-btn">
        <i class="ti ti-search"></i>
        <span>Explore</span>
      </a>
      <a href="{{ route('user.services') }}" class="shortcut-btn">
        <i class="ti ti-briefcase"></i>
        <span>Services</span>
      </a>
      <a href="{{ route('user.community-posts') }}" class="shortcut-btn">
        <i class="ti ti-users"></i>
        <span>Community</span>
      </a>
      <a href="{{ route('user.profile') }}" class="shortcut-btn">
        <i class="ti ti-user-edit"></i>
        <span>My Profile</span>
      </a>
    </div>
  </div>
</div>

{{-- New Services --}}
<div>
  <div class="section-header">
    <div class="section-title">✨ New Services</div>
    <a href="{{ route('user.services') }}" class="section-link">View all →</a>
  </div>
  @if($recentServices->isNotEmpty())
  <div class="services-grid">
    @foreach($recentServices as $service)
    <div class="service-card">
      @if($service->image)
        <img src="{{ $service->image }}" alt="{{ $service->name }}" class="service-img" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
        <div class="service-img-placeholder" style="display:none;">🛠️</div>
      @else
        <div class="service-img-placeholder">🛠️</div>
      @endif
      <div class="service-body">
        <div class="service-cat">{{ $service->category ?? $service->subcategory }}</div>
        <div class="service-name">{{ $service->name }}</div>
        <div class="service-desc">{{ $service->description }}</div>
      </div>
      <div class="service-footer">
        <div>
          <span class="service-price">${{ number_format($service->price, 0) }}</span>
          <span class="service-price-type">/ {{ $service->price_type ?? 'fixed' }}</span>
        </div>
        <a href="{{ route('user.services.details', $service->id) }}"
           class="btn btn-primary" style="padding: 6px 14px; font-size: 12px;">
          View
        </a>

      </div>
    </div>
    @endforeach
  </div>
  @else
  <div class="card">
    <div class="empty-state">
      <i class="ti ti-briefcase"></i>
      No services available yet.
    </div>
  </div>
  @endif
</div>

{{-- Top Professionals --}}
<div>
  <div class="section-header">
    <div class="section-title">⭐ Top Professionals</div>
    <a href="{{ route('user.explore') }}" class="section-link">Explore all →</a>
  </div>
  @if($topBusinesses->isNotEmpty())
  <div class="pro-grid">
    @foreach($topBusinesses as $biz)
    @php
      $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($biz->name) . "&background=0D9488&color=fff&size=128";
    @endphp
    <div class="pro-card">
      <img
        src="{{ $biz->image ? (Str::startsWith($biz->image, 'http') ? $biz->image : asset('storage/' . $biz->image)) : $avatarUrl }}"
        alt="{{ $biz->name }}"
        class="pro-avatar"
        onerror="this.src='{{ $avatarUrl }}'">
      <div class="pro-name">{{ $biz->name }}</div>
      <div class="pro-job">{{ $biz->name_job }}</div>
      <div class="pro-activity">
        <span class="pro-online"></span>{{ $biz->activity }}
      </div>
    </div>
    @endforeach
  </div>
  @else
  <div class="card">
    <div class="empty-state">
      <i class="ti ti-users"></i>
      No professionals registered yet.
    </div>
  </div>
  @endif
</div>

{{-- Ads --}}
<div>
  <div class="section-header">
    <div class="section-title">📢 Latest Listings</div>
    <a href="{{ route('user.ads') }}" class="section-link">View all →</a>
  </div>
  @if($recentAds->isNotEmpty())
  <div class="ads-grid">
    @foreach($recentAds as $ad)
    <div class="ad-card">
      @if($ad->image)
        <img src="{{ asset('storage/' . $ad->image) }}" class="ad-img" alt="{{ $ad->title }}">
      @else
        <div class="ad-placeholder">📢</div>
      @endif
      <div class="ad-body">
        <div class="ad-company">🏢 {{ $ad->company_name }}</div>
        <div class="ad-title">{{ $ad->title }}</div>
        <div class="ad-desc">{{ $ad->description }}</div>
      </div>
    </div>
    @endforeach
  </div>
  @else
  <div class="card">
    <div class="empty-state">
      <i class="ti ti-speakerphone"></i>
      No active listings at the moment.
    </div>
  </div>
  @endif
</div>

@endsection
