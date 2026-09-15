<?php

namespace App\Services;

use App\Mail\PaymentsProcessed;
use App\Models\Card;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EpointHelper
{

  public static function encodeArr(array $data)
  {
    return base64_encode(json_encode($data));
  }
  public static function decodeArr(string $data)
  {
    return json_decode(base64_decode($data), true);
  }

  public static function encryptArr(string $data)
  {
    return base64_encode(sha1(config('epoint.private_key') . $data . config('epoint.private_key'), 1));
  }

  public static function generateSignature($data = [])
  {
    // $data = base64_encode(json_encode($data));
    return base64_encode(self::generateSha1($data));
  }

  public static function generateSha1($data = [])
  {
    $str = self::encodeArr($data);
    $sha = config('epoint.private_key') . $str . config('epoint.private_key');
    $sha1 = sha1($sha, 1);
    return $sha1;
  }

  public static function extractExpiresAt(string $bank_response)
  {
    $expires_at = null;
    $array = preg_split("/\n/", $bank_response);
    foreach ($array as $key => $value) {
      $value = trim($value);
      if (Str::startsWith($value, 'RECC_PMNT_EXPIRY')) {
        $expired_date_array = explode(': ', $value);
        if ($expired_date_array[1]) {
          $expire_date = $expired_date_array[1];
          $month = substr($expire_date, 0, 2);
          $year = 20 . substr($expire_date, 2, 2);
          $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
          // $expires_at = \DateTime::createFromFormat('myd', $month . $year . $day);
          $expires_at = Carbon::createFromDate($year, $month, $day);
        }
        break;
      }
    }
    return $expires_at;
  }

  public static function addTransaction($data = array(), $api_endpoint = '')
  {
    $order = null;
    if (isset($data['order_id']) && $data['order_id'] != '') {
      $order = Order::find($data['order_id']);

    }
    $transaction_data = [
      'api_endpoint' => $api_endpoint,
      'order_id' => isset($data['order_id']) ? $data['order_id'] : null,
      'user_id' => $order ? $order->user->id : null,
      'status' => isset($data['status']) ? $data['status'] : null,
      'code' => isset($data['code']) ? $data['code'] : null,
      'message' => isset($data['message']) ? $data['message'] : null,
      'transaction' => isset($data['transaction']) ? $data['transaction'] : null,
      'bank_transaction' => isset($data['bank_transaction']) ? $data['bank_transaction'] : null,
      'bank_response' => isset($data['bank_response']) ? $data['bank_response'] : null,
      'card_name' => isset($data['card_name']) ? $data['card_name'] : null,
      'card_mask' => null,
      'operation_code' => isset($data['operation_code']) ? $data['operation_code'] : null,
      'rrn' => isset($data['rrn']) ? $data['rrn'] : null,
      'amount' => isset($data['amount']) ? $data['amount'] : null,
    ];
    return Transaction::create($transaction_data);
  }

  public static function updateOrder($data = [])
  {
    if (isset($data['order_id'])) {
      $order = Order::find($data['order_id']);
      if ($order) {
        $order->status = $data['code'] == '000' ? 'completed' : 'failed';
        $order->save();
        return $order;
      }
    }
    return false;
  }

  // public static function addSubscription(Order $order)
  // {
  //   return Subscription::create([
  //     'plan_name' => $order->plan->name,
  //     'plan_id' => $order->plan->id,
  //     'user_id' => $order->user->id,
  //     'ends_at' => now()->addDays(30)->toDateTimeString(),
  //   ]);
  // }

  public static function updateSubscription(Order $order)
  {
    $orderData = [];
    try {
      $orderData = unserialize($order['data']);
    } catch (\Throwable $th) {
    }

    if (isset($orderData['amount']) && (int) $orderData['amount'] > 0 && $order !== false && $order instanceof Order) {
      $plan = Plan::find($orderData['plan_id']);
      if ($plan) {

        if ($order->user->subscription) {
          $order->user->subscription->update([
            'plan_name' => $plan->name,
            'plan_id' => $plan->id,
            'renew' => $orderData['renew'],
            'status' => 'active',
            'ends_at' => now()->addDays($plan->day_count)->toDateTimeString(),
          ]);
          return $order->user->subscription;
        } else {
          return Subscription::create([
            'plan_name' => $plan->name,
            'plan_id' => $plan->id,
            'renew' => $orderData['renew'],
            'status' => 'active',
            'user_id' => $order->user->id,
            'ends_at' => now()->addDays($plan->day_count)->toDateTimeString(),
          ]);
        }
      }
    }
  }
  public static function getCardType($cardNumber)
  {
    $cardNumber = preg_replace('/\D/', '', $cardNumber); // Remove non-digit characters
    $firstFour = substr($cardNumber, 0, 4);

    $cardTypes = [
      'Visa' => ['4'],
      'Mastercard' => range(51, 55) + range(2221, 2720),
      'American Express' => [34, 37],
      'Discover' => [6011, 644, 645, 646, 647, 648, 649, 65],
      'Diners Club' => range(300, 305) + [36, 38, 39],
      'JCB' => range(3528, 3589),
      'UnionPay' => [62],
      'Maestro' => [50, 56, 57, 58, 6]
    ];

    foreach ($cardTypes as $cardType => $ranges) {
      foreach ($ranges as $range) {
        if (strpos((string) $firstFour, (string) $range) === 0) {
          return $cardType;
        }
      }
    }


    // return 'Unknown Card Type';
    return '';
  }

  public static function validResponse($data, $signature)
  {
    $decoded_data = self::decodeArr($data);
    $new_signature = self::generateSignature($decoded_data);
    return $signature == $new_signature;
  }

  public static function cardRegistrationWithPayEndpoint($decoded_data)
  {
    $transaction = EpointHelper::addTransaction($decoded_data, 'card-registration-with-pay');
    $order = EpointHelper::updateOrder($decoded_data);
    $orderData = [];
    try {
      $orderData = unserialize($order['data']);
    } catch (\Throwable $th) {
    }
    if (isset($orderData['amount']) && (int) $orderData['amount'] > 0 && $order !== false && $order instanceof Order) {

      $order->transaction_id = $transaction->id;
      $order->save();

      if ($orderData['type'] == 'subscription') {

        $subscription = EpointHelper::updateSubscription($order);

        if ($subscription) {

          $transaction->subscription_id = $subscription->id;
          $transaction->save();

          if (isset($decoded_data['card_id']) && $decoded_data['card_id'] != '') {
            $card = Card::where('card_id', $decoded_data['card_id'])->first();

            if ($card) {

              info('card found');
              $bank_response = $decoded_data['bank_response'];
              $expires_at = EpointHelper::extractExpiresAt($bank_response);
              $name = EpointHelper::getCardType($decoded_data['card_mask']);

              $card->update([
                'verified' => true,
                'name' => $name,
                'expires_at' => $expires_at ? $expires_at : null,
                'active' => true,
              ]);

              $transaction->card_id = $card->id;
              $transaction->user_id = $card->user->id;
              $transaction->save();

            } else {

              info('Transaction Error: ' . $transaction->id . ', Card not found: ' . $decoded_data['card_id']);

            }
          } else {

            info('Transaction Error: ' . $transaction->id . ', card_id is empty');

          }

        } else {

          info('Transaction Error: ' . $transaction->id . ', Could not add Subscription to DB');

        }

      }

      Mail::to($order->user->email)->queue(new PaymentsProcessed($order, $order->user->language));
      Mail::to(config('site.adminMail'))->queue(new PaymentsProcessed($order));

    } else {

      info('Transaction Error: ' . $transaction->id . ', Order not found');

    }
  }

  public static function requestEndpoint($decoded_data)
  {
    $transaction = EpointHelper::addTransaction($decoded_data, 'request');
    $order = EpointHelper::updateOrder($decoded_data);
    $orderData = unserialize($order->data);
    if ($order !== false && $order instanceof Order) {
      $order->transaction_id = $transaction->id;
      $order->save();
      Mail::to($order->user->email)->queue(new PaymentsProcessed($order, $order->user->language));
      Mail::to(config('site.adminMail'))->queue(new PaymentsProcessed($order));

      if ($orderData['type'] == 'subscription') {

        $subscription = EpointHelper::updateSubscription($order);
        if ($subscription) {

          $transaction->subscription_id = $subscription->id;
          $transaction->save();

        } else {

          info('Transaction Error: ' . $transaction->id . ', Could not add Subscription to DB');

        }
      }


    } else {

      info('Transaction Error: ' . $transaction->id . ', Order not found');

    }
  }

  public static function cardRegistrationEndpoint($decoded_data)
  {
    $transaction = EpointHelper::addTransaction($decoded_data, 'card-registration');

    if (isset($decoded_data['card_id']) && $decoded_data['card_id'] != '') {

      $card = Card::where('card_id', $decoded_data['card_id'])->first();

      if ($card) {

        $bank_response = $decoded_data['bank_response'];
        $expires_at = EpointHelper::extractExpiresAt($bank_response);
        $name = EpointHelper::getCardType($decoded_data['card_mask']);

        $card->update([
          'verified' => true,
          'name' => $name,
          'expires_at' => $expires_at ? $expires_at : null,
          'active' => true,
        ]);

        $transaction->card_id = $card->id;
        $transaction->user_id = $card->user->id;
        $transaction->save();

      } else {

        info('Transaction Error: ' . $transaction->id . ', Card not found: ' . $decoded_data['card_id']);

      }

    } else {

      info('Transaction Error: ' . $transaction->id . ', card_id is empty');

    }
  }

  public static function lang()
  {
    $langs = ['az', 'en', 'ru'];
    $lang = App::currentLocale();
    if (in_array($lang, $langs)) {
      return $lang;
    }
    return 'en';
  }

  public static function currency()
  {
    return config('epoint.currency');
  }
}
