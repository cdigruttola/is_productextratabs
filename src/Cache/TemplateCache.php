<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Cache;

use Oksydan\IsProductExtraTabs\Hook\AbstractCacheableDisplayHook;
use Oksydan\IsProductExtraTabs\Repository\HookModuleRepository;
use Oksydan\IsProductExtraTabs\Repository\ProductExtraTabRepository;
use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Core\Domain\Shop\ValueObject\ShopConstraint;

class TemplateCache
{
    protected $module;
    protected $context;

    /**
     * @var HookModuleRepository
     */
    protected $hookModuleRepository;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var ProductExtraTabRepository
     */
    protected $repository;

    public const IS_PRODUCT_EXTRA_TAB_CACHE_KEY = 'IS_PRODUCT_EXTRA_TAB_CACHE_KEY';

    private const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    public function __construct(
        \Module $module,
        \Context $context,
        HookModuleRepository $hookModuleRepository,
        Configuration $configuration,
        ProductExtraTabRepository $repository
    ) {
        $this->module = $module;
        $this->context = $context;
        $this->hookModuleRepository = $hookModuleRepository;
        $this->configuration = $configuration;
        $this->repository = $repository;
    }

    public function clearTemplateCache()
    {
        $hookedHooks = $this->hookModuleRepository->getAllHookRegisteredToModule($this->module->id);
        $uniqueHooks = [];

        foreach ($hookedHooks as $hook) {
            if (!in_array($hook['name'], $uniqueHooks)) {
                $uniqueHooks[] = $hook['name'];
            }
        }

        foreach ($uniqueHooks as $hook) {
            $this->clearCacheForHook($hook);
        }

        $this->setCacheValidityDateForSlider();
    }

    private function clearCacheForHook($hookName)
    {
        $displayHook = $this->getServiceFromHookName($hookName);

        if ($displayHook) {
            $this->module->_clearCache($displayHook->getTemplateFullPath());
        }
    }

    private function getServiceFromHookName($hookName)
    {
        $serviceName = sprintf(
            'oksydan.is_product_extra_tab.hook.%s',
            \Tools::toUnderscoreCase(str_replace('hook', '', $hookName))
        );

        $hook = $this->module->getService($serviceName);

        return $hook instanceof AbstractCacheableDisplayHook ? $hook : null;
    }

    private function setCacheValidityDate(\DateTime $date, ShopConstraint $shopConstraint): void
    {
        $this->configuration->set(self::IS_PRODUCT_EXTRA_TAB_CACHE_KEY, $date->format(self::DATE_TIME_FORMAT), $shopConstraint);
    }

    private function getCacheValidityDate(ShopConstraint $shopConstraint): string
    {
        return $this->configuration->get(self::IS_PRODUCT_EXTRA_TAB_CACHE_KEY, '', $shopConstraint);
    }

    private function resetCacheValidityDate(ShopConstraint $shopConstraint): void
    {
        $this->configuration->set(self::IS_PRODUCT_EXTRA_TAB_CACHE_KEY, '', $shopConstraint);
    }

    public function clearTemplateCacheIfNeeded(int $idShop): void
    {
        $now = new \DateTime();
        $shopConstraint = ShopConstraint::shop($idShop);
        $date = $this->getCacheValidityDate($shopConstraint);
        $dateCacheKey = $date ? \DateTime::createFromFormat(self::DATE_TIME_FORMAT, $date) : null;

        if ($dateCacheKey && $now > $dateCacheKey) {
            $this->clearTemplateCache();
        }
    }

    public function setCacheValidityDateForSlider(): void
    {
        $stores = \Shop::getShops();

        foreach ($stores as $store) {
            $shopConstraint = ShopConstraint::shop((int) $store['id_shop']);

            $slides = $this->repository->getActiveProductExtraTabByStoreId(
                $shopConstraint->getShopId()->getValue()
            );

            $this->setCacheValidityDateFromSliders($slides, $shopConstraint);
        }
    }

    private function setCacheValidityDateFromSliders(array $slides, ShopConstraint $shopConstraint): void
    {
        $closestDate = null;
        $now = new \DateTime();

        foreach ($slides as $slide) {
            $dateFrom = $slide['display_from'] ? \DateTime::createFromFormat(self::DATE_TIME_FORMAT, $slide['display_from']) : null;
            $dateTo = $slide['display_to'] ? \DateTime::createFromFormat(self::DATE_TIME_FORMAT, $slide['display_to']) : null;

            if ($dateFrom > $now && (($dateFrom && $closestDate && $closestDate > $dateFrom) || !$closestDate)) {
                $closestDate = $dateFrom;
            }

            if ($dateTo > $now && (($dateTo && $closestDate && $closestDate > $dateTo) || !$closestDate)) {
                $closestDate = $dateTo;
            }
        }

        if ($closestDate) {
            $this->setCacheValidityDate($closestDate, $shopConstraint);
        } else {
            $this->resetCacheValidityDate($shopConstraint);
        }
    }
}
