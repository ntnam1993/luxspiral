<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface SoundRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface SoundRepository extends RepositoryInterface
{
    public function createMsgGetId($url, $fileName);
}
