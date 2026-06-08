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
  <div class="table-toolbar">
    <div class="search-field">
      <i class="ti ti-search"></i>
      <input type="text" id="q" placeholder="Search title or author…">
    </div>
    <span class="tbl-count" id="tbl-count"></span>
  </div>
  <table class="data-table" id="tbl">
    <thead>
      <tr>
        <th>Post</th>
        <th>Author</th>
        <th>Views</th>
        <th>Date</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($posts as $post)
      <tr data-search="{{ strtolower(($post->title ?? '') . ' ' . ($post->user?->name ?? '') . ' ' . ($post->description ?? '')) }}">
        <td>
          <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;border-radius:8px;background:var(--accent-bg);color:var(--accent-txt);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
              <i class="ti ti-file-text"></i>
            </div>
            <div>
              <div class="cell-name">{{ Str::limit($post->title ?? 'Untitled', 55) }}</div>
              <div class="cell-email">{{ Str::limit($post->description ?? '', 60) }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">
          @if($post->user)
            <div class="cell-user" style="gap:7px;">
              <div class="avatar" style="background:#8B5CF6;width:24px;height:24px;font-size:10px;">
                {{ strtoupper(substr($post->user->name, 0, 1)) }}
              </div>
              <span style="font-size:12px;">{{ $post->user->name }}</span>
            </div>
          @else
            <span style="color:var(--text-muted);">—</span>
          @endif
        </td>
        <td style="color:var(--text-muted);font-size:12px;">
          @if($post->views ?? 0)
            <div style="display:flex;align-items:center;gap:4px;">
              <i class="ti ti-eye" style="font-size:13px;"></i>
              {{ number_format($post->views) }}
            </div>
          @else
            —
          @endif
        </td>
        <td style="color:var(--text-muted);font-size:12px;">
          {{ optional($post->post_date ?? $post->created_at)->format('M d, Y') }}
        </td>
        <td>
          <div style="display:flex;gap:6px;">
            <a href="{{ route('admin.reports.post', $post->id) }}" class="btn-ghost" style="padding:5px 10px;font-size:11px;">
              <i class="ti ti-flag" style="font-size:13px;"></i> Reports
            </a>
            <form method="POST" action="{{ route('admin.posts.destroy', $post->id) }}"
                  onsubmit="return confirm('Delete this post?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger" title="Delete">
                <i class="ti ti-trash" style="font-size:13px;"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5">
          <div class="empty-state"><i class="ti ti-file-text"></i>No posts found</div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection

@section('scripts')
<script>
(function(){
  const cnt = document.getElementById('tbl-count');
  function render(q){
    let n = 0;
    document.querySelectorAll('#tbl tbody tr[data-search]').forEach(r => {
      const ok = r.dataset.search.includes(q);
      r.style.display = ok ? '' : 'none';
      if(ok) n++;
    });
    if(cnt) cnt.textContent = n + ' result' + (n !== 1 ? 's' : '');
  }
  document.getElementById('q').addEventListener('input', e => render(e.target.value.toLowerCase()));
  render('');
})();
</script>
@endsection
