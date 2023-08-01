<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Oksydan\IsProductExtraTabs\Entity\ProductExtraTab;

class ProductExtraTabProductRepository extends EntityRepository
{

    /**
     * @var ProductExtraTabRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->repository = $em->getRepository(ProductExtraTab::class);
    }

    public function getActiveProductExtraTabProductByStoreId(
        int  $idStore,
        int  $idProduct,
        bool $activeOnly = true,
        int  $limit = 0
    ): array
    {
        $defaults = $this->repository->getActiveProductExtraTabByStoreId($idStore);

        \PrestaShopLogger::addLog(var_export($defaults,true));

        $qb = $this
            ->createQueryBuilder('s')
            ->select('s.id_product_extra_tab, s.id_product, pet.id, pet.name, pet.position, s.active')
            ->join('s.productExtraTab', 'pet')
            ->join('pet.shops', 'ss')
            ->andWhere('ss.id = :storeId')
            ->andWhere('s.id_product = :productId')
            ->orderBy('pet.position')
            ->setParameter('storeId', $idStore)
            ->setParameter('productId', $idProduct);

        if ($activeOnly) {
            $qb->andWhere('s.active = 1');
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $result =  $qb->getQuery()->getScalarResult();

        foreach ($defaults as $default) {

        }

        return $result;
    }

    public function getActiveProductExtraTabProductByLangAndStoreId(
        int  $idLang,
        int  $idStore,
        int  $idProduct,
        bool $activeOnly = true,
        int  $limit = 0
    ): array
    {
         \PrestaShopLogger::addLog(var_export($this->repository->getActiveProductExtraByLangAndStoreId($idLang, $idStore),true));

        $qb = $this
            ->createQueryBuilder('s')
            ->select('sl.title, sl.content, s.id_product_extra_tab, s.id_product, pet.id, pet.name, s.active')
            ->join('s.productExtraTabProductLangs', 'sl')
            ->join('s.id_product_extra_tab', 'pet')
            ->join('pet.shops', 'ss')
            ->andWhere('sl.lang = :langId')
            ->andWhere('ss.id = :storeId')
            ->andWhere('s.id_product = :productId')
            ->orderBy('pet.position')
            ->setParameter('langId', $idLang)
            ->setParameter('storeId', $idStore)
            ->setParameter('productId', $idProduct);

        if ($activeOnly) {
            $qb->andWhere('s.active = 1');
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getScalarResult();
    }
}
