@extends('admin.layout')
@section('title', 'Categories')
@section('breadcrumb', 'Categories')

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">Categories</div>
    <div class="page-sub">{{ $categories->count() }} category(s) registered</div>
  </div>
</div>

<div class="content-grid" style="grid-template-columns:1fr 360px;">

  <div class="card">
    <div class="card-head">
      <span class="card-title">Categories List</span>
    </div>
    <table class="data-table">
      <thead>
        <tr><th>#</th><th>Name</th><th>Business Type</th><th></th></tr>
      </thead>
      <tbody>
        @forelse($categories as $cat)
        <tr>
          <td style="color:var(--text-muted);">{{ $cat->id }}</td>
          <td>
            <div style="font-weight:500;">{{ $cat->name_ar }}</div>
            <div style="font-size:11px;color:var(--text-muted);">{{ $cat->name_en }}</div>
          </td>
          <td>
            <span class="badge active">{{ $cat->activeTypebusiness?->name_ar ?? '—' }}</span>
          </td>
          <td>
            <div style="display:flex;gap:6px;align-items:center;">
              @can('categories.edit')
              <button class="btn-ghost" style="padding:5px 10px;font-size:11px;"
                data-id="{{ $cat->id }}"
                data-name-ar="{{ $cat->name_ar }}"
                data-name-en="{{ $cat->name_en }}"
                data-btid="{{ $cat->active_typebusiness_id }}"
                onclick="openEditCat(this)">
                <i class="ti ti-edit" style="font-size:13px;"></i> Edit
              </button>
              @endcan
              @can('categories.delete')
              <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}"
                    onsubmit="return confirm('Delete this category?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger"><i class="ti ti-trash"></i></button>
              </form>
              @endcan
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="4"><div class="empty-state"><i class="ti ti-category"></i>No categories yet</div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @can('categories.create')
  <div class="card" style="padding:24px;">
    <div class="card-title" style="margin-bottom:20px;">Add Category</div>
    @if(session('success'))
      <div class="alert success"><i class="ti ti-check"></i> {{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.categories.store') }}">
      @csrf

      <div style="margin-bottom:14px;">
        <label class="form-label">Business Type</label>
        <div class="form-field"><i class="ti ti-briefcase"></i>
          <select name="active_typebusiness_id" required>
            <option value="">Select business type</option>
            @foreach($businessTypes as $bt)
              <option value="{{ $bt->id }}" {{ old('active_typebusiness_id') == $bt->id ? 'selected' : '' }}>
                {{ $bt->name_ar }}
              </option>
            @endforeach
          </select>
        </div>
        @error('active_typebusiness_id')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:14px;">
        <label class="form-label">Name (Arabic)</label>
        <div class="form-field"><i class="ti ti-typography"></i>
          <input type="text" name="name_ar" value="{{ old('name_ar') }}" required>
        </div>
        @error('name_ar')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:20px;">
        <label class="form-label">Name (English)</label>
        <div class="form-field"><i class="ti ti-typography"></i>
          <input type="text" name="name_en" value="{{ old('name_en') }}" required>
        </div>
        @error('name_en')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
        <i class="ti ti-plus"></i> Add Category
      </button>
    </form>
  </div>
  @endcan

</div>

{{-- ── Edit Category Modal ───────────────────────────────── --}}
<div class="modal-overlay" id="edit-cat-modal" onclick="if(event.target===this)closeModal('edit-cat-modal')">
  <div class="modal-box">
    <div class="modal-head">
      <span class="modal-title"><i class="ti ti-edit"></i> Edit Category</span>
      <button class="modal-close" onclick="closeModal('edit-cat-modal')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" id="edit-cat-form">
      @csrf @method('PUT')
      <div class="modal-body">
        <div>
          <label class="form-label">Business Type</label>
          <div class="form-field"><i class="ti ti-briefcase"></i>
            <select id="ec-btid" name="active_typebusiness_id" required>
              <option value="">Select business type</option>
              @foreach($businessTypes as $bt)
                <option value="{{ $bt->id }}">{{ $bt->name_ar }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div>
          <label class="form-label">Name (Arabic)</label>
          <div class="form-field"><i class="ti ti-typography"></i>
            <input type="text" id="ec-name-ar" name="name_ar" required>
          </div>
        </div>
        <div>
          <label class="form-label">Name (English)</label>
          <div class="form-field"><i class="ti ti-typography"></i>
            <input type="text" id="ec-name-en" name="name_en" required>
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost" onclick="closeModal('edit-cat-modal')">Cancel</button>
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
document.addEventListener('keydown', e => { if(e.key==='Escape') document.querySelectorAll('.modal-overlay.open').forEach(m=>{ m.classList.remove('open'); document.body.style.overflow=''; }); });

function openEditCat(btn) {
  const d = btn.dataset;
  document.getElementById('edit-cat-form').action = '{{ url("admin/categories") }}/' + d.id;
  document.getElementById('ec-name-ar').value = d.nameAr || '';
  document.getElementById('ec-name-en').value = d.nameEn || '';
  const sel = document.getElementById('ec-btid');
  [...sel.options].forEach(o => o.selected = o.value === d.btid);
  openModal('edit-cat-modal');
}
</script>
@endsection
