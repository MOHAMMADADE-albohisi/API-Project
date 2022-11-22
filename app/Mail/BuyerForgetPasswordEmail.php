<?php

namespace App\Mail;

use App\Models\Buyer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BuyerForgetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $code;
    protected Buyer $buyer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Buyer $buyer, String $code)
    {
        //
        $this->buyer = $buyer;
        $this->code = $code;
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function build()
    {
        return $this->with([
            'code' => $this->code,
            'name' => $this->buyer->name,
        ])->markdown('mail.buyer-forget-password-email');
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'buyer Forget Password Email',
        );
    }




    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
