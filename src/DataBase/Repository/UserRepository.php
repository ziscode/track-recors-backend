<?php

namespace App\DataBase\Repository;

use App\DataBase\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\DataBase\Entity\ListBase;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function listFilter(array $filter = []) 
    {
        $page = isset($filter['page']) ? (int)$filter['page'] : 1;
        $queryParams = $this->createQueryParams($filter);
        $params = $queryParams['params'];
        $where = $queryParams['where'];

        $dql = "SELECT 
                    r 
                FROM " . 
                    User::class .  " r " . 
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

        if (isset($filter['name']) && !empty($filter['name'])) {
            $where[] = "LOWER(r.name) LIKE :name";
            $params['name'] = strtolower("%".$filter['name']."%");
        }

        if (isset($filter['email']) && !empty($filter['email'])) {
            $where[] = "LOWER(r.email) LIKE :email";
            $params['email'] = strtolower("%".$filter['email']."%");
        }

        return ['params' => $params, 'where'=>$where];
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

}
