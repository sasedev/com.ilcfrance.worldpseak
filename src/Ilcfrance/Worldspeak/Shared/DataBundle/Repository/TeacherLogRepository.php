<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Teacher;
use Doctrine\ORM\EntityRepository;

/**
 * TeacherLog EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeacherLogRepository extends EntityRepository
{

	/**
	 * Count By Teacher
	 *
	 * @param Teacher $teacher
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function countByTeacher(Teacher $teacher)
	{
		$qb = $this->createQueryBuilder('log')->select('count(log)')->where('log.teacher = :teacher')->setParameter('teacher', $teacher);

		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}

	/**
	 * Get All entity Query By Teacher
	 *
	 * @param Teacher $teacher
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllByTeacherQuery(Teacher $teacher, $cache = true)
	{
		$qb = $this->createQueryBuilder('log')->where('log.teacher = :teacher')->setParameter('teacher', $teacher)->orderBy('log.dtCrea', 'DESC');

		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(20)->useResultCache(true, 20);
		}

		return $query;
	}

	/**
	 * Get All entity By Teacher
	 *
	 * @param Teacher $teacher
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, multitype:, mixed, \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllByTeacher(Teacher $teacher, $cache = true)
	{
		return $this->getAllByTeacherQuery($teacher, $cache)->execute();
	}
}
