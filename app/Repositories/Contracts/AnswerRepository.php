<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface AnswerRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface AnswerRepository extends RepositoryInterface
{
    public function showList($title);
    public function createMsg($credential, $url, $fileNameMsgAns);
//    public function updateUploadMsgAns($title, $file, $id);
    public function findWithRelation($id);
    public function getListWithRelation();
    public function checkCountID();
}
