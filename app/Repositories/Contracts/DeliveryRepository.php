<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface DeliveryRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface DeliveryRepository extends RepositoryInterface
{
    public function showList($title);
    public function uploadMsgSend($credential);
//    public function updateUploadMsgSend($credential, $id);
    public function findWithRelation($id);
    public function getUsersByDelivery($delivery);
    public function getUsersTestByDelivery($delivery);
    public function getAllWithRelation();
    public function findDeliveryByDate($date);
    public function getUsersToCall050($delivery);
    public function getUsersTestToCall050($delivery);
}
