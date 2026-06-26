@extends('user.layout')

@section('title', 'توثيق الهوية')

@section('styles')
<style>
.id-card {
  background: var(--bg-surface);
  border: 0.5px solid var(--border);
  border-radius: var(--radius-xl);
  padding: 28px;
  box-shadow: 0 2px 16px rgba(0,0,0,0.04);
}
.id-status-badge {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 5px 14px; border-radius: 24px;
  font-size: 12px; font-weight: 700;
}
.status-pending  { background:#FEF3C7; color:#92400E; border:1px solid #FDE68A; }
.status-approved { background:#D1FAE5; color:#065F46; border:1px solid #6EE7B7; }
.status-rejected { background:#FEE2E2; color:#991B1B; border:1px solid #FCA5A5; }
.upload-zone {
  border: 2px dashed rgba(0,0,0,0.15);
  border-radius: var(--radius-lg);
  padding: 28px 20px;
  text-align: center;
  cursor: pointer;
  transition: all .15s;
  position: relative;
  background: var(--bg-sunken);
}
.upload-zone:hover {
  border-color: var(--accent);
  background: var(--accent-bg);
}
.upload-zone input[type=file] {
  position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.upload-zone.has-file {
  border-color: var(--accent);
  background: var(--accent-bg);
}
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group label { font-size: 12px; font-weight: 600; color: var(--text-secondary); }
.form-control {
  padding: 9px 13px;
  border: 1px solid var(--border-md);
  border-radius: var(--radius-md);
  font-size: 13px; font-family: var(--font);
  background: var(--bg-sunken); outline: none;
  transition: border-color .15s;
}
.form-control:focus { border-color: var(--accent); background: #fff; }
.btn-submit {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 11px 24px; border-radius: var(--radius-md);
  background: var(--accent); color: #fff; border: none;
  font-size: 14px; font-weight: 700; cursor: pointer;
  font-family: var(--font); box-shadow: 0 4px 14px rgba(13,148,136,0.28);
  transition: all .15s;
}
.btn-submit:hover { background: var(--accent-hover); }
.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }
@media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div style="max-width:760px; margin:0 auto; padding: 0 16px 40px;">

  {{-- Alert messages --}}
  @if(session('success'))
  <div style="background:#D1FAE5; border:1px solid #6EE7B7; color:#065F46; border-radius:10px; padding:12px 16px; margin-bottom:20px; display:flex; align-items:center; gap:10px; font-size:13px; font-weight:600;">
    <i class="ti ti-circle-check" style="font-size:18px;"></i> {{ session('success') }}
  </div>
  @endif
  @if(session('error'))
  <div style="background:#FEE2E2; border:1px solid #FCA5A5; color:#991B1B; border-radius:10px; padding:12px 16px; margin-bottom:20px; display:flex; align-items:center; gap:10px; font-size:13px; font-weight:600;">
    <i class="ti ti-alert-triangle" style="font-size:18px;"></i> {{ session('error') }}
  </div>
  @endif

  {{-- Header --}}
  <div style="margin-bottom:24px;">
    <h1 style="font-size:22px; font-weight:800; color:var(--text-primary); margin:0 0 6px; letter-spacing:-0.4px;">توثيق الهوية</h1>
    <p style="font-size:13px; color:var(--text-muted); margin:0;">أرسل وثيقة هويتك لتفعيل ميزات إضافية على المنصة</p>
  </div>

  {{-- Current status (if has a verification) --}}
  @if($verification)
  <div class="id-card" style="margin-bottom:20px; border-right:3px solid
    @if($verification->status === 'approved') #10B981
    @elseif($verification->status === 'rejected') #EF4444
    @else #F59E0B @endif;">

    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:16px;">
      <div style="display:flex; align-items:center; gap:12px;">
        <div style="width:44px; height:44px; border-radius:12px; background:
          @if($verification->status === 'approved') #D1FAE5
          @elseif($verification->status === 'rejected') #FEE2E2
          @else #FEF3C7 @endif;
          display:flex; align-items:center; justify-content:center; font-size:20px; color:
          @if($verification->status === 'approved') #065F46
          @elseif($verification->status === 'rejected') #991B1B
          @else #92400E @endif;">
          <i class="ti @if($verification->status === 'approved') ti-circle-check @elseif($verification->status === 'rejected') ti-circle-x @else ti-clock @endif"></i>
        </div>
        <div>
          <div style="font-size:15px; font-weight:700; color:var(--text-primary);">طلب التوثيق</div>
          <div style="font-size:11px; color:var(--text-muted); margin-top:2px;">
            أُرسل {{ \Carbon\Carbon::parse($verification->created_at)->diffForHumans() }}
          </div>
        </div>
      </div>
      <span class="id-status-badge status-{{ $verification->status }}">
        <span style="width:5px; height:5px; border-radius:50%; background:
          @if($verification->status === 'approved') #10B981
          @elseif($verification->status === 'rejected') #EF4444
          @else #F59E0B @endif;"></span>
        @if($verification->status === 'approved') موثّق
        @elseif($verification->status === 'rejected') مرفوض
        @else قيد المراجعة @endif
      </span>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; font-size:12px; color:var(--text-secondary);">
      <div><span style="color:var(--text-muted);">الاسم على الوثيقة:</span><br><strong style="color:var(--text-primary); font-size:13px;">{{ $verification->full_name }}</strong></div>
      <div><span style="color:var(--text-muted);">رقم الهوية:</span><br><strong style="color:var(--text-primary); font-size:13px;">{{ $verification->id_number }}</strong></div>
    </div>

    @if($verification->rejection_reason)
    <div style="margin-top:14px; padding:10px 14px; background:#FEF2F2; border:1px solid #FECACA; border-radius:8px; font-size:12px; color:#991B1B; display:flex; gap:8px;">
      <i class="ti ti-alert-triangle" style="flex-shrink:0; margin-top:1px;"></i>
      <span><strong>سبب الرفض:</strong> {{ $verification->rejection_reason }}</span>
    </div>
    @endif

    @if($verification->status === 'approved')
    <div style="margin-top:14px; padding:10px 14px; background:#D1FAE5; border:1px solid #6EE7B7; border-radius:8px; font-size:12px; color:#065F46; display:flex; gap:8px;">
      <i class="ti ti-shield-check" style="flex-shrink:0; margin-top:1px;"></i>
      <span>هويتك موثّقة بنجاح. يمكنك الآن الاستفادة من كامل ميزات المنصة.</span>
    </div>
    @endif

    @if($verification->status === 'rejected')
    <p style="font-size:12px; color:var(--text-muted); margin:14px 0 0;">يمكنك إعادة إرسال الطلب بوثائق صحيحة.</p>
    @endif
  </div>
  @endif

  {{-- Form (show if no pending/approved) --}}
  @if(!$verification || $verification->status === 'rejected')
  <div class="id-card">
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:22px;">
      <div style="width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg,#0D9488,#2DD4BF); display:flex; align-items:center; justify-content:center; color:#fff; font-size:17px;">
        <i class="ti ti-id-badge"></i>
      </div>
      <div>
        <div style="font-size:14px; font-weight:700; color:var(--text-primary);">
          {{ $verification?->status === 'rejected' ? 'إعادة تقديم طلب التوثيق' : 'تقديم طلب توثيق الهوية' }}
        </div>
        <div style="font-size:11px; color:var(--text-muted);">الوثائق المقبولة: هوية وطنية أو جواز سفر</div>
      </div>
    </div>

    <form method="POST" action="{{ route('user.identity.store') }}" enctype="multipart/form-data" id="idForm">
      @csrf

      <div class="form-row" style="margin-bottom:16px;">
        <div class="form-group">
          <label>الاسم الكامل (كما في الوثيقة) *</label>
          <input type="text" name="full_name" class="form-control" required placeholder="محمد أحمد الأحمد" value="{{ old('full_name') }}" />
          @error('full_name')<span style="font-size:11px; color:#EF4444;">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label>رقم الهوية / الجواز *</label>
          <input type="text" name="id_number" class="form-control" required placeholder="0123456789" value="{{ old('id_number') }}" />
          @error('id_number')<span style="font-size:11px; color:#EF4444;">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="form-group" style="margin-bottom:16px;">
        <label>نوع الوثيقة *</label>
        <div style="display:flex; gap:10px;">
          <label style="display:flex; align-items:center; gap:8px; padding:9px 16px; border-radius:9px; cursor:pointer; border:1px solid var(--border-md); background:var(--bg-sunken); flex:1;" id="label-national">
            <input type="radio" name="id_type" value="national_id" required onchange="highlightIdType()" {{ old('id_type','national_id')==='national_id' ? 'checked' : '' }}>
            <i class="ti ti-id" style="color:var(--accent);"></i>
            <span style="font-size:13px; font-weight:600;">هوية وطنية</span>
          </label>
          <label style="display:flex; align-items:center; gap:8px; padding:9px 16px; border-radius:9px; cursor:pointer; border:1px solid var(--border-md); background:var(--bg-sunken); flex:1;" id="label-passport">
            <input type="radio" name="id_type" value="passport" onchange="highlightIdType()" {{ old('id_type')==='passport' ? 'checked' : '' }}>
            <i class="ti ti-license" style="color:var(--accent);"></i>
            <span style="font-size:13px; font-weight:600;">جواز سفر</span>
          </label>
        </div>
      </div>

      {{-- Upload zones --}}
      <div class="form-row" style="margin-bottom:16px;">
        <div class="form-group">
          <label>صورة الوجه الأمامي *</label>
          <div class="upload-zone" id="zone-front">
            <input type="file" name="front_image" accept="image/*" required onchange="previewUpload(this,'prev-front','zone-front')">
            <img id="prev-front" src="" alt="" style="display:none; max-height:90px; border-radius:6px; margin-bottom:8px; max-width:100%; object-fit:contain;">
            <div id="zone-front-label">
              <i class="ti ti-cloud-upload" style="font-size:26px; color:var(--accent); opacity:0.6; display:block; margin-bottom:6px;"></i>
              <div style="font-size:12px; font-weight:600; color:var(--text-secondary);">اضغط أو اسحب الصورة</div>
              <div style="font-size:11px; color:var(--text-muted); margin-top:3px;">PNG, JPG, WEBP — حتى 5MB</div>
            </div>
          </div>
          @error('front_image')<span style="font-size:11px; color:#EF4444;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
          <label>صورة الوجه الخلفي <span style="color:var(--text-muted); font-weight:400;">(اختياري)</span></label>
          <div class="upload-zone" id="zone-back">
            <input type="file" name="back_image" accept="image/*" onchange="previewUpload(this,'prev-back','zone-back')">
            <img id="prev-back" src="" alt="" style="display:none; max-height:90px; border-radius:6px; margin-bottom:8px; max-width:100%; object-fit:contain;">
            <div id="zone-back-label">
              <i class="ti ti-cloud-upload" style="font-size:26px; color:var(--accent); opacity:0.6; display:block; margin-bottom:6px;"></i>
              <div style="font-size:12px; font-weight:600; color:var(--text-secondary);">اضغط أو اسحب الصورة</div>
              <div style="font-size:11px; color:var(--text-muted); margin-top:3px;">PNG, JPG, WEBP — حتى 5MB</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Privacy note --}}
      <div style="padding:11px 14px; background:var(--bg-sunken); border-radius:9px; margin-bottom:20px; display:flex; gap:9px; font-size:11px; color:var(--text-muted);">
        <i class="ti ti-lock" style="flex-shrink:0; font-size:15px; margin-top:1px; color:var(--accent);"></i>
        <span>بياناتك ووثائقك محمية ومشفّرة. تستخدم فقط للتحقق من هويتك ولا تُشارك مع أطراف أخرى.</span>
      </div>

      <button type="submit" class="btn-submit" id="submitBtn">
        <i class="ti ti-send"></i> إرسال طلب التوثيق
      </button>
    </form>
  </div>

  @elseif($verification->status === 'pending')
  <div class="id-card" style="text-align:center; padding:48px 24px;">
    <div style="width:72px; height:72px; border-radius:50%; background:#FEF3C7; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:32px; color:#F59E0B;">
      <i class="ti ti-clock"></i>
    </div>
    <div style="font-size:17px; font-weight:700; color:var(--text-primary); margin-bottom:8px;">طلبك قيد المراجعة</div>
    <p style="font-size:13px; color:var(--text-muted); max-width:340px; margin:0 auto;">سيتم مراجعة طلبك خلال 24-48 ساعة. ستصلك إشعار فور اتخاذ القرار.</p>
  </div>
  @endif

</div>
@endsection

@section('scripts')
<script>
function previewUpload(input, previewId, zoneId) {
  const file = input.files[0];
  const preview = document.getElementById(previewId);
  const zone = document.getElementById(zoneId);
  const label = document.getElementById(zoneId + '-label');
  if (file) {
    const reader = new FileReader();
    reader.onload = e => {
      preview.src = e.target.result;
      preview.style.display = 'block';
      if (label) label.style.display = 'none';
      zone.classList.add('has-file');
    };
    reader.readAsDataURL(file);
  }
}

function highlightIdType() {
  const radios = document.querySelectorAll('input[name="id_type"]');
  radios.forEach(r => {
    const lbl = r.closest('label');
    if (r.checked) {
      lbl.style.borderColor = 'var(--accent)';
      lbl.style.background = 'var(--accent-bg)';
    } else {
      lbl.style.borderColor = 'var(--border-md)';
      lbl.style.background = 'var(--bg-sunken)';
    }
  });
}

document.getElementById('idForm')?.addEventListener('submit', function() {
  const btn = document.getElementById('submitBtn');
  btn.disabled = true;
  btn.innerHTML = '<i class="ti ti-loader" style="animation:spin 1s linear infinite;"></i> جارٍ الإرسال...';
});

// Highlight initial radio
highlightIdType();
</script>
<style>
@keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
</style>
@endsection
