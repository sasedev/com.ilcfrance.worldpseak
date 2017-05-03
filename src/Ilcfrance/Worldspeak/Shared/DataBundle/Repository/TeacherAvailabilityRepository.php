<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Teacher;

/**
 * TeacherAvailability EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeacherAvailabilityRepository extends EntityRepository
{

	/**
	 * Get Query for All Entity For Teacher between dtStart and dtEnd
	 *
	 * @param Teacher $teacher
	 * @param \DateTime $dtStart
	 * @param \DateTime $dtEnd
	 *
	 * @return Ambigous <\Doctrine\ORM\AbstractQuery, boolean,
	 *         \Doctrine\ORM\AbstractQuery,
	 *         \Doctrine\ORM\Query>
	 */
	public function getAllArroundForTeacherQuery(Teacher $teacher, \DateTime $dtStart, \DateTime $dtEnd, $cache = true)
	{
		$dql = 'SELECT ta FROM Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TeacherAvailability ta
                        WHERE ta.teacher = :teacher AND ((ta.dtStart < :dtStart AND ta.dtEnd > :dtStart)
                        OR (ta.dtStart < :dtEnd AND ta.dtEnd > :dtEnd)) ORDER BY ta.dtStart ASC';

		$query = $this->getEntityManager()->createQuery($dql)->setParameter('teacher', $teacher)->setParameter('dtStart', $dtStart)->setParameter('dtEnd', $dtEnd);
		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(60)->useResultCache(true, 60);
		}

		return $query;
	}

	/**
	 * Get All Entity For Teacher between dtStart and dtEnd
	 *
	 * @param Teacher $teacher
	 * @param \DateTime $dtStart
	 * @param \DateTime $dtEnd
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllArroundForTeacher(Teacher $teacher, \DateTime $dtStart, \DateTime $dtEnd, $cache = true)
	{
		return $this->getAllArroundForTeacherQuery($teacher, $dtStart, $dtEnd, $cache)->execute();
	}

	/**
	 * Get Query for All Entity For Teacher By week and year
	 *
	 * @param integer $year
	 * @param integer $week
	 * @param Teacher $teacher
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllByYearWeekTeacherQuery($year, $week, Teacher $teacher, $cache = true)
	{
		$dtStart = new \DateTime();
		$dtStart->setISODate($year, $week);
		$dtStart->setTime(0, 0, 0);

		$nextweek = $week + 1;
		$nextyear = $year;

		$date = new \DateTime();
		$date->setISODate($year, 53);
		$weeksinyear = $date->format("W") === "53" ? 53 : 52;

		if ($nextweek > $weeksinyear) {
			$nextyear++;
			$nextweek = 1;
		}

		$dtEnd = new \DateTime();
		$dtEnd->setISODate($nextyear, $nextweek);
		$dtEnd->setTime(0, 0, 0);

		$dql = 'SELECT ta FROM Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TeacherAvailability ta
                        WHERE ta.teacher = :teacher AND
                        ((ta.dtStart >= :dtStart AND ta.dtStart < :dtEnd)
                        OR (ta.dtEnd > :dtStart AND ta.dtEnd <= :dtEnd))
                        ORDER BY ta.dtStart ASC';

		$query = $this->getEntityManager()->createQuery($dql)->setParameter('teacher', $teacher)->setParameter('dtStart', $dtStart)->setParameter('dtEnd', $dtEnd);

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(60)->useResultCache(true, 60);
		}

		return $query;
	}

	/**
	 * Get All Entity For Teacher By week and year
	 *
	 * @param integer $year
	 * @param integer $week
	 * @param Teacher $teacher
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllByYearWeekTeacher($year, $week, Teacher $teacher, $cache = true)
	{
		return $this->getAllByYearWeekTeacherQuery($year, $week, $teacher, $cache)->execute();
	}

	/**
	 * Get Query for All Entity By week and year
	 *
	 * @param integer $week
	 * @param integer $year
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllByYearWeekQuery($year, $week, $cache = true)
	{
		$dtStart = new \DateTime();
		$dtStart->setISODate($year, $week);
		$dtStart->setTime(0, 0, 0);

		$nextWeek = $week + 1;
		$nextYear = $year;

		$date = new \DateTime();
		$date->setISODate($year, 53);
		$weeksinyear = $date->format("W") === "53" ? 53 : 52;

		if ($nextWeek > $weeksinyear) {
			$nextYear++;
			$nextWeek = 1;
		}

		$dtEnd = new \DateTime();
		$dtEnd->setISODate($nextYear, $nextWeek);
		$dtEnd->setTime(0, 0, 0);

		$dql = 'SELECT ta FROM Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TeacherAvailability ta
                        WHERE (ta.dtStart >= :dtStart AND ta.dtStart < :dtEnd)
                        OR (ta.dtEnd > :dtStart AND ta.dtEnd <= :dtEnd) ORDER BY ta.dtStart ASC';

		$query = $this->getEntityManager()->createQuery($dql)->setParameter('dtStart', $dtStart)->setParameter('dtEnd', $dtEnd);

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(60)->useResultCache(true, 60);
		}

		return $query;
	}

	/**
	 * Get All Entity By week and year
	 *
	 * @param integer $week
	 * @param integer $year
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllByYearWeek($year, $week, $cache = true)
	{
		return $this->getAllByYearWeekQuery($year, $week, $cache)->execute();
	}

	/**
	 * Get Query for All Entity By Date Start (dtEnd = +1 hour)
	 *
	 * @param \DateTime $dtStart
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllByDateQuery($dtStart, $cache = true)
	{
		$dtEnd = new \DateTime('now');
		$dtEnd->setTimestamp($dtStart->getTimestamp());
		$dtEnd->modify('+1 hour');

		$qb = $this->createQueryBuilder('ta')->join('ta.teacher', 't')->where('ta.dtStart <= :dtStart')->andWhere('ta.dtEnd >= :dtEnd')->setParameter('dtStart', $dtStart)->setParameter('dtEnd', $dtEnd)->orderBy('t.type', 'ASC')->addOrderBy('ta.dtStart', 'ASC');

		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(60)->useResultCache(true, 60);
		}

		return $query;
	}

	/**
	 * Get All Entity By Date Start (dtEnd = +1 hour)
	 *
	 * @param \DateTime $dtStart
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllByDate($dtStart, $cache = true)
	{
		return $this->getAllByDateQuery($dtStart, $cache)->execute();
	}
}
