@extends('layouts.email')

@section('content')
    <p style="font-size: 18px; font-weight: 500; color: #010b14;">🚀 {{$title}}</p>
    <div class="divider"></div>
    <p style="font-size: 16px; font-weight: 400; color: #010b14;">Hi {{ $user->profile->first_name }} 👋,</p>
    <p >Your transfer of NGN {{ $data['amount'] }} is succesful</p>
    <p style="font-size: 16px; font-weight: 800; color: #010b14;" >Transaction Details</p>
    <br>
    <p ><b>Reference No:</b> {{ $data->transaction_reference }} </p>
    <p ><b>Bank : </b>{{ $data->bankTransferTransaction->bank->bank_name }}  </p>
    <p ><b>Name :</b> {{ $data->bankTransferTransaction->account_name }}  </p>
    <p ><b>Acount Number :</b>{{ $data->bankTransferTransaction->account_number }}   </p>
    <p ><b>Naration :</b>{{ $data->naration }}   </p>
    <p ><b>Transaction Date:</b> {{ $data->created_at }}  </p>
 
    <br>
    <p>
        If you this is not your doing, we strongly advise you notify us as soon as possible via
        <a href="{{ env('SUPPORT_EMAIL') }}" target="_blank">{{ env('SUPPORT_EMAIL') }}</a>.
    </p>
    <p>Thank you.</p>
@endsection
