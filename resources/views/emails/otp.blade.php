@extends('layouts.email')

@section('content')
    <p style="font-size: 18px; font-weight: 500; color: #010b14;">🚀 OTP Verification</p>
    <div class="divider"></div>
    <p style="font-size: 16px; font-weight: 400; color: #010b14;">Hi {{ $user['first_name'] }} 👋,</p>
    <p>Verify your email using the one-time-password (OTP) below:</p>
    <p class="otp">{{ $otp }}</p>
    <p>The OTP will expire in 15 minutes.</p>
    <div class="divider"></div>
    <p>
        If you did not initiate this OTP request, we strongly advise you notify us as soon as possible via
        <a href="{{ env('SUPPORT_EMAIL') }}" target="_blank">{{ env('SUPPORT_EMAIL') }}</a>.
    </p>
    <p>Thank you.</p>
    </div>
@endsection
