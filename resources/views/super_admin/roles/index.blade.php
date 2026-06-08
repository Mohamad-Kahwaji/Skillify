@extends('super_admin.layout')

@section('title', 'Roles')
@section('breadcrumb', 'Roles')

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

/* ── Permission Pills ─────────────────────────── */
.perm-pills { display:flex; flex-wrap:wrap; gap:4px; }
.perm-pill {
  font-size:10px; font-weight:500; padding:2px 7px;
  border-radius:20px; border:.5px solid var(--border-md);
  color:var(--text-muted); background:var(--bg-sunken);
  font-family:monospace;
}
.perm-more {
  font-size:10px; color:var(--text-muted); font-style:italic;
  align-self:center;
}

/* ── Modal ────────────────────────────────────── */
.modal-overlay {
  display:none; position:fixed; inset:0; z-index:200;
  background:rgba(0,0,0,.65); backdrop-filter:blur(4px);
  align-items:center; justify-content:center; padding:16px;
}
.modal-overlay.open { display:flex; }
.modal-box {
  background:var(--bg-surface);
  border:.5px solid var(--border-md);
  border-radius:16px; width:100%; max-width:520px;
  max-height:90vh; display:flex; flex-direction:column;
  overflow:hidden; animation:fadeUp .18s ease;
}
@keyframes fadeUp {
  from { opacity:0; transform:translateY(16px); }
  to   { opacity:1; transform:translateY(0); }
}
.modal-head {
  display:flex; align-items:center; justify-content:space-between;
  padding:16px 20px; border-bottom:.5px solid var(--border);
  flex-shrink:0;
}
.modal-title { font-size:15px; font-weight:600; }
.modal-close {
  width:28px; height:28px; border-radius:8px;
  border:none; background:none; color:var(--text-secondary);
  font-size:17px; display:flex; align-items:center; justify-content:center;
  cursor:pointer; transition:background .12s;
}
.modal-close:hover { background:var(--bg-hover); }
.modal-scroll { overflow-y:auto; flex:1; }
.modal-body { padding:20px; display:flex; flex-direction:column; gap:16px; }
.modal-footer {
  padding:12px 20px; border-top:.5px solid var(--border);
  display:flex; justify-content:flex-end; gap:8px; flex-shrink:0;
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
.form-input[readonly] {
  opacity:.55; cursor:not-allowed;
}

/* ── Permissions Picker ───────────────────────── */
.perm-picker-head {
  display:flex; align-items:center; justify-content:space-between;
  margin-bottom:10px;
}
.perm-picker-label { font-size:12px; font-weight:500; color:var(--text-secondary); }
.perm-picker-actions { display:flex; gap:6px; }
.pick-link {
  font-size:11px; color:var(--accent); background:none;
  border:none; cursor:pointer; padding:0; text-decoration:underline;
}
.perm-picker-box {
  background:var(--bg-sunken); border:.5px solid var(--border-md);
  border-radius:var(--radius-sm); max-height:200px; overflow-y:auto;
  padding:4px 0;
}
.perm-cb-row {
  display:flex; align-items:center; gap:9px;
  padding:6px 12px; cursor:pointer; transition:background .1s;
}
.perm-cb-row:hover { background:var(--bg-hover); }
.perm-cb-row input[type=checkbox] {
  accent-color:var(--accent); width:14px; height:14px; flex-shrink:0;
}
.perm-cb-label { font-size:12px; font-family:monospace; color:var(--text-primary); }
.perm-guard-sep {
  padding:5px 12px 3px;
  font-size:10px; font-weight:600; letter-spacing:1px;
  text-transform:uppercase; color:var(--text-muted);
  border-top:.5px solid var(--border); margin-top:2px;
}
.perm-guard-sep:first-child { border-top:none; margin-top:0; }
.perm-group { }
.no-perms-hint {
  padding:24px; text-align:center;
  color:var(--text-muted); font-size:12px;
}

/* ── Action Buttons ───────────────────────────── */
.act-btn {
  display:inline-flex; align-items:center; justify-content:center;
  width:30px; height:30px; border-radius:var(--radius-sm);
  border:.5px solid var(--border-md); background:none;
  color:var(--text-secondary); cursor:pointer; transition:all .12s;
  font-size:14px;
}
.act-btn:hover     { background:var(--bg-hover); color:var(--text-primary); }
.act-btn.del:hover { background:var(--red-50); color:var(--red-400); border-color:var(--red-400); }
.act-btn.edit:hover{ background:rgba(59,130,246,.1); color:#60a5fa; border-color:#60a5fa; }

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

@php
  // Group permissions by guard for JS data
  $permsByGuard = $permissions->groupBy('guard_name');
@endphp

<div class="page-head">
  <div>
    <div class="page-title">Roles</div>
    <div class="page-sub">{{ $roles->count() }} role(s) across all guards</div>
  </div>
  <button class="btn-primary" onclick="document.getElementById('add-modal').classList.add('open')">
    <i class="ti ti-plus"></i> Add Role
  </button>
</div>

<div class="card">
  <table class="data-table">
    <thead>
      <tr>
        <th style="width:40px;">#</th>
        <th>Role Name</th>
        <th>Guard</th>
        <th>Permissions</th>
        <th style="width:80px;"></th>
      </tr>
    </thead>
    <tbody>
      @forelse($roles as $i => $role)
      <tr>
        <td style="color:var(--text-muted);">{{ $i + 1 }}</td>
        <td style="font-weight:600;">{{ $role->name }}</td>
        <td><span class="guard-badge {{ $role->guard_name }}">{{ $role->guard_name }}</span></td>
        <td>
          @if($role->permissions->isEmpty())
            <span style="color:var(--text-muted);font-size:12px;">No permissions</span>
          @else
            <div class="perm-pills">
              @foreach($role->permissions->take(4) as $p)
                <span class="perm-pill">{{ $p->name }}</span>
              @endforeach
              @if($role->permissions->count() > 4)
                <span class="perm-more">+{{ $role->permissions->count() - 4 }} more</span>
              @endif
            </div>
          @endif
        </td>
        <td>
          <div style="display:flex;gap:4px;">
            <button class="act-btn edit"
              title="Edit"
              data-id="{{ $role->id }}"
              data-name="{{ $role->name }}"
              data-guard="{{ $role->guard_name }}"
              data-perms="{{ $role->permissions->pluck('id')->join(',') }}"
              onclick="openEditModal(this)">
              <i class="ti ti-pencil"></i>
            </button>
            <form method="POST" action="{{ route('super_admin.roles.destroy', $role->id) }}"
                  onsubmit="return confirm('Delete role: {{ $role->name }}?')">
              @csrf @method('DELETE')
              <button type="submit" class="act-btn del" title="Delete">
                <i class="ti ti-trash"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">
          <i class="ti ti-lock-access" style="font-size:32px;display:block;margin-bottom:8px;opacity:.3;"></i>
          No roles yet.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- ── Add Role Modal ───────────────────────────────────────────────────── --}}
<div class="modal-overlay" id="add-modal" onclick="closeOnBackdrop(event,'add-modal')">
  <div class="modal-box">
    <div class="modal-head">
      <div class="modal-title"><i class="ti ti-lock-access" style="margin-right:6px;"></i>New Role</div>
      <button class="modal-close" onclick="document.getElementById('add-modal').classList.remove('open')">
        <i class="ti ti-x"></i>
      </button>
    </div>

    <form method="POST" action="{{ route('super_admin.roles.store') }}" id="add-form">
      @csrf
      <div class="modal-scroll">
        <div class="modal-body">

          @if($errors->has('name') && old('_form') === 'add')
          <div style="background:var(--red-50);color:var(--red-800);padding:10px 14px;border-radius:var(--radius-sm);font-size:12px;">
            <i class="ti ti-alert-circle"></i> {{ $errors->first('name') }}
          </div>
          @endif

          <input type="hidden" name="_form" value="add">

          <div class="form-group">
            <label class="form-label">Role Name</label>
            <input type="text" name="name" class="form-input"
                   placeholder="e.g. content_moderator"
                   value="{{ old('name') }}" required autocomplete="off">
          </div>

          <div class="form-group">
            <label class="form-label">Guard</label>
            <select name="guard_name" class="form-select" id="add-guard" required onchange="onAddGuardChange(this.value)">
              <option value="">— Select guard —</option>
              @foreach($guards as $g)
              <option value="{{ $g }}" {{ old('guard_name') === $g ? 'selected' : '' }}>{{ $g }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group" id="add-perms-wrap" style="display:none;">
            <div class="perm-picker-head">
              <span class="perm-picker-label">Permissions</span>
              <div class="perm-picker-actions">
                <button type="button" class="pick-link" onclick="selectAllAdd(true)">Select all</button>
                <span style="color:var(--text-muted);">·</span>
                <button type="button" class="pick-link" onclick="selectAllAdd(false)">Clear</button>
              </div>
            </div>
            <div class="perm-picker-box" id="add-perm-list">
              @foreach($permsByGuard as $guard => $perms)
              <div class="perm-group add-perm-group" data-guard="{{ $guard }}" style="display:none;">
                <div class="perm-guard-sep">{{ $guard }}</div>
                @foreach($perms as $perm)
                <label class="perm-cb-row">
                  <input type="checkbox" class="add-perm-cb" name="permissions[]" value="{{ $perm->id }}">
                  <span class="perm-cb-label">{{ $perm->name }}</span>
                </label>
                @endforeach
              </div>
              @endforeach
              <div class="no-perms-hint" id="add-no-perms">Select a guard to view available permissions.</div>
            </div>
          </div>

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

{{-- ── Edit Role Modal ──────────────────────────────────────────────────── --}}
<div class="modal-overlay" id="edit-modal" onclick="closeOnBackdrop(event,'edit-modal')">
  <div class="modal-box">
    <div class="modal-head">
      <div class="modal-title"><i class="ti ti-pencil" style="margin-right:6px;"></i>Edit Role</div>
      <button class="modal-close" onclick="document.getElementById('edit-modal').classList.remove('open')">
        <i class="ti ti-x"></i>
      </button>
    </div>

    <form method="POST" id="edit-form">
      @csrf @method('PUT')
      <div class="modal-scroll">
        <div class="modal-body">

          <input type="hidden" name="_form" value="edit">

          <div class="form-group">
            <label class="form-label">Role Name</label>
            <input type="text" name="name" id="edit-name" class="form-input" required autocomplete="off">
          </div>

          <div class="form-group">
            <label class="form-label">Guard</label>
            <input type="text" id="edit-guard-label" class="form-input" readonly>
          </div>

          <div class="form-group">
            <div class="perm-picker-head">
              <span class="perm-picker-label">Permissions</span>
              <div class="perm-picker-actions">
                <button type="button" class="pick-link" onclick="selectAllEdit(true)">Select all</button>
                <span style="color:var(--text-muted);">·</span>
                <button type="button" class="pick-link" onclick="selectAllEdit(false)">Clear</button>
              </div>
            </div>
            <div class="perm-picker-box" id="edit-perm-list">
              @foreach($permsByGuard as $guard => $perms)
              <div class="perm-group edit-perm-group" data-guard="{{ $guard }}" style="display:none;">
                <div class="perm-guard-sep">{{ $guard }}</div>
                @foreach($perms as $perm)
                <label class="perm-cb-row">
                  <input type="checkbox" class="edit-perm-cb" name="permissions[]" value="{{ $perm->id }}">
                  <span class="perm-cb-label">{{ $perm->name }}</span>
                </label>
                @endforeach
              </div>
              @endforeach
              <div class="no-perms-hint" id="edit-no-perms" style="display:none;">No permissions available for this guard.</div>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-sm ghost"
                onclick="document.getElementById('edit-modal').classList.remove('open')">Cancel</button>
        <button type="submit" class="btn-sm primary"><i class="ti ti-device-floppy"></i> Save Changes</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
// ── Add Modal ─────────────────────────────────────────────────────────────

function onAddGuardChange(guard) {
  const wrap = document.getElementById('add-perms-wrap');
  const hint = document.getElementById('add-no-perms');
  const groups = document.querySelectorAll('.add-perm-group');

  // Uncheck all first
  document.querySelectorAll('.add-perm-cb').forEach(cb => cb.checked = false);

  if (!guard) {
    wrap.style.display = 'none';
    return;
  }

  wrap.style.display = '';
  let hasPerms = false;
  groups.forEach(g => {
    const show = g.dataset.guard === guard;
    g.style.display = show ? '' : 'none';
    if (show) hasPerms = true;
  });
  hint.style.display = hasPerms ? 'none' : '';
}

function selectAllAdd(check) {
  const guard = document.getElementById('add-guard').value;
  document.querySelectorAll('.add-perm-group[data-guard="' + guard + '"] .add-perm-cb')
          .forEach(cb => cb.checked = check);
}

// ── Edit Modal ────────────────────────────────────────────────────────────

function openEditModal(btn) {
  const id    = btn.dataset.id;
  const name  = btn.dataset.name;
  const guard = btn.dataset.guard;
  const perms = btn.dataset.perms ? btn.dataset.perms.split(',').map(Number).filter(Boolean) : [];

  document.getElementById('edit-form').action = '/super-admin/roles/' + id;
  document.getElementById('edit-name').value  = name;
  document.getElementById('edit-guard-label').value = guard;

  // Show only the correct guard's permissions
  let hasPerms = false;
  document.querySelectorAll('.edit-perm-group').forEach(g => {
    const show = g.dataset.guard === guard;
    g.style.display = show ? '' : 'none';
    if (show) hasPerms = true;
  });
  document.getElementById('edit-no-perms').style.display = hasPerms ? 'none' : '';

  // Tick existing permissions
  document.querySelectorAll('.edit-perm-cb').forEach(cb => {
    cb.checked = perms.includes(Number(cb.value));
  });

  document.getElementById('edit-modal').classList.add('open');
}

function selectAllEdit(check) {
  const guard = document.getElementById('edit-guard-label').value;
  document.querySelectorAll('.edit-perm-group[data-guard="' + guard + '"] .edit-perm-cb')
          .forEach(cb => cb.checked = check);
}

// ── Shared ────────────────────────────────────────────────────────────────

function closeOnBackdrop(event, id) {
  if (event.target === event.currentTarget)
    document.getElementById(id).classList.remove('open');
}

// Re-open on validation error
@if($errors->any())
  @if(old('_form') === 'add')
    document.getElementById('add-modal').classList.add('open');
    onAddGuardChange('{{ old('guard_name') }}');
  @elseif(old('_form') === 'edit')
    {{-- edit errors re-open is handled server-side if needed --}}
  @endif
@endif
</script>
@endsection
