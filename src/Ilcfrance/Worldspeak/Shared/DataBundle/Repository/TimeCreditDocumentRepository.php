<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Doctrine\ORM\EntityRepository;

/**
 * TimeCreditDocument EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditDocumentRepository extends EntityRepository
{

	/**
	 * Get Query for All Entities By TimeCredit Order By dtCrea
	 *
	 * @param TimeCredit $timeCredit
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllByTimeCreditQuery(TimeCredit $timeCredit, $cache = true)
	{
		$qb = $this->createQueryBuilder('tcd')->where('tcd.timeCredit = :timeCredit')->setParameter('timeCredit', $timeCredit)->orderBy('tcd.dtCrea', 'DESC');
		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(20)->useResultCache(true, 20);
		}

		return $query;
	}

	/**
	 * Get All Entities TimeCredit Order By dtCrea
	 *
	 * @param TimeCredit $timeCredit
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllByTimeCredit(TimeCredit $timeCredit, $cache = true)
	{
		return $this->getAllByTimeCreditQuery($timeCredit, $cache)->execute();
	}
}
