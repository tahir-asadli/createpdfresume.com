<?php

namespace App\Mail;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerEmail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   */
  public function __construct(public Customer $customer, public $lang = 'en')
  {
    app()->setLocale($this->lang);
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    app()->setLocale($this->lang);
    return new Envelope(
      subject: __('Create a professional resume in minutes – with :sitename!', ['sitename' => config('app.name')]),
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    $plans = Plan::active()->get();
    $freeTemplateCount = freePlanTemplateCount();
    app()->setLocale($this->lang);
    return new Content(
      view: 'mail.customers',
      with: [
        'plans' => $plans,
        'freeTemplateCount' => $freeTemplateCount,
      ]
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
