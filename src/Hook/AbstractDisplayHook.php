<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Hook;

use Context;
use Product;
use Tools;

abstract class AbstractDisplayHook extends AbstractHook
{
    public function execute(array $params)
    {
        if (!$this->shouldBlockBeDisplayed($params)) {
            return '';
        }

        $this->assignTemplateVariables($params);

        return $this->module->fetch($this->getTemplateFullPath());
    }

    protected function assignTemplateVariables(array $params)
    {
    }

    protected function shouldBlockBeDisplayed(array $params)
    {
        return true;
    }

    public function getTemplateFullPath(): string
    {
        if($this->module->isSymfonyContext()) {
            return "@Modules/{$this->module->name}/views/templates/admin/product/{$this->getTemplate()}";
        }
        return "module:{$this->module->name}/views/templates/hook/{$this->getTemplate()}";
    }

    abstract protected function getTemplate(): string;

    protected function getProductData($params)
    {
        if ($params['product'] instanceof Product) {
            $productId = $params['product']->id;
        } elseif (isset($params['id_product'])) {
            $productId = (int) $params['id_product'];
        } else {
            $productId = (int) Tools::getValue('id_product');
        }

        return [
            $productId,
            (int) Context::getContext()->shop->id,
        ];
    }
}
