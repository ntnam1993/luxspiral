<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\SoundCreateRequest;
use App\Http\Requests\SoundUpdateRequest;
use App\Repositories\Contracts\SoundRepository;
use App\Validators\SoundValidator;

/**
 * Class SoundsController.
 *
 * @package namespace App\Http\Controllers\Admin;
 */
class SoundsController extends Controller
{
    /**
     * @var SoundRepository
     */
    protected $repository;

    /**
     * @var SoundValidator
     */
    protected $validator;

    /**
     * SoundsController constructor.
     *
     * @param SoundRepository $repository
     * @param SoundValidator $validator
     */
    public function __construct(SoundRepository $repository, SoundValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $sounds = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $sounds,
            ]);
        }

        return view('sounds.index', compact('sounds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SoundCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(SoundCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $sound = $this->repository->create($request->all());

            $response = [
                'message' => 'Sound created.',
                'data'    => $sound->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sound = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $sound,
            ]);
        }

        return view('sounds.show', compact('sound'));
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
        $sound = $this->repository->find($id);

        return view('sounds.edit', compact('sound'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SoundUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(SoundUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $sound = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Sound updated.',
                'data'    => $sound->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
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
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Sound deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Sound deleted.');
    }
}
