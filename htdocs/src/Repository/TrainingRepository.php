<?php

namespace App\Repository;

use App\Entity\Training;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Training>
 *
 * @method Training|null find($id, $lockMode = null, $lockVersion = null)
 * @method Training|null findOneBy(array $criteria, array $orderBy = null)
 * @method Training[]    findAll()
 * @method Training[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Training::class);
    }

    public function save(Training $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Training $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<Training>
     */
    public function findTrainings(
        \DateTime $start,
        \DateTime $end,
        int $clientId = null,
        int $coachId = null,
        bool $isWithTrainer = false
    ): array {
        $query = $this->createQueryBuilder('t')
            ->andWhere(':start <= t.startTime')
            ->andWhere('t.startTime <= :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        if ($coachId) {
            $query->andWhere('t.coach = :coach')
                ->setParameter('coach', $coachId);
        }

        if ($isWithTrainer) {
            $query->andWhere('t.withTrainer = 1');
        }

        if ($clientId) {
            $query->innerJoin('t.clients', 'c', 'WITH', 'c.id = :clientId')
                ->setParameter('clientId', $clientId);
        }

        return $query
                ->orderBy('t.startTime', 'ASC')
                ->getQuery()
                ->getResult();
    }

    //    /**
    //     * @return Training[] Returns an array of Training objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Training
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
