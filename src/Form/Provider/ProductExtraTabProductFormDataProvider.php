<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Form\Provider;

use Oksydan\IsProductExtraTabs\Entity\ProductExtraTab;
use Oksydan\IsProductExtraTabs\Repository\ProductExtraTabProductRepository;
use Oksydan\IsProductExtraTabs\Repository\ProductExtraTabRepository;
use PrestaShop\PrestaShop\Adapter\Shop\Context;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class ProductExtraTabProductFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var ProductExtraTabRepository
     */
    private $productExtraTabRepository;

    /**
     * @var ProductExtraTabProductRepository
     */
    private $productExtraTabProductRepository;

    /**
     * @var Context
     */
    private $shopContext;

    /**
     * @param ProductExtraTabRepository $productExtraTabRepository
     * @param ProductExtraTabProductRepository $productExtraTabProductRepository
     * @param Context $shopContext
     */
    public function __construct(
        ProductExtraTabRepository $productExtraTabRepository,
        ProductExtraTabProductRepository $productExtraTabProductRepository,
        Context $shopContext
    ) {
        $this->productExtraTabRepository = $productExtraTabRepository;
        $this->productExtraTabProductRepository = $productExtraTabProductRepository;
        $this->shopContext = $shopContext;
    }

    /**
     * @param mixed $id
     *
     * @return array
     */
    public function getData($id): array
    {
        /** @var ProductExtraTab[] $extraTabs */
        $extraTabs = $this->productExtraTabRepository->findBy(['active' => 1]);
        $toReturn = [];

        foreach ($extraTabs as $extraTab) {
            $data = [];
            $extraTabProduct = $extraTab->getProductExtraTabProductByProductId((int) $id);
            $idExtraTab = $extraTab->getId();
            $data['id_product_extra_tab'] = $extraTab->getId();
            $data['id_product'] = $id;
            $data["name_$idExtraTab"] = $extraTab->getName();
            if ($extraTabProduct == null) {
                $data["active_$idExtraTab"] = $extraTab->getActive();

                foreach ($extraTab->getProductExtraTabDefaultLangs() as $extraTabDefaultLang) {
                    $data["title_$idExtraTab"][$extraTabDefaultLang->getLang()->getId()] = $extraTabDefaultLang->getTitle();
                    $data["content_$idExtraTab"][$extraTabDefaultLang->getLang()->getId()] = $extraTabDefaultLang->getContent();
                }
            } else {
                $data["active_$idExtraTab"] = $extraTabProduct->getActive();

                foreach ($extraTabProduct->getProductExtraTabProductLangs() as $extraTabProductLang) {
                    $data["title_$idExtraTab"][$extraTabProductLang->getLang()->getId()] = $extraTabProductLang->getTitle();
                    $data["content_$idExtraTab"][$extraTabProductLang->getLang()->getId()] = $extraTabProductLang->getContent();
                }
            }
            $toReturn[$extraTab->getId()] = $data;
        }

        return $toReturn;
    }

    /**
     * @return array
     */
    public function getDefaultData(): array
    {
        return [
            'title' => [],
            'content' => [],
            'active' => false,
        ];
    }
}
