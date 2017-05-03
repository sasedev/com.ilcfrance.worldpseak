<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;

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
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllQuery($cache = true)
	{
		$qb = $this->createQueryBuilder('l')->orderBy('l.prefix', 'ASC');

		$query = $qb->getQuery();
		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(60)->useResultCache(true, 60);
		}

		return $query;
	}

	/**
	 * Get All Entities Order By prefix
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
	 * Get Query for All Entities Order By name
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllOrderedByNameQuery($cache = true)
	{
		$qb = $this->createQueryBuilder('l')->orderBy('l.name', 'ASC');

		$query = $qb->getQuery();
		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(60)->useResultCache(true, 60);
		}

		return $query;
	}

	/**
	 * Get All Entities Order By name
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllOrderedByName($cache = true)
	{
		return $this->getAllOrderedByNameQuery($cache)->execute();
	}
}
