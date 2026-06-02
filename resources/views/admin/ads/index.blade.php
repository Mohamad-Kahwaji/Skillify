@extends('admin.layout')

@section('title', 'Advertisements')
@section('breadcrumb', 'Ads')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Advertisements</div>
    <div class="page-sub">Create and manage platform advertisements</div>
  </div>
  <a href="{{ route('admin.ads.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> New Ad
  </a>
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">All Ads ({{ $advertisements->count() }})</span>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Ad</th>
        <th>Company</th>
        <th>Status</th>
        <th>Period</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($advertisements as $ad)
      <tr>
        <td>
          <div>
            <div class="cell-name">{{ $ad->title }}</div>
            <div class="cell-email">{{ Str::limit($ad->description ?? '', 50) }}</div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">{{ $ad->company_name ?? '—' }}</td>
        <td><span class="badge {{ $ad->status ?? 'pending' }}">{{ ucfirst($ad->status ?? 'pending') }}</span></td>
        <td style="color:var(--text-muted);font-size:12px;">
          @if($ad->start_date)
            {{ \Carbon\Carbon::parse($ad->start_date)->format('M d') }} –
            {{ \Carbon\Carbon::parse($ad->end_date)->format('M d, Y') }}
          @else
            —
          @endif
        </td>
        <td>
          <div style="display:flex;gap:6px;">
            <a href="{{ route('admin.ads.edit', $ad->id) }}" class="btn-ghost" style="padding:5px 10px;font-size:11px;">
              <i class="ti ti-edit" style="font-size:13px;"></i> Edit
            </a>
            <form method="POST" action="{{ route('admin.ads.destroy', $ad->id) }}"
                  onsubmit="return confirm('Delete this ad?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="5"><div class="empty-state"><i class="ti ti-speakerphone"></i>No advertisements yet</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
