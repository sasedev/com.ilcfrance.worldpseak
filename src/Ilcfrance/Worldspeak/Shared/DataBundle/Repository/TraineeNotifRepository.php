<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use DateTime;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Trainee;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TraineeNotif;

/**
 * TraineeNotif EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TraineeNotifRepository extends EntityRepository
{

    /**
     * Count All
     *
     * @return mixed|Statement|array|NULL
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('tn')->select('count(tn)');

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
        $qb = $this->createQueryBuilder('tn')->orderBy('tn.dtStart', 'DESC');

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
     * Count By Trainee
     *
     * @param Trainee $trainee
     *
     * @return mixed|Statement|array|NULL
     */
    public function countByTrainee(Trainee $trainee)
    {
        $qb = $this->createQueryBuilder('tn')
            ->select('count(tn)')
            ->where('tn.trainee = :trainee')
            ->setParameter('trainee', $trainee)
            ->setParameter('dt', new DateTime('now'));

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
        $qb = $this->createQueryBuilder('tn')
            ->where('tn.trainee = :trainee')
            ->setParameter('trainee', $trainee)
            ->orderBy('tn.dtStart', 'DESC');

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

    /**
     * Count Old Pending Txt By Trainee
     *
     * @param Trainee $trainee
     *
     * @return mixed|Statement|array|NULL
     */
    public function countOldPendingTxtByTrainee(Trainee $trainee)
    {
        $qb = $this->createQueryBuilder('tn')
            ->select('count(tn)')
            ->where('tn.trainee = :trainee')
            ->andWhere('tn.dtStart <= :dt')
            ->andWhere('tn.status = :status')
            ->andWhere('tn.type != :typeA')
            ->andWhere('tn.type != :typeB')
            ->andWhere('tn.type != :typeC')
            ->andWhere('tn.type != :typeD')
            ->andWhere('tn.type != :typeE')
            ->setParameter('trainee', $trainee)
            ->setParameter('dt', new DateTime('now'))
            ->setParameter('status', TraineeNotif::PENDING)
            ->setParameter('typeA', TraineeNotif::TYPE_EMAIL_15D_AFTER_COURS)
            ->setParameter('typeB', TraineeNotif::TYPE_EMAIL_24H_BEFORE_COURS)
            ->setParameter('typeC', TraineeNotif::TYPE_EMAIL_30D_AFTER_COURS)
            ->setParameter('typeD', TraineeNotif::TYPE_EMAIL_SURVEYBEGIN)
            ->setParameter('typeE', TraineeNotif::TYPE_EMAIL_SURVEYEND);

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get All Old Pending Txt entity Query By Trainee
     *
     * @param Trainee $trainee
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllOldPendingTxtByTraineeQuery(Trainee $trainee, $cache = true)
    {
        $qb = $this->createQueryBuilder('tn')
            ->where('tn.trainee = :trainee')
            ->andWhere('tn.dtStart <= :dt')
            ->andWhere('tn.status = :status')
            ->andWhere('tn.type != :typeA')
            ->andWhere('tn.type != :typeB')
            ->andWhere('tn.type != :typeC')
            ->andWhere('tn.type != :typeD')
            ->andWhere('tn.type != :typeE')
            ->setParameter('trainee', $trainee)
            ->setParameter('dt', new DateTime('now'))
            ->setParameter('status', TraineeNotif::PENDING)
            ->setParameter('typeA', TraineeNotif::TYPE_EMAIL_15D_AFTER_COURS)
            ->setParameter('typeB', TraineeNotif::TYPE_EMAIL_24H_BEFORE_COURS)
            ->setParameter('typeC', TraineeNotif::TYPE_EMAIL_30D_AFTER_COURS)
            ->setParameter('typeD', TraineeNotif::TYPE_EMAIL_SURVEYBEGIN)
            ->setParameter('typeE', TraineeNotif::TYPE_EMAIL_SURVEYEND)
            ->orderBy('tn.dtStart', 'DESC');

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
     * Get All Old Pending Txt entity By Trainee
     *
     * @param Trainee $trainee
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllOldPendingTxtByTrainee(Trainee $trainee, $cache = true)
    {
        return $this->getAllOldPendingTxtByTraineeQuery($trainee, $cache)->execute();
    }

    /**
     * Count Old Pending Email
     *
     * @return mixed|Statement|array|NULL
     */
    public function countOldPendingEmail()
    {
        $qb = $this->createQueryBuilder('tn')
            ->select('count(tn)')
            ->where('tn.dtStart <= :dt')
            ->andWhere('tn.status = :status')
            ->andWhere('tn.type != :typeA')
            ->andWhere('tn.type != :typeB')
            ->setParameter('dt', new DateTime('now'))
            ->setParameter('status', TraineeNotif::PENDING)
            ->setParameter('typeA', TraineeNotif::TYPE_TXT_SURVEYBEGIN)
            ->setParameter('typeB', TraineeNotif::TYPE_TXT_SURVEYEND);

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get All Old Pending Email entity Query
     *
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllOldPendingEmailQuery($cache = true)
    {
        $qb = $this->createQueryBuilder('tn')
            ->where('tn.dtStart <= :dt')
            ->andWhere('tn.status = :status')
            ->andWhere('tn.type != :typeA')
            ->andWhere('tn.type != :typeB')
            ->setParameter('dt', new DateTime('now'))
            ->setParameter('status', TraineeNotif::PENDING)
            ->setParameter('typeA', TraineeNotif::TYPE_TXT_SURVEYBEGIN)
            ->setParameter('typeB', TraineeNotif::TYPE_TXT_SURVEYEND)
            ->orderBy('tn.dtStart', 'ASC');

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
     * Get All Old Pending Email entity
     *
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllOldPendingEmail($cache = true)
    {
        return $this->getAllOldPendingEmailQuery($cache)->execute();
    }
}
