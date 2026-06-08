@extends('admin.layout')
@section('title', 'Advertisements')
@section('breadcrumb', 'Ads')

@section('content')

<div class="page-head">
  <div>
    <div class="page-title">Advertisements</div>
    <div class="page-sub">Create and manage platform advertisements</div>
  </div>
  @can('ads.create')
  <button class="btn-primary" onclick="openModal('create-ad-modal')">
    <i class="ti ti-plus"></i> New Ad
  </button>
  @endcan
</div>

<div class="card">
  <div class="card-head">
    <span class="card-title">All Ads ({{ $advertisements->count() }})</span>
  </div>
  <div class="table-toolbar">
    <div class="search-field">
      <i class="ti ti-search"></i>
      <input type="text" id="q" placeholder="Search title or company…">
    </div>
    <div class="filter-chips" id="chips">
      <button class="chip on" data-s="">All ({{ $advertisements->count() }})</button>
      <button class="chip" data-s="approved">Approved ({{ $advertisements->where('status','approved')->count() }})</button>
      <button class="chip" data-s="pending">Pending ({{ $advertisements->where('status','pending')->count() }})</button>
      <button class="chip" data-s="rejected">Rejected ({{ $advertisements->where('status','rejected')->count() }})</button>
    </div>
    <span class="tbl-count" id="tbl-count"></span>
  </div>
  <table class="data-table" id="tbl">
    <thead>
      <tr>
        <th>Ad</th>
        <th>Company</th>
        <th>Status</th>
        <th>Period</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($advertisements as $ad)
      <tr data-s="{{ $ad->status ?? 'pending' }}"
          data-search="{{ strtolower(($ad->title ?? '') . ' ' . ($ad->company_name ?? '') . ' ' . ($ad->description ?? '')) }}">
        <td>
          <div style="display:flex;align-items:center;gap:10px;">
            @if($ad->image)
              <img src="{{ $ad->image }}" alt=""
                   style="width:40px;height:32px;border-radius:6px;object-fit:cover;flex-shrink:0;">
            @else
              <div style="width:40px;height:32px;border-radius:6px;background:var(--bg-sunken);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="ti ti-speakerphone" style="color:var(--text-muted);"></i>
              </div>
            @endif
            <div>
              <div class="cell-name">{{ $ad->title }}</div>
              <div class="cell-email">{{ Str::limit($ad->description ?? '', 50) }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);font-size:12px;">{{ $ad->company_name ?? '—' }}</td>
        <td>
          @php
            $s = $ad->status ?? 'pending';
            $badgeClass = $s === 'approved' ? 'active' : ($s === 'rejected' ? 'blocked' : 'pending');
          @endphp
          <span class="badge {{ $badgeClass }}">{{ ucfirst($s) }}</span>
        </td>
        <td style="color:var(--text-muted);font-size:12px;">
          @if($ad->start_date)
            {{ \Carbon\Carbon::parse($ad->start_date)->format('M d') }}
            @if($ad->end_date)
              – {{ \Carbon\Carbon::parse($ad->end_date)->format('M d, Y') }}
            @endif
          @else
            <span style="color:var(--text-muted);">Always on</span>
          @endif
        </td>
        <td>
          <div style="display:flex;gap:6px;align-items:center;">
            @can('ads.edit')
            <button class="btn-ghost" style="padding:5px 10px;font-size:11px;"
              data-id="{{ $ad->id }}"
              data-title="{{ $ad->title }}"
              data-desc="{{ $ad->description }}"
              data-company="{{ $ad->company_name }}"
              data-image="{{ $ad->image }}"
              data-start="{{ $ad->start_date }}"
              data-end="{{ $ad->end_date }}"
              data-status="{{ $ad->status ?? 'pending' }}"
              onclick="openEditAd(this)">
              <i class="ti ti-edit" style="font-size:13px;"></i> Edit
            </button>
            @endcan
            @can('ads.delete')
            <form method="POST" action="{{ route('admin.ads.delete', $ad->id) }}"
                  onsubmit="return confirm('Delete this ad?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger" title="Delete">
                <i class="ti ti-trash" style="font-size:13px;"></i>
              </button>
            </form>
            @endcan
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5">
          <div class="empty-state"><i class="ti ti-speakerphone"></i>No advertisements yet</div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- ── Create Ad Modal ──────────────────────────────────── --}}
<div class="modal-overlay" id="create-ad-modal" onclick="if(event.target===this)closeModal('create-ad-modal')">
  <div class="modal-box">
    <div class="modal-head">
      <span class="modal-title"><i class="ti ti-speakerphone"></i> New Advertisement</span>
      <button class="modal-close" onclick="closeModal('create-ad-modal')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.ads.store') }}">
      @csrf
      <input type="hidden" name="admin_id" value="{{ Auth::guard('admins')->id() ?? Auth::guard('super_admins')->id() }}">
      <div class="modal-body">
        <div>
          <label class="form-label">Ad Title</label>
          <div class="form-field"><i class="ti ti-speakerphone"></i>
            <input type="text" name="title" required placeholder="Enter ad title">
          </div>
        </div>
        <div>
          <label class="form-label">Description</label>
          <div class="form-field">
            <textarea name="description" rows="3" placeholder="Ad description…"></textarea>
          </div>
        </div>
        <div>
          <label class="form-label">Company Name</label>
          <div class="form-field"><i class="ti ti-building"></i>
            <input type="text" name="company_name" placeholder="Company name">
          </div>
        </div>
        <div>
          <label class="form-label">Image URL <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
          <div class="form-field"><i class="ti ti-photo"></i>
            <input type="text" name="image" placeholder="https://…">
          </div>
        </div>
        <div class="form-row-2">
          <div>
            <label class="form-label">Start Date</label>
            <div class="form-field"><i class="ti ti-calendar"></i>
              <input type="date" name="start_date">
            </div>
          </div>
          <div>
            <label class="form-label">End Date</label>
            <div class="form-field"><i class="ti ti-calendar-event"></i>
              <input type="date" name="end_date">
            </div>
          </div>
        </div>
        <div>
          <label class="form-label">Status</label>
          <div class="form-field"><i class="ti ti-circle-dot"></i>
            <select name="status">
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost" onclick="closeModal('create-ad-modal')">Cancel</button>
        <button type="submit" class="btn-primary"><i class="ti ti-plus"></i> Create Ad</button>
      </div>
    </form>
  </div>
</div>

{{-- ── Edit Ad Modal ─────────────────────────────────────── --}}
<div class="modal-overlay" id="edit-ad-modal" onclick="if(event.target===this)closeModal('edit-ad-modal')">
  <div class="modal-box">
    <div class="modal-head">
      <span class="modal-title"><i class="ti ti-edit"></i> Edit Advertisement</span>
      <button class="modal-close" onclick="closeModal('edit-ad-modal')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" id="edit-ad-form">
      @csrf @method('PUT')
      <div class="modal-body">
        <div>
          <label class="form-label">Ad Title</label>
          <div class="form-field"><i class="ti ti-speakerphone"></i>
            <input type="text" id="ea-title" name="title" required>
          </div>
        </div>
        <div>
          <label class="form-label">Description</label>
          <div class="form-field">
            <textarea id="ea-desc" name="description" rows="3"></textarea>
          </div>
        </div>
        <div>
          <label class="form-label">Company Name</label>
          <div class="form-field"><i class="ti ti-building"></i>
            <input type="text" id="ea-company" name="company_name">
          </div>
        </div>
        <div>
          <label class="form-label">Image URL</label>
          <div class="form-field"><i class="ti ti-photo"></i>
            <input type="text" id="ea-image" name="image">
          </div>
        </div>
        <div class="form-row-2">
          <div>
            <label class="form-label">Start Date</label>
            <div class="form-field"><i class="ti ti-calendar"></i>
              <input type="date" id="ea-start" name="start_date">
            </div>
          </div>
          <div>
            <label class="form-label">End Date</label>
            <div class="form-field"><i class="ti ti-calendar-event"></i>
              <input type="date" id="ea-end" name="end_date">
            </div>
          </div>
        </div>
        <div>
          <label class="form-label">Status</label>
          <div class="form-field"><i class="ti ti-circle-dot"></i>
            <select id="ea-status" name="status">
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost" onclick="closeModal('edit-ad-modal')">Cancel</button>
        <button type="submit" class="btn-primary"><i class="ti ti-device-floppy"></i> Save Changes</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.addEventListener('keydown', e => { if(e.key==='Escape') { document.querySelectorAll('.modal-overlay.open').forEach(m=>{ m.classList.remove('open'); document.body.style.overflow=''; }); } });

function openEditAd(btn) {
  const d = btn.dataset;
  document.getElementById('edit-ad-form').action = '{{ url("admin/ads") }}/' + d.id;
  document.getElementById('ea-title').value   = d.title   || '';
  document.getElementById('ea-desc').value    = d.desc    || '';
  document.getElementById('ea-company').value = d.company || '';
  document.getElementById('ea-image').value   = d.image   || '';
  document.getElementById('ea-start').value   = d.start   || '';
  document.getElementById('ea-end').value     = d.end     || '';
  const sel = document.getElementById('ea-status');
  [...sel.options].forEach(o => o.selected = o.value === d.status);
  openModal('edit-ad-modal');
}

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
