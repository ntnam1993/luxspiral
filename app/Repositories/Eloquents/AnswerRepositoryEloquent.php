<?php

namespace App\Repositories\Eloquents;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\AnswerRepository;
use App\Models\Answer;
use App\Validators\AnswerValidator;
use DB;

/**
 * Class AnswerRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquents;
 */
class AnswerRepositoryEloquent extends BaseRepository implements AnswerRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Answer::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function showList($title)
    {
        return Answer::where('title', 'like', '%' . $title . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(Answer::LIMIT, ['*'], 'listAns');
    }

    public function createMsg($credential, $url, $fileNameMsgAns)
    {
        $idSoundNew = DB::table('sound')->insertGetId(
            [
                'url' => $url,
                'name' => $fileNameMsgAns,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ]
        );

        $idAns = DB::table('answer')->insertGetId(
            [
                'title' => $credential['title'],
                'sound_id' => $idSoundNew,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ]
        );

        return $idAns;
    }

    /*public function updateUploadMsgAns($newTitle, $newFile, $id)
    {
        $oldFile = Answer::find($id);

        @unlink($oldFile->sound->url);

        $newName = date('YmdHis')."message.".$newFile->getClientOriginalExtension();

        $newFile->move('upload', $newName);

        DB::table('sound')
                ->where('id', $oldFile->sound->id)
                ->update(
                    [
                        'url' => 'upload/'.$newName,
                        'updated_at' => date('Y-m-d h:i:s')
                    ]
        );

        DB::table('answer')->where('id', $id)->update(
            [
                'title' => $newTitle,
                'updated_at' => date('Y-m-d h:i:s')
            ]
        );

        return "success";

    }*/

    public function findWithRelation($id)
    {
        return Answer::where('id',$id)->with('sound')->first();
    }

    public function getListWithRelation()
    {
        return Answer::with('sound')->get()->toArray();
    }

    public function checkCountID()
    {
        return Answer::count();
    }
}
