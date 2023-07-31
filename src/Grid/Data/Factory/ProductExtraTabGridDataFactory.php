<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Grid\Data\Factory;

use PrestaShop\PrestaShop\Core\Grid\Data\Factory\DoctrineGridDataFactory;
use PrestaShop\PrestaShop\Core\Grid\Data\Factory\GridDataFactoryInterface;
use PrestaShop\PrestaShop\Core\Grid\Data\GridData;
use PrestaShop\PrestaShop\Core\Grid\Record\RecordCollection;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

final class ProductExtraTabGridDataFactory implements GridDataFactoryInterface
{
    /**
     * @var DoctrineGridDataFactory
     */
    private $doctrineExtraTabDataFactory;

    /**
     * @param DoctrineGridDataFactory $doctrineExtraTabDataFactory
     */
    public function __construct(
        DoctrineGridDataFactory $doctrineExtraTabDataFactory
    ) {
        $this->doctrineExtraTabDataFactory = $doctrineExtraTabDataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(SearchCriteriaInterface $searchCriteria)
    {
        $languageData = $this->doctrineExtraTabDataFactory->getData($searchCriteria);

        return new GridData(
            new RecordCollection($languageData->getRecords()->all()),
            $languageData->getRecordsTotal(),
            $languageData->getQuery()
        );
    }
}
