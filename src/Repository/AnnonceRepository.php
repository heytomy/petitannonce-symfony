<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Annonce>
 *
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function save(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Annonce[]
    */
    public function findAllNotSold(): array
    { 
        return $this->createQueryBuilder('a')
            ->andWhere('a.sold = false')
            ->getQuery() // permet de créer un objet utilisable pour récupérer le résultat
            ->getResult() // permet de récupérer le résultat
        ;
    }

   /**
    * @return Annonce[]
    */
    public function findLatestNotSold(): array{
        return $this->findNotSoldQuery()
            ->setMaxResults(3)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Query
    //  */
    // public function findAllNotSoldQuery()
    // {
    //     return $this->findNotSoldQuery()
    //         ->getQuery();
    // }

    public function findAllNotSoldPaginate($page = 0, $perPage = 10)
    {
        return $this->findNotSoldQuery()
            ->setFirstResult(($page-1) * $perPage)
            ->setMaxResults($perPage)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return mixed The scalar result, or NULL if the query returned no result.
     */
    public function findTotalNotSold()
    {
        return $this->findNotSoldQuery()
            ->select('COUNT(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

     /**
     * @return QueryBuilder
     */
    private function findNotSoldQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.sold = false')
        ;
    }
    
    public function findAllNotSoldQuery()
    {
        return $this->findNotSoldQuery()
            ->getQuery()
            ;
    }

}
