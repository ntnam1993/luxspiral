<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\FAQ;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\FAQCreateRequest;
use App\Http\Requests\FAQUpdateRequest;
use App\Repositories\Contracts\FAQRepository;
use App\Validators\FAQValidator;

/**
 * Class FAQsController.
 *
 * @package namespace App\Http\Controllers\Admin;
 */
class FAQsController extends BaseController
{
    /**
     * @var FAQRepository
     */
    protected $repository;

    /**
     * @var FAQValidator
     */
    protected $validator;

    /**
     * FAQsController constructor.
     *
     * @param FAQRepository $repository
     * @param FAQValidator $validator
     */
    public function __construct(FAQRepository $repository, FAQValidator $validator)
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
        $title = isset($request->title) ? $request->title : null;
        $faq = $this->repository->getAll($title);
        return view('admin.faq.list', compact('faq'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request)
    {
        try {
            $credential = $request->only('title','content');
//            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $credential['display_order'] = $this->repository->getMaxDisplayOrder() + 1;

            $fAQ = $this->repository->create($credential);
            return redirect()->route('admin.faq.index');
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
        $fAQ = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $fAQ,
            ]);
        }

        return view('fAQs.show', compact('fAQ'));
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
        $faq = $this->repository->find($id);

        return view('admin.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(Request $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $fAQ = $this->repository->update($request->all(), $id);

            return redirect()->route('admin.faq.index');
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
                'message' => 'FAQ deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'FAQ deleted.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function changeDisplayOrder(Request $request)
    {
        $id = $request->id;
        if ($request->key == 'down') {
            $faq = $this->repository->changeDisplayOrderDown($id);
        }else {
            $faq = $this->repository->changeDisplayOrderUp($id);
        }
        return convertHTML($faq);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function getBladeFAQ(Request $request)
    {
        $faq = FAQ::orderBy('display_order', 'asc')->get();
        return view('admin.faq.index',compact('faq'));
    }
}
