<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;
use Illuminate\Support\Facades\Log;

class SendPushIOS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $total;
    protected $notify;

    public function __construct($total, $notify = false)
    {
        $this->total  = $total;
        $this->notify = $notify;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $limit       = env('LIMIT_IOS_PUSH', 50);
            $maxPage     = ceil($this->total / $limit);
            $device_type = User::TYPE_IOS;
            if ( $this->notify ) {
                for ($i = 0; $i < $maxPage; $i++) {
                    $offset        = $i * $limit;
                    $query         = "SELECT device_token FROM users where device_type = '$device_type' LIMIT $limit OFFSET $offset";
                    $device_tokens = collect(DB::select($query))->map(function ($q) {
                        return $q->device_token;
                    })->toArray();
                    sendiOSNotification($device_tokens, $this->notify);
                }
            }
        } catch (\Exception $e) {
            Log::info('Error SendPushIOS offset: ' . $this->offset . ' | mess: ' . $e->getMessage());
        }
    }
}
