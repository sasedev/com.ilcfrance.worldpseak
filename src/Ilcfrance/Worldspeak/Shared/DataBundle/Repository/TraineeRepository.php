<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use DateTime;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Company;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Trainee;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Trainee EntityRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TraineeRepository extends EntityRepository implements UserProviderInterface, UserLoaderInterface
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
            ->setParameter('lockout', Trainee::LOCKOUT_UNLOCKED);

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
     * Buggy count
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
        $qb = $this->createQueryBuilder('t')
            ->leftJoin('t.company', 'c')
            ->orderBy('t.lastName', 'ASC')
            ->addOrderBy('t.firstName', 'ASC')
            ->addOrderBy('t.project', 'ASC')
            ->addOrderBy('c.name', 'ASC');

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

        $qb = $this->createQueryBuilder('t')
            ->where('t.lastActivity > :delay')
            ->setParameter('delay', $delay)
            ->orderBy('t.lastActivity', 'ASC');

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
        $qb = $this->createQueryBuilder('t')
            ->leftJoin('t.company', 'c')
            ->where('t.lockout = :lockout')
            ->setParameter('lockout', Trainee::LOCKOUT_UNLOCKED)
            ->orderBy('t.lastName', 'ASC')
            ->addOrderBy('t.firstName', 'ASC')
            ->addOrderBy('t.project', 'ASC')
            ->addOrderBy('c.name', 'ASC');

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
     * Get Query for All Entities by Company
     *
     * @param Company $company
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllByCompanyQuery($company, $cache = true)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.company = :company')
            ->setParameter('company', $company)
            ->orderBy('t.lastName', 'ASC')
            ->addOrderBy('t.firstName', 'ASC')
            ->addOrderBy('t.project', 'ASC');

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
     * Get Query for All Entities by Company
     *
     * @param Company $company
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllByCompany($company, $cache = true)
    {
        return $this->getAllByCompanyQuery($company, $cache)->execute();
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
            ->leftJoin('t.company', 'c')
            ->where('LOWER(t.codeMA) LIKE :key')
            ->orWhere('LOWER(t.project) LIKE :key')
            ->orWhere('LOWER(t.username) LIKE :key')
            ->orWhere('LOWER(t.email) LIKE :key')
            ->orWhere('LOWER(t.firstName) LIKE :key')
            ->orWhere('LOWER(t.lastName) LIKE :key')
            ->orWhere('LOWER(t.address) LIKE :key')
            ->orWhere('LOWER(t.phone) LIKE :key')
            ->orWhere('LOWER(t.mobile) LIKE :key')
            ->orWhere('LOWER(c.name) LIKE :key')
            ->orWhere('LOWER(c.prefix) LIKE :key')
            ->orWhere('LOWER(c.service) LIKE :key')
            ->orWhere('LOWER(c.address) LIKE :key')
            ->orWhere('LOWER(c.postalCode) LIKE :key')
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
            ->leftJoin('t.company', 'c')
            ->where('LOWER(t.codeMA) LIKE :key')
            ->orWhere('LOWER(t.project) LIKE :key')
            ->orWhere('LOWER(t.username) LIKE :key')
            ->orWhere('LOWER(t.email) LIKE :key')
            ->orWhere('LOWER(t.firstName) LIKE :key')
            ->orWhere('LOWER(t.lastName) LIKE :key')
            ->orWhere('LOWER(t.address) LIKE :key')
            ->orWhere('LOWER(t.phone) LIKE :key')
            ->orWhere('LOWER(t.mobile) LIKE :key')
            ->orWhere('LOWER(c.name) LIKE :key')
            ->orWhere('LOWER(c.prefix) LIKE :key')
            ->orWhere('LOWER(c.service) LIKE :key')
            ->orWhere('LOWER(c.address) LIKE :key')
            ->orWhere('LOWER(c.postalCode) LIKE :key')
            ->setParameter('key', '%' . strtolower($q) . '%')
            ->orderBy('t.lastName', 'ASC')
            ->addOrderBy('t.firstName', 'ASC')
            ->addOrderBy('t.project', 'ASC')
            ->addOrderBy('c.name', 'ASC');

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
            ->setParameter('buggy', Trainee::HEALTH_BUGGY);

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
            ->leftJoin('t.company', 'c')
            ->where('t.buggy = :buggy')
            ->setParameter('buggy', Trainee::HEALTH_BUGGY)
            ->orderBy('t.lastName', 'ASC')
            ->addOrderBy('t.firstName', 'ASC')
            ->addOrderBy('t.project', 'ASC')
            ->addOrderBy('c.name', 'ASC');

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
