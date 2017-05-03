<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * TeacherAvatar DocumentRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeacherAvatarRepository extends DocumentRepository
{

	/**
	 * Get Query for All Documents
	 *
	 * @return Ambigous <\Doctrine\MongoDB\Query\Query, \Doctrine\ODM\MongoDB\Query\Query>
	 */
	public function getAllQuery()
	{
		$qb = $this->createQueryBuilder()->sort('dtCrea', 'ASC');

		$query = $qb->getQuery();

		return $query;
	}

	/**
	 * Get All Documents
	 *
	 * @return Ambigous <\Doctrine\MongoDB\Cursor, Cursor, \Doctrine\MongoDB\EagerCursor, boolean,
	 *         multitype:,
	 *         \Doctrine\MongoDB\ArrayIterator, NULL, unknown, number, object>
	 */
	public function getAll()
	{
		return $this->getAllQuery()->execute();
	}
}
