<?php

namespace App\Repositories\Eloquents;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\CallRepository;
use App\Models\Call;
use App\Validators\CallValidator;
use DB;

/**
 * Class CallRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquents;
 */
class CallRepositoryEloquent extends BaseRepository implements CallRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Call::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return CallValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function findCallByField($key_date,$user_id)
    {
        return Call::where('user_id',$user_id)
            ->where('time_call','>',$key_date)
            ->where('time_call','<=',date('Y-m-d H:i:s'))
            //->where('status','!=',Call::STATUS_NO_RECEIVE)
            //->with('delivery')
            ->get()->toArray();
    }

    public function findCallNewest($user_id)
    {
        $stt_no_receive = Call::STATUS_NO_RECEIVE;

        $query = "SELECT * FROM `call` 
                  WHERE user_id = $user_id AND time_call <= NOW() AND status != $stt_no_receive 
                  ORDER BY time_call DESC LIMIT 1";
        return collect(DB::select($query))->first();
    }

    public function findCallNewestWithInNoReceive($user_id)
    {
        $query = "SELECT * FROM `call` 
                  WHERE user_id = $user_id AND time_call <= NOW()
                  ORDER BY time_call DESC LIMIT 1";
        return collect(DB::select($query))->first();
    }

    public function insert($data)
    {
        Call::insert($data);
    }

    public function findCallByUserIdWithRelation($user_id)
    {
        return Call::where('user_id',$user_id)
            //->where('status', '!=', Call::STATUS_NO_RECEIVE)
            ->with('delivery')
            ->get()
            ->toArray();
    }
}
