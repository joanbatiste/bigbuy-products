<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function addProducts(array $products): void
    {
        foreach($products as $product){
            $this->add($product);
        }
    }

    public function persist(Product $productExist): void
    {
        $this->getEntityManager()->persist($productExist);
        $this->getEntityManager()->flush();
    }

    public function findOneBySku(string $sku): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.sku = :val')
            ->setParameter('val', $sku)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByProductIdentifier(string $id): ?Product
    {
        return $this->createQueryBuilder('product')
            ->andWhere('product.productIdentifier = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
