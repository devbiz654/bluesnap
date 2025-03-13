<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $paymentLink;

    public function __construct($paymentLink)
    {
        $this->paymentLink = $paymentLink;
    }

    public function build()
    {
        return $this->subject('Your Payment Link')
                    ->view('emails.payment_link')
                    ->with(['paymentLink' => $this->paymentLink]);
    }
}
