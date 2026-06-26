@php
  $biz = $service->business ?? $service->user?->businesses;

  $lat = null; $lng = null;
  if (!empty($service->latitude) && !empty($service->longitude)
      && ((float)$service->latitude !== 0.0 || (float)$service->longitude !== 0.0)) {
    $lat = (float) $service->latitude;
    $lng = (float) $service->longitude;
  } elseif ($biz && !empty($biz->latitude) && !empty($biz->longitude)
      && ((float)$biz->latitude !== 0.0 || (float)$biz->longitude !== 0.0)) {
    $lat = (float) $biz->latitude;
    $lng = (float) $biz->longitude;
  }

  $avColors  = ['#0D9488','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#EC4899'];
  $avColor   = $avColors[($service->user_id ?? $service->id) % count($avColors)];
  $initial   = strtoupper(mb_substr($service->user?->first_name ?? 'H', 0, 1));
  $ownerName = trim(($service->user?->first_name ?? '') . ' ' . ($service->user?->last_name ?? '')) ?: 'Hirfa Craftsman';

  $svcImg = $service->image
    ? (str_starts_with($service->image, 'http') ? $service->image : asset('storage/'.$service->image))
    : null;
  $bizImg = ($biz && $biz->image)
    ? (str_starts_with($biz->image, 'http') ? $biz->image : asset('storage/'.$biz->image))
    : null;
@endphp

{{-- JS meta --}}
<div id="detail-meta" data-lat="{{ $lat ?? '' }}" data-lng="{{ $lng ?? '' }}"
     data-name="{{ $service->name }}" style="display:none;"></div>

{{-- ── Header ── --}}
<div class="svc-modal-header">
  <div style="min-width:0;flex:1;">
    <div class="svc-modal-title">{{ $service->name }}</div>
    @if($service->category || $service->subcategory)
      <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">
        {{ implode(' · ', array_filter([$service->category, $service->subcategory])) }}
      </div>
    @endif
  </div>
  <button class="svc-modal-close" onclick="closeSvcModal()">
    <i class="ti ti-x" style="font-size:15px;"></i>
  </button>
</div>

{{-- ── Image Banner (full-width) ── --}}
<div class="detail-banner">
  @if($svcImg)
    <img src="{{ $svcImg }}" alt="{{ $service->name }}"
         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
    <div class="detail-banner-fallback" style="display:none;background:{{ $avColor }};">
      <span>{{ $initial }}</span>
    </div>
  @else
    <div class="detail-banner-fallback" style="background:{{ $avColor }};">
      <span>{{ $initial }}</span>
    </div>
  @endif
</div>

{{-- ── Body: map | info ── --}}
<div class="svc-modal-body">

  {{-- Left: Map --}}
  <div class="svc-modal-left">
    @if($lat)
      <iframe
        src="https://www.openstreetmap.org/export/embed.html?bbox={{ $lng-0.02 }},{{ $lat-0.02 }},{{ $lng+0.02 }},{{ $lat+0.02 }}&layer=mapnik&marker={{ $lat }},{{ $lng }}"
        style="height:420px;width:100%;border:none;display:block;"
        loading="lazy"></iframe>
    @else
      <div class="map-placeholder">
        <i class="ti ti-map-off"></i>
        <span class="mp-title">Location</span>
        <span class="mp-val">{{ $service->city ?? 'Not specified' }}</span>
      </div>
    @endif
  </div>

  {{-- Right: Info --}}
  <div class="svc-modal-right">

    {{-- Price --}}
    <div class="detail-price-row">
      <div>
        <div class="detail-section-label">Price</div>
        <div class="detail-price-badge">
          {{ number_format($service->price, 0) }}
          <small>{{ $service->price_type === 'usd' ? 'USD' : 'SYP' }}</small>
        </div>
      </div>
      <span class="detail-status-badge">
        <i class="ti ti-circle-check-filled"></i> Available
      </span>
    </div>

    {{-- Description --}}
    @if($service->description)
    <div class="info-block">
      <div class="detail-section-label">About this service</div>
      <div class="detail-desc">{{ $service->description }}</div>
    </div>
    @endif

    {{-- Service fields --}}
    <div class="info-block">
      <div class="detail-section-label">Service Details</div>
      <div class="info-grid">
        @if($service->category)
          <div class="info-row">
            <span class="info-lbl"><i class="ti ti-tag"></i> Category</span>
            <span class="info-val">{{ $service->category }}</span>
          </div>
        @endif
        @if($service->subcategory)
          <div class="info-row">
            <span class="info-lbl"><i class="ti ti-point"></i> Specialty</span>
            <span class="info-val">{{ $service->subcategory }}</span>
          </div>
        @endif
        @if($service->city)
          <div class="info-row">
            <span class="info-lbl"><i class="ti ti-map-pin"></i> City</span>
            <span class="info-val">{{ $service->city }}</span>
          </div>
        @endif
      </div>
    </div>

    <div class="detail-divider"></div>

    {{-- Service Provider --}}
    <div class="info-block">
      <div class="detail-section-label">Service Provider</div>
      <div class="detail-owner">
        <div class="detail-owner-av" style="background:{{ $avColor }};">{{ $initial }}</div>
        <div style="flex:1;min-width:0;">
          <div class="info-grid" style="margin-top:4px;">
            <div class="info-row">
              <span class="info-lbl"><i class="ti ti-user"></i> Name</span>
              <span class="info-val">{{ $ownerName }}</span>
            </div>
            @if($service->user?->phone)
            <div class="info-row">
              <span class="info-lbl"><i class="ti ti-phone"></i> Phone</span>
              <span class="info-val">{{ $service->user->phone }}</span>
            </div>
            @endif
            @if($service->user?->city)
            <div class="info-row">
              <span class="info-lbl"><i class="ti ti-map-pin"></i> City</span>
              <span class="info-val">{{ $service->user->city }}</span>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Business Account --}}
    @if($biz)
    <div class="detail-divider"></div>
    <div class="info-block">
      <div class="detail-section-label">Business Account</div>
      <div class="detail-business">
        <div class="detail-biz-av">
          @if($bizImg)
            <img src="{{ $bizImg }}" alt=""
                 onerror="this.parentElement.innerHTML='<i class=\'ti ti-building\'></i>'">
          @else
            <i class="ti ti-building"></i>
          @endif
        </div>
        <div style="flex:1;min-width:0;">
          <div class="info-grid">
            <div class="info-row">
              <span class="info-lbl"><i class="ti ti-building-store"></i> Business</span>
              <span class="info-val fw">{{ $biz->name }}</span>
            </div>
            @if($biz->name_job)
            <div class="info-row">
              <span class="info-lbl"><i class="ti ti-id-badge"></i> Title</span>
              <span class="info-val">{{ $biz->name_job }}</span>
            </div>
            @endif
            @if($biz->activity)
            <div class="info-row">
              <span class="info-lbl"><i class="ti ti-briefcase"></i> Activity</span>
              <span class="info-val">{{ $biz->activity }}</span>
            </div>
            @endif
            @if($biz->number)
            <div class="info-row">
              <span class="info-lbl"><i class="ti ti-phone"></i> Phone</span>
              <span class="info-val">{{ $biz->number }}</span>
            </div>
            @endif
          </div>
          @if($biz->description)
            <div class="detail-biz-desc" style="margin-top:8px;">{{ Str::limit($biz->description, 140) }}</div>
          @endif
          <span class="detail-biz-badge" style="margin-top:8px;">
            <i class="ti ti-{{ $biz->status === 'active' ? 'circle-check-filled' : 'clock' }}"></i>
            {{ $biz->status === 'active' ? 'Verified Business' : ucfirst($biz->status) }}
          </span>
        </div>
      </div>
    </div>
    @endif

  </div>
</div>

{{-- ── Footer ── --}}
<div class="svc-modal-footer">
  <div class="detail-location-text">
    @if($lat)
      <i class="ti ti-map-pin"></i>
      <span>{{ number_format($lat, 4) }}, {{ number_format($lng, 4) }}</span>
    @elseif($service->city)
      <i class="ti ti-map-pin"></i>
      <span>{{ $service->city }}</span>
    @endif
  </div>
  <div style="display:flex;gap:8px;align-items:center;">
    <button class="btn-close-md" onclick="closeSvcModal()">Close</button>
    @if($service->user)
    <form method="POST" action="{{ route('user.chat.start') }}" style="margin:0;">
      @csrf
      <input type="hidden" name="business_user_id" value="{{ $service->user->id }}">
      <button type="submit" class="btn-contact">
        <i class="ti ti-message-2"></i> Contact Owner
      </button>
    </form>
    @endif
  </div>
</div>
