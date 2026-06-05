@extends('user.layout')

@section('title', 'My Posts')

@section('content')

@php $user = Auth::guard('users')->user(); @endphp

<div>
  <div class="page-title">My Posts</div>
  <div class="page-sub">{{ $posts->count() }} post(s)</div>
</div>

@if($posts->isEmpty())
  <div class="card" style="padding:48px;text-align:center;color:var(--text-muted);">
    <i class="ti ti-file-off" style="font-size:40px;display:block;margin-bottom:10px;opacity:.4;"></i>
    <div style="font-size:14px;">No posts yet</div>
  </div>
@else
  <div style="display:flex;flex-direction:column;gap:14px;">
    @foreach($posts as $post)
    <div class="card">
      <div class="card-head">
        <div style="display:flex;align-items:center;gap:8px;">
          <div style="width:32px;height:32px;border-radius:8px;background:var(--accent-bg);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:16px;">
            <i class="ti ti-file-text"></i>
          </div>
          <div>
            <div style="font-size:14px;font-weight:600;">{{ $post->title ?? 'Untitled Post' }}</div>
            <div style="font-size:11px;color:var(--text-muted);">{{ $post->created_at->format('Y/m/d') }}</div>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:6px;">
          @if($post->views)
            <span style="font-size:12px;color:var(--text-muted);">
              <i class="ti ti-eye" style="font-size:14px;vertical-align:middle;"></i>
              {{ $post->views }}
            </span>
          @endif
          <span class="badge {{ $post->status === 'active' ? 'active' : 'pending' }}">
            {{ $post->status === 'active' ? 'Active' : 'Pending' }}
          </span>
        </div>
      </div>
      @if($post->description)
      <div class="card-body" style="padding:16px 20px;">
        <p style="font-size:13px;color:var(--text-secondary);line-height:1.6;">
          {{ Str::limit($post->description, 200) }}
        </p>
      </div>
      @endif
    </div>
    @endforeach
  </div>
@endif

@endsection
