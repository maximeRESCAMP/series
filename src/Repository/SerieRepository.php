<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    const SERIE_LIMIT = 50;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function save(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBestSeries(int $page){


        //page 1 -> 0 - 49
        //page 2 -> 50 - 99

        $offset  = ($page - 1) * self::SERIE_LIMIT;

        //En querybuilder
        $qb = $this->createQueryBuilder('s');
        $qb
            //jointure sur les attribut d'instance
            ->leftJoin("s.seasons", "sea")
            //récupération des dolonnes de la jointure
            ->addSelect("sea")
            ->addOrderBy('s.popularity', 'DESC')
//            ->andWhere('s.vote > 8')
//            ->andWhere('s.popularity > 100')
            ->setFirstResult($offset)
            ->setMaxResults(self::SERIE_LIMIT);

        $query = $qb->getQuery();
        //permet de gérer les offset avec jointure
        $paginator = new Paginator($query );

        return $paginator;

    }





//    public function findBestSeries(int $page)
//    {
//
//        //En DQL
//        //récupération des series avec un vote > 8 et une popularité > 100
//        //ordonné par popularité
//
////        $dql = "SELECT s FROM App\Entity\Serie s
////                WHERE s.vote > 8
////                AND s.popularity > 100
////                ORDER BY s.popularity DESC";
////
////        //transforme le string en objet de requête
////        $query = $this->getEntityManager()->createQuery($dql);
//        //ajoute une limite de résultat
//        //$query->setMaxResults(50);
//
//    }


}
