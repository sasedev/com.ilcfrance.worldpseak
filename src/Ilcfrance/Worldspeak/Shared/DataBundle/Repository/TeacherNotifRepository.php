<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use DateTime;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Teacher;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TeacherNotif;

/**
 * TeacherNotif EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeacherNotifRepository extends EntityRepository
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
     * Count By Teacher
     *
     * @param Teacher $teacher
     *
     * @return mixed|Statement|array|NULL
     */
    public function countByTeacher(Teacher $teacher)
    {
        $qb = $this->createQueryBuilder('tn')
            ->select('count(tn)')
            ->where('tn.teacher = :teacher')
            ->setParameter('teacher', $teacher);

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get All entity Query By Teacher
     *
     * @param Teacher $teacher
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllByTeacherQuery(Teacher $teacher, $cache = true)
    {
        $qb = $this->createQueryBuilder('tn')
            ->where('tn.teacher = :teacher')
            ->setParameter('teacher', $teacher)
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
     * Get All entity By Teacher
     *
     * @param Teacher $teacher
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllByTeacher(Teacher $teacher, $cache = true)
    {
        return $this->getAllByTeacherQuery($teacher, $cache)->execute();
    }

    /**
     * Count Old Pending Txt By Teacher
     *
     * @param Teacher $teacher
     *
     * @return mixed|Statement|array|NULL
     */
    public function countOldPendingTxtByTeacher(Teacher $teacher)
    {
        $qb = $this->createQueryBuilder('tn')
            ->select('count(tn)')
            ->where('tn.teacher = :teacher')
            ->andWhere('tn.dtStart <= :dt')
            ->andWhere('tn.status = :status')
            ->andWhere('tn.type != :typeA')
            ->andWhere('tn.type != :typeB')
            ->setParameter('teacher', $teacher)
            ->setParameter('dt', new DateTime('now'))
            ->setParameter('status', TeacherNotif::PENDING)
            ->setParameter('typeA', TeacherNotif::TYPE_EMAIL_COURS_EDIT)
            ->setParameter('typeB', TeacherNotif::TYPE_EMAIL_TIMECREDIT_EDIT);

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get All Old Pending Txt entity Query By Teacher
     *
     * @param Teacher $teacher
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllOldPendingTxtByTeacherQuery(Teacher $teacher, $cache = true)
    {
        $qb = $this->createQueryBuilder('tn')
            ->where('tn.teacher = :teacher')
            ->andWhere('tn.dtStart <= :dt')
            ->andWhere('tn.status = :status')
            ->andWhere('tn.type != :typeA')
            ->andWhere('tn.type != :typeB')
            ->setParameter('teacher', $teacher)
            ->setParameter('dt', new DateTime('now'))
            ->setParameter('status', TeacherNotif::PENDING)
            ->setParameter('typeA', TeacherNotif::TYPE_EMAIL_COURS_EDIT)
            ->setParameter('typeB', TeacherNotif::TYPE_EMAIL_TIMECREDIT_EDIT)
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
     * Get All Old Pending Txt entity By Teacher
     *
     * @param Teacher $teacher
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllOldPendingTxtByTeacher(Teacher $teacher, $cache = true)
    {
        return $this->getAllOldPendingTxtByTeacherQuery($teacher, $cache)->execute();
    }

    /**
     * Count Old Pending Email
     *
     * @param Teacher $teacher
     *
     * @return mixed|Statement|array|NULL
     */
    public function countOldPendingEmail(Teacher $teacher)
    {
        $qb = $this->createQueryBuilder('tn')
            ->select('count(tn)')
            ->where('tn.dtStart <= :dt')
            ->andWhere('tn.status = :status')
            ->andWhere('tn.type != :typeA')
            ->andWhere('tn.type != :typeB')
            ->setParameter('dt', new DateTime('now'))
            ->setParameter('status', TeacherNotif::PENDING)
            ->setParameter('typeA', TeacherNotif::TYPE_TXT_COURS_EDIT)
            ->setParameter('typeB', TeacherNotif::TYPE_TXT_TIMECREDIT_EDIT);

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
            ->setParameter('status', TeacherNotif::PENDING)
            ->setParameter('typeA', TeacherNotif::TYPE_TXT_COURS_EDIT)
            ->setParameter('typeB', TeacherNotif::TYPE_TXT_TIMECREDIT_EDIT)
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
