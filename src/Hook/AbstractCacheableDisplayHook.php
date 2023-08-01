<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Hook;

use Context;
use Module;
use Oksydan\IsProductExtraTabs\Cache\TemplateCache;
use Oksydan\IsProductExtraTabs\Repository\ProductExtraTabRepository;

abstract class AbstractCacheableDisplayHook extends AbstractDisplayHook
{
    /**
     * @var ProductExtraTabRepository
     */
    protected $repository;

    /**
     * @var TemplateCache
     */
    protected $templateCache;

    public function __construct(
        Module $module,
        Context $context,
        ProductExtraTabRepository $slideRepository,
        TemplateCache $templateCache
    ) {
        parent::__construct($module, $context);

        $this->repository = $slideRepository;
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
