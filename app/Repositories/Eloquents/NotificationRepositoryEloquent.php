<?php

namespace App\Repositories\Eloquents;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\NotificationRepository;
use App\Models\Notification;
use DB;
use App\Validators\NotificationValidator;

/**
 * Class NotificationRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquents;
 */
class NotificationRepositoryEloquent extends BaseRepository implements NotificationRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Notification::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getList()
    {
        return Notification::orderBy('schedule','desc')->paginate(Notification::LIMIT);
    }

    public function searchTitle($data)
    {
        return Notification::where('title', 'like', '%'. $data .'%')
            ->orderBy('schedule','desc')
            ->paginate(Notification::LIMIT);
    }
    public function getAll()
    {
        return Notification::where('schedule','<=',date('Y-m-d H:i:s'))
            ->get();
    }
}
