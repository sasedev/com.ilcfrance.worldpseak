<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Repository;

use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Cours;

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
     * @param boolean $cache
     *
     * @return Query
     */
    public function getAllByCoursQuery(Cours $cours, $cache = true)
    {
        $qb = $this->createQueryBuilder('cd')
            ->where('cd.cours = :cours')
            ->setParameter('cours', $cours)
            ->orderBy('cd.dtCrea', 'DESC');

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
     * Get All Entities By Cours
     *
     * @param Cours $cours
     * @param boolean $cache
     *
     * @return mixed|Statement|array|NULL
     */
    public function getAllByCours($cours, $cache = true)
    {
        return $this->getAllByCoursQuery($cours, $cache)->execute();
    }
}
