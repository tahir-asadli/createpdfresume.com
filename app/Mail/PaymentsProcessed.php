<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Plan;
use App\Models\PremiumOption;
use App\Models\Property;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentsProcessed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Order $order, public $lang = 'en')
    {
        app()->setLocale($this->lang);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        app()->setLocale($this->lang);
        $orderData = unserialize($this->order->data);
        $subject = config('app.name');
        if ($orderData['type'] == 'subscription') {
            $subject = __('Your subscription has been renewed');
        }
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        app()->setLocale($this->lang);
        $orderData = unserialize($this->order->data);
        if ($orderData['type'] == 'subscription') {
            $user = $this->order->user;
            $subscription = $user->hasSubscription();
            $user->refresh();
            $user->subscription->refresh();
            return new Content(
                view: 'mail.payments-processed',
                with: [
                    'user' => $user,
                    'subscription' => $subscription,
                ]
            );
        }
        return new Content(
            view: 'empty'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
