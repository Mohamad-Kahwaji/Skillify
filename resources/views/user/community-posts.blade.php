@extends('user.layout')

@section('title', 'Community Posts')

@section('styles')
<style>
.posts-list { display:flex; flex-direction:column; gap:14px; }

.post-card {
  background:var(--bg-surface);
  border:.5px solid var(--border);
  border-radius:var(--radius-lg);
  overflow:hidden;
  transition:border-color .15s, box-shadow .15s;
}
.post-card:hover {
  border-color:var(--accent);
  box-shadow:0 4px 16px rgba(29,158,117,.07);
}

.post-head {
  display:flex; align-items:center; justify-content:space-between;
  padding:14px 20px; border-bottom:.5px solid var(--border);
  gap:12px;
}
.post-author {
  display:flex; align-items:center; gap:10px;
}
.post-avatar {
  width:34px; height:34px; border-radius:50%;
  background:var(--accent);
  display:flex; align-items:center; justify-content:center;
  font-size:13px; font-weight:600; color:#fff; flex-shrink:0;
}
.post-author-name  { font-size:13px; font-weight:600; }
.post-author-date  { font-size:11px; color:var(--text-muted); }

.post-meta { display:flex; align-items:center; gap:8px; }
.meta-chip {
  display:inline-flex; align-items:center; gap:4px;
  font-size:11px; color:var(--text-muted);
}

.post-body { padding:16px 20px; }
.post-title {
  font-size:15px; font-weight:700; margin-bottom:8px;
  color:var(--text-primary);
}
.post-desc {
  font-size:13px; color:var(--text-secondary);
  line-height:1.65;
  display:-webkit-box; -webkit-line-clamp:3;
  -webkit-box-orient:vertical; overflow:hidden;
}
.post-image {
  width:100%; max-height:280px; object-fit:cover;
  display:block; border-top:.5px solid var(--border);
}

.empty-state {
  padding:56px 24px; text-align:center;
  background:var(--bg-surface); border:.5px solid var(--border);
  border-radius:var(--radius-lg); color:var(--text-muted);
}
.empty-state i { font-size:44px; display:block; margin-bottom:12px; opacity:.25; }
.empty-state p { font-size:13px; }

/* Search bar */
.search-wrap { position:relative; max-width:340px; }
.search-wrap i {
  position:absolute; left:10px; top:50%; transform:translateY(-50%);
  color:var(--text-muted); font-size:15px; pointer-events:none;
}
.search-input {
  width:100%; padding:8px 12px 8px 34px;
  background:var(--bg-surface); border:.5px solid var(--border-md);
  border-radius:var(--radius-md); color:var(--text-primary);
  font-size:13px; font-family:var(--font); outline:none;
  transition:border-color .12s;
}
.search-input:focus { border-color:var(--accent); }
</style>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
  <div>
    <div class="page-title">Community Posts</div>
    <div class="page-sub">{{ $posts->count() }} post(s) from other users</div>
  </div>
  <div class="search-wrap">
    <i class="ti ti-search"></i>
    <input type="text" class="search-input" id="post-search"
           placeholder="Search posts…" oninput="filterPosts()">
  </div>
</div>

@if($posts->isEmpty())
<div class="empty-state">
  <i class="ti ti-message-circle-off"></i>
  <p>No posts from other users yet.</p>
</div>
@else
<div class="posts-list" id="posts-list">
  @foreach($posts as $post)
  @php
    $author   = $post->user;
    $initial  = strtoupper(substr($author?->first_name ?? 'U', 0, 1));
    $colors   = ['#1D9E75','#0F6E56','#3B82F6','#8B5CF6','#F59E0B','#EF4444'];
    $color    = $colors[$post->user_id % count($colors)];
    $authorName = trim(($author?->first_name ?? '') . ' ' . ($author?->last_name ?? '')) ?: 'Unknown User';
  @endphp
  <div class="post-card" data-title="{{ strtolower($post->title ?? '') }}" data-author="{{ strtolower($authorName) }}">
    <div class="post-head">
      <div class="post-author">
        <div class="post-avatar" style="background:{{ $color }};">{{ $initial }}</div>
        <div>
          <div class="post-author-name">{{ $authorName }}</div>
          <div class="post-author-date">{{ $post->created_at->diffForHumans() }}</div>
        </div>
      </div>
      <div class="post-meta">
        @if($post->views)
          <span class="meta-chip">
            <i class="ti ti-eye" style="font-size:13px;"></i>
            {{ number_format($post->views) }}
          </span>
        @endif
      </div>
    </div>

    <div class="post-body">
      @if($post->title)
        <div class="post-title">{{ $post->title }}</div>
      @endif
      @if($post->description)
        <div class="post-desc">{{ $post->description }}</div>
      @endif
    </div>

    @if($post->image)
      <img src="{{ Str::startsWith($post->image,'http') ? $post->image : asset('storage/'.$post->image) }}"
           alt="{{ $post->title }}" class="post-image"
           onerror="this.style.display='none'">
    @endif
  </div>
  @endforeach

  <div id="no-results" style="display:none;">
    <div class="empty-state">
      <i class="ti ti-filter-off"></i>
      <p>No posts match your search.</p>
    </div>
  </div>
</div>
@endif

@endsection

@section('scripts')
<script>
function filterPosts() {
  const q       = document.getElementById('post-search').value.toLowerCase().trim();
  const cards   = document.querySelectorAll('.post-card');
  const noRes   = document.getElementById('no-results');
  let visible   = 0;

  cards.forEach(card => {
    const match = !q || card.dataset.title.includes(q) || card.dataset.author.includes(q);
    card.style.display = match ? '' : 'none';
    if (match) visible++;
  });

  if (noRes) noRes.style.display = visible === 0 ? '' : 'none';
}
</script>
@endsection
