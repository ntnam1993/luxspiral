<?php

namespace App\Console\Commands;

use App\Jobs\SendPushAndroid;
use App\Jobs\SendPushIOS;
use App\Models\User;
use Illuminate\Console\Command;
use DB;
use App\Jobs\RePushNotificationFailToken;
use Illuminate\Support\Facades\Log;

class SendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push Notification in schedule list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now    = date('Y-m-d H:i');
        $notify = DB::table('notify')->where('schedule',$now)->first();

        try {
            if ( count($notify) ) {
                /*********************
                 * Send push android
                 */
                $totalAndroid    = DB::table('users')->where('device_type', User::TYPE_ANDROID)->count();
                if ( $totalAndroid ) {
                    dispatch(new SendPushAndroid($totalAndroid, $notify));
                }
                /***********************
                 * Send push ios
                 */
                $totalIOS        = DB::table('users')->where('device_type', User::TYPE_IOS)->count();
                if ( $totalIOS ) {
                    SendPushIOS::withChain([
                        new RePushNotificationFailToken($notify)
                    ])->dispatch($totalIOS,$notify);
                }
            }
        }catch (\Exception $exception) {
            Log::info('Error send push notification to device | Message : '.$exception->getMessage());
        }
    }
}
