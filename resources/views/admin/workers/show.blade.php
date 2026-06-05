@extends('admin.layout')
@section('title', 'Business Details')
@section('breadcrumb', 'Business Details')

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">{{ $business->name }}</div>
    <div class="page-sub">{{ $business->name_job }} &middot; {{ $business->activity }}</div>
  </div>
  <div style="display:flex;gap:8px;">
    @if(!$business->trashed())
      @if($business->status !== 'active')
        <form method="POST" action="{{ route('admin.workers.approve', $business->id) }}">
          @csrf @method('PATCH')
          <button type="submit" class="btn-primary"><i class="ti ti-circle-check"></i> Approve</button>
        </form>
      @endif
      @if($business->status !== 'rejected')
        <form method="POST" action="{{ route('admin.workers.reject', $business->id) }}">
          @csrf @method('PATCH')
          <button type="submit" class="btn-ghost"><i class="ti ti-x"></i> Reject</button>
        </form>
      @endif
    @endif
    <a href="{{ route('admin.workers.index') }}" class="btn-ghost">
      <i class="ti ti-arrow-right"></i> Back
    </a>
  </div>
</div>

<div class="content-grid" style="grid-template-columns:1fr 300px;">

  {{-- Main Card --}}
  <div class="card">
    <div class="card-head">
      <span class="card-title">Business Profile</span>
      @if($business->trashed())
        <span class="badge blocked">Deleted</span>
      @else
        <span class="badge {{ $business->status }}">{{ ucfirst($business->status) }}</span>
      @endif
    </div>

    @if($business->image)
    <div style="padding:16px 20px 0;">
      <img src="{{ $business->image }}" alt="{{ $business->name }}"
           style="width:100%;max-height:220px;object-fit:cover;border-radius:10px;border:0.5px solid var(--border);">
    </div>
    @endif

    <div style="padding:20px;">
      @if($business->description)
      <div style="margin-bottom:16px;">
        <div style="font-size:11px;font-weight:500;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Description</div>
        <p style="font-size:13px;color:var(--text-secondary);line-height:1.7;">{{ $business->description }}</p>
      </div>
      @endif

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div style="background:var(--bg-sunken);border-radius:10px;padding:14px;">
          <div style="font-size:11px;color:var(--text-muted);margin-bottom:4px;">Latitude</div>
          <div style="font-size:13px;font-weight:600;">{{ $business->latitude }}</div>
        </div>
        <div style="background:var(--bg-sunken);border-radius:10px;padding:14px;">
          <div style="font-size:11px;color:var(--text-muted);margin-bottom:4px;">Longitude</div>
          <div style="font-size:13px;font-weight:600;">{{ $business->longitude }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Side Info --}}
  <div style="display:flex;flex-direction:column;gap:14px;">

    {{-- Owner --}}
    <div class="card">
      <div class="card-head"><span class="card-title">Owner</span></div>
      <div style="padding:16px;">
        @if($business->user)
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
          <div class="avatar" style="background:var(--accent);width:38px;height:38px;font-size:15px;">
            {{ strtoupper(substr($business->user->first_name, 0, 1)) }}
          </div>
          <div>
            <div style="font-size:13px;font-weight:600;">{{ $business->user->first_name }} {{ $business->user->last_name }}</div>
            <div style="font-size:11px;color:var(--text-muted);">{{ $business->user->email }}</div>
          </div>
        </div>
        <div style="font-size:12px;color:var(--text-secondary);display:flex;align-items:center;gap:6px;">
          <i class="ti ti-phone" style="font-size:14px;"></i> {{ $business->user->phone ?? '—' }}
        </div>
        <div style="font-size:12px;color:var(--text-secondary);display:flex;align-items:center;gap:6px;margin-top:6px;">
          <i class="ti ti-map-pin" style="font-size:14px;"></i> {{ $business->user->city ?? '—' }}
        </div>
        @endif
      </div>
    </div>

    {{-- Details --}}
    <div class="card">
      <div class="card-head"><span class="card-title">Details</span></div>
      <div style="padding:16px;">
        @php
        $rows = [
          ['icon' => 'ti-tools',      'label' => 'Job Title',  'value' => $business->name_job],
          ['icon' => 'ti-tag',        'label' => 'Activity',   'value' => $business->activity],
          ['icon' => 'ti-phone',      'label' => 'Phone',      'value' => $business->number],
          ['icon' => 'ti-calendar',   'label' => 'Registered', 'value' => $business->created_at->format('M d, Y')],
        ];
        @endphp
        @foreach($rows as $row)
        <div style="display:flex;align-items:center;gap:8px;padding:8px 0;border-bottom:0.5px solid var(--border);">
          <i class="ti {{ $row['icon'] }}" style="font-size:15px;color:var(--text-muted);width:18px;flex-shrink:0;"></i>
          <span style="font-size:11px;color:var(--text-secondary);width:75px;flex-shrink:0;">{{ $row['label'] }}</span>
          <span style="font-size:13px;font-weight:500;">{{ $row['value'] }}</span>
        </div>
        @endforeach
      </div>
    </div>

    @if(!$business->trashed())
    <div class="card" style="padding:16px;">
      <form method="POST" action="{{ route('admin.workers.destroy', $business->id) }}"
            onsubmit="return confirm('Delete this business?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-danger" style="width:100%;justify-content:center;">
          <i class="ti ti-trash"></i> Delete Business
        </button>
      </form>
    </div>
    @endif

  </div>
</div>
@endsection
