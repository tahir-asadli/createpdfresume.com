<?php

namespace App\Services;

use App\Mail\AdminNotification;
use App\Mail\PaymentsProcessed;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;

class SubscriptionCronJob
{
  public static function run()
  {

    $subscriptionInfos = [];

    try {
      $subscriptions = Subscription::active()->renew()->due()->get();
      if (!count($subscriptions)) {
        $subscriptionInfos['info'] = 'There is no subscription';
      }

      if (count($subscriptions)) {

        foreach ($subscriptions as $key => $subscription) {
          $user = $subscription->user;
          $plan = $subscription->plan;
          $card = $user->cards()->verified()->first();
          $subId = $subscription->id;
          $subscriptionInfos[$subId] = [];
          if ($user) {
            $subscriptionInfos[$subId]['user'] = 'User found: ' . $user->name;

            if ($plan && $plan->active) {
              $subscriptionInfos[$subId]['plan'] = 'Plan found: ' . $plan->name;
              if ($card) {
                $subscriptionInfos[$subId]['card'] = 'Card found: ' . $card->id;

                $orderData = [
                  'plan_id' => $plan->id,
                  'amount' => $plan->price,
                  'type' => 'subscription',
                  'renew' => true,
                ];
                $order = Order::make([
                  'user_id' => $user->id,
                  'data' => serialize($orderData),
                  'status' => 'pending'
                ]);
                $subscriptionInfos[$subId]['price'] = $plan->price;
                $subscriptionInfos[$subId]['charging_started'] = 'Charging request started';
                $order->save();
                $subscriptionInfos[$subId]['order'] = $order->id;
                $epoint = app('epoint');
                $payload = [
                  'public_key' => config('epoint.public_key'),
                  'language' => $user->language,
                  'card_id' => $card->card_id,
                  'order_id' => $order->id,
                  'amount' => (float) $orderData['amount'] / 100,
                  'currency' => EpointHelper::currency(),
                  'description' => 'Renewing ' . $plan->name . ' plan, Order #' . $order->id,
                ];
                $subscriptionInfos[$subId]['charging_ended'] = 'Charging request ended';
                $response = $epoint->request('execute-pay', $epoint->payload($payload));
                $transaction_data = [
                  'api_endpoint' => 'execute-pay',
                  'order_id' => $order->id,
                  'user_id' => $card->user->id,
                  'card_id' => $card->id,
                  'subscription_id' => $subscription->id,
                  'status' => 'failed',
                  'code' => 999,
                  'message' => '',
                  'transaction' => null,
                  'bank_transaction' => null,
                  'bank_response' => null,
                  'card_name' => null,
                  'card_mask' => null,
                  'operation_code' => null,
                  'rrn' => null,
                  'amount' => null,
                ];
                if ($response) {
                  $json_data = json_decode($response, true);
                  if ($json_data) {
                    if (isset($json_data['status']) && $json_data['status'] == 'success') {
                      info('JSON Response status success');
                      $subscriptionInfos[$subId]['charging_response'] = 'JSON Response status success';
                      $subscription->ends_at = now()->addDays(30);
                      $subscription->save();
                      $transaction_data['status'] = $json_data['status'];
                      $transaction_data['code'] = $json_data['code'];
                      $transaction_data['message'] = $json_data['message'];
                      $transaction_data['transaction'] = $json_data['transaction'];
                      $transaction_data['bank_transaction'] = $json_data['bank_transaction'];
                      $transaction_data['bank_response'] = $json_data['bank_response'];
                      $transaction_data['card_name'] = $json_data['card_name'];
                      $transaction_data['card_mask'] = $json_data['card_mask'];
                      $transaction_data['rrn'] = $json_data['rrn'];
                      $transaction_data['amount'] = $json_data['amount'];
                      $transaction = Transaction::create($transaction_data);
                      $order->status = 'completed';
                      $order->transaction_id = $transaction->id;
                      $order->save();
                      $subscriptionInfos[$subId]['end'] = 'Subscription updated';
                      Mail::to($user->email)->queue(new PaymentsProcessed($order, $order->user->language));
                      Mail::to(config('site.adminMail'))->queue(new PaymentsProcessed($order));
                    } else {
                      $subscriptionInfos[$subId]['charging_response'] = 'JSON Response status ' . $json_data['status'];
                      $subscription->status = 'deactivated';
                      $subscription->save();
                      $transaction_data['status'] = $json_data['status'];
                      $transaction_data['code'] = $json_data['code'];
                      $transaction_data['message'] = null;
                      $transaction_data['transaction'] = null;
                      $transaction_data['bank_transaction'] = null;
                      $transaction_data['bank_response'] = null;
                      $transaction_data['card_name'] = null;
                      $transaction_data['card_mask'] = null;
                      $transaction_data['rrn'] = null;
                      $transaction_data['amount'] = null;
                      $transaction = Transaction::create($transaction_data);
                      $order->status = 'failed';
                      $order->transaction_id = $transaction->id;
                      $order->save();
                      $subscriptionInfos[$subId]['end'] = 'Subscription deactivated, Order failed';
                    }
                  } else {
                    $subscriptionInfos[$subId]['charging_response'] = 'No JSON Response';
                    $subscription->status = 'deactivated';
                    $subscription->save();
                    $transaction_data['status'] = 'failed';
                    $transaction_data['code'] = 999;
                    $transaction_data['message'] = __('Error occured, code: 2');
                    $transaction_data['transaction'] = null;
                    $transaction_data['bank_transaction'] = null;
                    $transaction_data['bank_response'] = null;
                    $transaction_data['card_name'] = null;
                    $transaction_data['card_mask'] = null;
                    $transaction_data['rrn'] = null;
                    $transaction_data['amount'] = null;
                    $transaction = Transaction::create($transaction_data);
                    $order->status = 'failed';
                    $order->transaction_id = $transaction->id;
                    $order->save();
                    $subscriptionInfos[$subId]['end'] = 'Subscription deactivated, Order failed';
                  }
                } else {
                  $subscriptionInfos[$subId]['charging_response'] = 'No Response';
                  $subscription->status = 'deactivated';
                  $subscription->save();
                  $transaction_data['status'] = 'failed';
                  $transaction_data['code'] = 999;
                  $transaction_data['message'] = __('Error occured, code: 3');
                  $transaction_data['transaction'] = null;
                  $transaction_data['bank_transaction'] = null;
                  $transaction_data['bank_response'] = null;
                  $transaction_data['card_name'] = null;
                  $transaction_data['card_mask'] = null;
                  $transaction_data['rrn'] = null;
                  $transaction_data['amount'] = null;
                  $transaction = Transaction::create($transaction_data);
                  $order->status = 'failed';
                  $order->transaction_id = $transaction->id;
                  $order->save();
                  $subscriptionInfos[$subId]['end'] = 'Subscription deactivated, Order failed';
                }
              } else {
                $subscriptionInfos[$subId]['card'] = 'Card not found, subscription deactivated, Subscription ID: ' . $subscription->id;
                $subscription->status = 'deactivated';
                $subscription->save();
                $subscriptionInfos[$subId]['end'] = 'Subscription deactivated';
              }
            } else {
              $subscriptionInfos[$subId]['plan'] = 'Plan not found, subscription deactivated, Subscription ID: ' . $subscription->id;
              info('plan not found, subscription deactivated, Subscription ID: ' . $subscription->id);
              $subscription->status = 'deactivated';
              $subscriptionInfos[$subId]['end'] = 'Subscription deactivated';
              $subscription->save();
            }
          } else {
            info('User not found, subscription deactivated, Subscription ID: ' . $subscription->id);
            $subscription->status = 'deactivated';
            $subscription->save();
            $subscriptionInfos[$subId]['end'] = 'Subscription deactivated';
          }
        }
      }
    } catch (\Throwable $th) {
      $subscriptionInfos['error'] = $th->getMessage();
      Mail::to(config('site.adminMail'))->queue(new AdminNotification($subscriptionInfos));
    }
    // Mail::to(config('site.adminMail'))->queue(new AdminNotification($subscriptionInfos));
  }

}