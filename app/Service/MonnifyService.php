<?php
namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Traits\Tools\HttpTrait;

class MonnifyService {

    use HttpTrait;

    public $headers;
    public $key;

    public function __construct()
    {
        $apiKey = config('monnify.api_key');
        $sKey = config('monnify.s_key');
        $this->headers = ['Authorization' => 'Bearer ' .  $this->monAuth()];
        $this->key = base64_encode($apiKey . ':' . $sKey);
    }

    public function monAuth(){
        try{
            $authHeader = [
                'Authorization' => 'Basic ' . $this->key,
            ];

            $response = $this->sendPostRequest(config('monnify.api_url') . '/api/v1/auth/login', [], $authHeader);


            if($response['requestSuccessful'] == true){
                return $response['responseBody']['accessToken'];
            }else{
                throw new \Exception('Error: ' . $response['message']);
            }
        }catch(\Exception $e){
            throw new \Exception('Error: ' . $e->getMessage());
        }
    }

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