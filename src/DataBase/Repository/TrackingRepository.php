<?php

declare(strict_types=1);

namespace App\DataBase\Repository;

use App\DataBase\Entity\TrackingRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\DataBase\Entity\ListBase;

/**
 * @method Tracking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tracking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tracking[]    findAll()
 * @method Tracking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrackingRecord::class);
    }

    public function listFilter(array $filter = []) {
        $page = isset($filter['page']) ? (int)$filter['page'] : 1;
        $queryParams = $this->createQueryParams($filter);
        $params = $queryParams['params'];
        $where = $queryParams['where'];

        $dql = "SELECT 
                    r 
                FROM " . 
                    TrackingRecord::class .  " r " . 
                    ( !empty($where) ? " WHERE " . implode(" AND ", $where) : "" ) .
                " ORDER BY r.id DESC";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setFirstResult((($page - 1) * 10))->setMaxResults(10);

        foreach($params as $key => $value) {
            $query->setParameter(":".$key, $value);
        }

        $paginator = new Paginator($query, $fetchJoinCollection = true);
        $iterator = $paginator->getIterator();
        return new ListBase($paginator->count(), $iterator);
    }

    private function createQueryParams(array $filter) 
    {
        $params = [];
        $where = [];        

        if (isset($filter['deviceId']) && !empty($filter['deviceId'])) {
            $where[] = "r.deviceId = :deviceId";
            $params['deviceId'] = $filter['deviceId'];
        }

        if (isset($filter['startDate']) && !empty($filter['startDate'])) {
            $date = explode('-', $filter['startDate']);

            if (checkdate((int)$date[1], (int)$date[2], (int)$date[0])) {
                $where[] = "r.startDate = :startDate";
                $params['startDate'] = $filter['startDate'];
            }
        }

        return ['params' => $params, 'where'=>$where];
    }

}
