<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use DateTime;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Cours;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Teacher;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Trainee;

/**
 * Cours EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CoursRepository extends EntityRepository
{

    /**
     * All count
     *
     * @return mixed|Statement|array|NULL
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('c')->select('count(c)');
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities
     *
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllQuery($cache = true)
    {
        $qb = $this->createQueryBuilder('c')->orderBy('c.dtStart', 'DESC');

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
     * Get Query for All Entities By TimeCredit
     *
     * @param TimeCredit $timeCredit
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllByTimeCreditQuery(TimeCredit $timeCredit, $cache = true)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.timeCredit = :timeCredit')
            ->setParameter('timeCredit', $timeCredit)
            ->orderBy('c.dtStart', 'ASC');

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
     * Get All Entities by TimeCredit
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

    /**
     * Get Query for Last Entities By TimeCredit
     *
     * @param TimeCredit $timeCredit
     * @param boolean $cache
     *
     * @return Query
     */
    public function getLastbytimeCreditQuery(TimeCredit $timeCredit, $cache = true)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.timeCredit = : timeCredit')
            ->setParameter('timeCredit', $timeCredit)
            ->orderBy('c.dtStart', 'DESC')
            ->setMaxResults(1);

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
     * Get Last Entities By TimeCredit
     *
     * @param TimeCredit $timeCredit
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getLastbytimeCredit(TimeCredit $timeCredit, $cache = true)
    {
        return $this->getLastbytimeCreditQuery($timeCredit, $cache)->execute();
        ;
    }

    /**
     * Get Query for All Entities By Trainee
     *
     * @param Trainee $trainee
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllByTraineeQuery(Trainee $trainee, $cache = true)
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.timeCredit', 'tc')
            ->where('tc.trainee = :trainee')
            ->orderBy('c.dtStart', 'ASC')
            ->setParameter('trainee', $trainee);

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
     * Get All Entities By Trainee
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
     * Get Query for All Entities By week and year
     *
     * @param integer $year
     * @param integer $week
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllByYearWeekQuery($year, $week, $cache = true)
    {
        $dtStart = new DateTime();
        $dtStart->setISODate($year, $week);
        $dtStart->setTime(0, 0, 0);

        $nextweek = $week + 1;
        $nextyear = $year;

        $date = new DateTime();
        $date->setISODate($year, 53);
        $weeksinyear = $date->format("W") === "53" ? 53 : 52;

        if ($nextweek > $weeksinyear) {
            $nextyear++;
            $nextweek = 1;
        }

        $dtEnd = new DateTime();
        $dtEnd->setISODate($nextyear, $nextweek);
        $dtEnd->setTime(0, 0, 0);
        $dtEnd->modify('-1 hour');

        $qb = $this->createQueryBuilder('c')
            ->where('c.dtStart >= :dtStart')
            ->andWhere('c.dtStart <= :dtEnd')
            ->setParameter('dtStart', $dtStart)
            ->setParameter('dtEnd', $dtEnd)
            ->orderBy('c.dtStart', 'ASC');

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
     * Get All Entities By week and year
     *
     * @param integer $year
     * @param integer $week
     * @param boolean $cache
     *
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllByYearWeek($year, $week, $cache = true)
    {
        return $this->getAllByYearWeekQuery($year, $week, $cache)->execute();
    }

    /**
     * Get Query for All Entities By week and year and Trainee
     *
     * @param integer $year
     * @param integer $week
     * @param Trainee $trainee
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllByYearWeekTraineeQuery($year, $week, Trainee $trainee, $cache = true)
    {
        $dtStart = new DateTime();
        $dtStart->setISODate($year, $week);
        $dtStart->setTime(0, 0, 0);

        $nextWeek = $week + 1;
        $nextYear = $year;

        $date = new DateTime();
        $date->setISODate($year, 53);
        $weeksInYear = $date->format("W") === "53" ? 53 : 52;

        if ($nextWeek > $weeksInYear) {
            $nextYear++;
            $nextWeek = 1;
        }

        $dtEnd = new DateTime();
        $dtEnd->setISODate($nextYear, $nextWeek);
        $dtEnd->setTime(0, 0, 0);
        $dtEnd->modify('-1 hour');

        $qb = $this->createQueryBuilder('c')
            ->join('c.timeCredit', 'tc')
            ->where('tc.trainee = :trainee')
            ->andWhere('c.dtStart >= :dtStart')
            ->andWhere('c.dtStart <= :dtEnd')
            ->setParameter('trainee', $trainee)
            ->setParameter('dtStart', $dtStart)
            ->setParameter('dtEnd', $dtEnd)
            ->orderBy('c.dtStart', 'ASC');

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
     * Get All Entities By week and year and Trainee
     *
     * @param integer $year
     * @param integer $week
     * @param Trainee $trainee
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllByYearWeekTrainee($year, $week, Trainee $trainee, $cache = true)
    {
        return $this->getAllByYearWeekTraineeQuery($year, $week, $trainee, $cache)->execute();
    }

    /**
     * Get Query for All Entities By week and year and Teacher
     *
     * @param integer $year
     * @param integer $week
     * @param Teacher $teacher
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllByYearWeekTeacherQuery($year, $week, Teacher $teacher, $cache = true)
    {
        $dtStart = new DateTime();
        $dtStart->setISODate($year, $week);
        $dtStart->setTime(0, 0, 0);

        $nextWeek = $week + 1;
        $nextYear = $year;

        $date = new DateTime();
        $date->setISODate($year, 53);
        $weeksInYear = $date->format("W") === "53" ? 53 : 52;

        if ($nextWeek > $weeksInYear) {
            $nextYear++;
            $nextWeek = 1;
        }

        $dtEnd = new DateTime();
        $dtEnd->setISODate($nextYear, $nextWeek);
        $dtEnd->setTime(0, 0, 0);
        $dtEnd->modify('-30 minutes');

        $qb = $this->createQueryBuilder('c')
            ->where('c.teacher = :teacher')
            ->andWhere('c.dtStart >= :dtStart')
            ->andWhere('c.dtStart <= :dtEnd')
            ->orderBy('c.dtStart', 'ASC')
            ->setParameter('teacher', $teacher)
            ->setParameter('dtStart', $dtStart)
            ->setParameter('dtEnd', $dtEnd);

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
     * Get All Entities By week and year and Teacher
     *
     * @param integer $year
     * @param integer $week
     * @param Teacher $teacher
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllByYearWeekTeacher($year, $week, Teacher $teacher, $cache = true)
    {
        return $this->getAllByYearWeekTeacherQuery($year, $week, $teacher, $cache)->execute();
    }

    /**
     * Buggy count
     *
     * @return mixed|Statement|array|NULL
     */
    public function countBuggy()
    {
        $qb = $this->createQueryBuilder('c')
            ->select('count(c)')
            ->where('c.buggy = :buggy')
            ->setParameter('buggy', Cours::HEALTH_BUGGY);

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for Entities buggy
     *
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllBuggyQuery($cache = true)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.buggy = :buggy')
            ->setParameter('buggy', Cours::HEALTH_BUGGY)
            ->orderBy('c.dtStart', 'DESC');

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
     * Get All Entities buggy
     *
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllBuggy($cache = true)
    {
        return $this->getAllBuggyQuery($cache)->execute();
    }
}
