<?php
namespace App\Service;

use App\Traits\Tools\HttpTrait;

class MonnifyService
{

    use HttpTrait;

    public $headers;
    public $key;

    public function __construct()
    {
        $apiKey        = config('monnify.api_key');
        $sKey          = config('monnify.s_key');
        $this->key     = base64_encode($apiKey . ':' . $sKey);
    }

    public function monAuth()
    {
        try {
            $authHeader = [
                'Authorization' => 'Basic ' . $this->key,
            ];

            $response = $this->sendPostRequest(config('monnify.api_url') . '/api/v1/auth/login', [], $authHeader);


            if (isset($response['requestSuccessful']) && $response['requestSuccessful'] == true) {
                return $response['responseBody']['accessToken'];
            } else {
                throw new \Exception('Error: ' . $response['message']);
            }
        } catch (\Exception $e) {
            throw new \Exception( $e->getMessage());
        }
    }

    public function verifyTransaction($transactionId)
    {
        try {

            $url = config('monnify.api_url') . '/api/v2/merchant/transactions/query?transactionReference=' . $transactionId;


        $this->headers = ['Authorization' => 'Bearer ' . $this->monAuth()];


            $response = $this->sendGetRequest($url, ['transactionReference' => $transactionId], $this->headers);


            if (isset($response['requestSuccessful']) && $response['requestSuccessful'] == true) {
                return $response['responseBody'];
            } else {
                throw new \Exception($response['message']);
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
