<?php

namespace App\Repositories\Eloquents;

use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\QuestionRepository;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class AnswerRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquents;
 */
class QuestionRepositoryEloquent extends BaseRepository implements QuestionRepository
{
    /**
    * Specify Model class name
    *
    * @return string
    */
    public function model()
    {
        return Question::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getList($key)
    {
        if (isset($key['question']) && isset($key['name'])) {
            return Question::where('question', 'like', '%'. $key['question'] .'%')->where('name', 'like', '%'. $key['name'] .'%')->orderBy('created_at', 'DESC')->paginate(Question::LIMIT);
        }else {
            if (isset($key['name'])) {
                return Question::where('name', 'like', '%'. $key['name'] .'%')->orderBy('created_at', 'DESC')->paginate(Question::LIMIT);
            }
            if (isset($key['question'])) {
                return Question::where('question', 'like', '%'. $key['question'] .'%')->orderBy('created_at', 'DESC')->paginate(Question::LIMIT);
            }
        }
        return Question::orderBy('created_at', 'DESC')->paginate(Question::LIMIT);
    }

    public function findWithUser($id)
    {
        return Question::with('user')->where('id',$id)->first();
    }
}
