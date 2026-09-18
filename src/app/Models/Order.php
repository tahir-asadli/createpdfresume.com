<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function amountStr()
    {
        $priceStr = '';
        $orderData = [];
        try {
            $orderData = unserialize($this->data);
            if (isset($orderData['usd']) && (int) $orderData['usd'] > 0) {
                return priceUSD((int) $orderData['usd']) . ' = ' . priceAZNLong($orderData['amount']);
            }
            if (isset($orderData['amount'])) {
                return priceAZNLong($orderData['amount']);
            }

            return $priceStr;
        } catch (\Throwable $th) {
            return 'error';
        }
    }
    public function name()
    {
        $orderData = [];
        try {
            $orderData = unserialize($this->data);
            //code...
        } catch (\Throwable $th) {
            info('order name unserialize error, order id: ' . $this->id);
        }
        $name = '';
        if (isset($orderData['type'])) {
            if ($orderData['type'] == 'subscription') {
                $name .= __('Subscription');
                if (isset($orderData['plan_id']) && $orderData['plan_id'] != '') {
                    $plan = Plan::find($orderData['plan_id']);
                    if ($plan) {
                        $name .= ', ' . $plan->name;
                    }
                }
                // if (isset($orderData['amount']) && (int) $orderData['amount'] > 0) {
                //     $name .= ', ' . priceAZN((int) $orderData['amount']);
                // }
            }
        }
        return $name;
    }

    public function getPlanNameAttribute(): string
    {
        try {
            $orderData = unserialize($this->data);
            if ($orderData['plan_id']) {
                $plan = Plan::find($orderData['plan_id']);
                if ($plan) {
                    return $plan->name;
                }
            }
        } catch (\Throwable $th) {
        }
        return '-';
    }

    public function getAmountAttribute(): string
    {
        try {
            $orderData = unserialize($this->data);
            if ($orderData['amount']) {
                return (int) $orderData['amount'] > 0 ? priceWithCurrency((int) $orderData['amount']) : '';
            }
        } catch (\Throwable $th) {
        }
        return '-';
    }
}
