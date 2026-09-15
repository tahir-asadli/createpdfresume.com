<?php
return [
  'private_key' => env('PAYRIFF_PRIVATE_KEY'),
  'base_url' => env('PAYRIFF_BASE_URL'),
  'success_callback' => env('PAYRIFF_SUCCESS_CALLBACK'),
  'error_callback' => env('PAYRIFF_ERROR_CALLBACK'),
  'result' => env('PAYRIFF_RESULT'),
  'currency' => env('PAYRIFF_CURRENCY'),
];

