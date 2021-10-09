<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendQuestion extends Mailable
{
    use Queueable, SerializesModels;

    protected $info;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($info)
    {
        $this->info = $info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.sendQuestion')->with([
            'name'           => $this->info['name'],
            'email'          => $this->info['email'],
            'question'       => $this->info['question'],
            'user_id'        => $this->info['user_id'],
        ]);
    }
}
