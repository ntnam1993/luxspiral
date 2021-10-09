<?php

namespace App\Repositories\Eloquents;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\SoundRepository;
use App\Models\Sound;
use App\Validators\SoundValidator;

/**
 * Class SoundRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquents;
 */
class SoundRepositoryEloquent extends BaseRepository implements SoundRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Sound::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return SoundValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function createMsgGetId($url, $fileName)
    {

        $idSound = Sound::insertGetId(
            [
                'url' => $url,
                'name'=> $fileName,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        );
        return $idSound;
    }
    
}
