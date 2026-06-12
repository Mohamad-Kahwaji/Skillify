@extends('user.layout')

@section('title', 'My Posts')

@section('styles')
<style>
.posts-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 24px;
}
.posts-meta { display: flex; flex-direction: column; gap: 3px; }
.posts-meta .page-title { margin-bottom: 0; }
.posts-meta .page-sub   { margin-top: 0; }
.btn-new-post {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 10px 18px;
  background: var(--accent); color: #fff;
  border: none; border-radius: 10px;
  font-size: 13px; font-weight: 600; font-family: var(--font, 'Inter', sans-serif);
  text-decoration: none; cursor: pointer;
  transition: background .15s, box-shadow .15s;
  box-shadow: 0 2px 8px rgba(29,158,117,.25);
}
.btn-new-post:hover { background: var(--accent-hover, #0F6E56); box-shadow: 0 4px 14px rgba(29,158,117,.3); }

/* ── Empty state ── */
.empty-wrap {
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 72px 24px; text-align: center;
}
.empty-wrap .empty-icon {
  width: 64px; height: 64px; border-radius: 18px;
  background: var(--accent-bg, #E1F5EE);
  display: flex; align-items: center; justify-content: center;
  font-size: 28px; color: var(--accent); margin-bottom: 18px;
}
.empty-wrap .empty-title { font-size: 16px; font-weight: 600; margin-bottom: 6px; }
.empty-wrap .empty-desc  { font-size: 13px; color: var(--text-muted); margin-bottom: 22px; }

/* ── Post cards ── */
.post-list { display: flex; flex-direction: column; gap: 12px; }
.post-card {
  background: var(--bg-surface, #fff);
  border: 0.5px solid var(--border, rgba(0,0,0,.08));
  border-radius: 14px; overflow: hidden;
  transition: box-shadow .15s, border-color .15s;
}
.post-card:hover {
  box-shadow: 0 4px 18px rgba(0,0,0,.07);
  border-color: var(--border-md, rgba(0,0,0,.13));
}
.post-inner {
  display: flex; align-items: flex-start; gap: 14px; padding: 18px 20px;
}
.post-icon {
  width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
  background: var(--accent-bg, #E1F5EE);
  display: flex; align-items: center; justify-content: center;
  font-size: 18px; color: var(--accent); margin-top: 2px;
}
.post-body  { flex: 1; min-width: 0; }
.post-title {
  font-size: 14px; font-weight: 600; color: var(--text-primary);
  margin-bottom: 5px; line-height: 1.4;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.post-desc {
  font-size: 12px; color: var(--text-secondary);
  line-height: 1.6; margin-bottom: 10px;
  display: -webkit-box; -webkit-line-clamp: 2;
  -webkit-box-orient: vertical; overflow: hidden;
}
.post-footer {
  display: flex; align-items: center; gap: 14px;
  flex-wrap: wrap;
}
.post-badge {
  display: inline-flex; align-items: center; gap: 4px;
  font-size: 11px; color: var(--text-muted);
}
.post-badge i { font-size: 13px; }
.post-actions { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
.btn-action {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 500;
  cursor: pointer; transition: all .13s; border: none; font-family: inherit;
  text-decoration: none;
}
.btn-action.secondary {
  background: var(--bg-sunken, #F8F7F4);
  color: var(--text-secondary);
  border: 0.5px solid var(--border-md, rgba(0,0,0,.13));
}
.btn-action.secondary:hover { background: var(--border, rgba(0,0,0,.08)); color: var(--text-primary); }
.btn-action.danger {
  background: rgba(226,75,74,.08); color: #C0392B;
  border: 0.5px solid rgba(226,75,74,.15);
}
.btn-action.danger:hover { background: rgba(226,75,74,.15); }

.status-dot {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 11px; font-weight: 500; padding: 3px 9px; border-radius: 20px;
}
.status-dot.active   { background: #E1F5EE; color: #0F6E56; }
.status-dot.inactive { background: rgba(0,0,0,.06); color: var(--text-muted); }
.status-dot::before  {
  content: ''; width: 5px; height: 5px; border-radius: 50%;
  background: currentColor; flex-shrink: 0;
}
</style>
@endsection

@section('content')

@php $user = Auth::guard('users')->user(); @endphp

<div class="posts-header">
  <div class="posts-meta">
    <div class="page-title">My Posts</div>
    <div class="page-sub">{{ $posts->count() }} {{ $posts->count() === 1 ? 'post' : 'posts' }} published</div>
  </div>
  {{-- <a href="{{ route('user.posts.create') }}" class="btn-new-post">
    <i class="ti ti-plus" style="font-size:16px;"></i> New Post
  </a> --}}
</div>

@if($posts->isEmpty())

  <div class="card">
    <div class="empty-wrap">
      <div class="empty-icon"><i class="ti ti-file-off"></i></div>
      <div class="empty-title">No posts yet</div>
      <div class="empty-desc">You haven't published any posts.<br>Start sharing your expertise with the community.</div>
      {{-- <a href="{{ route('user.posts.create') }}" class="btn-new-post">
        <i class="ti ti-plus" style="font-size:15px;"></i> Write your first post
      </a> --}}
    </div>
  </div>

@else

  <div class="post-list">
    @foreach($posts as $post)
    <div class="post-card">
      <div class="post-inner">
        <div class="post-icon"><i class="ti ti-file-text"></i></div>

        <div class="post-body">
          <div class="post-title">{{ $post->title ?? 'Untitled Post' }}</div>

          @if($post->description)
            <div class="post-desc">{{ $post->description }}</div>
          @endif

          <div class="post-footer">
            <span class="post-badge">
              <i class="ti ti-calendar"></i>
              {{ optional($post->post_date ?? $post->created_at)->format('M d, Y') }}
            </span>

            @if($post->views)
              <span class="post-badge">
                <i class="ti ti-eye"></i>
                {{ number_format($post->views) }} views
              </span>
            @endif

            @if($post->status)
              <span class="status-dot {{ $post->status === 'active' ? 'active' : 'inactive' }}">
                {{ ucfirst($post->status) }}
              </span>
            @endif
          </div>
        </div>

        {{-- Actions (add routes when ready) --}}
      </div>
    </div>
    @endforeach
  </div>

@endif

@endsection
