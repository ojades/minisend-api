<?php

namespace App\Jobs;

use App\Constants\Constants;
use App\Mail\Mailer;
use App\Models\Transactions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Mail;

class EmailTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $template;
    private $transaction;
    /**
     * Create a new job instance.
     * @param $data
     * @param $template
     * @param $transaction
     *
     *
     * @return void
     */
    public function __construct($transaction, $data, $template)
    {
        $this->data = $data;
        $this->template = $template;
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $params = json_decode($this->data['data']);

        $mail = Mail::to($this->data['recipient_email']);

        if(!empty($this->data['attachment'])) {
            foreach ($this->data['attachment'] as $file) {
                $mail->attach($file);
            }
        }

        $mail->send(new Mailer($this->data, (array)$params, $this->template));

        if(Mail::failures()) {
            Log::error(json_encode(Mail::failures()));
        }

        $this->transaction->status = Constants::SENT;
        $this->transaction->update();
    }
}
