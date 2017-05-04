<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Admin;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * Admin EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class AdminRepository extends EntityRepository implements UserProviderInterface, UserLoaderInterface
{

	/**
	 * Used for Authentification Security
	 * (non-PHPdoc) @see
	 * \Symfony\Component\Security\Core\User\UserProviderInterface::loadUserByUsername()
	 *
	 * @param string $username
	 *
	 * @throws UsernameNotFoundException
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function loadUserByUsername($username, $cache = false)
	{
		$qb = $this->createQueryBuilder('u')->where('u.username = :username')->andWhere('u.lockout = :lockout')->setParameter('username', $username)->setParameter('lockout', Admin::LOCKOUT_UNLOCKED);

		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(20)->useResultCache(true, 20);
		}

		try {
			$user = $query->getSingleResult();
		} catch (NoResultException $e) {
			$exp = new UsernameNotFoundException(sprintf('Unable to find an active User identified by "%s".', $username), 0, $e);
			$exp->setUsername($username);
			throw $exp;
		}

		return $user;
	}

	/**
	 * Used for Authentification Security
	 * (non-PHPdoc) @see
	 * \Symfony\Component\Security\Core\User\UserProviderInterface::refreshUser()
	 *
	 * @param UserInterface $user
	 *
	 * @return Admin
	 */
	public function refreshUser(UserInterface $user)
	{
		$class = get_class($user);
		if (!$this->supportsClass($class)) {
			throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
		}

		return $this->loadUserByUsername($user->getUsername());
	}

	/**
	 * Check if is a sublass of the Entity
	 * (non-PHPdoc) @see
	 * \Symfony\Component\Security\Core\User\UserProviderInterface::supportsClass()
	 *
	 * @param string $class
	 *
	 * @return boolean
	 */
	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function count()
	{
		$qb = $this->createQueryBuilder('a')->select('count(a)');
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
		$qb = $this->createQueryBuilder('u')->orderBy('u.username', 'ASC');

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
	 * Get Query for All Entities that are Active 1 minute ago
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllActiveNowQuery($cache = false)
	{
		$delay = new \DateTime();
		$delay->setTimestamp(strtotime('1 minutes ago'));

		$qb = $this->createQueryBuilder('u')->where('u.lastActivity > :delay')->orderBy('u.lastActivity', 'ASC')->setParameter('delay', $delay);

		$query = $qb->getQuery();
		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(20)->useResultCache(true, 20);
		}

		return $query;
	}

	/**
	 * Get All Entities that are Active 1 minute ago
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllActiveNow($cache = false)
	{
		return $this->getAllActiveNowQuery($cache)->execute();
	}

	/**
	 * Get Query for All Entities where lockout is unlocked
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllUnlockedQuery($cache = true)
	{
		$qb = $this->createQueryBuilder('u')->where('u.lockout = :lockout')->orderBy('u.username', 'ASC')->setParameter('lockout', Admin::LOCKOUT_UNLOCKED);

		$query = $qb->getQuery();
		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(20)->useResultCache(true, 20);
		}

		return $query;
	}

	/**
	 * Get All Entities where lockout is unlocked
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllUnlocked($cache = true)
	{
		return $this->getAllUnlockedQuery($cache)->execute();
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
		$qb = $this->createQueryBuilder('a')->select('count(a)')->distinct()->where('LOWER(a.username) LIKE :key')->orWhere('LOWER(a.email) LIKE :key')->orWhere('LOWER(a.firstName) LIKE :key')->orWhere('LOWER(a.lastName) LIKE :key')->orWhere('LOWER(a.address) LIKE :key')->orWhere('LOWER(a.phone) LIKE :key')->orWhere('LOWER(a.mobile) LIKE :key')->setParameter('key', '%' . strtolower($q) . '%');

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
		$qb = $this->createQueryBuilder('a')->distinct()->where('LOWER(a.username) LIKE :key')->orWhere('LOWER(a.email) LIKE :key')->orWhere('LOWER(a.firstName) LIKE :key')->orWhere('LOWER(a.lastName) LIKE :key')->orWhere('LOWER(a.address) LIKE :key')->orWhere('LOWER(a.phone) LIKE :key')->orWhere('LOWER(a.mobile) LIKE :key')->orderBy('a.username', 'ASC')->setParameter('key', '%' . strtolower($q) . '%');

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

	/**
	 * Buggy count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function countBuggy()
	{
		$qb = $this->createQueryBuilder('a')->select('count(a)')->distinct()->where('TRIM(BOTH FROM a.email) LIKE :empty')->orWhere('a.email IS NULL')->setParameter('empty', '');

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
		$qb = $this->createQueryBuilder('a')->distinct()->where('TRIM(BOTH FROM a.email) LIKE :empty')->orWhere('a.email IS NULL')->setParameter('empty', '');

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
}
