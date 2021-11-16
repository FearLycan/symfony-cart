<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    const PER_PAGE = 3;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getByPage($page): array
    {
        return $this->findBy([], [], self::PER_PAGE, $page < 1 ? 0 : ($page - 1) * self::PER_PAGE);

//        return $this->createQueryBuilder('p')
//            ->setFirstResult($page < 1 ? 0 : ($page - 1) * self::PER_PAGE)
//            ->setMaxResults(self::PER_PAGE)
//            ->getQuery()
//            ->getResult(Query::HYDRATE_ARRAY);
    }
}
