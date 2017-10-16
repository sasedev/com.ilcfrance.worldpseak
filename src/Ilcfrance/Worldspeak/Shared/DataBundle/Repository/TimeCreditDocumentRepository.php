<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;

/**
 * TimeCreditDocument EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditDocumentRepository extends EntityRepository
{

    /**
     * Get Query for All Entities By TimeCredit Order By dtCrea
     *
     * @param TimeCredit $timeCredit
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllByTimeCreditQuery(TimeCredit $timeCredit, $cache = true)
    {
        $qb = $this->createQueryBuilder('tcd')
            ->where('tcd.timeCredit = :timeCredit')
            ->setParameter('timeCredit', $timeCredit)
            ->orderBy('tcd.dtCrea', 'DESC');
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
     * Get All Entities TimeCredit Order By dtCrea
     *
     * @param TimeCredit $timeCredit
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllByTimeCredit(TimeCredit $timeCredit, $cache = true)
    {
        return $this->getAllByTimeCreditQuery($timeCredit, $cache)->execute();
    }
}
