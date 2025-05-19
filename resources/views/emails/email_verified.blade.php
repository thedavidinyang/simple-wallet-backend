@extends('layouts.email')

@section('content')
    <p style="font-size: 18px; font-weight: 500; color: #010b14;">🚀 Email Verification Succesfull</p>
    <div class="divider"></div>
    <p style="font-size: 16px; font-weight: 400; color: #010b14;">Hi {{ $user['first_name'] }} 👋,</p>
    <p>You have succesfully verified your email</p>
    <p>welcome to the {{ env('APP_NAME') }} family!</p>
    <p class="bold"></p>
    <div class="divider"></div>
    <p>
        If you this is not your doing, we strongly advise you notify us as soon as possible via
        <a href="{{ env('SUPPORT_EMAIL') }}" target="_blank">{{ env('SUPPORT_EMAIL') }}</a>.
    </p>
    <p>Thank you.</p>
@endsection
