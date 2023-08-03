<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Hook;

use Oksydan\IsProductExtraTabs\Entity\ProductExtraTab;
use PrestaShop\PrestaShop\Core\Product\ProductExtraContent;

class DisplayProductExtraContent extends AbstractCacheableDisplayHook
{
    private const TEMPLATE_FILE = 'extra-tab.tpl';

    protected function getTemplate(): string
    {
        return self::TEMPLATE_FILE;
    }

    protected function assignTemplateVariables(array $params)
    {
        $this->context->smarty->assign([
            'tab_content' => $params['tpl_content'],
        ]);
    }

    public function execute(array $params)
    {
        $tabs = [];

        $productId = $this->getProductData($params);

        foreach ($this->getExtraTab($productId) as $extraTab) {
            $tab = new ProductExtraContent();
            $params['tpl_content'] = $extraTab['content'];
            $tab->setTitle($extraTab['title'])
                ->setContent(parent::execute($params));
            parent::execute($params);
            $tabs[] = $tab;
        }

        return $tabs;
    }

    /**
     * @param int $productId
     *
     * @return array
     */
    private function getExtraTab(int $productId): array
    {
        $toReturn = [];

        /** @var ProductExtraTab[] $extraTabs */
        $extraTabs = $this->productExtraTabRepository->findBy(['active' => 1]);

        foreach ($extraTabs as $extraTab) {
            if ($extraTab->checkShop($this->context->shop->id)) {
                $extraTabProduct = $extraTab->getProductExtraTabProductByProductId($productId);
                if ($extraTabProduct && $extraTabProduct->getActive()) {
                    $data = [];
                    $extraTabProductLang = $extraTabProduct->getProductExtraTabProductLangByLangId($this->context->language->id);
                    $data['title'] = $extraTabProductLang->getTitle();
                    $data['content'] = $extraTabProductLang->getContent();
                    $toReturn[$extraTab->getId()] = $data;
                } elseif ($extraTabProduct == null) {
                    $data = [];
                    $extraTabDefaultLang = $extraTab->getProductExtraTabDefaultLangByLangId($this->context->language->id);
                    $data['title'] = $extraTabDefaultLang->getTitle();
                    $data['content'] = $extraTabDefaultLang->getContent();
                    $toReturn[$extraTab->getId()] = $data;
                }
            }
        }

        return $toReturn;
    }

    protected function getCacheKey(): string
    {
        return parent::getCacheKey() . '_' . ($this->context->isMobile() ? 'mobile' : 'desktop');
    }
}
