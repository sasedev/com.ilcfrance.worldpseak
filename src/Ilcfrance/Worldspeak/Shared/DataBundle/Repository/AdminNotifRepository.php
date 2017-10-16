<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use DateTime;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\AdminNotif;

/**
 * AdminNotif EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class AdminNotifRepository extends EntityRepository
{

    /**
     * Count All
     *
     * @return mixed|Statement|array|NULL
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('an')->select('count(an)');

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get All entity Query
     *
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllQuery($cache = true)
    {
        $qb = $this->createQueryBuilder('an')->orderBy('an.dtStart', 'DESC');

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
     * Get All entity
     *
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAll($cache = true)
    {
        return $this->getAllQuery($cache)->execute();
    }

    /**
     * Count All Old Pending
     *
     * @return mixed|Statement|array|NULL
     */
    public function countOldPending()
    {
        $qb = $this->createQueryBuilder('an')
            ->select('count(an)')
            ->where('an.dtStart <= :dt')
            ->andWhere('an.status = :status')
            ->setParameter('dt', new DateTime('now'))
            ->setParameter('status', AdminNotif::PENDING);

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get All Old Pending entity Query
     *
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllOldPendingQuery($cache = true)
    {
        $qb = $this->createQueryBuilder('an')
            ->where('an.dtStart <= :dt')
            ->andWhere('an.status = :status')
            ->setParameter('dt', new DateTime('now'))
            ->setParameter('status', AdminNotif::PENDING)
            ->orderBy('an.dtStart', 'DESC');

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
     * Get All Old Pending entity
     *
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllOldPending($cache = true)
    {
        return $this->getAllOldPendingQuery($cache)->execute();
    }
}
