<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Models\Call;
use App\Repositories\Contracts\CallRepository;
use App\Repositories\Contracts\DeliveryRepository;
use App\Repositories\Contracts\SoundRepository;
use App\Utils\BaseUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeliveryController extends BaseController
{
    protected $repository;
    protected $callRepository;
    protected $soundRepository;

    public function __construct(DeliveryRepository $repository, CallRepository $callRepository, SoundRepository $soundRepository)
    {
        $this->repository      = $repository;
        $this->callRepository  = $callRepository;
        $this->soundRepository = $soundRepository;
    }

    public function getList(Request $request)
    {
        $key_date = !empty($request->key_date) ? $request->key_date : BaseUtils::KEY_DATE_GET_ALL;

        if ($key_date == BaseUtils::KEY_DATE_GET_ALL) {
            $deliveries = $this->repository->getAllWithRelation();
        } else {
            $deliveries = $this->repository->findDeliveryByDate( $key_date );
        }

        if (!empty($deliveries) && count($deliveries) > 0) {
            $fullListArchive = [];
            foreach ($deliveries as  $delivery) {
                $fullListArchive[] = [
                    'id'            => $delivery['id'],
                    'title'         => $delivery['title'],
                    'url_send'      => $delivery['sound_message']['url'],
                    'url_no_answer' => $delivery['sound_no_answer']['url'],
                    'time_call'     => $delivery['schedule']
                ];
            }
            return $this->responseSuccess('success', $fullListArchive);
        } else {
            return $this->responseSuccess('success',null);
        }
    }

    public function getFullList()
    {
        $deliveries = $this->repository->getAllWithRelation();
        $fullListArchive = [];
        foreach ($deliveries as  $delivery) {
            $fullListArchive[] = [
                'id'            => $delivery['id'],
                'title'         => $delivery['title'],
                'url_send'      => $delivery['sound_message']['url'],
                'url_no_answer' => $delivery['sound_no_answer']['url'],
                'time_call'     => $delivery['schedule']
            ];
        }
        return $this->responseSuccess('success',$fullListArchive);
    }
}
