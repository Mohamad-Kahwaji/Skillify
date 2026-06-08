@extends('super_admin.layout')

@section('title', 'Admins')
@section('breadcrumb', 'Admins')

@section('styles')
/* ── Toggle Switch ─────────────────────────────── */
.toggle-wrap { display:flex; align-items:center; gap:8px; }
.toggle {
  position:relative; width:40px; height:22px; flex-shrink:0;
}
.toggle input { opacity:0; width:0; height:0; position:absolute; }
.toggle-track {
  position:absolute; inset:0; border-radius:20px;
  background:rgba(255,255,255,.12);
  border:0.5px solid rgba(255,255,255,.08);
  cursor:pointer; transition:background .2s, border-color .2s;
}
.toggle input:checked + .toggle-track { background:var(--accent); border-color:var(--accent); }
.toggle-knob {
  position:absolute; top:3px; left:3px;
  width:16px; height:16px; border-radius:50%;
  background:#fff; transition:transform .2s;
  box-shadow:0 1px 3px rgba(0,0,0,.3);
  pointer-events:none;
}
.toggle input:checked ~ .toggle-knob { transform:translateX(18px); }
.toggle-lbl { font-size:12px; color:var(--text-muted); min-width:48px; }
.toggle input:checked ~ .toggle-track + .toggle-lbl { display:none; }

/* ── Info Modal ────────────────────────────────── */
.modal-overlay {
  display:none; position:fixed; inset:0; z-index:200;
  background:rgba(0,0,0,.6); backdrop-filter:blur(4px);
  align-items:center; justify-content:center;
}
.modal-overlay.open { display:flex; }
.modal-box {
  background:var(--bg-surface);
  border:0.5px solid var(--border-md);
  border-radius:16px; width:100%; max-width:420px;
  overflow:hidden; animation:fadeUp .2s ease;
}
@keyframes fadeUp {
  from { opacity:0; transform:translateY(20px); }
  to   { opacity:1; transform:translateY(0); }
}
.modal-head {
  display:flex; align-items:center; justify-content:space-between;
  padding:16px 20px; border-bottom:0.5px solid var(--border);
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
.modal-avatar-row {
  display:flex; align-items:center; gap:14px;
}
.modal-avatar {
  width:52px; height:52px; border-radius:50%;
  display:flex; align-items:center; justify-content:center;
  font-size:20px; font-weight:700; color:#fff; flex-shrink:0;
}
.modal-name  { font-size:15px; font-weight:600; }
.modal-email { font-size:12px; color:var(--text-muted); margin-top:2px; }
.info-rows { display:flex; flex-direction:column; gap:10px; }
.info-row-item {
  display:flex; align-items:center; justify-content:space-between;
  font-size:13px;
}
.info-row-lbl { color:var(--text-secondary); display:flex; align-items:center; gap:6px; }
.info-row-lbl i { font-size:15px; }
.info-row-val { font-weight:500; }
.modal-foot {
  padding:14px 20px; border-top:0.5px solid var(--border);
  display:flex; justify-content:flex-end; gap:8px;
}
.btn-sm {
  display:inline-flex; align-items:center; gap:5px;
  padding:7px 14px; border-radius:8px; font-size:12px;
  font-weight:500; border:none; cursor:pointer; transition:background .12s;
}
.btn-sm-ghost {
  background:var(--bg-sunken);
  border:0.5px solid var(--border-md);
  color:var(--text-secondary);
}
.btn-sm-ghost:hover { background:var(--bg-hover); }
@endsection

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Manage Admins</div>
    <div class="page-sub">{{ $admins->count() }} admin(s) registered</div>
  </div>
  <button class="btn-primary" onclick="openModal('create-admin-modal')">
    <i class="ti ti-plus"></i> Add Admin
  </button>
</div>

<div class="card">
  <table class="data-table">
    <thead>
      <tr>
        <th>Admin</th>
        <th>Phone</th>
        <th>Role</th>
        <th>Status</th>
        <th>Created</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($admins as $admin)
      @php
        $isActive = ($admin->status ?? 'active') === 'active';
        $colors   = ['#1D9E75','#3B82F6','#8B5CF6','#F59E0B','#EF4444'];
        $color    = $colors[$admin->id % count($colors)];
      @endphp
      <tr>

        {{-- Name + Email --}}
        <td>
          <div class="cell-user">
            <div class="avatar" style="background:{{ $color }};">
              {{ strtoupper(substr($admin->first_name, 0, 1)) }}
            </div>
            <div>
              <div class="cell-name">{{ $admin->first_name }} {{ $admin->last_name }}</div>
              <div class="cell-email">{{ $admin->email }}</div>
            </div>
          </div>
        </td>

        {{-- Phone --}}
        <td style="color:var(--text-secondary);">{{ $admin->phone ?? '—' }}</td>

        {{-- Role --}}
        <td><span class="badge admin">{{ $admin->role ?? 'admin' }}</span></td>

        {{-- Toggle Switch --}}
        <td>
          <form method="POST"
                action="{{ $isActive
                    ? route('super_admin.admins.deactivate', $admin)
                    : route('super_admin.admins.activate',   $admin) }}">
            @csrf @method('PATCH')
            <div class="toggle-wrap">
              <label class="toggle" title="{{ $isActive ? 'Deactivate' : 'Activate' }}">
                <input type="checkbox" {{ $isActive ? 'checked' : '' }}
                       onchange="this.form.submit()">
                <span class="toggle-track"></span>
                <span class="toggle-knob"></span>
              </label>
              <span class="toggle-lbl" style="color:{{ $isActive ? 'var(--accent)' : 'var(--text-muted)' }};">
                {{ $isActive ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </form>
        </td>

        {{-- Created --}}
        <td style="color:var(--text-muted);">{{ $admin->created_at->format('Y/m/d') }}</td>

        {{-- Actions --}}
        <td>
          <div style="display:flex;align-items:center;gap:6px;">

            {{-- View button --}}
            <button class="btn-sm btn-sm-ghost"
                    onclick="openAdminModal({
                      id:        {{ $admin->id }},
                      name:      '{{ addslashes($admin->first_name . ' ' . $admin->last_name) }}',
                      email:     '{{ addslashes($admin->email) }}',
                      phone:     '{{ addslashes($admin->phone ?? '—') }}',
                      role:      '{{ addslashes($admin->role ?? 'admin') }}',
                      status:    '{{ $admin->status ?? 'active' }}',
                      created:   '{{ $admin->created_at->format('Y/m/d H:i') }}',
                      initial:   '{{ strtoupper(substr($admin->first_name, 0, 1)) }}',
                      color:     '{{ $color }}'
                    })">
              <i class="ti ti-eye"></i> View
            </button>

            {{-- Delete --}}
            <form method="POST" action="{{ route('super_admin.admins.destroy', $admin) }}"
                  onsubmit="return confirm('Delete {{ $admin->first_name }}?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger btn-sm">
                <i class="ti ti-trash"></i> Delete
              </button>
            </form>

          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" style="text-align:center;padding:48px;color:var(--text-muted);">
          <i class="ti ti-user-shield" style="font-size:32px;display:block;margin-bottom:8px;"></i>
          No admins yet
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- ── Create Admin Modal ──────────────────────────────── --}}
<div class="modal-overlay" id="create-admin-modal" onclick="if(event.target===this)closeModal('create-admin-modal')">
  <div class="modal-box" style="max-width:600px;">
    <div class="modal-head">
      <span class="modal-title"><i class="ti ti-user-plus"></i> Add New Admin</span>
      <button class="modal-close" onclick="closeModal('create-admin-modal')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" action="{{ route('super_admin.admins.store') }}">
      @csrf
      <div class="modal-body">
        @if($errors->any())
          <div style="background:rgba(226,75,74,.12);border:0.5px solid rgba(226,75,74,.3);border-radius:8px;padding:10px 14px;font-size:12px;color:#F09595;display:flex;gap:8px;align-items:center;">
            <i class="ti ti-alert-circle"></i> {{ $errors->first() }}
          </div>
        @endif

        <div class="form-row-2">
          <div>
            <label class="form-label">First Name</label>
            <div class="form-field"><i class="ti ti-user"></i>
              <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="First name">
            </div>
          </div>
          <div>
            <label class="form-label">Last Name</label>
            <div class="form-field"><i class="ti ti-user"></i>
              <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="Last name">
            </div>
          </div>
        </div>

        <div>
          <label class="form-label">Email Address</label>
          <div class="form-field"><i class="ti ti-mail"></i>
            <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@example.com">
          </div>
        </div>

        <div class="form-row-2">
          <div>
            <label class="form-label">Phone <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
            <div class="form-field"><i class="ti ti-phone"></i>
              <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+962…">
            </div>
          </div>
          <div>
            <label class="form-label">Role</label>
            <div class="form-field"><i class="ti ti-shield"></i>
              <select name="role" required>
                <option value="admin"     {{ old('role') === 'admin'     ? 'selected' : '' }}>Admin</option>
                <option value="moderator" {{ old('role') === 'moderator' ? 'selected' : '' }}>Moderator</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-row-2">
          <div>
            <label class="form-label">Password</label>
            <div class="form-field"><i class="ti ti-lock"></i>
              <input type="password" name="password" required minlength="8" placeholder="Min 8 characters">
            </div>
          </div>
          <div>
            <label class="form-label">Confirm Password</label>
            <div class="form-field"><i class="ti ti-lock-check"></i>
              <input type="password" name="password_confirmation" required placeholder="Repeat password">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost" onclick="closeModal('create-admin-modal')">Cancel</button>
        <button type="submit" class="btn-primary"><i class="ti ti-user-plus"></i> Create Admin</button>
      </div>
    </form>
  </div>
</div>

{{-- ── Info Modal ──────────────────────────────────────── --}}
<div class="modal-overlay" id="admin-modal" onclick="closeAdminModal(event)">
  <div class="modal-box">
    <div class="modal-head">
      <span class="modal-title">Admin Details</span>
      <button class="modal-close" onclick="closeAdminModal()">
        <i class="ti ti-x"></i>
      </button>
    </div>
    <div class="modal-body">
      <div class="modal-avatar-row">
        <div class="modal-avatar" id="m-avatar"></div>
        <div>
          <div class="modal-name"  id="m-name"></div>
          <div class="modal-email" id="m-email"></div>
        </div>
      </div>
      <div class="info-rows">
        <div class="info-row-item">
          <span class="info-row-lbl"><i class="ti ti-phone"></i> Phone</span>
          <span class="info-row-val" id="m-phone"></span>
        </div>
        <div class="info-row-item">
          <span class="info-row-lbl"><i class="ti ti-shield"></i> Role</span>
          <span class="info-row-val" id="m-role"></span>
        </div>
        <div class="info-row-item">
          <span class="info-row-lbl"><i class="ti ti-circle-dot"></i> Status</span>
          <span class="info-row-val" id="m-status"></span>
        </div>
        <div class="info-row-item">
          <span class="info-row-lbl"><i class="ti ti-calendar"></i> Joined</span>
          <span class="info-row-val" id="m-created"></span>
        </div>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-sm btn-sm-ghost" onclick="closeAdminModal()">Close</button>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  function openModal(id)  { document.getElementById(id).classList.add('open');    document.body.style.overflow='hidden'; }
  function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-overlay.open').forEach(m => { m.classList.remove('open'); document.body.style.overflow=''; });
    }
  });

  @if($errors->any())
    openModal('create-admin-modal');
  @endif

  function openAdminModal(d) {
    document.getElementById('m-avatar').textContent    = d.initial;
    document.getElementById('m-avatar').style.background = d.color;
    document.getElementById('m-name').textContent      = d.name;
    document.getElementById('m-email').textContent     = d.email;
    document.getElementById('m-phone').textContent     = d.phone;
    document.getElementById('m-role').textContent      = d.role;
    document.getElementById('m-created').textContent   = d.created;

    const statusEl = document.getElementById('m-status');
    const isActive = d.status === 'active';
    statusEl.innerHTML = isActive
      ? '<span class="badge active"><i class="ti ti-circle-check-filled" style="font-size:11px;"></i> Active</span>'
      : '<span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;background:rgba(255,255,255,.08);color:var(--text-muted);">● Inactive</span>';

    openModal('admin-modal');
  }

  function closeAdminModal(e) {
    if (e && e.target !== document.getElementById('admin-modal')) return;
    closeModal('admin-modal');
  }
</script>
@endsection
