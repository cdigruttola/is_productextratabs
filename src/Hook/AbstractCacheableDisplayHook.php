<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Hook;

use Oksydan\IsProductExtraTabs\Cache\TemplateCache;
use Oksydan\IsProductExtraTabs\Repository\ProductExtraTabProductRepository;
use Oksydan\IsProductExtraTabs\Repository\ProductExtraTabRepository;

abstract class AbstractCacheableDisplayHook extends AbstractDisplayHook
{
    /**
     * @var ProductExtraTabRepository
     */
    protected $productExtraTabRepository;
    /**
     * @var ProductExtraTabProductRepository
     */
    protected $productExtraTabProductRepository;

    /**
     * @var TemplateCache
     */
    protected $templateCache;

    public function __construct(
        \Module $module,
        \Context $context,
        ProductExtraTabRepository $productExtraTabRepository,
        ProductExtraTabProductRepository $productExtraTabProductRepository,
        TemplateCache $templateCache
    ) {
        parent::__construct($module, $context);

        $this->productExtraTabRepository = $productExtraTabRepository;
        $this->productExtraTabProductRepository = $productExtraTabProductRepository;
        $this->templateCache = $templateCache;
    }

    public function execute(array $params)
    {
        if (!$this->shouldBlockBeDisplayed($params)) {
            return '';
        }

        $this->templateCache->clearTemplateCacheIfNeeded($this->context->shop->id);

        if (!$this->isTemplateCached()) {
            $this->assignTemplateVariables($params);
        }

        return $this->module->fetch($this->getTemplateFullPath(), $this->getCacheKey());
    }

    protected function getCacheKey(): string
    {
        return $this->module->getCacheId();
    }

    protected function isTemplateCached(): bool
    {
        return $this->module->isCached($this->getTemplateFullPath(), $this->getCacheKey());
    }
}
