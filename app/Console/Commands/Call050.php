<?php

namespace App\Console\Commands;

use App\Models\Call;
use App\Models\Delivery;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class Call050 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call:050 {delivery}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $key=0;
    protected $client;

    public function __construct()
    {
        parent::__construct();
        $this->client   = new Client(getenv('API_KEY'), getenv('API_KEY_SECRET'), getenv('ACCOUNT_SID'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $delivery  = $this->argument('delivery');
        $data      = $this->getData($delivery);
        $url       = isset($data['url']) && $data['url'] ? $data['url'] : '';
        $tels      = isset($data['data']) && $data['data'] ? $data['data'] : [];
        $is_called = Call::CALLED_050;
        if( count($tels) ) {
            foreach ( $tels as $tel ) {
                try {
                    $call = $this->client->calls->create(
                        $tel, env('TWILIO_FORM', "+815031842715"),
                        array("method" => "GET", "url" => $url)
                    );
                } catch (\Exception $e) {
                    Log::info("Call To Phone $tel Error: ". $e->getMessage());
                }
            }
            //  Update status 050 (is_called)
            Call::where('delivery_id', $delivery)
                    ->whereExists(function ($query) use ($tels){
                        $query->select(\DB::raw(1))
                                ->from('users')
                                ->whereRaw('call.user_id = users.id')
                                ->whereIn('users.tel', $tels);
                    })->update(['is_called' => $is_called]);
            Log::info('---------------------Call 050 CMS sleep 60s---------------------------------');
            sleep(env('SLEEP_050',60));
            Artisan::call('call:050', [
                'delivery' => $delivery
            ]);
        } else {
            Log::info('---------------------STOP JOB CALL 050---------------------------------');
        }
    }

    function getData( $delivery )
    {
        $data = [
            'data' => '',
            'url'  => ''
        ];

        $deliveries = Delivery::find($delivery);
        if ( count($deliveries) ) {
            // Get list users
            if ($deliveries->test_flg != null) {
                $listUsers = $this->getUsersTestToCall050($delivery);
            } else {
                $listUsers = $this->getUsersToCall050($delivery);
            }
            //Check List User
            if( count($listUsers) ) {
                $delivery = DB::select("SELECT url FROM sound WHERE id IN (SELECT sound_id FROM delivery WHERE id = $delivery)");
                $play = isset($delivery[0]->url) ? $delivery[0]->url : '';
                $url = route('dataxmltwi') . "?delivery=$play";
                $data = ['data' => $listUsers, 'url' => $url];
            }
        }
        return $data;
    }

    /**
     * @param $delivery
     * @return $listusers to call 050
     */
    public function getUsersToCall050( $delivery )
    {
        return DB::table('call')->join('users', 'users.id', '=', 'call.user_id')
            ->where('delivery_id', $delivery)
            ->where('users.verify_status', User::STATUS_VERIFIED)
            ->whereNotNull('users.tel')
            ->where('call.is_called',Call::NO_CALL_050)
            ->limit(getLimit050())
            ->groupBy('users.tel')
            ->havingRaw('MIN(status) = ?', [Call::STATUS_NO_RECEIVE])
            ->pluck('tel');
    }

    /**
     * @param $delivery
     * @return $listusers test to call 050
     */
    public function getUsersTestToCall050( $delivery )
    {
        return DB::table('call')->join('users', 'users.id', '=', 'call.user_id')
            ->where('delivery_id', $delivery)
            ->where('users.verify_status', User::STATUS_VERIFIED)
            ->whereNotNull('users.tel')
            ->where('users.test_flg', User::IS_TEST)
            ->where('call.is_called',Call::NO_CALL_050)
            ->limit(getLimit050())
            ->groupBy('users.tel')
            ->havingRaw('MIN(status) = ?', [Call::STATUS_NO_RECEIVE])
            ->pluck('tel');
    }
}
