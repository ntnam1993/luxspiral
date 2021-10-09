<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CallRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface CallRepository extends RepositoryInterface
{
    public function findCallByField($key_date,$user_id);
    public function findCallNewest($user_id);
    public function findCallNewestWithInNoReceive($user_id);
    public function insert($data);
    public function findCallByUserIdWithRelation($user_id);
}
