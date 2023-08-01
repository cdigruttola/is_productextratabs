<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Hook;

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

        foreach ($this->getExtraTab() as $extraTab) {
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
     * @return array
     */
    private function getExtraTab(): array
    {
        return $this->repository->getActiveProductExtraByLangAndStoreId(
            $this->context->language->id,
            $this->context->shop->id
        );
    }

    protected function getCacheKey(): string
    {
        return parent::getCacheKey() . '_' . ($this->context->isMobile() ? 'mobile' : 'desktop');
    }
}
