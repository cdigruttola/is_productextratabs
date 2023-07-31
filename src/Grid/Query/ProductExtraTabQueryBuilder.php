<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Grid\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Adapter\Shop\Context;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

final class ProductExtraTabQueryBuilder extends AbstractDoctrineQueryBuilder
{
    /**
     * @var Context
     */
    private $shopContext;

    private $contextLangId;

    /**
     * ImageSliderQueryBuilder constructor.
     *
     * @param Connection $connection
     * @param $dbPrefix
     * @param Context $shopContext
     */
    public function __construct(Connection $connection, $dbPrefix, Context $shopContext, $contextLangId)
    {
        parent::__construct($connection, $dbPrefix);

        $this->shopContext = $shopContext;
        $this->contextLangId = $contextLangId;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return QueryBuilder
     */
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->getBaseQuery($searchCriteria->getFilters());
        $qb->select('s.*, sl.*')
            ->join('s', $this->dbPrefix . 'product_extra_tab_default_lang', 'sl', 'sl.id_product_extra_tab = s.id_product_extra_tab')
            ->where('sl.id_lang = :langId')
            ->setParameter('langId', (int) $this->contextLangId);

        if (!$this->shopContext->isAllShopContext()) {
            $qb->join('s', $this->dbPrefix . 'product_extra_tab_shop', 'ss', 'ss.id_product_extra_tab = s.id_product_extra_tab')
                ->where('ss.id_shop in (' . implode(', ', $this->shopContext->getContextListShopID()) . ')')
                ->groupBy('s.id_product_extra_tab');
        }

        $qb->orderBy(
            $searchCriteria->getOrderBy(),
            $searchCriteria->getOrderWay()
        )
            ->setFirstResult($searchCriteria->getOffset())
            ->setMaxResults($searchCriteria->getLimit());

        $qb->orderBy('position');

        return $qb;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return QueryBuilder
     */
    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->getBaseQuery();
        $qb->select('COUNT(DISTINCT s.id_product_extra_tab)');
        if (!$this->shopContext->isAllShopContext()) {
            $qb->join('s', $this->dbPrefix . 'product_extra_tab_shop', 'ss', 'ss.id_product_extra_tab = s.id_product_extra_tab')
                ->where('ss.id_shop in (' . implode(', ', $this->shopContext->getContextListShopID()) . ')');
        }

        return $qb;
    }

    /**
     * @return QueryBuilder
     */
    private function getBaseQuery(): QueryBuilder
    {
        return $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix . 'product_extra_tab', 's');
    }
}
