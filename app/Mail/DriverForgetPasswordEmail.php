<?php

namespace App\Mail;

use App\Models\Driver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DriverForgetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected string $code;
    protected Driver $driver;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Driver $driver, String $code)
    {
        //
        $this->driver = $driver;
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
            'name' => $this->driver->name,
        ])->markdown('mail.driver-forget-password-email');
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Driver Forget Password Email',
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
