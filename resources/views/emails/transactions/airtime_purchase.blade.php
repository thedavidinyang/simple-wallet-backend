@extends('layouts.email')

@section('content')
    <p style="font-size: 18px; font-weight: 500; color: #010b14;">🚀  {{$title}}</p>
    <div class="divider"></div>
    <p style="font-size: 16px; font-weight: 400; color: #010b14;">Hi {{ $user->profile->first_name }} 👋,</p>
    <p>NGN {{ $data->amount }} just left your {{ env('APP_NAME') }} Account </p>
    <p style="font-size: 16px; font-weight: 800; color: #010b14;">Here is what you need to know:</p>
    <br>
    <p><b>Reference No:</b> {{ $data->transaction_reference }} </p>
    <p><b>Network :</b> {{ $data->billTransaction->airtimeNetwork->network }} </p>
    <p><b>Phone Number :</b>{{ $request_payload['phone_number'] }} </p>
    <p><b>Naration :</b> {{ $data->naration }} </p>
    <p><b>Amount :</b> NGN {{ $data->amount }} </p>
    <p><b>Transaction Date:</b> {{ $data->created_at }} </p>

    <br>
    <p>
        If you this is not your doing, we strongly advise you notify us as soon as possible via
        <a href="{{ env('SUPPORT_EMAIL') }}" target="_blank">{{ env('SUPPORT_EMAIL') }}</a>.
    </p>
    <p>Thank you.</p>
@endsection
