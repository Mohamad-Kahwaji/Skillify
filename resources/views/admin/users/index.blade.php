@extends('admin.layout')
@section('title', 'Users')
@section('breadcrumb', 'Users')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Users</div>
    <div class="page-sub">Manage all registered users on the platform</div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">Users</span>
  </div>
  <div class="table-toolbar">
    <div class="search-field">
      <i class="ti ti-search"></i>
      <input type="text" id="q" placeholder="Search name or email…">
    </div>
    <div class="filter-chips" id="chips">
      <button class="chip on" data-s="">All <span id="c-all">({{ $users->count() }})</span></button>
      <button class="chip" data-s="active">Active ({{ $users->where('status','active')->count() }})</button>
      <button class="chip" data-s="inactive">Inactive ({{ $users->where('status','inactive')->count() }})</button>
    </div>
    <span class="tbl-count" id="tbl-count"></span>
  </div>
  <table class="data-table" id="tbl">
    <thead>
      <tr>
        <th>User</th>
        <th>City</th>
        <th>Status</th>
        <th>Joined</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $user)
      <tr data-s="{{ $user->status ?? 'active' }}"
          data-search="{{ strtolower($user->name . ' ' . $user->email . ' ' . ($user->city ?? '')) }}">
        <td>
          <div class="cell-user">
            <div class="avatar" style="background:#1D9E75;">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
              <div class="cell-name">{{ $user->name }}</div>
              <div class="cell-email">{{ $user->email }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);font-size:12px;">{{ $user->city ?? '—' }}</td>
        <td><span class="badge {{ $user->status ?? 'active' }}">{{ ucfirst($user->status ?? 'active') }}</span></td>
        <td style="color:var(--text-muted);font-size:12px;">{{ $user->created_at->format('M d, Y') }}</td>
        <td>
          <div style="display:flex;gap:6px;align-items:center;">
            @if(($user->status ?? 'active') !== 'active')
              <form method="POST" action="{{ route('admin.users.activate', $user->id) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn-ghost" style="padding:5px 12px;font-size:11px;color:var(--accent);border-color:var(--accent);">
                  <i class="ti ti-check" style="font-size:12px;"></i> Activate
                </button>
              </form>
            @else
              <form method="POST" action="{{ route('admin.users.deactivate', $user->id) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn-ghost" style="padding:5px 12px;font-size:11px;">Deactivate</button>
              </form>
            @endif
            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                  onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
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
          <div class="empty-state"><i class="ti ti-users"></i>No users found</div>
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
  let sts = '', q = '';
  const rows = () => [...document.querySelectorAll('#tbl tbody tr[data-s]')];
  const cnt  = document.getElementById('tbl-count');
  function render(){
    let n = 0;
    rows().forEach(r => {
      const ok = r.dataset.search.includes(q) && (!sts || r.dataset.s === sts);
      r.style.display = ok ? '' : 'none';
      if(ok) n++;
    });
    if(cnt) cnt.textContent = n + ' result' + (n !== 1 ? 's' : '');
  }
  document.getElementById('q').addEventListener('input', e => { q = e.target.value.toLowerCase(); render(); });
  document.getElementById('chips').querySelectorAll('.chip').forEach(c => c.addEventListener('click', () => {
    document.querySelectorAll('#chips .chip').forEach(x => x.classList.remove('on'));
    c.classList.add('on'); sts = c.dataset.s; render();
  }));
  render();
})();
</script>
@endsection
