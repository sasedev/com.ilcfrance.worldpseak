<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Role EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class RoleRepository extends EntityRepository
{

	/**
	 * Get Query for All Entities
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllQuery($cache = true)
	{
		$qb = $this->createQueryBuilder('r')->orderBy('r.name', 'ASC');

		$query = $qb->getQuery();
		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(60)->useResultCache(true, 60);
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
}
