<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface QuestionRepository extends RepositoryInterface
{
    public function getList($key);
    public function findWithUser($id);
}