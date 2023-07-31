<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Form\Provider;

use Doctrine\ORM\EntityRepository;
use Oksydan\IsProductExtraTabs\Entity\ProductExtraTab;
use PrestaShop\PrestaShop\Adapter\Shop\Context;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class ProductExtraTabFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var Context
     */
    private $shopContext;

    /**
     * ProductSliderFormDataProvider constructor.
     *
     * @param EntityRepository $repository
     * @param Context $shopContext
     */
    public function __construct(
        EntityRepository $repository,
        Context $shopContext
    ) {
        $this->repository = $repository;
        $this->shopContext = $shopContext;
    }

    /**
     * @param mixed $id
     *
     * @return array
     */
    public function getData($id): array
    {
        /** @var ProductExtraTab $extraTab */
        $extraTab = $this->repository->findOneBy(['id' => (int) $id]);

        $shopIds = [];
        $sliderData = [];

        foreach ($extraTab->getShops() as $shop) {
            $shopIds[] = $shop->getId();
        }

        $sliderData['shop_association'] = $shopIds;
        $sliderData['active'] = $extraTab->getActive();
        $sliderData['name'] = $extraTab->getName();

        foreach ($extraTab->getProductExtraTabDefaultLangs() as $extraTabDefaultLang) {
            $sliderData['title'][$extraTabDefaultLang->getLang()->getId()] = $extraTabDefaultLang->getTitle();
            $sliderData['content'][$extraTabDefaultLang->getLang()->getId()] = $extraTabDefaultLang->getContent();
        }

        return $sliderData;
    }

    /**
     * @return array
     */
    public function getDefaultData(): array
    {
        return [
            'title' => [],
            'content' => [],
            'name' => '',
            'active' => false,
            'shop_association' => $this->shopContext->getContextListShopID(),
        ];
    }
}
