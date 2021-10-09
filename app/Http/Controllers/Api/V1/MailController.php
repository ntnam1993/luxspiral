<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Mail\SendQuestion;
use App\Repositories\Contracts\QuestionRepository;
use App\Repositories\Contracts\CallRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class MailController extends BaseController
{
    protected $callRepository;
    protected $userRepository;
    protected $questionRepository;

    public function __construct(CallRepository $callRepository, UserRepository $userRepository, QuestionRepository $questionRepository)
    {
        $this->callRepository = $callRepository;
        $this->userRepository = $userRepository;
        $this->questionRepository = $questionRepository;
    }

    public function sendQuestion(Request $request)
    {
        $user_id          = $this->toUser();
        $param            = $request->only('name','email','question');
        $param['user_id'] = $user_id;
        Mail::to(getenv('MAIL_GET_QUESTION'))->send(new SendQuestion($param));
        $this->questionRepository->create($param);
        return $this->responseSuccess('success',null);
    }
}
