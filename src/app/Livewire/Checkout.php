<?php

namespace App\Livewire;

use App\Mail\AdminNotification;
use App\Mail\PaymentsProcessed;
use App\Models\Card;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\EpointHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Checkout extends Component
{
    public $renew = false;

    public Plan $plan;

    public $error = '';

    public $payment = '';


    public function submit()
    {
        $this->epoint_submit();
    }


    public function epoint_submit()
    {
        $this->error = '';

        if ($this->payment == '' || ($this->payment !== 'bank' && (int) $this->payment < 1)) {
            $this->error = __('Please select a payment method!');
            return;
        }
        $emailData = [];
        $orderData = [];

        try {

            $order = Order::create([
                'user_id' => auth()->user()->id,
                'data' => serialize([
                    'plan_id' => $this->plan->id,
                    'amount' => $this->plan->price,
                    'usd' => $this->plan->usd,
                    'type' => 'subscription',
                    'renew' => $this->renew,
                ]),
                'status' => 'pending',
            ]);
            $description = __('Subscription, plan: :plan, order: :order', [
                'plan' => $this->plan->name,
                'order' => $order->id
            ]);
            $endpoint = 'card-registration-with-pay';
            $orderData = [];
            try {
                $orderData = unserialize($order['data']);
            } catch (\Throwable $th) {
                $this->error = __('Error occured, code: 4');
                return;
            }
            if (!isset($orderData['amount']) || (int) $orderData['amount'] <= 0) {
                $this->error = __('Error occured, code: 5');
                return;
            }
            $epoint = app('epoint');
            if ($this->payment === 'bank') {
                $response = '';
                if ($this->renew) {
                    $payload = [
                        'public_key' => config('epoint.public_key'),
                        'amount' => (float) $orderData['amount'] / 100,
                        'currency' => EpointHelper::currency(),
                        'language' => EpointHelper::lang(),
                        'order_id' => $order->id,
                        'description' => $description,
                        'success_redirect_url' => config('epoint.success_callback') . '?order_id=' . $order->id,
                        'error_redirect_url' => config('epoint.error_callback') . '?order_id=' . $order->id,
                        'other_attr' => $endpoint,
                    ];
                    $response = $epoint->request($endpoint, $epoint->payload($payload));
                } else {
                    $payload = [
                        'public_key' => config('epoint.public_key'),
                        'amount' => (float) $orderData['amount'] / 100,
                        'currency' => EpointHelper::currency(),
                        'language' => EpointHelper::lang(),
                        'order_id' => $order->id,
                        'description' => $description,
                        'success_redirect_url' => config('epoint.success_callback') . '?order_id=' . $order->id,
                        'error_redirect_url' => config('epoint.error_callback') . '?order_id=' . $order->id,
                        'other_attr' => 'request',
                    ];
                    $response = $epoint->request('request', $epoint->payload($payload));
                }
                if ($response) {
                    $json_data = json_decode($response, true);
                    if ($json_data) {
                        if ($json_data['status'] == 'success' && $json_data['redirect_url'] != '') {
                            if (isset($json_data['card_id'])) {
                                Card::create([
                                    'card_id' => $json_data['card_id'],
                                    'verified' => false,
                                    'active' => false,
                                    'user_id' => auth()->user()->id,
                                ]);
                            }
                            return redirect($json_data['redirect_url']);
                        } else {
                            $this->error = __('Error occured, code: 1');
                            $emailData['subject'] = $this->error;
                            $order->delete();
                            Mail::to(config('site.adminMail'))->queue(new AdminNotification($emailData));
                        }
                    } else {
                        $this->error = __('Error occured, code: 2');
                        $emailData['subject'] = $this->error;
                        $order->delete();
                        Mail::to(config('site.adminMail'))->queue(new AdminNotification($emailData));
                    }
                } else {
                    $this->error = __('Error occured, code: 3');
                    $emailData['subject'] = $this->error;
                    $order->delete();
                    // Mail::to(config('site.adminMail'))->queue(new AdminNotification($emailData));
                }
            } elseif ((int) $this->payment > 0) {
                $card = auth()->user()->cards()->find((int) $this->payment);
                if ($card) {
                    $payload = [
                        'public_key' => config('epoint.public_key'),
                        'language' => EpointHelper::lang(),
                        'card_id' => $card->card_id,
                        'order_id' => $order->id,
                        'amount' => (float) $orderData['amount'] / 100,
                        'currency' => EpointHelper::currency(),
                        'description' => $description,
                    ];

                    $transaction_data = [
                        'api_endpoint' => 'execute-pay',
                        'order_id' => $order->id,
                        'user_id' => $card->user->id,
                        'card_id' => $card->id,
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
                    $endpoint = 'execute-pay';
                    $response = $epoint->request($endpoint, $epoint->payload($payload));
                    info('execute-pay');
                    $json_data = json_decode($response, true);
                    if (isset($json_data['status']) && $json_data['status'] == 'success') {
                        $order->status = 'completed';
                        info('execute-pay success');
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
                        $order->transaction_id = $transaction->id;
                        $order->save();
                        $property = EpointHelper::updateSubscription($order);
                        Mail::to($order->user->email)->queue(new PaymentsProcessed($order, auth()->user()->language));
                        info('execute-pay property');
                    } else {
                        $this->error = __('Payment failed, please try again');
                    }
                    return redirect(route('billing'));
                } else {
                    $this->error = __('Card not found');
                }
            } else {
                $this->error = __('Card not found');
            }
        } catch (\Throwable $th) {
            $this->error = __('Error occured, code: 4');
            Mail::to(config('site.adminMail'))->queue(new AdminNotification([
                'errorMessage' => $th->getMessage(),
                'error' => $this->error,
                'user' => auth() ? auth()->user()->id : null,
                'time' => now()
            ]));
        }

    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
