<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Doctrine\MongoDB\Cursor;
use Doctrine\MongoDB\CursorInterface;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Query\Query;
use MongoRegex;

/**
 * TeachingResource DocumentRepository
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeachingResourceRepository extends DocumentRepository
{

    /**
     * All count
     *
     * @return number
     */
    public function count()
    {
        return $this->getAllQuery()
            ->execute()
            ->count();
    }

    /**
     * Get Query for All Documents
     *
     * @return Query
     */
    public function getAllQuery()
    {
        return $this->createQueryBuilder()
            ->sort('filename', 'ASC')
            ->getQuery();
    }

    /**
     * Get All Documents
     *
     * @return mixed|CursorInterface|Cursor|array|boolean|object
     */
    public function getAll()
    {
        return $this->getAllQuery()->execute();
    }

    /**
     * Get Query for All Documents By level
     *
     * @param integer $level
     *
     * @return Query
     */
    public function getAllByLevelQuery($level)
    {
        return $this->createQueryBuilder()
            ->field('level')
            ->equals($level)
            ->sort('type', 'ASC')
            ->sort('dtCrea', 'DESC')
            ->getQuery();
    }

    /**
     * Get All Documents By level
     *
     * @param integer $level
     *
     * @return mixed|CursorInterface|Cursor|array|boolean|object
     */
    public function getAllByLevel($level)
    {
        return $this->getAllByLevelQuery($level)->execute();
    }

    /**
     * Get Query for All Documents By type
     *
     * @param integer $type
     *
     * @return Query
     */
    public function getAllByTypeQuery($type)
    {
        return $this->createQueryBuilder()
            ->field('type')
            ->equals($type)
            ->sort('level', 'ASC')
            ->sort('dtCrea', 'DESC')
            ->getQuery();
    }

    /**
     * Get All Documents By type
     *
     * @param integer $type
     *
     * @return mixed|CursorInterface|Cursor|array|boolean|object
     */
    public function getAllByType($type)
    {
        return $this->getAllByTypeQuery($type)->execute();
    }

    /**
     * Get Query for All Documents By level and type
     *
     * @param integer $level
     * @param integer $type
     *
     * @return Query
     */
    public function getAllByLevelAndTypeQuery($level, $type)
    {
        return $this->createQueryBuilder()
            ->field('level')
            ->equals($level)
            ->field('type')
            ->equals($type)
            ->sort('dtCrea', 'DESC')
            ->getQuery();
    }

    /**
     * Get All Documents By level and type
     *
     * @param integer $level
     * @param integer $type
     *
     * @return mixed|CursorInterface|Cursor|array|boolean|object
     */
    public function getAllByLevelAndType($level, $type)
    {
        return $this->getAllByLevelAndTypeQuery($level, $type)->execute();
    }

    /**
     * Search count
     *
     * @param string $q
     *
     * @return number
     */
    public function countSearch($q)
    {
        return $this->searchQuery($q)
            ->execute()
            ->count();
    }

    /**
     * Search Query
     *
     * @param string $q
     *
     * @return Query
     */
    public function searchQuery($q)
    {
        $search = "/" . strtolower($q) . "/i";

        return $this->createQueryBuilder()
            ->field('filename')
            ->equals(new MongoRegex($search))
            ->sort('filename', 'ASC')
            ->getQuery();
    }

    /**
     * Search
     *
     * @param string $q
     *
     * @return mixed|CursorInterface|Cursor|array|boolean|object
     */
    public function search($q)
    {
        return $this->searchQuery($q)->execute();
    }
}
