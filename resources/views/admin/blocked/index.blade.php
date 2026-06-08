@extends('admin.layout')
@section('title', 'Blocked Users')
@section('breadcrumb', 'Blocked Users')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Blocked Users</div>
    <div class="page-sub">{{ $blocked->count() }} account(s) currently blocked</div>
  </div>
  @can('blocked.create')
  <button class="btn-primary" style="background:var(--red-400);" onclick="openModal('block-user-modal')">
    <i class="ti ti-ban"></i> Block User
  </button>
  @endcan
</div>

@if(session('success'))
  <div class="alert success"><i class="ti ti-check"></i> {{ session('success') }}</div>
@endif

<div class="card">
  <div class="card-head">
    <span class="card-title">Blocked Accounts ({{ $blocked->count() }})</span>
  </div>
  <div class="table-toolbar">
    <div class="search-field">
      <i class="ti ti-search"></i>
      <input type="text" id="q" placeholder="Search name, email, or reason…">
    </div>
    <span class="tbl-count" id="tbl-count"></span>
  </div>
  <table class="data-table" id="tbl">
    <thead>
      <tr>
        <th>Blocked User</th>
        <th>Reason</th>
        <th>Blocked By</th>
        <th>Block Date</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($blocked as $record)
      <tr data-search="{{ strtolower(($record->user?->first_name ?? '') . ' ' . ($record->user?->last_name ?? '') . ' ' . ($record->user?->email ?? '') . ' ' . ($record->reason ?? '')) }}">
        <td>
          <div class="cell-user">
            <div class="avatar" style="background:var(--red-400);">
              {{ strtoupper(substr($record->user?->first_name ?? 'U', 0, 1)) }}
            </div>
            <div>
              <div class="cell-name">{{ $record->user?->first_name }} {{ $record->user?->last_name }}</div>
              <div class="cell-email">{{ $record->user?->email }}</div>
            </div>
          </div>
        </td>
        <td style="max-width:180px;">
          <span style="font-size:12px;color:var(--text-secondary);">{{ Str::limit($record->reason, 55) }}</span>
        </td>
        <td style="font-size:12px;color:var(--text-secondary);">
          {{ $record->admin?->first_name }} {{ $record->admin?->last_name }}
        </td>
        <td style="font-size:12px;color:var(--text-muted);">
          {{ $record->blocker_date
              ? \Carbon\Carbon::parse($record->blocker_date)->format('M d, Y')
              : $record->created_at->format('M d, Y') }}
        </td>
        <td>
          @can('blocked.delete')
          <form method="POST" action="{{ route('admin.blocked.destroy', $record->id) }}"
                onsubmit="return confirm('Unblock {{ addslashes($record->user?->first_name) }}?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-ghost"
                    style="padding:5px 12px;font-size:12px;color:var(--accent);border-color:rgba(29,158,117,.3);">
              <i class="ti ti-lock-open" style="font-size:13px;"></i> Unblock
            </button>
          </form>
          @endcan
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5">
          <div class="empty-state">
            <i class="ti ti-circle-check" style="color:var(--accent);"></i>
            No blocked users
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- ── Block User Modal ─────────────────────────────────── --}}
<div class="modal-overlay" id="block-user-modal" onclick="if(event.target===this)closeModal('block-user-modal')">
  <div class="modal-box">
    <div class="modal-head">
      <span class="modal-title"><i class="ti ti-ban" style="color:var(--red-400);"></i> Block a User</span>
      <button class="modal-close" onclick="closeModal('block-user-modal')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.blocked.store') }}">
      @csrf
      <div class="modal-body">
        @if($errors->any())
          <div class="alert error"><i class="ti ti-alert-circle"></i> {{ $errors->first() }}</div>
        @endif

        <div>
          <label class="form-label">User to Block</label>
          <div class="form-field"><i class="ti ti-user"></i>
            <select name="user_id" required>
              <option value="">Select user…</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                  {{ $user->first_name }} {{ $user->last_name }} — {{ $user->email }}
                </option>
              @endforeach
            </select>
          </div>
          @error('user_id')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
        </div>

        <div>
          <label class="form-label">Block Reason</label>
          <div class="form-field">
            <textarea name="reason" rows="3" required
                      placeholder="Describe why this user is being blocked…">{{ old('reason') }}</textarea>
          </div>
          @error('reason')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
        </div>

        <div>
          <label class="form-label">Block Date <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
          <div class="form-field"><i class="ti ti-calendar"></i>
            <input type="date" name="blocker_date" value="{{ old('blocker_date') }}">
          </div>
        </div>

        <div style="background:var(--red-50);border-radius:10px;padding:12px 14px;font-size:12px;color:var(--red-800);display:flex;gap:8px;align-items:flex-start;">
          <i class="ti ti-alert-triangle" style="font-size:16px;flex-shrink:0;margin-top:1px;"></i>
          <span>This will immediately deactivate the user's account and log them out.</span>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost" onclick="closeModal('block-user-modal')">Cancel</button>
        <button type="submit" style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;background:var(--red-400);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:500;font-family:var(--font);cursor:pointer;">
          <i class="ti ti-ban"></i> Block User
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.addEventListener('keydown', e => { if(e.key==='Escape') document.querySelectorAll('.modal-overlay.open').forEach(m=>{ m.classList.remove('open'); document.body.style.overflow=''; }); });

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

  @if($errors->any())
    openModal('block-user-modal');
  @endif
})();
</script>
@endsection
