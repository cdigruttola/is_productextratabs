<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Hook;

use Oksydan\IsProductExtraTabs\Form\ProductExtraTabProductType;
use Oksydan\IsProductExtraTabs\Translations\TranslationDomains;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;
use Tools;
use Twig\Environment;

class DisplayAdminProductsExtra extends AbstractDisplayHook
{
    private const TEMPLATE_FILE = 'extra-tabs.html.twig';

    protected function getTemplate(): string
    {
        return self::TEMPLATE_FILE;
    }

    public function execute(array $params)
    {
        if (!$this->shouldBlockBeDisplayed($params)) {
            return false;
        }

        $toReturn = '';

        $productId = $params['id_product'] ?? Tools::getValue('id_product');

        /** @var FormDataProviderInterface $formDataProvider */
        $formDataProvider = $this->module->get('oksydan.is_product_extra_tab.form.identifiable_object.data_provider.product_extra_tab_product_form_data_provider');

        $data = $formDataProvider->getData($productId);

        foreach ($data as $tab) {
            $form = $this->module->get('form.factory')->create(ProductExtraTabProductType::class, $tab);

            /** @var $twig Environment */
            $twig = $this->module->get('twig');

            $toReturn .= $twig->render($this->getTemplateFullPath(), [
                'form' => $form->createView(),
                'translationDomain' => TranslationDomains::TRANSLATION_DOMAIN_ADMIN,]);
        }
        return $toReturn;
    }

}
