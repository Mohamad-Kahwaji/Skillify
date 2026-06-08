@extends('user.layout')

@section('title', 'Advertisements')

@section('styles')
<style>
/* ── Ads Grid ─────────────────────────────────────── */
.ads-grid {
  display:grid;
  grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));
  gap:18px;
}

/* ── Ad Card ──────────────────────────────────────── */
.ad-card {
  background:var(--bg-surface);
  border:.5px solid var(--border);
  border-radius:var(--radius-lg);
  overflow:hidden;
  display:flex; flex-direction:column;
  transition:border-color .15s, box-shadow .15s;
}
.ad-card:hover {
  border-color:var(--accent);
  box-shadow:0 6px 24px rgba(29,158,117,.1);
}

/* ── Ad Banner ────────────────────────────────────── */
.ad-banner {
  width:100%; height:180px;
  background:linear-gradient(135deg, var(--accent-bg), #d4f0e4);
  display:flex; align-items:center; justify-content:center;
  overflow:hidden; position:relative;
}
.ad-banner img {
  width:100%; height:100%; object-fit:cover; display:block;
}
.ad-banner-placeholder {
  font-size:48px; color:var(--accent); opacity:.35;
}

/* ── Sponsored Tag ────────────────────────────────── */
.sponsored-tag {
  position:absolute; top:10px; left:10px;
  background:rgba(0,0,0,.55); backdrop-filter:blur(4px);
  color:#fff; font-size:10px; font-weight:600; letter-spacing:.5px;
  text-transform:uppercase; padding:3px 8px; border-radius:20px;
}

/* ── Ad Body ──────────────────────────────────────── */
.ad-body { padding:16px 18px; flex:1; display:flex; flex-direction:column; gap:8px; }
.ad-title { font-size:15px; font-weight:700; line-height:1.3; }
.ad-company {
  display:inline-flex; align-items:center; gap:5px;
  font-size:11px; color:var(--text-muted);
  background:var(--bg-sunken); padding:3px 9px;
  border-radius:20px; border:.5px solid var(--border);
  width:fit-content;
}
.ad-desc {
  font-size:13px; color:var(--text-secondary);
  line-height:1.6;
  display:-webkit-box; -webkit-line-clamp:3;
  -webkit-box-orient:vertical; overflow:hidden;
}

/* ── Ad Footer ────────────────────────────────────── */
.ad-footer {
  padding:11px 18px;
  border-top:.5px solid var(--border);
  display:flex; align-items:center; justify-content:space-between;
  font-size:11px; color:var(--text-muted);
}
.ad-dates { display:flex; align-items:center; gap:5px; }

/* ── Empty State ──────────────────────────────────── */
.empty-state {
  padding:64px 24px; text-align:center;
  background:var(--bg-surface); border:.5px solid var(--border);
  border-radius:var(--radius-lg); color:var(--text-muted);
  grid-column:1/-1;
}
.empty-state i { font-size:48px; display:block; margin-bottom:12px; opacity:.25; }
.empty-state p { font-size:14px; }
</style>
@endsection

@section('content')

<div>
  <div class="page-title">Advertisements</div>
  <div class="page-sub">{{ $advertisements->count() }} active ad(s)</div>
</div>

@if($advertisements->isEmpty())
<div class="ads-grid">
  <div class="empty-state">
    <i class="ti ti-speakerphone"></i>
    <p>No active advertisements at the moment.</p>
  </div>
</div>
@else
<div class="ads-grid">
  @foreach($advertisements as $ad)
  <div class="ad-card">

    {{-- Banner --}}
    <div class="ad-banner">
      @if($ad->image)
        <img src="{{ Str::startsWith($ad->image, 'http') ? $ad->image : asset('storage/'.$ad->image) }}"
             alt="{{ $ad->title }}"
             onerror="this.style.display='none';this.parentElement.querySelector('.ad-banner-placeholder').style.display='flex'">
        <div class="ad-banner-placeholder" style="display:none; position:absolute; inset:0; align-items:center; justify-content:center;">
          <i class="ti ti-speakerphone"></i>
        </div>
      @else
        <div class="ad-banner-placeholder">
          <i class="ti ti-speakerphone"></i>
        </div>
      @endif
      <span class="sponsored-tag">Sponsored</span>
    </div>

    {{-- Body --}}
    <div class="ad-body">
      <div class="ad-title">{{ $ad->title }}</div>

      @if($ad->company_name)
        <div class="ad-company">
          <i class="ti ti-building" style="font-size:11px;"></i>
          {{ $ad->company_name }}
        </div>
      @endif

      @if($ad->description)
        <div class="ad-desc">{{ $ad->description }}</div>
      @endif
    </div>

    {{-- Footer --}}
    <div class="ad-footer">
      @if($ad->start_date || $ad->end_date)
        <div class="ad-dates">
          <i class="ti ti-calendar" style="font-size:13px;"></i>
          @if($ad->start_date && $ad->end_date)
            {{ \Carbon\Carbon::parse($ad->start_date)->format('M d') }}
            –
            {{ \Carbon\Carbon::parse($ad->end_date)->format('M d, Y') }}
          @elseif($ad->end_date)
            Until {{ \Carbon\Carbon::parse($ad->end_date)->format('M d, Y') }}
          @else
            From {{ \Carbon\Carbon::parse($ad->start_date)->format('M d, Y') }}
          @endif
        </div>
      @else
        <span>—</span>
      @endif

      @if($ad->end_date && \Carbon\Carbon::parse($ad->end_date)->diffInDays(now()) <= 3)
        <span style="color:#F59E0B;font-weight:600;font-size:11px;">
          <i class="ti ti-clock" style="font-size:12px;"></i>
          Ends soon
        </span>
      @endif
    </div>

  </div>
  @endforeach
</div>
@endif

@endsection
