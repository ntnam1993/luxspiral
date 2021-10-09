<?php

namespace App\Repositories\Eloquents;

use App\Models\Call;
use App\Models\User;
use Mockery\CountValidator\Exception;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\DeliveryRepository;
use App\Models\Delivery;
use App\Models\Sound;
use App\Validators\DeliveryValidator;
use DB;

/**
 * Class DeliveryRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquents;
 */
class DeliveryRepositoryEloquent extends BaseRepository implements DeliveryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Delivery::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function showList($title)
    {
        return Delivery::where('title', 'like', '%' . $title . '%')
                        ->orderBy('schedule', 'desc')->paginate(Delivery::LIMIT, ['*'], 'listDeli');
    }

    public function uploadMsgSend($credential)
    {
        $newNameMsgSend = date('YmdHis')."message.".$credential['filemsgsend']->getClientOriginalExtension();
        $newNameMsgNoAns = date('YmdHis')."nonresponse.".$credential['filemsgnoans']->getClientOriginalExtension();

        $credential['filemsgsend']->move('upload', $newNameMsgSend);
        $credential['filemsgnoans']->move('upload', $newNameMsgNoAns);

        $idFileMsgSend = $this->getIdUploadSound($newNameMsgSend);
        $idFileMsgNoAns = $this->getIdUploadSound($newNameMsgNoAns);

        $idNewMsg = DB::table('delivery')->insertGetId(
            [
                'title' => $credential['title'],
                'sound_id' => $idFileMsgSend,
                'sound_id_no_answer' => $idFileMsgNoAns,
                'schedule' => $credential['schedule'],
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ]
        );

        return $idNewMsg;
    }

    public function findWithRelation($id)
    {
        return Delivery::where('id',$id)->with('soundMessage','soundNoAnswer')->first();
    }

    /**
     * @param $delivery
     */
    public function getUsersByDelivery($delivery) {
        return collect(Call::join('users', 'users.id', '=', 'call.user_id')
            ->where('delivery_id', $delivery)
            ->where('users.verify_status', User::STATUS_VERIFIED)
            ->whereNotNull('users.tel')
            ->groupBy('users.tel')
            ->havingRaw('MIN(status) = ?', [Call::STATUS_NO_RECEIVE])
            ->pluck('tel'))->chunk(env('MAX_CALL', 10));
    }
    /**
     * @param $delivery
     */
    public function getUsersTestByDelivery($delivery) {
        return collect(Call::join('users', 'users.id', '=', 'call.user_id')
                        ->where('delivery_id', $delivery)
                        ->where('users.verify_status', User::STATUS_VERIFIED)
                        ->whereNotNull('users.tel')
                        ->where('users.test_flg', User::IS_TEST)
                        ->groupBy('users.tel')
                        ->havingRaw('MIN(status) = ?', [Call::STATUS_NO_RECEIVE])
                        ->pluck('tel'))->chunk(env('MAX_CALL', 10));
    }

    public function getAllWithRelation()
    {
        return Delivery::whereNull('test_flg')
                        ->where('schedule', '<=', date('Y-m-d H:i:s'))
                        ->with('soundMessage', 'soundNoAnswer')
                        ->orderBy('schedule','desc')->get()->toArray();
    }

    /**
     * @param $date
     */
    public function findDeliveryByDate( $date )
    {
        return Delivery::where('schedule', '>', $date)
                        ->where('schedule', '<=', date('Y-m-d H:i:s'))
                        ->whereNull('test_flg')
                        ->with('soundMessage', 'soundNoAnswer')->orderBy('schedule','desc')->get()->toArray();
    }

    /**
     * @param $delivery
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
