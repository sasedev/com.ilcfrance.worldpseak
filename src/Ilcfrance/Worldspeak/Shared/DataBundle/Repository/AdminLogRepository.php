<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Admin;

/**
 * AdminLog EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class AdminLogRepository extends EntityRepository
{

    /**
     * Count By Admin
     *
     * @param Admin $admin
     * @return mixed|Statement|array|NULL
     */
    public function countByAdmin(Admin $admin)
    {
        $qb = $this->createQueryBuilder('log')
            ->select('count(log)')
            ->where('log.admin = :admin')
            ->setParameter('admin', $admin);

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get All entity Query By Admin
     *
     * @param Admin $admin
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllByAdminQuery(Admin $admin, $cache = true)
    {
        $qb = $this->createQueryBuilder('log')
            ->where('log.admin = :admin')
            ->setParameter('admin', $admin)
            ->orderBy('log.dtCrea', 'DESC');

        $query = $qb->getQuery();

        if ($cache) {
            $query->setCacheable('true')
                ->useQueryCache(true)
                ->setLifetime(20)
                ->useResultCache(true, 20);
        }

        return $query;
    }

    /**
     * Get All entity By Admin
     *
     * @param Admin $admin
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllByAdmin(Admin $admin, $cache = true)
    {
        return $this->getAllByAdminQuery($admin, $cache)->execute();
    }
}
