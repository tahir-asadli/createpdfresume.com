<?php

namespace App\Http\Controllers;

use App\Mail\TransactionEmail;
use App\Mail\TransactionError;
use App\Models\Card;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\EpointHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EpointController extends Controller
{

    public function __invoke()
    {
        $data = request('data');
        $signature = request('signature');
        if (EpointHelper::validResponse($data, $signature)) {

            $decoded_data = EpointHelper::decodeArr($data);


            if (isset($decoded_data['code']) && $decoded_data['code'] == '000') {

                Mail::to(config('site.adminMail'))->queue(new TransactionEmail($decoded_data));

                // If request is 'card-registration-with-pay'
                if ($decoded_data['operation_code'] == 200) {
                    EpointHelper::cardRegistrationWithPayEndpoint($decoded_data);
                }
                // If request is 'request' regular payment
                else if ($decoded_data['operation_code'] == 100) {
                    EpointHelper::requestEndpoint($decoded_data);
                }
                // If request is 'card-registration'
                else if ($decoded_data['operation_code'] == 001) {
                    EpointHelper::cardRegistrationEndpoint($decoded_data);
                }
            } else {

                $endpoint = $decoded_data['other_attr'];
                $description = $decoded_data['message'];
                $error = $decoded_data['status'];
                $user = null;
                $orderId = $decoded_data['order_id'];
                $response = $decoded_data;
                Mail::to(config('site.adminMail'))->queue(new TransactionError(
                    $endpoint,
                    $description,
                    $error,
                    $user,
                    $orderId,
                    $response
                ));
            }
        } else {
            info('error: signatures didnt match - ');
        }

    }
}
