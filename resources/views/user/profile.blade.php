@extends('user.layout')

@section('title', 'My Profile')

@section('styles')
<style>
/* ═══════ HERO ═══════ */
.profile-hero {
  background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #047857 70%, #059669 100%);
  border-radius: var(--radius-lg);
  padding: 32px 28px 28px;
  position: relative; overflow: hidden;
  color: #fff;
  box-shadow: 0 8px 32px rgba(4,78,59,.35);
}
.profile-hero::before {
  content: '';
  position: absolute; top: -60px; right: -60px;
  width: 240px; height: 240px; border-radius: 50%;
  background: rgba(255,255,255,.06); pointer-events: none;
}
.profile-hero::after {
  content: '';
  position: absolute; bottom: -40px; left: 40px;
  width: 160px; height: 160px; border-radius: 50%;
  background: rgba(255,255,255,.04); pointer-events: none;
}
.hero-top { display: flex; align-items: center; gap: 18px; position: relative; z-index: 1; }
.hero-avatar {
  width: 72px; height: 72px; border-radius: 18px; flex-shrink: 0;
  background: rgba(255,255,255,.18);
  border: 2.5px solid rgba(255,255,255,.45);
  display: flex; align-items: center; justify-content: center;
  font-size: 28px; font-weight: 800; color: #fff;
  letter-spacing: -.5px;
  box-shadow: 0 4px 12px rgba(0,0,0,.2);
}
.hero-name  { font-size: 21px; font-weight: 700; letter-spacing: -.4px; text-shadow: 0 1px 3px rgba(0,0,0,.15); }
.hero-email { font-size: 12px; color: rgba(255,255,255,.8); margin-top: 4px; }
.hero-status {
  margin-left: auto;
  padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
  background: rgba(255,255,255,.18);
  border: 1px solid rgba(255,255,255,.3);
  color: #fff;
  display: inline-flex; align-items: center; gap: 6px;
  backdrop-filter: blur(4px);
}
.hero-status-dot { width:7px;height:7px;border-radius:50%;background:#4ade80;box-shadow:0 0 6px #4ade80; }
.hero-divider {
  margin: 24px 0 0; padding-top: 20px;
  border-top: 1px solid rgba(255,255,255,.2);
  display: flex; gap: 0; position: relative; z-index: 1;
}
.hero-stat { flex: 1; text-align: center; padding: 0 12px; }
.hero-stat + .hero-stat { border-left: 1px solid rgba(255,255,255,.2); }
.hero-stat-num   { font-size: 26px; font-weight: 800; color: #fff; line-height: 1; text-shadow: 0 1px 3px rgba(0,0,0,.15); }
.hero-stat-label { font-size: 11px; color: rgba(255,255,255,.75); margin-top: 5px; font-weight: 500; letter-spacing: .3px; }

/* ═══════ SECTION CARD ═══════ */
.sec-card { background: var(--bg-surface); border: .5px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; }
.sec-head { display: flex; align-items: center; justify-content: space-between; padding: 15px 20px; background: var(--bg-sunken); border-bottom: .5px solid var(--border); }
.sec-head-left { display: flex; align-items: center; gap: 10px; }
.sec-head-icon { width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 17px; }
.ic-green  { background: var(--accent-bg);   color: var(--accent); }
.ic-orange { background: #FFF7ED;             color: #EA580C; }
.ic-purple { background: #F5F3FF;             color: #7C3AED; }
.sec-title { font-size: 14px; font-weight: 600; }

/* ═══════ INFO GRID ═══════ */
.info-grid { display: grid; grid-template-columns: 1fr 1fr; }
@media(max-width:560px){ .info-grid { grid-template-columns: 1fr; } }
.info-cell { padding: 14px 20px; border-bottom: .5px solid var(--border); border-right: .5px solid var(--border); }
.info-cell:nth-child(even) { border-right: none; }
.info-cell:last-child { border-bottom: none; }
.info-cell:nth-last-child(2):nth-child(odd) { border-bottom: none; }
.info-cell-label { font-size: 11px; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 5px; margin-bottom: 5px; }
.info-cell-label i { font-size: 13px; }
.info-cell-value { font-size: 14px; font-weight: 600; color: var(--text-primary); }

/* ═══════ BADGES ═══════ */
.badge { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
.badge::before { content:''; width:5px;height:5px;border-radius:50%;background:currentColor;opacity:.7; }
.badge.active   { background: var(--green-50);  color: var(--green-800); }
.badge.pending  { background: #FEF3C7;           color: #92400E; }
.badge.rejected { background: var(--red-50);     color: var(--red-800); }
.badge.inactive { background: #F3F4F6;           color: #6B7280; }

/* ═══════ BUTTONS ═══════ */
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; border-radius: var(--radius-sm); font-family: var(--font); font-size: 13px; font-weight: 500; cursor: pointer; border: none; transition: all .15s; text-decoration: none; white-space: nowrap; }
.btn-primary       { background: var(--accent); color: #fff; }
.btn-primary:hover { background: var(--accent-hover); box-shadow: 0 3px 10px rgba(29,158,117,.3); }
.btn-outline       { background: none; border: .5px solid var(--border-md); color: var(--text-secondary); }
.btn-outline:hover { background: var(--bg-hover); color: var(--text-primary); }
.btn-sm { padding: 5px 12px; font-size: 12px; }
.btn-icon { width: 30px; height: 30px; padding: 0; border-radius: var(--radius-sm); display: inline-flex; align-items: center; justify-content: center; font-size: 14px; background: none; border: .5px solid var(--border-md); color: var(--text-secondary); cursor: pointer; transition: all .12s; }
.btn-icon:hover     { background: var(--bg-hover); color: var(--text-primary); }
.btn-icon.del:hover { background: var(--red-50); color: var(--red-400); border-color: var(--red-400); }

/* ═══════ BUSINESS BANNERS ═══════ */
.biz-empty { padding: 44px 20px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px; }
.biz-empty-ico { width: 60px; height: 60px; border-radius: 16px; background: #FFF7ED; color: #EA580C; display: flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 4px; }
.biz-empty-title { font-size: 15px; font-weight: 600; }
.biz-empty-sub   { font-size: 13px; color: var(--text-secondary); max-width: 280px; line-height: 1.55; }
.status-banner { margin: 16px 20px; padding: 14px 16px; border-radius: var(--radius-md); display: flex; align-items: flex-start; gap: 12px; }
.status-banner.pending  { background: #FFFBEB; border: .5px solid #FDE68A; }
.status-banner.rejected { background: var(--red-50); border: .5px solid #FECACA; }
.sb-icon { width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 18px; }
.pending  .sb-icon { background: #FDE68A; color: #92400E; }
.rejected .sb-icon { background: #FECACA; color: var(--red-800); }
.sb-title { font-size: 13px; font-weight: 600; margin-bottom: 3px; }
.pending  .sb-title { color: #78350F; }
.rejected .sb-title { color: var(--red-800); }
.sb-sub   { font-size: 12px; opacity: .8; line-height: 1.5; }

/* ═══════ SERVICES ═══════ */
.svc-item { display: flex; align-items: center; gap: 13px; padding: 13px 20px; border-bottom: .5px solid var(--border); transition: background .1s; }
.svc-item:last-child { border-bottom: none; }
.svc-item:hover { background: var(--bg-sunken); }
.svc-thumb { width: 46px; height: 46px; border-radius: 10px; flex-shrink: 0; overflow: hidden; background: var(--bg-sunken); display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--text-muted); }
.svc-thumb img { width: 100%; height: 100%; object-fit: cover; }
.svc-info { flex: 1; min-width: 0; }
.svc-name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.svc-meta { font-size: 11px; color: var(--text-muted); margin-top: 3px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.svc-price { font-size: 14px; font-weight: 700; color: var(--accent); flex-shrink: 0; }
.svc-price small { font-size: 10px; font-weight: 400; color: var(--text-muted); }
.svc-actions { display: flex; gap: 5px; flex-shrink: 0; }
.svc-empty { padding: 36px 20px; text-align: center; color: var(--text-muted); }
.svc-empty i { font-size: 36px; display: block; margin-bottom: 8px; opacity: .3; }
.svc-empty p { font-size: 13px; }

/* ═══════ MODAL ═══════ */
.modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.45); display: none; align-items: center; justify-content: center; z-index: 999; padding: 16px; backdrop-filter: blur(3px); }
.modal-backdrop.open { display: flex; }
.modal { background: var(--bg-surface); border-radius: var(--radius-lg); width: 100%; max-width: 520px; max-height: 90vh; overflow-y: auto; box-shadow: 0 24px 72px rgba(0,0,0,.25); animation: mIn .22s cubic-bezier(.34,1.4,.64,1); }
@keyframes mIn { from{opacity:0;transform:scale(.94)translateY(12px)} to{opacity:1;transform:scale(1)translateY(0)} }
.modal-head { display: flex; align-items: center; gap: 12px; padding: 17px 20px; border-bottom: .5px solid var(--border); position: sticky; top: 0; background: var(--bg-surface); z-index: 1; }
.modal-head-icon { width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 17px; }
.modal-title { font-size: 15px; font-weight: 600; flex: 1; }
.modal-close { width: 30px; height: 30px; border-radius: 7px; background: none; border: none; font-size: 18px; color: var(--text-muted); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .12s; }
.modal-close:hover { background: var(--bg-hover); color: var(--text-primary); }
.modal-body   { padding: 20px; display: flex; flex-direction: column; gap: 14px; }
.modal-footer { padding: 14px 20px; border-top: .5px solid var(--border); display: flex; justify-content: flex-end; gap: 8px; background: var(--bg-sunken); border-radius: 0 0 var(--radius-lg) var(--radius-lg); }
.field     { display: flex; flex-direction: column; gap: 5px; }
.field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
@media(max-width:460px){ .field-row { grid-template-columns: 1fr; } }
.lbl { font-size: 12px; font-weight: 600; color: var(--text-secondary); }
.inp { width: 100%; padding: 9px 12px; border: .5px solid var(--border-md); border-radius: var(--radius-sm); font-family: var(--font); font-size: 13px; color: var(--text-primary); background: var(--bg-surface); outline: none; transition: border-color .12s, box-shadow .12s; }
.inp:focus  { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(29,158,117,.12); }
textarea.inp { resize: vertical; min-height: 82px; line-height: 1.55; }
select.inp  { cursor: pointer; }
.inp-file { width: 100%; padding: 8px 12px; border: .5px dashed var(--border-md); border-radius: var(--radius-sm); font-family: var(--font); font-size: 12px; color: var(--text-secondary); background: var(--bg-sunken); cursor: pointer; }
.inp-hint { font-size: 11px; color: var(--text-muted); }
</style>
@endsection


@section('content')
@php
  $joinMonths = (int) $user->created_at->diffInMonths(now());
@endphp

{{-- ═══════════════════ HERO ═══════════════════ --}}
<div class="profile-hero">
  <div class="profile-hero-circles"></div>
  <div class="hero-top">
    <div class="hero-avatar">{{ strtoupper(mb_substr($user->first_name, 0, 1)) }}</div>
    <div>
      <div class="hero-name">{{ $user->first_name }} {{ $user->last_name }}</div>
      <div class="hero-email">{{ $user->email }}</div>
    </div>
    <div class="hero-status" style="margin-left:auto;">
      <span class="hero-status-dot"></span>
      {{ $user->status === 'active' ? 'Active' : 'Suspended' }}
    </div>
  </div>
  <div class="hero-divider">
    <div class="hero-stat">
      <div class="hero-stat-num">{{ $user->posts()->count() }}</div>
      <div class="hero-stat-label">Posts</div>
    </div>
    <div class="hero-stat">
      <div class="hero-stat-num">{{ $userServices->count() }}</div>
      <div class="hero-stat-label">Services</div>
    </div>
    <div class="hero-stat">
      <div class="hero-stat-num">{{ $joinMonths }}</div>
      <div class="hero-stat-label">Months with us</div>
    </div>
    <div class="hero-stat">
      <div class="hero-stat-num">
        @if($business)
          <i class="ti ti-check-circle" style="font-size:18px;color:#4ade80;"></i>
        @else
          <i class="ti ti-circle-dashed" style="font-size:18px;color:rgba(255,255,255,.45);"></i>
        @endif
      </div>
      <div class="hero-stat-label">Business Acc.</div>
    </div>
  </div>
</div>


{{-- ═══════════════════ 1. PERSONAL INFO ═══════════════════ --}}
<div class="sec-card">
  <div class="sec-head">
    <div class="sec-head-left">
      <div class="sec-head-icon ic-green"><i class="ti ti-user"></i></div>
      <div class="sec-title">Personal Information</div>
    </div>
    <button class="btn btn-outline btn-sm" onclick="openModal('modal-edit-profile')">
      <i class="ti ti-edit"></i> Edit
    </button>
  </div>
  <div class="info-grid">
    <div class="info-cell">
      <div class="info-cell-label"><i class="ti ti-user"></i> First Name</div>
      <div class="info-cell-value">{{ $user->first_name }}</div>
    </div>
    <div class="info-cell">
      <div class="info-cell-label"><i class="ti ti-user"></i> Last Name</div>
      <div class="info-cell-value">{{ $user->last_name }}</div>
    </div>
    <div class="info-cell">
      <div class="info-cell-label"><i class="ti ti-mail"></i> Email Address</div>
      <div class="info-cell-value">{{ $user->email }}</div>
    </div>
    <div class="info-cell">
      <div class="info-cell-label"><i class="ti ti-phone"></i> Phone</div>
      <div class="info-cell-value">{{ $user->phone ?? '—' }}</div>
    </div>
    <div class="info-cell">
      <div class="info-cell-label"><i class="ti ti-map-pin"></i> City</div>
      <div class="info-cell-value">{{ $user->city ?? '—' }}</div>
    </div>
    <div class="info-cell">
      <div class="info-cell-label"><i class="ti ti-gender-bigender"></i> Gender</div>
      <div class="info-cell-value">{{ $user->gender === 'male' ? 'Male' : 'Female' }}</div>
    </div>
    <div class="info-cell">
      <div class="info-cell-label"><i class="ti ti-calendar"></i> Date of Birth</div>
      <div class="info-cell-value">{{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y/m/d') : '—' }}</div>
    </div>
    <div class="info-cell">
      <div class="info-cell-label"><i class="ti ti-calendar-check"></i> Member Since</div>
      <div class="info-cell-value">{{ $user->created_at->format('Y/m/d') }}</div>
    </div>
  </div>
</div>


{{-- ═══════════════════ 2. BUSINESS ACCOUNT ═══════════════════ --}}
<div class="sec-card">
  <div class="sec-head">
    <div class="sec-head-left">
      <div class="sec-head-icon ic-orange"><i class="ti ti-briefcase"></i></div>
      <div class="sec-title">
        Business Account
        @if($business)
          <span class="badge {{ $business->status }}" style="margin-left:8px;">
            @if($business->status==='active') Active
            @elseif($business->status==='pending') Under Review
            @else Rejected @endif
          </span>
        @endif
      </div>
    </div>
    @if(!$business)
      <button class="btn btn-primary btn-sm" onclick="openModal('modal-create-business')">
        <i class="ti ti-plus"></i> Create Business Account
      </button>
    @elseif($business->status === 'active')
      <button class="btn btn-outline btn-sm" onclick="openModal('modal-edit-business')">
        <i class="ti ti-edit"></i> Edit
      </button>
    @endif
  </div>

  @if(!$business)
    <div class="biz-empty">
      <div class="biz-empty-ico"><i class="ti ti-briefcase"></i></div>
      <div class="biz-empty-title">No Business Account Yet</div>
      <div class="biz-empty-sub">Create a business account to appear in the craftsmen directory and offer your services.</div>
      <button class="btn btn-primary" onclick="openModal('modal-create-business')">
        <i class="ti ti-plus"></i> Create Now
      </button>
    </div>

  @elseif($business->status === 'pending')
    <div class="status-banner pending">
      <div class="sb-icon"><i class="ti ti-clock"></i></div>
      <div>
        <div class="sb-title">Request Under Review</div>
        <div class="sb-sub" style="color:#92400E;">Your request has been submitted. The admin will review it and notify you soon.</div>
      </div>
    </div>
    <div class="info-grid" style="border-top:.5px solid var(--border);">
      <div class="info-cell">
        <div class="info-cell-label"><i class="ti ti-building"></i> Business Name</div>
        <div class="info-cell-value">{{ $business->name }}</div>
      </div>
      <div class="info-cell">
        <div class="info-cell-label"><i class="ti ti-id-badge"></i> Job Title</div>
        <div class="info-cell-value">{{ $business->name_job }}</div>
      </div>
      <div class="info-cell">
        <div class="info-cell-label"><i class="ti ti-tag"></i> Activity</div>
        <div class="info-cell-value">{{ $business->activity }}</div>
      </div>
      <div class="info-cell">
        <div class="info-cell-label"><i class="ti ti-phone"></i> Contact Number</div>
        <div class="info-cell-value">{{ $business->number }}</div>
      </div>
    </div>

  @elseif($business->status === 'rejected')
    <div class="status-banner rejected">
      <div class="sb-icon"><i class="ti ti-circle-x"></i></div>
      <div>
        <div class="sb-title">Request Rejected</div>
        <div class="sb-sub" style="color:var(--red-800);">Unfortunately, your business account request was rejected. Please contact the admin for more information.</div>
      </div>
    </div>

  @else
    <div class="info-grid">
      <div class="info-cell">
        <div class="info-cell-label"><i class="ti ti-building"></i> Business Name</div>
        <div class="info-cell-value">{{ $business->name }}</div>
      </div>
      <div class="info-cell">
        <div class="info-cell-label"><i class="ti ti-id-badge"></i> Job Title</div>
        <div class="info-cell-value">{{ $business->name_job }}</div>
      </div>
      <div class="info-cell">
        <div class="info-cell-label"><i class="ti ti-phone"></i> Contact Number</div>
        <div class="info-cell-value">{{ $business->number }}</div>
      </div>
      <div class="info-cell">
        <div class="info-cell-label"><i class="ti ti-tag"></i> Activity</div>
        <div class="info-cell-value">{{ $business->activity }}</div>
      </div>
      @if($business->description)
      <div class="info-cell" style="grid-column:1/-1;">
        <div class="info-cell-label"><i class="ti ti-align-left"></i> Description</div>
        <div class="info-cell-value" style="font-weight:400;color:var(--text-secondary);line-height:1.6;white-space:pre-line;">{{ $business->description }}</div>
      </div>
      @endif
    </div>
  @endif
</div>


{{-- ═══════════════════ 3. MY SERVICES ═══════════════════ --}}
<div class="sec-card">
  <div class="sec-head">
    <div class="sec-head-left">
      <div class="sec-head-icon ic-purple"><i class="ti ti-tool"></i></div>
      <div class="sec-title">
        My Services
        <span style="font-size:12px;font-weight:400;color:var(--text-muted);margin-left:6px;">{{ $userServices->count() }} service(s)</span>
      </div>
    </div>
    <button class="btn btn-primary btn-sm" onclick="openModal('modal-add-service')">
      <i class="ti ti-plus"></i> Add Service
    </button>
  </div>

  @if($userServices->isEmpty())
    <div class="svc-empty">
      <i class="ti ti-tool-off"></i>
      <p>No services added yet. Add your first service!</p>
    </div>
  @else
    @foreach($userServices as $svc)
    <div class="svc-item">
      <div class="svc-thumb">
        @if($svc->image)
          <img src="{{ asset('storage/'.$svc->image) }}" alt="">
        @else
          <i class="ti ti-tool"></i>
        @endif
      </div>
      <div class="svc-info">
        <div class="svc-name">{{ $svc->name }}</div>
        <div class="svc-meta">
          <span><i class="ti ti-tag" style="font-size:11px;"></i> {{ $svc->category }}</span>
          <span>·</span><span>{{ $svc->subcategory }}</span>
          <span>·</span><span><i class="ti ti-map-pin" style="font-size:11px;"></i> {{ $svc->city }}</span>
        </div>
      </div>
      <div class="svc-price">
        {{ number_format($svc->price, 0) }}
        <small>{{ $svc->price_type === 'usd' ? 'USD' : 'SYP' }}</small>
      </div>
      <span class="badge {{ $svc->is_active ? 'active' : 'inactive' }}">
        {{ $svc->is_active ? 'Available' : 'Inactive' }}
      </span>
      <div class="svc-actions">
        <button class="btn-icon" title="Edit"
          data-id="{{ $svc->id }}"
          data-svc="{{ e(json_encode($svc)) }}"
          onclick="openEditService(this)">
          <i class="ti ti-edit"></i>
        </button>
        <form method="POST" action="{{ route('user.my-services.destroy', $svc->id) }}"
              onsubmit="return confirm('Delete this service?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn-icon del" title="Delete">
            <i class="ti ti-trash"></i>
          </button>
        </form>
      </div>
    </div>
    @endforeach
  @endif
</div>


{{-- ═══════════════════════════════════════
     MODALS
═══════════════════════════════════════ --}}

{{-- ① Edit Profile --}}
<div class="modal-backdrop" id="modal-edit-profile" onclick="closeOnBackdrop(event,'modal-edit-profile')">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-head-icon ic-green"><i class="ti ti-user-edit"></i></div>
      <span class="modal-title">Edit Personal Information</span>
      <button class="modal-close" onclick="closeModal('modal-edit-profile')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" action="{{ route('user.profile.update') }}">
      @csrf @method('PUT')
      <div class="modal-body">
        <div class="field-row">
          <div class="field">
            <label class="lbl">First Name</label>
            <input class="inp" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
          </div>
          <div class="field">
            <label class="lbl">Last Name</label>
            <input class="inp" type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
          </div>
        </div>
        <div class="field-row">
          <div class="field">
            <label class="lbl">Phone</label>
            <input class="inp" type="text" name="phone" value="{{ old('phone', $user->phone) }}" required>
          </div>
          <div class="field">
            <label class="lbl">City</label>
            @if($cities->isNotEmpty())
              <select class="inp" name="city" required>
                @foreach($cities as $c)
                  <option value="{{ $c->name_ar }}" {{ $user->city===$c->name_ar ? 'selected':'' }}>{{ $c->name_ar }}</option>
                @endforeach
              </select>
            @else
              <input class="inp" type="text" name="city" value="{{ old('city', $user->city) }}" required>
            @endif
          </div>
        </div>
        <div class="field-row">
          <div class="field">
            <label class="lbl">Gender</label>
            <select class="inp" name="gender" required>
              <option value="male"   {{ $user->gender==='male'   ? 'selected':'' }}>Male</option>
              <option value="female" {{ $user->gender==='female' ? 'selected':'' }}>Female</option>
            </select>
          </div>
          <div class="field">
            <label class="lbl">Date of Birth</label>
            <input class="inp" type="date" name="birthdate"
              value="{{ old('birthdate', $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : '') }}" required>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-edit-profile')">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Save Changes</button>
      </div>
    </form>
  </div>
</div>

{{-- ② Create Business --}}
<div class="modal-backdrop" id="modal-create-business" onclick="closeOnBackdrop(event,'modal-create-business')">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-head-icon ic-orange"><i class="ti ti-briefcase"></i></div>
      <span class="modal-title">Create Business Account</span>
      <button class="modal-close" onclick="closeModal('modal-create-business')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" action="{{ route('user.business.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-body">
        <div class="field-row">
          <div class="field">
            <label class="lbl">Business Name</label>
            <input class="inp" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ahmed's Workshop" required>
          </div>
          <div class="field">
            <label class="lbl">Job Title</label>
            <input class="inp" type="text" name="name_job" value="{{ old('name_job') }}" placeholder="e.g. Professional Plumber" required>
          </div>
        </div>
        <div class="field-row">
          <div class="field">
            <label class="lbl">Contact Number</label>
            <input class="inp" type="text" name="number" value="{{ old('number') }}" placeholder="09xxxxxxxx" required>
          </div>
          <div class="field">
            <label class="lbl">Business Type</label>
            <select class="inp" name="active_typebusiness_id" required>
              <option value="">— Select —</option>
              @foreach($activeTypes as $at)
                <option value="{{ $at->id }}">{{ $at->name_ar }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="field">
          <label class="lbl">Specialty / Activity</label>
          <input class="inp" type="text" name="activity" value="{{ old('activity') }}" placeholder="e.g. Plumbing, Pipe installation..." required>
        </div>
        <div class="field">
          <label class="lbl">Description <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
          <textarea class="inp" name="description" placeholder="Describe your business and experience...">{{ old('description') }}</textarea>
        </div>
        <div class="field">
          <label class="lbl">Cover Image <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
          <input class="inp-file" type="file" name="image" accept="image/*">
          <span class="inp-hint">JPG or PNG — max 2MB</span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-create-business')">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="ti ti-send"></i> Submit Request</button>
      </div>
    </form>
  </div>
</div>

{{-- ③ Edit Business --}}
@if($business && $business->status === 'active')
<div class="modal-backdrop" id="modal-edit-business" onclick="closeOnBackdrop(event,'modal-edit-business')">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-head-icon ic-orange"><i class="ti ti-edit"></i></div>
      <span class="modal-title">Edit Business Account</span>
      <button class="modal-close" onclick="closeModal('modal-edit-business')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" action="{{ route('user.business.update') }}" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="modal-body">
        <div class="field-row">
          <div class="field">
            <label class="lbl">Business Name</label>
            <input class="inp" type="text" name="name" value="{{ old('name', $business->name) }}" required>
          </div>
          <div class="field">
            <label class="lbl">Job Title</label>
            <input class="inp" type="text" name="name_job" value="{{ old('name_job', $business->name_job) }}" required>
          </div>
        </div>
        <div class="field-row">
          <div class="field">
            <label class="lbl">Contact Number</label>
            <input class="inp" type="text" name="number" value="{{ old('number', $business->number) }}" required>
          </div>
          <div class="field">
            <label class="lbl">Activity</label>
            <input class="inp" type="text" name="activity" value="{{ old('activity', $business->activity) }}" required>
          </div>
        </div>
        <div class="field">
          <label class="lbl">Description</label>
          <textarea class="inp" name="description">{{ old('description', $business->description) }}</textarea>
        </div>
        <div class="field">
          <label class="lbl">New Image <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
          <input class="inp-file" type="file" name="image" accept="image/*">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-edit-business')">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Save Changes</button>
      </div>
    </form>
  </div>
</div>
@endif

{{-- ④ Add Service --}}
<div class="modal-backdrop" id="modal-add-service" onclick="closeOnBackdrop(event,'modal-add-service')">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-head-icon ic-purple"><i class="ti ti-tool"></i></div>
      <span class="modal-title">Add New Service</span>
      <button class="modal-close" onclick="closeModal('modal-add-service')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" action="{{ route('user.my-services.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-body">
        <div class="field">
          <label class="lbl">Service Name</label>
          <input class="inp" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ceramic Tile Installation" required>
        </div>
        <div class="field">
          <label class="lbl">Description <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
          <textarea class="inp" name="description" placeholder="Describe what you offer...">{{ old('description') }}</textarea>
        </div>
        <div class="field-row">
          <div class="field">
            <label class="lbl">Category</label>
            @if($categories->isNotEmpty())
              <select class="inp" name="category" id="add-cat-select" required onchange="filterAddSubcategories()">
                <option value="">— Select —</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->name_ar }}"
                          data-activity="{{ $cat->active_typebusiness_id }}"
                          data-id="{{ $cat->id }}">{{ $cat->name_ar }}</option>
                @endforeach
              </select>
            @else
              <input class="inp" type="text" name="category" placeholder="Construction" required>
            @endif
          </div>
          <div class="field">
            <label class="lbl">Subcategory</label>
            @if($subcategories->isNotEmpty())
              <select class="inp" name="subcategory" id="add-sub-select" required>
                <option value="">— Select —</option>
                @foreach($subcategories as $sub)
                  <option value="{{ $sub->name_ar }}" data-cat="{{ $sub->category_id }}">{{ $sub->name_ar }}</option>
                @endforeach
              </select>
            @else
              <input class="inp" type="text" name="subcategory" placeholder="Tiles" required>
            @endif
          </div>
        </div>
        <div class="field-row">
          <div class="field">
            <label class="lbl">City</label>
            @if($cities->isNotEmpty())
              <select class="inp" name="city" required>
                <option value="">— Select —</option>
                @foreach($cities as $c)
                  <option value="{{ $c->name_ar }}">{{ $c->name_ar }}</option>
                @endforeach
              </select>
            @else
              <input class="inp" type="text" name="city" placeholder="Damascus" required>
            @endif
          </div>
          <div class="field">
            <label class="lbl">Currency</label>
            <select class="inp" name="price_type" required>
              <option value="syp">Syrian Pound (SYP)</option>
              <option value="usd">US Dollar (USD)</option>
            </select>
          </div>
        </div>
        <div class="field">
          <label class="lbl">Price</label>
          <input class="inp" type="number" name="price" min="0" step="0.01" value="{{ old('price') }}" placeholder="0" required>
        </div>
        <div class="field">
          <label class="lbl">Service Location</label>
          <div style="display:flex;align-items:center;gap:10px;">
            <button type="button" class="btn btn-outline btn-sm" onclick="captureLocation('add-svc')">
              <i class="ti ti-map-pin"></i> Capture My Location
            </button>
            <span id="loc-status-add-svc" style="font-size:11px;color:var(--text-muted);"></span>
          </div>
          <input type="hidden" name="latitude"  id="add-svc-lat">
          <input type="hidden" name="longitude" id="add-svc-lng">
        </div>
        <div class="field">
          <label class="lbl">Image <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
          <input class="inp-file" type="file" name="image" accept="image/*">
          <span class="inp-hint">JPG or PNG — max 2MB</span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-add-service')">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Add Service</button>
      </div>
    </form>
  </div>
</div>

{{-- ⑤ Edit Service --}}
<div class="modal-backdrop" id="modal-edit-service" onclick="closeOnBackdrop(event,'modal-edit-service')">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-head-icon ic-purple"><i class="ti ti-edit"></i></div>
      <span class="modal-title">Edit Service</span>
      <button class="modal-close" onclick="closeModal('modal-edit-service')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" id="edit-service-form" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="modal-body">
        <div class="field">
          <label class="lbl">Service Name</label>
          <input class="inp" type="text" name="name" id="es-name" required>
        </div>
        <div class="field">
          <label class="lbl">Description</label>
          <textarea class="inp" name="description" id="es-description"></textarea>
        </div>
        <div class="field-row">
          <div class="field">
            <label class="lbl">Category</label>
            <input class="inp" type="text" name="category" id="es-category" required>
          </div>
          <div class="field">
            <label class="lbl">Subcategory</label>
            <input class="inp" type="text" name="subcategory" id="es-subcategory" required>
          </div>
        </div>
        <div class="field-row">
          <div class="field">
            <label class="lbl">City</label>
            <input class="inp" type="text" name="city" id="es-city" required>
          </div>
          <div class="field">
            <label class="lbl">Currency</label>
            <select class="inp" name="price_type" id="es-price_type" required>
              <option value="syp">Syrian Pound (SYP)</option>
              <option value="usd">US Dollar (USD)</option>
            </select>
          </div>
        </div>
        <div class="field">
          <label class="lbl">Price</label>
          <input class="inp" type="number" name="price" id="es-price" min="0" step="0.01" required>
        </div>
        <div class="field">
          <label class="lbl">Service Location</label>
          <div style="display:flex;align-items:center;gap:10px;">
            <button type="button" class="btn btn-outline btn-sm" onclick="captureLocation('edit-svc')">
              <i class="ti ti-map-pin"></i> Update Location
            </button>
            <span id="loc-status-edit-svc" style="font-size:11px;color:var(--text-muted);"></span>
          </div>
          <input type="hidden" name="latitude"  id="edit-svc-lat">
          <input type="hidden" name="longitude" id="edit-svc-lng">
        </div>
        <div class="field">
          <label class="lbl">New Image <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
          <input class="inp-file" type="file" name="image" accept="image/*">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-edit-service')">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Save Changes</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
function openModal(id)  { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
function closeOnBackdrop(e,id) { if(e.target===document.getElementById(id)) closeModal(id); }
document.addEventListener('keydown', e => {
  if(e.key==='Escape') document.querySelectorAll('.modal-backdrop.open').forEach(m=>{
    m.classList.remove('open'); document.body.style.overflow='';
  });
});
function openEditService(btn) {
  const id  = btn.dataset.id;
  const svc = JSON.parse(btn.dataset.svc);
  document.getElementById('edit-service-form').action = '{{ url("user/my-services") }}/' + id;
  document.getElementById('es-name').value        = svc.name        || '';
  document.getElementById('es-description').value = svc.description || '';
  document.getElementById('es-category').value    = svc.category    || '';
  document.getElementById('es-subcategory').value = svc.subcategory || '';
  document.getElementById('es-city').value        = svc.city        || '';
  document.getElementById('es-price').value       = svc.price       || '';
  document.getElementById('es-price_type').value  = svc.price_type  || 'syp';
  // Restore saved coordinates if present
  document.getElementById('edit-svc-lat').value = svc.latitude  || '';
  document.getElementById('edit-svc-lng').value = svc.longitude || '';
  const st = document.getElementById('loc-status-edit-svc');
  if (svc.latitude && svc.longitude) {
    st.textContent = `📍 ${parseFloat(svc.latitude).toFixed(5)}, ${parseFloat(svc.longitude).toFixed(5)}`;
    st.style.color = 'var(--accent)';
  } else {
    st.textContent = 'No location saved';
    st.style.color = 'var(--text-muted)';
  }
  openModal('modal-edit-service');
}

function captureLocation(prefix) {
  const statusEl = document.getElementById('loc-status-' + prefix);
  if (!navigator.geolocation) {
    statusEl.textContent = 'Geolocation not supported';
    return;
  }
  statusEl.textContent = 'Locating...';
  navigator.geolocation.getCurrentPosition(
    pos => {
      const lat = pos.coords.latitude.toFixed(7);
      const lng = pos.coords.longitude.toFixed(7);
      document.getElementById(prefix + '-lat').value = lat;
      document.getElementById(prefix + '-lng').value = lng;
      statusEl.textContent = `📍 ${parseFloat(lat).toFixed(5)}, ${parseFloat(lng).toFixed(5)}`;
      statusEl.style.color = 'var(--accent)';
    },
    () => {
      statusEl.textContent = 'Could not get location';
      statusEl.style.color = '#EF4444';
    }
  );
}
@if($errors->any())
  @if(old('_method')==='PUT' && old('first_name'))   openModal('modal-edit-profile');
  @elseif(old('_method')==='PUT' && old('name_job')) openModal('modal-edit-business');
  @elseif(old('name') && old('price'))               openModal('modal-add-service');
  @elseif(!@$business)                               openModal('modal-create-business');
  @endif
@endif

/* ── Category / Subcategory filtering by business activity ── */
const bizActivityId = {{ $business?->active_typebusiness_id ?? 'null' }};

function filterAddCategories() {
  const sel = document.getElementById('add-cat-select');
  if (!sel) return;
  Array.from(sel.options).forEach(opt => {
    if (!opt.value) return;
    opt.hidden = bizActivityId
      ? parseInt(opt.dataset.activity) !== bizActivityId
      : false;
  });
  sel.value = '';
  filterAddSubcategories();
}

function filterAddSubcategories() {
  const catSel = document.getElementById('add-cat-select');
  const subSel = document.getElementById('add-sub-select');
  if (!subSel) return;
  const catId = catSel && catSel.value
    ? catSel.options[catSel.selectedIndex]?.dataset?.id
    : null;
  Array.from(subSel.options).forEach(opt => {
    if (!opt.value) return;
    opt.hidden = catId ? opt.dataset.cat !== catId : false;
  });
  subSel.value = '';
}

document.addEventListener('DOMContentLoaded', filterAddCategories);
</script>
@endsection
