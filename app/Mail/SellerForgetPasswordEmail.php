<?php

namespace App\Mail;

use App\Models\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SellerForgetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $code;
    protected Seller $seller;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Seller $seller, String $code)
    {
        //
        $this->seller = $seller;
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
            'name' => $this->seller->full_name,
        ])->markdown('mail.seller-forget-password-email');
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Seller Forget Password Email',
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
