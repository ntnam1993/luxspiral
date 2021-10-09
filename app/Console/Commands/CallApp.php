<?php

namespace App\Console\Commands;

use App\Models\Delivery;
use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Console\Command;
use App\Jobs\RunCallApp;

class CallApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call send MP3 to app';

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
        //$now to check date time (H:i) with schedule in delivery
        $now      = date('Y-m-d H:i');
        $delivery = Delivery::where('schedule', $now)
                            ->with('soundMessage')
                            ->first();

        if ( count($delivery) > 0 ) {
            // Define client
            $client   = new Client(getenv('API_KEY'), getenv('API_KEY_SECRET'), getenv('ACCOUNT_SID'));
            /**************************
             * Check testing delivery
             */
            if( $delivery->test_flg ) {
                // Is Test
                $isTest    = true;
                $users     = User::where('test_flg', User::IS_TEST)->get();

            } else {
                $isTest    = false;
                $users     = User::where('expired','>=', date('Y-m-d'))->get();
            }
            /****************
             * Call VoIP
             */
            if ( count($users) ) {
                foreach($users as $user) {
                    dispatch(new RunCallApp( $user, $client, $delivery, $isTest ));
                }
            }
        }
    }
}
