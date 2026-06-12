@extends('user.layout')

@section('title', 'All Posts')

@section('styles')
<style>
/* ── Topbar ── */
.posts-topbar {
  display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 14px; margin-bottom: 24px;
}
.posts-meta .page-title { margin-bottom: 2px; }
.posts-meta .page-sub   { margin-top: 0; }
.search-wrap { position: relative; width: 240px; }
.search-wrap i {
  position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
  color: var(--text-muted); font-size: 15px; pointer-events: none;
}
.search-input {
  width: 100%; padding: 9px 12px 9px 35px;
  background: var(--bg-surface); border: 0.5px solid var(--border-md);
  border-radius: 10px; color: var(--text-primary);
  font-size: 13px; font-family: var(--font); outline: none;
  transition: border-color .12s, box-shadow .12s;
}
.search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(29,158,117,.08); }

/* ── Feed ── */
.feed { display: flex; flex-direction: column; gap: 14px; }

.post-card {
  background: var(--bg-surface);
  border: 0.5px solid var(--border);
  border-radius: 14px; overflow: hidden;
  transition: border-color .15s, box-shadow .15s;
}
.post-card:hover { border-color: var(--border-md); box-shadow: 0 4px 20px rgba(0,0,0,.07); }

/* Card header */
.post-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 18px; border-bottom: 0.5px solid var(--border); gap: 12px;
}
.post-author { display: flex; align-items: center; gap: 10px; }
.post-avatar {
  width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; font-weight: 700; color: #fff;
}
.post-author-name { font-size: 13px; font-weight: 600; line-height: 1.3; }
.post-author-time { font-size: 11px; color: var(--text-muted); }
.post-views { display: flex; align-items: center; gap: 5px; font-size: 12px; color: var(--text-muted); }
.post-views i { font-size: 14px; }

/* Card body */
.post-body  { padding: 16px 18px; }
.post-title { font-size: 15px; font-weight: 700; line-height: 1.4; color: var(--text-primary); margin-bottom: 8px; }
.post-desc  {
  font-size: 13px; color: var(--text-secondary); line-height: 1.65;
  display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}
.post-image { width: 100%; max-height: 260px; object-fit: cover; display: block; border-top: 0.5px solid var(--border); }

/* ── Reactions bar ── */
.reactions-bar {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 18px; border-top: 0.5px solid var(--border);
}
.btn-react {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 14px; border-radius: 20px;
  font-size: 12px; font-weight: 600; font-family: var(--font);
  border: 0.5px solid var(--border-md); background: var(--bg-sunken);
  color: var(--text-secondary); cursor: pointer;
  transition: all .15s;
}
.btn-react:hover { background: var(--border); }
.btn-react i { font-size: 15px; transition: transform .2s; }
.btn-react.liked { background: #FCEAEA; color: #C0392B; border-color: rgba(192,57,43,.2); }
.btn-react.liked i { transform: scale(1.2); }
.btn-react.liked:hover { background: #F9D5D5; }
.btn-react.comment-btn { margin-left: 4px; }
.btn-react.comment-btn.open { background: var(--accent-bg); color: var(--accent); border-color: rgba(29,158,117,.2); }

/* ── Comments panel ── */
.comments-panel { display: none; border-top: 0.5px solid var(--border); }
.comments-panel.open { display: block; }

.comment-list { padding: 0 18px; }
.comment-item {
  display: flex; align-items: flex-start; gap: 10px;
  padding: 14px 0; border-bottom: 0.5px solid var(--border);
}
.comment-item:last-child { border-bottom: none; }
.comment-avatar {
  width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; font-weight: 700; color: #fff; background: var(--accent);
}
.comment-body { flex: 1; min-width: 0; }
.comment-meta {
  display: flex; align-items: center; gap: 8px; margin-bottom: 4px;
}
.comment-name { font-size: 12px; font-weight: 600; }
.comment-time { font-size: 11px; color: var(--text-muted); }
.comment-text { font-size: 13px; color: var(--text-secondary); line-height: 1.5; }
.comment-delete {
  font-size: 12px; color: var(--text-muted);
  background: none; border: none; cursor: pointer; padding: 2px 4px;
  border-radius: 4px; transition: color .12s, background .12s;
}
.comment-delete:hover { color: #E24B4A; background: #FCEAEA; }

/* Reply button */
.btn-reply {
  background: none; border: none; cursor: pointer; font-family: var(--font);
  font-size: 11px; font-weight: 500; color: var(--text-muted);
  padding: 2px 6px; border-radius: 4px; margin-top: 5px;
  display: inline-flex; align-items: center; gap: 4px;
  transition: color .12s, background .12s;
}
.btn-reply:hover { color: var(--accent); background: rgba(29,158,117,.07); }
.btn-reply i { font-size: 12px; }

/* Reply form (inline under comment) */
.reply-form {
  display: none; margin-top: 10px;
  padding: 10px 12px; background: var(--bg-sunken);
  border-radius: 10px; border: 0.5px solid var(--border-md);
}
.reply-form.open { display: flex; gap: 8px; align-items: flex-end; }
.reply-input {
  flex: 1; resize: none; border: 0.5px solid var(--border-md);
  border-radius: 8px; padding: 7px 10px; font-size: 12px;
  font-family: var(--font); color: var(--text-primary);
  background: var(--bg-surface); outline: none; line-height: 1.5;
  min-height: 34px; max-height: 90px; overflow-y: auto;
  transition: border-color .12s;
}
.reply-input:focus { border-color: var(--accent); }
.btn-reply-send {
  padding: 6px 12px; background: var(--accent); color: #fff;
  border: none; border-radius: 7px; font-size: 11px; font-weight: 600;
  font-family: var(--font); cursor: pointer; flex-shrink: 0;
  transition: background .15s;
}
.btn-reply-send:hover { background: var(--accent-hover); }
.btn-reply-cancel {
  padding: 6px 10px; background: none; color: var(--text-muted);
  border: 0.5px solid var(--border-md); border-radius: 7px;
  font-size: 11px; font-family: var(--font); cursor: pointer; flex-shrink: 0;
  transition: all .12s;
}
.btn-reply-cancel:hover { background: var(--border); color: var(--text-primary); }

/* Nested replies */
.replies-list {
  margin-top: 8px; margin-left: 18px;
  border-left: 2px solid var(--border);
  padding-left: 14px;
  display: flex; flex-direction: column; gap: 0;
}
.reply-item {
  display: flex; align-items: flex-start; gap: 8px;
  padding: 10px 0; border-bottom: 0.5px solid var(--border);
}
.reply-item:last-child { border-bottom: none; }
.reply-avatar {
  width: 24px; height: 24px; border-radius: 50%; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 10px; font-weight: 700; color: #fff; background: var(--accent);
}
.reply-name { font-size: 12px; font-weight: 600; }
.reply-time { font-size: 10px; color: var(--text-muted); }
.reply-text { font-size: 12px; color: var(--text-secondary); line-height: 1.5; margin-top: 2px; }

.no-comments {
  padding: 20px 18px; font-size: 13px; color: var(--text-muted); text-align: center;
}

/* Comment form */
.comment-form {
  padding: 14px 18px; border-top: 0.5px solid var(--border);
  display: flex; gap: 10px; align-items: flex-start;
}
.comment-form .my-avatar {
  width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; font-weight: 700; color: #fff; background: var(--accent);
}
.comment-form form { flex: 1; display: flex; gap: 8px; align-items: flex-end; }
.comment-input {
  flex: 1; resize: none; border: 0.5px solid var(--border-md);
  border-radius: 10px; padding: 9px 12px; font-size: 13px;
  font-family: var(--font); color: var(--text-primary);
  background: var(--bg-sunken); outline: none; line-height: 1.5;
  transition: border-color .12s, box-shadow .12s;
  min-height: 38px; max-height: 120px; overflow-y: auto;
}
.comment-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(29,158,117,.08); }
.btn-send {
  padding: 8px 16px; background: var(--accent); color: #fff;
  border: none; border-radius: 9px; font-size: 12px; font-weight: 600;
  font-family: var(--font); cursor: pointer; white-space: nowrap;
  transition: background .15s; flex-shrink: 0;
}
.btn-send:hover { background: var(--accent-hover); }

/* ── Empty state ── */
.empty-wrap { padding: 64px 24px; text-align: center; color: var(--text-muted); }
.empty-wrap i { font-size: 44px; display: block; margin-bottom: 14px; opacity: .25; }
.empty-wrap .empty-title { font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 5px; }
.empty-wrap .empty-desc  { font-size: 13px; }
#no-results { display: none; }
</style>
@endsection

@section('content')

@php
  $me      = auth('users')->user();
  $myInit  = strtoupper(substr($me->first_name ?? $me->name ?? 'U', 0, 1));
  $palette = ['#1D9E75','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#0F6E56','#6366F1'];
@endphp

<div class="posts-topbar">
  <div class="posts-meta">
    <div class="page-title">All Posts</div>
    <div class="page-sub">{{ $posts->count() }} {{ $posts->count() === 1 ? 'post' : 'posts' }} from the community</div>
  </div>
  <div class="search-wrap">
    <i class="ti ti-search"></i>
    <input type="text" class="search-input" id="post-search"
           placeholder="Search posts or authors…" oninput="filterPosts()">
  </div>
</div>

@if(session('commented_post'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const panel = document.getElementById('comments-{{ session('commented_post') }}');
      if (panel) {
        panel.classList.add('open');
        const btn = document.querySelector('[data-comments="{{ session('commented_post') }}"]');
        if (btn) btn.classList.add('open');
      }
    });
  </script>
@endif

@if($posts->isEmpty())
  <div class="card">
    <div class="empty-wrap">
      <i class="ti ti-message-circle-off"></i>
      <div class="empty-title">No posts yet</div>
      <div class="empty-desc">No posts from other users. Check back later.</div>
    </div>
  </div>
@else

<div class="feed" id="feed">
  @foreach($posts as $post)

  @php
    $author     = $post->user;
    $firstName  = $author?->first_name ?? '';
    $lastName   = $author?->last_name  ?? '';
    $fullName   = trim("$firstName $lastName") ?: ($author?->name ?? 'Unknown User');
    $initial    = strtoupper(substr($firstName ?: ($author?->name ?? 'U'), 0, 1));
    $color      = $palette[$post->user_id % count($palette)];
    $likeCount  = $post->likes->count();
    $isLiked    = $post->likes->contains('user_id', $authId);
    $cmtCount   = $post->comments->count();
  @endphp

  <div class="post-card"
       data-title="{{ strtolower($post->title ?? '') }}"
       data-author="{{ strtolower($fullName) }}">

    {{-- Header --}}
    <div class="post-head">
      <div class="post-author">
        <div class="post-avatar" style="background:{{ $color }};">{{ $initial }}</div>
        <div>
          <div class="post-author-name">{{ $fullName }}</div>
          <div class="post-author-time">
            <i class="ti ti-clock" style="font-size:11px;vertical-align:middle;"></i>
            {{ optional($post->post_date ?? $post->created_at)->diffForHumans() }}
          </div>
        </div>
      </div>
      @if($post->views)
        <div class="post-views"><i class="ti ti-eye"></i> {{ number_format($post->views) }}</div>
      @endif
    </div>

    {{-- Body --}}
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
           alt="{{ $post->title }}" class="post-image" onerror="this.style.display='none'">
    @endif

    {{-- Reactions bar --}}
    <div class="reactions-bar">
      {{-- Like button (AJAX) --}}
      <button class="btn-react {{ $isLiked ? 'liked' : '' }}"
              onclick="toggleLike(this, {{ $post->id }})"
              data-liked="{{ $isLiked ? '1' : '0' }}">
        <i class="ti {{ $isLiked ? 'ti-heart-filled' : 'ti-heart' }}"></i>
        <span class="like-count">{{ $likeCount }}</span>
      </button>

      {{-- Comment toggle --}}
      <button class="btn-react comment-btn"
              onclick="toggleComments(this, {{ $post->id }})"
              data-comments="{{ $post->id }}">
        <i class="ti ti-message-circle"></i>
        <span>{{ $cmtCount }} {{ $cmtCount === 1 ? 'comment' : 'comments' }}</span>
      </button>
    </div>

    {{-- Comments panel --}}
    <div class="comments-panel" id="comments-{{ $post->id }}">

      {{-- List --}}
      <div class="comment-list">
        @forelse($post->comments as $comment)
          @php
            $cUser = $comment->user;
            $cName = trim(($cUser?->first_name ?? '') . ' ' . ($cUser?->last_name ?? '')) ?: ($cUser?->name ?? 'User');
            $cInit = strtoupper(substr($cUser?->first_name ?? $cUser?->name ?? 'U', 0, 1));
          @endphp
          <div class="comment-item">
            <div class="comment-avatar">{{ $cInit }}</div>
            <div class="comment-body">
              <div class="comment-meta">
                <span class="comment-name">{{ $cName }}</span>
                <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
              </div>
              <div class="comment-text">{{ $comment->content }}</div>

              {{-- Reply button --}}
              <button class="btn-reply" onclick="toggleReplyForm('reply-form-{{ $comment->id }}')">
                <i class="ti ti-corner-down-right"></i> Reply
              </button>

              {{-- Inline reply form --}}
              <div class="reply-form" id="reply-form-{{ $comment->id }}">
                <form method="POST" action="{{ route('user.posts.comments.store', $post->id) }}"
                      style="display:flex;gap:8px;align-items:flex-end;width:100%;">
                  @csrf
                  <input type="hidden" name="post_id"   value="{{ $post->id }}">
                  <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                  <textarea name="content" class="reply-input"
                            placeholder="Write a reply…" rows="1" required maxlength="1000"
                            oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px'"></textarea>
                  <button type="submit" class="btn-reply-send">
                    <i class="ti ti-send" style="font-size:12px;"></i>
                  </button>
                  <button type="button" class="btn-reply-cancel"
                          onclick="toggleReplyForm('reply-form-{{ $comment->id }}')">Cancel</button>
                </form>
              </div>

              {{-- Nested replies --}}
              @if($comment->replies->isNotEmpty())
                <div class="replies-list">
                  @foreach($comment->replies as $reply)
                    @php
                      $rUser = $reply->user;
                      $rName = trim(($rUser?->first_name ?? '') . ' ' . ($rUser?->last_name ?? '')) ?: ($rUser?->name ?? 'User');
                      $rInit = strtoupper(substr($rUser?->first_name ?? $rUser?->name ?? 'U', 0, 1));
                    @endphp
                    <div class="reply-item">
                      <div class="reply-avatar">{{ $rInit }}</div>
                      <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;gap:8px;">
                          <span class="reply-name">{{ $rName }}</span>
                          <span class="reply-time">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="reply-text">{{ $reply->content }}</div>
                      </div>
                      @if($reply->user_id === $authId)
                        <form method="POST" action="{{ route('user.comments.destroy', $reply->id) }}">
                          @csrf @method('DELETE')
                          <button type="submit" class="comment-delete" title="Delete reply">
                            <i class="ti ti-x" style="font-size:11px;"></i>
                          </button>
                        </form>
                      @endif
                    </div>
                  @endforeach
                </div>
              @endif

            </div>
            @if($comment->user_id === $authId)
              <form method="POST" action="{{ route('user.comments.destroy', $comment->id) }}">
                @csrf @method('DELETE')
                <button type="submit" class="comment-delete" title="Delete">
                  <i class="ti ti-x" style="font-size:12px;"></i>
                </button>
              </form>
            @endif
          </div>
        @empty
          <div class="no-comments">No comments yet. Be the first!</div>
        @endforelse
      </div>

      {{-- Add comment form --}}
      <div class="comment-form">
        <div class="my-avatar">{{ $myInit }}</div>
        <form method="POST" action="{{ route('user.posts.comments.store', $post->id) }}">
          @csrf
          <input type="hidden" name="post_id" value="{{ $post->id }}">
          <textarea name="content" class="comment-input"
                    placeholder="Write a comment…" rows="1"
                    required maxlength="1000"
                    oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px'"></textarea>
          <button type="submit" class="btn-send">
            <i class="ti ti-send" style="font-size:14px;"></i>
          </button>
        </form>
      </div>

    </div>

  </div>
  @endforeach

  <div id="no-results">
    <div class="card">
      <div class="empty-wrap">
        <i class="ti ti-filter-off"></i>
        <div class="empty-title">No results</div>
        <div class="empty-desc">No posts match your search.</div>
      </div>
    </div>
  </div>
</div>

@endif

@endsection

@section('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

/* ── Like toggle (AJAX) ── */
async function toggleLike(btn, postId) {
  btn.disabled = true;
  try {
    const res  = await fetch(`/user/posts/${postId}/like`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    });
    const data = await res.json();
    const icon = btn.querySelector('i');

    if (data.liked) {
      btn.classList.add('liked');
      icon.className = 'ti ti-heart-filled';
    } else {
      btn.classList.remove('liked');
      icon.className = 'ti ti-heart';
    }
    btn.querySelector('.like-count').textContent = data.count;
  } catch (e) {
    console.error(e);
  } finally {
    btn.disabled = false;
  }
}

/* ── Comments panel toggle ── */
function toggleComments(btn, postId) {
  const panel = document.getElementById('comments-' + postId);
  const isOpen = panel.classList.toggle('open');
  btn.classList.toggle('open', isOpen);
  if (isOpen) {
    panel.querySelector('.comment-input')?.focus();
  }
}

/* ── Reply form toggle ── */
function toggleReplyForm(id) {
  const form = document.getElementById(id);
  const isOpen = form.classList.toggle('open');
  if (isOpen) form.querySelector('textarea')?.focus();
}

/* ── Search filter ── */
function filterPosts() {
  const q      = document.getElementById('post-search').value.toLowerCase().trim();
  const cards  = document.querySelectorAll('.post-card');
  const noRes  = document.getElementById('no-results');
  let visible  = 0;
  cards.forEach(card => {
    const match = !q || card.dataset.title.includes(q) || card.dataset.author.includes(q);
    card.style.display = match ? '' : 'none';
    if (match) visible++;
  });
  if (noRes) noRes.style.display = visible === 0 ? '' : 'none';
}
</script>
@endsection
