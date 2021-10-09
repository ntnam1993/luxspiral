<?php

namespace App\Jobs;

use App\Models\Call;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class RunCallApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $delivery;
    protected $client;
    protected $isTest;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user,$client,$delivery,$isTest = true)
    {
        $this->user     = $user;
        $this->client   = $client;
        $this->delivery = $delivery;
        $this->isTest   = $isTest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->user->device_type == User::TYPE_IOS) {
            sendiOSNotification($this->user->device_token,'',[],true);
        }
        $statusCallback = route('statuscallback');
        $call = $this->client->calls->create(
            'client:'.md5($this->user->device_token), // Call this device
            'client:quick_start',     // From a valid Twilio number
            array(
                "method"               => "GET",
                'url'                  => $this->delivery->soundMessage->url,
                "statusCallback"       => $statusCallback,
                "statusCallbackMethod" => "POST"
            )
        );

        if($this->isTest) {
            Log::info('Call user Test ' . $this->user->id);
        } else {
            Log::info('Call user ' . $this->user->id);
        }
        $data_calls = [
            'user_id'        => $this->user->id,
            'delivery_id'    => $this->delivery->id,
            'twilio_call_id' => $call->sid,
            'status'         => Call::STATUS_NO_RECEIVE,
            'time_call'      => date('Y-m-d H:i:s'),
            'is_called'      => Call::NO_CALL_050,
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s')
        ];
        Call::insert($data_calls);
    }
}
