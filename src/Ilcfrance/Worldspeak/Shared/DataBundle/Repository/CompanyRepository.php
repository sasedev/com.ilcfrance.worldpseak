<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Company EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CompanyRepository extends EntityRepository
{

	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
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
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllQuery($cache = true)
	{
		$qb = $this->createQueryBuilder('c')->orderBy('c.name', 'ASC');

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
	 * Search count
	 *
	 * @param string $q
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function countSearch($q)
	{
		$qb = $this->createQueryBuilder('c')->select('count(c)')->distinct()->where('LOWER(c.name) LIKE :key')->orWhere('LOWER(c.prefix) LIKE :key')->orWhere('LOWER(c.codeMA) LIKE :key')->orWhere('LOWER(c.service) LIKE :key')->orWhere('LOWER(c.address) LIKE :key')->orWhere('LOWER(c.postalCode) LIKE :key')->orWhere('LOWER(c.town) LIKE :key')->setParameter('key', '%' . strtolower($q) . '%');

		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}

	/**
	 * Get Query for Entities found by search query
	 *
	 * @param string $q
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function searchQuery($q, $cache = true)
	{
		$qb = $this->createQueryBuilder('c')->distinct()->where('LOWER(c.name) LIKE :key')->orWhere('LOWER(c.prefix) LIKE :key')->orWhere('LOWER(c.codeMA) LIKE :key')->orWhere('LOWER(c.service) LIKE :key')->orWhere('LOWER(c.address) LIKE :key')->orWhere('LOWER(c.postalCode) LIKE :key')->orWhere('LOWER(c.town) LIKE :key')->orderBy('c.name', 'ASC')->setParameter('key', '%' . strtolower($q) . '%');

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
