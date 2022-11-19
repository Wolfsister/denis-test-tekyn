<?php

namespace App\Repository;

use App\Entity\FavoriteProduct;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FavoriteProduct>
 *
 * @method FavoriteProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteProduct[]    findAll()
 * @method FavoriteProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteProduct::class);
    }

    public function save(FavoriteProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FavoriteProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findExistingFavoriteProductOfUser(User $user, string $code): ?FavoriteProduct
    {
        return $this->createQueryBuilder('favoriteProduct')
            ->join('favoriteProduct.User', 'user')
            ->andWhere('user = :user')
            ->setParameter('user', $user)
            ->andWhere('favoriteProduct.code = :code')
            ->setParameter('code', $code)
            ->getQuery()->getOneOrNullResult();
    }
}
