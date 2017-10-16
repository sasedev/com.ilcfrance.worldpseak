<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use DateTime;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * ClosedDay EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class ClosedDayRepository extends EntityRepository
{

    /**
     * Get Query for All Entities
     *
     * @return Query
     */
    public function getAllQuery($cache = true)
    {
        $qb = $this->createQueryBuilder('cd')->orderBy('cd.day', 'ASC');

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
     * Get All Entities
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
     * Get Query for All Entities between dtStart and dtEnd (included)
     *
     * @param DateTime $dtStart
     * @param DateTime $dtEnd
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllBetweenQuery($dtStart, $dtEnd, $cache = true)
    {
        $qb = $this->createQueryBuilder('cd')
            ->where('cd.day >= :dtStart')
            ->andWhere('cd.day <= :dtEnd')
            ->setParameter('dtStart', $dtStart)
            ->setParameter('dtEnd', $dtEnd)
            ->orderBy('cd.day', 'ASC');

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
     * Get All Entities between dtStart and dtEnd (included)
     *
     * @param DateTime $dtStart
     * @param DateTime $dtEnd
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllBetween($dtStart, $dtEnd, $cache = true)
    {
        $query = $this->getAllBetweenQuery($dtStart, $dtEnd, $cache);

        return $query->execute();
    }
}
