<?php

namespace App\Repository;

use App\Entity\Car;
use App\Model\SearchData;
use Doctrine\Persistence\ManagerRegistry;
// use Knp\Component\Pager\Pagination\PaginationInterface;
// use Knp\Component\Pager\Pagination\PaginatorInterface;

use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
        // $this->paginator = $paginator;
    }

    public function save(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Get published posts thanks to Search Data value
     *
     * @param SearchData $searchData
    //  * @return PaginationInterface
     */

    public function findBySearch(SearchData $search)
    {
        $data = $this->createQueryBuilder('p')
        ->select('c', 'p')
        ->join('p.category', 'c');


        if(!empty($search->q)) {
            $data = $data->andWhere('p.name LIKE :q')->setParameter('q', "%{$search->q}%");
        }

        if(!empty($search->categories)) {
            $data = $data
                ->andWhere('c.id IN (:category)')
                ->setParameter('category', $search->categories);
        }
        
        $data = $data->getQuery()->getResult();

        // $pagination = $this->paginator->paginate(
        //     $data,
        //     $search->page, 
        //     2
        // );

        // return $pagination;
        return $data;
    }

//    /**
//     * @return Car[] Returns an array of Car objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Car
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
