<?php
namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Traits\Tools\HttpTrait;

class MonnifyService {

    use HttpTrait;

    public $headers =  [
            'Authorization: "Basic' . config('monnify.api_key').'"',
        ];


    public function verifyTransaction($transactionId)
    {

        try{

            $url = config('monnify.api_url') . '/v1/merchant/transactions/' . $transactionId;
        }
        catch(\Exception $e){

            throw new \Exception('Error: ' . $e->getMessage());
          
        }


    }



}