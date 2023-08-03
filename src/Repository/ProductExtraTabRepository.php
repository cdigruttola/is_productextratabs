<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ProductExtraTabRepository extends EntityRepository
{
    public function getAllIds(): array
    {
        /** @var QueryBuilder $qb */
        $qb = $this
            ->createQueryBuilder('s')
            ->select('s.id')
        ;

        $extraTabs = $qb->getQuery()->getScalarResult();

        return array_map(function ($extraTab) {
            return $extraTab['id'];
        }, $extraTabs);
    }

    public function getHighestPosition(): int
    {
        $position = 0;
        $qb = $this
            ->createQueryBuilder('s')
            ->select('s.position')
            ->orderBy('s.position', 'ASC')
            ->setMaxResults(1)
            ->getQuery();

        $result = $qb->getOneOrNullResult();

        if ($result) {
            $position = $result['position'];
        }

        return $position;
    }

    public function getActiveProductExtraTabByStoreId(
        int $idStore,
        bool $activeOnly = true,
        int $limit = 0
    ): array {
        $qb = $this
            ->createQueryBuilder('s')
            ->select('s.id, s.name, s.position, s.active')
            ->join('s.shops', 'ss')
            ->andWhere('ss.id = :storeId')
            ->orderBy('s.position')
            ->setParameter('storeId', (int) $idStore);

        if ($activeOnly) {
            $qb->andWhere('s.active = 1');
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getScalarResult();
    }

    public function getActiveProductExtraByLangAndStoreId(
        int $idLang,
        int $idStore,
        bool $activeOnly = true,
        int $limit = 0
    ): array {
        $qb = $this
            ->createQueryBuilder('s')
            ->select('sl.title, sl.content, s.id, s.name, s.active, s.position')
            ->join('s.productExtraTabDefaultLangs', 'sl')
            ->join('s.shops', 'ss')
            ->andWhere('sl.lang = :langId')
            ->andWhere('ss.id = :storeId')
            ->andWhere('sl.content != \'\'')
            ->orderBy('s.position')
            ->setParameter('langId', (int) $idLang)
            ->setParameter('storeId', (int) $idStore);

        if ($activeOnly) {
            $qb->andWhere('s.active = 1');
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getScalarResult();
    }
}
