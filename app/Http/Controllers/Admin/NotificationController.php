<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Utils\BaseUtils;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Notification;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\NotifyCreateRequest;
use App\Http\Requests\NotifyUpdateRequest;
use App\Repositories\Contracts\NotificationRepository;
use App\Validators\NotificationValidator;
use DB;
use DateTime;

/**
 * Class NotifiesController.
 *
 * @package namespace App\Http\Controllers\Admin;
 */
class NotificationController extends BaseController
{
    /**
     * @var NotifyRepository
     */
    protected $repository;

    /**
     * @var NotificationValidator
     */
    protected $validator;

    /**
     * NotifiesController constructor.
     *
     * @param NotifyRepository $repository
     * @param NotifyValidator $validator
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
    public function index(Request $request)
    {
        $notification = $this->repository->getList();

        if(!empty($request->keySearch) == true){
            $keySearch = $request->keySearch;
            $notification = $this->repository->searchTitle($keySearch);
        }
        return view('admin.notification.list',compact('notification'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.notification.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  NotifyCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request)
    {
        try {
            $credential = $request->only('title','date','time','description');
            $schedule = $request->date . " " . $request->time;
            $this->validator->with($credential)->passesOrFail(NotificationValidator::RULE_CREATE);
            $credential['schedule'] = $schedule;
            unset($credential['date']);
            unset($credential['time']);
            $this->repository->create($credential);
            return redirect()->route("admin.notification.index")->with('success',trans('message.create-notify-success'));
        } catch (ValidatorException $e) {
            return redirect()->route("admin.notification.index")->with('error',trans('message.create-notify-error'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $credential['id'] = $id;
            $this->validator->with($credential)->passesOrFail(NotificationValidator::RULE_EDIT);
            $notify = $this->repository->find($id);
            if ($notify->schedule < date('Y-m-d H:i:s')) return redirect()->route("admin.notification.index")->with('error',trans('message.id-invalid'));
            return view('admin.notification.edit', compact('notify'));
        } catch (ValidatorException $e) {
            return redirect()->route("admin.notification.index")->with('error',trans('message.id-invalid'));;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  NotifyUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(Request $request, $id)
    {
        try {
            $credential = $request->only('title','date','time','description');
            $this->validator->with($request->all())->passesOrFail(NotificationValidator::RULE_UPDATE);
            $credential = $this->getSchedule($credential);
            if ($credential == false) return redirect()->back()->withErrors(trans('message.after'))->withInput();
            $this->repository->update($credential, $id);
            return redirect()->route("admin.notification.index")->with('success',trans('message.edit-notify-success'));
        } catch (ValidatorException $e) {
            return redirect()->route("admin.notification.index")->with('error',trans('message.edit-notify-error'));
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $credential['id'] = $id;
            $this->validator->with($credential)->passesOrFail(NotificationValidator::RULE_DELETE);
            $this->repository->delete($credential['id']);
            return redirect()->route("admin.notification.index")->with('success',trans('message.delete-notify-success'));
        }catch(ValidationException $e) {
            return redirect()->route("admin.notification.index")->with('error',trans('message.delete-notify-error'));
        }
    }

    public function search(Request $request)
    {
        $name = $request->keySearch ? $request->keySeach : '';
        $notification = $this->repository->searchTitle($name);
        return view('admin.notification.list',compact('notification'));
    }

    public function showDetail($id)
    {
        $notify = $this->repository->find($id);
        return view('admin.notification.detail', compact('notify'));
    }
}
