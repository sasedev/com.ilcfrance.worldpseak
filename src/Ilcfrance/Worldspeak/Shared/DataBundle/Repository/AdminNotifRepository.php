<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\AdminNotif;
use Doctrine\ORM\EntityRepository;

/**
 * AdminNotif EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class AdminNotifRepository extends EntityRepository
{

	/**
	 * Count All
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function count()
	{
		$qb = $this->createQueryBuilder('an')->select('count(an)');

		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}

	/**
	 * Get All entity Query
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllQuery($cache = true)
	{
		$qb = $this->createQueryBuilder('an')->orderBy('an.dtStart', 'DESC');

		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(20)->useResultCache(true, 20);
		}

		return $query;
	}

	/**
	 * Get All entity
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, multitype:, mixed, \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAll($cache = true)
	{
		return $this->getAllQuery($cache)->execute();
	}

	/**
	 * Count All Old Pending
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function countOldPending()
	{
		$qb = $this->createQueryBuilder('an')->select('count(an)')->where('an.dtStart <= :dt')->andWhere('an.status = :status')->setParameter('dt', new \DateTime('now'))->setParameter('status', AdminNotif::PENDING);

		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}

	/**
	 * Get All Old Pending entity Query
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllOldPendingQuery($cache = true)
	{
		$qb = $this->createQueryBuilder('an')->where('an.dtStart <= :dt')->andWhere('an.status = :status')->setParameter('dt', new \DateTime('now'))->setParameter('status', AdminNotif::PENDING)->orderBy('an.dtStart', 'DESC');

		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(20)->useResultCache(true, 20);
		}

		return $query;
	}

	/**
	 * Get All Old Pending entity
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, multitype:, mixed, \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllOldPending($cache = true)
	{
		return $this->getAllOldPendingQuery($cache)->execute();
	}
}
