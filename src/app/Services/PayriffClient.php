<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class PayriffClient
{


  public function __construct(
    protected $private_key = null,
    protected $baseUrl = null,
    protected $currency = null,
  ) {
  }

  public function request($endpoint, $data)
  {

    $response = Http::withHeaders([
      'Content-Type' => 'application/json',
      'Accept' => 'application/json',
      'Authorization' => $this->private_key
    ])->post($this->baseUrl . $endpoint, $data);

    if ($response->successful()) {
      // Request was successful (2xx status code)
      $responseData = $response->json(); // Get the JSON response body as an array
      return [
        'status' => 'success',
        'message' => 'JSON data sent successfully!',
        'response_data' => $responseData
      ];
      ;
    } else {
      // Request failed (4xx or 5xx status code)
      $statusCode = $response->status();
      $errorMessage = $response->body(); // Get the raw error body
      return [
        'status' => 'error',
        'message' => 'Failed to send JSON data.',
        'status_code' => $statusCode,
        'error_body' => $errorMessage,
      ];
    }
  }

}