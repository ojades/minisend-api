<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Mailer extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    private $params;
    private $template;
    /**
     * Create a new message instance.
     * @param $data
     * @param $params
     * @param $template
     *
     * @return void
     */
    public function __construct($data, $params, $template)
    {
        $this->data = $data;
        $this->params = $params;
        $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $params = $this->params;
        $template = $this->template;

        //replace template notations with values
        foreach ($params as $key => $val) {
            $template = str_replace('{{'.$key.'}}', $val, $template);
        }

        return $this->subject($this->data['subject'])
            ->from(config('mail.mailers.smtp.username'), $this->data['sender_name'])
            ->html($template);
    }
}
