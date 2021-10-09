<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Repositories\Contracts\NotificationRepository;
use App\Validators\NotificationValidator;

/**
 * Class NotificationsController.
 *
 * @package namespace App\Http\Controllers\Api\V1;
 */
class NotificationsController extends BaseController
{
    /**
     * @var NotificationRepository
     */
    protected $repository;

    /**
     * @var NotificationValidator
     */
    protected $validator;

    /**
     * NotificationsController constructor.
     *
     * @param NotificationRepository $repository
     * @param NotificationValidator $validator
     */
    public function __construct(NotificationRepository $repository, NotificationValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listNotify()
    {
        $notification = $this->repository->getAll();
        if (count($notification) > 0 ) {
            return $this->responseSuccess('success', $notification);
        }else {
            return $this->responseSuccess('success', null);
        }
    }

    public function testPush(Request $request)
    {
        $param = (object)$request->all();
        return $this->push($param);
    }
}
