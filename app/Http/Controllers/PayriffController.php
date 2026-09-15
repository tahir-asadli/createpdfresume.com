<?php

namespace App\Http\Controllers;


class PayriffController extends Controller
{

  public function __invoke()
  {
    // $request = request();
    // info('payriff request');
    // info($request);
    $method = $_SERVER['REQUEST_METHOD'];
    info('method', [$method]);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = file_get_contents('php://input');
      $result = json_decode($data, true);
      info('payriff data');
      info($data);
      info('payriff json');
      info($result);
    }
  }
}
