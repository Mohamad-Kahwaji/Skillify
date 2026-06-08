@extends('admin.layout')
@section('title', 'Cities')
@section('breadcrumb', 'Cities')

@section('content')
<div class="page-head">
  <div>
    <div class="page-title">Cities</div>
    <div class="page-sub">{{ $cities->count() }} cities registered</div>
  </div>
  @can('cities.create')
  <button class="btn-primary" onclick="openModal('create-city-modal')">
    <i class="ti ti-plus"></i> Add City
  </button>
  @endcan
</div>

@if(session('success'))
  <div class="alert success"><i class="ti ti-check"></i> {{ session('success') }}</div>
@endif

<div class="card">
  <div class="card-head">
    <span class="card-title">All Cities</span>
    <span style="font-size:12px;color:var(--text-muted);">Grouped by governorate</span>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>City</th>
        <th>Governorate</th>
        <th>Coordinates</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($cities as $city)
      <tr>
        <td>
          <div style="font-weight:500;">{{ $city->name_en }}</div>
          <div style="font-size:11px;color:var(--text-muted);" dir="rtl">{{ $city->name_ar }}</div>
        </td>
        <td>
          <div style="font-size:13px;">{{ $city->governorate_en }}</div>
          <div style="font-size:11px;color:var(--text-muted);" dir="rtl">{{ $city->governorate_ar }}</div>
        </td>
        <td>
          <code style="font-size:11px;background:var(--bg-sunken);padding:3px 8px;border-radius:4px;color:var(--text-secondary);">
            {{ $city->latitude }}, {{ $city->longitude }}
          </code>
        </td>
        <td>
          <div style="display:flex;gap:6px;">
            @can('cities.edit')
            <button class="btn-ghost" style="padding:5px 12px;font-size:12px;"
              data-id="{{ $city->id }}"
              data-name-en="{{ $city->name_en }}"
              data-name-ar="{{ $city->name_ar }}"
              data-gov-en="{{ $city->governorate_en }}"
              data-gov-ar="{{ $city->governorate_ar }}"
              data-lat="{{ $city->latitude }}"
              data-lng="{{ $city->longitude }}"
              onclick="openEditCity(this)">
              <i class="ti ti-edit"></i> Edit
            </button>
            @endcan
            @can('cities.delete')
            <form method="POST" action="{{ route('admin.cities.destroy', $city->id) }}"
                  onsubmit="return confirm('Delete {{ $city->name_en }}?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger"><i class="ti ti-trash"></i></button>
            </form>
            @endcan
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="4"><div class="empty-state"><i class="ti ti-map-pin"></i>No cities added yet</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- ── Create City Modal ────────────────────────────────── --}}
<div class="modal-overlay" id="create-city-modal" onclick="if(event.target===this)closeModal('create-city-modal')">
  <div class="modal-box">
    <div class="modal-head">
      <span class="modal-title"><i class="ti ti-map-pin"></i> Add New City</span>
      <button class="modal-close" onclick="closeModal('create-city-modal')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.cities.store') }}">
      @csrf
      <div class="modal-body">
        <div class="form-row-2">
          <div>
            <label class="form-label">City Name (English)</label>
            <div class="form-field"><i class="ti ti-map-pin"></i>
              <input type="text" name="name_en" required placeholder="e.g. Amman">
            </div>
          </div>
          <div>
            <label class="form-label">City Name (Arabic)</label>
            <div class="form-field"><i class="ti ti-map-pin"></i>
              <input type="text" name="name_ar" required placeholder="e.g. عمّان" dir="rtl">
            </div>
          </div>
        </div>
        <div class="form-row-2">
          <div>
            <label class="form-label">Governorate (English)</label>
            <div class="form-field"><i class="ti ti-building-bank"></i>
              <input type="text" name="governorate_en" required placeholder="e.g. Amman">
            </div>
          </div>
          <div>
            <label class="form-label">Governorate (Arabic)</label>
            <div class="form-field"><i class="ti ti-building-bank"></i>
              <input type="text" name="governorate_ar" required placeholder="e.g. عمّان" dir="rtl">
            </div>
          </div>
        </div>
        <div class="form-row-2">
          <div>
            <label class="form-label">Latitude</label>
            <div class="form-field"><i class="ti ti-location"></i>
              <input type="text" name="latitude" placeholder="31.9539">
            </div>
          </div>
          <div>
            <label class="form-label">Longitude</label>
            <div class="form-field"><i class="ti ti-location"></i>
              <input type="text" name="longitude" placeholder="35.9106">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost" onclick="closeModal('create-city-modal')">Cancel</button>
        <button type="submit" class="btn-primary"><i class="ti ti-plus"></i> Add City</button>
      </div>
    </form>
  </div>
</div>

{{-- ── Edit City Modal ──────────────────────────────────── --}}
<div class="modal-overlay" id="edit-city-modal" onclick="if(event.target===this)closeModal('edit-city-modal')">
  <div class="modal-box">
    <div class="modal-head">
      <span class="modal-title"><i class="ti ti-edit"></i> Edit City</span>
      <button class="modal-close" onclick="closeModal('edit-city-modal')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" id="edit-city-form">
      @csrf @method('PUT')
      <div class="modal-body">
        <div class="form-row-2">
          <div>
            <label class="form-label">City Name (English)</label>
            <div class="form-field"><i class="ti ti-map-pin"></i>
              <input type="text" id="ec-name-en" name="name_en" required>
            </div>
          </div>
          <div>
            <label class="form-label">City Name (Arabic)</label>
            <div class="form-field"><i class="ti ti-map-pin"></i>
              <input type="text" id="ec-name-ar" name="name_ar" required dir="rtl">
            </div>
          </div>
        </div>
        <div class="form-row-2">
          <div>
            <label class="form-label">Governorate (English)</label>
            <div class="form-field"><i class="ti ti-building-bank"></i>
              <input type="text" id="ec-gov-en" name="governorate_en" required>
            </div>
          </div>
          <div>
            <label class="form-label">Governorate (Arabic)</label>
            <div class="form-field"><i class="ti ti-building-bank"></i>
              <input type="text" id="ec-gov-ar" name="governorate_ar" required dir="rtl">
            </div>
          </div>
        </div>
        <div class="form-row-2">
          <div>
            <label class="form-label">Latitude</label>
            <div class="form-field"><i class="ti ti-location"></i>
              <input type="text" id="ec-lat" name="latitude">
            </div>
          </div>
          <div>
            <label class="form-label">Longitude</label>
            <div class="form-field"><i class="ti ti-location"></i>
              <input type="text" id="ec-lng" name="longitude">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost" onclick="closeModal('edit-city-modal')">Cancel</button>
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

function openEditCity(btn) {
  const d = btn.dataset;
  document.getElementById('edit-city-form').action = '{{ url("admin/cities") }}/' + d.id;
  document.getElementById('ec-name-en').value = d.nameEn || '';
  document.getElementById('ec-name-ar').value = d.nameAr || '';
  document.getElementById('ec-gov-en').value  = d.govEn  || '';
  document.getElementById('ec-gov-ar').value  = d.govAr  || '';
  document.getElementById('ec-lat').value     = d.lat    || '';
  document.getElementById('ec-lng').value     = d.lng    || '';
  openModal('edit-city-modal');
}
</script>
@endsection
