<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Trainee;
use Doctrine\ORM\EntityRepository;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;

/**
 * TimeCredit EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditRepository extends EntityRepository
{

	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function count()
	{
		$qb = $this->createQueryBuilder('tc')->select('count(tc)');
		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}

	/**
	 * Get Query for All Entities
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllQuery($cache = true)
	{
		$qb = $this->createQueryBuilder('tc')->leftJoin('tc.trainee', 't')->leftJoin('t.company', 'c')->orderBy('t.lastName', 'ASC')->addOrderBy('t.firstName', 'ASC')->addOrderBy('t.project', 'ASC')->addOrderBy('c.name', 'ASC');

		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(20)->useResultCache(true, 20);
		}

		return $query;
	}

	/**
	 * Get All Entities
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAll($cache = true)
	{
		return $this->getAllQuery($cache)->execute();
	}

	/**
	 * Get Query for All Entities By Trainee object
	 *
	 * @param Trainee $trainee
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllByTraineeQuery($trainee, $cache = true)
	{
		$qb = $this->createQueryBuilder('tc')->where('tc.trainee = :trainee')->setParameter('trainee', $trainee)->orderBy('tc.dtCrea', 'DESC');

		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(20)->useResultCache(true, 20);
		}

		return $query;
	}

	/**
	 * Get All Entities By Trainee object
	 *
	 * @param Trainee $trainee
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllByTrainee(Trainee $trainee, $cache = true)
	{
		return $this->getAllByTraineeQuery($trainee, $cache)->execute();
	}

	/**
	 * Buggy count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function countBuggy()
	{
		$qb = $this->createQueryBuilder('tc')->select('count(tc)')->where('tc.buggy = :buggy')->setParameter('buggy', TimeCredit::HEALTH_BUGGY);

		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}

	/**
	 * Get Query for Entities buggy
	 *
	 * @return Ambigous <\Doctrine\ORM\AbstractQuery, boolean,
	 *         \Doctrine\ORM\Query>
	 */
	public function getAllBuggyQuery($cache = true)
	{
		$qb = $this->createQueryBuilder('tc')->leftJoin('tc.trainee', 't')->leftJoin('t.company', 'c')->where('tc.buggy = :buggy')->setParameter('buggy', TimeCredit::HEALTH_BUGGY)->orderBy('t.lastName', 'ASC')->addOrderBy('t.firstName', 'ASC')->addOrderBy('t.project', 'ASC')->addOrderBy('c.name', 'ASC');

		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(20)->useResultCache(true, 20);
		}

		return $query;
	}

	/**
	 * Get All Entities buggy
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, multitype:, mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllBuggy($cache = true)
	{
		return $this->getAllBuggyQuery($cache)->execute();
	}

	/**
	 *
	 * @param Trainee $trainee
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getLastByTraineeQuery(Trainee $trainee, $cache = true)
	{
		$qb = $this->createQueryBuilder('tc')->where('tc.trainee = :trainee')->setParameter('trainee', $trainee)->orderBy('tc.dtCrea', 'DESC')->setMaxResults(1);

		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(20)->useResultCache(true, 20);
		}

		return $query;
	}

	/**
	 *
	 * @param Trainee $trainee
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, \Doctrine\ORM\Internal\Hydration\mixed, \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getLastByTrainee(Trainee $trainee, $cache = true)
	{
		return $this->getLastByTraineeQuery($trainee, $cache)->execute();
	}

	/**
	 * Search count
	 *
	 * @param string $q
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:, \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function countSearch($q)
	{
		$qb = $this->createQueryBuilder('tc')->select('count(tc)')->distinct()->leftJoin('tc.trainee', 't')->leftJoin('t.company', 'c')->where('LOWER(t.codeMA) LIKE :key')->orWhere('LOWER(t.project) LIKE :key')->orWhere('LOWER(t.username) LIKE :key')->orWhere('LOWER(t.email) LIKE :key')->orWhere('LOWER(t.firstName) LIKE :key')->orWhere('LOWER(t.lastName) LIKE :key')->orWhere('LOWER(t.address) LIKE :key')->orWhere('LOWER(t.phone) LIKE :key')->orWhere('LOWER(t.mobile) LIKE :key')->orWhere('LOWER(c.name) LIKE :key')->orWhere('LOWER(c.prefix) LIKE :key')->orWhere('LOWER(c.service) LIKE :key')->orWhere('LOWER(c.address) LIKE :key')->orWhere('LOWER(c.postalCode) LIKE :key')->setParameter('key', '%' . strtolower($q) . '%');

		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}

	/**
	 * Get Query for Entities found by search query
	 *
	 * @param string $q
	 *
	 * @return Ambigous <\Doctrine\ORM\AbstractQuery, boolean,
	 *         \Doctrine\ORM\Query>
	 */
	public function searchQuery($q, $cache = true)
	{
		$qb = $this->createQueryBuilder('tc')->leftJoin('tc.trainee', 't')->leftJoin('t.company', 'c')->where('LOWER(t.codeMA) LIKE :key')->orWhere('LOWER(t.project) LIKE :key')->orWhere('LOWER(t.username) LIKE :key')->orWhere('LOWER(t.email) LIKE :key')->orWhere('LOWER(t.firstName) LIKE :key')->orWhere('LOWER(t.lastName) LIKE :key')->orWhere('LOWER(t.address) LIKE :key')->orWhere('LOWER(t.phone) LIKE :key')->orWhere('LOWER(t.mobile) LIKE :key')->orWhere('LOWER(c.name) LIKE :key')->orWhere('LOWER(c.prefix) LIKE :key')->orWhere('LOWER(c.service) LIKE :key')->orWhere('LOWER(c.address) LIKE :key')->orWhere('LOWER(c.postalCode) LIKE :key')->setParameter('key', '%' . strtolower($q) . '%')->orderBy('t.lastName', 'ASC')->addOrderBy('t.firstName', 'ASC')->addOrderBy('t.project', 'ASC')->addOrderBy('c.name', 'ASC');

		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(20)->useResultCache(true, 20);
		}

		return $query;
	}

	/**
	 * Get All Entities found by search query
	 *
	 * @param string $q
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, multitype:, mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function search($q, $cache = true)
	{
		return $this->searchQuery($q, $cache)->execute();
	}
}
