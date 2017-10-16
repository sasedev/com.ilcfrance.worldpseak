<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * Locale EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class LocaleRepository extends EntityRepository
{

    /**
     * Get Query for All Entities Order By prefix
     *
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllQuery($cache = true)
    {
        $qb = $this->createQueryBuilder('l')->orderBy('l.prefix', 'ASC');

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
     * Get All Entities Order By prefix
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
     * Get Query for All Entities Order By name
     *
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllOrderedByNameQuery($cache = true)
    {
        $qb = $this->createQueryBuilder('l')->orderBy('l.name', 'ASC');

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
     * Get All Entities Order By name
     *
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllOrderedByName($cache = true)
    {
        return $this->getAllOrderedByNameQuery($cache)->execute();
    }
}
