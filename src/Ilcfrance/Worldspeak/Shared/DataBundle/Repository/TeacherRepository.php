<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use DateTime;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Teacher;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Teacher EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeacherRepository extends EntityRepository implements UserProviderInterface, UserLoaderInterface
{

    /**
     * Used for Authentification Security
     *
     * {@inheritdoc}
     * @see UserProviderInterface::loadUserByUsername()
     */
    public function loadUserByUsername($username, $cache = false)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->andWhere('u.lockout = :lockout')
            ->setParameter('username', $username)
            ->setParameter('lockout', Teacher::LOCKOUT_UNLOCKED);

        $query = $qb->getQuery();

        if ($cache) {
            $query->setCacheable('true')
                ->useQueryCache(true)
                ->setLifetime(20)
                ->useResultCache(true, 20);
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
     *
     * {@inheritdoc}
     * @see UserProviderInterface::refreshUser()
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
     *
     * {@inheritdoc}
     * @see UserProviderInterface::supportsClass()
     */
    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

    /**
     * All count
     *
     * @return mixed|Statement|array|NULL
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('t')->select('count(t)');
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities
     *
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllQuery($cache = true)
    {
        $qb = $this->createQueryBuilder('u')->orderBy('u.username', 'ASC');

        $query = $qb->getQuery();
        if ($cache) {
            $query->setCacheable('true')
                ->useQueryCache(true)
                ->setLifetime(20)
                ->useResultCache(true, 20);
        }

        return $query;
    }

    /**
     * Get All Entities
     *
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAll($cache = true)
    {
        return $this->getAllQuery($cache)->execute();
    }

    /**
     * Get Query for All Entities that are Active 1 minute ago
     *
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllActiveNowQuery($cache = true)
    {
        $delay = new DateTime();
        $delay->setTimestamp(strtotime('1 minutes ago'));

        $qb = $this->createQueryBuilder('u')
            ->where('u.lastActivity > :delay')
            ->orderBy('u.lastActivity', 'ASC')
            ->setParameter('delay', $delay);

        $query = $qb->getQuery();
        if ($cache) {
            $query->setCacheable('true')
                ->useQueryCache(true)
                ->setLifetime(20)
                ->useResultCache(true, 20);
        }

        return $query;
    }

    /**
     * Get All Entities that are Active 1 minute ago
     *
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllActiveNow($cache = true)
    {
        return $this->getAllActiveNowQuery($cache)->execute();
    }

    /**
     * Get Query for All Entities where lockout is unlocked
     *
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllUnlockedQuery($cache = true)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.lockout = :lockout')
            ->orderBy('u.username', 'ASC')
            ->setParameter('lockout', Teacher::LOCKOUT_UNLOCKED);

        $query = $qb->getQuery();
        if ($cache) {
            $query->setCacheable('true')
                ->useQueryCache(true)
                ->setLifetime(20)
                ->useResultCache(true, 20);
        }

        return $query;
    }

    /**
     * Get All Entities where lockout is unlocked
     *
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
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
     * @return mixed|Statement|array|NULL
     */
    public function countSearch($q)
    {
        $qb = $this->createQueryBuilder('t')
            ->select('count(t)')
            ->distinct()
            ->where('LOWER(t.username) LIKE :key')
            ->orWhere('LOWER(t.email) LIKE :key')
            ->orWhere('LOWER(t.firstName) LIKE :key')
            ->orWhere('LOWER(t.lastName) LIKE :key')
            ->orWhere('LOWER(t.address) LIKE :key')
            ->orWhere('LOWER(t.phone) LIKE :key')
            ->orWhere('LOWER(t.mobile) LIKE :key')
            ->orWhere('LOWER(t.coursPhone) LIKE :key')
            ->setParameter('key', '%' . strtolower($q) . '%');

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for Entities found by search query
     *
     * @param string $q
     * @param boolean $cache
     *
     * @return Query
     */
    public function searchQuery($q, $cache = true)
    {
        $qb = $this->createQueryBuilder('t')
            ->distinct()
            ->where('LOWER(t.username) LIKE :key')
            ->orWhere('LOWER(t.email) LIKE :key')
            ->orWhere('LOWER(t.firstName) LIKE :key')
            ->orWhere('LOWER(t.lastName) LIKE :key')
            ->orWhere('LOWER(t.address) LIKE :key')
            ->orWhere('LOWER(t.phone) LIKE :key')
            ->orWhere('LOWER(t.mobile) LIKE :key')
            ->orWhere('LOWER(t.coursPhone) LIKE :key')
            ->orderBy('t.username', 'ASC')
            ->setParameter('key', '%' . strtolower($q) . '%');

        $query = $qb->getQuery();
        if ($cache) {
            $query->setCacheable('true')
                ->useQueryCache(true)
                ->setLifetime(20)
                ->useResultCache(true, 20);
        }

        return $query;
    }

    /**
     * Get All Entities found by search query
     *
     * @param string $q
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function search($q, $cache = true)
    {
        return $this->searchQuery($q, $cache)->execute();
    }

    /**
     * Buggy count
     *
     * @return mixed|Statement|array|NULL
     */
    public function countBuggy()
    {
        $qb = $this->createQueryBuilder('t')
            ->select('count(t)')
            ->where('t.buggy = :buggy')
            ->setParameter('buggy', Teacher::HEALTH_BUGGY);

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for Entities buggy
     *
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllBuggyQuery($cache = true)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.buggy = :buggy')
            ->setParameter('buggy', Teacher::HEALTH_BUGGY)
            ->orderBy('t.username', 'ASC');

        $query = $qb->getQuery();
        if ($cache) {
            $query->setCacheable('true')
                ->useQueryCache(true)
                ->setLifetime(20)
                ->useResultCache(true, 20);
        }

        return $query;
    }

    /**
     * Get All Entities buggy
     *
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllBuggy($cache = true)
    {
        return $this->getAllBuggyQuery($cache)->execute();
    }
}
