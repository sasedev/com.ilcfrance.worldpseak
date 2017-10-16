<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Query\Query;

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
     * @return Query
     */
    public function getAllQuery()
    {
        return $this->createQueryBuilder()
            ->sort('dtCrea', 'ASC')
            ->getQuery();
    }

    /**
     * Get All Documents
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->getAllQuery()->execute();
    }
}
