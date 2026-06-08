@extends('super_admin.layout')

@section('title', 'Permissions')
@section('breadcrumb', 'Permissions')

@section('styles')
<style>
/* ── Guard Badges ─────────────────────────────── */
.guard-badge {
  display:inline-flex; align-items:center; gap:4px;
  font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px;
  letter-spacing:.3px; text-transform:uppercase;
}
.guard-badge.admins       { background:rgba(59,130,246,.15);  color:#60a5fa; }
.guard-badge.super_admins { background:rgba(168,85,247,.15);  color:#c084fc; }
.guard-badge.users        { background:rgba(29,158,117,.15);  color:var(--accent-hover); }

/* ── Modal ────────────────────────────────────── */
.modal-overlay {
  display:none; position:fixed; inset:0; z-index:200;
  background:rgba(0,0,0,.65); backdrop-filter:blur(4px);
  align-items:center; justify-content:center;
}
.modal-overlay.open { display:flex; }
.modal-box {
  background:var(--bg-surface);
  border:.5px solid var(--border-md);
  border-radius:16px; width:100%; max-width:420px;
  overflow:hidden; animation:fadeUp .18s ease;
}
@keyframes fadeUp {
  from { opacity:0; transform:translateY(16px); }
  to   { opacity:1; transform:translateY(0); }
}
.modal-head {
  display:flex; align-items:center; justify-content:space-between;
  padding:16px 20px; border-bottom:.5px solid var(--border);
}
.modal-title { font-size:15px; font-weight:600; }
.modal-close {
  width:28px; height:28px; border-radius:8px;
  border:none; background:none; color:var(--text-secondary);
  font-size:17px; display:flex; align-items:center; justify-content:center;
  cursor:pointer; transition:background .12s;
}
.modal-close:hover { background:var(--bg-hover); }
.modal-body { padding:20px; display:flex; flex-direction:column; gap:16px; }
.modal-footer {
  padding:12px 20px; border-top:.5px solid var(--border);
  display:flex; justify-content:flex-end; gap:8px;
}

/* ── Form Elements ────────────────────────────── */
.form-group { display:flex; flex-direction:column; gap:6px; }
.form-label { font-size:12px; font-weight:500; color:var(--text-secondary); }
.form-input, .form-select {
  width:100%; padding:9px 12px;
  background:var(--bg-sunken); border:.5px solid var(--border-md);
  border-radius:var(--radius-sm); color:var(--text-primary);
  font-size:13px; font-family:var(--font);
  transition:border-color .12s; outline:none;
}
.form-input:focus, .form-select:focus { border-color:var(--accent); }
.form-select option { background:var(--bg-surface); }
.form-error { font-size:11px; color:var(--red-400); }

/* ── Action Buttons ───────────────────────────── */
.act-btn {
  display:inline-flex; align-items:center; justify-content:center;
  width:30px; height:30px; border-radius:var(--radius-sm);
  border:.5px solid var(--border-md); background:none;
  color:var(--text-secondary); cursor:pointer; transition:all .12s;
  font-size:14px;
}
.act-btn.del:hover { background:var(--red-50); color:var(--red-400); border-color:var(--red-400); }

/* ── Search ───────────────────────────────────── */
.search-wrap {
  position:relative; flex:1; max-width:280px;
}
.search-wrap i {
  position:absolute; left:10px; top:50%; transform:translateY(-50%);
  color:var(--text-muted); font-size:15px; pointer-events:none;
}
.search-input {
  width:100%; padding:7px 12px 7px 32px;
  background:var(--bg-sunken); border:.5px solid var(--border-md);
  border-radius:var(--radius-sm); color:var(--text-primary);
  font-size:13px; font-family:var(--font); outline:none;
  transition:border-color .12s;
}
.search-input:focus { border-color:var(--accent); }

.card-toolbar {
  display:flex; align-items:center; justify-content:space-between;
  padding:12px 20px; border-bottom:.5px solid var(--border);
  gap:12px; flex-wrap:wrap;
}

.btn-sm {
  display:inline-flex; align-items:center; gap:5px;
  padding:6px 14px; border-radius:var(--radius-sm);
  font-size:12px; font-weight:500; border:none; cursor:pointer;
}
.btn-sm.primary { background:var(--accent); color:#fff; transition:background .12s; }
.btn-sm.primary:hover { background:var(--accent-hover); }
.btn-sm.ghost {
  background:none; color:var(--text-secondary);
  border:.5px solid var(--border-md); transition:all .12s;
}
.btn-sm.ghost:hover { background:var(--bg-hover); color:var(--text-primary); }
</style>
@endsection

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Permissions</div>
    <div class="page-sub">{{ $permissions->count() }} permission(s) across all guards</div>
  </div>
  <button class="btn-primary" onclick="document.getElementById('add-modal').classList.add('open')">
    <i class="ti ti-plus"></i> Add Permission
  </button>
</div>

<div class="card">
  <div class="card-toolbar">
    <div class="search-wrap">
      <i class="ti ti-search"></i>
      <input type="text" class="search-input" id="perm-search" placeholder="Search permissions…" oninput="filterTable()">
    </div>
    <div style="display:flex;gap:6px;">
      <button class="btn-sm ghost" onclick="filterGuard('')">All</button>
      <button class="btn-sm ghost" onclick="filterGuard('admins')">Admins</button>
      <button class="btn-sm ghost" onclick="filterGuard('super_admins')">Super Admins</button>
      <button class="btn-sm ghost" onclick="filterGuard('users')">Users</button>
    </div>
  </div>

  <table class="data-table" id="perm-table">
    <thead>
      <tr>
        <th style="width:40px;">#</th>
        <th>Name</th>
        <th>Guard</th>
        <th style="width:60px;"></th>
      </tr>
    </thead>
    <tbody>
      @forelse($permissions as $i => $perm)
      <tr data-guard="{{ $perm->guard_name }}" data-name="{{ strtolower($perm->name) }}">
        <td style="color:var(--text-muted);">{{ $i + 1 }}</td>
        <td style="font-weight:500;font-family:monospace;font-size:12px;">{{ $perm->name }}</td>
        <td><span class="guard-badge {{ $perm->guard_name }}">{{ $perm->guard_name }}</span></td>
        <td>
          <form method="POST" action="{{ route('super_admin.permissions.destroy', $perm->id) }}"
                onsubmit="return confirm('Delete permission: {{ $perm->name }}?')">
            @csrf @method('DELETE')
            <button type="submit" class="act-btn del" title="Delete">
              <i class="ti ti-trash"></i>
            </button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="4" style="text-align:center;padding:40px;color:var(--text-muted);">
          <i class="ti ti-key" style="font-size:32px;display:block;margin-bottom:8px;opacity:.3;"></i>
          No permissions yet.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- ── Add Permission Modal ─────────────────────────────────────────────── --}}
<div class="modal-overlay" id="add-modal" onclick="closeOnBackdrop(event,'add-modal')">
  <div class="modal-box">
    <div class="modal-head">
      <div class="modal-title"><i class="ti ti-key" style="margin-right:6px;"></i>New Permission</div>
      <button class="modal-close" onclick="document.getElementById('add-modal').classList.remove('open')">
        <i class="ti ti-x"></i>
      </button>
    </div>

    <form method="POST" action="{{ route('super_admin.permissions.store') }}">
      @csrf
      <div class="modal-body">

        @if($errors->has('name'))
        <div style="background:var(--red-50);color:var(--red-800);padding:10px 14px;border-radius:var(--radius-sm);font-size:12px;">
          <i class="ti ti-alert-circle"></i> {{ $errors->first('name') }}
        </div>
        @endif

        <div class="form-group">
          <label class="form-label">Permission Name</label>
          <input type="text" name="name" class="form-input"
                 placeholder="e.g. posts.delete"
                 value="{{ old('name') }}" required autocomplete="off">
          <span class="form-error" style="font-size:11px;color:var(--text-muted);">
            Use dot notation: <code>resource.action</code>
          </span>
        </div>

        <div class="form-group">
          <label class="form-label">Guard</label>
          <select name="guard_name" class="form-select" required>
            <option value="">— Select guard —</option>
            @foreach($guards as $g)
            <option value="{{ $g }}" {{ old('guard_name') === $g ? 'selected' : '' }}>{{ $g }}</option>
            @endforeach
          </select>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn-sm ghost"
                onclick="document.getElementById('add-modal').classList.remove('open')">Cancel</button>
        <button type="submit" class="btn-sm primary"><i class="ti ti-check"></i> Create</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
let activeGuard = '';

function filterTable() {
  const q = document.getElementById('perm-search').value.toLowerCase();
  document.querySelectorAll('#perm-table tbody tr[data-name]').forEach(row => {
    const matchName  = row.dataset.name.includes(q);
    const matchGuard = !activeGuard || row.dataset.guard === activeGuard;
    row.style.display = matchName && matchGuard ? '' : 'none';
  });
}

function filterGuard(guard) {
  activeGuard = guard;
  document.querySelectorAll('[onclick^="filterGuard"]').forEach(btn => {
    btn.classList.toggle('primary', btn.getAttribute('onclick') === `filterGuard('${guard}')` || (guard === '' && btn.getAttribute('onclick') === "filterGuard('')"));
    btn.classList.toggle('ghost',   !(btn.getAttribute('onclick') === `filterGuard('${guard}')` || (guard === '' && btn.getAttribute('onclick') === "filterGuard('')")));
  });
  filterTable();
}

function closeOnBackdrop(event, id) {
  if (event.target === event.currentTarget)
    document.getElementById(id).classList.remove('open');
}

// Re-open modal on validation error
@if($errors->any())
document.getElementById('add-modal').classList.add('open');
@endif
</script>
@endsection
