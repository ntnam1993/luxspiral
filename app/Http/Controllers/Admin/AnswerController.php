<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Repositories\Contracts\SoundRepository;
use App\Models\Answer;
use App\Validators\AnswerValidator;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\AnswerRepository;

class AnswerController extends BaseController
{
    protected $repository;
    /**
     * @var DeliveryValidator
     */
    protected $validator;

    /**
     * @var AnswerRepository
     */
    protected $soundRepository;


    public function __construct(AnswerRepository $repository, AnswerValidator $validator,SoundRepository $soundRepository)
    {
        $this->repository = $repository;
        $this->soundRepository = $soundRepository;
        $this->validator = $validator;
    }

    public function create()
    {
        return view("admin.delivery.answer.add");
    }

    public function store(Request $request)
    {
        try {
            $credential = $request->only('title','filemsgans');
            $this->validator->with($credential)->passesOrFail(AnswerValidator::RULE_CREATE);

            $newName    = date('YmdHis')."message.".$credential['filemsgans']->getClientOriginalExtension();
            $fileName   = $credential['filemsgans']->getClientOriginalName();
            $url        = $this->uploadMP3ToS3($newName, $credential['filemsgans']->getPathName());
            $this->repository->createMsg($credential, $url, $fileName);

            return redirect()->route("admin.delivery.list")->with('success',trans('message.create-answer-success'));
        } catch (ValidatorException $e) {
            return redirect()->route("admin.delivery.list")->with('error',trans('message.create-answer-error'));
        }
    }

    public function destroy(Request $request)
    {
        try{
            $checkID = $this->repository->checkCountID();
            if ($checkID != Answer::MIN_ID_ACTIVE) {
                $credential = $request->only('id');
                $this->validator->with($credential)->passesOrFail(AnswerValidator::RULE_DELETE);
                $ansMsg = $this->repository->findWithRelation($credential['id']);
                $this->repository->delete($credential['id']);
                $this->removePathS3(getFileMp3Name($ansMsg->sound->url));
                $this->soundRepository->delete($ansMsg->sound->id);
            } else {
                return redirect()->route("admin.delivery.list")->with('error', trans('message.error-min-data'));
            }
            return redirect()->route("admin.delivery.list")->with('success',trans('message.delete-answer-success'));
        }catch(ValidationException $e) {
            return redirect()->route("admin.delivery.list")->with('error',trans('message.delete-answer-error'));
        }
    }

    public function edit($id)
    {
        $msgAns = $this->repository->find($id);
        return view("admin.delivery.answer.edit", compact('msgAns'));
    }

    public function update(Request $request, $id)
    {
        try {
            $msgAns = $this->repository->findWithRelation($id);
            $credential = $request->only('title','fileeditmsgans','urlAns');
            $this->validator->with($credential)->passesOrFail(AnswerValidator::RULE_UPDATE);

            if (isset($credential['fileeditmsgans'])) {
                $newName  = date('YmdHis')."message.".$credential['fileeditmsgans']->getClientOriginalExtension();
                $fileName = $credential['fileeditmsgans']->getClientOriginalName();
                $this->removePathS3($credential['urlAns']);
                $url      = $this->uploadMP3ToS3($newName,$credential['fileeditmsgans']->getPathName());
                $this->soundRepository->update(['url' => $url, 'name' => $fileName], $msgAns->sound->id);
                unset($credential['fileeditmsgans']);
            }
            if (isset($credential['urlAns'])) unset($credential['urlAns']);
            $this->repository->update($credential, $id);

            return redirect()->route('admin.delivery.list')->with('success',trans('message.edit-answer-success'));
        } catch (ValidatorException $e) {
            return redirect()->route('admin.delivery.list')->with('error',trans('message.edit-answer-error'));
        }
    }
}
