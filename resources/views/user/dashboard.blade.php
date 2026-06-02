@extends('user.layout')

@section('title', 'الرئيسية')

@section('content')

@php $user = Auth::guard('users')->user(); @endphp

<div>
  <div class="page-title">مرحباً، {{ $user->first_name }} 👋</div>
  <div class="page-sub">{{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}</div>
</div>

{{-- Profile Card --}}
<div class="card">
  <div class="card-head">
    <div class="card-title">ملخص حسابك</div>
    <span class="badge {{ $user->status === 'active' ? 'active' : 'pending' }}">
      {{ $user->status === 'active' ? 'نشط' : 'موقوف' }}
    </span>
  </div>
  <div class="card-body">
    <div class="info-row">
      <span class="info-label"><i class="ti ti-user"></i> الاسم الكامل</span>
      <span class="info-value">{{ $user->first_name }} {{ $user->last_name }}</span>
    </div>
    <div class="info-row">
      <span class="info-label"><i class="ti ti-mail"></i> البريد الإلكتروني</span>
      <span class="info-value">{{ $user->email }}</span>
    </div>
    <div class="info-row">
      <span class="info-label"><i class="ti ti-phone"></i> الهاتف</span>
      <span class="info-value">{{ $user->phone ?? '—' }}</span>
    </div>
    <div class="info-row">
      <span class="info-label"><i class="ti ti-map-pin"></i> المدينة</span>
      <span class="info-value">{{ $user->city ?? '—' }}</span>
    </div>
    <div class="info-row">
      <span class="info-label"><i class="ti ti-gender-bigender"></i> الجنس</span>
      <span class="info-value">{{ $user->gender === 'male' ? 'ذكر' : 'أنثى' }}</span>
    </div>
    @if($user->birthdate)
    <div class="info-row">
      <span class="info-label"><i class="ti ti-calendar"></i> تاريخ الميلاد</span>
      <span class="info-value">{{ \Carbon\Carbon::parse($user->birthdate)->format('Y/m/d') }}</span>
    </div>
    @endif
  </div>
</div>

@endsection
