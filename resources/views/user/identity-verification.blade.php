@extends('user.layout')

@section('title', 'توثيق الهوية')

@section('styles')
<style>
:root { --acc:#0D9488; --acc2:#0F766E; }

/* ── inputs ── */
.id-input {
  width:100%; padding:11px 14px; border:1.5px solid #E2E8F0; border-radius:11px;
  font-size:13px; font-family:inherit; background:#F8FAFC; outline:none;
  color:#0F172A; box-sizing:border-box; transition:border-color .15s, background .15s; direction:rtl;
}
.id-input:focus { border-color:var(--acc); background:#fff; }

/* ── doc-type radios ── */
.doc-radio { display:none; }
.doc-label {
  display:flex; align-items:center; gap:10px; padding:13px 16px;
  border-radius:13px; cursor:pointer; border:1.5px solid #E2E8F0;
  background:#F8FAFC; flex:1; transition:all .15s;
  font-size:13.5px; font-weight:600; color:#475569;
}
.doc-label:hover { border-color:var(--acc); background:#F0FDFA; }
.doc-radio:checked + .doc-label {
  border-color:var(--acc); background:linear-gradient(135deg,#F0FDFA,#ECFDF5);
  color:var(--acc); box-shadow:0 2px 12px rgba(13,148,136,0.13);
}
.doc-radio:checked + .doc-label .doc-dot { background:var(--acc); }
.doc-dot {
  width:16px; height:16px; border-radius:50%; border:2px solid #CBD5E1;
  flex-shrink:0; transition:all .15s; background:#fff; margin-right:auto;
}

/* ── upload zones ── */
.upload-zone {
  border:2px dashed #CBD5E1; border-radius:14px; cursor:pointer;
  transition:all .18s; position:relative; background:#FAFAFA;
  min-height:140px; display:flex; flex-direction:column;
  align-items:center; justify-content:center; gap:6px; text-align:center; padding:20px;
  overflow:hidden;
}
.upload-zone:hover { border-color:var(--acc); background:#F0FDFA; }
.upload-zone.has-file { border-color:var(--acc); background:#F0FDFA; border-style:solid; }
.upload-zone input[type=file] {
  position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%;
}

/* ── step tracker ── */
.step-wrap { display:flex; align-items:center; gap:0; }
.step-item  { display:flex; align-items:center; flex:1; gap:0; }
.step-icon  { width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.step-line  { flex:1; height:2px; border-radius:2px; }
.step-label { font-size:11px; font-weight:700; margin-top:5px; text-align:center; }
.step-sub   { font-size:10px; color:#CBD5E1; margin-top:1px; text-align:center; }

/* ── info tip ── */
.tip {
  display:flex; align-items:flex-start; gap:10px; padding:12px 14px;
  background:#F8FAFC; border:1px solid #E2E8F0; border-radius:11px;
  font-size:12px; color:#475569; line-height:1.6;
}

/* ── submit btn ── */
.btn-submit {
  display:inline-flex; align-items:center; gap:9px; padding:12px 28px;
  border-radius:12px; background:linear-gradient(135deg,var(--acc),var(--acc2));
  color:#fff; border:none; font-size:14px; font-weight:700; cursor:pointer;
  font-family:inherit; box-shadow:0 5px 18px rgba(13,148,136,0.32);
  transition:all .15s;
}
.btn-submit:hover    { box-shadow:0 8px 24px rgba(13,148,136,0.42); transform:translateY(-2px); }
.btn-submit:disabled { opacity:.65; cursor:not-allowed; transform:none; }

@keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
@keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

@media(max-width:700px){
  .two-col { flex-direction:column !important; }
  .sidebar  { width:100% !important; }
  .form-2   { grid-template-columns:1fr !important; }
  .doc-row  { flex-direction:column !important; }
}
</style>
@endsection

@section('content')
@php
  $status   = $verification?->status ?? 'none';
  $showForm = !$verification || $status === 'rejected';
@endphp

<div style="max-width:960px; margin:0 auto; padding:0 20px 60px; animation:fadeUp .3s ease;">

  {{-- Flash --}}
  @if(session('success'))
  <div style="background:#ECFDF5; border:1px solid #6EE7B7; color:#065F46; border-radius:13px; padding:13px 18px; margin-bottom:22px; display:flex; align-items:center; gap:10px; font-size:13px; font-weight:700; box-shadow:0 2px 12px rgba(16,185,129,0.12);">
    <i class="ti ti-circle-check" style="font-size:19px; color:#10B981; flex-shrink:0;"></i> {{ session('success') }}
  </div>
  @endif
  @if(session('error'))
  <div style="background:#FEF2F2; border:1px solid #FCA5A5; color:#991B1B; border-radius:13px; padding:13px 18px; margin-bottom:22px; display:flex; align-items:center; gap:10px; font-size:13px; font-weight:700;">
    <i class="ti ti-alert-triangle" style="font-size:19px; flex-shrink:0;"></i> {{ session('error') }}
  </div>
  @endif

  {{-- ── Page title ── --}}
  <div style="display:flex; align-items:center; gap:14px; margin-bottom:28px;">
    <div style="width:50px; height:50px; border-radius:15px; background:linear-gradient(135deg,var(--acc),var(--acc2)); display:flex; align-items:center; justify-content:center; box-shadow:0 6px 18px rgba(13,148,136,0.32); flex-shrink:0;">
      <i class="ti ti-id-badge" style="font-size:24px; color:#fff;"></i>
    </div>
    <div>
      <h1 style="font-size:22px; font-weight:800; color:#0F172A; margin:0; letter-spacing:-.3px;">توثيق الهوية</h1>
      <p  style="font-size:13px; color:#94A3B8; margin:3px 0 0;">أرسل وثيقة هويتك لتفعيل ميزات إضافية على المنصة</p>
    </div>
  </div>

  {{-- ── Step tracker ── --}}
  @php
    $steps = [
      ['icon'=>'ti-file-text',    'label'=>'إرسال الطلب',  'done'=> in_array($status,['pending','approved','rejected'])],
      ['icon'=>'ti-search',       'label'=>'المراجعة',       'done'=> in_array($status,['approved','rejected'])],
      ['icon'=>'ti-shield-check', 'label'=>'التوثيق',       'done'=> $status==='approved'],
    ];
  @endphp
  <div style="background:#fff; border-radius:16px; border:1px solid #F0F4F8; box-shadow:0 2px 10px rgba(0,0,0,0.04); padding:18px 24px; margin-bottom:28px;">
    <div style="display:flex; align-items:flex-start;">
      @foreach($steps as $i => $step)
        @php $done = $step['done']; @endphp
        <div style="display:flex; flex-direction:column; align-items:center; flex:1; position:relative;">
          <div style="display:flex; align-items:center; width:100%; justify-content:center; position:relative;">
            @if($i > 0)
            <div style="position:absolute; right:50%; top:50%; transform:translateY(-50%); width:50%; height:2px; background:{{ $steps[$i-1]['done'] ? 'var(--acc)' : '#E2E8F0' }};"></div>
            @endif
            @if($i < count($steps)-1)
            <div style="position:absolute; left:50%; top:50%; transform:translateY(-50%); width:50%; height:2px; background:{{ $done ? 'var(--acc)' : '#E2E8F0' }};"></div>
            @endif
            <div style="width:44px; height:44px; border-radius:50%; z-index:1; position:relative; background:{{ $done ? 'linear-gradient(135deg,var(--acc),var(--acc2))' : '#F1F5F9' }}; display:flex; align-items:center; justify-content:center; box-shadow:{{ $done ? '0 4px 14px rgba(13,148,136,0.3)' : 'none' }}; border:{{ $done ? 'none' : '2px solid #E2E8F0' }};">
              <i class="ti {{ $step['icon'] }}" style="font-size:18px; color:{{ $done ? '#fff' : '#94A3B8' }};"></i>
            </div>
          </div>
          <div style="margin-top:8px; text-align:center;">
            <div style="font-size:11.5px; font-weight:700; color:{{ $done ? 'var(--acc)' : '#94A3B8' }};">{{ $step['label'] }}</div>
            <div style="font-size:10px; color:#CBD5E1; margin-top:1px;">خطوة {{ $i+1 }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  {{-- ── Two-column layout ── --}}
  <div class="two-col" style="display:flex; gap:24px; align-items:flex-start;">

    {{-- ── Main column ── --}}
    <div style="flex:1; min-width:0; display:flex; flex-direction:column; gap:20px;">

      {{-- ── Status card (if submitted) ── --}}
      @if($verification)
      @php
        $sc = [
          'approved' => ['bg'=>'#ECFDF5','border'=>'#6EE7B7','stripe'=>'linear-gradient(90deg,#10B981,#34D399)','iconBg'=>'#D1FAE5','iconColor'=>'#065F46','icon'=>'ti-shield-check','label'=>'موثّق'],
          'rejected' => ['bg'=>'#FEF2F2','border'=>'#FCA5A5','stripe'=>'linear-gradient(90deg,#EF4444,#F87171)',  'iconBg'=>'#FEE2E2','iconColor'=>'#991B1B','icon'=>'ti-shield-x',    'label'=>'مرفوض'],
          'pending'  => ['bg'=>'#FFFBEB','border'=>'#FCD34D','stripe'=>'linear-gradient(90deg,#F59E0B,#FBBF24)', 'iconBg'=>'#FEF3C7','iconColor'=>'#92400E','icon'=>'ti-clock',        'label'=>'قيد المراجعة'],
        ][$status] ?? ['bg'=>'#FFFBEB','border'=>'#FCD34D','stripe'=>'linear-gradient(90deg,#F59E0B,#FBBF24)','iconBg'=>'#FEF3C7','iconColor'=>'#92400E','icon'=>'ti-clock','label'=>'قيد المراجعة'];
      @endphp
      <div style="background:#fff; border-radius:20px; border:1px solid {{ $sc['border'] }}; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.06);">
        <div style="height:5px; background:{{ $sc['stripe'] }};"></div>
        <div style="padding:22px 26px;">

          {{-- Header --}}
          <div style="display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap; margin-bottom:20px;">
            <div style="display:flex; align-items:center; gap:14px;">
              <div style="width:54px; height:54px; border-radius:15px; background:{{ $sc['iconBg'] }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="ti {{ $sc['icon'] }}" style="font-size:26px; color:{{ $sc['iconColor'] }};"></i>
              </div>
              <div>
                <div style="font-size:16px; font-weight:800; color:#0F172A;">طلب التوثيق</div>
                <div style="font-size:12px; color:#94A3B8; margin-top:3px;">
                  <i class="ti ti-clock" style="font-size:11px;"></i>
                  أُرسل {{ \Carbon\Carbon::parse($verification->created_at)->diffForHumans() }}
                </div>
              </div>
            </div>
            <span style="display:inline-flex; align-items:center; gap:6px; padding:7px 16px; border-radius:24px; font-size:12px; font-weight:800; background:{{ $sc['bg'] }}; color:{{ $sc['iconColor'] }}; border:1.5px solid {{ $sc['border'] }};">
              <span style="width:7px; height:7px; border-radius:50%; background:{{ $sc['iconColor'] }};"></span>
              {{ $sc['label'] }}
            </span>
          </div>

          {{-- Data grid --}}
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;" class="form-2">
            @foreach([['الاسم على الوثيقة','ti-user',$verification->full_name],['رقم الهوية','ti-hash',$verification->id_number]] as [$lbl,$ico,$val])
            <div style="padding:13px 16px; background:#F8FAFC; border-radius:12px; border:1px solid #F0F4F8;">
              <div style="font-size:11px; color:#94A3B8; font-weight:600; display:flex; align-items:center; gap:5px; margin-bottom:5px;">
                <i class="ti {{ $ico }}" style="color:var(--acc); font-size:12px;"></i> {{ $lbl }}
              </div>
              <div style="font-size:15px; font-weight:800; color:#0F172A;">{{ $val }}</div>
            </div>
            @endforeach
            <div style="padding:13px 16px; background:#F8FAFC; border-radius:12px; border:1px solid #F0F4F8;">
              <div style="font-size:11px; color:#94A3B8; font-weight:600; display:flex; align-items:center; gap:5px; margin-bottom:5px;">
                <i class="ti ti-file-certificate" style="color:var(--acc); font-size:12px;"></i> نوع الوثيقة
              </div>
              <div style="font-size:15px; font-weight:800; color:#0F172A;">{{ $verification->id_type === 'passport' ? 'جواز سفر' : 'هوية وطنية' }}</div>
            </div>
            <div style="padding:13px 16px; background:#F8FAFC; border-radius:12px; border:1px solid #F0F4F8;">
              <div style="font-size:11px; color:#94A3B8; font-weight:600; display:flex; align-items:center; gap:5px; margin-bottom:5px;">
                <i class="ti ti-calendar" style="color:var(--acc); font-size:12px;"></i> تاريخ الإرسال
              </div>
              <div style="font-size:15px; font-weight:800; color:#0F172A;">{{ \Carbon\Carbon::parse($verification->created_at)->format('Y/m/d') }}</div>
            </div>
          </div>

          {{-- Uploaded images --}}
          @if($verification->front_image || $verification->back_image || $verification->selfie_image)
          <div style="margin-top:16px; display:flex; gap:10px; flex-wrap:wrap;">
            @foreach([['front_image','وجه الهوية'],['back_image','ظهر الهوية'],['selfie_image','صورة سيلفي']] as [$field,$lbl])
              @if($verification->$field)
              <div style="flex:1; min-width:90px; max-width:140px;">
                <a href="/storage/{{ $verification->$field }}" target="_blank" style="display:block; text-decoration:none;">
                  <div style="border-radius:10px; overflow:hidden; border:1px solid #E2E8F0; background:#F8FAFC; height:80px; display:flex; align-items:center; justify-content:center;">
                    <img src="/storage/{{ $verification->$field }}" alt="{{ $lbl }}" style="width:100%; height:100%; object-fit:cover; display:block;">
                  </div>
                  <div style="font-size:10.5px; color:#64748B; font-weight:600; text-align:center; margin-top:5px;">{{ $lbl }}</div>
                </a>
              </div>
              @endif
            @endforeach
          </div>
          @endif

          {{-- Rejection reason --}}
          @if($verification->rejection_reason)
          <div style="margin-top:16px; padding:13px 16px; background:#FEF2F2; border:1px solid #FCA5A5; border-radius:12px; font-size:13px; color:#991B1B; display:flex; gap:10px; align-items:flex-start;">
            <i class="ti ti-alert-triangle" style="flex-shrink:0; font-size:18px; margin-top:1px;"></i>
            <div><strong>سبب الرفض:</strong> {{ $verification->rejection_reason }}</div>
          </div>
          @endif

          {{-- Pending notice --}}
          @if($status === 'pending')
          <div style="margin-top:16px; padding:13px 16px; background:#FFFBEB; border:1px solid #FDE68A; border-radius:12px; font-size:13px; color:#92400E; display:flex; gap:10px; align-items:center;">
            <i class="ti ti-info-circle" style="flex-shrink:0; font-size:18px;"></i>
            <div>يتم مراجعة طلبك خلال <strong>24 – 48 ساعة</strong>. ستصلك إشعار فور اتخاذ القرار.</div>
          </div>
          @endif

          {{-- Approved notice --}}
          @if($status === 'approved')
          <div style="margin-top:16px; padding:13px 16px; background:#ECFDF5; border:1px solid #6EE7B7; border-radius:12px; font-size:13px; color:#065F46; display:flex; gap:10px; align-items:center;">
            <i class="ti ti-shield-check" style="flex-shrink:0; font-size:20px;"></i>
            <div>هويتك موثّقة بنجاح. يظهر حسابك الآن بشارة الثقة الخضراء لجميع المستخدمين.</div>
          </div>
          @endif

        </div>
      </div>
      @endif

      {{-- ── Form ── --}}
      @if($showForm)
      <div style="background:#fff; border-radius:20px; border:1px solid #F0F4F8; box-shadow:0 4px 20px rgba(0,0,0,0.05); overflow:hidden;">

        {{-- Form header --}}
        <div style="padding:20px 26px; background:linear-gradient(135deg,#F0FDFA 0%,#ECFDF5 100%); border-bottom:1px solid rgba(13,148,136,0.12); display:flex; align-items:center; gap:14px;">
          <div style="width:44px; height:44px; border-radius:13px; background:linear-gradient(135deg,var(--acc),var(--acc2)); display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; box-shadow:0 4px 14px rgba(13,148,136,0.3); flex-shrink:0;">
            <i class="ti ti-pencil"></i>
          </div>
          <div>
            <div style="font-size:15px; font-weight:800; color:#0F172A;">{{ $status === 'rejected' ? 'إعادة تقديم طلب التوثيق' : 'تقديم طلب توثيق الهوية' }}</div>
            <div style="font-size:12px; color:#047857; margin-top:2px;">المستندات المقبولة: هوية وطنية أو جواز سفر</div>
          </div>
        </div>

        <div style="padding:26px 28px;">
          <form method="POST" action="{{ route('user.identity.store') }}" enctype="multipart/form-data" id="idForm">
            @csrf

            {{-- Name + ID --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;" class="form-2">
              <div>
                <div style="font-size:12px; font-weight:700; color:#374151; margin-bottom:7px; display:flex; align-items:center; gap:5px;">
                  <i class="ti ti-user" style="color:var(--acc); font-size:13px;"></i> الاسم الكامل (كما في الوثيقة) <span style="color:#EF4444;">*</span>
                </div>
                <input type="text" name="full_name" class="id-input" required placeholder="محمد أحمد الأحمد" value="{{ old('full_name') }}" />
                @error('full_name')<p style="font-size:11px; color:#EF4444; margin-top:5px; display:flex; align-items:center; gap:4px;"><i class="ti ti-alert-circle" style="font-size:11px;"></i>{{ $message }}</p>@enderror
              </div>
              <div>
                <div style="font-size:12px; font-weight:700; color:#374151; margin-bottom:7px; display:flex; align-items:center; gap:5px;">
                  <i class="ti ti-hash" style="color:var(--acc); font-size:13px;"></i> رقم الهوية / الجواز <span style="color:#EF4444;">*</span>
                </div>
                <input type="text" name="id_number" class="id-input" required placeholder="0123456789" value="{{ old('id_number') }}" />
                @error('id_number')<p style="font-size:11px; color:#EF4444; margin-top:5px; display:flex; align-items:center; gap:4px;"><i class="ti ti-alert-circle" style="font-size:11px;"></i>{{ $message }}</p>@enderror
              </div>
            </div>

            {{-- Doc type --}}
            <div style="margin-bottom:20px;">
              <div style="font-size:12px; font-weight:700; color:#374151; margin-bottom:8px; display:flex; align-items:center; gap:5px;">
                <i class="ti ti-file-certificate" style="color:var(--acc); font-size:13px;"></i> نوع الوثيقة <span style="color:#EF4444;">*</span>
              </div>
              <div style="display:flex; gap:12px;" class="doc-row">
                <input type="radio" name="id_type" value="national_id" id="type-national" class="doc-radio" required {{ old('id_type','national_id')==='national_id'?'checked':'' }}>
                <label for="type-national" class="doc-label">
                  <div style="width:38px; height:38px; border-radius:11px; background:rgba(13,148,136,.10); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="ti ti-id" style="font-size:19px; color:var(--acc);"></i>
                  </div>
                  <div>
                    <div style="font-size:13.5px; font-weight:700;">هوية وطنية</div>
                    <div style="font-size:11px; color:#94A3B8; margin-top:1px;">National ID</div>
                  </div>
                  <div class="doc-dot"></div>
                </label>

                <input type="radio" name="id_type" value="passport" id="type-passport" class="doc-radio" {{ old('id_type')==='passport'?'checked':'' }}>
                <label for="type-passport" class="doc-label">
                  <div style="width:38px; height:38px; border-radius:11px; background:rgba(13,148,136,.10); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="ti ti-license" style="font-size:19px; color:var(--acc);"></i>
                  </div>
                  <div>
                    <div style="font-size:13.5px; font-weight:700;">جواز سفر</div>
                    <div style="font-size:11px; color:#94A3B8; margin-top:1px;">Passport</div>
                  </div>
                  <div class="doc-dot"></div>
                </label>
              </div>
            </div>

            {{-- Upload zones --}}
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; margin-bottom:20px;" class="form-2">
              @foreach([
                ['front_image','الوجه الأمامي','ti-credit-card',true],
                ['back_image', 'الوجه الخلفي', 'ti-credit-card',false],
                ['selfie_image','صورة سيلفي',  'ti-camera',     false],
              ] as [$name,$label,$icon,$required])
              <div>
                <div style="font-size:12px; font-weight:700; color:#374151; margin-bottom:7px; display:flex; align-items:center; gap:5px;">
                  <i class="ti {{ $icon }}" style="color:var(--acc); font-size:12px;"></i>
                  {{ $label }}
                  @if($required) <span style="color:#EF4444;">*</span>
                  @else <span style="font-size:10.5px; color:#94A3B8; font-weight:400;">(اختياري)</span>
                  @endif
                </div>
                <div class="upload-zone" id="zone-{{ $name }}">
                  <input type="file" name="{{ $name }}" accept="image/*" {{ $required?'required':'' }}
                    onchange="previewUpload(this,'prev-{{ $name }}','zone-{{ $name }}','lbl-{{ $name }}')">
                  <img id="prev-{{ $name }}" src="" alt="" style="display:none; max-height:90px; border-radius:9px; max-width:100%; object-fit:contain;">
                  <div id="lbl-{{ $name }}">
                    <div style="width:44px; height:44px; border-radius:12px; background:rgba(13,148,136,0.1); display:flex; align-items:center; justify-content:center; margin:0 auto 8px;">
                      <i class="ti ti-cloud-upload" style="font-size:22px; color:var(--acc);"></i>
                    </div>
                    <div style="font-size:12.5px; font-weight:700; color:#0F172A;">اضغط أو اسحب</div>
                    <div style="font-size:11px; color:#94A3B8; margin-top:3px;">PNG, JPG — 5MB</div>
                  </div>
                </div>
                @error($name)<p style="font-size:11px; color:#EF4444; margin-top:5px;">{{ $message }}</p>@enderror
              </div>
              @endforeach
            </div>

            {{-- Privacy note --}}
            <div style="padding:13px 16px; background:linear-gradient(135deg,#F0FDFA,#ECFDF5); border:1px solid rgba(13,148,136,.15); border-radius:12px; margin-bottom:24px; display:flex; gap:12px; align-items:center;">
              <div style="width:38px; height:38px; border-radius:10px; background:rgba(13,148,136,.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="ti ti-lock" style="font-size:18px; color:var(--acc);"></i>
              </div>
              <div>
                <div style="font-size:12px; font-weight:700; color:#0F172A; margin-bottom:2px;">بياناتك محمية ومشفّرة</div>
                <div style="font-size:11.5px; color:#047857;">تُستخدم وثائقك فقط للتحقق من هويتك ولا تُشارَك مع أي طرف آخر</div>
              </div>
            </div>

            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
              <a href="/user/profile" style="display:inline-flex; align-items:center; gap:7px; padding:11px 22px; border-radius:12px; border:1.5px solid #E2E8F0; background:#F8FAFC; color:#475569; font-size:13px; font-weight:700; text-decoration:none; font-family:inherit; transition:all .15s;"
                onmouseover="this.style.borderColor='#CBD5E1';this.style.background='#F1F5F9'"
                onmouseout ="this.style.borderColor='#E2E8F0';this.style.background='#F8FAFC'">
                <i class="ti ti-arrow-right"></i> العودة للملف الشخصي
              </a>
              <button type="submit" class="btn-submit" id="submitBtn">
                <i class="ti ti-send"></i> إرسال طلب التوثيق
              </button>
            </div>
          </form>
        </div>
      </div>
      @endif

    </div>{{-- end main --}}

    {{-- ── Sidebar ── --}}
    <div class="sidebar" style="width:264px; flex-shrink:0; display:flex; flex-direction:column; gap:16px;">

      {{-- Why verify? --}}
      <div style="background:#fff; border-radius:18px; border:1px solid #F0F4F8; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <div style="padding:14px 18px; background:linear-gradient(135deg,#F0FDFA,#ECFDF5); border-bottom:1px solid rgba(13,148,136,0.1); display:flex; align-items:center; gap:9px;">
          <i class="ti ti-star" style="font-size:16px; color:var(--acc);"></i>
          <span style="font-size:13px; font-weight:800; color:#0F172A;">لماذا توثيق هويتك؟</span>
        </div>
        <div style="padding:14px 18px; display:flex; flex-direction:column; gap:10px;">
          @foreach([
            ['ti-shield-check','شارة الثقة الخضراء على ملفك الشخصي'],
            ['ti-star',        'أولوية الظهور في نتائج البحث'],
            ['ti-users',       'ثقة أعلى من العملاء والمزودين'],
            ['ti-lock',        'حماية إضافية لحسابك'],
          ] as [$ico,$txt])
          <div style="display:flex; align-items:flex-start; gap:9px;">
            <div style="width:28px; height:28px; border-radius:8px; background:#F0FDFA; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px;">
              <i class="ti {{ $ico }}" style="font-size:13px; color:var(--acc);"></i>
            </div>
            <span style="font-size:12px; color:#475569; line-height:1.6; padding-top:5px;">{{ $txt }}</span>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Accepted docs --}}
      <div style="background:#fff; border-radius:18px; border:1px solid #F0F4F8; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <div style="padding:14px 18px; background:linear-gradient(135deg,#F0FDFA,#ECFDF5); border-bottom:1px solid rgba(13,148,136,0.1); display:flex; align-items:center; gap:9px;">
          <i class="ti ti-file-check" style="font-size:16px; color:var(--acc);"></i>
          <span style="font-size:13px; font-weight:800; color:#0F172A;">الوثائق المقبولة</span>
        </div>
        <div style="padding:14px 18px; display:flex; flex-direction:column; gap:9px;">
          @foreach([
            ['ti-id',        'الهوية الوطنية',      'يُطلب صورة الوجهين'],
            ['ti-license',   'جواز السفر',          'صفحة البيانات الرئيسية'],
          ] as [$ico,$title,$sub])
          <div style="display:flex; align-items:center; gap:10px; padding:10px 12px; background:#F8FAFC; border-radius:11px; border:1px solid #F0F4F8;">
            <i class="ti {{ $ico }}" style="font-size:20px; color:var(--acc); flex-shrink:0;"></i>
            <div>
              <div style="font-size:12.5px; font-weight:700; color:#0F172A;">{{ $title }}</div>
              <div style="font-size:11px; color:#94A3B8; margin-top:1px;">{{ $sub }}</div>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Tips --}}
      <div style="background:#fff; border-radius:18px; border:1px solid #F0F4F8; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <div style="padding:14px 18px; background:linear-gradient(135deg,#FFFBEB,#FEF3C7); border-bottom:1px solid rgba(245,158,11,0.12); display:flex; align-items:center; gap:9px;">
          <i class="ti ti-bulb" style="font-size:16px; color:#D97706;"></i>
          <span style="font-size:13px; font-weight:800; color:#0F172A;">نصائح للصورة</span>
        </div>
        <div style="padding:14px 18px; display:flex; flex-direction:column; gap:9px;">
          @foreach([
            'تأكد أن الصورة واضحة وغير ضبابية',
            'تجنب الإضاءة الساطعة أو الظلال',
            'احرص أن تكون جميع البيانات مقروءة',
            'لا تقصّ أي جزء من الوثيقة',
          ] as $tip)
          <div style="display:flex; align-items:flex-start; gap:8px;">
            <i class="ti ti-point-filled" style="font-size:12px; color:#D97706; margin-top:4px; flex-shrink:0;"></i>
            <span style="font-size:12px; color:#475569; line-height:1.6;">{{ $tip }}</span>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Need help? --}}
      <div style="padding:16px; background:linear-gradient(135deg,var(--acc),var(--acc2)); border-radius:16px; text-align:center; box-shadow:0 4px 16px rgba(13,148,136,0.25);">
        <i class="ti ti-headset" style="font-size:28px; color:rgba(255,255,255,0.8); display:block; margin-bottom:8px;"></i>
        <div style="font-size:13px; font-weight:800; color:#fff; margin-bottom:4px;">تحتاج مساعدة؟</div>
        <div style="font-size:11.5px; color:rgba(255,255,255,0.7); margin-bottom:12px;">فريق الدعم متاح لمساعدتك</div>
        <a href="/user/chat" style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3); border-radius:9px; color:#fff; font-size:12px; font-weight:700; text-decoration:none; backdrop-filter:blur(4px);">
          <i class="ti ti-message-circle"></i> تواصل معنا
        </a>
      </div>

    </div>{{-- end sidebar --}}

  </div>

</div>
@endsection

@section('scripts')
<script>
function previewUpload(input, previewId, zoneId, lblId) {
  const file = input.files[0];
  if (!file) return;
  const preview = document.getElementById(previewId);
  const zone    = document.getElementById(zoneId);
  const lbl     = document.getElementById(lblId);
  const reader  = new FileReader();
  reader.onload = e => {
    preview.src = e.target.result;
    preview.style.display = 'block';
    if (lbl) lbl.style.display = 'none';
    zone.classList.add('has-file');
  };
  reader.readAsDataURL(file);
}

document.getElementById('idForm')?.addEventListener('submit', function() {
  const btn = document.getElementById('submitBtn');
  btn.disabled = true;
  btn.innerHTML = '<i class="ti ti-loader-2" style="animation:spin 1s linear infinite; display:inline-block; font-size:14px;"></i> جارٍ الإرسال...';
});
</script>
@endsection
