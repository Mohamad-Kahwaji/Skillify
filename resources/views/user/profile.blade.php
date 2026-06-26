@extends('user.layout')

@section('title', 'Profile')

@section('styles')
<style>
/* ── Tabs ─────────────────────────────────────────── */
.tab-bar {
  display:flex; gap:2px;
  background:var(--bg-surface);
  border:.5px solid var(--border);
  border-radius:var(--radius-lg);
  padding:4px; width:fit-content;
}
.tab-btn {
  display:flex; align-items:center; gap:7px;
  padding:8px 18px; border-radius:calc(var(--radius-lg) - 3px);
  font-size:13px; font-weight:500;
  border:none; background:none; color:var(--text-secondary);
  cursor:pointer; transition:all .15s; white-space:nowrap;
}
.tab-btn:hover { color:var(--text-primary); background:var(--bg-hover); }
.tab-btn.active {
  background:var(--accent); color:#fff;
  box-shadow:0 2px 8px rgba(29,158,117,.25);
}
.tab-btn .tab-badge {
  font-size:10px; font-weight:700; padding:1px 6px;
  border-radius:20px; background:rgba(255,255,255,.25); line-height:1.4;
}
.tab-btn:not(.active) .tab-badge {
  background:var(--bg-sunken); color:var(--text-muted);
}

.tab-pane { display:none; }
.tab-pane.active { display:flex; flex-direction:column; gap:20px; }

/* ── Form Grid ────────────────────────────────────── */
.form-grid {
  display:grid; grid-template-columns:1fr 1fr; gap:14px;
}
.col-span-2 { grid-column:span 2; }

.form-group { display:flex; flex-direction:column; gap:5px; }
.form-label { font-size:12px; font-weight:500; color:var(--text-secondary); }
.form-input, .form-select, .form-textarea {
  width:100%; padding:9px 12px;
  background:var(--bg-sunken); border:.5px solid var(--border-md);
  border-radius:var(--radius-sm); color:var(--text-primary);
  font-size:13px; font-family:var(--font);
  transition:border-color .12s; outline:none;
}
.form-input:focus, .form-select:focus, .form-textarea:focus {
  border-color:var(--accent); background:var(--bg-surface);
}
.form-select option { background:var(--bg-surface); }
.form-textarea { resize:vertical; min-height:80px; }

/* ── File Upload ──────────────────────────────────── */
.file-label {
  display:flex; align-items:center; gap:10px;
  padding:10px 14px; border:.5px dashed var(--border-md);
  border-radius:var(--radius-sm); cursor:pointer;
  color:var(--text-secondary); font-size:12px;
  transition:border-color .12s, background .12s;
}
.file-label:hover { border-color:var(--accent); background:var(--bg-hover); }
.file-label input { display:none; }
.file-label i { font-size:18px; }
.file-preview { width:56px; height:56px; border-radius:var(--radius-sm); object-fit:cover; border:.5px solid var(--border); }

/* ── Buttons ──────────────────────────────────────── */
.btn-primary {
  display:inline-flex; align-items:center; gap:6px;
  background:var(--accent); color:#fff; border:none; cursor:pointer;
  padding:9px 20px; border-radius:var(--radius-md);
  font-size:13px; font-weight:600; font-family:var(--font);
  transition:background .12s; white-space:nowrap;
}
.btn-primary:hover { background:var(--accent-hover); }
.btn-secondary {
  display:inline-flex; align-items:center; gap:6px;
  background:none; color:var(--text-secondary);
  border:.5px solid var(--border-md); cursor:pointer;
  padding:8px 16px; border-radius:var(--radius-md);
  font-size:13px; font-weight:500; font-family:var(--font);
  transition:all .12s;
}
.btn-secondary:hover { background:var(--bg-hover); color:var(--text-primary); }

/* ── Notice Banners ───────────────────────────────── */
.notice {
  display:flex; align-items:flex-start; gap:12px;
  padding:12px 16px; border-radius:var(--radius-md); font-size:13px;
}
.notice.pending  { background:#FFFBEB; border:.5px solid #FDE68A; color:#78350F; }
.notice.approved { background:var(--green-50); border:.5px solid #9FE1CB; color:var(--teal-900); }
.notice.rejected { background:var(--red-50); border:.5px solid #FECACA; color:var(--red-800); }
.notice i { font-size:17px; flex-shrink:0; margin-top:1px; }

/* ── Service Grid & Cards ─────────────────────────── */
.svc-grid {
  display:grid; grid-template-columns:repeat(auto-fill, minmax(255px, 1fr)); gap:14px;
}
.svc-card {
  background:var(--bg-surface); border:.5px solid var(--border);
  border-radius:var(--radius-lg); overflow:hidden;
  display:flex; flex-direction:column;
  transition:border-color .15s, box-shadow .15s;
}
.svc-card:hover { border-color:var(--accent); box-shadow:0 4px 16px rgba(29,158,117,.08); }
.svc-thumb {
  width:100%; height:140px; background:var(--bg-sunken); overflow:hidden;
  display:flex; align-items:center; justify-content:center;
  font-size:34px; color:var(--text-muted);
}
.svc-thumb img { width:100%; height:100%; object-fit:cover; }
.svc-body { padding:12px 14px; flex:1; display:flex; flex-direction:column; gap:5px; }
.svc-name { font-size:13px; font-weight:700; }
.svc-meta { display:flex; flex-wrap:wrap; gap:4px; }
.svc-tag {
  font-size:10px; color:var(--text-muted);
  background:var(--bg-sunken); padding:2px 7px;
  border-radius:20px; border:.5px solid var(--border);
}
.svc-price { font-size:14px; font-weight:800; color:var(--accent); }
.svc-price small { font-size:10px; font-weight:400; color:var(--text-muted); }
.svc-footer {
  padding:9px 14px; border-top:.5px solid var(--border);
  display:flex; align-items:center; justify-content:space-between;
}

/* ── Status Badge ─────────────────────────────────── */
.status-badge {
  display:inline-flex; align-items:center; gap:4px;
  font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px;
}
.status-badge::before { content:''; width:5px; height:5px; border-radius:50%; background:currentColor; opacity:.7; }
.status-badge.approved { background:var(--green-50); color:var(--teal-900); }
.status-badge.pending  { background:#FEF3C7; color:#92400E; }
.status-badge.rejected { background:var(--red-50); color:var(--red-800); }
.status-badge.inactive { background:#F3F4F6; color:#6B7280; }

/* ── Action Buttons ───────────────────────────────── */
.act-btn {
  display:inline-flex; align-items:center; justify-content:center;
  width:30px; height:30px; border-radius:var(--radius-sm);
  border:.5px solid var(--border-md); background:none;
  color:var(--text-secondary); cursor:pointer; transition:all .12s; font-size:14px;
}
.act-btn.edit:hover { background:rgba(59,130,246,.08); color:#3b82f6; border-color:#3b82f6; }
.act-btn.del:hover  { background:var(--red-50); color:var(--red-400); border-color:var(--red-400); }

/* ── Modal ────────────────────────────────────────── */
.modal-overlay {
  display:none; position:fixed; inset:0; z-index:200;
  background:rgba(0,0,0,.45); backdrop-filter:blur(3px);
  align-items:center; justify-content:center; padding:16px;
}
.modal-overlay.open { display:flex; }
.modal-box {
  background:var(--bg-surface); border:.5px solid var(--border-md);
  border-radius:16px; width:100%; max-width:540px;
  max-height:90vh; display:flex; flex-direction:column;
  overflow:hidden; animation:fadeUp .18s ease;
}
@keyframes fadeUp {
  from { opacity:0; transform:translateY(16px); }
  to   { opacity:1; transform:translateY(0); }
}
.modal-head {
  display:flex; align-items:center; justify-content:space-between;
  padding:16px 20px; border-bottom:.5px solid var(--border); flex-shrink:0;
}
.modal-title { font-size:15px; font-weight:600; }
.modal-close {
  width:28px; height:28px; border-radius:8px;
  border:none; background:none; color:var(--text-secondary);
  font-size:17px; display:flex; align-items:center; justify-content:center;
  cursor:pointer; transition:background .12s;
}
.modal-close:hover { background:var(--bg-hover); }
.modal-scroll { overflow-y:auto; flex:1; }
.modal-body { padding:20px; display:flex; flex-direction:column; gap:14px; }
.modal-footer {
  padding:12px 20px; border-top:.5px solid var(--border);
  display:flex; justify-content:flex-end; gap:8px; flex-shrink:0;
}

/* ── Business Avatar ──────────────────────────────── */
.biz-avatar {
  width:68px; height:68px; border-radius:var(--radius-md);
  background:var(--accent); display:flex; align-items:center;
  justify-content:center; font-size:26px; font-weight:700; color:#fff;
  flex-shrink:0; overflow:hidden;
}
.biz-avatar img { width:100%; height:100%; object-fit:cover; }

/* ── Empty State ──────────────────────────────────── */
.empty-state {
  padding:48px 24px; text-align:center;
  background:var(--bg-surface); border:.5px solid var(--border);
  border-radius:var(--radius-lg); color:var(--text-muted);
}
.empty-state i { font-size:42px; display:block; margin-bottom:10px; opacity:.25; }
.empty-state p { font-size:13px; margin-bottom:14px; }
</style>
@endsection

@section('content')
@php
  $svcCount  = $userServices->count();
  $activeTab = request('tab', 'profile');
@endphp

{{-- Page header --}}
<div>
  <div class="page-title">My Account</div>
  <div class="page-sub">Manage your profile, business account, and services</div>
</div>

{{-- Tab bar --}}
<div class="tab-bar">
  <button class="tab-btn {{ $activeTab === 'profile'  ? 'active' : '' }}" onclick="switchTab(event,'profile')">
    <i class="ti ti-user"></i> Profile
  </button>
  <button class="tab-btn {{ $activeTab === 'business' ? 'active' : '' }}" onclick="switchTab(event,'business')">
    <i class="ti ti-briefcase"></i> Business Account
    @if($business)
      <span class="tab-badge">{{ ucfirst($business->status) }}</span>
    @endif
  </button>
  <button class="tab-btn {{ $activeTab === 'services' ? 'active' : '' }}" onclick="switchTab(event,'services')">
    <i class="ti ti-tool"></i> My Services
    @if($svcCount)
      <span class="tab-badge">{{ $svcCount }}</span>
    @endif
  </button>
</div>

{{-- ════════════════════════ TAB: PROFILE ════════════════════════ --}}
<div class="tab-pane {{ $activeTab === 'profile' ? 'active' : '' }}" id="tab-profile">
  <div class="card">
    <div class="card-head">
      <div class="card-title"><i class="ti ti-user-edit" style="margin-right:5px;"></i>Personal Information</div>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('user.profile.update') }}">
        @csrf @method('PUT')
        <div class="form-grid">

          <div class="form-group">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-input"
                   value="{{ old('first_name', $user->first_name) }}" required>
            @error('first_name')<span style="font-size:11px;color:var(--red-400);">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-input"
                   value="{{ old('last_name', $user->last_name) }}" required>
            @error('last_name')<span style="font-size:11px;color:var(--red-400);">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-input"
                   value="{{ old('phone', $user->phone) }}" required>
            @error('phone')<span style="font-size:11px;color:var(--red-400);">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label class="form-label">City</label>
            <select name="city" class="form-select" required>
              <option value="">— Select city —</option>
              @foreach($cities as $city)
              <option value="{{ $city->name_ar }}"
                {{ old('city', $user->city) === $city->name_ar ? 'selected' : '' }}>
                {{ $city->name_ar }}
              </option>
              @endforeach
            </select>
            @error('city')<span style="font-size:11px;color:var(--red-400);">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select" required>
              <option value="">— Select —</option>
              <option value="male"   {{ old('gender', $user->gender) === 'male'   ? 'selected' : '' }}>Male</option>
              <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
            </select>
            @error('gender')<span style="font-size:11px;color:var(--red-400);">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label class="form-label">Birthdate</label>
            <input type="date" name="birthdate" class="form-input"
                   value="{{ old('birthdate', optional($user->birthdate)->format('Y-m-d')) }}">
            @error('birthdate')<span style="font-size:11px;color:var(--red-400);">{{ $message }}</span>@enderror
          </div>

          <div class="col-span-2" style="display:flex;justify-content:flex-end;margin-top:4px;">
            <button type="submit" class="btn-primary">
              <i class="ti ti-device-floppy"></i> Save Changes
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>
</div>

{{-- ════════════════════════ TAB: BUSINESS ════════════════════════ --}}
<div class="tab-pane {{ $activeTab === 'business' ? 'active' : '' }}" id="tab-business">

  @if(!$business)
  {{-- No business yet — create form --}}
  <div class="card">
    <div class="card-head">
      <div class="card-title"><i class="ti ti-building-store" style="margin-right:5px;"></i>Create Business Account</div>
    </div>
    <div class="card-body">
      <div class="notice pending" style="margin-bottom:16px;">
        <i class="ti ti-info-circle"></i>
        <span>After submitting, your business account will be reviewed by an admin before being activated.</span>
      </div>
      <form method="POST" action="{{ route('user.business.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-grid">

          <div class="form-group">
            <label class="form-label">Job Title</label>
            <input type="text" name="name_job" class="form-input" value="{{ old('name_job') }}" placeholder="e.g. Electrician, Plumber…" required>
            @error('name_job')<span style="font-size:11px;color:var(--red-400);">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label class="form-label">Commercial / License Number</label>
            <input type="text" name="number" class="form-input" value="{{ old('number') }}" placeholder="e.g. 123456789" required>
            @error('number')<span style="font-size:11px;color:var(--red-400);">{{ $message }}</span>@enderror
          </div>

          <div class="form-group col-span-2">
            <label class="form-label">Business Type</label>
            <select name="active_typebusiness_id" class="form-select" required>
              <option value="">— Select type —</option>
              @foreach($activeTypes as $type)
              <option value="{{ $type->id }}" {{ old('active_typebusiness_id') == $type->id ? 'selected' : '' }}>
                {{ $type->name_ar }}
              </option>
              @endforeach
            </select>
            @error('active_typebusiness_id')<span style="font-size:11px;color:var(--red-400);">{{ $message }}</span>@enderror
          </div>

          <div class="form-group col-span-2">
            <label class="form-label"><i class="ti ti-map-pin" style="font-size:13px;margin-right:3px;"></i>Location</label>
            <input type="hidden" name="latitude"  id="biz-lat-create"  value="{{ old('latitude') }}">
            <input type="hidden" name="longitude" id="biz-lng-create" value="{{ old('longitude') }}">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
              <button type="button" id="biz-loc-btn-create" class="btn-secondary"
                      style="font-size:12px;padding:7px 14px;"
                      onclick="getLocation('create',this)">
                <i class="ti ti-current-location"></i> Use My Location
              </button>
              <span id="biz-loc-label-create" style="font-size:12px;color:var(--text-muted);">
                {{ old('latitude') ? 'Location saved — click to update' : 'No location selected yet' }}
              </span>
            </div>
            @error('latitude')<span style="font-size:11px;color:var(--red-400);">{{ $message }}</span>@enderror
          </div>

          <div class="form-group col-span-2">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-textarea" placeholder="Tell us about your business and what you offer…">{{ old('description') }}</textarea>
            @error('description')<span style="font-size:11px;color:var(--red-400);">{{ $message }}</span>@enderror
          </div>

          <div class="form-group col-span-2">
            <label class="form-label">Business Image <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
            <label class="file-label">
              <i class="ti ti-photo-up"></i>
              <span id="biz-file-name-create">Click to upload image</span>
              <input type="file" name="image" accept="image/*"
                     onchange="previewFile(this,'biz-file-name-create','biz-img-create')">
            </label>
            <img id="biz-img-create" class="file-preview" style="display:none;margin-top:6px;">
            @error('image')<span style="font-size:11px;color:var(--red-400);">{{ $message }}</span>@enderror
          </div>

          <div class="col-span-2" style="display:flex;justify-content:flex-end;">
            <button type="submit" class="btn-primary">
              <i class="ti ti-send"></i> Submit for Review
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>

  @else
  {{-- Business exists --}}

  {{-- Status notice --}}
  @if($business->status === 'pending')
  <div class="notice pending">
    <i class="ti ti-clock"></i>
    <span><strong>Your business account is under review.</strong> An admin will process it shortly.</span>
  </div>
  @elseif($business->status === 'rejected')
  <div class="notice rejected">
    <i class="ti ti-circle-x"></i>
    <span><strong>Your business account was rejected.</strong> Please contact support for more information.</span>
  </div>
  @elseif(in_array($business->status, ['approved','active']))
  <div class="notice approved">
    <i class="ti ti-circle-check"></i>
    <span><strong>Your business account is active.</strong> You can now offer services to users.</span>
  </div>
  @endif

  <div class="card">
    <div class="card-head">
      <div style="display:flex;align-items:center;gap:12px;">
        <div class="biz-avatar">
          @if($business->image)
            <img src="{{ asset('storage/'.$business->image) }}" alt="{{ $business->name }}"
                 onerror="this.parentElement.textContent='{{ strtoupper(substr($business->name,0,1)) }}'">
          @else
            {{ strtoupper(substr($business->name, 0, 1)) }}
          @endif
        </div>
        <div>
          <div class="card-title">{{ $business->name }}</div>
          <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">
            {{ $business->name_job }}
            &nbsp;·&nbsp;
            <span class="status-badge {{ in_array($business->status,['approved','active']) ? 'approved' : $business->status }}">
              {{ ucfirst($business->status) }}
            </span>
          </div>
        </div>
      </div>
      <button class="btn-secondary" onclick="toggleBizEdit()">
        <i class="ti ti-pencil"></i> Edit
      </button>
    </div>

    {{-- Read-only view --}}
    <div class="card-body" id="biz-view">
      <div class="info-row">
        <span class="info-label"><i class="ti ti-certificate" style="font-size:12px;margin-right:4px;"></i>License No.</span>
        <span class="info-value">{{ $business->number }}</span>
      </div>
      @php
        $nearestCity = null;
        if ($business->latitude && $business->longitude) {
            $nearestCity = $cities->sortBy(function($c) use ($business) {
                $dLat = ($c->latitude  - $business->latitude)  * M_PI / 180;
                $dLng = ($c->longitude - $business->longitude) * M_PI / 180;
                $a = sin($dLat/2)**2
                   + cos($business->latitude*M_PI/180) * cos($c->latitude*M_PI/180) * sin($dLng/2)**2;
                return 2 * atan2(sqrt($a), sqrt(1-$a));
            })->first();
        }
      @endphp
      @if($nearestCity)
      <div class="info-row">
        <span class="info-label"><i class="ti ti-map-pin" style="font-size:12px;margin-right:4px;"></i>Location</span>
        <span class="info-value">{{ $nearestCity->name_ar }}</span>
      </div>
      @endif
      @if($business->description)
      <div class="info-row" style="align-items:flex-start;">
        <span class="info-label"><i class="ti ti-align-left" style="font-size:12px;margin-right:4px;"></i>Description</span>
        <span class="info-value" style="line-height:1.6;">{{ $business->description }}</span>
      </div>
      @endif
    </div>

    {{-- Edit form --}}
    <div id="biz-edit" style="display:none;border-top:.5px solid var(--border);">
      <div class="card-body">
        <form method="POST" action="{{ route('user.business.update') }}" enctype="multipart/form-data">
          @csrf @method('PUT')
          <div class="form-grid">

            <div class="form-group">
              <label class="form-label">Job Title</label>
              <input type="text" name="name_job" class="form-input" value="{{ old('name_job', $business->name_job) }}" placeholder="e.g. Electrician" required>
            </div>

            <div class="form-group">
              <label class="form-label">Commercial / License Number</label>
              <input type="text" name="number" class="form-input" value="{{ old('number', $business->number) }}" placeholder="e.g. 123456789" required>
            </div>

            <div class="form-group col-span-2">
              <label class="form-label"><i class="ti ti-map-pin" style="font-size:13px;margin-right:3px;"></i>Location</label>
              <input type="hidden" name="latitude"  id="biz-lat-edit"  value="">
              <input type="hidden" name="longitude" id="biz-lng-edit" value="">
              <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <button type="button" id="biz-loc-btn-edit" class="btn-secondary"
                        style="font-size:12px;padding:7px 14px;"
                        onclick="getLocation('edit',this)">
                  <i class="ti ti-current-location"></i> Update My Location
                </button>
                <span id="biz-loc-label-edit" style="font-size:12px;color:var(--text-muted);">
                  {{ $nearestCity ? 'Current: '.$nearestCity->name_ar.' — click to update' : 'Click to set location' }}
                </span>
              </div>
            </div>

            <div class="form-group col-span-2">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-textarea" placeholder="Tell us about your business…">{{ old('description', $business->description) }}</textarea>
            </div>

            <div class="form-group col-span-2">
              <label class="form-label">Business Image</label>
              <label class="file-label">
                <i class="ti ti-photo-up"></i>
                <span id="biz-file-name-edit">Click to change image</span>
                <input type="file" name="image" accept="image/*"
                       onchange="previewFile(this,'biz-file-name-edit','biz-img-edit')">
              </label>
              @if($business->image)
              <img id="biz-img-edit" src="{{ asset('storage/'.$business->image) }}"
                   class="file-preview" style="margin-top:6px;">
              @else
              <img id="biz-img-edit" class="file-preview" style="display:none;margin-top:6px;">
              @endif
            </div>

            <div class="col-span-2" style="display:flex;justify-content:flex-end;gap:8px;">
              <button type="button" class="btn-secondary" onclick="toggleBizEdit()">Cancel</button>
              <button type="submit" class="btn-primary">
                <i class="ti ti-device-floppy"></i> Save Changes
              </button>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>
  @endif

</div>

{{-- ════════════════════════ TAB: SERVICES ════════════════════════ --}}
<div class="tab-pane {{ $activeTab === 'services' ? 'active' : '' }}" id="tab-services">

  <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <div>
      <div style="font-size:15px;font-weight:600;">My Services</div>
      <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">{{ $svcCount }} service(s) listed</div>
    </div>
    <button class="btn-primary" onclick="document.getElementById('add-svc-modal').classList.add('open')">
      <i class="ti ti-plus"></i> Add Service
    </button>
  </div>

  @if($userServices->isEmpty())
  <div class="empty-state">
    <i class="ti ti-tool-off"></i>
    <p>You haven't added any services yet.</p>
    <button class="btn-primary" onclick="document.getElementById('add-svc-modal').classList.add('open')">
      <i class="ti ti-plus"></i> Add Your First Service
    </button>
  </div>

  @else
  <div class="svc-grid">
    @foreach($userServices as $svc)
    @php
      $img = $svc->image
        ? (str_starts_with($svc->image,'http') ? $svc->image : asset('storage/'.$svc->image))
        : null;
      $statusKey = match($svc->status ?? '') {
        'pending'  => 'pending',
        'rejected' => 'rejected',
        'approved' => 'approved',
        default    => $svc->is_active ? 'approved' : 'inactive',
      };
      $statusLabel = ['pending'=>'Under Review','rejected'=>'Rejected','approved'=>'Active','inactive'=>'Inactive'][$statusKey] ?? 'Active';
    @endphp
    <div class="svc-card">
      <div class="svc-thumb">
        @if($img)
          <img src="{{ $img }}" alt="{{ $svc->name }}"
               onerror="this.style.display='none';this.parentElement.innerHTML='<i class=\'ti ti-tool\'></i>'">
        @else
          <i class="ti ti-tool"></i>
        @endif
      </div>
      <div class="svc-body">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:6px;">
          <div class="svc-name">{{ $svc->name }}</div>
          <span class="status-badge {{ $statusKey }}">{{ $statusLabel }}</span>
        </div>
        <div class="svc-meta">
          @if($svc->category)    <span class="svc-tag">{{ $svc->category }}</span>    @endif
          @if($svc->subcategory) <span class="svc-tag">{{ $svc->subcategory }}</span> @endif
          @if($svc->city)        <span class="svc-tag"><i class="ti ti-map-pin" style="font-size:10px;"></i> {{ $svc->city }}</span> @endif
        </div>
      </div>
      <div class="svc-footer">
        <div class="svc-price">
          {{ number_format($svc->price, 0) }}
          <small>{{ strtoupper($svc->price_type) }}</small>
        </div>
        <div style="display:flex;gap:4px;">
          <button class="act-btn edit" title="Edit"
            data-id="{{ $svc->id }}"
            data-name="{{ $svc->name }}"
            data-description="{{ $svc->description }}"
            data-category="{{ $svc->category }}"
            data-subcategory="{{ $svc->subcategory }}"
            data-city="{{ $svc->city }}"
            data-price="{{ $svc->price }}"
            data-price_type="{{ $svc->price_type }}"
            data-image="{{ $img }}"
            onclick="openEditSvc(this)">
            <i class="ti ti-pencil"></i>
          </button>
          <form method="POST" action="{{ route('user.my-services.destroy', $svc->id) }}"
                onsubmit="return confirm('Delete this service?')">
            @csrf @method('DELETE')
            <button type="submit" class="act-btn del" title="Delete">
              <i class="ti ti-trash"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif

</div>

{{-- ════════════════════════ MODAL: Add Service ════════════════════════ --}}
<div class="modal-overlay" id="add-svc-modal" onclick="closeOnBackdrop(event,'add-svc-modal')">
  <div class="modal-box">
    <div class="modal-head">
      <div class="modal-title"><i class="ti ti-plus" style="margin-right:5px;"></i>Add New Service</div>
      <button class="modal-close" onclick="document.getElementById('add-svc-modal').classList.remove('open')">
        <i class="ti ti-x"></i>
      </button>
    </div>
    <form method="POST" action="{{ route('user.my-services.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-scroll">
        <div class="modal-body">
          <div class="form-grid">

            <div class="form-group col-span-2">
              <label class="form-label">Service Name</label>
              <input type="text" name="name" class="form-input" placeholder="e.g. Electrical Repair" required>
            </div>

            <div class="form-group">
              <label class="form-label">Category</label>
              <select name="category" id="add-svc-category" class="form-select"
                      onchange="filterSubcats('add-svc-category','add-svc-subcategory')" required>
                <option value="">— Select —</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->name_ar }}" data-id="{{ $cat->id }}">{{ $cat->name_ar }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Subcategory</label>
              <select name="subcategory" id="add-svc-subcategory" class="form-select" required>
                <option value="">— Select —</option>
                @foreach($subcategories as $sub)
                <option value="{{ $sub->name_ar }}" data-cat-id="{{ $sub->category_id }}">{{ $sub->name_ar }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">City</label>
              <select name="city" class="form-select" required>
                <option value="">— Select —</option>
                @foreach($cities as $city)
                <option value="{{ $city->name_ar }}">{{ $city->name_ar }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Price</label>
              <input type="number" name="price" class="form-input" placeholder="0" min="0" step="0.01" required>
            </div>

            <div class="form-group col-span-2">
              <label class="form-label">Currency</label>
              <select name="price_type" class="form-select" required>
                <option value="usd">USD</option>
                <option value="syp">SYP</option>
              </select>
            </div>

            <div class="form-group col-span-2">
              <label class="form-label">Description <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
              <textarea name="description" class="form-textarea" placeholder="Describe your service…"></textarea>
            </div>

            <div class="form-group col-span-2">
              <label class="form-label">Image <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
              <label class="file-label">
                <i class="ti ti-photo-up"></i>
                <span id="add-svc-file-name">Click to upload</span>
                <input type="file" name="image" accept="image/*"
                       onchange="previewFile(this,'add-svc-file-name','add-svc-img')">
              </label>
              <img id="add-svc-img" class="file-preview" style="display:none;margin-top:6px;">
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary"
                onclick="document.getElementById('add-svc-modal').classList.remove('open')">Cancel</button>
        <button type="submit" class="btn-primary"><i class="ti ti-check"></i> Add Service</button>
      </div>
    </form>
  </div>
</div>

{{-- ════════════════════════ MODAL: Edit Service ════════════════════════ --}}
<div class="modal-overlay" id="edit-svc-modal" onclick="closeOnBackdrop(event,'edit-svc-modal')">
  <div class="modal-box">
    <div class="modal-head">
      <div class="modal-title"><i class="ti ti-pencil" style="margin-right:5px;"></i>Edit Service</div>
      <button class="modal-close" onclick="document.getElementById('edit-svc-modal').classList.remove('open')">
        <i class="ti ti-x"></i>
      </button>
    </div>
    <form method="POST" id="edit-svc-form" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="modal-scroll">
        <div class="modal-body">
          <div class="form-grid">

            <div class="form-group col-span-2">
              <label class="form-label">Service Name</label>
              <input type="text" name="name" id="edit-svc-name" class="form-input" required>
            </div>

            <div class="form-group">
              <label class="form-label">Category</label>
              <select name="category" id="edit-svc-category" class="form-select"
                      onchange="filterSubcats('edit-svc-category','edit-svc-subcategory')" required>
                <option value="">— Select —</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->name_ar }}" data-id="{{ $cat->id }}">{{ $cat->name_ar }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Subcategory</label>
              <select name="subcategory" id="edit-svc-subcategory" class="form-select" required>
                <option value="">— Select —</option>
                @foreach($subcategories as $sub)
                <option value="{{ $sub->name_ar }}" data-cat-id="{{ $sub->category_id }}">{{ $sub->name_ar }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">City</label>
              <select name="city" id="edit-svc-city" class="form-select" required>
                <option value="">— Select —</option>
                @foreach($cities as $city)
                <option value="{{ $city->name_ar }}">{{ $city->name_ar }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Price</label>
              <input type="number" name="price" id="edit-svc-price" class="form-input" min="0" step="0.01" required>
            </div>

            <div class="form-group col-span-2">
              <label class="form-label">Currency</label>
              <select name="price_type" id="edit-svc-price_type" class="form-select" required>
                <option value="usd">USD</option>
                <option value="syp">SYP</option>
              </select>
            </div>

            <div class="form-group col-span-2">
              <label class="form-label">Description</label>
              <textarea name="description" id="edit-svc-description" class="form-textarea"></textarea>
            </div>

            <div class="form-group col-span-2">
              <label class="form-label">Image</label>
              <label class="file-label">
                <i class="ti ti-photo-up"></i>
                <span id="edit-svc-file-name">Click to change image</span>
                <input type="file" name="image" accept="image/*"
                       onchange="previewFile(this,'edit-svc-file-name','edit-svc-img')">
              </label>
              <img id="edit-svc-img" class="file-preview" style="display:none;margin-top:6px;">
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary"
                onclick="document.getElementById('edit-svc-modal').classList.remove('open')">Cancel</button>
        <button type="submit" class="btn-primary"><i class="ti ti-device-floppy"></i> Save Changes</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
// ── Tab switching ──────────────────────────────────────────────────────────
function switchTab(event, name) {
  document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + name).classList.add('active');
  event.currentTarget.classList.add('active');
  history.replaceState(null, '', '?tab=' + name);
}

// ── Toggle business edit form ──────────────────────────────────────────────
function toggleBizEdit() {
  const form = document.getElementById('biz-edit');
  const view = document.getElementById('biz-view');
  if (!form) return;
  const open = form.style.display === 'none';
  form.style.display = open ? '' : 'none';
  if (view) view.style.display = open ? 'none' : '';
}

// ── File preview ───────────────────────────────────────────────────────────
function previewFile(input, nameId, previewId) {
  const file    = input.files[0];
  const nameEl  = document.getElementById(nameId);
  const preview = document.getElementById(previewId);
  if (!file) return;
  nameEl.textContent = file.name;
  const reader = new FileReader();
  reader.onload = e => {
    preview.src           = e.target.result;
    preview.style.display = '';
  };
  reader.readAsDataURL(file);
}

// ── Open edit service modal ────────────────────────────────────────────────
function openEditSvc(btn) {
  const id = btn.dataset.id;
  document.getElementById('edit-svc-form').action   = '/user/my-services/' + id;
  document.getElementById('edit-svc-name').value        = btn.dataset.name        || '';
  document.getElementById('edit-svc-description').value = btn.dataset.description || '';
  document.getElementById('edit-svc-price').value       = btn.dataset.price       || '';

  setSelectValue('edit-svc-category',   btn.dataset.category);
  filterSubcats('edit-svc-category', 'edit-svc-subcategory');
  setSelectValue('edit-svc-subcategory',btn.dataset.subcategory);
  setSelectValue('edit-svc-city',       btn.dataset.city);
  setSelectValue('edit-svc-price_type', btn.dataset.price_type);

  const preview = document.getElementById('edit-svc-img');
  if (btn.dataset.image) {
    preview.src           = btn.dataset.image;
    preview.style.display = '';
  } else {
    preview.style.display = 'none';
  }

  document.getElementById('edit-svc-modal').classList.add('open');
}

function setSelectValue(id, value) {
  const sel = document.getElementById(id);
  if (!sel || !value) return;
  for (const opt of sel.options) opt.selected = (opt.value === value);
}

// ── Filter subcategories by selected category ──────────────────────────────
function filterSubcats(catId, subId) {
  const catSel = document.getElementById(catId);
  const subSel = document.getElementById(subId);
  if (!catSel || !subSel) return;

  const selOpt   = catSel.options[catSel.selectedIndex];
  const catDbId  = selOpt ? selOpt.dataset.id : null;

  subSel.value = '';
  Array.from(subSel.options).forEach(opt => {
    if (!opt.value) { opt.hidden = false; return; }
    opt.hidden   = catDbId ? opt.dataset.catId !== catDbId : false;
    opt.disabled = opt.hidden;
  });
}

// ── Close modal on backdrop click ──────────────────────────────────────────
function closeOnBackdrop(event, id) {
  if (event.target === event.currentTarget)
    document.getElementById(id).classList.remove('open');
}

// ── Geolocation + nearest city ─────────────────────────────────────────────
@php
  $bizCitiesJs = $cities->map(fn($c) => [
      'name' => $c->name_ar,
      'lat'  => (float)$c->latitude,
      'lng'  => (float)$c->longitude,
  ])->values();
@endphp
const _bizCities = @json($bizCitiesJs);

function _haversineKm(lat1, lng1, lat2, lng2) {
  const R = 6371, toR = Math.PI / 180;
  const dLat = (lat2 - lat1) * toR, dLng = (lng2 - lng1) * toR;
  const a = Math.sin(dLat/2)**2
          + Math.cos(lat1*toR) * Math.cos(lat2*toR) * Math.sin(dLng/2)**2;
  return 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)) * R;
}

function _nearestCity(lat, lng) {
  let best = null, bestDist = Infinity;
  _bizCities.forEach(c => {
    const d = _haversineKm(lat, lng, c.lat, c.lng);
    if (d < bestDist) { bestDist = d; best = c; }
  });
  return best ? { name: best.name, km: Math.round(bestDist) } : null;
}

function getLocation(mode, btn) {
  if (!navigator.geolocation) { alert('Geolocation not supported by your browser.'); return; }
  const origHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<i class="ti ti-loader-2"></i> Getting location…';

  navigator.geolocation.getCurrentPosition(
    pos => {
      const lat = pos.coords.latitude, lng = pos.coords.longitude;
      document.getElementById('biz-lat-' + mode).value = lat;
      document.getElementById('biz-lng-' + mode).value = lng;
      const nearest = _nearestCity(lat, lng);
      const label   = document.getElementById('biz-loc-label-' + mode);
      label.textContent = nearest
        ? 'Nearest city: ' + nearest.name + ' (' + nearest.km + ' km away)'
        : 'Location captured: ' + lat.toFixed(4) + ', ' + lng.toFixed(4);
      label.style.color = 'var(--accent)';
      btn.disabled = false;
      btn.innerHTML = '<i class="ti ti-map-check"></i> Location updated';
    },
    () => {
      alert('Could not get your location. Please allow location access and try again.');
      btn.disabled = false;
      btn.innerHTML = origHtml;
    },
    { timeout: 10000 }
  );
}
</script>
@endsection
