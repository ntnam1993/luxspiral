<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Models\Answer;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\AnswerCreateRequest;
use App\Http\Requests\AnswerUpdateRequest;
use App\Repositories\Contracts\AnswerRepository;
use App\Validators\AnswerValidator;

/**
 * Class AnswersController.
 *
 * @package namespace App\Http\Controllers\Api\V1;
 */
class AnswersController extends BaseController
{
    /**
     * @var AnswerRepository
     */
    protected $repository;

    /**
     * @var AnswerValidator
     */
    protected $validator;

    /**
     * AnswersController constructor.
     *
     * @param AnswerRepository $repository
     * @param AnswerValidator $validator
     */
    public function __construct(AnswerRepository $repository, AnswerValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAnswer()
    {
        $answer = $this->repository->getListWithRelation() ? $this->repository->getListWithRelation() : null;
        return $this->responseSuccess('success',$answer);
    }
}
