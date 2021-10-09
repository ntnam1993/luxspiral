<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendPushAndroid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $totalAndroid;
    protected $notify;

    public function __construct($totalAndroid, $notify = false)
    {
        $this->totalAndroid = $totalAndroid;
        $this->notify       = $notify;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $limit    = env('LIMIT_ANDROID_PUSH', 50);
            $maxPage  = ceil($this->totalAndroid/$limit);
            $device_type   = User::TYPE_ANDROID;

            if( $this->notify ) {
                for ( $i = 0; $i < $maxPage; $i++ ) {
                    $offset        = $i*$limit;
                    $query         = "SELECT device_token FROM users where device_type = '$device_type' LIMIT $limit OFFSET $offset";
                    $device_tokens = collect(DB::select($query))->map(function ($q){
                        return $q->device_token;
                    })->toArray();
                    //push android notification
                    sendAndroidNotification($device_tokens, $this->notify);
                }
            }
        } catch (\Exception $e) {
            Log::info('Error SendPushAndroid mess: '. $e->getMessage());
        }
    }
}
