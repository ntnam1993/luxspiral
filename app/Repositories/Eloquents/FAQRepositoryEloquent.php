<?php

namespace App\Repositories\Eloquents;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\FAQRepository;
use App\Models\FAQ;
use App\Validators\FAQValidator;

/**
 * Class FAQRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquents;
 */
class FAQRepositoryEloquent extends BaseRepository implements FAQRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FAQ::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return FAQValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getAll($title)
    {
        return FAQ::where('title', 'like', '%' . $title . '%')
            ->orderBy('display_order', 'asc')->paginate(15, ['*'], 'listFqa');
    }

    public function getMaxDisplayOrder()
    {
        return (FAQ::select(DB::raw('max(display_order) as display_order'))->first())->display_order;
    }

    public function changeDisplayOrderDown($id)
    {
        $faq          = FAQ::find($id);
        $displayOrder = $faq->display_order;
        $nextFaq      = FAQ::where('display_order','>',$displayOrder)->orderBy('display_order', 'asc')->limit(1)->first();

        FAQ::where('id',$id)->update(['display_order' => $nextFaq->display_order]);
        FAQ::where('id',$nextFaq->id)->update(['display_order' => $faq->display_order]);
        return $this->getAll(null);
    }
    public function changeDisplayOrderUp($id)
    {
        $faq          = FAQ::find($id);
        $displayOrder = $faq->display_order;
        $nextFaq      = FAQ::where('display_order','<',$displayOrder)->orderBy('display_order', 'desc')->limit(1)->first();

        FAQ::where('id',$id)->update(['display_order' => $nextFaq->display_order]);
        FAQ::where('id',$nextFaq->id)->update(['display_order' => $faq->display_order]);
        return $this->getAll(null);
    }
    
}
