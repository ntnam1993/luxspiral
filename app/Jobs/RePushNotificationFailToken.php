<?php

namespace App\Jobs;

use App\Models\DeviceTokenFail;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RePushNotificationFailToken implements ShouldQueue
{
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $total;
    protected $notify;

    public function __construct($notify)
    {
        $this->total  = DB::table('device_token_fail')->count();
        $this->notify = $notify;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->total) {
            Log::info('start repush ios notification');
            $maxPage           = $this->total;
            $device_token_fail = DeviceTokenFail::all();
            DB::table('device_token_fail')->truncate();

            foreach ($device_token_fail as $device) {
                sendIOSNotification($device->device_token_fail, $this->notify);
            }
        }
    }
}