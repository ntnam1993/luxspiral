<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface FAQRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface FAQRepository extends RepositoryInterface
{
    public function getAll($title);
    public function getMaxDisplayOrder();
    public function changeDisplayOrderDown($id);
    public function changeDisplayOrderUp($id);
}
