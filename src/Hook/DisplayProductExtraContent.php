<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Hook;

class DisplayProductExtraContent extends AbstractCacheableDisplayHook
{
    private const TEMPLATE_FILE = 'extra-content.tpl';

    protected function getTemplate(): string
    {
        return self::TEMPLATE_FILE;
    }

    protected function assignTemplateVariables(array $params)
    {
        $this->context->smarty->assign([

        ]);
    }

    /**
     * @return array
     */
    private function getExtraTab(): array
    {
        $now = new \DateTime();
        $slides = $this->slideRepository->getActiveProductExtraByLangAndStoreId(
            $this->context->language->id,
            $this->context->shop->id,
            true,
            0, // 0 means no limit
            $now
        );

        foreach ($slides as &$slide) {
            $slide = $this->slidePresenter->present($slide);
        }

        return $slides;
    }

    protected function getCacheKey(): string
    {
        return parent::getCacheKey() . '_' . ($this->context->isMobile() ? 'mobile' : 'desktop');
    }
}
