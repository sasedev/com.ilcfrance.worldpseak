<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Admin;
use Doctrine\ORM\EntityRepository;

/**
 * AdminLog EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class AdminLogRepository extends EntityRepository
{

	/**
	 * Count By Admin
	 *
	 * @param Admin $admin
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function countByAdmin(Admin $admin)
	{
		$qb = $this->createQueryBuilder('log')->select('count(log)')->where('log.admin = :admin')->setParameter('admin', $admin);

		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}

	/**
	 * Get All entity Query By Admin
	 *
	 * @param Admin $admin
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllByAdminQuery(Admin $admin, $cache = true)
	{
		$qb = $this->createQueryBuilder('log')->where('log.admin = :admin')->setParameter('admin', $admin)->orderBy('log.dtCrea', 'DESC');

		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(60)->useResultCache(true, 60);
		}

		return $query;
	}

	/**
	 * Get All entity By Admin
	 *
	 * @param Admin $admin
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, multitype:, mixed, \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllByAdmin(Admin $admin, $cache = true)
	{
		return $this->getAllByAdminQuery($admin, $cache)->execute();
	}
}
