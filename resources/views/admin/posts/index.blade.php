@extends('admin.layout')

@section('title', 'Posts')
@section('breadcrumb', 'Posts')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Posts</div>
    <div class="page-sub">Manage all platform posts</div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">All Posts ({{ $posts->count() }})</span>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Post</th>
        <th>Author</th>
        <th>Status</th>
        <th>Views</th>
        <th>Date</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($posts as $post)
      <tr>
        <td>
          <div>
            <div class="cell-name">{{ Str::limit($post->title, 50) }}</div>
            <div class="cell-email">{{ Str::limit($post->description, 60) }}</div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">
          @if($post->user)
            {{ $post->user->name }}
          @else
            <span style="color:var(--text-muted);">—</span>
          @endif
        </td>
        <td><span class="badge {{ $post->status ?? 'published' }}">{{ ucfirst($post->status ?? 'published') }}</span></td>
        <td style="color:var(--text-muted);">{{ $post->views ?? 0 }}</td>
        <td style="color:var(--text-muted);">{{ optional($post->post_date ?? $post->created_at)->format('M d, Y') }}</td>
        <td>
          <div style="display:flex;gap:6px;">
            <a href="{{ route('admin.reports.post', $post->id) }}" class="btn-ghost" style="padding:5px 10px;font-size:11px;">
              <i class="ti ti-flag" style="font-size:13px;"></i> Reports
            </a>
            <form method="POST" action="{{ route('admin.posts.destroy', $post->id) }}"
                  onsubmit="return confirm('Delete this post?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6"><div class="empty-state"><i class="ti ti-file-text"></i>No posts found</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
