<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Trainee;

/**
 * TraineeLog EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TraineeLogRepository extends EntityRepository
{

    /**
     * Count By Trainee
     *
     * @param Trainee $trainee
     *
     * @return mixed|Statement|array|NULL
     */
    public function countByTrainee(Trainee $trainee)
    {
        $qb = $this->createQueryBuilder('log')
            ->select('count(log)')
            ->where('log.trainee = :trainee')
            ->setParameter('trainee', $trainee);

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get All entity Query By Trainee
     *
     * @param Trainee $trainee
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllByTraineeQuery(Trainee $trainee, $cache = true)
    {
        $qb = $this->createQueryBuilder('log')
            ->where('log.trainee = :trainee')
            ->setParameter('trainee', $trainee)
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
     * Get All entity By Trainee
     *
     * @param Trainee $trainee
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllByTrainee(Trainee $trainee, $cache = true)
    {
        return $this->getAllByTraineeQuery($trainee, $cache)->execute();
    }
}
