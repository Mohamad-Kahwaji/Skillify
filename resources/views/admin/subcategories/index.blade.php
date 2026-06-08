@extends('admin.layout')
@section('title', 'Subcategories')
@section('breadcrumb', 'Subcategories')

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">Subcategories</div>
    <div class="page-sub">{{ $subcategories->count() }} subcategory(s)</div>
  </div>
</div>

<div class="content-grid" style="grid-template-columns:1fr 360px;">

  <div class="card">
    <div class="card-head"><span class="card-title">Subcategories List</span></div>
    <table class="data-table">
      <thead>
        <tr><th>#</th><th>Name</th><th>Parent Category</th><th></th></tr>
      </thead>
      <tbody>
        @forelse($subcategories as $sub)
        <tr>
          <td style="color:var(--text-muted);">{{ $sub->id }}</td>
          <td>
            <div style="font-weight:500;">{{ $sub->name_ar }}</div>
            <div style="font-size:11px;color:var(--text-muted);">{{ $sub->name_en }}</div>
          </td>
          <td><span class="badge active">{{ $sub->category?->name_ar ?? '—' }}</span></td>
          <td>
            <div style="display:flex;gap:6px;align-items:center;">
              @can('subcategories.edit')
              <button class="btn-ghost" style="padding:5px 10px;font-size:11px;"
                data-id="{{ $sub->id }}"
                data-name-ar="{{ $sub->name_ar }}"
                data-name-en="{{ $sub->name_en }}"
                data-catid="{{ $sub->category_id }}"
                onclick="openEditSub(this)">
                <i class="ti ti-edit" style="font-size:13px;"></i> Edit
              </button>
              @endcan
              @can('subcategories.delete')
              <form method="POST" action="{{ route('admin.subcategories.destroy', $sub->id) }}"
                    onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger"><i class="ti ti-trash"></i></button>
              </form>
              @endcan
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="4"><div class="empty-state"><i class="ti ti-category-2"></i>No subcategories yet</div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @can('subcategories.create')
  <div class="card" style="padding:24px;">
    <div class="card-title" style="margin-bottom:20px;">Add Subcategory</div>
    @if(session('success'))
      <div class="alert success"><i class="ti ti-check"></i> {{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.subcategories.store') }}">
      @csrf

      <div style="margin-bottom:14px;">
        <label class="form-label">Parent Category</label>
        <div class="form-field"><i class="ti ti-category"></i>
          <select name="category_id" required>
            <option value="">Select category</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name_ar }}
              </option>
            @endforeach
          </select>
        </div>
        @error('category_id')<div style="font-size:11px;color:var(--red-400);margin-top:5px;">{{ $message }}</div>@enderror
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
        <i class="ti ti-plus"></i> Add Subcategory
      </button>
    </form>
  </div>
  @endcan

</div>

{{-- ── Edit Subcategory Modal ────────────────────────────── --}}
<div class="modal-overlay" id="edit-sub-modal" onclick="if(event.target===this)closeModal('edit-sub-modal')">
  <div class="modal-box">
    <div class="modal-head">
      <span class="modal-title"><i class="ti ti-edit"></i> Edit Subcategory</span>
      <button class="modal-close" onclick="closeModal('edit-sub-modal')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" id="edit-sub-form">
      @csrf @method('PUT')
      <div class="modal-body">
        <div>
          <label class="form-label">Parent Category</label>
          <div class="form-field"><i class="ti ti-category"></i>
            <select id="es-catid" name="category_id" required>
              <option value="">Select category</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name_ar }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div>
          <label class="form-label">Name (Arabic)</label>
          <div class="form-field"><i class="ti ti-typography"></i>
            <input type="text" id="es-name-ar" name="name_ar" required>
          </div>
        </div>
        <div>
          <label class="form-label">Name (English)</label>
          <div class="form-field"><i class="ti ti-typography"></i>
            <input type="text" id="es-name-en" name="name_en" required>
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost" onclick="closeModal('edit-sub-modal')">Cancel</button>
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

function openEditSub(btn) {
  const d = btn.dataset;
  document.getElementById('edit-sub-form').action = '{{ url("admin/subcategories") }}/' + d.id;
  document.getElementById('es-name-ar').value = d.nameAr || '';
  document.getElementById('es-name-en').value = d.nameEn || '';
  const sel = document.getElementById('es-catid');
  [...sel.options].forEach(o => o.selected = o.value === d.catid);
  openModal('edit-sub-modal');
}
</script>
@endsection
