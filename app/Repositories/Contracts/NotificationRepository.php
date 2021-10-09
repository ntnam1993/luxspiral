<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface NotificationRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface NotificationRepository extends RepositoryInterface
{
    public function getList();
    public function searchTitle($data);
}
