<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Cours;
use Doctrine\ORM\EntityRepository;

/**
 * CoursDocument EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CoursDocumentRepository extends EntityRepository
{

	/**
	 * Get Query for All Entities By Cours
	 *
	 * @param Cours $cours
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllByCoursQuery(Cours $cours, $cache = true)
	{
		$qb = $this->createQueryBuilder('cd')->where('cd.cours = :cours')->setParameter('cours', $cours)->orderBy('cd.dtCrea', 'DESC');

		$query = $qb->getQuery();
		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(20)->useResultCache(true, 20);
		}

		return $query;
	}

	/**
	 * Get All Entities By Cours
	 *
	 * @param Cours $cours
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllByCours($cours, $cache = true)
	{
		return $this->getAllByCoursQuery($cours, $cache)->execute();
	}
}
