<?php
namespace App\Traits\Tools;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


trait HttpTrait
{

    public function sendGetRequest(string $url, array $queryParams = [], array $headers = [], $formData = false)
    {
        try {
        $response = Http::withoutVerifying()->withHeaders($headers)->get($url, $queryParams);

        return $this->handleResponse($response);

    } catch (\Exception $e) {
        Log::error($e->getMessage(), [$e]);
        return [
            'status'  => false,
            'message' => $e->getMessage(),
        ];
    }
    }


    public function sendPostRequest(string $url, array $data = [], array $headers = [])
    {

        try {

        $response = Http::withoutVerifying()->withHeaders($headers);

        $response = $response->post($url, $data);

        return $this->handleResponse($response);

    } catch (\Exception $e) {
        Log::error($e->getMessage(), [$e]);
        return [
            'status'  => false,
            'message' => $e->getMessage(),
        ];
    }
    }
    public function sendPost(string $url, array $data = [], array $headers = [])
    {

        try{

        $client = new \GuzzleHttp\Client();

        return $client->request('POST', $url, [
            'form_params' => $data,
            'headers'     => $headers,
            'verify'      => false,
        ]);


    } catch(\GuzzleHttp\Exception\GuzzleException  $e) {

        Log::error($e->getMessage(), [$e]);


        return [
            'status'  => false,
            'message' => $e->getMessage(),
        ];

    }
}

  
    private function handleResponse($response)
    {
        if ($response->successful()) {
            return $response->json(); // Return the JSON response
        }

        return [
            'error'   => true,
            'status'  => $response->status(),
            'message' => $response->body(),
        ];
    }
}
